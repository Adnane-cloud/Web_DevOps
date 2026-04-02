<?php require '../app/views/admin/layout/header.php'; ?>

<!-- FullCalendar CSS -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<link rel="stylesheet" href="/python/public/css/calender.css">

<!-- Floating Circles Animation Background -->
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

<div class="calendar-container" style="padding-top: 140px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-1">Events Calendar</h1>
            <p class="text-dark mb-0">Overview of all upcoming activities.</p>
        </div>
        <?php if(isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
            <a href="/python/public/admin/events" class="btn btn-dark rounded-pill px-4">Manage Events</a>
        <?php endif; ?>
    </div>

    <!-- Category Filter Pills -->
    <div class="category-filter-pills">
        <button class="cat-pill active" data-category="all">All Categories</button>
        <?php 
        $catColors = ['#0071e3', '#34c759', '#ff9f0a', '#ff3b30', '#af52de', '#5856d6'];
        $i = 0;
        if(isset($data['categories']) && is_array($data['categories'])):
            foreach($data['categories'] as $cat): 
                $color = $catColors[$i % count($catColors)];
            ?>
                <button class="cat-pill" data-category="<?= $cat['id'] ?>" data-color="<?= $color ?>">
                    <span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: <?= $color ?>; margin-right: 6px;"></span>
                    <?= htmlspecialchars($cat['nom']) ?>
                </button>
            <?php $i++; endforeach; 
        endif;
        ?>
    </div>

    <div class="row">
        <!-- Main Calendar Column -->
        <div class="col-lg-9 mb-4">
            <div class="calendar-card">
                <div id='calendar'></div>
            </div>
        </div>

        <!-- Sidebar Column -->
        <div class="col-lg-3">
            <!-- Tools Widget -->
            <div class="glass-sidebar-card">
                <h5 class="fw-bold mb-3" style="font-size: 16px;">Tools</h5>
                
                <div class="mb-3">
                    <label class="small text-muted mb-2 d-block fw-bold">JUMP TO DATE</label>
                    <input type="month" id="dateJumper" class="form-control" 
                           style="background: rgba(255,255,255,0.4); border:none; border-radius: 12px; height: 45px;">
                </div>

                <button class="rose-glass-btn mb-2" id="syncBtn">
                    <i class="bi bi-arrow-repeat"></i> Sync to Calendar
                </button>
            </div>

            <!-- Upcoming Events Widget -->
            <div class="glass-sidebar-card">
                <h5 class="fw-bold mb-3" style="font-size: 16px;">Up Next</h5>
                <div id="upcomingEventsList">
                    <div class="text-center py-3 text-muted small">Loading...</div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require '../app/views/admin/layout/footer.php'; ?>

<!-- Glass Event Modal -->
<div id="eventGlassModal" class="glass-modal-overlay">
    <div class="glass-modal-content position-relative">
        <button class="modal-close-btn" onclick="closeEventModal()">
            <i class="bi bi-x"></i>
        </button>
        
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-3" style="width: 60px; height: 60px;">
                <i class="bi bi-calendar-event fs-3 text-secondary"></i>
            </div>
            <h3 class="fw-bold mb-1" id="modalTitle">Event Title</h3>
            <p class="text-muted" id="modalDate">Date & Time</p>
        </div>

        <div class="bg-white bg-opacity-50 p-3 rounded-4 mb-4">
            <p class="mb-0 text-secondary" id="modalDesc">Description goes here...</p>
        </div>

        <a href="#" id="modalLink" class="rose-glass-btn">
            View Full Details <i class="bi bi-arrow-right"></i>
        </a>
    </div>

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src='/python/public/js/Calender.js'></script>


