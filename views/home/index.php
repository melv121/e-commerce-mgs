<!-- Hero Banner -->
<section class="hero-banner">
    <div id="mainCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="2"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <div class="hero-slide">
                    <div class="hero-image-container">
                        <img src="<?php echo BASE_URL; ?>/assets/images/nike-just-do-it.jpg" alt="Just Do It" class="hero-image">
                    </div>
                    <div class="container d-flex align-items-center h-100">
                        <div class="hero-content">
                            <h1 class="mega-title">MGS</h1>
                            <p class="lead">Dépasse tes limites avec notre nouvelle collection 2025</p>
                            <a href="#" class="btn btn-primary btn-lg">ACHETEZ MAINTENANT</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="hero-slide">
                    <div class="hero-image-container">
                        <img src="<?php echo BASE_URL; ?>/assets/images/caroussel1.jpg" alt="Performance Sportive" class="hero-image">
                    </div>
                    <div class="container d-flex align-items-center h-100">
                        <div class="hero-content">
                            <h1>PERFORMANCE EXTRÊME</h1>
                            <p class="lead">Équipements techniques conçus pour les athlètes</p>
                            <a href="#" class="btn btn-primary btn-lg">DÉCOUVRIR</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="hero-slide">
                    <div class="hero-image-container">
                        <img src="<?php echo BASE_URL; ?>/assets/images/caroussel2.jpg" alt="Lifestyle Sportif" class="hero-image">
                    </div>
                    <div class="container d-flex align-items-center h-100">
                        <div class="hero-content">
                            <h1>STREET STYLE</h1>
                            <p class="lead">Le sport au quotidien, avec style et attitude</p>
                            <a href="#" class="btn btn-primary btn-lg">EXPLORER</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Précédent</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Suivant</span>
        </button>
    </div>
</section>

<style>
.hero-banner {
    margin-top: -1.5rem; /* Pour compenser le padding de la navbar */
    overflow: hidden;
    position: relative;
    z-index: 1;
    height: 100vh; /* Prend toute la hauteur de la fenêtre */
}

.carousel, .carousel-inner, .carousel-item {
    height: 100%; /* Prend toute la hauteur du conteneur */
}

.hero-slide {
    position: relative;
    height: 100%;
    width: 100%;
    overflow: hidden;
}

.hero-image-container {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    overflow: hidden;
}

.hero-image {
    width: 100%; /* Modifié pour remplir toute la largeur */
    height: 100%; /* Modifié pour remplir toute la hauteur */
    object-fit: cover; /* Changé en 'cover' pour couvrir tout l'espace sans distorsion */
    object-position: center;
}

.hero-content {
    position: relative;
    z-index: 2;
    max-width: 600px;
    padding: 2.5rem;
    background-color: rgba(0, 0, 0, 0.6);
    border-radius: 8px;
    color: white;
    margin-left: 5%;
    transform: translateY(0);
    transition: transform 0.3s ease-out, opacity 0.3s ease-out;
}

.mega-title {
    font-size: 4rem; /* Plus grand pour un impact visuel */
    font-weight: 800;
    margin-bottom: 1.5rem;
    text-transform: uppercase;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
    letter-spacing: 1px;
}

.hero-content .lead {
    font-size: 1.3rem;
    margin-bottom: 2rem;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
}

.carousel-control-prev, .carousel-control-next {
    opacity: 0;
    transition: opacity 0.3s ease;
    width: 10%;
}

.hero-banner:hover .carousel-control-prev,
.hero-banner:hover .carousel-control-next {
    opacity: 0.8;
}

.carousel-indicators [data-bs-target] {
    width: 14px;
    height: 14px;
    border-radius: 50%;
    margin: 0 8px;
    background-color: rgba(255, 255, 255, 0.5);
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.carousel-indicators .active {
    background-color: #fff;
    transform: scale(1.2);
    box-shadow: 0 0 10px rgba(255, 255, 255, 0.8);
}

.hero-content .btn-primary {
    background-color: #ff0000;
    border: none;
    padding: 12px 28px;
    font-weight: 600;
    text-transform: uppercase;
    transition: all 0.3s ease;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    font-size: 1.1rem;
}

.hero-content .btn-primary:hover {
    background-color: #e60000;
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.3);
}

