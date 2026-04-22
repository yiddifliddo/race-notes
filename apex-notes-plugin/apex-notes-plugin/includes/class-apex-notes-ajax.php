<?php
/**
 * Apex Notes AJAX Handlers
 */

if (!defined('ABSPATH')) {
    exit;
}

class Apex_Notes_Ajax {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        // Public AJAX actions
        add_action('wp_ajax_apex_notes_get_notes', array($this, 'get_notes'));
        add_action('wp_ajax_nopriv_apex_notes_get_notes', array($this, 'get_notes'));
        
        add_action('wp_ajax_apex_notes_get_note', array($this, 'get_note'));
        add_action('wp_ajax_nopriv_apex_notes_get_note', array($this, 'get_note'));
        
        add_action('wp_ajax_apex_notes_get_user_profile', array($this, 'get_user_profile'));
        add_action('wp_ajax_nopriv_apex_notes_get_user_profile', array($this, 'get_user_profile'));
        
        add_action('wp_ajax_apex_notes_get_profile_by_username', array($this, 'get_profile_by_username'));
        add_action('wp_ajax_nopriv_apex_notes_get_profile_by_username', array($this, 'get_profile_by_username'));
        
        // Authenticated AJAX actions
        add_action('wp_ajax_apex_notes_create_note', array($this, 'create_note'));
        add_action('wp_ajax_apex_notes_rate_note', array($this, 'rate_note'));
        add_action('wp_ajax_apex_notes_get_my_notes', array($this, 'get_my_notes'));
        add_action('wp_ajax_apex_notes_vote_note', array($this, 'vote_note'));
        add_action('wp_ajax_apex_notes_update_profile', array($this, 'update_profile'));
        add_action('wp_ajax_apex_notes_post_comment', array($this, 'post_comment'));
        
        // Admin AJAX actions
        add_action('wp_ajax_apex_notes_moderate_note', array($this, 'moderate_note'));
        add_action('wp_ajax_apex_notes_get_pending_notes', array($this, 'get_pending_notes'));
        add_action('wp_ajax_apex_notes_delete_note', array($this, 'delete_note'));
        add_action('wp_ajax_apex_notes_toggle_featured', array($this, 'toggle_featured'));
        add_action('wp_ajax_apex_notes_delete_comment', array($this, 'delete_comment'));
        add_action('wp_ajax_apex_notes_edit_note', array($this, 'edit_note'));
        
        // Follow/Unfollow AJAX actions
        add_action('wp_ajax_apex_notes_follow_user', array($this, 'follow_user'));
        add_action('wp_ajax_apex_notes_unfollow_user', array($this, 'unfollow_user'));
        add_action('wp_ajax_apex_notes_get_followers', array($this, 'get_followers'));
        add_action('wp_ajax_apex_notes_get_following', array($this, 'get_following'));
        add_action('wp_ajax_nopriv_apex_notes_get_followers', array($this, 'get_followers'));
        add_action('wp_ajax_nopriv_apex_notes_get_following', array($this, 'get_following'));
        
        // Notification AJAX actions
        add_action('wp_ajax_apex_notes_get_notifications', array($this, 'get_notifications'));
        add_action('wp_ajax_apex_notes_get_notification_count', array($this, 'get_notification_count'));
        add_action('wp_ajax_apex_notes_mark_notification_read', array($this, 'mark_notification_read'));
        add_action('wp_ajax_apex_notes_mark_all_notifications_read', array($this, 'mark_all_notifications_read'));
        
        // AI Chat action
        add_action('wp_ajax_apex_notes_ai_chat', array($this, 'ai_chat'));
        add_action('wp_ajax_nopriv_apex_notes_ai_chat', array($this, 'ai_chat'));
        
        // Live Events AJAX actions (manual only - no YouTube API)
        add_action('wp_ajax_apex_notes_set_event_alert', array($this, 'set_event_alert'));
        add_action('wp_ajax_apex_notes_get_event_alerts', array($this, 'get_event_alerts'));
        add_action('wp_ajax_apex_notes_import_race_calendars', array($this, 'import_race_calendars'));
        add_action('wp_ajax_apex_notes_add_manual_event', array($this, 'add_manual_event'));
        add_action('wp_ajax_apex_notes_delete_event', array($this, 'delete_event'));
        
        // Fuel Data / XML Upload AJAX actions
        add_action('wp_ajax_apex_notes_upload_xml', array($this, 'upload_xml'));
        add_action('wp_ajax_apex_notes_confirm_fuel_upload', array($this, 'confirm_fuel_upload'));
        add_action('wp_ajax_apex_notes_save_lmu_driver_name', array($this, 'save_lmu_driver_name'));
        add_action('wp_ajax_apex_notes_get_fuel_sessions', array($this, 'get_fuel_sessions'));
        add_action('wp_ajax_apex_notes_get_fuel_session', array($this, 'get_fuel_session'));
        add_action('wp_ajax_apex_notes_share_fuel_session', array($this, 'share_fuel_session'));
        add_action('wp_ajax_apex_notes_unshare_fuel_session', array($this, 'unshare_fuel_session'));
        add_action('wp_ajax_apex_notes_delete_fuel_session', array($this, 'delete_fuel_session'));
        add_action('wp_ajax_apex_notes_get_community_fuel_data', array($this, 'get_community_fuel_data'));
        add_action('wp_ajax_nopriv_apex_notes_get_community_fuel_data', array($this, 'get_community_fuel_data'));
        add_action('wp_ajax_apex_notes_get_car_specs', array($this, 'get_car_specs'));
        add_action('wp_ajax_nopriv_apex_notes_get_car_specs', array($this, 'get_car_specs'));

        // Pit strategy calculator + AI fuel chat
        add_action('wp_ajax_apex_notes_calculate_pit_strategy', array($this, 'calculate_pit_strategy'));
        add_action('wp_ajax_apex_notes_ai_fuel_chat', array($this, 'ai_fuel_chat'));
        
        // Tire Data AJAX actions
        add_action('wp_ajax_apex_notes_get_tire_tracks', array($this, 'get_tire_tracks'));
        add_action('wp_ajax_nopriv_apex_notes_get_tire_tracks', array($this, 'get_tire_tracks'));
        add_action('wp_ajax_apex_notes_calculate_tire_strategy', array($this, 'calculate_tire_strategy'));
        add_action('wp_ajax_nopriv_apex_notes_calculate_tire_strategy', array($this, 'calculate_tire_strategy'));
        add_action('wp_ajax_apex_notes_get_tire_community_data', array($this, 'get_tire_community_data'));
        add_action('wp_ajax_nopriv_apex_notes_get_tire_community_data', array($this, 'get_tire_community_data'));
        
        // Stewards Room AJAX actions
        add_action('wp_ajax_apex_notes_parse_stewards_xml', array($this, 'parse_stewards_xml'));
        add_action('wp_ajax_nopriv_apex_notes_parse_stewards_xml', array($this, 'parse_stewards_xml'));
        add_action('wp_ajax_apex_notes_generate_stewards_pdf', array($this, 'generate_stewards_pdf'));
        add_action('wp_ajax_nopriv_apex_notes_generate_stewards_pdf', array($this, 'generate_stewards_pdf'));
        
        // Stewards Report Save/Share AJAX actions
        add_action('wp_ajax_apex_notes_save_stewards_report', array($this, 'save_stewards_report'));
        add_action('wp_ajax_apex_notes_get_my_stewards_reports', array($this, 'get_my_stewards_reports'));
        add_action('wp_ajax_apex_notes_delete_stewards_report', array($this, 'delete_stewards_report'));
        add_action('wp_ajax_apex_notes_get_shared_report', array($this, 'get_shared_report'));
        add_action('wp_ajax_nopriv_apex_notes_get_shared_report', array($this, 'get_shared_report'));
        
        // Live Broadcast AJAX actions
        add_action('wp_ajax_apex_notes_start_broadcast', array($this, 'start_broadcast'));
        add_action('wp_ajax_apex_notes_end_broadcast', array($this, 'end_broadcast'));
        add_action('wp_ajax_apex_notes_get_my_broadcast', array($this, 'get_my_broadcast'));
        add_action('wp_ajax_apex_notes_update_broadcast_stream', array($this, 'update_broadcast_stream'));
        add_action('wp_ajax_apex_notes_get_active_broadcasts', array($this, 'get_active_broadcasts'));
        add_action('wp_ajax_nopriv_apex_notes_get_active_broadcasts', array($this, 'get_active_broadcasts'));
        add_action('wp_ajax_apex_notes_get_live_telemetry', array($this, 'get_live_telemetry'));
        add_action('wp_ajax_nopriv_apex_notes_get_live_telemetry', array($this, 'get_live_telemetry'));
        
        // Affiliate Links AJAX actions
        add_action('wp_ajax_apex_notes_get_affiliate_links', array($this, 'get_affiliate_links'));
        add_action('wp_ajax_apex_notes_add_affiliate_link', array($this, 'add_affiliate_link'));
        add_action('wp_ajax_apex_notes_remove_affiliate_link', array($this, 'remove_affiliate_link'));
        
        // Livery Sharing AJAX actions
        add_action('wp_ajax_apex_notes_upload_livery', array($this, 'upload_livery'));
        add_action('wp_ajax_apex_notes_get_user_livery', array($this, 'get_user_livery'));
        add_action('wp_ajax_nopriv_apex_notes_get_user_livery', array($this, 'get_user_livery'));
        add_action('wp_ajax_apex_notes_delete_livery', array($this, 'delete_livery'));
        add_action('wp_ajax_apex_notes_get_livery_download', array($this, 'get_livery_download'));
        add_action('wp_ajax_nopriv_apex_notes_get_livery_download', array($this, 'get_livery_download'));
        add_action('wp_ajax_apex_notes_download_livery_file', array($this, 'download_livery_file'));
        add_action('wp_ajax_nopriv_apex_notes_download_livery_file', array($this, 'download_livery_file'));
        
        // Live Broadcast API endpoint (for SimHub plugin - no nonce, uses broadcast key)
        add_action('wp_ajax_apex_notes_push_telemetry', array($this, 'push_telemetry'));
        add_action('wp_ajax_nopriv_apex_notes_push_telemetry', array($this, 'push_telemetry'));
        
