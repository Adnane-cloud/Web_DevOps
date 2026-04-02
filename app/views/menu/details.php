<?php require '../app/views/admin/layout/header.php'; ?>
<link rel="stylesheet" href="/python/public/css/details.css">


<?php 
    $event = $data['event'];
    $imgSrc = $event['image_cover'];
    if(!empty($imgSrc)) {
         if(strpos($imgSrc, 'uploads/') === 0) {
            $imgSrc = '/python/public/' . $imgSrc;
        } else {
            $imgSrc = '/python/public/images/' . $imgSrc;
        }
    } else {
        $imgSrc = '/python/public/images/default_event.jpg'; 
    }
?>

<div class="container detail-container pb-5">
    <div class="row g-4 justify-content-center">
        
        <div class="col-lg-8">
            <div class="content-card p-4 p-md-5">
                
                <img src="<?= $imgSrc ?>" alt="<?= htmlspecialchars($event['titre']) ?>" class="event-cover-image">

                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <span class="badge bg-primary rounded-pill mb-3 px-3 py-2">Event</span>
                        <h1 class="fw-bold display-5 mb-2"><?= htmlspecialchars($event['titre']) ?></h1> 
                        <p class="text-secondary lead fs-6"><i class="bi bi-geo-alt-fill me-2"></i><?= htmlspecialchars($event['lieu']) ?></p>
                    </div>
                </div>

                <hr class="opacity-10 my-4">

                <h4 class="fw-bold mb-3">About this Event</h4>
                <p class="text-secondary" style="line-height: 1.8; font-size: 1.05rem;">
                    <?= nl2br(htmlspecialchars($event['description'])) ?>
                </p>
            </div>
            
            <?php if ($event['est_cloture'] == 1): ?>
                <div class="content-card p-4 mt-3" id="reviews" style="height: auto;">
                     <h4 class="fw-bold mb-3"><i class="bi bi-chat-left-quote me-2"></i> Reviews & Comments</h4>
                     <!-- Add Comment Form -->
                    <form action="/python/public/menu/comment" method="POST" class="mb-4">
                        <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
                        
                        <div class="mb-2">
                            <label class="form-label small fw-bold">Your Rating</label>
                            <div class="d-flex gap-1" id="starRating">
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                <i class="bi bi-star-fill fs-5 text-warning star-btn" data-value="<?= $i ?>" style="cursor: pointer; opacity: 0.3;" onclick="setRating(<?= $i ?>)"></i>
                                <?php endfor; ?>
                            </div>
                            <input type="hidden" name="rating" id="ratingValue" value="5">
                        </div>
                        
                        <div class="mb-3">
                            <textarea name="comment" class="form-control rounded-3 py-2 form-control-sm" rows="2" placeholder="Share your experience..." required style="resize: none;"></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold">
                            Submit Review
                        </button>
                    </form>
                    
                    <hr class="my-3 opacity-10">
                    
                    <!-- Comments List -->
                    <div class="comments-list" style="max-height: 350px; overflow-y: auto; padding-right: 5px;">
                        <?php if (empty($data['comments'])): ?>
                            <div class="text-center py-3 text-muted">
                                <i class="bi bi-chat-left-text fs-3 d-block mb-1 opacity-25"></i>
                                <p class="small mb-0">No reviews yet.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach($data['comments'] as $comment): ?>
                            <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                                <div class="flex-shrink-0">
                                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width: 38px; height: 38px; font-size: 0.8rem; background-image: url('https://ui-avatars.com/api/?name=<?= urlencode($comment['user_name']) ?>'); background-size: cover;"></div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-0">
                                        <h6 class="fw-bold mb-0 small"><?= htmlspecialchars($comment['user_name']) ?></h6>
                                        <small class="text-muted" style="font-size: 0.75rem;"><?= date('M j, Y', strtotime($comment['created_at'])) ?></small>
                                    </div>
                                    <div class="mb-1">
                                        <?php for($s = 1; $s <= 5; $s++): ?>
                                            <i class="bi bi-star-fill <?= $s <= $comment['rating'] ? 'text-warning' : 'text-muted opacity-25' ?>" style="font-size: 0.7rem;"></i>
                                        <?php endfor; ?>
                                    </div>
                                    <p class="mb-0 text-secondary small"><?= nl2br(htmlspecialchars($comment['comment'])) ?></p>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div class="col-lg-4">
            <div class="sticky-sidebar">
               
                <div class="content-card p-4 mb-4 text-center">
                     <span class="text-secondary small fw-bold text-uppercase d-block mb-2">Date & Time</span>
                     <h2 class="fw-bold text-danger display-4 mb-0"><?= date('d', strtotime($event['date_evenement'])) ?></h2>
                     <p class="text-uppercase fw-bold fs-5 mb-0"><?= date('M Y', strtotime($event['date_evenement'])) ?></p>
                     <p class="text-muted mt-2 mb-0"><i class="bi bi-clock me-1"></i><?= date('h:i A', strtotime($event['date_evenement'])) ?></p>
                </div>

                <!-- Registration Actions Card -->
                <div class="content-card p-4">
                    <h5 class="fw-bold mb-4">Registration</h5>

                    <?php if ($data['is_enrolled']): ?>
                        <div class="text-center mb-4">
                            <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                                <i class="bi bi-check-lg fs-3"></i>
                            </div>
                            <h6 class="fw-bold text-success">You are registered!</h6>
                        </div>

                        <a href="/python/public/menu/invitation/<?= $event['id'] ?>" class="btn btn-dark w-100 py-3 rounded-pill fw-bold mb-3">
                            <i class="bi bi-download me-2"></i> Download Invitation
                        </a>
                        
                        <form action="/python/public/menu/cancel-enroll" method="POST" onsubmit="return confirm('Are you sure you want to cancel your registration?');">
                            <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
                            <button type="submit" class="btn btn-outline-danger w-100 py-3 rounded-pill fw-bold">
                                Cancel Inscription
                            </button>
                        </form>
                    <?php elseif ($event['est_cloture'] == 1): ?>
                        <button class="btn btn-secondary w-100 py-3 rounded-pill fw-bold" disabled>
                            Event Ended
                        </button>
                    <?php else: ?>
                        <form action="/python/public/menu/enroll" method="POST">
                            <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
                            <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm mb-3">
                                Enroll Now
                            </button>
                        </form>
                        <div class="d-flex align-items-center justify-content-center gap-2 text-muted small">
                            <i class="bi bi-people-fill"></i>
                            <span><?= $event['nb_max_participants'] ?> spots total</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    function setRating(rating) {
        // Update hidden input
        document.getElementById('ratingValue').value = rating;
        
        // Update User Interface
        const stars = document.querySelectorAll('#starRating .star-btn');
        stars.forEach(star => {
            const starVal = parseInt(star.getAttribute('data-value'));
            if (starVal <= rating) {
                star.style.opacity = '1';
                star.classList.remove('text-muted');
                star.classList.add('text-warning');
            } else {
                star.style.opacity = '0.3';
                star.classList.remove('text-warning');
                star.classList.add('text-muted');
            }
        });
    }
</script>

<?php require '../app/views/admin/layout/footer.php'; ?>
