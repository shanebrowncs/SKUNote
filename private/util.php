<?php
class SKUtil{
	public static function die_json($msg){
		die('{"success": false, "error": "' . htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') . '"}');
	}
}
?>
