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
	public $email;
	private $logged = 0;
	private $droits = 0;
	public $roles = array();
	
	static $db;
	
	
/**
* constructeur de la classe. Elle vrifie d'abord si un user existe en session. 
* Si il existe, il est charg.
* Si des paramtres $login et $pass sont fournis, elle va vrifier si le compte existe
* en base. Si il existe, elle passe $logged ˆ 1 et enregistre le user en session. Sinon, elle passe
* $logged ˆ 0 
* 
*
*/	
	
	public function __construct($login='', $pass='') {		
//		if (self::$db == NULL) {
			$db = new mypdo();
			self::$db = $db;
//		}
		@session_start();
		if (isset($_SESSION['user'])) {
			$this->login = $_SESSION['user']->login;
			$this->logged = $_SESSION['user']->logged;
			$this->email = $_SESSION['user']->email;
			$this->droits = $_SESSION['user']->droits;
			$this->roles = $_SESSION['user']->roles;
		}
		if ($login != '' AND $pass != '') {
			$query = "SELECT login,droits,email FROM users WHERE login = :login AND password = MD5(:pass)";
			$stmt = self::$db->prepare($query);
			$stmt->bindParam(':login', $login);
			$stmt->bindParam(':pass', $pass);
			$stmt->execute();
			while($ligne = $stmt->fetch()) {
				if (isset($ligne['login'])) {
					$this->login = $ligne['login'];
					$this->email = $ligne['email'];
					$this->droits = $ligne['droits'];
					$this->logged = 1;
					$this->setRoles();
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
	
/**
* permet de vrifier si le user courant a le r™le admin (1 dans le champ droits)
*
* @return int 1 si admin, 0 sinon
* 
*/	
		
	public function isAdmin() {
		$valeur = $this->droits % 10;
		$result = $valeur / 1;
		if ($result >= 1)
			return 1;
		else
			return 0;
	}

/**
* permet de vrifier si le user courant a le r™le webmaster (10 dans le champ droits)
*
* @return int 1 si webmaster, 0 sinon
* 
*/	
		
	public function isWebmaster() {
		$valeur = $this->droits % 100;
		$result = $valeur / 10;
		if ($result >= 1)
			return 1;
		else
			return 0;
	}
	
	
/**
* permet de vrifier si le user courant a le r™le commercial (100 dans le champ droits)
*
* @return int 1 si commercial, 0 sinon
* 
*/	
		
	public function isCommercial() {
		$valeur = $this->droits % 1000;
		$result = $valeur / 100;
		if ($result >= 1)
			return 1;
		else
			return 0;
	}	
	
	
/**
* permet d'assigner une nouvelle valeur ˆ l'attribut droit
* la valeur est temporaire ˆ la page et n'est pas enregistre en base
* le tableau $roles est galement mis ˆ jour
*
* @param int $value la nouvelle valeur ˆ assigner
* 
*/	
	
	public function setDroits($value) {
		$this->droits = (int)$value;
		$this->setRoles();
	}
	

/**
* met ˆ jour les valeurs du tableau $role
*
* 
*/	
	
	public function setRoles() {
		$this->roles = NULL;
		if ($this->isAdmin())
			$this->roles['admin'] = 1;
		else 
			$this->roles['admin'] = 0;
		if ($this->isWebmaster())
			$this->roles['webmaster'] = 1;
		else 
			$this->roles['webmaster'] = 0;
		if ($this->isCommercial())
			$this->roles['commercial'] = 1;
		else
			$this->roles['commercial'] = 0;
	}
	
	

}