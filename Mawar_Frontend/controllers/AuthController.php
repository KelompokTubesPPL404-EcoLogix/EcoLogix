<?php
session_start();

class AuthController {
    private $db;

    public function __construct() {
        try {
            $this->db = new PDO(
                "mysql:host=localhost;dbname=laravel",
                "root",
                "",
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
            );
        } catch(PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function login($email, $password) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                return true;
            }
            return false;
        } catch(PDOException $e) {
            return false;
        }
    }
}

// Handle login request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth = new AuthController();
    
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($auth->login($email, $password)) {
        header('Location: /dashboard');
        exit();
    } else {
        $_SESSION['error'] = "Invalid email or password";
        header('Location: /login');
        exit();
    }
}