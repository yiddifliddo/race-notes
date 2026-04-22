<?php
/**
 * Apex Notes XML Parser for Le Mans Ultimate Session Data
 * 
 * Parses rFactor2/LMU XML result files to extract:
 * - Session metadata (track, car, date, etc.)
 * - Lap-by-lap fuel consumption
 * - Virtual Energy usage (Hypercars)
 * - Tire wear data
 * - Pit stop detection
 */

if (!defined('ABSPATH')) {
    exit;
}

class Apex_Notes_XML_Parser {
    
    /**
     * Parse an XML file and return structured data
     * 
     * @param string $file_path Path to XML file
     * @return array|WP_Error Parsed data or error
     */
    public static function parse_file($file_path) {
        if (!file_exists($file_path)) {
            return new WP_Error('file_not_found', 'XML file not found');
        }
        
        $xml_content = file_get_contents($file_path);
        return self::parse_content($xml_content, $file_path);
    }
    
    /**
     * Parse XML content string
     *
     * @param string $xml_content Raw XML content
     * @param string $source_file Optional source filename for hash
     * @param string|null $driver_name Optional: find this specific driver by <Name>.
     *        Required for multiplayer XMLs where every driver has isPlayer=1.
     * @return array|WP_Error Parsed data or error
     */
    public static function parse_content($xml_content, $source_file = '', $driver_name = null) {
        // Suppress XML errors and handle them manually
        libxml_use_internal_errors(true);

        // Parse XML
        $xml = simplexml_load_string($xml_content);

        if ($xml === false) {
            $errors = libxml_get_errors();
            libxml_clear_errors();
            return new WP_Error('xml_parse_error', 'Failed to parse XML: ' . ($errors[0]->message ?? 'Unknown error'));
        }

        // Verify this is an rFactor/LMU results file
        if (!isset($xml->RaceResults)) {
            return new WP_Error('invalid_format', 'Not a valid LMU/rFactor results file');
        }

        $results = $xml->RaceResults;

        // Extract session metadata
        $session_data = self::extract_session_metadata($results, $xml_content, $source_file);

        if (is_wp_error($session_data)) {
            return $session_data;
        }

        // Find the player's driver data
        $player_data = self::find_player_driver($results, $driver_name);

        if (is_wp_error($player_data)) {
            return $player_data;
        }

        // Update session data with player car info
        $session_data['car_type'] = (string) $player_data['driver']->CarType;
        $session_data['car_class'] = self::normalize_car_class((string) $player_data['driver']->CarClass);

        // Extract position data (Race sessions)
        $driver = $player_data['driver'];
        $session_data['grid_position'] = isset($driver->GridPos) ? intval($driver->GridPos) : null;
        $session_data['finish_position'] = isset($driver->Position) ? intval($driver->Position) : null;

        // Extract lap data
        $laps = self::extract_lap_data($player_data['driver'], $session_data['car_type']);

        // Calculate session statistics
        $stats = self::calculate_statistics($laps, $session_data['car_type']);

        // Merge stats into session data
        $session_data = array_merge($session_data, $stats);

        return array(
            'session' => $session_data,
            'laps' => $laps,
            'player_name' => (string) $player_data['driver']->Name,
            'session_type_name' => $player_data['session_type']
        );
    }

