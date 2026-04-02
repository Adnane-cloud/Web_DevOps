<?php require '../app/views/admin/layout/header.php'; ?>

<link rel="stylesheet" href="/python/public/css/store.css?v=<?= time() ?>">
<link rel="stylesheet" href="/python/public/css/store_animation.css">
<link rel="stylesheet" href="/python/public/css/store_carousel.css">
<!-- Floating Circles Animation Background -->
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

    <div class="container-fluid pt-5">
        <!-- Event Carousel Section -->
    <section class="store-carousel-section text-start mb-5">
        <div class="container mb-5" style="margin-top: 100px;">
            <div class="glass-morphism p-5 rounded-5 shadow-lg">
                <div class="row align-items-center">
                    <div class="col-lg-7">
                        <h1 class="fw-bold mb-3" style="font-size: 56px; letter-spacing: -2px; line-height: 1.1;">
                            <span class="apple-intelligence-text title-black">Eventium.</span>
                            <br>
                            <span class="text-dark opacity-75" style="font-size: 28px; font-weight: 300; letter-spacing: normal;">The best way to enter the Events you love.</span>
                        </h1>
                    </div>
                    <div class="col-lg-5 text-lg-end mt-4 mt-lg-0">
                        <?php if(!isset($_SESSION['user_id'])): ?>
                            <div class="d-inline-block text-lg-end bg-white bg-opacity-25 p-4 rounded-4 backdrop-blur-md rounded-4 border border-white border-opacity-25">
                                <h2 class="fw-bold mb-2 text-dark" style="font-size: 22px;">Give something special<br>this Year.</h2>
                                <a href="/python/public/login" class="btn btn-light rounded-pill px-4 py-2 mt-2 shadow-sm d-inline-flex align-items-center gap-2 transition-transform hover-scale fw-bold text-dark border">
                                    Sign in Now <i class="bi bi-arrow-right text-dark"></i>
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="d-inline-block text-lg-end">
                                <h2 class="fw-bold mb-0 text-dark" style="font-size: 24px;">Welcome back, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Guest') ?></h2>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="carousel-wrapper js-carousel position-relative container glass-morphism p-4">
                <!-- Navigation Buttons -->
                <!-- Navigation Buttons -->
                <button class="prev-btn glass-arrow rounded-circle position-absolute top-50 translate-middle-y" style="left: -80px; border: none;" id="prevBtn"><i class="bi bi-chevron-left"></i></button>
                <button class="next-btn glass-arrow rounded-circle position-absolute top-50 translate-middle-y" style="right: -80px; border: none;" id="nextBtn"><i class="bi bi-chevron-right"></i></button>

                <!-- Carousel Container -->
                <div class="carousel-container d-flex overflow-auto py-5 justify-content-center" id="carouselContainer" style="scroll-behavior: smooth; scrollbar-width: none;">
                    <?php foreach($data['carousel_events'] as $item): ?>
                    <div class="flex-shrink-0 text-center px-4 js-category-filter" data-category-id="<?= $item['id'] ?>" style="width: 180px; transition: transform 0.3s; cursor: pointer;">
                        <a href="javascript:void(0)" class="text-decoration-none text-dark d-block">
                            <div class="d-flex align-items-center justify-content-center mb-2" style="height: 100px;">
                                <?php 
                                    $imgSrc = $item['image'];
                                    if(strpos($imgSrc, 'uploads/') === 0) {
                                        $imgSrc = '/python/public/' . $imgSrc;
                                    } else {
                                        // Default fallback or legacy images in public/images/
                                        $imgSrc = '/python/public/images/' . $imgSrc; 
                                    }
                                ?>
                                <img src="<?= $imgSrc ?>" alt="<?= $item['title'] ?>" class="img-fluid" style="max-height: 100%; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));">
                            </div>
                            <p class="small fw-semibold mb-0"><?= $item['title'] ?></p>
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
        </div>
    </section>

    <!-- Product Grid (Existing) -->
    <div class="container-fluid px-5">
        <section class="store-grid py-5">
            <!-- No Events Message (Hidden by default) -->
            <div id="no-events-msg" class="text-center py-5 d-none">
                <i class="bi bi-calendar-x fs-1 text-muted mb-3"></i>
                <h3 class="fw-bold text-secondary">No events here.</h3>
                <p class="text-muted">Check back later for updates in this category.</p>
            </div>

            <?php if(empty($data['categorized_events'])): ?>
                <div class="text-center py-5">
                    <p class="text-muted">No events currently available.</p>
                </div>
            <?php else: ?>
                <?php foreach($data['categorized_events'] as $catId => $category): ?>
                    <div class="category-block mb-5 js-category-section glass-morphism p-5" id="category-section-<?= (int)$catId ?>">
                        <div class="px-5 mb-5">
                             <h2 class="fw-bold apple-intelligence-text"><?= htmlspecialchars($category['category_name']) ?>.</h2><h2><span class="text-muted">Browse the collection.</span></h2>
                        </div>
                        
                        <div class="position-relative js-carousel">
                            <button class="btn btn-light rounded-circle shadow-sm position-absolute top-50 translate-middle-y z-3 prev-btn d-flex align-items-center justify-content-center" style="width: 56px; height: 56px; left: -50px; background-color: rgba(255, 255, 255, 0.7); backdrop-filter: blur(5px); border: none;"><i class="bi bi-chevron-left fs-4"></i></button>
                            <button class="btn btn-light rounded-circle shadow-sm position-absolute top-50 translate-middle-y z-3 next-btn d-flex align-items-center justify-content-center" style="width: 56px; height: 56px; right: -50px; background-color: rgba(255, 255, 255, 0.7); backdrop-filter: blur(5px); border: none;"><i class="bi bi-chevron-right fs-4"></i></button>
            
                            <!-- Card Carousel Container -->
                            <div class="carousel-container d-flex overflow-auto py-5 px-0" style="scrollbar-width: none; scroll-behavior: smooth;">
                                <div class="ps-4"></div> <!-- Spacer for starting padding -->
                                <?php foreach($category['events'] as $event): ?>
                                <div class="flex-shrink-0 me-4" style="width: 480px; height: 500px;">
                                    <a href="/python/public/menu/event/<?= $event['id'] ?>" class="text-decoration-none text-dark d-block h-100">
                                        <div class="card h-100 border-0 apple-card overflow-hidden" style="transition: transform 0.2s;">
                                            <div class="card-body p-4 d-flex flex-column pt-5 ps-4 pe-4 position-relative">
                                                <div class="z-2 text-start position-relative">
                                                    <h3 class="store-card-title mb-1"><?= htmlspecialchars($event['title']) ?></h3>
                                                    <span class="apple-intelligence-text store-card-category d-inline-block"><?= htmlspecialchars($category['category_name']) ?></span>
                                                    <p class="mb-2 text-secondary" style="font-size: 14px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;"><?= htmlspecialchars($event['description']) ?></p>
                                                    <p class="mt-1 store-card-info">
                                                        <i class="bi bi-geo-alt"></i> <?= htmlspecialchars($event['place']) ?><br>
                                                        <i class="bi bi-calendar"></i> <?= htmlspecialchars($event['date']) ?>
                                                    </p>
                                                </div>
                                                <div class="position-absolute bottom-0 start-0 w-100 text-center z-1 pointer-events-none">
                                                    <?php if(!empty($event['image_cover'])): 
                                                        $evtImg = $event['image_cover'];
                                                        if(strpos($evtImg, 'uploads/') === 0) {
                                                            $evtImg = '/python/public/' . $evtImg;
                                                        } else {
                                                            $evtImg = '/python/public/images/' . $evtImg;
                                                        }
                                                    ?>
                                                    <img src="<?= $evtImg ?>" alt="<?= htmlspecialchars($event['title']) ?>" class="card-image" style="display: block;">
                                                    <?php else: ?>
                                                        <div style="height: 150px; background: #ddd; display:flex; align-items:center; justify-content:center;">No Image</div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>

<script src="/python/public/js/carousel.js?v=<?= time() ?>"></script>
<script src="/python/public/js/store_filter.js?v=<?= time() ?>"></script>
<?php require '../app/views/admin/layout/footer.php'; ?>