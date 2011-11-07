<?php
require 'conf/config.php';

require 'lib/user.class.php';
require 'lib/mypdo.php';
require 'lib/selection.class.php';
require 'inc/forms.inc.php';

$user = new user();
if (!$user->checkStatus()) {
	header ('Location: login.php');
	die();
}

$db = new mypdo();

selection::$db = $db;

if (isset($_GET['del']) AND isset($_GET['id']) AND $_GET['id'] != 0) {
	$selection = new selection($_GET['id']);
	$selection->delete();
	header('Location: list_selections.php');
	die();
}


if (isset ($_POST['enregistrer']) AND $_POST['nom'] != '') {
	$selection = new selection();
	$selection->nom = $_POST['nom'];
	$selection->commentaire = $_POST['commentaire'];
	$selection->save();
}


$listSelections = array();
$listSelections = selection::getSelections();

include 'menu.php';

?>

<H1>Liste des selections</H1>
<script type="text/javascript" src="js/list_selections.js"></script>
<?php 

foreach ($listSelections as $selection) {
	echo '<a href="show_selection.php?id='.$selection['id_selection'].'">'.$selection['nom_selection']."</a>";
	echo ' <a class="delText" id="'.$selection['id_selection'].'" href="#">X</a><br />';
	echo $selection['num'].' fomations<br />';
	$affDate = date('d/m/Y H:i', strtotime($selection['datemod']));
	echo $affDate.' : '.$selection['commentaire'];
	echo '<br /><br />';
}
?>
<br /><br />
Cr&eacute;er une nouvelle selection :<br/>
<form method="post">
<div style="position:relative;top:20px;width:100px"">nom :</div>
<div style="position:relative;left:120px;width: 494px;"><input type="text" name="nom"></div>
<div style="position:relative;top:20px;width:100px"">commentaire :</div>
<div style="position:relative;left:120px;width: 494px;"><textarea name="commentaire"></textarea></div>
<input type="submit" name="enregistrer" value="enregistrer">
</form>
<div id="dialog-confirm" title="Supprimer la selection?"></div>