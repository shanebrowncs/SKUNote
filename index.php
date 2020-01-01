<!DOCTYPE html>

<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="icon" type="image/png" href="images/favicon.png"/>
		<link rel="stylesheet" type="text/css" href="layout.css"/>
		<title>SKU Note</title>
	</head>

	<body>
		<a id="header" href="./"><h1>SKU Note</h1></a>
		
		<?php
			// Check if user entered SKU before continuing
			if(isset($_GET['sku']) && !empty($_GET['sku'])){
				require_once("private/dbhandler.php");

				// Sanitize and strip given sku
				$sku = htmlspecialchars($_GET['sku'], ENT_QUOTES, 'UTF-8');
				$sku = ltrim($sku, '0');

				// Create SQL connection from saved login details
				$handler = new DBHandler("private/mysql.ini");

				$notes = $handler->retrieve_notes($sku);
				if(!$notes || count($notes) < 1){
					echo '<h3>No Entries Found..</h3>';
				}else{
					echo "<h3>SKU: " . $sku . "</h3>";
					echo '<table><tr><th>Note</th><th>Employee</th><th>Date</th></tr>';

					// Add each row to table
					foreach($notes as &$cur){
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
