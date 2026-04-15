<?php
/**
 * Apex Notes Admin
 */

if (!defined('ABSPATH')) {
    exit;
}

class Apex_Notes_Admin {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_init', array($this, 'handle_backup_actions'));
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        $pending_count = Apex_Notes_DB::get_pending_count();
        $menu_title = 'Apex Notes';
        if ($pending_count > 0) {
            $menu_title .= sprintf(' <span class="awaiting-mod">%d</span>', $pending_count);
        }
        
        add_menu_page(
            __('Apex Notes', 'apex-notes'),
            $menu_title,
            'moderate_apex_notes',
            'apex-notes',
            array($this, 'render_admin_page'),
            'dashicons-flag',
            30
        );
        
        add_submenu_page(
            'apex-notes',
            __('All Notes', 'apex-notes'),
            __('All Notes', 'apex-notes'),
            'moderate_apex_notes',
            'apex-notes',
            array($this, 'render_admin_page')
        );
        
        add_submenu_page(
            'apex-notes',
            __('Pending Review', 'apex-notes'),
            sprintf(__('Pending %s', 'apex-notes'), $pending_count > 0 ? '<span class="awaiting-mod">' . $pending_count . '</span>' : ''),
            'moderate_apex_notes',
            'apex-notes-pending',
            array($this, 'render_pending_page')
        );
        
        add_submenu_page(
            'apex-notes',
            __('Settings', 'apex-notes'),
            __('Settings', 'apex-notes'),
            'manage_apex_notes',
            'apex-notes-settings',
            array($this, 'render_settings_page')
        );
        
        add_submenu_page(
            'apex-notes',
            __('Backup & Export', 'apex-notes'),
            __('Backup & Export', 'apex-notes'),
            'manage_apex_notes',
            'apex-notes-backup',
            array($this, 'render_backup_page')
        );
        
        add_submenu_page(
            'apex-notes',
            __('Liveries', 'apex-notes'),
            __('Liveries', 'apex-notes'),
            'manage_apex_notes',
            'apex-notes-liveries',
            array($this, 'render_liveries_page')
        );
        
        add_submenu_page(
            'apex-notes',
            __('Fuel Data Cars', 'apex-notes'),
            __('Fuel Data Cars', 'apex-notes'),
            'manage_apex_notes',
            'apex-notes-fuel-cars',
            array($this, 'render_fuel_cars_page')
        );
        
        add_submenu_page(
            'apex-notes',
            __('Fuel Data Tools', 'apex-notes'),
            __('Fuel Data Tools', 'apex-notes'),
            'manage_apex_notes',
            'apex-notes-fuel-tools',
            array($this, 'render_fuel_tools_page')
        );
        
