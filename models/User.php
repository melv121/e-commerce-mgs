<?php
class User {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    // Vérifie les informations d'identification de l'utilisateur
    public function login($email, $password) {
        try {
            $query = "SELECT * FROM users WHERE email = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user && password_verify($password, $user['password'])) {
                // Supprimer le mot de passe de l'objet utilisateur
                unset($user['password']);
                return $user;
            }
            
            return false;
        } catch (PDOException $e) {
            error_log("Erreur lors de la connexion: " . $e->getMessage());
            return false;
        }
    }
    
    // Enregistre un nouvel utilisateur
    public function register($userData) {
        try {
            // Vérifier si l'email existe déjà
            $emailCheck = "SELECT COUNT(*) FROM users WHERE email = ?";
            $stmt = $this->db->prepare($emailCheck);
            $stmt->execute([$userData['email']]);
            if ($stmt->fetchColumn() > 0) {
                return ['success' => false, 'message' => 'Cet email est déjà utilisé.'];
            }
            
            // Vérifier si le nom d'utilisateur existe déjà
            $usernameCheck = "SELECT COUNT(*) FROM users WHERE username = ?";
            $stmt = $this->db->prepare($usernameCheck);
            $stmt->execute([$userData['username']]);
            if ($stmt->fetchColumn() > 0) {
                return ['success' => false, 'message' => 'Ce nom d\'utilisateur est déjà utilisé.'];
            }
            
            // Si le rôle n'est pas spécifié, définir comme 'client'
            if (!isset($userData['role']) || empty($userData['role'])) {
                $userData['role'] = 'client';
            }
            
            // Hasher le mot de passe
            $hashedPassword = password_hash($userData['password'], PASSWORD_DEFAULT);
            
            // Insérer l'utilisateur
            $query = "INSERT INTO users (username, email, password, first_name, last_name, role, created_at) 
                      VALUES (?, ?, ?, ?, ?, ?, NOW())";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                $userData['username'],
                $userData['email'],
                $hashedPassword,
                $userData['first_name'] ?? '',
                $userData['last_name'] ?? '',
                $userData['role']
            ]);
            
            // Retourner l'ID de l'utilisateur créé
            return ['success' => true, 'user_id' => $this->db->lastInsertId()];
        } catch (PDOException $e) {
            error_log("Erreur lors de l'enregistrement: " . $e->getMessage());
            return ['success' => false, 'message' => 'Une erreur est survenue lors de l\'enregistrement.'];
        }
    }
    
    // Crée un compte administrateur s'il n'existe pas déjà
    public function createAdminIfNotExists() {
        try {
            // Vérifier si un administrateur existe déjà
            $query = "SELECT COUNT(*) FROM users WHERE role = 'admin'";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            
            if ($stmt->fetchColumn() == 0) {
                // Aucun administrateur trouvé, en créer un
                $adminData = [
                    'username' => 'admin',
                    'email' => 'admin@mgs-store.com',
                    'password' => password_hash('admin123', PASSWORD_DEFAULT),
                    'first_name' => 'Admin',
                    'last_name' => 'MGS',
                    'role' => 'admin'
                ];
                
                $query = "INSERT INTO users (username, email, password, first_name, last_name, role, created_at) 
                          VALUES (?, ?, ?, ?, ?, ?, NOW())";
                $stmt = $this->db->prepare($query);
                $stmt->execute([
                    $adminData['username'],
                    $adminData['email'],
                    $adminData['password'],
                    $adminData['first_name'],
                    $adminData['last_name'],
                    $adminData['role']
                ]);
                
                return true;
            }
            
            return false;
        } catch (PDOException $e) {
            error_log("Erreur lors de la création de l'administrateur: " . $e->getMessage());
            return false;
        }
    }
}
?>
