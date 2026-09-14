<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques - Gestion Handball</title>
<link rel="stylesheet" href="../Vues/css/styleAccueil.css">
<link rel="stylesheet" href="../Vues/css/styleStatistiques.css">
<link rel="stylesheet" href="../Vues/css/styleTableaux.css">
</head>
<body>

<?php 
    $page_active = 'statistiques'; 
    require_once 'menu.php'; 
?>

<main class="contenu-principal">
    <header class="entete-page">
        <h1>Statistiques de l'équipe</h1>
        <p>Vue d'ensemble des performances</p>
    </header>

    <div class="grille-statistiques">
        <div class="carte-info">
            <h3>Total Matchs</h3>
            <div class="chiffre"><?php echo $stats_matchs['total_matchs']; ?></div>
        </div>
        
        <div class="carte-info">
            <h3>Victoires</h3>
            <div class="chiffre victoire"><?php echo $stats_matchs['victoires']; ?></div>
        </div>
        
        <div class="carte-info">
            <h3>Defaites</h3>
            <div class="chiffre defaite"><?php echo $stats_matchs['defaites']; ?></div>
        </div>
        
        <div class="carte-info">
            <h3>Matchs Nuls</h3>
            <div class="chiffre nul"><?php echo $stats_matchs['nuls']; ?></div>
        </div>
        
        <div class="carte-info">
            <h3>Taux de Victoire</h3>
            <div class="chiffre"><?php echo $stats_matchs['pourcentage_victoires']; ?>%</div>
        </div>
        
        <div class="carte-info">
            <h3>Matchs a Venir</h3>
            <div class="chiffre"><?php echo $stats_matchs['a_venir']; ?></div>
        </div>
        
        <div class="carte-info">
            <h3>Joueurs Actifs</h3>
            <div class="chiffre"><?php echo $nb_joueurs; ?></div>
        </div>
        
        <div class="carte-info">
            <h3>Age Moyen</h3>
            <div class="chiffre"><?php echo $age_moyen; ?> ans</div>
        </div>
    </div>

    <div class="section-stats">
        <h2>Repartition des resultats</h2>
        <?php 
            $total_joues = $stats_matchs['victoires'] + $stats_matchs['defaites'] + $stats_matchs['nuls'];
            
            if ($total_joues > 0) {
                $pct_victoires = ($stats_matchs['victoires'] / $total_joues) * 100;
                $pct_nuls = ($stats_matchs['nuls'] / $total_joues) * 100;
                $pct_defaites = ($stats_matchs['defaites'] / $total_joues) * 100;
        ?>
            <div class="barre-resultats">
                <div class="segment-barre victoire" style="width: <?php echo $pct_victoires; ?>%;">
                    <?php 
                        if ($pct_victoires > 10) {
                            echo round($pct_victoires) . '%';
                        }
                    ?>
                </div>
                <div class="segment-barre nul" style="width: <?php echo $pct_nuls; ?>%;">
                    <?php 
                        if ($pct_nuls > 10) {
                            echo round($pct_nuls) . '%';
                        }
                    ?>
                </div>
                <div class="segment-barre defaite" style="width: <?php echo $pct_defaites; ?>%;">
                    <?php 
                        if ($pct_defaites > 10) {
                            echo round($pct_defaites) . '%';
                        }
                    ?>
                </div>
            </div>
            
            <div class="legende-barre">
                <span class="legende-item victoire">Victoires (<?php echo $stats_matchs['victoires']; ?>)</span>
                <span class="legende-item nul">Nuls (<?php echo $stats_matchs['nuls']; ?>)</span>
                <span class="legende-item defaite">Defaites (<?php echo $stats_matchs['defaites']; ?>)</span>
            </div>
        <?php } else { ?>
            <p>Aucun match joue pour le moment.</p>
        <?php } ?>
    </div>

    <div class="section-stats">
        <h2>Prochain Match</h2>
        <?php if ($prochain_match): ?>
            <div class="bloc-match">
                <p><strong>Adversaire :</strong> <?php echo htmlspecialchars($prochain_match->get_nom_equipe_adverse()); ?></p>
                <p><strong>Date :</strong> <?php echo date('d/m/Y a H:i', strtotime($prochain_match->get_date_heure())); ?></p>
                <p><strong>Lieu :</strong> <?php echo htmlspecialchars($prochain_match->get_lieu()); ?></p>
                <?php if ($prochain_match->get_adresse()): ?>
                    <p><strong>Adresse :</strong> <?php echo htmlspecialchars($prochain_match->get_adresse()); ?></p>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <p>Aucun match programme.</p>
        <?php endif; ?>
    </div>

    <div class="section-stats">
        <h2>Dernier Resultat</h2>
        <?php if ($dernier_resultat): ?>
            <div class="bloc-match">
                <p><strong>Contre :</strong> <?php echo htmlspecialchars($dernier_resultat->get_nom_equipe_adverse()); ?></p>
                <p><strong>Date :</strong> <?php echo date('d/m/Y', strtotime($dernier_resultat->get_date_heure())); ?></p>
                <p><strong>Resultat :</strong> 
                    <?php 
                        $classe_resultat = '';
                        if ($dernier_resultat->get_resultat() == 'Gagnée') {
                            $classe_resultat = 'victoire';
                        } else if ($dernier_resultat->get_resultat() == 'Perdue') {
                            $classe_resultat = 'defaite';
                        } else {
                            $classe_resultat = 'nul';
                        }
                    ?>
                    <span class="resultat-match <?php echo $classe_resultat; ?>">
                        <?php echo htmlspecialchars($dernier_resultat->get_resultat()); ?>
                    </span>
                </p>
            </div>
        <?php else: ?>
            <p>Aucun resultat disponible.</p>
        <?php endif; ?>
    </div>

    <div class="section-stats">
        <h2>Top 5 Joueurs par participations</h2>
        <?php if (!empty($top_joueurs)): ?>
            <ul class="liste-joueurs">
                <?php 
                    $position = 1;
                    foreach ($top_joueurs as $joueur): 
                ?>
                    <li>
                        <span>
                            <strong><?php echo $position; ?>.</strong> 
                            <?php echo strtoupper(htmlspecialchars($joueur['nom'])) . ' ' . htmlspecialchars($joueur['prenom']); ?>
                        </span>
                        <span class="badge-participation">
                            <?php 
                                echo $joueur['nb_participations'];
                                if ($joueur['nb_participations'] > 1) {
                                    echo ' matchs';
                                } else {
                                    echo ' match';
                                }
                            ?>
                        </span>
                    </li>
                <?php 
                    $position++;
                    endforeach; 
                ?>
            </ul>
        <?php else: ?>
            <p>Aucune donnee de participation disponible.</p>
        <?php endif; ?>
    </div>
<div class="section-stats">
    <h2>Statistiques par Joueur</h2>
    <table class="tableau-donnees">
        <thead>
            <tr>
                <th>Joueur</th>
                <th>Statut</th>
                <th>Poste Préféré</th>
                <th>Titularisations</th>
                <th>Remplacements</th>
                <th>Moyenne Notes</th>
                <th>% Victoires</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($stats_joueurs)): ?>
                <?php foreach ($stats_joueurs as $j): ?>
                    <tr>
                        <td><strong><?php echo strtoupper(htmlspecialchars($j['nom'])) . ' ' . htmlspecialchars($j['prenom']); ?></strong></td>
                        <td><?php echo htmlspecialchars($j['statut']); ?></td>
                        <td><?php echo $j['poste_prefere'] ?? '-'; ?></td>
                        <td><?php echo $j['nb_titularisations']; ?></td>
                        <td><?php echo $j['nb_remplacements']; ?></td>
                        <td><?php echo $j['moyenne_evaluations'] ?? '-'; ?></td>
                        <td><?php echo $j['pct_victoires'] ? round($j['pct_victoires']) . '%' : '-'; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7">Aucune donnée disponible</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</main>

</body>
</html>