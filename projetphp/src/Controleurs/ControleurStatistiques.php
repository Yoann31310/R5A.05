<?php
session_start();

require_once 'config_api_jwt.php';

if (!verifier_authentification()) {
    header('Location: ../Vues/PageConnexion.php');
    exit();
}

require_once '../Modeles/Classes/Matchs.php';
require_once '../Modeles/Classes/Joueur.php';
require_once '../Modeles/Classes/Participation.php';

class ControleurStatistiques
{
    public function afficher()
    {
        // Récupérer toutes les statistiques via les classes qui appellent l'API
        $stats_matchs = Matchs::obtenir_stats_globales();
        $prochain_match = Matchs::obtenir_prochain_match();
        $dernier_resultat = Matchs::obtenir_dernier_resultat();

        $nb_joueurs = Joueur::compter_joueurs_actifs();
        $age_moyen = Joueur::calculer_age_moyen();

        $top_joueurs = Participation::obtenir_top_participations(5);
        $stats_joueurs = Participation::obtenir_stats_joueurs();

        // Afficher la vue
        require_once '../Vues/PageStatistiques.php';
    }
}

$controleur = new ControleurStatistiques();
$controleur->afficher();