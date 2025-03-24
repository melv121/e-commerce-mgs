<!DOCTYPE html>
<html lang="fr">
<head>
    // ...existing code...
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
        <div class="container">
            // ...existing code...
            <div class="nav-icons ms-auto">
                <button type="button" class="btn search-toggle" data-bs-toggle="modal" data-bs-target="#searchModal">
                    <i class="fas fa-search"></i>
                </button>
                // ...other nav icons...
            </div>
        </div>
    </nav>

    <?php include __DIR__ . '/../partials/search-modal.php'; ?>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const BASE_URL = '<?php echo BASE_URL; ?>';
    </script>
    <script src="<?php echo BASE_URL; ?>/assets/js/search.js"></script>
    
</body>
</html>