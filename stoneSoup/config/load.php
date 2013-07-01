<?php
class Config{
	public static function get(){
		$filename = "generalConfig.json";
		$contents = file_get_contents($filename,true);

		$configObject = json_decode($contents);
		
		$configObject->talks = json_decode(file_get_contents($configObject->fileDataTalks,true));

		return $configObject;
	}
}

?>