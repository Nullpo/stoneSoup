<?php
class PlacesDAO {
	public $data;
	function __construct($data){
		$this->data = $data;
	}

	public function all(){ //All talks!
		$resp = array();
		foreach ($this->data as $key => $elem) {
			$resp = array_merge($resp, $elem->talks);
		}
		return $resp;
	}

	public function now(){
		$now = new DateTime();
		$resp = array();

		// Filter if init is before "NOW".
		$theQuery = array();
		$theQuery["type"] = "simple";
		$theQuery["operator"] = "antes";
		$theQuery["object"] = "charla";
		$theQuery["prop1"] = "timeDateInit";
		$theQuery["value"] = $now->format(DateUtils::getDateFormat());

		$queryResponse = $this->query($theQuery);

		// Filter if end is after "NOW" (the talk doesn't finished yet)
		$theQuery["type"] = "simple";
		$theQuery["operator"] = "despues";
		$theQuery["object"] = "charla";
		$theQuery["prop1"] = "timeDateFinish";
		$theQuery["value"] = $now->format(DateUtils::getDateFormat());		

		$resp = $this->query($theQuery,$queryResponse);

		return $resp;
	}

	public function after(){
		$now = new DateTime();
		$resp = array();

		// Filter if init is after "NOW"
		$theQuery = array();
		$theQuery["type"] = "simple";
		$theQuery["operator"] = "despues";
		$theQuery["object"] = "charla";
		$theQuery["prop1"] = "timeDateInit";
		$theQuery["value"] = $now->format(DateUtils::getDateFormat());

		$queryResponse = $this->query($theQuery);

		// TODO: Only use the Query language.
		// Obtained all AFTER elements.
		// Now, obtain minimals per Places:

		$resp = array();
		foreach ($queryResponse as $key => $elem) {
			$actualMinimal = null;
			if(array_key_exists($elem->place->id,$resp))
				$actualMinimal = $resp[$elem->place->id];

			$resp[$elem->place->id] = $elem->closerETA($actualMinimal);

		}

		return $resp;
	}

	public function before(){
		return array();
	}

	public function talksIn($idPlace){
		return array();
	}
	/*
	{
		- "type": "simple",
		- "operator": "(igual|mayor|menor|cantidad)",
		- "object" : "(salon|charla)"
		"prop1": name|timeDateInit|timeDateFinish|description|id|dataUrls|anyTalk",
		"prop2": si es anyTalk, la propiedad de la charla.
		"value": valor (constante) con el cual comparará.
		"return": "\d+|all"
	}
	*/
	public function query($theQuery, $optionalArray = null){
		
		// Operadores binarios
		$igualComparator = function ($elem,$i,$theQuery,$prop = "prop1",$value= "value"){
			return $elem->$theQuery[$prop] == $theQuery[$value];
		};
		$mayorComparator = function ($elem,$i,$theQuery,$prop = "prop1",$value= "value"){
			return $elem->$theQuery[$prop] > $theQuery[$value];
		};
		$menorComparator = function ($elem,$i,$theQuery,$prop = "prop1",$value= "value"){
			return $elem->$theQuery[$prop] < $theQuery[$value];
		};
		$cantidadComparator = function ($elem,$i,$theQuery,$prop= "prop1",$value= "value"){
			return count($elem->$theQuery[$prop]) == $theQuery[$value];
		};

		$antesComparator = function ($elem,$i,$theQuery,$prop = "prop1",$value= "value"){
			return $elem->$theQuery[$prop] <=  DateUtils::getDate($theQuery[$value]);
		};
		$despuesComparator = function ($elem,$i,$theQuery,$prop = "prop1",$value= "value"){
			return $elem->$theQuery[$prop] >=  DateUtils::getDate($theQuery[$value]);
		};
		
		// Operadores ternarios
		$entreComparator = function ($elem,$i,$theQuery) use (&$despuesComparator,&$antesComparator){
			$prop1 = "prop1";
			$prop2 = "prop2";
			if($theQuery["prop2"] == "")
				$prop2 = $prop1;
			
			return $despuesComparator($elem,$i,$theQuery,$prop1,"value1") &&
			$antesComparator($elem,$i,$theQuery,$prop2,"value2");
		};
		
		// Operadores lógicos 
		// TODO: Revisar!!!
		$OComparator = function($func1,$func2,$elem,$i,$theQuery){
			return call_user_func($func1,$elem,$i,$theQuery) ||
			call_user_func($func2,$elem,$i,$theQuery);
		};
		$YComparator = function($func1,$func2,$elem,$i,$theQuery){
			return call_user_func($func1,$elem,$i,$theQuery) &&
			call_user_func($func2,$elem,$i,$theQuery);
		};
		
		if ($theQuery["type"] == "simple"){
			// Divido por mayusculas
			$arr = preg_split('/(?=[A-Z])/',$theQuery["operator"]);
			$sizeArr = count($arr);
			/*
				Si sizeArr == 1, entonces estoy en una query comun.
				Si sizeArr > 2, entonces estoy en una query "compuesta".
			*/
			if($sizeArr == 1)
				$functionToDo = ${$arr[0] . "Comparator"};
			elseif($sizeArr>2){ //TODO: Aplicar a mas de dos argumentos
				
			}

			// Despues me fijo en el objeto.
			$arrayObjects = null;
			if($optionalArray){
				$arrayObjects = $optionalArray;
			} else if($theQuery["object"] == "charla"){
				$arrayObjects = $this->all();
			} else if($theQuery["object"] == "salon"){
				$arrayObjects = $this->data;
			}

			// Ahora voy a comparar!
			$queryResponse = array();
			foreach ($arrayObjects as $key => $elem){
				$r = call_user_func($functionToDo,$elem,$key,$theQuery);
				if($r){
					$queryResponse[] = $elem;
				}
			}
			
			return $queryResponse;
		}
		return "ERROR"; // see how to throw exceptions in php.
	}
}
?>
