<?php
require 'conf/config.php';

require 'lib/mypdo.php';
require 'lib/catalogue.class.php';
require 'inc/forms.inc.php';


$id = 0;
if (isset($_GET['id']))
	$id = (int)$_GET['id'];

$catalogue = new catalogue();

$listeAlpha = $catalogue->listAll();

$selected = $catalogue->listByCritere($id);

$nomCritere = catalogue::getCritereById($id);


include 'menu.php';
?>

<H1>formations pour le crit&egrave;re <?php echo $nomCritere; ?></H1>

<?php
foreach($listeAlpha as $formation) {
	if (in_array($formation,$selected))
		$checked = 1;
	else
		$checked = 0;
	echo '<a href="create_formation.php?id='.$formation['id_formation'].'">';
	echo genCheckBox($formation['nom_formation'],$formation['id_formation'],'formations[]',$checked); 
	echo '</a>';
	echo '<br/>';
}

