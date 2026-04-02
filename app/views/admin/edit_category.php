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
    <div class="glass-card mx-auto" style="max-width: 600px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4 fw-bold mb-0 text-dark"><i class="bi bi-pencil-square me-2 text-primary"></i>Edit Category</h2>
            <a href="/python/public/admin/categories" class="btn btn-glass-secondary rounded-pill px-4">
                Cancel
            </a>
        </div>

        <form method="POST" action="/python/public/admin/categories/update" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $data['category']['id'] ?>">

            <?php if(isset($_GET['error'])): ?>
                <div class="alert alert-danger d-flex align-items-center rounded-3 border-0 bg-danger-subtle text-danger mb-4 shadow-sm">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>
                    <div>
                        <?php 
                        if($_GET['error'] == 'exists') echo 'Category name already exists.';
                        else echo 'An error occurred. Please try again.';
                        ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="mb-4">
                <label class="form-label small fw-bold text-secondary text-uppercase ms-1">Category Name</label>
                <input type="text" name="name" class="form-control form-glass" value="<?= htmlspecialchars($data['category']['nom']) ?>" required>
            </div>

            <div class="mb-4">
                <label class="form-label small fw-bold text-secondary text-uppercase ms-1">Cover Image</label>
                <div class="p-3 rounded-3" style="background: rgba(255,255,255,0.4); border: 1px dashed rgba(0,0,0,0.1);">
                    <input type="file" name="image" class="form-control form-glass mb-3">
                    <?php if(!empty($data['category']['image'])): ?>
                        <div class="d-flex align-items-center bg-white rounded-3 p-2 shadow-sm" style="width: fit-content;">
                            <img src="/python/public/<?= $data['category']['image'] ?>" alt="Cover" class="rounded-2" style="height: 50px; width: 50px; object-fit: cover;">
                            <span class="ms-3 small text-secondary">Current Image</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="d-grid mt-5">
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
