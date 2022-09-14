<?php

add_action("TestDiscord/page_test_messages_robot", function () {
	
	
	$test_messages_salon = apply_filters("TestDiscord/discord/test_messages_salon", NULL);
	
	$messages = [];
	$citation_robot = [];
	
	
	// préparation des messages
	
	foreach ($test_messages_salon["messages"] as $tab_message) {
		
		if (	isset($tab_message["author"]["bot"])
			&&	$tab_message["author"]["bot"]
		) {
			continue;
		}
		
		if (	empty($tab_message["content"])
			||	empty($tab_message["author"]["username"])
		) {
			continue;
		}
		
		$timestamp = strtotime($tab_message["timestamp"]);
		
		$messages[] = [
			"auteur" => $tab_message["author"]["username"],
			"timestamp" => $timestamp,
			"contenu" => $tab_message["content"],
		];
		
		// recherche du texte "robot"
		if (FALSE !== stripos($tab_message["content"], "robot")) {
			$citation_robot[] = $tab_message["author"]["username"];
		}
		
	}
	
	$messages = array_reverse($messages);
	
	
	// envoi du message public
	
	if (0 !== count($citation_robot)) {
		
		if (1 === count($citation_robot)) {
			$noms = $citation_robot[0];
		} else {
			$dernier = array_pop($citation_robot);
			$noms = implode(", ", $citation_robot) . " et " . $dernier;
		}
		
		
		$donnees_message = [
			"content" => "Bonjour $noms. Vous parlez de moi ?",
		];
		
		apply_filters("TestDiscord/discord/ajouter_message_salon", NULL, $donnees_message);
		
		
	} // FIN if (0 !== count($citation_robot)) {
	
	
	// affichage
	
	$url_extension = apply_filters("TestDiscord/url_extension", NULL);
	$version_extension = apply_filters("TestDiscord/version_extension", NULL);
	
	wp_enqueue_style(
		  "TestDiscord/affichage"
		, "$url_extension/liens/css/affichage.css"
		, []
		, $version_extension
	);
	
	
	$nombre_message = count($messages);
	
	?>
		
		<h2>
			<a href="<?php echo htmlspecialchars(admin_url("admin.php?page=TestDiscord"));?>">
				&lt; retour à la page principale TestDiscord</a>
		</h2>
		
		<p>
			Le robot récupère au maximum les 50 derniers messages du salon. S'il lit un
			 message qui contient le mot "robot", il enverra un message public.
		</p>
		
		<p>
			Le code de lecture et d'envoi des messages est dans le fichier
			 <code>donnees/crochets/discord/client/robot.php</code>.
		</p>
		
		
		<?php if (0 === $nombre_message) {?>
			
			<p>
				Aucun message trouvé dans ce salon
			</p>
			
		<?php } else {?>
			
			<h2>
				<?php echo htmlspecialchars($nombre_message);?> message<?php echo ($nombre_message < 2) ? "" : "s";?>&nbsp;:
			</h2>
			
			<ul class="liste_messages">
				
				<?php foreach ($messages as $message) {?>
					
					<li>
						<span class="date">
							<?php echo htmlspecialchars(wp_date("d/m H:i:s", $message["timestamp"]));?>
						</span>
						<span class="auteur">
							<?php echo htmlspecialchars($message["auteur"]);?>
						</span>
						<span class="contenu">
							<?php echo nl2br(htmlspecialchars($message["contenu"]));?>
						</span>
					</li>
					
				<?php }?>
				
			</ul>
			
		<?php }?>
		
		
	<?php
	
}); // FIN add_action("TestDiscord/page_test_messages_robot", function () {


