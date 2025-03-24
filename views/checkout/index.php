<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <h1 class="mb-4">Finaliser votre commande</h1>
            
            <?php if(isset($_SESSION['checkout_error'])): ?>
                <div class="alert alert-danger">
                    <?php 
                    echo $_SESSION['checkout_error']; 
                    unset($_SESSION['checkout_error']);
                    ?>
                </div>
            <?php endif; ?>
            
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">1. Informations de livraison</h5>
                </div>
                <div class="card-body">
                    <form id="checkout-form" action="<?php echo BASE_URL; ?>/checkout/process" method="post">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="firstName" class="form-label">Prénom</label>
                                <input type="text" class="form-control" id="firstName" name="firstName" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="lastName" class="form-label">Nom</label>
                                <input type="text" class="form-control" id="lastName" name="lastName" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Adresse</label>
                            <input type="text" class="form-control" id="address" name="address" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-5 mb-3">
                                <label for="country" class="form-label">Pays</label>
                                <select class="form-select" id="country" name="country" required>
                                    <option value="">Choisir...</option>
                                    <option value="FR" selected>France</option>
                                    <option value="BE">Belgique</option>
                                    <option value="CH">Suisse</option>
                                    <option value="LU">Luxembourg</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="city" class="form-label">Ville</label>
                                <input type="text" class="form-control" id="city" name="city" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="zipCode" class="form-label">Code postal</label>
                                <input type="text" class="form-control" id="zipCode" name="zipCode" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">Téléphone</label>
                            <input type="tel" class="form-control" id="phone" name="phone" required>
                        </div>
                        
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="saveInfo" name="saveInfo">
                            <label class="form-check-label" for="saveInfo">Enregistrer ces informations pour la prochaine fois</label>
                        </div>
                    
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">2. Méthode de paiement</h5>
                </div>
                <div class="card-body">
                    <div class="payment-methods">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" id="credit_card" value="credit_card" checked>
                            <label class="form-check-label d-flex align-items-center" for="credit_card">
                                <span class="me-3">Carte de crédit</span>
                                <span class="payment-icons">
                                    <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons@develop/icons/visa.svg" alt="Visa" height="24" class="me-2">
                                    <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons@develop/icons/mastercard.svg" alt="Mastercard" height="24" class="me-2">
                                    <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons@develop/icons/americanexpress.svg" alt="American Express" height="24">
                                </span>
                            </label>
                            
                            <div id="credit_card_details" class="mt-3">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="cc_name" class="form-label">Nom sur la carte</label>
                                        <input type="text" class="form-control" id="cc_name" placeholder="John Doe" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="cc_number" class="form-label">Numéro de carte</label>
                                        <input type="text" class="form-control" id="cc_number" placeholder="XXXX XXXX XXXX XXXX" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="cc_expiration" class="form-label">Date d'expiration</label>
                                        <input type="text" class="form-control" id="cc_expiration" placeholder="MM/AA" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="cc_cvv" class="form-label">CVV</label>
                                        <input type="text" class="form-control" id="cc_cvv" placeholder="XXX" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" id="paypal" value="paypal">
                            <label class="form-check-label d-flex align-items-center" for="paypal">
                                <span class="me-3">PayPal</span>
                                <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons@develop/icons/paypal.svg" alt="PayPal" height="24">
                            </label>
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" id="apple_pay" value="apple_pay">
                            <label class="form-check-label d-flex align-items-center" for="apple_pay">
                                <span class="me-3">Apple Pay</span>
                                <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons@develop/icons/applepay.svg" alt="Apple Pay" height="24">
                            </label>
                        </div>
                        
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="google_pay" value="google_pay">
                            <label class="form-check-label d-flex align-items-center" for="google_pay">
                                <span class="me-3">Google Pay</span>
                                <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons@develop/icons/googlepay.svg" alt="Google Pay" height="24">
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Résumé de votre commande</h5>
                </div>
                <div class="card-body">
                    <?php foreach ($cartItems as $item): ?>
                        <div class="d-flex mb-3">
                            <img src="<?php echo BASE_URL . '/' . $item['image']; ?>" alt="<?php echo $item['name']; ?>" class="cart-summary-img" style="width: 64px; height: 64px; object-fit: cover;">
                            <div class="ms-3">
                                <h6 class="mb-0"><?php echo $item['name']; ?></h6>
                                <small class="text-muted">Quantité: <?php echo $item['quantity']; ?></small>
                                <p class="mb-0 fw-bold"><?php echo number_format($item['price'] * $item['quantity'], 2, ',', ' '); ?> €</p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Sous-total</span>
                        <span><?php echo number_format($total, 2, ',', ' '); ?> €</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Livraison</span>
                        <span><?php echo $shipping > 0 ? number_format($shipping, 2, ',', ' ') . ' €' : 'Gratuite'; ?></span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <strong>Total</strong>
                        <strong><?php echo number_format($grandTotal, 2, ',', ' '); ?> €</strong>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">Commander</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle payment method details
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    const creditCardDetails = document.getElementById('credit_card_details');
    
    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            if (this.id === 'credit_card') {
                creditCardDetails.style.display = 'block';
            } else {
                creditCardDetails.style.display = 'none';
            }
        });
    });
    
    // Form validation 
    const form = document.getElementById('checkout-form');
    form.addEventListener('submit', function(e) {
        const selectedPayment = document.querySelector('input[name="payment_method"]:checked').value;
        
        if (selectedPayment === 'credit_card') {
            const ccNumber = document.getElementById('cc_number').value;
            const ccExpiration = document.getElementById('cc_expiration').value;
            const ccCVV = document.getElementById('cc_cvv').value;
            
            // Basic validation for demonstration purposes
            if (!ccNumber || !ccExpiration || !ccCVV) {
                e.preventDefault();
                alert('Veuillez remplir tous les champs relatifs à votre carte de crédit.');
                return;
            }
        }
    });
});
</script>
