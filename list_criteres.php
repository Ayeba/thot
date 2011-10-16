<?php
require 'conf/config.php';

require 'lib/mypdo.php';
require 'lib/catalogue.class.php';
require 'inc/forms.inc.php';


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