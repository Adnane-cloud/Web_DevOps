<?php

class ProfileController extends Controller {
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /python/public/login');
            exit;
        }

        require '../app/config/database.php';
        $userId = $_SESSION['user_id'];

        // Fetch User Info
        $stmt = $pdo->prepare("SELECT id, nom, email FROM users WHERE id = :id");
        $stmt->execute(['id' => $userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Fetch Stats based on Role
        $stats = [];
        
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
            // Admin Stats: Events, Users, Categories
            $eventCount = $pdo->query("SELECT COUNT(*) FROM evenements")->fetchColumn();
            
            // Corrected: Filter by 'role' column directly
            $userCount = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetchColumn(); 
            
            
            $categoryCount = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();

            $stats = [
                'events' => $eventCount,
                'users' => $userCount, 
                'categories' => $categoryCount
            ];
        } else {
            // User Stats: My Inscriptions
            $countStmt = $pdo->prepare("SELECT COUNT(*) FROM inscriptions WHERE user_id = :id");
            $countStmt->execute(['id' => $userId]);
            $stats = [
                'inscriptions' => $countStmt->fetchColumn()
            ];
        }

        $data = [
            'title' => 'Account Settings',
            'user' => $user,
            'stats' => $stats
        ];

        $this->view('profile/index', $data);
    }

    public function update() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /python/public/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require '../app/config/database.php';
            $userId = $_SESSION['user_id'];
            
            $name = trim($_POST['name']);
            $password = $_POST['password'];
            $oldPassword = $_POST['old_password'];

            if (!empty($name)) {
                // Update Name
                $stmt = $pdo->prepare("UPDATE users SET nom = :name WHERE id = :id");
                $stmt->execute(['name' => $name, 'id' => $userId]);
                
                // Update Session
                $_SESSION['user_name'] = $name;
            }

            if (!empty($password)) {
                // Verify Old Password
                if (empty($oldPassword)) {
                    header('Location: /python/public/profile?error=' . urlencode('Current password is required to set a new password.'));
                    exit;
                }

                $stmt = $pdo->prepare("SELECT mot_de_passe FROM users WHERE id = :id");
                $stmt->execute(['id' => $userId]);
                $currentHash = $stmt->fetchColumn();

                if (!password_verify($oldPassword, $currentHash) && $oldPassword !== $currentHash) {
                    header('Location: /python/public/profile?error=' . urlencode('Incorrect current password.'));
                    exit;
                }

                // Update Password
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET mot_de_passe = :pass WHERE id = :id");
                $stmt->execute(['pass' => $hashed, 'id' => $userId]);
            }

            // Redirect back
            header('Location: /python/public/profile?updated=1');
            exit;
        }
    }
}
