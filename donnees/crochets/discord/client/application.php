<?php

add_filter("TestDiscord/discord/jeton_application", function ($_) {
	
	$configuration = apply_filters("TestDiscord/configuration", NULL);
	
	
	$parametres = [
		"chemin" => "/oauth2/token",
		"auth_http" => "$configuration[identifiant_application]:$configuration[secret_application]",
		"type_http" => "post",
		"champs" =>	 [
			"grant_type" => "client_credentials",
			"scope" => "applications.commands.update",
		],
		//"DEBUG" => TRUE,
	];
	
	$reponse_requete = apply_filters("TestDiscord/discord/envoi_requete", NULL, $parametres);
	
	$jeton_application = NULL;
	
	if (isset($reponse_requete["access_token"])) {
		$jeton_application = $reponse_requete["access_token"];
	}
	
	return $jeton_application;
	
});


add_filter("TestDiscord/discord/test_application", function ($_) {
	
	$test_application = [
		"informations_saisies" => FALSE,
		"application_trouvee" => FALSE,
	];
	
	
	$configuration = apply_filters("TestDiscord/configuration", NULL);
	
	
	if (	("" !== $configuration["identifiant_application"])
		&&	("" !== $configuration["cle_application"])
		&&	("" !== $configuration["secret_application"])
	) {
		
		$test_application["informations_saisies"] = TRUE;
		$test_application["identifiant_application"] = $configuration["identifiant_application"];
		
		
		$jeton_application = apply_filters("TestDiscord/discord/jeton_application", NULL);
		
		if (isset($jeton_application)) {
			
			$parametres = [
				"chemin" => "/oauth2/@me",
				"jeton_application" => $jeton_application,
			];
			
			$reponse_requete = apply_filters("TestDiscord/discord/envoi_requete", NULL, $parametres);
			
			if (isset($reponse_requete["application"]["name"])) {
				$test_application["application_trouvee"] = TRUE;
				$test_application["nom_application"] = $reponse_requete["application"]["name"];
			}
			
		} // FIN if (isset($jeton_application)) {
		
	} // FIN si informations saisies
	
	
	return $test_application;
	
}); // FIN add_filter("TestDiscord/discord/test_application", function ($_) {


