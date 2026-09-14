<?php 
// Initialisation de la session pour les messages d'erreur
session_start(); 
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Gestion Handball</title>
    <link rel="stylesheet" href="css/styleConnexion.css">
</head>

<body>
    <div class="conteneur-connexion">
        <form action="../Controleurs/ControleurConnexion.php" method="POST" class="formulaire-connexion">
            
            <img src="css/imageCoach.png" alt="Logo Handball" class="logo-club" width="100">
            
            <h2>Espace Entraîneur</h2>
            
            <?php 
            // Affichage du message d'erreur si présent en session
            if(isset($_SESSION['erreur'])): ?>
                <div class="message-erreur">
                    <?php 
                        echo $_SESSION['erreur']; 
                        // Supprimer le message après affichage
                        unset($_SESSION['erreur']); 
                    ?>
                </div>
            <?php endif; ?>

            <div class="groupe-saisie">
                <label for="identifiant">Identifiant (Numéro)</label>
                <input type="text" name="identifiant" id="identifiant" placeholder="Ex: 1573357" required>
            </div>

            <div class="groupe-saisie">
                <label for="mot_de_passe">Mot de passe</label>
                <input type="password" name="mdp" id="mot_de_passe" placeholder="Votre mot de passe" required>
            </div>

            <button type="submit" class="bouton-validation">Se connecter</button>
            
            <div class="footer-formulaire">
                <a href="#">Mot de passe oublié ?</a>
            </div>
        </form>
    </div>
</body>
</html>