<?php
require 'conf/config.php';

require 'lib/user.class.php';
require 'lib/mypdo.php';
require 'lib/catalogue.class.php';
require 'inc/forms.inc.php';

$user = new user();
if (!$user->checkStatus()) {
	header ('Location: login.php');
	die();
}

$db = new mypdo();
catalogue::$db = $db;


if (isset($_GET['del']) AND isset($_GET['id']) AND $_GET['id'] != 0) {
	catalogue::delCritere($_GET['id']);
	header('Location: list_criteres.php');
	die();
}

if (isset($_POST['ajouter'])) {
	catalogue::addCritere($_POST['critere'], $_POST['famille']);
	header('Location: list_criteres.php');
	die();
}

$listFamillesCriteres = catalogue::getAllFamillesCriteres();

include 'menu.php';

?>

<H1>liste des crit&egrave;res par famille</H1>
<script type="text/javascript" src="js/list_criteres.js"></script>
<form method=POST>
<input type="text" name="critere">
<?php 
$familles = catalogue::getFamilles();
echo genSelect($familles,'famille');

?>
<input type="submit" name="ajouter" value="ajouter">
</form>
<?php 
foreach ($listFamillesCriteres as $famille) {
	echo '<H2>'.$famille['nom'].'</H2>';
	foreach ($famille['criteres'] as $id=>$critere) {
		echo '<a href="list_formations_for_critere.php?id='.$id.'">'.$critere."</a> <a class='delText' id='".$id."' href='#'>X</a><br />";
	}
}


?>
<div id="dialog-confirm" title="Supprimer le crit&egrave;re?"></div>
</html>