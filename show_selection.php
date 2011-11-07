<?php 
require 'conf/config.php';

require 'lib/mypdo.php';
require 'lib/user.class.php';
require 'lib/selection.class.php';
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

include 'menu.php';
?>



<h1>selection : <?php echo $selection->nom; ?></h1>
derni&egrave;re mise &agrave; jour : <?php echo date('d/m/Y H:i', strtotime($selection->dateMod));?>
<br/>
commentaire : <?php echo $selection->commentaire; ?>
<br /><br />
formations : 
<br/><br />
<?php 
foreach ($selection->formations as $formation) {
	echo '-> <a href="show_formation.php?id='.$formation['id_formation'].'">'.$formation['nom_formation'].'</a><br />';
}


?>

<br /><br />
<a href="mod_selection.php?id=<?php echo $id ; ?>">modifier</a>