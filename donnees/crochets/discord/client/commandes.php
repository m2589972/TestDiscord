<?php

add_action("TestDiscord/traitement_requete", function ($requete) {
	
	/*  * /
	journal("TestDiscord/traitement_requete " . str_repeat("=+*", 40));
	journal($requete);
	/*  */
	
	
	// réponse à la commande "action2"
	
	if (	isset($requete["data"]["name"])
		&&	("action2" === $requete["data"]["name"])
	) {
		
		$type_bouton = 2;
		
		$bouton_bleu = 1;
		$bouton_vert = 3;
		$bouton_rouge = 4;
		
		
		$heure = wp_date("H \\h i");
		$serveur = $_SERVER["HTTP_HOST"];
		
		$donnees_message = [
			"content" => "Envoyé par WordPress et l'extension TestDiscord :"
				 . " il est $heure sur le serveur **$serveur** <a:loading:747680523459231834>\n"
				 . "Cela vous convient-il ?",
			"components" => [
				[
					"type" => 1, // groupe de boutons
					"components" => [
						[
							"custom_id" => "oui",
							"type" => $type_bouton,
							"style" => $bouton_vert,
							"label" => "Oui",
						],
						[
							"custom_id" => "non",
							"type" => $type_bouton,
							"style" => $bouton_rouge,
							"label" => "Non",
						],
					],
				],
			],
		];
		
		do_action("TestDiscord/discord/interaction/modifer_reponse", $requete["token"], $donnees_message);
		
		
	} // FIN réponse à la commande "action2"
	
	
	// réponse à un bouton
	
	if (	isset($requete["data"]["component_type"])
		&&	(2 === $requete["data"]["component_type"])
		&&	isset($requete["data"]["custom_id"])
	) {
		
		$id_bouton = $requete["data"]["custom_id"];
		
		$message = "";
		
		if ("oui" === $id_bouton) {
			
			$message = "super ! je suis content que vous soyez content :slight_smile:";
			
		} elseif ("non" === $id_bouton) {
			
			$message = "ah :frowning:\n"
				 . "si cette réponse ne vous plait pas trop, essayez de relancer la"
				 . " commande dans quelques minutes, je répondrai peut-être une heure qui"
				 . " vous conviendra mieux.";
			
		}
		
		
		$donnees_message = [
			"content" => $message,
		];
		
		do_action("TestDiscord/discord/interaction/ajouter_reponse", $requete["token"], $donnees_message);
		
		
	} // FIN réponse à un bouton
	
	
}, 10, 1); // FIN add_action("TestDiscord/traitement_requete", function ($requete) {


add_filter("TestDiscord/discord/test_commandes_serveur", function ($_) {
	
	$test_commandes_serveur = [
		"informations_saisies" => FALSE,
		"serveur_trouve" => FALSE,
	];
	
	
	$configuration = apply_filters("TestDiscord/configuration", NULL);
	
	
	if (	("" !== $configuration["identifiant_serveur"])
		&&	("" !== $configuration["identifiant_salon"])
	) {
		
		$test_commandes_serveur["informations_saisies"] = TRUE;
		$test_commandes_serveur["identifiant_serveur"] = $configuration["identifiant_serveur"];
		
		
		$jeton_application = apply_filters("TestDiscord/discord/jeton_application", NULL);
		
		if (isset($jeton_application)) {
			
			$parametres = [
				"chemin" => "/applications/$configuration[identifiant_application]/guilds/$configuration[identifiant_serveur]/commands",
				"jeton_application" => $jeton_application,
				//"DEBUG" => TRUE,
			];
			
			$reponse_requete = apply_filters("TestDiscord/discord/envoi_requete", NULL, $parametres);
			
			if (!isset($reponse_requete["code"])) { // s'il n'y a pas d'erreur
				$test_commandes_serveur["serveur_trouve"] = TRUE;
				
				if (0 === count($reponse_requete)) {
					do_action("TestDiscord/discord/definir_commandes");
				}
				
				
			} // FIN if (!isset($reponse_requete["code"])) {
			
		} // FIN if (isset($jeton_application)) {
		
	} // FIN si informations saisies
	
	
	return $test_commandes_serveur;
	
}); // FIN add_filter("TestDiscord/discord/test_commandes_salon", function ($_) {


add_action("TestDiscord/discord/definir_commandes", function () {
	
	$jeton_application = apply_filters("TestDiscord/discord/jeton_application", NULL);
	
	if (!isset($jeton_application)) {
		return;
	}
	
	
	$configuration = apply_filters("TestDiscord/configuration", NULL);
	
	$tableau_commande_test_1 = [
		"name" => "action2",
		"description" => "Description de la commande",
	];
	
	$parametres = [
		"chemin" => "/applications/$configuration[identifiant_application]/guilds/$configuration[identifiant_serveur]/commands",
		"type_http" => "post",
		"jeton_application" => $jeton_application,
		"champs" => $tableau_commande_test_1,
		"envoi_json" => TRUE,
	];
	
	apply_filters("TestDiscord/discord/envoi_requete", NULL, $parametres);
	
	
}); // FIN add_action("TestDiscord/discord/definir_commandes", function () {


