<?php
session_start();

require_once 'config_api_jwt.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$identifiant_saisi = $_POST['identifiant'];
	$mot_de_passe_saisi = $_POST['mdp'];

	// On prépare les données pour l'API
	$donnees_login = [
		'identifiant' => $identifiant_saisi,
		'password' => $mot_de_passe_saisi
	];

	// On appelle l'API d'authentification
	$resultat = appel_api('POST', urlApiAuthentification, $donnees_login);

	// On analyse la réponse de l'API
	if ($resultat && $resultat['status_code'] === 200) {

		// Le jeton JWT est dans $resultat['data']
		$jwt = $resultat['data'];

		// On décode le milieu du jeton pour récupérer le nom/prénom
		$parties = explode('.', $jwt);
		$payload = json_decode(base64_decode($parties[1]), true); // Le Payload est la 2ème partie

		// On remplit la session avec les infos extraites du jeton
		$_SESSION['id_entraineur'] = $payload['id_entraineur'];
		$_SESSION['nom_entraineur'] = $payload['nom'];
		$_SESSION['prenom_entraineur'] = $payload['prenom'];
		$_SESSION['jwt'] = $jwt; // On garde le jeton pour les futures requêtes

		header('Location: ../Controleurs/ControleurAccueil.php');
		exit();

	} else {
		// Erreur : on utilise le message renvoyé par l'API
		if (isset($resultat['status_message'])) {
			$_SESSION['erreur'] = $resultat['status_message'];
		} else {
			$_SESSION['erreur'] = "Erreur de connexion à l'API";
		}
		header('Location: ../Vues/PageConnexion.php');
		exit();
	}
}
