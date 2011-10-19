<?php

require 'conf/config.php';
require 'lib/mypdo.php';
require 'lib/user.class.php';

$error = '';
$db = new myPDO();

session_start();
if (isset($_SESSION['user']) AND $_SESSION['user']->checkStatus()) {
	header('Location: list.php');
	die();
}

if (isset($_POST['envoyer'])) {
	$user = new user($_POST['login'],$_POST['pass']);
	if ($user->checkStatus()) {
		header('Location: list.php');
		die();
	}
	else {
	$error = 'mauvais login ou mot de passe';
	}
	
}



?>


<html>
<?php echo $error; ?>
<form method="post">
login : <input type="text" name="login">
<br />
pass : <input type="password" name="pass">
<br />
<input type="submit" name="envoyer" value="envoyer">

</form>
</html>