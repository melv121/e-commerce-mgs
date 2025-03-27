/**
 * Script pour gérer les variantes de couleur des produits
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log("Initialisation du script de variantes de produit");
    
    // Gestion des variantes de couleur (cercles)
    const colorVariants = document.querySelectorAll('.product-color-variant');
    const variantIdInput = document.getElementById('variant_id');
    const selectedColorName = document.getElementById('selected-color-name');
    const mainImage = document.getElementById('mainProductImage');
    const dropdownColorName = document.getElementById('dropdownColorName');
    const colorDropdownItems = document.querySelectorAll('.color-dropdown-item');
    const colorDropdownToggle = document.getElementById('colorDropdown');
    
    console.log("Nombre de variantes trouvées:", colorVariants.length);
    
    function updateSelectedVariant(variantId, colorName, imageSrc, color) {
        console.log("Mise à jour de la variante:", variantId, colorName, imageSrc, color);
        
        // Mettre à jour l'input caché
        if (variantIdInput) {
            variantIdInput.value = variantId;
        }
        
        // Afficher le nom de la couleur sélectionnée
        if (selectedColorName) {
            selectedColorName.textContent = colorName;
        }
        
        // Mettre à jour le texte du dropdown
        if (dropdownColorName) {
            dropdownColorName.textContent = colorName;
            
            // Mettre à jour la couleur du point dans le bouton dropdown
            const colorDot = colorDropdownToggle.querySelector('.color-preview-dot');
            if (colorDot) {
                colorDot.style.backgroundColor = color;
            }
        }
        
        // Mettre à jour l'image principale
        if (mainImage && imageSrc) {
            // Assurer une transition fluide
            mainImage.style.opacity = '0.5';
            
            // Changer l'image
            mainImage.src = imageSrc;
            
            // Augmenter l'opacité lorsque l'image est chargée
            mainImage.onload = function() {
                mainImage.style.opacity = '1';
            };
            
            // Fallback au cas où l'événement onload ne se déclenche pas
            setTimeout(function() {
                mainImage.style.opacity = '1';
            }, 300);
        }
    }
    
    // Initialiser et ajouter les gestionnaires d'événements
    if (colorVariants.length > 0) {
        // Ajouter des gestionnaires pour les cercles de couleur
        colorVariants.forEach(variant => {
            variant.addEventListener('click', function() {
                // Réinitialiser toutes les variantes
                colorVariants.forEach(v => v.classList.remove('selected'));
                
                // Marquer celle-ci comme sélectionnée
                this.classList.add('selected');
                
                // Mettre à jour tous les éléments
                updateSelectedVariant(
                    this.getAttribute('data-variant-id'),
                    this.getAttribute('data-color-name'),
                    this.getAttribute('data-image'),
                    this.getAttribute('data-color')
                );
            });
        });
        
        // Sélectionner la première variante par défaut
        const firstVariant = colorVariants[0];
        firstVariant.classList.add('selected');
        
        // Initialiser avec la première variante
        updateSelectedVariant(
            firstVariant.getAttribute('data-variant-id'),
            firstVariant.getAttribute('data-color-name'),
            firstVariant.getAttribute('data-image'),
            firstVariant.getAttribute('data-color')
        );
    }
    
    // Gestion des éléments du menu dropdown
    if (colorDropdownItems.length > 0) {
        colorDropdownItems.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Sélectionner également la variante cercle correspondante
                const variantId = this.getAttribute('data-variant-id');
                colorVariants.forEach(v => {
                    if (v.getAttribute('data-variant-id') === variantId) {
                        v.classList.add('selected');
                    } else {
                        v.classList.remove('selected');
                    }
                });
                
                // Mettre à jour tous les éléments
                updateSelectedVariant(
                    variantId,
                    this.getAttribute('data-color-name'),
                    this.getAttribute('data-image'),
                    this.getAttribute('data-color')
                );
            });
        });
    }
});
