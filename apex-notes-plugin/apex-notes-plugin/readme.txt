=== Apex Notes - Le Mans Ultimate Track Notes ===
Contributors: apexnotes
Tags: racing, sim racing, le mans, track notes, gaming, community
Requires at least: 5.0
Tested up to: 6.4
Requires PHP: 7.4
Stable tag: 1.18.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A community-driven platform for sharing detailed racing track notes, braking zones, and racing lines for Le Mans Ultimate sim racing.

== Description ==

Apex Notes is a comprehensive WordPress plugin that allows your community to share and discover detailed track notes for Le Mans Ultimate. 

**Features:**

* **Complete Car Database** - All official Le Mans Ultimate cars including Hypercars, LMGT3, LMP2, LMP3, and GTE classes
* **Full Track List** - All 13 circuits from Le Mans Ultimate
* **Detailed Section Notes** - Corner-by-corner breakdowns with braking points, turn-in, apex, exit, gear, and speed information
* **Community Ratings** - 5-star rating system for quality control
* **Moderation System** - Review and approve submissions before publishing
* **Profanity Filter** - Automatic detection of inappropriate content
* **User Profiles** - Track your submitted notes and ratings
* **Featured Notes** - Highlight the best community contributions
* **DLC Badges** - Clearly mark DLC content
* **Community Fuel Database** - Upload session XML files to build community-wide fuel consumption averages

**Car Classes Included:**

* Hypercar (13 cars): Ferrari 499P, Toyota GR010-Hybrid, Porsche 963, and more
* LMGT3 (10 cars): Ford Mustang, McLaren 720S, Porsche 911 GT3 R, and more
* LMP2 (2 cars): ORECA 07 Gibson 2023 & 2024
* LMP3 (2 cars): Ginetta G61, Ligier JS P325
* GTE (4 cars): Aston Martin Vantage AMR, Corvette C8.R, Ferrari 488 GTE, Porsche 911 RSR

**Tracks Included:**

Circuit de la Sarthe (Le Mans), Spa-Francorchamps, Monza, Bahrain, Fuji, Sebring, Portimão, COTA, Imola, Interlagos, Lusail, Paul Ricard, Silverstone

== Installation ==

1. Upload the `apex-notes` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Create a new page and add the shortcode `[apex_notes]`
4. Configure settings in the WordPress admin under 'Apex Notes'

== Shortcodes ==

**Main Display:**
`[apex_notes]` - Displays the full Apex Notes interface

== Frequently Asked Questions ==

= How do users submit notes? =

Users must be logged into your WordPress site to submit track notes. Once logged in, they can click "New Note" to create a submission.

= How does moderation work? =

All new submissions go into a pending queue. Administrators can review and approve/reject notes from the WordPress admin panel under Apex Notes > Pending Review.

= Can I customize the profanity filter? =

Yes! Go to Apex Notes > Settings in WordPress admin to add custom blocked words.

= Does this work with any WordPress theme? =

Yes, Apex Notes is designed to work with any theme. It uses its own contained styling that won't conflict with your theme.

= How does the Fuel Data feature work? =

Users can upload their LMU session XML files (found in Documents\My Games\Le Mans Ultimate\UserData\Log\Results\). The plugin parses fuel consumption, Virtual Energy usage, and lap times. Users can optionally share their data anonymously to build community-wide fuel averages for each track/car combination.

== Screenshots ==

1. Main discovery page with filters
2. Detailed note view with corner breakdowns
3. Create new note form
4. Admin moderation panel
5. Settings page

== Changelog ==

= 1.19.1 =
* CRITICAL FIX: In LMU multiplayer XMLs every driver is flagged isPlayer=1 — the previous logic silently picked whichever driver appeared first, which was almost never the uploader. This caused wrong car/class/stats to be saved.
* NEW: Driver picker — after upload the plugin shows every driver in the XML (name, car, class, grid→finish, laps, pitstops) and requires you to confirm which one is you before saving anything. Nothing hits the database until you click Save.
* NEW: The plugin remembers your LMU driver name (per WP user) and auto-preselects it on future uploads. Uncheck the "remember" box to disable.
* NEW: Opt-in (default on) anonymous import of the other drivers' data from the same XML as ghost sessions (user_id=0, no names stored). Feeds community fuel averages without attributing to any user's profile.
* Fix: Reverted an over-aggressive container traversal in find_player_driver that could match drivers from unrelated nested sessions.
* Fix: Driver-name element is &lt;Name&gt; in LMU XMLs; the parser was reading &lt;n&gt;. Player names now populate correctly in the UI.

