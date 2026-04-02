<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Dashboard' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/python/public/css/admin.css">
    <link rel="stylesheet" href="/python/public/css/store.css">
</head>
<body class="admin-body">

    <!-- Background Animation (Global) -->
    <ul class="circles">
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
        <li></li>
    </ul>

    <!-- Admin Dock (Exact Style as Public) -->
    <nav class="floating-glass-header">
        <div class="glass-pill px-5">
            <!-- Left Group -->
            <a href="/python/public/" class="nav-link" title="Go Home">
                <i class="bi bi-house-fill"></i>
            </a>
            
            <a href="/python/public/menu" class="nav-link" title="Apps">
                <i class="bi bi-grid"></i>
            </a>

            <a href="/python/public/calendar" class="nav-link" title="Calendar">
                <i class="bi bi-calendar"></i>
            </a>

            <div class="dock-separator-vertical mx-3"></div>

            <!-- Center Group (Admin Features) - Only visible to Admins -->
            <?php if(isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
            <a href="/python/public/admin" class="nav-link <?= $_SERVER['REQUEST_URI'] == '/python/public/admin' ? 'active-glow' : '' ?>" title="Dashboard">
                <i class="bi bi-grid-1x2-fill"></i>
            </a>

            <a href="/python/public/admin/events" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/admin/events') !== false ? 'active-glow' : '' ?>" title="Manage Events">
                <i class="bi bi-calendar-event"></i>
            </a>

            <a href="/python/public/admin/categories" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/admin/categories') !== false ? 'active-glow' : '' ?>" title="Categories">
                <i class="bi bi-tags"></i>
            </a>

            <a href="/python/public/admin/attendance" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/admin/attendance') !== false ? 'active-glow' : '' ?>" title="Attendance">
                <i class="bi bi-person-check"></i>
            </a>
            <?php endif; ?>
            <?php if(isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'user'): ?>
                <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/inscriptions') !== false ? 'active-glow' : '' ?>" href="/python/public/inscriptions" title="Inscriptions">
                    <i class="bi bi-ticket-perforated"></i>
                </a>
            <?php endif; ?>

            <div class="dock-separator-vertical mx-3"></div>

            <!-- Right Group -->
            <a href="#" class="nav-link" id="searchTrigger" title="Search">
                <i class="bi bi-search"></i>
            </a>

            <!-- Profile / Login Logic -->
            <?php if(isset($_SESSION['user_id'])): ?>
                <!-- Notifications (User Only) -->
                <?php if(isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'user'): ?>
                    <a href="#" class="nav-link" id="notificationBtn" title="Notifications">
                        <i class="bi bi-bell"></i>
                    </a>
                <?php endif; ?>

                <!-- Logged In Profile -->
                <a href="/python/public/profile" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/profile') !== false ? 'active-glow' : '' ?>" title="Profile">
                    <i class="bi bi-person-circle"></i>
                </a>
                
                <a href="/python/public/logout" class="nav-link text-danger" title="Logout">
                    <i class="bi bi-box-arrow-right"></i>
                </a>
            <?php else: ?>
                <!-- Guest -> Link to Login -->
                <a href="/python/public/login" class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/login') !== false ? 'active-glow' : '' ?>" title="Sign In">
                    <i class="bi bi-person"></i>
                </a>
            <?php endif; ?>
        </div>
        </div>
    </nav>
    
    <!-- Notification Dropdown Template -->
    <div id="notificationDropdown" class="notification-dropdown">
        <div class="dropdown-header">
            <span>Notifications</span>
            <!-- <a href="#" class="text-sm">Mark all read</a> -->
        </div>
        <div class="dropdown-items">
            <?php 
            // Fetch real notifications if function exists
            $notifications = function_exists('get_user_notifications') ? get_user_notifications(3) : [];
            
            if (!empty($notifications)): 
                foreach($notifications as $notif): 
                    // Determine icon based on type (though filtered to new_event mostly)
                    $icon = 'bi-info-circle';
                    $bgClass = 'bg-primary-soft';
                    if($notif['type'] == 'new_event') { $icon = 'bi-calendar-event'; $bgClass = 'bg-primary-soft'; }
                    elseif($notif['type'] == 'success') { $icon = 'bi-check-circle'; $bgClass = 'bg-success-soft'; }
                    elseif($notif['type'] == 'warning') { $icon = 'bi-exclamation-triangle'; $bgClass = 'bg-warning-soft'; }
                    
                    // Time formatting (rough approximation)
                    $timeAgo = 'Just now';
                    if(isset($notif['created_at'])) {
                        $time = strtotime($notif['created_at']);
                        $diff = time() - $time;
                        if($diff < 3600) $timeAgo = floor($diff/60) . 'm ago';
                        elseif($diff < 86400) $timeAgo = floor($diff/3600) . 'h ago';
                        else $timeAgo = floor($diff/86400) . 'd ago';
                    }
            ?>
            <div class="dropdown-item unread">
                <div class="icon-circle <?= $bgClass ?>"><i class="bi <?= $icon ?>"></i></div>
                <div class="text">
                    <p class="title"><?= htmlspecialchars($notif['message'] ?? 'New Notification') ?></p>
                    <p class="time"><?= $timeAgo ?></p>
                </div>
            </div>
            <?php endforeach; ?>
            <?php else: ?>
            <div class="dropdown-item">
                <div class="text w-100 text-center text-muted py-3">
                    <p class="title mb-0">No new notifications</p>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <div class="dropdown-footer">
            <!-- <a href="#">View All</a> -->
        </div>
    </div>

    <style>
    .notification-dropdown {
        position: fixed;
        top: 120px; /* Below header */
        right: 20%; /* Adjusted via JS usually, but fixed is okay for now */
        width: 320px;
        background: rgba(255, 255, 255, 0.70);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.5);
        border-radius: 20px;
        box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        z-index: 1045;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        overflow: hidden;
    }
    
    .notification-dropdown.active {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .dropdown-header {
        padding: 15px 20px;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 600;
        color: #1d1d1f;
    }

    .dropdown-items {
        max-height: 300px;
        overflow-y: auto;
    }

    .dropdown-item {
        padding: 15px 20px;
        display: flex;
        align-items: start;
        gap: 15px;
        border-bottom: 1px solid rgba(0,0,0,0.03);
        transition: background 0.2s;
        cursor: pointer;
    }

    .dropdown-item:hover {
        background: rgba(255,255,255,0.4);
    }

    .dropdown-item.unread {
        background: rgba(0, 113, 227, 0.05);
    }

    .icon-circle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
    }
    
    .bg-primary-soft { background: rgba(63, 65, 66, 0.1); color: #626a72ff; }
    .bg-success-soft { background: rgba(52, 199, 89, 0.1); color: #b9c5bcff; }
    .bg-warning-soft { background: rgba(255, 149, 0, 0.1); color: #746f6aff; }

    .dropdown-item .text .title {
        font-size: 13px;
        font-weight: 600;
        margin: 0 0 2px 0;
        color: #1d1d1f;
    }

    .dropdown-item .text .time {
        font-size: 11px;
        color: #86868b;
        margin: 0;
    }

    .dropdown-footer {
        padding: 12px;
        text-align: center;
        border-top: 1px solid rgba(0,0,0,0.05);
    }

    .dropdown-footer a {
        font-size: 12px;
        font-weight: 600;
        color: #0071e3;
        text-decoration: none;
    }
    
    .text-sm { font-size: 11px; text-decoration: none; color: #0071e3; }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const notifBtn = document.getElementById('notificationBtn');
        const notifDropdown = document.getElementById('notificationDropdown');
        
        if(notifBtn && notifDropdown) {
            // Position toggle
            const updatePosition = () => {
                const rect = notifBtn.getBoundingClientRect();
                notifDropdown.style.left = (rect.left - 140) + 'px'; // Center ish
                notifDropdown.style.top = (rect.bottom + 20) + 'px';
            };

            notifBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                updatePosition();
                notifDropdown.classList.toggle('active');
            });

            // Close on click outside
            document.addEventListener('click', (e) => {
                if(!notifDropdown.contains(e.target) && e.target !== notifBtn && !notifBtn.contains(e.target)) {
                    notifDropdown.classList.remove('active');
                }
            });

            window.addEventListener('resize', updatePosition);
        }
    });
    </script>

    <!-- Styles from Public Header -->
    <style>
    /* Header Override for Split Layout */
    .floating-glass-header {
        background: none !important;
        border: none !important;
        box-shadow: none !important;
        padding: 0 !important;
        /* Centering and Positioning */
        position: fixed;
        top: 30px; /* Slight top margin */
        left: 50%;
        transform: translateX(-50%);
        width: auto !important;
        max-width: 95% !important;
        z-index: 1040;
        pointer-events: none; /* Pass through clicks on wrapper */
        display: flex; /* Flex to center child */
        justify-content: center;
    }

    .glass-pill {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.5), rgba(255, 255, 255, 0.2)); /* Slightly more opaque for prominence */
        backdrop-filter: blur(25px);
        -webkit-backdrop-filter: blur(25px);
        border: 1px solid rgba(255, 255, 255, 0.5);
        border-radius: 60px; /* Rounder */
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15), inset 0 0 0 1px rgba(255, 255, 255, 0.3);
        display: flex;
        align-items: center;
        justify-content: center; /* Ensure contents are centered */
        height: 80px; /* BIGGER height */
        padding: 0 49px; /* WIDER padding */
        width: fit-content;
        pointer-events: auto; /* Catch clicks */
        min-width: 400px; /* Ensure minimum width */
    }

    .dock-separator-vertical {
        width: 2px; /* Thicker separator */
        height: 40px; /* Taller separator */
        background: rgba(0,0,0,0.1); /* Slightly less margin since gap handles spacing */
    }
    
    /* Ensure Nav Links Reset (fix Admin.css conflicts) */
    .floating-glass-header .nav-link {
        width: auto;
        height: auto;
        padding: 0 12px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        margin: 0;
    }
    .floating-glass-header .nav-link i {
        font-size: 1.5rem;
        color: #1d1d1f;
    }
    .floating-glass-header .nav-link:hover {
        background: rgba(0,0,0,0.05);
        transform: scale(1.1);
    }
    </style>

    <!-- Search Overlay -->
    <div id="searchOverlay" class="search-overlay">
        <div class="search-container">
            <div class="search-bar-wrapper">
                <i class="bi bi-search py-2"></i>
                <input type="text" id="searchInput" placeholder="Search events..." autocomplete="off">
                <button id="closeSearch" class="btn-close-custom"><i class="bi bi-x-circle-fill"></i></button>
            </div>
            <div class="quick-links" id="quickLinks">
                <p class="section-title">Quick Links</p>
                <ul>
                    <li><a href="/python/public/menu">Browse All Events</a></li>
                    <li><a href="/python/public/admin/events">Manage Events</a></li>
                    <li><a href="/python/public/admin/users">Manage Users</a></li>
                </ul>
            </div>
            <div id="searchResults" class="search-results d-none">
                <!-- Results injected here -->
            </div>
        </div>
    </div>

    <style>
    .search-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.30);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        z-index: 1050;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        display: flex;
        justify-content: center;
        padding-top: 100px;
    }

    .search-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .search-container {
        width: 100%;
        max-width: 680px;
        padding: 0 20px;
    }

    .search-bar-wrapper {
        display: flex;
        align-items: center;
        margin-bottom: 40px;
    }

    .search-bar-wrapper i.bi-search {
        font-size: 24px;
        color: #86868b;
        margin-right: 15px;
    }

    #searchInput {
        width: 100%;
        border: none;
        background: transparent;
        font-size: 24px;
        font-weight: 400;
        color: #1d1d1f;
        outline: none;
    }

    #searchInput::placeholder {
        color: #86868b;
    }

    .btn-close-custom {
        background: none;
        border: none;
        font-size: 24px;
        color: #86868b;
        cursor: pointer;
        padding: 0;
        margin-left: 15px;
        transition: color 0.2s;
    }

    .btn-close-custom:hover {
        color: #1d1d1f;
    }

    .section-title {
        font-size: 12px;
        font-weight: 600;
        color: #86868b;
        text-transform: uppercase;
        margin-bottom: 10px;
        letter-spacing: 0.5px;
    }

    .quick-links ul {
        list-style: none;
        padding: 0;
    }

    .quick-links li {
        margin-bottom: 10px;
    }

    .quick-links a {
        text-decoration: none;
        color: #1d1d1f;
        font-size: 14px;
        font-weight: 500;
        transition: color 0.2s;
    }

    .quick-links a:hover {
        color: #0071e3;
        text-decoration: none;
    }

    .search-result-item {
        display: flex;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #e5e5e5;
        text-decoration: none;
        color: #1d1d1f;
        transition: background 0.2s;
    }

    .search-result-item:hover {
        background: rgba(0,0,0,0.02);
    }

    .search-result-item img {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 6px;
        margin-right: 15px;
    }

    .search-result-content h6 {
        margin: 0;
        font-size: 14px;
        font-weight: 600;
    }

    .search-result-content p {
        margin: 0;
        font-size: 12px;
        color: #86868b;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchTrigger = document.getElementById('searchTrigger');
        const overlay = document.getElementById('searchOverlay');
        const closeBtn = document.getElementById('closeSearch');
        const input = document.getElementById('searchInput');
        const resultsContainer = document.getElementById('searchResults');
        const quickLinks = document.getElementById('quickLinks');
        let dobounceTimer;

        // Open Overlay
        if(searchTrigger && overlay) {
            searchTrigger.addEventListener('click', function(e) {
                e.preventDefault();
                overlay.classList.add('active');
                setTimeout(() => input.focus(), 100);
                document.body.style.overflow = 'hidden';
            });
        }

        // Close Overlay
        function closeOverlay() {
            overlay.classList.remove('active');
            document.body.style.overflow = '';
            input.value = '';
            resultsContainer.innerHTML = '';
            resultsContainer.classList.add('d-none');
            quickLinks.classList.remove('d-none');
        }

        if(closeBtn) closeBtn.addEventListener('click', closeOverlay);

        // Close on Escape or click outside content
        document.addEventListener('keydown', function(e) {
            if(e.key === 'Escape' && overlay && overlay.classList.contains('active')) {
                closeOverlay();
            }
        });

        // Live Search (Admin - could be customized to search users/events)
        if(input) {
            input.addEventListener('input', function() {
                clearTimeout(dobounceTimer);
                const query = this.value.trim();

                if(query.length === 0) {
                    resultsContainer.innerHTML = '';
                    resultsContainer.classList.add('d-none');
                    quickLinks.classList.remove('d-none');
                    return;
                }

                quickLinks.classList.add('d-none');
                resultsContainer.classList.remove('d-none');
                resultsContainer.innerHTML = '<div class="text-center py-3 text-muted">Searching...</div>';

                dobounceTimer = setTimeout(() => {
                    // Using the same search endpoint for now, or could create an admin search
                    fetch('/python/public/menu/search?q=' + encodeURIComponent(query))
                        .then(res => res.json())
                        .then(data => {
                            resultsContainer.innerHTML = '';
                            if(data.length > 0) {
                                data.forEach(item => {
                                    const html = `
                                        <a href="/python/public/menu/event/${item.id}" class="search-result-item">
                                            <img src="${item.image_cover}" alt="${item.title}">
                                            <div class="search-result-content">
                                                <h6>${item.title}</h6>
                                                <p>${item.description}</p>
                                            </div>
                                        </a>
                                    `;
                                    resultsContainer.insertAdjacentHTML('beforeend', html);
                                });
                            } else {
                                resultsContainer.innerHTML = '<div class="text-center py-3 text-muted">No events found.</div>';
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            resultsContainer.innerHTML = '<div class="text-center py-3 text-danger">Error searching.</div>';
                        });
                }, 300);
            });
        }
    });
    </script>

    <!-- Main Content Wrapper was here - Greeting moved to greeting.php -->
