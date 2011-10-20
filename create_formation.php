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



$db = new mypdo();
$formation = new formation($id);

if (isset($_POST['enregistrer'])) {
	$values['nom'] = $_POST['nom'];
	foreach ($_POST['criteres'] as $famille){
		foreach($famille as $critere) {
			$values['criteres'][] = $critere;
		}
	}	
	$formation->save($values);	
	echo "modifi&eacute;";	
}

catalogue::$db = $db;
$famillesCriteres = catalogue::getAllFamillesCriteres();
$selectedCriteres = $formation->getCriteres();

include 'menu.php';
?>



<h1>Cr&eacute;er / modifier une formation</h1>

<form method="POST" action="create_formation.php?id=<?php echo $formation->id; ?>">
<div style="position:relative;top:20px;">nom : </div><div style="position:relative;left:120px;"><input type="text" name="nom" value="<?php echo $formation->nom; ?>"/></div>


<?php 
foreach ($famillesCriteres as $idFamille=>$familleCriteres) {
	
	echo '<div style="position:relative;top:20px;">'.$familleCriteres['nom'].' : </div>';
	echo '<div style="position:relative;left:120px;">';
	echo genMultCheckBox($familleCriteres['criteres'],"criteres['.$idFamille.'][]",$selectedCriteres);
	echo '</div>';
	
	
}

?>


<input type="submit" name="enregistrer" value="enregistrer"/>

<?php
if ($formation->id != 0)
	echo '<a href="delete_formation.php?id='.$formation->id.'">supprimer</a>';
?>

</form>



</html>


<?php
