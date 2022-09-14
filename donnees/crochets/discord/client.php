<?php

require "client/application.php";

require "client/commandes.php";

require "client/interactions.php";

require "client/serveur.php";
require "client/robot.php";



add_filter("TestDiscord/discord/envoi_requete", function ($_, $parametres) {
	
	$url_api = "https://discord.com/api/v10";
	
	$envoi_json = $parametres["envoi_json"] ?? FALSE;
	
	
	$ch = curl_init();
	
	$curl_options = [
		CURLOPT_URL => "$url_api$parametres[chemin]",
		CURLOPT_HTTPHEADER => [],
		CURLOPT_RETURNTRANSFER => TRUE,
	];
	
	
	if (isset($parametres["type_http"])) {
		
		if ("post" === $parametres["type_http"]) {
			$curl_options[CURLOPT_POST] = TRUE;
		} elseif ("patch" === $parametres["type_http"]) {
			$curl_options[CURLOPT_CUSTOMREQUEST] = "PATCH";
		}
		
		if (	in_array($parametres["type_http"], ["post", "patch"])
			&&	isset($parametres["champs"])
		) {
			
			$curl_options[CURLOPT_POSTFIELDS] = $parametres["champs"];
			
			if ($envoi_json) {
				
				$curl_options[CURLOPT_POSTFIELDS] = json_encode($curl_options[CURLOPT_POSTFIELDS]);
				
				$curl_options[CURLOPT_HTTPHEADER][] = "Content-Type: application/json";
				
			}
			
		}
		
	} // FIN if (isset($parametres["type_http"])) {
	
	
	if (isset($parametres["auth_http"])) {
		$curl_options[CURLOPT_HTTPAUTH] = CURLAUTH_BASIC;
		$curl_options[CURLOPT_USERPWD] = $parametres["auth_http"];
	}
	
	
	if (isset($parametres["jeton_robot"])) {
		$curl_options[CURLOPT_HTTPHEADER][] = "Authorization: Bot $parametres[jeton_robot]";
	}
	if (isset($parametres["jeton_application"])) {
		$curl_options[CURLOPT_HTTPHEADER][] = "Authorization: Bearer $parametres[jeton_application]";
	}
	
	
	curl_setopt_array($ch, $curl_options);
	
	$reponse = curl_exec($ch);
	curl_close($ch);
	
	$donnees = json_decode($reponse, TRUE);
	
	
	if (	isset($parametres["DEBUG"])
		&&  $parametres["DEBUG"]
	) {
		aff(str_repeat("=+*", 40));
		aff(wp_date("H:i:s"));
		aff($parametres);
		//aff($curl_options);
		//aff($reponse);
		aff($donnees);
	}
	
	
	return $donnees;
	
}, 10, 2); // FIN add_filter("TestDiscord/discord/envoi_requete", function ($_, $parametres) {


add_filter("TestDiscord/discord/test_acces", function ($_) {
	
	$duree_cache = 3 * MINUTE_IN_SECONDS;
	
	if (defined("WP_DEBUG") && WP_DEBUG) {
		// cache prolongé pendant le développement
		$duree_cache = 30 * MINUTE_IN_SECONDS;
	}
	
	
	if (	!isset($_SESSION["TestDiscord"]["test_acces"])
		||	($_SESSION["TestDiscord"]["test_acces"]["date"] < (time() - $duree_cache))
	) {
		
		$parametres = [
			"chemin" => "/",
		];
		
		$reponse_requete = apply_filters("TestDiscord/discord/envoi_requete", NULL, $parametres);
		
		
		$acces = isset($reponse_requete["code"])
			&&	(0 === $reponse_requete["code"])
		;
		
		
		$_SESSION["TestDiscord"]["test_acces"] = [
			"acces" => $acces,
			"date" => time(),
		];
		
	}
	
	
	return $_SESSION["TestDiscord"]["test_acces"];
	
}); // FIN add_filter("TestDiscord/discord/test_acces", function ($_) {


