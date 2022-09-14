<?php
/*
Plugin Name: TestDiscord
Version: 4
*/

if (!function_exists("add_action")) {
	echo "extension";
	exit();
}


add_filter("TestDiscord/configuration", function ($_) {
	
	$configuration = [
		
		"identifiant_application" => "",
		"cle_application" => "",
		"secret_application" => "",
		
		"identifiant_serveur" => "",
		"identifiant_salon" => "",
		
		"jeton_robot" => "",
		
	];
	
	
	return $configuration;
	
});


add_action("wp_loaded", function () {
	
	require "donnees/crochets/menu.php";
	
	require "donnees/crochets/affichage.php";
	require "donnees/crochets/page_test_messages_robot.php";
	
	require "donnees/crochets/discord.php";
	
	
	
}, 2);


add_filter("TestDiscord/base_extension", function ($_) {
	return __DIR__;
});

add_filter("TestDiscord/url_extension", function ($_) {
	return plugins_url("", __FILE__);
});


add_filter("TestDiscord/version_extension", function ($_) {
	
	if (!isset($GLOBALS["TestDiscord"]["version_extension"])) {
		
		$data = get_file_data(__FILE__, ["version" => "Version"]);
		$GLOBALS["TestDiscord"]["version_extension"] = $data["version"];
		
	}
	
	
	return $GLOBALS["TestDiscord"]["version_extension"];
	
});


