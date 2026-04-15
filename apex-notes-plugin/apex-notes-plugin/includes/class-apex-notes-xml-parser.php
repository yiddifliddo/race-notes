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
     * @return array|WP_Error Parsed data or error
     */
    public static function parse_content($xml_content, $source_file = '') {
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
        $player_data = self::find_player_driver($results);
        
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
            'player_name' => (string) $player_data['driver']->n,
            'session_type_name' => $player_data['session_type']
        );
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
     * Find the player's driver data in the XML
     * Returns the driver with isPlayer=1 from Practice, Qualifying, or Race.
     *
     * Handles several LMU/rFactor XML layouts:
     *  - Offline: <RaceResults><Race><Driver>...</Driver></Race></RaceResults>
     *             (session container wraps Driver elements directly)
     *  - Online:  <RaceResults><Race><Race><Driver>...</Driver></Race></Race></RaceResults>
     *             (multiplayer nests drivers one level deeper; changelog note 1.18.3)
     *  - Solo test sessions with a single driver and no <isPlayer> flag.
     *
     * Also accepts any session-type element that contains Driver elements
     * (Race, Race1, Race2, Qualifying, Qualifying1-3, Practice, Practice1-3,
     *  TestDay, WarmUp, etc.) so we don't break when LMU adds new session names.
     */
    private static function find_player_driver($results) {
        // Preferred order — Race data is best, then Qualifying, then Practice.
        $preferred = array(
            'Race', 'Race1', 'Race2', 'Race3',
            'Qualifying', 'Qualifying1', 'Qualifying2', 'Qualifying3',
            'Practice', 'Practice1', 'Practice2', 'Practice3', 'Practice4',
            'WarmUp', 'Warmup',
            'TestDay', 'Test'
        );

        // Build ordered list: preferred first, then any other session-like child
        // that wasn't in the preferred list (so we tolerate unknown session names).
        $seen = array();
        $session_names = array();
        foreach ($preferred as $name) {
            if (isset($results->$name)) {
                $session_names[] = $name;
                $seen[$name] = true;
            }
        }
        foreach ($results->children() as $child) {
            $name = $child->getName();
            if (isset($seen[$name])) {
                continue;
            }
            // Heuristic: only consider elements that contain at least one Driver
            if (isset($child->Driver) || (isset($child->Race) && isset($child->Race->Driver))) {
                $session_names[] = $name;
                $seen[$name] = true;
            }
        }

        foreach ($session_names as $session_type) {
            $session = $results->$session_type;

            // Collect candidate driver containers. Multiplayer XMLs nest drivers
            // under <Race> inside the session element; offline XMLs put them
            // directly under the session element.
            $containers = array($session);
            if (isset($session->Race)) {
                $containers[] = $session->Race;
            }
            if (isset($session->Qualifying)) {
                $containers[] = $session->Qualifying;
            }
            if (isset($session->Practice)) {
                $containers[] = $session->Practice;
            }

            // First pass: look for isPlayer=1 with lap data
            foreach ($containers as $container) {
                if (!isset($container->Driver)) {
                    continue;
                }
                foreach ($container->Driver as $driver) {
                    if ((string) $driver->isPlayer === '1' && self::driver_has_laps($driver)) {
                        return array(
                            'driver' => $driver,
                            'session_type' => $session_type
                        );
                    }
                }
            }

            // Second pass: any driver flagged isPlayer (even without laps — covers
            // XMLs that store laps elsewhere; extract_lap_data will simply find none)
            foreach ($containers as $container) {
                if (!isset($container->Driver)) {
                    continue;
                }
                foreach ($container->Driver as $driver) {
                    if ((string) $driver->isPlayer === '1') {
                        return array(
                            'driver' => $driver,
                            'session_type' => $session_type
                        );
                    }
                }
            }

            // Third pass: if there's only one driver (solo hotlap/test), use it.
            foreach ($containers as $container) {
                if (!isset($container->Driver)) {
                    continue;
                }
                $drivers = $container->Driver;
                if (count($drivers) === 1 && self::driver_has_laps($drivers[0])) {
                    return array(
                        'driver' => $drivers[0],
                        'session_type' => $session_type
                    );
                }
            }
        }

        return new WP_Error('no_player_data', 'No player lap data found in this session. Make sure you are uploading your own session XML.');
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
     * Process uploaded XML file and save to database
     */
    public static function process_upload($file, $user_id, $share_with_community = false) {
        // Validate file
        $validation = self::validate_upload($file);
        if (is_wp_error($validation)) {
            return $validation;
        }
        
        // Parse XML
        $parsed = self::parse_file($file['tmp_name']);
        if (is_wp_error($parsed)) {
            return $parsed;
        }
        
        // Accept all session types (Race, Practice, Qualifying, TestDay, WarmUp...).
        // Community averages are still computed only from sessions with enough
        // valid laps (see Apex_Notes_DB::update_community_average).
        $session_type = $parsed['session_type_name'];
        $session_data = $parsed['session'];
        $session_data['user_id'] = $user_id;
        $session_data['session_type'] = $session_type;
        $session_data['shared_with_community'] = $share_with_community ? 1 : 0;
        
        // Save session
        $result = Apex_Notes_DB::save_fuel_session($session_data);
        
        if (isset($result['error'])) {
            return new WP_Error('duplicate', $result['error']);
        }
        
        $session_id = $result['session_id'];
        
        // Prepare and save laps
        $laps_to_save = array();
        foreach ($parsed['laps'] as $lap) {
            $lap['session_id'] = $session_id;
            $laps_to_save[] = $lap;
        }
        
        Apex_Notes_DB::save_fuel_laps($laps_to_save);
        
        // Update community averages if shared
        if ($share_with_community) {
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
            'session_type' => $parsed['session_type_name']
        );
    }
}
