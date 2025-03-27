<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body p-5 text-center">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
                    </div>
                    <h2 class="mb-3">Merci pour votre commande!</h2>
                    <p class="lead mb-4">Votre commande #<?php echo isset($order['order_number']) ? $order['order_number'] : $order['id']; ?> a été confirmée.</p>
                    
                    <div class="alert alert-info text-start mb-4">
                        <p><strong>Un email de confirmation</strong> a été envoyé à <?php echo $order['email']; ?>.</p>
                        <p class="mb-0">Vous recevrez un email de suivi lorsque votre commande sera expédiée.</p>
                    </div>
                    
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Résumé de votre commande</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Produit</th>
                                            <th class="text-center">Quantité</th>
                                            <th class="text-end">Prix</th>
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
                                                        <img src="<?php echo BASE_URL . '/' . $item['image']; ?>" alt="<?php echo $item['name']; ?>" width="40" height="40" class="me-3 object-fit-cover">
                                                        <span><?php echo $item['name']; ?></span>
                                                    </div>
                                                </td>
                                                <td class="text-center"><?php echo $item['quantity']; ?></td>
                                                <td class="text-end"><?php echo number_format($itemTotal, 2, ',', ' '); ?> €</td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2" class="text-end">Sous-total:</td>
                                            <td class="text-end"><?php echo number_format($subtotal, 2, ',', ' '); ?> €</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="text-end">Livraison:</td>
                                            <td class="text-end">
                                                <?php 
                                                $shipping = $subtotal >= 50 ? 0 : 4.99;
                                                echo $shipping > 0 ? number_format($shipping, 2, ',', ' ') . ' €' : 'Gratuit';
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="text-end"><strong>Total:</strong></td>
                                            <td class="text-end"><strong><?php echo number_format($order['total_amount'], 2, ',', ' '); ?> €</strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h5>Informations de livraison</h5>
                        <p class="mb-0"><?php echo $order['shipping_address']; ?></p>
                    </div>
                    
                    <div class="d-flex justify-content-center gap-3">
                        <a href="<?php echo BASE_URL; ?>/invoice/view/<?php echo $order['id']; ?>" class="btn btn-outline-primary">
                            <i class="fas fa-file-invoice me-2"></i> Voir ma facture
                        </a>
                        <a href="<?php echo BASE_URL; ?>" class="btn btn-primary">
                            <i class="fas fa-home me-2"></i> Retour à l'accueil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
