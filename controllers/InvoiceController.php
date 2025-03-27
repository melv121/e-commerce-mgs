<?php
class InvoiceController {
    private $db;
    
    public function __construct() {
        require_once 'config/database.php';
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    // Affiche la liste des factures de l'utilisateur connecté
    public function index() {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            header("Location: " . BASE_URL . "/auth/login");
            exit;
        }
        
        // Vérifier s'il y a des commandes sans facture
        $ordersWithoutInvoices = $this->getOrdersWithoutInvoices($_SESSION['user']['id']);
        $hasOrdersWithoutInvoices = !empty($ordersWithoutInvoices);
        
        $invoices = $this->getUserInvoices($_SESSION['user']['id']);
        $pageTitle = "Mes factures";
        
        require_once 'views/templates/header.php';
        require_once 'views/invoices/index.php';
        require_once 'views/templates/footer.php';
    }
    
    // Affiche une facture spécifique
    public function view($invoiceId) {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            header("Location: " . BASE_URL . "/auth/login");
            exit;
        }
        
        $invoice = $this->getInvoiceById($invoiceId);
        
        // Vérifier si la facture appartient à l'utilisateur connecté
        if (!$invoice || $invoice['user_id'] != $_SESSION['user']['id']) {
            header("Location: " . BASE_URL . "/invoice");
            exit;
        }
        
        $pageTitle = "Facture #" . $invoice['invoice_number'];
        