= 1.19.0 =
* NEW: Pit-Stop Calculator — work out optimal pit laps from your uploaded session data, supporting both lap-count and time-based races. Plans stints, fuel loads per stint, and suggests an even-split pit schedule to minimise fuel carried.
* NEW: ApexFuelBot AI chat (Anthropic Claude) — ask questions about your fuel data, stint strategy, and pace. Uses your recent uploaded sessions plus community medians as context. Rate-limited to 30 questions/day per user.
* NEW: Community averages now bucketed by fuel multiplier (1x / 2x / etc.) so mixed-multiplier sessions no longer contaminate each other.
* NEW: Median + IQR-trimmed means replace raw averages for community fuel figures — a single bad lap no longer skews the bucket.
* Fixed: XML parser now handles multiplayer/online race XMLs where Driver elements are nested inside RaceResults->Race (same fix as the Stewards Room got in 1.18.3).
* Fixed: More LMU session types now parsed — Race1/Race2, Qualifying1-3, Practice1-4, TestDay, WarmUp — and unknown session-type elements are tolerated.
* Fixed: Solo/hotlap XMLs with a single driver and no isPlayer flag now parse correctly.
* Improved: Pit out-lap (the lap after a pit stop) is now excluded from fuel averages — no more noisy data from cold-tyre laps.
* Improved: "No fuel burn recorded" anomaly detection (tiny fuel-used values with normal lap times are now flagged invalid).
* Improved: Uploads are no longer restricted to Race sessions — Practice, Qualifying, TestDay, and WarmUp all work. Community averages still require ≥3 valid laps per session.
* Improved: Clearer error messages for unrecognised XMLs.

= 1.18.4 =
* Fixed: Driver names now correctly extracted from XML using xpath and fallback methods
* Improved: Uses multiple methods to find driver name element (xpath, direct access, children iteration)
* Fixed: Only includes drivers with valid position values in classification
* The <n> element in XML containing driver names is now properly parsed

= 1.18.3 =
* Fixed: Classification now works with multiplayer/online race XML files
* Driver elements in online races are inside RaceResults->Race, not directly under RaceResults
* Parser now checks both locations for compatibility with all XML formats

= 1.18.2 =
* Fixed: Classification data now correctly parsed from XML
* Fixed: Track name, event, and date now correctly extracted from RaceResults node
* Fixed: Lap count uses <Laps> element, finish status uses <FinishStatus>
* Stewards Room now shows full driver classification with positions, cars, and laps

= 1.18.1 =
* Fixed: Stewards Room now integrated directly into main plugin (no separate page needed)
* Stewards Room accessible from main navigation like other pages
* Click "Stewards" in nav to access the race report generator

= 1.18.0 =
* NEW FEATURE: Stewards Room - Race incident reports and official documents
* Upload race XML files to generate detailed stewards reports
* Extracts and displays: Incidents/collisions, Penalties, Track Limit violations, Final classification
* Race times converted to HH:MM:SS format
* Add custom Race Name, League Name, and Round Number to reports
* Download formatted PDF with Apex Race Notes branding
* PDF includes: Logo header, race details, stats summary, all incident tables
* Impact severity color-coded (High/Medium/Low)
* Penalty status tracking (Issued/Served/Post-Race)
* Track limit decisions shown (No Further Action/Invalid Lap)
* New shortcode: [apex_stewards] for standalone Stewards Room page
* Nav links added to main navigation

= 1.17.2 =
* Added: Fuel Data Tools admin page (Apex Notes > Fuel Data Tools)
* Added: Duplicate session detection and removal tool
* Shows duplicate groups with session IDs and lap counts
* Option to remove all duplicates at once (keeps oldest)
* Option to choose which session to keep for each duplicate group
* Duplicates identified by: same user, track, car, and session date

