<?php

add_action("TestDiscord/page_admin/html", function ($donnees) {
	
	$base_extension = apply_filters("TestDiscord/base_extension", NULL);
	$url_extension = apply_filters("TestDiscord/url_extension", NULL);
	$version_extension = apply_filters("TestDiscord/version_extension", NULL);
	
	
	wp_enqueue_style(
		  "TestDiscord/affichage"
		, "$url_extension/liens/css/affichage.css"
		, []
		, $version_extension
	);
	
	?>
		
		<?php if (
				!extension_loaded("gmp")
			&&	!extension_loaded("bcmath")
		) {?>
			<span class="message erreur">
				L'URL des interactions et les commandes ne pourront pas être utilisés sur ce serveur.
				 Une des extensions "gmp" ou "bcmath" doit être installée pour cela.
			</span>
		<?php }?>
		
		<h1>
			TestDiscord
		</h1>
		
		
		<?php if (
				!isset($donnees["test_application"]["informations_saisies"])
			||	!$donnees["test_application"]["informations_saisies"]
		) {?>
			<p>
				Le fichier à modifier pour indiquer les différentes valeurs de configuration est le fichier 
				<code><?php echo htmlspecialchars($base_extension . DIRECTORY_SEPARATOR . "TestDiscord.php");?></code>.
			</p>
			<p>
				Les valeurs sont à coller dans le tableau PHP <code>$configuration</code> au début du fichier.
				 Vous pouvez laisser ce fichier ouvert, il y a plusieurs valeurs à indiquer lors de différentes étapes.
			</p>
		<?php }?>
		
		<h2>
			1<sup>re</sup> étape - test de l'accès à l'A.P.I. Discord&nbsp;:
			<?php if ($donnees["test_acces"]["acces"]) {?>
				<span class="message bon">
					bon à <?php echo htmlspecialchars(wp_date("H \\h i", $donnees["test_acces"]["date"]));?>
				</span>
			<?php } else {?>
				<span class="message erreur">
					erreur à <?php echo htmlspecialchars(wp_date("H \\h i", $donnees["test_acces"]["date"]));?>
				</span>
			<?php }?>
		</h2>
		
		<?php if (!$donnees["test_acces"]["acces"]) {?>
			<p>
				Une erreur d'accès peut venir d'un problème réseau temporaire.
				Cela peut aussi venir de l'hébergeur qui a filtré l'accès aux serveurs de Discord.
			</p>
			<p>
				Vous pouvez rafraichir la page dans quelques minutes pour refaire un essai d'accès.
			</p>
		<?php } else {?>
			
			
			<h2>
				2<sup>e</sup> étape - création d'une application&nbsp;:
				
				<?php if ($donnees["test_application"]["application_trouvee"]) {?>
					<span class="message bon">
						l'application c'est bien connectée et a trouvé son nom
						 "<?php echo htmlspecialchars($donnees["test_application"]["nom_application"]);?>".
					</span>
				<?php }?>
				
			</h2>
			
			<p>
				Espace développeur&nbsp;: <a href="https://discord.com/developers/applications"
					>https://discord.com/developers/applications</a>
			</p>
			
			<?php if (!$donnees["test_application"]["application_trouvee"]) {?>
				
				<?php if ($donnees["test_application"]["informations_saisies"]) {?>
					
					<span class="message erreur">
						L'application n'a pas pu se connecter, revérifiez les actions ci-dessous.
						 Vous pouvez recliquer sur "Reset Secret" pour créer un nouveau code.
					</span>
					
				<?php }?>
				
				<ul>
					<li>
						<span class="numerotation">2a)</span> Pour créer une application, allez dans l'espace
						 développeur et cliquez en haut à droit sur "New Application"
					</li>
					<li>
						<span class="numerotation">2b)</span> Quand l'application est créée, vous arrivez sur
						 la page d'information générale. Sur cette page, vous récupérez l'identifiant "Application ID"
						 à copier dans le tableau de configuration dans <code>identifiant_application</code>.
					</li>
					<li>
						<span class="numerotation">2c)</span> Et sur la même page, la clé "Public Key" va dans
						 le tableau dans <code>cle_application</code>.
					</li>
					<li>
						<span class="numerotation">2d)</span> Allez sur la page "OAuth2", cliquez sur "Reset Secret",
						 confirmez et copiez le "Client Secret" dans le tableau dans <code>secret_application</code>.
					</li>
					<li>
						<span class="numerotation">2e)</span> Après avoir rempli ces 3 informations dans le fichier,
						 rafraichissez cette page TestDiscord.
					</li>
				</ul>
				
				
			<?php } else { // if ($donnees["test_application"]["application_trouvee"]) {?>
				
				<p>
					Vous pouvez trouver le code de connexion dans le fichier
					 <code>donnees/crochets/discord/client/application.php</code>
					 avec le filtre <code>TestDiscord/discord/test_application</code>.
				</p>
				
				
				<h2>
					3<sup>e</sup> étape - création d'un serveur Discord&nbsp;:
				</h2>
				
				<p>
					Espace de discussion&nbsp;: <a href="https://discord.com/app">https://discord.com/app</a>
				</p>
				
				<?php if (!$donnees["test_commandes_serveur"]["informations_saisies"]) {?>
					
					<ul>
						<li>
							<span class="numerotation">3a)</span> Si vous n'avez pas encore créé de serveur, 
							 allez dans l'espace de discussion et à gauche de l'écran, cliquez sur "+" (ajouter un serveur).
						</li>
						<li>
							<span class="numerotation">3b)</span> Vous allez arriver à un URL qui ressemble à 
							 <code>https://discord.com/channels/14444.../25555...</code>.
						</li>
						<li>
							<span class="numerotation">3c)</span> Dans cet exemple, <code>14444...</code> est l'identifiant du serveur 
							 et se met dans le fichier de configuration à <code>identifiant_serveur</code>.
						</li>
						<li>
							<span class="numerotation">3d)</span> Et <code>25555...</code> est l'identifiant du salon
							 et se met à <code>identifiant_salon</code>.
						</li>
						<li>
							<span class="numerotation">3e)</span> Après avoir rempli ces informations dans
							 le fichier, rafraichissez cette page TestDiscord.
						</li>
					</ul>
					
				<?php } else { // if ($donnees["test_commandes_serveur"]["informations_saisies"]) {?>
					
					<h2>
						4<sup>e</sup> étape - autorisation de l'application avec le serveur&nbsp;:
						
						<?php if ($donnees["test_commandes_serveur"]["serveur_trouve"]) {?>
							<span class="message bon">
								l'application est associée avec le serveur.
							</span>
						<?php }?>
						
					</h2>
					
					<p>
						URL d'autorisation de l'application pour un serveur&nbsp;: 
						<a href="<?php echo htmlspecialchars($donnees["url_validation_application"]);?>">
							<?php echo htmlspecialchars($donnees["url_validation_application"]);?></a>
					</p>
					
					<?php if (!$donnees["test_commandes_serveur"]["serveur_trouve"]) {?>
						
						<span class="message erreur">
							L'application n'a pas encore l'autorisation de fonctionner avec le serveur indiqué.
						</span>
						
						<ul>
							<li>
								<span class="numerotation">4a)</span> Allez à l'URL d'autorisation et
								 choisissez le serveur que vous avez indiqué dans le fichier de configuration.
							</li>
							<li>
								<span class="numerotation">4b)</span> Après avoir autorisé l'application,
								 rafraichissez cette page TestDiscord.
							</li>
						</ul>
						
					<?php } else { // if ($donnees["test_commandes_serveur"]["serveur_trouve"]) {?>
						
						<h2>
							5<sup>e</sup> étape - test des commandes&nbsp;:
						</h2>
						
						<ul>
							<li>
								<span class="numerotation">5a)</span> Dans l'espace développeur,
								 allez sur la page "General Information" de l'application&nbsp;:
								 <a href="<?php echo htmlspecialchars($donnees["url_application_information"]);?>"
								 	><?php echo htmlspecialchars($donnees["url_application_information"]);?></a>
							</li>
							<li>
								<span class="numerotation">5b)</span> Dans le champ "Interactions Endpoint URL"
								 mettez cet URL&nbsp;:
								 <code><?php echo htmlspecialchars($donnees["url_interactions"]);?></code>
							</li>
							<li>
								<span class="numerotation">5c)</span> Vous pouvez maintenant aller
								 dans un salon de votre serveur Discord&nbsp;
								 <a href="<?php echo htmlspecialchars($donnees["url_serveur"]);?>"
								 	><?php echo htmlspecialchars($donnees["url_serveur"]);?></a>
							</li>
							<li>
								<span class="numerotation">5d)</span> Tapez la commande
								 <code>/action2</code> et cliquez ensuite sur un des boutons.
							</li>
						</ul>
						
						<p>
							Le code qui s'occupe des commandes est dans le fichier
							 <code>donnees/crochets/discord/client/commandes.php</code> aux endroits suivants&nbsp;:
						</p>
						
						<ul class="liste_simple">
							<li>
								le code qui met en place la commande est dans le filtre
								 <code>TestDiscord/discord/definir_commandes</code>.
							</li>
							<li>
								le code qui génère la réponse est dans le filtre
								 <code>TestDiscord/traitement_requete</code>.
							</li>
						</ul>
						
						<p>
							Le code qui gère l'URL des interactions est dans
							 <code>donnees/crochets/discord/url_interaction.php</code>.
						</p>
						
						<p>
							Ce système de commandes peut aussi se placer dans certains menus.
							 Vous pouvez voir tous les détails dans la documentation&nbsp;:
							 <a href="https://discord.com/developers/docs/interactions/application-commands"
							 	>https://discord.com/developers/docs/interactions/application-commands</a>
						</p>
						
						
						<h2>
							6<sup>e</sup> étape - ajout d'un utilisateur robot dans l'application&nbsp;:
							
							<?php if ($donnees["test_messages_salon"]["salon_lisible"]) {?>
								<span class="message bon">
									le robot peut lire les messages du salon.
								</span>
							<?php }?>
						
						</h2>
						
						
						<?php if (!$donnees["test_messages_salon"]["salon_lisible"]) {?>
							
							<?php if ($donnees["test_messages_salon"]["informations_saisies"]) {?>
								
								<span class="message erreur">
									Le robot n'a pas pu lire les messages du salon, revérifiez les
									 valeurs de <code>identifiant_salon</code> et <code>jeton_robot</code>.
									 Vous pouvez recliquer sur "Reset Token" pour créer un nouveau jeton.
								</span>
								
							<?php } else { // if (!$donnees["test_application"]["informations_saisies"])?>
								
								<p>
									L'utilisation d'une application seule permet de faire plusieurs choses mais
									 pour avoir plus de possibilités, vous devrez rajouter un utilisateur robot
									 qui apparaitra dans la listes des utilisateurs du serveur.
								</p>
								
							<?php } // FIN if (!$donnees["test_application"]["informations_saisies"])?>
							
							<ul>
								<li>
									<span class="numerotation">6a)</span> Dans l'espace développeur,
									 allez sur la page "Bot"&nbsp;:
									 <a href="<?php echo htmlspecialchars($donnees["url_application_robot"]);?>"
									 	><?php echo htmlspecialchars($donnees["url_application_robot"]);?></a>
								</li>
								<li>
									<span class="numerotation">6b)</span> Cliquez sur "Add bot" pour ajouter
									 un utilisateur robot à l'application. Ensuite, cliquez sur "Reset Token",
									 confirmez et copiez le "Token" dans le tableau de configuration
									 dans <code>jeton_robot</code>.
								</li>
								<li>
									<span class="numerotation">6c)</span> Vous pouvez maintenant ajouter
									 le robot à votre serveur en allant à cet URL&nbsp;: 
									 <a href="<?php echo htmlspecialchars($donnees["url_validation_robot"]);?>">
										<?php echo htmlspecialchars($donnees["url_validation_robot"]);?></a>
								</li>
								<li>
									<span class="numerotation">6d)</span> Après avoir ajouté le robot,
									 rafraichissez cette page TestDiscord.
								</li>
							</ul>
						
						<?php } else { // if ($donnees["test_messages_salon"]["salon_lisible"]) {?>
						
							<p>
								URL d'ajout du robot à un serveur&nbsp;:
								 <a href="<?php echo htmlspecialchars($donnees["url_validation_robot"]);?>">
									<?php echo htmlspecialchars($donnees["url_validation_robot"]);?></a>
							</p>
							
							<p>
								Outils de calcul des permissions pour le robot&nbsp;:
								 <a href="https://discordapi.com/permissions.html#<?php echo htmlspecialchars($donnees["permissions_robot"]);?>">
									https://discordapi.com/permissions.html#<?php echo htmlspecialchars($donnees["permissions_robot"]);?></a>
							</p>
							
							
							<h2>
								7<sup>e</sup> étape - test de l'utilisateur robot&nbsp;:
							</h2>
							
							<p>
								Pour ce test de lecture des messages, allez sur la page "Bot"&nbsp;:
								 <a href="<?php echo htmlspecialchars($donnees["url_application_robot"]);?>"
								 	><?php echo htmlspecialchars($donnees["url_application_robot"]);?></a>,
								 puis autorisez "Message Content Intent".
							</p>
							
							<p>
								<a href="<?php echo htmlspecialchars($donnees["url_test_messages_robot"]);?>">
									Cliquez ici pour aller à la page de test du robot</a>
							</p>
							
						<?php } // FIN if ($donnees["test_messages_salon"]["salon_lisible"]) {?>
						
					<?php } // FIN if ($donnees["test_commandes_serveur"]["serveur_trouve"]) {?>
					
				<?php } // FIN if ($donnees["test_salon"]["informations_saisies"]) {?>
				
			<?php } // FIN if ($donnees["test_application"]["application_trouvee"]) {?>
			
		<?php } // FIN if ($donnees["test_acces"]["acces"]) {?>
		
		
	<?php
	
	
}); // FIN add_action("TestDiscord/page_admin/html", function ($donnees) {


