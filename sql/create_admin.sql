-- Créer un utilisateur administrateur
INSERT INTO users (username, email, password, first_name, last_name, role, created_at)
VALUES ('admin', 'admin@mgs-store.com', '$2y$10$A8tIJy5BzrFCHVYiyvzgruKJ0Ik5WTSZhHfGbsY2oDc7iE8HMrrdG', 'Admin', 'MGS', 'admin', NOW());
-- Note: Le mot de passe haché correspond à "admin123"
