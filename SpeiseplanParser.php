<?php

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
            $domDateIndex = 0;
            foreach($this->days as $day){
                $this->speiseplan[$day] = array();
                $this->speiseplan[$day]["date"] = substr(trim($this->dateNodes->item($domDateIndex)->nodeValue),-6);
                $this->getDay($domElementIndex,$day,"meal1");
                $domElementIndex++;
                $this->getDay($domElementIndex,$day,"meal2");
                $domElementIndex++;
                $domDateIndex++;
            }
            return $this->speiseplan;
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