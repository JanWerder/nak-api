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

class SpeiseplanParser{
	function __construct($url){
		$this->dom = new DomDocument;
		$this->dom->loadHTMLFile($url);

		$this->xpath = new DomXPath($this->dom);

		$this->descrNodes = $this->xpath->query("//div[contains(@class,'speiseplan-kurzbeschreibung')]");
		$this->priceNodes = $this->xpath->query("//div[contains(@class,'speiseplan-preis')]");
		$this->dateNodes = $this->xpath->query("//td[contains(@class,'speiseplan-head')]");
		$this->speiseplan = array();
		$this->days = array("monday", "tuesday", "wednesday", "thursday", "friday");
	}

	function parse(){

		if(null !== $this->descrNodes->item(0)){

			$domElementIndex = 0;
			foreach($this->days as $day){
				$speiseplan[$day] = array();
				$this->getDay($domElementIndex,$day,"meal1");
				$domElementIndex++;
				$this->getDay($domElementIndex,$day,"meal2");
				$domElementIndex++;
			}
			print_r(json_encode($this->speiseplan));
		}

	}

	function getDay($index,$day,$meal){
		if (null !== $this->descrNodes->item($index)){
			$this->speiseplan[$day][$meal] = array( "description" => trim($this->descrNodes->item($index)->nodeValue), "price" => trim($this->priceNodes->item($index)->nodeValue));
		}else{
			$this->speiseplan[$day][$meal] = array( "description" => null, "price" => null);
		}

	}

}

$parser = new SpeiseplanParser($url);
$parser->parse();

?>
</body>