    /**
     * List every driver in the XML along with summary info, so the user
     * can pick which one is them (especially for multiplayer XMLs where
     * LMU flags every driver as isPlayer=1).
     *
     * @param string $xml_content
     * @return array|WP_Error List of { name, car, class, laps, pits, grid, finish, is_player, has_laps, session_type }
     */
    public static function list_candidates($xml_content) {
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($xml_content);
        if ($xml === false || !isset($xml->RaceResults)) {
            libxml_clear_errors();
            return new WP_Error('invalid_format', 'Not a valid LMU/rFactor results file');
        }
        $results = $xml->RaceResults;

        $session_names = self::ordered_session_names($results);
        $out = array();
        $seen_names = array();

        foreach ($session_names as $session_type) {
            $session = $results->$session_type;
            if (!isset($session->Driver)) {
                continue;
            }
            foreach ($session->Driver as $driver) {
                $name = trim((string) $driver->Name);
                if ($name === '') {
                    continue;
                }
                // Only list each driver once across sessions (prefer Race info)
                $key = strtolower($name);
                if (isset($seen_names[$key])) {
                    continue;
                }
                $seen_names[$key] = true;
                $out[] = array(
                    'name' => $name,
                    'car' => (string) $driver->CarType,
                    'class_raw' => (string) $driver->CarClass,
                    'class' => self::normalize_car_class((string) $driver->CarClass),
                    'car_number' => isset($driver->CarNumber) ? (string) $driver->CarNumber : '',
                    'team' => isset($driver->TeamName) ? (string) $driver->TeamName : '',
                    'veh_name' => isset($driver->VehName) ? (string) $driver->VehName : '',
                    'is_player' => ((string) $driver->isPlayer === '1'),
                    'has_laps' => self::driver_has_laps($driver),
                    'grid' => isset($driver->GridPos) ? intval($driver->GridPos) : null,
                    'finish' => isset($driver->Position) ? intval($driver->Position) : null,
                    'total_laps' => isset($driver->Laps) ? intval($driver->Laps) : null,
                    'pitstops' => isset($driver->Pitstops) ? intval($driver->Pitstops) : null,
                    'finish_status' => isset($driver->FinishStatus) ? (string) $driver->FinishStatus : '',
                    'best_lap_time' => isset($driver->BestLapTime) ? floatval($driver->BestLapTime) : null,
                    'session_type' => $session_type,
                );
            }
        }

        return $out;
    }

    /**
     * Build an ordered list of session-type element names that actually
     * contain <Driver> children. Preferred order: Race > Qualifying > Practice > etc.
     */
    private static function ordered_session_names($results) {
        $preferred = array(
            'Race', 'Race1', 'Race2', 'Race3',
            'Qualifying', 'Qualifying1', 'Qualifying2', 'Qualifying3',
            'Practice', 'Practice1', 'Practice2', 'Practice3', 'Practice4',
            'WarmUp', 'Warmup',
            'TestDay', 'Test'
        );
        $seen = array();
        $names = array();
        foreach ($preferred as $name) {
            if (isset($results->$name) && isset($results->$name->Driver)) {
                $names[] = $name;
                $seen[$name] = true;
            }
        }
        foreach ($results->children() as $child) {
            $n = $child->getName();
            if (isset($seen[$n])) {
                continue;
            }
            if (isset($child->Driver)) {
                $names[] = $n;
                $seen[$n] = true;
            }
        }
        return $names;
    }