= 1.17.1 =
* Fixed: Individual session cards now display horizontally in a grid (not stacked vertically)
* Widened: Fuel Data container from 900px to 1100px for better card layout
* Cards now properly wrap to multiple rows on wider screens

= 1.17.0 =
* Redesigned Community Data layout:
  - TOP: Separate aggregated section for Normal (1x) fuel - green accent
  - MIDDLE: Separate aggregated section for 2x Fuel - orange accent (if 2x sessions exist)
  - BOTTOM: All individual session cards in a grid
* Each aggregated section shows: avg fuel/lap (big), range, VE, best lap, session/lap count
* Clear visual distinction between 1x and 2x fuel data
* Individual sessions now all display as separate cards

= 1.16.9 =
* Fixed: Community Data now shows ALL individual sessions as separate cards
* Each session card shows: date, fuel/lap, range, VE, best lap, lap count
* Sessions sorted by date (newest first)
* Added grid layout for session cards

= 1.16.8 =
* Added: Auto-detection of new cars from XML uploads
* When any user uploads an XML with a new car, it's automatically added to the dropdown
* New cars are sorted alphabetically within their class
* Car list is now self-maintaining - no manual updates needed!

= 1.16.7 =
* Added: Automatic car list updater - car names are now automatically corrected when plugin is updated
* No more manual database edits required!

= 1.16.6 =
* Fixed: ALL car names now match EXACT XML output from LMU
* Fixed: BMW M4 LMGT3 (was BMW M4 GT3)
* Fixed: Porsche 911 GT3 R LMGT3 (was Porsche 911 LMGT3 R)
* Fixed: Toyota GR010 (was Toyota GR010-Hybrid)
* Fixed: Peugeot 9x8 (was Peugeot 9X8 - case sensitive!)
* Fixed: Lexus RCF LMGT3 (was Lexus RC F LMGT3)
* Fixed: Chevrolet Corvette Z06 LMGT3.R (was Corvette Z06 GT3.R)
* Fixed: Isotta Fraschini TIPO6 (was Isotta Fraschini Tipo6-C)
* Added: Mercedes-AMG LMGT3 (was missing!)
* Added: Glickenhaus SCG007, Vanwall 680, Aston Martin Valkyrie LMH
* Added: GTE class with Porsche 911 RSR-19, Ferrari 488 GTE EVO, Corvette C8.R GTE, Aston Martin Vantage AMR
* Removed: Acura ARX-06 (not in LMU)

= 1.16.5 =
* Fixed: LMGT3 car names now match XML output (BMW M4 LMGT3 instead of BMW M4 GT3, etc.)
* Added: Admin page now shows actual car names from your database
* Added: "Car Names in Your Database" table showing exact names to use

= 1.16.4 =
* Added: LMP3 class with Ginetta G61-LT-P325 Evo and Ligier JS P325 to Fuel Data cars
* Updated: Reference list in admin panel now includes LMP3 cars

= 1.16.3 =
* Added: Admin page to manage Fuel Data cars (Apex Notes > Fuel Data Cars)
* Cars are now stored in database and can be edited without code changes
* Can add/remove cars per class
* Can add new classes
* Includes reference list of common LMU car names

= 1.16.2 =
* Added: Individual session summary card back below the fuel averages box
* Now shows both: aggregated averages by fuel mult AND the session summary card

= 1.16.1 =
* Changed: Community Data now requires BOTH track AND car selection
* Changed: Replaced Class dropdown with Car dropdown for precise filtering
* Community averages box only appears when both track and car are selected
* Added: Best lap time display in community averages
* Fixed: Filtering now works correctly - only shows data for selected car

= 1.16.0 =
* Added: Community Fuel Averages box on Fuel Data page
* Shows average fuel consumption for each car on selected track
* Displays separate averages for Normal (1x) and 2x fuel consumption
* Shows session count and total laps for each data point
* Groups data by car with class badges

= 1.15.9 =
* Changed: Fuel Data XML upload limit increased from 10MB to 50MB

= 1.15.8 =
* Added: Full note editing - can now edit Track Walkthrough sections (braking, turn-in, apex, exit, gear, speed, pro tip)
* Added: Add and remove sections from the edit modal
* Changed: Edit modal now scrollable for longer notes
* Changed: Note owners can now edit their own notes (not just moderators)

