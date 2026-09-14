<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Calendrier</title>
    <link rel="stylesheet" href="../Vues/css/styleAccueil.css">
    <link rel="stylesheet" href="../Vues/css/styleTableaux.css">
</head>
<body>
    <?php $page_active = 'matchs'; require_once 'menu.php'; ?>

    <main class="contenu-principal">
        <h1>Calendrier des Rencontres</h1>

        <?php if (isset($_SESSION['erreur'])) { ?>
            <div style="color:red; background:#fee; padding:10px; border:1px solid red; margin-bottom:20px;">
                <?php echo $_SESSION['erreur']; unset($_SESSION['erreur']); ?>
            </div>
        <?php } ?>

        <table class="tableau-donnees">
            <thead>
                <tr>
                    <th>Date & Heure</th><th>Adversaire</th><th>Lieu</th><th>Résultat</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr style="background:#e8f8f5;">
                    <form action="ControleurMatch.php" method="POST">
                        <input type="hidden" name="action" value="valider_ajout">
                        <td><input type="date" name="date" required><input type="time" name="heure" required></td>
                        <td><input type="text" name="adversaire" placeholder="Adversaire" required></td>
                        <td><select name="lieu"><option value="Domicile">Domicile</option><option value="Extérieur">Extérieur</option></select></td>
                        <td>-</td>
                        <td><button type="submit">✚ Programmer</button></td>
                    </form>
                </tr>
                <?php foreach ($liste_matchs as $m) { ?>
                    <tr>
                        <td><?php echo $m->get_date_heure(); ?></td>
                        <td><?php echo $m->get_nom_equipe_adverse(); ?></td>
                        <td><?php echo $m->get_lieu(); ?></td>
                        <td>
                            <?php 
                            if ($m->get_resultat()) {
                                echo $m->get_resultat();
                            } else {
                                echo "<em>À venir</em>";
                            }
                            ?>
                        </td>                        
                        <td>
                            <a href="ControleurMatch.php?action=details&id=<?php echo $m->get_id_matchs(); ?>" 
                               style="background:#3498db; color:white; padding:5px 10px; text-decoration:none;">Gérer</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </main>
</body>
</html>