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

catalogue::$db = $db;
$famillesCriteres = catalogue::getAllFamillesCriteres();
$selectedCriteres = $formation->getCriteres();

if ($formation->image == '') {
	$image = 'default.png';
}
else {
	$image = $formation->image;
}

include 'menu.php';
?>



<h1>Afficher une formation</h1>

<?php 
if ($formation->id != 0) {
	echo '<a href="gestion_dates.php?id='.$formation->id.'">g&eacute;rer les dates</a>';
}

?>

<div style="position:relative;top:20px;">nom : </div><div style="position:relative;left:120px;"><?php echo $formation->nom; ?></div>
<div style="position:relative;top:20px;">code : </div><div style="position:relative;left:120px;"><?php echo $formation->code; ?></div>
<div style="position:relative;top:20px;">sous-titre : </div><div style="position:relative;left:120px;width: 494px;"><?php echo $formation->sousTitre; ?></div>
<div style="position:relative;top:20px;">description : </div><div style="position:relative;left:120px;width: 494px;"><?php echo $formation->description; ?></div>
<div style="position:relative;top:20px;">objectifs : </div><div style="position:relative;left:120px;width: 494px;"><?php echo $formation->objectifs; ?></div>
<div style="position:relative;top:20px;">pre-requis : </div><div style="position:relative;left:120px;width: 494px;"><?php echo $formation->preRequis; ?></div>
<div style="position:relative;top:20px;">programme : </div><div style="position:relative;left:120px;width: 494px;"><?php echo $formation->programme; ?></div>
<div style="position:relative;top:20px;">dur&eacute;e : </div><div style="position:relative;left:120px;width: 494px;"><?php echo $formation->dureeJours; ?> jours</div>
<div style="position:relative;top:20px;"> </div><div style="position:relative;left:120px;width: 494px;"><?php echo $formation->dureeHeures; ?> heures</div>
<div style="position:relative;top:20px;">plus formation : </div><div style="position:relative;left:120px;width: 494px;"><?php echo $formation->plus; ?></div>
<div style="position:relative;top:20px;">tarif inter : </div><div style="position:relative;left:120px;width: 494px;"><?php echo $formation->tarifInter; ?> Euros H.T</div>
<div style="position:relative;top:20px;">tarif CP : </div><div style="position:relative;left:120px;width: 494px;"><?php echo $formation->tarifCp; ?> Euros H.T</div>


<script type="text/javascript" src="js/show_formation.js"></script>
<div class="preview" style="position:absolute;left:655px;top:115px;text-align: center;width: 200px;">
		<img id="thumb"  src="media/formation_img/<?php echo $image; ?>" style="max-width: 200px;min-width: 100px;max-height: 175px;min-height: 100px;"/>	
	</div>

<?php 
foreach ($famillesCriteres as $idFamille=>$familleCriteres) {
	
	echo '<div><div style="position:relative;top:20px">'.$familleCriteres['nom'].' : </div>';
	echo '<div style="position:relative;left:120px;width:750px">';
	foreach ($familleCriteres['criteres'] as $id=>$nom) {
		if (isset($selectedCriteres) AND in_array($id,$selectedCriteres)) {
			echo $nom.'<br/>';
		}
	}
	echo '&nbsp;</div></div>';
	
	
}

?>
<br/><br/>
<a href="create_formation.php?id=<?php echo $formation->id; ?>">modifier</a> 
&nbsp;&nbsp;
<?php
if ($formation->id != 0) {
	echo '<script language="javascript">var id = '.$formation->id.';</script>';
	echo '<a class="delText"  href="#">supprimer</a>';
}
?>

<div id="dialog-confirm" title="Supprimer la formation?"></div>


</html>


<?php