        // Ban/Unban users (admin only)
        add_action('wp_ajax_apex_notes_ban_user', array($this, 'ban_user'));
        add_action('wp_ajax_apex_notes_unban_user', array($this, 'unban_user'));
        add_action('wp_ajax_apex_notes_get_banned_users', array($this, 'get_banned_users'));
    }
    
    /**
     * Verify nonce
     */
    private function verify_nonce() {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'apex_notes_nonce')) {
            wp_send_json_error(array('message' => 'Security check failed.'));
            exit;
        }
    }
    
    /**
     * Get notes (public)
     */
    public function get_notes() {
        $this->verify_nonce();
        
        $args = array(
            'status' => 'approved',
            'car_class' => isset($_POST['car_class']) ? sanitize_text_field($_POST['car_class']) : '',
            'track_id' => isset($_POST['track_id']) ? sanitize_text_field($_POST['track_id']) : '',
            'car_id' => isset($_POST['car_id']) ? sanitize_text_field($_POST['car_id']) : '',
            'difficulty' => isset($_POST['difficulty']) ? sanitize_text_field($_POST['difficulty']) : '',
            'search' => isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '',
            'orderby' => isset($_POST['orderby']) ? sanitize_text_field($_POST['orderby']) : 'created_at',
            'order' => isset($_POST['order']) ? sanitize_text_field($_POST['order']) : 'DESC',
        );
        
        $notes = Apex_Notes_DB::get_notes($args);
        
        wp_send_json_success(array('notes' => $notes));
    }
    
    /**
     * Get single note (public)
     */
    public function get_note() {
        $this->verify_nonce();
        
        $note_id = isset($_POST['note_id']) ? intval($_POST['note_id']) : 0;
        
        if (!$note_id) {
            wp_send_json_error(array('message' => 'Invalid note ID.'));
            exit;
        }
        
        $note = Apex_Notes_DB::get_note($note_id);
        
        if (!$note) {
            wp_send_json_error(array('message' => 'Note not found.'));
            exit;
        }
        
        // Get user's vote status
        if (is_user_logged_in()) {
            $note['user_vote'] = Apex_Notes_DB::get_user_vote($note_id, get_current_user_id());
        } else {
            $note['user_vote'] = 0;
        }
        
        // Get comments for this note
        $note['comments'] = $this->get_note_comments($note_id);
        
        // Increment views
        Apex_Notes_DB::increment_views($note_id);
        $note['views']++;
        
        wp_send_json_success(array('note' => $note));
    }
    
    /**
     * Get comments for a note
     */
    private function get_note_comments($note_id) {
        global $wpdb;
        
        $table = $wpdb->prefix . 'apex_notes_comments';
        
        $comments = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table WHERE note_id = %d AND status = 'approved' ORDER BY created_at DESC",
            $note_id
        ), ARRAY_A);
        
        foreach ($comments as &$comment) {
            $comment['author'] = Apex_Notes_DB::get_author_info($comment['user_id']);
        }
        
        return $comments;
    }
    
    /**
     * Create note (authenticated)
     */
    public function create_note() {
        $this->verify_nonce();
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => 'You must be logged in to create notes.'));
            exit;
        }
        
        // Check if user is banned
        if (Apex_Notes_DB::is_user_banned(get_current_user_id())) {
            wp_send_json_error(array('message' => 'Your account has been banned. You cannot create notes.'));
            exit;
        }
        
        // Validate required fields
        $required = array('title', 'description', 'car_class', 'car_id', 'track_id', 'difficulty');
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                wp_send_json_error(array('message' => "Missing required field: $field"));
                exit;
            }
        }
        
        $data = array(
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'car_class' => $_POST['car_class'],
            'car_id' => $_POST['car_id'],
            'track_id' => $_POST['track_id'],
            'track_layout' => isset($_POST['track_layout']) ? $_POST['track_layout'] : '',
            'difficulty' => $_POST['difficulty'],
            'lap_time' => isset($_POST['lap_time']) ? $_POST['lap_time'] : '',
            'setup_notes' => isset($_POST['setup_notes']) ? $_POST['setup_notes'] : '',
            'sections' => isset($_POST['sections']) ? $_POST['sections'] : array(),
        );
        
        $note_id = Apex_Notes_DB::create_note($data);
        
        if ($note_id) {
            wp_send_json_success(array(
                'message' => 'Note submitted for moderation!',
                'note_id' => $note_id,
            ));
        } else {
            wp_send_json_error(array('message' => 'Failed to create note.'));
        }
    }
    
    /**
     * Rate note (authenticated)
     */
    public function rate_note() {
        $this->verify_nonce();
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => 'You must be logged in to rate notes.'));
            exit;
        }
        
        $note_id = isset($_POST['note_id']) ? intval($_POST['note_id']) : 0;
        $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;
        
        if (!$note_id || $rating < 1 || $rating > 5) {
            wp_send_json_error(array('message' => 'Invalid rating.'));
            exit;
        }
        
        $result = Apex_Notes_DB::add_rating($note_id, get_current_user_id(), $rating);
        
        wp_send_json_success(array(
            'message' => 'Thank you for rating!',
            'rating' => $result['rating'],
            'count' => $result['count'],
        ));
    }
    
    /**
     * Get my notes (authenticated)
     */
    public function get_my_notes() {
        $this->verify_nonce();
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => 'You must be logged in.'));
            exit;
        }
        
        $notes = Apex_Notes_DB::get_notes(array(
            'user_id' => get_current_user_id(),
            'status' => '', // Get all statuses
        ));
        
        wp_send_json_success(array('notes' => $notes));
    }
    
    /**
     * Moderate note (admin)
     */
    public function moderate_note() {
        $this->verify_nonce();
        
        if (!current_user_can('moderate_apex_notes')) {
            wp_send_json_error(array('message' => 'You do not have permission to moderate.'));
            exit;
        }
        
        $note_id = isset($_POST['note_id']) ? intval($_POST['note_id']) : 0;
        $action = isset($_POST['mod_action']) ? sanitize_text_field($_POST['mod_action']) : '';
        
        if (!$note_id || !in_array($action, array('approve', 'reject'))) {
            wp_send_json_error(array('message' => 'Invalid request.'));
            exit;
        }
        
        // Get note info before updating
        $note = Apex_Notes_DB::get_note($note_id);
        
        $status = $action === 'approve' ? 'approved' : 'rejected';
        $result = Apex_Notes_DB::update_note_status($note_id, $status);
        
        if ($result !== false) {
            // Notify followers when note is approved
            if ($action === 'approve' && $note) {
                Apex_Notes_DB::notify_followers_new_post($note['user_id'], $note_id);
            }
            
            wp_send_json_success(array(
                'message' => $action === 'approve' ? 'Note approved and published!' : 'Note rejected.',
            ));
        } else {
            wp_send_json_error(array('message' => 'Failed to update note.'));
        }
    }
    
    /**
     * Get pending notes (admin)
     */
    public function get_pending_notes() {
        $this->verify_nonce();
        
        if (!current_user_can('moderate_apex_notes')) {
            wp_send_json_error(array('message' => 'You do not have permission.'));
            exit;
        }
        
        $status = isset($_POST['status']) ? sanitize_text_field($_POST['status']) : 'pending';
        
        $notes = Apex_Notes_DB::get_notes(array(
            'status' => $status,
        ));
        
        $pending_count = Apex_Notes_DB::get_pending_count();
        
        wp_send_json_success(array(
            'notes' => $notes,
            'pending_count' => $pending_count,
        ));
    }
    
    /**
     * Delete note (admin)
     */
    public function delete_note() {
        $this->verify_nonce();
        
        if (!current_user_can('moderate_apex_notes')) {
            wp_send_json_error(array('message' => 'You do not have permission.'));
            exit;
        }
        
        $note_id = isset($_POST['note_id']) ? intval($_POST['note_id']) : 0;
        
        if (!$note_id) {
            wp_send_json_error(array('message' => 'Invalid note ID.'));
            exit;
        }
        
        $result = Apex_Notes_DB::delete_note($note_id);
        
        if ($result) {
            wp_send_json_success(array('message' => 'Note deleted.'));
        } else {
            wp_send_json_error(array('message' => 'Failed to delete note.'));
        }
    }
    
    /**
     * Toggle featured status (admin)
     */
    public function toggle_featured() {
        $this->verify_nonce();
        
        if (!current_user_can('moderate_apex_notes')) {
            wp_send_json_error(array('message' => 'You do not have permission.'));
            exit;
        }
        
        global $wpdb;
        
        $note_id = isset($_POST['note_id']) ? intval($_POST['note_id']) : 0;
        
        if (!$note_id) {
            wp_send_json_error(array('message' => 'Invalid note ID.'));
            exit;
        }
        
        $table = $wpdb->prefix . 'apex_notes';
        $current = $wpdb->get_var($wpdb->prepare("SELECT featured FROM $table WHERE id = %d", $note_id));
        $new_status = $current ? 0 : 1;
        
        $wpdb->update($table, array('featured' => $new_status), array('id' => $note_id));
        
        wp_send_json_success(array(
            'message' => $new_status ? 'Note featured!' : 'Note unfeatured.',
            'featured' => $new_status,
        ));
    }
    
    /**
     * Vote on a note (thumbs up/down)
     */
    public function vote_note() {
        $this->verify_nonce();
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => 'Please log in to vote.'));
            exit;
        }
        
        $note_id = isset($_POST['note_id']) ? intval($_POST['note_id']) : 0;
        $vote = isset($_POST['vote']) ? intval($_POST['vote']) : 0;
        
        if (!$note_id) {
            wp_send_json_error(array('message' => 'Invalid note ID.'));
            exit;
        }
        
        // Validate vote value (-1, 0, or 1)
        if (!in_array($vote, array(-1, 0, 1))) {
            wp_send_json_error(array('message' => 'Invalid vote value.'));
            exit;
        }
        
        $result = Apex_Notes_DB::vote_note($note_id, get_current_user_id(), $vote);
        
        wp_send_json_success(array(
            'upvotes' => $result['upvotes'],
            'downvotes' => $result['downvotes'],
            'user_vote' => $vote,
        ));
    }
    
    /**
     * Get user profile
     */
    public function get_user_profile() {
        $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
        
        if (!$user_id) {
            wp_send_json_error(array('message' => 'Invalid user ID.'));
            exit;
        }
        
        $profile = Apex_Notes_Auth::get_user_profile($user_id);
        
        if (!$profile) {
            wp_send_json_error(array('message' => 'User not found.'));
            exit;
        }
        
        // Get user's notes
        $notes = Apex_Notes_DB::get_user_notes($user_id);
        
        // Count total upvotes received
        global $wpdb;
        $table = $wpdb->prefix . 'apex_notes';
        $total_upvotes = $wpdb->get_var($wpdb->prepare(
            "SELECT SUM(upvotes) FROM $table WHERE user_id = %d AND status = 'approved'",
            $user_id
        ));
        
        $profile['notes'] = $notes;
        $profile['notes_count'] = count($notes);
        $profile['total_upvotes'] = (int)$total_upvotes;
        
        // Add follower/following data
        $profile['follower_count'] = Apex_Notes_DB::get_follower_count($user_id);
        $profile['following_count'] = Apex_Notes_DB::get_following_count($user_id);
        
        // Check if current user is following this profile
        $current_user_id = get_current_user_id();
        $profile['is_following'] = false;
        if ($current_user_id && $current_user_id != $user_id) {
            $profile['is_following'] = Apex_Notes_DB::is_following($current_user_id, $user_id);
        }
        
        // Get join date
        $user = get_userdata($user_id);
        if ($user) {
            $profile['joined'] = date('F Y', strtotime($user->user_registered));
        }
        
        wp_send_json_success($profile);
    }
    
    /**
     * Get user profile by username
     */
    public function get_profile_by_username() {
        $username = isset($_POST['username']) ? sanitize_text_field($_POST['username']) : '';
        
        if (empty($username)) {
            wp_send_json_error(array('message' => 'Username required.'));
            exit;
        }
        
        // Find user by login name or display name
        $user = get_user_by('login', $username);
        if (!$user) {
            $user = get_user_by('slug', $username);
        }
        if (!$user) {
            // Try display name (case insensitive)
            global $wpdb;
            $user_id = $wpdb->get_var($wpdb->prepare(
                "SELECT ID FROM $wpdb->users WHERE LOWER(display_name) = LOWER(%s) LIMIT 1",
                $username
            ));
            if ($user_id) {
                $user = get_user_by('id', $user_id);
            }
        }
        
        if (!$user) {
            wp_send_json_error(array('message' => 'User not found.'));
            exit;
        }
        
        $profile = Apex_Notes_Auth::get_user_profile($user->ID);
        
        if (!$profile) {
            wp_send_json_error(array('message' => 'Profile not found.'));
            exit;
        }
        
        wp_send_json_success(array('profile' => $profile));
    }
    
    /**
     * Update user profile
     */
    public function update_profile() {
        $this->verify_nonce();
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => 'Please log in.'));
            exit;
        }
        
        $user_id = get_current_user_id();
        
        $data = array(
            'bio' => isset($_POST['bio']) ? $_POST['bio'] : null,
            'discord_username' => isset($_POST['discord_username']) ? $_POST['discord_username'] : null,
            'steam_id' => isset($_POST['steam_id']) ? $_POST['steam_id'] : null,
            'display_name' => isset($_POST['display_name']) ? $_POST['display_name'] : null,
        );
        
        // Remove null values
        $data = array_filter($data, function($v) { return $v !== null; });
        
        if (Apex_Notes_Auth::update_user_profile($user_id, $data)) {
            $profile = Apex_Notes_Auth::get_user_profile($user_id);
            wp_send_json_success(array(
                'message' => 'Profile updated successfully!',
                'profile' => $profile,
            ));
        } else {
            wp_send_json_error(array('message' => 'Failed to update profile.'));
        }
    }
    
    /**
     * Post a comment
     */
    public function post_comment() {
        $this->verify_nonce();
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => 'Please log in to comment.'));
            exit;
        }
        
        $note_id = isset($_POST['note_id']) ? intval($_POST['note_id']) : 0;
        $comment_text = isset($_POST['comment']) ? sanitize_textarea_field($_POST['comment']) : '';
        
        if (!$note_id || !$comment_text) {
            wp_send_json_error(array('message' => 'Please enter a comment.'));
            exit;
        }
        
        // Check profanity
        $has_profanity = Apex_Notes_Profanity::check($comment_text);
        
        global $wpdb;
        $table = $wpdb->prefix . 'apex_notes_comments';
        $current_user_id = get_current_user_id();
        
        $result = $wpdb->insert($table, array(
            'note_id' => $note_id,
            'user_id' => $current_user_id,
            'comment' => $comment_text,
            'status' => $has_profanity ? 'pending' : 'approved',
            'has_profanity' => $has_profanity ? 1 : 0,
        ));
        
        if ($result) {
            $comment_id = $wpdb->insert_id;
            $comment = array(
                'id' => $comment_id,
                'comment' => $comment_text,
                'created_at' => current_time('mysql'),
                'author' => Apex_Notes_DB::get_author_info($current_user_id),
            );
            
            // Notify note author if it's not their own comment
            if (!$has_profanity) {
                $note = Apex_Notes_DB::get_note($note_id);
                if ($note && $note['user_id'] != $current_user_id) {
                    Apex_Notes_DB::create_notification(
                        $note['user_id'],
                        'new_comment',
                        $current_user_id,
                        $note_id,
                        $comment_id
                    );
                }
            }
            
            wp_send_json_success(array(
                'message' => $has_profanity ? 'Comment submitted for review.' : 'Comment posted!',
                'comment' => $comment,
            ));
        } else {
            wp_send_json_error(array('message' => 'Failed to post comment.'));
        }
    }
    
    /**
     * Delete a comment (admin)
     */
    public function delete_comment() {
        $this->verify_nonce();
        
        if (!current_user_can('moderate_apex_notes')) {
            wp_send_json_error(array('message' => 'You do not have permission.'));
            exit;
        }
        
        $comment_id = isset($_POST['comment_id']) ? intval($_POST['comment_id']) : 0;
        
        if (!$comment_id) {
            wp_send_json_error(array('message' => 'Invalid comment ID.'));
            exit;
        }
        
        global $wpdb;
        $table = $wpdb->prefix . 'apex_notes_comments';
        
        $result = $wpdb->delete($table, array('id' => $comment_id));
        
        if ($result) {
            wp_send_json_success(array('message' => 'Comment deleted.'));
        } else {
            wp_send_json_error(array('message' => 'Failed to delete comment.'));
        }
    }
    
    /**
     * Edit a note (admin)
     */
    public function edit_note() {
        $this->verify_nonce();
        
        $note_id = isset($_POST['note_id']) ? intval($_POST['note_id']) : 0;
        
        if (!$note_id) {
            wp_send_json_error(array('message' => 'Invalid note ID.'));
            exit;
        }
        
        global $wpdb;
        $table = $wpdb->prefix . 'apex_notes';
        $sections_table = $wpdb->prefix . 'apex_notes_sections';
        
        // Get the note to check ownership
        $note = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE id = %d", $note_id));
        
        if (!$note) {
            wp_send_json_error(array('message' => 'Note not found.'));
            exit;
        }
        
        // Check permission - must be owner or moderator
        $current_user_id = get_current_user_id();
        if ($note->user_id != $current_user_id && !current_user_can('moderate_apex_notes')) {
            wp_send_json_error(array('message' => 'You do not have permission to edit this note.'));
            exit;
        }
        
        $data = array();
        
        if (isset($_POST['title'])) {
            $data['title'] = sanitize_text_field($_POST['title']);
        }
        if (isset($_POST['description'])) {
            $data['description'] = sanitize_textarea_field($_POST['description']);
        }
        if (isset($_POST['lap_time'])) {
            $data['lap_time'] = sanitize_text_field($_POST['lap_time']);
        }
        if (isset($_POST['setup_notes'])) {
            $data['setup_notes'] = sanitize_textarea_field($_POST['setup_notes']);
        }
        
        // Update note
        if (!empty($data)) {
            $wpdb->update($table, $data, array('id' => $note_id));
        }
        
        // Handle sections if provided
        if (isset($_POST['sections'])) {
            $sections = json_decode(stripslashes($_POST['sections']), true);
            
            if (is_array($sections)) {
                // Delete existing sections
                $wpdb->delete($sections_table, array('note_id' => $note_id));
                
                // Insert new sections
                foreach ($sections as $index => $section) {
                    // Skip empty sections (no name)
                    if (empty($section['name'])) {
                        continue;
                    }
                    
                    $wpdb->insert($sections_table, array(
                        'note_id' => $note_id,
                        'section_order' => $index,
                        'name' => sanitize_text_field($section['name']),
                        'braking' => sanitize_text_field($section['braking'] ?? ''),
                        'turn_in' => sanitize_text_field($section['turn_in'] ?? ''),
                        'apex' => sanitize_text_field($section['apex'] ?? ''),
                        'exit_point' => sanitize_text_field($section['exit_point'] ?? ''),
                        'gear' => sanitize_text_field($section['gear'] ?? ''),
                        'speed' => sanitize_text_field($section['speed'] ?? ''),
                        'pro_tip' => sanitize_text_field($section['pro_tip'] ?? '')
                    ));
                }
            }
        }
        
        wp_send_json_success(array('message' => 'Note updated successfully!'));
    }
    
    /**
     * Follow a user
     */
    public function follow_user() {
        $this->verify_nonce();
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => 'Please log in to follow users.'));
            exit;
        }
        
        $user_id = intval($_POST['user_id']);
        $current_user_id = get_current_user_id();
        
        if ($user_id == $current_user_id) {
            wp_send_json_error(array('message' => 'You cannot follow yourself.'));
            exit;
        }
        
        $result = Apex_Notes_DB::follow_user($current_user_id, $user_id);
        
        if ($result) {
            wp_send_json_success(array(
                'message' => 'Successfully followed user!',
                'follower_count' => Apex_Notes_DB::get_follower_count($user_id),
            ));
        } else {
            wp_send_json_error(array('message' => 'Failed to follow user.'));
        }
    }
    
    /**
     * Unfollow a user
     */
    public function unfollow_user() {
        $this->verify_nonce();
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => 'Please log in.'));
            exit;
        }
        
        $user_id = intval($_POST['user_id']);
        $current_user_id = get_current_user_id();
        
        $result = Apex_Notes_DB::unfollow_user($current_user_id, $user_id);
        
        if ($result) {
            wp_send_json_success(array(
                'message' => 'Successfully unfollowed user.',
                'follower_count' => Apex_Notes_DB::get_follower_count($user_id),
            ));
        } else {
            wp_send_json_error(array('message' => 'Failed to unfollow user.'));
        }
    }
    
    /**
     * Get notifications for current user
     */
    public function get_notifications() {
        $this->verify_nonce();
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => 'Please log in.'));
            exit;
        }
        
        $user_id = get_current_user_id();
        $notifications = Apex_Notes_DB::get_notifications($user_id, 20);
        
        wp_send_json_success(array(
            'notifications' => $notifications,
            'unread_count' => Apex_Notes_DB::get_unread_notification_count($user_id),
        ));
    }
    
    /**
     * Get notification count for current user
     */
    public function get_notification_count() {
        if (!is_user_logged_in()) {
            wp_send_json_success(array('count' => 0));
            exit;
        }
        
        $user_id = get_current_user_id();
        $count = Apex_Notes_DB::get_unread_notification_count($user_id);
        
        wp_send_json_success(array('count' => $count));
    }
    
    /**
     * Mark a notification as read
     */
    public function mark_notification_read() {
        $this->verify_nonce();
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => 'Please log in.'));
            exit;
        }
        
        $notification_id = intval($_POST['notification_id']);
        $user_id = get_current_user_id();
        
        Apex_Notes_DB::mark_notification_read($notification_id, $user_id);
        
        wp_send_json_success(array(
            'unread_count' => Apex_Notes_DB::get_unread_notification_count($user_id),
        ));
    }
    
    /**
     * Mark all notifications as read
     */
    public function mark_all_notifications_read() {
        $this->verify_nonce();
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => 'Please log in.'));
            exit;
        }
        
        $user_id = get_current_user_id();
        Apex_Notes_DB::mark_all_notifications_read($user_id);
        
        wp_send_json_success(array('unread_count' => 0));
    }
    
    /**
     * Get followers for a user
     */
    public function get_followers() {
        $this->verify_nonce();
        
        $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
        
        if (!$user_id) {
            wp_send_json_error(array('message' => 'Invalid user ID.'));
            exit;
        }
        
        $followers = Apex_Notes_DB::get_followers($user_id);
        $current_user_id = get_current_user_id();
        
        // Add is_following status for each follower
        foreach ($followers as &$follower) {
            if ($current_user_id && $current_user_id != $follower['id']) {
                $follower['is_following'] = Apex_Notes_DB::is_following($current_user_id, $follower['id']);
            } else {
                $follower['is_following'] = false;
            }
        }
        
        wp_send_json_success(array('users' => $followers));
    }
    
    /**
     * Get users that a user is following
     */
    public function get_following() {
        $this->verify_nonce();
        
        $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
        
        if (!$user_id) {
            wp_send_json_error(array('message' => 'Invalid user ID.'));
            exit;
        }
        
        $following = Apex_Notes_DB::get_following($user_id);
        $current_user_id = get_current_user_id();
        
        // Add is_following status for each user
        foreach ($following as &$user) {
            if ($current_user_id && $current_user_id != $user['id']) {
                $user['is_following'] = Apex_Notes_DB::is_following($current_user_id, $user['id']);
            } else {
                $user['is_following'] = false;
            }
        }
        
        wp_send_json_success(array('users' => $following));
    }
    
    /**
     * AI Chat - Answer questions about racing circuits
     */
    public function ai_chat() {
        $this->verify_nonce();
        
        $message = isset($_POST['message']) ? sanitize_textarea_field($_POST['message']) : '';
        $history = isset($_POST['history']) ? json_decode(stripslashes($_POST['history']), true) : array();
        
        if (empty($message)) {
            wp_send_json_error(array('message' => 'Please enter a message.'));
            exit;
        }
        
        // Get API key from options
        $api_key = get_option('apex_notes_anthropic_api_key', '');
        
        if (empty($api_key)) {
            // Return a helpful message about motorsport circuits without API
            $reply = $this->get_fallback_circuit_response($message);
            wp_send_json_success(array('reply' => $reply));
            exit;
        }
        
        // Build messages for API
        $messages = array();
        
        // Add conversation history
        if (!empty($history) && is_array($history)) {
            foreach ($history as $msg) {
                if (isset($msg['role']) && isset($msg['content'])) {
                    $messages[] = array(
                        'role' => $msg['role'],
                        'content' => $msg['content']
                    );
                }
            }
        }
        
        // Add current message
        $messages[] = array(
            'role' => 'user',
            'content' => $message
        );
        
        // System prompt for the AI - STRICT focus on tracks and setups only
        $system_prompt = "You are ApexTrackBot, a specialized assistant that ONLY answers questions about:

1. MOTORSPORT RACE TRACKS - Turn names, track layouts, circuit history, track characteristics, lengths, elevation changes, corner sequences, famous races, FIA/FIM grades
2. CAR SETUPS for sim racing - Tyre pressures, suspension settings, aero balance, differential settings, brake bias, ride heights, spring rates, dampers, anti-roll bars
3. SIM RACING ERGONOMICS - Seat position, driving position, pedal placement, wheel height, cockpit/rig setup

DATA SOURCES you can search for track information:
- racingcircuits.info (700+ circuits with turn names, maps, history)
- silhouet.com/motorsport/tracks (worldwide track database)
- motorsportmagazine.com/database/circuits (race history and details)

DATA SOURCES for car setups:
- ultimatesetuphub.com/setups (community Le Mans Ultimate setups)
- coachdaveacademy.com (professional setup guides)


DATA SOURCES for sim racing ergonomics:
- qubicsystem.com/2023/01/gt-proper-seating-position/ (GT seating position guide)

SETUP FLOWCHART - ADJUSTMENT ORDER:

1. CORNER EXIT GRIP (Primary - adjust first):
- WHEEL RATE: Stiffer springs = lower ride height but less grip over bumps. Front/rear ratio should match weight distribution.
- RIDE HEIGHT: Minimum possible without clearance issues. Rake affects aero balance.
- TYRE PRESSURE & CAMBER: Follow bell curve for optimal grip. Look for even, inside-biased tire wear. Too low/high pressure or camber reduces grip.
- ANTI-ROLL BARS: Understeer = soften front and/or stiffen rear. Oversteer = stiffen front and/or soften rear. Adjust proportionally to maintain overall roll stiffness.
- DIFF ACCELERATION LOCK: Use minimum that prevents inside wheelspin in slowest corner.
- DAMPERS (HIGH-SPEED): Minimum rate that avoids chassis oscillation. Rebound typically 1-1.5x compression rate. F/R ratio should equal spring rate ratio.

2. CORNER ENTRY BALANCE (Secondary - after exit grip is sorted):
- BRAKE BIAS: Set so fronts lock momentarily before rears in straight-line threshold braking.
- ENGINE BRAKE MAPPING: Understeer = increase engine brake. Oversteer = decrease engine brake.
- DIFF PRELOAD & DECEL LOCK: Understeer = decrease lock. Oversteer = increase lock.
- DAMPERS (LOW-SPEED): Understeer = soften front and/or stiffen rear. Oversteer = stiffen front and/or soften rear.
- Then revisit BRAKE BIAS and ARBs if needed.

3. DRIVER PREFERENCE (Tertiary - fine-tuning):
- REAR TOE: Toe-in smooths turn-in response. Recommend 0 to minimal toe-in. High settings increase heat and drag. Toe-out increases turn-in but not recommended.
- FRONT TOE: Toe-in increases turn-in rate and centering force. Toe-out smooths turn-in and reduces centering force. Recommend 0 to minimal toe-out.
- DAMPERS (LOW-SPEED): Higher values quicken chassis response, aids chicane transitions.
- CASTER: Higher settings strengthen centering force. Generally beneficial unless steering becomes too heavy.

SEAT POSITION KNOWLEDGE:
- SEAT ANGLE: F1-style = 100-110° reclined, GT-style = 45-55° backrest with 32-40° base slope
- LEG POSITION: 130° knee bend when pressing pedals, never lock straight, knees aligned with hips
- ARM POSITION: 90-100° elbow angle at 9 & 3 on wheel, top of wheel at neck height
- PEDALS: Position so knees align with hips, angle slightly away, height below hip level
- LUMBAR: Use support to eliminate lower back gaps, shoulders stay pressed into seat during turns
- Note: No universal perfect position - prioritize comfort, circulation, and muscle balance

CRITICAL RULES:
1. ONLY answer questions about motorsport tracks, car setups, OR sim racing ergonomics
2. If asked about ANYTHING ELSE (weather, news, coding, general knowledge, etc.), politely decline and redirect to tracks/setups
3. Never provide information unrelated to racing circuits, car setups, or cockpit ergonomics
4. Be specific with turn names and setup values
5. Recommend visiting the data sources for more detailed information";
        
        // Call Anthropic API
        $response = wp_remote_post('https://api.anthropic.com/v1/messages', array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'x-api-key' => $api_key,
                'anthropic-version' => '2023-06-01'
            ),
            'body' => json_encode(array(
                'model' => 'claude-sonnet-4-20250514',
                'max_tokens' => 1024,
                'system' => $system_prompt,
                'messages' => $messages,
                'tools' => array(
                    array(
                        'type' => 'web_search_20250305',
                        'name' => 'web_search'
                    )
                )
            )),
            'timeout' => 60
        ));
        
        if (is_wp_error($response)) {
            wp_send_json_error(array('message' => 'API connection error.'));
            exit;
        }
        
        $body = json_decode(wp_remote_retrieve_body($response), true);
        
        if (isset($body['content'])) {
            $reply = '';
            foreach ($body['content'] as $block) {
                if (isset($block['type']) && $block['type'] === 'text') {
                    $reply .= $block['text'];
                }
            }
            wp_send_json_success(array('reply' => $reply));
        } else {
            // Fallback response
            $reply = $this->get_fallback_circuit_response($message);
            wp_send_json_success(array('reply' => $reply));
        }
    }
    
    /**
     * Get fallback circuit response when API is not configured
     */
    private function get_fallback_circuit_response($message) {
        $message_lower = strtolower($message);
        
        // Check if question is about tracks or setups - if not, decline
        $track_keywords = array('track', 'circuit', 'turn', 'corner', 'lap', 'spa', 'le mans', 'monza', 'silverstone', 'nurburgring', 'suzuka', 'bahrain', 'fuji', 'portimao', 'sebring', 'imola', 'cota', 'interlagos', 'qatar', 'paul ricard', 'eau rouge', 'raidillon', 'maggots', 'becketts', '130r', 'mulsanne', 'racing line', 'braking zone', 'apex', 'chicane', 'hairpin', 'straight', 'esses', 'parabolica', 'lesmo', 'ascari');
        $setup_keywords = array('setup', 'tyre', 'tire', 'pressure', 'suspension', 'spring', 'damper', 'aero', 'wing', 'downforce', 'ride height', 'differential', 'diff', 'brake bias', 'anti-roll', 'arb', 'camber', 'toe', 'understeer', 'oversteer', 'balance', 'hypercar', 'gt3', 'lmp2', 'gte', 'endurance', 'sprint', 'fuel', 'stint', 'degradation', 'preload', 'rebound', 'bump', 'seat', 'seating', 'position', 'ergonomic', 'pedal', 'wheel position', 'driving position', 'cockpit', 'rig', 'simrig', 'flowchart', 'corner exit', 'corner entry', 'wheel rate', 'caster', 'engine brake', 'deceleration', 'acceleration lock', 'motion ratio', 'rake', 'centering force', 'turn-in', 'rotation', 'grip', 'traction');
        
        $is_track_question = false;
        $is_setup_question = false;
        
        foreach ($track_keywords as $keyword) {
            if (strpos($message_lower, $keyword) !== false) {
                $is_track_question = true;
                break;
            }
        }
        
        foreach ($setup_keywords as $keyword) {
            if (strpos($message_lower, $keyword) !== false) {
                $is_setup_question = true;
                break;
            }
        }
        
        // If not about tracks or setups, decline politely
        if (!$is_track_question && !$is_setup_question) {
            return "I'm **ApexTrackBot**, and I specialize exclusively in:\n\n" .
                   "🏁 **Motorsport Race Tracks** - Turn names, layouts, history, characteristics\n" .
                   "🔧 **Car Setups** - Tyre pressures, suspension, aero, differential, brakes\n\n" .
                   "I can't help with other topics. Try asking me about:\n" .
                   "- \"What are the turn names at Spa-Francorchamps?\"\n" .
                   "- \"How do I fix understeer in my Hypercar setup?\"\n" .
                   "- \"What tyre pressures should I use for endurance racing?\"";
        }
        
        // Setup knowledge base
        $setup_topics = array(
            'understeer' => array(
                'title' => 'Fixing Understeer',
                'content' => "**How to Fix Understeer:**\n\n" .
                    "1. **Soften front anti-roll bar** - Reduces front-end stiffness\n" .
                    "2. **Stiffen rear anti-roll bar** - Shifts balance rearward\n" .
                    "3. **Increase front wing/aero** - More front downforce\n" .
                    "4. **Lower front ride height** - More front grip (careful not to bottom out)\n" .
                    "5. **Soften front springs** - Better front compliance\n" .
                    "6. **Reduce front tyre pressures** - More contact patch\n" .
                    "7. **Move brake bias rearward** - Helps rotation on entry\n" .
                    "8. **Reduce diff preload** - Easier rotation\n\n" .
                    "Start with anti-roll bars as they're the quickest balance adjustment."
            ),
            'oversteer' => array(
                'title' => 'Fixing Oversteer',
                'content' => "**How to Fix Oversteer:**\n\n" .
                    "1. **Stiffen front anti-roll bar** - More front-end stiffness\n" .
                    "2. **Soften rear anti-roll bar** - More rear compliance\n" .
                    "3. **Increase rear wing** - More rear downforce\n" .
                    "4. **Raise front ride height slightly** - Shifts aero balance rearward\n" .
                    "5. **Stiffen rear springs** - Reduces rear weight transfer\n" .
                    "6. **Reduce rear tyre pressures** - More rear grip\n" .
                    "7. **Move brake bias forward** - Safer under braking\n" .
                    "8. **Increase diff preload/locking** - More stability on power\n\n" .
                    "For corner exit oversteer, focus on diff settings."
            ),
            'tyre' => array(
                'title' => 'Tyre Setup Guide',
                'content' => "**Tyre Pressure Guidelines for LMU:**\n\n" .
                    "- Generally run pressures at their **lowest allowed setting** for predictable grip\n" .
                    "- Low pressures = stable, predictable, good for long stints\n" .
                    "- Higher pressures = more responsive but can feel nervous\n\n" .
                    "**Temperature Management:**\n" .
                    "- Cold tyres feel sluggish but are safer\n" .
                    "- Hot tyres are responsive but can go over the limit quickly\n" .
                    "- Hypercar tyres are more sensitive to temperature changes\n" .
                    "- LMGT3 tyres have more tolerance\n\n" .
                    "For more setups, visit: **ultimatesetuphub.com/setups**"
            ),
            'pressure' => array(
                'title' => 'Tyre Pressure Guide',
                'content' => "**Tyre Pressure Setup:**\n\n" .
                    "- Run pressures at their **lowest setting** for maximum grip\n" .
                    "- Lower pressures = larger contact patch = more grip\n" .
                    "- Higher pressures = quicker response but less overall grip\n\n" .
                    "**Hot vs Cold:**\n" .
                    "- Set pressures knowing they'll increase as tyres heat up\n" .
                    "- Monitor temps during practice to find optimal window\n" .
                    "- Endurance: prioritize consistency over peak grip"
            ),
            'differential' => array(
                'title' => 'Differential Setup Guide',
                'content' => "**Differential Settings:**\n\n" .
                    "**Preload:**\n" .
                    "- Higher preload = more stability, harder to rotate\n" .
                    "- Lower preload = easier rotation, more reactive\n\n" .
                    "**On-Power (Acceleration):**\n" .
                    "- Higher = stable on exit, less wheelspin\n" .
                    "- Lower = better rotation on throttle, but can feel loose\n\n" .
                    "**Off-Power (Braking/Coast):**\n" .
                    "- Higher = stable under braking\n" .
                    "- Lower = more rotation on turn-in\n\n" .
                    "GT3 diffs are more forgiving; Hypercars with hybrid can be lively if diff is too open."
            ),
            'diff' => array(
                'title' => 'Differential Guide',
                'content' => "**Differential Settings:**\n\n" .
                    "**Preload:** More = stable but harder to rotate. Less = easier rotation.\n\n" .
                    "**On-Power:** Controls behavior when accelerating. Higher = traction, stability.\n\n" .
                    "**Off-Power:** Controls coasting/braking behavior. Lower = more rotation on entry.\n\n" .
                    "For endurance, err on the side of stability."
            ),
            'brake' => array(
                'title' => 'Brake Bias Guide',
                'content' => "**Brake Bias Setup:**\n\n" .
                    "**Forward Bias (higher %):**\n" .
                    "- Very stable under braking\n" .
                    "- Can cause understeer on corner entry\n" .
                    "- Safer for long stints\n\n" .
                    "**Rearward Bias (lower %):**\n" .
                    "- Better rotation on corner entry\n" .
                    "- Risk of rear lockup\n" .
                    "- Requires more precision\n\n" .
                    "**Goal:** Get bias as rearward as you can handle without locking rears.\n\n" .
                    "Hypercars also have **brake migration** - dynamic adjustment during braking."
            ),
            'aero' => array(
                'title' => 'Aerodynamic Setup Guide',
                'content' => "**Aero Setup Principles:**\n\n" .
                    "**Hypercars:**\n" .
                    "- Heavily dependent on aerodynamics\n" .
                    "- Small wing changes completely alter balance\n" .
                    "- More front aero = crisp turn-in but less rear stability\n" .
                    "- More rear aero = safer but harder to rotate\n\n" .
                    "**LMGT3:**\n" .
                    "- Less aero dependent\n" .
                    "- Can lean more on mechanical grip\n" .
                    "- Rear wing still matters for high-speed stability\n\n" .
                    "**Ride Height:**\n" .
                    "- Too low = bottoming out, floor stalls\n" .
                    "- Too high = massive downforce loss\n" .
                    "- Hypercars need stable aero platform"
            ),
            'wing' => array(
                'title' => 'Wing/Downforce Setup',
                'content' => "**Wing Settings:**\n\n" .
                    "**More rear wing:** Safer, more stable, but slower on straights\n" .
                    "**Less rear wing:** Faster top speed, but less rear grip\n\n" .
                    "**More front aero:** Better turn-in, can cause rear instability\n" .
                    "**Less front aero:** Understeer on entry, safer overall\n\n" .
                    "For endurance, favor stability over outright pace."
            ),
            'ride height' => array(
                'title' => 'Ride Height Guide',
                'content' => "**Ride Height Setup:**\n\n" .
                    "**Hypercars:** CRITICAL - a few mm change affects floor downforce massively\n" .
                    "- Too low = bottoming out, unpredictable\n" .
                    "- Too high = big downforce loss\n\n" .
                    "**LMGT3:** Less sensitive but still important\n" .
                    "- Affects center of gravity and aero balance\n\n" .
                    "**Rake (front lower than rear):**\n" .
                    "- More rake = more front downforce, rotation\n" .
                    "- Less rake = more stable"
            ),
            'suspension' => array(
                'title' => 'Suspension Setup Guide',
                'content' => "**Suspension Settings:**\n\n" .
                    "**Springs:**\n" .
                    "- Stiffer = sharper direction changes, less compliant\n" .
                    "- Softer = more grip, can feel sluggish\n\n" .
                    "**Dampers:**\n" .
                    "- Bump (compression): Controls kerb handling and weight transfer\n" .
                    "- Rebound: Controls how suspension returns to position\n" .
                    "- Too stiff = nervous and skittish\n" .
                    "- Too soft = wallowy and slow to respond\n\n" .
                    "**Anti-Roll Bars:**\n" .
                    "- Stiffer front = understeer\n" .
                    "- Stiffer rear = more rotation (can be unpredictable over kerbs)"
            ),
            'spring' => array(
                'title' => 'Spring Rate Guide',
                'content' => "**Spring Rates:**\n\n" .
                    "**Stiffer Springs:**\n" .
                    "- Sharper direction changes\n" .
                    "- Better for low-speed technical circuits\n" .
                    "- Less kerb compliance\n\n" .
                    "**Softer Springs:**\n" .
                    "- More mechanical grip\n" .
                    "- Better over kerbs and bumps\n" .
                    "- Can feel slower to respond\n\n" .
                    "Balance front/rear to maintain aero platform (especially Hypercars)."
            ),
            'damper' => array(
                'title' => 'Damper Setup Guide',
                'content' => "**Damper Settings:**\n\n" .
                    "**Bump (Compression):**\n" .
                    "- Controls how suspension compresses over bumps/kerbs\n" .
                    "- Higher = less body roll, but harsher over kerbs\n\n" .
                    "**Rebound:**\n" .
                    "- Controls how suspension extends back\n" .
                    "- Higher = less bouncing, more controlled\n" .
                    "- Too high = car can't settle properly\n\n" .
                    "Match dampers to your spring rates."
            ),
            'anti-roll' => array(
                'title' => 'Anti-Roll Bar Guide',
                'content' => "**Anti-Roll Bar (ARB) Setup:**\n\n" .
                    "**Front ARB:**\n" .
                    "- Stiffer = more understeer\n" .
                    "- Softer = less understeer, better turn-in\n\n" .
                    "**Rear ARB:**\n" .
                    "- Stiffer = more oversteer/rotation\n" .
                    "- Softer = more stability but less rotation\n\n" .
                    "ARBs are the **quickest way to adjust balance**. Start here before touching springs or aero."
            ),
            'arb' => array(
                'title' => 'ARB Setup',
                'content' => "**Anti-Roll Bars:**\n\n" .
                    "Front stiffer = understeer\n" .
                    "Rear stiffer = oversteer\n\n" .
                    "For endurance, softer ARBs generally give more forgiving, predictable handling."
            ),
            'endurance' => array(
                'title' => 'Endurance Setup Philosophy',
                'content' => "**Endurance Setup vs Sprint:**\n\n" .
                    "**Endurance (long races):**\n" .
                    "- Slightly higher ride height (fuel load)\n" .
                    "- More rear stability\n" .
                    "- Conservative diff settings\n" .
                    "- Preserve tyres over outright pace\n" .
                    "- Consistent, predictable handling\n\n" .
                    "**Sprint (qualifying/short races):**\n" .
                    "- Lower ride height\n" .
                    "- More aggressive rotation\n" .
                    "- Lower fuel = different balance\n\n" .
                    "The fastest car over 1 hour isn't the one that does quali laps - it's the one you can trust consistently."
            ),
            'sprint' => array(
                'title' => 'Sprint Setup Tips',
                'content' => "**Sprint/Qualifying Setup:**\n\n" .
                    "- Lower ride heights\n" .
                    "- More aggressive rotation\n" .
                    "- Can sacrifice stability for speed\n" .
                    "- Low fuel changes balance significantly\n\n" .
                    "Note: Sprint setups often fall apart on old tyres."
            ),
            'hypercar' => array(
                'title' => 'Hypercar Setup Guide',
                'content' => "**Hypercar Setup Principles:**\n\n" .
                    "**Aero-Dependent:**\n" .
                    "- Highly sensitive to ride height and wing angles\n" .
                    "- Floor generates massive downforce\n" .
                    "- Small changes = big effects\n\n" .
                    "**Electronics:**\n" .
                    "- Hybrid deployment maps affect acceleration feel\n" .
                    "- Regen settings impact braking\n" .
                    "- Learn to manage energy for endurance\n\n" .
                    "**Key Differences from GT3:**\n" .
                    "- More aero sensitivity\n" .
                    "- Tyres more reactive to temperature\n" .
                    "- Hybrid makes diff feel different"
            ),
            'gt3' => array(
                'title' => 'LMGT3 Setup Guide',
                'content' => "**LMGT3 Setup Principles:**\n\n" .
                    "- Heavier than Hypercars, less downforce\n" .
                    "- Relies more on **mechanical grip** and tyres\n" .
                    "- More forgiving of setup errors\n" .
                    "- Absorbs kerbs better\n\n" .
                    "**Approach:**\n" .
                    "- Focus on suspension and mechanical balance\n" .
                    "- Aero matters but less critical than Hypercar\n" .
                    "- Can run more rearward brake bias (better ABS)\n" .
                    "- Diff settings more forgiving"
            ),
            'balance' => array(
                'title' => 'Car Balance Guide',
                'content' => "**Achieving Good Car Balance:**\n\n" .
                    "**Quick Adjustments (in order):**\n" .
                    "1. **Anti-Roll Bars** - Fastest balance change\n" .
                    "2. **Brake Bias** - Entry balance\n" .
                    "3. **Differential** - Exit balance\n" .
                    "4. **Aero** - Overall balance\n" .
                    "5. **Springs** - Fundamental handling\n\n" .
                    "**Test Methodology:**\n" .
                    "- Change ONE thing at a time\n" .
                    "- Run multiple consistent laps\n" .
                    "- Check telemetry\n" .
                    "- Always do long-run testing"
            ),
            'seat' => array(
                'title' => 'Optimal Seat Position for Sim Racing',
                'content' => "**Optimal Seat Position for Sim Racing:**\n\n" .
                    "The ideal seat position enhances control, comfort, and performance.\n\n" .
                    "**Seat Angle:**\n" .
                    "- **F1-style (reclined):** Tilt seatback to 100–110° (slightly reclined) to reduce shoulder/back strain\n" .
                    "- **GT-style (upright):** Use 32–40° base slope and 45–55° backrest for a more natural feel\n" .
                    "- **Pro Tip:** Use lumbar support to eliminate lower back gaps when braking\n\n" .
                    "**Leg Position:**\n" .
                    "- Maintain slight knee bend (130° angle) when pressing pedals\n" .
                    "- Never lock legs straight\n" .
                    "- Ensure full pedal depression without discomfort\n\n" .
                    "**Arm & Wheel Position:**\n" .
                    "- Elbows should form 90–100° angle at 9 & 3 position\n" .
                    "- Hands should rest on top of wheel rim with arms extended and shoulders back\n" .
                    "- Wheel height: Align top of wheel with base of your neck\n\n" .
                    "**Pedal Setup:**\n" .
                    "- Position pedals so knees are aligned with hips\n" .
                    "- Angle pedals slightly away from you\n" .
                    "- Height just below hip level to prevent shin/calf pain\n\n" .
                    "**Final Checks:**\n" .
                    "- Shoulders should stay pressed into seat back during turns\n" .
                    "- Use adjustable brackets for secure seat mounting\n" .
                    "- Test with a few laps and adjust for long-term comfort\n\n" .
                    "**Note:** There's no universal \"perfect\" position—customize based on your body. Prioritize comfort, circulation, and muscle balance over rigid rules."
            ),
            'seating' => array(
                'title' => 'Sim Racing Seating Position',
                'content' => "**Sim Racing Seating Position Guide:**\n\n" .
                    "**Key Principles:**\n" .
                    "- **Seat Angle:** F1-style = 100–110° reclined, GT-style = 45–55° backrest\n" .
                    "- **Legs:** Slight knee bend (130°), never lock straight\n" .
                    "- **Arms:** 90–100° elbow angle at 9 & 3 on wheel\n" .
                    "- **Wheel Height:** Top of wheel aligned with base of neck\n" .
                    "- **Pedals:** Knees aligned with hips, pedals angled slightly away\n\n" .
                    "**Comfort Tips:**\n" .
                    "- Use lumbar support for lower back\n" .
                    "- Shoulders pressed into seat during cornering\n" .
                    "- Test and adjust over multiple sessions\n\n" .
                    "Prioritize long-term comfort over any specific measurements."
            ),
            'driving position' => array(
                'title' => 'Driving Position Setup',
                'content' => "**Optimal Driving Position:**\n\n" .
                    "**Wheel Position:**\n" .
                    "- Arms at 90–100° angle at 9 & 3\n" .
                    "- Top of wheel at neck height\n" .
                    "- Shoulders should engage large upper-body muscles\n\n" .
                    "**Pedal Position:**\n" .
                    "- Knees aligned with hips\n" .
                    "- 130° knee angle when pedals pressed\n" .
                    "- No forward lean or strain\n\n" .
                    "**Seat:**\n" .
                    "- GT: 45–55° backrest\n" .
                    "- F1: 100–110° reclined\n" .
                    "- Use lumbar support\n\n" .
                    "Good position = better consistency over long stints."
            ),
            'pedal' => array(
                'title' => 'Pedal Position Guide',
                'content' => "**Pedal Setup for Sim Racing:**\n\n" .
                    "**Position:**\n" .
                    "- Knees aligned with hips (avoid forward lean)\n" .
                    "- Height just below hip level\n" .
                    "- Angle pedals slightly away from you\n\n" .
                    "**Leg Angle:**\n" .
                    "- Maintain 130° knee bend when pressing pedals\n" .
                    "- Never lock legs fully straight\n" .
                    "- Avoid excessive knee flexion\n\n" .
                    "**Comfort Check:**\n" .
                    "- Full pedal depression without strain\n" .
                    "- No shin or calf pressure\n" .
                    "- Good heel pivot point\n\n" .
                    "Proper pedal position prevents fatigue and improves consistency in long races."
            ),
            'ergonomic' => array(
                'title' => 'Sim Racing Ergonomics',
                'content' => "**Sim Racing Ergonomics:**\n\n" .
                    "**Why It Matters:**\n" .
                    "- Prevents fatigue in long stints\n" .
                    "- Improves consistency and control\n" .
                    "- Reduces injury risk\n\n" .
                    "**Key Points:**\n" .
                    "- **Seat:** F1 = reclined (100–110°), GT = upright (45–55°)\n" .
                    "- **Arms:** 90–100° at elbows, hands at 9 & 3\n" .
                    "- **Legs:** 130° knee bend, knees aligned with hips\n" .
                    "- **Back:** Lumbar support, shoulders pressed into seat\n" .
                    "- **Pedals:** Slightly angled away, below hip level\n\n" .
                    "Test your setup over multiple sessions and adjust for YOUR body—there's no universal perfect position."
            ),
            'rig' => array(
                'title' => 'Sim Rig Setup Guide',
                'content' => "**Sim Rig Positioning:**\n\n" .
                    "**Seat:**\n" .
                    "- GT-style: 45–55° backrest angle\n" .
                    "- F1-style: 100–110° reclined\n" .
                    "- Secure mounting (no slipping under braking)\n" .
                    "- Add lumbar support for lower back\n\n" .
                    "**Wheel:**\n" .
                    "- Top of rim at neck height\n" .
                    "- 90–100° elbow angle\n" .
                    "- Hands naturally at 9 & 3\n\n" .
                    "**Pedals:**\n" .
                    "- Knees aligned with hips\n" .
                    "- 130° knee angle\n" .
                    "- Slight angle away from body\n\n" .
                    "**Pro Tips:**\n" .
                    "- Use adjustable brackets for fine-tuning\n" .
                    "- Run 10+ laps to test comfort\n" .
                    "- Prioritize long-term comfort over exact measurements"
            ),
            'flowchart' => array(
                'title' => 'Race Car Setup Flowchart',
                'content' => "**Race Car Setup Flowchart** \n\n" .
                    "Follow this order for systematic setup development:\n\n" .
                    "**1. CORNER EXIT GRIP (Primary - adjust first)**\n" .
                    "- Wheel Rate (springs) → Ride Height → Tyre Pressure & Camber → Anti-Roll Bars → Diff Accel Lock → Dampers (high-speed)\n\n" .
                    "**2. CORNER ENTRY BALANCE (Secondary)**\n" .
                    "- Brake Bias → Engine Brake → Diff Preload/Decel → Dampers (low-speed) → Revisit Brake Bias & ARBs\n\n" .
                    "**3. DRIVER PREFERENCE (Tertiary - fine-tuning)**\n" .
                    "- Toe settings → Dampers (low-speed) → Caster\n\n" .
                    "**Key Principle:** Get corner exit grip sorted FIRST, then tune corner entry, then fine-tune to preference."
            ),
            'corner exit' => array(
                'title' => 'Corner Exit Grip Setup',
                'content' => "**Corner Exit Grip Setup (Primary Goals)**\n\n" .
                    "Maximize grip while minimizing understeer/oversteer at full throttle or edge of wheelspin.\n\n" .
                    "**Adjustment Order:**\n" .
                    "1. **Wheel Rate (Springs):** Stiffer = lower ride height but less grip over bumps. F/R ratio should match weight distribution.\n\n" .
                    "2. **Ride Height:** Minimum possible without clearance issues. Rake affects aero balance.\n\n" .
                    "3. **Tyre Pressure & Camber:** Follow bell curve for optimal grip. Look for even tire wear.\n" .
                    "   - Too low/high pressure = less grip\n" .
                    "   - Too negative/positive camber = less grip\n\n" .
                    "4. **Anti-Roll Bars:**\n" .
                    "   - Understeer → Soften front and/or stiffen rear\n" .
                    "   - Oversteer → Stiffen front and/or soften rear\n\n" .
                    "5. **Diff Acceleration Lock:** Use minimum that prevents inside wheelspin in slowest corner.\n\n" .
                    "6. **Dampers (High-Speed):** Minimum rate avoiding chassis oscillation. Rebound = 1-1.5x compression."
            ),
            'corner entry' => array(
                'title' => 'Corner Entry Balance Setup',
                'content' => "**Corner Entry Balance Setup (Secondary Goals)**\n\n" .
                    "Tune entry balance while not compromising corner exit grip.\n\n" .
                    "**Adjustment Order:**\n" .
                    "1. **Brake Bias:** Set so fronts lock momentarily before rears in straight-line threshold braking.\n\n" .
                    "2. **Engine Brake Mapping:**\n" .
                    "   - Understeer → Increase engine brake\n" .
                    "   - Oversteer → Decrease engine brake\n\n" .
                    "3. **Diff Preload & Deceleration Lock:**\n" .
                    "   - Understeer → Decrease lock\n" .
                    "   - Oversteer → Increase lock\n\n" .
                    "4. **Dampers (Low-Speed):**\n" .
                    "   - Understeer → Soften front and/or stiffen rear\n" .
                    "   - Oversteer → Stiffen front and/or soften rear\n\n" .
                    "5. **Revisit Brake Bias & ARBs** if needed after above changes."
            ),
            'toe' => array(
                'title' => 'Toe Settings Guide',
                'content' => "**Toe Settings Guide**\n\n" .
                    "Toe adjustments are driver preference (tertiary adjustments).\n\n" .
                    "**REAR TOE:**\n" .
                    "- **Toe-In:** Smooths turn-in response, adds stability. Recommend 0 to minimal toe-in.\n" .
                    "- **Toe-Out:** Can increase turn-in rate but generally NOT recommended - causes instability.\n" .
                    "- High settings increase tire heat and drag.\n\n" .
                    "**FRONT TOE:**\n" .
                    "- **Toe-In:** Increases turn-in rate, strengthens centering force on steering wheel.\n" .
                    "- **Toe-Out:** Smooths turn-in response, reduces centering force. Recommend 0 to minimal toe-out.\n\n" .
                    "**Summary:**\n" .
                    "- Rear: 0 to slight toe-in for stability\n" .
                    "- Front: 0 to slight toe-out for responsive steering"
            ),
            'caster' => array(
                'title' => 'Caster Settings Guide',
                'content' => "**Caster Settings Guide**\n\n" .
                    "Caster is a driver preference adjustment (tertiary).\n\n" .
                    "**Higher Caster:**\n" .
                    "- Strengthens centering force on steering wheel\n" .
                    "- Wheel returns to center more forcefully\n" .
                    "- Generally beneficial for stability and feel\n" .
                    "- Can make steering heavier\n\n" .
                    "**Lower Caster:**\n" .
                    "- Lighter steering effort\n" .
                    "- Less self-centering\n" .
                    "- May feel less stable\n\n" .
                    "**Recommendation:** Increase caster unless high steering effort hinders your car control ability."
            ),
            'engine brake' => array(
                'title' => 'Engine Brake Mapping Guide',
                'content' => "**Engine Brake Mapping Guide**\n\n" .
                    "Engine braking affects corner entry balance (off-throttle behavior).\n\n" .
                    "**More Engine Brake (+):**\n" .
                    "- Increases deceleration when lifting throttle\n" .
                    "- Helps rotate the car on entry\n" .
                    "- Use to fix UNDERSTEER on corner entry\n\n" .
                    "**Less Engine Brake (-):**\n" .
                    "- Reduces deceleration when lifting\n" .
                    "- Car coasts more freely\n" .
                    "- Use to fix OVERSTEER on corner entry\n\n" .
                    "**When to Adjust:**\n" .
                    "- Only after corner exit grip is sorted\n" .
                    "- Part of corner entry balance tuning"
            ),
            'wheel rate' => array(
                'title' => 'Wheel Rate (Spring Rate) Guide',
                'content' => "**Wheel Rate Guide**\n\n" .
                    "Wheel Rate = Spring Rate × Motion Ratio²\n\n" .
                    "**Stiffer Wheel Rates:**\n" .
                    "- Allow lower ride heights\n" .
                    "- Reduce grip over bumps\n" .
                    "- Sharper direction changes\n" .
                    "- Better for smooth tracks\n\n" .
                    "**Softer Wheel Rates:**\n" .
                    "- More grip over bumps and kerbs\n" .
                    "- Better compliance\n" .
                    "- May need higher ride height\n\n" .
                    "**Front/Rear Balance:**\n" .
                    "- F/R wheel rate ratio should generally equal weight distribution\n" .
                    "- More weight in rear = stiffer rear springs\n\n" .
                    "**Adjustment Order:** Springs are a PRIMARY adjustment - set these early in setup development."
            ),
            'rake' => array(
                'title' => 'Rake Setup Guide',
                'content' => "**Rake Setup Guide**\n\n" .
                    "Rake = Front ride height lower than rear.\n\n" .
                    "**More Rake (front lower):**\n" .
                    "- More front downforce\n" .
                    "- Better turn-in/rotation\n" .
                    "- Risk of bottoming out at front\n\n" .
                    "**Less Rake (more level):**\n" .
                    "- More rear stability\n" .
                    "- Less front grip\n" .
                    "- Safer high-speed behavior\n\n" .
                    "**Key Points:**\n" .
                    "- Rake directly affects aero balance\n" .
                    "- Critical for Hypercars with floor-generated downforce\n" .
                    "- Set ride height to minimum possible without clearance issues, then fine-tune rake for balance"
            ),
            'grip' => array(
                'title' => 'Grip Optimization Guide',
                'content' => "**Grip Optimization Guide**\n\n" .
                    "**Mechanical Grip (tyres & suspension):**\n" .
                    "- Tyre pressure: Follow bell curve - not too high, not too low\n" .
                    "- Camber: Look for even tire wear across surface\n" .
                    "- Springs: Softer = more grip over bumps\n" .
                    "- ARBs: Softer = more grip but more body roll\n\n" .
                    "**Aerodynamic Grip:**\n" .
                    "- Ride height: Lower = more downforce (within limits)\n" .
                    "- Wings: More angle = more downforce but more drag\n" .
                    "- Rake: Affects front/rear aero balance\n\n" .
                    "**Corner Exit Grip Priority:**\n" .
                    "1. Wheel rate (springs)\n" .
                    "2. Ride height\n" .
                    "3. Tyre pressure & camber\n" .
                    "4. Anti-roll bars\n" .
                    "5. Diff acceleration lock\n" .
                    "6. Dampers (high-speed)"
            ),
            'traction' => array(
                'title' => 'Traction & Wheelspin Guide',
                'content' => "**Traction & Wheelspin Guide**\n\n" .
                    "**If experiencing wheelspin on corner exit:**\n\n" .
                    "1. **Differential Acceleration Lock:**\n" .
                    "   - Increase lock to reduce inside wheel spin\n" .
                    "   - Use minimum that prevents wheelspin in slowest corner\n\n" .
                    "2. **Tyre Pressure:**\n" .
                    "   - Check rear pressures aren't too high\n" .
                    "   - Lower pressure = larger contact patch\n\n" .
                    "3. **Rear Wing/Aero:**\n" .
                    "   - More rear downforce = more traction\n\n" .
                    "4. **Anti-Roll Bars:**\n" .
                    "   - Softer rear ARB = more rear grip\n\n" .
                    "5. **Throttle Application:**\n" .
                    "   - Smooth, progressive throttle out of corners\n" .
                    "   - Partial throttle before full power"
            ),
            'turn-in' => array(
                'title' => 'Turn-In Response Guide',
                'content' => "**Turn-In Response Guide**\n\n" .
                    "**For sharper/quicker turn-in:**\n" .
                    "- Front toe-in (increases turn-in rate)\n" .
                    "- More front downforce/aero\n" .
                    "- Softer front ARB\n" .
                    "- Stiffer rear ARB\n" .
                    "- More rake (front lower)\n" .
                    "- More diff decel lock (can help rotation)\n\n" .
                    "**For smoother/more stable turn-in:**\n" .
                    "- Front toe-out (smooths response)\n" .
                    "- Rear toe-in (adds stability)\n" .
                    "- Softer ARBs overall\n" .
                    "- Less rake\n\n" .
                    "**Balance Considerations:**\n" .
                    "- Too sharp = nervous/unstable\n" .
                    "- Too smooth = sluggish/unresponsive\n" .
                    "- Find the balance that suits your driving style"
            ),
            'centering force' => array(
                'title' => 'Steering Centering Force Guide',
                'content' => "**Steering Centering Force Guide**\n\n" .
                    "Centering force = how strongly the wheel returns to center.\n\n" .
                    "**To INCREASE centering force:**\n" .
                    "- Increase caster angle\n" .
                    "- Front toe-in\n\n" .
                    "**To DECREASE centering force:**\n" .
                    "- Decrease caster angle\n" .
                    "- Front toe-out\n\n" .
                    "**Driver Preference:**\n" .
                    "- Strong centering = stable feel, wheel snaps back\n" .
                    "- Weak centering = lighter steering, less feedback\n\n" .
                    "Generally, higher centering force is beneficial unless it makes steering too heavy for comfortable control."
            )
        );
        
        // Check for setup topic matches first
        foreach ($setup_topics as $key => $topic) {
            if (strpos($message_lower, $key) !== false) {
                return $topic['content'] . "\n\n*For professional setups, visit [Ultimate Setup Hub](https://ultimatesetuphub.com/setups) or [Coach Dave Academy](https://coachdaveacademy.com)*";
            }
        }
        
        // Common circuit knowledge base
        $circuits = array(
            'spa' => array(
                'name' => 'Spa-Francorchamps',
                'country' => 'Belgium',
                'length' => '7.004 km (4.352 miles)',
                'turns' => 'La Source, Eau Rouge, Raidillon, Kemmel Straight, Les Combes, Malmedy, Rivage, Pouhon, Fagnes, Campus, Stavelot, Paul Frère, Blanchimont, Bus Stop Chicane',
                'info' => 'One of the most famous and challenging circuits in the world, known for its fast, flowing layout and unpredictable weather.'
            ),
            'le mans' => array(
                'name' => 'Circuit de la Sarthe',
                'country' => 'France',
                'length' => '13.626 km (8.467 miles)',
                'turns' => 'Dunlop Chicane, Dunlop Curve, Esses, Tertre Rouge, Mulsanne Straight, Mulsanne Corner, Indianapolis, Arnage, Porsche Curves, Maison Blanche, Ford Chicanes',
                'info' => 'Home of the 24 Hours of Le Mans, the oldest active sports car race in endurance racing.'
            ),
            'monza' => array(
                'name' => 'Autodromo Nazionale Monza',
                'country' => 'Italy',
                'length' => '5.793 km (3.600 miles)',
                'turns' => 'Variante del Rettifilo (Turns 1-2), Curva Grande, Variante della Roggia (Turns 4-5), Lesmo 1, Lesmo 2, Variante Ascari, Curva Parabolica',
                'info' => 'Known as the Temple of Speed, this is the fastest circuit on the F1 calendar and home to the Italian Grand Prix since 1950.'
            ),
            'silverstone' => array(
                'name' => 'Silverstone Circuit',
                'country' => 'United Kingdom',
                'length' => '5.891 km (3.661 miles)',
                'turns' => 'Abbey, Farm, Village, The Loop, Aintree, Wellington Straight, Brooklands, Luffield, Woodcote, Copse, Maggots, Becketts, Chapel, Hangar Straight, Stowe, Vale, Club',
                'info' => 'The home of British motorsport, located on a former WWII airfield. Hosts the British Grand Prix.'
            ),
            'nurburgring' => array(
                'name' => 'Nürburgring',
                'country' => 'Germany',
                'length' => 'Nordschleife: 20.832 km (12.944 miles), GP Circuit: 5.148 km (3.199 miles)',
                'turns' => 'The Nordschleife has over 150 corners including Hatzenbach, Flugplatz, Aremberg, Fuchsröhre, Adenauer Forst, Metzgesfeld, Kallenhard, Wehrseifen, Brünnchen, Pflanzgarten, Schwalbenschwanz, Galgenkopf, Döttinger Höhe, Hohenrain',
                'info' => 'The legendary Nordschleife (North Loop) is known as "The Green Hell" and is considered the most challenging circuit in the world.'
            ),
            'suzuka' => array(
                'name' => 'Suzuka International Racing Course',
                'country' => 'Japan',
                'length' => '5.807 km (3.608 miles)',
                'turns' => 'First Curve, S Curves, Dunlop Curve, Degner 1, Degner 2, Hairpin, Spoon Curve, 130R, Casio Triangle, Chicane',
                'info' => 'Famous for its unique figure-8 layout where the track crosses over itself. The S Curves and 130R are particularly challenging.'
            ),
            'bahrain' => array(
                'name' => 'Bahrain International Circuit',
                'country' => 'Bahrain',
                'length' => '5.412 km (3.363 miles)',
                'turns' => 'Turn 1-4 complex, Turn 5-7 complex, Turn 8, Turn 9-10, Turn 11, Turn 12-14 complex, Turn 15',
                'info' => 'The first purpose-built F1 circuit in the Middle East, known for its challenging combination of slow and fast corners.'
            ),
            'fuji' => array(
                'name' => 'Fuji Speedway',
                'country' => 'Japan',
                'length' => '4.563 km (2.835 miles)',
                'turns' => 'TGR Corner (Turn 1), Coca-Cola Corner, 100R, Hairpin, 300R, Dunlop Corner, Netz Corner, Final Corner',
                'info' => 'Located at the foot of Mount Fuji, featuring a 1.475 km main straight and challenging technical sections.'
            ),
            'portimao' => array(
                'name' => 'Autódromo Internacional do Algarve',
                'country' => 'Portugal',
                'length' => '4.653 km (2.891 miles)',
                'turns' => 'Turn 1-2 (uphill braking), Turn 3-4-5 complex, Turn 6-7, Turn 8 (Blind crest), Turn 9-10-11, Turn 12-13 (fast chicane), Turn 14-15',
                'info' => 'Known for its dramatic elevation changes and blind crests, making it challenging to learn but rewarding to master.'
            ),
            'sebring' => array(
                'name' => 'Sebring International Raceway',
                'country' => 'USA',
                'length' => '6.019 km (3.740 miles)',
                'turns' => 'Turn 1, Turn 3, Hairpin (Turn 7), Tower Turn, Cunningham Corner, Collier Curve, Bishop Bend, Sunset Bend, Turn 17',
                'info' => 'Built on a former WWII airfield, known for its bumpy concrete surface and hosting the 12 Hours of Sebring.'
            ),
            'imola' => array(
                'name' => 'Autodromo Enzo e Dino Ferrari',
                'country' => 'Italy',
                'length' => '4.909 km (3.050 miles)',
                'turns' => 'Tamburello, Villeneuve, Tosa, Piratella, Acque Minerali, Variante Alta, Rivazza 1, Rivazza 2',
                'info' => 'Named after Enzo Ferrari and his son Dino. One of the few anti-clockwise circuits in F1.'
            ),
            'cota' => array(
                'name' => 'Circuit of the Americas',
                'country' => 'USA',
                'length' => '5.513 km (3.426 miles)',
                'turns' => 'Turn 1 (uphill hairpin), Esses (Turn 2-6), Turn 11 hairpin, Turn 12-15, Turn 16-18, Turn 19-20',
                'info' => 'Purpose-built F1 circuit featuring elements inspired by famous corners from around the world.'
            ),
            'interlagos' => array(
                'name' => 'Autódromo José Carlos Pace',
                'country' => 'Brazil',
                'length' => '4.309 km (2.677 miles)',
                'turns' => 'Senna S (Turn 1-2), Curva do Sol, Reta Oposta, Descida do Lago, Ferradura, Laranja, Pinheirinho, Bico de Pato, Mergulho, Junção, Subida dos Boxes',
                'info' => 'An anti-clockwise circuit known for its dramatic elevation changes and passionate crowds.'
            ),
            'qatar' => array(
                'name' => 'Lusail International Circuit',
                'country' => 'Qatar',
                'length' => '5.419 km (3.367 miles)',
                'turns' => 'Turn 1, Turn 2-3-4 complex, Turn 5-6, Turn 7, Turn 8-9-10, Turn 11-12, Turn 13-14-15-16',
                'info' => 'A floodlit circuit primarily used for MotoGP, known for its high-speed corners and long straights.'
            ),
            'paul ricard' => array(
                'name' => 'Circuit Paul Ricard',
                'country' => 'France',
                'length' => '5.842 km (3.630 miles)',
                'turns' => 'Verrerie, Sainte-Baume, Hotel, Camp, Pont, Bendor, Village, Tour, Virage du Lac, Mistral Chicane, Signes, Beausset',
                'info' => 'Known for its distinctive blue and red run-off areas and the extremely long Mistral Straight.'
            ),
            'mugello' => array(
                'name' => 'Autodromo Internazionale del Mugello',
                'country' => 'Italy',
                'length' => '5.245 km (3.259 miles)',
                'turns' => 'San Donato, Luco, Poggio Secco, Materassi, Borgo San Lorenzo, Casanova-Savelli, Arrabbiata 1, Arrabbiata 2, Scarperia, Palagio, Correntaio, Biondetti, Bucine',
                'info' => 'Owned by Ferrari, this spectacular Tuscan circuit is known for MotoGP and challenging high-speed corners.'
            ),
            'albert park' => array(
                'name' => 'Albert Park Circuit',
                'country' => 'Australia',
                'length' => '5.278 km (3.280 miles)',
                'turns' => 'Turn 1, Turn 2, Turn 3, Turn 4, Turn 5, Turn 6 complex, Turn 7-8, Turn 9-10, Turn 11 (chicane), Turn 12-13-14',
                'info' => 'A street circuit around Albert Park Lake in Melbourne, host to the Australian Grand Prix.'
            ),
            'daytona' => array(
                'name' => 'Daytona International Speedway',
                'country' => 'USA',
                'length' => '5.73 km (3.56 miles) road course',
                'turns' => 'Turn 1 (NASCAR), International Horseshoe, Infield road course sections, Bus Stop chicane, NASCAR Turn 3-4',
                'info' => 'Famous for the Daytona 500 and Rolex 24 at Daytona. The road course uses the banking plus an infield section.'
            ),
            'road america' => array(
                'name' => 'Road America',
                'country' => 'USA',
                'length' => '6.515 km (4.048 miles)',
                'turns' => 'Turn 1, Turn 3, Turn 5 (Moraine Sweep), Turn 6, The Kink, Turn 8, Canada Corner, Turn 12, Carousel, Kink, Thunder Valley, Turn 14',
                'info' => 'One of the longest permanent road courses in America, set in the beautiful Kettle Moraine area of Wisconsin.'
            ),
            'watkins glen' => array(
                'name' => 'Watkins Glen International',
                'country' => 'USA',
                'length' => '5.43 km (3.37 miles)',
                'turns' => 'Turn 1 (The 90), The Esses, The Back Stretch, The Inner Loop, Toe of the Boot, The Boot, Turn 9, Turn 10, Turn 11',
                'info' => 'A classic American road course in upstate New York, known for its challenging elevation changes and The Boot section.'
            )
        );
        
        // Check for circuit matches
        foreach ($circuits as $key => $circuit) {
            if (strpos($message_lower, $key) !== false || strpos($message_lower, strtolower($circuit['name'])) !== false) {
                $response = "**{$circuit['name']}** ({$circuit['country']})\n\n";
                $response .= "**Length:** {$circuit['length']}\n\n";
                $response .= "**Notable Corners:** {$circuit['turns']}\n\n";
                $response .= "{$circuit['info']}\n\n";
                $response .= "*For more details, visit [RacingCircuits.info](https://www.racingcircuits.info/), [Motorsport Magazine Circuits](https://www.motorsportmagazine.com/database/circuits/), or [Silhouet Track Database](https://www.silhouet.com/motorsport/tracks/tracks.html)*";
                return $response;
            }
        }
        
        // If it's a setup question but no specific topic matched
        if ($is_setup_question) {
            return "**Car Setup Help**\n\n" .
                "I can help with specific setup topics. Try asking about:\n\n" .
                "**Balance Issues:**\n" .
                "- **Understeer/Oversteer** - Diagnose and fix balance problems\n" .
                "- **Corner Exit Grip** - Primary setup adjustments\n" .
                "- **Corner Entry Balance** - Secondary adjustments\n\n" .
                "**Setup Components:**\n" .
                "- **Tyre Pressures & Camber** - Optimal grip settings\n" .
                "- **Differential** - Preload, accel/decel lock\n" .
                "- **Brake Bias & Engine Brake** - Entry rotation\n" .
                "- **Anti-Roll Bars** - Quick balance changes\n" .
                "- **Suspension** - Springs, dampers, ride height, rake\n" .
                "- **Aero/Wings** - Downforce and balance\n" .
                "- **Toe & Caster** - Driver preference fine-tuning\n\n" .
                "**Guides:**\n" .
                "- **Setup Flowchart** - Systematic adjustment order\n" .
                "- **Seat Position** - Optimal driving position\n\n" .
                "*Sources: [Ultimate Setup Hub](https://ultimatesetuphub.com/setups) | [Coach Dave Academy](https://coachdaveacademy.com)*";
        }
        
        // Default response for track questions
        return "I can help with motorsport tracks and car setups! Here are some circuits I know well:\n\n" .
               "🏁 **Famous Circuits:** Spa, Le Mans, Monza, Suzuka, Nürburgring, Silverstone, Sebring, Imola, COTA, Interlagos, Daytona, Road America, Watkins Glen\n\n" .
               "🔧 **Setup Help:** Ask about tyre pressures, suspension, aero, differential, brake bias, understeer/oversteer fixes, or the setup flowchart\n\n" .
               "🪑 **Ergonomics:** Seat position, pedal placement, wheel height\n\n" .
               "Try asking:\n" .
               "- \"What are the turn names at [circuit name]?\"\n" .
               "- \"Show me the setup flowchart\"\n" .
               "- \"How do I fix understeer?\"\n\n" .
               "*Note: For AI-powered search of 700+ circuits, configure your Anthropic API key in Apex Notes settings.*";
    }
    
    /**
     * Set event alert preference
     */
    public function set_event_alert() {
        $this->verify_nonce();
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => 'Please log in to set alerts.'));
            return;
        }
        
        $user_id = get_current_user_id();
        $channel_id = isset($_POST['channel_id']) ? sanitize_text_field($_POST['channel_id']) : '';
        $enabled = isset($_POST['enabled']) ? (bool) $_POST['enabled'] : true;
        
        if (empty($channel_id)) {
            wp_send_json_error(array('message' => 'Invalid channel.'));
            return;
        }
        
        Apex_Notes_DB::set_user_event_alert($user_id, $channel_id, $enabled);
        
        wp_send_json_success(array('message' => $enabled ? 'Alert enabled!' : 'Alert disabled.'));
    }
    
    /**
     * Get user event alerts
     */
    public function get_event_alerts() {
        $this->verify_nonce();
        
        if (!is_user_logged_in()) {
            wp_send_json_success(array('alerts' => array()));
            return;
        }
        
        $user_id = get_current_user_id();
        $alerts = Apex_Notes_DB::get_user_event_alerts($user_id);
        
        // Convert to associative array keyed by channel_id
        $alert_map = array();
        foreach ($alerts as $alert) {
            $alert_map[$alert['channel_id']] = (bool) $alert['enabled'];
        }
        
        wp_send_json_success(array('alerts' => $alert_map));
    }
    
    /**
     * Import 2026 race calendars (admin only)
     */
    public function import_race_calendars() {
        $this->verify_nonce();
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Permission denied.'));
            return;
        }
        
        $imported = Apex_Notes_DB::seed_race_calendars();
        
        wp_send_json_success(array(
            'message' => $imported > 0 ? "Imported $imported race events." : 'All race events already imported.',
            'imported' => $imported
        ));
    }
    
    /**
     * Add a manual event (admin only)
     */
    public function add_manual_event() {
        $this->verify_nonce();
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Permission denied.'));
            return;
        }
        
        $title = isset($_POST['title']) ? sanitize_text_field($_POST['title']) : '';
        $channel = isset($_POST['channel']) ? sanitize_text_field($_POST['channel']) : '';
        $scheduled_start = isset($_POST['scheduled_start']) ? sanitize_text_field($_POST['scheduled_start']) : '';
        $description = isset($_POST['description']) ? sanitize_textarea_field($_POST['description']) : '';
        
        if (empty($title) || empty($channel) || empty($scheduled_start)) {
            wp_send_json_error(array('message' => 'Title, channel, and date are required.'));
            return;
        }
        
        $event_id = Apex_Notes_DB::add_manual_event($title, $channel, $scheduled_start, $description);
        
        if ($event_id) {
            wp_send_json_success(array('message' => 'Event added successfully.', 'event_id' => $event_id));
        } else {
            wp_send_json_error(array('message' => 'Failed to add event.'));
        }
    }
    
    /**
     * Delete an event (admin only)
     */
    public function delete_event() {
        $this->verify_nonce();
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Permission denied.'));
            return;
        }
        
        $video_id = isset($_POST['video_id']) ? sanitize_text_field($_POST['video_id']) : '';
        
        if (empty($video_id)) {
            wp_send_json_error(array('message' => 'Event ID required.'));
            return;
        }
        
        $deleted = Apex_Notes_DB::delete_event($video_id);
        
        if ($deleted) {
            wp_send_json_success(array('message' => 'Event deleted.'));
        } else {
            wp_send_json_error(array('message' => 'Event not found.'));
        }
    }
    
    /**
     * Step 1 of fuel upload: parse the XML, return all driver candidates,
     * and stash the XML in a user-scoped transient. No DB writes happen here.
     *
     * The uploader must then call apex_notes_confirm_fuel_upload with the
     * driver they confirmed is them (plus flags). This two-step flow is
     * mandatory because LMU multiplayer XMLs flag EVERY driver as
     * isPlayer=1 — we cannot guess which one is the uploader.
     */
    public function upload_xml() {
        $this->verify_nonce();

        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => 'You must be logged in to upload data.'));
            return;
        }
        if (!isset($_FILES['xml_file'])) {
            wp_send_json_error(array('message' => 'No file uploaded.'));
            return;
        }

        $file = $_FILES['xml_file'];
        $validation = Apex_Notes_XML_Parser::validate_upload($file);
        if (is_wp_error($validation)) {
            wp_send_json_error(array('message' => $validation->get_error_message()));
            return;
        }

        $xml_content = file_get_contents($file['tmp_name']);
        if ($xml_content === false || strlen($xml_content) < 100) {
            wp_send_json_error(array('message' => 'Could not read uploaded file.'));
            return;
        }

        $candidates = Apex_Notes_XML_Parser::list_candidates($xml_content);
        if (is_wp_error($candidates)) {
            wp_send_json_error(array('message' => $candidates->get_error_message()));
            return;
        }
        // Filter out candidates with no lap data (spectators, crashed out at start)
        $candidates = array_values(array_filter($candidates, function($c) {
            return !empty($c['has_laps']);
        }));
        if (empty($candidates)) {
            wp_send_json_error(array('message' => 'No drivers with lap data found in this XML.'));
            return;
        }

        $user_id = get_current_user_id();
        $saved_name = (string) get_user_meta($user_id, 'apex_notes_lmu_driver_name', true);
        $preselected = null;
        if ($saved_name !== '') {
            $needle = strtolower(trim($saved_name));
            foreach ($candidates as $c) {
                if (strtolower(trim($c['name'])) === $needle) {
                    $preselected = $c['name'];
                    break;
                }
            }
        }
        // Auto-preselect when only one candidate has isPlayer=1 (offline XML)
        if ($preselected === null) {
            $players = array_filter($candidates, function($c) { return !empty($c['is_player']); });
            if (count($players) === 1) {
                $only = array_values($players)[0];
                $preselected = $only['name'];
            }
        }

        // Stash XML in a transient keyed per-user
        $upload_key = wp_generate_password(24, false, false);
        set_transient(
            'apex_fuel_upload_' . $user_id . '_' . $upload_key,
            array(
                'xml' => $xml_content,
                'filename' => isset($file['name']) ? $file['name'] : '',
            ),
            30 * MINUTE_IN_SECONDS
        );

        // Sort candidates: player-flagged first, then by total_laps desc
        usort($candidates, function($a, $b) {
            if ($a['is_player'] !== $b['is_player']) {
                return $b['is_player'] <=> $a['is_player'];
            }
            return ($b['total_laps'] ?? 0) <=> ($a['total_laps'] ?? 0);
        });

        // Metadata (track, date, etc.) — parse lightweight
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($xml_content);
        $meta = array();
        if ($xml && isset($xml->RaceResults)) {
            $r = $xml->RaceResults;
            $meta = array(
                'track_venue' => (string) $r->TrackVenue,
                'track_course' => (string) $r->TrackCourse,
                'session_date' => (string) $r->TimeString,
                'fuel_mult' => floatval($r->FuelMult) ?: 1.0,
                'game_version' => (string) $r->GameVersion,
            );
        }
        libxml_clear_errors();

        wp_send_json_success(array(
            'needs_selection' => true,
            'upload_key' => $upload_key,
            'preselected_name' => $preselected,
            'saved_lmu_name' => $saved_name,
            'candidates' => $candidates,
            'metadata' => $meta,
            'candidate_count' => count($candidates),
        ));
    }

    /**
     * Step 2: user has picked which driver is them. Save their session,
     * plus anonymous ghost sessions for every OTHER driver (user_id=0,
     * shared=1) so the community fuel database gets maximum data.
     *
     * Required POST:
     *   upload_key   - transient key returned by upload_xml
     *   driver_name  - the <Name> the user picked
     * Optional POST:
     *   share_with_community (0/1)        - share user's session (default 1)
     *   import_other_drivers (0/1)        - import anonymous ghosts (default 1)
     *   save_as_my_driver    (0/1)        - remember driver_name in user meta (default 1)
     */
    public function confirm_fuel_upload() {
        $this->verify_nonce();

        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => 'You must be logged in.'));
            return;
        }

        $user_id = get_current_user_id();
        $upload_key = isset($_POST['upload_key']) ? sanitize_text_field($_POST['upload_key']) : '';
        $driver_name = isset($_POST['driver_name']) ? sanitize_text_field(wp_unslash($_POST['driver_name'])) : '';
        $share = !isset($_POST['share_with_community']) || $_POST['share_with_community'] === '1';
        $import_ghosts = !isset($_POST['import_other_drivers']) || $_POST['import_other_drivers'] === '1';
        $remember = !isset($_POST['save_as_my_driver']) || $_POST['save_as_my_driver'] === '1';

        if (!$upload_key || !$driver_name) {
            wp_send_json_error(array('message' => 'Missing upload reference or driver selection.'));
            return;
        }

        $stash = get_transient('apex_fuel_upload_' . $user_id . '_' . $upload_key);
        if (!is_array($stash) || empty($stash['xml'])) {
            wp_send_json_error(array('message' => 'Upload expired. Please re-upload the XML.'));
            return;
        }
        $xml_content = $stash['xml'];
        $source_file = !empty($stash['filename']) ? $stash['filename'] : $upload_key;

        // Save uploader's session
        $result = Apex_Notes_XML_Parser::save_for_driver(
            $xml_content,
            $user_id,
            $driver_name,
            $share,
            $source_file,
            true // skip community update; we'll do it once at the end per (track,car)
        );
        if (is_wp_error($result)) {
            wp_send_json_error(array('message' => $result->get_error_message()));
            return;
        }

        // Auto-register the user's car in the fuel_cars dropdown
        $this->maybe_add_new_car($result['session']['car_type'], $result['session']['car_class']);

        // Persist LMU driver name for next time
        if ($remember) {
            update_user_meta($user_id, 'apex_notes_lmu_driver_name', $driver_name);
        }

        // Import anonymous ghost sessions for the other drivers
        $ghost_saved = 0;
        $ghost_failed = 0;
        $community_refresh = array();
        // Always refresh community for uploader's car if shared
        if ($share) {
            $community_refresh[$result['session']['track_venue'] . '|' . $result['session']['car_type']] = array(
                'track' => $result['session']['track_venue'],
                'car' => $result['session']['car_type'],
            );
        }

        if ($import_ghosts) {
            $candidates = Apex_Notes_XML_Parser::list_candidates($xml_content);
            if (!is_wp_error($candidates)) {
                $needle = strtolower(trim($driver_name));
                foreach ($candidates as $c) {
                    if (empty($c['has_laps'])) continue;
                    if (strtolower(trim($c['name'])) === $needle) continue;
                    $ghost = Apex_Notes_XML_Parser::save_for_driver(
                        $xml_content,
                        0, // user_id=0 -> anonymous ghost, never appears in any user's "My Sessions"
                        $c['name'],
                        true, // always shared with community
                        $source_file,
                        true // skip community update (batched below)
                    );
                    if (is_wp_error($ghost)) {
                        $ghost_failed++;
                    } else {
                        $ghost_saved++;
                        $key = $ghost['session']['track_venue'] . '|' . $ghost['session']['car_type'];
                        $community_refresh[$key] = array(
                            'track' => $ghost['session']['track_venue'],
                            'car' => $ghost['session']['car_type'],
                        );
                    }
                }
            }
        }

        // Single community-average refresh per (track, car) pair touched —
        // both fuel AND tire averages so the Tire Data page reflects newly
        // uploaded XML data (tire_wear_* is extracted into fuel_laps).
        foreach ($community_refresh as $tc) {
            Apex_Notes_DB::update_community_average($tc['track'], $tc['car']);
            Apex_Notes_DB::update_tire_average($tc['track'], $tc['car']);
        }

        // Clear the transient — upload consumed
        delete_transient('apex_fuel_upload_' . $user_id . '_' . $upload_key);

        // Build the standard display response
        $session = $result['session'];
        $laps = $result['laps'];
        $tank_capacity = Apex_Notes_DB::get_tank_capacity($session['car_type']);

        wp_send_json_success(array(
            'session_id' => $result['session_id'],
            'player_name' => $result['player_name'],
            'session_type' => $result['session_type'],
            'track' => $session['track_venue'],
            'car' => $session['car_type'],
            'car_class' => $session['car_class'],
            'session_date' => $session['session_date'],
            'total_laps' => $session['total_laps'],
            'valid_laps' => $session['valid_laps'],
            'pitstops' => $session['pitstops'],
            'tank_capacity' => $tank_capacity,
            'fuel_mult' => isset($session['fuel_mult']) ? floatval($session['fuel_mult']) : 1.0,
            'avg_fuel_percent' => $session['avg_fuel_percent'],
            'avg_fuel_liters' => $session['avg_fuel_liters'],
            'min_fuel_liters' => $session['min_fuel_percent'] ? $session['min_fuel_percent'] * $tank_capacity : null,
            'max_fuel_liters' => $session['max_fuel_percent'] ? $session['max_fuel_percent'] * $tank_capacity : null,
            'avg_ve_percent' => $session['avg_ve_percent'],
            'avg_lap_time' => $session['avg_lap_time'],
            'best_lap_time' => $session['best_lap_time'],
            'avg_lap_time_formatted' => Apex_Notes_XML_Parser::format_lap_time($session['avg_lap_time']),
            'best_lap_time_formatted' => Apex_Notes_XML_Parser::format_lap_time($session['best_lap_time']),
            'shared' => $share,
            'laps' => $laps,
            'ghost_sessions_imported' => $ghost_saved,
            'ghost_sessions_failed' => $ghost_failed,
        ));
    }

    /**
     * Save the user's LMU driver name to user meta so future uploads can
     * auto-match. Empty string clears the preference.
     */
    public function save_lmu_driver_name() {
        $this->verify_nonce();
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => 'You must be logged in.'));
            return;
        }
        $user_id = get_current_user_id();
        $name = isset($_POST['driver_name']) ? sanitize_text_field(wp_unslash($_POST['driver_name'])) : '';
        if ($name === '') {
            delete_user_meta($user_id, 'apex_notes_lmu_driver_name');
        } else {
            update_user_meta($user_id, 'apex_notes_lmu_driver_name', $name);
        }
        wp_send_json_success(array('saved_name' => $name));
    }
    
    /**
     * Auto-add new car to fuel cars dropdown if not already in list
     */
    private function maybe_add_new_car($car_type, $car_class) {
        if (empty($car_type) || empty($car_class)) {
            return;
        }
        
        // Get current fuel cars list
        $fuel_cars = get_option('apex_notes_fuel_cars', array());
        
        // Ensure the class exists
        if (!isset($fuel_cars[$car_class])) {
            $fuel_cars[$car_class] = array();
        }
        
        // Check if car already exists in this class
        if (!in_array($car_type, $fuel_cars[$car_class])) {
            // Add the new car
            $fuel_cars[$car_class][] = $car_type;
            
            // Sort the cars alphabetically
            sort($fuel_cars[$car_class]);
            
            // Save updated list
            update_option('apex_notes_fuel_cars', $fuel_cars);
        }
    }
    
    /**
     * Get user's fuel sessions
     */
    public function get_fuel_sessions() {
        $this->verify_nonce();
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => 'You must be logged in.'));
            return;
        }
        
        $user_id = get_current_user_id();
        $limit = isset($_POST['limit']) ? intval($_POST['limit']) : 20;
        
        $sessions = Apex_Notes_DB::get_user_fuel_sessions($user_id, $limit);
        
        // Format sessions for display
        foreach ($sessions as &$session) {
            $tank_capacity = Apex_Notes_DB::get_tank_capacity($session['car_type']);
            $session['tank_capacity'] = $tank_capacity;
            $session['avg_lap_time_formatted'] = Apex_Notes_XML_Parser::format_lap_time($session['avg_lap_time']);
            $session['best_lap_time_formatted'] = Apex_Notes_XML_Parser::format_lap_time($session['best_lap_time']);
        }
        
        wp_send_json_success(array('sessions' => $sessions));
    }
    
    /**
     * Get specific fuel session with laps
     */
    public function get_fuel_session() {
        $this->verify_nonce();
        
        $session_id = isset($_POST['session_id']) ? intval($_POST['session_id']) : 0;
        
        if (!$session_id) {
            wp_send_json_error(array('message' => 'Session ID required.'));
            return;
        }
        
        $session = Apex_Notes_DB::get_fuel_session($session_id);
        
        if (!$session) {
            wp_send_json_error(array('message' => 'Session not found.'));
            return;
        }
        
        // Check ownership or if shared
        $user_id = get_current_user_id();
        if ($session['user_id'] != $user_id && !$session['shared_with_community']) {
            wp_send_json_error(array('message' => 'Access denied.'));
            return;
        }
        
        $laps = Apex_Notes_DB::get_fuel_session_laps($session_id);
        $tank_capacity = Apex_Notes_DB::get_tank_capacity($session['car_type']);
        
        // Format data
        $session['tank_capacity'] = $tank_capacity;
        $session['avg_lap_time_formatted'] = Apex_Notes_XML_Parser::format_lap_time($session['avg_lap_time']);
        $session['best_lap_time_formatted'] = Apex_Notes_XML_Parser::format_lap_time($session['best_lap_time']);
        
        foreach ($laps as &$lap) {
            $lap['lap_time_formatted'] = Apex_Notes_XML_Parser::format_lap_time($lap['lap_time']);
            $lap['fuel_used_liters'] = $lap['fuel_used'] * $tank_capacity;
        }
        
        wp_send_json_success(array(
            'session' => $session,
            'laps' => $laps,
            'is_owner' => $session['user_id'] == $user_id
        ));
    }
    
    /**
     * Share a fuel session with community
     */
    public function share_fuel_session() {
        $this->verify_nonce();
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => 'You must be logged in.'));
            return;
        }
        
        $session_id = isset($_POST['session_id']) ? intval($_POST['session_id']) : 0;
        $user_id = get_current_user_id();
        
        if (!$session_id) {
            wp_send_json_error(array('message' => 'Session ID required.'));
            return;
        }
        
        $result = Apex_Notes_DB::share_fuel_session($session_id, $user_id);
        
        if ($result) {
            wp_send_json_success(array('message' => 'Session shared with community. Thank you for contributing!'));
        } else {
            wp_send_json_error(array('message' => 'Failed to share session.'));
        }
    }
    
    /**
     * Unshare a fuel session from community
     */
    public function unshare_fuel_session() {
        $this->verify_nonce();
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => 'You must be logged in.'));
            return;
        }
        
        $session_id = isset($_POST['session_id']) ? intval($_POST['session_id']) : 0;
        $user_id = get_current_user_id();
        
        if (!$session_id) {
            wp_send_json_error(array('message' => 'Session ID required.'));
            return;
        }
        
        $result = Apex_Notes_DB::unshare_fuel_session($session_id, $user_id);
        
        if ($result) {
            wp_send_json_success(array('message' => 'Session removed from community data.'));
        } else {
            wp_send_json_error(array('message' => 'Failed to unshare session.'));
        }
    }
    
    /**
     * Delete a fuel session
     */
    public function delete_fuel_session() {
        $this->verify_nonce();
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => 'You must be logged in.'));
            return;
        }
        
        $session_id = isset($_POST['session_id']) ? intval($_POST['session_id']) : 0;
        $user_id = get_current_user_id();
        
        if (!$session_id) {
            wp_send_json_error(array('message' => 'Session ID required.'));
            return;
        }
        
        $result = Apex_Notes_DB::delete_fuel_session($session_id, $user_id);
        
        if ($result) {
            wp_send_json_success(array('message' => 'Session deleted.'));
        } else {
            wp_send_json_error(array('message' => 'Failed to delete session.'));
        }
    }
    
    /**
     * Get community fuel data
     */
    public function get_community_fuel_data() {
        $track = isset($_POST['track']) ? sanitize_text_field($_POST['track']) : '';
        $car = isset($_POST['car']) ? sanitize_text_field($_POST['car']) : '';
        $car_class = isset($_POST['car_class']) ? sanitize_text_field($_POST['car_class']) : '';
        $by_fuel_mult = isset($_POST['by_fuel_mult']) && $_POST['by_fuel_mult'] === '1';
        $individual_sessions = isset($_POST['individual_sessions']) && $_POST['individual_sessions'] === '1';
        
        // Get individual sessions for a track+car combo
        if ($track && $car && $individual_sessions) {
            $data = Apex_Notes_DB::get_community_individual_sessions($track, $car);
            
            if ($data && count($data) > 0) {
                foreach ($data as &$item) {
                    $item['avg_lap_time_formatted'] = Apex_Notes_XML_Parser::format_lap_time($item['avg_lap_time']);
                    $item['best_lap_time_formatted'] = Apex_Notes_XML_Parser::format_lap_time($item['best_lap_time']);
                    $item['fuel_mult_label'] = floatval($item['fuel_mult']) == 1 ? '1x' : $item['fuel_mult'] . 'x';
                    
                    // Get tank capacity for liters calculation
                    $tank_capacity = Apex_Notes_DB::get_tank_capacity($item['car_type']);
                    $item['tank_capacity'] = $tank_capacity;
                    $item['avg_fuel_liters'] = $item['avg_fuel_percent'] * $tank_capacity;
                    $item['min_fuel_liters'] = $item['min_fuel_percent'] * $tank_capacity;
                    $item['max_fuel_liters'] = $item['max_fuel_percent'] * $tank_capacity;
                }
                wp_send_json_success(array('data' => $data));
            } else {
                wp_send_json_success(array('data' => array(), 'message' => 'No community sessions available.'));
            }
            return;
        }
        
        // Get specific track+car combination with fuel_mult breakdown
        if ($track && $car && $by_fuel_mult) {
            $data = Apex_Notes_DB::get_community_fuel_by_mult($track, $car);
            
            if ($data && count($data) > 0) {
                foreach ($data as &$item) {
                    $item['avg_lap_time_formatted'] = Apex_Notes_XML_Parser::format_lap_time($item['avg_lap_time']);
                    $item['best_lap_time_formatted'] = Apex_Notes_XML_Parser::format_lap_time($item['best_lap_time']);
                    $item['fuel_mult_label'] = floatval($item['fuel_mult']) == 1 ? 'Normal (1x)' : $item['fuel_mult'] . 'x';
                }
                wp_send_json_success(array('data' => $data));
            } else {
                wp_send_json_success(array('data' => null, 'message' => 'No community data available for this combination yet.'));
            }
            return;
        }
        
        // Get all cars on a track with fuel_mult breakdown
        if ($track && $by_fuel_mult) {
            $data = Apex_Notes_DB::get_track_community_fuel($track);
            
            if ($data && count($data) > 0) {
                foreach ($data as &$item) {
                    $item['avg_lap_time_formatted'] = Apex_Notes_XML_Parser::format_lap_time($item['avg_lap_time']);
                    $item['best_lap_time_formatted'] = Apex_Notes_XML_Parser::format_lap_time($item['best_lap_time']);
                    $item['fuel_mult_label'] = floatval($item['fuel_mult']) == 1 ? 'Normal (1x)' : $item['fuel_mult'] . 'x';
                }
                wp_send_json_success(array('data' => $data));
            } else {
                wp_send_json_success(array('data' => null, 'message' => 'No community data available for this track yet.'));
            }
            return;
        }
        
        // Get specific track+car combination
        if ($track && $car) {
            $data = Apex_Notes_DB::get_community_fuel_average($track, $car);
            
            if ($data) {
                $data['avg_lap_time_formatted'] = Apex_Notes_XML_Parser::format_lap_time($data['avg_lap_time']);
                wp_send_json_success(array('data' => $data));
            } else {
                wp_send_json_success(array('data' => null, 'message' => 'No community data available for this combination yet.'));
            }
            return;
        }
        
        // Get all data for a track
        if ($track) {
            $data = Apex_Notes_DB::get_track_fuel_averages($track);
            
            foreach ($data as &$item) {
                $item['avg_lap_time_formatted'] = Apex_Notes_XML_Parser::format_lap_time($item['avg_lap_time']);
            }
            
            wp_send_json_success(array('data' => $data));
            return;
        }
        
        // Get all data for a car
        if ($car) {
            $data = Apex_Notes_DB::get_car_fuel_averages($car);
            
            foreach ($data as &$item) {
                $item['avg_lap_time_formatted'] = Apex_Notes_XML_Parser::format_lap_time($item['avg_lap_time']);
            }
            
            wp_send_json_success(array('data' => $data));
            return;
        }
        
        // Get all community averages
        $min_samples = isset($_POST['min_samples']) ? intval($_POST['min_samples']) : 1;
        $data = Apex_Notes_DB::get_all_community_averages($min_samples);
        
        foreach ($data as &$item) {
            $item['avg_lap_time_formatted'] = Apex_Notes_XML_Parser::format_lap_time($item['avg_lap_time']);
        }
        
        wp_send_json_success(array('data' => $data));
    }
    
    /**
     * Get car specifications
     */
    public function get_car_specs() {
        $car_type = isset($_POST['car_type']) ? sanitize_text_field($_POST['car_type']) : null;

        $specs = Apex_Notes_DB::get_car_specs($car_type);

        wp_send_json_success(array('specs' => $specs));
    }

    // ========================================
    // PIT STRATEGY CALCULATOR + AI FUEL CHAT
    // ========================================

    /**
     * Deterministic pit-stop strategy calculator.
     *
     * Inputs (POST):
     *   session_id      (int)    - user's fuel session to base numbers on (optional if explicit params supplied)
     *   race_laps       (int)    - total race length in laps (provide this OR race_minutes)
     *   race_minutes    (float)  - total race length in minutes
     *   tank_capacity   (float)  - litres; falls back to car spec
     *   avg_fuel_per_lap (float) - litres/lap; falls back to session avg or community median
     *   avg_lap_time    (float)  - seconds; used to convert minutes→laps
     *   reserve_liters  (float)  - safety margin kept in the tank (default 0.5L)
     *   start_fuel_liters (float)- optional starting fuel (default = full tank)
     *
     * Output: stint plan with pit lap numbers and fuel loads.
     */
    public function calculate_pit_strategy() {
        $this->verify_nonce();

        $session_id = isset($_POST['session_id']) ? intval($_POST['session_id']) : 0;
        $session = $session_id ? Apex_Notes_DB::get_fuel_session($session_id) : null;

        // Resolve parameters with session/community/defaults as fallbacks
        $tank_capacity = isset($_POST['tank_capacity']) ? floatval($_POST['tank_capacity']) : 0;
        $avg_fuel = isset($_POST['avg_fuel_per_lap']) ? floatval($_POST['avg_fuel_per_lap']) : 0;
        $avg_lap_time = isset($_POST['avg_lap_time']) ? floatval($_POST['avg_lap_time']) : 0;
        $race_laps = isset($_POST['race_laps']) ? intval($_POST['race_laps']) : 0;
        $race_minutes = isset($_POST['race_minutes']) ? floatval($_POST['race_minutes']) : 0;
        $reserve_liters = isset($_POST['reserve_liters']) ? max(0.0, floatval($_POST['reserve_liters'])) : 0.5;
        $start_fuel = isset($_POST['start_fuel_liters']) ? floatval($_POST['start_fuel_liters']) : 0;
        $fuel_mult = isset($_POST['fuel_mult']) ? floatval($_POST['fuel_mult']) : 0;
        $track_venue = isset($_POST['track_venue']) ? sanitize_text_field($_POST['track_venue']) : '';
        $car_type = isset($_POST['car_type']) ? sanitize_text_field($_POST['car_type']) : '';

        if ($session) {
            if (!$tank_capacity) {
                $tank_capacity = floatval(Apex_Notes_DB::get_tank_capacity($session['car_type']));
            }
            if (!$avg_fuel && !empty($session['avg_fuel_liters'])) {
                $avg_fuel = floatval($session['avg_fuel_liters']);
            }
            if (!$avg_lap_time && !empty($session['avg_lap_time'])) {
                $avg_lap_time = floatval($session['avg_lap_time']);
            }
            if (!$fuel_mult && !empty($session['fuel_mult'])) {
                $fuel_mult = floatval($session['fuel_mult']);
            }
            if (!$track_venue) {
                $track_venue = $session['track_venue'];
            }
            if (!$car_type) {
                $car_type = $session['car_type'];
            }
        }

        // Community fallback for avg_fuel if user hasn't uploaded a session
        if (!$avg_fuel && $track_venue && $car_type) {
            $bucket = Apex_Notes_DB::get_community_fuel_average($track_venue, $car_type, $fuel_mult ?: 1.0);
            if ($bucket && !empty($bucket['median_fuel_liters'])) {
                $avg_fuel = floatval($bucket['median_fuel_liters']);
                if (!$avg_lap_time && !empty($bucket['avg_lap_time'])) {
                    $avg_lap_time = floatval($bucket['avg_lap_time']);
                }
            }
        }

        if (!$tank_capacity && $car_type) {
            $tank_capacity = floatval(Apex_Notes_DB::get_tank_capacity($car_type)) ?: 100.0;
        }

        // Validate
        if ($tank_capacity <= 0 || $avg_fuel <= 0) {
            wp_send_json_error(array('message' => 'Need a tank capacity and an average fuel-per-lap figure. Upload a session or pick a track+car with community data.'));
            return;
        }
        if ($race_laps <= 0 && $race_minutes <= 0) {
            wp_send_json_error(array('message' => 'Specify the race length in laps or minutes.'));
            return;
        }
        if ($race_minutes > 0 && $avg_lap_time <= 0) {
            wp_send_json_error(array('message' => 'Average lap time required to plan a timed race.'));
            return;
        }

        // Convert timed race to laps (round up so we plan enough fuel to finish)
        if ($race_laps <= 0) {
            $race_laps = (int) ceil(($race_minutes * 60.0) / $avg_lap_time);
        }

        if ($start_fuel <= 0 || $start_fuel > $tank_capacity) {
            $start_fuel = $tank_capacity;
        }

        $usable_tank = max(0.0, $tank_capacity - $reserve_liters);
        $laps_per_tank = (int) floor($usable_tank / $avg_fuel);
        if ($laps_per_tank < 1) {
            wp_send_json_error(array('message' => 'Tank capacity cannot cover a single lap at this fuel burn.'));
            return;
        }

        // Stint planning: first stint constrained by start_fuel, subsequent by full tank.
        $laps_first_stint = (int) floor(max(0.0, $start_fuel - $reserve_liters) / $avg_fuel);
        $laps_first_stint = max(0, min($laps_first_stint, $race_laps));
        $total_laps = $race_laps;

        $stints = array();
        $remaining = $total_laps;
        $pit_laps = array();
        $cumulative_lap = 0;

        if ($laps_first_stint >= $remaining) {
            // No pit stops needed
            $stints[] = array(
                'stint' => 1,
                'laps' => $remaining,
                'start_lap' => 1,
                'end_lap' => $remaining,
                'fuel_needed_liters' => round($remaining * $avg_fuel + $reserve_liters, 2),
                'pit_in_lap' => null,
            );
        } else {
            // First stint
            $stints[] = array(
                'stint' => 1,
                'laps' => $laps_first_stint,
                'start_lap' => 1,
                'end_lap' => $laps_first_stint,
                'fuel_needed_liters' => round($laps_first_stint * $avg_fuel + $reserve_liters, 2),
                'pit_in_lap' => $laps_first_stint,
            );
            $pit_laps[] = $laps_first_stint;
            $cumulative_lap = $laps_first_stint;
            $remaining -= $laps_first_stint;

            // Subsequent stints
            $stint_num = 2;
            while ($remaining > 0) {
                $this_stint = min($laps_per_tank, $remaining);
                $start = $cumulative_lap + 1;
                $end = $cumulative_lap + $this_stint;
                $fuel_needed = round($this_stint * $avg_fuel + $reserve_liters, 2);
                $stints[] = array(
                    'stint' => $stint_num,
                    'laps' => $this_stint,
                    'start_lap' => $start,
                    'end_lap' => $end,
                    'fuel_needed_liters' => $fuel_needed,
                    'pit_in_lap' => ($remaining - $this_stint > 0) ? $end : null,
                );
                if ($remaining - $this_stint > 0) {
                    $pit_laps[] = $end;
                }
                $cumulative_lap = $end;
                $remaining -= $this_stint;
                $stint_num++;
            }
        }

        $num_pitstops = count($pit_laps);

        // Even-split alternative: distribute pit stops evenly to minimise fuel carried.
        // Number of pit stops needed at minimum is ceil(total_laps / laps_per_tank) - 1
        // when starting with a full tank.
        $min_stops = max(0, (int) ceil($total_laps / $laps_per_tank) - 1);
        $even_pit_laps = array();
        if ($min_stops > 0) {
            $stints_count = $min_stops + 1;
            $base_laps = intdiv($total_laps, $stints_count);
            $extra = $total_laps - ($base_laps * $stints_count);
            $lap = 0;
            for ($i = 0; $i < $min_stops; $i++) {
                $lap += $base_laps + ($i < $extra ? 1 : 0);
                $even_pit_laps[] = $lap;
            }
        }

        $total_fuel_used = round($total_laps * $avg_fuel, 2);
        $est_race_time_s = $total_laps * $avg_lap_time;

        wp_send_json_success(array(
            'inputs' => array(
                'tank_capacity' => round($tank_capacity, 2),
                'avg_fuel_per_lap' => round($avg_fuel, 3),
                'avg_lap_time' => $avg_lap_time ? round($avg_lap_time, 3) : null,
                'avg_lap_time_formatted' => $avg_lap_time ? Apex_Notes_XML_Parser::format_lap_time($avg_lap_time) : null,
                'race_laps' => $race_laps,
                'race_minutes_input' => $race_minutes ?: null,
                'reserve_liters' => round($reserve_liters, 2),
                'start_fuel_liters' => round($start_fuel, 2),
                'fuel_mult' => $fuel_mult ?: 1.0,
                'track_venue' => $track_venue,
                'car_type' => $car_type,
                'source' => $session ? 'session' : ($track_venue && $car_type ? 'community' : 'manual'),
            ),
            'plan' => array(
                'laps_per_tank' => $laps_per_tank,
                'num_pitstops' => $num_pitstops,
                'min_possible_pitstops' => $min_stops,
                'pit_laps' => $pit_laps,
                'even_split_pit_laps' => $even_pit_laps,
                'total_fuel_used_liters' => $total_fuel_used,
                'estimated_race_time_s' => $est_race_time_s ?: null,
                'estimated_race_time_formatted' => $est_race_time_s ? self::format_duration_h($est_race_time_s) : null,
                'stints' => $stints,
            ),
        ));
    }

    /**
     * Human-readable H:MM:SS duration.
     */
    private static function format_duration_h($seconds) {
        $seconds = max(0, (int) round($seconds));
        $h = intdiv($seconds, 3600);
        $m = intdiv($seconds % 3600, 60);
        $s = $seconds % 60;
        return sprintf('%d:%02d:%02d', $h, $m, $s);
    }

    /**
     * AI fuel chat — answers questions about a user's uploaded fuel data
     * and suggests pit strategies.
     *
     * Uses Anthropic Messages API. Employs prompt caching on the large,
     * stable system prompt so repeated questions are cheaper.
     *
     * POST:
     *   session_id  (int, optional)  - focus the AI on this session
     *   message     (string)         - the user's question
     *   history     (JSON array)     - recent turns [{role, content}, ...]
     */
    public function ai_fuel_chat() {
        $this->verify_nonce();

        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => 'You must be logged in to use AI chat.'));
            return;
        }

        $user_id = get_current_user_id();
        $message = isset($_POST['message']) ? sanitize_textarea_field($_POST['message']) : '';
        $session_id = isset($_POST['session_id']) ? intval($_POST['session_id']) : 0;
        $history = isset($_POST['history']) ? json_decode(stripslashes($_POST['history']), true) : array();

        if (empty($message)) {
            wp_send_json_error(array('message' => 'Please enter a question.'));
            return;
        }

        // Rate limit — 30 AI messages per user per day
        $rate_key = 'apex_notes_ai_fuel_rate_' . $user_id;
        $today_count = intval(get_transient($rate_key));
        if ($today_count >= 30) {
            wp_send_json_error(array('message' => 'Daily AI chat limit reached. Try again tomorrow.'));
            return;
        }

        $api_key = get_option('apex_notes_anthropic_api_key', '');
        if (empty($api_key)) {
            wp_send_json_error(array(
                'message' => 'AI chat is not configured. An admin needs to add an Anthropic API key in Apex Notes > Settings.'
            ));
            return;
        }

        // Build structured context from the user's data
        $context = $this->build_fuel_ai_context($user_id, $session_id);
        $context_json = wp_json_encode($context, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        // Static system prompt — cacheable
        $system_prompt = "You are ApexFuelBot, a pit-strategy and fuel-analytics assistant for the sim racing game Le Mans Ultimate.\n\n"
            . "Your job is to analyse a driver's race XML data and answer questions about:\n"
            . "- Optimal pit-stop laps given race length, tank size, and average fuel burn\n"
            . "- Number of stints, fuel loads per stint, and fuel carried (weight vs. safety margin)\n"
            . "- Virtual Energy (VE) usage for Hypercars and how it interacts with fuel strategy\n"
            . "- Stint pace, lap-time consistency, and where time is being lost\n"
            . "- Comparison of the driver's numbers to community medians (provided in context)\n\n"
            . "CALCULATION RULES:\n"
            . "- laps_per_tank = floor((tank_capacity - reserve_liters) / avg_fuel_per_lap)\n"
            . "- min_pit_stops = ceil(race_laps / laps_per_tank) - 1 (if starting with a full tank)\n"
            . "- When recommending pit laps, split the race evenly across stints to minimise fuel carried (lap-time gain).\n"
            . "- Always keep a 0.3-1.0 L reserve unless the user asks for aggressive splash-and-dash.\n"
            . "- If the data provided has fuel_mult > 1, the driver's avg_fuel_per_lap is already scaled; do not normalise unless asked.\n\n"
            . "STYLE:\n"
            . "- Be concrete and numeric. Round litres to 2 decimals, lap times to mm:ss.sss.\n"
            . "- When suggesting pit laps, show a table: Stint | Laps | Pit lap | Fuel load.\n"
            . "- Keep answers under 300 words unless more detail is requested.\n"
            . "- If the user's question isn't about fuel, pit strategy, pace, or race data, politely redirect.\n"
            . "- If data is missing (e.g. no sessions uploaded), tell them what to upload.";

        // Build messages
        $messages = array();
        if (!empty($history) && is_array($history)) {
            foreach (array_slice($history, -6) as $msg) {
                if (isset($msg['role'], $msg['content']) && in_array($msg['role'], array('user', 'assistant'), true)) {
                    $messages[] = array(
                        'role' => $msg['role'],
                        'content' => (string) $msg['content'],
                    );
                }
            }
        }

        $user_content = "Data about my uploaded sessions (JSON):\n```json\n" . $context_json . "\n```\n\nQuestion: " . $message;
        $messages[] = array('role' => 'user', 'content' => $user_content);

        // Call Anthropic with a cache_control breakpoint on the system prompt
        $body = array(
            'model' => 'claude-haiku-4-5-20251001',
            'max_tokens' => 1024,
            'system' => array(
                array(
                    'type' => 'text',
                    'text' => $system_prompt,
                    'cache_control' => array('type' => 'ephemeral'),
                ),
            ),
            'messages' => $messages,
        );

        $response = wp_remote_post('https://api.anthropic.com/v1/messages', array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'x-api-key' => $api_key,
                'anthropic-version' => '2023-06-01',
            ),
            'body' => wp_json_encode($body),
            'timeout' => 60,
        ));

        if (is_wp_error($response)) {
            wp_send_json_error(array('message' => 'AI API error: ' . $response->get_error_message()));
            return;
        }

        $status = wp_remote_retrieve_response_code($response);
        $decoded = json_decode(wp_remote_retrieve_body($response), true);

        if ($status !== 200 || !isset($decoded['content'])) {
            $err = isset($decoded['error']['message']) ? $decoded['error']['message'] : 'Unknown error';
            wp_send_json_error(array('message' => 'AI API returned ' . $status . ': ' . $err));
            return;
        }

        $reply = '';
        foreach ($decoded['content'] as $block) {
            if (isset($block['type']) && $block['type'] === 'text') {
                $reply .= $block['text'];
            }
        }

        // Update rate limit (expires at end of day)
        set_transient($rate_key, $today_count + 1, DAY_IN_SECONDS);

        wp_send_json_success(array(
            'reply' => $reply,
            'usage' => isset($decoded['usage']) ? $decoded['usage'] : null,
            'remaining_today' => max(0, 29 - $today_count),
        ));
    }

    /**
     * Assemble a compact JSON context about the user's fuel data for the AI.
     * Includes the focused session (if any), recent sessions, and community
     * medians for the relevant track+car buckets.
     */
    private function build_fuel_ai_context($user_id, $session_id = 0) {
        $ctx = array(
            'focused_session' => null,
            'recent_sessions' => array(),
            'community_context' => array(),
        );

        if ($session_id) {
            $session = Apex_Notes_DB::get_fuel_session($session_id);
            if ($session && intval($session['user_id']) === intval($user_id)) {
                $laps = Apex_Notes_DB::get_fuel_session_laps($session_id);
                $tank = Apex_Notes_DB::get_tank_capacity($session['car_type']);
                $ctx['focused_session'] = array(
                    'id' => intval($session['id']),
                    'track' => $session['track_venue'],
                    'car' => $session['car_type'],
                    'car_class' => $session['car_class'],
                    'session_type' => $session['session_type'],
                    'fuel_mult' => floatval($session['fuel_mult']),
                    'tank_capacity_l' => floatval($tank),
                    'total_laps' => intval($session['total_laps']),
                    'valid_laps' => intval($session['valid_laps']),
                    'pitstops' => intval($session['pitstops']),
                    'avg_fuel_liters' => $session['avg_fuel_liters'] !== null ? round(floatval($session['avg_fuel_liters']), 3) : null,
                    'avg_ve_percent' => $session['avg_ve_percent'] !== null ? round(floatval($session['avg_ve_percent']) * 100, 2) : null,
                    'avg_lap_time_s' => $session['avg_lap_time'] !== null ? round(floatval($session['avg_lap_time']), 3) : null,
                    'best_lap_time_s' => $session['best_lap_time'] !== null ? round(floatval($session['best_lap_time']), 3) : null,
                );

                // Per-lap summary (trimmed list to keep prompt small)
                $lap_summary = array();
                $tank_val = floatval($tank) ?: 100.0;
                foreach ($laps as $l) {
                    $lap_summary[] = array(
                        'n' => intval($l['lap_num']),
                        'time' => $l['lap_time'] !== null ? round(floatval($l['lap_time']), 3) : null,
                        'fuel_l' => $l['fuel_used'] !== null ? round(floatval($l['fuel_used']) * $tank_val, 3) : null,
                        've_pct' => $l['ve_used'] !== null ? round(floatval($l['ve_used']) * 100, 2) : null,
                        'pit' => intval($l['is_pit_lap']) ? 1 : 0,
                        'valid' => intval($l['is_valid']) ? 1 : 0,
                    );
                }
                // Cap to last 40 laps to stay compact
                if (count($lap_summary) > 40) {
                    $lap_summary = array_slice($lap_summary, -40);
                }
                $ctx['focused_session']['laps'] = $lap_summary;

                // Pull community bucket(s) for same track+car
                $buckets = Apex_Notes_DB::get_community_fuel_buckets($session['track_venue'], $session['car_type']);
                foreach ($buckets as $b) {
                    $ctx['community_context'][] = array(
                        'track' => $b['track_venue'],
                        'car' => $b['car_type'],
                        'fuel_mult' => floatval($b['fuel_mult']),
                        'sample_count' => intval($b['sample_count']),
                        'lap_count' => intval($b['lap_count']),
                        'median_fuel_l' => $b['median_fuel_liters'] !== null ? round(floatval($b['median_fuel_liters']), 3) : null,
                        'avg_fuel_l' => $b['avg_fuel_liters'] !== null ? round(floatval($b['avg_fuel_liters']), 3) : null,
                        'min_fuel_l' => $b['min_fuel_liters'] !== null ? round(floatval($b['min_fuel_liters']), 3) : null,
                        'max_fuel_l' => $b['max_fuel_liters'] !== null ? round(floatval($b['max_fuel_liters']), 3) : null,
                        'avg_lap_time_s' => $b['avg_lap_time'] !== null ? round(floatval($b['avg_lap_time']), 3) : null,
                    );
                }
            }
        }

        // Always include the user's last few sessions as background
        $recent = Apex_Notes_DB::get_user_fuel_sessions($user_id, 8);
        if (is_array($recent)) {
            foreach ($recent as $s) {
                $ctx['recent_sessions'][] = array(
                    'id' => intval($s['id']),
                    'track' => $s['track_venue'],
                    'car' => $s['car_type'],
                    'class' => $s['car_class'],
                    'session_type' => $s['session_type'],
                    'fuel_mult' => floatval($s['fuel_mult']),
                    'valid_laps' => intval($s['valid_laps']),
                    'avg_fuel_l' => $s['avg_fuel_liters'] !== null ? round(floatval($s['avg_fuel_liters']), 3) : null,
                    'avg_lap_time_s' => $s['avg_lap_time'] !== null ? round(floatval($s['avg_lap_time']), 3) : null,
                    'best_lap_time_s' => $s['best_lap_time'] !== null ? round(floatval($s['best_lap_time']), 3) : null,
                );
            }
        }

        return $ctx;
    }

    // ========================================
    // TIRE DATA HANDLERS
    // ========================================
    
    /**
     * Get available tracks and cars for tire calculator
     */
    public function get_tire_tracks() {
        global $wpdb;
        
        // Get unique tracks from tire averages
        $tracks_table = $wpdb->prefix . 'apex_notes_tire_averages';
        $fuel_table = $wpdb->prefix . 'apex_notes_fuel_averages';
        
        // Try tire averages first, fall back to fuel averages
        $tracks = $wpdb->get_col("SELECT DISTINCT track_venue FROM $tracks_table ORDER BY track_venue ASC");
        
        if (empty($tracks)) {
            $tracks = $wpdb->get_col("SELECT DISTINCT track_venue FROM $fuel_table ORDER BY track_venue ASC");
        }
        
        // Get unique cars
        $cars = $wpdb->get_col("SELECT DISTINCT car_type FROM $tracks_table ORDER BY car_type ASC");
        
        if (empty($cars)) {
            $cars = $wpdb->get_col("SELECT DISTINCT car_type FROM $fuel_table ORDER BY car_type ASC");
        }
        
        wp_send_json_success(array(
            'tracks' => $tracks,
            'cars' => $cars
        ));
    }
    
    /**
     * Calculate tire strategy based on community data
     */
    public function calculate_tire_strategy() {
        $track = isset($_POST['track']) ? sanitize_text_field($_POST['track']) : '';
        $car = isset($_POST['car']) ? sanitize_text_field($_POST['car']) : '';
        $race_laps = isset($_POST['race_laps']) ? intval($_POST['race_laps']) : 0;
        $threshold = isset($_POST['threshold']) ? intval($_POST['threshold']) : 50;
        
        if (!$track || !$car || !$race_laps) {
            wp_send_json_error(array('message' => 'Missing parameters.'));
            return;
        }
        
        // Get tire wear data from community averages
        $tire_data = Apex_Notes_DB::get_tire_average($track, $car);
        
        if (!$tire_data) {
            // Try to calculate from fuel laps if no tire averages exist
            $tire_data = $this->calculate_tire_wear_from_laps($track, $car);
        }
        
        if (!$tire_data) {
            wp_send_json_error(array('message' => 'No tire data available for this combination.'));
            return;
        }
        
        // Calculate strategy
        $wear_fl = floatval($tire_data['avg_wear_fl_per_lap']) * 100;
        $wear_fr = floatval($tire_data['avg_wear_fr_per_lap']) * 100;
        $wear_rl = floatval($tire_data['avg_wear_rl_per_lap']) * 100;
        $wear_rr = floatval($tire_data['avg_wear_rr_per_lap']) * 100;
        
        // Find critical tire (highest wear)
        $wears = array('FL' => $wear_fl, 'FR' => $wear_fr, 'RL' => $wear_rl, 'RR' => $wear_rr);
        arsort($wears);
        $critical_tire = key($wears);
        $critical_wear = current($wears);
        
        // Calculate tire life based on threshold
        // Starting at 100%, how many laps to reach threshold?
        $usable_percent = 100 - $threshold;
        $estimated_tire_life = floor($usable_percent / $critical_wear);
        
        // Calculate recommended stops
        $recommended_stops = max(0, ceil($race_laps / $estimated_tire_life) - 1);
        
        wp_send_json_success(array(
            'wear_fl' => $wear_fl,
            'wear_fr' => $wear_fr,
            'wear_rl' => $wear_rl,
            'wear_rr' => $wear_rr,
            'critical_tire' => $critical_tire,
            'avg_critical_wear' => $critical_wear,
            'estimated_tire_life' => $estimated_tire_life,
            'recommended_stops' => $recommended_stops,
            'threshold' => $threshold,
            'sample_count' => $tire_data['sample_count'] ?? 0
        ));
    }
    
    /**
     * Calculate tire wear from stored lap data
     */
    private function calculate_tire_wear_from_laps($track, $car) {
        global $wpdb;
        
        $sessions_table = $wpdb->prefix . 'apex_notes_fuel_sessions';
        $laps_table = $wpdb->prefix . 'apex_notes_fuel_laps';
        
        // Get sessions for this track/car combo that are shared
        $session_ids = $wpdb->get_col($wpdb->prepare(
            "SELECT id FROM $sessions_table 
             WHERE track_venue = %s AND car_type = %s AND shared_with_community = 1",
            $track, $car
        ));
        
        if (empty($session_ids)) {
            return null;
        }
        
        // Calculate average wear per lap for each corner
        $placeholders = implode(',', array_fill(0, count($session_ids), '%d'));
        $query = $wpdb->prepare(
            "SELECT 
                COUNT(DISTINCT l.session_id) as sample_count,
                COUNT(*) as lap_count,
                AVG(
                    CASE WHEN l.lap_num > 1 AND prev.tire_wear_fl IS NOT NULL 
                    THEN prev.tire_wear_fl - l.tire_wear_fl ELSE NULL END
                ) as avg_wear_fl_per_lap,
                AVG(
                    CASE WHEN l.lap_num > 1 AND prev.tire_wear_fr IS NOT NULL 
                    THEN prev.tire_wear_fr - l.tire_wear_fr ELSE NULL END
                ) as avg_wear_fr_per_lap,
                AVG(
                    CASE WHEN l.lap_num > 1 AND prev.tire_wear_rl IS NOT NULL 
                    THEN prev.tire_wear_rl - l.tire_wear_rl ELSE NULL END
                ) as avg_wear_rl_per_lap,
                AVG(
                    CASE WHEN l.lap_num > 1 AND prev.tire_wear_rr IS NOT NULL 
                    THEN prev.tire_wear_rr - l.tire_wear_rr ELSE NULL END
                ) as avg_wear_rr_per_lap
            FROM $laps_table l
            LEFT JOIN $laps_table prev ON l.session_id = prev.session_id AND l.lap_num = prev.lap_num + 1
            WHERE l.session_id IN ($placeholders) 
            AND l.is_valid = 1 
            AND l.is_pit_lap = 0
            AND l.tire_wear_fl IS NOT NULL",
            ...$session_ids
        );
        
        $result = $wpdb->get_row($query, ARRAY_A);
        
        if (!$result || !$result['avg_wear_fl_per_lap']) {
            return null;
        }
        
        return $result;
    }
    
    /**
     * Get community tire data for display
     */
    public function get_tire_community_data() {
        global $wpdb;
        
        $table = $wpdb->prefix . 'apex_notes_tire_averages';
        
        $data = $wpdb->get_results(
            "SELECT * FROM $table ORDER BY sample_count DESC, track_venue ASC",
            ARRAY_A
        );
        
        if (empty($data)) {
            // Try to build from fuel laps data
            $data = $this->build_tire_community_from_laps();
        }
        
        wp_send_json_success($data);
    }
    
    /**
     * Build tire community data from fuel laps
     */
    private function build_tire_community_from_laps() {
        global $wpdb;
        
        $sessions_table = $wpdb->prefix . 'apex_notes_fuel_sessions';
        $laps_table = $wpdb->prefix . 'apex_notes_fuel_laps';
        
        // Get unique track/car combos with tire data
        $combos = $wpdb->get_results(
            "SELECT DISTINCT s.track_venue, s.car_type, s.car_class
             FROM $sessions_table s
             INNER JOIN $laps_table l ON s.id = l.session_id
             WHERE s.shared_with_community = 1 
             AND l.tire_wear_fl IS NOT NULL
             GROUP BY s.track_venue, s.car_type
             HAVING COUNT(l.id) >= 5",
            ARRAY_A
        );
        
        $result = array();
        
        foreach ($combos as $combo) {
            $tire_data = $this->calculate_tire_wear_from_laps($combo['track_venue'], $combo['car_type']);
            
            if ($tire_data && $tire_data['avg_wear_fl_per_lap']) {
                // Determine critical tire
                $wears = array(
                    'FL' => floatval($tire_data['avg_wear_fl_per_lap']),
                    'FR' => floatval($tire_data['avg_wear_fr_per_lap']),
                    'RL' => floatval($tire_data['avg_wear_rl_per_lap']),
                    'RR' => floatval($tire_data['avg_wear_rr_per_lap'])
                );
                arsort($wears);
                $critical_tire = key($wears);
                $critical_wear = current($wears) * 100;
                
                // Estimate tire life (to 50%)
                $estimated_life = $critical_wear > 0 ? floor(50 / $critical_wear) : null;
                
                $result[] = array(
                    'track_venue' => $combo['track_venue'],
                    'car_type' => $combo['car_type'],
                    'car_class' => $combo['car_class'],
                    'tire_compound' => 'Medium',
                    'sample_count' => $tire_data['sample_count'],
                    'lap_count' => $tire_data['lap_count'],
                    'avg_wear_fl_per_lap' => $tire_data['avg_wear_fl_per_lap'],
                    'avg_wear_fr_per_lap' => $tire_data['avg_wear_fr_per_lap'],
                    'avg_wear_rl_per_lap' => $tire_data['avg_wear_rl_per_lap'],
                    'avg_wear_rr_per_lap' => $tire_data['avg_wear_rr_per_lap'],
                    'critical_tire' => $critical_tire,
                    'estimated_tire_life' => $estimated_life
                );
            }
        }
        
        return $result;
    }
    
    // ========================================
    // LIVE BROADCAST HANDLERS
    // ========================================
    
    /**
     * Start a new broadcast session
     */
    public function start_broadcast() {
        $this->verify_nonce();
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => 'You must be logged in to broadcast.'));
            return;
        }
        
        $user_id = get_current_user_id();
        $session_name = isset($_POST['session_name']) ? sanitize_text_field($_POST['session_name']) : '';
        $stream_url = isset($_POST['stream_url']) ? esc_url_raw($_POST['stream_url']) : '';
        
        // Check if user is premium (for stream embed)
        $is_premium = $this->user_is_premium($user_id);
        
        // If not premium, clear stream URL
        if (!$is_premium) {
            $stream_url = '';
        }
        
        $result = Apex_Notes_DB::create_live_broadcast($user_id, $session_name, $stream_url, $is_premium);
        
        if (!$result) {
            wp_send_json_error(array('message' => 'Failed to create broadcast.'));
            return;
        }
        
        // Build spectator URL
        $spectator_url = add_query_arg(array(
            'broadcast' => $result['broadcast_key']
        ), home_url('/apex-race-notes/'));
        
        wp_send_json_success(array(
            'broadcast_id' => $result['broadcast_id'],
            'broadcast_key' => $result['broadcast_key'],
            'spectator_url' => $spectator_url,
            'is_premium' => $is_premium,
            'affiliate_links' => Apex_Notes_DB::get_user_affiliate_links($user_id)
        ));
    }
    
    /**
     * Check if user has premium access (placeholder - customize based on your system)
     */
    private function user_is_premium($user_id) {
        // Check for premium role or capability
        $user = get_user_by('id', $user_id);
        if (!$user) return false;
        
        // Option 1: Check for specific role
        if (in_array('administrator', $user->roles) || in_array('premium', $user->roles)) {
            return true;
        }
        
        // Option 2: Check for user meta
        $is_premium = get_user_meta($user_id, 'apex_premium', true);
        if ($is_premium) {
            return true;
        }
        
        // Option 3: Check for WooCommerce subscription (if applicable)
        // Add your subscription check logic here
        
        return false;
    }
    
    /**
     * End current broadcast
     */
    public function end_broadcast() {
        $this->verify_nonce();
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => 'You must be logged in.'));
            return;
        }
        
        $user_id = get_current_user_id();
        $broadcast_id = isset($_POST['broadcast_id']) ? intval($_POST['broadcast_id']) : 0;
        
        if ($broadcast_id) {
            Apex_Notes_DB::end_broadcast($broadcast_id, $user_id);
        } else {
            Apex_Notes_DB::end_user_broadcasts($user_id);
        }
        
        wp_send_json_success(array('message' => 'Broadcast ended.'));
    }
    
    /**
     * Get user's active broadcast
     */
    public function get_my_broadcast() {
        $this->verify_nonce();
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => 'You must be logged in.'));
            return;
        }
        
        $user_id = get_current_user_id();
        $broadcast = Apex_Notes_DB::get_user_active_broadcast($user_id);
        
        if ($broadcast) {
            $broadcast['spectator_url'] = add_query_arg(array(
                'broadcast' => $broadcast['broadcast_key']
            ), home_url('/apex-race-notes/'));
            $broadcast['is_premium'] = $this->user_is_premium($user_id);
        }
        
        // Always return affiliate links
        $affiliate_links = Apex_Notes_DB::get_user_affiliate_links($user_id);
        
        wp_send_json_success(array(
            'broadcast' => $broadcast,
            'affiliate_links' => $affiliate_links,
            'is_premium' => $this->user_is_premium($user_id)
        ));
    }
    
    /**
     * Update broadcast stream URL (premium only)
     */
    public function update_broadcast_stream() {
        $this->verify_nonce();
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => 'You must be logged in.'));
            return;
        }
        
        $user_id = get_current_user_id();
        
        // Check premium
        if (!$this->user_is_premium($user_id)) {
            wp_send_json_error(array('message' => 'Stream embedding is a premium feature.'));
            return;
        }
        
        $broadcast_id = isset($_POST['broadcast_id']) ? intval($_POST['broadcast_id']) : 0;
        $stream_url = isset($_POST['stream_url']) ? esc_url_raw($_POST['stream_url']) : '';
        
        if (!$broadcast_id) {
            wp_send_json_error(array('message' => 'Invalid broadcast ID.'));
            return;
        }
        
        Apex_Notes_DB::update_broadcast_stream($broadcast_id, $user_id, $stream_url);
        
        wp_send_json_success(array('message' => 'Stream URL updated.'));
    }
    
    // ========================================
    // AFFILIATE LINK HANDLERS
    // ========================================
    
    /**
     * Get user's affiliate links
     */
    public function get_affiliate_links() {
        $this->verify_nonce();
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => 'You must be logged in.'));
            return;
        }
        
        $user_id = get_current_user_id();
        $links = Apex_Notes_DB::get_user_affiliate_links($user_id);
        
        wp_send_json_success(array('links' => $links));
    }
    
    /**
     * Add affiliate link
     */
    public function add_affiliate_link() {
        $this->verify_nonce();
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => 'You must be logged in.'));
            return;
        }
        
        $user_id = get_current_user_id();
        $link_type = isset($_POST['link_type']) ? sanitize_text_field($_POST['link_type']) : '';
        $label = isset($_POST['label']) ? sanitize_text_field($_POST['label']) : '';
        $url = isset($_POST['url']) ? esc_url_raw($_POST['url']) : '';
        
        if (empty($link_type) || empty($label) || empty($url)) {
            wp_send_json_error(array('message' => 'Please fill in all fields.'));
            return;
        }
        
        // Validate URL
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            wp_send_json_error(array('message' => 'Please enter a valid URL.'));
            return;
        }
        
        $link_id = Apex_Notes_DB::add_affiliate_link($user_id, $link_type, $label, $url);
        
        if (!$link_id) {
            wp_send_json_error(array('message' => 'Failed to add link. Maximum 10 links allowed.'));
            return;
        }
        
        $links = Apex_Notes_DB::get_user_affiliate_links($user_id);
        
        wp_send_json_success(array(
            'message' => 'Link added!',
            'link_id' => $link_id,
            'links' => $links
        ));
    }
    
    /**
     * Remove affiliate link
     */
    public function remove_affiliate_link() {
        $this->verify_nonce();
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => 'You must be logged in.'));
            return;
        }
        
        $user_id = get_current_user_id();
        $link_id = isset($_POST['link_id']) ? intval($_POST['link_id']) : 0;
        
        if (!$link_id) {
            wp_send_json_error(array('message' => 'Invalid link ID.'));
            return;
        }
        
        Apex_Notes_DB::remove_affiliate_link($link_id, $user_id);
        
        $links = Apex_Notes_DB::get_user_affiliate_links($user_id);
        
        wp_send_json_success(array(
            'message' => 'Link removed.',
            'links' => $links
        ));
    }
    
    /**
     * Get all active broadcasts (public listing)
     */
    public function get_active_broadcasts() {
        $broadcasts = Apex_Notes_DB::get_active_broadcasts();
        
        foreach ($broadcasts as &$broadcast) {
            $broadcast['spectator_url'] = add_query_arg(array(
                'broadcast' => $broadcast['broadcast_key']
            ), home_url('/apex-race-notes/'));
            // Don't expose full key in listing
            $broadcast['broadcast_key_short'] = substr($broadcast['broadcast_key'], 0, 8) . '...';
            unset($broadcast['broadcast_key']);
        }
        
        wp_send_json_success(array('broadcasts' => $broadcasts));
    }
    
    /**
     * Get live telemetry for spectators (polling endpoint)
     */
    public function get_live_telemetry() {
        $broadcast_key = isset($_POST['broadcast_key']) ? sanitize_text_field($_POST['broadcast_key']) : '';
        
        if (empty($broadcast_key)) {
            // Also check GET for direct access
            $broadcast_key = isset($_GET['broadcast_key']) ? sanitize_text_field($_GET['broadcast_key']) : '';
        }
        
        if (empty($broadcast_key)) {
            wp_send_json_error(array('message' => 'Broadcast key required.'));
            return;
        }
        
        $telemetry = Apex_Notes_DB::get_live_telemetry($broadcast_key);
        
        if (!$telemetry) {
            wp_send_json_error(array('message' => 'Broadcast not found or ended.'));
            return;
        }
        
        // Check if broadcast is still active
        if (!$telemetry['is_active']) {
            wp_send_json_error(array('message' => 'Broadcast has ended.', 'ended' => true));
            return;
        }
        
        // Hide stream URL if not premium broadcast
        if (!$telemetry['is_premium']) {
            $telemetry['stream_url'] = '';
        }
        
        wp_send_json_success(array('telemetry' => $telemetry));
    }
    
    /**
     * Push telemetry from SimHub plugin (API endpoint)
     * This doesn't use nonce - authenticates via broadcast_key
     */
    public function push_telemetry() {
        // Get broadcast key from request
        $broadcast_key = isset($_POST['broadcast_key']) ? sanitize_text_field($_POST['broadcast_key']) : '';
        
        if (empty($broadcast_key)) {
            wp_send_json_error(array('message' => 'Broadcast key required.'));
            return;
        }
        
        // Parse telemetry data
        $telemetry_json = isset($_POST['telemetry']) ? $_POST['telemetry'] : '';
        
        if (empty($telemetry_json)) {
            wp_send_json_error(array('message' => 'Telemetry data required.'));
            return;
        }
        
        $telemetry_data = json_decode(stripslashes($telemetry_json), true);
        
        if (!$telemetry_data) {
            wp_send_json_error(array('message' => 'Invalid telemetry JSON.'));
            return;
        }
        
        // Update telemetry
        $result = Apex_Notes_DB::update_live_telemetry($broadcast_key, $telemetry_data);
        
        if (!$result) {
            wp_send_json_error(array('message' => 'Failed to update telemetry. Broadcast may have ended.'));
            return;
        }
        
        wp_send_json_success(array('message' => 'OK'));
    }
    
    /**
     * Ban a user (admin only)
     */
    public function ban_user() {
        $this->verify_nonce();
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'You do not have permission to ban users.'));
            return;
        }
        
        $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
        $reason = isset($_POST['reason']) ? sanitize_text_field($_POST['reason']) : '';
        
        if (!$user_id) {
            wp_send_json_error(array('message' => 'Invalid user ID.'));
            return;
        }
        
        // Don't allow banning admins
        $user = get_user_by('ID', $user_id);
        if (!$user) {
            wp_send_json_error(array('message' => 'User not found.'));
            return;
        }
        
        if (user_can($user_id, 'manage_options')) {
            wp_send_json_error(array('message' => 'Cannot ban administrators.'));
            return;
        }
        
        Apex_Notes_DB::ban_user($user_id, $reason);
        
        wp_send_json_success(array(
            'message' => 'User has been banned.',
            'user' => array(
                'id' => $user_id,
                'display_name' => $user->display_name
            )
        ));
    }
    
    /**
     * Unban a user (admin only)
     */
    public function unban_user() {
        $this->verify_nonce();
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'You do not have permission to unban users.'));
            return;
        }
        
        $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
        
        if (!$user_id) {
            wp_send_json_error(array('message' => 'Invalid user ID.'));
            return;
        }
        
        Apex_Notes_DB::unban_user($user_id);
        
        wp_send_json_success(array('message' => 'User has been unbanned.'));
    }
    
    /**
     * Get banned users list (admin only)
     */
    public function get_banned_users() {
        $this->verify_nonce();
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'You do not have permission to view banned users.'));
            return;
        }
        
        $banned = Apex_Notes_DB::get_banned_users();
        
        wp_send_json_success(array(
            'users' => $banned,
            'count' => count($banned)
        ));
    }
    
    /**
     * Upload livery files
     */
    public function upload_livery() {
        // Wrap everything in try-catch to catch any errors
        try {
            // Log that we reached the function (for debugging)
            error_log('Apex Notes: upload_livery called');
            
            // Check for server-level upload errors first
            if (empty($_FILES)) {
                // Files may have been rejected at server level
                $content_length = isset($_SERVER['CONTENT_LENGTH']) ? intval($_SERVER['CONTENT_LENGTH']) : 0;
                $max_post = $this->return_bytes(ini_get('post_max_size'));
                $max_upload = $this->return_bytes(ini_get('upload_max_filesize'));
                
                wp_send_json_error(array(
                    'message' => 'No files received. Server may have rejected the upload. ' .
                                'Request size: ' . round($content_length / 1048576, 1) . 'MB, ' .
                                'Server max post: ' . round($max_post / 1048576, 1) . 'MB, ' .
                                'Server max upload: ' . round($max_upload / 1048576, 1) . 'MB'
                ));
                return;
            }
            
            // Check nonce first
            if (!check_ajax_referer('apex_notes_nonce', 'nonce', false)) {
                wp_send_json_error(array('message' => 'Security check failed. Please refresh the page and try again.'));
                return;
            }
            
            if (!is_user_logged_in()) {
                wp_send_json_error(array('message' => 'You must be logged in to upload a livery.'));
                return;
            }
            
            // Check legal agreement
            $legal_agreed = isset($_POST['legal_agreed']) && $_POST['legal_agreed'] === '1';
            if (!$legal_agreed) {
                wp_send_json_error(array('message' => 'You must agree to the terms and conditions to upload.'));
                return;
            }
            
            $user_id = get_current_user_id();
        
        // Get user's IP address
        $ip_address = '';
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip_address = sanitize_text_field($_SERVER['HTTP_CLIENT_IP']);
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip_address = sanitize_text_field($_SERVER['HTTP_X_FORWARDED_FOR']);
        } else {
            $ip_address = sanitize_text_field($_SERVER['REMOTE_ADDR'] ?? '');
        }
        
        // Get user's Discord ID if available
        $discord_id = get_user_meta($user_id, 'apex_discord_id', true);
        if (empty($discord_id)) {
            $discord_id = get_user_meta($user_id, 'discord_id', true);
        }
        
        // Validate files
        if (!isset($_FILES['file_1']) || !isset($_FILES['file_2'])) {
            wp_send_json_error(array('message' => 'Please upload both required livery files.'));
            return;
        }
        
        $file_1 = $_FILES['file_1'];
        $file_2 = $_FILES['file_2'];
        
        // Check for upload errors FIRST
        if ($file_1['error'] !== UPLOAD_ERR_OK || $file_2['error'] !== UPLOAD_ERR_OK) {
            $error_messages = array(
                UPLOAD_ERR_INI_SIZE => 'File exceeds server upload limit (upload_max_filesize). Contact admin.',
                UPLOAD_ERR_FORM_SIZE => 'File exceeds form limit.',
                UPLOAD_ERR_PARTIAL => 'File was only partially uploaded. Try again.',
                UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
                UPLOAD_ERR_NO_TMP_DIR => 'Server missing temp folder.',
                UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
                UPLOAD_ERR_EXTENSION => 'Upload blocked by server extension.'
            );
            $error_code = $file_1['error'] !== UPLOAD_ERR_OK ? $file_1['error'] : $file_2['error'];
            $error_msg = isset($error_messages[$error_code]) ? $error_messages[$error_code] : 'Unknown upload error (code: ' . $error_code . ')';
            wp_send_json_error(array('message' => $error_msg));
            return;
        }
        
        // STRICT validation - only accept exact file names
        $required_files = array('customskin.tga', 'customskin_region.tga');
        $uploaded_names = array(strtolower($file_1['name']), strtolower($file_2['name']));
        
        // Check that both required files are present (in any order)
        sort($required_files);
        sort($uploaded_names);
        
        if ($uploaded_names !== $required_files) {
            wp_send_json_error(array(
                'message' => 'Invalid files. You must upload exactly: customskin.tga and customskin_region.tga'
            ));
            return;
        }
        
        // Check file sizes (max 100MB each to allow for ~64MB files)
        $max_size = 100 * 1024 * 1024; // 100MB
        if ($file_1['size'] > $max_size || $file_2['size'] > $max_size) {
            wp_send_json_error(array('message' => 'File size exceeds maximum allowed (100MB per file).'));
            return;
        }
        
        // Get livery name
        $livery_name = isset($_POST['livery_name']) ? sanitize_text_field($_POST['livery_name']) : 'My Livery';
        
        // Create upload directory
        $upload_dir = wp_upload_dir();
        $livery_dir = $upload_dir['basedir'] . '/apex-liveries/' . $user_id;
        
        if (!file_exists($livery_dir)) {
            $created = wp_mkdir_p($livery_dir);
            if (!$created) {
                wp_send_json_error(array('message' => 'Failed to create upload directory. Check server permissions.'));
                return;
            }
        }
        
        // Determine which file is which based on name
        if (strtolower($file_1['name']) === 'customskin.tga') {
            $customskin_file = $file_1;
            $region_file = $file_2;
        } else {
            $customskin_file = $file_2;
            $region_file = $file_1;
        }
        
        // Move files with exact names
        $file_1_path = $livery_dir . '/customskin.tga';
        $file_2_path = $livery_dir . '/customskin_region.tga';
        
        if (!move_uploaded_file($customskin_file['tmp_name'], $file_1_path)) {
            wp_send_json_error(array('message' => 'Failed to upload customskin.tga. Check directory permissions.'));
            return;
        }
        
        if (!move_uploaded_file($region_file['tmp_name'], $file_2_path)) {
            @unlink($file_1_path); // Clean up first file
            wp_send_json_error(array('message' => 'Failed to upload customskin_region.tga'));
            return;
        }
        
        // Verify files exist
        if (!file_exists($file_1_path) || !file_exists($file_2_path)) {
            wp_send_json_error(array('message' => 'Files uploaded but not found. Server issue.'));
            return;
        }
        
        // Create livery record with IP and Discord tracking
        $result = Apex_Notes_DB::create_livery($user_id, array(
            'livery_name' => $livery_name,
            'car_class' => '',
            'car_id' => '',
            'file_1_name' => 'customskin.tga',
            'file_1_path' => $file_1_path,
            'file_2_name' => 'customskin_region.tga',
            'file_2_path' => $file_2_path,
            'uploader_ip' => $ip_address,
            'uploader_discord_id' => $discord_id,
            'legal_agreed' => 1
        ));
        
        if (!$result) {
            @unlink($file_1_path);
            @unlink($file_2_path);
            wp_send_json_error(array('message' => 'Failed to create livery record.'));
            return;
        }
        
        // Generate share URL
        $share_url = home_url('/livery/' . $result['share_token']);
        
        wp_send_json_success(array(
            'message' => 'Livery uploaded successfully! Files will be automatically deleted after 48 hours.',
            'livery_id' => $result['id'],
            'share_token' => $result['share_token'],
            'share_url' => $share_url,
            'expires_at' => $result['expires_at'],
            'expires_in' => '48 hours'
        ));
        
        } catch (Exception $e) {
            wp_send_json_error(array('message' => 'Server error: ' . $e->getMessage()));
        } catch (Error $e) {
            wp_send_json_error(array('message' => 'PHP error: ' . $e->getMessage()));
        }
    }
    
    /**
     * Get user's livery
     */
    public function get_user_livery() {
        $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
        
        if (!$user_id) {
            wp_send_json_error(array('message' => 'Invalid user ID.'));
            return;
        }
        
        $livery = Apex_Notes_DB::get_user_livery($user_id);
        
        if (!$livery) {
            wp_send_json_success(array('livery' => null));
            return;
        }
        
        // Calculate time remaining
        $expires_at = strtotime($livery['expires_at']);
        $now = time();
        $remaining = $expires_at - $now;
        
        $hours = floor($remaining / 3600);
        $minutes = floor(($remaining % 3600) / 60);
        $time_remaining = $hours . 'h ' . $minutes . 'm';
        
        $share_url = home_url('/livery/' . $livery['share_token']);
        
        wp_send_json_success(array(
            'livery' => array(
                'id' => $livery['id'],
                'livery_name' => $livery['livery_name'],
                'car_class' => $livery['car_class'],
                'car_id' => $livery['car_id'],
                'file_1_name' => $livery['file_1_name'],
                'file_2_name' => $livery['file_2_name'],
                'share_token' => $livery['share_token'],
                'share_url' => $share_url,
                'download_count' => $livery['download_count'],
                'expires_at' => $livery['expires_at'],
                'time_remaining' => $time_remaining,
                'created_at' => $livery['created_at']
            )
        ));
    }
    
    /**
     * Delete user's livery
     */
    public function delete_livery() {
        $this->verify_nonce();
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => 'You must be logged in.'));
            return;
        }
        
        $user_id = get_current_user_id();
        
        $result = Apex_Notes_DB::delete_user_livery($user_id);
        
        wp_send_json_success(array('message' => 'Livery deleted successfully.'));
    }
    
    /**
     * Get livery download info
     */
    public function get_livery_download() {
        $token = isset($_POST['token']) ? sanitize_text_field($_POST['token']) : '';
        
        if (empty($token)) {
            wp_send_json_error(array('message' => 'Invalid share token.'));
            return;
        }
        
        $livery = Apex_Notes_DB::get_livery_by_token($token);
        
        if (!$livery) {
            wp_send_json_error(array('message' => 'Livery not found or has expired.'));
            return;
        }
        
        // Increment download count
        Apex_Notes_DB::increment_livery_downloads($livery['id']);
        
        // Generate download URLs through AJAX (bypasses nginx 403)
        $file_1_url = admin_url('admin-ajax.php') . '?action=apex_notes_download_livery_file&token=' . $token . '&file=1';
        $file_2_url = admin_url('admin-ajax.php') . '?action=apex_notes_download_livery_file&token=' . $token . '&file=2';
        
        wp_send_json_success(array(
            'livery' => array(
                'livery_name' => $livery['livery_name'],
                'author_name' => $livery['author_name'],
                'car_class' => $livery['car_class'],
                'car_id' => $livery['car_id'],
                'files' => array(
                    array(
                        'name' => $livery['file_1_name'],
                        'url' => $file_1_url
                    ),
                    array(
                        'name' => $livery['file_2_name'],
                        'url' => $file_2_url
                    )
                ),
                'download_count' => $livery['download_count'] + 1
            )
        ));
    }
    
    /**
     * Download livery file (serves file through PHP to bypass nginx restrictions)
     */
    public function download_livery_file() {
        $token = isset($_GET['token']) ? sanitize_text_field($_GET['token']) : '';
        $file_num = isset($_GET['file']) ? intval($_GET['file']) : 0;
        
        if (empty($token) || !in_array($file_num, array(1, 2))) {
            wp_die('Invalid request');
        }
        
        $livery = Apex_Notes_DB::get_livery_by_token($token);
        
        if (!$livery) {
            wp_die('Livery not found or expired');
        }
        
        // Determine which file
        $file_path = ($file_num === 1) ? $livery['file_1_path'] : $livery['file_2_path'];
        $file_name = ($file_num === 1) ? $livery['file_1_name'] : $livery['file_2_name'];
        
        if (!file_exists($file_path)) {
            wp_die('File not found');
        }
        
        // Serve the file
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $file_name . '"');
        header('Content-Length: ' . filesize($file_path));
        header('Cache-Control: no-cache, must-revalidate');
        header('Pragma: no-cache');
        
        readfile($file_path);
        exit;
    }
    
    /**
     * Convert PHP size string to bytes
     */
    private function return_bytes($val) {
        $val = trim($val);
        $last = strtolower($val[strlen($val)-1]);
        $val = intval($val);
        switch($last) {
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }
        return $val;
    }
    
    /**
     * Parse XML for Stewards Room data
     */
    public function parse_stewards_xml() {
        if (!isset($_FILES['xml_file'])) {
            wp_send_json_error(array('message' => 'No file uploaded.'));
            return;
        }
        
        $file = $_FILES['xml_file'];
        
        // Validate file type
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if ($ext !== 'xml') {
            wp_send_json_error(array('message' => 'Please upload an XML file.'));
            return;
        }
        
        // Read file content
        $xml_content = file_get_contents($file['tmp_name']);
        if (empty($xml_content)) {
            wp_send_json_error(array('message' => 'Failed to read file.'));
            return;
        }
        
        // Parse XML
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($xml_content);
        if ($xml === false) {
            wp_send_json_error(array('message' => 'Invalid XML file.'));
            return;
        }
        
        // Find the results node (RaceResults or QualifyResults)
        $results_node = $xml->RaceResults ?? $xml->QualifyResults ?? null;
        
        // Extract race info from results node or root
        $track = 'Unknown Track';
        $event = '';
        $time_string = '';
        $session_type = 'Practice';
        
        if ($results_node) {
            $session_type = isset($xml->RaceResults) ? 'Race' : 'Qualifying';
            $track = isset($results_node->TrackVenue) ? (string) $results_node->TrackVenue : 
                    (isset($results_node->TrackCourse) ? (string) $results_node->TrackCourse : 'Unknown Track');
            $event = isset($results_node->TrackEvent) ? (string) $results_node->TrackEvent : '';
            $time_string = isset($results_node->TimeString) ? (string) $results_node->TimeString : '';
        }
        
        // Fallback to root level if not found
        if ($track === 'Unknown Track') {
            $track = isset($xml->TrackVenue) ? (string) $xml->TrackVenue : 
                    (isset($xml->TrackCourse) ? (string) $xml->TrackCourse : 'Unknown Track');
        }
        if (empty($event)) {
            $event = isset($xml->TrackEvent) ? (string) $xml->TrackEvent : '';
        }
        if (empty($time_string)) {
            $time_string = isset($xml->TimeString) ? (string) $xml->TimeString : '';
        }
        
        $race_info = array(
            'track' => $track,
            'event' => $event,
            'session_type' => $session_type,
            'date' => '',
            'time_string' => $time_string
        );
        
        // Try to extract date from TimeString
        if (!empty($race_info['time_string'])) {
            $race_info['date'] = date('Y-m-d H:i', strtotime($race_info['time_string']));
        }
        
        // Parse incidents
        $incidents = array();
        foreach ($xml->xpath('//Incident') as $incident) {
            $et = (float) $incident['et'];
            $text = (string) $incident;
            
            // Parse driver name and contact info
            $driver = '';
            $car_number = '';
            $contact_with = '';
            $impact = '';
            
            // Match pattern: "Driver Name(Number) reported contact (impact) with another vehicle Other Driver(Number)" or "with Immovable"
            if (preg_match('/^([^(]+)\((\d+)\)\s+reported contact\s+\(([0-9.]+)\)\s+with\s+(.+)$/i', $text, $matches)) {
                $driver = trim($matches[1]);
                $car_number = $matches[2];
                $impact = number_format((float) $matches[3], 2);
                $contact_raw = trim($matches[4]);
                
                // Check if contact with another vehicle
                if (preg_match('/another vehicle\s+([^(]+)\((\d+)\)/i', $contact_raw, $vehicle_match)) {
                    $contact_with = trim($vehicle_match[1]) . ' (#' . $vehicle_match[2] . ')';
                } else {
                    $contact_with = $contact_raw; // Immovable, Sign, Post, etc.
                }
            }
            
            $incidents[] = array(
                'time_seconds' => $et,
                'time_formatted' => $this->format_race_time($et),
                'driver' => $driver,
                'car_number' => $car_number,
                'contact_with' => $contact_with,
                'impact' => $impact,
                'raw' => $text
            );
        }
        
        // Sort incidents by time
        usort($incidents, function($a, $b) {
            return $a['time_seconds'] <=> $b['time_seconds'];
        });
        
        // Parse penalties
        $penalties = array();
        foreach ($xml->xpath('//Penalty') as $penalty) {
            $et = (float) $penalty['et'];
            $text = (string) $penalty;
            
            // Get data from attributes first (more reliable)
            $driver = (string) $penalty['Driver'];
            $penalty_type = (string) $penalty['Penalty'];
            $duration = '';
            $reason = (string) $penalty['Reason'];
            $status = 'Issued';
            
            // Get duration from Time attribute
            if (isset($penalty['Time'])) {
                $time_val = (int) $penalty['Time'];
                if ($time_val > 0) {
                    $duration = $time_val . 's';
                }
            }
            
            // Check if penalty was served by looking at the text
            if (stripos($text, 'served') !== false) {
                $status = 'Served';
            }
            
            // Fallback: Parse from text if attributes are empty
            if (empty($driver) && preg_match('/^([^\s]+(?:\s+[^\s]+)*?)\s+(received|served|finished)/i', $text, $matches)) {
                $driver = trim($matches[1]);
            }
            
            if (empty($penalty_type) && preg_match('/(Stop\/Go|Drive Thru|Time)\s+penalty/i', $text, $type_match)) {
                $penalty_type = $type_match[1];
            }
            
            if (empty($reason) && preg_match('/for\s+([^.]+)\./i', $text, $reason_match)) {
                $reason = trim($reason_match[1]);
            }
            
            $penalties[] = array(
                'time_seconds' => $et,
                'time_formatted' => $this->format_race_time($et),
                'driver' => $driver,
                'penalty_type' => $penalty_type,
                'duration' => $duration,
                'reason' => $reason,
                'status' => $status,
                'raw' => $text
            );
        }
        
        // Sort penalties by time
        usort($penalties, function($a, $b) {
            return $a['time_seconds'] <=> $b['time_seconds'];
        });
        
        // Parse track limits
        $track_limits = array();
        foreach ($xml->xpath('//TrackLimits') as $tl) {
            $et = (float) $tl['et'];
            $driver = (string) $tl['Driver'];
            $car_id = (string) $tl['ID'];
            $lap = (string) $tl['Lap'];
            $warning_points = (string) $tl['WarningPoints'];
            $current_points = (string) $tl['CurrentPoints'];
            $resolution = (string) $tl;
            
            $track_limits[] = array(
                'time_seconds' => $et,
                'time_formatted' => $this->format_race_time($et),
                'driver' => $driver,
                'car_number' => $car_id,
                'lap' => $lap,
                'warning_points' => $warning_points,
                'current_points' => $current_points,
                'resolution' => $resolution
            );
        }
        
        // Sort track limits by time
        usort($track_limits, function($a, $b) {
            return $a['time_seconds'] <=> $b['time_seconds'];
        });
        
        // Parse classification (final positions)
        $classification = array();
        $results_node = $xml->RaceResults ?? $xml->QualifyResults ?? null;
        
        if ($results_node) {
            // Drivers can be either directly under RaceResults or inside a Race sub-element
            $driver_containers = array();
            
            // Check for drivers directly under results node
            $driver_containers[] = $results_node;
            
            // Check for drivers inside Race sub-element (multiplayer/online races)
            if (isset($results_node->Race)) {
                $driver_containers[] = $results_node->Race;
            }
            
            foreach ($driver_containers as $container) {
                foreach ($container->children() as $driver_node) {
                    if ($driver_node->getName() !== 'Driver') continue;
                    
                    $position = (string) $driver_node->Position;
                    
                    // Driver name is in <n> element - use xpath to ensure we get it
                    $driver_name = '';
                    $name_nodes = $driver_node->xpath('n');
                    if (!empty($name_nodes)) {
                        $driver_name = (string) $name_nodes[0];
                    }
                    // Fallback: try Name element
                    if (empty($driver_name) && isset($driver_node->Name)) {
                        $driver_name = (string) $driver_node->Name;
                    }
                    // Fallback: iterate children to find the name element
                    if (empty($driver_name)) {
                        foreach ($driver_node->children() as $child) {
                            $child_name = $child->getName();
                            if ($child_name === 'n' || $child_name === 'Name') {
                                $driver_name = (string) $child;
                                break;
                            }
                        }
                    }
                    
                    $car_type = (string) $driver_node->CarType;
                    $car_class = (string) $driver_node->CarClass;
                    $car_number = (string) $driver_node->CarNumber;
                    $grid_pos = isset($driver_node->GridPos) ? (string) $driver_node->GridPos : '';
                    
                    // Get laps from Laps element or count Lap elements
                    $laps = 0;
                    if (isset($driver_node->Laps)) {
                        $laps = (int) $driver_node->Laps;
                    } else {
                        foreach ($driver_node->children() as $child) {
                            if ($child->getName() === 'Lap') {
                                $laps++;
                            }
                        }
                    }
                    
                    // Get total time from the last lap's et (elapsed time) attribute
                    $total_time = '';
                    $total_time_seconds = 0;
                    $last_lap = null;
                    foreach ($driver_node->children() as $child) {
                        if ($child->getName() === 'Lap') {
                            $last_lap = $child;
                        }
                    }
                    if ($last_lap !== null && isset($last_lap['et'])) {
                        $total_time_seconds = floatval($last_lap['et']);
                        // Format as H:MM:SS.mmm
                        $hours = floor($total_time_seconds / 3600);
                        $minutes = floor(($total_time_seconds % 3600) / 60);
                        $seconds = fmod($total_time_seconds, 60);
                        if ($hours > 0) {
                            $total_time = sprintf('%d:%02d:%06.3f', $hours, $minutes, $seconds);
                        } else {
                            $total_time = sprintf('%d:%06.3f', $minutes, $seconds);
                        }
                    }
                    
                    // Check finish status
                    $status = 'Finished';
                    
                    // Get best lap time
                    $best_lap_time = null;
                    $best_lap_formatted = '';
                    if (isset($driver_node->BestLapTime)) {
                        $best_lap_time = floatval((string) $driver_node->BestLapTime);
                        if ($best_lap_time > 0) {
                            // Format as M:SS.mmm
                            $minutes = floor($best_lap_time / 60);
                            $seconds = fmod($best_lap_time, 60);
                            $best_lap_formatted = sprintf('%d:%06.3f', $minutes, $seconds);
                        }
                    }
                    
                    // If 0 laps, mark as DNS (Did Not Start)
                    if ($laps === 0) {
                        $status = 'DNS';
                    } elseif (isset($driver_node->FinishStatus)) {
                        $finish_status = (string) $driver_node->FinishStatus;
                        if ($finish_status && $finish_status !== 'None') {
                            $status = $finish_status;
                        }
                    }
                    // Override with DNFReason if present and has value
                    if ($laps > 0 && isset($driver_node->DNFReason) && !empty((string) $driver_node->DNFReason)) {
                        $dnf_reason = (string) $driver_node->DNFReason;
                        if ($dnf_reason !== 'DNF') {
                            $status = $dnf_reason;
                        } else {
                            $status = 'DNF';
                        }
                    }
                    
                    // Only add if we have a valid position
                    if (!empty($position)) {
                        $classification[] = array(
                            'position' => $position,
                            'driver' => $driver_name,
                            'car_number' => $car_number,
                            'car_type' => $car_type,
                            'car_class' => $car_class,
                            'laps' => $laps,
                            'grid_pos' => $grid_pos,
                            'status' => $status,
                            'total_time' => $total_time,
                            'total_time_seconds' => $total_time_seconds,
                            'best_lap_time' => $best_lap_time,
                            'best_lap_formatted' => $best_lap_formatted
                        );
                    }
                }
            }
            
            // Sort order: 1) Finished (by laps desc, time asc), 2) DNF (by laps desc), 3) DNS (by grid pos)
            usort($classification, function($a, $b) {
                $a_dns = ($a['laps'] === 0);
                $b_dns = ($b['laps'] === 0);
                $a_dnf = ($a['laps'] > 0 && $a['status'] !== 'Finished');
                $b_dnf = ($b['laps'] > 0 && $b['status'] !== 'Finished');
                $a_finished = ($a['laps'] > 0 && $a['status'] === 'Finished');
                $b_finished = ($b['laps'] > 0 && $b['status'] === 'Finished');
                
                // DNS always at the very bottom
                if ($a_dns && !$b_dns) return 1;
                if ($b_dns && !$a_dns) return -1;
                
                // Both DNS - sort by grid position
                if ($a_dns && $b_dns) {
                    return intval($a['grid_pos']) <=> intval($b['grid_pos']);
                }
                
                // DNF below finished drivers
                if ($a_finished && $b_dnf) return -1;
                if ($b_finished && $a_dnf) return 1;
                
                // Both finished - sort by laps desc, then time asc
                if ($a_finished && $b_finished) {
                    if ($a['laps'] !== $b['laps']) {
                        return $b['laps'] <=> $a['laps'];
                    }
                    return $a['total_time_seconds'] <=> $b['total_time_seconds'];
                }
                
                // Both DNF - sort by laps desc (more laps = higher DNF position)
                if ($a_dnf && $b_dnf) {
                    return $b['laps'] <=> $a['laps'];
                }
                
                // Fallback - sort by laps
                return $b['laps'] <=> $a['laps'];
            });
            
            // Re-assign positions sequentially
            $pos = 1;
            foreach ($classification as &$entry) {
                $entry['position'] = $pos++;
            }
            unset($entry);
        }
        
        // Find overall fastest lap
        $fastest_lap = null;
        $fastest_lap_driver = null;
        $fastest_lap_car_number = null;
        $fastest_lap_formatted = '';
        foreach ($classification as $entry) {
            if ($entry['best_lap_time'] !== null && $entry['best_lap_time'] > 0) {
                if ($fastest_lap === null || $entry['best_lap_time'] < $fastest_lap) {
                    $fastest_lap = $entry['best_lap_time'];
                    $fastest_lap_driver = $entry['driver'];
                    $fastest_lap_car_number = $entry['car_number'];
                    $fastest_lap_formatted = $entry['best_lap_formatted'];
                }
            }
        }
        
        // Build response
        $response = array(
            'race_info' => $race_info,
            'incidents' => $incidents,
            'penalties' => $penalties,
            'track_limits' => $track_limits,
            'classification' => $classification,
            'fastest_lap' => array(
                'time' => $fastest_lap,
                'time_formatted' => $fastest_lap_formatted,
                'driver' => $fastest_lap_driver,
                'car_number' => $fastest_lap_car_number
            ),
            'stats' => array(
                'total_incidents' => count($incidents),
                'total_penalties' => count(array_filter($penalties, function($p) { return $p['status'] === 'Issued'; })),
                'total_track_limits' => count($track_limits),
                'total_drivers' => count($classification)
            )
        );
        
        wp_send_json_success($response);
    }
    
    /**
     * Format race time from seconds to HH:MM:SS
     */
    private function format_race_time($seconds) {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = floor($seconds % 60);
        
        if ($hours > 0) {
            return sprintf('%d:%02d:%02d', $hours, $minutes, $secs);
        } else {
            return sprintf('%d:%02d', $minutes, $secs);
        }
    }
    
    /**
     * Generate PDF for Stewards Report
     */
    public function generate_stewards_pdf() {
        // Get the data from POST - use wp_unslash for proper handling
        $raw_data = isset($_POST['report_data']) ? wp_unslash($_POST['report_data']) : '';
        $report_data = json_decode($raw_data, true);
        
        if (!$report_data || !is_array($report_data)) {
            wp_send_json_error(array('message' => 'No report data provided.'));
            return;
        }
        
        // Generate HTML for PDF
        $html = $this->generate_stewards_pdf_html($report_data);
        
        // Return HTML for client-side PDF generation
        wp_send_json_success(array(
            'html' => $html,
            'filename' => 'Race_Report_' . sanitize_file_name($report_data['race_info']['track'] ?? 'Unknown') . '_' . date('Y-m-d') . '.pdf'
        ));
    }
    
    /**
     * Generate HTML for PDF conversion
     */
    private function generate_stewards_pdf_html($data) {
        $race_info = $data['race_info'] ?? array();
        $incidents = $data['incidents'] ?? array();
        $penalties = $data['penalties'] ?? array();
        $track_limits = $data['track_limits'] ?? array();
        $classification = $data['classification'] ?? array();
        $custom = $data['custom'] ?? array();
        
        $logo_url = APEX_NOTES_PLUGIN_URL . 'assets/images/logo.png';
        
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, Helvetica, sans-serif; font-size: 11px; color: #333; padding: 20px; background: white; }
        .header { margin-bottom: 20px; padding-bottom: 15px; border-bottom: 3px solid #ff6a00; overflow: hidden; }
        .logo { height: 50px; float: left; margin-right: 15px; }
        .header-text { overflow: hidden; }
        .header-text h1 { font-size: 22px; color: #1a1a1a; margin-bottom: 5px; }
        .header-text .meta { font-size: 12px; color: #666; }
        .header-text .custom { font-size: 11px; color: #888; margin-top: 3px; }
        .section { margin-bottom: 20px; clear: both; }
        .section-title { font-size: 14px; font-weight: bold; color: #ff6a00; margin-bottom: 10px; padding-bottom: 5px; border-bottom: 1px solid #ddd; }
        table { width: 100%; border-collapse: collapse; font-size: 10px; }
        th { background-color: #1a1a1a; color: white; padding: 8px 6px; text-align: left; font-weight: 600; }
        td { padding: 6px; border-bottom: 1px solid #eee; }
        tr.even { background-color: #f9f9f9; }
        .impact-high { color: #dc3545; font-weight: bold; }
        .impact-medium { color: #fd7e14; }
        .impact-low { color: #28a745; }
        .status-issued { color: #dc3545; }
        .status-served { color: #28a745; }
        .no-data { color: #999; font-style: italic; padding: 10px; }
        .stats-row { margin-bottom: 15px; overflow: hidden; }
        .stat-box { float: left; width: 23%; margin-right: 2%; background-color: #f5f5f5; padding: 10px 15px; border-radius: 5px; text-align: center; }
        .stat-box:last-child { margin-right: 0; }
        .stat-value { font-size: 20px; font-weight: bold; color: #ff6a00; }
        .stat-label { font-size: 9px; color: #666; text-transform: uppercase; }
        .footer { margin-top: 30px; padding-top: 15px; border-top: 1px solid #ddd; font-size: 9px; color: #999; text-align: center; clear: both; }
        .clearfix { clear: both; }
    </style>
</head>
<body>';
        
        // Header
        $html .= '<div class="header">';
        $html .= '<img src="' . esc_url($logo_url) . '" class="logo" alt="Apex Race Notes">';
        $html .= '<div class="header-text">';
        $html .= '<h1>Official Race Report</h1>';
        $html .= '<div class="meta">';
        $html .= esc_html($race_info['track'] ?? 'Unknown Track');
        if (!empty($race_info['event'])) {
            $html .= ' • ' . esc_html($race_info['event']);
        }
        if (!empty($race_info['date'])) {
            $html .= ' • ' . esc_html($race_info['date']);
        }
        $html .= '</div>';
        
        if (!empty($custom['race_name']) || !empty($custom['league_name']) || !empty($custom['round'])) {
            $html .= '<div class="custom">';
            $custom_parts = array();
            if (!empty($custom['race_name'])) $custom_parts[] = esc_html($custom['race_name']);
            if (!empty($custom['league_name'])) $custom_parts[] = esc_html($custom['league_name']);
            if (!empty($custom['round'])) $custom_parts[] = esc_html($custom['round']);
            $html .= implode(' • ', $custom_parts);
            $html .= '</div>';
        }
        
        $html .= '</div></div>';
        
        // Stats Summary
        $html .= '<div class="stats-row">';
        $html .= '<div class="stat-box"><div class="stat-value">' . count($incidents) . '</div><div class="stat-label">Incidents</div></div>';
        $html .= '<div class="stat-box"><div class="stat-value">' . count(array_filter($penalties, function($p) { return ($p['status'] ?? '') === 'Issued'; })) . '</div><div class="stat-label">Penalties</div></div>';
        $html .= '<div class="stat-box"><div class="stat-value">' . count($track_limits) . '</div><div class="stat-label">Track Limits</div></div>';
        $html .= '<div class="stat-box"><div class="stat-value">' . count($classification) . '</div><div class="stat-label">Drivers</div></div>';
        $html .= '</div><div class="clearfix"></div>';
        
        // Incidents Section (limit to first 50 for PDF)
        $incidents_limited = array_slice($incidents, 0, 50);
        $html .= '<div class="section">';
        $html .= '<div class="section-title">Race Incidents' . (count($incidents) > 50 ? ' (First 50 of ' . count($incidents) . ')' : '') . '</div>';
        if (!empty($incidents_limited)) {
            $html .= '<table><thead><tr><th>Time</th><th>Driver</th><th>Contact With</th><th>Impact</th></tr></thead><tbody>';
            $row_count = 0;
            foreach ($incidents_limited as $inc) {
                $impact_class = '';
                $impact_val = floatval($inc['impact'] ?? 0);
                if ($impact_val > 1000) $impact_class = 'impact-high';
                elseif ($impact_val > 200) $impact_class = 'impact-medium';
                else $impact_class = 'impact-low';
                
                $row_class = ($row_count % 2 == 1) ? ' class="even"' : '';
                $html .= '<tr' . $row_class . '>';
                $html .= '<td>' . esc_html($inc['time_formatted'] ?? '') . '</td>';
                $html .= '<td>' . esc_html($inc['driver'] ?? '') . ' (#' . esc_html($inc['car_number'] ?? '') . ')</td>';
                $html .= '<td>' . esc_html($inc['contact_with'] ?? '') . '</td>';
                $html .= '<td class="' . $impact_class . '">' . esc_html($inc['impact'] ?? '') . '</td>';
                $html .= '</tr>';
                $row_count++;
            }
            $html .= '</tbody></table>';
        } else {
            $html .= '<p class="no-data">No incidents recorded.</p>';
        }
        $html .= '</div>';
        
        // Penalties Section
        $html .= '<div class="section">';
        $html .= '<div class="section-title">Penalties Issued</div>';
        if (!empty($penalties)) {
            $html .= '<table><thead><tr><th>Time</th><th>Driver</th><th>Penalty</th><th>Reason</th><th>Status</th></tr></thead><tbody>';
            $row_count = 0;
            foreach ($penalties as $pen) {
                $status_class = ($pen['status'] ?? '') === 'Issued' ? 'status-issued' : 'status-served';
                $row_class = ($row_count % 2 == 1) ? ' class="even"' : '';
                $html .= '<tr' . $row_class . '>';
                $html .= '<td>' . esc_html($pen['time_formatted'] ?? '') . '</td>';
                $html .= '<td>' . esc_html($pen['driver'] ?? '') . '</td>';
                $html .= '<td>' . esc_html(($pen['penalty_type'] ?? '') . (isset($pen['duration']) && $pen['duration'] ? ' ' . $pen['duration'] : '')) . '</td>';
                $html .= '<td>' . esc_html($pen['reason'] ?? '') . '</td>';
                $html .= '<td class="' . $status_class . '">' . esc_html($pen['status'] ?? '') . '</td>';
                $html .= '</tr>';
                $row_count++;
            }
            $html .= '</tbody></table>';
        } else {
            $html .= '<p class="no-data">No penalties issued.</p>';
        }
        $html .= '</div>';
        
        // Track Limits Section (limit to first 30 for PDF - can be hundreds)
        $track_limits_limited = array_slice($track_limits, 0, 30);
        $html .= '<div class="section">';
        $html .= '<div class="section-title">Track Limit Violations' . (count($track_limits) > 30 ? ' (First 30 of ' . count($track_limits) . ')' : '') . '</div>';
        if (!empty($track_limits_limited)) {
            $html .= '<table><thead><tr><th>Time</th><th>Driver</th><th>Lap</th><th>Points</th><th>Total</th><th>Decision</th></tr></thead><tbody>';
            $row_count = 0;
            foreach ($track_limits_limited as $tl) {
                $row_class = ($row_count % 2 == 1) ? ' class="even"' : '';
                $html .= '<tr' . $row_class . '>';
                $html .= '<td>' . esc_html($tl['time_formatted'] ?? '') . '</td>';
                $html .= '<td>' . esc_html($tl['driver'] ?? '') . ' (#' . esc_html($tl['car_number'] ?? '') . ')</td>';
                $html .= '<td>' . esc_html($tl['lap'] ?? '') . '</td>';
                $html .= '<td>' . esc_html($tl['warning_points'] ?? '0') . '</td>';
                $html .= '<td>' . esc_html($tl['current_points'] ?? '0') . '</td>';
                $html .= '<td>' . esc_html($tl['resolution'] ?? '') . '</td>';
                $html .= '</tr>';
                $row_count++;
            }
            $html .= '</tbody></table>';
        } else {
            $html .= '<p class="no-data">No track limit violations.</p>';
        }
        $html .= '</div>';
        
        // Classification Section
        $html .= '<div class="section">';
        $html .= '<div class="section-title">Final Classification</div>';
        if (!empty($classification)) {
            $html .= '<table><thead><tr><th>Pos</th><th>Driver</th><th>Car</th><th>Class</th><th>Laps</th><th>Time</th><th>Status</th></tr></thead><tbody>';
            foreach ($classification as $cls) {
                $status = $cls['status'] ?? 'Finished';
                $status_class = '';
                if ($status === 'DNS') {
                    $status_class = 'style="color:#6c757d;font-style:italic;"';
                } elseif ($status === 'DNF' || $status === 'Suspension') {
                    $status_class = 'style="color:#dc3545;"';
                }
                
                $total_time = $cls['total_time'] ?? '--';
                if (($cls['laps'] ?? 0) === 0) {
                    $total_time = '--';
                }
                
                $row_style = ($status === 'DNS') ? ' style="opacity:0.6;background:#f0f0f0;"' : '';
                
                $html .= '<tr' . $row_style . '>';
                $html .= '<td>' . esc_html($cls['position'] ?? '') . '</td>';
                $html .= '<td>' . esc_html($cls['driver'] ?? '') . ' (#' . esc_html($cls['car_number'] ?? '') . ')</td>';
                $html .= '<td>' . esc_html($cls['car_type'] ?? '') . '</td>';
                $html .= '<td>' . esc_html($cls['car_class'] ?? '') . '</td>';
                $html .= '<td>' . esc_html($cls['laps'] ?? '') . '</td>';
                $html .= '<td>' . esc_html($total_time) . '</td>';
                $html .= '<td ' . $status_class . '>' . esc_html($status) . '</td>';
                $html .= '</tr>';
            }
            $html .= '</tbody></table>';
        } else {
            $html .= '<p class="no-data">No classification data available.</p>';
        }
        $html .= '</div>';
        
        // Footer
        $html .= '<div class="footer">Generated by Apex Race Notes • apexracenotes.com • ' . date('Y-m-d H:i:s') . '</div>';
        
        $html .= '</body></html>';
        
        return $html;
    }
    
    /**
     * Save a stewards report
     */
    public function save_stewards_report() {
        // Verify user is logged in
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => 'You must be logged in to save reports.'));
        }
        
        $user_id = get_current_user_id();
        global $wpdb;
        $table = $wpdb->prefix . 'apex_notes_stewards_reports';
        
        // Ensure table exists
        $this->ensure_stewards_reports_table();
        
        // Get report data - wp_unslash to remove WordPress magic quotes
        $report_data = isset($_POST['report_data']) ? wp_unslash($_POST['report_data']) : '';
        $report_name = isset($_POST['report_name']) ? sanitize_text_field($_POST['report_name']) : '';
        $track_name = isset($_POST['track_name']) ? sanitize_text_field($_POST['track_name']) : '';
        $event_name = isset($_POST['event_name']) ? sanitize_text_field($_POST['event_name']) : '';
        $race_date = isset($_POST['race_date']) ? sanitize_text_field($_POST['race_date']) : '';
        
        if (empty($report_data) || empty($report_name)) {
            wp_send_json_error(array('message' => 'Missing required data.'));
        }
        
        // Validate that report_data is valid JSON
        $test_decode = json_decode($report_data, true);
        if ($test_decode === null && json_last_error() !== JSON_ERROR_NONE) {
            wp_send_json_error(array('message' => 'Invalid report data format.'));
        }
        
        // Check if a report with the same track and date already exists for this user
        $existing = $wpdb->get_row($wpdb->prepare(
            "SELECT id, share_token FROM $table WHERE user_id = %d AND track_name = %s AND race_date = %s",
            $user_id, $track_name, $race_date
        ));
        
        if ($existing) {
            // Update existing report
            $result = $wpdb->update($table, array(
                'report_name' => $report_name,
                'event_name' => $event_name,
                'report_data' => $report_data,
            ), array('id' => $existing->id), array('%s', '%s', '%s'), array('%d'));
            
            if ($result === false) {
                wp_send_json_error(array('message' => 'Failed to update report. Database error: ' . $wpdb->last_error));
            }
            
            $share_url = home_url('/stewards/view/' . $existing->share_token);
            
            // Get current count
            $count = $wpdb->get_var($wpdb->prepare(
                "SELECT COUNT(*) FROM $table WHERE user_id = %d",
                $user_id
            ));
            
            wp_send_json_success(array(
                'message' => 'Report updated successfully!',
                'report_id' => $existing->id,
                'share_token' => $existing->share_token,
                'share_url' => $share_url,
                'saved_count' => intval($count),
                'updated' => true
            ));
        }
        
        // Check if user already has 3 reports
        $count = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table WHERE user_id = %d",
            $user_id
        ));
        
        if ($count === null) {
            $count = 0;
        }
        
        if ($count >= 3) {
            wp_send_json_error(array('message' => 'You can only save up to 3 reports. Please delete an existing report first.'));
        }
        
        // Generate unique share token
        $share_token = bin2hex(random_bytes(16));
        
        // Insert the report
        $result = $wpdb->insert($table, array(
            'user_id' => $user_id,
            'share_token' => $share_token,
            'report_name' => $report_name,
            'track_name' => $track_name,
            'event_name' => $event_name,
            'race_date' => $race_date,
            'report_data' => $report_data,
            'created_at' => current_time('mysql')
        ), array('%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s'));
        
        if ($result === false) {
            wp_send_json_error(array('message' => 'Failed to save report. Database error: ' . $wpdb->last_error));
        }
        
        $share_url = home_url('/stewards/view/' . $share_token);
        
        wp_send_json_success(array(
            'message' => 'Report saved successfully!',
            'report_id' => $wpdb->insert_id,
            'share_token' => $share_token,
            'share_url' => $share_url,
            'saved_count' => intval($count) + 1
        ));
    }
    
    /**
     * Ensure stewards reports table exists
     */
    private function ensure_stewards_reports_table() {
        global $wpdb;
        $table = $wpdb->prefix . 'apex_notes_stewards_reports';
        
        // Check if table exists
        if ($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) {
            $charset_collate = $wpdb->get_charset_collate();
            
            $sql = "CREATE TABLE $table (
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
            dbDelta($sql);
        }
    }
    
    /**
     * Get user's saved stewards reports
     */
    public function get_my_stewards_reports() {
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => 'You must be logged in.'));
        }
        
        $user_id = get_current_user_id();
        global $wpdb;
        $table = $wpdb->prefix . 'apex_notes_stewards_reports';
        
        // Ensure table exists
        $this->ensure_stewards_reports_table();
        
        $reports = $wpdb->get_results($wpdb->prepare(
            "SELECT id, share_token, report_name, track_name, event_name, race_date, view_count, created_at 
             FROM $table 
             WHERE user_id = %d 
             ORDER BY created_at DESC",
            $user_id
        ));
        
        if ($reports === null) {
            $reports = array();
        }
        
        // Add share URLs
        foreach ($reports as &$report) {
            $report->share_url = home_url('/stewards/view/' . $report->share_token);
        }
        
        wp_send_json_success(array(
            'reports' => $reports,
            'count' => count($reports),
            'max_reports' => 3
        ));
    }
    
    /**
     * Delete a saved stewards report
     */
    public function delete_stewards_report() {
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => 'You must be logged in.'));
        }
        
        $user_id = get_current_user_id();
        $report_id = isset($_POST['report_id']) ? intval($_POST['report_id']) : 0;
        
        if (!$report_id) {
            wp_send_json_error(array('message' => 'Invalid report ID.'));
        }
        
        global $wpdb;
        $table = $wpdb->prefix . 'apex_notes_stewards_reports';
        
        // Make sure the report belongs to this user
        $result = $wpdb->delete($table, array(
            'id' => $report_id,
            'user_id' => $user_id
        ), array('%d', '%d'));
        
        if ($result === false || $result === 0) {
            wp_send_json_error(array('message' => 'Failed to delete report or report not found.'));
        }
        
        wp_send_json_success(array('message' => 'Report deleted successfully.'));
    }
    
    /**
     * Get a shared stewards report (public)
     */
    public function get_shared_report() {
        $share_token = isset($_POST['share_token']) ? sanitize_text_field($_POST['share_token']) : '';
        
        if (empty($share_token)) {
            wp_send_json_error(array('message' => 'Invalid share link.'));
        }
        
        global $wpdb;
        $table = $wpdb->prefix . 'apex_notes_stewards_reports';
        
        // Ensure table exists
        $this->ensure_stewards_reports_table();
        
        $report = $wpdb->get_row($wpdb->prepare(
            "SELECT r.*, u.display_name as author_name 
             FROM $table r 
             LEFT JOIN {$wpdb->users} u ON r.user_id = u.ID 
             WHERE r.share_token = %s",
            $share_token
        ));
        
        if (!$report) {
            wp_send_json_error(array('message' => 'Report not found or has expired.'));
        }
        
        // Increment view count
        $wpdb->query($wpdb->prepare(
            "UPDATE $table SET view_count = view_count + 1 WHERE id = %d",
            $report->id
        ));
        
        // Decode report data - handle potential double encoding
        $report_data = $report->report_data;
        
        // If it's a string, try to decode it
        if (is_string($report_data)) {
            // Remove potential slashes from WordPress
            $report_data = wp_unslash($report_data);
            
            // Try to decode
            $decoded = json_decode($report_data, true);
            
            // If still a string after decode, it might be double-encoded
            if (is_string($decoded)) {
                $decoded = json_decode($decoded, true);
            }
            
            $report_data = $decoded;
        }
        
        if (!$report_data || !is_array($report_data)) {
            wp_send_json_error(array(
                'message' => 'Report data could not be parsed.',
                'debug' => substr($report->report_data, 0, 200)
            ));
        }
        
        wp_send_json_success(array(
            'report_name' => $report->report_name,
            'track_name' => $report->track_name,
            'event_name' => $report->event_name,
            'race_date' => $report->race_date,
            'author_name' => $report->author_name,
            'view_count' => $report->view_count + 1,
            'created_at' => $report->created_at,
            'report_data' => $report_data
        ));
    }
}
