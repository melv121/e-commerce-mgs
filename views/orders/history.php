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
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Historique des commandes</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($orders)): ?>
                        <div class="alert alert-info">
                            Vous n'avez pas encore passé de commande.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Commande</th>
                                        <th>Date</th>
                                        <th>Montant</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orders as $order): ?>
                                        <tr>
                                            <td><?php echo isset($order['order_number']) ? $order['order_number'] : '#' . $order['id']; ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($order['created_at'])); ?></td>
                                            <td><?php echo number_format($order['total_amount'], 2, ',', ' '); ?> €</td>
                                            <td>
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
                                            </td>
                                            <td>
                                                <a href="<?php echo BASE_URL; ?>/order/detail/<?php echo $order['id']; ?>" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i> Voir
                                                </a>
                                                <?php if ($order['status'] === 'pending' || $order['status'] === 'processing'): ?>
                                                    <a href="<?php echo BASE_URL; ?>/order/cancel/<?php echo $order['id']; ?>" class="btn btn-sm btn-danger ms-1" onclick="return confirm('Êtes-vous sûr de vouloir annuler cette commande ?');">
                                                        <i class="fas fa-times"></i> Annuler
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