/* Médias queries pour la responsivité */
@media (max-width: 992px) {
    .mega-title {
        font-size: 3rem;
    }
}

@media (max-width: 768px) {
    .hero-banner {
        height: 80vh;
    }
    
    .mega-title {
        font-size: 2.5rem;
    }
    
    .hero-content {
        max-width: 90%;
        margin: 0 auto;
        padding: 1.5rem;
    }
    
    .hero-content .lead {
        font-size: 1.1rem;
    }
}

@media (max-width: 576px) {
    .hero-banner {
        height: 70vh;
    }
    
    .mega-title {
        font-size: 2rem;
    }
}
</style>

<!-- Script pour l'effet de parallaxe au défilement -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const heroImages = document.querySelectorAll('.hero-image');
    const heroContents = document.querySelectorAll('.hero-content');
    
    // Fonction pour initialiser les positions
    const initializePositions = () => {
        heroImages.forEach(image => {
            // Suppression de la transformation initiale pour garder l'image à sa taille réelle
            image.style.transform = 'none'; 
        });
        
        heroContents.forEach(content => {
            content.style.transform = 'translateY(0)';
            content.style.opacity = '1';
        });
    };
    
    // Appliquer la position initiale
    initializePositions();
    
    // Effet de parallaxe vertical au scroll
    window.addEventListener('scroll', function() {
        const scrollY = window.scrollY;
        const windowHeight = window.innerHeight;
        
        // N'appliquer l'effet que si le carrousel est visible
        if (scrollY <= windowHeight) {
            const scrollPercent = scrollY / windowHeight;
            
            // Ajuster l'effet de défilement pour éviter de voir les bords de l'image
            heroImages.forEach(image => {
                const translateY = scrollY * 0.1; // Vitesse réduite
                image.style.transform = `translateY(${translateY}px)`;
            });
            
            // Faire disparaître progressivement le contenu
            heroContents.forEach(content => {
                const opacity = 1 - scrollPercent * 1.5;
                const translateY = scrollY * 0.3;
                content.style.opacity = opacity > 0 ? opacity : 0;
                content.style.transform = `translateY(${translateY}px)`;
            });
        }
    });
    
    // Gérer les transitions entre les slides du carrousel
    const carouselElement = document.getElementById('mainCarousel');
    if (carouselElement) {
        carouselElement.addEventListener('slide.bs.carousel', function() {
            // Réinitialiser et appliquer le parallaxe pour le slide actuel
            setTimeout(initializePositions, 100);
        });
    }
    
    // Si le carrousel avance automatiquement
    setInterval(() => {
        const scrollY = window.scrollY;
        if (scrollY < 10) { // Seulement si on est au top de la page
            const nextButton = document.querySelector('.carousel-control-next');
            if (nextButton) nextButton.click();
        }
    }, 8000); // Avancer toutes les 8 secondes
});
</script>

<!-- Bannière promotionnelle -->
<section class="promo-banner">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h2 class="promo-title">NOUVELLE COLLECTION <span class="text-danger">2025</span></h2>
                <div class="promo-separator"></div>
            </div>
        </div>
    </div>
</section>

<!-- Retirer la Flag Colors Banner -->

<!-- Collections Featured -->
<section class="collections-featured py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-6">
                <div class="collection-feature-box">
                    <img src="<?php echo BASE_URL; ?>/assets/images/imagehomme.jpg" alt="Collection Homme" class="collection-feature-img">
                    <div class="collection-feature-content">
                        <h3 class="collection-feature-title">HOMME</h3>
                        <p>Les essentiels d'entraînement</p>
                        <a href="<?php echo BASE_URL; ?>/product/category/homme" class="btn-feature-link">ACHETEZ MAINTENANT <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="collection-feature-box">
                    <img src="<?php echo BASE_URL; ?>/assets/images/imagefemme.jpg" alt="Collection Femme" class="collection-feature-img">
                    <div class="collection-feature-content">
                        <h3 class="collection-feature-title">FEMME</h3>
                        <p>Mode et performance</p>
                        <a href="<?php echo BASE_URL; ?>/product/category/femme" class="btn-feature-link">ACHETEZ MAINTENANT <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Produits en vedette -->
