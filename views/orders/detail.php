<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Mon compte</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="<?php echo BASE_URL; ?>/auth/profile" class="list-group-item list-group-item-action">
                        <i class="fas fa-user me-2"></i> Mon profil
                    </a>
                    <a href="<?php echo BASE_URL; ?>/order/history" class="list-group-item list-group-item-action active">
                        <i class="fas fa-shopping-bag me-2"></i> Mes commandes
                    </a>
                    <a href="<?php echo BASE_URL; ?>/invoice" class="list-group-item list-group-item-action">
                        <i class="fas fa-file-invoice-dollar me-2"></i> Mes factures
                    </a>
                    <a href="<?php echo BASE_URL; ?>/auth/logout" class="list-group-item list-group-item-action text-danger">
                        <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger">
                    <?php echo $_SESSION['error_message']; ?>
                </div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success">
                    <?php echo $_SESSION['success_message']; ?>
                </div>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>
            
            <div class="card mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Détail de la commande <?php echo isset($order['order_number']) ? $order['order_number'] : '#' . $order['id']; ?></h5>
                    <a href="<?php echo BASE_URL; ?>/order/history" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="mb-3">Informations de commande</h6>
                            <p><strong>Date:</strong> <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></p>
                            <p>
                                <strong>Statut:</strong> 
                                <?php 
                                $statusClass = 'secondary';
                                $statusText = 'Inconnu';
                                
                                switch($order['status']) {
                                    case 'pending':
                                        $statusClass = 'warning';
                                        $statusText = 'En attente';
                                        break;
                                    case 'processing':
                                        $statusClass = 'info';
                                        $statusText = 'En traitement';
                                        break;
                                    case 'shipped':
                                        $statusClass = 'primary';
                                        $statusText = 'Expédiée';
                                        break;
                                    case 'completed':
                                        $statusClass = 'success';
                                        $statusText = 'Complétée';
                                        break;
                                    case 'cancelled':
                                        $statusClass = 'danger';
                                        $statusText = 'Annulée';
                                        break;
                                }
                                ?>
                                <span class="badge bg-<?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                            </p>
                            <p><strong>Méthode de paiement:</strong> <?php echo ucfirst($order['payment_method']); ?></p>
                            <p><strong>Total:</strong> <?php echo number_format($order['total_amount'], 2, ',', ' '); ?> €</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3">Adresse de livraison</h6>
                            <p><?php echo $order['first_name'] . ' ' . $order['last_name']; ?></p>
                            <p><?php echo $order['address']; ?></p>
                            <p><?php echo $order['postal_code'] . ' ' . $order['city']; ?></p>
                            <p><?php echo $order['country']; ?></p>
                            <p><?php echo $order['phone']; ?></p>
                        </div>
                    </div>
                    
                    <h6 class="mb-3">Articles commandés</h6>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th>Prix unitaire</th>
                                    <th>Quantité</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $subtotal = 0;
                                foreach ($order['items'] as $item): 
                                    $itemTotal = $item['price'] * $item['quantity'];
                                    $subtotal += $itemTotal;
                                ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if(isset($item['image'])): ?>
                                                <img src="<?php echo BASE_URL . '/' . $item['image']; ?>" alt="<?php echo $item['name']; ?>" class="me-2" style="width: 50px; height: 50px; object-fit: cover;">
                                            <?php endif; ?>
                                            <div>
                                                <?php echo $item['name']; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?php echo number_format($item['price'], 2, ',', ' '); ?> €</td>
                                    <td><?php echo $item['quantity']; ?></td>
                                    <td><?php echo number_format($itemTotal, 2, ',', ' '); ?> €</td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Sous-total</strong></td>
                                    <td><?php echo number_format($subtotal, 2, ',', ' '); ?> €</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Livraison</strong></td>
                                    <td>
                                        <?php 
                                        $shipping = $subtotal >= 50 ? 0 : 4.99;
                                        echo $shipping > 0 ? number_format($shipping, 2, ',', ' ') . ' €' : 'Gratuit';
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total</strong></td>
                                    <td><strong><?php echo number_format($order['total_amount'], 2, ',', ' '); ?> €</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <?php if ($order['status'] === 'pending' || $order['status'] === 'processing'): ?>
                        <div class="mt-4">
                            <a href="<?php echo BASE_URL; ?>/order/cancel/<?php echo $order['id']; ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir annuler cette commande ?');">
                                <i class="fas fa-times"></i> Annuler la commande
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
