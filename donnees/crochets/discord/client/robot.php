<?php

add_filter("TestDiscord/discord/test_messages_salon", function ($_) {
	
	$test_messages_salon = [
		"informations_saisies" => FALSE,
		"salon_lisible" => FALSE,
	];
	
	
	$configuration = apply_filters("TestDiscord/configuration", NULL);
	
	if (	("" !== $configuration["identifiant_salon"])
		&&	("" !== $configuration["jeton_robot"])
	) {
		
		$test_messages_salon["informations_saisies"] = TRUE;
		
		$parametres = [
			"chemin" => "/channels/$configuration[identifiant_salon]/messages",
			"jeton_robot" => $configuration["jeton_robot"],
			//"DEBUG" => TRUE,
		];
		
		$reponse_requete = apply_filters("TestDiscord/discord/envoi_requete", NULL, $parametres);
		
		if (!isset($reponse_requete["code"])) {
			$test_messages_salon["salon_lisible"] = TRUE;
			$test_messages_salon["messages"] = $reponse_requete;
		}
		
	} // FIN si informations saisies
	
	
	return $test_messages_salon;
	
}); // FIN add_filter("TestDiscord/discord/test_messages_salon", function ($_) {


add_filter("TestDiscord/discord/ajouter_message_salon", function ($_, $donnees_message) {
	
	$configuration = apply_filters("TestDiscord/configuration", NULL);
	
	
	$parametres = [
		"chemin" => "/channels/$configuration[identifiant_salon]/messages",
		"type_http" => "post",
		"jeton_robot" => $configuration["jeton_robot"],
		"champs" => $donnees_message,
		"envoi_json" => TRUE,
	];
	
	$reponse_requete = apply_filters("TestDiscord/discord/envoi_requete", NULL, $parametres);
	
	
	return $reponse_requete;
	
}, 10, 2); // FIN add_filter("TestDiscord/discord/ajouter_message_salon", function ($_, $donnees_message) {


