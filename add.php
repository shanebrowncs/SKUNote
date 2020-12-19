<?php
require_once("private/dbhandler.php");
require_once("private/util.php");

header("Content-Type: application/json");

if(isset($_POST['sku']) && !empty($_POST['sku'])
			&& isset($_POST['user']) && !empty($_POST['user'])
			&& isset($_POST['note']) && !empty($_POST['note'])){
	$sku = $_POST['sku'];
	$user = $_POST['user'];
	$note = $_POST['note'];

	$handler = new DBHandler("private/mysql.ini");
	$division = "default";
	if(isset($_POST['division'])){
		if(!SKUtil::divisions_enabled()){
			SKUtil::die_json("Divisions Disabled");
		}

		if(!empty($_POST['division'])){
			$division = $_POST['division'];
		}
	}
	if($handler->add_note($sku, $user, $note, $division)){
		echo '{"success": true, "error": ""}';
	}else{
		SKUtil::die_json("Database insertion error");
	}
}else{
	SKUtil::die_json("Missing required arguments");
}
?>
