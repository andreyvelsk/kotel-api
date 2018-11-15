<?php
include 'dbconnect.php';

$interval = $_GET['interval'] ?? 3;
$sensor = 1;

$sql = "
SELECT value, vdatetime FROM ( 
SELECT @row := @row +1 AS rownum, value, vdatetime
FROM (
SELECT @row :=0) r, value 
WHERE id_sensor = $sensor AND
vdatetime BETWEEN now()-INTERVAL $interval HOUR AND now()
ORDER BY vdatetime ASC
) tmp
WHERE rownum MOD 5 = 0
";

$result = mysqli_query($conn, $sql);

$final = array();
$labels = array();
$data = array();
$dataset = array();
$dataset1 = array();
$index = 0;

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        array_push($labels, $row['vdatetime']);
        array_push($data,  $row['value']);
     	$index++;
    }
    $dataset1['label'] = 't_pod';
    $dataset1['data'] = $data;
    array_push($dataset, $dataset1);
    
    $final['labels'] = $labels;
    $final['datasets'] = $dataset;

	$json = json_encode($final); 

	echo $json;
} else {
    echo "0 results";
}



?>