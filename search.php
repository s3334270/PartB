<?php

    $conn = mysql_connect('localhost:59443','webadmin','root') or die("Could not connect");
	
    mysql_select_db('winestore',$conn);

    $sql = "SELECT * FROM winery";

    $results = mysql_query($sql);
	
	/*
	if ($results == NULL)
	{
		echo 'No Match Found';
	}
	else
	{
		print_r($results);
	}
	*/

?>
