<?php
require_once __DIR__ . '/../../Controleurs/config_api_jwt.php';

class Matchs {
	private $id_matchs;
	private $date_heure;
	private $nom_equipe_adverse;
	private $lieu;
	private $adresse;
	private $resultat;

	
    public function __construct($donnees = null) {
        if ($donnees !== null) {
            // Gestion de l'identifiant du match
            if (isset($donnees['Id_Matchs'])) {
                $this->id_matchs = $donnees['Id_Matchs'];
            } else {
                if (isset($donnees['id_matchs'])) {
                    $this->id_matchs = $donnees['id_matchs'];
                } else {
                    $this->id_matchs = null;
                }
            }

            // Gestion de la date et l'heure
            if (isset($donnees['Date_heure'])) {
                $this->date_heure = $donnees['Date_heure'];
            } else {
                if (isset($donnees['date_heure'])) {
                    $this->date_heure = $donnees['date_heure'];
                } else {
                    $this->date_heure = null;
                }
            }

            // Gestion de l'équipe adverse
            if (isset($donnees['nom_equipe_adverse'])) {
                $this->nom_equipe_adverse = $donnees['nom_equipe_adverse'];
            } else {
                $this->nom_equipe_adverse = null;
            }

            // Gestion du lieu
            if (isset($donnees['lieu'])) {
                $this->lieu = $donnees['lieu'];
            } else {
                $this->lieu = null;
            }

            // Gestion de l'adresse
            if (isset($donnees['adresse'])) {
                $this->adresse = $donnees['adresse'];
            } else {
                $this->adresse = null;
            }

            // Gestion du résultat du match
            if (isset($donnees['resultat'])) {
                $this->resultat = $donnees['resultat'];
            } else {
                $this->resultat = null;
            }
        }
    }

    public function get_id_matchs() {               return $this->id_matchs; }
    public function get_date_heure() {              return $this->date_heure; }
    public function get_nom_equipe_adverse() {      return $this->nom_equipe_adverse; }
    public function get_lieu() {                    return $this->lieu; }
    public function get_adresse() {                 return $this->adresse; }
    public function get_resultat() {                return $this->resultat; }

    public function set_id_matchs($id) {            $this->id_matchs = $id; }
    public function set_date_heure($date) {         $this->date_heure = $date; }
    public function set_nom_equipe_adverse($nom) {  $this->nom_equipe_adverse = $nom; }
    public function set_lieu($lieu) {               $this->lieu = $lieu; }
    public function set_adresse($adresse) {         $this->adresse = $adresse; }
    public function set_resultat($resultat) {       $this->resultat = $resultat; }

    // Vérifier si le match est déjà passé
	public function est_passe() {
		return strtotime($this->date_heure) < time();
	}

	public function est_a_venir() {
		return strtotime($this->date_heure) >= time();
    }

    public function est_termine() {                 
        return $this->resultat !== null; 
    }
    
	public function est_victoire() {
		return strtolower($this->resultat) === 'gagnée';
	}
    
    // Appels API
    public static function recuperer_tout() {
        $reponse = appel_api('GET', urlApiGestionMatch);
        $liste_matchs = [];
        if (isset($reponse['data'])) {
            if (is_array($reponse['data'])) {
                foreach ($reponse['data'] as $donnees_match) {
                    $liste_matchs[] = new Matchs($donnees_match);
                }
            }
        }
        return $liste_matchs;
    }

    public static function trouver_par_id($identifiant) {
        $reponse = appel_api('GET', urlApiGestionMatch . "?id=" . $identifiant);
        if (isset($reponse['data'])) {
            return new Matchs($reponse['data']);
        }
        return null;
    }

    public static function ajouter(Matchs $un_match) {
        $donnees_a_envoyer = [
            'date_heure' => $un_match->get_date_heure(),
            'nom_equipe_adverse' => $un_match->get_nom_equipe_adverse(),
            'lieu' => $un_match->get_lieu(),
            'adresse' => $un_match->get_adresse()
        ];
        $reponse = appel_api('POST', urlApiGestionMatch, $donnees_a_envoyer);
        
        if (isset($reponse['status_code'])) {
            if ($reponse['status_code'] == 201 || $reponse['status_code'] == 200) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    public static function modifier($identifiant, Matchs $match_modifie) {
        $donnees_a_envoyer = [
            'date_heure' =>         $match_modifie->get_date_heure(),
            'nom_equipe_adverse' => $match_modifie->get_nom_equipe_adverse(),
            'lieu' =>               $match_modifie->get_lieu(),
            'adresse' =>            $match_modifie->get_adresse(),
            'resultat' =>           $match_modifie->get_resultat()
        ];
        $reponse = appel_api('PUT', urlApiGestionMatch . "?id=" . $identifiant, $donnees_a_envoyer);
        
        if (isset($reponse['status_code'])) {
            if ($reponse['status_code'] == 200) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    public static function supprimer($identifiant) {
        $reponse = appel_api('DELETE', urlApiGestionMatch . "?id=" . $identifiant);
        
        if (isset($reponse['status_code'])) {
            if ($reponse['status_code'] == 200) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    // Statistiques globales
    public static function obtenir_stats_globales() {
        $reponse = appel_api('GET', urlApiGestionStats);
        if (isset($reponse['data']['globales'])) {
            return $reponse['data']['globales'];
        }
        return null;
    }

    // Prochain match
    public static function obtenir_prochain_match() {
        $reponse = appel_api('GET', urlApiGestionStats);
        if (isset($reponse['data']['prochain_match'])) {
            if (is_array($reponse['data']['prochain_match'])) {
                return new Matchs($reponse['data']['prochain_match']);
            }
        }
        return null;
    }

    // Dernier résultat
    public static function obtenir_dernier_resultat() {
        $reponse = appel_api('GET', urlApiGestionStats);
        if (isset($reponse['data']['dernier_resultat'])) {
            if (is_array($reponse['data']['dernier_resultat'])) {
                return new Matchs($reponse['data']['dernier_resultat']);
            }
        }
        return null;
    }
}