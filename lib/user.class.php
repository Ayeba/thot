<?php


class user {
	
	public $login;
	private $logged = 0;
	
	static $db;
	
	public function __construct($login='', $pass='') {		
		if (self::$db == NULL) {
			$db = new mypdo();
			self::$db = $db;
		}
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
	
	
	
	public function checkStatus() {
		return $this->logged;
	}
	
	
}