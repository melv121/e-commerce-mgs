<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4">Gestion des produits</h1>
        <a href="<?php echo BASE_URL; ?>/admin/addProduct" class="btn btn-primary">
            <i class="fas fa-plus"></i> Ajouter un produit
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Liste des produits
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="productsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Nom</th>
                            <th>Catégorie</th>
                            <th>Prix</th>
                            <th>Remise</th>
                            <th>Stock</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($products)): ?>
                            <?php foreach($products as $product): ?>
                                <tr>
                                    <td><?php echo $product['id']; ?></td>
                                    <td>
                                        <img src="<?php echo BASE_URL . '/' . $product['image']; ?>" alt="<?php echo $product['name']; ?>" width="50" height="50" style="object-fit: cover;">
                                    </td>
                                    <td><?php echo $product['name']; ?></td>
                                    <td><?php echo $product['category_name'] ?? 'Non catégorisé'; ?></td>
                                    <td><?php echo number_format($product['price'], 2, ',', ' '); ?> €</td>
                                    <td>
                                        <?php if(!empty($product['discount'])): ?>
                                            <span class="badge bg-danger"><?php echo $product['discount']; ?>%</span>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($product['stock'] > 10): ?>
                                            <span class="badge bg-success"><?php echo $product['stock']; ?></span>
                                        <?php elseif($product['stock'] > 0): ?>
                                            <span class="badge bg-warning"><?php echo $product['stock']; ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Épuisé</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo BASE_URL; ?>/admin/editProduct/<?php echo $product['id']; ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger delete-product" data-id="<?php echo $product['id']; ?>" data-name="<?php echo $product['name']; ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center">Aucun produit trouvé</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteProductModal" tabindex="-1" aria-labelledby="deleteProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteProductModalLabel">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir supprimer le produit <span id="productName"></span> ?
                <br>Cette action ne peut pas être annulée.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <a href="#" id="confirmDelete" class="btn btn-danger">Supprimer</a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser DataTables si disponible
    if (typeof $.fn.DataTable !== 'undefined') {
        $('#productsTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json'
            }
        });
    }
    
    // Gestion de la modal de suppression
    const deleteButtons = document.querySelectorAll('.delete-product');
    const productNameElement = document.getElementById('productName');
    const confirmDeleteButton = document.getElementById('confirmDelete');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-id');
            const productName = this.getAttribute('data-name');
            
            productNameElement.textContent = '"' + productName + '"';
            confirmDeleteButton.href = `${BASE_URL}/admin/deleteProduct/${productId}`;
            
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteProductModal'));
            deleteModal.show();
        });
    });
});
</script>
