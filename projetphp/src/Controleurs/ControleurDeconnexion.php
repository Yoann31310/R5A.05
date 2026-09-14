<?php
session_start();

// Détruire toutes les données de session
$_SESSION = array();

// Détruire la session côté serveur
session_destroy();

// Supprimer le cookie de session
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-3600, '/');
}

// Rediriger vers la page de connexion
header('Location: ../Vues/PageConnexion.php');
exit();
?>