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

if (isset($_GET['pub']) AND $_GET['pub'] == 1) {
	$formation->setStatus(2);
	header('Location: show_formation.php?id='.$formation->id);
	die();
}


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

<div style="position:relative;top:20px;width:100px">nom : </div><div style="position:relative;left:120px;width: 494px;"><?php echo $formation->nom; ?></div>
<div style="position:relative;top:20px;width:100px">code : </div><div style="position:relative;left:120px;width: 494px;"><?php echo $formation->code; ?></div>
<div style="position:relative;top:20px;width:100px">sous-titre : </div><div style="position:relative;left:120px;width: 494px;"><?php echo $formation->sousTitre; ?></div>
<div style="position:relative;top:20px;width:100px">description : </div><div style="position:relative;left:120px;width: 494px;"><?php echo $formation->description; ?></div>
<div style="position:relative;top:20px;width:100px">objectifs : </div><div style="position:relative;left:120px;width: 494px;"><?php echo $formation->objectifs; ?></div>
<div style="position:relative;top:20px;width:100px">pre-requis : </div><div style="position:relative;left:120px;width: 494px;"><?php echo $formation->preRequis; ?></div>
<div style="position:relative;top:20px;width:100px">programme : </div><div style="position:relative;left:120px;width: 494px;"><?php echo $formation->programme; ?></div>
<div style="position:relative;top:20px;width:100px">dur&eacute;e : </div><div style="position:relative;left:120px;width: 494px;"><?php echo $formation->dureeJours; ?> jours</div>
<div style="position:relative;top:20px;width:100px"> </div><div style="position:relative;left:120px;width: 494px;"><?php echo $formation->dureeHeures; ?> heures</div>
<div style="position:relative;top:20px;width:100px">plus formation : </div><div style="position:relative;left:120px;width: 494px;"><?php echo $formation->plus; ?></div>
<div style="position:relative;top:20px;width:100px">tarif inter : </div><div style="position:relative;left:120px;width: 494px;"><?php echo $formation->tarifInter; ?> Euros H.T</div>
<div style="position:relative;top:20px;width:100px">tarif CP : </div><div style="position:relative;left:120px;width: 494px;"><?php echo $formation->tarifCp; ?> Euros H.T</div>


<script type="text/javascript" src="js/show_formation.js"></script>
<div class="preview" style="position:absolute;left:655px;top:115px;text-align: center;width: 200px;">
		<img id="thumb"  src="media/formation_img/<?php echo $image; ?>" style="max-width: 200px;min-width: 100px;max-height: 175px;min-height: 100px;"/>	
	</div>
<div  style="position:absolute;left:655px;top:300px;width: 200px;">
<H3>Lieux et dates</H3>
<?php 
$dates = $formation->getDates();
$ville_id = ''; 
setlocale(LC_TIME, "fr_FR");
$premiereLigne = '';
foreach ($dates as $date) {
	if ($ville_id != $date['ville_id']) {
		$ville_id = $date['ville_id'];
		echo $premiereLigne.$date['nom_ville'].'<br/>';
		$premiereLigne = '<br />';
	}
	$timeStamp = strtotime($date['date']);
	echo strftime("%a %d/%m/%Y",$timeStamp).'<br/>';
}

if ($formation->id != 0) {
	echo '<br /><a href="gestion_dates.php?id='.$formation->id.'">g&eacute;rer les dates</a>';
}

?>
<br /><br />
<div>
<H3>Formations en relation</H3>
<?php 
$relations = $formation->getRelations();
foreach($relations as $relation) {
	echo '-> <a href="show_formation.php?id='.$relation['id_formation'].'">'.$relation['nom_formation'].'</a><br/>';
}

if ($formation->id != 0) {
	echo '<br /><a href="gestion_relations.php?id='.$formation->id.'">g&eacute;rer les relations</a>';
}
?>

</div>
<br /><br />
<div>
<H3>Aller plus loin</H3>
<?php 
$plusLoins = $formation->getPlusLoins();
foreach($plusLoins as $plusLoin) {
	echo '-> <a href="show_formation.php?id='.$plusLoin['id_formation'].'">'.$plusLoin['nom_formation'].'</a><br/>';
}

if ($formation->id != 0) {
	echo '<br /><a href="gestion_plusloin.php?id='.$formation->id.'">g&eacute;rer les "aller plus loin"</a>';
}
?>

</div>


</div>	
	

<?php 
foreach ($famillesCriteres as $idFamille=>$familleCriteres) {
	
	echo '<div><div style="position:relative;top:20px;width:100px">'.$familleCriteres['nom'].' : </div>';
	echo '<div style="position:relative;left:120px;width:494px">';
	foreach ($familleCriteres['criteres'] as $id=>$nom) {
		if (isset($selectedCriteres) AND in_array($id,$selectedCriteres)) {
			echo $nom.'<br/>';
		}
	}
	echo '&nbsp;</div></div>';
	
	
}

?>
<div style="position:relative;top:20px;width:100px">Status :</div>
<div style="position:relative;left:120px;width:494px">
<?php 
echo $formationStatus[$formation->status];

if ($formation->status == 1) {
	echo '<br /> <a class="validate" href="#">valider la relecture</a>';
}

?>
</div>
<br/><br/>
<a href="create_formation.php?id=<?php echo $formation->id; ?>">modifier</a> 
&nbsp;&nbsp;
<?php
if ($formation->id != 0) {
	echo '<script language="javascript">var id = '.$formation->id.';</script>';
	echo '<a class="delText"  href="#">supprimer</a>';
}
?>

<div id="dialog-delete" title="Supprimer la formation?"></div>
<div id="dialog-validate" title="valider la formation?"></div>

</html>


<?php
