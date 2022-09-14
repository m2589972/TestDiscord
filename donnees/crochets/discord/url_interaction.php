<?php

add_filter("TestDiscord/discord/verifier_signature", function ($_, $contenu, $signature, $timestamp) {
	
	$configuration = apply_filters("TestDiscord/configuration", NULL);
	
	
	require_once __DIR__ . "/../../classes/composer/autoload.php";
	
	$ec = new \Elliptic\EdDSA("ed25519");
	$key = $ec->keyFromPublic($configuration["cle_application"], "hex");
	
	$message = array_merge(unpack("C*", $timestamp), unpack("C*", $contenu));
	
	
	return $key->verify($message, $signature);
	
	
}, 10, 4);


add_action("rest_api_init", function () {
	
	
	register_rest_route("TestDiscord", "interaction",
		[
			"methods" => WP_REST_Server::CREATABLE, // méthode POST
			"permission_callback" => function (\WP_REST_Request $request) {
				
				
				$autorise = FALSE;
				
				
				if (	isset($_SERVER["HTTP_X_SIGNATURE_ED25519"])
					&&	isset($_SERVER["HTTP_X_SIGNATURE_TIMESTAMP"])
				) {
					
					if (	!$request->offsetExists("type")
						||	(1 !== $request->get_param("type")) // requete ping
					) {
						
						$autorise = TRUE;
						
					} else {
						
						$autorise = apply_filters(
							  "TestDiscord/discord/verifier_signature"
							, FALSE
							, $request->get_body()
							, $_SERVER["HTTP_X_SIGNATURE_ED25519"]
							, $_SERVER["HTTP_X_SIGNATURE_TIMESTAMP"]
						);
						
					}
					
					
				}
				
				
				return $autorise;
				
			}, // FIN "permission_callback" => function (\WP_REST_Request $request) {
			"callback" => function (\WP_REST_Request $request) {
				
				
				if (	$request->offsetExists("type")
					&&	(1 === $request->get_param("type")) // requete ping
				) {
					
					$reponse = ["type" => 1]; // reponse pong
					
				} else {
					
					// la réponse doit être envoyée dans les 3 secondes donc une nouvelle requete HTTP est lancée en arrière plan
					// et en même temps, la réponse à Discord indique qu'un message privé va arriver.
					// la requete en arrière plan a ensuite 15 minutes pour modifier le message d'attente ou ajouter des messages à la suite.
					
					
					// transfert à une nouvelle requete en arrière plan
					
					$donnees_transfert = [
						"signature" => $_SERVER["HTTP_X_SIGNATURE_ED25519"],
						"timestamp" => $_SERVER["HTTP_X_SIGNATURE_TIMESTAMP"],
						"contenu_brut" => $request->get_body(),
					];
					
					wp_remote_post(
						  rest_url("/TestDiscord/traitement_requete")
						,
						[
							"timeout" => 0.01,
							"blocking" => FALSE,
							"body" => $donnees_transfert,
						]
					);
					
					
					// réponse envoyée à Discord
					
					$reponse = [
						"type" => 5, // type attente
						"data" => [
							"flags" => 64, // message privé
						],
					];
					
				}
				
				
				return rest_ensure_response($reponse);
				
			}, // FIN "callback" => function (\WP_REST_Request $request) {
			"show_in_index" => FALSE,
		]
	); // FIN register_rest_route("TestDiscord", "interaction",
	
	
	register_rest_route("TestDiscord", "traitement_requete",
		[
			"methods" => WP_REST_Server::CREATABLE, // méthode POST
			"permission_callback" => function (\WP_REST_Request $request) {
				
				
				$_POST["contenu_brut"] = stripslashes($_POST["contenu_brut"]);
				
				$autorise = FALSE;
				
				
				if (	isset($_POST["contenu_brut"])
					&&	isset($_POST["signature"])
					&&	isset($_POST["timestamp"])
					&&	apply_filters(
							  "TestDiscord/discord/verifier_signature"
							, FALSE
							, $_POST["contenu_brut"]
							, $_POST["signature"]
							, $_POST["timestamp"]
						)
				) {
					$autorise = TRUE;
				}
				
				
				return $autorise;
				
			}, // FIN "permission_callback" => function (\WP_REST_Request $request) {
			"callback" => function (\WP_REST_Request $request) {
				
				$requete = json_decode($_POST["contenu_brut"], TRUE);
				
				do_action("TestDiscord/traitement_requete", $requete);
				
			}, // FIN "callback" => function (\WP_REST_Request $request) {
			"show_in_index" => FALSE,
		]
	); // FIN register_rest_route("TestDiscord", "traitement_requete",
	
	
}); // FIN add_action("rest_api_init", function () {


