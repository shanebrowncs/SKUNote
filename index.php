<!DOCTYPE html>

<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="layout.css"/>
		<title>SKU Note</title>
	</head>

	<body>
		<a id="header" href="./"><h1>SKU Note</h1></a>
		
		<?php
			// Check if user entered SKU before continuing
			if(isset($_GET['sku']) && !empty($_GET['sku'])){
				$sku = $_GET['sku'];
				// Create SQL connection from saved login details
				$sql_details = parse_ini_file("private/mysql.ini");
				$conn = new mysqli($sql_details['host'], $sql_details['user'], $sql_details['password'], $sql_details['database']);
				
				// Connect failed guard
				if($conn->connect_error)
					die("Failed to connect to mysql");

				// Retrieve notes corresponding to given SKU
				$statement = $conn->prepare("SELECT * FROM `notetable` WHERE sku = ? ORDER BY date DESC");
				$statement->bind_param("s", $sku);
				$statement->execute();

				// Parse SQL result into table	
				$result = $statement->get_result();
				if($result->num_rows == 0){
					echo '<h3>No Entries Found..</h3>';
				}else{
					echo "<h3>SKU: " . $sku . "</h3>";
					echo '<table><tr><th>Note</th><th>Employee</th><th>Date</th></tr>';

					// Add each row to table
					while($cur = $result->fetch_assoc()){
						echo '<tr>';
						echo '<td>' . $cur['note'] . '</td><td>' . $cur['user'] . '</td><td>' . $cur['date'] . '</td>';
						echo '</tr>';
					}
					echo '</table>';
				}

				echo '<a id="backbtn" class="btn" href="./">Back</a>';

			}else{
				// No submition, display form
				echo '<form method="GET" action="index.php">
						<label for="sku">SKU: </label>
						<input type="text" name="sku"/>
						<input type="submit" value="Search SKU"/>
					</form>
					<a class="btn" href="./addnote.php">Add Note</a>';
			}
		?>
	</body>
</html>
