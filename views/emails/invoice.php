<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-bottom: 3px solid #ff0000;
        }
        .header img {
            max-width: 150px;
        }
        .content {
            padding: 20px;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 15px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .btn {
            display: inline-block;
            background-color: #ff0000;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 4px;
            margin-top: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="<?php echo BASE_URL; ?>/assets/images/logo.png" alt="MGS Store">
        </div>
        <div class="content">
            <h2>Votre facture est disponible</h2>
            <p>Bonjour <?php echo $invoice['first_name']; ?>,</p>
            <p>Nous vous remercions pour votre commande chez MGS Store. Veuillez trouver ci-dessous les détails de votre facture.</p>
            
            <table>
                <tr>
                    <th>Numéro de facture:</th>
                    <td><?php echo $invoice['invoice_number']; ?></td>
                </tr>
                <tr>
                    <th>Numéro de commande:</th>
                    <td><?php echo $invoice['order_number']; ?></td>
                </tr>
                <tr>
                    <th>Date:</th>
                    <td><?php echo date('d/m/Y', strtotime($invoice['created_at'])); ?></td>
                </tr>
                <tr>
                    <th>Montant total:</th>
                    <td><?php echo number_format($invoice['amount'], 2, ',', ' '); ?> €</td>
                </tr>
            </table>
            
            <p>Vous pouvez consulter et télécharger votre facture complète en vous connectant à votre compte sur notre site web.</p>
            
            <a href="<?php echo BASE_URL; ?>/invoice/view/<?php echo $invoice['id']; ?>" class="btn">Voir ma facture</a>
            
            <p>Si vous avez des questions concernant votre commande ou votre facture, n'hésitez pas à nous contacter.</p>
            
            <p>Cordialement,<br>L'équipe MGS Store</p>
        </div>
        <div class="footer">
            <p>© <?php echo date('Y'); ?> MGS Store. Tous droits réservés.</p>
            <p>123 Rue du Sport, 75001 Paris, France</p>
        </div>
    </div>
</body>
</html>
