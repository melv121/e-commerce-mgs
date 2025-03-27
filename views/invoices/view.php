<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Facture #<?php echo $invoice['invoice_number']; ?></h5>
                    <div>
                        <a href="<?php echo BASE_URL; ?>/invoice" class="btn btn-sm btn-outline-secondary me-2">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                        <a href="<?php echo BASE_URL; ?>/invoice/download/<?php echo $invoice['id']; ?>" class="btn btn-sm btn-primary">
                            <i class="fas fa-download"></i> Télécharger PDF
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="invoice-header row mb-4">
                        <div class="col-md-6">
                            <img src="<?php echo BASE_URL; ?>/assets/images/logo.png" alt="MGS Store" width="150">
                            <div class="mt-4">
                                <h6 class="mb-2">MGS Store</h6>
                                <p class="mb-1">123 Rue du Sport</p>
                                <p class="mb-1">75001 Paris</p>
                                <p class="mb-1">France</p>
                                <p class="mb-1">Tél: 01 23 45 67 89</p>
                                <p class="mb-1">Email: contact@mgs-store.com</p>
                            </div>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h2 class="mb-2">FACTURE</h2>
                            <p class="mb-1"><strong>N° de facture:</strong> <?php echo $invoice['invoice_number']; ?></p>
                            <p class="mb-1"><strong>Date:</strong> <?php echo date('d/m/Y', strtotime($invoice['created_at'])); ?></p>
                            <p class="mb-1"><strong>N° de commande:</strong> <?php echo $invoice['order_number']; ?></p>
                            <p class="mb-1"><strong>Date de commande:</strong> <?php echo date('d/m/Y', strtotime($invoice['order_date'])); ?></p>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="mb-2">Facturer à:</h6>
                            <p class="mb-1"><?php echo $invoice['first_name'] . ' ' . $invoice['last_name']; ?></p>
                            <p class="mb-1"><?php echo $invoice['address']; ?></p>
                            <p class="mb-1"><?php echo $invoice['postal_code'] . ' ' . $invoice['city']; ?></p>
                            <p class="mb-1"><?php echo $invoice['country']; ?></p>
                            <p class="mb-1">Tél: <?php echo $invoice['phone']; ?></p>
                            <p class="mb-1">Email: <?php echo $invoice['email']; ?></p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h6 class="mb-2">Méthode de paiement:</h6>
                            <p>Carte bancaire</p>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th class="text-center">Quantité</th>
                                    <th class="text-end">Prix unitaire</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $subtotal = 0;
                                foreach ($invoice['items'] as $item): 
                                    $itemTotal = $item['price'] * $item['quantity'];
                                    $subtotal += $itemTotal;
                                ?>
                                <tr>
                                    <td><?php echo $item['name']; ?></td>
                                    <td class="text-center"><?php echo $item['quantity']; ?></td>
                                    <td class="text-end"><?php echo number_format($item['price'], 2, ',', ' '); ?> €</td>
                                    <td class="text-end"><?php echo number_format($itemTotal, 2, ',', ' '); ?> €</td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Sous-total</strong></td>
                                    <td class="text-end"><?php echo number_format($subtotal, 2, ',', ' '); ?> €</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Livraison</strong></td>
                                    <td class="text-end">
                                        <?php 
                                        $shipping = $subtotal >= 50 ? 0 : 4.99;
                                        echo $shipping > 0 ? number_format($shipping, 2, ',', ' ') . ' €' : 'Gratuit';
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>TOTAL</strong></td>
                                    <td class="text-end"><strong><?php echo number_format($subtotal + $shipping, 2, ',', ' '); ?> €</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h6 class="mb-2">Remarques:</h6>
                            <p>Nous vous remercions pour votre commande. Cette facture est générée automatiquement et est valable sans signature.</p>
                            <p>Pour toute question ou information complémentaire, n'hésitez pas à nous contacter.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
