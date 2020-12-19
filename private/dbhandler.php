<?php
	require_once __DIR__ . '/util.php';

	class DBHandler{
		private $conn;

		function __construct($inifile){
			$this->read_ini($inifile);	
		}

		private function read_ini($inifile){
			$sql_details = parse_ini_file($inifile);
			if($sql_details){
				$this->conn = new mysqli($sql_details['host'], $sql_details['user'], $sql_details['password'], $sql_details['database']);
			}
		}

		private function get_default_division(){
			if(!$this->conn){
				return false;
			}

			$result = $this->conn->query("SELECT min(id) FROM `divisions` WHERE 1");
			$obj = $result->fetch_assoc();
			return $obj['min(id)'];
		}

		private function get_division_id($name){
			if(!$this->conn){
				return false;
			}

			$stmt = $this->conn->prepare("SELECT id FROM `divisions` WHERE `name`= ?");
			$stmt->bind_param("s", $name);

			if(!$stmt->execute()){
				return false;
			}

			$result = $stmt->get_result();

			if($result->num_rows < 1){
				return false;
			}

			$obj = $result->fetch_assoc();
			return $obj['id'];
		}

		private function add_division($name){
			if(!$this->conn){
				return false;
			}

			$stmt = $this->conn->prepare("INSERT INTO `divisions` (`id`, `name`) VALUES (NULL, ?)");
			$stmt->bind_param("s", $name);

			if(!$stmt->execute()){
				return false;
			}

			return true;
		}

		public function add_note($sku, $user, $note, $division="default"){
			$sku_stzd = htmlspecialchars($sku, ENT_QUOTES, 'UTF-8');
			$user_stzd = htmlspecialchars($user, ENT_QUOTES, 'UTF-8');
			$note_stzd = htmlspecialchars($note, ENT_QUOTES, 'UTF-8');

			// Can't specify divisions if they aren't enabled
			if(strcmp($division, "default") != 0 && !SKUtil::divisions_enabled()){
				return false;
			}

			// No non-numeric skus
			if(!is_numeric($sku_stzd)){
				return false;
			}

			// Strip zeroes as 000404882 = 404882
			$sku_stzd = ltrim($sku_stzd, '0');

			$division_id = $this->get_default_division();
			if(strcmp($division, "default") != 0){
				$division_id = $this->get_division_id($division);
			}
			if($division_id === false){ // Division doesn't exist
				if($this->add_division($division)){
					$division_id = $this->get_division_id($division);
				}else{
					return false;
				}
			}

			$statement = $this->conn->prepare("INSERT INTO `notetable` (`id`, `sku`, `user`, `note`, `date`, `division`) VALUES (NULL, ?, ?, ?, FROM_UNIXTIME(?), ?)");

			$statement->bind_param("sssii", $sku_stzd, $user_stzd, $note_stzd, time(), $division_id);

			if(!$statement->execute()){
				return false;
			}

			return true;
		}

		public function retrieve_notes($sku, $division="all"){
			$notes = array();
			if(!$this->conn){
				return False;
			}

			$sku = ltrim($sku, '0');

			$statement = null;
			if(strcmp($division, "all") != 0){
				$statement = $this->conn->prepare("SELECT `notetable`.`note`, `notetable`.`user`, `notetable`.`date` FROM `notetable` INNER JOIN `divisions` ON `notetable`.`division` = `divisions`.`id` WHERE (`notetable`.`sku`= ? AND `divisions`.`name`= ?) ORDER BY `notetable`.`date` DESC");
				$statement->bind_param("ss", $sku, $division);
			}else{
				$statement = $this->conn->prepare("SELECT `notetable`.`note`,`notetable`.`user`,`notetable`.`date`,`divisions`.`name` FROM `notetable` INNER JOIN `divisions` ON `notetable`.`division` = `divisions`.`id` WHERE `notetable`.`sku` = ? ORDER BY date DESC");
				$statement->bind_param("s", $sku);
			}
			$statement->execute();

			$result = $statement->get_result();
			if($result->num_rows > 0){
				while($cur = $result->fetch_assoc()){
					$cur_sanitized = array();
					$cur_sanitized['note'] = htmlspecialchars_decode($cur['note'], ENT_QUOTES);
					$cur_sanitized['user'] = htmlspecialchars_decode($cur['user'], ENT_QUOTES);
					$cur_sanitized['date'] = htmlspecialchars_decode($cur['date'], ENT_QUOTES);
					if(SKUtil::divisions_enabled() && strcmp($division, "all") == 0){
						$cur_sanitized['division'] = $cur['name'];
					}
					array_push($notes, $cur_sanitized);
				}
			}

			return $notes;
		}

		public function retrieve_divisions(){
			if(!$this->conn){
				return false;
			}

			$divisions = array();

			if($result = $this->conn->query("SELECT `name` from `divisions` WHERE 1")){
				while($obj = $result->fetch_assoc()){
					array_push($divisions, $obj['name']);
				}
			}

			return $divisions;
		}
	}
?>
