<?php
define('urlApiAuthentification', 'https://alfred.alwaysdata.net/authapi.php');
define('urlApiGestionJoueur', 'https://alphonse.alwaysdata.net/apiGestionJoueur.php');
define('urlApiGestionMatch', 'https://alphonse.alwaysdata.net/apiGestionMatch.php');

define('urlApiGestionFeuilleMatch', 'https://alphonse.alwaysdata.net/apiGestionFeuilleMatch.php');
define('urlApiGestionStats', 'https://alphonse.alwaysdata.net/apiGestionStats.php');
define('signatureJWT', 'random');

// Fonction d'appel api
function appel_api($methode, $url, $donnees = null)
{
	// On initie la connexion avec l'URL en utilisant cURL
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    	 	// On veut récupérer le retour, pas juste l'afficher
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $methode); 		// On définit la méthode

	$headers = []; // Tableau contenant l'entête HTTP de notre requête
	
	// Si un jeton est présent en session (utilisateur connecté), on le rajoute dans l'entête pour dire à l'API qu'on est bien connecté
	if (isset($_SESSION['jwt'])) {
		$headers[] = 'Authorization: Bearer ' . $_SESSION['jwt'];
	}

	// Si on doit envoyer des données à l'API
	if ($donnees) {
		$json_data = json_encode($donnees);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data); // On injecte le JSON dans la requête
		
		// On prévient l'API qu'on lui envoi du JSON
		$headers[] = 'Content-Type: application/json';
		$headers[] = 'Content-Length: ' . strlen($json_data);
	}

	// On applique l'entête complet à notre requête
	if (!empty($headers)) {
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	}

	// On lance la requête et on récupère la réponse de l'API
	$reponse = curl_exec($ch);
	curl_close($ch);

	// On convertit le JSON de l'API en tableau PHP
	return json_decode($reponse, true);
}

function verifier_authentification()
{
	// Vérifie si un jeton existe dans la session actuelle
	if (!isset($_SESSION['jwt'])) {
		header('Location: ../Vues/PageConnexion.php');
		exit(); 
	}

	// Vérifie si le jeton est authentique et encore valide au niveau du temps
	if (!is_jwt_valid($_SESSION['jwt'], signatureJWT)) {
		session_destroy(); 
		header('Location: ../Vues/PageConnexion.php');
		exit();
	}
	return true;
}

?>



















<?php

function generate_jwt($headers, $payload, $secret)
{
	$headers_encoded = base64url_encode(json_encode($headers));

	$payload_encoded = base64url_encode(json_encode($payload));

	$signature = hash_hmac('SHA256', "$headers_encoded.$payload_encoded", $secret, true);
	$signature_encoded = base64url_encode($signature);

	$jwt = "$headers_encoded.$payload_encoded.$signature_encoded";

	return $jwt;
}

function is_jwt_valid($jwt, $secret)
{
	// split the jwt
	$tokenParts = explode('.', $jwt);
	//print_r($tokenParts);
	$header = base64_decode($tokenParts[0]);
	$payload = base64_decode($tokenParts[1]);
	$signature_provided = $tokenParts[2];

	// check the expiration time - note this will cause an error if there is no 'exp' claim in the jwt
	$expiration = json_decode($payload)->exp;
	$is_token_expired = ($expiration - time()) < 0;

	// build a signature based on the header and payload using the secret
	$base64_url_header = base64url_encode($header);
	$base64_url_payload = base64url_encode($payload);
	$signature = hash_hmac('SHA256', $base64_url_header . "." . $base64_url_payload, $secret, true);
	$base64_url_signature = base64url_encode($signature);

	// verify it matches the signature provided in the jwt
	$is_signature_valid = ($base64_url_signature === $signature_provided);

	if ($is_token_expired || !$is_signature_valid) {
		return FALSE;
	} else {
		return TRUE;
	}
}

function base64url_encode($data)
{
	return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function get_authorization_header()
{
	$headers = null;

	if (isset($_SERVER['Authorization'])) {
		$headers = trim($_SERVER["Authorization"]);
	} else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
		$headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
	} else if (function_exists('apache_request_headers')) {
		$requestHeaders = apache_request_headers();
		// Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
		$requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
		//print_r($requestHeaders);
		if (isset($requestHeaders['Authorization'])) {
			$headers = trim($requestHeaders['Authorization']);
		}
	}

	return $headers;
}

function get_bearer_token()
{
	$headers = get_authorization_header();

	// HEADER: Get the access token from the header
	if (!empty($headers)) {
		if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
			if ($matches[1] == 'null') //$matches[1] est de type string et peut contenir 'null'
				return null;
			else
				return $matches[1];
		}
	}
	return null;
}

?>
