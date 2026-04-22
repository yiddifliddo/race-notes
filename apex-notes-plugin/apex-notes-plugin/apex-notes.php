<?php
/**
 * Plugin Name: Apex Notes - Le Mans Ultimate Track Notes
 * Plugin URI: https://apexnotes.racing
 * Description: A community-driven platform for sharing detailed racing track notes, braking zones, and racing lines for Le Mans Ultimate sim racing.
 * Version: 1.19.2
 * Author: Apex Notes Team
 * Author URI: https://apexnotes.racing
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: apex-notes
 * Domain Path: /languages
 */

// =============================================================================
// CRITICAL: Handle OAuth redirect BEFORE WordPress can add any compression
// =============================================================================
if (isset($_GET['apex_oauth']) && isset($_GET['code'])) {
    // We need WordPress to be loaded for database access, so just set a flag
    // and disable output buffering as much as possible
    define('APEX_OAUTH_PENDING', true);
    
    // Disable compression at PHP level
    @ini_set('zlib.output_compression', 0);
    @ini_set('output_buffering', 0);
    
    if (function_exists('apache_setenv')) {
        @apache_setenv('no-gzip', '1');
    }
}

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Plugin constants
define('APEX_NOTES_VERSION', '1.19.2');
define('APEX_NOTES_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('APEX_NOTES_PLUGIN_URL', plugin_dir_url(__FILE__));
define('APEX_NOTES_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Main Apex Notes Plugin Class
 */
class Apex_Notes {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->includes();
        $this->init_hooks();
    }
    
    private function includes() {
        require_once APEX_NOTES_PLUGIN_DIR . 'includes/class-apex-notes-data.php';
        require_once APEX_NOTES_PLUGIN_DIR . 'includes/class-apex-notes-db.php';
        require_once APEX_NOTES_PLUGIN_DIR . 'includes/class-apex-notes-ajax.php';
        require_once APEX_NOTES_PLUGIN_DIR . 'includes/class-apex-notes-shortcode.php';
        require_once APEX_NOTES_PLUGIN_DIR . 'includes/class-apex-notes-profanity.php';
        require_once APEX_NOTES_PLUGIN_DIR . 'includes/class-apex-notes-auth.php';
        require_once APEX_NOTES_PLUGIN_DIR . 'includes/class-apex-notes-xml-parser.php';
        
        if (is_admin()) {
            require_once APEX_NOTES_PLUGIN_DIR . 'admin/class-apex-notes-admin.php';
        }
    }
    
    private function init_hooks() {
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
        
        // Initialize Auth EARLY for OAuth callback handling (before plugins_loaded priority 1)
        add_action('plugins_loaded', array($this, 'init_auth_early'), 0);
        
        add_action('plugins_loaded', array($this, 'load_textdomain'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_public_assets'));
        add_action('init', array($this, 'init'));
        
        // Allow TGA file uploads for liveries
        add_filter('upload_mimes', array($this, 'allow_tga_uploads'));
        
        // Cron for cleaning up expired liveries (runs hourly)
        add_action('apex_notes_cleanup_liveries', array($this, 'cleanup_liveries_cron'));
        
        // Schedule livery cleanup if not already scheduled
        if (!wp_next_scheduled('apex_notes_cleanup_liveries')) {
            wp_schedule_event(time(), 'hourly', 'apex_notes_cleanup_liveries');
        }
        
        // Cron for cleaning up old stewards reports (runs daily)
        add_action('apex_notes_cleanup_stewards_reports', array($this, 'cleanup_stewards_reports_cron'));
        
        // Schedule stewards reports cleanup if not already scheduled
        if (!wp_next_scheduled('apex_notes_cleanup_stewards_reports')) {
            wp_schedule_event(time(), 'daily', 'apex_notes_cleanup_stewards_reports');
        }
    }
    
    /**
     * Allow TGA file uploads
     */
    public function allow_tga_uploads($mimes) {
        $mimes['tga'] = 'image/x-tga';
        return $mimes;
    }
    
    /**
     * Cleanup expired liveries cron job
     */
    public function cleanup_liveries_cron() {
        Apex_Notes_DB::cleanup_expired_liveries();
    }
    
    /**
     * Cleanup old stewards reports (90+ days) cron job
     */
    public function cleanup_stewards_reports_cron() {
        global $wpdb;
        $table = $wpdb->prefix . 'apex_notes_stewards_reports';
        $wpdb->query(
            "DELETE FROM $table WHERE created_at < DATE_SUB(NOW(), INTERVAL 90 DAY)"
        );
    }
    
    public function init_auth_early() {
        // Initialize Auth early so OAuth callback can run at plugins_loaded priority 1
        Apex_Notes_Auth::get_instance();
    }
    
    public function init() {
        // Initialize components
        Apex_Notes_Ajax::get_instance();
        Apex_Notes_Shortcode::get_instance();
        
        if (is_admin()) {
            Apex_Notes_Admin::get_instance();
        }
        
        // Add rewrite rules for SPA URLs
        $this->add_rewrite_rules();
        
        // Prevent canonical redirect for livery URLs
        add_filter('redirect_canonical', array($this, 'prevent_livery_redirect'), 10, 2);
        
        // Check if we need to run updates (on version update)
        $stored_version = get_option('apex_notes_version', '0');
        if (version_compare($stored_version, APEX_NOTES_VERSION, '<')) {
            flush_rewrite_rules();

            // Re-run table creation to add any new tables / columns
            Apex_Notes_DB::create_tables();

            // Update fuel cars list to correct names (v1.16.6+)
            $this->update_fuel_cars_list();

            // v1.19: community fuel averages are now bucketed by fuel_mult and
            // use median + IQR trimming. Rebuild from shared sessions once.
            if (version_compare($stored_version, '1.19.0', '<')) {
                Apex_Notes_DB::recalculate_all_community_averages();
            }

            // v1.19.2: tire averages now refresh on upload; backfill once for
            // existing fuel sessions so the Tire Data page reflects them.
            if (version_compare($stored_version, '1.19.2', '<')) {
                Apex_Notes_DB::recalculate_all_tire_averages();
            }

            update_option('apex_notes_version', APEX_NOTES_VERSION);
        }
    }
    
    /**
     * Update fuel cars list with correct car names from LMU XML files
     */
    private function update_fuel_cars_list() {
        $correct_cars = array(
            'Hypercar' => array(
                'Ferrari 499P',
                'Porsche 963',
                'Toyota GR010',
                'Cadillac V-Series.R',
                'Peugeot 9x8',
                'BMW M Hybrid V8',
                'Alpine A424',
                'Lamborghini SC63',
                'Isotta Fraschini TIPO6',
                'Glickenhaus SCG007',
                'Aston Martin Valkyrie LMH',
                'Vanwall 680'
            ),
            'LMGT3' => array(
                'McLaren 720S LMGT3 Evo',
                'BMW M4 LMGT3',
                'Porsche 911 GT3 R LMGT3',
                'Ferrari 296 LMGT3',
                'Ford Mustang LMGT3',
                'Mercedes-AMG LMGT3',
                'Lamborghini Huracan LMGT3 Evo2',
                'Lexus RCF LMGT3',
                'Aston Martin Vantage AMR LMGT3',
                'Chevrolet Corvette Z06 LMGT3.R'
            ),
            'LMP2' => array(
                'Oreca 07'
            ),
            'LMP3' => array(
                'Ginetta G61-LT-P325 Evo',
                'Ligier JS P325'
            ),
            'GTE' => array(
                'Porsche 911 RSR-19',
                'Ferrari 488 GTE EVO',
                'Corvette C8.R GTE',
                'Aston Martin Vantage AMR'
            )
        );
        
        // Always update to the correct list on plugin update
        update_option('apex_notes_fuel_cars', $correct_cars);
    }
    
    /**
     * Prevent canonical redirect for livery URLs
     */
    public function prevent_livery_redirect($redirect_url, $requested_url) {
        $request_uri = $_SERVER['REQUEST_URI'] ?? '';
        
        // If this is a livery URL, don't redirect
        if (preg_match('/\/livery\/([a-zA-Z0-9]+)\/?/', $request_uri)) {
            return false;
        }
        
        // If this is a stewards view URL, don't redirect
        if (preg_match('/\/stewards\/view\/([a-zA-Z0-9]+)\/?/', $request_uri)) {
            return false;
        }
        
        return $redirect_url;
    }
    
    /**
     * Add rewrite rules for SPA navigation
     */
    public function add_rewrite_rules() {
        // Get the front page ID
        $front_page_id = get_option('page_on_front');
        
        // If a static front page is set, use that
        if ($front_page_id) {
            add_rewrite_rule('^trackguide/?$', 'index.php?page_id=' . $front_page_id . '&apex_route=trackguide', 'top');
            add_rewrite_rule('^liveevents/?$', 'index.php?page_id=' . $front_page_id . '&apex_route=liveevents', 'top');
            add_rewrite_rule('^apextrackbot/?$', 'index.php?page_id=' . $front_page_id . '&apex_route=apextrackbot', 'top');
            add_rewrite_rule('^boxbox/?$', 'index.php?page_id=' . $front_page_id . '&apex_route=boxbox', 'top');
            add_rewrite_rule('^streaming/?$', 'index.php?page_id=' . $front_page_id . '&apex_route=streaming', 'top');
            add_rewrite_rule('^stewards/?$', 'index.php?page_id=' . $front_page_id . '&apex_route=stewards', 'top');
            add_rewrite_rule('^stewards/view/([a-zA-Z0-9]+)/?$', 'index.php?page_id=' . $front_page_id . '&apex_route=stewards-view&apex_stewards_token=$matches[1]', 'top');
            add_rewrite_rule('^newnote/?$', 'index.php?page_id=' . $front_page_id . '&apex_route=newnote', 'top');
            add_rewrite_rule('^mynotes/?$', 'index.php?page_id=' . $front_page_id . '&apex_route=mynotes', 'top');
            add_rewrite_rule('^moderation/?$', 'index.php?page_id=' . $front_page_id . '&apex_route=moderation', 'top');
            add_rewrite_rule('^profile/([^/]+)/?$', 'index.php?page_id=' . $front_page_id . '&apex_route=profile&apex_username=$matches[1]', 'top');
            add_rewrite_rule('^note/([0-9]+)/?$', 'index.php?page_id=' . $front_page_id . '&apex_route=note&apex_note_id=$matches[1]', 'top');
            add_rewrite_rule('^livery/([a-zA-Z0-9]+)/?$', 'index.php?page_id=' . $front_page_id . '&apex_route=livery&apex_livery_token=$matches[1]', 'top');
        }
        
        // Register query vars
        add_filter('query_vars', function($vars) {
            $vars[] = 'apex_route';
            $vars[] = 'apex_username';
            $vars[] = 'apex_note_id';
            $vars[] = 'apex_livery_token';
            $vars[] = 'apex_stewards_token';
            return $vars;
        });
        
        // Fallback: Handle livery URLs via template_redirect if rewrite doesn't work
        add_action('template_redirect', array($this, 'handle_livery_redirect'));
        
        // Fallback: Handle stewards view URLs via template_redirect if rewrite doesn't work
        add_action('template_redirect', array($this, 'handle_stewards_redirect'));
        
        // Capture stewards token early before any redirects
        add_action('parse_request', array($this, 'capture_stewards_token'));
    }
    
    /**
     * Capture stewards token early before redirects
     */
    public function capture_stewards_token($wp) {
        $request_uri = $_SERVER['REQUEST_URI'] ?? '';
        
        if (preg_match('/\/stewards\/view\/([a-zA-Z0-9]+)\/?/', $request_uri, $matches)) {
            // Store the token in a global for later use
            $GLOBALS['apex_stewards_token'] = $matches[1];
        }
    }
    
    /**
     * Fallback handler for stewards view URLs - prevent 404
     */
    public function handle_stewards_redirect() {
        $request_uri = $_SERVER['REQUEST_URI'] ?? '';
        
        // Check if this is a stewards view URL
        if (preg_match('/\/stewards\/view\/([a-zA-Z0-9]+)\/?/', $request_uri, $matches)) {
            $front_page_id = get_option('page_on_front');
            
            if ($front_page_id && (is_404() || !is_page($front_page_id))) {
                // Redirect to front page - the JS will handle the report loading
                global $wp_query;
                $wp_query->is_404 = false;
                $wp_query->is_page = true;
                $wp_query->is_singular = true;
                $wp_query->queried_object = get_post($front_page_id);
                $wp_query->queried_object_id = $front_page_id;
                
                status_header(200);
                
                // Load the front page template
                include(get_page_template());
                exit;
            }
        }
    }
    
    /**
     * Fallback handler for livery URLs - prevent 404
     */
    public function handle_livery_redirect() {
        // Only handle if this is a 404
        if (!is_404()) {
            return;
        }
        
        $request_uri = $_SERVER['REQUEST_URI'] ?? '';
        
        // Check if this is a livery URL
        if (preg_match('/\/livery\/([a-zA-Z0-9]+)\/?/', $request_uri, $matches)) {
            $front_page_id = get_option('page_on_front');
            
            if ($front_page_id) {
                // Redirect to front page - the JS will handle the livery loading
                // The token is passed via apexNotesData.liveryToken
                global $wp_query;
                $wp_query->is_404 = false;
                $wp_query->is_page = true;
                $wp_query->is_singular = true;
                $wp_query->queried_object = get_post($front_page_id);
                $wp_query->queried_object_id = $front_page_id;
                
                status_header(200);
                
                // Load the front page template
                include(get_page_template());
                exit;
            }
        }
    }
    
    public function activate() {
        Apex_Notes_DB::create_tables();
        Apex_Notes_DB::insert_sample_data();
        
        // Recalculate community averages to populate new fields
        Apex_Notes_DB::recalculate_all_community_averages();
        
        // Add capabilities
        $admin = get_role('administrator');
        if ($admin) {
            $admin->add_cap('manage_apex_notes');
            $admin->add_cap('moderate_apex_notes');
        }
        
        flush_rewrite_rules();
    }
    
    public function deactivate() {
        flush_rewrite_rules();
    }
    
    public function load_textdomain() {
        load_plugin_textdomain('apex-notes', false, dirname(APEX_NOTES_PLUGIN_BASENAME) . '/languages');
    }
    
    public function enqueue_public_assets() {
        // Google Fonts
        wp_enqueue_style(
            'apex-notes-fonts',
            'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap',
            array(),
            APEX_NOTES_VERSION
        );
        
        // Plugin CSS
        wp_enqueue_style(
            'apex-notes-style',
            APEX_NOTES_PLUGIN_URL . 'assets/css/apex-notes.css',
            array(),
            APEX_NOTES_VERSION
        );
        
        // Plugin JS
        wp_enqueue_script(
            'apex-notes-script',
            APEX_NOTES_PLUGIN_URL . 'assets/js/apex-notes.js',
            array('jquery'),
            APEX_NOTES_VERSION,
            true
        );
        
        // Localize script
        $is_banned = is_user_logged_in() ? Apex_Notes_DB::is_user_banned(get_current_user_id()) : false;
        
        // Check if this is a livery URL and extract token
        $livery_token = '';
        $request_uri = $_SERVER['REQUEST_URI'] ?? '';
        if (preg_match('/\/livery\/([a-zA-Z0-9]+)\/?/', $request_uri, $matches)) {
            $livery_token = $matches[1];
        }
        
        // Detect stewards view token - check multiple sources
        $stewards_token = '';
        // First check global (captured early before redirects)
        if (!empty($GLOBALS['apex_stewards_token'])) {
            $stewards_token = $GLOBALS['apex_stewards_token'];
        }
        // Then try query var (set by rewrite rule)
        if (empty($stewards_token)) {
            $stewards_token = get_query_var('apex_stewards_token', '');
        }
        // Fallback to URL parsing
        if (empty($stewards_token) && preg_match('/\/stewards\/view\/([a-zA-Z0-9]+)\/?/', $request_uri, $matches)) {
            $stewards_token = $matches[1];
        }
        
        wp_localize_script('apex-notes-script', 'apexNotesData', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('apex_notes_nonce'),
            'isLoggedIn' => is_user_logged_in(),
            'isBanned' => $is_banned,
            'isVerified' => Apex_Notes_Auth::is_user_verified(),
            'currentUser' => $this->get_current_user_data(),
            'canModerate' => current_user_can('moderate_apex_notes'),
            'loginUrl' => wp_login_url(get_permalink()),
            'googleAuthUrl' => Apex_Notes_Auth::get_instance()->get_google_auth_url(),
            'discordAuthUrl' => Apex_Notes_Auth::get_instance()->get_discord_auth_url(),
            'cars' => Apex_Notes_Data::get_cars(),
            'tracks' => Apex_Notes_Data::get_tracks(),
            'difficulties' => Apex_Notes_Data::get_difficulties(),
            'siteUrl' => home_url(),
            'siteBase' => parse_url(home_url(), PHP_URL_PATH) ?: '',
            'pluginUrl' => APEX_NOTES_PLUGIN_URL,
            'liveryToken' => $livery_token,
            'stewardsToken' => $stewards_token,
        ));
    }
    
    private function get_current_user_data() {
        if (!is_user_logged_in()) {
            return null;
        }
        
        $user = wp_get_current_user();
        $avatar = Apex_Notes_Auth::get_user_avatar($user->ID);
        
        return array(
            'id' => $user->ID,
            'name' => $user->display_name,
            'email' => $user->user_email,
            'avatar' => $avatar,
            'isAdmin' => current_user_can('moderate_apex_notes'),
        );
    }
}

// Add custom cron intervals
add_filter('cron_schedules', function($schedules) {
    $schedules['five_minutes'] = array(
        'interval' => 300,
        'display' => __('Every 5 Minutes', 'apex-notes')
    );
    $schedules['fifteen_minutes'] = array(
        'interval' => 900,
        'display' => __('Every 15 Minutes', 'apex-notes')
    );
    $schedules['thirty_minutes'] = array(
        'interval' => 1800,
        'display' => __('Every 30 Minutes', 'apex-notes')
    );
    return $schedules;
});

// Initialize plugin
function apex_notes() {
    return Apex_Notes::get_instance();
}

apex_notes();
