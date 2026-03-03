<!-- Formulaire de connexion moderne -->
<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow-lg rounded-4 card-login" style="width: 100%; max-width: 400px; background: linear-gradient(135deg,#0d6efd,#6610f2);">
        <div class="text-center mb-4">
            <i class="fas fa-boxes fa-3x text-white mb-2"></i>
            <h3 class="text-white fw-bold">Connexion</h3>
            <p class="text-light small">Gestion de Stock - PME Sénégal</p>
        </div>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($error_message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        <?php endif; ?>

        <form method="post" action="" class="needs-validation" novalidate>
            <div class="mb-3 position-relative">
                <i class="fas fa-envelope input-icon"></i>
                <input type="email" class="form-control" id="email" name="email" placeholder="Adresse Email" required>
                <div class="invalid-feedback">Veuillez entrer un email valide.</div>
            </div>

            <div class="mb-4 position-relative">
                <i class="fas fa-lock input-icon"></i>
                <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" placeholder="Mot de passe" required>
                <div class="invalid-feedback">Veuillez entrer votre mot de passe.</div>
            </div>

            <button type="submit" class="btn btn-warning w-100 btn-login">Se connecter</button>
        </form>

        <div class="text-center mt-3 text-white small">
            © 2024 - Projet Gestion de Stock - L2 LIAGE
        </div>
    </div>
</div>

<style>
.card-login .form-control {
    border-radius: 50px;
    padding-left: 3rem;
}
.card-login .form-control::placeholder {
    color: rgba(255,255,255,0.7);
}
.card-login .form-control:focus {
    box-shadow: 0 0 10px #ffc107;
    border-color: #ffc107;
}
.input-icon {
    position: absolute;
    left: 15px;
    top: 10px;
    color: #ffc107;
}
.btn-login {
    border-radius: 50px;
    font-weight: bold;
    transition: 0.3s;
}
.btn-login:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.4);
}
</style>

<script>
// Validation Bootstrap 5
(() => {
    'use strict';
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();
</script>
