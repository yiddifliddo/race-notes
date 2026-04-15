<?php
/**
 * Apex Notes Authentication
 * Handles Google and Discord OAuth, email verification
 */

if (!defined('ABSPATH')) {
    exit;
}

class Apex_Notes_Auth {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        // Handle OAuth callback as EARLY as possible before any output
        add_action('plugins_loaded', array($this, 'handle_oauth_callback'), 1);
        add_action('wp_ajax_nopriv_apex_notes_oauth_login', array($this, 'ajax_oauth_login'));
        add_action('wp_ajax_apex_notes_resend_verification', array($this, 'resend_verification'));
        add_action('wp_ajax_nopriv_apex_notes_resend_verification', array($this, 'resend_verification'));
        add_action('init', array($this, 'handle_email_verification'));
        add_shortcode('apex_notes_login', array($this, 'login_shortcode'));
    }
    
    /**
     * Get OAuth settings
     */
    public static function get_settings() {
        return array(
            'google_client_id' => get_option('apex_notes_google_client_id', ''),
            'google_client_secret' => get_option('apex_notes_google_client_secret', ''),
            'discord_client_id' => get_option('apex_notes_discord_client_id', ''),
            'discord_client_secret' => get_option('apex_notes_discord_client_secret', ''),
            'require_email_verification' => get_option('apex_notes_require_email_verification', '1'),
        );
    }
    
    /**
     * Check if user is verified
     */
    public static function is_user_verified($user_id = null) {
        if (!$user_id) {
            $user_id = get_current_user_id();
        }
        if (!$user_id) {
            return false;
        }
        
        // Admins don't need verification
        $user = get_user_by('id', $user_id);
        if ($user && user_can($user, 'manage_options')) {
            return true;
        }
        
        $settings = self::get_settings();
        if ($settings['require_email_verification'] !== '1') {
            return true;
        }
        
        return get_user_meta($user_id, 'apex_notes_email_verified', true) === '1';
    }
    
    /**
     * Generate verification token
     */
    private function generate_verification_token($user_id) {
        $token = wp_generate_password(32, false);
        update_user_meta($user_id, 'apex_notes_verification_token', $token);
        update_user_meta($user_id, 'apex_notes_verification_expires', time() + (24 * 60 * 60)); // 24 hours
        return $token;
    }
    
    /**
     * Send verification email
     */
    public function send_verification_email($user_id) {
        $user = get_user_by('id', $user_id);
        if (!$user) {
            return false;
        }
        
        $token = $this->generate_verification_token($user_id);
        $verify_url = add_query_arg(array(
            'apex_verify' => '1',
            'user' => $user_id,
            'token' => $token,
        ), home_url());
        
        $subject = 'Verify your email for Apex Notes';
        $message = sprintf(
            "Hi %s,\n\nPlease click the link below to verify your email address and start posting on Apex Notes:\n\n%s\n\nThis link will expire in 24 hours.\n\nIf you didn't create an account, you can ignore this email.\n\nThanks,\nThe Apex Notes Team",
            $user->display_name,
            $verify_url
        );
        
        $headers = array('Content-Type: text/plain; charset=UTF-8');
        
        return wp_mail($user->user_email, $subject, $message, $headers);
    }
    
    /**
     * Handle email verification link
     */
    public function handle_email_verification() {
        if (!isset($_GET['apex_verify']) || $_GET['apex_verify'] !== '1') {
            return;
        }
        
        $user_id = isset($_GET['user']) ? intval($_GET['user']) : 0;
        $token = isset($_GET['token']) ? sanitize_text_field($_GET['token']) : '';
        
        if (!$user_id || !$token) {
            wp_die('Invalid verification link.');
        }
        
        $stored_token = get_user_meta($user_id, 'apex_notes_verification_token', true);
        $expires = get_user_meta($user_id, 'apex_notes_verification_expires', true);
        
        if ($stored_token !== $token) {
            wp_die('Invalid verification token.');
        }
        
        if (time() > $expires) {
            wp_die('Verification link has expired. Please request a new one.');
        }
        
        // Mark as verified
        update_user_meta($user_id, 'apex_notes_email_verified', '1');
        delete_user_meta($user_id, 'apex_notes_verification_token');
        delete_user_meta($user_id, 'apex_notes_verification_expires');
        
        // Redirect to success page
        wp_redirect(add_query_arg('verified', '1', home_url()));
        exit;
    }
    
    /**
     * Resend verification email
     */
    public function resend_verification() {
        check_ajax_referer('apex_notes_nonce', 'nonce');
        
        if (!is_user_logged_in()) {
            wp_send_json_error(array('message' => 'Please log in first.'));
        }
        
        $user_id = get_current_user_id();
        
        if (self::is_user_verified($user_id)) {
            wp_send_json_error(array('message' => 'Your email is already verified.'));
        }
        
        if ($this->send_verification_email($user_id)) {
            wp_send_json_success(array('message' => 'Verification email sent! Check your inbox.'));
        } else {
            wp_send_json_error(array('message' => 'Failed to send email. Please try again.'));
        }
    }
    
    /**
     * Get Google OAuth URL
     */
    public function get_google_auth_url() {
        $settings = self::get_settings();
        if (empty($settings['google_client_id'])) {
            return '';
        }
        
        $redirect_uri = home_url('?apex_oauth=google');
        $state = wp_create_nonce('apex_google_oauth');
        
        $params = array(
            'client_id' => $settings['google_client_id'],
            'redirect_uri' => $redirect_uri,
            'response_type' => 'code',
            'scope' => 'email profile',
            'state' => $state,
            'access_type' => 'online',
        );
        
        return 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params);
    }
    
    /**
     * Get Discord OAuth URL
     */
    public function get_discord_auth_url() {
        $settings = self::get_settings();
        if (empty($settings['discord_client_id'])) {
            return '';
        }
        
        // Use standalone OAuth file to bypass server compression
        $redirect_uri = home_url('/discord-oauth.php');
        $state = wp_create_nonce('apex_discord_oauth');
        
        $params = array(
            'client_id' => $settings['discord_client_id'],
            'redirect_uri' => $redirect_uri,
            'response_type' => 'code',
            'scope' => 'identify email',
            'state' => $state,
        );
        
        return 'https://discord.com/api/oauth2/authorize?' . http_build_query($params);
    }
    
    /**
     * Handle OAuth callback
     */
    public function handle_oauth_callback() {
        if (!isset($_GET['apex_oauth'])) {
            return;
        }
        
        $provider = sanitize_text_field($_GET['apex_oauth']);
        $code = isset($_GET['code']) ? sanitize_text_field($_GET['code']) : '';
        $state = isset($_GET['state']) ? sanitize_text_field($_GET['state']) : '';
        
        if (empty($code)) {
            $this->raw_redirect(home_url('?login_error=no_code'));
            return;
        }
        
        if ($provider === 'google') {
            $this->handle_google_callback($code, $state);
        } elseif ($provider === 'discord') {
            $this->handle_discord_callback($code, $state);
        }
    }
    
    /**
     * Raw PHP redirect - bypasses ALL WordPress output handling
     */
    private function raw_redirect($url) {
        // Abort ALL output buffering
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        // Prevent ANY further output
        ob_start(function() { return ''; });
        
        // Send Location header and exit immediately
        header('HTTP/1.1 302 Found');
        header('Location: ' . $url);
        header('Connection: close');
        header('Content-Length: 0');
        
        // Terminate immediately
        exit();
    }
    
    /**
     * Handle Google OAuth callback
     */
    private function handle_google_callback($code, $state) {
        if (!wp_verify_nonce($state, 'apex_google_oauth')) {
            $this->raw_redirect(home_url('?login_error=invalid_state'));
        }
        
        $settings = self::get_settings();
        $redirect_uri = home_url('?apex_oauth=google');
        
        // Exchange code for token
        $response = wp_remote_post('https://oauth2.googleapis.com/token', array(
            'body' => array(
                'code' => $code,
                'client_id' => $settings['google_client_id'],
                'client_secret' => $settings['google_client_secret'],
                'redirect_uri' => $redirect_uri,
                'grant_type' => 'authorization_code',
            ),
        ));
        
        if (is_wp_error($response)) {
            $this->raw_redirect(home_url('?login_error=token_error'));
        }
        
        $token_data = json_decode(wp_remote_retrieve_body($response), true);
        
        if (empty($token_data['access_token'])) {
            $this->raw_redirect(home_url('?login_error=no_token'));
        }
        
        // Get user info
        $user_response = wp_remote_get('https://www.googleapis.com/oauth2/v2/userinfo', array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $token_data['access_token'],
            ),
        ));
        
        if (is_wp_error($user_response)) {
            $this->raw_redirect(home_url('?login_error=user_error'));
        }
        
        $user_data = json_decode(wp_remote_retrieve_body($user_response), true);
        
        if (empty($user_data['email'])) {
            $this->raw_redirect(home_url('?login_error=no_email'));
        }
        
        $this->login_or_create_user($user_data['email'], $user_data['name'] ?? '', 'google', $user_data['id'], $user_data['picture'] ?? '');
    }
    
    /**
     * Handle Discord OAuth callback
     */
    private function handle_discord_callback($code, $state) {
        if (!wp_verify_nonce($state, 'apex_discord_oauth')) {
            $this->raw_redirect(home_url('?login_error=invalid_state'));
        }
        
        $settings = self::get_settings();
        $redirect_uri = home_url('?apex_oauth=discord');
        
        // Exchange code for token
        $response = wp_remote_post('https://discord.com/api/oauth2/token', array(
            'headers' => array(
                'Content-Type' => 'application/x-www-form-urlencoded',
            ),
            'body' => array(
                'code' => $code,
                'client_id' => $settings['discord_client_id'],
                'client_secret' => $settings['discord_client_secret'],
                'redirect_uri' => $redirect_uri,
                'grant_type' => 'authorization_code',
            ),
        ));
        
        if (is_wp_error($response)) {
            $this->raw_redirect(home_url('?login_error=token_error'));
        }
        
        $token_data = json_decode(wp_remote_retrieve_body($response), true);
        
        if (empty($token_data['access_token'])) {
            $this->raw_redirect(home_url('?login_error=no_token'));
        }
        
        // Get user info
        $user_response = wp_remote_get('https://discord.com/api/users/@me', array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $token_data['access_token'],
            ),
        ));
        
        if (is_wp_error($user_response)) {
            $this->raw_redirect(home_url('?login_error=user_error'));
        }
        
        $user_data = json_decode(wp_remote_retrieve_body($user_response), true);
        
        if (empty($user_data['email'])) {
            $this->raw_redirect(home_url('?login_error=no_email'));
        }
        
        $display_name = $user_data['username'] ?? '';
        if (!empty($user_data['global_name'])) {
            $display_name = $user_data['global_name'];
        }
        
        // Build Discord avatar URL
        $avatar_url = '';
        if (!empty($user_data['avatar'])) {
            $avatar_url = 'https://cdn.discordapp.com/avatars/' . $user_data['id'] . '/' . $user_data['avatar'] . '.png?size=128';
        }
        
        $this->login_or_create_user($user_data['email'], $display_name, 'discord', $user_data['id'], $avatar_url);
    }
    
    /**
     * Login or create user from OAuth
     */
    private function login_or_create_user($email, $name, $provider, $provider_id, $avatar_url = '') {
        $user = get_user_by('email', $email);
        
        if ($user) {
            // Existing user - log them in
            wp_set_auth_cookie($user->ID, true);
            update_user_meta($user->ID, 'apex_notes_' . $provider . '_id', $provider_id);
            
            // Update avatar if from Discord/Google
            if ($avatar_url) {
                update_user_meta($user->ID, 'apex_notes_avatar', $avatar_url);
            }
            
            // If they logged in with OAuth, mark email as verified
            update_user_meta($user->ID, 'apex_notes_email_verified', '1');
            
            $this->raw_redirect(home_url());
        }
        
        // Create new user
        $username = sanitize_user(strtolower(str_replace(' ', '', $name)));
        if (empty($username) || username_exists($username)) {
            $username = sanitize_user(strtolower(explode('@', $email)[0]));
        }
        if (username_exists($username)) {
            $username .= '_' . wp_rand(100, 999);
        }
        
        $password = wp_generate_password(16, true, true);
        
        $user_id = wp_create_user($username, $password, $email);
        
        if (is_wp_error($user_id)) {
            $this->raw_redirect(home_url('?login_error=create_failed'));
        }
        
        // Update user meta
        wp_update_user(array(
            'ID' => $user_id,
            'display_name' => $name,
        ));
        
        update_user_meta($user_id, 'apex_notes_' . $provider . '_id', $provider_id);
        update_user_meta($user_id, 'apex_notes_registered_via', $provider);
        
        // Store avatar
        if ($avatar_url) {
            update_user_meta($user_id, 'apex_notes_avatar', $avatar_url);
        }
        
        // OAuth users are automatically verified (they verified with Google/Discord)
        update_user_meta($user_id, 'apex_notes_email_verified', '1');
        
        // Log them in
        wp_set_auth_cookie($user_id, true);
        
        $this->raw_redirect(home_url('?welcome=1'));
    }
    
    /**
     * JavaScript redirect to bypass gzip issues
     */
    private function js_redirect($url) {
        // Clear all output buffers
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        // Output a simple HTML page with JS redirect
        header('Content-Type: text/html; charset=utf-8');
        header('Content-Encoding: none');
        echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Redirecting...</title></head><body>';
        echo '<script>window.location.href = "' . esc_url($url) . '";</script>';
        echo '<noscript><meta http-equiv="refresh" content="0;url=' . esc_url($url) . '"></noscript>';
        echo '<p>Redirecting... <a href="' . esc_url($url) . '">Click here</a> if not redirected.</p>';
        echo '</body></html>';
        exit;
    }
    
    /**
     * Login shortcode for custom login page
     */
    public function login_shortcode($atts) {
        if (is_user_logged_in()) {
            return '<p>You are already logged in.</p>';
        }
        
        $settings = self::get_settings();
        $google_url = $this->get_google_auth_url();
        $discord_url = $this->get_discord_auth_url();
        
        ob_start();
        ?>
        <div class="apex-login-box">
            <h2>Sign in to Apex Notes</h2>
            <p>Login with your Google or Discord account to share track notes.</p>
            
            <div class="apex-oauth-buttons">
                <?php if ($google_url): ?>
                <a href="<?php echo esc_url($google_url); ?>" class="apex-oauth-btn apex-oauth-google">
                    <svg width="20" height="20" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                    Continue with Google
                </a>
                <?php endif; ?>
                
                <?php if ($discord_url): ?>
                <a href="<?php echo esc_url($discord_url); ?>" class="apex-oauth-btn apex-oauth-discord">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="#5865F2"><path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0 12.64 12.64 0 0 0-.617-1.25.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057 19.9 19.9 0 0 0 5.993 3.03.078.078 0 0 0 .084-.028 14.09 14.09 0 0 0 1.226-1.994.076.076 0 0 0-.041-.106 13.107 13.107 0 0 1-1.872-.892.077.077 0 0 1-.008-.128 10.2 10.2 0 0 0 .372-.292.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127 12.299 12.299 0 0 1-1.873.892.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028 19.839 19.839 0 0 0 6.002-3.03.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03zM8.02 15.33c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.956-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.956 2.418-2.157 2.418zm7.975 0c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.955-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.946 2.418-2.157 2.418z"/></svg>
                    Continue with Discord
                </a>
                <?php endif; ?>
            </div>
            
            <?php if (!$google_url && !$discord_url): ?>
            <p class="apex-login-notice">OAuth login is not configured. Please contact the site administrator.</p>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Get user profile data
     */
    public static function get_user_profile($user_id) {
        $user = get_user_by('id', $user_id);
        if (!$user) {
            return null;
        }
        
        return array(
            'id' => $user->ID,
            'name' => $user->display_name,
            'avatar' => self::get_user_avatar($user_id),
            'bio' => get_user_meta($user_id, 'apex_notes_bio', true) ?: '',
            'discord_username' => get_user_meta($user_id, 'apex_notes_discord_username', true) ?: '',
            'steam_id' => get_user_meta($user_id, 'apex_notes_steam_id', true) ?: '',
            'registered_via' => get_user_meta($user_id, 'apex_notes_registered_via', true) ?: 'wordpress',
            'joined' => $user->user_registered,
            'is_admin' => user_can($user, 'manage_options'),
        );
    }
    
    /**
     * Get user avatar (Discord/Google or gravatar)
     */
    public static function get_user_avatar($user_id) {
        $oauth_avatar = get_user_meta($user_id, 'apex_notes_avatar', true);
        if ($oauth_avatar) {
            return $oauth_avatar;
        }
        
        // Fallback to gravatar
        $user = get_user_by('id', $user_id);
        if ($user) {
            return get_avatar_url($user->user_email, array('size' => 128));
        }
        
        return '';
    }
    
    /**
     * Update user profile
     */
    public static function update_user_profile($user_id, $data) {
        if (!$user_id) {
            return false;
        }
        
        // Only allow users to update their own profile
        if (get_current_user_id() !== $user_id && !current_user_can('manage_options')) {
            return false;
        }
        
        if (isset($data['bio'])) {
            update_user_meta($user_id, 'apex_notes_bio', sanitize_textarea_field($data['bio']));
        }
        
        if (isset($data['discord_username'])) {
            update_user_meta($user_id, 'apex_notes_discord_username', sanitize_text_field($data['discord_username']));
        }
        
        if (isset($data['steam_id'])) {
            update_user_meta($user_id, 'apex_notes_steam_id', sanitize_text_field($data['steam_id']));
        }
        
        if (isset($data['display_name'])) {
            wp_update_user(array(
                'ID' => $user_id,
                'display_name' => sanitize_text_field($data['display_name']),
            ));
        }
        
        return true;
    }
}
