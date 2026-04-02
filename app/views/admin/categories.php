<?php
require_once __DIR__ . '/layout/header.php';
require_once __DIR__ . '/layout/greeting.php';
?>

<div class="container-fluid px-5">
    <div class="row">
        <div class="col-md-4">
            <div class="pastel-glass-card mb-4" style="padding: 1.5rem;">
                <h3 class="h5 fw-bold mb-4">Add Category</h3>
                <form method="POST" action="/python/public/admin/categories/add" enctype="multipart/form-data">
                    <?php if(isset($_GET['error']) && $_GET['error'] == 'exists'): ?>
                        <div class="alert alert-danger small py-2 mb-3">Category already exists</div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Name</label>
                        <input type="text" name="name" class="form-control form-glass" placeholder="e.g. Technology" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Cover Image</label>
                        <input type="file" name="image" class="form-control form-glass">
                    </div>
                    <button type="submit" class="btn btn-glass-primary w-100 rounded-3 fw-medium">Save Category</button>
                </form>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="pastel-glass-card text-start card-padding-remove" style="padding: 0;">
                <div class="d-flex justify-content-between align-items-center mb-4 px-4 pt-4 w-100">
                    <h2 class="h5 fw-bold mb-0">Managed Categories</h2>
                </div>
                
                <?php if(isset($_GET['success']) && $_GET['success'] == 'updated'): ?>
                    <div class="alert alert-success small py-2 mb-3 mx-4">Category updated successfully.</div>
                <?php endif; ?>

                <div class="table-responsive w-100">
                    <table class="table-apple w-100 mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">ID</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Event Count</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                    <tbody>
                            <?php foreach($data['categories'] as $cat): ?>
                            <tr style="border-bottom: 1px solid rgba(0,0,0,0.05);">
                                <td class="text-secondary ps-4">#<?= $cat['id'] ?></td>
                                <td class="fw-medium">
                                    <div class="d-flex align-items-center">
                                        <?php if(!empty($cat['image']) && file_exists('../public/' . $cat['image'])): ?>
                                            <img src="/python/public/<?= $cat['image'] ?>" class="rounded-3 me-3" width="40" height="40" style="object-fit:cover;">
                                        <?php else: ?>
                                            <span class="d-inline-block rounded-3 me-3" style="width: 40px; height: 40px; background-color: #f5f5f7;"></span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="fw-medium"><?= htmlspecialchars($cat['nom']) ?></td>
                                <td><span class="badge rounded-pill bg-light text-dark border"><?= $cat['event_count'] ?> events</span></td>
                                <td class="text-end pe-4">
                                    <a href="/python/public/admin/categories/edit?id=<?= $cat['id'] ?>" class="btn-table-action me-1"><i class="bi bi-pencil"></i></a>
                                    <form action="/python/public/admin/categories/delete" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                        <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                                        <button type="submit" class="btn-table-action text-danger border-0"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
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
</style>

<?php require '../app/views/admin/layout/footer.php'; ?>
