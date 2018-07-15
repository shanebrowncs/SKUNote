<!DOCTYPE html>

<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="layout.css"/>
		<link rel="stylesheet" type="text/css" href="addnote.css"/>
		<title>SKU Note - Add Note</title>
	</head>

	<body>
		<a id="header" href="./"><h1>SKU Note</h1></a>
		
		<?php
			// Check all inputs
			if((isset($_POST['sku']) && !empty($_POST['sku']))
				&& isset($_POST['user']) && !empty($_POST['user'])
				&& isset($_POST['note']) && !empty($_POST['note'])){

				// Convert posted values to variables
				$sku = $_POST['sku'];
				$user = $_POST['user'];
				$note = $_POST['note'];
				$dt = time();

				// Create SQL connection from saved login details
				$sql_details = parse_ini_file("private/mysql.ini");
				$conn = new mysqli($sql_details['host'], $sql_details['user'], $sql_details['password'], $sql_details['database']);

				// Insert form data into database
				$statement = $conn->prepare("INSERT INTO `notetable` (`id`, `sku`, `user`, `note`, `date`) VALUES (NULL, ?, ?, ?, FROM_UNIXTIME(?))");
				$statement->bind_param("sssi", $sku, $user, $note, $dt);
				if($statement->execute()){
					echo "<h3>Successfully Added Note.</h3>";
				}else{
					echo "<h3>Failed to Add Note.</h3>";
				}
				
				// Connect failed guard
				if($conn->connect_error)
					die("Failed to connect to mysql");


			}else{
				// No submission, display form
				echo '<div class="container"><form method="POST" action="addnote.php">
						<label for="sku">SKU: </label>
						<input type="text" name="sku"/><br/>
						<label for="user">Employee: </label>
						<input type="text" name="user"/><br/>
						<label for="note">Note: </label>
						<textarea  name="note" rows="4" cols="48"></textarea><br/>
						<input type="submit" name="submit" value="Submit Note"/>
					</form></div>';

				// Submit pressed with insufficient field completion
				if(isset($_POST['submit']))
					echo "<h3>Please fill all fields.</h3>";
			}
		
			echo '<a id="backbtn" class="btn" href="./">Back</a>';
		?>
	</body>
</html>
