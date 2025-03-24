<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center mb-5">
                <div class="confirmation-icon">
                    <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
                </div>
                <h1 class="mt-4">Merci pour votre commande!</h1>
                <p class="lead">Votre commande #<?php echo $order['id']; ?> a été placée avec succès.</p>
            </div>
            
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Détails de la commande</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Numéro de commande:</strong></p>
                            <p>#<?php echo $order['id']; ?></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Date:</strong></p>
                            <p><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></p>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Total:</strong></p>
                            <p><?php echo number_format($order['total_amount'], 2, ',', ' '); ?> €</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Mode de paiement:</strong></p>
                            <p>
                                <?php 
                                switch($order['payment_method']) {
                                    case 'credit_card':
                                        echo 'Carte de crédit';
                                        break;
                                    case 'paypal':
                                        echo 'PayPal';
                                        break;
                                    case 'apple_pay':
                                        echo 'Apple Pay';
                                        break;
                                    case 'google_pay':
                                        echo 'Google Pay';
                                        break;
                                    default:
                                        echo $order['payment_method'];
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Statut:</strong></p>
                            <p>
                                <span class="badge bg-success">
                                    <?php 
                                    switch($order['status']) {
                                        case 'pending':
                                            echo 'En attente';
                                            break;
                                        case 'processing':
                                            echo 'En cours de traitement';
                                            break;
                                        case 'shipped':
                                            echo 'Expédié';
                                            break;
                                        case 'delivered':
                                            echo 'Livré';
                                            break;
                                        default:
                                            echo $order['status'];
                                    }
                                    ?>
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Informations de livraison</h5>
                </div>
                <div class="card-body">
                    <p>Un e-mail de confirmation a été envoyé à <strong><?php echo $order['email'] ?? 'votre adresse e-mail'; ?></strong>.</p>
                    <p>Votre commande sera expédiée dans les 24 à 48 heures ouvrables.</p>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <a href="<?php echo BASE_URL; ?>" class="btn btn-primary me-2">Continuer mes achats</a>
                <a href="#" class="btn btn-outline-secondary">Suivre ma commande</a>
            </div>
        </div>
    </div>
</div>
