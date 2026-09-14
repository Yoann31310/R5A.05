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

class ControleurMatch
{
	// Vérifier que les quotas de la feuille de match sont respectés
	private function valider_quotas_feuille($participants)
	{
		$nb_titulaires = 0;
		$nb_remplacants = 0;

		// Compter les titulaires et remplaçants
		foreach ($participants as $p) {
			if ($p['role'] == "titulaire") {
				$nb_titulaires++;
			} else if ($p['role'] == "remplaçant") {
				$nb_remplacants++;
			}
		}

		// Vérifier les quotas réglementaires
		if ($nb_titulaires < 5) {
			return "Nombre de titulaires insuffisant : 5 minimum (actuellement : $nb_titulaires).";
		}
		if ($nb_titulaires > 7) {
			return "Trop de titulaires : 7 maximum (actuellement : $nb_titulaires).";
		}
		if ($nb_remplacants > 7) {
			return "Trop de remplaçants : 7 maximum (actuellement : $nb_remplacants).";
		}

		return "OK";
	}

	// Vérifier que la date-heure du match n'est pas dans le passé
	private function valider_date_heure($date, $heure)
	{
		$date_heure_match = strtotime($date . " " . $heure);
		$maintenant = time();

		if ($date_heure_match < $maintenant) {
			return "Impossible de créer/modifier un match dans le passé.";
		}

		return "OK";
	}

	// Afficher la liste de tous les matchs
	public function lister()
	{
		$liste_matchs = Matchs::recuperer_tout();
		require_once '../Vues/PageListeMatchs.php';
	}

	// Afficher les détails d'un match
	public function details()
	{
		// Vérifier que l'ID du match est fourni
		if (isset($_GET['id'])) {
			$id_match = (int) $_GET['id'];

			// Récupérer le match et ses participants
			$match = Matchs::trouver_par_id($id_match);
			$joueurs_actifs = Joueur::recuperer_actifs();
			$participants = Participation::recuperer_participants($id_match);

			// Déterminer si le match est passé ou à venir
			$date_match = strtotime($match->get_date_heure());
			if ($date_match < time()) {
				$mode_match = "POST_MATCH";
			} else {
				$mode_match = "PRE_MATCH";
			}

			require_once '../Vues/PageDetailsMatch.php';
		}
	}

	// Ajouter un nouveau match
	public function ajouter()
	{
		// Combiner la date et l'heure pour créer le datetime
		$date = $_POST['date'];
		$heure = $_POST['heure'];

		// Valider que la date-heure n'est pas dans le passé
		$erreur = $this->valider_date_heure($date, $heure);
		if ($erreur != "OK") {
			$_SESSION['erreur'] = $erreur;
			header('Location: ControleurMatch.php');
			exit();
		}

		$date_heure = $date . " " . $heure . ":00";

		// Créer un objet Match avec les données du formulaire
		$nouveau_match = new Matchs();
		$nouveau_match->set_date_heure($date_heure);
		$nouveau_match->set_nom_equipe_adverse($_POST['adversaire']);
		$nouveau_match->set_lieu($_POST['lieu']);
		$nouveau_match->set_adresse($_POST['adresse']);

		// Enregistrer le match et vérifier le succès via l'API
		if (Matchs::ajouter($nouveau_match)) {
            $_SESSION['message'] = "Le match contre " . $_POST['adversaire'] . " a été ajouté.";
        } else {
            $_SESSION['erreur'] = "Échec de l'ajout du match via l'API (problème serveur ou base de données).";
        }

		header('Location: ControleurMatch.php');
		exit();
	}

	// Modifier un match existant
	public function modifier()
	{
		$id = (int) $_POST['id'];
		$date = $_POST['date'];
		$heure = $_POST['heure'];

		// Valider que la date-heure n'est pas dans le passé
		$erreur = $this->valider_date_heure($date, $heure);
		if ($erreur != "OK") {
			$_SESSION['erreur'] = $erreur;
			header("Location: ControleurMatch.php?action=details&id=$id");
			exit();
		}

		$date_heure = $date . " " . $heure;

		// récupération du résultat si disponible dans le formulaire
		if (isset($_POST['resultat'])) {
			$resultat_match = $_POST['resultat'];
		} else {
			$resultat_match = NULL;
		}

		// Créer un objet Match
		$match_modifie = new Matchs();
		$match_modifie->set_date_heure($date_heure);
		$match_modifie->set_nom_equipe_adverse($_POST['adversaire']);
		$match_modifie->set_lieu($_POST['lieu']);
		$match_modifie->set_adresse($_POST['adresse']);
		$match_modifie->set_resultat($resultat_match);

		// Enregistrer les modifications
		if (Matchs::modifier($id, $match_modifie)) {
            $_SESSION['message'] = "Les modifications ont été enregistrées.";
        } else {
            $_SESSION['erreur'] = "Impossible de modifier le match via l'API.";
        }

		header("Location: ControleurMatch.php?action=details&id=$id");
		exit();
	}

	// Enregistrer la feuille de match (sélection titulaires/remplaçants)
	public function valider_feuille()
	{
		$id_match = (int) $_POST['id_match'];
		$tous_les_ids = $_POST['tous_les_joueurs'];

		$liste_finale = array();

		// Filtrer les joueurs qui participent
		foreach ($tous_les_ids as $id_j) {
			$role_choisi = $_POST['role_' . $id_j];

			if ($role_choisi != "non_partant") {
				$liste_finale[] = [
					'id_joueur' => $id_j,
					'role' => $role_choisi,
					'poste' => $_POST['poste_' . $id_j]
				];
			}
		}

		// Valider les quotas réglementaires
		$erreur = $this->valider_quotas_feuille($liste_finale);
		if ($erreur != "OK") {
			$_SESSION['erreur'] = $erreur;
			header("Location: ControleurMatch.php?action=details&id=$id_match");
			exit();
		}

		// Enregistrer toute la feuille de match en une seule fois via l'API
		if (Participation::enregistrer_feuille($id_match, $liste_finale)) {
            $_SESSION['message'] = "La feuille de match a été mise à jour.";
        } else {
            $_SESSION['erreur'] = "Impossible d'enregistrer la feuille de match via l'API.";
        }

		header("Location: ControleurMatch.php");
		exit();
	}

	// Supprimer un match et toutes ses participations
	public function supprimer()
	{
		$id = (int) $_POST['id'];

		// Supprimer d'abord toutes les participations et le match
		if (Matchs::supprimer($id)) {
            $_SESSION['message'] = "Le match a bien été supprimé.";
        } else {
            $_SESSION['erreur'] = "Erreur lors de la suppression du match via l'API.";
        }
		header('Location: ControleurMatch.php');
		exit();
	}
}

$gestionnaire = new ControleurMatch();

// Déterminer l'action demandée (par défaut : lister)
$action = "lister";
if (isset($_GET['action'])) {
	$action = $_GET['action'];
} else if (isset($_POST['action'])) {
	$action = $_POST['action'];
}

// Exécuter l'action correspondante
switch ($action) {
	case "details":
		$gestionnaire->details();
		break;
	case "valider_ajout":
		$gestionnaire->ajouter();
		break;
	case "enregistrer_modif":
		$gestionnaire->modifier();
		break;
	case "enregistrer_feuille":
		$gestionnaire->valider_feuille();
		break;
	case "supprimer":
		$gestionnaire->supprimer();
		break;
	default:
		$gestionnaire->lister();
		break;
}