<?php require '../app/views/admin/layout/header.php'; ?>

<link rel="stylesheet" href="/python/public/css/inscription.css">

<!-- Greeting Widget (User) -->
<header class="d-flex justify-content-center align-items-center mb-5 pt-4" style="margin-top: 120px;">
    <div class="glass-greeting-pill px-5 py-4 text-center" style="min-width: 600px;">
        <div class="d-flex flex-column align-items-center">
            <h1 class="display-5 fw-bold mb-1 text-dark" id="greetingText">Welcome</h1>
            <div class="d-flex align-items-center gap-3">
                <span class="fs-4 text-secondary" id="clockTime">00:00:00</span>
                <span class="fs-4 text-secondary">|</span>
                <span class="fs-5 text-secondary" id="currentDate"><?= date('l, F j') ?></span>
            </div>
        </div>
    </div>
    
    <style>
        .glass-greeting-pill {
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 50px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .glass-greeting-pill:hover {
            transform: scale(1.02);
            background: rgba(255, 255, 255, 0.5);
        }
    </style>
    
    <script>
        function updateClock() {
            const now = new Date();
            const timeElement = document.getElementById('clockTime');
            const greetingElement = document.getElementById('greetingText');
            
            if(timeElement) {
                timeElement.textContent = now.toLocaleTimeString('en-US', { hour12: false });
            }
            
            if(greetingElement) {
                const hour = now.getHours();
                let greeting = 'Good Evening';
                if (hour < 12) greeting = 'Good Morning';
                else if (hour < 18) greeting = 'Good Afternoon';
                
                // Use PHP Session name safely
                const userName = "<?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?>";
                greetingElement.textContent = greeting + ", " + userName;
            }
        }
        setInterval(updateClock, 1000);
        document.addEventListener('DOMContentLoaded', updateClock);
    </script>
</header>

<div class="container pb-5">
    <!-- Events Grid -->
    <div class="row g-4 justify-content-center">
        <?php if (empty($data['events'])): ?>
            <div class="col-12 text-center py-5">
                <div class="glass-card p-5 mx-auto" style="max-width: 600px; border-radius: 30px; background: rgba(255,255,255,0.5);">
                    <i class="bi bi-calendar-x display-1 text-secondary opacity-50 mb-3"></i>
                    <h3 class="fw-bold text-dark">No inscriptions yet.</h3>
                    <p class="text-secondary mb-4">You haven't registered for any events.</p>
                    <a href="/python/public/menu" class="btn btn-primary rounded-pill px-4 py-2 bg-dark border-0">Browse Events</a>
                </div>
            </div>
        <?php else: ?>
            <?php foreach($data['events'] as $event): ?>
            <div class="col-md-6 col-lg-4">
                <a href="/python/public/menu/event/<?= $event['id'] ?>" class="text-decoration-none text-dark d-block h-100">
                    <!-- Glassmorphism Card -->
                    <div class="card h-100 border-0 glass-event-card overflow-hidden shadow-sm hover-card">
                        <div class="card-body p-4 d-flex flex-column pt-4 ps-4 pe-4 position-relative">
                            <!-- Status Badge -->
                            <div class="mb-3">
                                <?php if ($event['est_cloture'] == 0): ?>
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2">Ended</span>
                                <?php endif; ?>
                            </div>

                            <div class="z-2 text-start position-relative">
                                <h3 class="fw-bold mb-2 fs-4 text-dark"><?= htmlspecialchars($event['titre']) ?></h3>
                                <p class="mb-3 text-secondary small line-clamp-2"><?= htmlspecialchars($event['description']) ?></p>
                                
                                <div class="d-flex align-items-center text-secondary small gap-3 mb-4">
                                    <div><i class="bi bi-geo-alt me-1"></i> <?= htmlspecialchars($event['lieu']) ?></div>
                                    <div><i class="bi bi-calendar me-1"></i> <?= htmlspecialchars($event['date_evenement']) ?></div>
                                </div>
                            </div>
                            
                            <div class="mt-auto w-100 text-center z-1">
                                <?php if(!empty($event['image_cover'])): 
                                    $evtImg = $event['image_cover'];
                                    if(strpos($evtImg, 'uploads/') === 0) {
                                        $evtImg = '/python/public/' . $evtImg;
                                    } else {
                                        $evtImg = '/python/public/images/' . $evtImg;
                                    }
                                ?>
                                <img src="<?= $evtImg ?>" alt="<?= htmlspecialchars($event['titre']) ?>" class="img-fluid rounded-4 mb-2 shadow-sm" style="width: 100%; height: 200px; object-fit: cover;">
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<style>
    .glass-event-card {
        background: rgba(255, 255, 255, 0.65);
        backdrop-filter: blur(25px);
        -webkit-backdrop-filter: blur(25px);
        border: 1px solid rgba(255, 255, 255, 0.5);
        border-radius: 30px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .glass-event-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important;
        background: rgba(255, 255, 255, 0.75);
    }
</style>


<script src="/python/public/js/admin.js"></script>
<?php require '../app/views/admin/layout/footer.php'; ?>
