<?php
$mot_de_passe_correct = 'BOUBACAR'; 
$nouveau_hash = password_hash($mot_de_passe_correct, PASSWORD_DEFAULT);
echo "Voici le hash correct pour le mot de passe 'admin123' : <br><br>";
echo "<code style='word-break:break-all;'>" . $nouveau_hash . "</code>";