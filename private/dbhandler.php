<?php
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

		public function add_note($sku, $user, $note){
			$sku_stzd = htmlspecialchars($sku, ENT_QUOTES, 'UTF-8');
			$user_stzd = htmlspecialchars($user, ENT_QUOTES, 'UTF-8');
			$note_stzd = htmlspecialchars($note, ENT_QUOTES, 'UTF-8');

			// No non-numeric skus
			if(!is_numeric($sku_stzd)){
				return false;
			}

			// Strip zeroes as 000404882 = 404882
			$sku_stzd = ltrim($sku_stzd, '0');

			$statement = $this->conn->prepare("INSERT INTO `notetable` (`id`, `sku`, `user`, `note`, `date`) VALUES (NULL, ?, ?, ?, FROM_UNIXTIME(?))");

			$statement->bind_param("sssi", $sku_stzd, $user_stzd, $note_stzd, time());

			if(!$statement->execute()){
				return false;
			}

			return true;
		}

		public function retrieve_notes($sku){
			$notes = array();
			if(!$this->conn){
				return False;
			}

			$sku = ltrim($sku, '0');

			$statement = $this->conn->prepare("SELECT note,user,date FROM `notetable` WHERE sku = ? ORDER BY date DESC");	

			$statement->bind_param("s", $sku);
			$statement->execute();

			$result = $statement->get_result();
			if($result->num_rows > 0){
				while($cur = $result->fetch_assoc()){
					$cur_sanitized = array();
					$cur_sanitized['note'] = htmlspecialchars($cur['note'], ENT_QUOTES, 'UTF-8');
					$cur_sanitized['user'] = htmlspecialchars($cur['user'], ENT_QUOTES, 'UTF-8');
					$cur_sanitized['date'] = htmlspecialchars($cur['date'], ENT_QUOTES, 'UTF-8');
					array_push($notes, $cur_sanitized);
				}
			}

			return $notes;
		}
	}
?>
