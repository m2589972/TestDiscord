<?php

add_action("admin_menu", function () {
	
	$permission = "install_plugins";
	
	
	$hook = add_menu_page(
		  "TestDiscord"
		, "TestDiscord"
		, $permission
		, "TestDiscord"
		, function () {
			do_action("TestDiscord/page_admin");
		}
	);
	
	
	add_submenu_page(
		  ""
		, "Test messages robot"
		, "Test messages robot"
		, $permission
		, "TestDiscord__messages_robot"
		, function () {
			do_action("TestDiscord/page_test_messages_robot");
		}
	);
	
	
	
});


