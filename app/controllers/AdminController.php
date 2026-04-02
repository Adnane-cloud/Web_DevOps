<?php

class AdminController extends Controller {
    public function __construct() {
        require_admin();
    }



    public function index() {
        // Dashboard Home
        require '../app/config/database.php';

        // 1. Total Events
        $stmt = $pdo->query("SELECT COUNT(*) FROM evenements");
        $total_events = $stmt->fetchColumn();

        // 2. Total Users
        $stmt = $pdo->query("SELECT COUNT(*) FROM users");
        $total_users = $stmt->fetchColumn();

        // 3. Active Events (Replacing Revenue)
        $stmt = $pdo->query("SELECT COUNT(*) FROM evenements WHERE est_cloture = 0");
        $active_events = $stmt->fetchColumn();

        // 4. Total Inscriptions (Replacing Attendance percentage)
        $stmt = $pdo->query("SELECT COUNT(*) FROM inscriptions");
        $total_inscriptions = $stmt->fetchColumn();


        // Recent Events Table
        $stmt = $pdo->query("SELECT titre, date_evenement, nb_max_participants, est_cloture FROM evenements ORDER BY created_at DESC LIMIT 5");
        $recent_events = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 5. Chart Data: Events per Category (Doughnut)
        $stmt = $pdo->query("SELECT c.nom, COUNT(e.id) as count FROM categories c LEFT JOIN evenements e ON c.id = e.categorie_id GROUP BY c.id");
        $category_stats = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 6. Chart Data: Inscriptions over last 30 days (Line)
        $stmt = $pdo->query("
            SELECT DATE_FORMAT(date_inscription, '%Y-%m-%d') as day, COUNT(*) as count 
            FROM inscriptions 
            WHERE date_inscription >= DATE_SUB(NOW(), INTERVAL 30 DAY) 
            GROUP BY day 
            ORDER BY day ASC
        ");
        $registration_stats = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 7. Top Events for Podium (Ranked by Inscriptions)
        $stmt = $pdo->query("
            SELECT e.titre, COUNT(i.id) as inscription_count
            FROM evenements e
            LEFT JOIN inscriptions i ON e.id = i.evenement_id
            GROUP BY e.id
            ORDER BY inscription_count DESC
            LIMIT 3
        ");
        $top_events = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 8. Recent Activity (New Widget Data)
        $stmtActivity = $pdo->query("
            SELECT u.nom as user_name, e.titre as event_title, i.date_inscription
            FROM inscriptions i
            JOIN users u ON i.user_id = u.id
            JOIN evenements e ON i.evenement_id = e.id
            ORDER BY i.date_inscription DESC
            LIMIT 5
        ");
        $recentActivity = $stmtActivity->fetchAll(PDO::FETCH_ASSOC);

        $data = [
            'title' => 'Dashboard',
            'current_page' => 'dashboard',
            'stats' => [
                'total_events' => $total_events,
                'active_users' => $total_users, 
                'active_events' => $active_events,
                'total_inscriptions' => $total_inscriptions
            ],
            'recent_events' => $recent_events,
            'charts' => [
                'categories' => $category_stats,
                'registrations' => $registration_stats
            ],
            'top_events' => $top_events,
            'recent_activity' => $recentActivity // Activity Data
        ];
        $this->view('admin/index', $data);
    }

    public function events() {
        // Event Management
        require '../app/config/database.php';
        
        // Fetch all events with creator name (optional)
        $stmt = $pdo->query("SELECT * FROM evenements ORDER BY date_evenement DESC");
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch categories for the modal
        $catStmt = $pdo->query("SELECT * FROM categories");
        $categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);

        $data = [
            'title' => 'Event Management',
            'current_page' => 'events',
            'events' => $events,
            'categories' => $categories
        ];
        $this->view('admin/events', $data);
    }   

    public function add_event() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require '../app/config/database.php';

            $title = $_POST['title'];
            $description = $_POST['description'];
            $date = $_POST['date'];
            $location = $_POST['location'];
            $max_participants = $_POST['max_participants'];
            $category_id = $_POST['category_id'];
            $creator_id = $_SESSION['user_id'];
            $image_cover = 'workshop.jpg'; // Default or handle upload

            // Handle Image Upload if provided
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = '../public/uploads/events/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                
                $fileName = time() . '_' . basename($_FILES['image']['name']);
                $targetPath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                    $image_cover = 'uploads/events/' . $fileName; // Store relative path
                }
            }

