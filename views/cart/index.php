<div class="container py-5">
    <h1 class="mb-4">Mon Panier</h1>

    <?php if (empty($cartItems)): ?>
        <div class="alert alert-info">
            Votre panier est vide. <a href="<?php echo BASE_URL; ?>">Continuez vos achats</a>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <?php 
                        // Index pour suivre les éléments du panier
                        $itemCount = count($cartItems);
                        $index = 0;
                        
                        foreach ($cartItems as $item): 
                            $index++;
                            $isLast = ($index === $itemCount);
                        ?>
                            <div class="cart-item d-flex align-items-center mb-4">
                                <img src="<?php echo BASE_URL . '/' . $item['image']; ?>" alt="<?php echo $item['name']; ?>" class="cart-item-image" style="width: 100px; height: 100px; object-fit: cover;">
                                <div class="ms-3 flex-grow-1">
                                    <h5 class="mb-1"><?php echo $item['name']; ?></h5>
                                    <p class="text-muted mb-0">Prix unitaire: <?php echo number_format($item['price'], 2, ',', ' '); ?> €</p>
                                    <div class="quantity-selector mt-2">
                                        <button class="btn btn-sm btn-outline-secondary quantity-btn" data-action="decrease" data-item-id="<?php echo $item['id']; ?>">-</button>
                                        <input type="number" class="form-control form-control-sm d-inline-block mx-2 text-center" style="width: 60px;" value="<?php echo $item['quantity']; ?>" min="1" max="99">
                                        <button class="btn btn-sm btn-outline-secondary quantity-btn" data-action="increase" data-item-id="<?php echo $item['id']; ?>">+</button>
                                    </div>
                                </div>
                                <div class="ms-auto">
                                    <p class="h5 mb-0"><?php echo number_format($item['price'] * $item['quantity'], 2, ',', ' '); ?> €</p>
                                    <button class="btn btn-link text-danger mt-2 remove-item" data-item-id="<?php echo $item['id']; ?>">
                                        <i class="fas fa-trash"></i> Supprimer
                                    </button>
                                </div>
                            </div>
                            <?php if (!$isLast): ?>
                                <hr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Récapitulatif</h5>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Sous-total</span>
                            <span><?php echo number_format($total, 2, ',', ' '); ?> €</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Livraison</span>
                            <span><?php echo $total >= 50 ? 'Gratuite' : '4,99 €'; ?></span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total</strong>
                            <strong><?php echo number_format($total >= 50 ? $total : $total + 4.99, 2, ',', ' '); ?> €</strong>
                        </div>
                        <a href="<?php echo BASE_URL; ?>/checkout" class="btn btn-primary w-100">
                            Procéder au paiement
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion des quantités
    document.querySelectorAll('.quantity-btn').forEach(button => {
        button.addEventListener('click', function() {
            const action = this.dataset.action;
            const itemId = this.dataset.itemId;
            const input = this.parentElement.querySelector('input');
            let value = parseInt(input.value);

            if (action === 'increase') {
                value = Math.min(value + 1, 99);
            } else {
                value = Math.max(value - 1, 1);
            }

            input.value = value;
            updateCartItem(itemId, value);
        });
    });

    // Suppression d'articles
    document.querySelectorAll('.remove-item').forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('Voulez-vous vraiment supprimer cet article ?')) {
                removeCartItem(this.dataset.itemId);
            }
        });
    });
});

function updateCartItem(itemId, quantity) {
    fetch(`${BASE_URL}/cart/update`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ itemId, quantity })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function removeCartItem(itemId) {
    fetch(`${BASE_URL}/cart/remove`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ itemId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}
</script>
