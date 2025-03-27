<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4">Modifier le produit</h1>
        <a href="<?php echo BASE_URL; ?>/admin/products" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-edit me-1"></i>
            Formulaire de modification
        </div>
        <div class="card-body">
            <form action="<?php echo BASE_URL; ?>/admin/processEditProduct/<?php echo $product['id']; ?>" method="post" enctype="multipart/form-data">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Nom du produit *</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo $product['name']; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="category_id" class="form-label">Catégorie *</label>
                        <select class="form-select" id="category_id" name="category_id" required>
                            <option value="">Sélectionnez une catégorie</option>
                            <?php foreach($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>" <?php echo $category['id'] == $product['category_id'] ? 'selected' : ''; ?>>
                                    <?php echo $category['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="5"><?php echo $product['description']; ?></textarea>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="price" class="form-label">Prix *</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="price" name="price" step="0.01" min="0" value="<?php echo $product['price']; ?>" required>
                            <span class="input-group-text">€</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="discount" class="form-label">Remise (%)</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="discount" name="discount" min="0" max="100" value="<?php echo $product['discount'] ?? ''; ?>">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="stock" class="form-label">Stock *</label>
                        <input type="number" class="form-control" id="stock" name="stock" min="0" value="<?php echo $product['stock']; ?>" required>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="image" class="form-label">Image du produit</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    <div class="form-text">Laissez vide pour conserver l'image actuelle.</div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Image actuelle</label>
                    <div class="mt-2">
                        <img id="imagePreview" src="<?php echo BASE_URL . '/' . $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                    </div>
                </div>
                
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-outline-secondary me-2" onclick="window.location.href='<?php echo BASE_URL; ?>/admin/products'">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Aperçu de l'image
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    
    imageInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
            }
            
            reader.readAsDataURL(this.files[0]);
        }
    });
    
    // Validation du formulaire
    const form = document.querySelector('form');
    form.addEventListener('submit', function(event) {
        const price = document.getElementById('price').value;
        const stock = document.getElementById('stock').value;
        
        let hasError = false;
        
        if (price <= 0) {
            alert('Le prix doit être supérieur à 0.');
            hasError = true;
        }
        
        if (stock < 0) {
            alert('Le stock ne peut pas être négatif.');
            hasError = true;
        }
        
        if (hasError) {
            event.preventDefault();
        }
    });
});
</script>
