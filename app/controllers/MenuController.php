<?php

class MenuController extends Controller {
    public function index() {
        require '../app/config/database.php';

        // 1. Fetch Categories
        $catStmt = $pdo->query("SELECT * FROM categories");
        $all_categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);
        $categories_map = [];
        foreach ($all_categories as $cat) {
            $categories_map[(int)$cat['id']] = $cat['nom'];
        }

        // 2. Fetch Events
        $stmt = $pdo->query("SELECT * FROM evenements ORDER BY created_at DESC");
        $raw_events = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $grouped_events = [];
        
        foreach ($raw_events as $row) {
            $catId = (int)$row['categorie_id']; // Cast to ensure consistent keys
            $catName = $categories_map[$catId] ?? 'Uncategorized';
            
            if (!isset($grouped_events[$catId])) {
                $grouped_events[$catId] = [
                    'category_name' => $catName,
                    'events' => []
                ];
            }

            $grouped_events[$catId]['events'][] = [
                'id' => $row['id'],
                'title' => $row['titre'],
                'description' => $row['description'],
                'date' => $row['date_evenement'],
                'place' => $row['lieu'],
                'nb_max' => $row['nb_max_participants'],
                'categorie_id' => $row['categorie_id'],
                'image_cover' => $row['image_cover'],
                'closed' => $row['est_cloture'],
                'creator_id' => $row['createur_id'],
                'time_created' => $row['created_at'],
                // View Helpers
                'image' => $row['image_cover'] ?: 'workshop.jpg', // Fallback
            ];
        }
        
        $carousel_events = [];
        foreach ($all_categories as $cat) {
            $catId = (int)$cat['id'];
            $img = !empty($cat['image']) ? $cat['image'] : 'default_category.jpg';
            
            $carousel_events[] = [
                'id' => $catId,
                'title' => $cat['nom'],
                'image' => $img,
                'link' => '#cat-' . $catId
            ];
        }

        $data = [
            'title' => 'Event menu',
            'carousel_events' => $carousel_events,
            'categorized_events' => $grouped_events
        ];
        
