<?php
session_start();

// Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
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
        <title>Évaluation du Match - Gestion Handball</title>
        <link rel="stylesheet" href="css/styleAccueil.css">
</head>
<body>
        <?php include 'menu.php'; ?>

        <div class="conteneur-principal">
                <h1>Évaluation des joueurs</h1>
                <h2>Match contre <?php echo $match->get_nom_equipe_adverse(); ?></h2>

                <div class="carte-evaluation">
                        <?php if (empty($participants)): ?>
                                <p class="message-vide">Aucun joueur n'a participé à ce match.</p>
                        <?php else: ?>
                                <form action="../Controleurs/ControleurEvaluationMatch.php" method="POST">
                                        <input type="hidden" name="action" value="enregistrer">
                                        <input type="hidden" name="id_match" value="<?php echo $match->get_id_matchs(); ?>">

                                        <table class="tableau-evaluation">
                                                <thead>
                                                        <tr>
                                                                <th>Joueur</th>
                                                                <th>Rôle</th>
                                                                <th>Poste</th>
                                                                <th>Note (/10)</th>
                                                                <th>Commentaire</th>
                                                        </tr>
                                                </thead>
                                                <tbody>
                                                        <?php foreach ($participants as $p): ?>
                                                                <tr>
                                                                        <td>
                                                                                <strong><?php echo $p['nom'] . " " . $p['prenom']; ?></strong>
                                                                                <input type="hidden" name="tous_les_joueurs[]" value="<?php echo $p['Id_Joueurs']; ?>">
                                                                        </td>
                                                                        <td>
                                                                                <span class="badge-role <?php echo $p['feuille_match']; ?>">
                                                                                        <?php echo ucfirst($p['feuille_match']); ?>
                                                                                </span>
                                                                        </td>
                                                                        <td><?php echo $p['nom_poste']; ?></td>
                                                                        <td>
                                                                                <input 
                                                                                        type="number" 
                                                                                        name="note_<?php echo $p['Id_Joueurs']; ?>" 
                                                                                        min="0" 
                                                                                        max="10" 
                                                                                        step="0.01"
                                                                                        value="
                                                                                        <?php
                                                                                        if (!empty($p['evaluation'])) {
                                                                                                echo $p['evaluation'];
                                                                                        } else {
                                                                                                echo '';
                                                                                        }
                                                                                        ?>"
                                                                                        placeholder="0-10"
                                                                                        class="input-note"
                                                                                >
                                                                        </td>
                                                                        <td>
                                                                                <textarea 
                                                                                        name="commentaire_<?php echo $p['Id_Joueurs']; ?>" 
                                                                                        rows="2" 
                                                                                        placeholder="Commentaire sur la performance..."
                                                                                        class="input-commentaire"
                                                                                ><?php echo $p['commentaire']; ?></textarea>
                                                                        </td>
                                                                </tr>
                                                        <?php endforeach; ?>
                                                </tbody>
                                        </table>

                                        <div class="actions-evaluation">
                                                <button type="submit" class="bouton-valider">Enregistrer les évaluations</button>
                                                <a href="../Controleurs/ControleurMatch.php?action=details&id=<?php echo $match->get_id_matchs(); ?>" class="bouton-annuler">
                                                        ✗ Annuler
                                                </a>
                                        </div>
                                </form>
                        <?php endif; ?>
                </div>

                <a href="../Controleurs/ControleurMatch.php?action=details&id=<?php echo $match->get_id_matchs(); ?>" class="bouton-retour">
                        ← Retour aux détails du match
                </a>
        </div>

</body>
</html>