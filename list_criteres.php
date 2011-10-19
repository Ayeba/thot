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
$listFamillesCriteres = catalogue::getAllFamillesCriteres();

include 'menu.php';

?>

<H1>liste des crit&egrave;res par famille</H1>

<?php 
foreach ($listFamillesCriteres as $famille) {
	echo '<H2>'.$famille['nom'].'</H2>';
	foreach ($famille['criteres'] as $id=>$critere) {
		echo '<a href="list_formations_for_critere.php?id='.$id.'">'.$critere."</a><br />";
	}
}


?>

</html>