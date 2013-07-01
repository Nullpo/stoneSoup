<?php

class DateUtils {
	public static function getDateFormat(){
		return 'Y/m/d H:i:s';
	}
	
	public static function getDate($string){
		return DateTime::createFromFormat( DateUtils::getDateFormat(),$string);
	}
}

class Talk {
	public $name;
	public $timeDateInit;
	public $timeDateFinish;
	public $description;
	public $id;
	public $dataUrls;
	public $place;
	function __construct($id = "", $name = "<None>", $timeDateInit = null, $timeDateFinish= null, $description = "<Empty>", $dataUrls = "",
		$place = "<None>") {
       $this->name = $name;
       $this->timeDateInit = $timeDateInit;
       $this->timeDateFinish = $timeDateFinish;
       $this->description = $description;
       $this->id = $id;
       $this->dataUrls = $dataUrls;
       $this->place = $place;
   }
   
   public static function fromJsonObject($object){
		$instance = new self();
		$instance->name = $object->name;
		
		$instance->timeDateInit = DateUtils::getDate($object->timeDateInit->date);
		$instance->timeDateFinish = DateUtils::getDate($object->timeDateFinish->date);
		$instance->description = $object->description;
		$instance->id = $object->id;
		$instance->dataUrls = $object->dataUrls;
		
		
		return $instance;
	}

	public function ETA(){
		return $this->timeDateInit->diff(new DateTime());
	}

	public function closerETA($talk){
		if($talk)
			return $this->ETA() < $talk->ETA()? $this : $talk;
		else
			return $this;
	}
}

class Place {
	public $name;
	public $talks;
	public $urlPhoto;
	public $id;

	function __construct($id = 0, $name = "<None>", $talks = array(),$urlPhoto = ""){
		$this->name = $name;
		$this->talks = $talks;
		$this->urlPhoto = $urlPhoto;
		$instance->id = $id;

	}
	
	public static function fromJsonObject($object){
		$instance = new self();
		$instance->name = $object->name;
		$instance->urlPhoto = $object->urlPhoto;
		$instance->talks = array();
		$instance->id = $object->id;

		$instanceForTalks = clone $instance;
		$tmpTalks = array();
		
		foreach($object->talks as $key => $elem){
			$newTalk = Talk::fromJsonObject($elem);
			$newTalk->place = $instanceForTalks;
			$tmpTalks[] = $newTalk;
		}
		
		$instance->talks = $tmpTalks;
		
		return $instance;
	}
} 





?>
