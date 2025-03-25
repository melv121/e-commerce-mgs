<?php
class AuthController {
    private $db;
    
    public function __construct() {
        require_once 'config/database.php';
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    public function index() {
        // Rediriger vers la page de connexion
        header("Location: " . BASE_URL . "/auth/login");
        exit;
    }
    
    public function login() {
        // Si l'utilisateur est déjà connecté, rediriger vers la page d'accueil
        if (isset($_SESSION['user'])) {
            header("Location: " . BASE_URL);
            exit;
        }
        
        $pageTitle = "Connexion";
        
        require_once 'views/templates/header.php';
        require_once 'views/auth/login.php';
        require_once 'views/templates/footer.php';
    }
    
    public function register() {
        // Si l'utilisateur est déjà connecté, rediriger vers la page d'accueil
        if (isset($_SESSION['user'])) {
            header("Location: " . BASE_URL);
            exit;
        }
        
        $pageTitle = "Créer un compte";
        
        require_once 'views/templates/header.php';
        require_once 'views/auth/register.php';
        require_once 'views/templates/footer.php';
    }
    
    public function processLogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            // Validation
            $errors = [];
            
            if (empty($email)) {
                $errors[] = "L'adresse email est requise";
            }
            
            if (empty($password)) {
                $errors[] = "Le mot de passe est requis";
            }
            
            if (!empty($errors)) {
                $_SESSION['auth_errors'] = $errors;
                header("Location: " . BASE_URL . "/auth/login");
                exit;
            }
            
            // Authentification
            try {
                $query = "SELECT * FROM users WHERE email = ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($user && password_verify($password, $user['password'])) {
                    // Authentification réussie
                    
                    // Supprimer le mot de passe pour la session
                    unset($user['password']);
                    
                    // Stocker les informations de l'utilisateur dans la session
                    $_SESSION['user'] = $user;
                    
                    // Transférer les articles du panier anonyme
                    if (isset($_SESSION['temp_cart'])) {
                        // Votre code pour transférer le panier
                        unset($_SESSION['temp_cart']);
                    }
                    
                    // Rediriger vers la page d'accueil ou la dernière page visitée
                    $redirect = $_SESSION['redirect_after_login'] ?? BASE_URL;
                    unset($_SESSION['redirect_after_login']);
                    
                    header("Location: " . $redirect);
                    exit;
                } else {
                    // Authentification échouée
                    $_SESSION['auth_errors'] = ["Email ou mot de passe incorrect"];
                    header("Location: " . BASE_URL . "/auth/login");
                    exit;
                }
            } catch (PDOException $e) {
                $_SESSION['auth_errors'] = ["Une erreur s'est produite, veuillez réessayer plus tard"];
                error_log("Erreur d'authentification: " . $e->getMessage());
                header("Location: " . BASE_URL . "/auth/login");
                exit;
            }
        } else {
            header("Location: " . BASE_URL . "/auth/login");
            exit;
        }
    }
    
    public function processRegister() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $passwordConfirm = $_POST['password_confirm'] ?? '';
            $firstName = $_POST['first_name'] ?? '';
            $lastName = $_POST['last_name'] ?? '';
            
            // Validation
            $errors = [];
            
            if (empty($username)) {
                $errors[] = "Le nom d'utilisateur est requis";
            }
            
            if (empty($email)) {
                $errors[] = "L'adresse email est requise";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "L'adresse email n'est pas valide";
            }
            
            if (empty($password)) {
                $errors[] = "Le mot de passe est requis";
            } elseif (strlen($password) < 6) {
                $errors[] = "Le mot de passe doit contenir au moins 6 caractères";
            }
            
            if ($password !== $passwordConfirm) {
                $errors[] = "Les mots de passe ne correspondent pas";
            }
            
            // Vérifier si l'email ou le nom d'utilisateur existe déjà
            try {
                $query = "SELECT COUNT(*) FROM users WHERE email = ? OR username = ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$email, $username]);
                $count = $stmt->fetchColumn();
                
                if ($count > 0) {
                    $errors[] = "L'adresse email ou le nom d'utilisateur est déjà utilisé";
                }
            } catch (PDOException $e) {
                error_log("Erreur lors de la vérification de l'email/username: " . $e->getMessage());
                $errors[] = "Une erreur s'est produite, veuillez réessayer plus tard";
            }
            
            if (!empty($errors)) {
                $_SESSION['auth_errors'] = $errors;
                $_SESSION['form_data'] = [
                    'username' => $username,
                    'email' => $email,
                    'first_name' => $firstName,
                    'last_name' => $lastName
                ];
                header("Location: " . BASE_URL . "/auth/register");
                exit;
            }
            
            // Insertion dans la base de données
            try {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                
                $query = "INSERT INTO users (username, email, password, first_name, last_name, role, created_at) 
                          VALUES (?, ?, ?, ?, ?, 'client', NOW())";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$username, $email, $hashedPassword, $firstName, $lastName]);
                
                // Récupérer l'ID de l'utilisateur inséré
                $userId = $this->db->lastInsertId();
                
                // Récupérer toutes les informations de l'utilisateur
                $query = "SELECT * FROM users WHERE id = ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$userId]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Supprimer le mot de passe pour la session
                unset($user['password']);
                
                // Stocker les informations de l'utilisateur dans la session
                $_SESSION['user'] = $user;
                
                // Rediriger vers la page d'accueil
                $_SESSION['success_message'] = "Votre compte a été créé avec succès";
                header("Location: " . BASE_URL);
                exit;
            } catch (PDOException $e) {
                error_log("Erreur lors de l'inscription: " . $e->getMessage());
                $_SESSION['auth_errors'] = ["Une erreur s'est produite lors de la création de votre compte"];
                header("Location: " . BASE_URL . "/auth/register");
                exit;
            }
        } else {
            header("Location: " . BASE_URL . "/auth/register");
            exit;
        }
    }
    
    public function logout() {
        // Détruire toutes les données de session
        session_unset();
        session_destroy();
        
        // Redémarrer la session pour les messages
        session_start();
        $_SESSION['success_message'] = "Vous avez été déconnecté avec succès";
        
        // Rediriger vers la page d'accueil
        header("Location: " . BASE_URL);
        exit;
    }
    
    public function profile() {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            $_SESSION['redirect_after_login'] = BASE_URL . "/auth/profile";
            header("Location: " . BASE_URL . "/auth/login");
            exit;
        }
        
        $pageTitle = "Mon profil";
        $user = $_SESSION['user'];
        
        require_once 'views/templates/header.php';
        require_once 'views/auth/profile.php';
        require_once 'views/templates/footer.php';
    }
    
    public function updateProfile() {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            header("Location: " . BASE_URL . "/auth/login");
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user']['id'];
            $firstName = $_POST['first_name'] ?? '';
            $lastName = $_POST['last_name'] ?? '';
            $address = $_POST['address'] ?? '';
            $city = $_POST['city'] ?? '';
            $postalCode = $_POST['postal_code'] ?? '';
            $country = $_POST['country'] ?? '';
            $phone = $_POST['phone'] ?? '';
            
            try {
                $query = "UPDATE users SET 
                          first_name = ?, 
                          last_name = ?, 
                          address = ?, 
                          city = ?, 
                          postal_code = ?, 
                          country = ?, 
                          phone = ?,
                          updated_at = NOW()
                          WHERE id = ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([
                    $firstName, $lastName, $address, $city, 
                    $postalCode, $country, $phone, $userId
                ]);
                
                // Mettre à jour les informations de l'utilisateur dans la session
                $query = "SELECT * FROM users WHERE id = ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$userId]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Supprimer le mot de passe pour la session
                unset($user['password']);
                
                // Mettre à jour les informations de l'utilisateur dans la session
                $_SESSION['user'] = $user;
                
                $_SESSION['success_message'] = "Votre profil a été mis à jour avec succès";
                header("Location: " . BASE_URL . "/auth/profile");
                exit;
            } catch (PDOException $e) {
                error_log("Erreur lors de la mise à jour du profil: " . $e->getMessage());
                $_SESSION['auth_errors'] = ["Une erreur s'est produite lors de la mise à jour de votre profil"];
                header("Location: " . BASE_URL . "/auth/profile");
                exit;
            }
        } else {
            header("Location: " . BASE_URL . "/auth/profile");
            exit;
        }
    }
}
?>
