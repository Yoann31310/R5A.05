<?php
require_once __DIR__ . '/../../Controleurs/config_api_jwt.php';

class Participation {
	private $id_joueurs;
	private $id_matchs;
	private $feuille_match;
	private $evaluation;
	private $nom_poste;
	private $est_capitaine;
	private $commentaire;

    public function __construct($data = null) {
        if ($data !== null) {
            if (isset($data['Id_Joueurs'])) {
                $this->id_joueurs = $data['Id_Joueurs'];
            } else {
                if (isset($data['id_joueurs'])) {
                    $this->id_joueurs = $data['id_joueurs'];
                } else {
                    $this->id_joueurs = null;
                }
            }

            if (isset($data['Id_Matchs'])) {
                $this->id_matchs = $data['Id_Matchs'];
            } else {
                if (isset($data['id_matchs'])) {
                    $this->id_matchs = $data['id_matchs'];
                } else {
                    $this->id_matchs = null;
                }
            }

            if (isset($data['feuille_match'])) {
                $this->feuille_match = $data['feuille_match'];
            } else {
                $this->feuille_match = null;
            }

            if (isset($data['evaluation'])) {
                $this->evaluation = $data['evaluation'];
            } else {
                $this->evaluation = null;
            }

            if (isset($data['nom_poste'])) {
                $this->nom_poste = $data['nom_poste'];
            } else {
                $this->nom_poste = null;
            }

            if (isset($data['est_Capitaine'])) {
                $this->est_capitaine = $data['est_Capitaine'];
            } else {
                if (isset($data['est_capitaine'])) {
                    $this->est_capitaine = $data['est_capitaine'];
                } else {
                    $this->est_capitaine = null;
                }
            }

            if (isset($data['commentaire'])) {
                $this->commentaire = $data['commentaire'];
            } else {
                $this->commentaire = null;
            }
        }
    }

    public function get_id_joueurs() {              return $this->id_joueurs; }
    public function get_id_matchs() {               return $this->id_matchs; }
    public function get_feuille_match() {           return $this->feuille_match; }
    public function get_evaluation() {              return $this->evaluation; }
    public function get_nom_poste() {               return $this->nom_poste; }
    public function get_est_capitaine() {           return $this->est_capitaine; }
    public function get_commentaire() {             return $this->commentaire; }

    public function set_id_joueurs($id) {           $this->id_joueurs = $id; }
    public function set_id_matchs($id) {            $this->id_matchs = $id; }
    public function set_feuille_match($role) {      $this->feuille_match = $role; }
    public function set_evaluation($note) {         $this->evaluation = $note; }
    public function set_nom_poste($poste) {         $this->nom_poste = $poste; }
    public function set_est_capitaine($est_cap) {   $this->est_capitaine = $est_cap; }
    public function set_commentaire($comm) {        $this->commentaire = $comm; }

    // Vérifier si le joueur est titulaire pour ce match
	public function est_titulaire() {
		return strtolower($this->feuille_match) === 'titulaire';
	}

	// Vérifier si le joueur est remplaçant pour ce match
	public function est_remplacant() {
		return strtolower($this->feuille_match) === 'remplaçant';
	}


    // Récupérer les participants d'un match avec infos joueur via l'API
    public static function recuperer_participants($id_match) {
        $reponse = appel_api('GET', urlApiGestionFeuilleMatch . "?id_match=" . $id_match);
        if (isset($reponse['data'])) {
            return $reponse['data'];
        }
        return array();
    }

    // Enregistrer toute la feuille de match
    public static function enregistrer_feuille($id_match, $participants) {
        $donnees = [
            'id_match' => $id_match,
            'participants' => $participants
        ];
        $reponse = appel_api('POST', urlApiGestionFeuilleMatch, $donnees);
        if (isset($reponse['status_code'])) {
            if ($reponse['status_code'] == 200 || $reponse['status_code'] == 201) {
                return true;
            }
        }
        return false;
    }

    // Vider la feuille de match
    public static function vider_feuille($id_match) {
        $donnees = [
            'id_match' => $id_match,
            'participants' => array()
        ];
        $reponse = appel_api('POST', urlApiGestionFeuilleMatch, $donnees);
        if (isset($reponse['status_code'])) {
            if ($reponse['status_code'] == 200) {
                return true;
            }
        }
        return false;
    }

    // Retirer un participant via l'API
    public static function retirer_participant($id_match, $id_joueur) {
        $url = urlApiGestionFeuilleMatch . "?id_match=" . $id_match . "&id_joueur=" . $id_joueur;
        $reponse = appel_api('DELETE', $url);
        if (isset($reponse['status_code'])) {
            if ($reponse['status_code'] == 200) {
                return true;
            }
        }
        return false;
    }

    // Évaluer un joueur via l'API
    public static function evaluer_joueur($id_match, $id_joueur, $note, $commentaire) {
        $url = urlApiGestionFeuilleMatch . "?id_match=" . $id_match . "&id_joueur=" . $id_joueur;
        $donnees = [
            'evaluation' => $note,
            'commentaire' => $commentaire
        ];
        $reponse = appel_api('PUT', $url, $donnees);
        if (isset($reponse['status_code'])) {
            if ($reponse['status_code'] == 200) {
                return true;
            }
        }
        return false;
    }

    // Statistiques top participations
    public static function obtenir_top_participations($limite = 5) {
        $reponse = appel_api('GET', urlApiGestionStats);
        if (isset($reponse['data']['par_joueur'])) {
            return array_slice($reponse['data']['par_joueur'], 0, $limite);
        }
        return array();
    }

    // Toutes les stats joueurs
    public static function obtenir_stats_joueurs() {
        $reponse = appel_api('GET', urlApiGestionStats);
        if (isset($reponse['data']['par_joueur'])) {
            return $reponse['data']['par_joueur'];
        }
        return array();
    }

    // Sélections consécutives pour un joueur
    public static function obtenir_selections_consecutives($id_joueur) {
        $reponse = appel_api('GET', urlApiGestionStats . "?id_joueur=" . $id_joueur);
        if (isset($reponse['data']['selections_consecutives'])) {
            return $reponse['data']['selections_consecutives'];
        }
        return 0;
    }
}