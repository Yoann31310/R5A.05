<?php
session_start();

require_once 'config_api_jwt.php';

if (!verifier_authentification()) {
        header('Location: ../Vues/PageConnexion.php');
        exit();
}

require_once __DIR__ . '/../Modeles/Classes/Joueur.php';

class ControleurJoueur
{
        // Vérifier le format du nom ou prénom (lettres avec accents, >=3 caractères)
        private function verifier_format_texte($chaine) {
                // Mesurer la longueur de la chaîne en UTF-8 pour gérer les accents
                $longueur = mb_strlen($chaine, 'UTF-8');

                if ($longueur < 3) {
                        return "trop court (min 3 caractères)";
                }

                // Caractères autorisés (lettres avec accents et tiret)
                $autorises = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ éèàçîïôûùêëÂÀÉÈÊËÎÏÔÛÙÇ-";

                for ($i = 0; $i < $longueur; $i++) {
                        // Extraire un caractère à la position i en UTF-8
                        $lettre = mb_substr($chaine, $i, 1, 'UTF-8');
                        // Vérifier si le caractère est dans la liste des autorisés
                        if (strpos($autorises, $lettre) === false) {
                                return "caractère interdit : [" . $lettre . "] à la position " . ($i + 1);
                        }
                }
                return "OK";
        }

        // Vérifier que le joueur a au moins 18 ans
        private function verifier_age_minimum($date_naissance) {
                $date_obj = DateTime::createFromFormat('Y-m-d', $date_naissance);

                if (!$date_obj) return "Format de date invalide.";

                $aujourdhui = new DateTime();
                $age = $aujourdhui->diff($date_obj)->y;

                if ($age < 18) return "Le joueur doit avoir au moins 18 ans (âge actuel : $age ans).";
                return "OK";
        }

        // Valider toutes les données d'un joueur (ajout ou modification)
        public function valider_donnees_joueur($id, $nom, $prenom, $licence, $taille, $poids, $date_naissance = null) {
                // Vérifier que les champs obligatoires ne sont pas vides
                if (empty($nom) || empty($prenom)) return "Le nom et le prénom ne peuvent être vides.";

                // Vérifier que la licence est un nombre positif
                if (!is_numeric($licence) || $licence <= 0) return "La licence doit être un nombre entier positif.";

                // Vérifier les valeurs minimales de taille et poids
                if (!is_numeric($taille) || $taille < 80) return "La taille doit être d'au moins 80 cm.";
                if (!is_numeric($poids) || $poids < 20) return "Le poids doit être d'au moins 20 kg.";

                // Vérifier l'âge si la date de naissance est fournie
                if ($date_naissance !== null) {
                        $res_age = $this->verifier_age_minimum($date_naissance);
                        if ($res_age !== "OK") return $res_age;
                }

                // Vérifier le format du nom
                $res_nom = $this->verifier_format_texte($nom);
                if ($res_nom !== "OK") return "Format NOM incorrect : " . $res_nom;

                // Vérifier le format du prénom
                $res_pre = $this->verifier_format_texte($prenom);
                if ($res_pre !== "OK") return "Format PRÉNOM incorrect : " . $res_pre;

                return "OK";
        }

        // Afficher la liste des joueurs actifs
        public function lister() {
                // Vérifier si un joueur est en cours d'édition
                if (isset($_GET['id_edition'])) {
                        $id_edition = (int) $_GET['id_edition'];
                } else {
                        $id_edition = 0;
                }

                $liste_joueurs = Joueur::recuperer_actifs();

                // Afficher la vue
                require_once '../Vues/PageListeJoueurs.php';
        }

        // Ajouter un nouveau joueur
        public function ajouter() {
                $nom = $_POST['nom'];
                $prenom = $_POST['prenom'];
                $licence = $_POST['licence'];
                $date_n = $_POST['date_naissance'];
                $taille = $_POST['taille'];
                $poids = $_POST['poids'];
                $statut = $_POST['statut'];

                // Valider les données
                $erreur = $this->valider_donnees_joueur(0, $nom, $prenom, $licence, $taille, $poids, $date_n);

                if ($erreur !== "OK") {
                        $_SESSION['erreur'] = $erreur;
                        header('Location: ControleurJoueur.php');
                        exit();
                }

                // Création de l'objet Joueur
                $nouveau_joueur = new Joueur();
                $nouveau_joueur->set_numero_licence($licence);
                $nouveau_joueur->set_nom($nom);
                $nouveau_joueur->set_prenom($prenom);
                $nouveau_joueur->set_date_naissance($date_n);
                $nouveau_joueur->set_taille($taille);
                $nouveau_joueur->set_poids($poids);
                $nouveau_joueur->set_statut($statut);

                if (Joueur::ajouter($nouveau_joueur)) {
                        header('Location: ControleurJoueur.php');
                } else {
                        $_SESSION['erreur'] = "Échec de l'ajout via l'API.";
                        header('Location: ControleurJoueur.php');
                }
                exit();
        }

        // Supprimer un joueur (changement de statut)
        public function supprimer()
        {
                // Vérifier que l'ID est bien envoyé
                if (isset($_POST['id'])) {
                        $id = (int) $_POST['id'];
                        Joueur::supprimer($id);
                }
                header('Location: ControleurJoueur.php');
                exit();
        }
}



$gestionnaire = new ControleurJoueur();

$action = "lister";
if (isset($_POST['action'])) {
        $action = $_POST['action'];
} elseif (isset($_GET['action'])) {
        $action = $_GET['action'];
}

switch ($action) {
        case "valider_ajout":
                $gestionnaire->ajouter();
                break;

        case "supprimer":
                $gestionnaire->supprimer();
                break;

        case "enregistrer_modif":
                // Vérifier que l'ID est bien envoyé
                if (isset($_POST['id'])) {
                        $id = (int) $_POST['id'];

                        // Valider les données
                        $erreur = $gestionnaire->valider_donnees_joueur(
                                $id,
                                $_POST['nom'],
                                $_POST['prenom'],
                                $_POST['licence'],
                                $_POST['taille'],
                                $_POST['poids'],
                                $_POST['date_naissance']
                        );

                        if ($erreur !== "OK") {
                                $_SESSION['erreur'] = $erreur;
                                header("Location: ControleurJoueur.php?id_edition=$id");
                        } else {
                                // Création de l'objet Joueur
                                $joueur_modifie = new Joueur();
                                $joueur_modifie->set_numero_licence($_POST['licence']);
                                $joueur_modifie->set_nom($_POST['nom']);
                                $joueur_modifie->set_prenom($_POST['prenom']);
                                $joueur_modifie->set_date_naissance($_POST['date_naissance']);
                                $joueur_modifie->set_taille($_POST['taille']);
                                $joueur_modifie->set_poids($_POST['poids']);
                                $joueur_modifie->set_statut($_POST['statut']);

                                if (Joueur::modifier($id, $joueur_modifie)) {
                                        header('Location: ControleurJoueur.php');
                                } else {
                                        $_SESSION['erreur'] = "Erreur lors de la modification via l'API.";
                                        header("Location: ControleurJoueur.php?id_edition=$id");
                                }
                        }
                        exit();
                }
                break;

        default:
                $gestionnaire->lister();
                break;
}