        $this->view('menu/index', $data);
    }

    public function inscriptions() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /python/public/login');
            exit;
        }

        require '../app/config/database.php';
        $userId = $_SESSION['user_id'];

        // Fetch user inscriptions with event details
        $stmt = $pdo->prepare("
            SELECT e.* 
            FROM inscriptions i 
            JOIN evenements e ON i.evenement_id = e.id 
            WHERE i.user_id = :userId 
            ORDER BY i.date_inscription DESC
        ");
        $stmt->execute(['userId' => $userId]);
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $data = [
            'title' => 'My Inscriptions',
            'events' => $events
        ];
        
        $this->view('menu/inscriptions', $data);
    }
    
    public function details($id) {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /python/public/login');
            exit;
        }

        require '../app/config/database.php';
        $userId = $_SESSION['user_id'];

        // Fetch Event Details
        $stmt = $pdo->prepare("SELECT * FROM evenements WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $event = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$event) {
            header('Location: /python/public/menu');
            exit;
        }

        // Check if user is already enrolled
        $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM inscriptions WHERE user_id = :userId AND evenement_id = :eventId");
        $checkStmt->execute(['userId' => $userId, 'eventId' => $id]);
        $isEnrolled = $checkStmt->fetchColumn() > 0;

        // Fetch comments if event is ended
        $comments = [];
        if ($event['est_cloture'] == 1) {
            $commentsStmt = $pdo->prepare("
                SELECT c.*, u.nom as user_name 
                FROM comments c 
                JOIN users u ON c.user_id = u.id 
                WHERE c.event_id = :eventId 
                ORDER BY c.created_at DESC
            ");
            $commentsStmt->execute(['eventId' => $id]);
            $comments = $commentsStmt->fetchAll(PDO::FETCH_ASSOC);
        }

        $data = [
            'title' => $event['titre'],
            'event' => $event,
            'is_enrolled' => $isEnrolled,
            'comments' => $comments
        ];

        $this->view('menu/details', $data);
    }

    public function enroll() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /python/public/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require '../app/config/database.php';
            
            $userId = $_SESSION['user_id'];
            $eventId = $_POST['event_id'];

            // Prevent duplicate enrollment
            $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM inscriptions WHERE user_id = :userId AND evenement_id = :eventId");
            $checkStmt->execute(['userId' => $userId, 'eventId' => $eventId]);
            
            if ($checkStmt->fetchColumn() == 0) {
                // Generate unique token
                $token = bin2hex(random_bytes(16));

                // Insert Enrollment with token
                $stmt = $pdo->prepare("INSERT INTO inscriptions (user_id, evenement_id, token_unique) VALUES (:userId, :eventId, :token)");
                $stmt->execute(['userId' => $userId, 'eventId' => $eventId, 'token' => $token]);

                // Create Notification for Admin
                // Fetch User Name and Event Title
                $infoStmt = $pdo->prepare("
                    SELECT u.nom as user_name, e.titre as event_title 
                    FROM users u, evenements e 
                    WHERE u.id = :userId AND e.id = :eventId
                ");
                $infoStmt->execute(['userId' => $userId, 'eventId' => $eventId]);
                $info = $infoStmt->fetch(PDO::FETCH_ASSOC);

                if ($info) {
                    $message = "{$info['user_name']} joined {$info['event_title']}";
                    $notifStmt = $pdo->prepare("INSERT INTO notifications (message, type) VALUES (:message, 'inscription')");
                    $notifStmt->execute(['message' => $message]);
                }
            }
            
            // Redirect to Inscriptions page as requested ("store the result in inscription column" -> imply showing it)
            header('Location: /python/public/inscriptions');
            exit;
        }
    }

    public function cancel_enroll() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /python/public/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require '../app/config/database.php';
            
            $userId = $_SESSION['user_id'];
            $eventId = $_POST['event_id'];

            $stmt = $pdo->prepare("DELETE FROM inscriptions WHERE user_id = :userId AND evenement_id = :eventId");
            $stmt->execute(['userId' => $userId, 'eventId' => $eventId]);
            
            // Redirect back to details to show 'Enroll Now' state
            header('Location: /python/public/menu/event/' . $eventId);
            exit;
        }
    }

    public function add_comment() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /python/public/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require '../app/config/database.php';
            
            $userId = $_SESSION['user_id'];
            $eventId = $_POST['event_id'];
            $comment = trim($_POST['comment']);
            $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 5;

            // Validate rating range
            if ($rating < 1) $rating = 1;
            if ($rating > 5) $rating = 5;

            // Validate comment not empty
            if (!empty($comment)) {
                $stmt = $pdo->prepare("INSERT INTO comments (event_id, user_id, comment, rating) VALUES (:event_id, :user_id, :comment, :rating)");
                $stmt->execute([
                    'event_id' => $eventId,
                    'user_id' => $userId,
                    'comment' => $comment,
                    'rating' => $rating
                ]);
            }
            
            header('Location: /python/public/menu/event/' . $eventId . '#reviews');
            exit;
        }
    }

    public function download_invitation($eventId) {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /python/public/login');
            exit;
        }

        require '../app/config/database.php';
        $userId = $_SESSION['user_id'];

        // Fetch inscription with token
        $stmt = $pdo->prepare("
            SELECT i.*, u.nom as user_name, u.email as user_email, 
                   e.titre as event_title, e.date_evenement, e.lieu as event_location, e.description as event_description
            FROM inscriptions i
            JOIN users u ON i.user_id = u.id
            JOIN evenements e ON i.evenement_id = e.id
            WHERE i.user_id = :userId AND i.evenement_id = :eventId
        ");
        $stmt->execute(['userId' => $userId, 'eventId' => $eventId]);
        $inscription = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$inscription) {
            header('Location: /python/public/menu');
            exit;
        }

        // Generate token if missing (for old inscriptions)
        if (empty($inscription['token_unique'])) {
            $token = bin2hex(random_bytes(16));
            $updateStmt = $pdo->prepare("UPDATE inscriptions SET token_unique = :token WHERE id = :id");
            $updateStmt->execute(['token' => $token, 'id' => $inscription['id']]);
            $inscription['token_unique'] = $token;
        }

        $token = $inscription['token_unique'];
        $qrData = "EVENTIUM-TICKET|{$inscription['event_title']}|{$inscription['user_name']}|{$token}";
        $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($qrData);

        // Generate HTML for PDF
        $html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Event Invitation</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f5f5f7; padding: 40px; }
        .print-bar { max-width: 600px; margin: 0 auto 20px; text-align: center; }
        .print-bar button { background: #0071e3; color: #fff; border: none; padding: 12px 30px; border-radius: 980px; font-size: 16px; font-weight: 500; cursor: pointer; transition: all 0.2s; }
        .print-bar button:hover { background: #0077ed; }
        .print-bar p { margin-top: 10px; font-size: 13px; color: #86868b; }
        .ticket { background: #fff; border-radius: 20px; max-width: 600px; margin: 0 auto; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.1); }
        .ticket-header { background: linear-gradient(135deg, #0071e3 0%, #5856d6 100%); color: #fff; padding: 30px; text-align: center; }
        .ticket-header h1 { font-size: 28px; margin-bottom: 5px; }
        .ticket-header p { opacity: 0.8; font-size: 14px; }
        .ticket-body { padding: 30px; }
        .info-row { display: flex; justify-content: space-between; padding: 15px 0; border-bottom: 1px solid #f0f0f5; }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: #86868b; font-size: 14px; }
        .info-value { font-weight: 600; color: #1d1d1f; font-size: 16px; text-align: right; max-width: 60%; }
        .qr-section { text-align: center; padding: 30px; background: #f5f5f7; }
        .qr-section img { border-radius: 10px; }
        .qr-section p { margin-top: 15px; font-size: 12px; color: #86868b; }
        .token { font-family: monospace; font-size: 11px; color: #0071e3; background: #e8f4ff; padding: 5px 10px; border-radius: 5px; margin-top: 10px; display: inline-block; }
        .footer { text-align: center; padding: 20px; font-size: 12px; color: #86868b; }
        @media print { .print-bar { display: none !important; } body { padding: 0; background: #fff; } .ticket { box-shadow: none; } }
    </style>
</head>
<body>
    <div class="print-bar">
        <button onclick="window.print()">📄 Save as PDF / Print</button>
        <p>Click the button above, then select "Save as PDF" as the destination</p>
    </div>
    <div class="ticket">
        <div class="ticket-header">
            <h1>🎫 Event Invitation</h1>
            <p>Your ticket to an amazing experience</p>
        </div>
        <div class="ticket-body">
            <div class="info-row">
                <span class="info-label">Event</span>
                <span class="info-value">' . htmlspecialchars($inscription['event_title']) . '</span>
            </div>
            <div class="info-row">
                <span class="info-label">Date & Time</span>
                <span class="info-value">' . date('F j, Y - g:i A', strtotime($inscription['date_evenement'])) . '</span>
            </div>
            <div class="info-row">
                <span class="info-label">Location</span>
                <span class="info-value">' . htmlspecialchars($inscription['event_location']) . '</span>
            </div>
            <div class="info-row">
                <span class="info-label">Attendee Name</span>
                <span class="info-value">' . htmlspecialchars($inscription['user_name']) . '</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email</span>
                <span class="info-value">' . htmlspecialchars($inscription['user_email']) . '</span>
            </div>
        </div>
        <div class="qr-section">
            <img src="' . $qrUrl . '" alt="QR Code" width="200" height="200">
            <p>Scan this QR code at the event entrance</p>
            <span class="token">' . $token . '</span>
        </div>
        <div class="footer">
            <p>Powered by <strong>Eventium</strong> • This invitation is non-transferable</p>
        </div>
    </div>
</body>
</html>';

        // Output inline (displays in browser with print option)
        header('Content-Type: text/html; charset=UTF-8');
        echo $html;
        exit;
    }

    public function search() {
        if (!isset($_GET['q'])) {
            echo json_encode([]);
            exit;
        }

        require '../app/config/database.php';
        $query = '%' . trim($_GET['q']) . '%';

        $stmt = $pdo->prepare("SELECT id, titre as title, description, image_cover FROM evenements WHERE titre LIKE :query OR description LIKE :query LIMIT 5");
        $stmt->execute(['query' => $query]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Format image paths
        foreach ($results as &$row) {
             if(strpos($row['image_cover'], 'uploads/') === 0) {
                $row['image_cover'] = '/python/public/' . $row['image_cover'];
            } else {
                $row['image_cover'] = '/python/public/images/' . $row['image_cover'];
            }
        }

        header('Content-Type: application/json');
        echo json_encode($results);
        exit;
    }
}
