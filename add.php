<?php
include 'dbconnect.php';

//$sql = "INSERT INTO value VALUES (default, ".$_GET['id_sensor'].", '".$_GET['value']."', now())";

$sensor = $_GET['id_sensor']; 
$sensorvalue = $_GET['value']; 


  	$sql = "INSERT INTO value VALUES (default, ?, ?, now());";

	if ($stmt = $conn->prepare($sql)) {
	    $stmt->bind_param('ss', $sensor, $sensorvalue);
	    $stmt->execute();
	} else {
	    echo "Error: " . $sql . "<br>" . $conn->error;
	}
	
	$conn->close();
?>
