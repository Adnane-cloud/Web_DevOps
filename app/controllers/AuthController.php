<?php

class AuthController extends Controller {
    public function login() {
        $data = ['title' => 'Sign In - Eventium'];
        $this->view('auth/login', $data);
    }

    public function processLogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            // Simple validation
            if (empty($email) || empty($password)) {
                $data = ['error' => 'Please enter both email and password.'];
                $this->view('auth/login', $data);
                return;
            }

            // Database Connection
            require '../app/config/database.php';
            // $pdo is now available

            try {
                // Check user
                $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
                $stmt->execute(['email' => $email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user) {
                    $password = $_POST['password']; 
                    $db_password = $user['mot_de_passe'];

                    // Password verification (Support both plain text and hash)
                    if ($password === $db_password || password_verify($password, $db_password)) {
                        // Login Success
                        // Session is already started in index.php
                        session_regenerate_id(true); // Prevent session fixation

                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['user_name'] = $user['nom'];
                        $_SESSION['user_email'] = $user['email'];
                        $_SESSION['user_role'] = $user['role'];
                        
                        session_write_close(); // Force save before redirect

                        if ($_SESSION['user_role'] === 'admin') {
                            if (!headers_sent()) {
                                header('Location: /python/public/admin');
                                exit;
                            } else {
                                echo '<script>window.location.href="/python/public/admin";</script>';
                                exit;
                            }
                        } else {
                            if (!headers_sent()) {
                                header('Location: /python/public/menu');
                                exit;
                            } else {
                                echo '<script>window.location.href="/python/public/menu";</script>';
                                exit;
                            }
                        }
                    } else {
                        $data = ['error' => 'Invalid password.'];
                        $this->view('auth/login', $data);
                    }
                } else {
                    $data = ['error' => 'No account found with this email.'];
                    $this->view('auth/login', $data);
                }
            } catch (Exception $e) {
                // In production, log this error instead of showing it
                $data = ['error' => 'System error. Please try again later.'];
                $this->view('auth/login', $data);
            }
        
        } else {
            // Redirect if accessed directly via GET
            header('Location: /python/public/login');
            exit;
        }
    }

    public function register() {
        $data = [
            'title' => 'Create Your Account - Eventium',
        ];
        $this->view('auth/register', $data);
    }

    public function processRegister() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            var_dump($_POST);
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            // Validation
            if (empty($name) || empty($email) || empty($password)) {
                $data = ['error' => 'All fields are required.'];
                $this->view('auth/register', $data);
                return;
            }

            if ($password !== $confirm_password) {
                $data = ['error' => 'Passwords do not match.'];
                $this->view('auth/register', $data);
                return;
            }

            // Database Connection
            require '../app/config/database.php';

            try {
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                
                // Check if email already exists
                $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
                $stmt->execute(['email' => $email]);
                
                if ($stmt->fetch()) {
                    $data = ['error' => 'This email is already registered.'];
                    $this->view('auth/register', $data);
                    return;
                }
                // Insert User
                // WARNING: Storing plain text password as requested by user. NOT SECURE.
                // $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                $sql = "INSERT INTO users (nom, email, mot_de_passe, role) VALUES (:name, :email, :password, 'user')";
                $stmt = $pdo->prepare($sql);
                
                if ($stmt->execute([
                    'name' => $name, 
                    'email' => $email, 
                    'password' => $password 
                ])) {
                    // Success - Redirect to Login
                    if (!headers_sent()) {
                        header('Location: /python/public/login');
                        exit;
                    } else {
                        echo '<script>window.location.href="/python/public/login";</script>';
                        exit;
                    }
                } else {
                    $data = ['error' => 'Registration failed. Please try again.'];
                    $this->view('auth/register', $data);
                }
            } catch (Exception $e) {
                $data = ['error' => 'System error. ' . $e->getMessage()];
                $this->view('auth/register', $data);
            }
        } else {
            header('Location: /python/public/register');
            exit;
        }
    }
    public function logout() {
        session_start(); // Ensure session is started before destroying
        $_SESSION = array(); // Clear all session variables

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();
        
        header('Location: /python/public/login');
        exit;
    }
}
