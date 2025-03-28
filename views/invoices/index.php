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
                    <a href="<?php echo BASE_URL; ?>/order/history" class="list-group-item list-group-item-action">
                        <i class="fas fa-shopping-bag me-2"></i> Mes commandes
                    </a>
                    <a href="<?php echo BASE_URL; ?>/invoice" class="list-group-item list-group-item-action active">
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
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Mes factures</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($hasOrdersWithoutInvoices) && $hasOrdersWithoutInvoices): ?>
                        <div class="alert alert-info">
                            <p><i class="fas fa-info-circle me-2"></i> Certaines de vos commandes n'ont pas encore de facture associée.</p>
                            <a href="<?php echo BASE_URL; ?>/repair-invoices.php" class="btn btn-sm btn-primary mt-2">
                                <i class="fas fa-sync-alt me-2"></i> Générer les factures
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (empty($invoices)): ?>
                        <div class="alert alert-info">
                            Vous n'avez pas encore de factures.
                            <?php if (isset($hasOrdersWithoutInvoices) && $hasOrdersWithoutInvoices): ?>
                                <p class="mt-2">Utilisez le bouton ci-dessus pour créer les factures pour vos commandes existantes.</p>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>N° de facture</th>
                                        <th>N° de commande</th>
                                        <th>Date</th>
                                        <th>Montant</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($invoices as $invoice): ?>
                                        <tr>
                                            <td><?php echo $invoice['invoice_number']; ?></td>
                                            <td><?php echo $invoice['order_number']; ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($invoice['created_at'])); ?></td>
                                            <td><?php echo number_format($invoice['amount'], 2, ',', ' '); ?> €</td>
                                            <td>
                                               
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
