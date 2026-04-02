<?php require '../app/views/admin/layout/header.php'; ?>
<link rel="stylesheet" href="../public/css/profile.css">

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

<div class="container py-5" style="margin-top: 120px;"> 
    <div class="row g-5 justify-content-center">
        <!-- Sidebar / Stats -->
        <div class="col-md-4">
            <div class="stat-container">
                <div class="text-center mb-5">
                    <div class="bg-secondary rounded-circle mx-auto mb-3 profile-avatar" style="background-image: url('https://ui-avatars.com/api/?name=<?= urlencode($data['user']['nom']) ?>&size=240');"></div>
                    <h3 class="fw-bold mb-1 text-dark"><?= htmlspecialchars($data['user']['nom']) ?></h3>
                    <p class="text-secondary small text-uppercase fw-bold tracking-wide">Eventium ID</p>
                    <div class="badge bg-white text-dark shadow-sm px-3 py-2 rounded-pill mt-2">
                        <?= htmlspecialchars($data['user']['email']) ?>
                    </div>
                </div>

                <div class="row g-3">
                    <?php if(isset($data['stats']['inscriptions'])): ?>
                        <div class="col-12">
                            <div class="stat-glass-card">
                                <h2 class="display-4 fw-bold text-primary mb-0"><?= $data['stats']['inscriptions'] ?></h2>
                                <span class="text-secondary small fw-bold text-uppercase">Events Enrolled</span>
                            </div>
                        </div>
                    <?php elseif(isset($data['stats']['events'])): ?>
                        <div class="col-6">
                            <div class="stat-glass-card">
                                <h3 class="fw-bold text-primary mb-0"><?= $data['stats']['events'] ?></h3>
                                <span class="text-secondary small fw-bold text-uppercase" style="font-size: 10px;">Events</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-glass-card">
                                <h3 class="fw-bold text-success mb-0"><?= $data['stats']['users'] ?></h3>
                                <span class="text-secondary small fw-bold text-uppercase" style="font-size: 10px;">Users</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="stat-glass-card">
                                <h3 class="fw-bold text-warning mb-0"><?= $data['stats']['categories'] ?></h3>
                                <span class="text-secondary small fw-bold text-uppercase" style="font-size: 10px;">Categories</span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Sign Out Button (Sidebar) -->
                <div class="mt-4">
                     <a href="/python/public/logout" class="btn w-100 btn-glass-danger rounded-pill fw-bold py-2">
                        <i class="bi bi-box-arrow-right me-2"></i> Sign Out
                    </a>
                </div>
            </div>
        </div>

        <!-- MainSettings Form -->
        <div class="col-md-8 col-lg-7">
            <div class="profile-glass-card">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h4 class="fw-bold m-0"><i class="bi bi-gear-wide-connected me-2"></i>Account Settings</h4>
                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3">Active</span>
                </div>
                
                <?php if(isset($_GET['updated'])): ?>
                <div class="alert alert-success d-flex align-items-center rounded-4 border-0 bg-success-subtle text-success mb-4 shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                    <div>Profile updated successfully.</div>
                </div>
                <?php endif; ?>

                <?php if(isset($_GET['error'])): ?>
                <div class="alert alert-danger d-flex align-items-center rounded-4 border-0 bg-danger-subtle text-danger mb-4 shadow-sm" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i>
                    <div><?= htmlspecialchars($_GET['error']) ?></div>
                </div>
                <?php endif; ?>

                <form action="/python/public/profile/update" method="POST">
                    <div class="mb-5">
                        <label class="form-label text-secondary small fw-bold text-uppercase ms-1 mb-3">Personal Information</label>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Name" value="<?= htmlspecialchars($data['user']['nom']) ?>">
                                    <label for="name">Full Name</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <input type="email" class="form-control" id="email" value="<?= htmlspecialchars($data['user']['email']) ?>" disabled readonly>
                                    <label for="email">Email Address</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="form-label text-secondary small fw-bold text-uppercase ms-1 mb-3">Security</label>
                        <div class="p-3 rounded-4" style="background: rgba(255,255,255,0.3); border: 1px dashed rgba(0,0,0,0.1);">
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="old_password" name="old_password" placeholder="Current Password">
                                <label for="old_password">Current Password (Required)</label>
                            </div>
                            <div class="form-floating">
                                <input type="password" class="form-control" id="password" name="password" placeholder="New Password">
                                <label for="password">New Password</label>
                            </div>
                            <div class="form-text ms-1 mt-2 text-dark opacity-50"><i class="bi bi-info-circle me-1"></i>Leave blank to keep current password.</div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end align-items-center pt-3 border-top border-secondary-subtle">
                        <button type="submit" class="btn btn-glass-primary rounded-pill px-5 py-3 fw-bold shadow-lg">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-glass-danger {
        background: rgba(255, 59, 48, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 59, 48, 0.2);
        color: #ff7e77ff;
        transition: all 0.3s ease;
    }
    
    .btn-glass-danger:hover {
        background: rgba(255, 59, 48, 0.2);
        color: #fdb3afff;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 59, 48, 0.2);
    }
</style>
<?php require '../app/views/admin/layout/footer.php'; ?>
