/**
 * Fichier JavaScript principal pour MGS Store
 */

document.addEventListener('DOMContentLoaded', function() {
    // Change navbar background on scroll
    const navbar = document.querySelector('.navbar');
    
    function checkScroll() {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    }
    
    window.addEventListener('scroll', checkScroll);
    checkScroll(); // Check on page load
    
    // Initialisation des tooltips Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Effet de défilement doux pour les ancres
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 70,
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // Animation des produits et sections au défilement
    const animateElements = document.querySelectorAll('.product-card, .feature-box, .collection-card');
    
    // Définir une animation de retard différente pour chaque élément
    animateElements.forEach((element, index) => {
        element.classList.add('fade-in-element');
        element.style.animationDelay = `${index * 0.1}s`;
    });
    
    // Fonction pour animer les éléments au défilement
    const animateOnScroll = function() {
        const elements = document.querySelectorAll('.fade-in-element');
        
        elements.forEach(element => {
            const position = element.getBoundingClientRect();
            
            // Si l'élément est visible dans la fenêtre
            if(position.top < window.innerHeight - 100 && position.bottom >= 0) {
                element.classList.add('fade-in');
                element.classList.remove('fade-in-element');
            }
        });
    };
    
    // Appliquer l'animation au chargement et au défilement
    window.addEventListener('scroll', animateOnScroll);
    animateOnScroll(); // Déclencher une fois au chargement
    
    // Animation hover des images produits
    const productImages = document.querySelectorAll('.product-image img');
    
    productImages.forEach(image => {
        const parent = image.parentElement;
        parent.addEventListener('mouseenter', function() {
            image.style.transform = 'scale(1.05)';
        });
        parent.addEventListener('mouseleave', function() {
            image.style.transform = 'scale(1)';
        });
    });
    
    // Animation de l'ajout au panier
    const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');
    const cartCounter = document.querySelector('.cart-counter');
    
    if (addToCartButtons.length) {
        let count = parseInt(cartCounter.textContent || '0');
        
        addToCartButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const productId = this.dataset.productId;
                
                // Envoyer la requête AJAX pour ajouter au panier
                const formData = new FormData();
                formData.append('product_id', productId);
                formData.append('quantity', 1);
                
                fetch(`${BASE_URL}/cart/add`, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Mettre à jour le compteur du panier
                        count = data.cartCount || (count + 1);
                        cartCounter.textContent = count;
                        
                        // Animation pulsation
                        cartCounter.classList.add('cart-pulse');
                        setTimeout(() => {
                            cartCounter.classList.remove('cart-pulse');
                        }, 500);
                        
                        // Afficher une notification d'ajout au panier
                        const product = this.closest('.product-card');
                        const productName = product ? product.querySelector('.product-title').textContent : 'Produit';
                        
                        // Afficher la mini-notification
                        showCartNotification(productName, count);
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                });
            });
        });
    }
    
    // Fonction pour afficher une mini-notification d'ajout au panier
    function showCartNotification(productName, count) {
        // Vérifier si une notification existe déjà
        let notification = document.querySelector('.cart-notification');
        
        // Si elle n'existe pas encore, la créer
        if (!notification) {
            notification = document.createElement('div');
            notification.className = 'cart-notification';
            document.body.appendChild(notification);
        }
        
        // Mettre à jour le contenu de la notification
        notification.innerHTML = `
            <div class="notification-content">
                <div class="notification-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="notification-text">
                    <p><strong>${productName}</strong> ajouté au panier</p>
                    <p class="cart-count-text"><i class="fas fa-shopping-cart"></i> ${count} article${count > 1 ? 's' : ''} dans votre panier</p>
                </div>
            </div>
            <div class="notification-actions">
                <a href="${BASE_URL}/cart" class="btn btn-sm btn-primary">Voir le panier</a>
                <button class="btn btn-sm btn-outline-secondary notification-close">Continuer</button>
            </div>
        `;
        
        // Ajouter la classe active pour afficher la notification
        notification.classList.add('active');
        
        // Ajouter un gestionnaire d'événements pour le bouton de fermeture
        const closeButton = notification.querySelector('.notification-close');
        if (closeButton) {
            closeButton.addEventListener('click', function() {
                notification.classList.remove('active');
            });
        }
        
        // Masquer automatiquement la notification après 5 secondes
        setTimeout(() => {
            notification.classList.remove('active');
        }, 5000);
    }
    
    // Créer une fonction pour afficher des notifications toast
    function showToast(message) {
        // Vérifier si l'élément toast container existe déjà
        let toastContainer = document.querySelector('.toast-container');
        
        // Sinon, le créer
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
            document.body.appendChild(toastContainer);
        }
        
        // Créer un nouvel élément toast
        const toastId = 'toast-' + Date.now();
        const toastHTML = `
            <div id="${toastId}" class="toast align-items-center text-white bg-dark" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fas fa-check-circle me-2"></i> ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;
        
        // Ajouter le toast au container
        toastContainer.innerHTML += toastHTML;
        
        // Initialiser et afficher le toast
        const toastElement = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastElement, { 
            animation: true,
            autohide: true,
            delay: 3000
        });
        toast.show();
        
        // Supprimer le toast du DOM après qu'il soit caché
        toastElement.addEventListener('hidden.bs.toast', function() {
            toastElement.remove();
        });
    }
    
    // Animation des chiffres (compteur)
    function animateCounter(element) {
        const target = parseInt(element.getAttribute('data-target'));
        const duration = 2000; // durée en ms
        const stepTime = 50; // intervalle entre chaque étape
        const initialValue = 0;
        const steps = Math.ceil(duration / stepTime);
        const increment = (target - initialValue) / steps;
        let current = initialValue;
        let counter = setInterval(function() {
            current += increment;
            if (current >= target) {
                element.textContent = target.toLocaleString();
                clearInterval(counter);
            } else {
                element.textContent = Math.floor(current).toLocaleString();
            }
        }, stepTime);
    }

    // Observer pour déclencher l'animation des compteurs
    const counters = document.querySelectorAll('.counter-value');
    if (counters.length > 0) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        
        counters.forEach(counter => {
            observer.observe(counter);
        });
    }
    
    // Animation du hover sur les catégories
    const categoryCards = document.querySelectorAll('.category-card');
    categoryCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            const overlay = this.querySelector('.category-overlay');
            const heading = overlay.querySelector('h3');
            const button = overlay.querySelector('.btn');
            
            overlay.style.opacity = '1';
            if (heading) heading.style.transform = 'translateY(0)';
            if (button) button.style.transform = 'translateY(0)';
        });
        
        card.addEventListener('mouseleave', function() {
            const overlay = this.querySelector('.category-overlay');
            const heading = overlay.querySelector('h3');
            const button = overlay.querySelector('.btn');
            
            overlay.style.opacity = '0';
            if (heading) heading.style.transform = 'translateY(20px)';
            if (button) button.style.transform = 'translateY(20px)';
        });
    });
    
    // Créer un effet de parallaxe pour les images héro
    const heroSlides = document.querySelectorAll('.hero-slide');
    window.addEventListener('scroll', function() {
        const scrollTop = window.scrollY;
        heroSlides.forEach(slide => {
            const moveY = scrollTop * 0.5;
            slide.style.backgroundPositionY = `calc(50% + ${moveY}px)`;
        });
    });
    
    // Lazy loading des images
    const lazyImages = document.querySelectorAll('.lazy-load');
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                if (img.dataset.src) {
                    img.src = img.dataset.src;
                    img.addEventListener('load', () => {
                        img.classList.add('lazy-loaded');
                    });
                    observer.unobserve(img);
                }
            }
        });
    });
    
    lazyImages.forEach(img => {
        imageObserver.observe(img);
    });
    
    // Animation des icônes améliorée
    const navIcons = document.querySelectorAll('.nav-icons a');
    
    navIcons.forEach(icon => {
        icon.addEventListener('mouseenter', function() {
            const iconElement = this.querySelector('i');
            if (iconElement) {
                iconElement.classList.add('fa-beat');
                setTimeout(() => {
                    iconElement.classList.remove('fa-beat');
                }, 800);
            }
        });
    });
    
    // Animation pour les icônes des fonctionnalités (features)
    const featureIcons = document.querySelectorAll('.feature-icon');
    
    featureIcons.forEach(icon => {
        icon.parentElement.addEventListener('mouseenter', function() {
            icon.style.animation = "bounceIn 0.8s ease";
            icon.style.color = "#ff3333";
            setTimeout(() => {
                icon.style.animation = "";
                icon.style.color = "#ff0000";
            }, 800);
        });
    });
    
    // Animation pour les icônes sociales du footer
    const socialIcons = document.querySelectorAll('.social-icon');
    
    socialIcons.forEach(icon => {
        icon.addEventListener('mouseenter', function() {
            const iconElement = this.querySelector('i');
            if (iconElement) {
                iconElement.classList.add('fa-bounce');
                setTimeout(() => {
                    iconElement.classList.remove('fa-bounce');
                }, 1000);
            }
        });
    });
    
    // Animation pour les icônes
    const animatedIcons = document.querySelectorAll('.animated-icon');
    
    animatedIcons.forEach(icon => {
        icon.addEventListener('mouseenter', function() {
            this.classList.add('fa-beat');
        });
        
        icon.addEventListener('mouseleave', function() {
            this.classList.remove('fa-beat');
        });
    });
    
    // Formulaire d'ajout au panier (AJAX)
    const addToCartForms = document.querySelectorAll('.add-to-cart-form');
    
    addToCartForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const productId = this.getAttribute('data-product-id');
            const url = BASE_URL + '/cart/add/' + productId;
            
            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mettre à jour le nombre d'articles dans le panier
                    updateCartCount(data.cart_count);
                    
                    // Afficher une notification
                    showCartNotification('Produit ajouté au panier avec succès!');
                    
                    // Animation du bouton panier
                    const cartIcon = document.querySelector('.cart-icon');
                    if (cartIcon) {
                        cartIcon.classList.add('cart-pulse');
                        setTimeout(() => {
                            cartIcon.classList.remove('cart-pulse');
                        }, 1000);
                    }
                } else {
                    showCartNotification('Erreur: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showCartNotification('Une erreur est survenue.', 'error');
            });
        });
    });
    
    // Mettre à jour le compteur du panier
    function updateCartCount(count) {
        const cartCounter = document.querySelector('.cart-counter');
        if (cartCounter) {
            cartCounter.textContent = count;
            cartCounter.style.display = count > 0 ? 'block' : 'none';
        }
    }
    
    // Afficher une notification d'ajout au panier
    function showCartNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = 'cart-notification ' + (type === 'success' ? 'success' : 'error');
        
        notification.innerHTML = `
            <div class="notification-content">
                <div class="notification-icon">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
                </div>
                <div class="notification-text">
                    <p>${message}</p>
                </div>
            </div>
            ${type === 'success' ? `
            <div class="notification-actions">
                <a href="${BASE_URL}/cart" class="btn btn-sm btn-primary">Voir le panier</a>
                <button class="btn btn-sm btn-secondary close-notification">Continuer mes achats</button>
            </div>
            ` : ''}
        `;
        
        document.body.appendChild(notification);
        
        // Afficher la notification avec une animation
        setTimeout(() => {
            notification.classList.add('active');
        }, 10);
        
        // Ajouter gestionnaire d'événements pour fermer la notification
        const closeBtn = notification.querySelector('.close-notification');
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                notification.classList.remove('active');
                setTimeout(() => {
                    notification.remove();
                }, 300);
            });
        }
        
        // Fermer automatiquement après 5 secondes
        setTimeout(() => {
            if (document.body.contains(notification)) {
                notification.classList.remove('active');
                setTimeout(() => {
                    if (document.body.contains(notification)) {
                        notification.remove();
                    }
                }, 300);
            }
        }, 5000);
    }
    
    // Initialiser les éléments qui doivent apparaître en fondu
    const fadeElements = document.querySelectorAll('.fade-in-element');
    if (fadeElements.length > 0) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('fade-in');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        
        fadeElements.forEach(element => {
            observer.observe(element);
        });
    }
    
    // Sticky navbar
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    }
    
    // Initialiser les formulaires avec validation
    const forms = document.querySelectorAll('.needs-validation');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            form.classList.add('was-validated');
        }, false);
    });
});

// Fonction pour charger plus de produits (simulée)
function loadMoreProducts() {
    const productsContainer = document.querySelector('.products-load-more');
    if (!productsContainer) return;
    
    const loadMoreBtn = document.querySelector('.load-more-btn');
    if (loadMoreBtn) {
        loadMoreBtn.textContent = 'Chargement...';
        loadMoreBtn.disabled = true;
        
        // Simuler un délai de chargement
        setTimeout(() => {
            const productTemplate = productsContainer.querySelector('.product-card');
            if (productTemplate) {
                const clonedProducts = [];
                
                // Cloner quelques produits
                for (let i = 0; i < 4; i++) {
                    const clone = productTemplate.cloneNode(true);
                    clone.classList.add('fade-in-element');
                    clonedProducts.push(clone);
                }
                
                // Ajouter les produits clonés
                clonedProducts.forEach(product => {
                    productsContainer.appendChild(product);
                });
                
                // Animer l'entrée des nouveaux produits
                setTimeout(() => {
                    clonedProducts.forEach(product => {
                        product.classList.add('fade-in');
                        product.classList.remove('fade-in-element');
                    });
                }, 100);
                
                loadMoreBtn.textContent = 'Voir plus de produits';
                loadMoreBtn.disabled = false;
            }
        }, 1000);
    }
}

// Fonction pour filtrer les produits (simulée)
function filterProducts(category) {
    const products = document.querySelectorAll('.filterable-product');
    const filterButtons = document.querySelectorAll('.filter-btn');
    
    // Mettre à jour les boutons de filtre
    filterButtons.forEach(btn => {
        if (btn.getAttribute('data-filter') === category || (category === 'all' && btn.getAttribute('data-filter') === 'all')) {
            btn.classList.add('active');
        } else {
            btn.classList.remove('active');
        }
    });
    
    // Filtrer les produits
    products.forEach(product => {
        const productCategory = product.getAttribute('data-category');
        
        if (category === 'all' || productCategory === category) {
            product.style.display = 'block';
            setTimeout(() => {
                product.style.opacity = '1';
                product.style.transform = 'translateY(0)';
            }, 100);
        } else {
            product.style.opacity = '0';
            product.style.transform = 'translateY(20px)';
            setTimeout(() => {
                product.style.display = 'none';
            }, 300);
        }
    });
}

// Ajout d'une animation CSS pour l'effet de rebond
document.head.insertAdjacentHTML('beforeend', `
<style>
@keyframes bounceIn {
    0% { transform: scale(1); }
    20% { transform: scale(1.3); }
    40% { transform: scale(0.9); }
    60% { transform: scale(1.2); }
    80% { transform: scale(0.95); }
    100% { transform: scale(1); }
}
</style>
`);
