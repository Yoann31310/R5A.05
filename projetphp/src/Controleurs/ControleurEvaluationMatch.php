<?php
session_start();

require_once 'config_api_jwt.php';

if (!verifier_authentification()) {
        header('Location: ../Vues/PageConnexion.php');
        exit();
}

require_once '../Modeles/Classes/Matchs.php';
require_once '../Modeles/Classes/Participation.php';

class ControleurEvaluationMatch
{

        // Afficher la page d'évaluation d'un match
        public function afficher_evaluation()
        {
                // Vérifier que l'ID du match est fourni
                if (isset($_GET['id'])) {
                        $id_match = (int) $_GET['id'];

                        // Récupérer le match et ses participants
                        $match = Matchs::trouver_par_id($id_match);
                        $participants = Participation::recuperer_participants($id_match);

                        require_once '../Vues/PageEvaluationMatch.php';
                }
        }

        // Enregistrer les évaluations de tous les joueurs
        public function enregistrer_evaluations()
        {
                $id_match = (int) $_POST['id_match'];
                $tous_les_ids = $_POST['id_joueurs'];

                // Parcourir tous les joueurs et enregistrer leur évaluation
                foreach ($tous_les_ids as $id_j) {
                        // Récupérer les valeurs depuis le formulaire
                        if (isset($_POST['evaluation_' . $id_j]) && $_POST['evaluation_' . $id_j] !== '') {
                                $note = (float) $_POST['evaluation_' . $id_j];
                        } else {
                                $note = null;
                        }

                        if (isset($_POST['commentaire_' . $id_j])) {
                                $commentaire = $_POST['commentaire_' . $id_j];
                        } else {
                                $commentaire = '';
                        }

                        // Enregistrer l'évaluation dans la base de données seulement si une note est fournie
                        if ($note !== null) {
                                Participation::evaluer_joueur($id_match, $id_j, $note, $commentaire);
                        }
                }

                // Rediriger vers la liste des matchs
                header("Location: ControleurMatch.php");
                exit();
        }
}


$gestionnaire = new ControleurEvaluationMatch();
$action = "afficher";
if (isset($_GET['action'])) {
        $action = $_GET['action'];
} else if (isset($_POST['action'])) {
        $action = $_POST['action'];
}

switch ($action) {
        case "enregistrer_evaluation":
                $gestionnaire->enregistrer_evaluations();
                break;
        default:
                $gestionnaire->afficher_evaluation();
                break;
}