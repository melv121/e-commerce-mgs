<div class="container py-5">
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Détails de facturation et livraison</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['error_message'])): ?>
                        <div class="alert alert-danger">
                            <?php echo $_SESSION['error_message']; ?>
                        </div>
                        <?php unset($_SESSION['error_message']); ?>
                    <?php endif; ?>
                    
                    <form action="<?php echo BASE_URL; ?>/checkout/process" method="post" id="checkout-form">
                        <h6 class="mb-3">Informations de facturation</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="billing_first_name" class="form-label">Prénom *</label>
                                <input type="text" class="form-control" id="billing_first_name" name="billing_first_name" value="<?php echo $user['first_name'] ?? ''; ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="billing_last_name" class="form-label">Nom *</label>
                                <input type="text" class="form-control" id="billing_last_name" name="billing_last_name" value="<?php echo $user['last_name'] ?? ''; ?>" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="billing_email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="billing_email" name="billing_email" value="<?php echo $user['email'] ?? ''; ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="billing_address" class="form-label">Adresse *</label>
                            <input type="text" class="form-control" id="billing_address" name="billing_address" value="<?php echo $user['address'] ?? ''; ?>" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="billing_city" class="form-label">Ville *</label>
                                <input type="text" class="form-control" id="billing_city" name="billing_city" value="<?php echo $user['city'] ?? ''; ?>" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="billing_postal_code" class="form-label">Code postal *</label>
                                <input type="text" class="form-control" id="billing_postal_code" name="billing_postal_code" value="<?php echo $user['postal_code'] ?? ''; ?>" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="billing_country" class="form-label">Pays *</label>
                                <select class="form-select" id="billing_country" name="billing_country" required>
                                    <option value="">Sélectionner...</option>
                                    <option value="FR" <?php echo (isset($user['country']) && $user['country'] === 'FR') ? 'selected' : ''; ?>>France</option>
                                    <option value="BE" <?php echo (isset($user['country']) && $user['country'] === 'BE') ? 'selected' : ''; ?>>Belgique</option>
                                    <option value="CH" <?php echo (isset($user['country']) && $user['country'] === 'CH') ? 'selected' : ''; ?>>Suisse</option>
                                    <option value="LU" <?php echo (isset($user['country']) && $user['country'] === 'LU') ? 'selected' : ''; ?>>Luxembourg</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="billing_phone" class="form-label">Téléphone *</label>
                            <input type="tel" class="form-control" id="billing_phone" name="billing_phone" value="<?php echo $user['phone'] ?? ''; ?>" required>
                        </div>
                        
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="same_address" name="same_address" checked>
                                <label class="form-check-label" for="same_address">
                                    L'adresse de livraison est la même que l'adresse de facturation
                                </label>
                            </div>
                        </div>
                        
                        <div id="shipping_address_form" style="display:none;">
                            <h6 class="mb-3">Informations de livraison</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="shipping_first_name" class="form-label">Prénom *</label>
                                    <input type="text" class="form-control" id="shipping_first_name" name="shipping_first_name">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="shipping_last_name" class="form-label">Nom *</label>
                                    <input type="text" class="form-control" id="shipping_last_name" name="shipping_last_name">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="shipping_address" class="form-label">Adresse *</label>
                                <input type="text" class="form-control" id="shipping_address" name="shipping_address">
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="shipping_city" class="form-label">Ville *</label>
                                    <input type="text" class="form-control" id="shipping_city" name="shipping_city">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="shipping_postal_code" class="form-label">Code postal *</label>
                                    <input type="text" class="form-control" id="shipping_postal_code" name="shipping_postal_code">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="shipping_country" class="form-label">Pays *</label>
                                    <select class="form-select" id="shipping_country" name="shipping_country">
                                        <option value="">Sélectionner...</option>
                                        <option value="FR">France</option>
                                        <option value="BE">Belgique</option>
                                        <option value="CH">Suisse</option>
                                        <option value="LU">Luxembourg</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <h6 class="mb-3 mt-4">Méthode de paiement</h6>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="credit_card" value="credit_card" checked>
                                <label class="form-check-label" for="credit_card">
                                    <i class="fab fa-cc-visa me-2"></i> Carte de crédit
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="paypal" value="paypal">
                                <label class="form-check-label" for="paypal">
                                    <i class="fab fa-paypal me-2"></i> PayPal
                                </label>
                            </div>
                        </div>
                        
                        <div id="credit_card_form">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="card_number" class="form-label">Numéro de carte *</label>
                                    <input type="text" class="form-control" id="card_number" name="card_number" placeholder="XXXX XXXX XXXX XXXX" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="card_exp" class="form-label">Date d'expiration *</label>
                                    <input type="text" class="form-control" id="card_exp" name="card_exp" placeholder="MM/AA" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="card_cvv" class="form-label">CVV *</label>
                                    <input type="text" class="form-control" id="card_cvv" name="card_cvv" placeholder="123" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="card_name" class="form-label">Nom sur la carte *</label>
                                <input type="text" class="form-control" id="card_name" name="card_name" required>
                            </div>
                        </div>
                        
                        <div id="paypal_form" style="display:none;">
                            <div class="alert alert-info">
                                Vous serez redirigé vers PayPal pour compléter votre paiement une fois que vous aurez validé votre commande.
                            </div>
                        </div>
                        
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                            <label class="form-check-label" for="terms">
                                J'accepte les <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">conditions générales de vente</a> *
                            </label>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Confirmer la commande</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Résumé de votre commande</h5>
                </div>
                <div class="card-body">
                    <?php foreach ($cart['items'] as $item): ?>
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                <img src="<?php echo BASE_URL . '/' . $item['image']; ?>" alt="<?php echo $item['name']; ?>" width="60" height="60" class="object-fit-cover">
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1"><?php echo $item['name']; ?></h6>
                                <p class="mb-0 text-muted">
                                    <?php echo $item['quantity']; ?> x <?php echo number_format($item['price'], 2, ',', ' '); ?> €
                                </p>
                            </div>
                            <div class="flex-shrink-0">
                                <p class="mb-0 fw-bold"><?php echo number_format($item['price'] * $item['quantity'], 2, ',', ' '); ?> €</p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                    <hr>
                    
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
                    <div class="d-flex justify-content-between mt-3">
                        <span class="fw-bold">Total</span>
                        <span class="fw-bold fs-5"><?php echo number_format($cart['total'], 2, ',', ' '); ?> €</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Conditions Générales de Vente -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="termsModalLabel">Conditions Générales de Vente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>1. Introduction</h6>
                <p>Les présentes conditions générales de vente s'appliquent à toutes les commandes passées sur le site MGS Store.</p>
                
                <h6>2. Prix et paiement</h6>
                <p>Les prix sont indiqués en euros et comprennent la TVA. Le paiement est exigible immédiatement à la date de la commande.</p>
                
                <h6>3. Livraison</h6>
                <p>Les délais de livraison sont donnés à titre indicatif. Nous mettons tout en œuvre pour respecter ces délais.</p>
                
                <h6>4. Droit de rétractation</h6>
                <p>Vous disposez d'un délai de 14 jours pour exercer votre droit de rétractation sans avoir à justifier de motifs.</p>
                
                <h6>5. Garanties</h6>
                <p>Tous nos produits bénéficient de la garantie légale de conformité et de la garantie des vices cachés.</p>
                
                <h6>6. Protection des données</h6>
                <p>Les données collectées sont nécessaires au traitement de votre commande. Elles peuvent être transmises aux partenaires chargés de l'exécution de vos commandes.</p>
                
                <h6>7. Litiges</h6>
                <p>En cas de litige, une solution amiable sera recherchée avant toute action judiciaire.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" id="acceptTerms">J'accepte</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion de l'adresse de livraison
    const sameAddressCheckbox = document.getElementById('same_address');
    const shippingAddressForm = document.getElementById('shipping_address_form');
    
    sameAddressCheckbox.addEventListener('change', function() {
        if (this.checked) {
            shippingAddressForm.style.display = 'none';
            
            // Désactiver les champs de livraison s'ils ne sont pas utilisés
            const shippingInputs = shippingAddressForm.querySelectorAll('input, select');
            shippingInputs.forEach(input => {
                input.removeAttribute('required');
            });
        } else {
            shippingAddressForm.style.display = 'block';
            
            // Activer les champs de livraison s'ils sont utilisés
            const shippingInputs = shippingAddressForm.querySelectorAll('input, select');
            shippingInputs.forEach(input => {
                input.setAttribute('required', '');
            });
        }
    });
    
    // Gestion du mode de paiement
    const creditCardRadio = document.getElementById('credit_card');
    const paypalRadio = document.getElementById('paypal');
    const creditCardForm = document.getElementById('credit_card_form');
    const paypalForm = document.getElementById('paypal_form');
    
    creditCardRadio.addEventListener('change', function() {
        if (this.checked) {
            creditCardForm.style.display = 'block';
            paypalForm.style.display = 'none';
            
            // Activer les champs de carte bancaire
            const cardInputs = creditCardForm.querySelectorAll('input');
            cardInputs.forEach(input => {
                input.setAttribute('required', '');
            });
        }
    });
    
    paypalRadio.addEventListener('change', function() {
        if (this.checked) {
            creditCardForm.style.display = 'none';
            paypalForm.style.display = 'block';
            
            // Désactiver les champs de carte bancaire
            const cardInputs = creditCardForm.querySelectorAll('input');
            cardInputs.forEach(input => {
                input.removeAttribute('required');
            });
        }
    });
    
    // Accepter les conditions générales depuis la modal
    const acceptTermsBtn = document.getElementById('acceptTerms');
    const termsCheckbox = document.getElementById('terms');
    
    acceptTermsBtn.addEventListener('click', function() {
        termsCheckbox.checked = true;
    });
    
    // Validation du formulaire
    const checkoutForm = document.getElementById('checkout-form');
    
    checkoutForm.addEventListener('submit', function(event) {
        if (!termsCheckbox.checked) {
            event.preventDefault();
            alert('Vous devez accepter les conditions générales de vente pour continuer.');
        }
    });
});
</script>
