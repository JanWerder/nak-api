<html>
<meta charset="utf-8">
</html>
<body>
<?php
libxml_use_internal_errors(true);

function getWeekDates($year, $week)
{
    return strtotime("{$year}-W{$week}-6");
}


$dom = new DomDocument;

$url = "https://cis.nordakademie.de/service/tp-mensa/speiseplan.cmd";

if(!empty($_GET["date"])){
	$date = $_GET["date"]; //UNIX timestamp des Samstags der Woche
	$url .= "?date=" . $date . "999&action=show";
}

if(!empty($_GET["year"])){
	$year = $_GET["year"];
	if(!empty($_GET["week"])){
		$week = $_GET["week"];
		$url .= "?date=" . getWeekDates($year,$week) . "999&action=show";
	}
}

$dom->loadHTMLFile($url);

$xpath = new DomXPath($dom);

$descrNodes = $xpath->query("//div[contains(@class,'speiseplan-kurzbeschreibung')]");
$priceNodes = $xpath->query("//div[contains(@class,'speiseplan-preis')]");
$dateNodes = $xpath->query("//td[contains(@class,'speiseplan-head')]");

if(null !== $descrNodes->item(0)){
	$speiseplan = array();
	$speiseplan["monday"] = array();
	$speiseplan = getDay(0,"monday","meal1", $descrNodes, $priceNodes, $speiseplan);
	$speiseplan = getDay(1,"monday","meal2", $descrNodes, $priceNodes, $speiseplan);

	$speiseplan["tuesday"] = array();
	$speiseplan = getDay(2,"tuesday","meal1", $descrNodes, $priceNodes, $speiseplan);
	$speiseplan = getDay(3,"tuesday","meal2", $descrNodes, $priceNodes, $speiseplan);

	$speiseplan["wednesday"] = array();
	$speiseplan = getDay(4,"wednesday","meal1", $descrNodes, $priceNodes, $speiseplan);
	$speiseplan = getDay(5,"wednesday","meal2", $descrNodes, $priceNodes, $speiseplan);

	$speiseplan["thursday"] = array();
	$speiseplan = getDay(6,"thursday","meal1", $descrNodes, $priceNodes, $speiseplan);
	$speiseplan = getDay(7,"thursday","meal2", $descrNodes, $priceNodes, $speiseplan);

	$speiseplan["friday"] = array();
	$speiseplan = getDay(8,"friday","meal1", $descrNodes, $priceNodes, $speiseplan);
	$speiseplan = getDay(9,"friday","meal2", $descrNodes, $priceNodes, $speiseplan);
	

	print_r(json_encode($speiseplan));
}

function getDay($index,$day,$meal, $descrNodes, $priceNodes, $speiseplan){
	if (null !== $descrNodes->item($index)){
		$speiseplan[$day][$meal] = array( "description" => trim($descrNodes->item($index)->nodeValue), "price" => trim($priceNodes->item($index)->nodeValue));
	}else{
		$speiseplan[$day][$meal] = array( "description" => null, "price" => null);
	}
	return $speiseplan;
	
}



?>
</body>