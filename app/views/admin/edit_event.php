<?php
require_once __DIR__ . '/layout/header.php';
?>

<!-- Background Animation -->
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

<div class="container py-5" style="margin-top: 80px;">
    <div class="glass-card mx-auto" style="max-width: 800px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4 fw-bold mb-0 text-dark"><i class="bi bi-pencil-square me-2 text-primary"></i>Edit Event</h2>
            <a href="/python/public/admin/events" class="btn btn-glass-secondary rounded-pill px-4">
                Cancel
            </a>
        </div>

        <form method="POST" action="/python/public/admin/events/update" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $data['event']['id'] ?>">

            <div class="mb-4">
                <label class="form-label small fw-bold text-secondary text-uppercase ms-1">Event Title</label>
                <input type="text" name="title" class="form-control form-glass" value="<?= htmlspecialchars($data['event']['titre']) ?>" required>
            </div>
            
            <div class="mb-4">
                    <label class="form-label small fw-bold text-secondary text-uppercase ms-1">Description</label>
                    <textarea name="description" class="form-control form-glass" rows="4" required><?= htmlspecialchars($data['event']['description']) ?></textarea>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-secondary text-uppercase ms-1">Date & Time</label>
                    <input type="datetime-local" name="date" class="form-control form-glass" value="<?= date('Y-m-d\TH:i', strtotime($data['event']['date_evenement'])) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-secondary text-uppercase ms-1">Category</label>
                    <select name="category_id" class="form-select form-glass" required>
                        <?php foreach($data['categories'] as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= ($cat['id'] == $data['event']['categorie_id']) ? 'selected' : '' ?>>
                                <?= $cat['nom'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                        <label class="form-label small fw-bold text-secondary text-uppercase ms-1">Location</label>
                        <input type="text" name="location" class="form-control form-glass" value="<?= htmlspecialchars($data['event']['lieu']) ?>" required>
                </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-secondary text-uppercase ms-1">Max Participants</label>
                        <input type="number" name="max_participants" class="form-control form-glass" value="<?= $data['event']['nb_max_participants'] ?>" required>
                </div>
            </div>

            <div class="mb-5">
                <label class="form-label small fw-bold text-secondary text-uppercase ms-1">Cover Image</label>
                <div class="p-3 rounded-3" style="background: rgba(255,255,255,0.4); border: 1px dashed rgba(0,0,0,0.1);">
                    <input type="file" name="image" class="form-control form-glass mb-3">
                    <?php if(!empty($data['event']['image_cover'])): ?>
                        <div class="d-flex align-items-center bg-white rounded-3 p-2 shadow-sm" style="width: fit-content;">
                            <img src="/python/public/<?= $data['event']['image_cover'] ?>" alt="Cover" class="rounded-2" style="height: 60px; width: 60px; object-fit: cover;">
                            <span class="ms-3 small text-secondary">Current Image</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-glass-primary py-3 fw-bold rounded-pill shadow-sm">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<style>
/* Page Specific Glass Styles */
.glass-card {
    background: rgba(255, 255, 255, 0.65);
    backdrop-filter: blur(25px);
    -webkit-backdrop-filter: blur(25px);
    border: 1px solid rgba(255, 255, 255, 0.5);
    border-radius: 24px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    padding: 40px;
}

.form-glass {
    background: rgba(255, 255, 255, 0.5) !important;
    border: 1px solid rgba(255, 255, 255, 0.3) !important;
    border-radius: 12px !important;
    padding: 12px 16px;
    color: #1d1d1f;
    transition: all 0.2s ease;
}

.form-glass:focus {
    background: rgba(255, 255, 255, 0.8) !important;
    border-color: #0071e3 !important;
    box-shadow: 0 0 0 4px rgba(0, 113, 227, 0.15);
}

.btn-glass-primary {
    background: #0071e3;
    background: linear-gradient(135deg, #0071e3, #0077ed);
    color: white;
    border: 1px solid rgba(255,255,255,0.2);
    transition: transform 0.2s, box-shadow 0.2s;
}

.btn-glass-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 113, 227, 0.3);
    color: white;
}

.btn-glass-secondary {
    background: rgba(255, 255, 255, 0.5);
    border: 1px solid rgba(0,0,0,0.1);
    color: #1d1d1f;
    backdrop-filter: blur(5px);
    transition: all 0.2s;
}

.btn-glass-secondary:hover {
    background: rgba(255, 255, 255, 0.8);
    transform: translateY(-1px);
}
</style>

<?php require '../app/views/admin/layout/footer.php'; ?>
