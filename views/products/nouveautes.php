<div class="nouveautes-hero py-5 mb-5" style="background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('<?php echo BASE_URL; ?>/assets/images/nouveautes-bg.jpg') center/cover;">
    <div class="container text-center text-white">
        <h1 class="display-4 fw-bold mb-4">Nouveautés</h1>
        <p class="lead mb-0">Découvrez nos dernières collections</p>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4">
        <?php foreach ($products as $product): ?>
            <?php 
            $product['new'] = true; // Marquer comme nouveau
            include __DIR__ . '/../partials/product-card.php';
            ?>
        <?php endforeach; ?>
    </div>
</div>
