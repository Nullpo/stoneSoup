<?php
date_default_timezone_set('America/Argentina/Buenos_Aires');

include '../model.php';

$dao = new PlacesDAO($places);


switch ($_GET["q"]){
		case "allData" : $resp = json_encode($dao->data);;break;
		case "now": $resp = json_encode($dao->now());; break;
		case "after": $resp = json_encode($dao->after());; break;
		case "before": $resp = json_encode($dao->before());; break;
		case "query": $resp = json_encode($dao->query($_POST["query"]));
		break;
		default: $resp = '["STATUS":"BAD REQUEST"]';
}
header('Content-type: application/json');
exit($resp);
?>
