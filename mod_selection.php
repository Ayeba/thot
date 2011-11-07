<?php 
require 'conf/config.php';

require 'lib/mypdo.php';
require 'lib/user.class.php';
require 'lib/selection.class.php';
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
	header('Location: list_selection.php');
	die();
}


$db = new mypdo();

$selection = new selection($id);

if (isset($_POST['enregistrer'])) {
	if ($_POST['nom'] != '') {
		$selection->nom = $_POST['nom'];
		$selection->commentaire = $_POST['commentaire'];
		$selection->save();
	}
	$selection->updateFormations($_POST['formations']);
	header('Location: show_selection.php?id='.$id);
}





$catalogue = new catalogue();
$listeAlpha = $catalogue->listByStatus(2);

include 'menu.php';
?>



<h1>Modifier la selection</h1>

derni&egrave;re mise &agrave; jour : <?php echo date('d/m/Y H:i', strtotime($selection->dateMod));?>
<br />
<form method="post">
<div style="position:relative;top:20px;">nom : </div>
<div style="position:relative;left:120px;"><input type="text" name="nom" value="<?php echo $selection->nom; ?>"></div>
<div style="position:relative;top:20px;">commentaire : </div>
<div style="position:relative;left:120px;"><textarea name="commentaire"><?php echo $selection->commentaire; ?></textarea></div>
<br /><br />
formations : 
<br/><br />
<?php 
foreach($listeAlpha as $formation) {
	if (in_array($formation,$selection->formations))
		$checked = 1;
	else
		$checked = 0;
	echo genCheckBoxFormation($formation['nom_formation'],$formation['id_formation'],'formations[]',$checked); 
	echo '<br/>';
}


?>

<br /><br />
<input type="submit" name="enregistrer" value="enregistrer"> 
</form></html>