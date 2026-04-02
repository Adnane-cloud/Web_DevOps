<?php require '../app/views/admin/layout/header.php'; ?>
<link rel="stylesheet" href="/python/public/css/register.css">

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
<div class="d-flex flex-column min-vh-100 justify-content-center align-items-center py-5 mt-5 pt-5">
    <div class="auth-glass-container glass-morphism p-5 rounded-5 position-relative z-2">
        <div class="auth-header text-center mb-5">
            <h1>Create your Eventium ID</h1>
            <p class="text-secondary small">One Eventium ID is all you need to access all Eventium services.</p>
        </div>

        <form method="POST" action="/python/public/register">
            <?php if(isset($error)): ?>
                <div class="alert alert-danger small mb-4 rounded-3">
                    <i class="bi bi-exclamation-circle-fill me-2"></i> <?= $error ?>
                </div>
            <?php endif; ?>
            
            <div class="form-floating mb-3">
                <input type="text" class="form-control rounded-3" id="name" name="name" placeholder="Name" required>
                <label for="name" class="text-secondary">Name</label>
            </div>

            <div class="form-floating mb-3">
                <input type="email" class="form-control rounded-3" id="email" name="email" placeholder="name@example.com" required>
                <label for="email" class="text-secondary">Email</label>
            </div>
            <div class="form-text small ps-1 pt-1 mb-3 text-secondary">This will be your new Eventium ID.</div>

            <div class="form-floating mb-3">
                <input type="password" class="form-control rounded-3" id="password" name="password" placeholder="Password" required>
                <label for="password" class="text-secondary">Password</label>
            </div>

            <div class="form-floating mb-4">
                <input type="password" class="form-control rounded-3" id="confirmPassword" name="confirm_password" placeholder="Confirm Password" required>
                <label for="confirmPassword" class="text-secondary">Confirm Password</label>
            </div>

            <button class="btn btn-rose w-100 py-3 rounded-3" style="font-size: 17px;" type="submit">Continue</button>
        </form>
        
        <div class="text-center mt-4 border-top pt-4 border-black-10">
            <a href="login" class="text-decoration-none small text-rose">Already have an account? Sign in ></a>
        </div>
    </div>
</div>

<?php require '../app/views/admin/layout/footer.php'; ?>
