<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails Match</title>
    <link rel="stylesheet" href="../Vues/css/styleAccueil.css">
    <link rel="stylesheet" href="../Vues/css/styleTableaux.css">
</head>
<body>
    <?php $page_active = 'matchs'; require_once 'menu.php'; ?>

    <main class="contenu-principal">
        <h1>Match contre : <?php echo $match->get_nom_equipe_adverse(); ?></h1>

        <?php if (isset($_SESSION['erreur'])) { ?>
            <div style="color:red; background:#fee; padding:10px; border:1px solid red; margin-bottom:20px;">
                <?php echo $_SESSION['erreur']; unset($_SESSION['erreur']); ?>
            </div>
        <?php } ?>


        
        <?php if ($mode_match == "PRE_MATCH") { ?>
            
            <section style="background:#fff; padding:20px; border:1px solid #ddd; margin-bottom:20px;">
                <h3>1. Infos Logistiques</h3>
                <form action="../Controleurs/ControleurMatch.php" method="POST">
                    <input type="hidden" name="action" value="enregistrer_modif">
                    <input type="hidden" name="id" value="<?php echo $match->get_id_matchs(); ?>">
                    
                    <label>Date :</label> <input type="date" name="date" value="<?php echo explode(' ', $match->get_date_heure())[0]; ?>">
                    <label>Heure :</label> <input type="time" name="heure" value="<?php echo explode(' ', $match->get_date_heure())[1]; ?>">
                    <label>Adversaire :</label> <input type="text" name="adversaire" value="<?php echo $match->get_nom_equipe_adverse(); ?>">
                    <label>Lieu :</label> 
                    <select name="lieu">
                        <option value="Domicile" <?php if($match->get_lieu()=='Domicile') echo 'selected'; ?>>Domicile</option>
                        <option value="Extérieur" <?php if($match->get_lieu()=='Extérieur') echo 'selected'; ?>>Extérieur</option>
                    </select>
                    <label>Adresse :</label> <input type="text" name="adresse" value="<?php echo $match->get_adresse(); ?>">

                    <button type="submit">Enregistrer Infos</button>
                </form>

                <div style="text-align:right; margin-bottom:10px;">
                    <form action="../Controleurs/ControleurMatch.php" method="POST" style="display:inline;"
                        onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer définitivement ce match et toutes ses données ?');">
                        <input type="hidden" name="action" value="supprimer">
                        <input type="hidden" name="id" value="<?php echo $match->get_id_matchs(); ?>">
                        <button type="submit" style="background:#e74c3c; color:white; padding:8px 15px; border:none; cursor:pointer;">
                        Supprimer le match </button>
                    </form>
        </div>
            </section>

            <section style="background:#fff; padding:20px; border:1px solid #ddd;">
                <h3>2. Feuille de Match</h3>
                <div style="background:#e8f4fd; padding:10px; margin-bottom:10px;">Quotas : 5 à 7 titulaires, Max 7 remplaçants.</div>

                <form action="../Controleurs/ControleurMatch.php" method="POST">
                    <input type="hidden" name="action" value="enregistrer_feuille">
                    <input type="hidden" name="id_match" value="<?php echo $match->get_id_matchs(); ?>">
                    
                    <table class="tableau-donnees">
                        <tr><th>Joueur</th><th>Rôle</th><th>Poste</th></tr>
                        <?php foreach ($joueurs_actifs as $j) { 
                            // Trouver la participation existante
                            $role_actuel = 'non_partant';
                            $poste_actuel = 'Gardien';
                            foreach ($participants as $p) {
                                if ($p['Id_Joueurs'] == $j->get_id_joueurs()) {
                                    $role_actuel = $p['feuille_match'];
                                    $poste_actuel = $p['nom_poste'];
                                    break;
                                }
                            }
                        ?>
                            <tr>
                                <input type="hidden" name="tous_les_joueurs[]" value="<?php echo $j->get_id_joueurs(); ?>">
                                <td><?php echo strtoupper($j->get_nom()) . " " . $j->get_prenom(); ?></td>
                                <td>
                                    <select name="role_<?php echo $j->get_id_joueurs(); ?>">
                                        <option value="non_partant" <?php if($role_actuel == 'non_partant') echo 'selected'; ?>>-- Ne participe pas --</option>
                                        <option value="titulaire" <?php if($role_actuel == 'titulaire') echo 'selected'; ?>>Titulaire</option>
                                        <option value="remplaçant" <?php if($role_actuel == 'remplaçant') echo 'selected'; ?>>Remplaçant</option>
                                    </select>
                                </td>
                                <td>
                                    <select name="poste_<?php echo $j->get_id_joueurs(); ?>">
                                        <option value="Gardien" <?php if($poste_actuel == 'Gardien') echo 'selected'; ?>>Gardien</option>
                                        <option value="Pivot" <?php if($poste_actuel == 'Pivot') echo 'selected'; ?>>Pivot</option>
                                        <option value="Demi-centre" <?php if($poste_actuel == 'Demi-centre') echo 'selected'; ?>>Demi-centre</option>
                                        <option value="Arrière gauche" <?php if($poste_actuel == 'Arrière gauche') echo 'selected'; ?>>Arrière gauche</option>
                                        <option value="Arrière droit" <?php if($poste_actuel == 'Arrière droit') echo 'selected'; ?>>Arrière droit</option>
                                        <option value="Ailier gauche" <?php if($poste_actuel == 'Ailier gauche') echo 'selected'; ?>>Ailier gauche</option>
                                        <option value="Ailier droit" <?php if($poste_actuel == 'Ailier droit') echo 'selected'; ?>>Ailier droit</option>
                                    </select>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                    <button type="submit" style="margin-top:10px; background:#27ae60; color:white; padding:10px;">Valider l'équipe</button>
                </form>
            </section>

        <?php } else { ?>
            <section style="background:#fff; padding:20px; border:1px solid #ddd; margin-bottom:20px;">
                <h3>Résultat Final</h3>
                <form action="../Controleurs/ControleurMatch.php" method="POST">
                    <input type="hidden" name="action" value="enregistrer_modif">
                    <input type="hidden" name="id" value="<?php echo $match->get_id_matchs(); ?>">
                    <input type="hidden" name="date" value="<?php echo explode(' ', $match->get_date_heure())[0]; ?>">
                    <input type="hidden" name="heure" value="<?php echo explode(' ', $match->get_date_heure())[1]; ?>">
                    <input type="hidden" name="adversaire" value="<?php echo $match->get_nom_equipe_adverse(); ?>">
                    <input type="hidden" name="lieu" value="<?php echo $match->get_lieu(); ?>">
                    <input type="hidden" name="adresse" value="<?php echo $match->get_adresse(); ?>">

                    <select name="resultat">
                        <option value="gagnée" <?php if($match->get_resultat()=="gagnée") echo "selected"; ?>>Gagnée</option>
                        <option value="perdue" <?php if($match->get_resultat()=="perdue") echo "selected"; ?>>perdue</option>
                        <option value="égalité" <?php if($match->get_resultat()=="égalité") echo "selected"; ?>>Égalité</option>
                    </select>
                    <button type="submit">Valider Score</button>
                </form>
            </section>

            <section style="background:#fff; padding:20px; border:1px solid #ddd;">
                <h3>Évaluation des joueurs</h3>
                
                <?php if (empty($participants)) { ?>
                    <p style="text-align:center; color:#999; font-style:italic;">Aucun joueur sélectionné pour ce match.</p>
                <?php } else { ?>
                    
                    <form action="../Controleurs/ControleurEvaluationMatch.php" method="POST">
                        <input type="hidden" name="action" value="enregistrer_evaluation">
                        <input type="hidden" name="id_match" value="<?php echo $match->get_id_matchs(); ?>">
                        
                        <table class="tableau-donnees">
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
                                <?php foreach ($participants as $p) { ?>
                                    <tr>
                                        <input type="hidden" name="id_joueurs[]" value="<?php echo $p['Id_Joueurs']; ?>">
                                        
                                        <td><strong><?php echo strtoupper($p['nom']) . " " . $p['prenom']; ?></strong></td>
                                        <td><?php echo ucfirst($p['feuille_match']); ?></td>
                                        <td><?php echo $p['nom_poste']; ?></td>
                                        <td>
                                            <input type="number" name="evaluation_<?php echo $p['Id_Joueurs']; ?>" 
                                                   min="0" max="10" step="0.01" 
                                                   value="<?php if (isset($p['evaluation'])) { 
                                                        echo $p['evaluation']; } 
                                                        else { echo ''; } ?>"
                                                   style="width:60px;">
                                        </td>
                                        <td>
                                            <textarea name="commentaire_<?php echo $p['Id_Joueurs']; ?>" 
                                                      rows="2" 
                                                      style="width:100%;"><?php if (isset($p['commentaire'])) { 
                                                        echo $p['commentaire']; } 
                                                        else { echo ''; } ?>
                                                    </textarea>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        
                        <button type="submit" style="margin-top:15px; background:#27ae60; color:white; border:none; padding:12px 30px; border-radius:5px; cursor:pointer; font-size:16px; font-weight:bold;">
                            Enregistrer les évaluations
                        </button>
                    </form>
                    
                <?php } ?>
            </section>
        <?php } ?>
    </main>
</body>
</html>