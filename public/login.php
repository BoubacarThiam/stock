 <?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../src/models/Utilisateur.php';
$error_message = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $mot_de_passe = $_POST['mot_de_passe'] ?? '';

    $utilisateur = getUtilisateurByEmail($email);

    if ($utilisateur && password_verify($mot_de_passe, $utilisateur['mot_de_passe'])) {
        $_SESSION['user'] = [
            'id' => $utilisateur['id'],
            'nom' => $utilisateur['nom'],
            'email' => $utilisateur['email'],
            'role' => $utilisateur['role']
        ];
        header('Location: index.php');
        exit;
    } else {
        $error_message = "Email ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Connexion - Gestion Stock</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="assets/css/style.css">
<style>
html, body {
    height: 100%;
    margin: 0;
    padding: 0;
    font-family: 'Poppins', sans-serif;
    background: url('assets/images/image.png') no-repeat center center/cover;
}
.main-wrapper {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}
.content {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* Card de connexion */
.card-login {
    background: rgba(255,255,255,0.08);
    backdrop-filter: blur(15px);
    border-radius: 1rem;
    padding: 2rem;
    width: 100%;
    max-width: 400px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.3);
    text-align: center;
    color: #fff;
}
.input-icon {
    position: absolute;
    left: 15px;
    top: 12px;
    color: #ffc107;
}
.card-login .form-control {
    border-radius: 50px;
    padding-left: 3rem;
    background: rgba(255,255,255,0.15);
    color: #fff;
    border: none;
}
.card-login .form-control:focus {
    box-shadow: 0 0 15px #ffc107;
    border-color: #ffc107;
    background: rgba(255,255,255,0.25);
}
.btn-login {
    border-radius: 50px;
    font-weight: 600;
    transition: all 0.3s ease;
    background: #ffc107;
    color: #000;
}
.btn-login:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.5);
}

/* Footer sticky */
.footer-login {
    background-color: rgba(0,0,0,0.2);
    color: #fff;
    text-align: center;
    padding: 10px 0;
    font-size: 0.85rem;
}
</style>
</head>
<body>
<div class="main-wrapper">
    <div class="content">
        <div class="card-login position-relative">
            <i class="fas fa-boxes fa-3x mb-3 text-white"></i>
            <h3>Connexion</h3>
            <p>Une application web de gestion</p>

            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($error_message) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            <?php endif; ?>

            <form method="post" action="" class="needs-validation position-relative" novalidate>
                <div class="mb-3 position-relative">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Adresse Email" required>
                </div>

                <div class="mb-4 position-relative">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" placeholder="Mot de passe" required>
                </div>

                <button type="submit" class="btn btn-login w-100">Se connecter</button>
            </form>
        </div>
    </div>

    <footer class="footer-login">
        © 2026 - Projet Gestion de Stock - L2 LIAGE | Contact : +221 78 406 17 91 - info@chezThiam.sn
    </footer>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
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
</body>
</html>
