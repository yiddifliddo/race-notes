/**
 * Apex Notes - Le Mans Ultimate Track Notes
 */

(function($) {
    'use strict';

    var state = {
        page: 'home',
        notes: [],
        currentNote: null,
        currentSort: 'created_at',
        sections: [],
        filters: {
            search: '',
            track: '',
            car_class: '',
            car_id: '',
            difficulty: ''
        }
    };
    
    // Stewards room state
    var stewardsInitialized = false;
    var stewardsData = null;
    var stewardsFile = null;
    
    // Pagination state
    var incidentsPerPage = 50;
    var currentIncidentPage = 1;
    var allIncidents = [];
    var trackLimitsPerPage = 50;
    var currentTrackLimitPage = 1;
    var allTrackLimits = [];
    
    // Track Video Guides Data - Real LMU videos organized by track
    var trackVideos = {
        'bahrain': {
            name: 'Bahrain International Circuit',
            description: 'Modern desert circuit known for its floodlit night racing and technical sectors.',
            videos: [
                { id: 'AraLTQUkhbc', title: 'Bahrain Track Guide - Le Mans Ultimate', author: 'LMU Community', car: 'Hypercar' },
                { id: 'vO99xYEohDY', title: 'Bahrain Circuit Guide - Night Racing', author: 'LMU Community', car: 'Hypercar' },
                { id: 'L6rRKJF0C54', title: 'Bahrain Lap Guide - LMU', author: 'LMU Community', car: 'Prototype' }
            ]
        },
        'le-mans': {
            name: 'Circuit de la Sarthe (Le Mans)',
            description: 'The legendary 13.6km circuit featuring the iconic Mulsanne Straight and Porsche Curves.',
            videos: [
                { id: 'AJJIhLmfc-o', title: 'Le Mans Track Guide - Complete Breakdown', author: 'LMU Community', car: 'Hypercar' },
                { id: 'dDTjIlmR_O8', title: 'Le Mans Circuit Guide - Full Lap', author: 'LMU Community', car: 'Prototype' },
                { id: 'Upu1stguqdE', title: 'Le Mans Lap Guide - LMU', author: 'LMU Community', car: 'Hypercar' }
            ]
        },
        'paul-ricard': {
            name: 'Circuit Paul Ricard',
            description: 'Technical French circuit with distinctive blue and red runoff areas and long Mistral straight.',
            videos: [
                { id: 'm0m2pr0-F0c', title: 'Paul Ricard Track Guide - LMU', author: 'LMU Community', car: 'Hypercar' },
                { id: 'baJ_PSrNfRQ', title: 'Paul Ricard Circuit Guide', author: 'LMU Community', car: 'LMGT3' },
                { id: 'IcgShAI_77E', title: 'Paul Ricard Lap Guide', author: 'LMU Community', car: 'Prototype' }
            ]
        },
        'cota': {
            name: 'Circuit of the Americas',
            description: 'Modern American circuit featuring the dramatic Turn 1 climb and technical sector 2.',
            videos: [
                { id: 'zulxpePMmks', title: 'COTA Track Guide - Le Mans Ultimate', author: 'LMU Community', car: 'Hypercar' },
                { id: 'oTKbMvH6hZ0', title: 'COTA Circuit Guide - Full Lap', author: 'LMU Community', car: 'Prototype' },
                { id: 'WY0RYBKw5PM', title: 'COTA Lap Guide - LMU', author: 'LMU Community', car: 'Hypercar' }
            ]
        },
        'fuji': {
            name: 'Fuji Speedway',
            description: 'Technical Japanese circuit with one of the longest straights in motorsport.',
            videos: [
                { id: 'fGUPPqRfEu0', title: 'Fuji Track Guide - Le Mans Ultimate', author: 'LMU Community', car: 'Hypercar' },
                { id: 'pn9J_pbbKCs', title: 'Fuji Speedway Circuit Guide', author: 'LMU Community', car: 'LMP2' },
                { id: 'PqixpoIVt7Y', title: 'Fuji Lap Guide - LMU', author: 'LMU Community', car: 'Prototype' }
            ]
        },
        'imola': {
            name: 'Autodromo Enzo e Dino Ferrari (Imola)',
            description: 'Classic Italian circuit with challenging high-speed sections and technical chicanes.',
            videos: [
                { id: 'Jg7VdEi-ziI', title: 'Imola Track Guide - Le Mans Ultimate', author: 'LMU Community', car: 'Hypercar' },
                { id: 'Bn5AK9DZezA', title: 'Imola Circuit Guide - Full Lap', author: 'LMU Community', car: 'LMGT3' },
                { id: 'akkQXQWeWMI', title: 'Imola Lap Guide - LMU', author: 'LMU Community', car: 'Prototype' }
            ]
        },
        'interlagos': {
            name: 'Autódromo José Carlos Pace (Interlagos)',
            description: 'Legendary Brazilian anti-clockwise circuit with unpredictable weather.',
            videos: [
                { id: 'c4YINY4C2rg', title: 'Interlagos Track Guide - Le Mans Ultimate', author: 'LMU Community', car: 'Hypercar' },
                { id: 'jWdALE9mrHE', title: 'Interlagos Circuit Guide', author: 'LMU Community', car: 'Prototype' },
                { id: 'iAgFJhpjxdo', title: 'Interlagos Lap Guide - LMU', author: 'LMU Community', car: 'LMGT3' }
            ]
        },
        'qatar': {
            name: 'Lusail International Circuit',
            description: 'Fast flowing desert circuit under the lights with long straights.',
            videos: [
                { id: 'epSNGm_rx2M', title: 'Qatar Lusail Track Guide - LMU', author: 'LMU Community', car: 'Hypercar' },
                { id: 'mRKuGff3Ux8', title: 'Lusail Circuit Guide - Night Racing', author: 'LMU Community', car: 'Prototype' },
                { id: 'nuZmnQrGKyw', title: 'Qatar Lap Guide - Le Mans Ultimate', author: 'LMU Community', car: 'Hypercar' }
            ]
        },
        'monza': {
            name: 'Autodromo Nazionale Monza',
            description: 'The Temple of Speed - fast straights and iconic chicanes.',
            videos: [
                { id: 'y8pbsKKa1DQ', title: 'Monza Track Guide - Le Mans Ultimate', author: 'LMU Community', car: 'Hypercar' },
                { id: 'zYZXbkjRqho', title: 'Monza Circuit Guide - Full Lap', author: 'LMU Community', car: 'LMGT3' },
                { id: 'EozaTbLB0HE', title: 'Monza Lap Guide - LMU', author: 'LMU Community', car: 'Prototype' }
            ]
        },
        'portimao': {
            name: 'Algarve International Circuit (Portimão)',
            description: 'Rollercoaster circuit with dramatic elevation changes and blind crests.',
            videos: [
                { id: 'uchDycmDj-g', title: 'Portimão Track Guide - Le Mans Ultimate', author: 'LMU Community', car: 'Hypercar' },
                { id: 'iEPx9myf7bA', title: 'Portimão Circuit Guide - Full Lap', author: 'LMU Community', car: 'GTE' },
                { id: 'babsqNj9iZU', title: 'Portimão Lap Guide - LMU', author: 'LMU Community', car: 'Prototype' }
            ]
        },
        'sebring': {
            name: 'Sebring International Raceway',
            description: 'Americas oldest road racing track - bumpy, challenging, and historic.',
            videos: [
                { id: 'Cx0QqEjSh9Y', title: 'Sebring Track Guide - Le Mans Ultimate', author: 'LMU Community', car: 'Hypercar' },
                { id: 'Zpli5bGVtVg', title: 'Sebring Circuit Guide - Full Lap', author: 'LMU Community', car: 'LMP2' },
                { id: 'COVMEogF0gw', title: 'Sebring Lap Guide - LMU', author: 'LMU Community', car: 'Prototype' }
            ]
        },
        'silverstone': {
            name: 'Silverstone Circuit',
            description: 'The home of British motorsport - fast, flowing, and demanding.',
            videos: [
                { id: 'IRMOReZJIUU', title: 'Silverstone Track Guide - Le Mans Ultimate', author: 'LMU Community', car: 'Hypercar' },
                { id: 'PGRrPGJ7bq0', title: 'Silverstone Circuit Guide - Full Lap', author: 'LMU Community', car: 'LMGT3' },
                { id: 'R9f6V5I3NWY', title: 'Silverstone Lap Guide - LMU', author: 'LMU Community', car: 'Prototype' }
            ]
        },
        'spa': {
            name: 'Spa-Francorchamps',
            description: 'Master the legendary Belgian circuit with its iconic Eau Rouge and challenging weather.',
            videos: [
                { id: 'Yj8gV2URJxE', title: 'Spa Track Guide - Le Mans Ultimate', author: 'LMU Community', car: 'Hypercar' },
                { id: 'z_JX3vYkU1c', title: 'Spa Circuit Guide - Full Lap', author: 'LMU Community', car: 'LMGT3' },
                { id: 'OVoPvwdgJGY', title: 'Spa IN-DEPTH Hypercar Guide - Setup & Sectors', author: 'LMU Community', car: 'Hypercar' }
            ]
        }
    };

    // Initialize
    $(document).ready(function() {
        // Enable fullscreen mode - hide WordPress/theme elements
        if ($('.apex-notes-wrapper.apex-fullscreen').length) {
            $('body').addClass('apex-notes-active');
            $('html').addClass('apex-notes-active');
            
            // Add admin class if user is admin
            if (apexNotesData.canModerate) {
                $('body').addClass('apex-is-admin');
                $('html').addClass('apex-is-admin');
            }
        }
        
        initFilters();
        initTrackGuide();
        bindEvents();
        updateAuthUI();
        
        // Initialize page based on URL
        initFromUrl();
    });
    
    // Initialize Track Guide dropdown
    function initTrackGuide() {
        var $dropdown = $('#apex-track-select');
        if (!$dropdown.length) return;
        
        // Populate dropdown with tracks that have videos
        $.each(trackVideos, function(trackId, trackData) {
            $dropdown.append('<option value="' + trackId + '">' + trackData.name + '</option>');
        });
        
        // Handle track selection
        $dropdown.on('change', function() {
            var trackId = $(this).val();
            if (trackId) {
                renderTrackVideos(trackId);
            } else {
                // Show intro
                $('#apex-track-videos-content').html(
                    '<div class="apex-track-guide-intro">' +
                    '<div class="apex-intro-card">' +
                    '<svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>' +
                    '<h3>Select a Track to Begin</h3>' +
                    '<p>Choose a circuit from the dropdown above to view video tutorials, lap guides, and setup advice from the Le Mans Ultimate community.</p>' +
                    '</div>' +
                    '</div>'
                );
            }
        });
    }
    
    // Render videos for selected track
    function renderTrackVideos(trackId) {
        var track = trackVideos[trackId];
        if (!track) return;
        
        var html = '<div class="apex-track-videos-header">';
        html += '<h2>' + escapeHtml(track.name) + '</h2>';
        html += '<p>' + escapeHtml(track.description) + '</p>';
        html += '<span class="apex-video-count">' + track.videos.length + ' video' + (track.videos.length !== 1 ? 's' : '') + ' available</span>';
        html += '</div>';
        
        html += '<div class="apex-videos-grid">';
        
        $.each(track.videos, function(i, video) {
            html += '<div class="apex-video-card">';
            html += '<div class="apex-video-thumbnail" data-video-id="' + video.id + '">';
            html += '<img src="https://img.youtube.com/vi/' + video.id + '/maxresdefault.jpg" alt="' + escapeHtml(video.title) + '" onerror="this.src=\'https://img.youtube.com/vi/' + video.id + '/hqdefault.jpg\'">';
            html += '<div class="apex-video-play"><svg viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg></div>';
            html += '</div>';
            html += '<div class="apex-video-info">';
            html += '<h4 class="apex-video-title">' + escapeHtml(video.title) + '</h4>';
            html += '<div class="apex-video-meta">';
            html += '<span class="apex-video-author"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>' + escapeHtml(video.author) + '</span>';
            html += '<span class="apex-video-car"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="10" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>' + escapeHtml(video.car) + '</span>';
            html += '</div>';
            html += '</div>';
            html += '</div>';
        });
        
        html += '</div>';
        
        $('#apex-track-videos-content').html(html);
    }

    // Initialize filter dropdowns
    function initFilters() {
        // Only run once - check if already initialized
        if ($('#apex-note-track').data('initialized')) {
            return;
        }
        $('#apex-note-track').data('initialized', true);
        
        // Tracks - clear and populate
        var $trackFilter = $('#apex-filter-track');
        var $trackForm = $('#apex-note-track');
        
        // Clear existing options first
        $trackFilter.empty();
        $trackForm.empty();
        
        // Form dropdown gets a placeholder (no disabled - causes issues in some browsers)
        $trackForm.append('<option value="">-- Select Track --</option>');
        
        $.each(apexNotesData.tracks, function(i, track) {
            var label = track.name + (track.dlc ? ' (DLC)' : '');
            $trackFilter.append('<option value="' + track.id + '">' + label + '</option>');
            $trackForm.append('<option value="' + track.id + '">' + label + '</option>');
        });

        // Car classes - populate form dropdown only (filter is now a reset button)
        var $classForm = $('#apex-note-class');
        
        $classForm.empty();
        
        $classForm.append('<option value="">-- Select Class --</option>');
        $.each(apexNotesData.cars, function(classId, classData) {
            $classForm.append('<option value="' + classId + '">' + classData.name + '</option>');
        });

        // Difficulties - clear and populate
        var $diffFilter = $('#apex-filter-difficulty');
        var $diffForm = $('#apex-note-difficulty');
        
        $diffFilter.empty();
        $diffForm.empty();
        
        $diffForm.append('<option value="">-- Select Difficulty --</option>');
        $.each(apexNotesData.difficulties, function(i, diff) {
            $diffFilter.append('<option value="' + diff.id + '">' + diff.name + '</option>');
            $diffForm.append('<option value="' + diff.id + '">' + diff.name + '</option>');
        });

        // Cars - populate the car dropdown
        updateCarSelect();
        
        // Ensure layout group is hidden initially
        $('#apex-layout-group').hide();
        
        // Track layout handler for create form - unbind first to prevent duplicates
        $('#apex-note-track').off('change').on('change', function() {
            var trackId = $(this).val();
            if (trackId) {
                updateLayoutSelect(trackId);
            } else {
                $('#apex-layout-group').hide();
            }
        });
    }
    
    // Update layout dropdown based on selected track
    function updateLayoutSelect(trackId) {
        var $layoutGroup = $('#apex-layout-group');
        var $layoutSelect = $('#apex-note-layout');
        
        // Find the selected track
        var selectedTrack = null;
        $.each(apexNotesData.tracks, function(i, track) {
            if (track.id === trackId) {
                selectedTrack = track;
                return false;
            }
        });
        
        // Clear existing options
        $layoutSelect.empty();
        
        // Check if track has layouts
        if (selectedTrack && selectedTrack.layouts && selectedTrack.layouts.length > 0) {
            // Add options
            $.each(selectedTrack.layouts, function(i, layout) {
                $layoutSelect.append('<option value="' + layout + '">' + layout + '</option>');
            });
            $layoutGroup.show();
        } else {
            $layoutGroup.hide();
        }
    }

    function updateCarSelect(classId) {
        // Update BOTH the filter dropdown AND the form dropdown
        var $filterCar = $('#apex-filter-car');
        var $formCar = $('#apex-note-car');
        
        // Clear existing options
        $filterCar.find('option').remove();
        $formCar.find('option').remove();
        
        // Add placeholder for form (no disabled - causes issues in some browsers)
        $formCar.append('<option value="">-- Select Car --</option>');

        var classes = classId ? [classId] : Object.keys(apexNotesData.cars);
        $.each(classes, function(i, cls) {
            var classData = apexNotesData.cars[cls];
            if (classData && classData.cars) {
                $.each(classData.cars, function(j, car) {
                    var label = car.name + (car.dlc ? ' (DLC)' : '');
                    $filterCar.append('<option value="' + car.id + '">' + label + '</option>');
                    $formCar.append('<option value="' + car.id + '">' + label + '</option>');
                });
            }
        });
    }

    // Bind events
    function bindEvents() {
        // Navigation - only handle clicks on actual navigation links, NOT page containers
        $(document).on('click', '[data-page]', function(e) {
            // Skip if this is a page container (not a navigation element)
            if ($(this).hasClass('apex-page')) {
                return;
            }
            // Don't handle if it's inside a note card (let card click handler work)
            if ($(this).closest('.apex-note-card').length && !$(this).hasClass('apex-back-link')) {
                return;
            }
            e.preventDefault();
            e.stopPropagation();
            var page = $(this).data('page');
            showPage(page);
            
            // Close mobile menu if open
            closeMobileMenu();
        });
        
        // Hamburger menu toggle
        $('#apex-hamburger').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            toggleMobileMenu();
        });
        
        // Close mobile menu button
        $('#apex-mobile-menu-close').on('click', function(e) {
            e.preventDefault();
            closeMobileMenu();
        });
        
        // Close mobile menu on overlay click
        $('#apex-mobile-menu-overlay').on('click', function() {
            closeMobileMenu();
        });
        
        // Mobile menu navigation links
        $(document).on('click', '.apex-mobile-nav-link, .apex-mobile-profile-link', function(e) {
            var page = $(this).data('page');
            var userId = $(this).data('user-id');
            
            // Handle profile view
            if ($(this).hasClass('apex-view-profile') && userId) {
                e.preventDefault();
                closeMobileMenu();
                viewUserProfile(userId);
                return;
            }
            
            // Handle page navigation
            if (page) {
                // Update active state in mobile menu
                $('.apex-mobile-nav-link').removeClass('active');
                $(this).addClass('active');
            }
            
            // Close mobile menu
            closeMobileMenu();
        });
        
        // Back to Notes link - explicit handler
        $(document).on('click', '.apex-back-link', function(e) {
            e.preventDefault();
            e.stopPropagation();
            showPage('home');
        });

        // New note buttons - use event delegation
        $(document).on('click', '.apex-new-note-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            if (!apexNotesData.isLoggedIn) {
                showLoginModal();
                return;
            }
            if (apexNotesData.isBanned) {
                showNotification('Your account has been banned. You cannot create notes.', 'error');
                return;
            }
            if (!apexNotesData.isVerified) {
                showNotification('Please verify your email before posting.', 'error');
                return;
            }
            showPage('create');
        });
        
        // Show login modal
        $('#apex-show-login').on('click', function(e) {
            e.preventDefault();
            showLoginModal();
        });
        
        // Show login modal from login-required prompts
        $(document).on('click', '.apex-open-login', function(e) {
            e.preventDefault();
            showLoginModal();
        });
        
        // Close login modal
        $('#apex-login-modal .apex-modal-close').on('click', function() {
            hideLoginModal();
        });
        
        $('#apex-login-modal').on('click', function(e) {
            if (e.target === this) {
                hideLoginModal();
            }
        });
        
        // Resend verification email
        $('#apex-resend-verify').on('click', function() {
            var $btn = $(this);
            $btn.prop('disabled', true).text('Sending...');
            
            $.ajax({
                url: apexNotesData.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'apex_notes_resend_verification',
                    nonce: apexNotesData.nonce
                },
                success: function(response) {
                    if (response.success) {
                        showNotification(response.data.message, 'success');
                    } else {
                        showNotification(response.data.message, 'error');
                    }
                    $btn.prop('disabled', false).text('Resend verification email');
                },
                error: function() {
                    showNotification('Failed to send email. Please try again.', 'error');
                    $btn.prop('disabled', false).text('Resend verification email');
                }
            });
        });
        
        // User menu click toggle (for better UX)
        $(document).on('click', '.apex-user-avatar', function(e) {
            e.stopPropagation();
            $(this).closest('.apex-user-menu').toggleClass('active');
        });
        
        // Close user menu when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.apex-user-menu').length) {
                $('.apex-user-menu').removeClass('active');
            }
        });
        
        // Vote buttons
        $(document).on('click', '.apex-vote-btn', function(e) {
            e.stopPropagation();
            
            if (!apexNotesData.isLoggedIn) {
                showLoginModal();
                return;
            }
            
            var $btn = $(this);
            var noteId = $btn.data('note-id');
            var voteType = $btn.hasClass('upvote') ? 1 : -1;
            var currentVote = voteType;
            
            // If already voted this way, remove vote
            if ($btn.hasClass('active')) {
                currentVote = 0;
            }
            
            $.ajax({
                url: apexNotesData.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'apex_notes_vote_note',
                    nonce: apexNotesData.nonce,
                    note_id: noteId,
                    vote: currentVote
                },
                success: function(response) {
                    if (response.success) {
                        // Update UI
                        var $container = $btn.closest('.apex-vote-container');
                        $container.find('.apex-vote-btn').removeClass('active');
                        
                        if (currentVote !== 0) {
                            $btn.addClass('active');
                        }
                        
                        $container.find('.upvote .vote-count').text(response.data.upvotes);
                        $container.find('.downvote .vote-count').text(response.data.downvotes);
                    }
                }
            });
        });
        
        // Share button
        $(document).on('click', '.apex-share-btn', function(e) {
            e.stopPropagation();
            var noteId = $(this).data('note-id');
            var noteTitle = $(this).data('note-title') || 'Track Notes';
            showShareModal(noteId, noteTitle);
        });
        
        // Copy share link
        $(document).on('click', '#apex-copy-link', function() {
            var $input = $('#apex-share-url-input');
            $input.select();
            document.execCommand('copy');
            showNotification('Link copied to clipboard!', 'success');
        });
        
        // Close share modal
        $(document).on('click', '#apex-share-modal .apex-modal-close', function() {
            $('#apex-share-modal').removeClass('active');
        });
        
        $('#apex-share-modal').on('click', function(e) {
            if (e.target === this) {
                $(this).removeClass('active');
            }
        });
        
        // Video thumbnail click - open YouTube video modal
        $(document).on('click', '.apex-video-thumbnail', function() {
            var videoId = $(this).data('video-id');
            if (videoId) {
                showVideoModal(videoId);
            }
        });
        
        // Close video modal
        $(document).on('click', '#apex-video-modal .apex-modal-close', function() {
            closeVideoModal();
        });
        
        $(document).on('click', '#apex-video-modal', function(e) {
            if (e.target === this) {
                closeVideoModal();
            }
        });
        
        // View user profile
        $(document).on('click', '.apex-view-profile', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var userId = $(this).data('user-id');
            showUserProfile(userId);
        });
        
        // Edit profile
        $(document).on('click', '#apex-edit-profile-btn', function() {
            $('#apex-profile-view').hide();
            $('#apex-profile-edit').show();
        });
        
        // Cancel edit profile
        $(document).on('click', '#apex-cancel-edit', function() {
            $('#apex-profile-edit').hide();
            $('#apex-profile-view').show();
        });
        
        // Save profile
        $(document).on('click', '#apex-save-profile', function() {
            var $btn = $(this);
            $btn.prop('disabled', true).text('Saving...');
            
            $.ajax({
                url: apexNotesData.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'apex_notes_update_profile',
                    nonce: apexNotesData.nonce,
                    display_name: $('#apex-profile-display-name').val(),
                    bio: $('#apex-profile-bio').val(),
                    discord_username: $('#apex-profile-discord').val(),
                    steam_id: $('#apex-profile-steam').val()
                },
                success: function(response) {
                    if (response.success) {
                        showNotification(response.data.message, 'success');
                        showUserProfile(apexNotesData.currentUser.id);
                    } else {
                        showNotification(response.data.message, 'error');
                    }
                    $btn.prop('disabled', false).text('Save Changes');
                },
                error: function() {
                    showNotification('Failed to save profile.', 'error');
                    $btn.prop('disabled', false).text('Save Changes');
                }
            });
        });
        
        // Livery form submission with progress bar
        $(document).on('submit', '#apex-livery-form', function(e) {
            e.preventDefault();
            
            var $form = $(this);
            var $btn = $('#apex-upload-livery-btn');
            var $progress = $('#apex-livery-progress');
            var $progressFill = $('#apex-progress-fill');
            var $progressText = $('#apex-progress-text');
            var liveryName = $('#apex-livery-name').val().trim();
            var file1 = $('#apex-livery-file-1')[0].files[0];
            var file2 = $('#apex-livery-file-2')[0].files[0];
            var legalAgreed = $('#apex-livery-legal').is(':checked');
            
            if (!liveryName || !file1 || !file2) {
                showNotification('Please fill in all fields and select both files.', 'error');
                return;
            }
            
            if (!legalAgreed) {
                showNotification('You must agree to the terms and conditions.', 'error');
                return;
            }
            
            $btn.prop('disabled', true).html('<div class="apex-spinner-small"></div> Uploading...');
            $progress.show();
            $progressFill.css('width', '0%');
            $progressText.text('Uploading... 0%');
            
            var formData = new FormData();
            formData.append('action', 'apex_notes_upload_livery');
            formData.append('nonce', apexNotesData.nonce);
            formData.append('livery_name', liveryName);
            formData.append('file_1', file1);
            formData.append('file_2', file2);
            formData.append('legal_agreed', '1');
            
            // Use XMLHttpRequest for progress tracking
            var xhr = new XMLHttpRequest();
            
            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    var percent = Math.round((e.loaded / e.total) * 100);
                    $progressFill.css('width', percent + '%');
                    $progressText.text('Uploading... ' + percent + '%');
                }
            });
            
            xhr.addEventListener('load', function() {
                try {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        $progressFill.css('width', '100%');
                        $progressText.text('Upload complete!');
                        showNotification(response.data.message, 'success');
                        // Reload livery section
                        setTimeout(function() {
                            loadUserLivery(apexNotesData.currentUser.id, true);
                        }, 500);
                    } else {
                        $progress.hide();
                        showNotification(response.data.message || 'Upload failed', 'error');
                        $btn.prop('disabled', false).html('<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg> Share Livery');
                    }
                } catch (e) {
                    $progress.hide();
                    showNotification('Failed to parse response.', 'error');
                    $btn.prop('disabled', false).html('<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg> Share Livery');
                }
            });
            
            xhr.addEventListener('error', function() {
                $progress.hide();
                showNotification('Upload failed. Check your connection and file sizes.', 'error');
                $btn.prop('disabled', false).html('<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg> Share Livery');
            });
            
            xhr.open('POST', apexNotesData.ajaxUrl);
            xhr.send(formData);
        });
        
        // Copy livery URL
        $(document).on('click', '.apex-copy-livery-url', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var $input = $(this).siblings('input[type="text"]');
            if (!$input.length) {
                $input = $('#apex-livery-url');
            }
            
            var url = $input.val();
            
            if (!url) {
                showNotification('No URL to copy', 'error');
                return;
            }
            
            // Try modern clipboard API first
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(url).then(function() {
                    showNotification('URL copied to clipboard!', 'success');
                }).catch(function() {
                    fallbackCopy(url);
                });
            } else {
                fallbackCopy(url);
            }
            
            function fallbackCopy(text) {
                // Fallback for older browsers
                var textarea = document.createElement('textarea');
                textarea.value = text;
                textarea.style.position = 'fixed';
                textarea.style.opacity = '0';
                document.body.appendChild(textarea);
                textarea.select();
                try {
                    document.execCommand('copy');
                    showNotification('URL copied to clipboard!', 'success');
                } catch (err) {
                    showNotification('Failed to copy. Please copy manually.', 'error');
                }
                document.body.removeChild(textarea);
            }
        });
        
        // Delete livery
        $(document).on('click', '.apex-delete-livery', function() {
            if (!confirm('Delete your shared livery? This cannot be undone.')) return;
            
            var $btn = $(this);
            $btn.prop('disabled', true);
            
            $.ajax({
                url: apexNotesData.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'apex_notes_delete_livery',
                    nonce: apexNotesData.nonce
                },
                success: function(response) {
                    if (response.success) {
                        showNotification('Livery deleted.', 'success');
                        // Reload livery section
                        loadUserLivery(apexNotesData.currentUser.id, true);
                    } else {
                        showNotification(response.data.message, 'error');
                        $btn.prop('disabled', false);
                    }
                },
                error: function() {
                    showNotification('Failed to delete livery.', 'error');
                    $btn.prop('disabled', false);
                }
            });
        });
        
        // Share livery to Discord
        $(document).on('click', '.apex-share-livery-discord', function(e) {
            e.preventDefault();
            var url = $('#apex-livery-url').val();
            var liveryName = $('.apex-livery-details h4').text();
            // Copy the shareable message to clipboard
            var message = '🏎️ Check out my Le Mans Ultimate livery: ' + liveryName + '\nDownload: ' + url;
            navigator.clipboard.writeText(message).then(function() {
                showNotification('Share message copied! Paste it in Discord.', 'success');
            });
        });
        
        // Post comment
        $(document).on('click', '#apex-post-comment', function() {
            var $btn = $(this);
            var noteId = $btn.data('note-id');
            var comment = $('#apex-comment-text').val().trim();
            
            if (!comment) {
                showNotification('Please enter a comment.', 'error');
                return;
            }
            
            $btn.prop('disabled', true);
            
            $.ajax({
                url: apexNotesData.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'apex_notes_post_comment',
                    nonce: apexNotesData.nonce,
                    note_id: noteId,
                    comment: comment
                },
                success: function(response) {
                    if (response.success) {
                        $('#apex-comment-text').val('');
                        // Add comment to list
                        var $list = $('#apex-comments-list');
                        $list.find('.apex-no-comments').remove();
                        $list.prepend(renderComment(response.data.comment));
                        // Update count
                        var count = $list.find('.apex-comment').length;
                        $('.apex-comment-count').text('(' + count + ')');
                        showNotification('Comment posted!', 'success');
                    } else {
                        showNotification(response.data.message, 'error');
                    }
                    $btn.prop('disabled', false);
                },
                error: function() {
                    showNotification('Failed to post comment.', 'error');
                    $btn.prop('disabled', false);
                }
            });
        });
        
        // Delete comment (admin)
        $(document).on('click', '.apex-delete-comment', function(e) {
            e.stopPropagation();
            if (!confirm('Delete this comment?')) return;
            
            var $btn = $(this);
            var commentId = $btn.data('comment-id');
            
            $.ajax({
                url: apexNotesData.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'apex_notes_delete_comment',
                    nonce: apexNotesData.nonce,
                    comment_id: commentId
                },
                success: function(response) {
                    if (response.success) {
                        $btn.closest('.apex-comment').fadeOut(function() {
                            $(this).remove();
                            var count = $('#apex-comments-list .apex-comment').length;
                            $('.apex-comment-count').text('(' + count + ')');
                            if (count === 0) {
                                $('#apex-comments-list').html('<p class="apex-no-comments">No comments yet. Be the first!</p>');
                            }
                        });
                        showNotification('Comment deleted.', 'success');
                    }
                }
            });
        });
        
        // Delete note (admin)
        $(document).on('click', '.apex-delete-note-btn', function(e) {
            e.stopPropagation();
            if (!confirm('Are you sure you want to delete this track note? This cannot be undone.')) return;
            
            var noteId = $(this).data('note-id');
            
            $.ajax({
                url: apexNotesData.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'apex_notes_delete_note',
                    nonce: apexNotesData.nonce,
                    note_id: noteId
                },
                success: function(response) {
                    if (response.success) {
                        showNotification('Note deleted.', 'success');
                        showPage('home');
                        loadNotes();
                    } else {
                        showNotification(response.data.message || 'Failed to delete note.', 'error');
                    }
                }
            });
        });
        
        // Edit note (admin) - opens edit modal
        $(document).on('click', '.apex-edit-note-btn', function(e) {
            e.stopPropagation();
            var noteId = $(this).data('note-id');
            openEditNoteModal(noteId);
        });
        
        // Save edited note
        $(document).on('click', '#apex-save-edit-note', function() {
            saveEditedNote();
        });
        
        // Close edit modal
        $(document).on('click', '#apex-edit-modal .apex-modal-close', function() {
            $('#apex-edit-modal').removeClass('active');
        });

        // Filters
        $('#apex-search').on('input', debounce(function() {
            state.filters.search = $(this).val();
            loadNotes();
        }, 300));

        $('#apex-filter-track').on('change', function() {
            state.filters.track = $(this).val();
            loadNotes();
        });

        // Reset filters button
        $('#apex-reset-filters').on('click', function() {
            // Reset all filter values
            state.filters.search = '';
            state.filters.track = '';
            state.filters.car_class = '';
            state.filters.car_id = '';
            state.filters.difficulty = '';
            
            // Reset form elements
            $('#apex-search').val('');
            $('#apex-filter-track').val('');
            $('#apex-filter-car').val('');
            $('#apex-filter-difficulty').val('');
            
            // Reset car dropdown to show all cars
            updateCarSelect();
            
            // Reload notes
            loadNotes();
        });

        $('#apex-filter-car').on('change', function() {
            state.filters.car_id = $(this).val();
            loadNotes();
        });

        $('#apex-filter-difficulty').on('change', function() {
            state.filters.difficulty = $(this).val();
            loadNotes();
        });

        // Sort buttons
        $(document).on('click', '.apex-sort-btn', function() {
            $('.apex-sort-btn').removeClass('active');
            $(this).addClass('active');
            state.currentSort = $(this).data('sort');
            loadNotes();
        });

        // Note card click
        $(document).on('click', '.apex-note-card', function() {
            var noteId = $(this).data('id');
            viewNote(noteId);
        });

        // Car class change updates car select
        $('#apex-note-class').on('change', function() {
            updateCarSelect($(this).val());
        });

        // Save section (just save, don't clear form)
        $('#apex-add-section-btn').on('click', saveSection);
        
        // Save and add new section (save + clear form for new section)
        $('#apex-save-and-add-section-btn').on('click', saveAndAddSection);

        // Remove section
        $(document).on('click', '.apex-remove-section', function() {
            var index = $(this).data('index');
            state.sections.splice(index, 1);
            renderAddedSections();
        });

        // Form submit - check for unsaved section data
        $('#apex-create-form').on('submit', function(e) {
            e.preventDefault();
            
            // Check if there's unsaved section data
            var sectionName = $('#apex-section-name').val().trim();
            if (sectionName) {
                // Check if Save button is still active (not already saved)
                var $saveBtn = $('#apex-add-section-btn');
                if (!$saveBtn.hasClass('saved')) {
                    // Ask user if they want to save the current section
                    if (confirm('You have unsaved section data for "' + sectionName + '". Would you like to save it before submitting?')) {
                        saveSection();
                    }
                }
            }
            
            submitNote();
        });
        
        // Reset Save button when user modifies section fields
        $('#apex-section-name, #apex-section-braking, #apex-section-turnin, #apex-section-apex, #apex-section-exit, #apex-section-gear, #apex-section-speed, #apex-section-tip').on('input', function() {
            var $saveBtn = $('#apex-add-section-btn');
            if ($saveBtn.hasClass('saved')) {
                $saveBtn.removeClass('saved').html('<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg> Save Section');
            }
        });

        // Like button
        $(document).on('click', '.apex-like-btn', function() {
            if (!apexNotesData.isLoggedIn) {
                window.location.href = apexNotesData.loginUrl;
                return;
            }
            var noteId = state.currentNote.id;
            likeNote(noteId);
        });

        // Moderation tabs
        $(document).on('click', '.apex-mod-tab', function() {
            $('.apex-mod-tab').removeClass('active');
            $(this).addClass('active');
            loadModeration($(this).data('status'));
        });
        
        // Ban user button
        $(document).on('click', '.apex-ban-btn', function() {
            var userId = $(this).data('user-id');
            var userName = $(this).data('user-name');
            var reason = prompt('Ban reason for ' + userName + ' (optional):');
            
            if (reason === null) return; // Cancelled
            
            if (confirm('Are you sure you want to ban ' + userName + '?')) {
                banUser(userId, reason);
            }
        });
        
        // Unban user button
        $(document).on('click', '.apex-unban-btn', function() {
            var userId = $(this).data('user-id');
            
            if (confirm('Are you sure you want to unban this user?')) {
                unbanUser(userId);
            }
        });
    }

    // Auth UI
    function updateAuthUI() {
        if (apexNotesData.isLoggedIn) {
            $('[data-auth-required]').show();
            if (apexNotesData.canModerate) {
                $('[data-mod-required]').show();
            }
        }
    }

    // Show page
    // URL routing configuration
    var routes = {
        'home': '/',
        'track-guide': '/trackguide',
        'apex-track-bot': '/apextrackbot',
        'fuel-data': '/boxbox',
        'tire-data': '/tiredata',
        'live-race': '/streaming',
        'stewards-room': '/stewards',
        'stewards-view': '/stewards/view',
        'create': '/newnote',
        'my-notes': '/mynotes',
        'moderation': '/moderation'
    };
    
    var reverseRoutes = {};
    $.each(routes, function(page, path) {
        reverseRoutes[path] = page;
    });
    
    // Mobile Menu Functions
    function toggleMobileMenu() {
        var $hamburger = $('#apex-hamburger');
        var $menu = $('#apex-mobile-menu');
        var $overlay = $('#apex-mobile-menu-overlay');
        
        if ($menu.hasClass('active')) {
            closeMobileMenu();
        } else {
            $hamburger.addClass('active');
            $menu.addClass('active');
            $overlay.addClass('active');
            $('body').css('overflow', 'hidden');
        }
    }
    
    function closeMobileMenu() {
        $('#apex-hamburger').removeClass('active');
        $('#apex-mobile-menu').removeClass('active');
        $('#apex-mobile-menu-overlay').removeClass('active');
        $('body').css('overflow', '');
    }
    
    // Update mobile menu active state when page changes
    function updateMobileMenuActive(page) {
        $('.apex-mobile-nav-link').removeClass('active');
        $('.apex-mobile-nav-link[data-page="' + page + '"]').addClass('active');
    }

    function showPage(page, pushState) {
        // Default to pushing state unless explicitly false
        if (typeof pushState === 'undefined') {
            pushState = true;
        }
        
        $('.apex-page').removeClass('active');
        $('.apex-page[data-page="' + page + '"]').addClass('active');

        $('.apex-nav-link').removeClass('active');
        $('.apex-nav-link[data-page="' + page + '"]').addClass('active');
        
        // Update mobile menu active state
        updateMobileMenuActive(page);

        if (page === 'home') {
            loadNotes();
        } else if (page === 'my-notes') {
            loadMyNotes();
        } else if (page === 'moderation') {
            loadModeration('pending');
        } else if (page === 'create') {
            // Only reset if we're navigating TO the create page, not if we're already there
            if (state.page !== 'create') {
                resetCreateForm();
            }
        }
        
        state.page = page;

        // Update URL using History API
        if (pushState && routes[page]) {
            var newUrl = apexNotesData.siteUrl + routes[page];
            if (window.location.href !== newUrl) {
                history.pushState({ page: page }, '', newUrl);
            }
        }

        window.scrollTo(0, 0);
    }
    
    // Handle browser back/forward
    $(window).on('popstate', function(e) {
        var state = e.originalEvent.state;
        if (state) {
            if (state.noteId) {
                // Viewing a specific note
                viewNote(state.noteId);
                return;
            }
            if (state.userId) {
                // Viewing a profile
                showUserProfile(state.userId);
                return;
            }
            if (state.page) {
                showPage(state.page, false);
                return;
            }
        }
        
        // Parse URL to determine page
        var path = window.location.pathname;
        
        // Handle note URLs
        var noteMatch = path.match(/\/note\/(\d+)/);
        if (noteMatch) {
            viewNote(parseInt(noteMatch[1]));
            return;
        }
        
        // Handle profile URLs
        var profileMatch = path.match(/\/profile\/([^\/]+)/);
        if (profileMatch) {
            showProfileByUsername(decodeURIComponent(profileMatch[1]));
            return;
        }
        
        var page = getPageFromUrl();
        showPage(page, false);
    });
    
    // Get page from current URL
    function getPageFromUrl() {
        var path = window.location.pathname;
        var siteBase = apexNotesData.siteBase || '';
        
        // Remove site base path if present
        if (siteBase && path.indexOf(siteBase) === 0) {
            path = path.substring(siteBase.length);
        }
        
        // Check for profile URLs
        if (path.match(/^\/profile\/([^\/]+)/)) {
            return 'profile';
        }
        
        // Check for note URLs
        if (path.match(/^\/note\/(\d+)/)) {
            return 'note-detail';
        }
        
        // Check for livery URLs
        if (path.match(/\/livery\/([a-zA-Z0-9]+)/)) {
            return 'livery';
        }
        
        // Check for stewards view URLs
        if (path.match(/\/stewards\/view\/([a-zA-Z0-9]+)/)) {
            return 'stewards-room';
        }
        
        // Match against routes
        if (reverseRoutes[path]) {
            return reverseRoutes[path];
        }
        
        // Default to home
        return 'home';
    }
    
    // Initialize page from URL on load
    function initFromUrl() {
        var page = getPageFromUrl();
        var path = window.location.pathname;
        
        // Check if PHP detected a livery token (fallback when rewrite rules don't work)
        if (apexNotesData.liveryToken) {
            loadLiveryDownload(apexNotesData.liveryToken);
            return;
        }
        
        // Check URL path for stewards view - this is the most reliable method
        var stewardsMatch = path.match(/\/stewards\/view\/([a-zA-Z0-9]+)/);
        if (stewardsMatch) {
            var token = stewardsMatch[1];
            showPage('stewards-room', false);
            setTimeout(function() {
                // Initialize stewards room
                initStewardsRoom();
                stewardsInitialized = true;
                loadSharedReport(token);
            }, 300);
            return;
        }
        
        // Fallback: Check if PHP detected a stewards token
        if (apexNotesData.stewardsToken) {
            showPage('stewards-room', false);
            setTimeout(function() {
                initStewardsRoom();
                stewardsInitialized = true;
                loadSharedReport(apexNotesData.stewardsToken);
            }, 300);
            return;
        }
        
        // Handle profile URLs
        var profileMatch = path.match(/\/profile\/([^\/]+)/);
        if (profileMatch) {
            var username = decodeURIComponent(profileMatch[1]);
            showProfileByUsername(username);
            return;
        }
        
        // Handle note URLs
        var noteMatch = path.match(/\/note\/(\d+)/);
        if (noteMatch) {
            var noteId = parseInt(noteMatch[1]);
            viewNote(noteId);
            return;
        }
        
        // Handle livery URLs (if rewrite rules work)
        var liveryMatch = path.match(/\/livery\/([a-zA-Z0-9]+)/);
        if (liveryMatch) {
            var liveryToken = liveryMatch[1];
            loadLiveryDownload(liveryToken);
            return;
        }
        
        // Show the page without pushing state (we're already at this URL)
        showPage(page, false);
        
        // Replace current state
        history.replaceState({ page: page }, '', window.location.href);
    }
    
    // Load livery download page
    function loadLiveryDownload(token) {
        showPage('livery', false);
        var $container = $('#apex-livery-download-content');
        $container.html('<div class="apex-loading"><div class="apex-spinner"></div></div>');
        
        $.ajax({
            url: apexNotesData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'apex_notes_get_livery_download',
                nonce: apexNotesData.nonce,
                token: token
            },
            success: function(response) {
                if (response.success) {
                    renderLiveryDownloadPage(response.data.livery);
                } else {
                    $container.html(
                        '<div class="apex-livery-error">' +
                        '<svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="var(--apex-red)" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>' +
                        '<h2>Livery Not Found</h2>' +
                        '<p>' + (response.data.message || 'This livery may have expired or been deleted.') + '</p>' +
                        '<button class="apex-btn apex-btn-primary" data-page="home">Back to Home</button>' +
                        '</div>'
                    );
                }
            },
            error: function() {
                $container.html(
                    '<div class="apex-livery-error">' +
                    '<svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="var(--apex-red)" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>' +
                    '<h2>Error Loading Livery</h2>' +
                    '<p>Failed to load livery data. Please try again.</p>' +
                    '<button class="apex-btn apex-btn-primary" data-page="home">Back to Home</button>' +
                    '</div>'
                );
            }
        });
    }
    
    // Render livery download page
    function renderLiveryDownloadPage(livery) {
        if (!livery) {
            $('#apex-livery-download-content').html('<div class="apex-livery-error"><h2>Error</h2><p>No livery data received.</p></div>');
            return;
        }
        
        var html = '<div class="apex-livery-download-page">' +
            '<div class="apex-livery-download-card">' +
            '<div class="apex-livery-download-icon">' +
            '<svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="var(--apex-orange)" stroke-width="1.5"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><path d="M9 17h6"/><circle cx="17" cy="17" r="2"/></svg>' +
            '</div>' +
            '<h1>' + escapeHtml(livery.livery_name || 'Shared Livery') + '</h1>' +
            '<p class="apex-livery-author">Shared by <strong>' + escapeHtml(livery.author_name || 'Unknown') + '</strong></p>' +
            '<p class="apex-livery-downloads">' + (livery.download_count || 0) + ' downloads</p>' +
            '<div class="apex-livery-files-list">' +
            '<h3>Files Included</h3>';
        
        if (livery.files && livery.files.length > 0) {
            livery.files.forEach(function(file) {
                html += '<div class="apex-livery-file-item">' +
                    '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>' +
                    '<span class="apex-livery-file-name">' + escapeHtml(file.name) + '</span>' +
                    '<a href="' + escapeHtml(file.url) + '" class="apex-btn apex-btn-primary apex-btn-small">' +
                    '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>' +
                    'Download' +
                    '</a>' +
                    '</div>';
            });
        } else {
            html += '<p>No files available</p>';
        }
        
        html += '</div>' +
            '<div class="apex-livery-instructions">' +
            '<h3>Installation Instructions</h3>' +
            '<ol>' +
            '<li>Download both files above</li>' +
            '<li>Navigate to your Le Mans Ultimate livery folder:<br><code>Documents\\My Games\\Le Mans Ultimate\\Customs\\Liveries\\[CarFolder]</code></li>' +
            '<li>Place both <code>customskin.tga</code> and <code>customskin_region.tga</code> in the car folder</li>' +
            '<li>Launch the game and select the custom livery in the garage</li>' +
            '</ol>' +
            '</div>' +
            '</div>' +
            '</div>';
        
        $('#apex-livery-download-content').html(html);
    }
    
    // Show profile by username
    function showProfileByUsername(username) {
        $.ajax({
            url: apexNotesData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'apex_notes_get_profile_by_username',
                nonce: apexNotesData.nonce,
                username: username
            },
            success: function(response) {
                if (response.success && response.data.profile) {
                    showUserProfile(response.data.profile.id);
                } else {
                    showPage('home', false);
                }
            },
            error: function() {
                showPage('home', false);
            }
        });
    }

    // Load notes
    function loadNotes() {
        $.ajax({
            url: apexNotesData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'apex_notes_get_notes',
                nonce: apexNotesData.nonce,
                search: state.filters.search,
                track_id: state.filters.track,
                car_class: state.filters.car_class,
                car_id: state.filters.car_id,
                difficulty: state.filters.difficulty,
                orderby: state.currentSort
            },
            success: function(response) {
                if (response.success) {
                    state.notes = response.data.notes;
                    renderNotes();
                }
            }
        });
    }

    // Render notes
    function renderNotes() {
        var $grid = $('#apex-notes-grid');
        var $empty = $('#apex-empty-state');
        var $count = $('#apex-results-count');

        $count.text(state.notes.length + ' results');

        if (state.notes.length === 0) {
            $grid.empty();
            $empty.show();
            return;
        }

        $empty.hide();

        var html = '';
        $.each(state.notes, function(i, note) {
            html += renderNoteCard(note);
        });

        $grid.html(html);
    }

    // Render note card
    function renderNoteCard(note) {
        var track = getTrackById(note.track_id);
        var car = getCarById(note.car_id);
        var carClass = getCarClass(note.car_class);
        var difficulty = getDifficultyById(note.difficulty);

        var html = '<div class="apex-note-card" data-id="' + note.id + '">';
        
        // Author info
        if (note.author) {
            var authorAvatar = note.author.avatar ? '<img src="' + escapeHtml(note.author.avatar) + '" alt="">' : note.author.name.charAt(0).toUpperCase();
            html += '<div class="apex-card-author">';
            html += '<div class="apex-card-author-avatar">' + authorAvatar + '</div>';
            html += '<a href="#" class="apex-card-author-name apex-view-profile" data-user-id="' + note.author.id + '">' + escapeHtml(note.author.name) + '</a>';
            html += '</div>';
        }

        // Badges
        html += '<div class="apex-note-badges">';
        html += '<span class="apex-note-badge apex-note-badge-class">' + (carClass ? carClass.name : note.car_class) + '</span>';
        if (difficulty) {
            html += '<span class="apex-note-badge apex-note-badge-difficulty" style="color:' + difficulty.color + '">' + difficulty.name + '</span>';
        }
        if (note.featured == 1) {
            html += '<span class="apex-note-badge apex-note-badge-featured">Featured</span>';
        }
        html += '</div>';

        // Title
        html += '<h3 class="apex-note-title">' + escapeHtml(note.title) + '</h3>';

        // Meta
        var trackDisplay = track ? track.name : note.track_id;
        if (note.track_layout && note.track_layout !== 'Default') {
            trackDisplay += ' (' + escapeHtml(note.track_layout) + ')';
        }
        html += '<div class="apex-note-meta">';
        html += '<div class="apex-note-meta-item"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>' + trackDisplay + '</div>';
        html += '<div class="apex-note-meta-item"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>' + (car ? car.name : note.car_id) + '</div>';
        if (note.lap_time) {
            html += '<div class="apex-note-meta-item"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>' + escapeHtml(note.lap_time) + '</div>';
        }
        html += '</div>';

        // Description
        if (note.description) {
            html += '<p class="apex-note-description">' + escapeHtml(note.description) + '</p>';
        }

        // Footer with votes and share
        html += '<div class="apex-card-footer">';
        html += '<div class="apex-card-stats">';
        html += '<div class="apex-vote-container">';
        html += '<button class="apex-vote-btn upvote" data-note-id="' + note.id + '"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/></svg><span class="vote-count">' + (note.upvotes || 0) + '</span></button>';
        html += '<button class="apex-vote-btn downvote" data-note-id="' + note.id + '"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 15v4a3 3 0 0 0 3 3l4-9V2H5.72a2 2 0 0 0-2 1.7l-1.38 9a2 2 0 0 0 2 2.3zm7-13h2.67A2.31 2.31 0 0 1 22 4v7a2.31 2.31 0 0 1-2.33 2H17"/></svg><span class="vote-count">' + (note.downvotes || 0) + '</span></button>';
        html += '</div>';
        html += '<span class="apex-note-stat"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>' + (note.views || 0) + '</span>';
        html += '</div>';
        html += '<div class="apex-card-actions">';
        html += '<button class="apex-share-btn" data-note-id="' + note.id + '" data-note-title="' + escapeHtml(note.title) + '"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg></button>';
        var sectionCount = note.sections ? note.sections.length : 0;
        html += '<span class="apex-note-sections-count">' + sectionCount + ' sections</span>';
        html += '</div>';
        html += '</div>';

        html += '</div>';
        return html;
    }
    
    // Reusable note card HTML for profile page
    function renderNoteCardHtml(note) {
        return renderNoteCard(note);
    }

    // View note detail
    function viewNote(noteId) {
        $.ajax({
            url: apexNotesData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'apex_notes_get_note',
                nonce: apexNotesData.nonce,
                note_id: noteId
            },
            success: function(response) {
                if (response.success) {
                    state.currentNote = response.data.note;
                    renderNoteDetail();
                    
                    // Show detail page without pushing state (we'll push custom URL)
                    $('.apex-page').removeClass('active');
                    $('.apex-page[data-page="detail"]').addClass('active');
                    
                    // Push note URL to history
                    var newUrl = apexNotesData.siteUrl + '/note/' + noteId;
                    history.pushState({ page: 'detail', noteId: noteId }, '', newUrl);
                    
                    window.scrollTo(0, 0);
                }
            }
        });
    }

    // Render note detail - matches screenshot design exactly
    function renderNoteDetail() {
        var note = state.currentNote;
        var track = getTrackById(note.track_id);
        var car = getCarById(note.car_id);
        var carClass = getCarClass(note.car_class);
        var difficulty = getDifficultyById(note.difficulty);

        var html = '<div class="apex-detail-page">';

        // Back button
        html += '<button class="apex-back-link" data-page="home"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>Back to Notes</button>';

        // Author
        var authorAvatar = (note.author && note.author.avatar) ? '<img src="' + escapeHtml(note.author.avatar) + '" alt="">' : (note.author && note.author.name ? note.author.name.charAt(0) : 'U');
        html += '<div class="apex-detail-author">';
        html += '<div class="apex-detail-author-avatar">' + authorAvatar + '</div>';
        html += '<div class="apex-detail-author-info"><span>Created by</span><a href="#" class="apex-view-profile" data-user-id="' + (note.author ? note.author.id : 0) + '"><strong>' + (note.author ? escapeHtml(note.author.name) : 'Unknown') + '</strong></a></div>';
        html += '</div>';

        // Badges
        html += '<div class="apex-detail-badges">';
        html += '<span class="apex-note-badge apex-note-badge-class">' + (carClass ? carClass.name : note.car_class) + '</span>';
        if (difficulty) {
            html += '<span class="apex-note-badge apex-note-badge-difficulty" style="color:' + difficulty.color + '">' + difficulty.name + '</span>';
        }
        if (note.featured == 1) {
            html += '<span class="apex-note-badge apex-note-badge-featured">Featured</span>';
        }
        html += '</div>';

        // Title
        html += '<h1 class="apex-detail-title">' + escapeHtml(note.title) + '</h1>';

        // Meta
        var detailTrackDisplay = track ? track.name : note.track_id;
        if (note.track_layout && note.track_layout !== 'Default') {
            detailTrackDisplay += ' (' + escapeHtml(note.track_layout) + ')';
        }
        html += '<div class="apex-detail-meta">';
        html += '<span class="apex-detail-meta-item"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>' + detailTrackDisplay + '</span>';
        html += '<span class="apex-detail-meta-item"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>' + (car ? car.name : note.car_id) + '</span>';
        if (note.lap_time) {
            html += '<span class="apex-detail-meta-item"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>' + escapeHtml(note.lap_time) + '</span>';
        }
        html += '</div>';

        // Actions - with voting, share, and admin controls
        html += '<div class="apex-detail-actions">';
        html += '<div class="apex-vote-container">';
        html += '<button class="apex-vote-btn upvote' + (note.user_vote == 1 ? ' active' : '') + '" data-note-id="' + note.id + '"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/></svg><span class="vote-count">' + (note.upvotes || 0) + '</span></button>';
        html += '<button class="apex-vote-btn downvote' + (note.user_vote == -1 ? ' active' : '') + '" data-note-id="' + note.id + '"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 15v4a3 3 0 0 0 3 3l4-9V2H5.72a2 2 0 0 0-2 1.7l-1.38 9a2 2 0 0 0 2 2.3zm7-13h2.67A2.31 2.31 0 0 1 22 4v7a2.31 2.31 0 0 1-2.33 2H17"/></svg><span class="vote-count">' + (note.downvotes || 0) + '</span></button>';
        html += '</div>';
        html += '<span class="apex-views-count"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>' + (note.views || 0) + ' views</span>';
        html += '<button class="apex-share-btn" data-note-id="' + note.id + '" data-note-title="' + escapeHtml(note.title) + '"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>Share</button>';
        
        // Admin controls
        if (apexNotesData.canModerate) {
            html += '<div class="apex-admin-actions">';
            html += '<button class="apex-btn apex-btn-secondary apex-edit-note-btn" data-note-id="' + note.id + '"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>Edit</button>';
            html += '<button class="apex-btn apex-btn-danger apex-delete-note-btn" data-note-id="' + note.id + '"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>Delete</button>';
            html += '</div>';
        }
        html += '</div>';

        // Description
        if (note.description) {
            html += '<div class="apex-content-box"><p>' + escapeHtml(note.description) + '</p></div>';
        }

        // Setup Notes
        if (note.setup_notes) {
            html += '<div class="apex-content-box">';
            html += '<div class="apex-setup-header"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>Setup Notes</div>';
            html += '<p>' + escapeHtml(note.setup_notes) + '</p>';
            html += '</div>';
        }

        // Track Walkthrough - MATCHING SCREENSHOTS EXACTLY
        if (note.sections && note.sections.length > 0) {
            html += '<h2 class="apex-section-title">Track Walkthrough <span class="apex-results-count">' + note.sections.length + ' sections</span></h2>';
            html += '<div class="apex-sections-list">';

            $.each(note.sections, function(i, section) {
                html += '<div class="apex-section-card">';
                html += '<div class="apex-section-card-header">';
                html += '<div class="apex-section-number">' + (i + 1) + '</div>';
                html += '<h4 class="apex-section-name">' + escapeHtml(section.name) + '</h4>';
                html += '</div>';
                html += '<div class="apex-section-grid">';

                // Braking
                html += '<div class="apex-section-item braking"><div class="apex-item-icon"><svg viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="6"/></svg></div><div class="apex-item-content"><div class="apex-item-label">Braking</div><div class="apex-item-value">' + escapeHtml(section.braking || '-') + '</div></div></div>';

                // Turn-In
                html += '<div class="apex-section-item turn-in"><div class="apex-item-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M9 18l6-6-6-6"/></svg></div><div class="apex-item-content"><div class="apex-item-label">Turn-In</div><div class="apex-item-value">' + escapeHtml(section.turn_in || '-') + '</div></div></div>';

                // Apex
                html += '<div class="apex-section-item apex"><div class="apex-item-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="8"/><circle cx="12" cy="12" r="4"/></svg></div><div class="apex-item-content"><div class="apex-item-label">Apex</div><div class="apex-item-value">' + escapeHtml(section.apex || '-') + '</div></div></div>';

                // Exit
                html += '<div class="apex-section-item exit"><div class="apex-item-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M7 17l5-5-5-5"/><path d="M13 17l5-5-5-5"/></svg></div><div class="apex-item-content"><div class="apex-item-label">Exit</div><div class="apex-item-value">' + escapeHtml(section.exit_point || '-') + '</div></div></div>';

                // Gear
                html += '<div class="apex-section-item gear"><div class="apex-item-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="10" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></div><div class="apex-item-content"><div class="apex-item-label">Gear</div><div class="apex-item-value">' + escapeHtml(section.gear || '-') + '</div></div></div>';

                // Speed
                html += '<div class="apex-section-item speed"><div class="apex-item-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg></div><div class="apex-item-content"><div class="apex-item-label">Speed</div><div class="apex-item-value">' + escapeHtml(section.speed || '-') + '</div></div></div>';

                html += '</div>'; // grid

                // Pro tip
                if (section.pro_tip) {
                    html += '<div class="apex-section-protip">';
                    html += '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>';
                    html += '<div><div class="apex-protip-label">Pro Tip</div><div class="apex-protip-text">' + escapeHtml(section.pro_tip) + '</div></div>';
                    html += '</div>';
                }

                html += '</div>'; // card
            });

            html += '</div>'; // sections-list
        }
        
        // Comments Section
        html += '<div class="apex-comments-section">';
        html += '<h3 class="apex-comments-title"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>Comments <span class="apex-comment-count">(' + (note.comments ? note.comments.length : 0) + ')</span></h3>';
        
        // Comment form
        if (apexNotesData.isLoggedIn) {
            var userAvatar = apexNotesData.currentUser && apexNotesData.currentUser.avatar ? '<img src="' + escapeHtml(apexNotesData.currentUser.avatar) + '" alt="">' : (apexNotesData.currentUser ? apexNotesData.currentUser.name.charAt(0) : 'U');
            html += '<div class="apex-comment-form">';
            html += '<div class="apex-comment-avatar">' + userAvatar + '</div>';
            html += '<div class="apex-comment-input-wrapper">';
            html += '<textarea id="apex-comment-text" placeholder="Share your thoughts..." rows="2"></textarea>';
            html += '<button class="apex-btn apex-btn-primary" id="apex-post-comment" data-note-id="' + note.id + '"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>Post</button>';
            html += '</div>';
            html += '</div>';
        }
        
        // Comments list
        html += '<div class="apex-comments-list" id="apex-comments-list">';
        if (note.comments && note.comments.length > 0) {
            $.each(note.comments, function(i, comment) {
                html += renderComment(comment);
            });
        } else {
            html += '<p class="apex-no-comments">No comments yet. Be the first!</p>';
        }
        html += '</div>';
        html += '</div>';

        html += '</div>'; // detail-page

        $('#apex-detail-content').html(html);
    }
    
    function renderComment(comment) {
        var avatar = comment.author && comment.author.avatar ? '<img src="' + escapeHtml(comment.author.avatar) + '" alt="">' : (comment.author ? comment.author.name.charAt(0) : 'U');
        var html = '<div class="apex-comment" data-comment-id="' + comment.id + '">';
        html += '<div class="apex-comment-avatar">' + avatar + '</div>';
        html += '<div class="apex-comment-content">';
        html += '<div class="apex-comment-header">';
        html += '<a href="#" class="apex-comment-author apex-view-profile" data-user-id="' + (comment.author ? comment.author.id : 0) + '">' + (comment.author ? escapeHtml(comment.author.name) : 'Unknown') + '</a>';
        html += '<span class="apex-comment-date">' + formatDate(comment.created_at) + '</span>';
        if (apexNotesData.canModerate) {
            html += '<button class="apex-delete-comment" data-comment-id="' + comment.id + '" title="Delete comment"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>';
        }
        html += '</div>';
        html += '<p class="apex-comment-text">' + escapeHtml(comment.comment) + '</p>';
        html += '</div>';
        html += '</div>';
        return html;
    }
    
    function formatDate(dateStr) {
        var date = new Date(dateStr);
        var now = new Date();
        var diff = now - date;
        var minutes = Math.floor(diff / 60000);
        var hours = Math.floor(diff / 3600000);
        var days = Math.floor(diff / 86400000);
        
        if (minutes < 1) return 'Just now';
        if (minutes < 60) return minutes + 'm ago';
        if (hours < 24) return hours + 'h ago';
        if (days < 7) return days + 'd ago';
        return date.toLocaleDateString();
    }

    // Load my notes
    function loadMyNotes() {
        $.ajax({
            url: apexNotesData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'apex_notes_get_my_notes',
                nonce: apexNotesData.nonce
            },
            success: function(response) {
                if (response.success) {
                    renderMyNotes(response.data.notes);
                }
            }
        });
    }

    function renderMyNotes(notes) {
        var html = '';
        if (notes.length === 0) {
            html = '<div class="apex-empty-state"><svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg><h3>No notes yet</h3><p>Start by creating your first track note!</p></div>';
        } else {
            html = '<div class="apex-notes-grid">';
            $.each(notes, function(i, note) {
                html += renderNoteCard(note);
            });
            html += '</div>';
        }
        $('#apex-my-notes-list').html(html);
    }

    // Create note form
    function resetCreateForm() {
        // Only reset text inputs and textareas, not selects (they get reset to placeholder)
        var $form = $('#apex-create-form');
        if ($form.length) {
            // Reset text inputs and textareas
            $form.find('input[type="text"], textarea').val('');
            
            // Reset selects to first option (placeholder)
            $form.find('select').each(function() {
                $(this).prop('selectedIndex', 0);
            });
            
            // Hide layout group
            $('#apex-layout-group').hide();
        }
        state.sections = [];
        renderAddedSections();
    }

    function saveSection() {
        var section = {
            name: $('#apex-section-name').val(),
            braking: $('#apex-section-braking').val(),
            turn_in: $('#apex-section-turnin').val(),
            apex: $('#apex-section-apex').val(),
            exit_point: $('#apex-section-exit').val(),
            gear: $('#apex-section-gear').val(),
            speed: $('#apex-section-speed').val(),
            pro_tip: $('#apex-section-tip').val()
        };

        if (!section.name) {
            showNotification('Please enter a section name', 'error');
            return false;
        }

        state.sections.push(section);
        renderAddedSections();
        showNotification('Section "' + section.name + '" saved!', 'success');
        
        // Disable the Save button and show checkmark to indicate it's saved
        var $saveBtn = $('#apex-add-section-btn');
        $saveBtn.addClass('saved').html('<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Section Saved');
        
        return true;
    }
    
    function saveAndAddSection() {
        var section = {
            name: $('#apex-section-name').val(),
            braking: $('#apex-section-braking').val(),
            turn_in: $('#apex-section-turnin').val(),
            apex: $('#apex-section-apex').val(),
            exit_point: $('#apex-section-exit').val(),
            gear: $('#apex-section-gear').val(),
            speed: $('#apex-section-speed').val(),
            pro_tip: $('#apex-section-tip').val()
        };

        if (!section.name) {
            showNotification('Please enter a section name', 'error');
            return;
        }

        state.sections.push(section);
        renderAddedSections();
        showNotification('Section "' + section.name + '" saved! Now add your next section.', 'success');

        // Clear section form for new entry
        $('#apex-section-name, #apex-section-braking, #apex-section-turnin, #apex-section-apex, #apex-section-exit, #apex-section-gear, #apex-section-speed, #apex-section-tip').val('');
        
        // Reset the Save button
        var $saveBtn = $('#apex-add-section-btn');
        $saveBtn.removeClass('saved').html('<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg> Save Section');
        
        // Focus on section name for quick entry
        $('#apex-section-name').focus();
    }
    
    // Legacy function name for compatibility
    function addSection() {
        saveAndAddSection();
    }

    function renderAddedSections() {
        var $container = $('#apex-added-sections');
        if (!$container.length) return;
        
        var html = '';

        $.each(state.sections, function(i, section) {
            html += '<div class="apex-added-section">';
            html += '<span>' + (i + 1) + '. ' + escapeHtml(section.name) + '</span>';
            html += '<button type="button" class="apex-remove-section" data-index="' + i + '">×</button>';
            html += '</div>';
        });

        $container.html(html);
    }

    function submitNote() {
        var data = {
            action: 'apex_notes_create_note',
            nonce: apexNotesData.nonce,
            title: $('#apex-note-title').val(),
            description: $('#apex-note-description').val(),
            car_class: $('#apex-note-class').val(),
            difficulty: $('#apex-note-difficulty').val(),
            track_id: $('#apex-note-track').val(),
            track_layout: $('#apex-note-layout').val() || '',
            car_id: $('#apex-note-car').val(),
            lap_time: $('#apex-note-laptime').val(),
            setup_notes: $('#apex-note-setup').val(),
            sections: state.sections
        };

        $.ajax({
            url: apexNotesData.ajaxUrl,
            type: 'POST',
            data: data,
            success: function(response) {
                if (response.success) {
                    showNotification('Note submitted successfully!', 'success');
                    showPage('home');
                } else {
                    showNotification(response.data.message || 'Failed to submit note', 'error');
                }
            },
            error: function() {
                showNotification('An error occurred', 'error');
            }
        });
    }

    function likeNote(noteId) {
        $.ajax({
            url: apexNotesData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'apex_notes_like_note',
                nonce: apexNotesData.nonce,
                note_id: noteId
            },
            success: function(response) {
                if (response.success) {
                    viewNote(noteId);
                }
            }
        });
    }

    // Moderation
    function loadModeration(status) {
        // Handle banned users tab separately
        if (status === 'banned') {
            loadBannedUsers();
            return;
        }
        
        $.ajax({
            url: apexNotesData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'apex_notes_get_moderation',
                nonce: apexNotesData.nonce,
                status: status
            },
            success: function(response) {
                if (response.success) {
                    renderModeration(response.data.notes);
                    if (response.data.pending_count !== undefined) {
                        $('#apex-pending-count').text(response.data.pending_count);
                    }
                }
            }
        });
    }
    
    function loadBannedUsers() {
        $.ajax({
            url: apexNotesData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'apex_notes_get_banned_users',
                nonce: apexNotesData.nonce
            },
            success: function(response) {
                if (response.success) {
                    renderBannedUsers(response.data.users);
                    $('#apex-banned-count').text(response.data.count);
                }
            }
        });
    }
    
    function renderBannedUsers(users) {
        var $list = $('#apex-mod-list');
        if (users.length === 0) {
            $list.html('<div class="apex-empty-state"><h3>No banned users</h3></div>');
            return;
        }
        
        var html = '';
        $.each(users, function(i, user) {
            html += '<div class="apex-mod-card apex-banned-user-card">';
            html += '<div class="apex-banned-user-info">';
            if (user.avatar) {
                html += '<img src="' + escapeHtml(user.avatar) + '" alt="" class="apex-banned-avatar">';
            }
            html += '<div class="apex-banned-user-details">';
            html += '<h3>' + escapeHtml(user.display_name) + '</h3>';
            html += '<p class="apex-banned-email">' + escapeHtml(user.email) + '</p>';
            if (user.ban_reason) {
                html += '<p class="apex-banned-reason">Reason: ' + escapeHtml(user.ban_reason) + '</p>';
            }
            if (user.ban_date) {
                html += '<p class="apex-banned-date">Banned: ' + escapeHtml(user.ban_date) + '</p>';
            }
            html += '</div></div>';
            html += '<div class="apex-mod-actions">';
            html += '<button class="apex-btn apex-btn-primary apex-unban-btn" data-user-id="' + user.id + '">Unban User</button>';
            html += '</div></div>';
        });
        $list.html(html);
    }

    function renderModeration(notes) {
        var $list = $('#apex-mod-list');
        if (notes.length === 0) {
            $list.html('<div class="apex-empty-state"><h3>No notes</h3></div>');
            return;
        }

        var html = '';
        $.each(notes, function(i, note) {
            html += '<div class="apex-mod-card">';
            html += '<h3>' + escapeHtml(note.title) + '</h3>';
            html += '<p>By: ' + (note.author ? escapeHtml(note.author.name) : 'Unknown') + '</p>';
            html += '<div class="apex-mod-actions">';
            html += '<button class="apex-btn apex-btn-primary" onclick="ApexNotes.moderate(' + note.id + ', \'approved\')">Approve</button>';
            html += '<button class="apex-btn apex-btn-ghost" onclick="ApexNotes.moderate(' + note.id + ', \'rejected\')">Reject</button>';
            if (note.author && note.author.id) {
                html += '<button class="apex-btn apex-btn-danger apex-ban-btn" data-user-id="' + note.author.id + '" data-user-name="' + escapeHtml(note.author.name) + '">Ban User</button>';
            }
            html += '</div></div>';
        });
        $list.html(html);
    }
    
    function banUser(userId, reason) {
        $.ajax({
            url: apexNotesData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'apex_notes_ban_user',
                nonce: apexNotesData.nonce,
                user_id: userId,
                reason: reason || ''
            },
            success: function(response) {
                if (response.success) {
                    showNotification('User has been banned.', 'success');
                    // Reload current moderation tab
                    loadModeration($('.apex-mod-tab.active').data('status'));
                    // Update banned count
                    loadBannedUsers();
                } else {
                    showNotification(response.data.message || 'Failed to ban user.', 'error');
                }
            },
            error: function() {
                showNotification('Failed to ban user.', 'error');
            }
        });
    }
    
    function unbanUser(userId) {
        $.ajax({
            url: apexNotesData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'apex_notes_unban_user',
                nonce: apexNotesData.nonce,
                user_id: userId
            },
            success: function(response) {
                if (response.success) {
                    showNotification('User has been unbanned.', 'success');
                    loadBannedUsers();
                } else {
                    showNotification(response.data.message || 'Failed to unban user.', 'error');
                }
            },
            error: function() {
                showNotification('Failed to unban user.', 'error');
            }
        });
    }

    // Helpers
    function getTrackById(id) {
        var found = null;
        $.each(apexNotesData.tracks, function(i, track) {
            if (track.id === id) {
                found = track;
                return false;
            }
        });
        return found;
    }

    function getCarById(id) {
        var found = null;
        $.each(apexNotesData.cars, function(classId, classData) {
            $.each(classData.cars, function(i, car) {
                if (car.id === id) {
                    found = car;
                    return false;
                }
            });
            if (found) return false;
        });
        return found;
    }

    function getCarClass(classId) {
        return apexNotesData.cars[classId] || null;
    }

    function getDifficultyById(id) {
        var found = null;
        $.each(apexNotesData.difficulties, function(i, diff) {
            if (diff.id === id) {
                found = diff;
                return false;
            }
        });
        return found;
    }

    function escapeHtml(text) {
        if (!text) return '';
        var div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function debounce(func, wait) {
        var timeout;
        return function() {
            var context = this, args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(function() {
                func.apply(context, args);
            }, wait);
        };
    }

    function showNotification(message, type) {
        var $notif = $('#apex-notification');
        $notif.removeClass('success error').addClass(type).text(message).addClass('show');
        setTimeout(function() {
            $notif.removeClass('show');
        }, 3000);
    }
    
    function showLoginModal() {
        $('#apex-login-modal').addClass('active');
    }
    
    function hideLoginModal() {
        $('#apex-login-modal').removeClass('active');
    }
    
    function showShareModal(noteId, noteTitle) {
        var shareUrl = window.location.origin + window.location.pathname + '?note=' + noteId;
        
        var html = '<div class="apex-modal-overlay active" id="apex-share-modal">' +
            '<div class="apex-modal apex-share-modal">' +
            '<button class="apex-modal-close">&times;</button>' +
            '<h2>Share This Note</h2>' +
            '<p>Share "' + escapeHtml(noteTitle) + '" with others</p>' +
            '<div class="apex-share-options">' +
            '<a href="https://twitter.com/intent/tweet?text=' + encodeURIComponent(noteTitle) + '&url=' + encodeURIComponent(shareUrl) + '" target="_blank" class="apex-share-option">' +
            '<svg width="20" height="20" viewBox="0 0 24 24" fill="#1DA1F2"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>' +
            'Share on Twitter' +
            '</a>' +
            '<a href="https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(shareUrl) + '" target="_blank" class="apex-share-option">' +
            '<svg width="20" height="20" viewBox="0 0 24 24" fill="#4267B2"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>' +
            'Share on Facebook' +
            '</a>' +
            '<a href="https://discord.com/channels/@me" target="_blank" class="apex-share-option">' +
            '<svg width="20" height="20" viewBox="0 0 24 24" fill="#5865F2"><path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0 12.64 12.64 0 0 0-.617-1.25.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057 19.9 19.9 0 0 0 5.993 3.03.078.078 0 0 0 .084-.028c.462-.63.874-1.295 1.226-1.994a.076.076 0 0 0-.041-.106 13.107 13.107 0 0 1-1.872-.892.077.077 0 0 1-.008-.128 10.2 10.2 0 0 0 .372-.292.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127 12.299 12.299 0 0 1-1.873.892.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028 19.839 19.839 0 0 0 6.002-3.03.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03zM8.02 15.33c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.956-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.956 2.418-2.157 2.418zm7.975 0c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.955-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.946 2.418-2.157 2.418z"/></svg>' +
            'Share on Discord' +
            '</a>' +
            '</div>' +
            '<div class="apex-share-url">' +
            '<input type="text" id="apex-share-url-input" value="' + shareUrl + '" readonly>' +
            '<button class="apex-btn apex-btn-primary" id="apex-copy-link">Copy</button>' +
            '</div>' +
            '</div>' +
            '</div>';
        
        // Remove existing and add new
        $('#apex-share-modal').remove();
        $('#apex-notes-app').append(html);
    }
    
    function showVideoModal(videoId) {
        var html = '<div class="apex-modal-overlay active" id="apex-video-modal">' +
            '<div class="apex-modal apex-video-modal">' +
            '<button class="apex-modal-close">&times;</button>' +
            '<div class="apex-video-embed">' +
            '<iframe src="https://www.youtube.com/embed/' + videoId + '?autoplay=1&rel=0" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>' +
            '</div>' +
            '</div>' +
            '</div>';
        
        $('#apex-video-modal').remove();
        $('#apex-notes-app').append(html);
    }
    
    function closeVideoModal() {
        var $modal = $('#apex-video-modal');
        // Stop the video by removing the iframe
        $modal.find('iframe').attr('src', '');
        $modal.removeClass('active');
        setTimeout(function() {
            $modal.remove();
        }, 300);
    }
    
    function showUserProfile(userId) {
        // Show profile page without pushing state yet
        $('.apex-page').removeClass('active');
        $('.apex-page[data-page="profile"]').addClass('active');
        $('.apex-nav-link').removeClass('active');
        
        var $container = $('#apex-profile-content');
        $container.html('<div class="apex-loading"><div class="apex-spinner"></div></div>');
        
        $.ajax({
            url: apexNotesData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'apex_notes_get_user_profile',
                user_id: userId
            },
            success: function(response) {
                if (response.success) {
                    renderUserProfile(response.data);
                    
                    // Push profile URL to history using display name
                    var username = response.data.name.toLowerCase().replace(/\s+/g, '-');
                    var newUrl = apexNotesData.siteUrl + '/profile/' + encodeURIComponent(username);
                    history.pushState({ page: 'profile', userId: userId }, '', newUrl);
                } else {
                    $container.html('<p>User not found.</p>');
                }
            },
            error: function() {
                $container.html('<p>Failed to load profile.</p>');
            }
        });
        
        window.scrollTo(0, 0);
    }
    
    function renderUserProfile(profile) {
        var isOwnProfile = apexNotesData.isLoggedIn && apexNotesData.currentUser && apexNotesData.currentUser.id === profile.id;
        var avatarHtml = profile.avatar ? '<img src="' + escapeHtml(profile.avatar) + '" alt="">' : profile.name.charAt(0).toUpperCase();
        var isFollowing = profile.is_following || false;
        
        var html = '<div class="apex-profile-page">' +
            '<div class="apex-profile-header">' +
            '<div class="apex-profile-avatar-large">' + avatarHtml + '</div>' +
            '<div class="apex-profile-info">' +
            '<h1 class="apex-profile-name">' + escapeHtml(profile.name) + '</h1>' +
            '<p class="apex-profile-joined">Joined ' + (profile.joined || 'recently') + '</p>' +
            (profile.bio ? '<p class="apex-profile-bio">' + escapeHtml(profile.bio) + '</p>' : '');
        
        // Profile stats with followers/following
        html += '<div class="apex-profile-stats">' +
            '<div class="apex-stat-item" data-action="show-notes">' +
            '<span class="apex-stat-value">' + profile.notes_count + '</span>' +
            '<span class="apex-stat-label">Notes</span>' +
            '</div>' +
            '<div class="apex-stat-item" data-action="show-followers" data-user-id="' + profile.id + '">' +
            '<span class="apex-stat-value" id="apex-follower-count-' + profile.id + '">' + (profile.follower_count || 0) + '</span>' +
            '<span class="apex-stat-label">Followers</span>' +
            '</div>' +
            '<div class="apex-stat-item" data-action="show-following" data-user-id="' + profile.id + '">' +
            '<span class="apex-stat-value">' + (profile.following_count || 0) + '</span>' +
            '<span class="apex-stat-label">Following</span>' +
            '</div>' +
            '</div>';
        
        // Action buttons
        html += '<div class="apex-profile-actions">';
        
        if (!isOwnProfile && apexNotesData.isLoggedIn) {
            html += '<button class="apex-follow-btn ' + (isFollowing ? 'following' : '') + '" data-user-id="' + profile.id + '">' +
                '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>' +
                '<span class="apex-follow-text">' + (isFollowing ? 'Following' : 'Follow') + '</span>' +
                '<span class="apex-unfollow-text">Unfollow</span>' +
                '</button>';
        }
        
        if (isOwnProfile) {
            html += '<button class="apex-btn apex-btn-secondary" id="apex-edit-profile-btn">Edit Profile</button>';
        }
        
        html += '</div>'; // Close actions
        
        // Social links
        if (profile.discord_username || profile.steam_id) {
            html += '<div class="apex-profile-links" style="margin-top:16px;">';
            
            if (profile.discord_username) {
                html += '<a href="https://discord.com/users/' + escapeHtml(profile.discord_username) + '" target="_blank" class="apex-profile-link">' +
                    '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0 12.64 12.64 0 0 0-.617-1.25.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057 19.9 19.9 0 0 0 5.993 3.03.078.078 0 0 0 .084-.028c.462-.63.874-1.295 1.226-1.994a.076.076 0 0 0-.041-.106 13.107 13.107 0 0 1-1.872-.892.077.077 0 0 1-.008-.128 10.2 10.2 0 0 0 .372-.292.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127 12.299 12.299 0 0 1-1.873.892.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028 19.839 19.839 0 0 0 6.002-3.03.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03z"/></svg>' +
                    escapeHtml(profile.discord_username) +
                    '</a>';
            }
            
            if (profile.steam_id) {
                html += '<a href="https://steamcommunity.com/id/' + escapeHtml(profile.steam_id) + '" target="_blank" class="apex-profile-link">' +
                    '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M11.979 0C5.678 0 .511 4.86.022 11.037l6.432 2.658c.545-.371 1.203-.59 1.912-.59.063 0 .125.004.188.006l2.861-4.142V8.91c0-2.495 2.028-4.524 4.524-4.524 2.494 0 4.524 2.031 4.524 4.527s-2.03 4.525-4.524 4.525h-.105l-4.076 2.911c0 .052.004.105.004.159 0 1.875-1.515 3.396-3.39 3.396-1.635 0-3.016-1.173-3.331-2.727L.436 15.27C1.862 20.307 6.486 24 11.979 24c6.627 0 11.999-5.373 11.999-12S18.605 0 11.979 0z"/></svg>' +
                    'Steam Profile' +
                    '</a>';
            }
            
            html += '</div>';
        }
        
        html += '</div></div>'; // Close profile-info and profile-header
        
        // Edit form (hidden by default)
        if (isOwnProfile) {
            html += '<div class="apex-profile-edit" id="apex-profile-edit" style="display:none;">' +
                '<h3>Edit Profile</h3>' +
                '<div class="apex-profile-form-row">' +
                '<label>Display Name</label>' +
                '<input type="text" id="apex-profile-display-name" value="' + escapeHtml(profile.name) + '">' +
                '</div>' +
                '<div class="apex-profile-form-row">' +
                '<label>Bio</label>' +
                '<textarea id="apex-profile-bio">' + escapeHtml(profile.bio || '') + '</textarea>' +
                '</div>' +
                '<div class="apex-profile-form-row">' +
                '<label>Discord Username</label>' +
                '<input type="text" id="apex-profile-discord" value="' + escapeHtml(profile.discord_username || '') + '">' +
                '<p class="apex-profile-form-hint">Your Discord username (e.g., username#1234)</p>' +
                '</div>' +
                '<div class="apex-profile-form-row">' +
                '<label>Steam ID</label>' +
                '<input type="text" id="apex-profile-steam" value="' + escapeHtml(profile.steam_id || '') + '">' +
                '<p class="apex-profile-form-hint">Your Steam custom URL or ID</p>' +
                '</div>' +
                '<div style="display:flex;gap:12px;margin-top:20px;">' +
                '<button class="apex-btn apex-btn-primary" id="apex-save-profile">Save Changes</button>' +
                '<button class="apex-btn apex-btn-secondary" id="apex-cancel-edit">Cancel</button>' +
                '</div>' +
                '</div>';
        }
        
        // Livery Section (for own profile or viewing others' active livery)
        html += '<div class="apex-profile-livery" id="apex-profile-livery">' +
            '<div class="apex-livery-header">' +
            '<h3><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><path d="M9 17h6"/><circle cx="17" cy="17" r="2"/></svg> Livery Sharing</h3>';
        
        if (isOwnProfile) {
            html += '<span class="apex-livery-hint">Share your custom livery for 48 hours</span>';
        }
        
        html += '</div>' +
            '<div class="apex-livery-content" id="apex-livery-content">' +
            '<div class="apex-loading"><div class="apex-spinner"></div></div>' +
            '</div>' +
            '</div>';
        
        // Stewards Reports Section (only for own profile)
        if (isOwnProfile) {
            html += '<div class="apex-profile-stewards-reports" id="apex-profile-stewards-reports">' +
                '<div class="apex-stewards-reports-header">' +
                '<h3><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg> Stewards Reports</h3>' +
                '<span class="apex-stewards-reports-hint">Save up to 3 race reports to share</span>' +
                '</div>' +
                '<div class="apex-stewards-reports-content" id="apex-stewards-reports-content">' +
                '<div class="apex-loading"><div class="apex-spinner"></div></div>' +
                '</div>' +
                '</div>';
        }
        
        // User's notes
        html += '<div class="apex-profile-notes"><h3>Notes by ' + escapeHtml(profile.name) + '</h3>';
        
        if (profile.notes && profile.notes.length > 0) {
            html += '<div class="apex-notes-grid">';
            profile.notes.forEach(function(note) {
                html += renderNoteCardHtml(note);
            });
            html += '</div>';
        } else {
            html += '<p style="color:var(--apex-text-muted);">No notes yet.</p>';
        }
        
        html += '</div></div>';
        
        $('#apex-profile-content').html(html);
        
        // Load livery data
        loadUserLivery(profile.id, isOwnProfile);
        
        // Load stewards reports (only for own profile)
        if (isOwnProfile) {
            loadUserStewardsReports();
        }
    }
    
    // Load user's livery
    function loadUserLivery(userId, isOwnProfile) {
        var $container = $('#apex-livery-content');
        
        $.ajax({
            url: apexNotesData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'apex_notes_get_user_livery',
                user_id: userId
            },
            success: function(response) {
                if (response.success) {
                    renderLiverySection(response.data.livery, isOwnProfile);
                } else {
                    $container.html('<p style="color:var(--apex-text-muted);">Failed to load livery.</p>');
                }
            },
            error: function() {
                $container.html('<p style="color:var(--apex-text-muted);">Failed to load livery.</p>');
            }
        });
    }
    
    // Render livery section
    function renderLiverySection(livery, isOwnProfile) {
        var $container = $('#apex-livery-content');
        var html = '';
        
        if (livery) {
            // Has active livery
            html += '<div class="apex-livery-active">' +
                '<div class="apex-livery-info">' +
                '<div class="apex-livery-icon">' +
                '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--apex-orange)" stroke-width="2"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><path d="M9 17h6"/><circle cx="17" cy="17" r="2"/></svg>' +
                '</div>' +
                '<div class="apex-livery-details">' +
                '<h4>' + escapeHtml(livery.livery_name) + '</h4>' +
                '<p class="apex-livery-files">' +
                '<span>' + escapeHtml(livery.file_1_name) + '</span>' +
                '<span>' + escapeHtml(livery.file_2_name) + '</span>' +
                '</p>' +
                '<p class="apex-livery-meta">' +
                '<span class="apex-livery-time"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg> ' + livery.time_remaining + ' remaining</span>' +
                '<span class="apex-livery-downloads"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg> ' + livery.download_count + ' downloads</span>' +
                '</p>' +
                '</div>' +
                '</div>' +
                '<div class="apex-livery-actions">' +
                '<div class="apex-livery-share-url">' +
                '<input type="text" value="' + escapeHtml(livery.share_url) + '" readonly id="apex-livery-url">' +
                '<button class="apex-btn apex-btn-secondary apex-copy-livery-url" title="Copy URL"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg></button>' +
                '</div>' +
                '<a href="https://discord.com/channels" target="_blank" class="apex-btn apex-btn-discord apex-share-livery-discord">' +
                '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0 12.64 12.64 0 0 0-.617-1.25.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057 19.9 19.9 0 0 0 5.993 3.03.078.078 0 0 0 .084-.028c.462-.63.874-1.295 1.226-1.994a.076.076 0 0 0-.041-.106 13.107 13.107 0 0 1-1.872-.892.077.077 0 0 1-.008-.128 10.2 10.2 0 0 0 .372-.292.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127 12.299 12.299 0 0 1-1.873.892.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028 19.839 19.839 0 0 0 6.002-3.03.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03z"/></svg>' +
                'Share to Discord' +
                '</a>';
            
            if (isOwnProfile) {
                html += '<button class="apex-btn apex-btn-danger apex-delete-livery" data-livery-id="' + livery.id + '">' +
                    '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>' +
                    'Delete' +
                    '</button>';
            }
            
            html += '</div></div>';
        } else if (isOwnProfile) {
            // No livery, show upload form
            html += '<div class="apex-livery-upload">' +
                '<form id="apex-livery-form" enctype="multipart/form-data">' +
                '<div class="apex-livery-form-row">' +
                '<label>Livery Name</label>' +
                '<input type="text" id="apex-livery-name" placeholder="My Custom Livery">' +
                '</div>' +
                '<div class="apex-livery-form-row">' +
                '<label>customskin.tga</label>' +
                '<input type="file" id="apex-livery-file-1" accept=".tga">' +
                '</div>' +
                '<div class="apex-livery-form-row">' +
                '<label>customskin_region.tga</label>' +
                '<input type="file" id="apex-livery-file-2" accept=".tga">' +
                '</div>' +
                '<div class="apex-livery-legal">' +
                '<label class="apex-checkbox-label">' +
                '<input type="checkbox" id="apex-livery-legal">' +
                '<span class="apex-checkbox-text">I confirm that I have the right to share this livery and that it does not contain any illegal, offensive, or copyrighted content. I understand that my IP address and Discord ID will be recorded. I take sole responsibility for this upload.</span>' +
                '</label>' +
                '</div>' +
                '<div class="apex-livery-progress" id="apex-livery-progress" style="display:none;">' +
                '<div class="apex-progress-bar">' +
                '<div class="apex-progress-fill" id="apex-progress-fill" style="width:0%"></div>' +
                '</div>' +
                '<span class="apex-progress-text" id="apex-progress-text">Uploading... 0%</span>' +
                '</div>' +
                '<p class="apex-livery-notice">' +
                '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>' +
                'Files must be named exactly <strong>customskin.tga</strong> and <strong>customskin_region.tga</strong>. Liveries expire and are deleted after 48 hours. Only one livery can be shared at a time.' +
                '</p>' +
                '<button type="submit" class="apex-btn apex-btn-primary apex-upload-livery-btn" id="apex-upload-livery-btn">' +
                '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>' +
                'Share Livery' +
                '</button>' +
                '</form>' +
                '</div>';
        } else {
            // Not own profile and no livery
            html += '<p class="apex-livery-empty">No livery currently shared.</p>';
        }
        
        $container.html(html);
    }
    
    // Load user's stewards reports for profile
    function loadUserStewardsReports() {
        var $container = $('#apex-stewards-reports-content');
        if (!$container.length) return;
        
        $.ajax({
            url: apexNotesData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'apex_notes_get_my_stewards_reports'
            },
            success: function(response) {
                if (response.success) {
                    renderStewardsReportsSection(response.data.reports);
                } else {
                    $container.html('<p style="color:var(--apex-text-muted);">Failed to load reports.</p>');
                }
            },
            error: function() {
                $container.html('<p style="color:var(--apex-text-muted);">Failed to load reports.</p>');
            }
        });
    }
    
    // Render stewards reports section in profile
    function renderStewardsReportsSection(reports) {
        var $container = $('#apex-stewards-reports-content');
        var html = '<div class="apex-stewards-reports-grid">';
        
        // Always show 3 boxes
        for (var i = 0; i < 3; i++) {
            var report = reports[i];
            
            if (report) {
                // Clean up the title - if it contains track name, just use "Race Report"
                var displayTitle = report.report_name || 'Race Report';
                if (displayTitle.indexOf(report.track_name) === 0) {
                    displayTitle = 'Race Report';
                }
                
                // Format date nicely
                var dateDisplay = report.race_date || 'Unknown date';
                
                html += '<div class="apex-stewards-report-box filled" data-id="' + report.id + '">' +
                    '<div class="apex-stewards-report-icon">' +
                    '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>' +
                    '</div>' +
                    '<h4>' + escapeHtml(displayTitle) + '</h4>' +
                    '<p class="apex-report-track">' + escapeHtml(report.track_name) + '</p>' +
                    '<p class="apex-report-meta">' + escapeHtml(dateDisplay) + '</p>' +
                    '<p class="apex-report-views">' + (report.view_count || 0) + ' views</p>' +
                    '<div class="apex-stewards-report-actions">' +
                    '<a href="' + escapeHtml(report.share_url) + '" target="_blank" class="apex-btn apex-btn-sm apex-btn-secondary">View</a>' +
                    '<button class="apex-btn apex-btn-sm apex-btn-secondary apex-copy-stewards-link" data-url="' + escapeHtml(report.share_url) + '">Copy Link</button>' +
                    '<button class="apex-btn apex-btn-sm apex-btn-danger apex-delete-stewards-report" data-id="' + report.id + '">Delete</button>' +
                    '</div>' +
                    '</div>';
            } else {
                html += '<div class="apex-stewards-report-box empty">' +
                    '<div class="apex-stewards-report-icon empty-icon">' +
                    '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>' +
                    '</div>' +
                    '<p class="apex-report-empty-text">Empty Slot</p>' +
                    '<p class="apex-report-empty-hint">Generate a race report in the Stewards Room to save here</p>' +
                    '</div>';
            }
        }
        
        html += '</div>';
        $container.html(html);
        
        // Bind copy link buttons
        $container.find('.apex-copy-stewards-link').on('click', function() {
            var url = $(this).data('url');
            var $btn = $(this);
            
            var $temp = $('<input>');
            $('body').append($temp);
            $temp.val(url).select();
            document.execCommand('copy');
            $temp.remove();
            
            $btn.text('Copied!');
            setTimeout(function() {
                $btn.text('Copy Link');
            }, 2000);
        });
        
        // Bind delete buttons
        $container.find('.apex-delete-stewards-report').on('click', function() {
            var reportId = $(this).data('id');
            if (confirm('Are you sure you want to delete this report?')) {
                deleteUserStewardsReport(reportId);
            }
        });
    }
    
    // Delete a stewards report from profile
    function deleteUserStewardsReport(reportId) {
        $.ajax({
            url: apexNotesData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'apex_notes_delete_stewards_report',
                report_id: reportId
            },
            success: function(response) {
                if (response.success) {
                    showNotification('Report deleted.', 'success');
                    loadUserStewardsReports();
                    loadStewardsReportCount();
                } else {
                    showNotification(response.data.message || 'Failed to delete report.', 'error');
                }
            },
            error: function() {
                showNotification('Error deleting report.', 'error');
            }
        });
    }

    // Expose moderation function
    window.ApexNotes = {
        moderate: function(noteId, status) {
            $.ajax({
                url: apexNotesData.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'apex_notes_moderate_note',
                    nonce: apexNotesData.nonce,
                    note_id: noteId,
                    status: status
                },
                success: function(response) {
                    if (response.success) {
                        showNotification('Note ' + status, 'success');
                        loadModeration($('.apex-mod-tab.active').data('status'));
                    }
                }
            });
        }
    };
    
    // Edit note modal
    function openEditNoteModal(noteId) {
        var note = state.currentNote;
        if (!note || note.id != noteId) {
            showNotification('Note data not available.', 'error');
            return;
        }
        
        var html = '<div class="apex-modal-overlay active" id="apex-edit-modal">' +
            '<div class="apex-modal apex-edit-modal apex-edit-modal-full">' +
            '<button class="apex-modal-close">&times;</button>' +
            '<h2>Edit Track Note</h2>' +
            '<div class="apex-edit-form">' +
            '<div class="apex-edit-section">' +
            '<h3>Basic Information</h3>' +
            '<div class="apex-form-row">' +
            '<label>Title</label>' +
            '<input type="text" id="apex-edit-title" value="' + escapeHtml(note.title) + '">' +
            '</div>' +
            '<div class="apex-form-row">' +
            '<label>Description</label>' +
            '<textarea id="apex-edit-description" rows="3">' + escapeHtml(note.description || '') + '</textarea>' +
            '</div>' +
            '<div class="apex-form-grid">' +
            '<div class="apex-form-row">' +
            '<label>Lap Time</label>' +
            '<input type="text" id="apex-edit-laptime" value="' + escapeHtml(note.lap_time || '') + '" placeholder="1:23.456">' +
            '</div>' +
            '</div>' +
            '<div class="apex-form-row">' +
            '<label>Setup Notes</label>' +
            '<textarea id="apex-edit-setup" rows="3" placeholder="TC, ABS, Brake Bias, etc.">' + escapeHtml(note.setup_notes || '') + '</textarea>' +
            '</div>' +
            '</div>';
        
        // Sections editing
        html += '<div class="apex-edit-section">' +
            '<h3>Track Walkthrough Sections</h3>' +
            '<div id="apex-edit-sections-list">';
        
        if (note.sections && note.sections.length > 0) {
            $.each(note.sections, function(i, section) {
                html += renderEditSection(i, section);
            });
        }
        
        html += '</div>' +
            '<button type="button" class="apex-btn apex-btn-secondary" id="apex-edit-add-section">' +
            '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>' +
            'Add Section</button>' +
            '</div>';
        
        html += '<div class="apex-form-actions">' +
            '<button class="apex-btn apex-btn-primary" id="apex-save-edit-note" data-note-id="' + note.id + '">Save Changes</button>' +
            '<button class="apex-btn apex-btn-secondary apex-modal-close">Cancel</button>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>';
        
        $('#apex-edit-modal').remove();
        $('#apex-notes-app').append(html);
        
        // Bind add section button
        $('#apex-edit-add-section').on('click', function() {
            var newIndex = $('#apex-edit-sections-list .apex-edit-section-item').length;
            $('#apex-edit-sections-list').append(renderEditSection(newIndex, {}));
        });
        
        // Bind remove section buttons
        $(document).on('click', '.apex-edit-remove-section', function() {
            $(this).closest('.apex-edit-section-item').remove();
            // Renumber sections
            $('#apex-edit-sections-list .apex-edit-section-item').each(function(i) {
                $(this).find('.apex-edit-section-number').text('Section ' + (i + 1));
                $(this).attr('data-section-index', i);
            });
        });
    }
    
    function renderEditSection(index, section) {
        return '<div class="apex-edit-section-item" data-section-index="' + index + '">' +
            '<div class="apex-edit-section-header">' +
            '<span class="apex-edit-section-number">Section ' + (index + 1) + '</span>' +
            '<button type="button" class="apex-btn apex-btn-ghost apex-btn-small apex-edit-remove-section">' +
            '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>' +
            '</button>' +
            '</div>' +
            '<div class="apex-form-row">' +
            '<label>Section Name (e.g., T1, Maggots-Becketts)</label>' +
            '<input type="text" class="apex-edit-section-name" value="' + escapeHtml(section.name || '') + '" placeholder="Turn name or number">' +
            '</div>' +
            '<div class="apex-form-grid apex-form-grid-3">' +
            '<div class="apex-form-row">' +
            '<label>Braking</label>' +
            '<input type="text" class="apex-edit-section-braking" value="' + escapeHtml(section.braking || '') + '" placeholder="100m board">' +
            '</div>' +
            '<div class="apex-form-row">' +
            '<label>Turn-In</label>' +
            '<input type="text" class="apex-edit-section-turnin" value="' + escapeHtml(section.turn_in || '') + '" placeholder="At the kerb">' +
            '</div>' +
            '<div class="apex-form-row">' +
            '<label>Apex</label>' +
            '<input type="text" class="apex-edit-section-apex" value="' + escapeHtml(section.apex || '') + '" placeholder="Tight to kerb">' +
            '</div>' +
            '<div class="apex-form-row">' +
            '<label>Exit</label>' +
            '<input type="text" class="apex-edit-section-exit" value="' + escapeHtml(section.exit_point || '') + '" placeholder="Use all the road">' +
            '</div>' +
            '<div class="apex-form-row">' +
            '<label>Gear</label>' +
            '<input type="text" class="apex-edit-section-gear" value="' + escapeHtml(section.gear || '') + '" placeholder="3rd">' +
            '</div>' +
            '<div class="apex-form-row">' +
            '<label>Speed</label>' +
            '<input type="text" class="apex-edit-section-speed" value="' + escapeHtml(section.speed || '') + '" placeholder="80-90 km/h">' +
            '</div>' +
            '</div>' +
            '<div class="apex-form-row">' +
            '<label>Pro Tip (optional)</label>' +
            '<input type="text" class="apex-edit-section-protip" value="' + escapeHtml(section.pro_tip || '') + '" placeholder="Trail brake into the apex...">' +
            '</div>' +
            '</div>';
    }
    
    function saveEditedNote() {
        var noteId = $('#apex-save-edit-note').data('note-id');
        var $btn = $('#apex-save-edit-note');
        $btn.prop('disabled', true).text('Saving...');
        
        // Collect sections data
        var sections = [];
        $('#apex-edit-sections-list .apex-edit-section-item').each(function() {
            var $item = $(this);
            sections.push({
                name: $item.find('.apex-edit-section-name').val(),
                braking: $item.find('.apex-edit-section-braking').val(),
                turn_in: $item.find('.apex-edit-section-turnin').val(),
                apex: $item.find('.apex-edit-section-apex').val(),
                exit_point: $item.find('.apex-edit-section-exit').val(),
                gear: $item.find('.apex-edit-section-gear').val(),
                speed: $item.find('.apex-edit-section-speed').val(),
                pro_tip: $item.find('.apex-edit-section-protip').val()
            });
        });
        
        $.ajax({
            url: apexNotesData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'apex_notes_edit_note',
                nonce: apexNotesData.nonce,
                note_id: noteId,
                title: $('#apex-edit-title').val(),
                description: $('#apex-edit-description').val(),
                lap_time: $('#apex-edit-laptime').val(),
                setup_notes: $('#apex-edit-setup').val(),
                sections: JSON.stringify(sections)
            },
            success: function(response) {
                if (response.success) {
                    showNotification('Note updated!', 'success');
                    $('#apex-edit-modal').removeClass('active');
                    setTimeout(function() {
                        $('#apex-edit-modal').remove();
                    }, 300);
                    // Reload the note
                    viewNote(noteId);
                } else {
                    showNotification(response.data.message || 'Failed to update note.', 'error');
                }
                $btn.prop('disabled', false).text('Save Changes');
            },
            error: function() {
                showNotification('Failed to update note.', 'error');
                $btn.prop('disabled', false).text('Save Changes');
            }
        });
    }
    
    // Make renderComment available
    window.renderComment = renderComment;

    // ================================================
    // Notifications System
    // ================================================
    
    // Toggle notifications dropdown
    $(document).on('click', '#apex-notifications-toggle', function(e) {
        e.stopPropagation();
        var $dropdown = $('#apex-notifications-dropdown');
        var isActive = $dropdown.hasClass('active');
        
        // Close other dropdowns
        $('.apex-user-dropdown').removeClass('active');
        
        if (!isActive) {
            $dropdown.addClass('active');
            loadNotifications();
        } else {
            $dropdown.removeClass('active');
        }
    });
    
    // Close notifications dropdown when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.apex-notifications-menu').length) {
            $('#apex-notifications-dropdown').removeClass('active');
        }
    });
    
    // Load notifications
    function loadNotifications() {
        var $list = $('#apex-notifications-list');
        $list.html('<div class="apex-loading"><div class="apex-spinner"></div></div>');
        
        $.ajax({
            url: apexNotesData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'apex_notes_get_notifications',
                nonce: apexNotesData.nonce
            },
            success: function(response) {
                if (response.success) {
                    renderNotifications(response.data.notifications);
                    updateNotificationBadge(response.data.unread_count);
                } else {
                    $list.html('<div class="apex-notifications-empty">Failed to load notifications</div>');
                }
            },
            error: function() {
                $list.html('<div class="apex-notifications-empty">Failed to load notifications</div>');
            }
        });
    }
    
    // Render notifications list
    function renderNotifications(notifications) {
        var $list = $('#apex-notifications-list');
        
        if (!notifications || notifications.length === 0) {
            $list.html(
                '<div class="apex-notifications-empty">' +
                '<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">' +
                '<path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>' +
                '<path d="M13.73 21a2 2 0 0 1-3.46 0"/>' +
                '</svg>' +
                '<p>No notifications yet</p>' +
                '</div>'
            );
            return;
        }
        
        var html = '';
        notifications.forEach(function(notif) {
            var iconClass = 'post';
            var iconSvg = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg>';
            
            if (notif.type === 'new_follower') {
                iconClass = 'follow';
                iconSvg = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>';
            } else if (notif.type === 'new_comment') {
                iconClass = 'comment';
                iconSvg = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>';
            }
            
            var avatarHtml = notif.actor_avatar 
                ? '<img src="' + escapeHtml(notif.actor_avatar) + '" alt="">' 
                : (notif.actor_name ? notif.actor_name.charAt(0).toUpperCase() : '?');
            
            html += '<div class="apex-notification-item ' + (notif.is_read == 0 ? 'unread' : '') + '" ' +
                'data-notification-id="' + notif.id + '" ' +
                'data-type="' + notif.type + '" ' +
                'data-note-id="' + (notif.note_id || '') + '" ' +
                'data-actor-id="' + (notif.actor_id || '') + '">' +
                '<div class="apex-notification-avatar">' + avatarHtml + '</div>' +
                '<div class="apex-notification-content">' +
                '<div class="apex-notification-text">' + escapeHtml(notif.message) + '</div>' +
                '<div class="apex-notification-time">' + formatTimeAgo(notif.created_at) + '</div>' +
                '</div>' +
                '<div class="apex-notification-icon ' + iconClass + '">' + iconSvg + '</div>' +
                '</div>';
        });
        
        $list.html(html);
    }
    
    // Format time ago
    function formatTimeAgo(dateString) {
        var date = new Date(dateString);
        var now = new Date();
        var seconds = Math.floor((now - date) / 1000);
        
        if (seconds < 60) return 'just now';
        if (seconds < 3600) return Math.floor(seconds / 60) + 'm ago';
        if (seconds < 86400) return Math.floor(seconds / 3600) + 'h ago';
        if (seconds < 604800) return Math.floor(seconds / 86400) + 'd ago';
        return date.toLocaleDateString();
    }
    
    // Update notification badge
    function updateNotificationBadge(count) {
        var $badge = $('#apex-notifications-badge');
        if (count > 0) {
            $badge.text(count > 9 ? '9+' : count).show();
        } else {
            $badge.hide();
        }
    }
    
    // Click notification item
    $(document).on('click', '.apex-notification-item', function() {
        var $item = $(this);
        var notificationId = $item.data('notification-id');
        var type = $item.data('type');
        var noteId = $item.data('note-id');
        var actorId = $item.data('actor-id');
        
        // Mark as read
        $.ajax({
            url: apexNotesData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'apex_notes_mark_notification_read',
                nonce: apexNotesData.nonce,
                notification_id: notificationId
            },
            success: function(response) {
                if (response.success) {
                    $item.removeClass('unread');
                    updateNotificationBadge(response.data.unread_count);
                }
            }
        });
        
        // Navigate based on type
        $('#apex-notifications-dropdown').removeClass('active');
        
        if (type === 'new_follower' && actorId) {
            showUserProfile(actorId);
        } else if (noteId) {
            viewNote(noteId);
        }
    });
    
    // Mark all notifications as read
    $(document).on('click', '#apex-mark-all-read', function(e) {
        e.stopPropagation();
        
        $.ajax({
            url: apexNotesData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'apex_notes_mark_all_notifications_read',
                nonce: apexNotesData.nonce
            },
            success: function(response) {
                if (response.success) {
                    $('.apex-notification-item').removeClass('unread');
                    updateNotificationBadge(0);
                }
            }
        });
    });
    
    // ================================================
    // Follow System
    // ================================================
    
    // Follow/unfollow button click
    $(document).on('click', '.apex-follow-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        if (!apexNotesData.isLoggedIn) {
            showNotification('Please sign in to follow users.', 'error');
            return;
        }
        
        var $btn = $(this);
        var userId = $btn.data('user-id');
        var isFollowing = $btn.hasClass('following');
        var action = isFollowing ? 'apex_notes_unfollow_user' : 'apex_notes_follow_user';
        
        $btn.prop('disabled', true);
        
        $.ajax({
            url: apexNotesData.ajaxUrl,
            type: 'POST',
            data: {
                action: action,
                nonce: apexNotesData.nonce,
                user_id: userId
            },
            success: function(response) {
                if (response.success) {
                    if (isFollowing) {
                        $btn.removeClass('following');
                        $btn.find('.apex-follow-text').text('Follow');
                    } else {
                        $btn.addClass('following');
                        $btn.find('.apex-follow-text').text('Following');
                    }
                    // Update follower count
                    $('#apex-follower-count-' + userId).text(response.data.follower_count);
                } else {
                    showNotification(response.data.message || 'Action failed.', 'error');
                }
                $btn.prop('disabled', false);
            },
            error: function() {
                showNotification('Failed to process request.', 'error');
                $btn.prop('disabled', false);
            }
        });
    });
    
    // Show followers modal
    $(document).on('click', '[data-action="show-followers"]', function() {
        var userId = $(this).data('user-id');
        showFollowersModal(userId, 'followers');
    });
    
    // Show following modal
    $(document).on('click', '[data-action="show-following"]', function() {
        var userId = $(this).data('user-id');
        showFollowersModal(userId, 'following');
    });
    
    // Show followers/following modal
    function showFollowersModal(userId, type) {
        var title = type === 'followers' ? 'Followers' : 'Following';
        
        var html = '<div class="apex-modal-overlay active" id="apex-followers-modal">' +
            '<div class="apex-modal" style="max-width:400px;">' +
            '<div class="apex-modal-header">' +
            '<h3>' + title + '</h3>' +
            '<button class="apex-modal-close">&times;</button>' +
            '</div>' +
            '<div class="apex-modal-body">' +
            '<div class="apex-followers-list" id="apex-followers-list">' +
            '<div class="apex-loading"><div class="apex-spinner"></div></div>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>';
        
        $('#apex-followers-modal').remove();
        $('#apex-notes-app').append(html);
        
        // Load data
        $.ajax({
            url: apexNotesData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'apex_notes_get_' + type,
                nonce: apexNotesData.nonce,
                user_id: userId
            },
            success: function(response) {
                if (response.success) {
                    renderFollowersList(response.data.users, type);
                } else {
                    $('#apex-followers-list').html('<p style="text-align:center;color:var(--apex-text-muted);padding:20px;">Failed to load</p>');
                }
            },
            error: function() {
                $('#apex-followers-list').html('<p style="text-align:center;color:var(--apex-text-muted);padding:20px;">Failed to load</p>');
            }
        });
    }
    
    // Render followers/following list
    function renderFollowersList(users, type) {
        var $list = $('#apex-followers-list');
        
        if (!users || users.length === 0) {
            $list.html('<p style="text-align:center;color:var(--apex-text-muted);padding:40px 20px;">No ' + type + ' yet</p>');
            return;
        }
        
        var html = '';
        users.forEach(function(user) {
            var avatarHtml = user.avatar 
                ? '<img src="' + escapeHtml(user.avatar) + '" alt="">' 
                : user.name.charAt(0).toUpperCase();
            
            var isCurrentUser = apexNotesData.currentUser && apexNotesData.currentUser.id === user.id;
            var followBtnHtml = '';
            
            if (!isCurrentUser && apexNotesData.isLoggedIn) {
                followBtnHtml = '<button class="apex-follow-btn apex-follow-btn-small ' + (user.is_following ? 'following' : '') + '" data-user-id="' + user.id + '">' +
                    '<span class="apex-follow-text">' + (user.is_following ? 'Following' : 'Follow') + '</span>' +
                    '<span class="apex-unfollow-text">Unfollow</span>' +
                    '</button>';
            }
            
            html += '<div class="apex-follower-item">' +
                '<div class="apex-follower-avatar">' + avatarHtml + '</div>' +
                '<div class="apex-follower-info">' +
                '<span class="apex-follower-name" data-user-id="' + user.id + '">' + escapeHtml(user.name) + '</span>' +
                '<span class="apex-follower-notes">' + (user.notes_count || 0) + ' notes</span>' +
                '</div>' +
                followBtnHtml +
                '</div>';
        });
        
        $list.html(html);
    }
    
    // Click follower name to view profile
    $(document).on('click', '.apex-follower-name', function() {
        var userId = $(this).data('user-id');
        $('#apex-followers-modal').removeClass('active').remove();
        showUserProfile(userId);
    });
    
    // Close followers modal
    $(document).on('click', '#apex-followers-modal .apex-modal-close, #apex-followers-modal.apex-modal-overlay', function(e) {
        if (e.target === this || $(e.target).hasClass('apex-modal-close')) {
            $('#apex-followers-modal').removeClass('active').remove();
        }
    });
    
    // Periodically check for new notifications (every 60 seconds)
    if (apexNotesData.isLoggedIn) {
        setInterval(function() {
            $.ajax({
                url: apexNotesData.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'apex_notes_get_notification_count',
                    nonce: apexNotesData.nonce
                },
                success: function(response) {
                    if (response.success) {
                        updateNotificationBadge(response.data.count);
                    }
                }
            });
        }, 60000);
    }

    // ================================================
    // AI Track Assistant
    // ================================================
    
    var aiChatHistory = [];
    
    // Send AI message on Enter key
    $(document).on('keydown', '#apex-ai-input', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendAIMessage();
        }
    });
    
    // Send AI message on button click
    $(document).on('click', '#apex-ai-send', function() {
        sendAIMessage();
    });
    
    // Click suggestion to populate input
    $(document).on('click', '.apex-ai-suggestion', function() {
        var query = $(this).data('query');
        $('#apex-ai-input').val(query);
        sendAIMessage();
    });
    
    // Send message to AI
    function sendAIMessage() {
        var $input = $('#apex-ai-input');
        var $sendBtn = $('#apex-ai-send');
        var message = $input.val().trim();
        
        if (!message) return;
        
        // Disable input while processing
        $input.val('').prop('disabled', true);
        $sendBtn.prop('disabled', true);
        
        // Add user message to chat
        addAIChatMessage(message, 'user');
        
        // Add to history
        aiChatHistory.push({ role: 'user', content: message });
        
        // Show typing indicator
        showAITyping();
        
        // Call server-side API
        $.ajax({
            url: apexNotesData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'apex_notes_ai_chat',
                nonce: apexNotesData.nonce,
                message: message,
                history: JSON.stringify(aiChatHistory.slice(-6)) // Last 3 exchanges
            },
            success: function(response) {
                hideAITyping();
                
                if (response.success) {
                    var reply = response.data.reply;
                    addAIChatMessage(reply, 'assistant');
                    aiChatHistory.push({ role: 'assistant', content: reply });
                } else {
                    addAIChatMessage('Sorry, I encountered an error. Please try again.', 'assistant');
                }
                
                $input.prop('disabled', false).focus();
                $sendBtn.prop('disabled', false);
            },
            error: function() {
                hideAITyping();
                addAIChatMessage('Sorry, there was a connection error. Please try again.', 'assistant');
                $input.prop('disabled', false).focus();
                $sendBtn.prop('disabled', false);
            }
        });
    }
    
    // Add message to chat
    function addAIChatMessage(content, role) {
        var $messages = $('#apex-ai-messages');
        
        var avatarSvg = role === 'assistant' 
            ? '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2a10 10 0 0 1 10 10c0 5.52-4.48 10-10 10S2 17.52 2 12 6.48 2 12 2z"/><path d="M12 8v4l3 3"/></svg>'
            : '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>';
        
        // Convert markdown-style formatting
        var formattedContent = formatAIResponse(content);
        
        var html = '<div class="apex-ai-message apex-ai-' + role + '">' +
            '<div class="apex-ai-avatar">' + avatarSvg + '</div>' +
            '<div class="apex-ai-bubble">' + formattedContent + '</div>' +
            '</div>';
        
        $messages.append(html);
        $messages.scrollTop($messages[0].scrollHeight);
    }
    
    // Format AI response with basic markdown
    function formatAIResponse(text) {
        // Escape HTML first
        text = escapeHtml(text);
        
        // Convert **bold** to <strong>
        text = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        
        // Convert *italic* to <em>
        text = text.replace(/\*(.*?)\*/g, '<em>$1</em>');
        
        // Convert ### headers
        text = text.replace(/^### (.*?)$/gm, '<h3>$1</h3>');
        
        // Convert bullet lists
        text = text.replace(/^- (.*?)$/gm, '<li>$1</li>');
        text = text.replace(/(<li>.*?<\/li>[\n]*)+/g, '<ul>$&</ul>');
        
        // Convert numbered lists
        text = text.replace(/^\d+\. (.*?)$/gm, '<li>$1</li>');
        
        // Convert line breaks to paragraphs
        var paragraphs = text.split(/\n\n+/);
        text = paragraphs.map(function(p) {
            p = p.trim();
            if (!p) return '';
            if (p.startsWith('<h3>') || p.startsWith('<ul>') || p.startsWith('<li>')) {
                return p;
            }
            return '<p>' + p.replace(/\n/g, '<br>') + '</p>';
        }).join('');
        
        return text;
    }
    
    // Show typing indicator
    function showAITyping() {
        var $messages = $('#apex-ai-messages');
        var html = '<div class="apex-ai-message apex-ai-assistant" id="apex-ai-typing-indicator">' +
            '<div class="apex-ai-avatar"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2a10 10 0 0 1 10 10c0 5.52-4.48 10-10 10S2 17.52 2 12 6.48 2 12 2z"/><path d="M12 8v4l3 3"/></svg></div>' +
            '<div class="apex-ai-bubble"><div class="apex-ai-typing"><span></span><span></span><span></span></div></div>' +
            '</div>';
        $messages.append(html);
        $messages.scrollTop($messages[0].scrollHeight);
    }
    
    // Hide typing indicator
    function hideAITyping() {
        $('#apex-ai-typing-indicator').remove();
    }
    
    // ==========================================
    // Live Events Functionality
    // ==========================================
    
    // Debug: Check if apexNotesData is available
    console.log('Live Events JS loaded');
    console.log('apexNotesData available:', typeof apexNotesData !== 'undefined');
    if (typeof apexNotesData !== 'undefined') {
        console.log('ajaxUrl:', apexNotesData.ajaxUrl);
    }
    
    var liveEventsCurrentMonth = new Date().getMonth();
    var liveEventsCurrentYear = new Date().getFullYear();
    var liveEventsData = [];
    
    // Initialize Live Events when page loads
    $(document).on('click', '[data-page="live-events"]', function() {
        console.log('Live Events page clicked');
        setTimeout(initLiveEvents, 100);
    });
    
    // Also init if we're already on the page
    if ($('.apex-page[data-page="live-events"]').is(':visible')) {
        console.log('Live Events page already visible');
        initLiveEvents();
    }
    
    function initLiveEvents() {
        console.log('Initializing Live Events');
        loadUserAlerts();
        loadLiveEvents();
        renderCalendar();
    }
    
    // Load user's alert preferences
    function loadUserAlerts() {
        console.log('Loading user alerts');
        $.ajax({
            url: apexNotesData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'apex_notes_get_event_alerts',
                nonce: apexNotesData.nonce
            },
            success: function(response) {
                console.log('User alerts response:', response);
                if (response.success && response.data.alerts) {
                    var alerts = response.data.alerts;
                    $('.apex-alert-checkbox').each(function() {
                        var channelId = $(this).data('channel');
                        if (alerts[channelId] !== undefined) {
                            $(this).prop('checked', alerts[channelId]);
                        }
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading alerts:', error);
            }
        });
    }
    
    // Handle alert toggle
    $(document).on('change', '.apex-alert-checkbox', function() {
        var $checkbox = $(this);
        var $toggle = $checkbox.closest('.apex-live-alert-toggle');
        var channelId = $checkbox.data('channel');
        var enabled = $checkbox.is(':checked');
        
        console.log('Toggle alert:', channelId, enabled);
        
        // Visual feedback
        $toggle.css('opacity', '0.5');
        
        $.ajax({
            url: apexNotesData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'apex_notes_set_event_alert',
                nonce: apexNotesData.nonce,
                channel_id: channelId,
                enabled: enabled ? 1 : 0
            },
            success: function(response) {
                console.log('Set alert response:', response);
                $toggle.css('opacity', '1');
            },
            error: function(xhr, status, error) {
                console.error('Error setting alert:', error);
                $toggle.css('opacity', '1');
                // Revert on error
                $checkbox.prop('checked', !enabled);
            }
        });
    });
    
    // Handle click on slider/label areas  
    $(document).on('click', '.apex-alert-slider, .apex-alert-label', function(e) {
        e.preventDefault();
        var $checkbox = $(this).closest('.apex-live-alert-toggle').find('.apex-alert-checkbox');
        $checkbox.prop('checked', !$checkbox.is(':checked')).trigger('change');
    });
    
    // Load live events from server
    function loadLiveEvents() {
        console.log('Loading live events for', liveEventsCurrentMonth + 1, liveEventsCurrentYear);
        $.ajax({
            url: apexNotesData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'apex_notes_get_live_events',
                nonce: apexNotesData.nonce,
                month: liveEventsCurrentMonth + 1,
                year: liveEventsCurrentYear
            },
            success: function(response) {
                console.log('Live events response:', response);
                if (response.success) {
                    liveEventsData = response.data.events || [];
                    renderLiveNow(response.data.live_now || []);
                    renderUpcomingEvents(response.data.upcoming || []);
                    renderCalendar();
                    
                    // Update nav button if there are live events
                    if (response.data.live_now && response.data.live_now.length > 0) {
                        $('.apex-nav-link[data-page="live-events"]').addClass('has-live');
                    } else {
                        $('.apex-nav-link[data-page="live-events"]').removeClass('has-live');
                    }
                }
            },
            error: function() {
                $('#apex-events-list').html('<div class="apex-live-empty"><p>Unable to load events. Please try again later.</p></div>');
            }
        });
    }
    
    // Render currently live streams
    function renderLiveNow(liveEvents) {
        var $container = $('#apex-live-now');
        
        if (!liveEvents || liveEvents.length === 0) {
            $container.html('<div class="apex-live-empty">' +
                '<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>' +
                '<p>No races currently live</p>' +
                '</div>');
            return;
        }
        
        var html = '';
        liveEvents.forEach(function(event) {
            var videoUrl = 'https://www.youtube.com/watch?v=' + encodeURIComponent(event.video_id);
            html += '<div class="apex-live-card is-live">' +
                '<div class="apex-live-card-thumb">' +
                '<img src="' + escapeHtml(event.thumbnail_url || 'https://i.ytimg.com/vi/' + event.video_id + '/hqdefault.jpg') + '" alt="">' +
                '<span class="apex-live-badge">LIVE</span>' +
                '</div>' +
                '<div class="apex-live-card-content">' +
                '<div class="apex-live-card-channel">' + escapeHtml(event.channel_name) + '</div>' +
                '<h3 class="apex-live-card-title">' + escapeHtml(event.title) + '</h3>' +
                '<a href="' + videoUrl + '" target="_blank" rel="noopener noreferrer" class="apex-live-card-link apex-watch-link" data-video-id="' + escapeHtml(event.video_id) + '">' +
                '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg> Watch Now' +
                '</a>' +
                '</div>' +
                '</div>';
        });
        
        $container.html(html);
    }
    
    // Handle Watch Now link clicks - open embedded player
    $(document).on('click', '.apex-live-card-link, .apex-event-watch-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        var $link = $(this);
        var videoId = $link.data('video-id') || $link.attr('href').split('v=')[1];
        var $card = $link.closest('.apex-live-card, .apex-event-item');
        var title = $card.find('.apex-live-card-title, .apex-event-title').text();
        var channel = $card.find('.apex-live-card-channel, .apex-event-channel').text();
        var isLive = $card.hasClass('is-live') || $link.text().indexOf('Watch') !== -1;
        
        console.log('Opening video player:', videoId, title);
        
        openVideoPlayer(videoId, title, channel, isLive);
        return false;
    });
    
    // Open the video player modal
    function openVideoPlayer(videoId, title, channel, isLive) {
        var $modal = $('#apex-video-modal');
        var $iframe = $('#apex-video-iframe');
        var embedUrl = 'https://www.youtube.com/embed/' + videoId + '?autoplay=1&rel=0';
        
        // Set modal content
        $('#apex-video-title').text(title || 'Live Stream');
        $('#apex-video-channel').text(channel || '');
        $('#apex-video-external').attr('href', 'https://www.youtube.com/watch?v=' + videoId);
        
        // Show/hide live badge
        if (isLive) {
            $('.apex-video-live-badge').show();
        } else {
            $('.apex-video-live-badge').hide();
        }
        
        // Set iframe src and show modal
        $iframe.attr('src', embedUrl);
        $modal.addClass('active');
        
        // Prevent body scroll
        $('body').css('overflow', 'hidden');
    }
    
    // Close video modal
    $(document).on('click', '#apex-video-close, .apex-video-modal-overlay', function(e) {
        if (e.target === this || $(e.target).is('#apex-video-close')) {
            closeVideoPlayer();
        }
    });
    
    // Close on escape key
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && $('#apex-video-modal').hasClass('active')) {
            closeVideoPlayer();
        }
    });
    
    function closeVideoPlayer() {
        var $modal = $('#apex-video-modal');
        var $iframe = $('#apex-video-iframe');
        
        // Stop video by clearing src
        $iframe.attr('src', '');
        $modal.removeClass('active');
        
        // Restore body scroll
        $('body').css('overflow', '');
    }
    
    // Render upcoming events list
    function renderUpcomingEvents(events) {
        var $container = $('#apex-events-list');
        
        if (!events || events.length === 0) {
            $container.html('<div class="apex-live-empty">' +
                '<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>' +
                '<p>No upcoming events scheduled</p>' +
                '</div>');
            return;
        }
        
        var html = '';
        events.forEach(function(event) {
            var isLive = event.status === 'live';
            var eventDate = event.scheduled_start ? new Date(event.scheduled_start) : new Date(event.created_at);
            var day = eventDate.getDate();
            var month = eventDate.toLocaleDateString('en-US', { month: 'short' });
            var time = eventDate.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });
            var videoUrl = 'https://www.youtube.com/watch?v=' + encodeURIComponent(event.video_id);
            
            html += '<div class="apex-event-item' + (isLive ? ' is-live' : '') + '">' +
                '<div class="apex-event-date">' +
                '<div class="apex-event-date-day">' + day + '</div>' +
                '<div class="apex-event-date-month">' + month + '</div>' +
                '</div>' +
                '<div class="apex-event-thumb">' +
                '<img src="' + escapeHtml(event.thumbnail_url || 'https://i.ytimg.com/vi/' + event.video_id + '/hqdefault.jpg') + '" alt="">' +
                '</div>' +
                '<div class="apex-event-info">' +
                '<div class="apex-event-channel">' + escapeHtml(event.channel_name) + '</div>' +
                '<h4 class="apex-event-title">' + escapeHtml(event.title) + '</h4>' +
                '<div class="apex-event-time">' + (isLive ? '🔴 Live Now' : time) + '</div>' +
                '</div>' +
                '<div class="apex-event-actions">' +
                '<a href="' + videoUrl + '" target="_blank" class="apex-event-watch-btn" data-video-id="' + escapeHtml(event.video_id) + '">' +
                '<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>' +
                (isLive ? 'Watch' : 'Preview') +
                '</a>' +
                '</div>' +
                '</div>';
        });
        
        $container.html(html);
    }
    
    // Render calendar
    function renderCalendar() {
        var $grid = $('#apex-calendar-grid');
        var $monthLabel = $('#apex-calendar-month');
        
        var date = new Date(liveEventsCurrentYear, liveEventsCurrentMonth, 1);
        var monthName = date.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
        $monthLabel.text(monthName);
        
        var firstDay = date.getDay();
        var daysInMonth = new Date(liveEventsCurrentYear, liveEventsCurrentMonth + 1, 0).getDate();
        var prevMonthDays = new Date(liveEventsCurrentYear, liveEventsCurrentMonth, 0).getDate();
        
        var today = new Date();
        var isCurrentMonth = today.getMonth() === liveEventsCurrentMonth && today.getFullYear() === liveEventsCurrentYear;
        
        var html = '';
        var dayCount = 1;
        var nextMonthDay = 1;
        
        // 6 weeks max
        for (var week = 0; week < 6; week++) {
            for (var dow = 0; dow < 7; dow++) {
                var cellDay, cellMonth, isOtherMonth, cellDate;
                
                if (week === 0 && dow < firstDay) {
                    // Previous month
                    cellDay = prevMonthDays - firstDay + dow + 1;
                    cellMonth = liveEventsCurrentMonth - 1;
                    isOtherMonth = true;
                } else if (dayCount > daysInMonth) {
                    // Next month
                    cellDay = nextMonthDay++;
                    cellMonth = liveEventsCurrentMonth + 1;
                    isOtherMonth = true;
                } else {
                    // Current month
                    cellDay = dayCount++;
                    cellMonth = liveEventsCurrentMonth;
                    isOtherMonth = false;
                }
                
                var isToday = isCurrentMonth && !isOtherMonth && cellDay === today.getDate();
                var classes = 'apex-calendar-day';
                if (isOtherMonth) classes += ' other-month';
                if (isToday) classes += ' today';
                
                // Get events for this day
                var dayEvents = getEventsForDay(cellDay, cellMonth, liveEventsCurrentYear);
                
                html += '<div class="' + classes + '">';
                html += '<div class="apex-calendar-day-num">' + cellDay + '</div>';
                
                dayEvents.forEach(function(event) {
                    var eventClass = 'event-' + event.status;
                    html += '<div class="apex-calendar-event ' + eventClass + '" title="' + escapeHtml(event.title) + '">' + 
                        truncateText(event.title, 15) + '</div>';
                });
                
                html += '</div>';
            }
            
            // Stop if we've rendered all days of current month and started next month
            if (dayCount > daysInMonth && nextMonthDay > 7) break;
        }
        
        $grid.html(html);
    }
    
    // Get events for a specific day
    function getEventsForDay(day, month, year) {
        if (!liveEventsData) return [];
        
        return liveEventsData.filter(function(event) {
            var eventDate = event.scheduled_start ? new Date(event.scheduled_start) : new Date(event.created_at);
            return eventDate.getDate() === day && 
                   eventDate.getMonth() === month && 
                   eventDate.getFullYear() === year;
        });
    }
    
    // Calendar navigation
    $(document).on('click', '#apex-calendar-prev', function() {
        liveEventsCurrentMonth--;
        if (liveEventsCurrentMonth < 0) {
            liveEventsCurrentMonth = 11;
            liveEventsCurrentYear--;
        }
        loadLiveEvents();
    });
    
    $(document).on('click', '#apex-calendar-next', function() {
        liveEventsCurrentMonth++;
        if (liveEventsCurrentMonth > 11) {
            liveEventsCurrentMonth = 0;
            liveEventsCurrentYear++;
        }
        loadLiveEvents();
    });
    
    // Helper: Truncate text
    function truncateText(text, maxLength) {
        if (text.length <= maxLength) return escapeHtml(text);
        return escapeHtml(text.substring(0, maxLength)) + '...';
    }
    
    // Admin: Manual refresh from YouTube
    $(document).on('click', '#apex-refresh-events', function(e) {
        e.preventDefault();
        console.log('Refresh button clicked');
        
        var $btn = $(this);
        var $status = $('#apex-refresh-status');
        
        $btn.prop('disabled', true).html('<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="apex-spin"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg> Refreshing...');
        $status.removeClass('success error').text('Fetching data from YouTube...');
        
        console.log('Making AJAX request to refresh_live_events');
        
        $.ajax({
            url: apexNotesData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'apex_notes_refresh_live_events',
                nonce: apexNotesData.nonce
            },
            success: function(response) {
                console.log('Refresh response:', response);
                $btn.prop('disabled', false).html('<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg> Refresh from YouTube');
                
                if (response.success) {
                    var eventCount = (response.data.events || []).length;
                    var liveCount = (response.data.live_now || []).length;
                    $status.addClass('success').text('✓ Found ' + eventCount + ' events, ' + liveCount + ' live');
                    
                    // Reload the display
                    liveEventsData = response.data.events || [];
                    renderLiveNow(response.data.live_now || []);
                    renderUpcomingEvents(response.data.events || []);
                    renderCalendar();
                } else {
                    $status.addClass('error').text('✗ ' + (response.data.message || 'Error refreshing events'));
                }
            },
            error: function(xhr, status, error) {
                console.error('Refresh error:', error, xhr.responseText);
                $btn.prop('disabled', false).html('<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg> Refresh from YouTube');
                $status.addClass('error').text('✗ Network error: ' + error);
            }
        });
    });
    
    // Import 2026 Race Calendars
    $(document).on('click', '#apex-import-calendars', function(e) {
        e.preventDefault();
        
        var $btn = $(this);
        var $status = $('#apex-refresh-status');
        
        $btn.prop('disabled', true);
        $status.removeClass('success error').text('Importing race calendars...');
        
        $.ajax({
            url: apexNotesData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'apex_notes_import_race_calendars',
                nonce: apexNotesData.nonce
            },
            success: function(response) {
                $btn.prop('disabled', false);
                if (response.success) {
                    $status.addClass('success').text('✓ ' + response.data.message);
                    loadLiveEvents(); // Refresh display
                } else {
                    $status.addClass('error').text('✗ ' + (response.data.message || 'Import failed'));
                }
            },
            error: function() {
                $btn.prop('disabled', false);
                $status.addClass('error').text('✗ Network error');
            }
        });
    });
    
    // Toggle Add Event Form
    $(document).on('click', '#apex-add-event-toggle', function() {
        $('#apex-add-event-form').slideToggle();
    });
    
    $(document).on('click', '#apex-cancel-event', function() {
        $('#apex-add-event-form').slideUp();
        // Clear form
        $('#apex-event-title, #apex-event-description').val('');
        $('#apex-event-channel').val('');
        $('#apex-event-datetime').val('');
    });
    
    // Save Manual Event
    $(document).on('click', '#apex-save-event', function() {
        var $btn = $(this);
        var title = $('#apex-event-title').val().trim();
        var channel = $('#apex-event-channel').val();
        var datetime = $('#apex-event-datetime').val();
        var description = $('#apex-event-description').val().trim();
        
        if (!title || !channel || !datetime) {
            alert('Please fill in title, channel, and date/time.');
            return;
        }
        
        $btn.prop('disabled', true).text('Saving...');
        
        $.ajax({
            url: apexNotesData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'apex_notes_add_manual_event',
                nonce: apexNotesData.nonce,
                title: title,
                channel: channel,
                scheduled_start: datetime.replace('T', ' ') + ':00',
                description: description
            },
            success: function(response) {
                $btn.prop('disabled', false).text('Save Event');
                if (response.success) {
                    // Clear form and hide
                    $('#apex-event-title, #apex-event-description').val('');
                    $('#apex-event-channel').val('');
                    $('#apex-event-datetime').val('');
                    $('#apex-add-event-form').slideUp();
                    $('#apex-refresh-status').addClass('success').text('✓ Event added!');
                    loadLiveEvents(); // Refresh display
                } else {
                    alert(response.data.message || 'Failed to add event');
                }
            },
            error: function() {
                $btn.prop('disabled', false).text('Save Event');
                alert('Network error');
            }
        });
    });
    
    // Auto-refresh live events every 2 minutes
    setInterval(function() {
        if ($('.apex-page[data-page="live-events"]').is(':visible')) {
            loadLiveEvents();
        }
    }, 120000);

    // ========================================
    // FUEL DATA FUNCTIONALITY
    // ========================================
    
    // Fuel Data Tab Switching
    $(document).on('click', '.apex-fuel-tab', function() {
        var tab = $(this).data('fuel-tab');
        
        $('.apex-fuel-tab').removeClass('active');
        $(this).addClass('active');
        
        $('.apex-fuel-tab-content').removeClass('active');
        $('.apex-fuel-tab-content[data-fuel-content="' + tab + '"]').addClass('active');
        
        // Load content for specific tabs
        if (tab === 'my-sessions') {
            loadMySessions();
        } else if (tab === 'community') {
            loadCommunityData();
        }
    });
    
    // File Upload - Click
    $(document).on('click', '#apex-fuel-dropzone', function(e) {
        if (e.target.id !== 'apex-fuel-file-input') {
            $('#apex-fuel-file-input').click();
        }
    });
    
    // File Upload - Change
    $(document).on('change', '#apex-fuel-file-input', function() {
        var file = this.files[0];
        if (file) {
            handleFuelFileUpload(file);
        }
    });
    
    // Drag and Drop
    var $dropzone = $('#apex-fuel-dropzone');
    
    $(document).on('dragover dragenter', '#apex-fuel-dropzone', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).addClass('dragover');
    });
    
    $(document).on('dragleave dragend', '#apex-fuel-dropzone', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).removeClass('dragover');
    });
    
    $(document).on('drop', '#apex-fuel-dropzone', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).removeClass('dragover');
        
        var files = e.originalEvent.dataTransfer.files;
        if (files.length > 0) {
            handleFuelFileUpload(files[0]);
        }
    });
    
    // Handle File Upload
    function handleFuelFileUpload(file) {
        // Validate file type
        if (!file.name.toLowerCase().endsWith('.xml')) {
            showNotification('Please select an XML file', 'error');
            return;
        }
        
        // Show share option
        $('#apex-fuel-share-option').show();
        
        // Show loading state
        var $dropzone = $('#apex-fuel-dropzone');
        var originalContent = $dropzone.html();
        $dropzone.html('<div class="apex-loading"><div class="apex-spinner"></div><p style="margin-top:1rem;color:rgba(255,255,255,0.6);">Analyzing session data...</p></div>');
        
        // Prepare form data
        var formData = new FormData();
        formData.append('action', 'apex_notes_upload_xml');
        formData.append('nonce', apexNotesData.nonce);
        formData.append('xml_file', file);
        formData.append('share_with_community', $('#apex-fuel-share-checkbox').is(':checked') ? '1' : '0');
        
        $.ajax({
            url: apexNotesData.ajaxUrl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    displayFuelResult(response.data);
                    showNotification('Session analyzed successfully!', 'success');
                } else {
                    $dropzone.html(originalContent);
                    showNotification(response.data.message || 'Failed to process file', 'error');
                }
            },
            error: function() {
                $dropzone.html(originalContent);
                showNotification('Network error. Please try again.', 'error');
            }
        });
    }
    
    // Display Fuel Result
    function displayFuelResult(data) {
        // Hide dropzone, show result
        $('#apex-fuel-dropzone').hide();
        $('#apex-fuel-share-option').hide();
        $('#apex-fuel-result').show();
        
        // Populate data
        $('#apex-fuel-result-type').text(data.session_type || 'Session');
        $('#apex-fuel-stat-track').text(data.track || '--');
        $('#apex-fuel-stat-car').text(data.car || '--');
        $('#apex-fuel-stat-tank').text(data.tank_capacity ? data.tank_capacity + ' L' : '--');
        $('#apex-fuel-stat-laps').text(data.valid_laps + ' / ' + data.total_laps);
        
        // Fuel multiplier badge
        var fuelMult = parseFloat(data.fuel_mult) || 1.0;
        if (fuelMult !== 1.0) {
            $('#apex-fuel-mult-badge').text(fuelMult + 'x Fuel').show();
        } else {
            $('#apex-fuel-mult-badge').hide();
        }
        
        // Main fuel stats
        if (data.avg_fuel_liters) {
            $('#apex-fuel-stat-avg').text(parseFloat(data.avg_fuel_liters).toFixed(2) + ' L');
            
            var minFuel = data.min_fuel_liters ? parseFloat(data.min_fuel_liters).toFixed(2) : '--';
            var maxFuel = data.max_fuel_liters ? parseFloat(data.max_fuel_liters).toFixed(2) : '--';
            $('#apex-fuel-stat-range').text('Range: ' + minFuel + ' to ' + maxFuel + ' L');
        } else {
            $('#apex-fuel-stat-avg').text('-- L');
            $('#apex-fuel-stat-range').text('No valid laps');
        }
        
        // VE stats (Hypercars only)
        if (data.avg_ve_percent && parseFloat(data.avg_ve_percent) > 0) {
            $('#apex-fuel-ve-section').show();
            $('#apex-fuel-stat-ve').text((parseFloat(data.avg_ve_percent) * 100).toFixed(1) + '%');
        } else {
            $('#apex-fuel-ve-section').hide();
        }
        
        // Lap times
        $('#apex-fuel-stat-best').text(data.best_lap_time_formatted || '--:--.---');
        $('#apex-fuel-stat-avglap').text(data.avg_lap_time_formatted || '--:--.---');
        $('#apex-fuel-stat-pits').text(data.pitstops || '0');
    }
    
    // Upload Another Button
    $(document).on('click', '#apex-fuel-upload-another', function() {
        // Reset the upload area
        $('#apex-fuel-result').hide();
        $('#apex-fuel-dropzone').show().html(
            '<div class="apex-fuel-upload-icon">' +
            '<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">' +
            '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>' +
            '<polyline points="17 8 12 3 7 8"/>' +
            '<line x1="12" y1="3" x2="12" y2="15"/>' +
            '</svg></div>' +
            '<h3>Drop your LMU session XML file here</h3>' +
            '<p>or click to browse</p>' +
            '<p class="apex-fuel-upload-hint">Files located in: <code>Documents\\My Games\\Le Mans Ultimate\\UserData\\Log\\Results\\</code></p>' +
            '<input type="file" id="apex-fuel-file-input" accept=".xml" style="display:none;">'
        );
        $('#apex-fuel-share-option').show();
        $('#apex-fuel-file-input').val('');
    });
    
    // Load My Sessions
    function loadMySessions() {
        var $list = $('#apex-fuel-sessions-list');
        $list.html('<div class="apex-loading"><div class="apex-spinner"></div></div>');
        
        $.ajax({
            url: apexNotesData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'apex_notes_get_fuel_sessions',
                nonce: apexNotesData.nonce,
                limit: 20
            },
            success: function(response) {
                if (response.success && response.data.sessions.length > 0) {
                    var html = '';
                    response.data.sessions.forEach(function(session) {
                        html += buildSessionCard(session);
                    });
                    $list.html(html);
                } else {
                    $list.html('<div class="apex-fuel-login-prompt"><p>No sessions uploaded yet. Upload your first session to get started!</p></div>');
                }
            },
            error: function() {
                $list.html('<div class="apex-fuel-login-prompt"><p>Failed to load sessions. Please try again.</p></div>');
            }
        });
    }
    
    // Build Session Card
    function buildSessionCard(session) {
        var avgFuel = session.avg_fuel_liters ? parseFloat(session.avg_fuel_liters).toFixed(2) + ' L' : '--';
        var vePercent = session.avg_ve_percent ? (parseFloat(session.avg_ve_percent) * 100).toFixed(1) + '%' : '--';
        var sharedBadge = session.shared_with_community == 1 ? 
            '<span class="apex-fuel-session-shared"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg> Shared</span>' : '';
        
        // Fuel multiplier badge
        var fuelMult = parseFloat(session.fuel_mult) || 1.0;
        var fuelMultBadge = fuelMult !== 1.0 ? 
            '<span class="apex-fuel-mult-badge-small">' + fuelMult + 'x</span>' : '';
        
        var sessionDate = new Date(session.session_date);
        var dateStr = sessionDate.toLocaleDateString() + ' ' + sessionDate.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        
        // Position info
        var positionInfo = '';
        if (session.grid_position && session.finish_position) {
            var posChange = session.grid_position - session.finish_position;
            var posColor = posChange > 0 ? '#00d4aa' : (posChange < 0 ? '#ff6b6b' : 'white');
            var posArrow = posChange > 0 ? '↑' : (posChange < 0 ? '↓' : '→');
            positionInfo = '<span class="apex-fuel-session-position" style="color:' + posColor + ';">P' + session.grid_position + ' ' + posArrow + ' P' + session.finish_position + '</span>';
        }
        
        return '<div class="apex-fuel-session-card" data-session-id="' + session.id + '">' +
            '<div class="apex-fuel-session-header">' +
                '<div>' +
                    '<h4 class="apex-fuel-session-track">' + escapeHtml(session.track_venue) + '</h4>' +
                    '<p class="apex-fuel-session-car">' + escapeHtml(session.car_type) + ' <span style="opacity:0.5;">(' + escapeHtml(session.car_class) + ')</span> ' + fuelMultBadge + '</p>' +
                '</div>' +
                '<div style="text-align:right;">' +
                    sharedBadge +
                    '<p class="apex-fuel-session-date">' + dateStr + '</p>' +
                    positionInfo +
                '</div>' +
            '</div>' +
            '<div class="apex-fuel-session-stats">' +
                '<div class="apex-fuel-session-stat">' +
                    '<span class="apex-fuel-session-stat-label">Fuel/Lap</span>' +
                    '<span class="apex-fuel-session-stat-value">' + avgFuel + '</span>' +
                '</div>' +
                '<div class="apex-fuel-session-stat">' +
                    '<span class="apex-fuel-session-stat-label">VE/Lap</span>' +
                    '<span class="apex-fuel-session-stat-value" style="color:#00d4aa;">' + vePercent + '</span>' +
                '</div>' +
                '<div class="apex-fuel-session-stat">' +
                    '<span class="apex-fuel-session-stat-label">Best Lap</span>' +
                    '<span class="apex-fuel-session-stat-value" style="color:white;">' + (session.best_lap_time_formatted || '--:--.---') + '</span>' +
                '</div>' +
                '<div class="apex-fuel-session-stat">' +
                    '<span class="apex-fuel-session-stat-label">Valid Laps</span>' +
                    '<span class="apex-fuel-session-stat-value" style="color:white;">' + session.valid_laps + '</span>' +
                '</div>' +
                '<div class="apex-fuel-session-stat">' +
                    '<span class="apex-fuel-session-stat-label">Pit Stops</span>' +
                    '<span class="apex-fuel-session-stat-value" style="color:white;">' + (session.pitstops || 0) + '</span>' +
                '</div>' +
            '</div>' +
            '<div class="apex-fuel-session-actions">' +
                (session.shared_with_community == 1 ? 
                    '<button class="apex-btn apex-btn-ghost apex-fuel-unshare-btn" data-session-id="' + session.id + '">Unshare</button>' :
                    '<button class="apex-btn apex-btn-primary apex-fuel-share-btn" data-session-id="' + session.id + '">Share with Community</button>'
                ) +
                '<button class="apex-btn apex-btn-ghost apex-fuel-delete-btn" data-session-id="' + session.id + '">Delete</button>' +
            '</div>' +
        '</div>';
    }
    
    // Share Session
    $(document).on('click', '.apex-fuel-share-btn', function() {
        var $btn = $(this);
        var sessionId = $btn.data('session-id');
        
        $btn.prop('disabled', true).text('Sharing...');
        
        $.ajax({
            url: apexNotesData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'apex_notes_share_fuel_session',
                nonce: apexNotesData.nonce,
                session_id: sessionId
            },
            success: function(response) {
                if (response.success) {
                    showNotification('Session shared with community!', 'success');
                    loadMySessions(); // Refresh
                } else {
                    $btn.prop('disabled', false).text('Share with Community');
                    showNotification(response.data.message || 'Failed to share', 'error');
                }
            },
            error: function() {
                $btn.prop('disabled', false).text('Share with Community');
                showNotification('Network error', 'error');
            }
        });
    });
    
    // Unshare Session
    $(document).on('click', '.apex-fuel-unshare-btn', function() {
        var $btn = $(this);
        var sessionId = $btn.data('session-id');
        
        $btn.prop('disabled', true).text('Removing...');
        
        $.ajax({
            url: apexNotesData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'apex_notes_unshare_fuel_session',
                nonce: apexNotesData.nonce,
                session_id: sessionId
            },
            success: function(response) {
                if (response.success) {
                    showNotification('Session removed from community data', 'success');
                    loadMySessions(); // Refresh
                } else {
                    $btn.prop('disabled', false).text('Unshare');
                    showNotification(response.data.message || 'Failed to unshare', 'error');
                }
            },
            error: function() {
                $btn.prop('disabled', false).text('Unshare');
                showNotification('Network error', 'error');
            }
        });
    });
    
    // Delete Session
    $(document).on('click', '.apex-fuel-delete-btn', function() {
        var $btn = $(this);
        var sessionId = $btn.data('session-id');
        
        if (!confirm('Are you sure you want to delete this session?')) {
            return;
        }
        
        $btn.prop('disabled', true).text('Deleting...');
        
        $.ajax({
            url: apexNotesData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'apex_notes_delete_fuel_session',
                nonce: apexNotesData.nonce,
                session_id: sessionId
            },
            success: function(response) {
                if (response.success) {
                    showNotification('Session deleted', 'success');
                    loadMySessions(); // Refresh
                } else {
                    $btn.prop('disabled', false).text('Delete');
                    showNotification(response.data.message || 'Failed to delete', 'error');
                }
            },
            error: function() {
                $btn.prop('disabled', false).text('Delete');
                showNotification('Network error', 'error');
            }
        });
    });
    
    // Load Community Data
    function loadCommunityData() {
        var track = $('#apex-fuel-filter-track').val();
        var car = $('#apex-fuel-filter-car').val();
        
        var $grid = $('#apex-fuel-community-grid');
        var $empty = $('#apex-fuel-community-empty');
        var $averagesBox = $('#apex-fuel-community-averages');
        var $averagesContent = $('#apex-fuel-community-averages-content');
        
        // Only show community averages when BOTH track AND car are selected
        if (track && car) {
            $averagesBox.show();
            $grid.html('<div class="apex-loading"><div class="apex-spinner"></div></div>');
            $empty.hide();
            $('#apex-fuel-selected-track').text(track);
            $('#apex-fuel-selected-car').text(car);
            $averagesContent.html('<div class="apex-loading"><div class="apex-spinner"></div></div>');
            
            // Load community averages for this specific track+car by fuel mult
            loadCommunityAveragesForCar(track, car);
            
            // Also load individual session cards
            loadCommunitySessionCards(track, car);
        } else {
            $averagesBox.hide();
            $empty.hide();
            $grid.html('<p class="apex-fuel-select-hint">Select a track and car above to view community fuel data.</p>');
        }
    }
    
    // Load individual session cards for track+car
    function loadCommunitySessionCards(track, car) {
        var $grid = $('#apex-fuel-community-grid');
        var $empty = $('#apex-fuel-community-empty');
        
        $.ajax({
            url: apexNotesData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'apex_notes_get_community_fuel_data',
                nonce: apexNotesData.nonce,
                track: track,
                car: car,
                individual_sessions: '1'
            },
            success: function(response) {
                if (response.success && response.data.data && response.data.data.length > 0) {
                    var html = '<h4 class="apex-fuel-sessions-title">Individual Sessions (' + response.data.data.length + ')</h4>';
                    html += '<div class="apex-fuel-session-cards">';
                    response.data.data.forEach(function(session) {
                        html += buildIndividualSessionCard(session);
                    });
                    html += '</div>';
                    $grid.html(html);
                    $empty.hide();
                } else {
                    $grid.html('');
                }
            },
            error: function() {
                $grid.html('');
            }
        });
    }
    
    // Build individual session card
    function buildIndividualSessionCard(session) {
        var avgFuel = session.avg_fuel_liters ? parseFloat(session.avg_fuel_liters).toFixed(2) : '--';
        var minFuel = session.min_fuel_liters ? parseFloat(session.min_fuel_liters).toFixed(2) : '--';
        var maxFuel = session.max_fuel_liters ? parseFloat(session.max_fuel_liters).toFixed(2) : '--';
        var vePercent = session.avg_ve_percent ? (parseFloat(session.avg_ve_percent) * 100).toFixed(1) + '%' : 'N/A';
        var bestLap = session.best_lap_time_formatted || '--';
        var sessionDate = session.session_date ? new Date(session.session_date).toLocaleDateString() : '--';
        
        var fuelMult = parseFloat(session.fuel_mult) || 1;
        var fuelMultBadge = fuelMult > 1 ? '<span class="apex-fuel-mult-badge-small">' + fuelMult + 'x</span>' : '';
        
        var html = '<div class="apex-fuel-community-card">';
        html += '<div class="apex-fuel-card-header">';
        html += '<span class="apex-fuel-card-date">' + sessionDate + '</span>';
        html += fuelMultBadge;
        html += '</div>';
        html += '<div class="apex-fuel-card-fuel">';
        html += '<span class="apex-fuel-card-fuel-value">' + avgFuel + '</span>';
        html += '<span class="apex-fuel-card-fuel-unit">L/LAP</span>';
        html += '</div>';
        html += '<div class="apex-fuel-card-stats">';
        html += '<div class="apex-fuel-card-stat"><span class="label">Range</span><span class="value">' + minFuel + ' - ' + maxFuel + ' L</span></div>';
        html += '<div class="apex-fuel-card-stat"><span class="label">VE</span><span class="value">' + vePercent + '</span></div>';
        html += '<div class="apex-fuel-card-stat"><span class="label">Best Lap</span><span class="value">' + bestLap + '</span></div>';
        html += '<div class="apex-fuel-card-stat"><span class="label">Laps</span><span class="value">' + (session.valid_laps || 0) + '</span></div>';
        html += '</div>';
        html += '</div>';
        
        return html;
    }
    
    // Load community averages for a specific track+car combination
    function loadCommunityAveragesForCar(track, car) {
        var $content = $('#apex-fuel-community-averages-content');
        
        $.ajax({
            url: apexNotesData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'apex_notes_get_community_fuel_data',
                nonce: apexNotesData.nonce,
                track: track,
                car: car,
                by_fuel_mult: '1'
            },
            success: function(response) {
                if (response.success && response.data.data && response.data.data.length > 0) {
                    var html = buildCarFuelAverages(response.data.data);
                    $content.html(html);
                } else {
                    $content.html('<div class="apex-fuel-no-data"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M8 15h8"/><circle cx="9" cy="9" r="1"/><circle cx="15" cy="9" r="1"/></svg><p>No community data available for this combination yet.</p><p>Be the first to upload a race session!</p></div>');
                    $('#apex-fuel-community-empty').show();
                }
            },
            error: function() {
                $content.html('<div class="apex-fuel-no-data"><p>Failed to load community data.</p></div>');
            }
        });
    }
    
    // Build fuel averages display for a single car (separate sections for each fuel mult)
    function buildCarFuelAverages(data) {
        if (!data || data.length === 0) {
            return '<div class="apex-fuel-no-data"><p>No data available.</p></div>';
        }
        
        // Sort by fuel mult (1x first)
        data.sort(function(a, b) {
            return parseFloat(a.fuel_mult) - parseFloat(b.fuel_mult);
        });
        
        var html = '';
        
        data.forEach(function(item) {
            var fuelMult = parseFloat(item.fuel_mult);
            var isNormal = fuelMult === 1;
            var sectionClass = isNormal ? 'normal' : 'double';
            var sectionTitle = isNormal ? 'Normal Fuel (1x)' : fuelMult + 'x Fuel Consumption';
            
            var avgFuel = item.avg_fuel_liters ? parseFloat(item.avg_fuel_liters).toFixed(2) : '--';
            var minFuel = item.min_fuel_liters ? parseFloat(item.min_fuel_liters).toFixed(2) : '--';
            var maxFuel = item.max_fuel_liters ? parseFloat(item.max_fuel_liters).toFixed(2) : '--';
            var vePercent = item.avg_ve_percent ? (parseFloat(item.avg_ve_percent) * 100).toFixed(1) + '%' : 'N/A';
            var bestLap = item.best_lap_time_formatted || '--';
            
            html += '<div class="apex-fuel-aggregated-section ' + sectionClass + '">';
            html += '<div class="apex-fuel-aggregated-header">';
            html += '<span class="apex-fuel-aggregated-title">' + sectionTitle + '</span>';
            html += '<span class="apex-fuel-aggregated-meta">' + item.session_count + ' sessions • ' + item.total_laps + ' laps</span>';
            html += '</div>';
            html += '<div class="apex-fuel-aggregated-stats">';
            html += '<div class="apex-fuel-aggregated-stat main"><span class="value">' + avgFuel + '</span><span class="label">AVG L/LAP</span></div>';
            html += '<div class="apex-fuel-aggregated-stat"><span class="value">' + minFuel + ' - ' + maxFuel + ' L</span><span class="label">Range</span></div>';
            html += '<div class="apex-fuel-aggregated-stat"><span class="value">' + vePercent + '</span><span class="label">VE Used</span></div>';
            html += '<div class="apex-fuel-aggregated-stat"><span class="value">' + bestLap + '</span><span class="label">Best Lap</span></div>';
            html += '</div>';
            html += '</div>';
        });
        
        return html;
    }
    
    // Build Community Card
    function buildCommunityCard(item) {
        var avgFuel = item.avg_fuel_liters ? parseFloat(item.avg_fuel_liters).toFixed(2) : '--';
        var minFuel = item.min_fuel_liters ? parseFloat(item.min_fuel_liters).toFixed(2) : '--';
        var maxFuel = item.max_fuel_liters ? parseFloat(item.max_fuel_liters).toFixed(2) : '--';
        var vePercent = item.avg_ve_percent ? (parseFloat(item.avg_ve_percent) * 100).toFixed(1) + '%' : 'N/A';
        var avgPitstops = item.avg_pitstops ? parseFloat(item.avg_pitstops).toFixed(1) : '--';
        
        // Fuel multiplier badge
        var fuelMultNote = item.fuel_mult_note || '1x';
        var fuelMultBadge = (fuelMultNote !== '1x') ? 
            '<span class="apex-fuel-mult-badge-small">' + escapeHtml(fuelMultNote) + '</span>' : '';
        
        return '<div class="apex-fuel-community-card">' +
            '<div class="apex-fuel-community-card-header">' +
                '<h4 class="apex-fuel-community-track">' + escapeHtml(item.track_venue) + '</h4>' +
                '<p class="apex-fuel-community-car">' + escapeHtml(item.car_type) + ' ' + fuelMultBadge + '</p>' +
            '</div>' +
            '<div class="apex-fuel-community-main">' +
                '<span class="apex-fuel-community-value">' + avgFuel + ' L</span>' +
                '<span class="apex-fuel-community-label">Avg Fuel/Lap</span>' +
            '</div>' +
            '<div class="apex-fuel-community-details">' +
                '<span class="apex-fuel-community-detail">Range: <span>' + minFuel + ' - ' + maxFuel + ' L</span></span>' +
                '<span class="apex-fuel-community-detail">VE: <span style="color:#00d4aa;">' + vePercent + '</span></span>' +
            '</div>' +
            '<div class="apex-fuel-community-details">' +
                '<span class="apex-fuel-community-detail">Avg Pit Stops: <span style="color:white;">' + avgPitstops + '</span></span>' +
            '</div>' +
            '<div class="apex-fuel-community-samples">' +
                '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>' +
                item.sample_count + ' sessions • ' + item.lap_count + ' laps' +
            '</div>' +
        '</div>';
    }
    
    // Community Filter Change
    $(document).on('change', '#apex-fuel-filter-track, #apex-fuel-filter-car', function() {
        loadCommunityData();
    });
    
    // Helper: Escape HTML
    function escapeHtml(text) {
        if (!text) return '';
        var div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // ========================================
    // LIVE RACE BROADCASTING
    // ========================================
    
    var liveRaceState = {
        pollingInterval: null,
        broadcastKey: null,
        broadcastId: null,
        showFullGrid: false,
        lastUpdate: null,
        wizardStep: 1,
        affiliateLinks: []
    };
    
    // Check URL for broadcast parameter on page load
    function checkBroadcastUrl() {
        var urlParams = new URLSearchParams(window.location.search);
        var broadcastKey = urlParams.get('broadcast');
        
        if (broadcastKey) {
            liveRaceState.broadcastKey = broadcastKey;
            showPage('spectator');
            startSpectatorPolling();
        }
    }
    
    // Initialize Live Race page
    function initLiveRacePage() {
        loadMyBroadcast();
        loadActiveBroadcasts();
        loadAffiliateLinks();
    }
    
    // Load user's active broadcast
    function loadMyBroadcast() {
        $.ajax({
            url: apexNotesAjax.ajaxUrl,
            type: 'POST',
            data: {
                action: 'apex_notes_get_my_broadcast',
                nonce: apexNotesAjax.nonce
            },
            success: function(response) {
                if (response.success) {
                    if (response.data.broadcast) {
                        showActiveBroadcast(response.data.broadcast);
                    } else {
                        showInactiveBroadcast();
                    }
                    // Update premium status for stream field
                    if (response.data.is_premium) {
                        enableStreamFields();
                    }
                    // Update affiliate links
                    if (response.data.affiliate_links) {
                        liveRaceState.affiliateLinks = response.data.affiliate_links;
                        renderAffiliateLinks();
                    }
                }
            }
        });
    }
    
    // Enable stream fields for premium users
    function enableStreamFields() {
        $('#apex-broadcast-stream, #apex-broadcast-stream-quick, #apex-broadcast-stream-update').prop('disabled', false);
        $('#apex-stream-premium-badge, #apex-stream-field-quick .apex-premium-badge').hide();
    }
    
    // Show active broadcast state
    function showActiveBroadcast(broadcast) {
        $('#apex-broadcast-inactive').hide();
        $('#apex-broadcast-active').show();
        $('#apex-broadcast-key').text(broadcast.broadcast_key);
        $('#apex-spectator-link').text(broadcast.spectator_url);
        liveRaceState.broadcastId = broadcast.id;
        
        // Set current stream URL if exists
        if (broadcast.stream_url) {
            $('#apex-broadcast-stream-update').val(broadcast.stream_url);
        }
        
        // Enable stream field if premium
        if (broadcast.is_premium) {
            enableStreamFields();
        }
    }
    
    // Show inactive broadcast state
    function showInactiveBroadcast() {
        $('#apex-broadcast-active').hide();
        $('#apex-broadcast-inactive').show();
        $('#apex-wizard-intro').show();
        $('#apex-wizard').hide();
        $('#apex-broadcast-quickstart').hide();
    }
    
    // ========================================
    // WIZARD FUNCTIONS
    // ========================================
    
    // Start wizard
    $(document).on('click', '#apex-wizard-start', function() {
        $('#apex-wizard-intro').hide();
        $('#apex-wizard').show();
        liveRaceState.wizardStep = 1;
        showWizardStep(1);
    });
    
    // Skip to quick start
    $(document).on('click', '#apex-wizard-skip', function() {
        $('#apex-wizard-intro').hide();
        $('#apex-broadcast-quickstart').show();
    });
    
    // Show wizard from quick start
    $(document).on('click', '#apex-wizard-show', function() {
        $('#apex-broadcast-quickstart').hide();
        $('#apex-wizard-intro').show();
    });
    
    // Wizard navigation
    $(document).on('click', '#apex-wizard-next', function() {
        if (liveRaceState.wizardStep < 5) {
            liveRaceState.wizardStep++;
            showWizardStep(liveRaceState.wizardStep);
        }
    });
    
    $(document).on('click', '#apex-wizard-back', function() {
        if (liveRaceState.wizardStep > 1) {
            liveRaceState.wizardStep--;
            showWizardStep(liveRaceState.wizardStep);
        }
    });
    
    function showWizardStep(step) {
        // Hide all steps
        $('.apex-wizard-content').hide();
        $('.apex-wizard-content[data-step="' + step + '"]').show();
        
        // Update progress
        $('.apex-wizard-step').removeClass('active completed');
        $('.apex-wizard-step').each(function() {
            var stepNum = $(this).data('step');
            if (stepNum < step) {
                $(this).addClass('completed');
            } else if (stepNum === step) {
                $(this).addClass('active');
            }
        });
        
        // Update back button
        $('#apex-wizard-back').prop('disabled', step === 1);
    }
    
    // Start Broadcast (from wizard step 5)
    $(document).on('click', '#apex-start-broadcast', function() {
        var $btn = $(this);
        var sessionName = $('#apex-broadcast-name').val();
        var streamUrl = $('#apex-broadcast-stream').val();
        
        startBroadcast($btn, sessionName, streamUrl);
    });
    
    // Start Broadcast (from quick start)
    $(document).on('click', '#apex-start-broadcast-quick', function() {
        var $btn = $(this);
        var sessionName = $('#apex-broadcast-name-quick').val();
        var streamUrl = $('#apex-broadcast-stream-quick').val();
        
        startBroadcast($btn, sessionName, streamUrl);
    });
    
    function startBroadcast($btn, sessionName, streamUrl) {
        $btn.prop('disabled', true).text('Starting...');
        
        $.ajax({
            url: apexNotesAjax.ajaxUrl,
            type: 'POST',
            data: {
                action: 'apex_notes_start_broadcast',
                nonce: apexNotesAjax.nonce,
                session_name: sessionName,
                stream_url: streamUrl
            },
            success: function(response) {
                if (response.success) {
                    showActiveBroadcast(response.data);
                    showNotification('Broadcast started! Share your link with viewers.');
                    if (response.data.affiliate_links) {
                        liveRaceState.affiliateLinks = response.data.affiliate_links;
                    }
                } else {
                    showNotification(response.data.message || 'Failed to start broadcast', 'error');
                }
            },
            error: function() {
                showNotification('Network error', 'error');
            },
            complete: function() {
                $btn.prop('disabled', false).html(
                    '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="3" fill="currentColor"/></svg> Go Live!'
                );
            }
        });
    }
    
    // End Broadcast
    $(document).on('click', '#apex-end-broadcast', function() {
        if (!confirm('Are you sure you want to end your broadcast?')) return;
        
        $.ajax({
            url: apexNotesAjax.ajaxUrl,
            type: 'POST',
            data: {
                action: 'apex_notes_end_broadcast',
                nonce: apexNotesAjax.nonce
            },
            success: function(response) {
                showInactiveBroadcast();
                showNotification('Broadcast ended');
                loadActiveBroadcasts();
            }
        });
    });
    
    // Update Stream URL
    $(document).on('click', '#apex-update-stream', function() {
        var streamUrl = $('#apex-broadcast-stream-update').val();
        
        $.ajax({
            url: apexNotesAjax.ajaxUrl,
            type: 'POST',
            data: {
                action: 'apex_notes_update_broadcast_stream',
                nonce: apexNotesAjax.nonce,
                broadcast_id: liveRaceState.broadcastId,
                stream_url: streamUrl
            },
            success: function(response) {
                if (response.success) {
                    showNotification('Stream URL updated!');
                } else {
                    showNotification(response.data.message || 'Failed to update', 'error');
                }
            }
        });
    });
    
    // Copy Broadcast Key
    $(document).on('click', '#apex-copy-key', function() {
        var key = $('#apex-broadcast-key').text();
        copyToClipboard(key);
        showNotification('Broadcast key copied!');
    });
    
    // Copy Spectator Link
    $(document).on('click', '#apex-copy-link', function() {
        var link = $('#apex-spectator-link').text();
        copyToClipboard(link);
        showNotification('Spectator link copied!');
    });
    
    // Copy to clipboard helper
    function copyToClipboard(text) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(text);
        } else {
            var $temp = $('<input>');
            $('body').append($temp);
            $temp.val(text).select();
            document.execCommand('copy');
            $temp.remove();
        }
    }
    
    // Global copy function for wizard
    window.ApexNotes = window.ApexNotes || {};
    window.ApexNotes.copyText = function(text) {
        copyToClipboard(text);
        showNotification('Copied!');
    };
    
    // ========================================
    // AFFILIATE LINKS
    // ========================================
    
    function loadAffiliateLinks() {
        $.ajax({
            url: apexNotesAjax.ajaxUrl,
            type: 'POST',
            data: {
                action: 'apex_notes_get_affiliate_links',
                nonce: apexNotesAjax.nonce
            },
            success: function(response) {
                if (response.success) {
                    liveRaceState.affiliateLinks = response.data.links || [];
                    renderAffiliateLinks();
                }
            }
        });
    }
    
    function renderAffiliateLinks() {
        var $container = $('#apex-affiliate-links');
        var links = liveRaceState.affiliateLinks;
        
        if (!links || links.length === 0) {
            $container.html('<p style="color: rgba(255,255,255,0.5); font-size: 0.875rem;">No affiliate links yet. Add your first one below!</p>');
            $('#apex-affiliate-preview').hide();
            return;
        }
        
        var html = '';
        links.forEach(function(link) {
            html += '<div class="apex-affiliate-item" data-id="' + link.id + '">' +
                '<div class="apex-affiliate-item-type apex-affiliate-type-' + escapeHtml(link.link_type) + '"></div>' +
                '<div class="apex-affiliate-item-info">' +
                    '<div class="apex-affiliate-item-label">' + escapeHtml(link.label) + '</div>' +
                    '<div class="apex-affiliate-item-url">' + escapeHtml(link.url) + '</div>' +
                '</div>' +
                '<button class="apex-affiliate-item-remove" data-id="' + link.id + '">' +
                    '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>' +
                '</button>' +
            '</div>';
        });
        
        $container.html(html);
        
        // Show preview
        renderAffiliatePreview(links);
    }
    
    function renderAffiliatePreview(links) {
        if (!links || links.length === 0) {
            $('#apex-affiliate-preview').hide();
            return;
        }
        
        var html = '';
        links.forEach(function(link) {
            html += '<a href="' + escapeHtml(link.url) + '" target="_blank" rel="nofollow sponsored">' +
                '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>' +
                escapeHtml(link.label) +
            '</a>';
        });
        
        $('#apex-affiliate-preview .apex-affiliate-preview-links').html(html);
        $('#apex-affiliate-preview').show();
    }
    
    // Add affiliate link
    $(document).on('click', '#apex-affiliate-add-btn', function() {
        var linkType = $('#apex-affiliate-type').val();
        var label = $('#apex-affiliate-label').val().trim();
        var url = $('#apex-affiliate-url').val().trim();
        
        if (!linkType || !label || !url) {
            showNotification('Please fill in all fields', 'error');
            return;
        }
        
        var $btn = $(this);
        $btn.prop('disabled', true);
        
        $.ajax({
            url: apexNotesAjax.ajaxUrl,
            type: 'POST',
            data: {
                action: 'apex_notes_add_affiliate_link',
                nonce: apexNotesAjax.nonce,
                link_type: linkType,
                label: label,
                url: url
            },
            success: function(response) {
                if (response.success) {
                    liveRaceState.affiliateLinks = response.data.links;
                    renderAffiliateLinks();
                    // Clear form
                    $('#apex-affiliate-type').val('');
                    $('#apex-affiliate-label').val('');
                    $('#apex-affiliate-url').val('');
                    showNotification('Link added!');
                } else {
                    showNotification(response.data.message || 'Failed to add link', 'error');
                }
            },
            complete: function() {
                $btn.prop('disabled', false);
            }
        });
    });
    
    // Remove affiliate link
    $(document).on('click', '.apex-affiliate-item-remove', function() {
        var linkId = $(this).data('id');
        
        $.ajax({
            url: apexNotesAjax.ajaxUrl,
            type: 'POST',
            data: {
                action: 'apex_notes_remove_affiliate_link',
                nonce: apexNotesAjax.nonce,
                link_id: linkId
            },
            success: function(response) {
                if (response.success) {
                    liveRaceState.affiliateLinks = response.data.links;
                    renderAffiliateLinks();
                    showNotification('Link removed');
                }
            }
        });
    });
    
    // Load Active Broadcasts
    function loadActiveBroadcasts() {
        $.ajax({
            url: apexNotesAjax.ajaxUrl,
            type: 'POST',
            data: {
                action: 'apex_notes_get_active_broadcasts',
                nonce: apexNotesAjax.nonce
            },
            success: function(response) {
                if (response.success) {
                    renderActiveBroadcasts(response.data.broadcasts);
                }
            }
        });
    }
    
    // Render Active Broadcasts
    function renderActiveBroadcasts(broadcasts) {
        var $grid = $('#apex-broadcasts-grid');
        var $empty = $('#apex-broadcasts-empty');
        
        if (!broadcasts || broadcasts.length === 0) {
            $grid.empty();
            $empty.show();
            return;
        }
        
        $empty.hide();
        var html = '';
        
        broadcasts.forEach(function(b) {
            html += '<div class="apex-broadcast-card" data-url="' + escapeHtml(b.spectator_url) + '">' +
                '<div class="apex-broadcast-card-header">' +
                    '<div class="apex-broadcast-card-live"><span class="apex-live-dot"></span> LIVE</div>' +
                    '<div class="apex-broadcast-card-track">' + escapeHtml(b.track_name || b.session_name || 'Racing') + '</div>' +
                '</div>' +
                '<div class="apex-broadcast-card-meta">' +
                    '<span>' + escapeHtml(b.broadcaster_name) + '</span>' +
                '</div>' +
            '</div>';
        });
        
        $grid.html(html);
    }
    
    // Click on broadcast card to watch
    $(document).on('click', '.apex-broadcast-card', function() {
        var url = $(this).data('url');
        if (url) {
            window.location.href = url;
        }
    });
    
    // ========================================
    // SPECTATOR VIEW
    // ========================================
    
    // Start polling for spectator view
    function startSpectatorPolling() {
        if (!liveRaceState.broadcastKey) return;
        
        // Initial fetch
        fetchTelemetry();
        
        // Poll every 2 seconds
        liveRaceState.pollingInterval = setInterval(fetchTelemetry, 2000);
    }
    
    // Stop polling
    function stopSpectatorPolling() {
        if (liveRaceState.pollingInterval) {
            clearInterval(liveRaceState.pollingInterval);
            liveRaceState.pollingInterval = null;
        }
    }
    
    // Fetch telemetry data
    function fetchTelemetry() {
        $.ajax({
            url: apexNotesAjax.ajaxUrl,
            type: 'POST',
            data: {
                action: 'apex_notes_get_live_telemetry',
                broadcast_key: liveRaceState.broadcastKey
            },
            success: function(response) {
                if (response.success) {
                    updateSpectatorView(response.data.telemetry);
                } else if (response.data && response.data.ended) {
                    stopSpectatorPolling();
                    $('#apex-broadcast-ended').show();
                }
            }
        });
    }
    
    // Update spectator view with telemetry data
    function updateSpectatorView(data) {
        if (!data) return;
        
        liveRaceState.lastUpdate = data;
        
        // Update top bar
        $('#apex-spectator-track').text(data.track_name || 'Unknown Track');
        $('#apex-spectator-session-time').text(data.session_time || '0:00:00');
        $('#apex-spectator-current-lap').text(data.current_lap || 0);
        $('#apex-spectator-total-laps').text(data.total_laps || 0);
        $('#apex-spectator-broadcaster-name').text(data.broadcaster_name || 'Unknown');
        
        // Update timing tower
        updateTimingTower(data.positions || [], data.player_position);
        
        // Update driver stats
        $('#apex-stat-last-lap').text(data.last_lap_time || '--:--.---');
        $('#apex-stat-best-lap').text(data.best_lap_time || '--:--.---');
        $('#apex-stat-gap-ahead').text(data.gap_ahead || '--');
        $('#apex-stat-gap-behind').text(data.gap_behind || '--');
        $('#apex-stat-fuel').text((data.fuel_remaining || 0).toFixed(1) + ' L');
        $('#apex-stat-fuel-laps').text((data.fuel_laps_remaining || 0).toFixed(1) + ' laps');
        
        // Update flags
        updateFlagDisplay(data.flag_status, data.flag_sector, data.penalties);
        
        // Update tires
        updateTireDisplay(data);
        
        // Update video embed
        updateVideoEmbed(data.stream_url, data.stream_type, data.is_premium);
        
        // Update affiliate links
        updateSpectatorAffiliates(data.affiliate_links);
    }
    
    // Update timing tower
    function updateTimingTower(positions, playerPos) {
        var $tower = $('#apex-timing-tower');
        
        if (!positions || positions.length === 0) {
            $tower.html('<div class="apex-timing-empty">Waiting for data...</div>');
            return;
        }
        
        // Filter positions if nearby mode
        var displayPositions = positions;
        if (!liveRaceState.showFullGrid && playerPos > 0) {
            var minPos = Math.max(1, playerPos - 5);
            var maxPos = Math.min(positions.length, playerPos + 5);
            displayPositions = positions.filter(function(p) {
                return p.position >= minPos && p.position <= maxPos;
            });
        }
        
        var html = '';
        displayPositions.forEach(function(p) {
            var isPlayer = p.position === playerPos;
            var rowClass = 'apex-timing-row' + (isPlayer ? ' apex-timing-player' : '');
            
            html += '<div class="' + rowClass + '" data-class="' + escapeHtml(p.car_class || '') + '">' +
                '<span class="apex-timing-pos">P' + p.position + '</span>' +
                '<span class="apex-timing-name">' + escapeHtml(p.name || 'Driver') + '</span>' +
                '<span class="apex-timing-gap">' + formatGap(p.gap) + '</span>' +
                '<span class="apex-timing-lap">L' + (p.lap || 0) + '</span>' +
            '</div>';
        });
        
        $tower.html(html);
    }
    
    // Format gap display
    function formatGap(gap) {
        if (!gap || gap === 0) return '+0.000';
        if (typeof gap === 'string') return gap;
        if (gap > 0) return '+' + gap.toFixed(3);
        return gap.toFixed(3);
    }
    
    // Update flag display
    function updateFlagDisplay(flagStatus, flagSector, penalties) {
        var $flag = $('#apex-flag-display');
        var $penalties = $('#apex-penalties');
        
        // Reset classes
        $flag.removeClass('apex-flag-green apex-flag-yellow apex-flag-blue apex-flag-white apex-flag-black-white apex-flag-red apex-flag-chequered');
        
        var flagText = 'GREEN';
        var flagClass = 'apex-flag-green';
        
        switch ((flagStatus || '').toLowerCase()) {
            case 'yellow':
                flagText = 'YELLOW';
                flagClass = 'apex-flag-yellow';
                if (flagSector) flagText += ' <span class="apex-flag-sector">Sector ' + flagSector + '</span>';
                break;
            case 'blue':
                flagText = 'BLUE FLAG';
                flagClass = 'apex-flag-blue';
                break;
            case 'white':
                flagText = 'WHITE';
                flagClass = 'apex-flag-white';
                break;
            case 'black_white':
            case 'blackwhite':
                flagText = 'BLACK & WHITE';
                flagClass = 'apex-flag-black-white';
                break;
            case 'red':
                flagText = 'RED FLAG';
                flagClass = 'apex-flag-red';
                break;
            case 'chequered':
            case 'checkered':
                flagText = 'CHEQUERED';
                flagClass = 'apex-flag-chequered';
                break;
            default:
                flagText = 'GREEN';
                flagClass = 'apex-flag-green';
        }
        
        $flag.addClass(flagClass).html('<span class="apex-flag-text">' + flagText + '</span>');
        
        // Update penalties
        if (penalties && penalties.trim()) {
            $penalties.show();
            $('#apex-penalty-text').text(penalties);
        } else {
            $penalties.hide();
        }
    }
    
    // Update tire display
    function updateTireDisplay(data) {
        var tires = [
            { id: 'fl', wear: data.tire_fl_wear, temp: data.tire_fl_temp },
            { id: 'fr', wear: data.tire_fr_wear, temp: data.tire_fr_temp },
            { id: 'rl', wear: data.tire_rl_wear, temp: data.tire_rl_temp },
            { id: 'rr', wear: data.tire_rr_wear, temp: data.tire_rr_temp }
        ];
        
        tires.forEach(function(tire) {
            var $tire = $('.apex-tire-' + tire.id);
            var wear = tire.wear || 100;
            var temp = tire.temp || 0;
            
            $('#apex-tire-' + tire.id + '-wear').text(wear.toFixed(0) + '%');
            $('#apex-tire-' + tire.id + '-temp').text(temp.toFixed(0) + '°C');
            
            // Color coding
            $tire.removeClass('apex-tire-critical apex-tire-warning');
            if (wear < 50) {
                $tire.addClass('apex-tire-critical');
            } else if (wear < 70) {
                $tire.addClass('apex-tire-warning');
            }
        });
    }
    
    // Update video embed (YouTube or Twitch)
    function updateVideoEmbed(streamUrl, streamType, isPremium) {
        var $placeholder = $('#apex-video-placeholder');
        var $iframe = $('#apex-spectator-iframe');
        
        if (streamUrl && isPremium) {
            var embedUrl = '';
            
            if (streamType === 'youtube') {
                var videoId = extractYoutubeId(streamUrl);
                if (videoId) {
                    embedUrl = 'https://www.youtube.com/embed/' + videoId + '?autoplay=1&mute=1';
                }
            } else if (streamType === 'twitch') {
                var channel = extractTwitchChannel(streamUrl);
                if (channel) {
                    embedUrl = 'https://player.twitch.tv/?channel=' + channel + '&parent=' + window.location.hostname + '&muted=true';
                }
            }
            
            if (embedUrl && $iframe.attr('src') !== embedUrl) {
                $iframe.attr('src', embedUrl).show();
                $placeholder.hide();
            }
        } else if (!isPremium && streamUrl) {
            // Show premium prompt
            $placeholder.html(
                '<div class="apex-video-premium-prompt">' +
                    '<h4>🎬 Premium Feature</h4>' +
                    '<p>Video stream embedding is available for premium broadcasters</p>' +
                '</div>'
            ).show();
            $iframe.hide();
        } else {
            $placeholder.html(
                '<svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><polygon points="5 3 19 12 5 21 5 3"/></svg>' +
                '<p>No video stream available</p>'
            ).show();
            $iframe.hide();
        }
    }
    
    // Extract YouTube video ID
    function extractYoutubeId(url) {
        if (!url) return null;
        var match = url.match(/(?:youtu\.be\/|youtube\.com(?:\/embed\/|\/v\/|\/watch\?v=|\/watch\?.+&v=|\/live\/))([^"&?\/\s]{11})/);
        return match ? match[1] : null;
    }
    
    // Extract Twitch channel name
    function extractTwitchChannel(url) {
        if (!url) return null;
        var match = url.match(/twitch\.tv\/([a-zA-Z0-9_]+)/);
        return match ? match[1] : null;
    }
    
    // Update spectator affiliate links
    function updateSpectatorAffiliates(links) {
        var $container = $('#apex-spectator-affiliates');
        var $linksContainer = $('#apex-affiliates-links');
        
        if (!links || links.length === 0) {
            $container.hide();
            return;
        }
        
        var html = '';
        links.forEach(function(link) {
            html += '<a href="' + escapeHtml(link.url) + '" target="_blank" rel="nofollow sponsored">' +
                '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>' +
                escapeHtml(link.label) +
            '</a>';
        });
        
        $linksContainer.html(html);
        $container.show();
    }
    
    // Timing tower toggle
    $(document).on('click', '#apex-timing-nearby', function() {
        liveRaceState.showFullGrid = false;
        $('#apex-timing-nearby').addClass('active');
        $('#apex-timing-full').removeClass('active');
        if (liveRaceState.lastUpdate) {
            updateTimingTower(liveRaceState.lastUpdate.positions, liveRaceState.lastUpdate.player_position);
        }
    });
    
    $(document).on('click', '#apex-timing-full', function() {
        liveRaceState.showFullGrid = true;
        $('#apex-timing-full').addClass('active');
        $('#apex-timing-nearby').removeClass('active');
        if (liveRaceState.lastUpdate) {
            updateTimingTower(liveRaceState.lastUpdate.positions, liveRaceState.lastUpdate.player_position);
        }
    });
    
    // Page navigation handling for live race
    $(document).on('click', '[data-page="live-race"]', function() {
        setTimeout(initLiveRacePage, 100);
    });
    
    // Stop polling when leaving spectator page
    $(document).on('click', '[data-page]:not([data-page="spectator"])', function() {
        stopSpectatorPolling();
    });
    
    // Check for broadcast URL on load
    $(document).ready(function() {
        checkBroadcastUrl();
    });

    // ===========================================
    // TIRE DATA FUNCTIONALITY
    // ===========================================
    
    var tireDataInitialized = false;
    var tireTracksLoaded = false;
    var tireCommunityData = [];
    
    // Initialize tire data when navigating to that page
    $(document).on('click', '[data-page="tire-data"]', function() {
        setTimeout(function() {
            if (!tireDataInitialized) {
                initTireData();
                tireDataInitialized = true;
            }
        }, 100);
    });
    
    function initTireData() {
        // Tab switching
        $('.apex-tire-tab').on('click', function() {
            var tab = $(this).data('tire-tab');
            $('.apex-tire-tab').removeClass('active');
            $(this).addClass('active');
            $('.apex-tire-tab-content').removeClass('active');
            $('.apex-tire-tab-content[data-tire-content="' + tab + '"]').addClass('active');
            
            if (tab === 'community' && !tireTracksLoaded) {
                loadTireCommunityData();
            }
        });
        
        // Load tracks for calculator
        loadTireTracksForCalculator();
        
        // Calculator button
        $('#apex-tire-calculate-btn').on('click', function() {
            calculateTireStrategy();
        });
        
        // Community filters
        $('#apex-tire-community-track, #apex-tire-community-class').on('change', function() {
            filterTireCommunityTable();
        });
    }
    
    function loadTireTracksForCalculator() {
        $.ajax({
            url: apexNotesData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'apex_notes_get_tire_tracks',
                nonce: apexNotesData.nonce
            },
            success: function(response) {
                if (response.success && response.data.tracks) {
                    var $trackSelect = $('#apex-tire-track-select, #apex-tire-community-track');
                    response.data.tracks.forEach(function(track) {
                        $trackSelect.append('<option value="' + escapeHtml(track) + '">' + escapeHtml(track) + '</option>');
                    });
                }
                
                if (response.success && response.data.cars) {
                    var $carSelect = $('#apex-tire-car-select');
                    response.data.cars.forEach(function(car) {
                        $carSelect.append('<option value="' + escapeHtml(car) + '">' + escapeHtml(car) + '</option>');
                    });
                }
            }
        });
    }
    
    function calculateTireStrategy() {
        var track = $('#apex-tire-track-select').val();
        var car = $('#apex-tire-car-select').val();
        var raceLaps = parseInt($('#apex-tire-race-laps').val()) || 0;
        var threshold = parseInt($('#apex-tire-threshold').val()) || 50;
        
        if (!track || !car || !raceLaps) {
            showNotification('Please fill in all fields', 'error');
            return;
        }
        
        var $btn = $('#apex-tire-calculate-btn');
        $btn.prop('disabled', true).html('<span class="apex-spinner-small"></span> Calculating...');
        
        $.ajax({
            url: apexNotesData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'apex_notes_calculate_tire_strategy',
                nonce: apexNotesData.nonce,
                track: track,
                car: car,
                race_laps: raceLaps,
                threshold: threshold
            },
            success: function(response) {
                $btn.prop('disabled', false).html('<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg> Calculate Strategy');
                
                if (response.success && response.data) {
                    displayTireStrategyResult(response.data);
                } else {
                    $('#apex-tire-calc-results').hide();
                    $('#apex-tire-no-data').show();
                }
            },
            error: function() {
                $btn.prop('disabled', false).html('<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg> Calculate Strategy');
                $('#apex-tire-calc-results').hide();
                $('#apex-tire-no-data').show();
            }
        });
    }
    
    function displayTireStrategyResult(data) {
        $('#apex-tire-no-data').hide();
        $('#apex-tire-calc-results').show();
        
        // Main stats
        $('#apex-tire-result-stops').text(data.recommended_stops);
        $('#apex-tire-result-critical').text(data.critical_tire);
        $('#apex-tire-result-life').text(data.estimated_tire_life + ' laps');
        $('#apex-tire-result-wear').text(data.avg_critical_wear.toFixed(2) + '%');
        
        // Per-corner wear
        $('#apex-tire-wear-fl').text(data.wear_fl.toFixed(2) + '%');
        $('#apex-tire-wear-fr').text(data.wear_fr.toFixed(2) + '%');
        $('#apex-tire-wear-rl').text(data.wear_rl.toFixed(2) + '%');
        $('#apex-tire-wear-rr').text(data.wear_rr.toFixed(2) + '%');
        
        // Wear bars (scaled to max wear)
        var maxWear = Math.max(data.wear_fl, data.wear_fr, data.wear_rl, data.wear_rr);
        var scaleFactor = 100 / (maxWear * 1.2);
        $('#apex-tire-bar-fl').css('width', (data.wear_fl * scaleFactor) + '%');
        $('#apex-tire-bar-fr').css('width', (data.wear_fr * scaleFactor) + '%');
        $('#apex-tire-bar-rl').css('width', (data.wear_rl * scaleFactor) + '%');
        $('#apex-tire-bar-rr').css('width', (data.wear_rr * scaleFactor) + '%');
        
        // Highlight critical tire
        $('.apex-tire-position').removeClass('apex-tire-critical-position');
        $('.apex-tire-' + data.critical_tire.toLowerCase()).addClass('apex-tire-critical-position');
        
        // Track characteristic note
        var noteText = '';
        if (data.wear_fr > data.wear_fl * 1.1) {
            noteText = '🔄 Track favors left turns - higher right-side tire wear';
        } else if (data.wear_fl > data.wear_fr * 1.1) {
            noteText = '🔄 Track favors right turns - higher left-side tire wear';
        } else {
            noteText = '⚖️ Balanced track - relatively even left/right tire wear';
        }
        $('#apex-tire-wear-note').text(noteText);
        
        // Stint breakdown
        var $stints = $('#apex-tire-stints');
        $stints.empty();
        
        var stintLength = data.estimated_tire_life;
        var totalLaps = parseInt($('#apex-tire-race-laps').val());
        var currentLap = 1;
        var stintNum = 1;
        
        while (currentLap <= totalLaps) {
            var lapsThisStint = Math.min(stintLength, totalLaps - currentLap + 1);
            var endLap = currentLap + lapsThisStint - 1;
            
            $stints.append(
                '<div class="apex-tire-stint">' +
                    '<div class="apex-tire-stint-header">Stint ' + stintNum + '</div>' +
                    '<div class="apex-tire-stint-laps">' + lapsThisStint + ' <span>laps</span></div>' +
                    '<div class="apex-tire-stint-range">Laps ' + currentLap + '-' + endLap + '</div>' +
                '</div>'
            );
            
            currentLap = endLap + 1;
            
            if (currentLap <= totalLaps) {
                $stints.append(
                    '<div class="apex-tire-stint apex-tire-stint-pit">' +
                        '<div class="apex-tire-stint-header">🔧 Pit Stop</div>' +
                        '<div class="apex-tire-stint-laps">~25s</div>' +
                    '</div>'
                );
            }
            
            stintNum++;
        }
    }
    
    function loadTireCommunityData() {
        tireTracksLoaded = true;
        
        $.ajax({
            url: apexNotesData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'apex_notes_get_tire_community_data',
                nonce: apexNotesData.nonce
            },
            success: function(response) {
                if (response.success && response.data) {
                    tireCommunityData = response.data;
                    renderTireCommunityTable(tireCommunityData);
                } else {
                    $('#apex-tire-community-body').html('<tr><td colspan="10" class="apex-tire-loading">No community data available yet.</td></tr>');
                }
            },
            error: function() {
                $('#apex-tire-community-body').html('<tr><td colspan="10" class="apex-tire-loading">Error loading data.</td></tr>');
            }
        });
    }
    
    function renderTireCommunityTable(data) {
        var $body = $('#apex-tire-community-body');
        $body.empty();
        
        if (!data || data.length === 0) {
            $body.html('<tr><td colspan="10" class="apex-tire-loading">No community data available yet.</td></tr>');
            return;
        }
        
        data.forEach(function(row) {
            var criticalClass = '';
            if (row.critical_tire === 'FR' || row.critical_tire === 'FL') {
                criticalClass = 'apex-tire-critical';
            }
            
            $body.append(
                '<tr>' +
                    '<td>' + escapeHtml(row.track_venue) + '</td>' +
                    '<td>' + escapeHtml(row.car_type) + '</td>' +
                    '<td>' + escapeHtml(row.tire_compound || 'Medium') + '</td>' +
                    '<td>' + (parseFloat(row.avg_wear_fl_per_lap) * 100).toFixed(2) + '%</td>' +
                    '<td>' + (parseFloat(row.avg_wear_fr_per_lap) * 100).toFixed(2) + '%</td>' +
                    '<td>' + (parseFloat(row.avg_wear_rl_per_lap) * 100).toFixed(2) + '%</td>' +
                    '<td>' + (parseFloat(row.avg_wear_rr_per_lap) * 100).toFixed(2) + '%</td>' +
                    '<td class="' + criticalClass + '">' + escapeHtml(row.critical_tire) + '</td>' +
                    '<td>' + (row.estimated_tire_life || '--') + ' laps</td>' +
                    '<td>' + row.sample_count + '</td>' +
                '</tr>'
            );
        });
    }
    
    function filterTireCommunityTable() {
        var trackFilter = $('#apex-tire-community-track').val();
        var classFilter = $('#apex-tire-community-class').val();
        
        var filtered = tireCommunityData.filter(function(row) {
            var matchTrack = !trackFilter || row.track_venue === trackFilter;
            var matchClass = !classFilter || row.car_class === classFilter;
            return matchTrack && matchClass;
        });
        
        renderTireCommunityTable(filtered);
    }

    // ===========================================
    // STEWARDS ROOM FUNCTIONALITY
    // ===========================================
    
    // Initialize stewards room when navigating to that page
    $(document).on('click', '[data-page="stewards-room"]', function() {
        setTimeout(function() {
            if (!stewardsInitialized) {
                initStewardsRoom();
                stewardsInitialized = true;
            }
        }, 100);
    });
    
    function initStewardsRoom() {
        var $dropzone = $('#apex-stewards-dropzone');
        var $fileInput = $('#apex-stewards-file-input');
        
        // Click to browse
        $dropzone.on('click', function() {
            $fileInput.click();
        });
        
        // File selected
        $fileInput.on('change', function(e) {
            if (e.target.files.length > 0) {
                handleStewardsFile(e.target.files[0]);
            }
        });
        
        // Drag and drop
        $dropzone.on('dragover dragenter', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).addClass('dragover');
        });
        
        $dropzone.on('dragleave drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).removeClass('dragover');
        });
        
        $dropzone.on('drop', function(e) {
            var files = e.originalEvent.dataTransfer.files;
            if (files.length > 0) {
                handleStewardsFile(files[0]);
            }
        });
        
        // Generate button
        $('#apex-stewards-generate-btn').on('click', function() {
            generateStewardsReport();
        });
        
        // Tab navigation
        $('.apex-stewards-tab').on('click', function() {
            var tab = $(this).data('tab');
            $('.apex-stewards-tab').removeClass('active');
            $(this).addClass('active');
            $('.apex-stewards-panel').removeClass('active');
            $('.apex-stewards-panel[data-panel="' + tab + '"]').addClass('active');
        });
        
        // Incidents filter
        $(document).on('change', '#apex-stewards-incidents-filter', function() {
            var selectedDriver = $(this).val();
            var $rows = $('#apex-stewards-incidents-body tr');
            
            if (selectedDriver === '') {
                $rows.show();
            } else {
                $rows.each(function() {
                    var rowDriver = $(this).data('driver');
                    if (rowDriver === selectedDriver) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            }
        });
        
        // Track limits filter
        $(document).on('change', '#apex-stewards-tracklimits-filter', function() {
            var selectedDriver = $(this).val();
            var $rows = $('#apex-stewards-tracklimits-body tr');
            
            if (selectedDriver === '') {
                $rows.show();
            } else {
                $rows.each(function() {
                    var rowDriver = $(this).data('driver');
                    if (rowDriver === selectedDriver) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            }
        });
        
        // New report button
        $('#apex-stewards-new-report').on('click', function() {
            resetStewardsRoom();
        });
        
        // Pagination click handler for incidents (delegated)
        $(document).on('click', '.apex-incidents-page-btn', function() {
            var page = parseInt($(this).data('page'));
            if (page && window.renderIncidentsPage) {
                window.renderIncidentsPage(page);
                // Scroll to top of incidents table
                $('html, body').animate({
                    scrollTop: $('#apex-stewards-incidents-table').offset().top - 100
                }, 300);
            }
        });
        
        // Pagination click handler for track limits (delegated)
        $(document).on('click', '.apex-tracklimits-page-btn', function() {
            var page = parseInt($(this).data('page'));
            if (page && window.renderTrackLimitsPage) {
                window.renderTrackLimitsPage(page);
                // Scroll to top of track limits table
                $('html, body').animate({
                    scrollTop: $('#apex-stewards-tracklimits-table').offset().top - 100
                }, 300);
            }
        });
        
        // Download PDF button
        $('#apex-stewards-download-pdf').on('click', function() {
            downloadStewardsPDF();
        });
    }
    
    function handleStewardsFile(file) {
        if (!file.name.toLowerCase().endsWith('.xml')) {
            alert('Please upload an XML file.');
            return;
        }
        
        stewardsFile = file;
        
        // Show meta fields
        $('#apex-stewards-dropzone').hide();
        $('#apex-stewards-meta-fields').show();
    }
    
    function generateStewardsReport() {
        if (!stewardsFile) {
            alert('No file selected.');
            return;
        }
        
        var formData = new FormData();
        formData.append('action', 'apex_notes_parse_stewards_xml');
        formData.append('xml_file', stewardsFile);
        
        // Show loading
        $('#apex-stewards-meta-fields').hide();
        $('#apex-stewards-loading').show();
        
        $.ajax({
            url: apexNotesData.ajaxUrl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#apex-stewards-loading').hide();
                
                if (response.success) {
                    stewardsData = response.data;
                    
                    // Add custom fields
                    stewardsData.custom = {
                        race_name: $('#apex-stewards-race-name').val(),
                        league_name: $('#apex-stewards-league-name').val(),
                        round: $('#apex-stewards-round').val()
                    };
                    
                    displayStewardsReport(stewardsData);
                } else {
                    alert(response.data.message || 'Error processing file.');
                    $('#apex-stewards-meta-fields').show();
                }
            },
            error: function() {
                $('#apex-stewards-loading').hide();
                alert('Error uploading file.');
                $('#apex-stewards-meta-fields').show();
            }
        });
    }
    
    function displayStewardsReport(data) {
        // Hide upload section, show results
        $('#apex-stewards-upload-section').hide();
        $('#apex-stewards-results').show();
        
        // Update race info
        $('#apex-stewards-track-name').text(data.race_info.track || 'Unknown Track');
        $('#apex-stewards-event-name').text(data.race_info.event || data.race_info.session_type || 'Race');
        $('#apex-stewards-race-date').text(data.race_info.date || 'Unknown Date');
        
        // Custom fields
        var customParts = [];
        if (data.custom.race_name) customParts.push(data.custom.race_name);
        if (data.custom.league_name) customParts.push(data.custom.league_name);
        if (data.custom.round) customParts.push(data.custom.round);
        $('#apex-stewards-race-custom').text(customParts.join(' • '));
        
        // Update stats
        $('#apex-stewards-total-incidents').text(data.stats.total_incidents);
        $('#apex-stewards-total-penalties').text(data.stats.total_penalties);
        $('#apex-stewards-total-tracklimits').text(data.stats.total_track_limits);
        $('#apex-stewards-total-drivers').text(data.stats.total_drivers);
        
        // Update fastest lap display
        if (data.fastest_lap && data.fastest_lap.time_formatted) {
            $('#apex-stewards-fastest-lap').text(data.fastest_lap.time_formatted);
            $('#apex-stewards-fastest-lap-driver').text(data.fastest_lap.driver + ' (#' + data.fastest_lap.car_number + ')');
            $('.apex-stewards-fastest-lap-stat').show();
        } else {
            $('.apex-stewards-fastest-lap-stat').hide();
        }
        
        // Populate incidents table with pagination
        var $incidentsBody = $('#apex-stewards-incidents-body');
        $incidentsBody.empty();
        var incidentDrivers = [];
        currentIncidentPage = 1;
        allIncidents = data.incidents || [];
        
        function renderIncidentsPage(page) {
            currentIncidentPage = page;
            $incidentsBody.empty();
            
            var startIdx = (page - 1) * incidentsPerPage;
            var endIdx = Math.min(startIdx + incidentsPerPage, allIncidents.length);
            var pageIncidents = allIncidents.slice(startIdx, endIdx);
            
            pageIncidents.forEach(function(inc) {
                var impactClass = '';
                var impactLabel = '';
                var impactVal = parseFloat(inc.impact) || 0;
                if (impactVal > 1000) {
                    impactClass = 'impact-high';
                    impactLabel = 'High';
                } else if (impactVal > 200) {
                    impactClass = 'impact-medium';
                    impactLabel = 'Medium';
                } else {
                    impactClass = 'impact-low';
                    impactLabel = 'Low';
                }
                
                var impactHtml = '<span class="impact-indicator ' + impactClass + '" title="Impact: ' + escapeHtml(inc.impact) + '">' +
                    '<span class="impact-dot"></span>' +
                    '<span class="impact-label">' + impactLabel + '</span>' +
                    '</span>';
                
                $incidentsBody.append(
                    '<tr data-driver="' + escapeHtml(inc.driver) + '">' +
                    '<td>' + escapeHtml(inc.time_formatted) + '</td>' +
                    '<td>' + escapeHtml(inc.driver) + ' (#' + escapeHtml(inc.car_number) + ')</td>' +
                    '<td>' + escapeHtml(inc.contact_with) + '</td>' +
                    '<td>' + impactHtml + '</td>' +
                    '</tr>'
                );
            });
            
            // Update pagination display
            updateIncidentsPagination();
        }
        
        function updateIncidentsPagination() {
            var totalPages = Math.ceil(allIncidents.length / incidentsPerPage);
            var $pagination = $('#apex-stewards-incidents-pagination');
            
            if (totalPages <= 1) {
                $pagination.hide();
                return;
            }
            
            $pagination.show();
            var html = '<div class="apex-pagination">';
            html += '<span class="apex-pagination-info">Showing ' + ((currentIncidentPage - 1) * incidentsPerPage + 1) + '-' + Math.min(currentIncidentPage * incidentsPerPage, allIncidents.length) + ' of ' + allIncidents.length + '</span>';
            html += '<div class="apex-pagination-buttons">';
            
            // Previous button
            if (currentIncidentPage > 1) {
                html += '<button class="apex-btn apex-btn-sm apex-incidents-page-btn" data-page="' + (currentIncidentPage - 1) + '">← Prev</button>';
            }
            
            // Page numbers
            var startPage = Math.max(1, currentIncidentPage - 2);
            var endPage = Math.min(totalPages, currentIncidentPage + 2);
            
            if (startPage > 1) {
                html += '<button class="apex-btn apex-btn-sm apex-incidents-page-btn" data-page="1">1</button>';
                if (startPage > 2) html += '<span class="apex-pagination-ellipsis">...</span>';
            }
            
            for (var p = startPage; p <= endPage; p++) {
                var activeClass = p === currentIncidentPage ? 'active' : '';
                html += '<button class="apex-btn apex-btn-sm apex-incidents-page-btn ' + activeClass + '" data-page="' + p + '">' + p + '</button>';
            }
            
            if (endPage < totalPages) {
                if (endPage < totalPages - 1) html += '<span class="apex-pagination-ellipsis">...</span>';
                html += '<button class="apex-btn apex-btn-sm apex-incidents-page-btn" data-page="' + totalPages + '">' + totalPages + '</button>';
            }
            
            // Next button
            if (currentIncidentPage < totalPages) {
                html += '<button class="apex-btn apex-btn-sm apex-incidents-page-btn" data-page="' + (currentIncidentPage + 1) + '">Next →</button>';
            }
            
            html += '</div></div>';
            $pagination.html(html);
        }
        
        // Store render function for use by pagination clicks
        window.renderIncidentsPage = renderIncidentsPage;
        
        if (data.incidents.length > 0) {
            // Collect unique drivers for filter
            data.incidents.forEach(function(inc) {
                if (inc.driver && incidentDrivers.indexOf(inc.driver) === -1) {
                    incidentDrivers.push(inc.driver);
                }
            });
            
            // Render first page
            renderIncidentsPage(1);
            $('#apex-stewards-no-incidents').hide();
            
            // Populate incidents filter dropdown
            var $incidentsFilter = $('#apex-stewards-incidents-filter');
            $incidentsFilter.find('option:not(:first)').remove();
            incidentDrivers.sort().forEach(function(driver) {
                $incidentsFilter.append('<option value="' + escapeHtml(driver) + '">' + escapeHtml(driver) + '</option>');
            });
        } else {
            $('#apex-stewards-no-incidents').show();
            $('#apex-stewards-incidents-pagination').hide();
        }
        
        // Populate penalties table
        var $penaltiesBody = $('#apex-stewards-penalties-body');
        $penaltiesBody.empty();
        if (data.penalties.length > 0) {
            data.penalties.forEach(function(pen) {
                var statusClass = '';
                if (pen.status === 'Issued') statusClass = 'status-issued';
                else if (pen.status === 'Served') statusClass = 'status-served';
                else statusClass = 'status-post-race';
                
                var penaltyText = pen.penalty_type || '';
                if (pen.duration) penaltyText += ' ' + pen.duration;
                
                $penaltiesBody.append(
                    '<tr>' +
                    '<td>' + escapeHtml(pen.time_formatted) + '</td>' +
                    '<td>' + escapeHtml(pen.driver) + '</td>' +
                    '<td>' + escapeHtml(penaltyText) + '</td>' +
                    '<td>' + escapeHtml(pen.reason || '-') + '</td>' +
                    '<td class="' + statusClass + '">' + escapeHtml(pen.status) + '</td>' +
                    '</tr>'
                );
            });
            $('#apex-stewards-no-penalties').hide();
        } else {
            $('#apex-stewards-no-penalties').show();
        }
        
        // Populate track limits table
        // Populate track limits table with pagination (filter out "No Further Action")
        var $tracklimitsBody = $('#apex-stewards-tracklimits-body');
        $tracklimitsBody.empty();
        var trackLimitDrivers = [];
        currentTrackLimitPage = 1;
        
        // Filter out "No Further Action" events - only show actionable track limits
        allTrackLimits = (data.track_limits || []).filter(function(tl) {
            var resolution = (tl.resolution || '').toLowerCase();
            return !resolution.includes('no further action') && resolution !== 'nfa';
        });
        
        // Update the stat count to show filtered count
        $('#apex-stewards-total-tracklimits').text(allTrackLimits.length);
        
        function renderTrackLimitsPage(page) {
            currentTrackLimitPage = page;
            $tracklimitsBody.empty();
            
            var startIdx = (page - 1) * trackLimitsPerPage;
            var endIdx = Math.min(startIdx + trackLimitsPerPage, allTrackLimits.length);
            var pageTrackLimits = allTrackLimits.slice(startIdx, endIdx);
            
            pageTrackLimits.forEach(function(tl) {
                var resClass = 'resolution-invalid';
                
                $tracklimitsBody.append(
                    '<tr data-driver="' + escapeHtml(tl.driver) + '">' +
                    '<td>' + escapeHtml(tl.time_formatted) + '</td>' +
                    '<td>' + escapeHtml(tl.driver) + ' (#' + escapeHtml(tl.car_number) + ')</td>' +
                    '<td>' + escapeHtml(tl.lap) + '</td>' +
                    '<td>' + escapeHtml(tl.warning_points) + '</td>' +
                    '<td>' + escapeHtml(tl.current_points) + '</td>' +
                    '<td class="' + resClass + '">' + escapeHtml(tl.resolution) + '</td>' +
                    '</tr>'
                );
            });
            
            // Update pagination display
            updateTrackLimitsPagination();
        }
        
        function updateTrackLimitsPagination() {
            var totalPages = Math.ceil(allTrackLimits.length / trackLimitsPerPage);
            var $pagination = $('#apex-stewards-tracklimits-pagination');
            
            if (totalPages <= 1) {
                $pagination.hide();
                return;
            }
            
            $pagination.show();
            var html = '<div class="apex-pagination">';
            html += '<span class="apex-pagination-info">Showing ' + ((currentTrackLimitPage - 1) * trackLimitsPerPage + 1) + '-' + Math.min(currentTrackLimitPage * trackLimitsPerPage, allTrackLimits.length) + ' of ' + allTrackLimits.length + '</span>';
            html += '<div class="apex-pagination-buttons">';
            
            // Previous button
            if (currentTrackLimitPage > 1) {
                html += '<button class="apex-btn apex-btn-sm apex-tracklimits-page-btn" data-page="' + (currentTrackLimitPage - 1) + '">← Prev</button>';
            }
            
            // Page numbers
            var startPage = Math.max(1, currentTrackLimitPage - 2);
            var endPage = Math.min(totalPages, currentTrackLimitPage + 2);
            
            if (startPage > 1) {
                html += '<button class="apex-btn apex-btn-sm apex-tracklimits-page-btn" data-page="1">1</button>';
                if (startPage > 2) html += '<span class="apex-pagination-ellipsis">...</span>';
            }
            
            for (var p = startPage; p <= endPage; p++) {
                var activeClass = p === currentTrackLimitPage ? 'active' : '';
                html += '<button class="apex-btn apex-btn-sm apex-tracklimits-page-btn ' + activeClass + '" data-page="' + p + '">' + p + '</button>';
            }
            
            if (endPage < totalPages) {
                if (endPage < totalPages - 1) html += '<span class="apex-pagination-ellipsis">...</span>';
                html += '<button class="apex-btn apex-btn-sm apex-tracklimits-page-btn" data-page="' + totalPages + '">' + totalPages + '</button>';
            }
            
            // Next button
            if (currentTrackLimitPage < totalPages) {
                html += '<button class="apex-btn apex-btn-sm apex-tracklimits-page-btn" data-page="' + (currentTrackLimitPage + 1) + '">Next →</button>';
            }
            
            html += '</div></div>';
            $pagination.html(html);
        }
        
        // Store render function for use by pagination clicks
        window.renderTrackLimitsPage = renderTrackLimitsPage;
        
        if (allTrackLimits.length > 0) {
            // Collect unique drivers for filter
            allTrackLimits.forEach(function(tl) {
                if (tl.driver && trackLimitDrivers.indexOf(tl.driver) === -1) {
                    trackLimitDrivers.push(tl.driver);
                }
            });
            
            // Render first page
            renderTrackLimitsPage(1);
            $('#apex-stewards-no-tracklimits').hide();
            
            // Populate track limits filter dropdown
            var $tracklimitsFilter = $('#apex-stewards-tracklimits-filter');
            $tracklimitsFilter.find('option:not(:first)').remove();
            trackLimitDrivers.sort().forEach(function(driver) {
                $tracklimitsFilter.append('<option value="' + escapeHtml(driver) + '">' + escapeHtml(driver) + '</option>');
            });
        } else {
            $('#apex-stewards-no-tracklimits').show();
            $('#apex-stewards-tracklimits-pagination').hide();
        }
        
        // Populate classification table
        var $classBody = $('#apex-stewards-classification-body');
        $classBody.empty();
        
        // Find the fastest lap time to highlight it
        var fastestLapTime = data.fastest_lap && data.fastest_lap.time ? data.fastest_lap.time : null;
        
        if (data.classification.length > 0) {
            data.classification.forEach(function(cls) {
                var statusClass = '';
                if (cls.status === 'DNS') {
                    statusClass = 'status-dns';
                } else if (cls.status === 'DNF' || cls.status === 'Suspension') {
                    statusClass = 'status-dnf';
                }
                
                var totalTime = cls.total_time || '--';
                if (parseInt(cls.laps) === 0) {
                    totalTime = '--';
                }
                
                // Best lap with fastest lap highlight
                var bestLap = cls.best_lap_formatted || '--';
                var bestLapClass = '';
                if (fastestLapTime && cls.best_lap_time === fastestLapTime) {
                    bestLapClass = 'fastest-lap';
                }
                
                var row = '<tr class="' + (cls.status === 'DNS' ? 'dns-row' : '') + '">' +
                    '<td>' + escapeHtml(String(cls.position)) + '</td>' +
                    '<td>' + escapeHtml(cls.driver || '') + ' (#' + escapeHtml(cls.car_number || '') + ')</td>' +
                    '<td>' + escapeHtml(cls.car_type || '') + '</td>' +
                    '<td>' + escapeHtml(cls.car_class || '') + '</td>' +
                    '<td>' + escapeHtml(String(cls.laps)) + '</td>' +
                    '<td class="' + bestLapClass + '">' + escapeHtml(bestLap) + '</td>' +
                    '<td>' + escapeHtml(totalTime) + '</td>' +
                    '<td class="' + statusClass + '">' + escapeHtml(cls.status || '') + '</td>' +
                    '</tr>';
                
                $classBody.append(row);
            });
            $('#apex-stewards-no-classification').hide();
        } else {
            $('#apex-stewards-no-classification').show();
        }
    }
    
    function resetStewardsRoom() {
        stewardsData = null;
        stewardsFile = null;
        
        // Reset form
        $('#apex-stewards-race-name').val('');
        $('#apex-stewards-league-name').val('');
        $('#apex-stewards-round').val('');
        $('#apex-stewards-file-input').val('');
        
        // Show upload, hide results
        $('#apex-stewards-results').hide();
        $('#apex-stewards-meta-fields').hide();
        $('#apex-stewards-dropzone').show();
        $('#apex-stewards-upload-section').show();
    }
    
    function downloadStewardsPDF() {
        if (!stewardsData) {
            showNotification('No report data available.', 'error');
            return;
        }
        
        // Get summary mode option
        var summaryMode = $('#apex-pdf-summary-mode').is(':checked');
        
        // Show loading
        var $btn = $('#apex-stewards-download-pdf');
        var originalText = $btn.html();
        $btn.prop('disabled', true).html('<span class="apex-spinner-small"></span> Generating...');
        
        // Load jsPDF directly
        if (typeof jspdf === 'undefined' && typeof jsPDF === 'undefined') {
            var script = document.createElement('script');
            script.src = 'https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js';
            script.onload = function() {
                buildPDFDirectly(summaryMode, function() {
                    $btn.prop('disabled', false).html(originalText);
                });
            };
            script.onerror = function() {
                showNotification('Failed to load PDF library.', 'error');
                $btn.prop('disabled', false).html(originalText);
            };
            document.head.appendChild(script);
        } else {
            buildPDFDirectly(summaryMode, function() {
                $btn.prop('disabled', false).html(originalText);
            });
        }
    }
    
    function buildPDFDirectly(summaryMode, callback) {
        var jsPDF = window.jspdf.jsPDF;
        var doc = new jsPDF('p', 'mm', 'a4');
        var pageWidth = doc.internal.pageSize.getWidth();
        var pageHeight = doc.internal.pageSize.getHeight();
        var margin = 15;
        var y = margin;
        var lineHeight = 5;
        var useSummaryMode = summaryMode || false;
        
        // Helper to add new page if needed
        function checkPage(neededHeight) {
            if (y + neededHeight > pageHeight - margin) {
                doc.addPage();
                y = margin;
                return true;
            }
            return false;
        }
        
        // Get race info
        var trackName = stewardsData.race_info ? stewardsData.race_info.track : 'Unknown Track';
        var raceDate = stewardsData.race_info ? stewardsData.race_info.date : '';
        var eventName = stewardsData.custom && stewardsData.custom.race_name ? stewardsData.custom.race_name : trackName;
        
        // Parse date and time
        var dateStr = '';
        var timeStr = '';
        if (raceDate) {
            var parts = raceDate.split(' ');
            dateStr = parts[0] || raceDate;
            timeStr = parts[1] || '';
        }
        if (!dateStr) {
            var now = new Date();
            dateStr = now.toISOString().split('T')[0];
            timeStr = now.toTimeString().split(' ')[0].substring(0, 5);
        }
        
        // ============================================
        // FIA-STYLE HEADER WITH LOGO
        // ============================================
        
        // Try to load and add logo image
        var logoUrl = apexNotesData.pluginUrl + 'assets/images/logo.png';
        var logoSize = 22;
        var logoX = margin;
        var logoY = y;
        
        // Load logo as image
        var img = new Image();
        img.crossOrigin = 'anonymous';
        img.onload = function() {
            try {
                // Create canvas to convert image
                var canvas = document.createElement('canvas');
                canvas.width = img.width;
                canvas.height = img.height;
                var ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0);
                var dataUrl = canvas.toDataURL('image/png');
                
                // Add logo to PDF
                doc.addImage(dataUrl, 'PNG', logoX, logoY, logoSize, logoSize);
            } catch(e) {
                console.log('Could not add logo:', e);
                // Draw placeholder circle if image fails
                doc.setDrawColor(26, 53, 93);
                doc.setLineWidth(1);
                doc.circle(logoX + logoSize/2, logoY + logoSize/2, logoSize/2, 'S');
            }
            
            // Continue building PDF after logo
            buildPDFContent();
        };
        
        img.onerror = function() {
            console.log('Logo load failed, using placeholder');
            // Draw placeholder circle
            doc.setDrawColor(26, 53, 93);
            doc.setLineWidth(1);
            doc.circle(logoX + logoSize/2, logoY + logoSize/2, logoSize/2, 'S');
            doc.setFontSize(5);
            doc.setTextColor(26, 53, 93);
            doc.text('APEX', logoX + logoSize/2, logoY + logoSize/2, { align: 'center' });
            
            buildPDFContent();
        };
        
        // Start loading logo
        img.src = logoUrl;
        
        function buildPDFContent() {
            // Right side - Document info (smaller, right-aligned)
            var rightX = pageWidth - margin;
            var infoY = logoY + 2;
            
            doc.setFontSize(8);
            doc.setTextColor(51, 51, 51);
            doc.setFont(undefined, 'bold');
            doc.text('Document', rightX - 50, infoY);
            doc.setFont(undefined, 'normal');
            doc.text('Stewards Report', rightX, infoY, { align: 'right' });
            
            infoY += 5;
            doc.setFont(undefined, 'bold');
            doc.text('Date', rightX - 50, infoY);
            doc.setFont(undefined, 'normal');
            doc.text(dateStr, rightX, infoY, { align: 'right' });
            
            infoY += 5;
            doc.setFont(undefined, 'bold');
            doc.text('Time', rightX - 50, infoY);
            doc.setFont(undefined, 'normal');
            doc.text(timeStr || '--:--', rightX, infoY, { align: 'right' });
            
            // EVENT NAME - Large and bold in center
            y = logoY + logoSize + 8;
            doc.setFontSize(16);
            doc.setFont(undefined, 'bold');
            doc.setTextColor(26, 53, 93); // Navy blue
            
            // Center the event name
            var eventNameDisplay = eventName;
            if (eventName.length > 50) {
                eventNameDisplay = eventName.substring(0, 47) + '...';
            }
            doc.text(eventNameDisplay, pageWidth / 2, y, { align: 'center' });
            
            // "From" section
            y += 8;
            doc.setFontSize(9);
            doc.setTextColor(51, 51, 51);
            doc.setFont(undefined, 'bold');
            doc.text('From', margin, y);
            doc.setFont(undefined, 'normal');
            doc.text('The Stewards', margin + 15, y);
            
            // Horizontal separator line
            y += 5;
            doc.setDrawColor(51, 51, 51);
            doc.setLineWidth(0.5);
            doc.line(margin, y, pageWidth - margin, y);
            
            y += 8;
            
            // ============================================
            // STATS SUMMARY
            // ============================================
            
            var incidents = stewardsData.incidents || [];
            var penalties = stewardsData.penalties || [];
            // Filter out "No Further Action" track limits for PDF
            var trackLimits = (stewardsData.track_limits || []).filter(function(tl) {
                var resolution = (tl.resolution || '').toLowerCase();
                return !resolution.includes('no further action') && resolution !== 'nfa';
            });
            var classification = stewardsData.classification || [];
            var fastestLap = stewardsData.fastest_lap || {};
            
            // Check if we have fastest lap data
            var hasFastestLap = fastestLap.time_formatted && fastestLap.time_formatted !== '';
            var numCols = hasFastestLap ? 5 : 4;
            var colWidth = (pageWidth - margin * 2) / numCols;
            
            doc.setFillColor(245, 245, 245);
            doc.rect(margin, y, pageWidth - margin * 2, 15, 'F');
            
            doc.setFontSize(14);
            doc.setTextColor(255, 106, 0);
            doc.setFont(undefined, 'bold');
            var statsY = y + 10;
            
            doc.text(incidents.length.toString(), margin + colWidth * 0.5, statsY, { align: 'center' });
            doc.text(penalties.length.toString(), margin + colWidth * 1.5, statsY, { align: 'center' });
            doc.text(trackLimits.length.toString(), margin + colWidth * 2.5, statsY, { align: 'center' });
            doc.text(classification.length.toString(), margin + colWidth * 3.5, statsY, { align: 'center' });
            
            if (hasFastestLap) {
                doc.setTextColor(155, 89, 182); // Purple for fastest lap
                doc.text(fastestLap.time_formatted, margin + colWidth * 4.5, statsY, { align: 'center' });
            }
            
            doc.setFontSize(7);
            doc.setTextColor(100, 100, 100);
            doc.setFont(undefined, 'normal');
            statsY += 4;
            doc.text('INCIDENTS', margin + colWidth * 0.5, statsY, { align: 'center' });
            doc.text('PENALTIES', margin + colWidth * 1.5, statsY, { align: 'center' });
            doc.text('TRACK LIMITS', margin + colWidth * 2.5, statsY, { align: 'center' });
            doc.text('DRIVERS', margin + colWidth * 3.5, statsY, { align: 'center' });
            
            if (hasFastestLap) {
                doc.text('FASTEST LAP', margin + colWidth * 4.5, statsY, { align: 'center' });
            }
            
            y += 22;
            
            // Continue with rest of PDF content
            buildPDFTables();
        }
        
        function buildPDFTables() {
            var incidents = stewardsData.incidents || [];
            var penalties = stewardsData.penalties || [];
            // Filter out "No Further Action" track limits for PDF
            var trackLimits = (stewardsData.track_limits || []).filter(function(tl) {
                var resolution = (tl.resolution || '').toLowerCase();
                return !resolution.includes('no further action') && resolution !== 'nfa';
            });
            var classification = stewardsData.classification || [];
        
            // Incidents section
            checkPage(20);
            doc.setFontSize(12);
            doc.setTextColor(255, 106, 0);
            doc.setFont(undefined, 'bold');
            
            if (useSummaryMode && incidents.length > 0) {
                // SUMMARY MODE: Group incidents by driver
                doc.text('Incident Summary by Driver', margin, y);
                doc.setFont(undefined, 'normal');
                y += 2;
                doc.setDrawColor(200, 200, 200);
                doc.line(margin, y, pageWidth - margin, y);
                y += 5;
                
                // Aggregate incidents by driver
                var driverSummary = {};
                incidents.forEach(function(inc) {
                    var driverKey = inc.driver + ' (#' + inc.car_number + ')';
                    if (!driverSummary[driverKey]) {
                        driverSummary[driverKey] = {
                            driver: inc.driver,
                            car_number: inc.car_number,
                            contacts: {},
                            totalIncidents: 0,
                            highSeverity: 0,
                            mediumSeverity: 0,
                            lowSeverity: 0,
                            maxImpact: 0
                        };
                    }
                    driverSummary[driverKey].totalIncidents++;
                    
                    var impact = parseFloat(inc.impact) || 0;
                    if (impact > driverSummary[driverKey].maxImpact) {
                        driverSummary[driverKey].maxImpact = impact;
                    }
                    if (impact > 1000) driverSummary[driverKey].highSeverity++;
                    else if (impact > 200) driverSummary[driverKey].mediumSeverity++;
                    else driverSummary[driverKey].lowSeverity++;
                    
                    // Track who they had contact with
                    var contactWith = inc.contact_with || 'Unknown';
                    if (!driverSummary[driverKey].contacts[contactWith]) {
                        driverSummary[driverKey].contacts[contactWith] = [];
                    }
                    driverSummary[driverKey].contacts[contactWith].push(inc.time_formatted);
                });
                
                // Convert to array and sort by total incidents (descending)
                var sortedDrivers = Object.keys(driverSummary).map(function(key) {
                    return driverSummary[key];
                }).sort(function(a, b) {
                    return b.totalIncidents - a.totalIncidents;
                });
                
                // Table header
                doc.setFillColor(26, 26, 26);
                doc.rect(margin, y, pageWidth - margin * 2, 6, 'F');
                doc.setFontSize(8);
                doc.setTextColor(255, 255, 255);
                doc.text('Driver', margin + 2, y + 4);
                doc.text('Total', margin + 65, y + 4);
                doc.text('High', margin + 82, y + 4);
                doc.text('Med', margin + 97, y + 4);
                doc.text('Low', margin + 112, y + 4);
                doc.text('Contacts With', margin + 127, y + 4);
                y += 8;
                
                // Render each driver summary
                doc.setTextColor(51, 51, 51);
                for (var d = 0; d < sortedDrivers.length; d++) {
                    var driver = sortedDrivers[d];
                    checkPage(8);
                    
                    if (d % 2 === 1) {
                        doc.setFillColor(249, 249, 249);
                        doc.rect(margin, y - 1, pageWidth - margin * 2, 7, 'F');
                    }
                    
                    doc.setFontSize(7);
                    doc.setFont(undefined, 'bold');
                    doc.setTextColor(51, 51, 51);
                    doc.text(driver.driver + ' (#' + driver.car_number + ')', margin + 2, y + 3);
                    
                    doc.setFont(undefined, 'normal');
                    doc.text(driver.totalIncidents.toString(), margin + 70, y + 3);
                    
                    // Colored severity counts
                    doc.setTextColor(220, 53, 69);
                    doc.text(driver.highSeverity.toString(), margin + 85, y + 3);
                    doc.setTextColor(253, 126, 20);
                    doc.text(driver.mediumSeverity.toString(), margin + 100, y + 3);
                    doc.setTextColor(40, 167, 69);
                    doc.text(driver.lowSeverity.toString(), margin + 115, y + 3);
                    
                    // List contacts (truncated)
                    doc.setTextColor(51, 51, 51);
                    var contactList = Object.keys(driver.contacts).slice(0, 3).join(', ');
                    if (Object.keys(driver.contacts).length > 3) {
                        contactList += ' +' + (Object.keys(driver.contacts).length - 3);
                    }
                    doc.text(contactList.substring(0, 35), margin + 127, y + 3);
                    
                    y += 7;
                }
                
            } else {
                // DETAILED MODE: Show individual incidents
                doc.text('Race Incidents', margin, y);
                doc.setFont(undefined, 'normal');
                y += 2;
                doc.setDrawColor(200, 200, 200);
                doc.line(margin, y, pageWidth - margin, y);
                y += 5;
                
                if (incidents.length > 0) {
                    // Table header
                    doc.setFillColor(26, 26, 26);
                    doc.rect(margin, y, pageWidth - margin * 2, 6, 'F');
                    doc.setFontSize(8);
                    doc.setTextColor(255, 255, 255);
                    doc.text('Time', margin + 2, y + 4);
                    doc.text('Driver', margin + 25, y + 4);
                    doc.text('Contact With', margin + 80, y + 4);
                    doc.text('Severity', margin + 145, y + 4);
                    y += 8;
                    
                    // Table rows (limit to 30)
                    doc.setTextColor(51, 51, 51);
                    var maxRows = Math.min(incidents.length, 30);
                    for (var i = 0; i < maxRows; i++) {
                        checkPage(6);
                        var inc = incidents[i];
                        if (i % 2 === 1) {
                            doc.setFillColor(249, 249, 249);
                            doc.rect(margin, y - 1, pageWidth - margin * 2, 5, 'F');
                        }
                        doc.setFontSize(7);
                        doc.setFont(undefined, 'normal');
                        doc.text(inc.time_formatted || '', margin + 2, y + 3);
                        doc.text((inc.driver || '') + ' (#' + (inc.car_number || '') + ')', margin + 25, y + 3);
                        doc.text(inc.contact_with || '', margin + 80, y + 3);
                        
                        // Severity indicator with colored dot and label
                        var impact = parseFloat(inc.impact) || 0;
                        var severityLabel = '';
                        var dotX = margin + 145;
                        
                        if (impact > 1000) {
                            doc.setFillColor(220, 53, 69); // Red
                            doc.setTextColor(220, 53, 69);
                            severityLabel = 'High';
                        } else if (impact > 200) {
                            doc.setFillColor(253, 126, 20); // Orange
                            doc.setTextColor(253, 126, 20);
                            severityLabel = 'Medium';
                        } else {
                            doc.setFillColor(40, 167, 69); // Green
                            doc.setTextColor(40, 167, 69);
                            severityLabel = 'Low';
                        }
                        
                        // Draw colored dot
                        doc.circle(dotX + 1.5, y + 2, 1.5, 'F');
                        // Draw label
                        doc.text(severityLabel, dotX + 5, y + 3);
                        doc.setTextColor(51, 51, 51);
                        
                        y += 5;
                    }
                    if (incidents.length > 30) {
                        doc.setFontSize(7);
                        doc.setTextColor(100, 100, 100);
                        doc.text('... and ' + (incidents.length - 30) + ' more incidents', margin + 2, y + 3);
                        y += 5;
                    }
                } else {
                    doc.setFontSize(9);
                    doc.setTextColor(150, 150, 150);
                    doc.text('No incidents recorded.', margin, y + 3);
                    y += 8;
                }
            }
            
            y += 8;
            
            // Penalties section
            checkPage(20);
            doc.setFontSize(12);
            doc.setTextColor(255, 106, 0);
            doc.setFont(undefined, 'bold');
            doc.text('Penalties Issued', margin, y);
            doc.setFont(undefined, 'normal');
            y += 2;
            doc.line(margin, y, pageWidth - margin, y);
            y += 5;
            
            if (penalties.length > 0) {
                doc.setFillColor(26, 26, 26);
                doc.rect(margin, y, pageWidth - margin * 2, 6, 'F');
                doc.setFontSize(8);
                doc.setTextColor(255, 255, 255);
                doc.text('Time', margin + 2, y + 4);
                doc.text('Driver', margin + 25, y + 4);
                doc.text('Penalty', margin + 80, y + 4);
                doc.text('Status', margin + 140, y + 4);
                y += 8;
                
                doc.setTextColor(51, 51, 51);
                for (var j = 0; j < penalties.length; j++) {
                    checkPage(6);
                    var pen = penalties[j];
                    if (j % 2 === 1) {
                        doc.setFillColor(249, 249, 249);
                        doc.rect(margin, y - 1, pageWidth - margin * 2, 5, 'F');
                    }
                    doc.setFontSize(7);
                    doc.setFont(undefined, 'normal');
                    doc.text(pen.time_formatted || '', margin + 2, y + 3);
                    doc.text(pen.driver || '', margin + 25, y + 3);
                    doc.text((pen.penalty_type || '') + (pen.duration ? ' ' + pen.duration : ''), margin + 80, y + 3);
                    
                    if (pen.status === 'Issued') doc.setTextColor(220, 53, 69);
                    else doc.setTextColor(40, 167, 69);
                    doc.text(pen.status || '', margin + 140, y + 3);
                    doc.setTextColor(51, 51, 51);
                    
                    y += 5;
                }
            } else {
                doc.setFontSize(9);
                doc.setTextColor(150, 150, 150);
                doc.text('No penalties issued.', margin, y + 3);
                y += 8;
            }
            
            y += 8;
            
            // Classification section
            checkPage(20);
            doc.setFontSize(12);
            doc.setTextColor(255, 106, 0);
            doc.setFont(undefined, 'bold');
            doc.text('Final Classification', margin, y);
            doc.setFont(undefined, 'normal');
            y += 2;
            doc.line(margin, y, pageWidth - margin, y);
            y += 5;
            
            // Get fastest lap time for highlighting
            var fastestLapTime = stewardsData.fastest_lap && stewardsData.fastest_lap.time ? stewardsData.fastest_lap.time : null;
        
            if (classification.length > 0) {
                doc.setFillColor(26, 26, 26);
                doc.rect(margin, y, pageWidth - margin * 2, 6, 'F');
                doc.setFontSize(8);
                doc.setTextColor(255, 255, 255);
                doc.text('Pos', margin + 2, y + 4);
                doc.text('Driver', margin + 12, y + 4);
                doc.text('Car', margin + 65, y + 4);
                doc.text('Laps', margin + 110, y + 4);
                doc.text('Best Lap', margin + 125, y + 4);
                doc.text('Status', margin + 160, y + 4);
                y += 8;
                
                doc.setTextColor(51, 51, 51);
                for (var k = 0; k < classification.length; k++) {
                    checkPage(6);
                    var cls = classification[k];
                    if (k % 2 === 1) {
                        doc.setFillColor(249, 249, 249);
                        doc.rect(margin, y - 1, pageWidth - margin * 2, 5, 'F');
                    }
                    doc.setFontSize(7);
                    doc.setFont(undefined, 'normal');
                    doc.text((cls.position || '').toString(), margin + 2, y + 3);
                    doc.text((cls.driver || '') + ' (#' + (cls.car_number || '') + ')', margin + 12, y + 3);
                    doc.text((cls.car_type || '').substring(0, 25), margin + 65, y + 3);
                    doc.text((cls.laps || '0').toString(), margin + 110, y + 3);
                    
                    // Best lap with fastest lap highlighting
                    var bestLap = cls.best_lap_formatted || '--';
                    if (fastestLapTime && cls.best_lap_time === fastestLapTime) {
                        doc.setTextColor(155, 89, 182); // Purple for fastest
                        doc.setFont(undefined, 'bold');
                    }
                    doc.text(bestLap, margin + 125, y + 3);
                    doc.setFont(undefined, 'normal');
                    doc.setTextColor(51, 51, 51);
                    
                    var status = cls.status || 'Finished';
                    if (status === 'DNF') doc.setTextColor(220, 53, 69);
                    else if (status === 'DNS') doc.setTextColor(108, 117, 125);
                    else doc.setTextColor(51, 51, 51);
                    doc.text(status, margin + 160, y + 3);
                    doc.setTextColor(51, 51, 51);
                    
                    y += 5;
                }
            } else {
                doc.setFontSize(9);
                doc.setTextColor(150, 150, 150);
                doc.text('No classification data.', margin, y + 3);
            }
            
            // Footer
            doc.setFontSize(8);
            doc.setTextColor(150, 150, 150);
            doc.text('Generated by Apex Race Notes - apexracenotes.com', pageWidth / 2, pageHeight - 10, { align: 'center' });
            
            // Save
            var trackNameClean = (stewardsData.race_info ? stewardsData.race_info.track : 'Race').replace(/[^a-zA-Z0-9]/g, '-');
            var filename = 'Stewards_Report_' + trackNameClean + '_' + new Date().toISOString().split('T')[0] + '.pdf';
            doc.save(filename);
            
            showNotification('PDF downloaded successfully!', 'success');
            if (callback) callback();
        }
    }
    
    function escapeHtml(text) {
        if (!text) return '';
        var div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Stewards Report Save/Share functionality
    function initStewardsReportSaving() {
        // Load saved report count on page load
        if ($('#apex-stewards-report-count').length) {
            loadStewardsReportCount();
        }
        
        // Save Report button
        $('#apex-stewards-save-report').on('click', function() {
            if (!stewardsData) {
                showNotification('No report data to save. Please generate a report first.', 'error');
                return;
            }
            
            // Pre-fill the name with track and date
            var suggestedName = (stewardsData.race_info.track || 'Race') + ' - ' + (stewardsData.race_info.date || 'Unknown Date');
            if (stewardsData.custom && stewardsData.custom.race_name) {
                suggestedName = stewardsData.custom.race_name;
            }
            $('#apex-stewards-save-name').val(suggestedName);
            
            $('#apex-stewards-save-modal').addClass('active');
        });
        
        // Confirm save
        $('#apex-stewards-save-confirm').on('click', function() {
            var reportName = $('#apex-stewards-save-name').val().trim();
            if (!reportName) {
                showNotification('Please enter a name for this report.', 'error');
                return;
            }
            
            var $btn = $(this);
            $btn.prop('disabled', true).text('Saving...');
            
            $.ajax({
                url: apexNotesData.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'apex_notes_save_stewards_report',
                    report_name: reportName,
                    track_name: stewardsData.race_info.track || '',
                    event_name: stewardsData.race_info.event || stewardsData.race_info.session_type || '',
                    race_date: stewardsData.race_info.date || '',
                    report_data: JSON.stringify(stewardsData)
                },
                success: function(response) {
                    $btn.prop('disabled', false).text('Save Report');
                    
                    if (response.success) {
                        $('#apex-stewards-save-modal').removeClass('active');
                        
                        // Show success notification
                        if (response.data.updated) {
                            showNotification('Report Updated!', 'success');
                        } else {
                            showNotification('Report Saved to Profile!', 'success');
                        }
                        
                        // Update count
                        $('#apex-stewards-report-count').text(response.data.saved_count);
                    } else {
                        showNotification(response.data.message || 'Failed to save report.', 'error');
                    }
                },
                error: function() {
                    $btn.prop('disabled', false).text('Save Report');
                    showNotification('Error saving report.', 'error');
                }
            });
        });
        
        // My Reports button - now navigates to profile
        $('#apex-stewards-my-reports').on('click', function() {
            // Navigate to own profile
            if (apexNotesData.isLoggedIn && apexNotesData.currentUser) {
                showUserProfile(apexNotesData.currentUser.id);
                // Scroll to stewards section after a brief delay
                setTimeout(function() {
                    var $section = $('#apex-profile-stewards-reports');
                    if ($section.length) {
                        $('html, body').animate({
                            scrollTop: $section.offset().top - 100
                        }, 300);
                    }
                }, 800);
            } else {
                showNotification('Please log in to view your saved reports.', 'error');
            }
        });
        
        // Close modals
        $('[data-close]').on('click', function() {
            var modalId = $(this).data('close');
            $('#' + modalId).removeClass('active');
        });
        
        // Close modal on backdrop click
        $('.apex-modal-overlay').on('click', function(e) {
            if (e.target === this) {
                $(this).removeClass('active');
            }
        });
        
        // Check for shared report URL
        checkSharedReportUrl();
    }
    
    function loadStewardsReportCount() {
        $.ajax({
            url: apexNotesData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'apex_notes_get_my_stewards_reports'
            },
            success: function(response) {
                if (response.success) {
                    $('#apex-stewards-report-count').text(response.data.count);
                }
            }
        });
    }
    
    function checkSharedReportUrl() {
        var path = window.location.pathname;
        var match = path.match(/\/stewards\/view\/([a-zA-Z0-9]+)/);
        
        if (match) {
            var token = match[1];
            loadSharedReport(token);
        }
    }
    
    function loadSharedReport(token) {
        // Make sure stewards room page is visible
        $('.apex-page').removeClass('active');
        $('.apex-page[data-page="stewards-room"]').addClass('active');
        
        // Show loading state, hide upload section
        var $uploadSection = $('#apex-stewards-upload-section');
        var $loading = $('#apex-stewards-loading');
        var $results = $('#apex-stewards-results');
        
        if ($uploadSection.length) $uploadSection.hide();
        if ($loading.length) {
            $loading.find('p').text('Loading shared report...');
            $loading.show();
        }
        if ($results.length) $results.hide();
        
        $.ajax({
            url: apexNotesData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'apex_notes_get_shared_report',
                share_token: token
            },
            success: function(response) {
                if ($loading.length) $loading.hide();
                
                if (response.success) {
                    // Set stewardsData and display
                    stewardsData = response.data.report_data;
                    stewardsData.custom = {
                        race_name: response.data.report_name,
                        league_name: '',
                        round: ''
                    };
                    stewardsData.shared_by = response.data.author_name;
                    stewardsData.view_count = response.data.view_count;
                    
                    displayStewardsReport(stewardsData);
                    
                    // Show shared indicator
                    if (response.data.author_name) {
                        $('#apex-stewards-race-custom').append(' <span class="apex-shared-by">• Shared by ' + escapeHtml(response.data.author_name) + '</span>');
                    }
                } else {
                    showNotification(response.data.message || 'Report not found.', 'error');
                    if ($uploadSection.length) $uploadSection.show();
                }
            },
            error: function(xhr, status, error) {
                if ($loading.length) $loading.hide();
                showNotification('Error loading report.', 'error');
                if ($uploadSection.length) $uploadSection.show();
            }
        });
    }
    
    // Initialize report saving on document ready
    $(document).ready(function() {
        if ($('#apex-stewards-save-report').length) {
            initStewardsReportSaving();
        }
    });

    // ========================================================================
    // PIT STRATEGY CALCULATOR + FUEL AI CHAT
    // ========================================================================

    var fuelChatHistory = [];

    // Populate session dropdown when Pit Strategy tab is shown for the first time
    var pitSessionsLoaded = false;
    $(document).on('click', '.apex-fuel-tab[data-fuel-tab="pit-strategy"]', function() {
        if (!pitSessionsLoaded) {
            loadPitSessions();
            pitSessionsLoaded = true;
        }
    });

    function loadPitSessions() {
        if (!apexNotesData.isLoggedIn) {
            $('#apex-pit-session').html('<option value="">Sign in to use your sessions</option>');
            return;
        }
        $.ajax({
            url: apexNotesData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'apex_notes_get_fuel_sessions',
                nonce: apexNotesData.nonce,
                limit: 25
            },
            success: function(response) {
                var $sel = $('#apex-pit-session');
                $sel.empty();
                $sel.append('<option value="">— No session (manual entry) —</option>');
                if (response.success && response.data.sessions && response.data.sessions.length) {
                    response.data.sessions.forEach(function(s) {
                        var label = s.track_venue + ' • ' + s.car_type +
                            ' (' + (s.avg_fuel_liters ? parseFloat(s.avg_fuel_liters).toFixed(2) + 'L/lap' : 'no fuel data') + ')';
                        $sel.append('<option value="' + s.id + '">' + escapeHtml(label) + '</option>');
                    });
                } else {
                    $sel.append('<option value="">No sessions yet — upload one for best results</option>');
                }
            }
        });
    }

    $(document).on('click', '#apex-pit-calc-run', function() {
        var $btn = $(this);
        var $result = $('#apex-pit-calc-result');

        var payload = {
            action: 'apex_notes_calculate_pit_strategy',
            nonce: apexNotesData.nonce,
            session_id: $('#apex-pit-session').val() || 0,
            race_laps: $('#apex-pit-race-laps').val() || 0,
            race_minutes: $('#apex-pit-race-minutes').val() || 0,
            reserve_liters: $('#apex-pit-reserve').val() || 0.5,
            avg_fuel_per_lap: $('#apex-pit-avg-fuel').val() || 0,
            tank_capacity: $('#apex-pit-tank').val() || 0
        };

        $btn.prop('disabled', true).text('Calculating…');
        $result.hide().empty();

        $.ajax({
            url: apexNotesData.ajaxUrl,
            type: 'POST',
            data: payload,
            success: function(response) {
                $btn.prop('disabled', false).text('Calculate Pit Plan');
                if (response.success) {
                    renderPitPlan(response.data);
                } else {
                    $result.show().html('<p class="apex-pit-calc-error">' + escapeHtml(response.data.message || 'Calculation failed') + '</p>');
                }
            },
            error: function() {
                $btn.prop('disabled', false).text('Calculate Pit Plan');
                $result.show().html('<p class="apex-pit-calc-error">Network error — try again.</p>');
            }
        });
    });

    function renderPitPlan(data) {
        var $result = $('#apex-pit-calc-result');
        var inp = data.inputs;
        var plan = data.plan;

        var rows = plan.stints.map(function(s) {
            return '<tr>' +
                '<td>' + s.stint + '</td>' +
                '<td>' + s.start_lap + '–' + s.end_lap + ' (' + s.laps + ' laps)</td>' +
                '<td>' + (s.pit_in_lap !== null ? 'Pit after lap ' + s.pit_in_lap : '—') + '</td>' +
                '<td>' + s.fuel_needed_liters.toFixed(2) + ' L</td>' +
                '</tr>';
        }).join('');

        var evenSplit = plan.even_split_pit_laps && plan.even_split_pit_laps.length
            ? '<p class="apex-pit-even-split">Even-split suggestion (minimises fuel carried): pit on laps <strong>' +
              plan.even_split_pit_laps.join(', ') + '</strong>.</p>'
            : '';

        var html =
            '<div class="apex-pit-calc-summary">' +
                '<div><span>Laps per tank</span><strong>' + plan.laps_per_tank + '</strong></div>' +
                '<div><span>Pit stops</span><strong>' + plan.num_pitstops + '</strong></div>' +
                '<div><span>Min possible stops</span><strong>' + plan.min_possible_pitstops + '</strong></div>' +
                '<div><span>Total fuel used</span><strong>' + plan.total_fuel_used_liters.toFixed(2) + ' L</strong></div>' +
                (plan.estimated_race_time_formatted ? '<div><span>Est. race time</span><strong>' + plan.estimated_race_time_formatted + '</strong></div>' : '') +
            '</div>' +
            '<p class="apex-pit-calc-inputs">Source: <strong>' + inp.source + '</strong> • ' +
                inp.tank_capacity + ' L tank • ' + inp.avg_fuel_per_lap.toFixed(3) + ' L/lap' +
                (inp.avg_lap_time_formatted ? ' • avg ' + inp.avg_lap_time_formatted : '') +
                ' • reserve ' + inp.reserve_liters + ' L' +
                (inp.fuel_mult !== 1 ? ' • ' + inp.fuel_mult + 'x fuel mult' : '') +
            '</p>' +
            evenSplit +
            '<table class="apex-pit-calc-table">' +
                '<thead><tr><th>Stint</th><th>Laps</th><th>Pit lap</th><th>Fuel load</th></tr></thead>' +
                '<tbody>' + rows + '</tbody>' +
            '</table>' +
            '<div class="apex-pit-calc-followup">' +
                '<button class="apex-btn apex-btn-ghost" id="apex-pit-ask-ai">Ask AI to refine this plan</button>' +
            '</div>';

        $result.show().html(html);
    }

    $(document).on('click', '#apex-pit-ask-ai', function() {
        var sessionId = $('#apex-pit-session').val() || 0;
        var laps = $('#apex-pit-race-laps').val();
        var mins = $('#apex-pit-race-minutes').val();
        var q = 'Given my session data, suggest the best pit laps for a race of ';
        if (laps) q += laps + ' laps'; else if (mins) q += mins + ' minutes'; else q += 'this length';
        q += '. Explain your reasoning briefly and consider fuel saving vs. pace.';
        $('#apex-fuel-ai-input').val(q).focus();
        $('html, body').animate({ scrollTop: $('#apex-fuel-ai-input').offset().top - 100 }, 300);
    });

    // AI Chat
    $(document).on('click', '#apex-fuel-ai-send', sendFuelAIMessage);
    $(document).on('keydown', '#apex-fuel-ai-input', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendFuelAIMessage();
        }
    });
    $(document).on('click', '.apex-fuel-ai-suggestion', function() {
        $('#apex-fuel-ai-input').val($(this).data('query'));
        sendFuelAIMessage();
    });

    function sendFuelAIMessage() {
        var $input = $('#apex-fuel-ai-input');
        var $send = $('#apex-fuel-ai-send');
        var $messages = $('#apex-fuel-ai-messages');
        var message = ($input.val() || '').trim();

        if (!message) return;

        // Remove the empty-state hints on first message
        $messages.find('.apex-fuel-ai-empty').remove();

        appendFuelChatMessage(message, 'user');
        fuelChatHistory.push({ role: 'user', content: message });

        $input.val('').prop('disabled', true);
        $send.prop('disabled', true);

        var $typing = $('<div class="apex-fuel-ai-msg assistant typing"><em>ApexFuelBot is thinking…</em></div>');
        $messages.append($typing);
        $messages.scrollTop($messages[0].scrollHeight);

        $.ajax({
            url: apexNotesData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'apex_notes_ai_fuel_chat',
                nonce: apexNotesData.nonce,
                message: message,
                session_id: $('#apex-pit-session').val() || 0,
                history: JSON.stringify(fuelChatHistory.slice(-6))
            },
            success: function(response) {
                $typing.remove();
                if (response.success) {
                    var reply = response.data.reply || '(no response)';
                    appendFuelChatMessage(reply, 'assistant');
                    fuelChatHistory.push({ role: 'assistant', content: reply });
                } else {
                    appendFuelChatMessage((response.data && response.data.message) ? response.data.message : 'AI request failed.', 'assistant error');
                }
                $input.prop('disabled', false).focus();
                $send.prop('disabled', false);
            },
            error: function() {
                $typing.remove();
                appendFuelChatMessage('Network error — please retry.', 'assistant error');
                $input.prop('disabled', false).focus();
                $send.prop('disabled', false);
            }
        });
    }

    function appendFuelChatMessage(text, role) {
        var $messages = $('#apex-fuel-ai-messages');
        var content = (role === 'user') ? escapeHtml(text) : formatAIResponse(text);
        var classes = 'apex-fuel-ai-msg ' + role;
        $messages.append('<div class="' + classes + '">' + content + '</div>');
        $messages.scrollTop($messages[0].scrollHeight);
    }

})(jQuery);
