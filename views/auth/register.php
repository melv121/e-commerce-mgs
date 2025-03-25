<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-light">
                    <h4 class="mb-0">Créer un compte</h4>
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
                    
                    <?php 
                    // Récupérer les données du formulaire en cas d'erreur
                    $formData = $_SESSION['form_data'] ?? [];
                    unset($_SESSION['form_data']);
                    ?>
                    
                    <form action="<?php echo BASE_URL; ?>/auth/processRegister" method="post">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">Prénom</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $formData['first_name'] ?? ''; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Nom</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $formData['last_name'] ?? ''; ?>">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="username" class="form-label">Nom d'utilisateur*</label>
                            <input type="text" class="form-control" id="username" name="username" value="<?php echo $formData['username'] ?? ''; ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Adresse e-mail*</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo $formData['email'] ?? ''; ?>" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Mot de passe*</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <div class="form-text">Le mot de passe doit contenir au moins 6 caractères</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirm" class="form-label">Confirmer le mot de passe*</label>
                                <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                            </div>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                            <label class="form-check-label" for="terms">J'accepte les <a href="#">termes et conditions</a>*</label>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Créer mon compte</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer bg-light text-center">
                    <p class="mb-0">Vous avez déjà un compte ? <a href="<?php echo BASE_URL; ?>/auth/login">Connectez-vous</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
