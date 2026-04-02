<?php require '../app/views/admin/layout/header.php'; ?>
<link rel="stylesheet" href="/python/public/css/login.css">

<!-- Centered Big Glass Container -->
<div class="d-flex min-vh-100 align-items-center justify-content-center py-5">
    <div class="glass-morphism p-0 overflow-hidden shadow-lg login-card-container">
        <div class="row g-0 h-100">
            <!-- Left Side: Big Container with Animation -->
            <div class="col-lg-6 d-none d-lg-flex align-items-center justify-content-center position-relative overflow-hidden">
                <!-- Animated Background for Left Panel -->
                <ul class="circles-local">
                     <!-- Circles updated to be darker for contrast on light bg -->
                     <li class="bg-secondary opacity-10"></li>
                     <li class="bg-secondary opacity-10"></li>
                     <li class="bg-secondary opacity-10"></li>
                     <li class="bg-secondary opacity-10"></li>
                     <li class="bg-secondary opacity-10"></li>
                     <li class="bg-secondary opacity-10"></li>
                     <li class="bg-secondary opacity-10"></li>
                     <li class="bg-secondary opacity-10"></li>
                     <li class="bg-secondary opacity-10"></li>
                     <li class="bg-secondary opacity-10"></li>
                </ul>
                
                <div class="z-2 text-center text-dark px-5">
                    <i class="bi bi-calendar-event display-1 mb-3 d-block"></i>
                    <p class="lead fw-light mt-2 opacity-75">Welcome back to Eventium</p>
                </div>
            </div>

            <!-- Right Side: Login Form -->
            <div class="col-lg-6 d-flex align-items-center justify-content-center position-relative">
                <div class="p-5 w-100" style="max-width: 500px;">
                    <div class="auth-header mb-5 text-center">
                        <h1 class="mb-3">Sign in</h1>
                        <h2 class="h6 fw-medium text-secondary">Continue to Eventium</h2>
                    </div>

                    <form id="EventiumAuthForm" method="POST" action="/python/public/login">
                        
                        <?php if(isset($error)): ?>
                            <div class="alert alert-danger small mb-4 rounded-3 text-start">
                                <i class="bi bi-exclamation-circle-fill me-2"></i> <?= $error ?>
                            </div>
                        <?php endif; ?>

                        <!-- Email Field -->
                        <div class="position-relative mb-3" id="emailGroup">
                            <div class="form-floating">
                                <input type="email" class="form-control rounded-3" id="emailInput" name="email" placeholder="Email" style="height: 56px;" required>
                                <label for="emailInput" class="text-secondary">Email</label>
                            </div>
                            
                            <!-- Arrow Button -->
                            <button type="button" class="btn btn-input-arrow rounded-circle position-absolute top-50 end-0 translate-middle-y me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;" id="emailNextBtn" onclick="showPasswordStep()">
                                <i class="bi bi-arrow-right-circle fs-4"></i>
                            </button>
                        </div>

                        <!-- Password Field (Hidden Initially) -->
                        <div class="position-relative d-none mb-3" id="passwordGroup">
                            <div class="form-floating">
                                <input type="password" class="form-control rounded-3" id="passwordInput" name="password" placeholder="Password" style="height: 56px;" >
                                <label for="passwordInput" class="text-secondary">Password</label>
                            </div>
                             <button type="submit" class="btn btn-input-arrow rounded-circle position-absolute top-50 end-0 translate-middle-y me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="bi bi-arrow-right-circle fs-4"></i>
                            </button>
                        </div>
                    </form>
                    
                    <div class="mt-4 text-center border-top pt-4 border-secondary-subtle">
                        <p class="small text-muted mb-0">New here? <a href="register" class="text-decoration-none text-rose">Create Account ></a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="/python/public/js/login.js"></script>

<?php require '../app/views/admin/layout/footer.php'; ?>