= 1.15.7 =
* Changed: Track Guide navigation icon from video camera to checkered flag

= 1.15.6 =
* Fixed: Better error handling for livery uploads - now shows actual server limits when uploads fail
* Fixed: Added try-catch wrapper to prevent silent failures
* Added: Server limit detection to diagnose upload failures
* Note: If uploads fail with 400 error, check nginx client_max_body_size and PHP upload_max_filesize

= 1.15.5 =
* Fixed: Livery URL routing - added multiple fallbacks:
  - PHP passes livery token to JS via apexNotesData.liveryToken
  - Added redirect_canonical filter to prevent WordPress from redirecting livery URLs
  - Added template_redirect fallback to handle 404s on livery URLs
* Now livery URLs should work even if WordPress rewrite rules don't match

= 1.15.4 =
* Added: Debug logging to diagnose livery URL routing issues
* Debug: Check browser console (F12) when visiting /livery/TOKEN URLs

= 1.15.3 =
* Fixed: Copy URL button now works properly with fallback for older browsers
* Fixed: Livery download now serves files through PHP to bypass nginx 403 restrictions
* Fixed: Admin liveries page now shows direct download buttons instead of folder link
* Fixed: Livery share URL routing - added livery to getPageFromUrl matcher
* Added: PHP-based file download handler for livery files

= 1.15.2 =
* Fixed: Legal checkbox now visible (was being hidden by conflicting CSS rule)
* Fixed: Share Livery button now properly orange colored
* Fixed: Form validation moved to JavaScript to avoid HTML5 validation issues
* Added: Console debug logging to help diagnose upload issues

= 1.15.1 =
* Added: Liveries admin page - view all uploaded liveries with user info, IP addresses, Discord IDs
* Added: Legal disclaimer checkbox for livery uploads - users must agree before uploading
* Added: IP address and Discord ID tracking for livery uploads
* Added: Upload progress bar with percentage during livery upload
* Fixed: Livery download page URL routing
* Fixed: Better error messages for upload failures
* Improved: Livery upload now verifies directory creation before moving files

= 1.15.0 =
* REMOVED: Live Events feature completely removed (YouTube API integration)
* REMOVED: Live Events admin page
* REMOVED: YouTube API key settings
* REMOVED: Live Events cron jobs
* All other features remain intact

= 1.14.8 =
* Added: "Test API" button in admin that shows exact API response for each channel
* Added: Debug output showing channel ID resolution success/failure with details
* This helps diagnose whether channels are being found correctly

= 1.14.7 =
* FIXED: YouTube channel handles now use EXACT case matching (IMSAOfficial, not imsaofficial)
* FIXED: Cache check now skips empty cached values that could cause false failures
* NOTE: YouTube API forHandle is CASE-SENSITIVE!

= 1.14.6 =
* REVERTED: Channel lookup back to original working format
* FIXED: Channels now use original lowercase handles that were working before

= 1.14.5 =
* Added: Multiple fallback methods for YouTube channel lookup (forHandle, forUsername, search)
* Added: "Clear Channel Cache" button in admin to force re-fetch of channel IDs
* Fixed: Cache now properly skips empty cached values

= 1.14.4 =
* Fixed: Removed made-up channel handle that was breaking FIA WEC
* Fixed: Channels now use only verified handles: IMSAOfficial, FIAWEC, GTWorld, creventicmotorsportstv

= 1.14.3 =
* Fixed: YouTube channel handle resolution for IMSA (IMSAOfficial)
* Added: Better error reporting when channel handles fail to resolve
* Added: Debug info showing which channels failed to resolve

= 1.14.2 =
* Added: WordPress Admin page for Live Events management (Apex Notes > Live Events)
* Added: Manual "Refresh Now" button in admin to trigger YouTube API refresh
* Added: Configurable cron intervals (5min, 15min, 30min, hourly, disabled)
* Added: Admin dashboard showing live, upcoming, and completed events
* Added: YouTube API key management in admin panel
* Added: "Clear All Events" admin function
* Added: Visual indicators for events past their scheduled time
* Fixed: Cron job management with proper scheduling/rescheduling