    /**
     * Find the player's driver data.
     *
     * Selection rules, in order:
     * 1. If $preferred_name is given: return the driver whose <Name> matches
     *    (case-insensitive, trimmed) and has lap data. This is the ONLY safe
     *    path for multiplayer XMLs where every driver has isPlayer=1.
     * 2. Else, if exactly one driver across all sessions has isPlayer=1 AND
     *    has laps, return that driver (offline / solo XML — unambiguous).
     * 3. Else, if the XML has a single driver total with laps, return it
     *    (solo hotlap/test with no isPlayer flag set).
     * 4. Else, return WP_Error('multiple_candidates') so the caller can
     *    prompt the user to pick. The list is retrievable via list_candidates().
     */
    private static function find_player_driver($results, $preferred_name = null) {
        $session_names = self::ordered_session_names($results);

        // Case 1: caller specified a driver name — exact match wins
        if ($preferred_name !== null && $preferred_name !== '') {
            $needle = strtolower(trim($preferred_name));
            foreach ($session_names as $session_type) {
                $session = $results->$session_type;
                if (!isset($session->Driver)) {
                    continue;
                }
                foreach ($session->Driver as $driver) {
                    $n = strtolower(trim((string) $driver->Name));
                    if ($n === $needle && self::driver_has_laps($driver)) {
                        return array('driver' => $driver, 'session_type' => $session_type);
                    }
                }
            }
            return new WP_Error('driver_not_found', 'Driver "' . $preferred_name . '" not found in this XML.');
        }

        // Count isPlayer=1 drivers across all sessions
        $player_drivers = array();
        $all_drivers_with_laps = array();
        foreach ($session_names as $session_type) {
            $session = $results->$session_type;
            if (!isset($session->Driver)) {
                continue;
            }
            foreach ($session->Driver as $driver) {
                if (!self::driver_has_laps($driver)) {
                    continue;
                }
                $all_drivers_with_laps[] = array('driver' => $driver, 'session_type' => $session_type);
                if ((string) $driver->isPlayer === '1') {
                    $player_drivers[] = array('driver' => $driver, 'session_type' => $session_type);
                }
            }
        }

        // Case 2: exactly one isPlayer=1 with laps — offline XML, unambiguous
        if (count($player_drivers) === 1) {
            return $player_drivers[0];
        }

        // Case 3: single-driver solo XML with no isPlayer flag
        if (count($player_drivers) === 0 && count($all_drivers_with_laps) === 1) {
            return $all_drivers_with_laps[0];
        }

        // Case 4: ambiguous — multiple drivers flagged isPlayer=1 (multiplayer)
        if (count($player_drivers) > 1) {
            return new WP_Error(
                'multiple_candidates',
                'This XML contains ' . count($player_drivers) . ' drivers — please pick which one is you.'
            );
        }

        return new WP_Error('no_player_data', 'No driver with lap data found in this XML.');
    }

    /**
     * Does this driver element contain any <Lap> children?
     */
    private static function driver_has_laps($driver) {
        foreach ($driver->children() as $child) {
            if ($child->getName() === 'Lap') {
                return true;
            }
        }
        return false;
    }

