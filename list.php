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


$catalogue = new catalogue();

if (isset($_SESSION['status'])) {
	$StatusOrder = $_SESSION['status'];
}


if (isset($_GET['status'])) {
	if ($_GET['status'] >= 0) {
		$StatusOrder = 	$_GET['status'];
	}
	else {
		unset($StatusOrder);
		unset ($_SESSION['status']);
	}
}

if (isset($StatusOrder)) {
	$listeAlpha = $catalogue->listByStatus($StatusOrder);
	$_SESSION['status'] = $StatusOrder;
}
else {
	$listeAlpha = $catalogue->listAll();
}
include 'menu.php';
?>


<H1>liste des formations</H1>
<script type="text/javascript" src="js/list.js"></script>
<?php 
$list = $formationStatus;
$list[-1] = 'toutes';
if (isset($StatusOrder)) {
	$selected = (int)$StatusOrder;
}
else {
	$selected = -1;
}
echo genSelect($list,'status',$selected);
?>


<br/>
<?php 
foreach ($listeAlpha as $formation) {
	echo "<a href='show_formation.php?id=".$formation['id_formation']."'>".$formation['nom_formation'].'</a><br>';
}
?>


</html>
