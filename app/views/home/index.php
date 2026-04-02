<?php require '../app/views/admin/layout/header.php'; ?>
<link rel="stylesheet" href="/python/public/css/home.css">

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


<main>
    <!-- Hero Section with Logo Animation -->
    <section class="hero-intro">
        <div class="logo-glow"></div>
        <div class="logo-animation-wrapper">
            <i class="bi bi-calendar-event logo-icon" style="color: #272727ff; font-size: 80px; display: block;"></i>
            <h1 class="logo-main">Eventium</h1>
            <p class="logo-tagline">Discover. Connect. Experience.</p>
        </div>
        <div class="scroll-indicator">
            <i class="bi bi-chevron-double-down"></i>
        </div>
    </section>

    <!-- Categories Grid Section (Apple.com Style) -->
    <section class="categories-section">
        <div class="glass-morphism p-5 rounded-5 mb-5 mx-4"> 
            <div class="categories-grid">
            <?php 
            $taglines = [
                'Explore amazing events in this category.',
                'Discover what\'s happening near you.',
                'Connect with like-minded people.',
                'Experience something extraordinary.',
                'Join the community today.',
                'Find your next adventure.'
            ];
            $i = 0;
            foreach($data['categories'] as $cat): 
                $catImg = !empty($cat['image']) ? $cat['image'] : 'default_category.jpg';
                if(strpos($catImg, 'uploads/') === 0) {
                    $catImg = '/python/public/' . $catImg;
                } else {
                    $catImg = '/python/public/images/' . $catImg;
                }
                $tagline = $taglines[$i % count($taglines)];
            ?>
            <div class="category-tile">
                <h2 class="category-tile-title"><?= htmlspecialchars($cat['nom']) ?></h2>
                <p class="category-tile-tagline"><?= $tagline ?></p>
                <div class="category-tile-buttons">
                    <a href="/python/public/menu#cat-<?= $cat['id'] ?>" class="cat-btn-primary">Learn more</a>
                    <a href="/python/public/menu" class="cat-btn-secondary">Browse</a>
                </div>
                <div class="category-tile-image">
                    <img src="<?= $catImg ?>" alt="<?= htmlspecialchars($cat['nom']) ?>">
                </div>
            </div>
            <?php $i++; endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Apple TV+ Style Events Carousel -->
    <section class="events-carousel-section">
        <div class="glass-morphism p-5 rounded-5 mx-4">
        <div class="carousel-header">
            <h2>Upcoming Events</h2>
            <p>Don't miss what's happening next</p>
        </div>
        
        <div class="atv-carousel">
            <button class="atv-nav atv-nav-prev" onclick="atvPrev()"><i class="bi bi-chevron-left"></i></button>
            <button class="atv-nav atv-nav-next" onclick="atvNext()"><i class="bi bi-chevron-right"></i></button>
            
            <div class="atv-carousel-track" id="atvTrack">
                <?php 
                $idx = 0;
                foreach($data['events'] as $event): 
                    $evtImg = !empty($event['image_cover']) ? $event['image_cover'] : 'workshop.jpg';
                    if(strpos($evtImg, 'uploads/') === 0) {
                        $evtImg = '/python/public/' . $evtImg;
                    } else {
                        $evtImg = '/python/public/images/' . $evtImg;
                    }
                ?>
                <div class="atv-slide <?= $idx === 0 ? 'active' : '' ?>" data-index="<?= $idx ?>" onclick="atvGoTo(<?= $idx ?>)">
                    <img src="<?= $evtImg ?>" alt="<?= htmlspecialchars($event['titre']) ?>">
                    <div class="atv-slide-overlay">
                        <h3 class="atv-slide-title"><?= htmlspecialchars($event['titre']) ?></h3>
                        <div class="atv-slide-meta">
                            <a href="/python/public/menu/event/<?= $event['id'] ?>" class="atv-slide-btn">View details</a>
                            <span class="atv-slide-category"><span><?= htmlspecialchars($event['category_name'] ?? 'Event') ?></span> · <?= date('M j', strtotime($event['date_evenement'])) ?></span>
                        </div>
                    </div>
                </div>
                <?php $idx++; endforeach; ?>
            </div>
            
            <div class="atv-dots" id="atvDots">
                <?php for($i = 0; $i < count($data['events']); $i++): ?>
                    <button class="atv-dot <?= $i === 0 ? 'active' : '' ?>" onclick="atvGoTo(<?= $i ?>)"></button>
                <?php endfor; ?>
            </div>
        </div>
        </div>
    </section>
  
</main>
<script src="/python/public/js/home.js"></script>

<?php require '../app/views/admin/layout/footer.php'; ?>