= 1.14.1 =
* Added: Direct video status checking for live stream detection
* Fixed: Live events now properly detect when streams go live using YouTube Videos API
* Fixed: Events past their scheduled time are individually checked for live status
* Improved: More reliable live/upcoming status detection bypassing Search API delays

= 1.14.0 =
* Added: Mobile hamburger menu for navigation on screens under 900px
* Added: Mobile menu drawer with all navigation links and user profile
* Added: Livery Sharing feature - users can share custom liveries for 48 hours
* Added: Livery upload requiring exact files: customskin.tga and customskin_region.tga
* Added: Share livery URL and Discord message copy functionality
* Added: Download tracking for shared liveries
* Added: Automatic livery expiration and cleanup (hourly cron)
* Added: Livery download page with installation instructions
* Replaced: Class filter dropdown with Reset Filters button
* Fixed: Filter dropdowns no longer affect search box sizing

= 1.10.4 =
* Fixed: Search bar and dropdowns now identical height (36px)
* Fixed: Removed search icon as requested
* Fixed: All filter elements now flex to fill available space
* Fixed: Dropdowns use flex: 1 to distribute width evenly

= 1.10.3 =
* Fixed: Search bar now has dark background matching dropdowns
* Fixed: Search bar height reduced to 38px to match dropdowns
* Fixed: Search icon now visible with proper z-index
* Fixed: Search bar width fixed at 250px, not stretching full width
* Fixed: Used !important to override browser defaults on background-color

= 1.10.2 =
* Fixed: Search bar and dropdowns now have identical 40px height
* Fixed: Search icon now visible with proper positioning
* Fixed: Dropdown text padding corrected for readability
* Fixed: All filter elements now perfectly aligned

= 1.10.1 =
* Fixed: Filter dropdowns now have uniform height and consistent styling across all browsers
* Fixed: Live Race login gate now matches other pages (Sign In Required with lock icon)
* Improved: Search input and dropdowns are now properly aligned and professional looking
* Improved: Responsive filter layout for mobile and tablet

= 1.10.0 =
* NEW FEATURE: Live Race Broadcasting
* Broadcast live race telemetry to spectators via unique shareable links
* Real-time timing tower with full grid or ±5 nearby positions toggle
* Live flag display: yellow, blue, black & white flags with sector info
* Live tire wear visualization with temperature data
* Driver stats: gaps, lap times, fuel levels
* Step-by-step "Get Started" wizard for first-time broadcasters
* Premium feature: YouTube AND Twitch stream embedding
* Affiliate links: Broadcasters can add product links (Amazon, Fanatec, etc.) to monetize
* SimHub plugin for sending telemetry (requires compilation)
* Database: Added live_broadcasts, live_telemetry, and affiliate_links tables
* New navigation button for Live Race section
* Polling-based updates (2 second intervals) for hosting compatibility
* NEW: Backup & Export feature in admin panel
* Export all settings including API keys to JSON file
* Import backup to restore settings on new WordPress site
* Optional: Include all data (notes, ratings, fuel data) in backup
* Safe import: Existing data won't be overwritten

= 1.9.6 =
* Community Data: Now displays fuel multiplier badge (e.g., "2x" or "Mixed")
* Community Data: Now displays average pit stops per session
* Community Data: Database schema updated with avg_pitstops and fuel_mult_note columns
* Activation: Plugin now recalculates all community averages on activation/update
* Fixed: Community averages now properly include pit stop and fuel multiplier data
* Note: Grid/finish position only appears for sessions uploaded after v1.9.5

= 1.9.5 =
* Fuel Data: My Sessions now shows fuel multiplier badge (e.g., "2x") when non-standard
* Fuel Data: My Sessions now shows pit stop count
* Fuel Data: Extracts grid position and finish position from Race XML
* Fuel Data: My Sessions shows position change (P5 → P3) with color indicator
* Fuel Data: Database schema updated for grid/finish positions
* UI: Added styling for position changes and fuel multiplier badges

= 1.9.4 =
* Removed "My Notes" and "Moderation" from top navigation bar (already in user dropdown menu)
* Fuel Data: Now displays fuel multiplier badge when race uses non-standard fuel burn rate (e.g., "2x Fuel")
* Fuel Data: Fuel multiplier extracted from XML and shown in session analysis
* UI: More space for main navigation menu items

