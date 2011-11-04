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


$id = 0;
if (isset($_GET['id']))
	$id = (int)$_GET['id'];

$catalogue = new catalogue();

$message = '';
if (isset($_POST['enregistrer'])) {
	if (!isset($_POST['formations']))
		$_POST['formations'] = array();
	$catalogue->updateByCritere($id,$_POST['formations']);
	$message = 'mise &agrave; jour effectu&eacute;';
}


$listeAlpha = $catalogue->listAll();

$selected = $catalogue->listByCritere($id);

$nomCritere = catalogue::getCritereById($id);





include 'menu.php';
?>

<H1>formations pour le crit&egrave;re <?php echo $nomCritere; ?></H1>
<?php echo $message; ?>
<form method="post">
<?php
foreach($listeAlpha as $formation) {
	if (in_array($formation,$selected)) {
		echo $formation['nom_formation'];
	echo '<br/>';
	}
} 
?>
<br />
<br />
<a href="mod_formations_for_critere.php?id=<?php echo $id; ?>">modifier</a>
</html>