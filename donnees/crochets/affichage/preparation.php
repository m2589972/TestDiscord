<?php

add_action("TestDiscord/page_admin", function () {
	
	$donnees = [];
	
	/*
	$configuration = apply_filters("TestDiscord/configuration", NULL);
	$donnees["configuration"] = $configuration;
	*/
	
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	
	if (!isset($_SESSION["TestDiscord"])) {
		$_SESSION["TestDiscord"] = [];
	}
	
	
	$test_acces = apply_filters("TestDiscord/discord/test_acces", NULL);
	$donnees["test_acces"] = $test_acces;
	
	if ($test_acces["acces"]) {
		
		$test_application = apply_filters("TestDiscord/discord/test_application", NULL);
		$donnees["test_application"] = $test_application;
		
		if ($test_application["application_trouvee"]) {
			
			$test_commandes_serveur = apply_filters("TestDiscord/discord/test_commandes_serveur", NULL);
			$donnees["test_commandes_serveur"] = $test_commandes_serveur;
			
			if ($test_commandes_serveur["informations_saisies"]) {
				
				$donnees["url_validation_application"] = 
					"https://discord.com/oauth2/authorize?client_id=$test_application[identifiant_application]&scope=applications.commands"
				;
				
			}
			
			
			if ($test_commandes_serveur["serveur_trouve"]) {
				
				$donnees["url_interactions"] = rest_url("/TestDiscord/interaction");
				
				$donnees["url_serveur"] = "https://discord.com/channels/$test_commandes_serveur[identifiant_serveur]/";
				
				$donnees["url_application_information"] = "https://discord.com/developers/applications/$test_application[identifiant_application]/information";
				$donnees["url_application_robot"] = "https://discord.com/developers/applications/$test_application[identifiant_application]/bot";
				
				$donnees["permissions_robot"] = "68608";
				$donnees["url_validation_robot"] = 
					"https://discord.com/oauth2/authorize?client_id=$test_application[identifiant_application]&permissions=$donnees[permissions_robot]&scope=bot"
				;
				
				
				$test_messages_salon = apply_filters("TestDiscord/discord/test_messages_salon", NULL);
				$donnees["test_messages_salon"] = $test_messages_salon;
				
				if ($test_messages_salon["salon_lisible"]) {
					
					$donnees["url_test_messages_robot"] = admin_url("admin.php?page=TestDiscord__messages_robot");
					
				}
				
			} // FIN if ($test_serveur["serveur_trouve"]) {
			
		} // FIN if ($test_application["application_trouvee"]) {
		
	} // FIN if ($test_acces["acces"]) {
	
	
	// affichage
	do_action("TestDiscord/page_admin/html", $donnees);
	
	
	
}); // FIN add_action("TestDiscord/page_admin", function () {


