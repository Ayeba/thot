<?php 
require_once('inc/fpdi/fpdf.php'); 
require_once('inc/fpdi/fpdi.php'); 	 
require_once('inc/fpdi/myfpdi.php');
require ('lib/selection.class.php');
require ('lib/cataloguepdf.class.php');
require ('lib/formation.class.php');
require ('lib/catalogue.class.php');
require ('lib/user.class.php');
require ('lib/mypdo.php');
require ('conf/config.php');



$db = new mypdo();
cataloguePdf::$db = $db;
catalogue::$db = $db;
formation::$db = $db;
user::$db = $db;


$user = new user();
if (!$user->checkStatus()) {
	header ('Location: login.php');
	die();
}


$id = 0;
if (isset($_GET['id']))
	$id = (int)$_GET['id'];

if ($id == 0) {
	die();
}

$selectionPdf = new cataloguePdf();
if (isset($_GET['idModele'])) {
	$selectionPdf->setModeleFormation($_GET['idModele']);
}
$selectionPdf->fpdi = new myfpdi();
$selectionPdf->addPageFormation($id);
$selectionPdf->fpdi->Output();
