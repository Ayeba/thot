<?php
require 'conf/config.php';

require 'lib/user.class.php';
require 'lib/mypdo.php';
require 'lib/selection.class.php';
require 'lib/cataloguepdf.class.php';
require 'lib/catalogue.class.php';
require 'inc/forms.inc.php';

$user = new user();
if (!$user->checkStatus()) {
	header ('Location: login.php');
	die();
}

$db = new mypdo();

selection::$db = $db;
cataloguePdf::$db = $db;

if (isset($_GET['del']) AND isset($_GET['id']) AND $_GET['id'] != 0) {
	$catalogue = new cataloguePdf($_GET['id']);
	$catalogue->delete();
	header('Location: list_catalogues_pdf.php');
	die();
}

if (isset ($_POST['enregistrer']) AND $_POST['nom'] != '') {
	$catalogue = new cataloguePdf();
	$catalogue->nomCatalogue = $_POST['nom'];
	$catalogue->commentaire = $_POST['commentaire'];
	$catalogue->save();
	header('Location: list_catalogues_pdf.php');
	die();
}

$listCataloguesPDF = array();
$listCataloguesPDF = cataloguePdf::getCatalogues();


include 'menu.php';

?>

<H1>Liste des catalogues PDF</H1>
<script type="text/javascript" src="js/list_catalogues_pdf.js"></script>
<?php 

foreach ($listCataloguesPDF as $catalogue) {
	echo '<a href="show_catalogue_pdf.php?id='.$catalogue['id_pdf_catalogue'].'">'.$catalogue['nom_pdf_catalogue']."</a>";
	echo ' <a class="delText" id="'.$catalogue['id_pdf_catalogue'].'" href="#">X</a><br />';
	$affDate = date('d/m/Y H:i', strtotime($catalogue['datemod']));
	echo $affDate.' : '.$catalogue['commentaire'];
	echo '<br /><br />';
}
?>
<br /><br />
Cr&eacute;er un nouveau catalogue :<br/>
<form method="post">
<div style="position:relative;top:20px;width:100px"">nom :</div>
<div style="position:relative;left:120px;width: 494px;"><input type="text" name="nom"></div>
<div style="position:relative;top:20px;width:100px"">commentaire :</div>
<div style="position:relative;left:120px;width: 494px;"><textarea name="commentaire"></textarea></div>
<input type="submit" name="enregistrer" value="enregistrer">
</form>
<div id="dialog-confirm" title="Supprimer le catalogue?"></div>