    /**
     * Extract session metadata from XML
     */
    private static function extract_session_metadata($results, $xml_content, $source_file) {
        // Generate unique hash for deduplication
        $hash_content = $source_file ?: $xml_content;
        $session_hash = hash('sha256', $hash_content);
        
        // Parse session date
        $time_string = (string) $results->TimeString;
        $session_date = null;
        if ($time_string) {
            // Format: 2025/12/01 15:00:26
            $session_date = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $time_string)));
        }
        
        return array(
            'session_hash' => $session_hash,
            'track_venue' => (string) $results->TrackVenue,
            'track_course' => (string) $results->TrackCourse,
            'session_date' => $session_date,
            'game_version' => (string) $results->GameVersion,
            'fuel_mult' => floatval($results->FuelMult) ?: 1.0,
            'tire_mult' => floatval($results->TireMult) ?: 1.0,
        );
    }


    /**
     * Normalize car class names
     */
    private static function normalize_car_class($car_class) {
        $map = array(
            'Hyper' => 'Hypercar',
            'GT3' => 'LMGT3',
            'LMP2_ELMS' => 'LMP2',
            'GTE' => 'GTE',
            'LMP2' => 'LMP2',
            'LMP3' => 'LMP3',
        );
        
        return $map[$car_class] ?? $car_class;
    }
    
    /**
     * Extract lap data from driver element.
     *
     * Post-processes laps to flag the out-lap (lap immediately after a pit
     * stop). Out-laps begin with cold tyres and incomplete fuel stint data,
     * so their per-lap fuel/VE usage is unreliable.
     */
    private static function extract_lap_data($driver, $car_type) {
        $laps = array();

        foreach ($driver->children() as $child) {
            if ($child->getName() !== 'Lap') {
                continue;
            }

            $lap = self::parse_lap_element($child, $car_type);
            if ($lap) {
                $laps[] = $lap;
            }
        }

        // Second pass: mark out-laps (the lap after any pit in-lap) as invalid
        // for fuel calculations. We iterate in order; the lap with pit=1 is the
        // in-lap, and the next lap in sequence is the out-lap.
        $count = count($laps);
        for ($i = 0; $i < $count - 1; $i++) {
            if (!empty($laps[$i]['is_pit_lap'])) {
                $next = $i + 1;
                if ($laps[$next]['is_valid']) {
                    $laps[$next]['is_valid'] = 0;
                    $laps[$next]['invalid_reason'] = 'Out-lap (post-pit)';
                }
            }
        }

        return $laps;
    }
    
    /**
     * Parse a single lap element
     */
    private static function parse_lap_element($lap_element, $car_type) {
        $attrs = $lap_element->attributes();
        
        $lap_num = intval($attrs['num']);
        $fuel_remaining = floatval($attrs['fuel']);
        $fuel_used = floatval($attrs['fuelUsed']);
        $ve_remaining = isset($attrs['ve']) ? floatval($attrs['ve']) : null;
        $ve_used = isset($attrs['veUsed']) ? floatval($attrs['veUsed']) : null;
        $is_pit = isset($attrs['pit']) && (string) $attrs['pit'] === '1';
        
        // Parse lap time from element content
        $lap_time_str = trim((string) $lap_element);
        $lap_time = null;
        if ($lap_time_str && $lap_time_str !== '--.----' && $lap_time_str !== '--.---') {
            $lap_time = floatval($lap_time_str);
        }
        
        // Determine if lap is valid for fuel calculations
        $is_valid = true;
        $invalid_reason = '';

        // Pit laps have incomplete stint fuel and often refueling
        if ($is_pit) {
            $is_valid = false;
            $invalid_reason = 'Pit stop lap';
        }
        // Negative fuel used means refueling happened
        elseif ($fuel_used < 0) {
            $is_valid = false;
            $invalid_reason = 'Refueling detected';
        }
        // No lap time recorded (incomplete lap)
        elseif ($lap_time === null) {
            $is_valid = false;
            $invalid_reason = 'Incomplete lap';
        }
        // First lap is usually an out-lap from the grid or pits with
        // abnormally low fuel burn. Only mark invalid if the burn is
        // clearly below realistic stint usage (< 25% of tank / lap).
        // For very short races (sprint formats) the first lap may be
        // legitimately close to average, but excluding it is the safer
        // default for fuel-consumption analytics.
        elseif ($lap_num === 1) {
            $is_valid = false;
            $invalid_reason = 'First lap (out-lap)';
        }
        // Extremely high fuel usage (likely incident, stall, or telemetry glitch)
        elseif ($fuel_used > 0.15) { // More than 15% in one lap is suspicious
            $is_valid = false;
            $invalid_reason = 'Abnormal fuel usage';
        }
        // Near-zero fuel usage with a valid lap time is a data anomaly
        // (e.g. telemetry skipped a lap, virtual-energy-only mode).
        elseif ($fuel_used < 0.0005 && $lap_time > 30) {
            $is_valid = false;
            $invalid_reason = 'No fuel burn recorded';
        }
        
        return array(
            'lap_num' => $lap_num,
            'position' => isset($attrs['p']) ? intval($attrs['p']) : null,
            'lap_time' => $lap_time,
            'sector1' => isset($attrs['s1']) ? floatval($attrs['s1']) : null,
            'sector2' => isset($attrs['s2']) ? floatval($attrs['s2']) : null,
            'sector3' => isset($attrs['s3']) ? floatval($attrs['s3']) : null,
            'top_speed' => isset($attrs['topspeed']) ? floatval($attrs['topspeed']) : null,
            'fuel_remaining' => $fuel_remaining,
            'fuel_used' => $fuel_used,
            've_remaining' => $ve_remaining,
            've_used' => $ve_used,
            'tire_wear_fl' => isset($attrs['twfl']) ? floatval($attrs['twfl']) : null,
            'tire_wear_fr' => isset($attrs['twfr']) ? floatval($attrs['twfr']) : null,
            'tire_wear_rl' => isset($attrs['twrl']) ? floatval($attrs['twrl']) : null,
            'tire_wear_rr' => isset($attrs['twrr']) ? floatval($attrs['twrr']) : null,
            'tire_compound' => isset($attrs['fcompound']) ? (string) $attrs['fcompound'] : '',
            'is_pit_lap' => $is_pit ? 1 : 0,
            'is_valid' => $is_valid ? 1 : 0,
            'invalid_reason' => $invalid_reason
        );
    }
    
    /**
     * Calculate statistics from lap data
     */
    private static function calculate_statistics($laps, $car_type) {
        $total_laps = count($laps);
        $valid_laps = array_filter($laps, function($lap) {
            return $lap['is_valid'] === 1;
        });
        $valid_count = count($valid_laps);
        
        // Count pit stops
        $pitstops = count(array_filter($laps, function($lap) {
            return $lap['is_pit_lap'] === 1;
        }));
        
        if ($valid_count === 0) {
            return array(
                'total_laps' => $total_laps,
                'valid_laps' => 0,
                'pitstops' => $pitstops,
                'avg_fuel_percent' => null,
                'avg_fuel_liters' => null,
                'min_fuel_percent' => null,
                'max_fuel_percent' => null,
                'avg_ve_percent' => null,
                'min_ve_percent' => null,
                'max_ve_percent' => null,
                'avg_lap_time' => null,
                'best_lap_time' => null
            );
        }
        
        // Get tank capacity
        $tank_capacity = Apex_Notes_DB::get_tank_capacity($car_type);
        if (!$tank_capacity) {
            $tank_capacity = 100; // Default fallback
        }
        
        // Calculate fuel stats
        $fuel_values = array_column($valid_laps, 'fuel_used');
        $avg_fuel_percent = array_sum($fuel_values) / count($fuel_values);
        $min_fuel_percent = min($fuel_values);
        $max_fuel_percent = max($fuel_values);
        
        // Convert to liters
        $avg_fuel_liters = $avg_fuel_percent * $tank_capacity;
        
        // Calculate VE stats (if available)
        $ve_values = array_filter(array_column($valid_laps, 've_used'), function($v) {
            return $v !== null;
        });
        
        $avg_ve_percent = null;
        $min_ve_percent = null;
        $max_ve_percent = null;
        
        if (!empty($ve_values)) {
            $avg_ve_percent = array_sum($ve_values) / count($ve_values);
            $min_ve_percent = min($ve_values);
            $max_ve_percent = max($ve_values);
        }
        
        // Calculate lap time stats
        $lap_times = array_filter(array_column($valid_laps, 'lap_time'), function($t) {
            return $t !== null && $t > 0;
        });
        
        $avg_lap_time = null;
        $best_lap_time = null;
        
        if (!empty($lap_times)) {
            $avg_lap_time = array_sum($lap_times) / count($lap_times);
            $best_lap_time = min($lap_times);
        }
        
        return array(
            'total_laps' => $total_laps,
            'valid_laps' => $valid_count,
            'pitstops' => $pitstops,
            'avg_fuel_percent' => round($avg_fuel_percent, 6),
            'avg_fuel_liters' => round($avg_fuel_liters, 3),
            'min_fuel_percent' => round($min_fuel_percent, 6),
            'max_fuel_percent' => round($max_fuel_percent, 6),
            'avg_ve_percent' => $avg_ve_percent !== null ? round($avg_ve_percent, 6) : null,
            'min_ve_percent' => $min_ve_percent !== null ? round($min_ve_percent, 6) : null,
            'max_ve_percent' => $max_ve_percent !== null ? round($max_ve_percent, 6) : null,
            'avg_lap_time' => $avg_lap_time !== null ? round($avg_lap_time, 3) : null,
            'best_lap_time' => $best_lap_time !== null ? round($best_lap_time, 3) : null
        );
    }
    
    /**
     * Format lap time for display (seconds to MM:SS.mmm)
     */
    public static function format_lap_time($seconds) {
        if ($seconds === null || $seconds <= 0) {
            return '--:--.---';
        }
        
        $minutes = floor($seconds / 60);
        $secs = fmod($seconds, 60);
        
        return sprintf('%d:%06.3f', $minutes, $secs);
    }
    
    /**
     * Format fuel consumption for display
     */
    public static function format_fuel($liters, $decimals = 2) {
        if ($liters === null) {
            return '--';
        }
        
        return number_format($liters, $decimals) . ' L';
    }
    
    /**
     * Format percentage for display
     */
    public static function format_percentage($decimal, $decimals = 1) {
        if ($decimal === null) {
            return '--';
        }
        
        return number_format($decimal * 100, $decimals) . '%';
    }
    
    /**
     * Validate uploaded file
     */
    public static function validate_upload($file) {
        // Check file type
        $allowed_types = array('text/xml', 'application/xml');
        if (!in_array($file['type'], $allowed_types)) {
            // Some servers report different MIME types, check extension
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if ($ext !== 'xml') {
                return new WP_Error('invalid_type', 'File must be an XML file');
            }
        }
        
        // Check file size (max 50MB)
        $max_size = 50 * 1024 * 1024;
        if ($file['size'] > $max_size) {
            return new WP_Error('file_too_large', 'File size must be less than 50MB');
        }
        
        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return new WP_Error('upload_error', 'File upload failed');
        }
        
        return true;
    }
    
    /**
     * Process parsed XML content into a saved session.
     *
     * Accepts the XML content and an explicit driver name (the user's
     * confirmed identity from the picker), parses for that driver, and
     * saves to the DB.
     *
     * @param string $xml_content Raw XML
     * @param int    $user_id
     * @param string $driver_name The user-confirmed <Name> to save for
     * @param bool   $share_with_community
     * @param string $source_file Filename used for the session hash (dedupe)
     * @return array|WP_Error
     */
    public static function save_for_driver($xml_content, $user_id, $driver_name, $share_with_community = false, $source_file = '', $skip_community_update = false) {
        $parsed = self::parse_content($xml_content, $source_file, $driver_name);
        if (is_wp_error($parsed)) {
            return $parsed;
        }

        $session_type = $parsed['session_type_name'];
        $session_data = $parsed['session'];
        $session_data['user_id'] = $user_id;
        $session_data['session_type'] = $session_type;
        $session_data['shared_with_community'] = $share_with_community ? 1 : 0;
        // Include driver name in the session_hash so two different drivers in
        // the same XML don't collide on the dedupe check. Also include user_id
        // so ghost-imports from different uploaders stay distinct.
        $session_data['session_hash'] = hash('sha256', ($source_file ?: $xml_content) . '|' . strtolower(trim($driver_name)) . '|u' . $user_id);

        $result = Apex_Notes_DB::save_fuel_session($session_data);
        if (isset($result['error'])) {
            return new WP_Error('duplicate', $result['error']);
        }
        $session_id = $result['session_id'];

        $laps_to_save = array();
        foreach ($parsed['laps'] as $lap) {
            $lap['session_id'] = $session_id;
            $laps_to_save[] = $lap;
        }
        Apex_Notes_DB::save_fuel_laps($laps_to_save);

        if ($share_with_community && !$skip_community_update) {
            Apex_Notes_DB::update_community_average(
                $session_data['track_venue'],
                $session_data['car_type']
            );
        }

        return array(
            'success' => true,
            'session_id' => $session_id,
            'session' => $session_data,
            'laps' => $parsed['laps'],
            'player_name' => $parsed['player_name'],
            'session_type' => $parsed['session_type_name'],
        );
    }

    /**
     * Legacy wrapper: kept for back-compat. Requires an unambiguous XML
     * (single isPlayer=1 or single driver). Multiplayer XMLs will return
     * a WP_Error('multiple_candidates') which the caller must handle.
     *
     * @deprecated Prefer the explicit two-step flow: list_candidates() +
     *             save_for_driver(). See class-apex-notes-ajax.php::upload_xml.
     */
    public static function process_upload($file, $user_id, $share_with_community = false) {
        $validation = self::validate_upload($file);
        if (is_wp_error($validation)) {
            return $validation;
        }
        $xml_content = file_get_contents($file['tmp_name']);
        $parsed = self::parse_content($xml_content, $file['tmp_name']);
        if (is_wp_error($parsed)) {
            return $parsed;
        }
        return self::save_for_driver(
            $xml_content,
            $user_id,
            $parsed['player_name'],
            $share_with_community,
            $file['tmp_name']
        );
    }
}
