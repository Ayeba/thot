<?php

/** 
 * Fichier contenant la classe user
 * 
 * 
 * @package lib 
 * @author Romain BOURDON <romain@ayeba.fr> 
 */


/** 
 * La classe user permet de grer les authentifications 
 * si l'utilisateur courant est authentifi, elle le stock en session 
 * Son utilisation est simple. 
 * On cr un user en passant un login et un mot de passe
 * pour faire une authentification. 
 * On cr un user sans paramtres pour charger un user djˆ en session.
 * On utilisa le mthode checkStatus() pour vrifier si le user est bien authentifi
 * 
 * 
 * @package lib 
 * @author Romain BOURDON <romain@ayeba.fr> 
 */


class user {
	
	public $login;
	private $logged = 0;
	
	static $db;
	
	
/**
* constructeur de la classe. Elle vrifie d'abord si un user existe en session. 
* Si il existe, il est charg.
* Si des paramtres $login et $pass sont fournis, elle va vrifier si le compte existe
* en base. Si il existe, elle passe $logged ˆ 1 et enregistre le user en session. Sinon, elle passe
* $logged ˆ 0 
* 
*
* @param string $pathToTpl le nom (chemin inclus) du gabarit ˆ charger
*/	
	
	public function __construct($login='', $pass='') {		
		if (self::$db == NULL) {
			$db = new mypdo();
			self::$db = $db;
		}
		if (!isset($_SESSION))
			session_start();
		if (isset($_SESSION['user'])) {
			$this->login = $_SESSION['user']->login;
			$this->logged = $_SESSION['user']->logged;
		}
		if ($login != '' AND $pass != '') {
			$query = "SELECT login FROM users WHERE login = :login AND password = :pass";
			$stmt = self::$db->prepare($query);
			$stmt->bindParam(':login', $login);
			$stmt->bindParam(':pass', $pass);
			$stmt->execute();
			while($ligne = $stmt->fetch()) {
				if (isset($ligne['login'])) {
					$this->logged = 1;
					$_SESSION['user'] = $this;
				}
				else 
					$this->logged = 0;
			}
		}
	}
	

/**
* permet de vrifier si le user courant est authentifi ou non
*
* @return int 1 si authentifi, 0 sinon
* 
*/	
	
	public function checkStatus() {
		return $this->logged;
	}
	
	
}