<div class="container py-5">
    <h1 class="mb-4">Votre panier</h1>
    
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
    
    <?php
    // Débogage pour afficher l'état de la session directement dans la page
    if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
        echo '<div class="alert alert-warning">Problème détecté avec la session du panier. Réinitialisation...</div>';
        $_SESSION['cart'] = [];
    }
    ?>
    
    <?php if (empty($cart['items'])): ?>
        <div class="alert alert-info">
            <p>Votre panier est vide.</p>
            <a href="<?php echo BASE_URL; ?>" class="btn btn-primary mt-3">Continuer mes achats</a>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Articles dans votre panier (<?php echo count($cart['items']); ?>)</h5>
                    </div>
                    <div class="card-body">
                        <?php foreach ($cart['items'] as $item): ?>
                            <div class="row mb-4">
                                <div class="col-md-2 col-4">
                                    <img src="<?php echo BASE_URL . '/' . $item['image']; ?>" alt="<?php echo $item['name']; ?>" class="img-fluid rounded">
                                </div>
                                <div class="col-md-10 col-8">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5 class="mb-2"><?php echo $item['name']; ?></h5>
                                            <p class="mb-2 text-muted">Prix unitaire: <?php echo number_format($item['price'], 2, ',', ' '); ?> €</p>
                                            <form action="<?php echo BASE_URL; ?>/cart/update/<?php echo $item['id']; ?>" method="post" class="d-flex align-items-center">
                                                <div class="input-group input-group-sm" style="width: 120px;">
                                                    <button class="btn btn-outline-secondary quantity-btn" type="button" data-action="decrease">-</button>
                                                    <input type="number" class="form-control text-center quantity-input" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" max="99">
                                                    <button class="btn btn-outline-secondary quantity-btn" type="button" data-action="increase">+</button>
                                                </div>
                                                <button type="submit" class="btn btn-sm btn-outline-secondary ms-2 update-btn">Mettre à jour</button>
                                            </form>
                                        </div>
                                        <div class="text-end">
                                            <p class="h5 mb-3"><?php echo number_format($item['price'] * $item['quantity'], 2, ',', ' '); ?> €</p>
                                            <a href="<?php echo BASE_URL; ?>/cart/remove/<?php echo $item['id']; ?>" class="text-danger remove-item">
                                                <i class="fas fa-trash"></i> Supprimer
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-3">
                            </div>
                        <?php endforeach; ?>
                        <div class="d-flex justify-content-between">
                            <a href="<?php echo BASE_URL; ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Continuer mes achats
                            </a>
                            <a href="<?php echo BASE_URL; ?>/cart/clear" class="btn btn-outline-danger">
                                <i class="fas fa-trash me-2"></i>Vider le panier
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Récapitulatif</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Sous-total</span>
                            <span><?php echo number_format($cart['subtotal'], 2, ',', ' '); ?> €</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Livraison</span>
                            <span>
                                <?php if ($cart['shipping'] > 0): ?>
                                    <?php echo number_format($cart['shipping'], 2, ',', ' '); ?> €
                                <?php else: ?>
                                    Gratuit
                                <?php endif; ?>
                            </span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="h5">Total</span>
                            <span class="h5"><?php echo number_format($cart['total'], 2, ',', ' '); ?> €</span>
                        </div>
                        <a href="<?php echo BASE_URL; ?>/checkout" class="btn btn-primary w-100 py-3">
                            <i class="fas fa-credit-card me-2"></i> Valider ma commande
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion des boutons de quantité
    const quantityBtns = document.querySelectorAll('.quantity-btn');
    
    quantityBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const action = this.getAttribute('data-action');
            const input = this.closest('.input-group').querySelector('.quantity-input');
            const updateBtn = this.closest('form').querySelector('.update-btn');
            
            let value = parseInt(input.value);
            
            if (action === 'increase') {
                if (value < 99) {
                    value++;
                }
            } else if (action === 'decrease') {
                if (value > 1) {
                    value--;
                }
            }
            
            input.value = value;
            
            // Ajouter une classe pour indiquer que la quantité a changé
            updateBtn.classList.add('btn-primary');
            updateBtn.classList.remove('btn-outline-secondary');
        });
    });
    
    // Confirmation de suppression
    const removeLinks = document.querySelectorAll('.remove-item');
    
    removeLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cet article du panier ?')) {
                e.preventDefault();
            }
        });
    });
});
</script>
