<?php

require_once('db.php');

function connect(){

// Checks the connection to the local install of MySQL

$pdo = new PDO("mysql:host=localhost;dbname=winestore;port=59443", "webadmin", "root") or die("Could not connect");
    return $pdo;

}



function get_regions(){
    $conn = connect();

    $sql = "SELECT * FROM region";

    foreach($conn->query($sql) as $row){
        echo "<option value='";
        if($row['region_name']=='All'){
            echo '';
        }
        else{
          echo $row['region_name'];
        }

        echo "'>".$row['region_name']."</option>";
    }


}

function get_variety(){
    $conn = connect();

    $sql = "SELECT * FROM grape_variety";
    foreach ($conn->query($sql) as $row){
       echo "<option value='".$row['variety'] ."'>".$row['variety']."</option>";
    }

}

function get_years(){

    $conn = connect();

    $sql = "SELECT MAX(year) FROM wine";
    $val= $conn->query($sql)->fetch();


    $max = $val[0];

    $sql = "SELECT MIN(year) FROM wine";
    $val = $conn->query($sql)->fetch();

    $min = $val[0];

    for($i=$min;$i<=$max;$i++){

        echo "<option value\"$i\" >$i</option>";

    }

}


?>
