<html>
<meta charset="utf-8">
</html>
<body>
<?php
include('SpeiseplanParser.php');
include('util.php');
libxml_use_internal_errors(true);

$url = "https://cis.nordakademie.de/service/tp-mensa/speiseplan.cmd";

if(!empty($_GET["date"])){
	$date = $_GET["date"]; //UNIX timestamp des Samstags der Woche (s. ReadMe)
	$url .= "?date=" . $date . "999&action=show";
}elseif(!empty($_GET["year"])){
	$year = $_GET["year"];
	if(!empty($_GET["week"])){
		$week = $_GET["week"];
		$url .= "?date=" . getWeekDates($year,$week) . "999&action=show";
	}
}


$parser = new SpeiseplanParser($url);
if (json_encode($parser->parse()) != "null") {
	print_r(json_encode($parser->parse()));
}else{
	echo "[]";
}

?>
</body>