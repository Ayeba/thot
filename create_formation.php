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
	$values['sousTitre']= $_POST['sousTitre'];
	$values['description'] = $_POST['description']; 
	$values['objectifs'] = $_POST['objectifs'];
	$values['preRequis'] = $_POST['preRequis'];
	$values['code'] = $_POST['code'];
	$values['programme'] = $_POST['programme'];
	$values['dureeJours'] = $_POST['dureeJours'];
	$values['dureeHeures'] = $_POST['dureeHeures'];
	$values['plus'] = $_POST['plus'];
	$values['tarifInter'] = $_POST['tarifInter'];
	$values['tarifCp'] = $_POST['tarifCp'];
	$values['image'] = $_POST['image'];
	$values['criteres'] = array();
	if (isset($_POST['criteres'])) {
	foreach ($_POST['criteres'] as $famille){
		foreach($famille as $critere) {
			$values['criteres'][] = $critere;
		}
	}
	}	
	$formation->save($values);	
	echo "modifi&eacute;";	
}

catalogue::$db = $db;
$famillesCriteres = catalogue::getAllFamillesCriteres();
$selectedCriteres = $formation->getCriteres();

if ($formation->image == '') {
	$image = 'default.png';
	$displayDelImage = ';display:none';
}
else {
	$image = $formation->image;
	$displayDelImage = '';
}

include 'menu.php';
?>



<h1>Cr&eacute;er / modifier une formation</h1>

<?php 
if ($formation->id != 0) {
	echo '<a href="gestion_dates.php?id='.$formation->id.'">g&eacute;rer les dates</a>';
}

?>


<form method="POST" action="create_formation.php?id=<?php echo $formation->id; ?>">
<div style="position:relative;top:20px;">nom : </div><div style="position:relative;left:120px;"><input type="text" name="nom" size="70" value="<?php echo $formation->nom; ?>"/></div>
<div style="position:relative;top:20px;">code : </div><div style="position:relative;left:120px;"><input type="text" name="code" size="5" maxlength="5" value="<?php echo $formation->code; ?>"/></div>
<div style="position:relative;top:20px;">sous-titre : </div><div style="position:relative;left:120px;"><textarea name="sousTitre" style="width: 494px; height: 54px;"><?php echo $formation->sousTitre; ?></textarea></div>
<div style="position:relative;top:20px;">description : </div><div style="position:relative;left:120px;"><textarea name="description" style="width: 494px; height: 100px;"><?php echo $formation->description; ?></textarea></div>
<div style="position:relative;top:20px;">objectifs : </div><div style="position:relative;left:120px;"><textarea name="objectifs" style="width: 494px; height: 100px;"><?php echo $formation->objectifs; ?></textarea></div>
<div style="position:relative;top:20px;">pre-requis : </div><div style="position:relative;left:120px;"><textarea name="preRequis" style="width: 494px; height: 100px;"><?php echo $formation->preRequis; ?></textarea></div>
<div style="position:relative;top:20px;">programme : </div><div style="position:relative;left:120px;"><textarea name="programme" style="width: 494px; height: 400px;"><?php echo $formation->programme; ?></textarea></div>
<div style="position:relative;top:20px;">dur&eacute;e : </div><div style="position:relative;left:120px;"><input type="text" name="dureeJours" size="5" maxlength="5" value="<?php echo $formation->dureeJours; ?>"/> jours</div>
<div style="position:relative;top:20px;"> </div><div style="position:relative;left:120px;"><input type="text" name="dureeHeures" size="5" maxlength="5" value="<?php echo $formation->dureeHeures; ?>"/> heures</div>
<div style="position:relative;top:20px;">plus formation : </div><div style="position:relative;left:120px;"><textarea name="plus" style="width: 494px; height: 100px;"><?php echo $formation->plus; ?></textarea></div>
<div style="position:relative;top:20px;">tarif inter : </div><div style="position:relative;left:120px;"><input type="text" name="tarifInter" size="10" maxlength="10" value="<?php echo $formation->tarifInter; ?>"/> Euros H.T</div>
<div style="position:relative;top:20px;">tarif CP : </div><div style="position:relative;left:120px;"><input type="text" name="tarifCp" size="10" maxlength="10" value="<?php echo $formation->tarifCp; ?>"/> Euros H.T</div>
<input type="hidden" name="image" value="<?php echo $formation->image; ?>">

<script type="text/javascript" src="js/formation.js"></script>
<div class="preview" style="position:absolute;left:655px;top:115px;text-align: center;width: 200px;">
		<img id="thumb"  src="media/formation_img/<?php echo $image; ?>" style="max-width: 200px;min-width: 100px;max-height: 175px;min-height: 100px;"/>	
	<div id="deletePicture" style="font-size:10px<?php echo $displayDelImage; ?>"><a id='deletePicture' href="#">Supprimer</a></div>
	<div></div>
	<div id="file-uploader" style="position:absolute;left:45px;">       
    <noscript>          
        <p>Please enable JavaScript to use file uploader.</p>
        <!-- or put a simple form for upload here -->
    </noscript>         
</div>
	</div>

<?php 
foreach ($famillesCriteres as $idFamille=>$familleCriteres) {
	
	echo '<div><div style="position:relative;top:20px">'.$familleCriteres['nom'].' : </div>';
	echo '<div style="position:relative;left:120px;width:750px">';
	echo genMultCheckBox($familleCriteres['criteres'],"criteres[".$idFamille."][]",$selectedCriteres);
	echo '</div></div>';
	
	
}

?>


<input type="submit" name="enregistrer" value="enregistrer"/>

<?php
if ($formation->id != 0) {
	echo '<script language="javascript">var id = '.$formation->id.';</script>';
	echo '<a class="delText"  href="#">supprimer</a>';
}
?>

</form>
<div id="dialog-confirm" title="Supprimer la formation?"></div>


</html>


<?php
