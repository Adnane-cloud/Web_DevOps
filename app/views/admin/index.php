<?php 
require_once __DIR__ . '/layout/header.php';
require_once __DIR__ . '/layout/greeting.php';
?>

<link rel="stylesheet" href="/python/public/css/dashboard.css">
<!-- Stats Overview -->
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
<style>
    /* Ported Menu Background */
   
</style>
<div class="container-fluid px-5">
    <div class="row g-4 mb-5">
        <!-- Total Events -->
        <div class="col-md-3">
            <div class="pastel-glass-card stats-card">
                <i class="bi bi-calendar-event card-icon" style="color: #af52de;"></i>
                <span class="card-label">Total Events</span>
                <div class="d-flex align-items-center gap-2 my-2">
                    <h2><?= $data['stats']['total_events'] ?></h2>
                    <span class="trend-badge positive">+12% <i class="bi bi-arrow-up-short"></i></span>
                </div>
                <span class="card-footer-text">In Database</span>
            </div>
        </div>
        
        <!-- Total Users -->
        <div class="col-md-3">
            <div class="pastel-glass-card stats-card">
                <i class="bi bi-people-fill card-icon" style="color: #ff9f0a;"></i>
                <span class="card-label">Total Users</span>
                 <div class="d-flex align-items-center gap-2 my-2">
                    <h2><?= $data['stats']['active_users'] ?></h2>
                    <span class="trend-badge positive">+5% <i class="bi bi-arrow-up-short"></i></span>
                </div>
                <span class="card-footer-text">Registered Accounts</span>
            </div>
        </div>
        
        <!-- Active Events -->
        <div class="col-md-3">
            <div class="pastel-glass-card stats-card">
                <i class="bi bi-activity card-icon" style="color: #32ade6;"></i>
                <span class="card-label">Active Events</span>
                 <div class="d-flex align-items-center gap-2 my-2">
                    <h2><?= $data['stats']['active_events'] ?></h2>
                    <span class="trend-badge neutral">0% <i class="bi bi-dash"></i></span>
                </div>
                <span class="card-footer-text">Open for Registration</span>
            </div>
        </div>
        
        <!-- Total Inscriptions -->
        <div class="col-md-3">
            <div class="pastel-glass-card stats-card">
                <i class="bi bi-ticket-perforated-fill card-icon" style="color: #ff2d55;"></i>
                <span class="card-label">Inscriptions</span>
                 <div class="d-flex align-items-center gap-2 my-2">
                    <h2><?= $data['stats']['total_inscriptions'] ?></h2>
                    <span class="trend-badge positive">+28% <i class="bi bi-arrow-up-short"></i></span>
                </div>
                <span class="card-footer-text">All Time</span>
            </div>
        </div>
    </div>

    <!-- Analytics Charts -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="pastel-glass-card h-100">
                <div class="d-flex flex-column align-items-center mb-4 w-100">
                    <h3 class="h5 fw-bold mb-1">Top Events</h3>
                    <span class="text-secondary small fw-bold text-uppercase" style="font-size: 0.65rem; letter-spacing: 1px;">By Signups</span>
                </div>
                <div class="glass-podium-container flex-grow-1 d-flex align-items-end justify-content-center gap-3 pb-3 w-100">
                    
                    <?php 
                        // Prepare Data for 1st, 2nd, 3rd positions
                        // Logic: Array index 0 = 1st, 1 = 2nd, 2 = 3rd
                        $first  = $data['top_events'][0] ?? ['titre' => 'N/A', 'inscription_count' => 0];
                        $second = $data['top_events'][1] ?? ['titre' => 'N/A', 'inscription_count' => 0];
                        $third  = $data['top_events'][2] ?? ['titre' => 'N/A', 'inscription_count' => 0];
                    ?>

                    <!-- 2nd Place (Left) -->
                    <div class="podium-bar-wrapper">
                        <div class="glass-bar" style="height: 120px; background: linear-gradient(to top, rgba(52, 199, 89, 0.4), rgba(52, 199, 89, 0.1));">
                            <span class="podium-rank">2</span>
                        </div>
                        <span class="podium-label text-truncate" title="<?= htmlspecialchars($second['titre']) ?>"><?= htmlspecialchars($second['titre']) ?></span>
                        <span class="podium-score"><?= $second['inscription_count'] ?></span>
                    </div>
                    
                    <!-- 1st Place (Center - Tallest) -->
                    <div class="podium-bar-wrapper">
                        <div class="glass-bar" style="height: 160px; background: linear-gradient(to top, rgba(0, 113, 227, 0.4), rgba(0, 113, 227, 0.1)); box-shadow: 0 0 20px rgba(0, 113, 227, 0.3);">
                            <i class="bi bi-trophy-fill text-warning mb-2"></i>
                            <span class="podium-rank">1</span>
                        </div>
                        <span class="podium-label text-truncate text-center fw-bold text-primary" title="<?= htmlspecialchars($first['titre']) ?>"><?= htmlspecialchars($first['titre']) ?></span>
                        <span class="podium-score fw-bold text-dark"><?= $first['inscription_count'] ?></span>
                    </div>
                    
                    <!-- 3rd Place (Right) -->
                    <div class="podium-bar-wrapper">
                        <div class="glass-bar" style="height: 90px; background: linear-gradient(to top, rgba(255, 159, 10, 0.4), rgba(255, 159, 10, 0.1));">
                            <span class="podium-rank">3</span>
                        </div>
                        <span class="podium-label text-truncate" title="<?= htmlspecialchars($third['titre']) ?>"><?= htmlspecialchars($third['titre']) ?></span>
                        <span class="podium-score"><?= $third['inscription_count'] ?></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="pastel-glass-card h-100">
                <div class="d-flex flex-column align-items-center mb-4 w-100">
                    <h3 class="h5 fw-bold mb-1">Live Activity</h3>
                    <span class="text-secondary small fw-bold text-uppercase" style="font-size: 0.65rem; letter-spacing: 1px;">Recent Registrations</span>
                </div>
                
                <div class="activity-feed w-100 px-2" style="max-height: 300px; overflow-y: auto;">
                    <?php if (empty($data['recent_activity'])): ?>
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-bell-slash mb-2 fs-4 opacity-50"></i>
                            <p class="small mb-0">No recent activity</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($data['recent_activity'] as $log): ?>
                        <div class="d-flex align-items-center mb-3 p-3 rounded-4" style="background: rgba(255, 255, 255, 0.4); border: 1px solid rgba(255, 255, 255, 0.5);">
                            <div class="flex-shrink-0 me-3">
                                <?php if (!empty($log['user_image']) && file_exists('public/' . $log['user_image'])): ?>
                                    <img src="/python/public/<?= htmlspecialchars($log['user_image']) ?>" class="rounded-circle shadow-sm" width="46" height="46" style="object-fit: cover;">
                                <?php else: ?>
                                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center text-primary fw-bold" style="width: 46px; height: 46px;">
                                        <?= strtoupper(substr($log['user_name'], 0, 1)) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="flex-grow-1">
                                <p class="mb-0 text-dark small" style="line-height: 1.4;">
                                    <span class="fw-bold"><?= htmlspecialchars($log['user_name']) ?></span> 
                                    <span class="text-secondary">registered for</span> 
                                    <span class="fw-bold text-primary"><?= htmlspecialchars($log['event_title']) ?></span>
                                </p>
                                <small class="text-muted" style="font-size: 0.7rem;">
                                    <i class="bi bi-clock me-1"></i>
                                    <?= date('M d, H:i', strtotime($log['date_inscription'])) ?>
                                </small>
                            </div>
                            <div class="ms-2">
                                <span class="badge rounded-pill bg-white text-success shadow-sm border border-white">
                                    <i class="bi bi-check-lg"></i>
                                </span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity & Charts Row -->
    <div class="row g-4 mb-4">
        <!-- Recent Events Table -->
        <div class="col-md-8">
            <div class="pastel-glass-card h-auto text-start card-padding-remove" style="padding-top: 0 !important; padding-left: 0; padding-right: 0; align-items: stretch !important;">
                <div class="d-flex justify-content-between align-items-center mb-4 px-4 pt-4 w-100">
                    <h3 class="h5 fw-bold mb-0">Recent Events</h3>
                    <a href="/python/public/admin/events" class="btn btn-sm btn-glass-secondary rounded-pill px-3">View All</a>
                </div>
                 <div class="table-responsive w-100">
                    <table class="table-apple mb-0 w-100">
                        <thead>
                            <tr>
                                <th class="ps-4">Event</th>
                                <th>Date</th>
                                <th>Cap.</th>
                                <th class="pe-4 text-end">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data['recent_events'] as $evt): ?>
                            <tr>
                                <td class="ps-4 fw-medium"><?= htmlspecialchars($evt['titre']) ?></td>
                                <td class="text-secondary"><?= date('M d', strtotime($evt['date_evenement'])) ?></td>
                                <td><span class="badge bg-light text-dark border"><?= $evt['nb_max_participants'] ?></span></td>
                                <td class="pe-4 text-end">
                                    <?php if($evt['est_cloture']): ?>
                                        <span class="status-badge status-inactive">Closed</span>
                                    <?php else: ?>
                                        <span class="status-badge status-active">Active</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Platform Insights (Database Connected) -->
            <div class="pastel-glass-card mt-4" style="padding: 30px !important; max-height: 330px !important; justify-content: flex-start !important; align-items: stretch !important; text-align: left !important;">
                <h3 class="h5 fw-bold mb-4 text-start">Platform Insights</h3>
                
                <?php
                    // Real Data Calculations
                    $totalEvt = max(1, $data['stats']['total_events']);
                    $activeEvt = $data['stats']['active_events'];
                    $activationRate = round(($activeEvt / $totalEvt) * 100);
                    
                    $totalUsers = max(1, $data['stats']['active_users']);
                    $totalIns = $data['stats']['total_inscriptions'];
                    $avgEngagement = round($totalIns / $totalUsers, 1);
                ?>

                <!-- Status Indicators -->
                <div class="d-flex align-items-center gap-4 mb-4">
                    <div class="d-flex align-items-center gap-2">
                        <span class="position-relative d-flex p-1">
                            <span class="position-absolute top-50 start-50 translate-middle p-1 bg-success border border-light rounded-circle"></span>
                        </span>
                        <span class="small fw-bold text-success"><?= $activeEvt ?> Events Live</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-person-check text-primary"></i>
                        <span class="small fw-semibold text-secondary"><?= $avgEngagement ?> Events / User</span>
                    </div>
                </div>

                <!-- Event Activation Rate -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="small fw-bold text-dark">Activation Rate</span>
                        <span class="small fw-bold text-secondary"><?= $activationRate ?>%</span>
                    </div>
                    <div class="progress" style="height: 6px; background: rgba(0,0,0,0.05);">
                        <div class="progress-bar rounded-pill" role="progressbar" style="width: <?= $activationRate ?>%; background: linear-gradient(90deg, #0071e3, #40a0ff);" aria-valuenow="<?= $activationRate ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>

                <!-- Inscription Density (Visual Only) -->
                <div class="mb-1">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="small fw-bold text-dark">User Engagement</span>
                        <span class="small fw-bold text-secondary">High</span>
                    </div>
                    <div class="progress" style="height: 6px; background: rgba(0,0,0,0.05);">
                        <div class="progress-bar rounded-pill" role="progressbar" style="width: 85%; background: linear-gradient(90deg, #34c759, #30d158);" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Category Distribution Chart & Quick Actions -->
        <div class="col-md-4">
            <!-- Chart -->
            <div class="pastel-glass-card mb-4 d-flex flex-column" style="height: 380px; padding: 20px !important;">
                <h3 class="h5 fw-bold mb-4">Events by Category</h3>
                <div class="flex-grow-1 position-relative d-flex align-items-center justify-content-center" style="width: 100%;">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="pastel-glass-card" style="justify-content: flex-start !important; padding: 20px !important; height: auto !important;">
                <h3 class="h5 fw-bold mb-3">Quick Actions</h3>
                <div class="d-grid gap-3 w-100">
                    <a href="/python/public/admin/events" class="btn btn-glass-primary w-100 fw-medium fs-6 rounded-3 d-flex align-items-center justify-content-center py-2">
                        <i class="bi bi-plus-lg me-2"></i> Create New Event
                    </a>
                    <a href="/python/public/admin/export/events" class="btn btn-glass-secondary w-100 fw-medium fs-6 rounded-3 d-flex align-items-center justify-content-center py-2">
                        <i class="bi bi-file-earmark-text me-2"></i> Export Report
                    </a>
                    <a href="/python/public/profile" class="btn btn-glass-secondary w-100 fw-medium fs-6 rounded-3 d-flex align-items-center justify-content-center py-2">
                        <i class="bi bi-gear me-2"></i> Settings
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require '../app/views/admin/layout/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Category Distribution Chart (Doughnut)
    const catCanvas = document.getElementById('categoryChart');
    if (catCanvas) {
        const catDataRaw = <?= json_encode($data['charts']['categories'] ?? []) ?>;
        const labels = catDataRaw.map(item => item.nom);
        const counts = catDataRaw.map(item => item.count);

        const ctxCat = catCanvas.getContext('2d');
        
        new Chart(ctxCat, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: counts,
                    backgroundColor: [
                        'rgba(0, 113, 227, 0.6)',
                        'rgba(52, 199, 89, 0.6)',
                        'rgba(255, 149, 0, 0.6)',
                        'rgba(175, 82, 222, 0.6)',
                        'rgba(255, 59, 48, 0.6)'
                    ],
                    borderColor: 'rgba(255, 255, 255, 0.5)',
                    borderWidth: 2,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            font: { family: '-apple-system', size: 11 },
                            color: '#666',
                            padding: 20
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(255, 255, 255, 0.9)',
                        titleColor: '#000',
                        bodyColor: '#666',
                        borderColor: 'rgba(0,0,0,0.1)',
                        borderWidth: 1,
                        padding: 12,
                        cornerRadius: 12,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                return ' ' + context.label + ': ' + context.parsed + ' Events';
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
