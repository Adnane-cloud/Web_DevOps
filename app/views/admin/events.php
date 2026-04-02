<?php
require_once __DIR__ . '/layout/header.php';
require_once __DIR__ . '/layout/greeting.php';
?>

<div class="container-fluid px-5">
    <div class="pastel-glass-card text-start card-padding-remove" style="padding-left: 0; padding-right: 0; padding-bottom: 0;">
        <div class="d-flex justify-content-between align-items-center mb-4 px-4 pt-4 w-100">
            <h2 class="h5 fw-bold mb-0">All Events</h2>
            <button class="btn btn-glass-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addEventModal">
                <i class="bi bi-plus-lg me-2"></i> Add Event
            </button>
        </div>

        <div class="table-responsive w-100">
            <table class="table-apple mb-0">
                <thead class="bg-transparent">
                    <tr style="border-bottom: 1px solid rgba(0,0,0,0.1);">
                        <th class="ps-4">ID</th>
                        <th>Status</th>
                        <th>Title</th>
                        <th>Date</th>
                        <th>Attendees</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['events'] as $event): ?>
                    <tr style="border-bottom: 1px solid rgba(0,0,0,0.05);">
                        <td class="text-secondary ps-4">#<?= $event['id'] ?></td>
                        <td>
                            <?php if($event['est_cloture'] == 0): ?>
                                <span class="status-badge status-active">Active</span>
                            <?php else: ?>
                                <span class="status-badge status-pending" style="color: #ff3b30; background: rgba(255, 59, 48, 0.1);">Closed</span>
                            <?php endif; ?>
                        </td>
                        <td class="fw-medium"><?= htmlspecialchars($event['titre']) ?></td>
                        <td><?= date('M d, Y', strtotime($event['date_evenement'])) ?></td>
                        <td><?= $event['nb_max_participants'] ?> Max</td>
                        <td class="text-end pe-4">
                            <a href="/python/public/admin/events/edit?id=<?= $event['id'] ?>" class="btn-table-action me-1"><i class="bi bi-pencil"></i></a>
                            <form action="/python/public/admin/events/delete" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this event?');">
                                <input type="hidden" name="id" value="<?= $event['id'] ?>">
                                <button type="submit" class="btn-table-action text-danger border-0 bg-transparent"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Event Modal -->
<div class="modal fade" id="addEventModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-modal border-0 shadow-lg">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <h5 class="modal-title fw-bold text-dark">New Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 pt-3 pb-4">
                <form method="POST" action="/python/public/admin/events/add" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Event Title</label>
                        <input type="text" name="title" class="form-control form-glass" placeholder="e.g. WWDC 2025" required>
                    </div>
                    
                    <div class="mb-3">
                         <label class="form-label small fw-bold text-secondary">Description</label>
                         <textarea name="description" class="form-control form-glass" rows="3" required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-secondary">Date & Time</label>
                            <input type="datetime-local" name="date" class="form-control form-glass" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-secondary">Category</label>
                            <select name="category_id" class="form-select form-glass" required>
                                <?php foreach($data['categories'] as $cat): ?>
                                    <option value="<?= $cat['id'] ?>"><?= $cat['nom'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                             <label class="form-label small fw-bold text-secondary">Location</label>
                             <input type="text" name="location" class="form-control form-glass" required>
                        </div>
                         <div class="col-md-6 mb-3">
                             <label class="form-label small fw-bold text-secondary">Max Participants</label>
                             <input type="number" name="max_participants" class="form-control form-glass" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-secondary">Cover Image</label>
                        <input type="file" name="image" class="form-control form-glass">
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-glass-primary py-2 fw-bold">Create Event</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
/* Glass Modal Styles */
.glass-modal {
    background: rgba(255, 255, 255, 0.75);
    backdrop-filter: blur(25px);
    -webkit-backdrop-filter: blur(25px);
    border: 1px solid rgba(255, 255, 255, 0.5);
    border-radius: 24px !important;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
}

.form-glass {
    background: rgba(255, 255, 255, 0.5) !important;
    border: 1px solid rgba(255, 255, 255, 0.3) !important;
    border-radius: 12px !important;
    color: #1d1d1f;
    transition: all 0.2s ease;
}

.form-glass:focus {
    background: rgba(255, 255, 255, 0.8) !important;
    border-color: #0071e3 !important;
    box-shadow: 0 0 0 4px rgba(0, 113, 227, 0.1);
}

.btn-glass-primary {
    background: rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    color: #1d1d1f;
    border: 1px solid rgba(0, 0, 0, 0.1);
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    transition: transform 0.2s, box-shadow 0.2s;
}

.btn-glass-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    color: #000;
    background: rgba(0, 0, 0, 0.2);
}
</style>

<?php require '../app/views/admin/layout/footer.php'; ?>
