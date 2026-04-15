<?php
/**
 * Apex Notes Database Operations
 */

if (!defined('ABSPATH')) {
    exit;
}

class Apex_Notes_DB {
    
    /**
     * Create database tables
     */
    public static function create_tables() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        // Notes table
        $table_notes = $wpdb->prefix . 'apex_notes';
        $sql_notes = "CREATE TABLE $table_notes (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            title varchar(255) NOT NULL,
            description text NOT NULL,
            car_class varchar(50) NOT NULL,
            car_id varchar(100) NOT NULL,
            track_id varchar(100) NOT NULL,
            track_layout varchar(100) DEFAULT '',
            difficulty varchar(50) NOT NULL,
            lap_time varchar(50) DEFAULT '',
            setup_notes text DEFAULT '',
            rating decimal(3,2) DEFAULT 0,
            rating_count int(11) DEFAULT 0,
            upvotes int(11) DEFAULT 0,
            downvotes int(11) DEFAULT 0,
            views int(11) DEFAULT 0,
            status varchar(20) DEFAULT 'pending',
            featured tinyint(1) DEFAULT 0,
            has_profanity tinyint(1) DEFAULT 0,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY user_id (user_id),
            KEY status (status),
            KEY car_class (car_class),
            KEY track_id (track_id)
        ) $charset_collate;";
        
        // Sections table
        $table_sections = $wpdb->prefix . 'apex_notes_sections';
        $sql_sections = "CREATE TABLE $table_sections (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            note_id bigint(20) NOT NULL,
            section_order int(11) NOT NULL DEFAULT 0,
            name varchar(255) NOT NULL,
            braking varchar(255) DEFAULT '',
            turn_in varchar(255) DEFAULT '',
            apex varchar(255) DEFAULT '',
            exit_point varchar(255) DEFAULT '',
            gear varchar(50) DEFAULT '',
            speed varchar(100) DEFAULT '',
            pro_tip text DEFAULT '',
            PRIMARY KEY (id),
            KEY note_id (note_id)
        ) $charset_collate;";
        
        // Ratings table
        $table_ratings = $wpdb->prefix . 'apex_notes_ratings';
        $sql_ratings = "CREATE TABLE $table_ratings (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            note_id bigint(20) NOT NULL,
            user_id bigint(20) NOT NULL,
            rating int(11) NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY user_note (user_id, note_id),
            KEY note_id (note_id)
        ) $charset_collate;";
        
        // Comments table
        $table_comments = $wpdb->prefix . 'apex_notes_comments';
        $sql_comments = "CREATE TABLE $table_comments (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            note_id bigint(20) NOT NULL,
            user_id bigint(20) NOT NULL,
            comment text NOT NULL,
            status varchar(20) DEFAULT 'pending',
            has_profanity tinyint(1) DEFAULT 0,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY note_id (note_id),
            KEY user_id (user_id),
            KEY status (status)
        ) $charset_collate;";
        
        // Votes table (thumbs up/down)
        $table_votes = $wpdb->prefix . 'apex_notes_votes';
        $sql_votes = "CREATE TABLE $table_votes (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            note_id bigint(20) NOT NULL,
            user_id bigint(20) NOT NULL,
            vote tinyint(1) NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY user_note_vote (user_id, note_id),
            KEY note_id (note_id)
        ) $charset_collate;";
        
        // Followers table
        $table_followers = $wpdb->prefix . 'apex_notes_followers';
        $sql_followers = "CREATE TABLE $table_followers (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            follower_id bigint(20) NOT NULL,
            following_id bigint(20) NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY follower_following (follower_id, following_id),
            KEY follower_id (follower_id),
            KEY following_id (following_id)
        ) $charset_collate;";
        
        // Notifications table
        $table_notifications = $wpdb->prefix . 'apex_notes_notifications';
        $sql_notifications = "CREATE TABLE $table_notifications (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            type varchar(50) NOT NULL,
            actor_id bigint(20) NOT NULL,
            note_id bigint(20) DEFAULT NULL,
            comment_id bigint(20) DEFAULT NULL,
            message text NOT NULL,
            is_read tinyint(1) DEFAULT 0,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY user_id (user_id),
            KEY is_read (is_read),
            KEY created_at (created_at)
        ) $charset_collate;";
        
        // Live events table
        $table_live_events = $wpdb->prefix . 'apex_notes_live_events';
        $sql_live_events = "CREATE TABLE $table_live_events (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            video_id varchar(50) NOT NULL,
            channel_id varchar(50) NOT NULL,
            channel_name varchar(100) NOT NULL,
            title varchar(500) NOT NULL,
            description text DEFAULT '',
            thumbnail_url varchar(500) DEFAULT '',
            scheduled_start datetime DEFAULT NULL,
            actual_start datetime DEFAULT NULL,
            actual_end datetime DEFAULT NULL,
            status varchar(20) DEFAULT 'upcoming',
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY video_id (video_id),
            KEY channel_id (channel_id),
            KEY status (status),
            KEY scheduled_start (scheduled_start)
        ) $charset_collate;";
        
        // User event alerts preferences
        $table_event_alerts = $wpdb->prefix . 'apex_notes_event_alerts';
        $sql_event_alerts = "CREATE TABLE $table_event_alerts (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            channel_id varchar(50) NOT NULL,
            enabled tinyint(1) DEFAULT 1,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY user_channel (user_id, channel_id),
            KEY user_id (user_id)
        ) $charset_collate;";
        
        // Car specifications table (tank capacity, class, etc.)
        $table_car_specs = $wpdb->prefix . 'apex_notes_car_specs';
        $sql_car_specs = "CREATE TABLE $table_car_specs (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            car_type varchar(100) NOT NULL,
            car_class varchar(50) NOT NULL,
            tank_capacity decimal(5,1) NOT NULL,
            manufacturer varchar(100) DEFAULT '',
            hybrid_type varchar(20) DEFAULT '',
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY car_type (car_type),
            KEY car_class (car_class)
        ) $charset_collate;";
        
        // Fuel data sessions table (one row per uploaded session)
        $table_fuel_sessions = $wpdb->prefix . 'apex_notes_fuel_sessions';
        $sql_fuel_sessions = "CREATE TABLE $table_fuel_sessions (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            session_hash varchar(64) NOT NULL,
            track_venue varchar(200) NOT NULL,
            track_course varchar(200) NOT NULL,
            car_type varchar(100) NOT NULL,
            car_class varchar(50) NOT NULL,
            session_type varchar(20) NOT NULL,
            session_date datetime NOT NULL,
            game_version varchar(20) DEFAULT '',
            fuel_mult decimal(3,2) DEFAULT 1.00,
            tire_mult decimal(3,2) DEFAULT 1.00,
            grid_position int(11) DEFAULT NULL,
            finish_position int(11) DEFAULT NULL,
            total_laps int(11) DEFAULT 0,
            valid_laps int(11) DEFAULT 0,
            avg_fuel_percent decimal(6,4) DEFAULT NULL,
            avg_fuel_liters decimal(5,3) DEFAULT NULL,
            min_fuel_percent decimal(6,4) DEFAULT NULL,
            max_fuel_percent decimal(6,4) DEFAULT NULL,
            avg_ve_percent decimal(6,4) DEFAULT NULL,
            min_ve_percent decimal(6,4) DEFAULT NULL,
            max_ve_percent decimal(6,4) DEFAULT NULL,
            avg_lap_time decimal(8,3) DEFAULT NULL,
            best_lap_time decimal(8,3) DEFAULT NULL,
            pitstops int(11) DEFAULT 0,
            shared_with_community tinyint(1) DEFAULT 0,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY session_hash (session_hash),
            KEY user_id (user_id),
            KEY track_venue (track_venue),
            KEY car_type (car_type),
            KEY car_class (car_class),
            KEY shared_with_community (shared_with_community)
        ) $charset_collate;";
        
        // Fuel data laps table (individual lap data for detailed analysis)
        $table_fuel_laps = $wpdb->prefix . 'apex_notes_fuel_laps';
        $sql_fuel_laps = "CREATE TABLE $table_fuel_laps (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            session_id bigint(20) NOT NULL,
            lap_num int(11) NOT NULL,
            position int(11) DEFAULT NULL,
            lap_time decimal(8,3) DEFAULT NULL,
            sector1 decimal(7,4) DEFAULT NULL,
            sector2 decimal(7,4) DEFAULT NULL,
            sector3 decimal(7,4) DEFAULT NULL,
            top_speed decimal(6,2) DEFAULT NULL,
            fuel_remaining decimal(6,4) NOT NULL,
            fuel_used decimal(6,4) NOT NULL,
            ve_remaining decimal(6,4) DEFAULT NULL,
            ve_used decimal(6,4) DEFAULT NULL,
            tire_wear_fl decimal(5,4) DEFAULT NULL,
            tire_wear_fr decimal(5,4) DEFAULT NULL,
            tire_wear_rl decimal(5,4) DEFAULT NULL,
            tire_wear_rr decimal(5,4) DEFAULT NULL,
            tire_compound varchar(50) DEFAULT '',
            is_pit_lap tinyint(1) DEFAULT 0,
            is_valid tinyint(1) DEFAULT 1,
            invalid_reason varchar(100) DEFAULT '',
            PRIMARY KEY (id),
            KEY session_id (session_id),
            KEY lap_num (lap_num),
            KEY is_valid (is_valid)
        ) $charset_collate;";
        
        // Community fuel averages table (aggregated data by track+car)
        $table_fuel_averages = $wpdb->prefix . 'apex_notes_fuel_averages';
        $sql_fuel_averages = "CREATE TABLE $table_fuel_averages (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            track_venue varchar(200) NOT NULL,
            car_type varchar(100) NOT NULL,
            car_class varchar(50) NOT NULL,
            sample_count int(11) DEFAULT 0,
            lap_count int(11) DEFAULT 0,
            avg_fuel_liters decimal(5,3) DEFAULT NULL,
            min_fuel_liters decimal(5,3) DEFAULT NULL,
            max_fuel_liters decimal(5,3) DEFAULT NULL,
            std_dev_fuel decimal(5,3) DEFAULT NULL,
            avg_ve_percent decimal(6,4) DEFAULT NULL,
            min_ve_percent decimal(6,4) DEFAULT NULL,
            max_ve_percent decimal(6,4) DEFAULT NULL,
            avg_lap_time decimal(8,3) DEFAULT NULL,
            avg_pitstops decimal(4,2) DEFAULT NULL,
            fuel_mult_note varchar(20) DEFAULT '1x',
            last_updated datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY track_car (track_venue, car_type),
            KEY car_class (car_class),
            KEY sample_count (sample_count)
        ) $charset_collate;";
        
        // Community tire averages table (aggregated tire wear data by track+car)
        $table_tire_averages = $wpdb->prefix . 'apex_notes_tire_averages';
        $sql_tire_averages = "CREATE TABLE $table_tire_averages (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            track_venue varchar(200) NOT NULL,
            car_type varchar(100) NOT NULL,
            car_class varchar(50) NOT NULL,
            tire_compound varchar(50) DEFAULT 'Medium',
            sample_count int(11) DEFAULT 0,
            lap_count int(11) DEFAULT 0,
            avg_wear_fl_per_lap decimal(6,4) DEFAULT NULL,
            avg_wear_fr_per_lap decimal(6,4) DEFAULT NULL,
            avg_wear_rl_per_lap decimal(6,4) DEFAULT NULL,
            avg_wear_rr_per_lap decimal(6,4) DEFAULT NULL,
            min_wear_per_lap decimal(6,4) DEFAULT NULL,
            max_wear_per_lap decimal(6,4) DEFAULT NULL,
            critical_tire varchar(10) DEFAULT 'FR',
            avg_critical_wear decimal(6,4) DEFAULT NULL,
            estimated_tire_life int(11) DEFAULT NULL,
            tire_mult_note varchar(20) DEFAULT '1x',
            last_updated datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY track_car_compound (track_venue, car_type, tire_compound),
            KEY car_class (car_class),
            KEY sample_count (sample_count)
        ) $charset_collate;";
        
        // Live broadcast sessions table
        $table_live_broadcasts = $wpdb->prefix . 'apex_notes_live_broadcasts';
        $sql_live_broadcasts = "CREATE TABLE $table_live_broadcasts (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            broadcast_key varchar(64) NOT NULL,
            session_name varchar(200) DEFAULT '',
            track_name varchar(200) DEFAULT '',
            is_active tinyint(1) DEFAULT 1,
            stream_url varchar(500) DEFAULT '',
            stream_type varchar(20) DEFAULT '',
            is_premium tinyint(1) DEFAULT 0,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            last_update datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY broadcast_key (broadcast_key),
            KEY user_id (user_id),
            KEY is_active (is_active)
        ) $charset_collate;";
        
        // Live telemetry data table (current state, overwritten each update)
        $table_live_telemetry = $wpdb->prefix . 'apex_notes_live_telemetry';
        $sql_live_telemetry = "CREATE TABLE $table_live_telemetry (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            broadcast_id bigint(20) NOT NULL,
            session_time varchar(20) DEFAULT '0:00:00',
            current_lap int(11) DEFAULT 0,
            total_laps int(11) DEFAULT 0,
            player_position int(11) DEFAULT 0,
            player_car varchar(100) DEFAULT '',
            player_class varchar(50) DEFAULT '',
            fuel_remaining decimal(5,2) DEFAULT 0,
            fuel_percent decimal(5,2) DEFAULT 0,
            fuel_laps_remaining decimal(5,2) DEFAULT 0,
            tire_fl_wear decimal(5,2) DEFAULT 100,
            tire_fr_wear decimal(5,2) DEFAULT 100,
            tire_rl_wear decimal(5,2) DEFAULT 100,
            tire_rr_wear decimal(5,2) DEFAULT 100,
            tire_fl_temp decimal(5,1) DEFAULT 0,
            tire_fr_temp decimal(5,1) DEFAULT 0,
            tire_rl_temp decimal(5,1) DEFAULT 0,
            tire_rr_temp decimal(5,1) DEFAULT 0,
            last_lap_time varchar(20) DEFAULT '',
            best_lap_time varchar(20) DEFAULT '',
            gap_ahead varchar(20) DEFAULT '',
            gap_behind varchar(20) DEFAULT '',
            flag_status varchar(50) DEFAULT 'green',
            flag_sector int(11) DEFAULT 0,
            penalties text DEFAULT NULL,
            positions_json longtext DEFAULT NULL,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY broadcast_id (broadcast_id)
        ) $charset_collate;";
        
        // User affiliate links table
        $table_affiliate_links = $wpdb->prefix . 'apex_notes_affiliate_links';
        $sql_affiliate_links = "CREATE TABLE $table_affiliate_links (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            link_type varchar(50) NOT NULL,
            label varchar(200) NOT NULL,
            url varchar(1000) NOT NULL,
            display_order int(11) DEFAULT 0,
            is_active tinyint(1) DEFAULT 1,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY user_id (user_id),
            KEY is_active (is_active)
        ) $charset_collate;";
        
        // User liveries table (temporary livery sharing)
        $table_liveries = $wpdb->prefix . 'apex_notes_liveries';
        $sql_liveries = "CREATE TABLE $table_liveries (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            livery_name varchar(200) NOT NULL,
            car_class varchar(50) DEFAULT '',
            car_id varchar(100) DEFAULT '',
            file_1_name varchar(255) NOT NULL,
            file_1_path varchar(500) NOT NULL,
            file_2_name varchar(255) NOT NULL,
            file_2_path varchar(500) NOT NULL,
            share_token varchar(64) NOT NULL,
            download_count int(11) DEFAULT 0,
            uploader_ip varchar(45) DEFAULT '',
            uploader_discord_id varchar(100) DEFAULT '',
            legal_agreed tinyint(1) DEFAULT 0,
            expires_at datetime NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY share_token (share_token),
            KEY user_id (user_id),
            KEY expires_at (expires_at)
        ) $charset_collate;";
        
        // Saved stewards reports table
        $table_stewards_reports = $wpdb->prefix . 'apex_notes_stewards_reports';
        $sql_stewards_reports = "CREATE TABLE $table_stewards_reports (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            share_token varchar(32) NOT NULL,
            report_name varchar(200) NOT NULL,
            track_name varchar(200) NOT NULL,
            event_name varchar(200) DEFAULT '',
            race_date varchar(50) DEFAULT '',
            report_data longtext NOT NULL,
            view_count int(11) DEFAULT 0,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY share_token (share_token),
            KEY user_id (user_id)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql_notes);
        dbDelta($sql_sections);
        dbDelta($sql_ratings);
        dbDelta($sql_comments);
        dbDelta($sql_votes);
        dbDelta($sql_followers);
        dbDelta($sql_notifications);
        dbDelta($sql_live_events);
        dbDelta($sql_event_alerts);
        dbDelta($sql_car_specs);
        dbDelta($sql_fuel_sessions);
        dbDelta($sql_fuel_laps);
        dbDelta($sql_fuel_averages);
        dbDelta($sql_tire_averages);
        dbDelta($sql_live_broadcasts);
        dbDelta($sql_live_telemetry);
        dbDelta($sql_affiliate_links);
        dbDelta($sql_liveries);
        dbDelta($sql_stewards_reports);
        
        // Seed car specifications
        self::seed_car_specifications();
        
        // Add track_layout column if it doesn't exist (for upgrades)
        $table_notes = $wpdb->prefix . 'apex_notes';
        $column = $wpdb->get_results("SHOW COLUMNS FROM $table_notes LIKE 'track_layout'");
        if (empty($column)) {
            $wpdb->query("ALTER TABLE $table_notes ADD COLUMN track_layout varchar(100) DEFAULT '' AFTER track_id");
        }
        
        update_option('apex_notes_db_version', APEX_NOTES_VERSION);
    }
    
    /**
     * Insert sample data
     */
    public static function insert_sample_data() {
        global $wpdb;
        
        $table_notes = $wpdb->prefix . 'apex_notes';
        
        // Check if sample data exists
        $count = $wpdb->get_var("SELECT COUNT(*) FROM $table_notes");
        if ($count > 0) {
            return;
        }
        
        // Get admin user ID
        $admin_user = get_users(array('role' => 'administrator', 'number' => 1));
        $user_id = !empty($admin_user) ? $admin_user[0]->ID : 1;
        
        // Sample notes
        $sample_notes = array(
            array(
                'user_id' => $user_id,
                'title' => 'Ultimate Guide to Circuit de la Sarthe - Ferrari 499P',
                'description' => 'Comprehensive pace notes for the full 13.6km Le Mans circuit. Covers all major braking zones and racing lines optimized for the Ferrari 499P Hypercar.',
                'car_class' => 'hypercar',
                'car_id' => 'ferrari-499p',
                'track_id' => 'la-sarthe',
                'difficulty' => 'advanced',
                'lap_time' => '3:25.456',
                'setup_notes' => 'Run medium downforce. Front ARB slightly stiffer. Brake bias 54-55%. Tire pressures: 27.5 PSI hot.',
                'rating' => 4.8,
                'rating_count' => 34,
                'views' => 1250,
                'status' => 'approved',
                'featured' => 1,
                'sections' => array(
                    array('name' => 'Dunlop Chicane', 'braking' => '100m board', 'turn_in' => 'Before right curb', 'apex' => 'Clip inside curb gently', 'exit_point' => 'Use all track, watch bump', 'gear' => '2nd', 'speed' => 'Entry: 280 / Apex: 85 km/h', 'pro_tip' => 'Smooth inputs key here.'),
                    array('name' => 'Tertre Rouge', 'braking' => 'Lift only', 'turn_in' => 'After bridge', 'apex' => 'Late for Mulsanne', 'exit_point' => 'Power early, track out left', 'gear' => '4th', 'speed' => 'Entry: 220 / Apex: 195 km/h', 'pro_tip' => 'Critical corner. Good exit = fast straight.'),
                    array('name' => 'Mulsanne Corner', 'braking' => '150m board', 'turn_in' => '50m marker', 'apex' => 'Geometric apex', 'exit_point' => 'Power as you unwind', 'gear' => '3rd', 'speed' => 'Entry: 330 / Apex: 95 km/h', 'pro_tip' => 'Trail brake to rotate.'),
                )
            ),
            array(
                'user_id' => $user_id,
                'title' => 'Spa-Francorchamps LMGT3 Guide - Porsche 911 GT3 R',
                'description' => 'Complete walkthrough of Spa with the Porsche 911 GT3 R. Perfect for intermediate drivers looking to improve lap times.',
                'car_class' => 'lmgt3',
                'car_id' => 'porsche-911-gt3-r',
                'track_id' => 'spa',
                'difficulty' => 'intermediate',
                'lap_time' => '2:18.234',
                'setup_notes' => 'Lower rear wing 1-2 clicks for Kemmel straight speed.',
                'rating' => 4.5,
                'rating_count' => 22,
                'views' => 890,
                'status' => 'approved',
                'featured' => 1,
                'sections' => array(
                    array('name' => 'La Source', 'braking' => '100m board', 'turn_in' => '50m marker', 'apex' => 'Very late, almost at exit', 'exit_point' => 'Short shift 3rd for traction', 'gear' => '2nd', 'speed' => 'Entry: 260 / Apex: 65 km/h', 'pro_tip' => 'Patience. Wait for rotation.'),
                    array('name' => 'Eau Rouge / Raidillon', 'braking' => 'None - flat out', 'turn_in' => 'Left at compression', 'apex' => 'Blind over crest', 'exit_point' => 'Hold line left', 'gear' => '5th-6th', 'speed' => 'Entry: 250 / Apex: 230 km/h', 'pro_tip' => 'Full commitment. Any lift unsettles car.'),
                )
            ),
            array(
                'user_id' => $user_id,
                'title' => 'Monza Speed Setup - Toyota GR010-Hybrid',
                'description' => 'Low downforce setup guide for maximum speed at Monza. Focus on braking zones and chicane techniques.',
                'car_class' => 'hypercar',
                'car_id' => 'toyota-gr010-hybrid',
                'track_id' => 'monza',
                'difficulty' => 'intermediate',
                'lap_time' => '1:34.567',
                'setup_notes' => 'Minimum downforce. Move brake bias forward.',
                'rating' => 4.2,
                'rating_count' => 15,
                'views' => 560,
                'status' => 'approved',
                'featured' => 0,
                'sections' => array(
                    array('name' => 'Prima Variante', 'braking' => '125m board', 'turn_in' => '50m marker', 'apex' => 'Clip first kerb lightly', 'exit_point' => 'Track out right', 'gear' => '2nd', 'speed' => 'Entry: 340 / Apex: 80 km/h', 'pro_tip' => 'Trail brake through chicane.'),
                )
            ),
        );
        
        foreach ($sample_notes as $note_data) {
            $sections = $note_data['sections'];
            unset($note_data['sections']);
            
            $wpdb->insert($table_notes, $note_data);
            $note_id = $wpdb->insert_id;
            
            if ($note_id && !empty($sections)) {
                $table_sections = $wpdb->prefix . 'apex_notes_sections';
                foreach ($sections as $index => $section) {
                    $section['note_id'] = $note_id;
                    $section['section_order'] = $index;
                    $wpdb->insert($table_sections, $section);
                }
            }
        }
    }
    
    /**
     * Get notes with filters
     */
    public static function get_notes($args = array()) {
        global $wpdb;
        
        $defaults = array(
            'status' => 'approved',
            'car_class' => '',
            'track_id' => '',
            'car_id' => '',
            'difficulty' => '',
            'search' => '',
            'user_id' => 0,
            'orderby' => 'created_at',
            'order' => 'DESC',
            'limit' => 50,
            'offset' => 0,
        );
        
        $args = wp_parse_args($args, $defaults);
        
        $table_notes = $wpdb->prefix . 'apex_notes';
        
        $where = array('1=1');
        $values = array();
        
        if (!empty($args['status'])) {
            $where[] = 'status = %s';
            $values[] = $args['status'];
        }
        
        if (!empty($args['car_class'])) {
            $where[] = 'car_class = %s';
            $values[] = $args['car_class'];
        }
        
        if (!empty($args['track_id'])) {
            $where[] = 'track_id = %s';
            $values[] = $args['track_id'];
        }
        
        if (!empty($args['car_id'])) {
            $where[] = 'car_id = %s';
            $values[] = $args['car_id'];
        }
        
        if (!empty($args['difficulty'])) {
            $where[] = 'difficulty = %s';
            $values[] = $args['difficulty'];
        }
        
        if (!empty($args['user_id'])) {
            $where[] = 'user_id = %d';
            $values[] = $args['user_id'];
        }
        
        if (!empty($args['search'])) {
            $where[] = '(title LIKE %s OR description LIKE %s)';
            $search_term = '%' . $wpdb->esc_like($args['search']) . '%';
            $values[] = $search_term;
            $values[] = $search_term;
        }
        
        $where_clause = implode(' AND ', $where);
        
        $allowed_orderby = array('created_at', 'rating', 'views', 'title');
        $orderby = in_array($args['orderby'], $allowed_orderby) ? $args['orderby'] : 'created_at';
        $order = strtoupper($args['order']) === 'ASC' ? 'ASC' : 'DESC';
        
        $sql = "SELECT * FROM $table_notes WHERE $where_clause ORDER BY featured DESC, $orderby $order LIMIT %d OFFSET %d";
        $values[] = intval($args['limit']);
        $values[] = intval($args['offset']);
        
        $prepared = $wpdb->prepare($sql, $values);
        $results = $wpdb->get_results($prepared, ARRAY_A);
        
        // Add sections and author info to each note
        foreach ($results as &$note) {
            $note['sections'] = self::get_note_sections($note['id']);
            $note['author'] = self::get_author_info($note['user_id']);
        }
        
        return $results;
    }
    
    /**
     * Get single note by ID
     */
    public static function get_note($note_id) {
        global $wpdb;
        
        $table_notes = $wpdb->prefix . 'apex_notes';
        
        $note = $wpdb->get_row(
            $wpdb->prepare("SELECT * FROM $table_notes WHERE id = %d", $note_id),
            ARRAY_A
        );
        
        if ($note) {
            $note['sections'] = self::get_note_sections($note_id);
            $note['author'] = self::get_author_info($note['user_id']);
        }
        
        return $note;
    }
    
    /**
     * Get note sections
     */
    public static function get_note_sections($note_id) {
        global $wpdb;
        
        $table_sections = $wpdb->prefix . 'apex_notes_sections';
        
        return $wpdb->get_results(
            $wpdb->prepare("SELECT * FROM $table_sections WHERE note_id = %d ORDER BY section_order ASC", $note_id),
            ARRAY_A
        );
    }
    
    /**
     * Get author info
     */
    public static function get_author_info($user_id) {
        $user = get_user_by('ID', $user_id);
        if (!$user) {
            return array(
                'id' => 0,
                'name' => 'Unknown',
                'avatar' => '',
            );
        }
        
        // Get avatar from OAuth if available, otherwise use gravatar
        $avatar = get_user_meta($user_id, 'apex_notes_avatar', true);
        if (!$avatar) {
            $avatar = get_avatar_url($user->ID, array('size' => 128));
        }
        
        return array(
            'id' => $user->ID,
            'name' => $user->display_name,
            'avatar' => $avatar,
        );
    }
    
    /**
     * Create a new note
     */
    public static function create_note($data) {
        global $wpdb;
        
        $table_notes = $wpdb->prefix . 'apex_notes';
        
        // Check for profanity
        $profanity = Apex_Notes_Profanity::check_content($data);
        
        $note_data = array(
            'user_id' => get_current_user_id(),
            'title' => sanitize_text_field($data['title']),
            'description' => sanitize_textarea_field($data['description']),
            'car_class' => sanitize_text_field($data['car_class']),
            'car_id' => sanitize_text_field($data['car_id']),
            'track_id' => sanitize_text_field($data['track_id']),
            'track_layout' => isset($data['track_layout']) ? sanitize_text_field($data['track_layout']) : '',
            'difficulty' => sanitize_text_field($data['difficulty']),
            'lap_time' => sanitize_text_field($data['lap_time']),
            'setup_notes' => sanitize_textarea_field($data['setup_notes']),
            'status' => 'pending',
            'has_profanity' => $profanity ? 1 : 0,
        );
        
        $wpdb->insert($table_notes, $note_data);
        $note_id = $wpdb->insert_id;
        
        // Insert sections
        if ($note_id && !empty($data['sections'])) {
            $table_sections = $wpdb->prefix . 'apex_notes_sections';
            foreach ($data['sections'] as $index => $section) {
                $section_data = array(
                    'note_id' => $note_id,
                    'section_order' => $index,
                    'name' => sanitize_text_field($section['name']),
                    'braking' => sanitize_text_field($section['braking']),
                    'turn_in' => sanitize_text_field($section['turn_in']),
                    'apex' => sanitize_text_field($section['apex']),
                    'exit_point' => sanitize_text_field($section['exit']),
                    'gear' => sanitize_text_field($section['gear']),
                    'speed' => sanitize_text_field($section['speed']),
                    'pro_tip' => sanitize_textarea_field($section['tip']),
                );
                $wpdb->insert($table_sections, $section_data);
            }
        }
        
        return $note_id;
    }
    
    /**
     * Update note status
     */
    public static function update_note_status($note_id, $status) {
        global $wpdb;
        
        $table_notes = $wpdb->prefix . 'apex_notes';
        
        return $wpdb->update(
            $table_notes,
            array('status' => sanitize_text_field($status)),
            array('id' => intval($note_id))
        );
    }
    
    /**
     * Update note views
     */
    public static function increment_views($note_id) {
        global $wpdb;
        
        $table_notes = $wpdb->prefix . 'apex_notes';
        
        $wpdb->query(
            $wpdb->prepare("UPDATE $table_notes SET views = views + 1 WHERE id = %d", $note_id)
        );
    }
    
    /**
     * Add rating
     */
    public static function add_rating($note_id, $user_id, $rating) {
        global $wpdb;
        
        $table_ratings = $wpdb->prefix . 'apex_notes_ratings';
        $table_notes = $wpdb->prefix . 'apex_notes';
        
        // Check if user already rated
        $existing = $wpdb->get_var(
            $wpdb->prepare("SELECT id FROM $table_ratings WHERE note_id = %d AND user_id = %d", $note_id, $user_id)
        );
        
        if ($existing) {
            // Update existing rating
            $wpdb->update(
                $table_ratings,
                array('rating' => intval($rating)),
                array('note_id' => $note_id, 'user_id' => $user_id)
            );
        } else {
            // Insert new rating
            $wpdb->insert($table_ratings, array(
                'note_id' => $note_id,
                'user_id' => $user_id,
                'rating' => intval($rating),
            ));
        }
        
        // Recalculate average rating
        $avg = $wpdb->get_var(
            $wpdb->prepare("SELECT AVG(rating) FROM $table_ratings WHERE note_id = %d", $note_id)
        );
        $count = $wpdb->get_var(
            $wpdb->prepare("SELECT COUNT(*) FROM $table_ratings WHERE note_id = %d", $note_id)
        );
        
        $wpdb->update(
            $table_notes,
            array('rating' => round($avg, 2), 'rating_count' => $count),
            array('id' => $note_id)
        );
        
        return array('rating' => round($avg, 2), 'count' => $count);
    }
    
    /**
     * Get pending count
     */
    public static function get_pending_count() {
        global $wpdb;
        
        $table_notes = $wpdb->prefix . 'apex_notes';
        
        return $wpdb->get_var("SELECT COUNT(*) FROM $table_notes WHERE status = 'pending'");
    }
    
    /**
     * Delete note
     */
    public static function delete_note($note_id) {
        global $wpdb;
        
        // Delete sections
        $wpdb->delete($wpdb->prefix . 'apex_notes_sections', array('note_id' => $note_id));
        
        // Delete ratings
        $wpdb->delete($wpdb->prefix . 'apex_notes_ratings', array('note_id' => $note_id));
        
        // Delete comments
        $wpdb->delete($wpdb->prefix . 'apex_notes_comments', array('note_id' => $note_id));
        
        // Delete votes
        $wpdb->delete($wpdb->prefix . 'apex_notes_votes', array('note_id' => $note_id));
        
        // Delete note
        return $wpdb->delete($wpdb->prefix . 'apex_notes', array('id' => $note_id));
    }
    
    /**
     * Vote on a note (1 = upvote, -1 = downvote, 0 = remove vote)
     */
    public static function vote_note($note_id, $user_id, $vote) {
        global $wpdb;
        
        $table_votes = $wpdb->prefix . 'apex_notes_votes';
        $table_notes = $wpdb->prefix . 'apex_notes';
        
        // Get existing vote
        $existing = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table_votes WHERE note_id = %d AND user_id = %d",
            $note_id, $user_id
        ));
        
        if ($vote == 0) {
            // Remove vote
            if ($existing) {
                $wpdb->delete($table_votes, array('id' => $existing->id));
            }
        } else {
            if ($existing) {
                // Update vote
                $wpdb->update($table_votes, array('vote' => $vote), array('id' => $existing->id));
            } else {
                // Insert new vote
                $wpdb->insert($table_votes, array(
                    'note_id' => $note_id,
                    'user_id' => $user_id,
                    'vote' => $vote,
                ));
            }
        }
        
        // Recalculate vote counts
        $upvotes = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table_votes WHERE note_id = %d AND vote = 1",
            $note_id
        ));
        $downvotes = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table_votes WHERE note_id = %d AND vote = -1",
            $note_id
        ));
        
        $wpdb->update($table_notes, 
            array('upvotes' => $upvotes, 'downvotes' => $downvotes),
            array('id' => $note_id)
        );
        
        return array('upvotes' => (int)$upvotes, 'downvotes' => (int)$downvotes);
    }
    
    /**
     * Get user's vote on a note
     */
    public static function get_user_vote($note_id, $user_id) {
        global $wpdb;
        
        $table_votes = $wpdb->prefix . 'apex_notes_votes';
        
        return $wpdb->get_var($wpdb->prepare(
            "SELECT vote FROM $table_votes WHERE note_id = %d AND user_id = %d",
            $note_id, $user_id
        ));
    }
    
    /**
     * Get notes by user
     */
    public static function get_user_notes($user_id, $status = 'approved') {
        global $wpdb;
        
        $table_notes = $wpdb->prefix . 'apex_notes';
        
        $where = "WHERE user_id = %d";
        if ($status) {
            $where .= " AND status = %s";
            $notes = $wpdb->get_results($wpdb->prepare(
                "SELECT * FROM $table_notes $where ORDER BY created_at DESC",
                $user_id, $status
            ), ARRAY_A);
        } else {
            $notes = $wpdb->get_results($wpdb->prepare(
                "SELECT * FROM $table_notes $where ORDER BY created_at DESC",
                $user_id
            ), ARRAY_A);
        }
        
        foreach ($notes as &$note) {
            $note['author'] = self::get_author_info($note['user_id']);
            $note['sections'] = self::get_note_sections($note['id']);
        }
        
        return $notes;
    }
    
    /**
     * Follow a user
     */
    public static function follow_user($follower_id, $following_id) {
        global $wpdb;
        
        if ($follower_id == $following_id) {
            return false; // Can't follow yourself
        }
        
        $table = $wpdb->prefix . 'apex_notes_followers';
        
        // Check if already following
        $existing = $wpdb->get_var($wpdb->prepare(
            "SELECT id FROM $table WHERE follower_id = %d AND following_id = %d",
            $follower_id, $following_id
        ));
        
        if ($existing) {
            return true; // Already following
        }
        
        $result = $wpdb->insert($table, array(
            'follower_id' => $follower_id,
            'following_id' => $following_id,
        ));
        
        if ($result) {
            // Create notification for the followed user
            self::create_notification(
                $following_id,
                'new_follower',
                $follower_id,
                null,
                null
            );
        }
        
        return $result !== false;
    }
    
    /**
     * Unfollow a user
     */
    public static function unfollow_user($follower_id, $following_id) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'apex_notes_followers';
        
        return $wpdb->delete($table, array(
            'follower_id' => $follower_id,
            'following_id' => $following_id,
        )) !== false;
    }
    
    /**
     * Check if user is following another user
     */
    public static function is_following($follower_id, $following_id) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'apex_notes_followers';
        
        return (bool) $wpdb->get_var($wpdb->prepare(
            "SELECT id FROM $table WHERE follower_id = %d AND following_id = %d",
            $follower_id, $following_id
        ));
    }
    
    /**
     * Get follower count for a user
     */
    public static function get_follower_count($user_id) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'apex_notes_followers';
        
        return (int) $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table WHERE following_id = %d",
            $user_id
        ));
    }
    
    /**
     * Get following count for a user
     */
    public static function get_following_count($user_id) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'apex_notes_followers';
        
        return (int) $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table WHERE follower_id = %d",
            $user_id
        ));
    }
    
    /**
     * Get followers list for a user
     */
    public static function get_followers($user_id, $limit = 50) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'apex_notes_followers';
        
        $follower_ids = $wpdb->get_col($wpdb->prepare(
            "SELECT follower_id FROM $table WHERE following_id = %d ORDER BY created_at DESC LIMIT %d",
            $user_id, $limit
        ));
        
        $followers = array();
        foreach ($follower_ids as $id) {
            $followers[] = self::get_author_info($id);
        }
        
        return $followers;
    }
    
    /**
     * Get following list for a user
     */
    public static function get_following($user_id, $limit = 50) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'apex_notes_followers';
        
        $following_ids = $wpdb->get_col($wpdb->prepare(
            "SELECT following_id FROM $table WHERE follower_id = %d ORDER BY created_at DESC LIMIT %d",
            $user_id, $limit
        ));
        
        $following = array();
        foreach ($following_ids as $id) {
            $following[] = self::get_author_info($id);
        }
        
        return $following;
    }
    
    /**
     * Ban a user
     */
    public static function ban_user($user_id, $reason = '') {
        update_user_meta($user_id, 'apex_notes_banned', 1);
        update_user_meta($user_id, 'apex_notes_ban_reason', sanitize_text_field($reason));
        update_user_meta($user_id, 'apex_notes_ban_date', current_time('mysql'));
        
        // Also reject all their pending notes
        global $wpdb;
        $table = $wpdb->prefix . 'apex_notes';
        $wpdb->update(
            $table,
            array('status' => 'rejected'),
            array('user_id' => $user_id, 'status' => 'pending')
        );
        
        return true;
    }
    
    /**
     * Unban a user
     */
    public static function unban_user($user_id) {
        delete_user_meta($user_id, 'apex_notes_banned');
        delete_user_meta($user_id, 'apex_notes_ban_reason');
        delete_user_meta($user_id, 'apex_notes_ban_date');
        return true;
    }
    
    /**
     * Check if user is banned
     */
    public static function is_user_banned($user_id) {
        return (bool) get_user_meta($user_id, 'apex_notes_banned', true);
    }
    
    /**
     * Get ban info for a user
     */
    public static function get_ban_info($user_id) {
        if (!self::is_user_banned($user_id)) {
            return null;
        }
        
        return array(
            'reason' => get_user_meta($user_id, 'apex_notes_ban_reason', true),
            'date' => get_user_meta($user_id, 'apex_notes_ban_date', true)
        );
    }
    
    /**
     * Get all banned users
     */
    public static function get_banned_users() {
        $users = get_users(array(
            'meta_key' => 'apex_notes_banned',
            'meta_value' => 1
        ));
        
        $banned = array();
        foreach ($users as $user) {
            $banned[] = array(
                'id' => $user->ID,
                'display_name' => $user->display_name,
                'email' => $user->user_email,
                'avatar' => get_user_meta($user->ID, 'apex_notes_avatar', true) ?: get_avatar_url($user->ID),
                'ban_reason' => get_user_meta($user->ID, 'apex_notes_ban_reason', true),
                'ban_date' => get_user_meta($user->ID, 'apex_notes_ban_date', true)
            );
        }
        
        return $banned;
    }
    
    /**
     * Create a notification
     */
    public static function create_notification($user_id, $type, $actor_id, $note_id = null, $comment_id = null, $custom_message = null) {
        global $wpdb;
        
        // Don't notify yourself (but allow system notifications where actor_id is 0)
        if ($actor_id > 0 && $user_id == $actor_id) {
            return false;
        }
        
        $table = $wpdb->prefix . 'apex_notes_notifications';
        
        // Use custom message if provided
        if ($custom_message) {
            $message = $custom_message;
        } else {
            // Build message based on type
            $actor = $actor_id > 0 ? get_userdata($actor_id) : null;
            $actor_name = $actor ? $actor->display_name : 'Someone';
            
            switch ($type) {
                case 'new_follower':
                    $message = $actor_name . ' started following you';
                    break;
                case 'new_comment':
                    $message = $actor_name . ' commented on your note';
                    break;
                case 'new_post':
                    $message = $actor_name . ' published a new track note';
                    break;
                case 'live_event':
                    $message = 'A race is now live!';
                    break;
                case 'upcoming_event':
                    $message = 'A race is starting soon!';
                    break;
                default:
                    $message = 'You have a new notification';
            }
        }
        
        return $wpdb->insert($table, array(
            'user_id' => $user_id,
            'type' => $type,
            'actor_id' => $actor_id,
            'note_id' => $note_id,
            'comment_id' => $comment_id,
            'message' => $message,
        )) !== false;
    }
    
    /**
     * Get notifications for a user
     */
    public static function get_notifications($user_id, $limit = 20, $unread_only = false) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'apex_notes_notifications';
        
        $where = "WHERE user_id = %d";
        if ($unread_only) {
            $where .= " AND is_read = 0";
        }
        
        $notifications = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table $where ORDER BY created_at DESC LIMIT %d",
            $user_id, $limit
        ), ARRAY_A);
        
        // Add actor info and note info
        foreach ($notifications as &$notif) {
            $actor_info = self::get_author_info($notif['actor_id']);
            $notif['actor'] = $actor_info;
            $notif['actor_name'] = $actor_info['name'];
            $notif['actor_avatar'] = $actor_info['avatar'];
            if ($notif['note_id']) {
                $note = self::get_note($notif['note_id']);
                $notif['note_title'] = $note ? $note['title'] : '';
            }
        }
        
        return $notifications;
    }
    
    /**
     * Get unread notification count
     */
    public static function get_unread_notification_count($user_id) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'apex_notes_notifications';
        
        return (int) $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table WHERE user_id = %d AND is_read = 0",
            $user_id
        ));
    }
    
    /**
     * Mark notification as read
     */
    public static function mark_notification_read($notification_id, $user_id) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'apex_notes_notifications';
        
        return $wpdb->update(
            $table,
            array('is_read' => 1),
            array('id' => $notification_id, 'user_id' => $user_id)
        ) !== false;
    }
    
    /**
     * Mark all notifications as read for a user
     */
    public static function mark_all_notifications_read($user_id) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'apex_notes_notifications';
        
        return $wpdb->update(
            $table,
            array('is_read' => 1),
            array('user_id' => $user_id, 'is_read' => 0)
        ) !== false;
    }
    
    /**
     * Notify followers of a new post
     */
    public static function notify_followers_new_post($user_id, $note_id) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'apex_notes_followers';
        
        $follower_ids = $wpdb->get_col($wpdb->prepare(
            "SELECT follower_id FROM $table WHERE following_id = %d",
            $user_id
        ));
        
        foreach ($follower_ids as $follower_id) {
            self::create_notification($follower_id, 'new_post', $user_id, $note_id);
        }
    }
    
    /**
     * Save or update a live event
     */
    public static function save_live_event($event_data) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'apex_notes_live_events';
        
        // Check if event already exists
        $existing = $wpdb->get_var($wpdb->prepare(
            "SELECT id FROM $table WHERE video_id = %s",
            $event_data['video_id']
        ));
        
        if ($existing) {
            // Update existing
            $wpdb->update(
                $table,
                array(
                    'title' => $event_data['title'],
                    'description' => isset($event_data['description']) ? $event_data['description'] : '',
                    'thumbnail_url' => isset($event_data['thumbnail_url']) ? $event_data['thumbnail_url'] : '',
                    'scheduled_start' => isset($event_data['scheduled_start']) ? $event_data['scheduled_start'] : null,
                    'actual_start' => isset($event_data['actual_start']) ? $event_data['actual_start'] : null,
                    'actual_end' => isset($event_data['actual_end']) ? $event_data['actual_end'] : null,
                    'status' => isset($event_data['status']) ? $event_data['status'] : 'upcoming'
                ),
                array('video_id' => $event_data['video_id'])
            );
            return $existing;
        } else {
            // Insert new
            $wpdb->insert($table, array(
                'video_id' => $event_data['video_id'],
                'channel_id' => $event_data['channel_id'],
                'channel_name' => $event_data['channel_name'],
                'title' => $event_data['title'],
                'description' => isset($event_data['description']) ? $event_data['description'] : '',
                'thumbnail_url' => isset($event_data['thumbnail_url']) ? $event_data['thumbnail_url'] : '',
                'scheduled_start' => isset($event_data['scheduled_start']) ? $event_data['scheduled_start'] : null,
                'actual_start' => isset($event_data['actual_start']) ? $event_data['actual_start'] : null,
                'status' => isset($event_data['status']) ? $event_data['status'] : 'upcoming'
            ));
            return $wpdb->insert_id;
        }
    }
    
    /**
     * Get live events for calendar
     */
    public static function get_live_events($start_date = null, $end_date = null, $status = null) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'apex_notes_live_events';
        
        $where = "WHERE 1=1";
        $params = array();
        
        if ($start_date) {
            $where .= " AND (scheduled_start >= %s OR actual_start >= %s)";
            $params[] = $start_date;
            $params[] = $start_date;
        }
        
        if ($end_date) {
            $where .= " AND (scheduled_start <= %s OR actual_start <= %s)";
            $params[] = $end_date;
            $params[] = $end_date;
        }
        
        if ($status) {
            $where .= " AND status = %s";
            $params[] = $status;
        }
        
        $sql = "SELECT * FROM $table $where ORDER BY COALESCE(scheduled_start, actual_start, created_at) ASC";
        
        if (!empty($params)) {
            $sql = $wpdb->prepare($sql, ...$params);
        }
        
        return $wpdb->get_results($sql, ARRAY_A);
    }
    
    /**
     * Get upcoming and live events
     */
    public static function get_upcoming_live_events($limit = 20) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'apex_notes_live_events';
        
        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table 
             WHERE status IN ('upcoming', 'live') 
             ORDER BY COALESCE(scheduled_start, actual_start, created_at) ASC 
             LIMIT %d",
            $limit
        ), ARRAY_A);
    }
    
    /**
     * Get currently live events
     */
    public static function get_currently_live_events() {
        global $wpdb;
        
        $table = $wpdb->prefix . 'apex_notes_live_events';
        
        return $wpdb->get_results(
            "SELECT * FROM $table WHERE status = 'live' ORDER BY actual_start DESC",
            ARRAY_A
        );
    }
    
    /**
     * Update event status
     */
    public static function update_event_status($video_id, $status) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'apex_notes_live_events';
        
        $data = array('status' => $status);
        
        if ($status === 'live') {
            $data['actual_start'] = current_time('mysql');
        } elseif ($status === 'completed') {
            $data['actual_end'] = current_time('mysql');
        }
        
        return $wpdb->update($table, $data, array('video_id' => $video_id));
    }
    
    /**
     * Get user event alert preferences
     */
    public static function get_user_event_alerts($user_id) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'apex_notes_event_alerts';
        
        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table WHERE user_id = %d",
            $user_id
        ), ARRAY_A);
    }
    
    /**
     * Set user event alert preference
     */
    public static function set_user_event_alert($user_id, $channel_id, $enabled = true) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'apex_notes_event_alerts';
        
        // Check if exists
        $existing = $wpdb->get_var($wpdb->prepare(
            "SELECT id FROM $table WHERE user_id = %d AND channel_id = %s",
            $user_id, $channel_id
        ));
        
        if ($existing) {
            return $wpdb->update(
                $table,
                array('enabled' => $enabled ? 1 : 0),
                array('user_id' => $user_id, 'channel_id' => $channel_id)
            );
        } else {
            return $wpdb->insert($table, array(
                'user_id' => $user_id,
                'channel_id' => $channel_id,
                'enabled' => $enabled ? 1 : 0
            ));
        }
    }
    
    /**
     * Get users subscribed to channel alerts
     */
    public static function get_channel_alert_subscribers($channel_id) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'apex_notes_event_alerts';
        
        return $wpdb->get_col($wpdb->prepare(
            "SELECT user_id FROM $table WHERE channel_id = %s AND enabled = 1",
            $channel_id
        ));
    }
    
    /**
     * Create live event notification for subscribed users
     */
    public static function notify_live_event($event) {
        $subscribers = self::get_channel_alert_subscribers($event['channel_id']);
        
        foreach ($subscribers as $user_id) {
            self::create_notification(
                $user_id,
                'live_event',
                0, // No actor for system notifications
                null,
                null,
                sprintf('🔴 LIVE NOW: %s on %s', $event['title'], $event['channel_name'])
            );
        }
    }
    
    /**
     * Delete old completed events (cleanup)
     */
    public static function cleanup_old_events($days_old = 30) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'apex_notes_live_events';
        
        return $wpdb->query($wpdb->prepare(
            "DELETE FROM $table WHERE status = 'completed' AND actual_end < DATE_SUB(NOW(), INTERVAL %d DAY)",
            $days_old
        ));
    }
    
    /**
     * Seed 2026 race calendars
     */
    public static function seed_race_calendars() {
        $calendars = array(
            // IMSA WeatherTech 2026
            array(
                'video_id' => 'calendar-imsa-daytona-2026',
                'channel_id' => 'imsaofficial',
                'channel_name' => 'IMSA Official',
                'title' => 'Rolex 24 At Daytona',
                'description' => '2026 IMSA WeatherTech SportsCar Championship - 24 Hours of Daytona',
                'scheduled_start' => '2026-01-24 13:40:00',
                'status' => 'upcoming'
            ),
            array(
                'video_id' => 'calendar-imsa-sebring-2026',
                'channel_id' => 'imsaofficial',
                'channel_name' => 'IMSA Official',
                'title' => 'Mobil 1 Twelve Hours of Sebring',
                'description' => '2026 IMSA WeatherTech SportsCar Championship - 12 Hours of Sebring',
                'scheduled_start' => '2026-03-21 10:00:00',
                'status' => 'upcoming'
            ),
            array(
                'video_id' => 'calendar-imsa-longbeach-2026',
                'channel_id' => 'imsaofficial',
                'channel_name' => 'IMSA Official',
                'title' => 'Acura Grand Prix of Long Beach',
                'description' => '2026 IMSA WeatherTech SportsCar Championship - Long Beach',
                'scheduled_start' => '2026-04-18 17:00:00',
                'status' => 'upcoming'
            ),
            array(
                'video_id' => 'calendar-imsa-laguna-2026',
                'channel_id' => 'imsaofficial',
                'channel_name' => 'IMSA Official',
                'title' => 'Monterey SportsCar Championship',
                'description' => '2026 IMSA WeatherTech SportsCar Championship - Laguna Seca',
                'scheduled_start' => '2026-05-03 14:00:00',
                'status' => 'upcoming'
            ),
            array(
                'video_id' => 'calendar-imsa-detroit-2026',
                'channel_id' => 'imsaofficial',
                'channel_name' => 'IMSA Official',
                'title' => 'Chevrolet Detroit Grand Prix',
                'description' => '2026 IMSA WeatherTech SportsCar Championship - Detroit',
                'scheduled_start' => '2026-05-30 13:00:00',
                'status' => 'upcoming'
            ),
            array(
                'video_id' => 'calendar-imsa-watkins-2026',
                'channel_id' => 'imsaofficial',
                'channel_name' => 'IMSA Official',
                'title' => "Sahlen's Six Hours of The Glen",
                'description' => '2026 IMSA WeatherTech SportsCar Championship - Watkins Glen 6 Hours',
                'scheduled_start' => '2026-06-28 10:30:00',
                'status' => 'upcoming'
            ),
            array(
                'video_id' => 'calendar-imsa-mosport-2026',
                'channel_id' => 'imsaofficial',
                'channel_name' => 'IMSA Official',
                'title' => 'Chevrolet Grand Prix',
                'description' => '2026 IMSA WeatherTech SportsCar Championship - Canadian Tire Motorsport Park',
                'scheduled_start' => '2026-07-12 12:00:00',
                'status' => 'upcoming'
            ),
            array(
                'video_id' => 'calendar-imsa-roadamerica-2026',
                'channel_id' => 'imsaofficial',
                'channel_name' => 'IMSA Official',
                'title' => 'IMSA SportsCar Grand Prix at Road America',
                'description' => '2026 IMSA WeatherTech SportsCar Championship - Road America 6 Hours',
                'scheduled_start' => '2026-08-02 11:00:00',
                'status' => 'upcoming'
            ),
            array(
                'video_id' => 'calendar-imsa-vir-2026',
                'channel_id' => 'imsaofficial',
                'channel_name' => 'IMSA Official',
                'title' => 'Michelin GT Challenge at VIR',
                'description' => '2026 IMSA WeatherTech SportsCar Championship - Virginia International Raceway',
                'scheduled_start' => '2026-08-23 14:00:00',
                'status' => 'upcoming'
            ),
            array(
                'video_id' => 'calendar-imsa-indy-2026',
                'channel_id' => 'imsaofficial',
                'channel_name' => 'IMSA Official',
                'title' => 'Battle on the Bricks',
                'description' => '2026 IMSA WeatherTech SportsCar Championship - Indianapolis Motor Speedway',
                'scheduled_start' => '2026-09-20 13:00:00',
                'status' => 'upcoming'
            ),
            array(
                'video_id' => 'calendar-imsa-petitlemans-2026',
                'channel_id' => 'imsaofficial',
                'channel_name' => 'IMSA Official',
                'title' => 'Motul Petit Le Mans',
                'description' => '2026 IMSA WeatherTech SportsCar Championship - Road Atlanta 10 Hours',
                'scheduled_start' => '2026-10-17 12:00:00',
                'status' => 'upcoming'
            ),
            
            // FIA WEC 2026
            array(
                'video_id' => 'calendar-wec-qatar-2026',
                'channel_id' => 'FIAWEC',
                'channel_name' => 'FIA WEC',
                'title' => 'Qatar 1812km',
                'description' => '2026 FIA World Endurance Championship - Lusail International Circuit',
                'scheduled_start' => '2026-03-28 12:00:00',
                'status' => 'upcoming'
            ),
            array(
                'video_id' => 'calendar-wec-imola-2026',
                'channel_id' => 'FIAWEC',
                'channel_name' => 'FIA WEC',
                'title' => '6 Hours of Imola',
                'description' => '2026 FIA World Endurance Championship - Autodromo Enzo e Dino Ferrari',
                'scheduled_start' => '2026-04-19 12:00:00',
                'status' => 'upcoming'
            ),
            array(
                'video_id' => 'calendar-wec-spa-2026',
                'channel_id' => 'FIAWEC',
                'channel_name' => 'FIA WEC',
                'title' => 'TotalEnergies 6 Hours of Spa-Francorchamps',
                'description' => '2026 FIA World Endurance Championship - Circuit de Spa-Francorchamps',
                'scheduled_start' => '2026-05-09 13:30:00',
                'status' => 'upcoming'
            ),
            array(
                'video_id' => 'calendar-wec-lemans-2026',
                'channel_id' => 'FIAWEC',
                'channel_name' => 'FIA WEC',
                'title' => '24 Hours of Le Mans',
                'description' => '2026 FIA World Endurance Championship - Circuit de la Sarthe',
                'scheduled_start' => '2026-06-13 16:00:00',
                'status' => 'upcoming'
            ),
            array(
                'video_id' => 'calendar-wec-saopaulo-2026',
                'channel_id' => 'FIAWEC',
                'channel_name' => 'FIA WEC',
                'title' => '6 Hours of São Paulo',
                'description' => '2026 FIA World Endurance Championship - Autódromo José Carlos Pace (Interlagos)',
                'scheduled_start' => '2026-07-12 11:00:00',
                'status' => 'upcoming'
            ),
            array(
                'video_id' => 'calendar-wec-cota-2026',
                'channel_id' => 'FIAWEC',
                'channel_name' => 'FIA WEC',
                'title' => 'Lone Star Le Mans',
                'description' => '2026 FIA World Endurance Championship - Circuit of The Americas',
                'scheduled_start' => '2026-09-06 12:00:00',
                'status' => 'upcoming'
            ),
            array(
                'video_id' => 'calendar-wec-fuji-2026',
                'channel_id' => 'FIAWEC',
                'channel_name' => 'FIA WEC',
                'title' => '6 Hours of Fuji',
                'description' => '2026 FIA World Endurance Championship - Fuji Speedway',
                'scheduled_start' => '2026-09-27 11:00:00',
                'status' => 'upcoming'
            ),
            array(
                'video_id' => 'calendar-wec-bahrain-2026',
                'channel_id' => 'FIAWEC',
                'channel_name' => 'FIA WEC',
                'title' => '8 Hours of Bahrain',
                'description' => '2026 FIA World Endurance Championship - Bahrain International Circuit',
                'scheduled_start' => '2026-11-07 14:00:00',
                'status' => 'upcoming'
            ),
        );
        
        $imported = 0;
        foreach ($calendars as $event) {
            // Check if already exists
            global $wpdb;
            $table = $wpdb->prefix . 'apex_notes_live_events';
            $exists = $wpdb->get_var($wpdb->prepare(
                "SELECT id FROM $table WHERE video_id = %s",
                $event['video_id']
            ));
            
            if (!$exists) {
                self::save_live_event($event);
                $imported++;
            }
        }
        
        return $imported;
    }
    
    /**
     * Add a manual calendar event
     */
    public static function add_manual_event($title, $channel_name, $scheduled_start, $description = '') {
        $video_id = 'manual-' . sanitize_title($title) . '-' . strtotime($scheduled_start);
        
        // Map channel name to channel ID
        $channel_map = array(
            'IMSA Official' => 'imsaofficial',
            'FIA WEC' => 'FIAWEC',
            'Creventic' => 'creventicmotorsportstv',
            'GT World' => 'GTWorld'
        );
        
        $channel_id = isset($channel_map[$channel_name]) ? $channel_map[$channel_name] : strtolower(str_replace(' ', '', $channel_name));
        
        return self::save_live_event(array(
            'video_id' => $video_id,
            'channel_id' => $channel_id,
            'channel_name' => $channel_name,
            'title' => $title,
            'description' => $description,
            'scheduled_start' => $scheduled_start,
            'status' => 'upcoming'
        ));
    }
    
    /**
     * Delete a calendar event
     */
    public static function delete_event($video_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'apex_notes_live_events';
        
        return $wpdb->delete($table, array('video_id' => $video_id));
    }
    
    /**
     * Seed car specifications with tank capacities
     */
    public static function seed_car_specifications() {
        global $wpdb;
        $table = $wpdb->prefix . 'apex_notes_car_specs';
        
        // Check if already seeded
        $count = $wpdb->get_var("SELECT COUNT(*) FROM $table");
        if ($count > 0) {
            return;
        }
        
        $cars = array(
            // Hypercar - LMDh (110L tanks)
            array('car_type' => 'Porsche 963', 'car_class' => 'Hyper', 'tank_capacity' => 110.0, 'manufacturer' => 'Porsche', 'hybrid_type' => 'LMDh'),
            array('car_type' => 'Cadillac V-Series.R', 'car_class' => 'Hyper', 'tank_capacity' => 110.0, 'manufacturer' => 'Cadillac', 'hybrid_type' => 'LMDh'),
            array('car_type' => 'BMW M Hybrid V8', 'car_class' => 'Hyper', 'tank_capacity' => 110.0, 'manufacturer' => 'BMW', 'hybrid_type' => 'LMDh'),
            array('car_type' => 'Alpine A424', 'car_class' => 'Hyper', 'tank_capacity' => 110.0, 'manufacturer' => 'Alpine', 'hybrid_type' => 'LMDh'),
            array('car_type' => 'Lamborghini SC63', 'car_class' => 'Hyper', 'tank_capacity' => 110.0, 'manufacturer' => 'Lamborghini', 'hybrid_type' => 'LMDh'),
            array('car_type' => 'Isotta Fraschini TIPO6', 'car_class' => 'Hyper', 'tank_capacity' => 110.0, 'manufacturer' => 'Isotta Fraschini', 'hybrid_type' => 'LMDh'),
            
            // Hypercar - LMH (90L tanks)
            array('car_type' => 'Ferrari 499P', 'car_class' => 'Hyper', 'tank_capacity' => 90.0, 'manufacturer' => 'Ferrari', 'hybrid_type' => 'LMH'),
            array('car_type' => 'Toyota GR010', 'car_class' => 'Hyper', 'tank_capacity' => 90.0, 'manufacturer' => 'Toyota', 'hybrid_type' => 'LMH'),
            array('car_type' => 'Peugeot 9x8', 'car_class' => 'Hyper', 'tank_capacity' => 90.0, 'manufacturer' => 'Peugeot', 'hybrid_type' => 'LMH'),
            array('car_type' => 'Aston Martin Valkyrie LMH', 'car_class' => 'Hyper', 'tank_capacity' => 90.0, 'manufacturer' => 'Aston Martin', 'hybrid_type' => 'LMH'),
            array('car_type' => 'Glickenhaus SCG007', 'car_class' => 'Hyper', 'tank_capacity' => 90.0, 'manufacturer' => 'Glickenhaus', 'hybrid_type' => 'LMH'),
            array('car_type' => 'Vanwall 680', 'car_class' => 'Hyper', 'tank_capacity' => 90.0, 'manufacturer' => 'Vanwall', 'hybrid_type' => 'LMH'),
            
            // LMP2 (75L tanks)
            array('car_type' => 'Oreca 07', 'car_class' => 'LMP2', 'tank_capacity' => 75.0, 'manufacturer' => 'Oreca', 'hybrid_type' => ''),
            
            // LMP3 (60L tanks)
            array('car_type' => 'Ginetta G61-LT-P325 Evo', 'car_class' => 'LMP3', 'tank_capacity' => 60.0, 'manufacturer' => 'Ginetta', 'hybrid_type' => ''),
            array('car_type' => 'Ligier JS P325', 'car_class' => 'LMP3', 'tank_capacity' => 60.0, 'manufacturer' => 'Ligier', 'hybrid_type' => ''),
            
            // LMGT3 (120L tanks)
            array('car_type' => 'McLaren 720S LMGT3 Evo', 'car_class' => 'GT3', 'tank_capacity' => 120.0, 'manufacturer' => 'McLaren', 'hybrid_type' => ''),
            array('car_type' => 'BMW M4 LMGT3', 'car_class' => 'GT3', 'tank_capacity' => 120.0, 'manufacturer' => 'BMW', 'hybrid_type' => ''),
            array('car_type' => 'Ferrari 296 LMGT3', 'car_class' => 'GT3', 'tank_capacity' => 120.0, 'manufacturer' => 'Ferrari', 'hybrid_type' => ''),
            array('car_type' => 'Porsche 911 GT3 R LMGT3', 'car_class' => 'GT3', 'tank_capacity' => 120.0, 'manufacturer' => 'Porsche', 'hybrid_type' => ''),
            array('car_type' => 'Ford Mustang LMGT3', 'car_class' => 'GT3', 'tank_capacity' => 120.0, 'manufacturer' => 'Ford', 'hybrid_type' => ''),
            array('car_type' => 'Mercedes-AMG LMGT3', 'car_class' => 'GT3', 'tank_capacity' => 120.0, 'manufacturer' => 'Mercedes-AMG', 'hybrid_type' => ''),
            array('car_type' => 'Lexus RCF LMGT3', 'car_class' => 'GT3', 'tank_capacity' => 120.0, 'manufacturer' => 'Lexus', 'hybrid_type' => ''),
            array('car_type' => 'Lamborghini Huracan LMGT3 Evo2', 'car_class' => 'GT3', 'tank_capacity' => 120.0, 'manufacturer' => 'Lamborghini', 'hybrid_type' => ''),
            array('car_type' => 'Aston Martin Vantage AMR LMGT3', 'car_class' => 'GT3', 'tank_capacity' => 120.0, 'manufacturer' => 'Aston Martin', 'hybrid_type' => ''),
            array('car_type' => 'Chevrolet Corvette Z06 LMGT3.R', 'car_class' => 'GT3', 'tank_capacity' => 120.0, 'manufacturer' => 'Chevrolet', 'hybrid_type' => ''),
            
            // GTE (90L tanks - legacy cars)
            array('car_type' => 'Porsche 911 RSR-19', 'car_class' => 'GTE', 'tank_capacity' => 90.0, 'manufacturer' => 'Porsche', 'hybrid_type' => ''),
            array('car_type' => 'Ferrari 488 GTE EVO', 'car_class' => 'GTE', 'tank_capacity' => 90.0, 'manufacturer' => 'Ferrari', 'hybrid_type' => ''),
            array('car_type' => 'Aston Martin Vantage AMR', 'car_class' => 'GTE', 'tank_capacity' => 90.0, 'manufacturer' => 'Aston Martin', 'hybrid_type' => ''),
            array('car_type' => 'Corvette C8.R GTE', 'car_class' => 'GTE', 'tank_capacity' => 90.0, 'manufacturer' => 'Chevrolet', 'hybrid_type' => ''),
        );
        
        foreach ($cars as $car) {
            $wpdb->insert($table, $car);
        }
    }
    
    /**
     * Get car specifications by car type
     */
    public static function get_car_specs($car_type = null) {
        global $wpdb;
        $table = $wpdb->prefix . 'apex_notes_car_specs';
        
        if ($car_type) {
            // First try exact match
            $result = $wpdb->get_row($wpdb->prepare(
                "SELECT * FROM $table WHERE car_type = %s",
                $car_type
            ), ARRAY_A);
            
            // If no exact match, try LIKE match (handles slight name variations)
            if (!$result) {
                $result = $wpdb->get_row($wpdb->prepare(
                    "SELECT * FROM $table WHERE car_type LIKE %s ORDER BY car_type LIMIT 1",
                    '%' . $wpdb->esc_like($car_type) . '%'
                ), ARRAY_A);
            }
            
            // If still no match, try matching without class suffix
            if (!$result) {
                // Extract base car name (e.g., "McLaren 720S" from "McLaren 720S LMGT3 Evo")
                $parts = explode(' ', $car_type);
                if (count($parts) >= 2) {
                    $base_name = $parts[0] . ' ' . $parts[1];
                    $result = $wpdb->get_row($wpdb->prepare(
                        "SELECT * FROM $table WHERE car_type LIKE %s ORDER BY car_type LIMIT 1",
                        '%' . $wpdb->esc_like($base_name) . '%'
                    ), ARRAY_A);
                }
            }
            
            return $result;
        }
        
        return $wpdb->get_results("SELECT * FROM $table ORDER BY car_class, car_type", ARRAY_A);
    }
    
    /**
     * Get tank capacity for a car type
     */
    public static function get_tank_capacity($car_type) {
        $specs = self::get_car_specs($car_type);
        if ($specs) {
            return floatval($specs['tank_capacity']);
        }
        
        // Fallback to class-based defaults if car not found
        $car_type_lower = strtolower($car_type);
        
        if (strpos($car_type_lower, 'hypercar') !== false || 
            strpos($car_type_lower, '499p') !== false || 
            strpos($car_type_lower, 'gr010') !== false ||
            strpos($car_type_lower, '963') !== false ||
            strpos($car_type_lower, '9x8') !== false ||
            strpos($car_type_lower, 'sc63') !== false) {
            // Hypercars: LMDh have 110L, LMH have 90L - use average
            return 100.0;
        }
        
        if (strpos($car_type_lower, 'gt3') !== false || 
            strpos($car_type_lower, 'lmgt3') !== false) {
            return 120.0; // LMGT3 tanks
        }
        
        if (strpos($car_type_lower, 'lmp2') !== false ||
            strpos($car_type_lower, 'oreca') !== false) {
            return 75.0; // LMP2 tanks
        }
        
        if (strpos($car_type_lower, 'lmp3') !== false ||
            strpos($car_type_lower, 'ligier') !== false ||
            strpos($car_type_lower, 'ginetta') !== false) {
            return 60.0; // LMP3 tanks
        }
        
        if (strpos($car_type_lower, 'gte') !== false ||
            strpos($car_type_lower, 'rsr') !== false ||
            strpos($car_type_lower, 'c8.r') !== false ||
            strpos($car_type_lower, '488 gte') !== false) {
            return 90.0; // GTE tanks
        }
        
        // Default fallback
        return 100.0;
    }
    
    /**
     * Save a fuel session from XML upload
     */
    public static function save_fuel_session($session_data) {
        global $wpdb;
        $table = $wpdb->prefix . 'apex_notes_fuel_sessions';
        
        // Check if table exists
        if ($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) {
            return array('error' => 'Fuel data tables not initialized. Please deactivate and reactivate the plugin.');
        }
        
        // Check for duplicate
        $exists = $wpdb->get_var($wpdb->prepare(
            "SELECT id FROM $table WHERE session_hash = %s",
            $session_data['session_hash']
        ));
        
        if ($exists) {
            return array('error' => 'Session already uploaded', 'session_id' => $exists);
        }
        
        // Filter data to only include valid columns
        $valid_columns = array(
            'session_hash', 'user_id', 'track_venue', 'track_course', 'car_type', 
            'car_class', 'session_type', 'session_date', 'game_version', 
            'fuel_mult', 'tire_mult', 'grid_position', 'finish_position',
            'total_laps', 'valid_laps',
            'avg_fuel_percent', 'avg_fuel_liters', 'min_fuel_percent', 'max_fuel_percent',
            'avg_ve_percent', 'min_ve_percent', 'max_ve_percent',
            'avg_lap_time', 'best_lap_time', 'pitstops', 'shared_with_community'
        );
        
        $filtered_data = array_intersect_key($session_data, array_flip($valid_columns));
        
        $result = $wpdb->insert($table, $filtered_data);
        
        if ($result === false) {
            return array('error' => 'Failed to save session: ' . $wpdb->last_error);
        }
        
        return array('success' => true, 'session_id' => $wpdb->insert_id);
    }
    
    /**
     * Save lap data for a fuel session
     */
    public static function save_fuel_lap($lap_data) {
        global $wpdb;
        $table = $wpdb->prefix . 'apex_notes_fuel_laps';
        
        return $wpdb->insert($table, $lap_data);
    }
    
    /**
     * Save multiple laps at once
     */
    public static function save_fuel_laps($laps) {
        global $wpdb;
        $table = $wpdb->prefix . 'apex_notes_fuel_laps';
        
        foreach ($laps as $lap) {
            $wpdb->insert($table, $lap);
        }
        
        return count($laps);
    }
    
    /**
     * Get fuel sessions for a user
     */
    public static function get_user_fuel_sessions($user_id, $limit = 20) {
        global $wpdb;
        $table = $wpdb->prefix . 'apex_notes_fuel_sessions';
        
        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table WHERE user_id = %d ORDER BY created_at DESC LIMIT %d",
            $user_id,
            $limit
        ), ARRAY_A);
    }
    
    /**
     * Get fuel session by ID
     */
    public static function get_fuel_session($session_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'apex_notes_fuel_sessions';
        
        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table WHERE id = %d",
            $session_id
        ), ARRAY_A);
    }
    
    /**
     * Get laps for a fuel session
     */
    public static function get_fuel_session_laps($session_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'apex_notes_fuel_laps';
        
        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table WHERE session_id = %d ORDER BY lap_num",
            $session_id
        ), ARRAY_A);
    }
    
    /**
     * Get community fuel averages for a track+car combination
     */
    public static function get_community_fuel_average($track_venue, $car_type) {
        global $wpdb;
        $table = $wpdb->prefix . 'apex_notes_fuel_averages';
        
        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table WHERE track_venue = %s AND car_type = %s",
            $track_venue,
            $car_type
        ), ARRAY_A);
    }
    
    /**
     * Get community tire average for a track+car combination
     */
    public static function get_tire_average($track_venue, $car_type, $compound = null) {
        global $wpdb;
        $table = $wpdb->prefix . 'apex_notes_tire_averages';
        
        if ($compound) {
            return $wpdb->get_row($wpdb->prepare(
                "SELECT * FROM $table WHERE track_venue = %s AND car_type = %s AND tire_compound = %s",
                $track_venue,
                $car_type,
                $compound
            ), ARRAY_A);
        }
        
        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table WHERE track_venue = %s AND car_type = %s ORDER BY sample_count DESC LIMIT 1",
            $track_venue,
            $car_type
        ), ARRAY_A);
    }
    
    /**
     * Get individual community sessions for a track+car combination
     */
    public static function get_community_individual_sessions($track_venue, $car_type, $limit = 50) {
        global $wpdb;
        $sessions_table = $wpdb->prefix . 'apex_notes_fuel_sessions';
        
        $results = $wpdb->get_results($wpdb->prepare(
            "SELECT 
                id,
                user_id,
                track_venue,
                car_type,
                car_class,
                session_date,
                session_type,
                fuel_mult,
                total_laps,
                valid_laps,
                pitstops,
                avg_fuel_percent,
                min_fuel_percent,
                max_fuel_percent,
                avg_ve_percent,
                avg_lap_time,
                best_lap_time,
                created_at
            FROM $sessions_table
            WHERE track_venue = %s 
                AND car_type = %s 
                AND shared_with_community = 1
                AND valid_laps > 0
            ORDER BY session_date DESC
            LIMIT %d",
            $track_venue,
            $car_type,
            $limit
        ), ARRAY_A);
        
        return $results;
    }
    
    /**
     * Get community fuel averages for a track+car broken down by fuel multiplier
     */
    public static function get_community_fuel_by_mult($track_venue, $car_type) {
        global $wpdb;
        $sessions_table = $wpdb->prefix . 'apex_notes_fuel_sessions';
        
        // Get averages grouped by fuel_mult
        $results = $wpdb->get_results($wpdb->prepare(
            "SELECT 
                fuel_mult,
                COUNT(*) as session_count,
                SUM(valid_laps) as total_laps,
                AVG(avg_fuel_liters) as avg_fuel_liters,
                MIN(avg_fuel_liters) as min_fuel_liters,
                MAX(avg_fuel_liters) as max_fuel_liters,
                AVG(avg_fuel_percent) as avg_fuel_percent,
                AVG(avg_ve_percent) as avg_ve_percent,
                AVG(avg_lap_time) as avg_lap_time,
                MIN(best_lap_time) as best_lap_time,
                car_class
            FROM $sessions_table
            WHERE track_venue = %s 
                AND car_type = %s 
                AND shared_with_community = 1
                AND valid_laps > 0
            GROUP BY fuel_mult
            ORDER BY fuel_mult ASC",
            $track_venue,
            $car_type
        ), ARRAY_A);
        
        return $results;
    }
    
    /**
     * Get community fuel averages for all cars on a track broken down by fuel multiplier
     */
    public static function get_track_community_fuel($track_venue) {
        global $wpdb;
        $sessions_table = $wpdb->prefix . 'apex_notes_fuel_sessions';
        
        // Get averages grouped by car and fuel_mult
        $results = $wpdb->get_results($wpdb->prepare(
            "SELECT 
                car_type,
                car_class,
                fuel_mult,
                COUNT(*) as session_count,
                SUM(valid_laps) as total_laps,
                AVG(avg_fuel_liters) as avg_fuel_liters,
                MIN(avg_fuel_liters) as min_fuel_liters,
                MAX(avg_fuel_liters) as max_fuel_liters,
                AVG(avg_fuel_percent) as avg_fuel_percent,
                AVG(avg_ve_percent) as avg_ve_percent,
                AVG(avg_lap_time) as avg_lap_time,
                MIN(best_lap_time) as best_lap_time
            FROM $sessions_table
            WHERE track_venue = %s 
                AND shared_with_community = 1
                AND valid_laps > 0
            GROUP BY car_type, fuel_mult
            ORDER BY car_class, car_type, fuel_mult ASC",
            $track_venue
        ), ARRAY_A);
        
        return $results;
    }
    
    /**
     * Get all community fuel averages for a track
     */
    public static function get_track_fuel_averages($track_venue) {
        global $wpdb;
        $table = $wpdb->prefix . 'apex_notes_fuel_averages';
        
        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table WHERE track_venue = %s ORDER BY car_class, car_type",
            $track_venue
        ), ARRAY_A);
    }
    
    /**
     * Get all community fuel averages for a car
     */
    public static function get_car_fuel_averages($car_type) {
        global $wpdb;
        $table = $wpdb->prefix . 'apex_notes_fuel_averages';
        
        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table WHERE car_type = %s ORDER BY track_venue",
            $car_type
        ), ARRAY_A);
    }
    
    /**
     * Update community averages for a track+car combination
     * This recalculates from all shared sessions
     */
    public static function update_community_average($track_venue, $car_type) {
        global $wpdb;
        $sessions_table = $wpdb->prefix . 'apex_notes_fuel_sessions';
        $laps_table = $wpdb->prefix . 'apex_notes_fuel_laps';
        $averages_table = $wpdb->prefix . 'apex_notes_fuel_averages';
        
        // Get all valid laps from shared sessions for this track+car
        $stats = $wpdb->get_row($wpdb->prepare(
            "SELECT 
                COUNT(DISTINCT s.id) as sample_count,
                COUNT(l.id) as lap_count,
                AVG(l.fuel_used) as avg_fuel_percent,
                MIN(l.fuel_used) as min_fuel_percent,
                MAX(l.fuel_used) as max_fuel_percent,
                STDDEV(l.fuel_used) as std_dev_fuel,
                AVG(l.ve_used) as avg_ve_percent,
                MIN(l.ve_used) as min_ve_percent,
                MAX(l.ve_used) as max_ve_percent,
                AVG(l.lap_time) as avg_lap_time,
                s.car_class
            FROM $sessions_table s
            JOIN $laps_table l ON s.id = l.session_id
            WHERE s.track_venue = %s 
                AND s.car_type = %s 
                AND s.shared_with_community = 1
                AND l.is_valid = 1
            GROUP BY s.track_venue, s.car_type",
            $track_venue,
            $car_type
        ), ARRAY_A);
        
        if (!$stats || $stats['sample_count'] == 0) {
            return false;
        }
        
        // Get average pitstops and fuel_mult info from sessions
        $session_stats = $wpdb->get_row($wpdb->prepare(
            "SELECT 
                AVG(pitstops) as avg_pitstops,
                MIN(fuel_mult) as min_fuel_mult,
                MAX(fuel_mult) as max_fuel_mult,
                AVG(fuel_mult) as avg_fuel_mult
            FROM $sessions_table
            WHERE track_venue = %s 
                AND car_type = %s 
                AND shared_with_community = 1",
            $track_venue,
            $car_type
        ), ARRAY_A);
        
        // Determine fuel_mult note
        $fuel_mult_note = '1x';
        if ($session_stats) {
            $min_mult = floatval($session_stats['min_fuel_mult']);
            $max_mult = floatval($session_stats['max_fuel_mult']);
            $avg_mult = floatval($session_stats['avg_fuel_mult']);
            
            if ($min_mult == $max_mult && $min_mult != 1.0) {
                // All sessions have same non-1x multiplier
                $fuel_mult_note = $min_mult . 'x';
            } elseif ($min_mult != $max_mult) {
                // Mixed multipliers
                $fuel_mult_note = 'Mixed';
            }
        }
        
        // Get tank capacity
        $tank_capacity = self::get_tank_capacity($car_type);
        if (!$tank_capacity) {
            $tank_capacity = 100; // Default fallback
        }
        
        // Convert percentages to liters
        $avg_fuel_liters = $stats['avg_fuel_percent'] * $tank_capacity;
        $min_fuel_liters = $stats['min_fuel_percent'] * $tank_capacity;
        $max_fuel_liters = $stats['max_fuel_percent'] * $tank_capacity;
        $std_dev_fuel = $stats['std_dev_fuel'] * $tank_capacity;
        
        // Check if record exists
        $exists = $wpdb->get_var($wpdb->prepare(
            "SELECT id FROM $averages_table WHERE track_venue = %s AND car_type = %s",
            $track_venue,
            $car_type
        ));
        
        $data = array(
            'track_venue' => $track_venue,
            'car_type' => $car_type,
            'car_class' => $stats['car_class'],
            'sample_count' => $stats['sample_count'],
            'lap_count' => $stats['lap_count'],
            'avg_fuel_liters' => $avg_fuel_liters,
            'min_fuel_liters' => $min_fuel_liters,
            'max_fuel_liters' => $max_fuel_liters,
            'std_dev_fuel' => $std_dev_fuel,
            'avg_ve_percent' => $stats['avg_ve_percent'],
            'min_ve_percent' => $stats['min_ve_percent'],
            'max_ve_percent' => $stats['max_ve_percent'],
            'avg_lap_time' => $stats['avg_lap_time'],
            'avg_pitstops' => $session_stats ? $session_stats['avg_pitstops'] : null,
            'fuel_mult_note' => $fuel_mult_note
        );
        
        if ($exists) {
            $wpdb->update($averages_table, $data, array('id' => $exists));
        } else {
            $wpdb->insert($averages_table, $data);
        }
        
        return true;
    }
    
    /**
     * Get all community fuel averages with sample counts
     */
    public static function get_all_community_averages($min_samples = 1) {
        global $wpdb;
        $table = $wpdb->prefix . 'apex_notes_fuel_averages';
        
        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table WHERE sample_count >= %d ORDER BY track_venue, car_class, car_type",
            $min_samples
        ), ARRAY_A);
    }
    
    /**
     * Recalculate all community averages from shared sessions
     * Called on plugin activation/update to ensure new fields are populated
     */
    public static function recalculate_all_community_averages() {
        global $wpdb;
        $sessions_table = $wpdb->prefix . 'apex_notes_fuel_sessions';
        
        // Get all unique track+car combinations from shared sessions
        $combinations = $wpdb->get_results(
            "SELECT DISTINCT track_venue, car_type 
             FROM $sessions_table 
             WHERE shared_with_community = 1",
            ARRAY_A
        );
        
        if (!$combinations) {
            return 0;
        }
        
        $count = 0;
        foreach ($combinations as $combo) {
            $result = self::update_community_average($combo['track_venue'], $combo['car_type']);
            if ($result) {
                $count++;
            }
        }
        
        return $count;
    }
    
    /**
     * Mark a session as shared with community
     */
    public static function share_fuel_session($session_id, $user_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'apex_notes_fuel_sessions';
        
        // Verify ownership
        $session = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table WHERE id = %d AND user_id = %d",
            $session_id,
            $user_id
        ), ARRAY_A);
        
        if (!$session) {
            return false;
        }
        
        // Update shared status
        $wpdb->update(
            $table,
            array('shared_with_community' => 1),
            array('id' => $session_id)
        );
        
        // Recalculate community averages
        self::update_community_average($session['track_venue'], $session['car_type']);
        
        return true;
    }
    
    /**
     * Unshare a session from community
     */
    public static function unshare_fuel_session($session_id, $user_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'apex_notes_fuel_sessions';
        
        // Verify ownership
        $session = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table WHERE id = %d AND user_id = %d",
            $session_id,
            $user_id
        ), ARRAY_A);
        
        if (!$session) {
            return false;
        }
        
        // Update shared status
        $wpdb->update(
            $table,
            array('shared_with_community' => 0),
            array('id' => $session_id)
        );
        
        // Recalculate community averages
        self::update_community_average($session['track_venue'], $session['car_type']);
        
        return true;
    }
    
    /**
     * Delete a fuel session and its laps
     */
    public static function delete_fuel_session($session_id, $user_id) {
        global $wpdb;
        $sessions_table = $wpdb->prefix . 'apex_notes_fuel_sessions';
        $laps_table = $wpdb->prefix . 'apex_notes_fuel_laps';
        
        // Verify ownership
        $session = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $sessions_table WHERE id = %d AND user_id = %d",
            $session_id,
            $user_id
        ), ARRAY_A);
        
        if (!$session) {
            return false;
        }
        
        $track_venue = $session['track_venue'];
        $car_type = $session['car_type'];
        $was_shared = $session['shared_with_community'];
        
        // Delete laps first
        $wpdb->delete($laps_table, array('session_id' => $session_id));
        
        // Delete session
        $wpdb->delete($sessions_table, array('id' => $session_id));
        
        // Recalculate community averages if was shared
        if ($was_shared) {
            self::update_community_average($track_venue, $car_type);
        }
        
        return true;
    }
    
    // ========================================
    // LIVE BROADCAST FUNCTIONS
    // ========================================
    
    /**
     * Create a new live broadcast session
     */
    public static function create_live_broadcast($user_id, $session_name = '', $stream_url = '', $is_premium = false) {
        global $wpdb;
        $table = $wpdb->prefix . 'apex_notes_live_broadcasts';
        
        // Generate unique broadcast key
        $broadcast_key = wp_generate_password(32, false, false);
        
        // End any existing active broadcasts for this user
        self::end_user_broadcasts($user_id);
        
        // Detect stream type
        $stream_type = self::detect_stream_type($stream_url);
        
        $result = $wpdb->insert($table, array(
            'user_id' => $user_id,
            'broadcast_key' => $broadcast_key,
            'session_name' => sanitize_text_field($session_name),
            'stream_url' => esc_url_raw($stream_url),
            'stream_type' => $stream_type,
            'is_premium' => $is_premium ? 1 : 0,
            'is_active' => 1
        ));
        
        if ($result === false) {
            return false;
        }
        
        $broadcast_id = $wpdb->insert_id;
        
        // Create initial telemetry record
        $telemetry_table = $wpdb->prefix . 'apex_notes_live_telemetry';
        $wpdb->insert($telemetry_table, array(
            'broadcast_id' => $broadcast_id,
            'positions_json' => json_encode(array())
        ));
        
        return array(
            'broadcast_id' => $broadcast_id,
            'broadcast_key' => $broadcast_key
        );
    }
    
    /**
     * Detect stream type from URL
     */
    public static function detect_stream_type($url) {
        if (empty($url)) return '';
        
        if (strpos($url, 'youtube.com') !== false || strpos($url, 'youtu.be') !== false) {
            return 'youtube';
        }
        if (strpos($url, 'twitch.tv') !== false) {
            return 'twitch';
        }
        return 'other';
    }
    
    /**
     * End all active broadcasts for a user
     */
    public static function end_user_broadcasts($user_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'apex_notes_live_broadcasts';
        
        return $wpdb->update(
            $table,
            array('is_active' => 0),
            array('user_id' => $user_id, 'is_active' => 1)
        );
    }
    
    /**
     * End a specific broadcast
     */
    public static function end_broadcast($broadcast_id, $user_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'apex_notes_live_broadcasts';
        
        return $wpdb->update(
            $table,
            array('is_active' => 0),
            array('id' => $broadcast_id, 'user_id' => $user_id)
        );
    }
    
    /**
     * Get broadcast by key (for spectators)
     */
    public static function get_broadcast_by_key($broadcast_key) {
        global $wpdb;
        $table = $wpdb->prefix . 'apex_notes_live_broadcasts';
        
        return $wpdb->get_row($wpdb->prepare(
            "SELECT b.*, u.display_name as broadcaster_name 
             FROM $table b 
             LEFT JOIN {$wpdb->users} u ON b.user_id = u.ID
             WHERE b.broadcast_key = %s AND b.is_active = 1",
            $broadcast_key
        ), ARRAY_A);
    }
    
    /**
     * Get user's active broadcast
     */
    public static function get_user_active_broadcast($user_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'apex_notes_live_broadcasts';
        
        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table WHERE user_id = %d AND is_active = 1 ORDER BY id DESC LIMIT 1",
            $user_id
        ), ARRAY_A);
    }
    
    /**
     * Update live telemetry data (called by SimHub plugin)
     */
    public static function update_live_telemetry($broadcast_key, $telemetry_data) {
        global $wpdb;
        $broadcasts_table = $wpdb->prefix . 'apex_notes_live_broadcasts';
        $telemetry_table = $wpdb->prefix . 'apex_notes_live_telemetry';
        
        // Get broadcast
        $broadcast = $wpdb->get_row($wpdb->prepare(
            "SELECT id, is_active FROM $broadcasts_table WHERE broadcast_key = %s",
            $broadcast_key
        ), ARRAY_A);
        
        if (!$broadcast || !$broadcast['is_active']) {
            return false;
        }
        
        // Update track name if provided
        if (!empty($telemetry_data['track_name'])) {
            $wpdb->update(
                $broadcasts_table,
                array('track_name' => sanitize_text_field($telemetry_data['track_name'])),
                array('id' => $broadcast['id'])
            );
        }
        
        // Prepare telemetry update
        $update_data = array(
            'session_time' => isset($telemetry_data['session_time']) ? sanitize_text_field($telemetry_data['session_time']) : '0:00:00',
            'current_lap' => isset($telemetry_data['current_lap']) ? intval($telemetry_data['current_lap']) : 0,
            'total_laps' => isset($telemetry_data['total_laps']) ? intval($telemetry_data['total_laps']) : 0,
            'player_position' => isset($telemetry_data['player_position']) ? intval($telemetry_data['player_position']) : 0,
            'player_car' => isset($telemetry_data['player_car']) ? sanitize_text_field($telemetry_data['player_car']) : '',
            'player_class' => isset($telemetry_data['player_class']) ? sanitize_text_field($telemetry_data['player_class']) : '',
            'fuel_remaining' => isset($telemetry_data['fuel_remaining']) ? floatval($telemetry_data['fuel_remaining']) : 0,
            'fuel_percent' => isset($telemetry_data['fuel_percent']) ? floatval($telemetry_data['fuel_percent']) : 0,
            'fuel_laps_remaining' => isset($telemetry_data['fuel_laps_remaining']) ? floatval($telemetry_data['fuel_laps_remaining']) : 0,
            'tire_fl_wear' => isset($telemetry_data['tire_fl_wear']) ? floatval($telemetry_data['tire_fl_wear']) : 100,
            'tire_fr_wear' => isset($telemetry_data['tire_fr_wear']) ? floatval($telemetry_data['tire_fr_wear']) : 100,
            'tire_rl_wear' => isset($telemetry_data['tire_rl_wear']) ? floatval($telemetry_data['tire_rl_wear']) : 100,
            'tire_rr_wear' => isset($telemetry_data['tire_rr_wear']) ? floatval($telemetry_data['tire_rr_wear']) : 100,
            'tire_fl_temp' => isset($telemetry_data['tire_fl_temp']) ? floatval($telemetry_data['tire_fl_temp']) : 0,
            'tire_fr_temp' => isset($telemetry_data['tire_fr_temp']) ? floatval($telemetry_data['tire_fr_temp']) : 0,
            'tire_rl_temp' => isset($telemetry_data['tire_rl_temp']) ? floatval($telemetry_data['tire_rl_temp']) : 0,
            'tire_rr_temp' => isset($telemetry_data['tire_rr_temp']) ? floatval($telemetry_data['tire_rr_temp']) : 0,
            'last_lap_time' => isset($telemetry_data['last_lap_time']) ? sanitize_text_field($telemetry_data['last_lap_time']) : '',
            'best_lap_time' => isset($telemetry_data['best_lap_time']) ? sanitize_text_field($telemetry_data['best_lap_time']) : '',
            'gap_ahead' => isset($telemetry_data['gap_ahead']) ? sanitize_text_field($telemetry_data['gap_ahead']) : '',
            'gap_behind' => isset($telemetry_data['gap_behind']) ? sanitize_text_field($telemetry_data['gap_behind']) : '',
            'flag_status' => isset($telemetry_data['flag_status']) ? sanitize_text_field($telemetry_data['flag_status']) : 'green',
            'flag_sector' => isset($telemetry_data['flag_sector']) ? intval($telemetry_data['flag_sector']) : 0,
            'penalties' => isset($telemetry_data['penalties']) ? sanitize_text_field($telemetry_data['penalties']) : '',
            'positions_json' => isset($telemetry_data['positions']) ? wp_json_encode($telemetry_data['positions']) : '[]'
        );
        
        // Check if telemetry record exists
        $exists = $wpdb->get_var($wpdb->prepare(
            "SELECT id FROM $telemetry_table WHERE broadcast_id = %d",
            $broadcast['id']
        ));
        
        if ($exists) {
            $wpdb->update($telemetry_table, $update_data, array('broadcast_id' => $broadcast['id']));
        } else {
            $update_data['broadcast_id'] = $broadcast['id'];
            $wpdb->insert($telemetry_table, $update_data);
        }
        
        return true;
    }
    
    /**
     * Get live telemetry for spectators
     */
    public static function get_live_telemetry($broadcast_key) {
        global $wpdb;
        $broadcasts_table = $wpdb->prefix . 'apex_notes_live_broadcasts';
        $telemetry_table = $wpdb->prefix . 'apex_notes_live_telemetry';
        
        $result = $wpdb->get_row($wpdb->prepare(
            "SELECT t.*, b.session_name, b.track_name, b.stream_url, b.stream_type, b.is_premium, b.is_active, b.user_id,
                    u.display_name as broadcaster_name
             FROM $broadcasts_table b
             LEFT JOIN $telemetry_table t ON b.id = t.broadcast_id
             LEFT JOIN {$wpdb->users} u ON b.user_id = u.ID
             WHERE b.broadcast_key = %s",
            $broadcast_key
        ), ARRAY_A);
        
        if ($result && !empty($result['positions_json'])) {
            $result['positions'] = json_decode($result['positions_json'], true);
            unset($result['positions_json']);
        }
        
        // Get affiliate links for this broadcaster
        if ($result && !empty($result['user_id'])) {
            $result['affiliate_links'] = self::get_user_affiliate_links($result['user_id']);
        }
        
        return $result;
    }
    
    /**
     * Get all active broadcasts (for listing)
     */
    public static function get_active_broadcasts() {
        global $wpdb;
        $table = $wpdb->prefix . 'apex_notes_live_broadcasts';
        
        return $wpdb->get_results(
            "SELECT b.*, u.display_name as broadcaster_name 
             FROM $table b 
             LEFT JOIN {$wpdb->users} u ON b.user_id = u.ID
             WHERE b.is_active = 1 
             ORDER BY b.created_at DESC",
            ARRAY_A
        );
    }
    
    /**
     * Update broadcast stream URL
     */
    public static function update_broadcast_stream($broadcast_id, $user_id, $stream_url) {
        global $wpdb;
        $table = $wpdb->prefix . 'apex_notes_live_broadcasts';
        
        $stream_type = self::detect_stream_type($stream_url);
        
        return $wpdb->update(
            $table,
            array(
                'stream_url' => esc_url_raw($stream_url),
                'stream_type' => $stream_type
            ),
            array('id' => $broadcast_id, 'user_id' => $user_id)
        );
    }
    
    // ========================================
    // AFFILIATE LINK FUNCTIONS
    // ========================================
    
    /**
     * Get user's affiliate links
     */
    public static function get_user_affiliate_links($user_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'apex_notes_affiliate_links';
        
        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table WHERE user_id = %d AND is_active = 1 ORDER BY display_order ASC, id ASC",
            $user_id
        ), ARRAY_A);
    }
    
    /**
     * Add affiliate link
     */
    public static function add_affiliate_link($user_id, $link_type, $label, $url) {
        global $wpdb;
        $table = $wpdb->prefix . 'apex_notes_affiliate_links';
        
        // Limit to 10 links per user
        $count = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table WHERE user_id = %d AND is_active = 1",
            $user_id
        ));
        
        if ($count >= 10) {
            return false;
        }
        
        // Get next display order
        $max_order = $wpdb->get_var($wpdb->prepare(
            "SELECT MAX(display_order) FROM $table WHERE user_id = %d",
            $user_id
        ));
        
        $result = $wpdb->insert($table, array(
            'user_id' => $user_id,
            'link_type' => sanitize_text_field($link_type),
            'label' => sanitize_text_field($label),
            'url' => esc_url_raw($url),
            'display_order' => ($max_order ? $max_order + 1 : 0),
            'is_active' => 1
        ));
        
        if ($result === false) {
            return false;
        }
        
        return $wpdb->insert_id;
    }
    
    /**
     * Remove affiliate link
     */
    public static function remove_affiliate_link($link_id, $user_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'apex_notes_affiliate_links';
        
        return $wpdb->delete($table, array(
            'id' => $link_id,
            'user_id' => $user_id
        ));
    }
    
    /**
     * Update affiliate link order
     */
    public static function update_affiliate_link_order($user_id, $link_ids) {
        global $wpdb;
        $table = $wpdb->prefix . 'apex_notes_affiliate_links';
        
        foreach ($link_ids as $order => $link_id) {
            $wpdb->update(
                $table,
                array('display_order' => $order),
                array('id' => intval($link_id), 'user_id' => $user_id)
            );
        }
        
        return true;
    }
    
    /**
     * Get user's active livery
     */
    public static function get_user_livery($user_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'apex_notes_liveries';
        
        // Clean up expired liveries first
        self::cleanup_expired_liveries();
        
        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table WHERE user_id = %d AND expires_at > NOW() ORDER BY created_at DESC LIMIT 1",
            $user_id
        ), ARRAY_A);
    }
    
    /**
     * Get livery by share token
     */
    public static function get_livery_by_token($token) {
        global $wpdb;
        $table = $wpdb->prefix . 'apex_notes_liveries';
        
        return $wpdb->get_row($wpdb->prepare(
            "SELECT l.*, u.display_name as author_name 
             FROM $table l 
             LEFT JOIN {$wpdb->users} u ON l.user_id = u.ID 
             WHERE l.share_token = %s AND l.expires_at > NOW()",
            $token
        ), ARRAY_A);
    }
    
    /**
     * Create new livery
     */
    public static function create_livery($user_id, $data) {
        global $wpdb;
        $table = $wpdb->prefix . 'apex_notes_liveries';
        
        // Delete any existing livery for this user
        self::delete_user_livery($user_id);
        
        // Generate unique share token
        $share_token = wp_generate_password(32, false);
        
        // Set expiry to 48 hours from now
        $expires_at = date('Y-m-d H:i:s', strtotime('+48 hours'));
        
        $result = $wpdb->insert($table, array(
            'user_id' => $user_id,
            'livery_name' => sanitize_text_field($data['livery_name']),
            'car_class' => sanitize_text_field($data['car_class'] ?? ''),
            'car_id' => sanitize_text_field($data['car_id'] ?? ''),
            'file_1_name' => sanitize_file_name($data['file_1_name']),
            'file_1_path' => $data['file_1_path'],
            'file_2_name' => sanitize_file_name($data['file_2_name']),
            'file_2_path' => $data['file_2_path'],
            'share_token' => $share_token,
            'uploader_ip' => sanitize_text_field($data['uploader_ip'] ?? ''),
            'uploader_discord_id' => sanitize_text_field($data['uploader_discord_id'] ?? ''),
            'legal_agreed' => intval($data['legal_agreed'] ?? 0),
            'expires_at' => $expires_at
        ));
        
        if ($result === false) {
            return false;
        }
        
        return array(
            'id' => $wpdb->insert_id,
            'share_token' => $share_token,
            'expires_at' => $expires_at
        );
    }
    
    /**
     * Delete user's livery
     */
    public static function delete_user_livery($user_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'apex_notes_liveries';
        
        // Get livery to delete files
        $livery = self::get_user_livery($user_id);
        if ($livery) {
            // Delete physical files
            if (!empty($livery['file_1_path']) && file_exists($livery['file_1_path'])) {
                @unlink($livery['file_1_path']);
            }
            if (!empty($livery['file_2_path']) && file_exists($livery['file_2_path'])) {
                @unlink($livery['file_2_path']);
            }
        }
        
        return $wpdb->delete($table, array('user_id' => $user_id));
    }
    
    /**
     * Increment livery download count
     */
    public static function increment_livery_downloads($livery_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'apex_notes_liveries';
        
        return $wpdb->query($wpdb->prepare(
            "UPDATE $table SET download_count = download_count + 1 WHERE id = %d",
            $livery_id
        ));
    }
    
    /**
     * Cleanup expired liveries
     */
    public static function cleanup_expired_liveries() {
        global $wpdb;
        $table = $wpdb->prefix . 'apex_notes_liveries';
        
        // Get expired liveries
        $expired = $wpdb->get_results(
            "SELECT * FROM $table WHERE expires_at <= NOW()",
            ARRAY_A
        );
        
        // Delete files
        foreach ($expired as $livery) {
            if (!empty($livery['file_1_path']) && file_exists($livery['file_1_path'])) {
                @unlink($livery['file_1_path']);
            }
            if (!empty($livery['file_2_path']) && file_exists($livery['file_2_path'])) {
                @unlink($livery['file_2_path']);
            }
        }
        
        // Delete records
        return $wpdb->query("DELETE FROM $table WHERE expires_at <= NOW()");
    }
    
    /**
     * Get all liveries for admin view
     */
    public static function get_all_liveries() {
        global $wpdb;
        $table = $wpdb->prefix . 'apex_notes_liveries';
        $users_table = $wpdb->users;
        
        return $wpdb->get_results(
            "SELECT l.*, u.display_name as author_name, u.user_email 
             FROM $table l 
             LEFT JOIN $users_table u ON l.user_id = u.ID 
             ORDER BY l.created_at DESC",
            ARRAY_A
        );
    }
}
