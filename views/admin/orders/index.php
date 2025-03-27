<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4">Gestion des commandes</h1>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-shopping-cart me-1"></i>
            Liste des commandes
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="ordersTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Client</th>
                            <th>Email</th>
                            <th>Montant</th>
                            <th>Date</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($orders)): ?>
                            <?php foreach($orders as $order): ?>
                                <tr>
                                    <td><?php echo $order['id']; ?></td>
                                    <td><?php echo $order['username']; ?></td>
                                    <td><?php echo $order['email']; ?></td>
                                    <td><?php echo number_format($order['total_amount'], 2, ',', ' '); ?> €</td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
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
                                        <a href="<?php echo BASE_URL; ?>/admin/orderDetail/<?php echo $order['id']; ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">Aucune commande trouvée</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Si DataTables est disponible, initialiser le tableau
    if (typeof $.fn.DataTable !== 'undefined') {
        $('#ordersTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json'
            },
            order: [[4, 'desc']] // Trier par date de création (5ème colonne) par défaut
        });
    }
});
</script>
