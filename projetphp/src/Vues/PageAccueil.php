<?php 

// Si la session n'existe pas, on redirige vers la connexion
if (!isset($_SESSION['id_entraineur'])) {
    header('Location: PageConnexion.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Gestion Handball</title>
    <link rel="stylesheet" href="../Vues/css/styleAccueil.css">
    <link rel="stylesheet" href="../Vues/css/styleMenu.css">
</head>
<body>

<?php 
    $page_active = 'accueil'; 
    require_once 'menu.php'; 
?>

    
<!-- le reste -->
    <main class="contenu-principal">
        <header class="entete-page">
            <h1>Bienvenue, <?php echo $_SESSION['prenom_entraineur'] . " " . $_SESSION['nom_entraineur']; ?> !</h1>
            <p>Voici l'état actuel de votre équipe de Handball.</p>
        </header>

        <section class="grille-statistiques">
            <div class="carte-info">
                <h3>Joueurs Actifs</h3>
                <p class="chiffre"><?php echo $nb_joueurs; ?></p>
            </div>
            <div class="carte-info">
                <h3>Prochain Match</h3>
                <?php if ($prochain_match): ?>
                    <p>Contre : <strong><?php echo htmlspecialchars($prochain_match->get_nom_equipe_adverse()); ?></strong></p>
                    <p>Date : <?php echo date('d/m/Y à H:i', strtotime($prochain_match->get_date_heure())); ?></p>
                <?php else: ?>
                    <p>Aucun match programmé</p>
                <?php endif; ?>
            </div>
            <div class="carte-info">
                <h3>Dernier Résultat</h3>
                <?php if ($dernier_resultat): ?>
                    <?php 
                        $classe_resultat = '';
                        if ($dernier_resultat->get_resultat() == 'Gagnée') {
                            $classe_resultat = 'resultat-gagne';
                        } else if ($dernier_resultat->get_resultat() == 'Perdue') {
                            $classe_resultat = 'resultat-perdu';
                        } else {
                            $classe_resultat = 'resultat-nul';
                        }
                    ?>
                    <p class="<?php echo $classe_resultat; ?>"><?php echo htmlspecialchars($dernier_resultat->get_resultat()); ?></p>
                    <p><small>Contre <?php echo htmlspecialchars($dernier_resultat->get_nom_equipe_adverse()); ?></small></p>
                <?php else: ?>
                    <p>Aucun résultat</p>
                <?php endif; ?>
            </div>
        </section>
    </main>

</body>
</html>