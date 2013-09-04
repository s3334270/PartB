<?php

include('utilities.php');

function process_form($form_array)
 {
    $fields_array = array
        (
        "wine_name" => " LIKE ",
        "winery_name" => " LIKE ",
        "region_name" => " LIKE ",
        "variety" => " LIKE ",
        "stock" => " >= ",
        "order" => " >= ",
        "year_min" => " >= ",
        "year_max" => " <= ",
        "max_price" => " <= ",
        "min_price" => " >= "
    );

    $query_terms = "";

    foreach ($form_array as $key => $value) {

        if (array_key_exists($key, $fields_array) && $value != "") {

            $query_terms .=" AND ";
            switch ($key) {
                case 'year_min': $query_terms.='wine.year >= ' . $value;
                    break;

                case 'year_max': $query_terms.='wine.year <= ' . $value;
                    break;

                case 'max_price': $query_terms.='items.price <=' . $value;
                    break;

                case 'min_price':$query_terms.='items.price >=' . $value;
                    break;

                case 'stock':$query_terms.='inventory.on_hand >=' . $value;
                    break;

                case 'order':$query_terms.='items.qty >=' . $value;
                    break;

                case 'wine_name': $query_terms.='wine.wine_name LIKE \'%' . $value . '%\'';
                    break;

                case 'winery_name': $query_terms.='winery.winery_name LIKE \'%' . $value . '%\'';
                    break;

                case 'region_name': $query_terms.='region.region_name LIKE \'%' . $value . '%\'';
                    break;

                case 'variety': $query_terms.='grape_variety.variety LIKE \'%' . $value. '%\'';
                    break;
            }
        }
    }
    return $query_terms;
}

if (isset ($_POST['wine_name'], $_POST['winery_name'], $_POST['region_name'], $_POST['variety'], $_POST['stock'], $_POST['order'], $_POST['year_min'], $_POST['year_max'], $_POST['max_price'], $_POST['min_price']))
	{
		$errors = array();
		$year_min = $_POST['year_min'];
		$year_max = $_POST['year_max'];
		$max_price = $_POST['max_price'];
		$min_price = $_POST['min_price'];
		
		if ($year_min > $year_max)
			{
				$errors[] = 'Minimum Year Must not exceed Maximum Year';
			}
		if ($min_price > $max_price)
			{
				$errors[] = 'Minimum Price Must not exceed Maximum Price';
			}
		for ($i=0; $i<sizeof($errors); $i++) 
			{
			echo $errors[$i];
			}
	}

function get_wine_info($search_params) {
    
	$conn = connect();
	
	$idx = 0;

	$params = explode("|", $search_params);
    
	$sql = "SELECT SUM(items.qty) as total_qty, SUM(items.price) as total_revenue, wine.wine_name,inventory.cost, items.price, items.qty, inventory.on_hand,
	winery.winery_name, region.region_name,wine.year, grape_variety.variety
	FROM wine,inventory,items, winery, region,grape_variety, wine_variety
	WHERE (wine.wine_id = items.wine_id AND wine.wine_id = inventory.wine_id
	AND winery.winery_id = wine.winery_id AND winery.region_id = region.region_id AND wine.wine_id=wine_variety.wine_id
	AND grape_variety.variety_id=wine_variety.variety_id" . 
	$params[0] . ") GROUP BY wine.wine_id ";
		
	echo "
	<table align = center>
		<thead>
			<tr>
				<th style='text-align:left;width:60px;'></th>
				<th>Wine Name</th>
				<th>Variety</th>
				<th>Year</th>
				<th>Winery Name</th>
				<th>Region Name</th>
				<th>Inventory Cost</th>
				<th>Stock on Hand</th>
				<th>Quantity Sold</th>
				<th>Total Revenue</th>
			</tr>
		</thead>
	<tbody>

	";
	
		foreach ($conn->query($sql) as $results)
			{

				echo "
				<tr>
				<td style='text-align:centre;'>$idx</td>
				<td>$results[wine_name]</td>
				<td>$results[variety]</td>
				<td>$results[year]</td>
				<td>$results[winery_name]</td>
				<td>$results[region_name]</td>
				<td>$ $results[cost]</td>
				<td>$results[on_hand]</td>
				<td>$results[total_qty]</td>
				<td>$ $results[total_revenue]</td>

				</tr>";

				$idx++;
				
				

			}
				
    echo "</tbody></table>";
		if ($idx == 0)
			{
				echo "<tr><td>No Data Found</td></tr>";
			}
}

include('header.php');
?>


<div id="main-container">
<div id="main-inner">

<div id="results-container">
<?php get_wine_info(process_form($_POST)); ?>


</div>

</div>
</div>
