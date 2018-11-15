<?php
include 'dbconnect.php';

$interval = $_GET['interval'];

$sql = "
SELECT value, vdatetime FROM ( 
SELECT @row := @row +1 AS rownum, value, vdatetime
FROM (
SELECT @row :=0) r, value 
WHERE id_sensor = 1 AND
vdatetime BETWEEN now()-INTERVAL $interval HOUR AND now()
ORDER BY vdatetime ASC
) tmp
WHERE rownum MOD 5 = 0
";

$result = mysqli_query($conn, $sql);

$yourArray = array(); // make a new array to hold all your data
$index = 0;

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        $yourArray[$index] = $row;
     	$index++;
    }

	$json = json_encode($yourArray); 

	echo $json;
} else {
    echo "0 results";
}



?>