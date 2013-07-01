<?php
	function generateMocks(){
		$mocks = Config::get()->talks;
		
		$response = array();
		foreach($mocks as $key => $elem){
			$response[] = Place::fromJsonObject($elem);
		}
		
	 	return $response;
	}

	$places = generateMocks();
?>