= 1.9.3 =
* Fuel Data: Now only accepts RACE sessions (not Practice/Qualifying)
* Fuel Data: Clear error message when non-Race session is uploaded
* Fuel Data: Added missing tracks to Community Data filter (Interlagos, Qatar, Paul Ricard)
* Fuel Data: Improved car name matching for tank capacity lookup (fuzzy matching)
* Fuel Data: Added class-based tank capacity fallbacks (GT3=120L, LMP2=75L, etc.)
* Fuel Data: Better error handling when saving sessions to database
* Fuel Data: Clear messaging about Race session file naming (-R1.xml, -R2.xml)
* Fuel Data: Updated "How it works" section to clarify Race-only requirement

= 1.9.2 =
* Added sign-in requirement for premium features
* ApexTrackBot now requires login to use
* Live Events (race streams) now requires login to view
* Fuel Data (XML upload/analyze) now requires login
* Create/Edit Race Notes now requires login
* My Notes page now requires login
* Added styled login prompt with Sign In button on restricted pages

= 1.9.1 =
* ApexTrackBot: Added Race Car Setup Flowchart knowledge
* New topics: corner exit grip, corner entry balance, toe, caster, engine brake, wheel rate, rake, grip, traction, turn-in, centering force
* ApexTrackBot: Added seat position and sim rig ergonomics guidance
* New data source: Qubic System (GT seating position guide)
* Systematic setup adjustment order: Exit Grip → Entry Balance → Driver Preference
* Updated suggestion chips and help text

= 1.9.0 =
* NEW: Fuel Data page - Community-powered fuel consumption database
* Upload LMU session XML files for automatic fuel analysis
* Parser extracts: fuel/lap, Virtual Energy/lap, lap times, pit stops
* Car specification database with tank capacities for all 29 LMU cars
* "Share with Community" option to contribute anonymous data
* Community averages by track + car combination with sample counts
* My Sessions tab to view/manage uploaded sessions
* Community Data tab with track and class filters
* Drag-and-drop file upload with progress indicator
* Shows min/max fuel range and VE usage for Hypercars
* Database tables: car_specs, fuel_sessions, fuel_laps, fuel_averages
* XML Parser handles Practice, Qualifying, and Race sessions
* Invalid lap detection: pit laps, incomplete laps, outliers filtered
* Fuel Data nav button with "NEW" badge

= 1.8.0 =
* NEW: Live Events page with calendar view
* Track upcoming and live motorsport streams from YouTube
* Channels: IMSA Official, Creventic, FIA WEC, GT World
* Calendar shows events by date with color-coded status
* "Live Now" section highlights currently streaming races
* User alert notifications when subscribed channels go live
* Toggle alerts on/off for each channel individually
* Auto-refresh events every 15 minutes via WordPress cron
* YouTube API integration for real-time event data
* New admin setting for YouTube API key
* "Live Events" navigation button with live indicator
* Mobile responsive calendar and event list

= 1.7.1 =
* Renamed Track AI to "ApexTrackBot" - specialized assistant for tracks and setups
* ApexTrackBot now ONLY answers questions about motorsport tracks and car setups
* Added comprehensive car setup knowledge base (tyre pressures, suspension, aero, differential, brakes)
* New setup topics: understeer/oversteer fixes, endurance vs sprint setups, class-specific guides
* Added additional track data sources: Silhouet Track Database, Motorsport Magazine Circuits
* Added setup data sources: Ultimate Setup Hub, Coach Dave Academy
* Bot politely declines questions not related to tracks or setups
* Expanded built-in circuit database with Mugello, Albert Park, Daytona, Road America, Watkins Glen
* Updated quick suggestions for both track and setup queries
* New clock icon for ApexTrackBot branding
* Updated admin settings with detailed data source information

