<?php

require_once '../conf/config.php';
require_once '../lib/user.class.php';
require_once '../lib/mypdo.php';
ob_start();
class userTest extends PHPUnit_Framework_TestCase
{
    private $_user;


    protected function setUp()
    {
  //  	$db = new mypdo();
    }

    
    public function testBonneAuthentification()
    {
        $this->_user = new user('romain','toto');
        $this->assertEquals(1,$this->_user->checkStatus());
        $this->assertEquals('romain',$this->_user->login);
        $this->assertEquals($_SESSION['user'],$this->_user);
    }

    public function testAuthentificationSansMotDePasse()
    {
        $this->_user = new user('romain','');
        $this->assertEquals(0,$this->_user->checkStatus());
        
    }
    
    public function testMauvaiseAuthentification()
    {
        $this->_user = new user('romain','titi');
        $this->assertEquals(0,$this->_user->checkStatus());
    }
    
    public function testUtilisateurEnSession()
    {
    	$this->_user = new user('romain','toto');
    	if (isset($_SESSION['user'])) {
    		$this->_user = NULL;
    		$this->_user = new user();
        	$this->assertEquals(1,$this->_user->checkStatus());
        	$this->assertEquals('romain',$this->_user->login);	
        	$this->assertEquals($_SESSION['user'],$this->_user);
    	}
    }
}