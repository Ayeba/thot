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

if (isset($_POST['enregistrer']) && isset($_POST['plusloins'])) {
	$formation->updatePlusLoins($_POST['plusloins']);
	header('Location: show_formation.php?id='.$formation->id);
	die();
}


$plusLoins = $formation->getPlusLoins();

$catalogue = new catalogue();
$listeAlpha = $catalogue->listAllExcept($id);


include 'menu.php';
?>

<h1>G&eacute;rer les formations "pour aller plus loin"</h1>
formation : <?php echo $formation->nom;?>
<br /><br />
<form method="post">
<?php
foreach($listeAlpha as $formation) {
	if (in_array($formation,$plusLoins))
		$checked = 1;
	else
		$checked = 0;
	echo genCheckBoxFormation($formation['nom_formation'],$formation['id_formation'],'plusloins[]',$checked); 
	echo '<br/>';
}

?>


<input type="submit" name="enregistrer" value="enregistrer"/>
</form>
<br />
<a href="show_formation.php?id=<?php  echo $id;?>">retour</a>

