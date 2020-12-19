<?php
class SKUtil{
	public static function die_json($msg){
		die('{"success": false, "error": "' . htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') . '"}');
	}

	public static function divisions_enabled(){
		$obj = parse_ini_file("config.ini");
		if($obj['divisions']){
			return true;
		}

		return false;
	}
}
?>