            $sql = "INSERT INTO evenements (titre, description, date_evenement, lieu, nb_max_participants, categorie_id, image_cover, est_cloture, createur_id) 
                    VALUES (:title, :description, :date, :location, :max_participants, :category_id, :image_cover, 0, :creator_id)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'title' => $title,
                'description' => $description,
                'date' => $date,
                'location' => $location,
                'max_participants' => $max_participants,
                'category_id' => $category_id,
                'image_cover' => $image_cover,
                'creator_id' => $creator_id
            ]);

            // Create Global Notification for Users
            $msg = "New event added: $title";
            $notifStmt = $pdo->prepare("INSERT INTO notifications (message, type) VALUES (:message, 'new_event')");
            $notifStmt->execute(['message' => $msg]);

            header('Location: /python/public/admin/events'); // Should probably use a router helper
            exit;
        }
    }   

    public function delete_event() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require '../app/config/database.php';
            $id = $_POST['id'];
            
            // Optional: Check ownership or admin rights (already checked by constructor)
            $stmt = $pdo->prepare("DELETE FROM evenements WHERE id = :id");
            $stmt->execute(['id' => $id]);
            
            header('Location: /python/public/admin/events');
            exit;
        }
    }

    public function edit_event() {
        require '../app/config/database.php';
        $id = $_GET['id'] ?? 0;

        $stmt = $pdo->prepare("SELECT * FROM evenements WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $event = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$event) {
            header('Location: /python/public/admin/events');
            exit;
        }

        // Fetch categories
        $catStmt = $pdo->query("SELECT * FROM categories");
        $categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);

        $data = [
            'title' => 'Edit Event',
            'current_page' => 'events',
            'event' => $event,
            'categories' => $categories
        ];
        $this->view('admin/edit_event', $data);
    }

    public function update_event() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require '../app/config/database.php';
            
            $id = $_POST['id'];
            $title = $_POST['title'];
            $description = $_POST['description'];
            $date = $_POST['date']; // datetime-local format
            $location = $_POST['location'];
            $max_participants = $_POST['max_participants'];
            $category_id = $_POST['category_id'];
            
            // Handle Image Upload if provided
            $image_sql_part = "";
            $params = [
                'title' => $title,
                'description' => $description,
                'date' => $date,
                'location' => $location,
                'max_participants' => $max_participants,
                'category_id' => $category_id,
                'id' => $id
            ];

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = '../public/uploads/events/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                
                $fileName = time() . '_' . basename($_FILES['image']['name']);
                $targetPath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                    $image_cover = 'uploads/events/' . $fileName;
                    $image_sql_part = ", image_cover = :image_cover";
                    $params['image_cover'] = $image_cover;
                }
            }

            $sql = "UPDATE evenements SET 
                    titre = :title, 
                    description = :description, 
                    date_evenement = :date, 
                    lieu = :location, 
                    nb_max_participants = :max_participants, 
                    categorie_id = :category_id 
                    $image_sql_part
                    WHERE id = :id";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);

            header('Location: /python/public/admin/events');
            exit;
        }
    }

    public function categories() {
        // Categories Management
        require '../app/config/database.php';

        $stmt = $pdo->query("
            SELECT c.*, COUNT(e.id) as event_count 
            FROM categories c 
            LEFT JOIN evenements e ON c.id = e.categorie_id 
            GROUP BY c.id
        ");
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $data = [
            'title' => 'Categories',
            'current_page' => 'categories',
            'categories' => $categories
        ];
        $this->view('admin/categories', $data);
    }

    public function add_category() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require '../app/config/database.php';
            
            $name = trim($_POST['name']);
            $image = 'default_category.jpg';

            if (empty($name)) {
                // Should handle error, but for now redirect back
                header('Location: /python/public/admin/categories');
                exit;
            }

            // Check if exists
            $stmt = $pdo->prepare("SELECT id FROM categories WHERE nom = :nom");
            $stmt->execute(['nom' => $name]);
            if ($stmt->fetch()) {
                header('Location: /python/public/admin/categories?error=exists');
                exit;
            }

            // Handle Image Upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = '../public/uploads/categories/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                
                $fileName = time() . '_' . basename($_FILES['image']['name']);
                $targetPath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                    $image = 'uploads/categories/' . $fileName;
                }
            }

            $stmt = $pdo->prepare("INSERT INTO categories (nom, image) VALUES (:nom, :image)");
            $stmt->execute(['nom' => $name, 'image' => $image]);

            header('Location: /python/public/admin/categories');
            exit;
        }
    }

    public function delete_category() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require '../app/config/database.php';
            $id = $_POST['id'];
            
            // Optional: Check if used in events? For now foreign key might restrict or set null.
            // If we set null on delete cascade:
            $stmt = $pdo->prepare("DELETE FROM categories WHERE id = :id");
            try {
                $stmt->execute(['id' => $id]);
            } catch (PDOException $e) {
                // If deletion fails (e.g. foreign key constraint), redirect with error
                 header('Location: /python/public/admin/categories?error=used');
                 exit;
            }
            
            header('Location: /python/public/admin/categories');
            exit;
        }
    }

    public function edit_category() {
        require '../app/config/database.php';
        $id = $_GET['id'] ?? 0;

        $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $category = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$category) {
            header('Location: /python/public/admin/categories');
            exit;
        }

        $data = [
            'title' => 'Edit Category',
            'current_page' => 'categories',
            'category' => $category
        ];
        $this->view('admin/edit_category', $data);
    }



    public function update_category() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require '../app/config/database.php';
            
            $id = $_POST['id'];
            $name = trim($_POST['name']);
            
            $image_sql_part = "";
            $params = [
                'name' => $name,
                'id' => $id
            ];

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = '../public/uploads/categories/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                
                $fileName = time() . '_' . basename($_FILES['image']['name']);
                $targetPath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                    $image = 'uploads/categories/' . $fileName;
                    $image_sql_part = ", image = :image";
                    $params['image'] = $image;
                }
            }

            $sql = "UPDATE categories SET nom = :name $image_sql_part WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            
            try {
                $stmt->execute($params);
                header('Location: /python/public/admin/categories?success=updated');
                exit;
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) { 
                     header('Location: /python/public/admin/categories/edit?id=' . $id . '&error=exists');
                     exit;
                }
                header('Location: /python/public/admin/categories/edit?id=' . $id . '&error=unknown');
                exit;
            }
        }
    }

    public function attendance() {
        require '../app/config/database.php';
        
        $search = $_GET['search'] ?? '';
        $eventId = $_GET['event_id'] ?? '';
        $whereClauses = [];
        $params = [];

        // Base Search
        if (!empty($search)) {
            $whereClauses[] = "(u.nom LIKE :search OR e.titre LIKE :search)";
            $params['search'] = "%$search%";
        }

        // Event Filter
        if (!empty($eventId)) {
            $whereClauses[] = "i.evenement_id = :event_id";
            $params['event_id'] = $eventId;
        }

        // Combine Clauses
        $whereSql = "";
        if (!empty($whereClauses)) {
            $whereSql = "WHERE " . implode(' AND ', $whereClauses);
        }

        // 1. Main List
        $sql = "SELECT u.id as user_id, u.nom as name, e.titre as event, i.date_inscription as check_in, 'Confirmed' as status 
                FROM inscriptions i 
                JOIN users u ON i.user_id = u.id
                LEFT JOIN evenements e ON i.evenement_id = e.id 
                $whereSql
                ORDER BY i.date_inscription DESC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $attendees = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 2. Stats
        // Total
        $stmtTotal = $pdo->query("SELECT COUNT(*) FROM inscriptions");
        $totalCheckins = $stmtTotal->fetchColumn();

        // Today
        $stmtToday = $pdo->query("SELECT COUNT(*) FROM inscriptions WHERE DATE(date_inscription) = CURDATE()");
        $todayCheckins = $stmtToday->fetchColumn();

        // Busiest Event
        $stmtBusy = $pdo->query("SELECT e.titre, COUNT(i.id) as count FROM inscriptions i JOIN evenements e ON i.evenement_id = e.id GROUP BY i.evenement_id ORDER BY count DESC LIMIT 1");
        $busiestEvent = $stmtBusy->fetch(PDO::FETCH_ASSOC);

        // 3. Dropdown Data
        $stmtEvents = $pdo->query("SELECT id, titre FROM evenements ORDER BY date_evenement DESC");
        $allEvents = $stmtEvents->fetchAll(PDO::FETCH_ASSOC);

        $data = [
            'title' => 'Attendance Lists',
            'current_page' => 'attendance',
            'attendees' => $attendees,
            'search' => $search,
            'selected_event' => $eventId,
            'events_list' => $allEvents,
            'stats' => [
                'total' => $totalCheckins,
                'today' => $todayCheckins,
                'busiest' => $busiestEvent
            ]
        ];
        $this->view('admin/attendance', $data);
    }
    public function export_events() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: /python/public/login');
            exit;
        }

        require '../app/config/database.php';

        // Fetch all events
        $stmt = $pdo->prepare("SELECT * FROM evenements ORDER BY id DESC");
        $stmt->execute();
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Set headers for download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="events_report_' . date('Y-m-d') . '.csv"');

        // Open output stream
        $output = fopen('php://output', 'w');

        // Add CSV headers (removed created_at)
        fputcsv($output, ['ID', 'Title', 'Description', 'Date', 'Location', 'Category ID', 'Max Participants', 'Cover Image', 'Created By', 'Status (0=Open, 1=Closed)']);

        // Add data rows
        foreach ($events as $event) {
            fputcsv($output, [
                $event['id'],
                $event['titre'],
                $event['description'],
                $event['date_evenement'],
                $event['lieu'],
                $event['id_categorie'],
                $event['nb_max_participants'],
                $event['image_cover'],
                $event['id_organisateur'],
                $event['est_cloture']
            ]);
        }

        fclose($output);
        exit;
    }

    // API Endpoint for Modal
    public function get_user_details() {
        if (!isset($_GET['id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }

        require '../app/config/database.php';
        $userId = $_GET['id'];

        // 1. Fetch User Info
        $stmt = $pdo->prepare("SELECT nom as name, email, created_at FROM users WHERE id = :id");
        $stmt->execute(['id' => $userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            echo json_encode(['error' => 'User not found']);
            exit;
        }

        // 2. Fetch Stats
        // Total Inscriptions
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM inscriptions WHERE user_id = :id");
        $stmt->execute(['id' => $userId]);
        $totalInscriptions = $stmt->fetchColumn();

        // Last Event attended (based on inscription date)
        $stmt = $pdo->prepare("
            SELECT e.titre 
            FROM inscriptions i 
            JOIN evenements e ON i.evenement_id = e.id 
            WHERE i.user_id = :id 
            ORDER BY i.date_inscription DESC 
            LIMIT 1
        ");
        $stmt->execute(['id' => $userId]);
        $lastEvent = $stmt->fetchColumn();

        $response = [
            'name' => $user['name'],
            'email' => $user['email'],
            'joined_date' => date('M d, Y', strtotime($user['created_at'])),
            'stats' => [
                'total_inscriptions' => $totalInscriptions,
                'last_event' => $lastEvent ?: 'None'
            ]
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}
