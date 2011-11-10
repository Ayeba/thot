<?php 
require 'conf/config.php';
require 'lib/selection.class.php';
require 'lib/mypdo.php';
require 'lib/user.class.php';
require 'lib/cataloguePDF.class.php';
require 'lib/formation.class.php';
require 'inc/forms.inc.php';


$db = new mypdo();
cataloguePdf::$db = $db;
formation::$db = $db;
user::$db = $db;


$user = new user();
if (!$user->checkStatus()) {
	header ('Location: login.php');
	die();
}

$id = 0;
if (isset($_GET['id']))
	$id = (int)$_GET['id'];

if ($id == 0) {
	header('Location: list_catalogues_pdf.php');
	die();
}

$catalogue = new cataloguePdf($id);
$selection = $catalogue->getSelection();
$couverture = $catalogue->getCouverture12();
$modeleSommaire = $catalogue->getModeleSommaire();
$modeleFormation = $catalogue->getModeleFormation();
$elementsBefore = $catalogue->getElementsBefore();
$elementsAfter = $catalogue->getElementsAfter();
$couverture34 = $catalogue->getCouverture34();



include 'menu.php';
?>



<h1>Afficher un catalogue PDF</h1>
<script type="text/javascript" src="js/show_catalogue_pdf.js"></script>

<div style="position:relative;top:20px;width:150px">nom : </div><div style="position:relative;left:150px;width: 494px;">&nbsp;<?php echo $catalogue->nomCatalogue; ?></div>
<div style="position:relative;top:20px;width:150px">date : </div><div style="position:relative;left:150px;width: 494px;">&nbsp;<?php echo date('d/m/Y H:i', strtotime($catalogue->dateMod)); ?></div>

<div style="position:relative;top:20px;width:150px">commentaire : </div><div style="position:relative;left:150px;width: 494px;">&nbsp;<?php echo $catalogue->commentaire; ?></div>

<div style="position:relative;top:20px;width:150px">couverture : </div><div style="position:relative;left:150px;width: 494px;">&nbsp;<?php echo '<a class="pdfLink" id="'.$couverture['fichier_element'].'" href="#">'.$couverture['nom_element'].'</a>'; ?></div>
<div style="position:relative;top:20px;width:150px">modele de sommaire : </div><div style="position:relative;left:150px;width: 494px;">&nbsp;<?php echo '<a class="pdfLink" id="'.$modeleSommaire['fichier_element'].'" href="#">'.$modeleSommaire['nom_element'].'</a>'; ?></div>
<div style="position:relative;top:20px;width:150px">elements before : </div><div style="position:relative;left:150px;width: 494px;">&nbsp;
<?php 
foreach ($elementsBefore as $element) {
	echo '<a class="pdfLink" id="'.$element['fichier_element'].'" href="#">'.$element['nom_element'].'</a><br />&nbsp;';
}

?></div>
<div style="position:relative;top:20px;width:150px">modele de formation : </div><div style="position:relative;left:150px;width: 494px;">&nbsp;<?php echo '<a class="pdfLink" id="'.$modeleFormation['fichier_element'].'" href="#">'.$modeleFormation['nom_element'].'</a>'; ?></div>

<div style="position:relative;top:20px;width:150px">selection : </div><div style="position:relative;left:150px;width: 494px;">&nbsp;
<?php 
echo $selection->nom;
echo '<br />&nbsp;';
foreach ($selection->formations as $formation) {
	echo '> <a class="pdfLink" id="generate_formation_pdf.php?id='.$formation['id_formation'].'&idModele='.$modeleFormation['id_element'].'" href="#">'.$formation['nom_formation'].'</a><br />&nbsp;';
}

?></div>
<div style="position:relative;top:20px;width:150px">elements after : </div><div style="position:relative;left:150px;width: 494px;">&nbsp;
<?php 
foreach ($elementsAfter as $element) {
	echo '<a class="pdfLink" id="'.$element['fichier_element'].'" href="#">'.$element['nom_element'].'</a><br />&nbsp;';
}

?></div>
<div style="position:relative;top:20px;width:150px">fin couverture : </div><div style="position:relative;left:150px;width: 494px;">&nbsp;<?php echo '<a class="pdfLink" id="'.$couverture34['fichier_element'].'" href="#">'.$couverture34['nom_element'].'</a>'; ?></div>

<br/><br/>
<a href="#">modifier</a> 
<a class="pdfLink" id="generate_catalogue_pdf.php?id=<?php echo $id; ?>" href="#">vusaliser</a> 
<a target="_blank" href="generate_catalogue_pdf.php?id=<?php echo $id; ?>">t&eacute;l&eacute;charger</a> 
<iframe id="pdfFrame" type="application/pdf" style="position: absolute; top: 100px; left: 500px; border: 0pt none;" src="generate_catalogue_pdf.php?id=<?php echo $id; ?>" width="300px" height="500px">
  <p>Your browser does not support iframes.</p>
</iframe>

</html>

