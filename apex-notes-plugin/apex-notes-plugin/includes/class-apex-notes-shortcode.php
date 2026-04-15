<?php
/**
 * Apex Notes Shortcode
 */

if (!defined('ABSPATH')) {
    exit;
}

class Apex_Notes_Shortcode {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_shortcode('apex_notes', array($this, 'render_shortcode'));
        add_shortcode('apex_stewards', array($this, 'render_stewards_shortcode'));
    }
    
    /**
     * Render the shortcode
     */
    public function render_shortcode($atts) {
        $atts = shortcode_atts(array(
            'view' => 'full', // full, list, featured
        ), $atts, 'apex_notes');
        
        ob_start();
        ?>
        <div id="apex-notes-app" class="apex-notes-wrapper apex-fullscreen" data-view="<?php echo esc_attr($atts['view']); ?>">
            
            <!-- Navigation -->
            <nav class="apex-nav">
                <div class="apex-nav-container">
                    <a class="apex-logo" href="#" data-page="home">
                        <img src="<?php echo APEX_NOTES_PLUGIN_URL; ?>assets/images/logo.png" alt="Apex Notes" class="apex-logo-img">
                    </a>
                    
                    <!-- Hamburger Menu Button (Mobile) -->
                    <button class="apex-hamburger" id="apex-hamburger" aria-label="Open menu">
                        <span class="apex-hamburger-line"></span>
                        <span class="apex-hamburger-line"></span>
                        <span class="apex-hamburger-line"></span>
                    </button>
                    
                    <div class="apex-nav-links">
                        <button class="apex-nav-link active" data-page="home">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                            <span>Notes</span>
                        </button>
                        <button class="apex-nav-link" data-page="track-guide">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/></svg>
                            <span>Track Guide</span>
                        </button>
                        <button class="apex-nav-link apex-nav-highlight" data-page="track-ai">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2a10 10 0 0 1 10 10c0 5.52-4.48 10-10 10S2 17.52 2 12 6.48 2 12 2z"/><path d="M12 8v4l3 3"/></svg>
                            <span>ApexTrackBot</span>
                        </button>
                        <button class="apex-nav-link apex-nav-fuel" data-page="fuel-data">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><path d="M9 15h6"/><path d="M12 18v-6"/></svg>
                            <span>Fuel Data</span>
                        </button>
                        <button class="apex-nav-link apex-nav-tire" data-page="tire-data">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
                            <span>Tire Data</span>
                        </button>
                        <button class="apex-nav-link apex-nav-stewards" data-page="stewards-room">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                            <span>Stewards</span>
                        </button>
                        <button class="apex-nav-link apex-nav-live-race" data-page="live-race">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="3" fill="currentColor"/></svg>
                            <span>Live Race</span>
                            <span class="apex-live-indicator"></span>
                        </button>
                    </div>
                    
                    <div class="apex-nav-actions">
                        <button class="apex-btn apex-btn-primary apex-new-note-btn">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            <span>New Note</span>
                        </button>
                        
                        <?php if (is_user_logged_in()) : ?>
                            <?php 
                            $user = wp_get_current_user(); 
                            $avatar_url = Apex_Notes_Auth::get_user_avatar($user->ID);
                            $unread_count = Apex_Notes_DB::get_unread_notification_count($user->ID);
                            ?>
                            
                            <!-- Notifications Bell -->
                            <div class="apex-notifications-menu">
                                <button class="apex-notifications-btn" id="apex-notifications-toggle">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                                    <span class="apex-notifications-badge" id="apex-notifications-badge" <?php echo $unread_count > 0 ? '' : 'style="display:none;"'; ?>><?php echo $unread_count > 9 ? '9+' : $unread_count; ?></span>
                                </button>
                                <div class="apex-notifications-dropdown" id="apex-notifications-dropdown">
                                    <div class="apex-notifications-header">
                                        <h4>Notifications</h4>
                                        <button class="apex-mark-all-read" id="apex-mark-all-read">Mark all read</button>
                                    </div>
                                    <div class="apex-notifications-list" id="apex-notifications-list">
                                        <div class="apex-loading"><div class="apex-spinner"></div></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="apex-user-menu">
                                <div class="apex-user-avatar">
                                    <?php if ($avatar_url): ?>
                                        <img src="<?php echo esc_url($avatar_url); ?>" alt="">
                                    <?php else: ?>
                                        <?php echo esc_html(substr($user->display_name, 0, 1)); ?>
                                    <?php endif; ?>
                                </div>
                                <div class="apex-user-dropdown">
                                    <div class="apex-user-info">
                                        <span class="apex-user-name"><?php echo esc_html($user->display_name); ?></span>
                                        <span class="apex-user-email"><?php echo esc_html($user->user_email); ?></span>
                                    </div>
                                    <button class="apex-view-profile" data-user-id="<?php echo esc_attr($user->ID); ?>">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                        My Profile
                                    </button>
                                    <button data-page="my-notes">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg>
                                        My Notes
                                    </button>
                                    <?php if (current_user_can('moderate_apex_notes')) : ?>
                                        <button data-page="moderation">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                            Moderation
                                            <span class="apex-admin-badge">Admin</span>
                                        </button>
                                    <?php endif; ?>
                                    <a href="<?php echo esc_url(wp_logout_url(get_permalink())); ?>">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                        Sign Out
                                    </a>
                                </div>
                            </div>
                        <?php else : ?>
                            <button class="apex-btn apex-btn-primary apex-sign-in-btn" id="apex-show-login">
                                Sign In
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </nav>
            
            <!-- Mobile Menu Overlay -->
            <div class="apex-mobile-menu-overlay" id="apex-mobile-menu-overlay"></div>
            <div class="apex-mobile-menu" id="apex-mobile-menu">
                <div class="apex-mobile-menu-header">
                    <span class="apex-mobile-menu-title">Menu</span>
                    <button class="apex-mobile-menu-close" id="apex-mobile-menu-close">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>
                <div class="apex-mobile-menu-links">
                    <button class="apex-mobile-nav-link active" data-page="home">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        <span>Notes</span>
                    </button>
                    <button class="apex-mobile-nav-link" data-page="track-guide">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/></svg>
                        <span>Track Guide</span>
                    </button>
                    <button class="apex-mobile-nav-link" data-page="track-ai">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2a10 10 0 0 1 10 10c0 5.52-4.48 10-10 10S2 17.52 2 12 6.48 2 12 2z"/><path d="M12 8v4l3 3"/></svg>
                        <span>ApexTrackBot</span>
                    </button>
                    <button class="apex-mobile-nav-link" data-page="fuel-data">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><path d="M9 15h6"/><path d="M12 18v-6"/></svg>
                        <span>Fuel Data</span>
                    </button>
                    <button class="apex-mobile-nav-link" data-page="tire-data">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
                        <span>Tire Data</span>
                    </button>
                    <button class="apex-mobile-nav-link" data-page="stewards-room">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                        <span>Stewards Room</span>
                    </button>
                    <button class="apex-mobile-nav-link" data-page="live-race">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="3" fill="currentColor"/></svg>
                        <span>Live Race</span>
                    </button>
                    
                    <div class="apex-mobile-menu-divider"></div>
                    
                    <button class="apex-mobile-nav-link apex-mobile-new-note apex-new-note-btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        <span>New Note</span>
                    </button>
                </div>
                
                <!-- Mobile Menu Profile Section -->
                <div class="apex-mobile-menu-profile">
                    <?php if (is_user_logged_in()) : ?>
                        <?php 
                        $user = wp_get_current_user(); 
                        $avatar_url = Apex_Notes_Auth::get_user_avatar($user->ID);
                        ?>
                        <div class="apex-mobile-profile-info">
                            <div class="apex-mobile-avatar">
                                <?php if ($avatar_url): ?>
                                    <img src="<?php echo esc_url($avatar_url); ?>" alt="">
                                <?php else: ?>
                                    <?php echo esc_html(substr($user->display_name, 0, 1)); ?>
                                <?php endif; ?>
                            </div>
                            <div class="apex-mobile-user-details">
                                <span class="apex-mobile-user-name"><?php echo esc_html($user->display_name); ?></span>
                                <span class="apex-mobile-user-email"><?php echo esc_html($user->user_email); ?></span>
                            </div>
                        </div>
                        <div class="apex-mobile-profile-actions">
                            <button class="apex-mobile-profile-link apex-view-profile" data-user-id="<?php echo esc_attr($user->ID); ?>">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                My Profile
                            </button>
                            <button class="apex-mobile-profile-link" data-page="my-notes">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg>
                                My Notes
                            </button>
                            <?php if (current_user_can('moderate_apex_notes')) : ?>
                            <button class="apex-mobile-profile-link" data-page="moderation">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                Moderation
                            </button>
                            <?php endif; ?>
                            <a href="<?php echo esc_url(wp_logout_url(get_permalink())); ?>" class="apex-mobile-profile-link apex-mobile-logout">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                Sign Out
                            </a>
                        </div>
                    <?php else : ?>
                        <button class="apex-btn apex-btn-primary apex-mobile-sign-in apex-open-login" style="width:100%;">
                            Sign In
                        </button>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Login Modal -->
            <div class="apex-modal-overlay" id="apex-login-modal">
                <div class="apex-modal apex-login-modal">
                    <button class="apex-modal-close">&times;</button>
                    <h2>Sign in to Apex Notes</h2>
                    <p>Login to share your track notes with the community.</p>
                    
                    <div class="apex-oauth-buttons">
                        <?php 
                        $auth = Apex_Notes_Auth::get_instance();
                        $google_url = $auth->get_google_auth_url();
                        $discord_url = $auth->get_discord_auth_url();
                        ?>
                        
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
                        
                        <?php if (!$google_url && !$discord_url): ?>
                        <p class="apex-login-notice">Social login not configured. <a href="<?php echo esc_url(wp_login_url(get_permalink())); ?>">Use standard WordPress login</a>.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Verification Notice -->
            <?php if (is_user_logged_in() && !Apex_Notes_Auth::is_user_verified()): ?>
            <div class="apex-verification-banner" id="apex-verify-banner">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span>Please verify your email to post notes.</span>
                <button class="apex-btn apex-btn-primary" id="apex-resend-verify">Resend verification email</button>
            </div>
            <?php endif; ?>
            
            <!-- Home Page -->
            <div class="apex-page active" data-page="home">
                <!-- Hero -->
                <section class="apex-hero">
                    <div class="apex-hero-bg"></div>
                    <div class="apex-hero-content">
                        <span class="apex-badge apex-badge-featured">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/></svg>
                            Le Mans Ultimate Community
                        </span>
                        <h1 class="apex-hero-title">Master Every <span class="apex-accent">Corner</span></h1>
                        <p class="apex-hero-subtitle">Share and discover detailed track notes, braking zones, and racing lines for Le Mans Ultimate. Hypercars, LMP2, LMGT3 and more.</p>
                        <button class="apex-btn apex-btn-primary apex-btn-large apex-new-note-btn">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Share Your Notes
                        </button>
                    </div>
                </section>
                
                <!-- Filters -->
                <section class="apex-filters">
                    <div class="apex-filters-header">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                        <span>Filters</span>
                    </div>
                    <div class="apex-filters-row">
                        <div class="apex-search-wrapper">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            <input type="text" class="apex-search-input" id="apex-search" placeholder="Search notes...">
                        </div>
                        <button class="apex-btn apex-btn-ghost apex-reset-filters-btn" id="apex-reset-filters" title="Reset all filters">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                            <span>Reset</span>
                        </button>
                        <div class="apex-select-wrapper">
                            <select id="apex-filter-track">
                                <option value="">All Tracks</option>
                            </select>
                        </div>
                        <div class="apex-select-wrapper">
                            <select id="apex-filter-car">
                                <option value="">All Cars</option>
                            </select>
                        </div>
                        <div class="apex-select-wrapper">
                            <select id="apex-filter-difficulty">
                                <option value="">All Levels</option>
                            </select>
                        </div>
                    </div>
                </section>
                
                <!-- Notes Grid -->
                <section class="apex-section">
                    <div class="apex-section-header">
                        <h2 class="apex-section-title">Track Notes <span class="apex-results-count" id="apex-results-count">0 results</span></h2>
                        <div class="apex-sort-buttons">
                            <button class="apex-sort-btn active" data-sort="created_at">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                Recent
                            </button>
                            <button class="apex-sort-btn" data-sort="rating">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                Top Rated
                            </button>
                            <button class="apex-sort-btn" data-sort="views">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                                Popular
                            </button>
                        </div>
                    </div>
                    <div class="apex-notes-grid" id="apex-notes-grid"></div>
                    <div class="apex-empty-state" id="apex-empty-state" style="display:none;">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        <h3>No notes found</h3>
                        <p>Try adjusting your filters or be the first to share notes!</p>
                        <button class="apex-btn apex-btn-primary apex-new-note-btn">Create First Note</button>
                    </div>
                </section>
            </div>
            
            <!-- Detail Page -->
            <div class="apex-page" data-page="detail">
                <div class="apex-detail-page" id="apex-detail-content"></div>
            </div>
            
            <!-- Create Page -->
            <div class="apex-page" data-page="create">
                <div class="apex-create-page">
                    <button class="apex-back-link" data-page="home">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                        Back to Notes
                    </button>
                    
                    <?php if (!is_user_logged_in()) : ?>
                    <div class="apex-login-required">
                        <div class="apex-login-required-icon">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                        </div>
                        <h2>Sign In Required</h2>
                        <p>You need to be signed in to create track notes.</p>
                        <button class="apex-btn apex-btn-primary apex-open-login">Sign In</button>
                    </div>
                    <?php else : ?>
                    <h1 class="apex-page-title">Create New Track Note</h1>
                    <form id="apex-create-form">
                        <div class="apex-form-section">
                            <h3>Basic Information</h3>
                            <div class="apex-form-group">
                                <label>Title *</label>
                                <input type="text" id="apex-note-title" placeholder="e.g., Ultimate Guide to Le Mans - Ferrari 499P" required>
                            </div>
                            <div class="apex-form-group">
                                <label>Description *</label>
                                <textarea id="apex-note-description" placeholder="Describe your track notes..." rows="4" required></textarea>
                            </div>
                            <div class="apex-form-row">
                                <div class="apex-form-group">
                                    <label>Car Class *</label>
                                    <select id="apex-note-class" required></select>
                                </div>
                                <div class="apex-form-group">
                                    <label>Difficulty *</label>
                                    <select id="apex-note-difficulty" required></select>
                                </div>
                            </div>
                            <div class="apex-form-row">
                                <div class="apex-form-group">
                                    <label>Track *</label>
                                    <select id="apex-note-track" required></select>
                                </div>
                                <div class="apex-form-group" id="apex-layout-group" style="display: none;">
                                    <label>Layout</label>
                                    <select id="apex-note-layout"></select>
                                </div>
                            </div>
                            <div class="apex-form-row">
                                <div class="apex-form-group">
                                    <label>Car *</label>
                                    <select id="apex-note-car" required></select>
                                </div>
                                <div class="apex-form-group">
                                    <label>Lap Time</label>
                                    <input type="text" id="apex-note-laptime" placeholder="e.g., 3:25.456">
                                </div>
                            </div>
                            <div class="apex-form-group">
                                <label>Setup Notes</label>
                                <textarea id="apex-note-setup" placeholder="Car setup recommendations..." rows="3"></textarea>
                            </div>
                        </div>
                        
                        <div class="apex-form-section">
                            <h3>Track Sections</h3>
                            <p class="apex-section-hint">Add detailed notes for each corner or section.</p>
                            <div class="apex-added-sections" id="apex-added-sections"></div>
                            <div class="apex-section-form">
                                <div class="apex-form-group">
                                    <label>Corner/Section Name</label>
                                    <input type="text" id="apex-section-name" placeholder="e.g., Dunlop Chicane">
                                </div>
                                <div class="apex-form-row">
                                    <div class="apex-form-group">
                                        <label>Braking Point</label>
                                        <input type="text" id="apex-section-braking" placeholder="e.g., 100m board">
                                    </div>
                                    <div class="apex-form-group">
                                        <label>Turn-In Point</label>
                                        <input type="text" id="apex-section-turnin" placeholder="e.g., At the 50m marker">
                                    </div>
                                </div>
                                <div class="apex-form-row">
                                    <div class="apex-form-group">
                                        <label>Apex</label>
                                        <input type="text" id="apex-section-apex" placeholder="e.g., Clip inside curb">
                                    </div>
                                    <div class="apex-form-group">
                                        <label>Exit</label>
                                        <input type="text" id="apex-section-exit" placeholder="e.g., Track out left">
                                    </div>
                                </div>
                                <div class="apex-form-row">
                                    <div class="apex-form-group">
                                        <label>Gear</label>
                                        <input type="text" id="apex-section-gear" placeholder="e.g., 3rd gear">
                                    </div>
                                    <div class="apex-form-group">
                                        <label>Speed</label>
                                        <input type="text" id="apex-section-speed" placeholder="e.g., Entry: 280 km/h">
                                    </div>
                                </div>
                                <div class="apex-form-group">
                                    <label>Pro Tip</label>
                                    <textarea id="apex-section-tip" placeholder="Special advice..." rows="2"></textarea>
                                </div>
                                <div class="apex-section-buttons">
                                    <button type="button" class="apex-btn apex-btn-primary" id="apex-add-section-btn">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                        Save Section
                                    </button>
                                    <button type="button" class="apex-btn apex-btn-secondary" id="apex-save-and-add-section-btn">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                        Save &amp; Add Another
                                    </button>
                                </div>
                                <p class="apex-section-hint">⚠️ Don't forget to click "Save Section" before submitting! Your section data will be lost otherwise.</p>
                            </div>
                        </div>
                        
                        <div class="apex-form-actions">
                            <button type="button" class="apex-btn apex-btn-ghost" data-page="home">Cancel</button>
                            <button type="submit" class="apex-btn apex-btn-primary">Submit for Review</button>
                        </div>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Detail Page -->
            <div class="apex-page" data-page="detail">
                <div id="apex-detail-content"></div>
            </div>
            
            <!-- My Notes Page -->
            <div class="apex-page" data-page="my-notes">
                <div class="apex-create-page">
                    <?php if (!is_user_logged_in()) : ?>
                    <div class="apex-login-required">
                        <div class="apex-login-required-icon">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                        </div>
                        <h2>Sign In Required</h2>
                        <p>You need to be signed in to view your notes.</p>
                        <button class="apex-btn apex-btn-primary apex-open-login">Sign In</button>
                    </div>
                    <?php else : ?>
                    <div class="apex-section-header" style="margin-bottom:24px;">
                        <h1 class="apex-page-title" style="margin:0;">My Notes</h1>
                        <button class="apex-btn apex-btn-primary apex-new-note-btn">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Create New Note
                        </button>
                    </div>
                    <div id="apex-my-notes-list"></div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Moderation Page -->
            <div class="apex-page" data-page="moderation">
                <div class="apex-mod-page">
                    <h1 class="apex-page-title">Content Moderation</h1>
                    <div class="apex-mod-tabs">
                        <button class="apex-mod-tab active" data-status="pending">
                            Pending <span class="apex-mod-count" id="apex-pending-count">0</span>
                        </button>
                        <button class="apex-mod-tab" data-status="approved">Approved</button>
                        <button class="apex-mod-tab" data-status="rejected">Rejected</button>
                        <button class="apex-mod-tab" data-status="banned">
                            Banned Users <span class="apex-mod-count" id="apex-banned-count">0</span>
                        </button>
                    </div>
                    <div class="apex-mod-list" id="apex-mod-list"></div>
                </div>
            </div>
            
            <!-- Profile Page -->
            <div class="apex-page" data-page="profile">
                <div class="apex-main-content">
                    <button class="apex-back-link" data-page="home">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                        Back to Notes
                    </button>
                    <div id="apex-profile-content">
                        <div class="apex-loading"><div class="apex-spinner"></div></div>
                    </div>
                </div>
            </div>
            
            <!-- Livery Download Page -->
            <div class="apex-page" data-page="livery">
                <div class="apex-main-content">
                    <button class="apex-back-link" data-page="home">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                        Back to Notes
                    </button>
                    <div id="apex-livery-download-content">
                        <div class="apex-loading"><div class="apex-spinner"></div></div>
                    </div>
                </div>
            </div>
            
            <!-- Track Guide Page -->
            <div class="apex-page" data-page="track-guide">
                <div class="apex-main-content">
                    <button class="apex-back-link" data-page="home">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                        Back to Notes
                    </button>
                    
                    <div class="apex-track-guide-header">
                        <h1 class="apex-page-title">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>
                            Video Track Guides
                        </h1>
                        <p class="apex-track-guide-subtitle">Watch detailed lap guides and tutorials for every Le Mans Ultimate circuit</p>
                        
                        <div class="apex-track-guide-selector">
                            <label for="apex-track-select">Select Track:</label>
                            <select id="apex-track-select" class="apex-track-dropdown">
                                <option value="">-- Choose a Circuit --</option>
                            </select>
                        </div>
                    </div>
                    
                    <div id="apex-track-videos-content">
                        <div class="apex-track-guide-intro">
                            <div class="apex-intro-card">
                                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>
                                <h3>Select a Track to Begin</h3>
                                <p>Choose a circuit from the dropdown above to view video tutorials, lap guides, and setup advice from the Le Mans Ultimate community.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- ApexTrackBot Page -->
            <div class="apex-page" data-page="track-ai">
                <div class="apex-main-content">
                    <button class="apex-back-link" data-page="home">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                        Back to Notes
                    </button>
                    
                    <?php if (!is_user_logged_in()) : ?>
                    <div class="apex-login-required">
                        <div class="apex-login-required-icon">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                        </div>
                        <h2>Sign In Required</h2>
                        <p>You need to be signed in to use ApexTrackBot.</p>
                        <button class="apex-btn apex-btn-primary apex-open-login">Sign In</button>
                    </div>
                    <?php else : ?>
                    <div class="apex-ai-chat-container">
                        <div class="apex-ai-header">
                            <div class="apex-ai-icon">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 2a10 10 0 0 1 10 10c0 5.52-4.48 10-10 10S2 17.52 2 12 6.48 2 12 2z"/>
                                    <path d="M12 8v4l3 3"/>
                                </svg>
                            </div>
                            <div class="apex-ai-header-text">
                                <h1>ApexTrackBot</h1>
                                <p>Your expert for motorsport circuits &amp; car setups</p>
                            </div>
                        </div>
                        
                        <div class="apex-ai-suggestions">
                            <span class="apex-ai-suggestion" data-query="What are the turn names at Spa-Francorchamps?">Spa turn names</span>
                            <span class="apex-ai-suggestion" data-query="Show me the race car setup flowchart">Setup flowchart</span>
                            <span class="apex-ai-suggestion" data-query="How do I set up a Hypercar for endurance racing?">Hypercar setup tips</span>
                            <span class="apex-ai-suggestion" data-query="What tyre pressures should I use in Le Mans Ultimate?">LMU tyre pressures</span>
                            <span class="apex-ai-suggestion" data-query="What is the optimal seat position for sim racing?">Seat position guide</span>
                            <span class="apex-ai-suggestion" data-query="Explain toe settings and how they affect handling">Toe settings</span>
                        </div>
                        
                        <div class="apex-ai-messages" id="apex-ai-messages">
                            <div class="apex-ai-message apex-ai-assistant">
                                <div class="apex-ai-avatar">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2a10 10 0 0 1 10 10c0 5.52-4.48 10-10 10S2 17.52 2 12 6.48 2 12 2z"/><path d="M12 8v4l3 3"/></svg>
                                </div>
                                <div class="apex-ai-bubble">
                                    <p>Hello! I'm <strong>ApexTrackBot</strong>, your specialist for motorsport circuits and car setups. I can help you with:</p>
                                    <ul>
                                        <li><strong>Track Information</strong> - Turn names, layouts, history, characteristics for 700+ circuits worldwide</li>
                                        <li><strong>Car Setups</strong> - Tyre pressures, suspension, aero, differential, brake bias for Le Mans Ultimate</li>
                                        <li><strong>Setup Tuning</strong> - How to diagnose and fix understeer, oversteer, and balance issues</li>
                                        <li><strong>Sim Rig Ergonomics</strong> - Optimal seat position, pedal placement, and wheel height</li>
                                    </ul>
                                    <p>Ask me anything about racing circuits, car setups, or driving position!</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="apex-ai-input-container">
                            <input type="text" id="apex-ai-input" class="apex-ai-input" placeholder="Ask about tracks or setups..." autocomplete="off">
                            <button class="apex-ai-send-btn" id="apex-ai-send">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                            </button>
                        </div>
                        
                        <p class="apex-ai-disclaimer">Data sourced from RacingCircuits.info, Motorsport Magazine, Ultimate Setup Hub, Coach Dave Academy &amp; Qubic System</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Fuel Data Page -->
            <div class="apex-page" data-page="fuel-data">
                <div class="apex-main-content">
                    <button class="apex-back-link" data-page="home">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                        Back to Notes
                    </button>
                    
                    <?php if (!is_user_logged_in()) : ?>
                    <div class="apex-login-required">
                        <div class="apex-login-required-icon">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                        </div>
                        <h2>Sign In Required</h2>
                        <p>You need to be signed in to upload and analyze race data.</p>
                        <button class="apex-btn apex-btn-primary apex-open-login">Sign In</button>
                    </div>
                    <?php else : ?>
                    <div class="apex-fuel-container">
                        <div class="apex-fuel-header">
                            <div class="apex-fuel-icon">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <polyline points="14 2 14 8 20 8"/>
                                    <path d="M9 15h6"/>
                                    <path d="M12 18v-6"/>
                                </svg>
                            </div>
                            <div class="apex-fuel-header-text">
                                <h1>Fuel Data</h1>
                                <p>Community-powered fuel consumption database for Le Mans Ultimate</p>
                            </div>
                        </div>
                        
                        <!-- Fuel Data Tabs -->
                        <div class="apex-fuel-tabs">
                            <button class="apex-fuel-tab active" data-fuel-tab="upload">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                Upload Session
                            </button>
                            <button class="apex-fuel-tab" data-fuel-tab="my-sessions">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                                My Sessions
                            </button>
                            <button class="apex-fuel-tab" data-fuel-tab="community">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                Community Data
                            </button>
                            <button class="apex-fuel-tab" data-fuel-tab="pit-strategy">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                Pit Strategy &amp; AI
                            </button>
                        </div>
                        
                        <!-- Upload Tab -->
                        <div class="apex-fuel-tab-content active" data-fuel-content="upload">
                            <div class="apex-fuel-upload-zone" id="apex-fuel-dropzone">
                                <div class="apex-fuel-upload-icon">
                                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                        <polyline points="17 8 12 3 7 8"/>
                                        <line x1="12" y1="3" x2="12" y2="15"/>
                                    </svg>
                                </div>
                                <h3>Drop your LMU session XML file here</h3>
                                <p>or click to browse</p>
                                <p class="apex-fuel-upload-hint">Race (<code>-R1.xml</code>/<code>-R2.xml</code>), Practice, Qualifying, and TestDay are all supported.</p>
                                <p class="apex-fuel-upload-hint">Located in: <code>Documents\My Games\Le Mans Ultimate\UserData\Log\Results\</code></p>
                                <input type="file" id="apex-fuel-file-input" accept=".xml" style="display:none;">
                            </div>
                            
                            <div class="apex-fuel-share-option" id="apex-fuel-share-option" style="display:none;">
                                <label class="apex-checkbox-label">
                                    <input type="checkbox" id="apex-fuel-share-checkbox" checked>
                                    <span class="apex-checkbox-custom"></span>
                                    Share anonymously with community to help improve accuracy
                                </label>
                            </div>
                            
                            <!-- Upload Result -->
                            <div class="apex-fuel-result" id="apex-fuel-result" style="display:none;">
                                <div class="apex-fuel-result-header">
                                    <h3>Session Analysis</h3>
                                    <div class="apex-fuel-result-badges">
                                        <span class="apex-fuel-result-badge" id="apex-fuel-result-type">Race</span>
                                        <span class="apex-fuel-result-badge apex-fuel-mult-badge" id="apex-fuel-mult-badge" style="display:none;">2x Fuel</span>
                                    </div>
                                </div>
                                
                                <div class="apex-fuel-result-grid">
                                    <div class="apex-fuel-stat">
                                        <span class="apex-fuel-stat-label">Track</span>
                                        <span class="apex-fuel-stat-value" id="apex-fuel-stat-track">--</span>
                                    </div>
                                    <div class="apex-fuel-stat">
                                        <span class="apex-fuel-stat-label">Car</span>
                                        <span class="apex-fuel-stat-value" id="apex-fuel-stat-car">--</span>
                                    </div>
                                    <div class="apex-fuel-stat">
                                        <span class="apex-fuel-stat-label">Tank Capacity</span>
                                        <span class="apex-fuel-stat-value" id="apex-fuel-stat-tank">--</span>
                                    </div>
                                    <div class="apex-fuel-stat">
                                        <span class="apex-fuel-stat-label">Valid Laps</span>
                                        <span class="apex-fuel-stat-value" id="apex-fuel-stat-laps">--</span>
                                    </div>
                                </div>
                                
                                <div class="apex-fuel-result-main">
                                    <div class="apex-fuel-big-stat">
                                        <span class="apex-fuel-big-label">Average Fuel per Lap</span>
                                        <span class="apex-fuel-big-value" id="apex-fuel-stat-avg">-- L</span>
                                        <span class="apex-fuel-big-range" id="apex-fuel-stat-range">Range: -- to -- L</span>
                                    </div>
                                    
                                    <div class="apex-fuel-ve-stat" id="apex-fuel-ve-section" style="display:none;">
                                        <span class="apex-fuel-big-label">Avg Virtual Energy per Lap</span>
                                        <span class="apex-fuel-big-value apex-fuel-ve-value" id="apex-fuel-stat-ve">--%</span>
                                    </div>
                                </div>
                                
                                <div class="apex-fuel-result-times">
                                    <div class="apex-fuel-time">
                                        <span class="apex-fuel-time-label">Best Lap</span>
                                        <span class="apex-fuel-time-value" id="apex-fuel-stat-best">--:--.---</span>
                                    </div>
                                    <div class="apex-fuel-time">
                                        <span class="apex-fuel-time-label">Average Lap</span>
                                        <span class="apex-fuel-time-value" id="apex-fuel-stat-avglap">--:--.---</span>
                                    </div>
                                    <div class="apex-fuel-time">
                                        <span class="apex-fuel-time-label">Pit Stops</span>
                                        <span class="apex-fuel-time-value" id="apex-fuel-stat-pits">0</span>
                                    </div>
                                </div>
                                
                                <div class="apex-fuel-result-actions">
                                    <button class="apex-btn apex-btn-primary" id="apex-fuel-upload-another">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                        Upload Another
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- My Sessions Tab -->
                        <div class="apex-fuel-tab-content" data-fuel-content="my-sessions">
                            <?php if (!is_user_logged_in()) : ?>
                                <div class="apex-fuel-login-prompt">
                                    <p>Please log in to view your uploaded sessions.</p>
                                </div>
                            <?php else : ?>
                                <div class="apex-fuel-sessions-list" id="apex-fuel-sessions-list">
                                    <div class="apex-loading"><div class="apex-spinner"></div></div>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Community Data Tab -->
                        <div class="apex-fuel-tab-content" data-fuel-content="community">
                            <div class="apex-fuel-community-filters">
                                <select id="apex-fuel-filter-track" class="apex-select">
                                    <option value="">Select Track</option>
                                    <option value="Circuit de la Sarthe">Le Mans</option>
                                    <option value="Circuit de Spa-Francorchamps">Spa-Francorchamps</option>
                                    <option value="Sebring International Raceway">Sebring</option>
                                    <option value="Circuit of the Americas">COTA</option>
                                    <option value="Fuji Speedway">Fuji</option>
                                    <option value="Bahrain International Circuit">Bahrain</option>
                                    <option value="Autodromo Nazionale Monza">Monza</option>
                                    <option value="Algarve International Circuit">Portimão</option>
                                    <option value="Autodromo Enzo e Dino Ferrari">Imola</option>
                                    <option value="Silverstone Circuit">Silverstone</option>
                                    <option value="Autodromo Jose Carlos Pace">Interlagos</option>
                                    <option value="Lusail International Circuit">Qatar/Lusail</option>
                                    <option value="Circuit Paul Ricard">Paul Ricard</option>
                                </select>
                                
                                <select id="apex-fuel-filter-car" class="apex-select">
                                    <option value="">Select Car</option>
                                    <?php
                                    $fuel_cars = get_option('apex_notes_fuel_cars', array(
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
                                        'LMP2' => array('Oreca 07'),
                                        'LMP3' => array('Ginetta G61-LT-P325 Evo', 'Ligier JS P325'),
                                        'GTE' => array(
                                            'Porsche 911 RSR-19',
                                            'Ferrari 488 GTE EVO',
                                            'Corvette C8.R GTE',
                                            'Aston Martin Vantage AMR'
                                        )
                                    ));
                                    foreach ($fuel_cars as $class => $cars):
                                        if (!empty($cars)):
                                    ?>
                                    <optgroup label="<?php echo esc_attr($class); ?>">
                                        <?php foreach ($cars as $car): ?>
                                        <option value="<?php echo esc_attr($car); ?>"><?php echo esc_html($car); ?></option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                    <?php 
                                        endif;
                                    endforeach; 
                                    ?>
                                </select>
                            </div>
                            
                            <!-- Community Averages Box - only shows when track AND car selected -->
                            <div class="apex-fuel-community-averages" id="apex-fuel-community-averages" style="display:none;">
                                <div class="apex-fuel-community-averages-header">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                    <h3>Community Fuel Averages</h3>
                                </div>
                                <div class="apex-fuel-community-averages-info">
                                    <span id="apex-fuel-selected-track"></span>
                                    <span class="apex-fuel-separator">•</span>
                                    <span id="apex-fuel-selected-car"></span>
                                </div>
                                <div class="apex-fuel-community-averages-content" id="apex-fuel-community-averages-content">
                                    <div class="apex-loading"><div class="apex-spinner"></div></div>
                                </div>
                            </div>
                            
                            <div class="apex-fuel-community-grid" id="apex-fuel-community-grid">
                                <p class="apex-fuel-select-hint">Select a track and car above to view community fuel data.</p>
                            </div>
                            
                            <div class="apex-fuel-community-empty" id="apex-fuel-community-empty" style="display:none;">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <circle cx="12" cy="12" r="10"/>
                                    <path d="M16 16s-1.5-2-4-2-4 2-4 2"/>
                                    <line x1="9" y1="9" x2="9.01" y2="9"/>
                                    <line x1="15" y1="9" x2="15.01" y2="9"/>
                                </svg>
                                <p>No community data yet for this car/track combination.</p>
                                <p>Be the first to contribute!</p>
                            </div>
                        </div>

                        <!-- Pit Strategy & AI Tab -->
                        <div class="apex-fuel-tab-content" data-fuel-content="pit-strategy">
                            <div class="apex-pit-calc">
                                <div class="apex-pit-calc-header">
                                    <h3>Pit-Stop Calculator</h3>
                                    <p>Work out optimal pit laps from your uploaded session data. AI chat below can explain or refine the plan.</p>
                                </div>
                                <div class="apex-pit-calc-grid">
                                    <div class="apex-pit-calc-field">
                                        <label for="apex-pit-session">Base on session</label>
                                        <select id="apex-pit-session" class="apex-select">
                                            <option value="">Loading your sessions…</option>
                                        </select>
                                    </div>
                                    <div class="apex-pit-calc-field">
                                        <label for="apex-pit-race-laps">Race length (laps)</label>
                                        <input type="number" id="apex-pit-race-laps" min="1" step="1" placeholder="e.g. 24">
                                    </div>
                                    <div class="apex-pit-calc-field">
                                        <label for="apex-pit-race-minutes">…or minutes</label>
                                        <input type="number" id="apex-pit-race-minutes" min="1" step="1" placeholder="e.g. 60">
                                    </div>
                                    <div class="apex-pit-calc-field">
                                        <label for="apex-pit-reserve">Fuel reserve (L)</label>
                                        <input type="number" id="apex-pit-reserve" min="0" step="0.1" value="0.5">
                                    </div>
                                    <div class="apex-pit-calc-field">
                                        <label for="apex-pit-avg-fuel">Fuel / lap (L) — override</label>
                                        <input type="number" id="apex-pit-avg-fuel" min="0" step="0.01" placeholder="auto from session">
                                    </div>
                                    <div class="apex-pit-calc-field">
                                        <label for="apex-pit-tank">Tank (L) — override</label>
                                        <input type="number" id="apex-pit-tank" min="0" step="0.1" placeholder="auto from car">
                                    </div>
                                </div>
                                <div class="apex-pit-calc-actions">
                                    <button class="apex-btn apex-btn-primary" id="apex-pit-calc-run">Calculate Pit Plan</button>
                                </div>
                                <div class="apex-pit-calc-result" id="apex-pit-calc-result" style="display:none;"></div>
                            </div>

                            <div class="apex-fuel-ai-chat">
                                <div class="apex-fuel-ai-header">
                                    <h3>Ask ApexFuelBot</h3>
                                    <p>Questions about stint strategy, pace, or "when should I pit?" — grounded in your uploaded data.</p>
                                </div>
                                <div class="apex-fuel-ai-messages" id="apex-fuel-ai-messages">
                                    <div class="apex-fuel-ai-empty">
                                        <p>Try:</p>
                                        <button class="apex-fuel-ai-suggestion" data-query="What's the best lap to pit in a 45-minute race?">When should I pit in a 45-minute race?</button>
                                        <button class="apex-fuel-ai-suggestion" data-query="Am I burning more fuel than the community average?">Am I burning more fuel than the community?</button>
                                        <button class="apex-fuel-ai-suggestion" data-query="Plan a 2-stop strategy for a 3-hour race.">Plan a 2-stop strategy for a 3-hour race</button>
                                    </div>
                                </div>
                                <div class="apex-fuel-ai-input-row">
                                    <textarea id="apex-fuel-ai-input" rows="2" placeholder="Ask about your fuel data…"></textarea>
                                    <button class="apex-btn apex-btn-primary" id="apex-fuel-ai-send">Send</button>
                                </div>
                                <p class="apex-fuel-ai-hint">Limit: 30 questions/day per user. Uses your most recent uploaded sessions as context.</p>
                            </div>
                        </div>

                        <div class="apex-fuel-info">
                            <h4>How it works</h4>
                            <ol>
                                <li><strong>Upload your LMU session XML</strong> - Race, Practice, Qualifying, or TestDay all work</li>
                                <li><strong>Find your XML files</strong> - Look in the LMU results folder (path below)</li>
                                <li><strong>We extract fuel data automatically</strong> - Pit laps and out-laps are excluded from averages</li>
                                <li><strong>Share anonymously</strong> - Help build accurate community medians (bucketed by fuel multiplier)</li>
                                <li><strong>Plan stints & ask AI</strong> - Use the Pit Strategy tab for a calculator and fuel-specific chatbot</li>
                            </ol>
                            <p class="apex-fuel-info-note">Files are in: <code>Documents\My Games\Le Mans Ultimate\UserData\Log\Results\</code></p>
                            <p class="apex-fuel-info-note">Your data is anonymous when shared. Community averages use median + IQR outlier trim and are bucketed separately per fuel multiplier.</p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Tire Data Page -->
            <div class="apex-page" data-page="tire-data">
                <div class="apex-main-content">
                    <button class="apex-back-link" data-page="home">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                        Back to Notes
                    </button>
                    
                    <?php if (!is_user_logged_in()) : ?>
                    <div class="apex-login-required">
                        <div class="apex-login-required-icon">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                        </div>
                        <h2>Sign In Required</h2>
                        <p>You need to be signed in to view and analyze tire data.</p>
                        <button class="apex-btn apex-btn-primary apex-open-login">Sign In</button>
                    </div>
                    <?php else : ?>
                    <div class="apex-tire-container">
                        <div class="apex-tire-header">
                            <div class="apex-tire-icon">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <circle cx="12" cy="12" r="6"/>
                                    <circle cx="12" cy="12" r="2"/>
                                </svg>
                            </div>
                            <div class="apex-tire-header-text">
                                <h1>Tire Data</h1>
                                <p>Community-powered tire wear analytics for Le Mans Ultimate</p>
                            </div>
                        </div>
                        
                        <!-- Tire Data Tabs -->
                        <div class="apex-tire-tabs">
                            <button class="apex-tire-tab active" data-tire-tab="calculator">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="4" width="16" height="16" rx="2"/><line x1="8" y1="9" x2="16" y2="9"/><line x1="8" y1="12" x2="16" y2="12"/><line x1="8" y1="15" x2="12" y2="15"/></svg>
                                Pit Stop Calculator
                            </button>
                            <button class="apex-tire-tab" data-tire-tab="community">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                Community Data
                            </button>
                        </div>
                        
                        <!-- Calculator Tab -->
                        <div class="apex-tire-tab-content active" data-tire-content="calculator">
                            <div class="apex-tire-calculator">
                                <div class="apex-tire-calc-form">
                                    <h3>Race Setup</h3>
                                    <div class="apex-tire-calc-grid">
                                        <div class="apex-tire-calc-field">
                                            <label>Track</label>
                                            <select id="apex-tire-track-select" class="apex-select">
                                                <option value="">Select Track...</option>
                                            </select>
                                        </div>
                                        <div class="apex-tire-calc-field">
                                            <label>Car</label>
                                            <select id="apex-tire-car-select" class="apex-select">
                                                <option value="">Select Car...</option>
                                            </select>
                                        </div>
                                        <div class="apex-tire-calc-field">
                                            <label>Race Length (Laps)</label>
                                            <input type="number" id="apex-tire-race-laps" class="apex-input" placeholder="e.g. 45" min="1" max="999">
                                        </div>
                                        <div class="apex-tire-calc-field">
                                            <label>Tire Wear Threshold (%)</label>
                                            <input type="number" id="apex-tire-threshold" class="apex-input" value="50" min="20" max="80">
                                            <span class="apex-tire-calc-hint">When to pit (typical: 45-55%)</span>
                                        </div>
                                    </div>
                                    <button class="apex-btn apex-btn-primary" id="apex-tire-calculate-btn">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                                        Calculate Strategy
                                    </button>
                                </div>
                                
                                <!-- Calculator Results -->
                                <div class="apex-tire-calc-results" id="apex-tire-calc-results" style="display:none;">
                                    <h3>Strategy Recommendation</h3>
                                    <div class="apex-tire-result-grid">
                                        <div class="apex-tire-result-card apex-tire-result-primary">
                                            <span class="apex-tire-result-label">Recommended Stops</span>
                                            <span class="apex-tire-result-value" id="apex-tire-result-stops">--</span>
                                        </div>
                                        <div class="apex-tire-result-card">
                                            <span class="apex-tire-result-label">Critical Tire</span>
                                            <span class="apex-tire-result-value" id="apex-tire-result-critical">--</span>
                                        </div>
                                        <div class="apex-tire-result-card">
                                            <span class="apex-tire-result-label">Estimated Tire Life</span>
                                            <span class="apex-tire-result-value" id="apex-tire-result-life">-- laps</span>
                                        </div>
                                        <div class="apex-tire-result-card">
                                            <span class="apex-tire-result-label">Avg Wear/Lap</span>
                                            <span class="apex-tire-result-value" id="apex-tire-result-wear">--%</span>
                                        </div>
                                    </div>
                                    
                                    <div class="apex-tire-strategy-detail">
                                        <h4>Stint Breakdown</h4>
                                        <div class="apex-tire-stints" id="apex-tire-stints">
                                            <!-- Stints will be inserted here -->
                                        </div>
                                    </div>
                                    
                                    <div class="apex-tire-wear-chart">
                                        <h4>Per-Corner Wear Analysis</h4>
                                        <div class="apex-tire-wear-visual">
                                            <div class="apex-tire-car-diagram">
                                                <div class="apex-tire-position apex-tire-fl">
                                                    <span class="apex-tire-label">FL</span>
                                                    <span class="apex-tire-wear-value" id="apex-tire-wear-fl">--%</span>
                                                    <div class="apex-tire-wear-bar"><div class="apex-tire-wear-fill" id="apex-tire-bar-fl"></div></div>
                                                </div>
                                                <div class="apex-tire-position apex-tire-fr">
                                                    <span class="apex-tire-label">FR</span>
                                                    <span class="apex-tire-wear-value" id="apex-tire-wear-fr">--%</span>
                                                    <div class="apex-tire-wear-bar"><div class="apex-tire-wear-fill" id="apex-tire-bar-fr"></div></div>
                                                </div>
                                                <div class="apex-tire-position apex-tire-rl">
                                                    <span class="apex-tire-label">RL</span>
                                                    <span class="apex-tire-wear-value" id="apex-tire-wear-rl">--%</span>
                                                    <div class="apex-tire-wear-bar"><div class="apex-tire-wear-fill" id="apex-tire-bar-rl"></div></div>
                                                </div>
                                                <div class="apex-tire-position apex-tire-rr">
                                                    <span class="apex-tire-label">RR</span>
                                                    <span class="apex-tire-wear-value" id="apex-tire-wear-rr">--%</span>
                                                    <div class="apex-tire-wear-bar"><div class="apex-tire-wear-fill" id="apex-tire-bar-rr"></div></div>
                                                </div>
                                                <div class="apex-tire-car-body">
                                                    <span>FRONT</span>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="apex-tire-wear-note" id="apex-tire-wear-note"></p>
                                    </div>
                                </div>
                                
                                <div class="apex-tire-no-data" id="apex-tire-no-data" style="display:none;">
                                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                    <h3>No Data Available</h3>
                                    <p>We don't have enough tire data for this track/car combination yet.</p>
                                    <p>Upload a race session from Fuel Data to contribute!</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Community Tab -->
                        <div class="apex-tire-tab-content" data-tire-content="community">
                            <div class="apex-tire-community">
                                <div class="apex-tire-community-filters">
                                    <select id="apex-tire-community-track" class="apex-select">
                                        <option value="">All Tracks</option>
                                    </select>
                                    <select id="apex-tire-community-class" class="apex-select">
                                        <option value="">All Classes</option>
                                        <option value="Hypercar">Hypercar</option>
                                        <option value="LMP2">LMP2</option>
                                        <option value="LMGT3">LMGT3</option>
                                        <option value="GTE">GTE</option>
                                    </select>
                                </div>
                                
                                <div class="apex-tire-community-table-wrapper">
                                    <table class="apex-tire-community-table">
                                        <thead>
                                            <tr>
                                                <th>Track</th>
                                                <th>Car</th>
                                                <th>Compound</th>
                                                <th>FL/Lap</th>
                                                <th>FR/Lap</th>
                                                <th>RL/Lap</th>
                                                <th>RR/Lap</th>
                                                <th>Critical</th>
                                                <th>Est. Life</th>
                                                <th>Samples</th>
                                            </tr>
                                        </thead>
                                        <tbody id="apex-tire-community-body">
                                            <tr><td colspan="10" class="apex-tire-loading">Loading community data...</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="apex-tire-info-box">
                            <h3>💡 How Tire Data Works</h3>
                            <p class="apex-tire-info-note">Tire wear data is automatically extracted when you upload race sessions to <strong>Fuel Data</strong>.</p>
                            <p class="apex-tire-info-note">The more sessions shared with the community, the more accurate the predictions become!</p>
                            <p class="apex-tire-info-note"><strong>Critical Tire:</strong> The tire that wears fastest and determines your stint length.</p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Stewards Room Page -->
            <div class="apex-page" data-page="stewards-room">
                <div class="apex-main-content">
                    <button class="apex-back-link" data-page="home">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                        Back to Notes
                    </button>
                    
                    <div class="apex-stewards-container">
                        <!-- Header -->
                        <div class="apex-stewards-header">
                            <div class="apex-stewards-icon">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <polyline points="14 2 14 8 20 8"/>
                                    <line x1="16" y1="13" x2="8" y2="13"/>
                                    <line x1="16" y1="17" x2="8" y2="17"/>
                                    <polyline points="10 9 9 9 8 9"/>
                                </svg>
                            </div>
                            <div class="apex-stewards-header-text">
                                <h1>Stewards Room</h1>
                                <p>Race incident reports and official race documents</p>
                            </div>
                        </div>
                        
                        <!-- Upload Section -->
                        <div class="apex-stewards-upload-section" id="apex-stewards-upload-section">
                            <div class="apex-stewards-upload-box">
                                <h2>Generate Race Report</h2>
                                <p>Upload your race XML file to generate a detailed stewards report including incidents, penalties, and track limits.</p>
                                
                                <div class="apex-stewards-dropzone" id="apex-stewards-dropzone">
                                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                        <polyline points="17 8 12 3 7 8"/>
                                        <line x1="12" y1="3" x2="12" y2="15"/>
                                    </svg>
                                    <p>Drop your race XML file here or click to browse</p>
                                    <span class="apex-stewards-hint">Files ending in -R1.xml, -R2.xml (Race sessions)</span>
                                    <input type="file" id="apex-stewards-file-input" accept=".xml" style="display:none;">
                                </div>
                                
                                <div class="apex-stewards-meta-fields" id="apex-stewards-meta-fields" style="display:none;">
                                    <h3>Race Details (Optional)</h3>
                                    <div class="apex-stewards-field-row">
                                        <div class="apex-stewards-field">
                                            <label for="apex-stewards-race-name">Race Name</label>
                                            <input type="text" id="apex-stewards-race-name" placeholder="e.g., 6 Hours of Spa">
                                        </div>
                                        <div class="apex-stewards-field">
                                            <label for="apex-stewards-league-name">League Name</label>
                                            <input type="text" id="apex-stewards-league-name" placeholder="e.g., Sunday Night Endurance">
                                        </div>
                                        <div class="apex-stewards-field">
                                            <label for="apex-stewards-round">Round Number</label>
                                            <input type="text" id="apex-stewards-round" placeholder="e.g., Round 5">
                                        </div>
                                    </div>
                                    <button class="apex-btn apex-btn-primary" id="apex-stewards-generate-btn">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                            <polyline points="14 2 14 8 20 8"/>
                                        </svg>
                                        Generate Race Report
                                    </button>
                                </div>
                                
                                <div class="apex-stewards-loading" id="apex-stewards-loading" style="display:none;">
                                    <div class="apex-spinner"></div>
                                    <p>Processing race data...</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Results Section -->
                        <div class="apex-stewards-results" id="apex-stewards-results" style="display:none;">
                            
                            <!-- Race Info Header -->
                            <div class="apex-stewards-race-info" id="apex-stewards-race-info">
                                <div class="apex-stewards-race-info-main">
                                    <img src="<?php echo APEX_NOTES_PLUGIN_URL; ?>assets/images/logo.png" alt="Apex Race Notes" class="apex-stewards-logo">
                                    <div class="apex-stewards-race-details">
                                        <h2 id="apex-stewards-race-title">Race Report</h2>
                                        <div class="apex-stewards-race-meta">
                                            <span id="apex-stewards-track-name"></span>
                                            <span class="apex-stewards-separator">•</span>
                                            <span id="apex-stewards-event-name"></span>
                                            <span class="apex-stewards-separator">•</span>
                                            <span id="apex-stewards-race-date"></span>
                                        </div>
                                        <div class="apex-stewards-race-custom" id="apex-stewards-race-custom"></div>
                                    </div>
                                </div>
                                <div class="apex-stewards-actions">
                                    <button class="apex-btn apex-btn-secondary" id="apex-stewards-new-report">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <line x1="12" y1="5" x2="12" y2="19"/>
                                            <line x1="5" y1="12" x2="19" y2="12"/>
                                        </svg>
                                        New Report
                                    </button>
                                    <div class="apex-btn-dropdown">
                                        <button class="apex-btn apex-btn-primary" id="apex-stewards-download-pdf">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                                <polyline points="7 10 12 15 17 10"/>
                                                <line x1="12" y1="15" x2="12" y2="3"/>
                                            </svg>
                                            Download PDF
                                        </button>
                                        <div class="apex-pdf-options">
                                            <label class="apex-checkbox-label">
                                                <input type="checkbox" id="apex-pdf-summary-mode">
                                                <span>Summary by Driver (recommended for 50+ incidents)</span>
                                            </label>
                                        </div>
                                    </div>
                                    <?php if (is_user_logged_in()) : ?>
                                    <button class="apex-btn apex-btn-secondary" id="apex-stewards-save-report">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                                            <polyline points="17 21 17 13 7 13 7 21"/>
                                            <polyline points="7 3 7 8 15 8"/>
                                        </svg>
                                        Save & Share
                                    </button>
                                    <button class="apex-btn apex-btn-secondary" id="apex-stewards-my-reports">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                            <polyline points="14 2 14 8 20 8"/>
                                            <line x1="16" y1="13" x2="8" y2="13"/>
                                            <line x1="16" y1="17" x2="8" y2="17"/>
                                        </svg>
                                        My Reports (<span id="apex-stewards-report-count">0</span>/3)
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Statistics Summary -->
                            <div class="apex-stewards-summary" id="apex-stewards-summary">
                                <div class="apex-stewards-stat-card">
                                    <span class="apex-stewards-stat-value" id="apex-stewards-total-incidents">0</span>
                                    <span class="apex-stewards-stat-label">Incidents</span>
                                </div>
                                <div class="apex-stewards-stat-card">
                                    <span class="apex-stewards-stat-value" id="apex-stewards-total-penalties">0</span>
                                    <span class="apex-stewards-stat-label">Penalties</span>
                                </div>
                                <div class="apex-stewards-stat-card">
                                    <span class="apex-stewards-stat-value" id="apex-stewards-total-tracklimits">0</span>
                                    <span class="apex-stewards-stat-label">Track Limit Warnings</span>
                                </div>
                                <div class="apex-stewards-stat-card">
                                    <span class="apex-stewards-stat-value" id="apex-stewards-total-drivers">0</span>
                                    <span class="apex-stewards-stat-label">Drivers</span>
                                </div>
                                <div class="apex-stewards-stat-card apex-stewards-fastest-lap-stat" style="display:none;">
                                    <span class="apex-stewards-stat-value fastest-lap-value" id="apex-stewards-fastest-lap">--</span>
                                    <span class="apex-stewards-stat-label">Fastest Lap</span>
                                    <span class="apex-stewards-stat-driver" id="apex-stewards-fastest-lap-driver"></span>
                                </div>
                            </div>
                            
                            <!-- Tabs -->
                            <div class="apex-stewards-tabs">
                                <button class="apex-stewards-tab active" data-tab="incidents">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"/>
                                        <line x1="12" y1="8" x2="12" y2="12"/>
                                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                                    </svg>
                                    Incidents
                                </button>
                                <button class="apex-stewards-tab" data-tab="penalties">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                    </svg>
                                    Penalties
                                </button>
                                <button class="apex-stewards-tab" data-tab="tracklimits">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                                        <line x1="12" y1="9" x2="12" y2="13"/>
                                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                                    </svg>
                                    Track Limits
                                </button>
                                <button class="apex-stewards-tab" data-tab="classification">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="8" y1="6" x2="21" y2="6"/>
                                        <line x1="8" y1="12" x2="21" y2="12"/>
                                        <line x1="8" y1="18" x2="21" y2="18"/>
                                        <line x1="3" y1="6" x2="3.01" y2="6"/>
                                        <line x1="3" y1="12" x2="3.01" y2="12"/>
                                        <line x1="3" y1="18" x2="3.01" y2="18"/>
                                    </svg>
                                    Classification
                                </button>
                            </div>
                            
                            <!-- Tab Content -->
                            <div class="apex-stewards-tab-content" id="apex-stewards-tab-content">
                                
                                <!-- Incidents Tab -->
                                <div class="apex-stewards-panel active" data-panel="incidents">
                                    <h3>Race Incidents</h3>
                                    <p class="apex-stewards-panel-desc">Contacts and collisions during the race session</p>
                                    <div class="apex-stewards-filter-row">
                                        <label for="apex-stewards-incidents-filter">Filter by Driver:</label>
                                        <select id="apex-stewards-incidents-filter" class="apex-stewards-driver-filter">
                                            <option value="">All Drivers</option>
                                        </select>
                                    </div>
                                    <div class="apex-stewards-table-wrapper">
                                        <table class="apex-stewards-table" id="apex-stewards-incidents-table">
                                            <thead>
                                                <tr>
                                                    <th>Time</th>
                                                    <th>Driver</th>
                                                    <th>Contact With</th>
                                                    <th>Severity</th>
                                                </tr>
                                            </thead>
                                            <tbody id="apex-stewards-incidents-body">
                                            </tbody>
                                        </table>
                                    </div>
                                    <div id="apex-stewards-incidents-pagination"></div>
                                    <p class="apex-stewards-no-data" id="apex-stewards-no-incidents" style="display:none;">No incidents recorded during this session.</p>
                                </div>
                                
                                <!-- Penalties Tab -->
                                <div class="apex-stewards-panel" data-panel="penalties">
                                    <h3>Penalties Issued</h3>
                                    <p class="apex-stewards-panel-desc">Official penalties handed out during the race</p>
                                    <div class="apex-stewards-table-wrapper">
                                        <table class="apex-stewards-table" id="apex-stewards-penalties-table">
                                            <thead>
                                                <tr>
                                                    <th>Time</th>
                                                    <th>Driver</th>
                                                    <th>Penalty</th>
                                                    <th>Reason</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody id="apex-stewards-penalties-body">
                                            </tbody>
                                        </table>
                                    </div>
                                    <p class="apex-stewards-no-data" id="apex-stewards-no-penalties" style="display:none;">No penalties issued during this session.</p>
                                </div>
                                
                                <!-- Track Limits Tab -->
                                <div class="apex-stewards-panel" data-panel="tracklimits">
                                    <h3>Track Limit Violations</h3>
                                    <p class="apex-stewards-panel-desc">Track limit warnings and lap invalidations</p>
                                    <div class="apex-stewards-filter-row">
                                        <label for="apex-stewards-tracklimits-filter">Filter by Driver:</label>
                                        <select id="apex-stewards-tracklimits-filter" class="apex-stewards-driver-filter">
                                            <option value="">All Drivers</option>
                                        </select>
                                    </div>
                                    <div class="apex-stewards-table-wrapper">
                                        <table class="apex-stewards-table" id="apex-stewards-tracklimits-table">
                                            <thead>
                                                <tr>
                                                    <th>Time</th>
                                                    <th>Driver</th>
                                                    <th>Lap</th>
                                                    <th>Points Added</th>
                                                    <th>Total Points</th>
                                                    <th>Decision</th>
                                                </tr>
                                            </thead>
                                            <tbody id="apex-stewards-tracklimits-body">
                                            </tbody>
                                        </table>
                                    </div>
                                    <div id="apex-stewards-tracklimits-pagination"></div>
                                    <p class="apex-stewards-no-data" id="apex-stewards-no-tracklimits" style="display:none;">No track limit violations recorded.</p>
                                </div>
                                
                                <!-- Classification Tab -->
                                <div class="apex-stewards-panel" data-panel="classification">
                                    <h3>Final Classification</h3>
                                    <p class="apex-stewards-panel-desc">Official race results and finishing positions</p>
                                    <div class="apex-stewards-table-wrapper">
                                        <table class="apex-stewards-table" id="apex-stewards-classification-table">
                                            <thead>
                                                <tr>
                                                    <th>Pos</th>
                                                    <th>Driver</th>
                                                    <th>Car</th>
                                                    <th>Class</th>
                                                    <th>Laps</th>
                                                    <th>Best Lap</th>
                                                    <th>Time</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody id="apex-stewards-classification-body">
                                            </tbody>
                                        </table>
                                    </div>
                                    <p class="apex-stewards-no-data" id="apex-stewards-no-classification" style="display:none;">No classification data available.</p>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    
                    <!-- Save Report Modal -->
                    <div class="apex-modal-overlay" id="apex-stewards-save-modal">
                        <div class="apex-modal" style="max-width: 500px; text-align: left;">
                            <button class="apex-modal-close" data-close="apex-stewards-save-modal">&times;</button>
                            <h2>Save Race Report</h2>
                            <div class="apex-form-group" style="margin: 20px 0;">
                                <label for="apex-stewards-save-name" style="display: block; margin-bottom: 8px; color: var(--apex-text-secondary);">Report Name *</label>
                                <input type="text" id="apex-stewards-save-name" placeholder="e.g., League Race Round 5 - Sebring" style="width: 100%; padding: 12px; background: var(--apex-bg); border: 1px solid var(--apex-border); border-radius: 6px; color: white;">
                            </div>
                            <p class="apex-stewards-save-info" style="color: rgba(255,255,255,0.6); font-size: 0.9rem; margin-bottom: 20px;">Save this report to share with others. You can save up to 3 reports.</p>
                            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                                <button class="apex-btn apex-btn-secondary" data-close="apex-stewards-save-modal">Cancel</button>
                                <button class="apex-btn apex-btn-primary" id="apex-stewards-save-confirm">Save Report</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Live Race Page -->
            <div class="apex-page" data-page="live-race">
                <div class="apex-main-content apex-live-race-page">
                    <button class="apex-back-link" data-page="home">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                        Back to Notes
                    </button>
                    
                    <?php if (!is_user_logged_in()) : ?>
                    <div class="apex-login-required">
                        <div class="apex-login-required-icon">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                        </div>
                        <h2>Sign In Required</h2>
                        <p>You need to be signed in to broadcast your races or view active broadcasts.</p>
                        <button class="apex-btn apex-btn-primary apex-open-login">Sign In</button>
                    </div>
                    <?php else : ?>
                    <div class="apex-live-race-header">
                        <div class="apex-live-race-icon">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <circle cx="12" cy="12" r="10"/>
                                <circle cx="12" cy="12" r="3" fill="currentColor"/>
                            </svg>
                        </div>
                        <div>
                            <h1>Live Race Broadcasting</h1>
                            <p class="apex-live-race-subtitle">Share your race in real-time with spectators</p>
                        </div>
                    </div>
                    
                    <!-- Broadcaster Controls -->
                    <div class="apex-broadcast-controls" id="apex-broadcast-controls">
                        <div class="apex-broadcast-status" id="apex-broadcast-status">
                            <!-- Get Started / Not Broadcasting -->
                            <div class="apex-broadcast-inactive" id="apex-broadcast-inactive">
                                <div class="apex-wizard-intro" id="apex-wizard-intro">
                                    <div class="apex-wizard-icon">
                                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><polygon points="10 8 16 12 10 16 10 8" fill="currentColor"/></svg>
                                    </div>
                                    <h3>Ready to go live?</h3>
                                    <p>Share your race with spectators worldwide. We'll walk you through the setup.</p>
                                    <button class="apex-btn apex-btn-primary apex-btn-lg" id="apex-wizard-start">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.5 10c-.83 0-1.5-.67-1.5-1.5v-5c0-.83.67-1.5 1.5-1.5s1.5.67 1.5 1.5v5c0 .83-.67 1.5-1.5 1.5z"/><path d="M20.5 10H19V8.5c0-.83.67-1.5 1.5-1.5s1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/><path d="M9.5 14c.83 0 1.5.67 1.5 1.5v5c0 .83-.67 1.5-1.5 1.5S8 21.33 8 20.5v-5c0-.83.67-1.5 1.5-1.5z"/><path d="M3.5 14H5v1.5c0 .83-.67 1.5-1.5 1.5S2 16.33 2 15.5 2.67 14 3.5 14z"/><path d="M14 14.5c0-.83.67-1.5 1.5-1.5h5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5h-5c-.83 0-1.5-.67-1.5-1.5z"/><path d="M15.5 19H14v1.5c0 .83.67 1.5 1.5 1.5s1.5-.67 1.5-1.5-.67-1.5-1.5-1.5z"/><path d="M10 9.5C10 10.33 9.33 11 8.5 11h-5C2.67 11 2 10.33 2 9.5S2.67 8 3.5 8h5c.83 0 1.5.67 1.5 1.5z"/><path d="M8.5 5H10V3.5C10 2.67 9.33 2 8.5 2S7 2.67 7 3.5 7.67 5 8.5 5z"/></svg>
                                        Get Started
                                    </button>
                                    <button class="apex-btn apex-btn-ghost apex-btn-sm" id="apex-wizard-skip">I've done this before - Quick Start</button>
                                </div>
                                
                                <!-- Step-by-Step Wizard -->
                                <div class="apex-wizard" id="apex-wizard" style="display:none;">
                                    <div class="apex-wizard-progress">
                                        <div class="apex-wizard-step active" data-step="1"><span>1</span> Download</div>
                                        <div class="apex-wizard-step" data-step="2"><span>2</span> Install</div>
                                        <div class="apex-wizard-step" data-step="3"><span>3</span> Configure</div>
                                        <div class="apex-wizard-step" data-step="4"><span>4</span> Stream</div>
                                        <div class="apex-wizard-step" data-step="5"><span>5</span> Go Live</div>
                                    </div>
                                    
                                    <!-- Step 1: Download -->
                                    <div class="apex-wizard-content" data-step="1">
                                        <h4>Step 1: Download the SimHub Plugin</h4>
                                        <p>First, you'll need our SimHub plugin to send telemetry data from Le Mans Ultimate.</p>
                                        <div class="apex-wizard-action">
                                            <a href="#" class="apex-btn apex-btn-primary" id="apex-wizard-download">
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                                Download SimHub Plugin
                                            </a>
                                        </div>
                                        <div class="apex-wizard-note">
                                            <strong>Don't have SimHub?</strong> <a href="https://www.simhubdash.com/" target="_blank">Download it free here</a>
                                        </div>
                                        <div class="apex-wizard-nav">
                                            <button class="apex-btn apex-btn-ghost" id="apex-wizard-back" disabled>Back</button>
                                            <button class="apex-btn apex-btn-primary" id="apex-wizard-next">Downloaded? Next →</button>
                                        </div>
                                    </div>
                                    
                                    <!-- Step 2: Install -->
                                    <div class="apex-wizard-content" data-step="2" style="display:none;">
                                        <h4>Step 2: Install the Plugin</h4>
                                        <ol class="apex-wizard-steps-list">
                                            <li>Extract <code>ApexRaceNotes.dll</code> from the zip</li>
                                            <li>Copy it to your SimHub folder:<br><code>C:\Program Files (x86)\SimHub\</code></li>
                                            <li>Restart SimHub if it's running</li>
                                        </ol>
                                        <div class="apex-wizard-tip">
                                            <strong>💡 Tip:</strong> You can find your SimHub folder by right-clicking the SimHub shortcut → Open file location
                                        </div>
                                        <div class="apex-wizard-nav">
                                            <button class="apex-btn apex-btn-ghost" id="apex-wizard-back">← Back</button>
                                            <button class="apex-btn apex-btn-primary" id="apex-wizard-next">Installed? Next →</button>
                                        </div>
                                    </div>
                                    
                                    <!-- Step 3: Configure -->
                                    <div class="apex-wizard-content" data-step="3" style="display:none;">
                                        <h4>Step 3: Configure SimHub</h4>
                                        <ol class="apex-wizard-steps-list">
                                            <li>Open <strong>SimHub</strong></li>
                                            <li>Click the <strong>⚙️ Settings</strong> icon</li>
                                            <li>Find <strong>"Apex Race Notes"</strong> in the left menu</li>
                                            <li>Enter your Server URL:<br>
                                                <div class="apex-wizard-copybox">
                                                    <code id="apex-wizard-server-url"><?php echo admin_url('admin-ajax.php'); ?></code>
                                                    <button class="apex-btn apex-btn-ghost apex-btn-sm" onclick="ApexNotes.copyText('<?php echo esc_js(admin_url('admin-ajax.php')); ?>')">Copy</button>
                                                </div>
                                            </li>
                                        </ol>
                                        <div class="apex-wizard-nav">
                                            <button class="apex-btn apex-btn-ghost" id="apex-wizard-back">← Back</button>
                                            <button class="apex-btn apex-btn-primary" id="apex-wizard-next">Configured? Next →</button>
                                        </div>
                                    </div>
                                    
                                    <!-- Step 4: Stream Setup (Optional) -->
                                    <div class="apex-wizard-content" data-step="4" style="display:none;">
                                        <h4>Step 4: Add Your Stream (Optional)</h4>
                                        <p>Embed your YouTube or Twitch stream so spectators can watch video alongside telemetry.</p>
                                        
                                        <div class="apex-form-group">
                                            <label>Session Name</label>
                                            <input type="text" id="apex-broadcast-name" placeholder="e.g., 6h Spa Endurance" maxlength="100">
                                        </div>
                                        
                                        <div class="apex-form-group" id="apex-stream-field">
                                            <label>Stream URL <span class="apex-premium-badge" id="apex-stream-premium-badge">Premium</span></label>
                                            <input type="url" id="apex-broadcast-stream" placeholder="https://youtube.com/watch?v=... or https://twitch.tv/..." disabled>
                                            <div class="apex-stream-platforms">
                                                <span class="apex-platform-icon apex-platform-youtube">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M23.5 6.2c-.3-1-1-1.8-2-2.1C19.6 3.5 12 3.5 12 3.5s-7.6 0-9.5.6c-1 .3-1.7 1.1-2 2.1C0 8.1 0 12 0 12s0 3.9.5 5.8c.3 1 1 1.8 2 2.1 1.9.6 9.5.6 9.5.6s7.6 0 9.5-.6c1-.3 1.7-1.1 2-2.1.5-1.9.5-5.8.5-5.8s0-3.9-.5-5.8zM9.5 15.5v-7l6.4 3.5-6.4 3.5z"/></svg>
                                                    YouTube
                                                </span>
                                                <span class="apex-platform-icon apex-platform-twitch">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M11.6 11.1h1.6v4.7h-1.6v-4.7zm4.4 0h1.6v4.7H16v-4.7zM6 0L1.7 4.2v15.6h5.5V24l4.2-4.2h3.4L21.9 12.6V0H6zm14.3 11.8l-3.4 3.4h-3.4L10.1 18.5v-3.4H6.2V1.6h14.1v10.2z"/></svg>
                                                    Twitch
                                                </span>
                                            </div>
                                            <small>Paste your live stream URL. Supports YouTube and Twitch.</small>
                                        </div>
                                        
                                        <div class="apex-wizard-nav">
                                            <button class="apex-btn apex-btn-ghost" id="apex-wizard-back">← Back</button>
                                            <button class="apex-btn apex-btn-primary" id="apex-wizard-next">Next →</button>
                                        </div>
                                    </div>
                                    
                                    <!-- Step 5: Go Live -->
                                    <div class="apex-wizard-content" data-step="5" style="display:none;">
                                        <h4>Step 5: Start Broadcasting!</h4>
                                        <p>You're all set! Click the button below to generate your broadcast key and go live.</p>
                                        
                                        <div class="apex-wizard-checklist">
                                            <label><input type="checkbox" checked disabled> SimHub plugin installed</label>
                                            <label><input type="checkbox" checked disabled> Server URL configured</label>
                                            <label><input type="checkbox" id="apex-wizard-check-simhub"> SimHub is running</label>
                                            <label><input type="checkbox" id="apex-wizard-check-enabled"> Plugin enabled in SimHub</label>
                                        </div>
                                        
                                        <button class="apex-btn apex-btn-primary apex-btn-lg apex-btn-glow" id="apex-start-broadcast">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="3" fill="currentColor"/></svg>
                                            Go Live!
                                        </button>
                                        
                                        <div class="apex-wizard-nav">
                                            <button class="apex-btn apex-btn-ghost" id="apex-wizard-back">← Back</button>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Quick Start Form (for experienced users) -->
                                <div class="apex-broadcast-quickstart" id="apex-broadcast-quickstart" style="display:none;">
                                    <h3>Quick Start</h3>
                                    <div class="apex-broadcast-form">
                                        <div class="apex-form-group">
                                            <label>Session Name (optional)</label>
                                            <input type="text" id="apex-broadcast-name-quick" placeholder="e.g., 6h Spa Practice" maxlength="100">
                                        </div>
                                        
                                        <div class="apex-form-group" id="apex-stream-field-quick">
                                            <label>Stream URL <span class="apex-premium-badge">Premium</span></label>
                                            <input type="url" id="apex-broadcast-stream-quick" placeholder="YouTube or Twitch URL..." disabled>
                                        </div>
                                        
                                        <button class="apex-btn apex-btn-primary apex-btn-lg" id="apex-start-broadcast-quick">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="3" fill="currentColor"/></svg>
                                            Start Broadcast
                                        </button>
                                        <button class="apex-btn apex-btn-ghost apex-btn-sm" id="apex-wizard-show">Show setup guide</button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Broadcasting Active State -->
                            <div class="apex-broadcast-active" id="apex-broadcast-active" style="display:none;">
                                <div class="apex-broadcast-live-badge">
                                    <span class="apex-live-dot"></span> BROADCASTING LIVE
                                </div>
                                
                                <div class="apex-broadcast-info">
                                    <div class="apex-broadcast-info-item">
                                        <label>Your Broadcast Key</label>
                                        <div class="apex-broadcast-key-display">
                                            <code id="apex-broadcast-key">--</code>
                                            <button class="apex-btn apex-btn-ghost apex-btn-sm" id="apex-copy-key">Copy</button>
                                        </div>
                                        <small>Paste this in SimHub → Apex Race Notes → Broadcast Key</small>
                                    </div>
                                    
                                    <div class="apex-broadcast-info-item">
                                        <label>Spectator Link</label>
                                        <div class="apex-broadcast-key-display">
                                            <code id="apex-spectator-link">--</code>
                                            <button class="apex-btn apex-btn-ghost apex-btn-sm" id="apex-copy-link">Copy</button>
                                        </div>
                                        <small>Share this link with viewers</small>
                                    </div>
                                    
                                    <div class="apex-broadcast-info-item">
                                        <label>Update Stream URL</label>
                                        <div class="apex-broadcast-key-display">
                                            <input type="url" id="apex-broadcast-stream-update" placeholder="YouTube or Twitch URL...">
                                            <button class="apex-btn apex-btn-ghost apex-btn-sm" id="apex-update-stream">Update</button>
                                        </div>
                                    </div>
                                </div>
                                
                                <button class="apex-btn apex-btn-ghost" id="apex-end-broadcast">End Broadcast</button>
                            </div>
                        </div>
                        
                        <!-- Affiliate Links Section -->
                        <div class="apex-broadcast-affiliate" id="apex-broadcast-affiliate">
                            <div class="apex-affiliate-header">
                                <h4>💰 Monetize Your Broadcast</h4>
                                <p>Add affiliate links to display on your broadcast page. Earn commissions when viewers purchase.</p>
                            </div>
                            
                            <div class="apex-affiliate-links" id="apex-affiliate-links">
                                <!-- Existing links loaded here -->
                            </div>
                            
                            <div class="apex-affiliate-add">
                                <div class="apex-affiliate-form">
                                    <select id="apex-affiliate-type">
                                        <option value="">Select type...</option>
                                        <option value="wheel">Racing Wheel</option>
                                        <option value="pedals">Pedals</option>
                                        <option value="rig">Sim Rig/Cockpit</option>
                                        <option value="monitor">Monitor/Display</option>
                                        <option value="vr">VR Headset</option>
                                        <option value="pc">PC/Hardware</option>
                                        <option value="game">Game/DLC</option>
                                        <option value="merch">Merchandise</option>
                                        <option value="other">Other</option>
                                    </select>
                                    <input type="text" id="apex-affiliate-label" placeholder="Product name (e.g., Fanatec DD Pro)">
                                    <input type="url" id="apex-affiliate-url" placeholder="Your affiliate link...">
                                    <button class="apex-btn apex-btn-primary apex-btn-sm" id="apex-affiliate-add-btn">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                        Add
                                    </button>
                                </div>
                                <small>Supports Amazon, Fanatec, or any affiliate program. Links display on your spectator page.</small>
                            </div>
                            
                            <div class="apex-affiliate-preview" id="apex-affiliate-preview" style="display:none;">
                                <h5>Preview (how spectators see it)</h5>
                                <div class="apex-affiliate-preview-links"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Active Broadcasts List -->
                    <div class="apex-active-broadcasts">
                        <h3>Active Broadcasts</h3>
                        <div class="apex-broadcasts-grid" id="apex-broadcasts-grid">
                            <div class="apex-loading"><div class="apex-spinner"></div></div>
                        </div>
                        <div class="apex-broadcasts-empty" id="apex-broadcasts-empty" style="display:none;">
                            <p>No active broadcasts right now. Be the first to go live!</p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Spectator View Page (loaded when ?broadcast=KEY is in URL) -->
            <div class="apex-page" data-page="spectator">
                <div class="apex-spectator-view" id="apex-spectator-view">
                    <!-- Top Bar -->
                    <div class="apex-spectator-topbar" id="apex-spectator-topbar">
                        <div class="apex-spectator-live-badge">
                            <span class="apex-live-dot"></span> LIVE
                        </div>
                        <div class="apex-spectator-track" id="apex-spectator-track">--</div>
                        <div class="apex-spectator-session-time" id="apex-spectator-session-time">0:00:00</div>
                        <div class="apex-spectator-lap">
                            Lap <span id="apex-spectator-current-lap">0</span> of <span id="apex-spectator-total-laps">0</span>
                        </div>
                        <div class="apex-spectator-broadcaster">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            <span id="apex-spectator-broadcaster-name">--</span>
                        </div>
                    </div>
                    
                    <!-- Main Content Grid -->
                    <div class="apex-spectator-grid">
                        <!-- Left Panel: Timing Tower -->
                        <div class="apex-spectator-left">
                            <div class="apex-timing-tower-header">
                                <h3>Positions</h3>
                                <div class="apex-timing-toggle">
                                    <button class="apex-btn apex-btn-sm active" id="apex-timing-nearby">±5</button>
                                    <button class="apex-btn apex-btn-sm" id="apex-timing-full">Full</button>
                                </div>
                            </div>
                            <div class="apex-timing-tower" id="apex-timing-tower">
                                <!-- Timing rows populated by JS -->
                            </div>
                            
                            <div class="apex-driver-stats">
                                <h4>Driver Stats</h4>
                                <div class="apex-driver-stats-grid">
                                    <div class="apex-driver-stat">
                                        <label>Last Lap</label>
                                        <span id="apex-stat-last-lap">--:--.---</span>
                                    </div>
                                    <div class="apex-driver-stat">
                                        <label>Best Lap</label>
                                        <span id="apex-stat-best-lap">--:--.---</span>
                                    </div>
                                    <div class="apex-driver-stat">
                                        <label>Gap Ahead</label>
                                        <span id="apex-stat-gap-ahead">--</span>
                                    </div>
                                    <div class="apex-driver-stat">
                                        <label>Gap Behind</label>
                                        <span id="apex-stat-gap-behind">--</span>
                                    </div>
                                    <div class="apex-driver-stat apex-stat-fuel">
                                        <label>Fuel</label>
                                        <span id="apex-stat-fuel">-- L</span>
                                        <small id="apex-stat-fuel-laps">-- laps</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Panel: Video + Flags + Tires -->
                        <div class="apex-spectator-right">
                            <!-- Live Feed / YouTube Embed -->
                            <div class="apex-spectator-video" id="apex-spectator-video">
                                <div class="apex-video-placeholder" id="apex-video-placeholder">
                                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                                    <p>No video stream available</p>
                                </div>
                                <iframe id="apex-spectator-iframe" style="display:none;" 
                                    src="" 
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen>
                                </iframe>
                            </div>
                            
                            <!-- Bottom Row: Flags and Tires -->
                            <div class="apex-spectator-bottom">
                                <!-- Flags Panel -->
                                <div class="apex-spectator-flags" id="apex-spectator-flags">
                                    <div class="apex-flag-display apex-flag-green" id="apex-flag-display">
                                        <span class="apex-flag-text">GREEN</span>
                                    </div>
                                    <div class="apex-penalties" id="apex-penalties" style="display:none;">
                                        <span class="apex-penalty-icon">⚠️</span>
                                        <span id="apex-penalty-text">--</span>
                                    </div>
                                </div>
                                
                                <!-- Tire Data Panel -->
                                <div class="apex-spectator-tires">
                                    <h4>Tire Wear</h4>
                                    <div class="apex-tire-diagram">
                                        <div class="apex-tire apex-tire-fl">
                                            <span class="apex-tire-label">FL</span>
                                            <span class="apex-tire-wear" id="apex-tire-fl-wear">100%</span>
                                            <span class="apex-tire-temp" id="apex-tire-fl-temp">--°C</span>
                                        </div>
                                        <div class="apex-tire apex-tire-fr">
                                            <span class="apex-tire-label">FR</span>
                                            <span class="apex-tire-wear" id="apex-tire-fr-wear">100%</span>
                                            <span class="apex-tire-temp" id="apex-tire-fr-temp">--°C</span>
                                        </div>
                                        <div class="apex-tire apex-tire-rl">
                                            <span class="apex-tire-label">RL</span>
                                            <span class="apex-tire-wear" id="apex-tire-rl-wear">100%</span>
                                            <span class="apex-tire-temp" id="apex-tire-rl-temp">--°C</span>
                                        </div>
                                        <div class="apex-tire apex-tire-rr">
                                            <span class="apex-tire-label">RR</span>
                                            <span class="apex-tire-wear" id="apex-tire-rr-wear">100%</span>
                                            <span class="apex-tire-temp" id="apex-tire-rr-temp">--°C</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Affiliate Links Bar (for spectators) -->
                    <div class="apex-spectator-affiliates" id="apex-spectator-affiliates" style="display:none;">
                        <div class="apex-affiliates-label">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                            Broadcaster's Gear:
                        </div>
                        <div class="apex-affiliates-links" id="apex-affiliates-links">
                            <!-- Populated by JS -->
                        </div>
                    </div>
                    
                    <!-- Broadcast Ended Overlay -->
                    <div class="apex-broadcast-ended" id="apex-broadcast-ended" style="display:none;">
                        <div class="apex-broadcast-ended-content">
                            <h2>Broadcast Ended</h2>
                            <p>This broadcast has finished. Thanks for watching!</p>
                            <button class="apex-btn apex-btn-primary" data-page="live-race">View Other Broadcasts</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Rating Modal -->
            <div class="apex-modal-overlay" id="apex-rating-modal">
                <div class="apex-modal">
                    <button class="apex-modal-close">&times;</button>
                    <h2>Rate this guide</h2>
                    <p>How helpful did you find these track notes?</p>
                    <div class="apex-rating-stars" id="apex-rating-stars"></div>
                    <button class="apex-btn apex-btn-ghost apex-modal-close">Cancel</button>
                </div>
            </div>
            
            <!-- YouTube Player Modal -->
            <div class="apex-video-modal-overlay" id="apex-video-modal">
                <div class="apex-video-modal">
                    <button class="apex-video-modal-close" id="apex-video-close">&times;</button>
                    <div class="apex-video-header">
                        <span class="apex-video-live-badge">🔴 LIVE</span>
                        <h3 class="apex-video-title" id="apex-video-title">Loading...</h3>
                    </div>
                    <div class="apex-video-container">
                        <iframe id="apex-video-iframe" 
                            src="" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                            allowfullscreen>
                        </iframe>
                    </div>
                    <div class="apex-video-footer">
                        <span class="apex-video-channel" id="apex-video-channel"></span>
                        <a href="#" target="_blank" class="apex-video-external" id="apex-video-external">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                            Open on YouTube
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Notification -->
            <div class="apex-notification" id="apex-notification"></div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Render the Stewards Room shortcode
     */
    public function render_stewards_shortcode($atts) {
        ob_start();
        ?>
        <div id="apex-stewards-app" class="apex-stewards-wrapper">
            
            <!-- Header -->
            <div class="apex-stewards-header">
                <div class="apex-stewards-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                        <polyline points="10 9 9 9 8 9"/>
                    </svg>
                </div>
                <div class="apex-stewards-header-text">
                    <h1>Stewards Room</h1>
                    <p>Race incident reports and official race documents</p>
                </div>
            </div>
            
            <!-- Upload Section -->
            <div class="apex-stewards-upload-section" id="apex-stewards-upload-section">
                <div class="apex-stewards-upload-box">
                    <h2>Generate Race Report</h2>
                    <p>Upload your race XML file to generate a detailed stewards report including incidents, penalties, and track limits.</p>
                    
                    <div class="apex-stewards-dropzone" id="apex-stewards-dropzone">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="17 8 12 3 7 8"/>
                            <line x1="12" y1="3" x2="12" y2="15"/>
                        </svg>
                        <p>Drop your race XML file here or click to browse</p>
                        <span class="apex-stewards-hint">Files ending in -R1.xml, -R2.xml (Race sessions)</span>
                        <input type="file" id="apex-stewards-file-input" accept=".xml" style="display:none;">
                    </div>
                    
                    <div class="apex-stewards-meta-fields" id="apex-stewards-meta-fields" style="display:none;">
                        <h3>Race Details (Optional)</h3>
                        <div class="apex-stewards-field-row">
                            <div class="apex-stewards-field">
                                <label for="apex-stewards-race-name">Race Name</label>
                                <input type="text" id="apex-stewards-race-name" placeholder="e.g., 6 Hours of Spa">
                            </div>
                            <div class="apex-stewards-field">
                                <label for="apex-stewards-league-name">League Name</label>
                                <input type="text" id="apex-stewards-league-name" placeholder="e.g., Sunday Night Endurance">
                            </div>
                            <div class="apex-stewards-field">
                                <label for="apex-stewards-round">Round Number</label>
                                <input type="text" id="apex-stewards-round" placeholder="e.g., Round 5">
                            </div>
                        </div>
                        <button class="apex-btn apex-btn-primary" id="apex-stewards-generate-btn">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                            </svg>
                            Generate Race Report
                        </button>
                    </div>
                    
                    <div class="apex-stewards-loading" id="apex-stewards-loading" style="display:none;">
                        <div class="apex-spinner"></div>
                        <p>Processing race data...</p>
                    </div>
                </div>
            </div>
            
            <!-- Results Section -->
            <div class="apex-stewards-results" id="apex-stewards-results" style="display:none;">
                
                <!-- Race Info Header -->
                <div class="apex-stewards-race-info" id="apex-stewards-race-info">
                    <div class="apex-stewards-race-info-main">
                        <img src="<?php echo APEX_NOTES_PLUGIN_URL; ?>assets/images/logo.png" alt="Apex Race Notes" class="apex-stewards-logo">
                        <div class="apex-stewards-race-details">
                            <h2 id="apex-stewards-race-title">Race Report</h2>
                            <div class="apex-stewards-race-meta">
                                <span id="apex-stewards-track-name"></span>
                                <span class="apex-stewards-separator">•</span>
                                <span id="apex-stewards-event-name"></span>
                                <span class="apex-stewards-separator">•</span>
                                <span id="apex-stewards-race-date"></span>
                            </div>
                            <div class="apex-stewards-race-custom" id="apex-stewards-race-custom"></div>
                        </div>
                    </div>
                    <div class="apex-stewards-actions">
                        <button class="apex-btn apex-btn-secondary" id="apex-stewards-new-report">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            New Report
                        </button>
                        <div class="apex-btn-dropdown">
                            <button class="apex-btn apex-btn-primary" id="apex-stewards-download-pdf">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                    <polyline points="7 10 12 15 17 10"/>
                                    <line x1="12" y1="15" x2="12" y2="3"/>
                                </svg>
                                Download PDF
                            </button>
                            <div class="apex-pdf-options">
                                <label class="apex-checkbox-label">
                                    <input type="checkbox" id="apex-pdf-summary-mode">
                                    <span>Summary by Driver (recommended for 50+ incidents)</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Statistics Summary -->
                <div class="apex-stewards-summary" id="apex-stewards-summary">
                    <div class="apex-stewards-stat-card">
                        <span class="apex-stewards-stat-value" id="apex-stewards-total-incidents">0</span>
                        <span class="apex-stewards-stat-label">Incidents</span>
                    </div>
                    <div class="apex-stewards-stat-card">
                        <span class="apex-stewards-stat-value" id="apex-stewards-total-penalties">0</span>
                        <span class="apex-stewards-stat-label">Penalties</span>
                    </div>
                    <div class="apex-stewards-stat-card">
                        <span class="apex-stewards-stat-value" id="apex-stewards-total-tracklimits">0</span>
                        <span class="apex-stewards-stat-label">Track Limit Warnings</span>
                    </div>
                    <div class="apex-stewards-stat-card">
                        <span class="apex-stewards-stat-value" id="apex-stewards-total-drivers">0</span>
                        <span class="apex-stewards-stat-label">Drivers</span>
                    </div>
                    <div class="apex-stewards-stat-card apex-stewards-fastest-lap-stat" style="display:none;">
                        <span class="apex-stewards-stat-value fastest-lap-value" id="apex-stewards-fastest-lap">--</span>
                        <span class="apex-stewards-stat-label">Fastest Lap</span>
                        <span class="apex-stewards-stat-driver" id="apex-stewards-fastest-lap-driver"></span>
                    </div>
                </div>
                
                <!-- Tabs -->
                <div class="apex-stewards-tabs">
                    <button class="apex-stewards-tab active" data-tab="incidents">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"/>
                            <line x1="12" y1="8" x2="12" y2="12"/>
                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                        Incidents
                    </button>
                    <button class="apex-stewards-tab" data-tab="penalties">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                        Penalties
                    </button>
                    <button class="apex-stewards-tab" data-tab="tracklimits">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                            <line x1="12" y1="9" x2="12" y2="13"/>
                            <line x1="12" y1="17" x2="12.01" y2="17"/>
                        </svg>
                        Track Limits
                    </button>
                    <button class="apex-stewards-tab" data-tab="classification">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5C5.71 4 7 5.5 7 7v10a2 2 0 0 0 2 2h8"/>
                            <path d="M18 15v6"/>
                            <path d="M14 11h4"/>
                            <path d="M14 15h4"/>
                            <path d="M14 19h4"/>
                        </svg>
                        Classification
                    </button>
                </div>
                
                <!-- Tab Content -->
                <div class="apex-stewards-tab-content" id="apex-stewards-tab-content">
                    
                    <!-- Incidents Tab -->
                    <div class="apex-stewards-panel active" data-panel="incidents">
                        <h3>Race Incidents</h3>
                        <p class="apex-stewards-panel-desc">Contacts and collisions during the race session</p>
                        <div class="apex-stewards-filter-row">
                            <label for="apex-stewards-incidents-filter">Filter by Driver:</label>
                            <select id="apex-stewards-incidents-filter" class="apex-stewards-driver-filter">
                                <option value="">All Drivers</option>
                            </select>
                        </div>
                        <div class="apex-stewards-table-wrapper">
                            <table class="apex-stewards-table" id="apex-stewards-incidents-table">
                                <thead>
                                    <tr>
                                        <th>Time</th>
                                        <th>Driver</th>
                                        <th>Contact With</th>
                                        <th>Severity</th>
                                    </tr>
                                </thead>
                                <tbody id="apex-stewards-incidents-body">
                                </tbody>
                            </table>
                        </div>
                        <div id="apex-stewards-incidents-pagination"></div>
                        <p class="apex-stewards-no-data" id="apex-stewards-no-incidents" style="display:none;">No incidents recorded during this session.</p>
                    </div>
                    
                    <!-- Penalties Tab -->
                    <div class="apex-stewards-panel" data-panel="penalties">
                        <h3>Penalties Issued</h3>
                        <p class="apex-stewards-panel-desc">Official penalties handed out during the race</p>
                        <div class="apex-stewards-table-wrapper">
                            <table class="apex-stewards-table" id="apex-stewards-penalties-table">
                                <thead>
                                    <tr>
                                        <th>Time</th>
                                        <th>Driver</th>
                                        <th>Penalty</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="apex-stewards-penalties-body">
                                </tbody>
                            </table>
                        </div>
                        <p class="apex-stewards-no-data" id="apex-stewards-no-penalties" style="display:none;">No penalties issued during this session.</p>
                    </div>
                    
                    <!-- Track Limits Tab -->
                    <div class="apex-stewards-panel" data-panel="tracklimits">
                        <h3>Track Limit Violations</h3>
                        <p class="apex-stewards-panel-desc">Track limit warnings and lap invalidations</p>
                        <div class="apex-stewards-filter-row">
                            <label for="apex-stewards-tracklimits-filter">Filter by Driver:</label>
                            <select id="apex-stewards-tracklimits-filter" class="apex-stewards-driver-filter">
                                <option value="">All Drivers</option>
                            </select>
                        </div>
                        <div class="apex-stewards-table-wrapper">
                            <table class="apex-stewards-table" id="apex-stewards-tracklimits-table">
                                <thead>
                                    <tr>
                                        <th>Time</th>
                                        <th>Driver</th>
                                        <th>Lap</th>
                                        <th>Points Added</th>
                                        <th>Total Points</th>
                                        <th>Decision</th>
                                    </tr>
                                </thead>
                                <tbody id="apex-stewards-tracklimits-body">
                                </tbody>
                            </table>
                        </div>
                        <div id="apex-stewards-tracklimits-pagination"></div>
                        <p class="apex-stewards-no-data" id="apex-stewards-no-tracklimits" style="display:none;">No track limit violations recorded.</p>
                    </div>
                    
                    <!-- Classification Tab -->
                    <div class="apex-stewards-panel" data-panel="classification">
                        <h3>Final Classification</h3>
                        <p class="apex-stewards-panel-desc">Official race results and finishing positions</p>
                        <div class="apex-stewards-table-wrapper">
                            <table class="apex-stewards-table" id="apex-stewards-classification-table">
                                <thead>
                                    <tr>
                                        <th>Pos</th>
                                        <th>Driver</th>
                                        <th>Car</th>
                                        <th>Class</th>
                                        <th>Laps</th>
                                        <th>Best Lap</th>
                                        <th>Time</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="apex-stewards-classification-body">
                                </tbody>
                            </table>
                        </div>
                        <p class="apex-stewards-no-data" id="apex-stewards-no-classification" style="display:none;">No classification data available.</p>
                    </div>
                    
                </div>
            </div>
            
        </div>
        <?php
        return ob_get_clean();
    }
}
