<?php

add_action("TestDiscord/discord/interaction/modifer_reponse", function ($jeton_interaction, $donnees_message) {
	
	$configuration = apply_filters("TestDiscord/configuration", NULL);
	
	
	$parametres = [
		"chemin" => "/webhooks/$configuration[identifiant_application]/$jeton_interaction/messages/@original",
		"type_http" => "patch",
		"champs" => $donnees_message,
		"envoi_json" => TRUE,
	];
	
	apply_filters("TestDiscord/discord/envoi_requete", NULL, $parametres);
	
}, 10, 2);


add_action("TestDiscord/discord/interaction/ajouter_reponse", function ($jeton_interaction, $donnees_message) {
	
	$configuration = apply_filters("TestDiscord/configuration", NULL);
	
	
	$parametres = [
		"chemin" => "/webhooks/$configuration[identifiant_application]/$jeton_interaction",
		"type_http" => "post",
		"champs" => $donnees_message,
		"envoi_json" => TRUE,
	];
	
	apply_filters("TestDiscord/discord/envoi_requete", NULL, $parametres);
	
}, 10, 2);


