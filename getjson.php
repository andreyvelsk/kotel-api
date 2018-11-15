<?php
include 'dbconnect.php';

$sql = "SELECT * FROM
(SELECT v.id_sensor, s.name, v.value, v.vdatetime
FROM value v
RIGHT JOIN sensors s ON v.id_sensor = s.id
ORDER BY vdatetime DESC LIMIT 10
) tmp
GROUP BY id_sensor";

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