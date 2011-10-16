<?php

/** 
 * Fichier contenant la classe myPDO et la fonction removeaccents()
 * 
 * 
 * @package lib 
 * @author Romain BOURDON <romain@ayeba.fr> 
 */



/** 
 * La classe myPDO est une surchage de la classe PDO. 
 * La connexion db ne sera établie qu'au moment où une première requête sera effectuée. 
 * La classe passe automatiquement en mode Exception. 
 * 
 * @package lib 
 * @author Romain BOURDON <romain@ayeba.fr> 
 */


class myPDO extends PDO {
	static $db = false;
   	private $sqlUser;
   	private $sqlPass;
   	private $sqlServer;
    private $sqlDb; 
    private $isConnected = false;

    
/**
* constructeur
*
*/
	public function __construct() {
		//pseudo singleton
		if (self::$db != false)
			return self::$db; 
		$this->sqlUser = SQL_USER;
		$this->sqlPass = SQL_PASS;
		$this->sqlServer = SQL_SERVER;
		$this->sqlDb = SQL_DB;
	}
		
	private function _connect() {
		if ($this->isConnected === false) {
			try {
				$dsn = 'mysql:host='.$this->sqlServer.';dbname='.$this->sqlDb;
				parent::__construct($dsn,$this->sqlUser,$this->sqlPass);
			 	$this->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			 	self::$db = $this;
			 	$this->isConnected = true;
			}catch (PDOException $e){
				echo $e->getMessage();
				die();
			}
		}
	}

/**
* surcharge de la méthode query
*
* @param string $sql la requête à effectuer
* @return ressource le résultat de la requête ou une erreur jQuery si elle échoue
*/

	public function query($sql) {
		try {
		$this->_connect();
		return parent::query($sql);
		}catch (PDOException $e){
			echo $e->getMessage();
			die();
		}
	}
	
/**
* surcharge de la méthode exec
*
* @param string $sql la requête à effectuer
* @return ressource le résultat de la requête ou une erreur jQuery si elle échoue
*/
	
	public function exec($sql) {
		try {
		$this->_connect();
		return parent::exec($sql);
		}catch (PDOException $e){
			echo $e->getMessage();
			die();
		}
	}

/**
* surcharge de la méthode prepare
*
* @param string $sql la requête à effectuer
* @return ressource le résultat de la requête ou une erreur jQuery si elle échoue
*/
	
	public function prepare($sql) {
		try {
		$this->_connect();
		return parent::prepare($sql);
		}catch (PDOException $e){
			echo $e->getMessage();
			die();
		}
	}
		
}

/**
* fonction qui retire les accents d'une chaîne
*
* @param string $string la chaîne à nettoyer
* @return string la chaîne netoyée
*/

function removeaccents($string)
{
	return  str_replace( array('à','á','â','ã','ä', 'ç', 'è','é','ê','ë', 'ì','í','î','ï', 'ñ', 'ò','ó','ô','õ','ö', 'ù','ú','û','ü', 'ý','ÿ', 'À','Á','Â','Ã','Ä', 'Ç', 'È','É','Ê','Ë', 'Ì','Í','Î','Ï', 'Ñ', 'Ò','Ó','Ô','Õ','Ö', 'Ù','Ú','Û','Ü', 'Ý'), 
			   	        array('a','a','a','a','a', 'c', 'e','e','e','e', 'i','i','i','i', 'n', 'o','o','o','o','o', 'u','u','u','u', 'y','y', 'A','A','A','A','A', 'C', 'E','E','E','E', 'I','I','I','I', 'N', 'O','O','O','O','O', 'U','U','U','U', 'Y'), $string);
}

