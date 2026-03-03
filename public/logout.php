<?php
session_start();
session_destroy();
// Rediriger vers la page de connexion ou d'accueil
header('Location: login.php');
exit;