<section class="featured-products py-5">
    <div class="container">
        <h2 class="section-title text-center">PRODUITS TENDANCE</h2>
        <div class="featured-products-slider">
            <div class="row g-4">
                <?php foreach ($featuredProducts as $product): ?>
                <div class="col-lg-3 col-md-6">
                    <div class="product-card" data-aos="fade-up" data-aos-delay="<?php echo $product['id'] * 100; ?>">
                        <?php if(isset($product['new']) && $product['new']): ?>
                        <div class="product-badge">Nouveau</div>
                        <?php endif; ?>
                        <div class="product-image">
                            <img src="<?php echo BASE_URL . '/' . $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="img-fluid">
                            <div class="product-overlay">
                                <a href="<?php echo BASE_URL; ?>/product/detail/<?php echo $product['id']; ?>" class="btn"><i class="fas fa-eye"></i></a>
                                <a href="#" class="btn add-to-cart-btn" data-product-id="<?php echo $product['id']; ?>"><i class="fas fa-shopping-cart"></i></a>
                                <a href="#" class="btn"><i class="fas fa-heart"></i></a>
                            </div>
                        </div>
                        <div class="product-info p-3">
                            <p class="product-category"><?php echo isset($product['category_name']) ? $product['category_name'] : 'Performance'; ?></p>
                            <h5 class="product-title"><?php echo $product['name']; ?></h5>
                            <div class="product-rating">
                                <?php 
                                $rating = isset($product['rating']) ? $product['rating'] : 4.5;
                                for($i = 1; $i <= 5; $i++): 
                                    if($i <= $rating): ?>
                                        <i class="fas fa-star"></i>
                                    <?php elseif($i - 0.5 <= $rating): ?>
                                        <i class="fas fa-star-half-alt"></i>
                                    <?php else: ?>
                                        <i class="far fa-star"></i>
                                    <?php endif;
                                endfor; 
                                ?>
                            </div>
                            <p class="product-price"><?php echo number_format($product['price'], 2, ',', ' '); ?> €</p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="text-center mt-5">
            <a href="<?php echo BASE_URL; ?>/product/nouveautes" class="btn btn-outline-primary btn-lg">VOIR TOUTE LA COLLECTION</a>
        </div>
    </div>
</section>

<!-- Banner vidéo -->
<section class="video-section">
    <video class="video-bg" autoplay muted loop>
        <source src="<?php echo BASE_URL; ?>/assets/images/videosport.mp4" type="video/mp4">
    </video>
    <div class="video-overlay"></div>
    <div class="video-content">
        <h2 class="video-title">ENTRAÎNE-TOi COMME UN PRO</h2>
        <p class="video-text">Découvre notre gamme d'équipements techniques conçus pour optimiser tes performances</p>
        <a href="<?php echo BASE_URL; ?>/product/nouveautes" class="btn btn-primary btn-lg">DÉCOUVRIR</a>
    </div>
</section>

<!-- Avantages -->
<section class="features-section py-5">
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-box p-4">
                    <i class="feature-icon fas fa-truck"></i>
                    <h5 class="feature-title">LIVRAISON EXPRESS</h5>
                    <p class="text-muted">Livraison gratuite dès 50€</p>
                </div>
            </div>
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-box p-4">
                    <i class="feature-icon fas fa-undo-alt"></i>
                    <h5 class="feature-title">RETOURS FACILES</h5>
                    <p class="text-muted">30 jours pour changer d'avis</p>
                </div>
            </div>
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-box p-4">
                    <i class="feature-icon fas fa-lock"></i>
                    <h5 class="feature-title">PAIEMENT SÉCURISÉ</h5>
                    <p class="text-muted">Transactions 100% sécurisées</p>
                </div>
            </div>
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="400">
                <div class="feature-box p-4">
                    <i class="feature-icon fas fa-headset"></i>
                    <h5 class="feature-title">SERVICE CLIENT</h5>
                    <p class="text-muted">Assistance 7j/7</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="newsletter-box p-5">
            <div class="newsletter-pattern"></div>
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h3 class="mb-3">REJOINS LA COMMUNAUTÉ MGS</h3>
                    <p class="mb-0">Sois le premier à découvrir nos nouveautés et nos offres exclusives</p>
                </div>
                <div class="col-lg-6">
                    <form class="newsletter-form">
                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="Ton adresse email">
                            <button class="btn" type="submit">S'INSCRIRE</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
