</main>

    <!-- Footer -->
    <footer>
        <div class="container py-5">
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <div class="footer-logo"><span class="text-mgs-black">MGS</span></div>
                    <p class="footer-text">Votre destination sportive préférée avec des collections tendance et performantes</p>
                    <div class="footer-social">
                        <a href="#" class="social-icon" title="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-icon" title="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-icon" title="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="social-icon" title="YouTube">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="footer-title">Categories</h5>
                    <ul class="footer-links">
                        <li><a href="#">Homme</a></li>
                        <li><a href="#">Femme</a></li>
                        <li><a href="#">Enfant</a></li>
                        <li><a href="#">Accessoire</a></li>
                        <li><a href="#">Nouveautés</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="footer-title">Information</h5>
                    <ul class="footer-links">
                        <li><a href="#">A propos</a></li>
                        <li><a href="#">Contactez nous </a></li>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">TERMS & CONDITIONS</a></li>
                        <li><a href="#">POLITIQUE DE CONFIDENTIALITÉ</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="footer-title">Newsletter</h5>
                    <p class="footer-text">Inscrivez-vous pour recevoir nos offres exclusives.</p>
                    <div class="footer-newsletter">
                        <form>
                            <input type="email" class="form-control" placeholder="Your email">
                            <button type="submit" class="btn w-100">S'abonner</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start">
                        <p class="mb-md-0">© 2025 MGS Sport. Tous droits réservés.</p>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <img src="<?php echo BASE_URL; ?>/assets/images/payment-methods.png" alt="Payment Methods" width="200">
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS avec Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AOS Animation Library -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <!-- Custom JS with version parameter to prevent caching -->
    <script src="<?php echo BASE_URL; ?>/assets/js/main.js?v=<?php echo time(); ?>"></script>

    <script>
        // Initialisation de AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });
    </script>
</body>
</html>
