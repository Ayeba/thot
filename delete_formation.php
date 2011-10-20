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



if (isset($_GET['id'])) {
	$formation = new formation($_GET['id']);
	$formation->delete();
}

header('Location: list.php');
