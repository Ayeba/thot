<?php 
require 'conf/config.php';

require 'lib/mypdo.php';
require 'lib/user.class.php';
require 'lib/formation.class.php';
require 'lib/catalogue.class.php';
require 'inc/forms.inc.php';


$user = new user();
if (!$user->checkStatus()) {
	header ('Location: login.php');
	die();
}

$id = 0;
if (isset($_GET['id']))
	$id = (int)$_GET['id'];
if ($id == 0) {
	header('Location: list.php');
	die();
}



$db = new mypdo();
$formation = new formation($id);

if (isset($_POST['enregistrer']) && $_POST['date'] != '') {
	$dateParts = explode('/',$_POST['date']);
	$formation->addDate($_POST['ville'],$dateParts[2].'-'.$dateParts[1].'-'.$dateParts[0]);	
}

if (isset($_GET['deldate']) AND isset($_GET['date']) AND isset($_GET['ville']) AND $_GET['date'] != '' AND $_GET['ville'] != 0) {
	$dateParts = explode('/',$_GET['date']);
	$formation->delDate($_GET['ville'],$dateParts[2].'-'.$dateParts[1].'-'.$dateParts[0]);	
	header('Location: gestion_dates.php?id='.$id);
	die();
}


if (isset($_POST['ajouter']) AND $_POST['ville'] != '') {
	catalogue::$db = $db;
	catalogue::addVille($_POST['ville']);	
}


$dates = $formation->getDates();

include 'menu.php';
?>

<h1>G&eacute;rer les dates</h1>

<?php
$ville_id = ''; 
setlocale(LC_TIME, "fr_FR");
foreach ($dates as $date) {
	if ($ville_id != $date['ville_id']) {
		$ville_id = $date['ville_id'];
		echo '<br/>'.$date['nom_ville'].'<br/>';
	}
	$timeStamp = strtotime($date['date']);
	//echo strftime("%a %d/%m/%Y",$timeStamp).' <a href="gestion_dates.php?id='.$id.'&deldate=1&date='.strftime("%d/%m/%Y",$timeStamp).'&ville='.$date['ville_id'].'">X</a><br/>';
	echo strftime("%a %d/%m/%Y",$timeStamp).' <a href="#" class="delText" id="id='.$id.'&deldate=1&date='.strftime("%d/%m/%Y",$timeStamp).'&ville='.$date['ville_id'].'">X</a><br/>';
}
catalogue::$db = $db;
$villes = catalogue::getVilles();

?>
<script type="text/javascript" src="js/jquery.ui.datepicker-fr.js"></script>
<script type="text/javascript" src="js/date.js"></script>
<form method="post">
<input type="text" name="date" id="datepicker"/>
<?php echo genSelect($villes,'ville',1); ?>
<input type="submit" name="enregistrer" value="enregistrer"/>
</form>
<div id="dialog-confirm" title="Supprimer la date?"></div>
<br />
<br />
Ajouter une ville :
<form method=post>
<input type="text" name="ville">
<input type="submit" name="ajouter" value="ajouter">
</form>
<br/>
<br />
<a href="show_formation.php?id=<?php  echo $id;?>">retour</a>

