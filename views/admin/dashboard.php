<div class="container-fluid px-4">
    <h1 class="mt-4 mb-4">Tableau de bord</h1>
    
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card mb-4 dashboard-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-0">Produits</h5>
                            <p class="card-text display-6"><?php echo $stats['total_products']; ?></p>
                        </div>
                        <div class="icon-container bg-primary">
                            <i class="fas fa-box"></i>
                        </div>
                    </div>
                    <a href="<?php echo BASE_URL; ?>/admin/products" class="btn btn-sm btn-outline-primary mt-3">Gérer les produits</a>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card mb-4 dashboard-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-0">Commandes</h5>
                            <p class="card-text display-6"><?php echo $stats['total_orders']; ?></p>
                        </div>
                        <div class="icon-container bg-success">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                    </div>
                    <a href="<?php echo BASE_URL; ?>/admin/orders" class="btn btn-sm btn-outline-success mt-3">Gérer les commandes</a>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card mb-4 dashboard-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-0">Utilisateurs</h5>
                            <p class="card-text display-6"><?php echo $stats['total_users']; ?></p>
                        </div>
                        <div class="icon-container bg-info">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <a href="<?php echo BASE_URL; ?>/admin/users" class="btn btn-sm btn-outline-info mt-3">Gérer les utilisateurs</a>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card mb-4 dashboard-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-0">Revenus</h5>
                            <p class="card-text display-6"><?php echo number_format($stats['total_revenue'], 2, ',', ' '); ?> €</p>
                        </div>
                        <div class="icon-container bg-warning">
                            <i class="fas fa-euro-sign"></i>
                        </div>
                    </div>
                    <a href="<?php echo BASE_URL; ?>/admin/orders" class="btn btn-sm btn-outline-warning mt-3">Voir les détails</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i>
                    Ventes récentes
                </div>
                <div class="card-body">
                    <canvas id="salesChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-pie me-1"></i>
                    Répartition des produits par catégorie
                </div>
                <div class="card-body">
                    <canvas id="categoryChart" width="100%" height="50"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-shopping-bag me-1"></i>
                    Commandes récentes
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Client</th>
                                    <th>Date</th>
                                    <th>Montant</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($stats['recent_orders'])): ?>
                                    <?php foreach ($stats['recent_orders'] as $order): ?>
                                        <tr>
                                            <td><?php echo $order['id']; ?></td>
                                            <td><?php echo $order['username']; ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($order['created_at'])); ?></td>
                                            <td><?php echo number_format($order['total_amount'], 2, ',', ' '); ?> €</td>
                                            <td>
                                                <?php
                                                switch ($order['status']) {
                                                    case 'pending':
                                                        echo '<span class="badge bg-warning">En attente</span>';
                                                        break;
                                                    case 'completed':
                                                        echo '<span class="badge bg-success">Terminée</span>';
                                                        break;
                                                    case 'processing':
                                                        echo '<span class="badge bg-info">En traitement</span>';
                                                        break;
                                                    case 'shipped':
                                                        echo '<span class="badge bg-primary">Expédiée</span>';
                                                        break;
                                                    case 'cancelled':
                                                        echo '<span class="badge bg-danger">Annulée</span>';
                                                        break;
                                                    default:
                                                        echo '<span class="badge bg-secondary">' . $order['status'] . '</span>';
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">Aucune commande récente</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <a href="<?php echo BASE_URL; ?>/admin/orders" class="btn btn-primary mt-3">Voir toutes les commandes</a>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-users me-1"></i>
                    Utilisateurs récents
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nom d'utilisateur</th>
                                    <th>Email</th>
                                    <th>Date d'inscription</th>
                                    <th>Rôle</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($stats['recent_users'])): ?>
                                    <?php foreach ($stats['recent_users'] as $user): ?>
                                        <tr>
                                            <td><?php echo $user['id']; ?></td>
                                            <td><?php echo $user['username']; ?></td>
                                            <td><?php echo $user['email']; ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></td>
                                            <td>
                                                <?php if ($user['role'] === 'admin'): ?>
                                                    <span class="badge bg-danger">Admin</span>
                                                <?php elseif ($user['role'] === 'club'): ?>
                                                    <span class="badge bg-primary">Club</span>
                                                <?php else: ?>
                                                    <span class="badge bg-success">Client</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">Aucun utilisateur récent</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <a href="<?php echo BASE_URL; ?>/admin/users" class="btn btn-primary mt-3">Voir tous les utilisateurs</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Exemple de données pour les graphiques - À remplacer par des données réelles
    
    // Graphique des ventes
    var salesCtx = document.getElementById('salesChart').getContext('2d');
    var salesChart = new Chart(salesCtx, {
        type: 'bar',
        data: {
            labels: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet'],
            datasets: [{
                label: 'Ventes (€)',
                data: [1500, 1800, 1200, 2000, 2500, 1900, 3000],
                backgroundColor: 'rgba(0, 123, 255, 0.5)',
                borderColor: 'rgba(0, 123, 255, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    
    // Graphique des catégories
    var categoryCtx = document.getElementById('categoryChart').getContext('2d');
    var categoryChart = new Chart(categoryCtx, {
        type: 'pie',
        data: {
            labels: ['Homme', 'Femme', 'Enfant', 'Accessoires'],
            datasets: [{
                data: [40, 30, 20, 10],
                backgroundColor: [
                    'rgba(0, 123, 255, 0.7)',
                    'rgba(40, 167, 69, 0.7)',
                    'rgba(255, 193, 7, 0.7)',
                    'rgba(220, 53, 69, 0.7)'
                ],
                borderColor: [
                    'rgba(0, 123, 255, 1)',
                    'rgba(40, 167, 69, 1)',
                    'rgba(255, 193, 7, 1)',
                    'rgba(220, 53, 69, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true
        }
    });
});
</script>
