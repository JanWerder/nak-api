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
	$speiseplan["monday"]["date"] = substr(trim($dateNodes->item(0)->nodeValue),-6);
	$speiseplan["monday"]["meal1"] = array( "description" => trim($descrNodes->item(0)->nodeValue), "price" => trim($priceNodes->item(0)->nodeValue));
	$speiseplan["monday"]["meal2"] = array( "description" => trim($descrNodes->item(1)->nodeValue), "price" => trim($priceNodes->item(1)->nodeValue));

	$speiseplan["tuesday"] = array();
	$speiseplan["tuesday"]["date"] = substr(trim($dateNodes->item(1)->nodeValue),-6);
	$speiseplan["tuesday"]["meal1"] = array( "description" => trim($descrNodes->item(2)->nodeValue), "price" => trim($priceNodes->item(2)->nodeValue));
	$speiseplan["tuesday"]["meal2"] = array( "description" => trim($descrNodes->item(3)->nodeValue), "price" => trim($priceNodes->item(3)->nodeValue));

	$speiseplan["wednesday"] = array();
	$speiseplan["wednesday"]["date"] = substr(trim($dateNodes->item(2)->nodeValue),-6);
	$speiseplan["wednesday"]["meal1"] = array( "description" => trim($descrNodes->item(4)->nodeValue), "price" => trim($priceNodes->item(4)->nodeValue));
	$speiseplan["wednesday"]["meal2"] = array( "description" => trim($descrNodes->item(5)->nodeValue), "price" => trim($priceNodes->item(5)->nodeValue));

	$speiseplan["thursday"] = array();
	$speiseplan["thursday"]["date"] = substr(trim($dateNodes->item(3)->nodeValue),-6);
	$speiseplan["thursday"]["meal1"] = array( "description" => trim($descrNodes->item(6)->nodeValue), "price" => trim($priceNodes->item(6)->nodeValue));
	$speiseplan["thursday"]["meal2"] = array( "description" => trim($descrNodes->item(7)->nodeValue), "price" => trim($priceNodes->item(7)->nodeValue));

	$speiseplan["friday"] = array();
	$speiseplan["friday"]["date"] = substr(trim($dateNodes->item(4)->nodeValue),-6);
	$speiseplan["friday"]["meal1"] = array( "description" => trim($descrNodes->item(8)->nodeValue), "price" => trim($priceNodes->item(8)->nodeValue));
	$speiseplan["friday"]["meal2"] = array( "description" => trim($descrNodes->item(9)->nodeValue), "price" => trim($priceNodes->item(9)->nodeValue));

	print_r(json_encode($speiseplan));
}



?>
</body>