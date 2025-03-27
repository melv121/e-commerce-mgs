<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4">Détail de la commande #<?php echo $order['id']; ?></h1>
        <a href="<?php echo BASE_URL; ?>/admin/orders" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-info-circle me-1"></i>
                    Informations de la commande
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Numéro de commande:</strong> 
                        <?php echo isset($order['order_number']) ? $order['order_number'] : $order['id']; ?>
                    </div>
                    <div class="mb-3">
                        <strong>Date de commande:</strong> 
                        <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?>
                    </div>
                    <div class="mb-3">
                        <strong>Montant total:</strong> 
                        <?php echo number_format($order['total_amount'], 2, ',', ' '); ?> €
                    </div>
                    <div class="mb-3">
                        <strong>Méthode de paiement:</strong> 
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
                    </div>
                    <div class="mb-3">
                        <strong>Statut actuel:</strong> 
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
                    </div>
                </div>
                <div class="card-footer">
                    <form action="<?php echo BASE_URL; ?>/admin/updateOrderStatus/<?php echo $order['id']; ?>" method="post" class="d-flex align-items-center">
                        <label for="status" class="me-2">Modifier le statut:</label>
                        <select name="status" id="status" class="form-select me-2">
                            <option value="pending" <?php echo $order['status'] === 'pending' ? 'selected' : ''; ?>>En attente</option>
                            <option value="processing" <?php echo $order['status'] === 'processing' ? 'selected' : ''; ?>>En traitement</option>
                            <option value="shipped" <?php echo $order['status'] === 'shipped' ? 'selected' : ''; ?>>Expédiée</option>
                            <option value="completed" <?php echo $order['status'] === 'completed' ? 'selected' : ''; ?>>Complétée</option>
                            <option value="cancelled" <?php echo $order['status'] === 'cancelled' ? 'selected' : ''; ?>>Annulée</option>
                        </select>
                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-user me-1"></i>
                    Informations client
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Nom:</strong> 
                        <?php echo $order['first_name'] . ' ' . $order['last_name']; ?>
                    </div>
                    <div class="mb-3">
                        <strong>Email:</strong> 
                        <?php echo $order['email']; ?>
                    </div>
                    <div class="mb-3">
                        <strong>Adresse:</strong> 
                        <?php echo $order['address']; ?>
                    </div>
                    <div class="mb-3">
                        <strong>Ville:</strong> 
                        <?php echo $order['city']; ?>
                    </div>
                    <div class="mb-3">
                        <strong>Code postal:</strong> 
                        <?php echo $order['postal_code']; ?>
                    </div>
                    <div class="mb-3">
                        <strong>Pays:</strong> 
                        <?php echo $order['country']; ?>
                    </div>
                    <div class="mb-3">
                        <strong>Téléphone:</strong> 
                        <?php echo $order['phone']; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-shopping-cart me-1"></i>
            Articles commandés
        </div>
        <div class="card-body">
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
                        <?php if(!empty($order['items'])): ?>
                            <?php 
                            $subtotal = 0;
                            foreach($order['items'] as $item): 
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
                                                <?php if(isset($item['sku'])): ?>
                                                    <div class="small text-muted">SKU: <?php echo $item['sku']; ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?php echo number_format($item['price'], 2, ',', ' '); ?> €</td>
                                    <td><?php echo $item['quantity']; ?></td>
                                    <td><?php echo number_format($itemTotal, 2, ',', ' '); ?> €</td>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Sous-total:</strong></td>
                                <td><?php echo number_format($subtotal, 2, ',', ' '); ?> €</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Livraison:</strong></td>
                                <td>
                                    <?php 
                                    $shipping = $subtotal >= 50 ? 0 : 4.99;
                                    echo $shipping > 0 ? number_format($shipping, 2, ',', ' ') . ' €' : 'Gratuit';
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                <td><strong><?php echo number_format($order['total_amount'], 2, ',', ' '); ?> €</strong></td>
                            </tr>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">Aucun article trouvé pour cette commande</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="mb-4">
        <a href="<?php echo BASE_URL; ?>/admin/orders" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
        
        <?php if($order['status'] !== 'cancelled'): ?>
            <button type="button" class="btn btn-outline-danger ms-2" data-bs-toggle="modal" data-bs-target="#cancelOrderModal">
                <i class="fas fa-times"></i> Annuler la commande
            </button>
        <?php endif; ?>
        
        <!-- Modal pour confirmer l'annulation -->
        <div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cancelOrderModalLabel">Confirmer l'annulation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Êtes-vous sûr de vouloir annuler cette commande ? Cette action ne peut pas être annulée.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <form action="<?php echo BASE_URL; ?>/admin/updateOrderStatus/<?php echo $order['id']; ?>" method="post">
                            <input type="hidden" name="status" value="cancelled">
                            <button type="submit" class="btn btn-danger">Confirmer l'annulation</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