        add_submenu_page(
            'apex-notes',
            __('Stewards Reports', 'apex-notes'),
            __('Stewards Reports', 'apex-notes'),
            'manage_apex_notes',
            'apex-notes-stewards-reports',
            array($this, 'render_stewards_reports_page')
        );
    }
    
    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook) {
        if (strpos($hook, 'apex-notes') === false) {
            return;
        }
        
        wp_enqueue_style(
            'apex-notes-admin',
            APEX_NOTES_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            APEX_NOTES_VERSION
        );
    }
    
    /**
     * Register settings
     */
    public function register_settings() {
        register_setting('apex_notes_settings', 'apex_notes_require_moderation');
        register_setting('apex_notes_settings', 'apex_notes_profanity_filter');
        register_setting('apex_notes_settings', 'apex_notes_custom_profanity_words');
        
        // OAuth settings
        register_setting('apex_notes_settings', 'apex_notes_google_client_id');
        register_setting('apex_notes_settings', 'apex_notes_google_client_secret');
        register_setting('apex_notes_settings', 'apex_notes_discord_client_id');
        register_setting('apex_notes_settings', 'apex_notes_discord_client_secret');
        register_setting('apex_notes_settings', 'apex_notes_require_email_verification');
        
        // AI settings
        register_setting('apex_notes_settings', 'apex_notes_anthropic_api_key');
    }
    
    /**
     * Render main admin page
     */
    public function render_admin_page() {
        $notes = Apex_Notes_DB::get_notes(array('status' => ''));
        
        ?>
        <div class="wrap">
            <h1><?php _e('Apex Notes - All Notes', 'apex-notes'); ?></h1>
            
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php _e('Title', 'apex-notes'); ?></th>
                        <th><?php _e('Author', 'apex-notes'); ?></th>
                        <th><?php _e('Track', 'apex-notes'); ?></th>
                        <th><?php _e('Car', 'apex-notes'); ?></th>
                        <th><?php _e('Status', 'apex-notes'); ?></th>
                        <th><?php _e('Rating', 'apex-notes'); ?></th>
                        <th><?php _e('Views', 'apex-notes'); ?></th>
                        <th><?php _e('Date', 'apex-notes'); ?></th>
                        <th><?php _e('Actions', 'apex-notes'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($notes)) : ?>
                        <tr>
                            <td colspan="9"><?php _e('No notes found.', 'apex-notes'); ?></td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($notes as $note) : ?>
                            <?php
                            $track = Apex_Notes_Data::get_track_by_id($note['track_id']);
                            $car = Apex_Notes_Data::get_car_by_id($note['car_id']);
                            ?>
                            <tr>
                                <td>
                                    <strong><?php echo esc_html($note['title']); ?></strong>
                                    <?php if ($note['featured']) : ?>
                                        <span class="dashicons dashicons-star-filled" style="color:#F58220;" title="Featured"></span>
                                    <?php endif; ?>
                                    <?php if ($note['has_profanity']) : ?>
                                        <span class="dashicons dashicons-warning" style="color:#dc3232;" title="Contains profanity"></span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo esc_html($note['author']['name']); ?></td>
                                <td><?php echo esc_html($track ? $track['name'] : $note['track_id']); ?></td>
                                <td><?php echo esc_html($car ? $car['name'] : $note['car_id']); ?></td>
                                <td>
                                    <span class="apex-status apex-status-<?php echo esc_attr($note['status']); ?>">
                                        <?php echo esc_html(ucfirst($note['status'])); ?>
                                    </span>
                                </td>
                                <td><?php echo number_format($note['rating'], 1); ?> (<?php echo $note['rating_count']; ?>)</td>
                                <td><?php echo number_format($note['views']); ?></td>
                                <td><?php echo date('M j, Y', strtotime($note['created_at'])); ?></td>
                                <td>
                                    <?php if ($note['status'] === 'pending') : ?>
                                        <button class="button button-small apex-approve-btn" data-id="<?php echo $note['id']; ?>">Approve</button>
                                        <button class="button button-small apex-reject-btn" data-id="<?php echo $note['id']; ?>">Reject</button>
                                    <?php endif; ?>
                                    <button class="button button-small apex-feature-btn" data-id="<?php echo $note['id']; ?>">
                                        <?php echo $note['featured'] ? 'Unfeature' : 'Feature'; ?>
                                    </button>
                                    <button class="button button-small button-link-delete apex-delete-btn" data-id="<?php echo $note['id']; ?>">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <script>
        jQuery(document).ready(function($) {
            var nonce = '<?php echo wp_create_nonce('apex_notes_nonce'); ?>';
            
            $('.apex-approve-btn').on('click', function() {
                var id = $(this).data('id');
                $.post(ajaxurl, {
                    action: 'apex_notes_moderate_note',
                    nonce: nonce,
                    note_id: id,
                    mod_action: 'approve'
                }, function() {
                    location.reload();
                });
            });
            
            $('.apex-reject-btn').on('click', function() {
                var id = $(this).data('id');
                $.post(ajaxurl, {
                    action: 'apex_notes_moderate_note',
                    nonce: nonce,
                    note_id: id,
                    mod_action: 'reject'
                }, function() {
                    location.reload();
                });
            });
            
            $('.apex-feature-btn').on('click', function() {
                var id = $(this).data('id');
                $.post(ajaxurl, {
                    action: 'apex_notes_toggle_featured',
                    nonce: nonce,
                    note_id: id
                }, function() {
                    location.reload();
                });
            });
            
            $('.apex-delete-btn').on('click', function() {
                if (!confirm('Are you sure you want to delete this note?')) return;
                var id = $(this).data('id');
                $.post(ajaxurl, {
                    action: 'apex_notes_delete_note',
                    nonce: nonce,
                    note_id: id
                }, function() {
                    location.reload();
                });
            });
        });
        </script>
        
        <style>
        .apex-status {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: 600;
        }
        .apex-status-pending { background: #fff3cd; color: #856404; }
        .apex-status-approved { background: #d4edda; color: #155724; }
        .apex-status-rejected { background: #f8d7da; color: #721c24; }
        </style>
        <?php
    }
    
    /**
     * Render pending page
     */
    public function render_pending_page() {
        $notes = Apex_Notes_DB::get_notes(array('status' => 'pending'));
        
        ?>
        <div class="wrap">
            <h1><?php _e('Apex Notes - Pending Review', 'apex-notes'); ?></h1>
            
            <?php if (empty($notes)) : ?>
                <p><?php _e('No notes pending review.', 'apex-notes'); ?></p>
            <?php else : ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th><?php _e('Title', 'apex-notes'); ?></th>
                            <th><?php _e('Author', 'apex-notes'); ?></th>
                            <th><?php _e('Track', 'apex-notes'); ?></th>
                            <th><?php _e('Car', 'apex-notes'); ?></th>
                            <th><?php _e('Profanity', 'apex-notes'); ?></th>
                            <th><?php _e('Date', 'apex-notes'); ?></th>
                            <th><?php _e('Actions', 'apex-notes'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($notes as $note) : ?>
                            <?php
                            $track = Apex_Notes_Data::get_track_by_id($note['track_id']);
                            $car = Apex_Notes_Data::get_car_by_id($note['car_id']);
                            ?>
                            <tr <?php echo $note['has_profanity'] ? 'style="background:#fff3cd;"' : ''; ?>>
                                <td><strong><?php echo esc_html($note['title']); ?></strong></td>
                                <td><?php echo esc_html($note['author']['name']); ?></td>
                                <td><?php echo esc_html($track ? $track['name'] : $note['track_id']); ?></td>
                                <td><?php echo esc_html($car ? $car['name'] : $note['car_id']); ?></td>
                                <td>
                                    <?php if ($note['has_profanity']) : ?>
                                        <span style="color:#dc3232;">⚠️ Flagged</span>
                                    <?php else : ?>
                                        <span style="color:#46b450;">✓ Clean</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo date('M j, Y', strtotime($note['created_at'])); ?></td>
                                <td>
                                    <button class="button button-primary apex-approve-btn" data-id="<?php echo $note['id']; ?>">Approve</button>
                                    <button class="button apex-reject-btn" data-id="<?php echo $note['id']; ?>">Reject</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        
        <script>
        jQuery(document).ready(function($) {
            var nonce = '<?php echo wp_create_nonce('apex_notes_nonce'); ?>';
            
            $('.apex-approve-btn').on('click', function() {
                var id = $(this).data('id');
                $.post(ajaxurl, {
                    action: 'apex_notes_moderate_note',
                    nonce: nonce,
                    note_id: id,
                    mod_action: 'approve'
                }, function() {
                    location.reload();
                });
            });
            
            $('.apex-reject-btn').on('click', function() {
                var id = $(this).data('id');
                $.post(ajaxurl, {
                    action: 'apex_notes_moderate_note',
                    nonce: nonce,
                    note_id: id,
                    mod_action: 'reject'
                }, function() {
                    location.reload();
                });
            });
        });
        </script>
        <?php
    }
    
    /**
     * Render settings page
     */
    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('Apex Notes Settings', 'apex-notes'); ?></h1>
            
            <form method="post" action="options.php">
                <?php settings_fields('apex_notes_settings'); ?>
                
                <h2><?php _e('Content Moderation', 'apex-notes'); ?></h2>
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php _e('Require Moderation', 'apex-notes'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="apex_notes_require_moderation" value="1" <?php checked(get_option('apex_notes_require_moderation', 1)); ?>>
                                <?php _e('All new notes must be approved before publishing', 'apex-notes'); ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Profanity Filter', 'apex-notes'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="apex_notes_profanity_filter" value="1" <?php checked(get_option('apex_notes_profanity_filter', 1)); ?>>
                                <?php _e('Enable profanity detection for new submissions', 'apex-notes'); ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Custom Blocked Words', 'apex-notes'); ?></th>
                        <td>
                            <textarea name="apex_notes_custom_profanity_words" rows="5" cols="50" class="large-text"><?php echo esc_textarea(get_option('apex_notes_custom_profanity_words', '')); ?></textarea>
                            <p class="description"><?php _e('Enter additional words to block, one per line.', 'apex-notes'); ?></p>
                        </td>
                    </tr>
                </table>
                
                <h2><?php _e('Email Verification', 'apex-notes'); ?></h2>
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php _e('Require Email Verification', 'apex-notes'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="apex_notes_require_email_verification" value="1" <?php checked(get_option('apex_notes_require_email_verification', 1)); ?>>
                                <?php _e('Users must verify their email before posting (OAuth users are auto-verified)', 'apex-notes'); ?>
                            </label>
                        </td>
                    </tr>
                </table>
                
                <h2><?php _e('Google OAuth', 'apex-notes'); ?></h2>
                <p class="description">
                    <?php _e('Create credentials at', 'apex-notes'); ?> 
                    <a href="https://console.cloud.google.com/apis/credentials" target="_blank">Google Cloud Console</a>.
                    <?php _e('Redirect URI:', 'apex-notes'); ?> 
                    <code><?php echo esc_html(home_url('?apex_oauth=google')); ?></code>
                </p>
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php _e('Google Client ID', 'apex-notes'); ?></th>
                        <td>
                            <input type="text" name="apex_notes_google_client_id" value="<?php echo esc_attr(get_option('apex_notes_google_client_id', '')); ?>" class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Google Client Secret', 'apex-notes'); ?></th>
                        <td>
                            <input type="password" name="apex_notes_google_client_secret" value="<?php echo esc_attr(get_option('apex_notes_google_client_secret', '')); ?>" class="regular-text">
                        </td>
                    </tr>
                </table>
                
                <h2><?php _e('Discord OAuth', 'apex-notes'); ?></h2>
                <p class="description">
                    <?php _e('Create an app at', 'apex-notes'); ?> 
                    <a href="https://discord.com/developers/applications" target="_blank">Discord Developer Portal</a>.
                    <?php _e('Redirect URI:', 'apex-notes'); ?> 
                    <code><?php echo esc_html(home_url('?apex_oauth=discord')); ?></code>
                </p>
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php _e('Discord Client ID', 'apex-notes'); ?></th>
                        <td>
                            <input type="text" name="apex_notes_discord_client_id" value="<?php echo esc_attr(get_option('apex_notes_discord_client_id', '')); ?>" class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Discord Client Secret', 'apex-notes'); ?></th>
                        <td>
                            <input type="password" name="apex_notes_discord_client_secret" value="<?php echo esc_attr(get_option('apex_notes_discord_client_secret', '')); ?>" class="regular-text">
                        </td>
                    </tr>
                </table>
                
                <h2><?php _e('ApexTrackBot', 'apex-notes'); ?></h2>
                <p class="description">
                    <?php _e('ApexTrackBot is a specialized AI assistant that ONLY answers questions about motorsport race tracks and car setups.', 'apex-notes'); ?>
                </p>
                <p class="description">
                    <strong><?php _e('Track Data Sources:', 'apex-notes'); ?></strong> RacingCircuits.info, Motorsport Magazine, Silhouet Track Database<br>
                    <strong><?php _e('Setup Data Sources:', 'apex-notes'); ?></strong> Ultimate Setup Hub, Coach Dave Academy
                </p>
                <p class="description">
                    <?php _e('Get an API key from', 'apex-notes'); ?> 
                    <a href="https://console.anthropic.com/" target="_blank">Anthropic Console</a>.
                    <?php _e('Without an API key, the chatbot will use built-in knowledge about major circuits and setup tuning.', 'apex-notes'); ?>
                </p>
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php _e('Anthropic API Key', 'apex-notes'); ?></th>
                        <td>
                            <input type="password" name="apex_notes_anthropic_api_key" value="<?php echo esc_attr(get_option('apex_notes_anthropic_api_key', '')); ?>" class="regular-text">
                            <p class="description"><?php _e('Optional. Enables AI-powered responses with real-time web search for 700+ circuits.', 'apex-notes'); ?></p>
                        </td>
                    </tr>
                </table>
                
                <h2><?php _e('Shortcode Usage', 'apex-notes'); ?></h2>
                <p><?php _e('Use the following shortcode to display Apex Notes on any page:', 'apex-notes'); ?></p>
                <code>[apex_notes]</code>
                
                <h2><?php _e('Database', 'apex-notes'); ?></h2>
                <p>
                    <button type="button" class="button" id="apex-reset-sample">
                        <?php _e('Reset Sample Data', 'apex-notes'); ?>
                    </button>
                </p>
                
                <?php submit_button(); ?>
            </form>
        </div>
        
        <script>
        jQuery('#apex-reset-sample').on('click', function() {
            if (confirm('This will add sample notes. Continue?')) {
                // This would need an AJAX handler to reset sample data
                alert('Sample data reset functionality would go here.');
            }
        });
        </script>
        <?php
    }
    
    /**
     * Handle backup actions (export/import)
     */
    public function handle_backup_actions() {
        // Handle Export
        if (isset($_POST['apex_notes_export']) && isset($_POST['apex_notes_backup_nonce'])) {
            if (!wp_verify_nonce($_POST['apex_notes_backup_nonce'], 'apex_notes_backup')) {
                wp_die('Security check failed');
            }
            
            if (!current_user_can('manage_apex_notes')) {
                wp_die('Permission denied');
            }
            
            $this->export_backup();
            exit;
        }
        
        // Handle Import
        if (isset($_POST['apex_notes_import']) && isset($_POST['apex_notes_backup_nonce'])) {
            if (!wp_verify_nonce($_POST['apex_notes_backup_nonce'], 'apex_notes_backup')) {
                wp_die('Security check failed');
            }
            
            if (!current_user_can('manage_apex_notes')) {
                wp_die('Permission denied');
            }
            
            $this->import_backup();
        }
    }
    
    /**
     * Export backup as JSON
     */
    private function export_backup() {
        $include_data = isset($_POST['include_data']) ? true : false;
        
        $backup = array(
            'plugin_version' => APEX_NOTES_VERSION,
            'export_date' => current_time('mysql'),
            'site_url' => get_site_url(),
            'settings' => array(),
            'data' => array()
        );
        
        // All settings to export
        $settings_keys = array(
            'apex_notes_require_moderation',
            'apex_notes_profanity_filter',
            'apex_notes_custom_profanity_words',
            'apex_notes_google_client_id',
            'apex_notes_google_client_secret',
            'apex_notes_discord_client_id',
            'apex_notes_discord_client_secret',
            'apex_notes_require_email_verification',
            'apex_notes_anthropic_api_key',
            'apex_notes_db_version'
        );
        
        foreach ($settings_keys as $key) {
            $backup['settings'][$key] = get_option($key, '');
        }
        
        // Include database data if requested
        if ($include_data) {
            global $wpdb;
            
            // Notes
            $table = $wpdb->prefix . 'apex_notes_notes';
            $backup['data']['notes'] = $wpdb->get_results("SELECT * FROM $table", ARRAY_A);
            
            // Sections
            $table = $wpdb->prefix . 'apex_notes_sections';
            $backup['data']['sections'] = $wpdb->get_results("SELECT * FROM $table", ARRAY_A);
            
            // Ratings
            $table = $wpdb->prefix . 'apex_notes_ratings';
            $backup['data']['ratings'] = $wpdb->get_results("SELECT * FROM $table", ARRAY_A);
            
            // Comments
            $table = $wpdb->prefix . 'apex_notes_comments';
            $backup['data']['comments'] = $wpdb->get_results("SELECT * FROM $table", ARRAY_A);
            
            // Followers
            $table = $wpdb->prefix . 'apex_notes_followers';
            $backup['data']['followers'] = $wpdb->get_results("SELECT * FROM $table", ARRAY_A);
            
            // Fuel Sessions
            $table = $wpdb->prefix . 'apex_notes_fuel_sessions';
            if ($wpdb->get_var("SHOW TABLES LIKE '$table'") === $table) {
                $backup['data']['fuel_sessions'] = $wpdb->get_results("SELECT * FROM $table", ARRAY_A);
            }
            
            // Fuel Laps
            $table = $wpdb->prefix . 'apex_notes_fuel_laps';
            if ($wpdb->get_var("SHOW TABLES LIKE '$table'") === $table) {
                $backup['data']['fuel_laps'] = $wpdb->get_results("SELECT * FROM $table", ARRAY_A);
            }
            
            // Fuel Averages
            $table = $wpdb->prefix . 'apex_notes_fuel_averages';
            if ($wpdb->get_var("SHOW TABLES LIKE '$table'") === $table) {
                $backup['data']['fuel_averages'] = $wpdb->get_results("SELECT * FROM $table", ARRAY_A);
            }
            
            // Affiliate Links
            $table = $wpdb->prefix . 'apex_notes_affiliate_links';
            if ($wpdb->get_var("SHOW TABLES LIKE '$table'") === $table) {
                $backup['data']['affiliate_links'] = $wpdb->get_results("SELECT * FROM $table", ARRAY_A);
            }
            
            // Live Events
            $table = $wpdb->prefix . 'apex_notes_live_events';
            if ($wpdb->get_var("SHOW TABLES LIKE '$table'") === $table) {
                $backup['data']['live_events'] = $wpdb->get_results("SELECT * FROM $table", ARRAY_A);
            }
        }
        
        // Generate filename
        $filename = 'apex-notes-backup-' . date('Y-m-d-His') . '.json';
        
        // Send headers
        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        echo json_encode($backup, JSON_PRETTY_PRINT);
        exit;
    }
    
    /**
     * Import backup from JSON
     */
    private function import_backup() {
        if (!isset($_FILES['backup_file']) || $_FILES['backup_file']['error'] !== UPLOAD_ERR_OK) {
            add_settings_error('apex_notes_backup', 'upload_error', __('Failed to upload backup file.', 'apex-notes'), 'error');
            return;
        }
        
        $file_content = file_get_contents($_FILES['backup_file']['tmp_name']);
        $backup = json_decode($file_content, true);
        
        if (!$backup || !isset($backup['settings'])) {
            add_settings_error('apex_notes_backup', 'invalid_file', __('Invalid backup file format.', 'apex-notes'), 'error');
            return;
        }
        
        $import_settings = isset($_POST['import_settings']) ? true : false;
        $import_data = isset($_POST['import_data']) ? true : false;
        
        $imported_count = 0;
        
        // Import settings
        if ($import_settings && !empty($backup['settings'])) {
            foreach ($backup['settings'] as $key => $value) {
                update_option($key, $value);
                $imported_count++;
            }
        }
        
        // Import data
        if ($import_data && !empty($backup['data'])) {
            global $wpdb;
            
            // Import notes
            if (!empty($backup['data']['notes'])) {
                $table = $wpdb->prefix . 'apex_notes_notes';
                foreach ($backup['data']['notes'] as $row) {
                    $existing = $wpdb->get_var($wpdb->prepare("SELECT id FROM $table WHERE id = %d", $row['id']));
                    if (!$existing) {
                        $wpdb->insert($table, $row);
                        $imported_count++;
                    }
                }
            }
            
            // Import sections
            if (!empty($backup['data']['sections'])) {
                $table = $wpdb->prefix . 'apex_notes_sections';
                foreach ($backup['data']['sections'] as $row) {
                    $existing = $wpdb->get_var($wpdb->prepare("SELECT id FROM $table WHERE id = %d", $row['id']));
                    if (!$existing) {
                        $wpdb->insert($table, $row);
                        $imported_count++;
                    }
                }
            }
            
            // Import ratings
            if (!empty($backup['data']['ratings'])) {
                $table = $wpdb->prefix . 'apex_notes_ratings';
                foreach ($backup['data']['ratings'] as $row) {
                    $existing = $wpdb->get_var($wpdb->prepare("SELECT id FROM $table WHERE id = %d", $row['id']));
                    if (!$existing) {
                        $wpdb->insert($table, $row);
                        $imported_count++;
                    }
                }
            }
            
            // Import comments
            if (!empty($backup['data']['comments'])) {
                $table = $wpdb->prefix . 'apex_notes_comments';
                foreach ($backup['data']['comments'] as $row) {
                    $existing = $wpdb->get_var($wpdb->prepare("SELECT id FROM $table WHERE id = %d", $row['id']));
                    if (!$existing) {
                        $wpdb->insert($table, $row);
                        $imported_count++;
                    }
                }
            }
            
            // Import followers
            if (!empty($backup['data']['followers'])) {
                $table = $wpdb->prefix . 'apex_notes_followers';
                foreach ($backup['data']['followers'] as $row) {
                    $existing = $wpdb->get_var($wpdb->prepare("SELECT id FROM $table WHERE id = %d", $row['id']));
                    if (!$existing) {
                        $wpdb->insert($table, $row);
                        $imported_count++;
                    }
                }
            }
            
            // Import fuel sessions
            if (!empty($backup['data']['fuel_sessions'])) {
                $table = $wpdb->prefix . 'apex_notes_fuel_sessions';
                foreach ($backup['data']['fuel_sessions'] as $row) {
                    $existing = $wpdb->get_var($wpdb->prepare("SELECT id FROM $table WHERE id = %d", $row['id']));
                    if (!$existing) {
                        $wpdb->insert($table, $row);
                        $imported_count++;
                    }
                }
            }
            
            // Import fuel laps
            if (!empty($backup['data']['fuel_laps'])) {
                $table = $wpdb->prefix . 'apex_notes_fuel_laps';
                foreach ($backup['data']['fuel_laps'] as $row) {
                    $existing = $wpdb->get_var($wpdb->prepare("SELECT id FROM $table WHERE id = %d", $row['id']));
                    if (!$existing) {
                        $wpdb->insert($table, $row);
                        $imported_count++;
                    }
                }
            }
            
            // Import fuel averages
            if (!empty($backup['data']['fuel_averages'])) {
                $table = $wpdb->prefix . 'apex_notes_fuel_averages';
                foreach ($backup['data']['fuel_averages'] as $row) {
                    $existing = $wpdb->get_var($wpdb->prepare("SELECT id FROM $table WHERE id = %d", $row['id']));
                    if (!$existing) {
                        $wpdb->insert($table, $row);
                        $imported_count++;
                    }
                }
            }
            
            // Import affiliate links
            if (!empty($backup['data']['affiliate_links'])) {
                $table = $wpdb->prefix . 'apex_notes_affiliate_links';
                foreach ($backup['data']['affiliate_links'] as $row) {
                    $existing = $wpdb->get_var($wpdb->prepare("SELECT id FROM $table WHERE id = %d", $row['id']));
                    if (!$existing) {
                        $wpdb->insert($table, $row);
                        $imported_count++;
                    }
                }
            }
            
            // Import live events
            if (!empty($backup['data']['live_events'])) {
                $table = $wpdb->prefix . 'apex_notes_live_events';
                foreach ($backup['data']['live_events'] as $row) {
                    $existing = $wpdb->get_var($wpdb->prepare("SELECT id FROM $table WHERE id = %d", $row['id']));
                    if (!$existing) {
                        $wpdb->insert($table, $row);
                        $imported_count++;
                    }
                }
            }
        }
        
        add_settings_error(
            'apex_notes_backup', 
            'import_success', 
            sprintf(__('Backup imported successfully! %d items restored.', 'apex-notes'), $imported_count), 
            'success'
        );
    }
    
    /**
     * Render backup page
     */
    public function render_backup_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('Apex Notes - Backup & Export', 'apex-notes'); ?></h1>
            
            <?php settings_errors('apex_notes_backup'); ?>
            
            <div class="apex-backup-container" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px;">
                
                <!-- Export Section -->
                <div class="card" style="padding: 20px;">
                    <h2><?php _e('Export Backup', 'apex-notes'); ?></h2>
                    <p><?php _e('Download a backup file containing all your settings and optionally your data.', 'apex-notes'); ?></p>
                    
                    <form method="post" action="">
                        <?php wp_nonce_field('apex_notes_backup', 'apex_notes_backup_nonce'); ?>
                        
                        <table class="form-table">
                            <tr>
                                <th scope="row"><?php _e('What to Export', 'apex-notes'); ?></th>
                                <td>
                                    <fieldset>
                                        <label>
                                            <input type="checkbox" checked disabled>
                                            <?php _e('Settings & API Keys', 'apex-notes'); ?>
                                            <span class="description">(<?php _e('always included', 'apex-notes'); ?>)</span>
                                        </label>
                                        <br>
                                        <label>
                                            <input type="checkbox" name="include_data" value="1">
                                            <?php _e('Include All Data', 'apex-notes'); ?>
                                            <span class="description">(<?php _e('notes, ratings, fuel data, etc.', 'apex-notes'); ?>)</span>
                                        </label>
                                    </fieldset>
                                </td>
                            </tr>
                        </table>
                        
                        <div style="background: #fff3cd; border: 1px solid #ffc107; padding: 10px 15px; border-radius: 4px; margin: 15px 0;">
                            <strong>⚠️ <?php _e('Security Notice', 'apex-notes'); ?>:</strong>
                            <?php _e('The backup file contains sensitive API keys. Keep it secure and do not share it publicly.', 'apex-notes'); ?>
                        </div>
                        
                        <p>
                            <button type="submit" name="apex_notes_export" class="button button-primary">
                                <?php _e('Download Backup', 'apex-notes'); ?>
                            </button>
                        </p>
                    </form>
                    
                    <h3><?php _e('Included Settings', 'apex-notes'); ?></h3>
                    <ul style="list-style: disc; margin-left: 20px;">
                        <li><?php _e('Google OAuth Client ID & Secret', 'apex-notes'); ?></li>
                        <li><?php _e('Discord OAuth Client ID & Secret', 'apex-notes'); ?></li>
                        <li><?php _e('Anthropic API Key (ApexTrackBot)', 'apex-notes'); ?></li>
                        <li><?php _e('Moderation & Profanity Settings', 'apex-notes'); ?></li>
                    </ul>
                </div>
                
                <!-- Import Section -->
                <div class="card" style="padding: 20px;">
                    <h2><?php _e('Import Backup', 'apex-notes'); ?></h2>
                    <p><?php _e('Restore settings and data from a previously exported backup file.', 'apex-notes'); ?></p>
                    
                    <form method="post" action="" enctype="multipart/form-data">
                        <?php wp_nonce_field('apex_notes_backup', 'apex_notes_backup_nonce'); ?>
                        
                        <table class="form-table">
                            <tr>
                                <th scope="row"><?php _e('Backup File', 'apex-notes'); ?></th>
                                <td>
                                    <input type="file" name="backup_file" accept=".json" required>
                                    <p class="description"><?php _e('Select an apex-notes-backup-*.json file', 'apex-notes'); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e('What to Import', 'apex-notes'); ?></th>
                                <td>
                                    <fieldset>
                                        <label>
                                            <input type="checkbox" name="import_settings" value="1" checked>
                                            <?php _e('Settings & API Keys', 'apex-notes'); ?>
                                        </label>
                                        <br>
                                        <label>
                                            <input type="checkbox" name="import_data" value="1">
                                            <?php _e('Data (notes, ratings, fuel data, etc.)', 'apex-notes'); ?>
                                        </label>
                                        <p class="description"><?php _e('Data import will skip existing records to avoid duplicates.', 'apex-notes'); ?></p>
                                    </fieldset>
                                </td>
                            </tr>
                        </table>
                        
                        <div style="background: #d4edda; border: 1px solid #28a745; padding: 10px 15px; border-radius: 4px; margin: 15px 0;">
                            <strong>✅ <?php _e('Safe Import', 'apex-notes'); ?>:</strong>
                            <?php _e('Existing data will not be overwritten. Only new records will be added.', 'apex-notes'); ?>
                        </div>
                        
                        <p>
                            <button type="submit" name="apex_notes_import" class="button button-secondary">
                                <?php _e('Import Backup', 'apex-notes'); ?>
                            </button>
                        </p>
                    </form>
                    
                    <h3><?php _e('Migration Steps', 'apex-notes'); ?></h3>
                    <ol style="margin-left: 20px;">
                        <li><?php _e('Export backup from your old site', 'apex-notes'); ?></li>
                        <li><?php _e('Install Apex Notes plugin on new site', 'apex-notes'); ?></li>
                        <li><?php _e('Import the backup file here', 'apex-notes'); ?></li>
                        <li><?php _e('Update OAuth redirect URLs in Google/Discord console', 'apex-notes'); ?></li>
                        <li><?php _e('Test login and features', 'apex-notes'); ?></li>
                    </ol>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render Liveries admin page
     */
    public function render_liveries_page() {
        // Handle delete action
        if (isset($_POST['delete_livery']) && check_admin_referer('apex_liveries_nonce')) {
            $livery_id = intval($_POST['livery_id']);
            global $wpdb;
            $table = $wpdb->prefix . 'apex_notes_liveries';
            
            // Get livery to delete files
            $livery = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE id = %d", $livery_id), ARRAY_A);
            if ($livery) {
                if (!empty($livery['file_1_path']) && file_exists($livery['file_1_path'])) {
                    @unlink($livery['file_1_path']);
                }
                if (!empty($livery['file_2_path']) && file_exists($livery['file_2_path'])) {
                    @unlink($livery['file_2_path']);
                }
                $wpdb->delete($table, array('id' => $livery_id));
                echo '<div class="notice notice-success"><p>Livery deleted successfully.</p></div>';
            }
        }
        
        $liveries = Apex_Notes_DB::get_all_liveries();
        $upload_dir = wp_upload_dir();
        ?>
        <div class="wrap">
            <h1><?php _e('Uploaded Liveries', 'apex-notes'); ?></h1>
            
            <p class="description">
                <?php _e('View all liveries uploaded by users. Files are stored in wp-content/uploads/apex-liveries/[user_id]/. Liveries automatically expire and are deleted after 48 hours.', 'apex-notes'); ?>
            </p>
            
            <?php if (empty($liveries)) : ?>
            <div class="notice notice-info">
                <p><?php _e('No liveries have been uploaded yet.', 'apex-notes'); ?></p>
            </div>
            <?php else : ?>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th style="width: 50px;">ID</th>
                        <th>User</th>
                        <th>Livery Name</th>
                        <th>Files</th>
                        <th>Downloads</th>
                        <th>IP Address</th>
                        <th>Discord ID</th>
                        <th>Legal Agreed</th>
                        <th>Expires</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($liveries as $livery) : 
                        $expired = strtotime($livery['expires_at']) < time();
                        $file_1_exists = !empty($livery['file_1_path']) && file_exists($livery['file_1_path']);
                        $file_2_exists = !empty($livery['file_2_path']) && file_exists($livery['file_2_path']);
                    ?>
                    <tr style="<?php echo $expired ? 'opacity: 0.5;' : ''; ?>">
                        <td><?php echo esc_html($livery['id']); ?></td>
                        <td>
                            <strong><?php echo esc_html($livery['author_name'] ?: 'User #' . $livery['user_id']); ?></strong>
                            <br><small><?php echo esc_html($livery['user_email'] ?? ''); ?></small>
                        </td>
                        <td><?php echo esc_html($livery['livery_name']); ?></td>
                        <td>
                            <?php if ($file_1_exists) : ?>
                                <span style="color: #28a745;">✓</span> customskin.tga
                                <small>(<?php echo size_format(filesize($livery['file_1_path'])); ?>)</small>
                            <?php else : ?>
                                <span style="color: #dc3545;">✗</span> customskin.tga
                            <?php endif; ?>
                            <br>
                            <?php if ($file_2_exists) : ?>
                                <span style="color: #28a745;">✓</span> customskin_region.tga
                                <small>(<?php echo size_format(filesize($livery['file_2_path'])); ?>)</small>
                            <?php else : ?>
                                <span style="color: #dc3545;">✗</span> customskin_region.tga
                            <?php endif; ?>
                        </td>
                        <td><?php echo intval($livery['download_count']); ?></td>
                        <td><code><?php echo esc_html($livery['uploader_ip'] ?: 'N/A'); ?></code></td>
                        <td><?php echo esc_html($livery['uploader_discord_id'] ?: 'N/A'); ?></td>
                        <td>
                            <?php if ($livery['legal_agreed']) : ?>
                                <span style="color: #28a745;">✓ Yes</span>
                            <?php else : ?>
                                <span style="color: #dc3545;">✗ No</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($expired) : ?>
                                <span style="color: #dc3545;">Expired</span>
                            <?php else : ?>
                                <?php echo esc_html(human_time_diff(time(), strtotime($livery['expires_at']))); ?>
                            <?php endif; ?>
                            <br>
                            <small><?php echo esc_html(date('M j, Y g:ia', strtotime($livery['expires_at']))); ?></small>
                        </td>
                        <td>
                            <?php if ($file_1_exists) : ?>
                                <a href="<?php echo admin_url('admin-ajax.php?action=apex_notes_download_livery_file&token=' . $livery['share_token'] . '&file=1'); ?>" class="button button-small">DL File 1</a>
                            <?php endif; ?>
                            <?php if ($file_2_exists) : ?>
                                <a href="<?php echo admin_url('admin-ajax.php?action=apex_notes_download_livery_file&token=' . $livery['share_token'] . '&file=2'); ?>" class="button button-small">DL File 2</a>
                            <?php endif; ?>
                            <form method="post" style="display: inline;">
                                <?php wp_nonce_field('apex_liveries_nonce'); ?>
                                <input type="hidden" name="livery_id" value="<?php echo intval($livery['id']); ?>">
                                <button type="submit" name="delete_livery" class="button button-small" onclick="return confirm('Delete this livery?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <h3 style="margin-top: 30px;"><?php _e('Livery Storage Path', 'apex-notes'); ?></h3>
            <p><code><?php echo esc_html($upload_dir['basedir'] . '/apex-liveries/'); ?></code></p>
            <?php endif; ?>
        </div>
        <?php
    }
    
    /**
     * Render the Fuel Data Cars management page
     */
    public function render_fuel_cars_page() {
        // Get current cars from option or use defaults
        $default_cars = array(
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
        
        $cars = get_option('apex_notes_fuel_cars', $default_cars);
        
        // Handle form submissions
        if (isset($_POST['apex_save_fuel_cars']) && check_admin_referer('apex_fuel_cars_nonce')) {
            $new_cars = array();
            $classes = array('Hypercar', 'LMGT3', 'LMP2', 'LMP3', 'GTE');
            
            foreach ($classes as $class) {
                $field_name = 'cars_' . sanitize_key($class);
                if (isset($_POST[$field_name])) {
                    $car_list = sanitize_textarea_field($_POST[$field_name]);
                    $car_array = array_filter(array_map('trim', explode("\n", $car_list)));
                    $new_cars[$class] = $car_array;
                } else {
                    $new_cars[$class] = array();
                }
            }
            
            update_option('apex_notes_fuel_cars', $new_cars);
            $cars = $new_cars;
            echo '<div class="notice notice-success"><p>Fuel Data Cars updated successfully!</p></div>';
        }
        
        // Handle adding a new class
        if (isset($_POST['apex_add_class']) && check_admin_referer('apex_fuel_cars_nonce')) {
            $new_class = sanitize_text_field($_POST['new_class_name']);
            if ($new_class && !isset($cars[$new_class])) {
                $cars[$new_class] = array();
                update_option('apex_notes_fuel_cars', $cars);
                echo '<div class="notice notice-success"><p>Class "' . esc_html($new_class) . '" added successfully!</p></div>';
            }
        }
        
        ?>
        <div class="wrap">
            <h1><?php _e('Fuel Data Cars', 'apex-notes'); ?></h1>
            <p>Manage the cars available in the Fuel Data community dropdown. Enter one car per line.</p>
            
            <form method="post" action="">
                <?php wp_nonce_field('apex_fuel_cars_nonce'); ?>
                
                <table class="form-table">
                    <?php foreach ($cars as $class => $car_list): ?>
                    <tr>
                        <th scope="row">
                            <label for="cars_<?php echo esc_attr(sanitize_key($class)); ?>"><?php echo esc_html($class); ?></label>
                        </th>
                        <td>
                            <textarea 
                                name="cars_<?php echo esc_attr(sanitize_key($class)); ?>" 
                                id="cars_<?php echo esc_attr(sanitize_key($class)); ?>" 
                                rows="8" 
                                cols="50" 
                                class="large-text code"
                            ><?php echo esc_textarea(implode("\n", $car_list)); ?></textarea>
                            <p class="description"><?php echo count($car_list); ?> cars</p>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
                
                <p class="submit">
                    <input type="submit" name="apex_save_fuel_cars" class="button button-primary" value="Save Cars">
                </p>
            </form>
            
            <hr>
            
            <h2>Add New Class</h2>
            <form method="post" action="">
                <?php wp_nonce_field('apex_fuel_cars_nonce'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="new_class_name">Class Name</label></th>
                        <td>
                            <input type="text" name="new_class_name" id="new_class_name" class="regular-text">
                            <input type="submit" name="apex_add_class" class="button" value="Add Class">
                        </td>
                    </tr>
                </table>
            </form>
            
            <hr>
            
            <h2>How It Works</h2>
            <ul style="list-style: disc; margin-left: 20px;">
                <li>Each class appears as an <code>&lt;optgroup&gt;</code> in the car dropdown on the Fuel Data page</li>
                <li>Car names must match EXACTLY how they appear in the LMU XML files</li>
                <li>Empty classes will not appear in the dropdown</li>
                <li>Changes take effect immediately after saving</li>
            </ul>
            
            <h3>Correct Car Names from LMU XML Files:</h3>
            <pre style="background: #f0f0f0; padding: 15px; overflow: auto;">
Hypercar:
- Ferrari 499P
- Porsche 963
- Toyota GR010
- Cadillac V-Series.R
- Peugeot 9x8
- BMW M Hybrid V8
- Alpine A424
- Lamborghini SC63
- Isotta Fraschini TIPO6
- Glickenhaus SCG007
- Aston Martin Valkyrie LMH
- Vanwall 680

LMGT3:
- McLaren 720S LMGT3 Evo
- BMW M4 LMGT3
- Porsche 911 GT3 R LMGT3
- Ferrari 296 LMGT3
- Ford Mustang LMGT3
- Mercedes-AMG LMGT3
- Lamborghini Huracan LMGT3 Evo2
- Lexus RCF LMGT3
- Aston Martin Vantage AMR LMGT3
- Chevrolet Corvette Z06 LMGT3.R

LMP2:
- Oreca 07

LMP3:
- Ginetta G61-LT-P325 Evo
- Ligier JS P325

GTE:
- Porsche 911 RSR-19
- Ferrari 488 GTE EVO
- Corvette C8.R GTE
- Aston Martin Vantage AMR
            </pre>
            
            <hr>
            
            <h2>Car Names in Your Database</h2>
            <p>These are the EXACT car names stored from uploaded XML files. Copy these exactly to the fields above.</p>
            <?php
            global $wpdb;
            $sessions_table = $wpdb->prefix . 'apex_notes_fuel_sessions';
            $db_cars = $wpdb->get_results("SELECT DISTINCT car_type, car_class, COUNT(*) as session_count FROM $sessions_table GROUP BY car_type, car_class ORDER BY car_class, car_type");
            
            if ($db_cars && count($db_cars) > 0):
            ?>
            <table class="widefat striped" style="max-width: 600px;">
                <thead>
                    <tr>
                        <th>Car Name (copy this exactly)</th>
                        <th>Class</th>
                        <th>Sessions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($db_cars as $car): ?>
                    <tr>
                        <td><code style="background: #fffbe6; padding: 2px 6px;"><?php echo esc_html($car->car_type); ?></code></td>
                        <td><?php echo esc_html($car->car_class); ?></td>
                        <td><?php echo intval($car->session_count); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <p><em>No fuel sessions uploaded yet.</em></p>
            <?php endif; ?>
        </div>
        <?php
    }
    
    /**
     * Render the Fuel Data Tools page
     */
    public function render_fuel_tools_page() {
        global $wpdb;
        $sessions_table = $wpdb->prefix . 'apex_notes_fuel_sessions';
        
        $duplicates_removed = 0;
        $message = '';
        
        // Handle duplicate removal
        if (isset($_POST['remove_duplicates']) && check_admin_referer('apex_fuel_tools_nonce')) {
            $duplicates_removed = $this->remove_duplicate_sessions();
            $message = sprintf('Successfully removed %d duplicate session(s).', $duplicates_removed);
        }
        
        // Handle removing specific duplicate group
        if (isset($_POST['remove_duplicate_group']) && check_admin_referer('apex_fuel_tools_nonce')) {
            $keep_id = intval($_POST['keep_id']);
            $duplicate_ids = isset($_POST['duplicate_ids']) ? array_map('intval', explode(',', $_POST['duplicate_ids'])) : array();
            
            if (!empty($duplicate_ids)) {
                $ids_to_delete = array_diff($duplicate_ids, array($keep_id));
                if (!empty($ids_to_delete)) {
                    $placeholders = implode(',', array_fill(0, count($ids_to_delete), '%d'));
                    $wpdb->query($wpdb->prepare(
                        "DELETE FROM $sessions_table WHERE id IN ($placeholders)",
                        ...$ids_to_delete
                    ));
                    $duplicates_removed = count($ids_to_delete);
                    $message = sprintf('Removed %d duplicate(s), kept session ID %d.', $duplicates_removed, $keep_id);
                }
            }
        }
        
        // Find duplicates (same user, track, car, session_date)
        $duplicates = $wpdb->get_results("
            SELECT 
                user_id,
                track_venue,
                car_type,
                session_date,
                COUNT(*) as duplicate_count,
                GROUP_CONCAT(id ORDER BY id ASC) as session_ids,
                GROUP_CONCAT(valid_laps ORDER BY id ASC) as lap_counts,
                GROUP_CONCAT(created_at ORDER BY id ASC) as created_dates
            FROM $sessions_table
            GROUP BY user_id, track_venue, car_type, session_date
            HAVING COUNT(*) > 1
            ORDER BY duplicate_count DESC, session_date DESC
        ");
        
        // Get total session count
        $total_sessions = $wpdb->get_var("SELECT COUNT(*) FROM $sessions_table");
        $total_duplicates = 0;
        foreach ($duplicates as $dup) {
            $total_duplicates += ($dup->duplicate_count - 1);
        }
        
        ?>
        <div class="wrap">
            <h1><?php _e('Fuel Data Tools', 'apex-notes'); ?></h1>
            
            <?php if ($message): ?>
            <div class="notice notice-success"><p><?php echo esc_html($message); ?></p></div>
            <?php endif; ?>
            
            <div class="card" style="max-width: 100%; margin-bottom: 20px;">
                <h2>Database Statistics</h2>
                <p><strong>Total Sessions:</strong> <?php echo intval($total_sessions); ?></p>
                <p><strong>Duplicate Groups:</strong> <?php echo count($duplicates); ?></p>
                <p><strong>Total Duplicates to Remove:</strong> <?php echo intval($total_duplicates); ?></p>
            </div>
            
            <div class="card" style="max-width: 100%; margin-bottom: 20px;">
                <h2>Remove All Duplicates</h2>
                <p>This will keep the <strong>oldest</strong> session (first uploaded) from each duplicate group and remove all newer duplicates.</p>
                <form method="post" action="">
                    <?php wp_nonce_field('apex_fuel_tools_nonce'); ?>
                    <p>
                        <input type="submit" name="remove_duplicates" class="button button-primary" 
                               value="Remove All Duplicates (<?php echo intval($total_duplicates); ?>)" 
                               onclick="return confirm('Are you sure you want to remove <?php echo intval($total_duplicates); ?> duplicate sessions? This cannot be undone.');"
                               <?php echo $total_duplicates === 0 ? 'disabled' : ''; ?>>
                    </p>
                </form>
            </div>
            
            <?php if (!empty($duplicates)): ?>
            <h2>Duplicate Sessions</h2>
            <p>Sessions with the same user, track, car, and session date. You can choose which one to keep for each group.</p>
            
            <table class="widefat striped">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Track</th>
                        <th>Car</th>
                        <th>Session Date</th>
                        <th>Duplicates</th>
                        <th>Session IDs (Laps)</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($duplicates as $dup): 
                        $user = get_userdata($dup->user_id);
                        $username = $user ? $user->display_name : 'User #' . $dup->user_id;
                        $ids = explode(',', $dup->session_ids);
                        $laps = explode(',', $dup->lap_counts);
                    ?>
                    <tr>
                        <td><?php echo esc_html($username); ?></td>
                        <td><?php echo esc_html($dup->track_venue); ?></td>
                        <td><?php echo esc_html($dup->car_type); ?></td>
                        <td><?php echo esc_html($dup->session_date); ?></td>
                        <td><strong><?php echo intval($dup->duplicate_count); ?></strong></td>
                        <td>
                            <?php 
                            $id_lap_pairs = array();
                            for ($i = 0; $i < count($ids); $i++) {
                                $id_lap_pairs[] = '#' . $ids[$i] . ' (' . ($laps[$i] ?? '?') . ' laps)';
                            }
                            echo esc_html(implode(', ', $id_lap_pairs));
                            ?>
                        </td>
                        <td>
                            <form method="post" action="" style="display: inline;">
                                <?php wp_nonce_field('apex_fuel_tools_nonce'); ?>
                                <input type="hidden" name="duplicate_ids" value="<?php echo esc_attr($dup->session_ids); ?>">
                                <select name="keep_id" style="width: 100px;">
                                    <?php foreach ($ids as $i => $id): ?>
                                    <option value="<?php echo intval($id); ?>">Keep #<?php echo intval($id); ?> (<?php echo intval($laps[$i] ?? 0); ?> laps)</option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="submit" name="remove_duplicate_group" class="button button-small" value="Remove Others">
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div class="notice notice-success"><p>No duplicate sessions found! Your database is clean.</p></div>
            <?php endif; ?>
            
            <hr style="margin: 30px 0;">
            
            <h2>How Duplicates are Identified</h2>
            <p>A session is considered a duplicate if it has the same:</p>
            <ul style="list-style: disc; margin-left: 20px;">
                <li><strong>User ID</strong> - Same uploader</li>
                <li><strong>Track</strong> - Same track venue</li>
                <li><strong>Car</strong> - Same car type</li>
                <li><strong>Session Date</strong> - Same race date/time from the XML</li>
            </ul>
            <p>This typically happens when the same XML file is uploaded multiple times.</p>
        </div>
        <?php
    }
    
    /**
     * Remove duplicate fuel sessions, keeping the oldest (first uploaded) of each group
     */
    private function remove_duplicate_sessions() {
        global $wpdb;
        $sessions_table = $wpdb->prefix . 'apex_notes_fuel_sessions';
        
        // Find all duplicate groups
        $duplicates = $wpdb->get_results("
            SELECT 
                user_id,
                track_venue,
                car_type,
                session_date,
                MIN(id) as keep_id,
                GROUP_CONCAT(id) as all_ids
            FROM $sessions_table
            GROUP BY user_id, track_venue, car_type, session_date
            HAVING COUNT(*) > 1
        ");
        
        $total_removed = 0;
        
        foreach ($duplicates as $dup) {
            $all_ids = explode(',', $dup->all_ids);
            $ids_to_delete = array_diff($all_ids, array($dup->keep_id));
            
            if (!empty($ids_to_delete)) {
                $placeholders = implode(',', array_fill(0, count($ids_to_delete), '%d'));
                $wpdb->query($wpdb->prepare(
                    "DELETE FROM $sessions_table WHERE id IN ($placeholders)",
                    ...$ids_to_delete
                ));
                $total_removed += count($ids_to_delete);
            }
        }
        
        return $total_removed;
    }
    
    /**
     * Render the stewards reports admin page
     */
    public function render_stewards_reports_page() {
        global $wpdb;
        $table = $wpdb->prefix . 'apex_notes_stewards_reports';
        
        // Handle delete action
        if (isset($_POST['delete_report']) && isset($_POST['report_id'])) {
            $report_id = intval($_POST['report_id']);
            if (wp_verify_nonce($_POST['_wpnonce'] ?? '', 'delete_stewards_report_' . $report_id)) {
                $wpdb->delete($table, array('id' => $report_id), array('%d'));
                echo '<div class="notice notice-success"><p>Report deleted successfully.</p></div>';
            }
        }
        
        // Handle bulk delete old reports
        if (isset($_POST['delete_old_reports'])) {
            if (wp_verify_nonce($_POST['_wpnonce'] ?? '', 'delete_old_stewards_reports')) {
                $deleted = $wpdb->query(
                    "DELETE FROM $table WHERE created_at < DATE_SUB(NOW(), INTERVAL 90 DAY)"
                );
                echo '<div class="notice notice-success"><p>' . $deleted . ' reports older than 90 days deleted.</p></div>';
            }
        }
        
        // Get all reports
        $reports = $wpdb->get_results("
            SELECT r.*, u.display_name as author_name, u.user_email as author_email
            FROM $table r
            LEFT JOIN {$wpdb->users} u ON r.user_id = u.ID
            ORDER BY r.created_at DESC
        ");
        
        // Count old reports
        $old_reports_count = $wpdb->get_var(
            "SELECT COUNT(*) FROM $table WHERE created_at < DATE_SUB(NOW(), INTERVAL 90 DAY)"
        );
        
        ?>
        <div class="wrap">
            <h1>Stewards Reports</h1>
            
            <div class="apex-admin-stats" style="display:flex;gap:20px;margin:20px 0;">
                <div style="background:#fff;padding:15px 20px;border-radius:8px;border-left:4px solid #0073aa;">
                    <strong style="font-size:24px;"><?php echo count($reports); ?></strong><br>
                    <span style="color:#666;">Total Reports</span>
                </div>
                <div style="background:#fff;padding:15px 20px;border-radius:8px;border-left:4px solid #dc3545;">
                    <strong style="font-size:24px;"><?php echo $old_reports_count; ?></strong><br>
                    <span style="color:#666;">Reports 90+ Days Old</span>
                </div>
            </div>
            
            <?php if ($old_reports_count > 0): ?>
            <form method="post" style="margin-bottom:20px;">
                <?php wp_nonce_field('delete_old_stewards_reports'); ?>
                <button type="submit" name="delete_old_reports" class="button button-secondary" 
                        onclick="return confirm('Delete all <?php echo $old_reports_count; ?> reports older than 90 days?');">
                    Delete All Reports 90+ Days Old (<?php echo $old_reports_count; ?>)
                </button>
            </form>
            <?php endif; ?>
            
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Report Name</th>
                        <th>Track</th>
                        <th>Author</th>
                        <th>Views</th>
                        <th>Created</th>
                        <th>Age</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($reports)): ?>
                    <tr>
                        <td colspan="8">No stewards reports found.</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($reports as $report): 
                        $created = strtotime($report->created_at);
                        $age_days = floor((time() - $created) / 86400);
                        $share_url = home_url('/stewards/view/' . $report->share_token);
                    ?>
                    <tr <?php if ($age_days >= 90) echo 'style="background:#fff5f5;"'; ?>>
                        <td><?php echo $report->id; ?></td>
                        <td>
                            <strong><?php echo esc_html($report->report_name); ?></strong>
                            <div class="row-actions">
                                <a href="<?php echo esc_url($share_url); ?>" target="_blank">View</a>
                            </div>
                        </td>
                        <td><?php echo esc_html($report->track_name); ?></td>
                        <td>
                            <?php echo esc_html($report->author_name ?: 'Unknown'); ?>
                            <br><small><?php echo esc_html($report->author_email ?? ''); ?></small>
                        </td>
                        <td><?php echo $report->view_count; ?></td>
                        <td><?php echo date('Y-m-d H:i', $created); ?></td>
                        <td>
                            <?php 
                            if ($age_days >= 90) {
                                echo '<span style="color:#dc3545;font-weight:bold;">' . $age_days . ' days</span>';
                            } else {
                                echo $age_days . ' days';
                            }
                            ?>
                        </td>
                        <td>
                            <form method="post" style="display:inline;">
                                <?php wp_nonce_field('delete_stewards_report_' . $report->id); ?>
                                <input type="hidden" name="report_id" value="<?php echo $report->id; ?>">
                                <button type="submit" name="delete_report" class="button button-small" 
                                        onclick="return confirm('Delete this report?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php
    }
}