        require_once 'views/templates/header.php';
        require_once 'views/invoices/view.php';
        require_once 'views/templates/footer.php';
    }
    
    // Télécharger une facture en PDF
    public function download($invoiceId) {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            header("Location: " . BASE_URL . "/auth/login");
            exit;
        }
        
        $invoice = $this->getInvoiceById($invoiceId);
        
        // Vérifier si la facture appartient à l'utilisateur connecté
        if (!$invoice || $invoice['user_id'] != $_SESSION['user']['id']) {
            header("Location: " . BASE_URL . "/invoice");
            exit;
        }
        
        // Générer le PDF et le télécharger
        $this->generatePDF($invoice);
    }
    
    // Génère une facture après une commande
    public function generate($orderId) {
        $order = $this->getOrderById($orderId);
        
        if (!$order) {
            return false;
        }
        
        // Vérifier si une facture existe déjà pour cette commande
        if ($this->invoiceExists($orderId)) {
            return true;
        }
        
        // Générer un numéro de facture unique
        $invoiceNumber = $this->generateInvoiceNumber();
        
        // Créer la facture dans la base de données
        $invoiceId = $this->createInvoice($order, $invoiceNumber);
        
        if ($invoiceId) {
            // Envoyer la facture par email
            $this->sendInvoiceByEmail($invoiceId);
            return true;
        }
        
        return false;
    }
    
    // Récupère les factures d'un utilisateur
    private function getUserInvoices($userId) {
        try {
            $query = "SELECT i.*, o.order_number, o.total_amount 
                      FROM invoices i
                      JOIN orders o ON i.order_id = o.id
                      WHERE o.user_id = ?
                      ORDER BY i.created_at DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des factures: " . $e->getMessage());
            return [];
        }
    }
    
    // Récupère une facture par son ID
    private function getInvoiceById($invoiceId) {
        try {
            $query = "SELECT i.*, o.order_number, o.total_amount, o.created_at as order_date, o.user_id,
                      u.first_name, u.last_name, u.email, u.address, u.city, u.postal_code, u.country, u.phone
                      FROM invoices i
                      JOIN orders o ON i.order_id = o.id
                      JOIN users u ON o.user_id = u.id
                      WHERE i.id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$invoiceId]);
            $invoice = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($invoice) {
                // Récupérer les articles de la commande
                $query = "SELECT oi.*, p.name, p.sku
                          FROM order_items oi
                          JOIN products p ON oi.product_id = p.id
                          WHERE oi.order_id = ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$invoice['order_id']]);
                $invoice['items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            
            return $invoice;
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération de la facture: " . $e->getMessage());
            return false;
        }
    }
    
    // Récupère une commande par son ID
    private function getOrderById($orderId) {
        try {
            $query = "SELECT o.*, u.first_name, u.last_name, u.email, u.address, u.city, u.postal_code, u.country, u.phone
                      FROM orders o
                      JOIN users u ON o.user_id = u.id
                      WHERE o.id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$orderId]);
            $order = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($order) {
                // Récupérer les articles de la commande
                $query = "SELECT oi.*, p.name, p.sku
                          FROM order_items oi
                          JOIN products p ON oi.product_id = p.id
                          WHERE oi.order_id = ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$orderId]);
                $order['items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            
            return $order;
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération de la commande: " . $e->getMessage());
            return false;
        }
    }
    
    // Vérifie si une facture existe déjà pour une commande
    private function invoiceExists($orderId) {
        try {
            $query = "SELECT COUNT(*) FROM invoices WHERE order_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$orderId]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Erreur lors de la vérification de l'existence de la facture: " . $e->getMessage());
            return false;
        }
    }
    
    // Récupère les commandes d'un utilisateur qui n'ont pas de facture
    private function getOrdersWithoutInvoices($userId) {
        try {
            $query = "SELECT o.* FROM orders o
                      LEFT JOIN invoices i ON o.id = i.order_id
                      WHERE o.user_id = ? AND i.id IS NULL";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des commandes sans facture: " . $e->getMessage());
            return [];
        }
    }
    
    // Génère un numéro de facture unique
    private function generateInvoiceNumber() {
        $prefix = 'F' . date('Ymd');
        
        try {
            // Récupérer le dernier numéro de facture avec ce préfixe
            $query = "SELECT invoice_number FROM invoices WHERE invoice_number LIKE ? ORDER BY id DESC LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$prefix . '%']);
            $lastInvoice = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($lastInvoice) {
                // Incrémenter le numéro de série
                $lastNumber = substr($lastInvoice['invoice_number'], strlen($prefix));
                $newNumber = intval($lastNumber) + 1;
                return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
            } else {
                // Première facture du jour
                return $prefix . '0001';
            }
        } catch (PDOException $e) {
            error_log("Erreur lors de la génération du numéro de facture: " . $e->getMessage());
            // Fallback: utiliser timestamp
            return $prefix . rand(1000, 9999);
        }
    }
    
    // Crée une nouvelle facture dans la base de données
    private function createInvoice($order, $invoiceNumber) {
        try {
            $query = "INSERT INTO invoices (order_id, invoice_number, amount, created_at) VALUES (?, ?, ?, NOW())";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$order['id'], $invoiceNumber, $order['total_amount']]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erreur lors de la création de la facture: " . $e->getMessage());
            return false;
        }
    }
    
    // Envoie la facture par email
    private function sendInvoiceByEmail($invoiceId) {
        $invoice = $this->getInvoiceById($invoiceId);
        
        if (!$invoice) {
            return false;
        }
        
        // Générer le contenu HTML de l'email avec la facture
        ob_start();
        include 'views/emails/invoice.php';
        $emailContent = ob_get_clean();
        
        // Entêtes pour l'email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: MGS Store <noreply@mgs-store.com>' . "\r\n";
        
        // Envoyer l'email
        $subject = "Votre facture #" . $invoice['invoice_number'] . " - MGS Store";
        
        mail($invoice['email'], $subject, $emailContent, $headers);
        
        return true;
    }
    
    // Génère un PDF de la facture
    private function generatePDF($invoice) {
        // Inclure la bibliothèque TCPDF
        require_once 'lib/tcpdf/tcpdf.php';
        
        // Créer un nouveau document PDF
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        // Définir les informations du document
        $pdf->SetCreator('MGS Store');
        $pdf->SetAuthor('MGS Store');
        $pdf->SetTitle('Facture #' . $invoice['invoice_number']);
        $pdf->SetSubject('Facture');
        $pdf->SetKeywords('Facture, MGS, Store');
        
        // Supprimer l'en-tête et le pied de page par défaut
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        
        // Définir les marges
        $pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);
        
        // Ajouter une page
        $pdf->AddPage();
        
        // Logo et informations de la société
        $html = '
        <table>
            <tr>
                <td width="40%">
                    <img src="assets/images/logo.png" width="100" />
                </td>
                <td width="60%" style="text-align: right;">
                    <h1>FACTURE</h1>
                    <p>N° ' . $invoice['invoice_number'] . '</p>
                    <p>Date: ' . date('d/m/Y', strtotime($invoice['created_at'])) . '</p>
                </td>
            </tr>
        </table>
        <hr />
        
        <table cellspacing="5" cellpadding="5">
            <tr>
                <td width="50%">
                    <h4>ADRESSE DE FACTURATION</h4>
                    <p>' . $invoice['first_name'] . ' ' . $invoice['last_name'] . '</p>
                    <p>' . $invoice['address'] . '</p>
                    <p>' . $invoice['postal_code'] . ' ' . $invoice['city'] . '</p>
                    <p>' . $invoice['country'] . '</p>
                    <p>Tél: ' . $invoice['phone'] . '</p>
                    <p>Email: ' . $invoice['email'] . '</p>
                </td>
                <td width="50%">
                    <h4>INFORMATIONS SOCIÉTÉ</h4>
                    <p>MGS Store</p>
                    <p>123 Rue du Sport</p>
                    <p>75001 Paris</p>
                    <p>France</p>
                    <p>Tél: 01 23 45 67 89</p>
                    <p>Email: contact@mgs-store.com</p>
                    <p>SIRET: 123 456 789 00012</p>
                </td>
            </tr>
        </table>
        <br />
        
        <table border="1" cellspacing="0" cellpadding="5">
            <tr style="background-color: #f0f0f0;">
                <th width="10%" style="text-align: center;">N°</th>
                <th width="50%">Description</th>
                <th width="10%" style="text-align: right;">Qté</th>
                <th width="15%" style="text-align: right;">Prix Unit.</th>
                <th width="15%" style="text-align: right;">Total</th>
            </tr>';
        
        $i = 1;
        $subtotal = 0;
        
        foreach ($invoice['items'] as $item) {
            $total = $item['price'] * $item['quantity'];
            $subtotal += $total;
            
            $html .= '
            <tr>
                <td style="text-align: center;">' . $i . '</td>
                <td>' . $item['name'] . '</td>
                <td style="text-align: right;">' . $item['quantity'] . '</td>
                <td style="text-align: right;">' . number_format($item['price'], 2, ',', ' ') . ' €</td>
                <td style="text-align: right;">' . number_format($total, 2, ',', ' ') . ' €</td>
            </tr>';
            
            $i++;
        }
        
        $shipping = $subtotal >= 50 ? 0 : 4.99;
        $total = $subtotal + $shipping;
        
        $html .= '
            <tr>
                <td colspan="4" style="text-align: right;"><strong>Sous-total</strong></td>
                <td style="text-align: right;">' . number_format($subtotal, 2, ',', ' ') . ' €</td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: right;"><strong>Livraison</strong></td>
                <td style="text-align: right;">' . number_format($shipping, 2, ',', ' ') . ' €</td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: right;"><strong>TOTAL</strong></td>
                <td style="text-align: right;"><strong>' . number_format($total, 2, ',', ' ') . ' €</strong></td>
            </tr>
        </table>
        <br />
        
        <h4>CONDITIONS DE PAIEMENT</h4>
        <p>Payé le ' . date('d/m/Y', strtotime($invoice['order_date'])) . '</p>
        <p>Méthode de paiement: Carte bancaire</p>
        <br />
        
        <p>Nous vous remercions pour votre confiance.</p>
        <p>Pour toute question concernant cette facture, contactez-nous à contact@mgs-store.com</p>
        ';
        
        // Écrire le HTML dans le PDF
        $pdf->writeHTML($html, true, false, true, false, '');
        
        // Fermer et envoyer le PDF
        $pdf->Output('Facture_' . $invoice['invoice_number'] . '.pdf', 'D');
        exit;
    }
}
?>
