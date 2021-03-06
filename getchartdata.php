<?php
include 'dbconnect.php';

$interval = $_GET['interval'] ?? 3;
$sensor =  $_GET['sensor'] ?? [1];
$dataperiod = $_GET['period'] ?? 5;

$final = array();
$dataset = array();
$dataset_data = array();

for ($i = 0; $i < count($sensor); $i++){

    $labelname='sensor'.$sensor[$i];
    $sql_labelname = "
    SELECT s.name FROM sensors s WHERE s.id = $sensor[$i];
    ";
    $result_labelname = mysqli_query($conn, $sql_labelname);
    if(mysqli_num_rows($result_labelname) > 0) {
        while($row = mysqli_fetch_assoc($result_labelname)){
            $labelname=$row['name'];
        }
    }

    $data = array();
    $labels = array();
    $sql = "
    SELECT value, vdatetime FROM ( 
    SELECT @row := @row +1 AS rownum, value, vdatetime
    FROM (
    SELECT @row :=0) r, value 
    WHERE id_sensor = $sensor[$i] AND
    vdatetime BETWEEN now()-INTERVAL $interval HOUR AND now()
    ORDER BY vdatetime ASC
    ) tmp
    WHERE rownum MOD $dataperiod = 0
    ";
    // rownum mod 5 - выбираем каждую 5 строчку

    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            array_push($labels, $row['vdatetime']);
            array_push($data, $row['value']);
            }

            $dataset_data['label'] = $labelname;
            $dataset_data['data'] = $data;
            array_push($dataset, $dataset_data);
    }

    $final['labels'] = $labels;
}    
    $final['datasets'] = $dataset;

	$json = json_encode($final); 

     echo $json;



?>