<?php
class Database {
    private $host = "localhost";
    private $db_name = "mgs_store";
    private $username = "root";
    private $password = "";
    private $conn;
    
    public function getConnection() {
        $this->conn = null;
        
        try {
            // Vérifier si la base de données existe
            $tempConn = new PDO("mysql:host=" . $this->host, $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
            
            // Vérifier si la base de données existe
            $dbCheck = $tempConn->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$this->db_name'");
            if ($dbCheck->rowCount() == 0) {
                // La base de données n'existe pas, la créer
                $tempConn->exec("CREATE DATABASE IF NOT EXISTS `$this->db_name` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
                echo "<div style='background: #d4edda; color: #155724; padding: 15px; margin-bottom: 20px; border: 1px solid #c3e6cb; border-radius: 4px;'>
                    Base de données '$this->db_name' créée avec succès.
                </div>";
            }
            
            // Se connecter à la base de données
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);
            $this->conn->exec("set names utf8mb4");
            
        } catch(PDOException $exception) {
            // Message d'erreur amélioré
            $errorMessage = "Erreur de connexion à la base de données: " . $exception->getMessage();
            error_log($errorMessage);
            
            echo "<div style='background: #f8d7da; color: #721c24; padding: 15px; margin-bottom: 20px; border: 1px solid #f5c6cb; border-radius: 4px;'>
                <h3>Erreur de connexion à la base de données</h3>
                <p>$errorMessage</p>
                <p><strong>Vérifiez que:</strong></p>
                <ul>
                    <li>Le serveur MySQL est en cours d'exécution</li>
                    <li>Les informations de connexion dans config/database.php sont correctes</li>
                    <li>La base de données '$this->db_name' existe</li>
                </ul>
            </div>";
        }
        
        return $this->conn;
    }
}
?>
