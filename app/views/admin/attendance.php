<?php
require_once __DIR__ . '/layout/header.php';
require_once __DIR__ . '/layout/greeting.php';
?>

<div class="container-fluid px-5">
    <!-- Stats Row -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="pastel-glass-card p-3 d-flex align-items-center justify-content-between text-start">
                <div>
                    <span class="text-secondary small fw-bold text-uppercase d-block mb-1">Total Check-ins</span>
                    <h3 class="fw-bold mb-0 text-dark"><?= $data['stats']['total'] ?></h3>
                </div>
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: rgba(0, 113, 227, 0.1); color: #0071e3;">
                    <i class="bi bi-people-fill fs-5"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="pastel-glass-card p-3 d-flex align-items-center justify-content-between text-start">
                <div>
                    <span class="text-secondary small fw-bold text-uppercase d-block mb-1">Today's Activity</span>
                    <h3 class="fw-bold mb-0 text-dark"><?= $data['stats']['today'] ?></h3>
                </div>
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: rgba(52, 199, 89, 0.1); color: #34c759;">
                    <i class="bi bi-clock-history fs-5"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="pastel-glass-card p-3 d-flex align-items-center justify-content-between text-start" title="<?= htmlspecialchars($data['stats']['busiest']['titre'] ?? 'No Data') ?>">
                <div style="min-width: 0;"> <!-- Fix for truncate -->
                    <span class="text-secondary small fw-bold text-uppercase d-block mb-1">Top Event</span>
                    <h3 class="fw-bold mb-0 text-dark text-truncate fs-4"><?= htmlspecialchars($data['stats']['busiest']['titre'] ?? '-') ?></h3>
                </div>
                <div class="rounded-circle d-flex align-items-center justify-content-center ms-3 flex-shrink-0" style="width: 48px; height: 48px; background: rgba(255, 159, 10, 0.1); color: #ff9f0a;">
                    <i class="bi bi-trophy-fill fs-5"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="pastel-glass-card text-start card-padding-remove" style="padding: 0;">
        <div class="d-flex justify-content-between align-items-center mb-4 px-4 pt-4 w-100">
            <h2 class="h5 fw-bold mb-0">Attendance Lists</h2>
            
            <form method="GET" action="/python/public/admin/attendance" class="d-flex gap-2">
                <!-- Event Filter -->
                <select name="event_id" class="form-select rounded-pill border-0 ps-3" 
                        style="background: rgba(255, 255, 255, 0.5); backdrop-filter: blur(5px); width: 180px; font-size: 0.9rem;"
                        onchange="this.form.submit()">
                    <option value="">All Events</option>
                    <?php foreach($data['events_list'] as $evt): ?>
                        <option value="<?= $evt['id'] ?>" <?= ($data['selected_event'] == $evt['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($evt['titre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <!-- Search -->
                <div class="input-group" style="width: 250px;">
                    <input type="text" name="search" class="form-control rounded-pill border-0 ps-3" 
                           placeholder="Search name..." value="<?= htmlspecialchars($data['search'] ?? '') ?>"
                           style="background: rgba(255, 255, 255, 0.5); backdrop-filter: blur(5px);">
                    <button class="btn btn-primary rounded-pill ms-2" type="submit" style="background-color: #0071e3; border: none;">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
                
                <!-- Export -->
                <a href="/python/public/admin/attendance/export?event_id=<?= $data['selected_event'] ?>" class="btn btn-glass-secondary rounded-pill d-flex align-items-center justify-content-center" title="Export CSV">
                    <i class="bi bi-download"></i>
                </a>
            </form>
        </div>

        <div class="table-responsive w-100">
            <table class="table-apple w-100 mb-0">
                <thead>
                    <tr style="border-bottom: 1px solid rgba(0,0,0,0.1);">
                        <th class="ps-4">User</th>
                        <th>Event</th>
                        <th>Check-in Time</th>
                        <th class="text-end pe-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($data['attendees'])): ?>
                        <tr><td colspan="4" class="text-center py-5 text-muted">No attendance records found.</td></tr>
                    <?php else: ?>
                        <?php foreach($data['attendees'] as $attendee): ?>
                        <tr style="border-bottom: 1px solid rgba(0,0,0,0.05);">
                            <td class="fw-medium ps-4">
                                <div class="d-flex align-items-center">
                                    <span class="d-flex align-items-center justify-content-center rounded-circle me-3 bg-light text-primary fw-bold" style="width: 36px; height: 36px; font-size: 0.8rem;">
                                        <?= strtoupper(substr($attendee['name'], 0, 1)) ?>
                                    </span>
                                    <div>
                                        <?= htmlspecialchars($attendee['name']) ?>
                                        <div class="small text-muted" style="font-size: 0.75rem;">ID: #<?= $attendee['user_id'] ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($attendee['event']) ?></td>
                            <td>
                                <div class="d-flex align-items-center text-secondary">
                                    <i class="bi bi-clock me-2"></i>
                                    <?= date('M d, H:i', strtotime($attendee['check_in'])) ?>
                                </div>
                            </td>
                            <td class="text-end pe-4">
                                <span class="status-badge status-active">
                                    <i class="bi bi-check-circle-fill me-1"></i> Checked In
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</div>

<!-- User Profile Modal -->
<div class="modal fade" id="userProfileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 24px;">
            <div class="modal-body p-0">
                <!-- Header with Avatar -->
                <div class="position-relative p-4 text-center pb-0">
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-4" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="mx-auto mb-3 shadow-sm rounded-circle" id="modalAvatar" style="width: 100px; height: 100px; background-color: #f5f5f7; background-size: cover;"></div>
                    <h4 class="fw-bold mb-1" id="modalName">User Name</h4>
                    <p class="text-secondary small" id="modalEmail">email@example.com</p>
                </div>
                
                <!-- Stats Grid -->
                <div class="p-4 pt-2">
                    <div class="row g-3 mt-2 text-center">
                        <div class="col-6">
                            <div class="p-3 bg-light rounded-4">
                                <h3 class="fw-bold text-primary mb-0" id="modalTotalInscriptions">0</h3>
                                <span class="text-secondary small fw-bold text-uppercase" style="font-size: 10px;">Enrolled</span>
                            </div>
                        </div>
                        <div class="col-6">
                             <div class="p-3 bg-light rounded-4">
                                <h5 class="fw-bold text-dark mb-0 fs-6" id="modalJoined" style="line-height: 24px;">-</h5>
                                <span class="text-secondary small fw-bold text-uppercase" style="font-size: 10px;">Joined</span>
                            </div>
                        </div>
                    </div>
                </div>

                 <!-- Last Activity -->
                <div class="p-4 pt-0">
                    <h6 class="text-secondary small fw-bold text-uppercase mb-3">Recent Activity</h6>
                    <div class="d-flex align-items-center gap-3">
                         <div class="bg-primary bg-opacity-10 p-2 rounded-circle text-primary">
                            <i class="bi bi-calendar-event-fill"></i>
                        </div>
                        <div>
                            <p class="mb-0 fw-medium small text-dark">Last Event Attended</p>
                            <p class="mb-0 text-secondary small" id="modalLastEvent">None</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modalElement = document.getElementById('userProfileModal');
    const modal = new bootstrap.Modal(modalElement);
    
    // Elements to update
    const avatarEl = document.getElementById('modalAvatar');
    const nameEl = document.getElementById('modalName');
    const emailEl = document.getElementById('modalEmail');
    const inscriptionsEl = document.getElementById('modalTotalInscriptions');
    const joinedEl = document.getElementById('modalJoined');
    const lastEventEl = document.getElementById('modalLastEvent');

    window.openUserProfile = function(userId) {
        // Show loading state/reset
        nameEl.textContent = 'Loading...';
        
        // Fetch Data
        fetch('/python/public/admin/user_details?id=' + userId)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert('Error: ' + data.error);
                    return;
                }
                
                // Populate Modal
                nameEl.textContent = data.name;
                emailEl.textContent = data.email;
                inscriptionsEl.textContent = data.stats.total_inscriptions;
                joinedEl.textContent = data.joined_date;
                lastEventEl.textContent = data.stats.last_event;
                
                // Avatar
                const avatarUrl = `https://ui-avatars.com/api/?name=${encodeURIComponent(data.name)}&size=200&background=random`;
                avatarEl.style.backgroundImage = `url('${avatarUrl}')`;

                modal.show();
            })
            .catch(err => {
                console.error('Failed to fetch user details', err);
                nameEl.textContent = 'Error loading data';
            });
    };
});
</script>

<?php require '../app/views/admin/layout/footer.php'; ?>