= 1.7.0 =
* Added Track AI Assistant - AI-powered chatbot for motorsport circuit information
* Ask questions about any of 700+ racing circuits worldwide
* Built-in knowledge about turn names, track layouts, history, and characteristics
* Covers major circuits: Spa, Le Mans, Monza, Silverstone, Nürburgring, Suzuka, etc.
* Quick suggestion buttons for common questions
* Chat history within session for follow-up questions
* Optional Anthropic API integration for real-time web search
* Data sourced from RacingCircuits.info
* Admin setting for Anthropic API key in Settings page
* New "Track AI" navigation button with distinctive styling

= 1.6.0 =
* Added follow system - users can follow each other
* Notifications system with bell icon in header
* Get notified when someone follows you
* Get notified when someone comments on your note
* Get notified when someone you follow publishes a new note
* Profile page shows follower and following counts
* Click stats to view followers/following list
* Follow/unfollow buttons on profiles and in lists
* Notifications dropdown with read/unread states
* Mark individual or all notifications as read
* Updated Track Video Guide with correct YouTube video IDs
* Added Paul Ricard to track video guides
* Periodic notification count polling (60 seconds)

= 1.5.2 =
* Added Track Video Guide page with dedicated nav button
* Video guides for all LMU tracks (Spa, Le Mans, Sebring, Monza, Portimão, Fuji, Bahrain, Imola, COTA, Interlagos, Qatar, Silverstone)
* YouTube video embeds from top LMU content creators
* Track selector dropdown with video thumbnails
* Video modal player with embedded YouTube
* Orange highlighted "Track Guide" nav button

= 1.5.1 =
* Fixed "Back to Notes" button not working on detail page
* Added comments section to note detail page
* Added share button to note detail page
* Admin can edit note title, description, lap time, and setup notes
* Admin can delete notes of poor quality
* Admin can delete inappropriate comments
* Comments show author avatar and timestamp
* Improved back link styling and clickability

= 1.5.0 =
* User profiles with bio, Discord username, and Steam ID
* Profile avatars from Discord/Google (cannot be changed locally)
* Note cards now show author with clickable link to profile
* Thumbs up/down voting system for rating note quality
* Share button for Twitter, Facebook, Discord, and copy link
* Fixed dropdown menu disappearing on hover (added click toggle)
* WordPress admin bar now visible for admins, hidden for regular users
* Admin users no longer require email verification
* Profile page showing user's notes and total upvotes received

= 1.4.0 =
* Added Google OAuth login
* Added Discord OAuth login
* Added email verification requirement before users can post
* OAuth users are automatically verified
* Added login modal with OAuth buttons
* Added verification banner for unverified users
* Fixed logo size issue (was displaying at full size)
* Admin settings for OAuth credentials

= 1.3.0 =
* Added new Apex Notes logo (racing car with notepad design)
* Fullscreen mode - plugin now fills the entire screen
* Hides WordPress admin bar, theme headers, footers, and sidebars when viewing the plugin
* Logo displayed as image instead of SVG for better branding

= 1.2.0 =
* Fixed JavaScript error: "Cannot read properties of undefined (reading 'reset')"
* Fixed form ID mismatch between HTML and JavaScript (apex-create-form)
* Added null checks to prevent JS errors when elements don't exist
* Fixed "Share Your Notes", "Back to Notes", and "Create New Note" buttons

= 1.1.0 =
* Updated design to match new TrackNotes visual style
* New dark theme with orange accents
* Redesigned Track Walkthrough section with:
  - Numbered orange circles for section headers
  - 3-column grid layout for corner data
  - Colored icons for Braking (red), Turn-In (blue), Apex (green), Exit (purple), Gear (orange), Speed (pink)
  - Pro Tip section with lightbulb icon
* Changed font to Inter for modern appearance
* Improved card layouts and spacing
* Updated detail page with author info and actions

= 1.0.0 =
* Initial release
* Complete car and track database for Le Mans Ultimate
* User submissions with moderation
* Star rating system
* Profanity filter
* Admin dashboard

== Upgrade Notice ==

= 1.3.0 =
New branding with official Apex Notes logo. Fullscreen mode for immersive experience.

= 1.2.0 =
Critical bug fix for JavaScript errors preventing buttons from working. Please update immediately.

= 1.1.0 =
Major visual redesign with new dark theme, improved Track Walkthrough layout, and modern styling.

= 1.0.0 =
Initial release of Apex Notes for Le Mans Ultimate.
