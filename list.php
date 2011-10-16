<?php
require 'conf/config.php';

require 'lib/mypdo.php';
require 'lib/formation.class.php';
require 'lib/catalogue.class.php';
require 'inc/forms.inc.php';




$catalogue = new catalogue();

$listeAlpha = $catalogue->listAll();

include 'menu.php';
?>


<H1>liste des formations</H1>


<br/>
<?php 
foreach ($listeAlpha as $formation) {
	echo "<a href='create_formation.php?id=".$formation['id_formation']."'>".$formation['nom_formation'].'</a><br>';
}
?>


</html>
