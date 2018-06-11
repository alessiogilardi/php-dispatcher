<?php
require_once './API/core.php';
$sec = new Security(128);

$rm = new RouteManager();
$dis = new Dispatcher();
$dis->setRouteManager($rm);
$dis->setControllerPath(__DIR__.DIRECTORY_SEPARATOR.'controllers');
$cont = $dis->dispatch();



//echo parse_url($url, PHP_URL_PATH);



/*
$rm = new RouteManager();
$dis = new Dispatcher();
$dis->setRouteManager($rm);
$dis->setControllerPath(__DIR__.DIRECTORY_SEPARATOR.'controllers');
$dis->dispatch();
*/
// In base alle eccezzioni uso il dispatcher per aggiungere ed inviare header


?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<form action='#' method="POST">
		<input type="text" name="prova">
		<input type="submit" name="submit">
	</form>
</body>
</html>