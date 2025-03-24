<?php
class AuthController {
    private $db;
    
    public function __construct() {
        require_once 'config/database.php';
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function login() {
        $errors = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            if ($this->validateLogin($email, $password)) {
                $user = $this->getUserByEmail($email);
                $_SESSION['user'] = $user;
                header('Location: ' . BASE_URL . '/account');
                exit;
            } else {
                $errors[] = "Email ou mot de passe incorrect";
            }
        }
        
        $pageTitle = "Connexion";
        require_once 'views/templates/header.php';
        require_once 'views/auth/login.php';
        require_once 'views/templates/footer.php';
    }

    public function register() {
        $errors = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validation et création du compte
            if ($this->validateRegistration($_POST)) {
                $this->createUser($_POST);
                header('Location: ' . BASE_URL . '/auth/login?registered=1');
                exit;
            }
        }
        
        $pageTitle = "Création de compte";
        require_once 'views/templates/header.php';
        require_once 'views/auth/register.php';
        require_once 'views/templates/footer.php';
    }

    private function validateLogin($email, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            return true;
        }
        return false;
    }

}
