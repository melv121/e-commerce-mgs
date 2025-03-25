<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Mon compte</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="<?php echo BASE_URL; ?>/auth/profile" class="list-group-item list-group-item-action active">
                        <i class="fas fa-user me-2"></i> Mon profil
                    </a>
                    <a href="<?php echo BASE_URL; ?>/order/history" class="list-group-item list-group-item-action">
                        <i class="fas fa-shopping-bag me-2"></i> Mes commandes
                    </a>
                    <a href="<?php echo BASE_URL; ?>/auth/logout" class="list-group-item list-group-item-action text-danger">
                        <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Mes informations personnelles</h5>
                </div>
                <div class="card-body">
                    <?php if(isset($_SESSION['auth_errors'])): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach($_SESSION['auth_errors'] as $error): ?>
                                    <li><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php unset($_SESSION['auth_errors']); ?>
                    <?php endif; ?>
                    
                    <?php if(isset($_SESSION['success_message'])): ?>
                        <div class="alert alert-success">
                            <?php echo $_SESSION['success_message']; ?>
                        </div>
                        <?php unset($_SESSION['success_message']); ?>
                    <?php endif; ?>
                    
                    <form action="<?php echo BASE_URL; ?>/auth/updateProfile" method="post">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">Prénom</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $user['first_name'] ?? ''; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Nom</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $user['last_name'] ?? ''; ?>">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Adresse e-mail</label>
                            <input type="email" class="form-control" id="email" value="<?php echo $user['email']; ?>" readonly>
                            <div class="form-text">L'adresse e-mail ne peut pas être modifiée</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Adresse</label>
                            <input type="text" class="form-control" id="address" name="address" value="<?php echo $user['address'] ?? ''; ?>">
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label">Ville</label>
                                <input type="text" class="form-control" id="city" name="city" value="<?php echo $user['city'] ?? ''; ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="postal_code" class="form-label">Code postal</label>
                                <input type="text" class="form-control" id="postal_code" name="postal_code" value="<?php echo $user['postal_code'] ?? ''; ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="country" class="form-label">Pays</label>
                                <select class="form-select" id="country" name="country">
                                    <option value="">Sélectionner...</option>
                                    <option value="FR" <?php echo ($user['country'] ?? '') === 'FR' ? 'selected' : ''; ?>>France</option>
                                    <option value="BE" <?php echo ($user['country'] ?? '') === 'BE' ? 'selected' : ''; ?>>Belgique</option>
                                    <option value="CH" <?php echo ($user['country'] ?? '') === 'CH' ? 'selected' : ''; ?>>Suisse</option>
                                    <option value="LU" <?php echo ($user['country'] ?? '') === 'LU' ? 'selected' : ''; ?>>Luxembourg</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">Téléphone</label>
                            <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo $user['phone'] ?? ''; ?>">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Mettre à jour mon profil</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
