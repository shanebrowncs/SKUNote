<?php
require_once('private/dbhandler.php');
require_once('private/util.php');

header("Content-Type: application/json");

if(!isset($_GET['sku']) || empty($_GET['sku'])){
	SKUtil::die_json("No sku given");
}

$division = "all";

if(isset($_GET['division']) && !empty($_GET['division'])){
	if(!SKUtil::divisions_enabled()){
		SKUtil::die_json("Divisions disabled");
	}

	$division = $_GET['division'];
}

$handler = new DBHandler("private/mysql.ini");

$notes = $handler->retrieve_notes($_GET['sku'], $division);

if(!$notes){
	SKUtil::die_json("Database error");
}

$obj = new stdClass;
$obj->success = true;
$obj->error = "";
$obj->sku = htmlspecialchars($_GET['sku'], ENT_QUOTES, 'UTF-8');
$obj->sku = ltrim($obj->sku, '0');
$obj->length = count($notes);
$obj->notes = $notes;

echo json_encode($obj, JSON_PRETTY_PRINT);
?>
