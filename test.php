<pre><?php
ini_set('display_errors','on');
require 'conf/config.php';

require 'lib/mypdo.php';
require 'lib/formation.class.php';
require 'lib/catalogue.class.php';
require 'inc/forms.inc.php';

$id = 1;





$catalogue = new catalogue();

$listeAlpha = $catalogue->listAll('id');

var_dump($listeAlpha);

?></pre>