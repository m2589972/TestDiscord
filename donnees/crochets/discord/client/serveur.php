<?php

add_filter("TestDiscord/discord/test_serveur", function ($_) {
	
	$test_serveur = [
		"informations_saisies" => FALSE,
		"serveur_trouve" => FALSE,
	];
	
	
	$configuration = apply_filters("TestDiscord/configuration", NULL);
	
	
	if (	("" !== $configuration["identifiant_serveur"])
		&&	("" !== $configuration["identifiant_salon"])
	) {
		
		$test_serveur["informations_saisies"] = TRUE;
		$test_serveur["identifiant_serveur"] = $configuration["identifiant_serveur"];
		
		
		$jeton_application = apply_filters("TestDiscord/discord/jeton_application", NULL);
		
		if (isset($jeton_application)) {
			
			$parametres = [
				"chemin" => "/guilds/$configuration[identifiant_serveur]",
				"jeton_application" => $jeton_application,
				"DEBUG" => TRUE,
			];
			
			$reponse_requete = apply_filters("TestDiscord/discord/envoi_requete", NULL, $parametres);
			
			if (isset($reponse_requete["name"])) {
				$test_serveur["serveur_trouve"] = TRUE;
				$test_serveur["nom_serveur"] = $reponse_requete["name"];
			}
			
		} // FIN if (isset($jeton_application)) {
		
	} // FIN si informations saisies
	
	
	return $test_serveur;
	
}); // FIN add_filter("TestDiscord/discord/test_serveur", function ($_) {


add_filter("TestDiscord/discord/test_salon", function ($_) {
	
	$test_salon = [
		"informations_saisies" => FALSE,
		"salon_trouve" => FALSE,
	];
	
	
	$configuration = apply_filters("TestDiscord/configuration", NULL);
	
	
	if (	("" !== $configuration["identifiant_serveur"])
		&&	("" !== $configuration["identifiant_salon"])
	) {
		
		$test_salon["informations_saisies"] = TRUE;
		
		
		$jeton_application = apply_filters("TestDiscord/discord/jeton_application", NULL);
		
		if (isset($jeton_application)) {
			
			$parametres = [
				"chemin" => "/channels/$configuration[identifiant_salon]",
				"jeton_application" => $jeton_application,
				"DEBUG" => TRUE,
			];
			
			$reponse_requete = apply_filters("TestDiscord/discord/envoi_requete", NULL, $parametres);
			
			if (isset($reponse_requete["name"])) {
				$test_salon["salon_trouve"] = TRUE;
				$test_salon["nom_salon"] = $reponse_requete["name"];
			}
			
		} // FIN if (isset($jeton_application)) {
		
	} // FIN si informations saisies
	
	
	return $test_salon;
	
}); // FIN add_filter("TestDiscord/discord/test_salon", function ($_) {


