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
    
    public function testDroitsUtilisateur()
    {
    	$this->user = new user();
    	$this->user->setDroits(0);
    	$this->assertEquals(0,$this->user->isAdmin());
    	$this->assertEquals(0,$this->user->isWebmaster());
    	$this->assertEquals(0,$this->user->isCommercial());
		$this->assertEquals(0,$this->user->roles['admin']);
		$this->assertEquals(0,$this->user->roles['webmaster']);
		$this->assertEquals(0,$this->user->roles['commercial']);
		$this->user->setDroits(1);
    	$this->assertEquals(1,$this->user->isAdmin());
    	$this->assertEquals(0,$this->user->isWebmaster());
    	$this->assertEquals(0,$this->user->isCommercial());
		$this->assertEquals(1,$this->user->roles['admin']);
		$this->assertEquals(0,$this->user->roles['webmaster']);
		$this->assertEquals(0,$this->user->roles['commercial']);
    	$this->user->setDroits(10);
    	$this->assertEquals(0,$this->user->isAdmin());
    	$this->assertEquals(1,$this->user->isWebmaster());
    	$this->assertEquals(0,$this->user->isCommercial());
		$this->assertEquals(0,$this->user->roles['admin']);
		$this->assertEquals(1,$this->user->roles['webmaster']);
		$this->assertEquals(0,$this->user->roles['commercial']);
    	$this->user->setDroits(11);
    	$this->assertEquals(1,$this->user->isAdmin());
    	$this->assertEquals(1,$this->user->isWebmaster());
    	$this->assertEquals(0,$this->user->isCommercial());
		$this->assertEquals(1,$this->user->roles['admin']);
		$this->assertEquals(1,$this->user->roles['webmaster']);
		$this->assertEquals(0,$this->user->roles['commercial']);
    	$this->user->setDroits(100);
    	$this->assertEquals(0,$this->user->isAdmin());
    	$this->assertEquals(0,$this->user->isWebmaster());
    	$this->assertEquals(1,$this->user->isCommercial());
		$this->assertEquals(0,$this->user->roles['admin']);
		$this->assertEquals(0,$this->user->roles['webmaster']);
		$this->assertEquals(1,$this->user->roles['commercial']);
    	$this->user->setDroits(101);
    	$this->assertEquals(1,$this->user->isAdmin());
    	$this->assertEquals(0,$this->user->isWebmaster());
    	$this->assertEquals(1,$this->user->isCommercial());
    	$this->assertEquals(1,$this->user->roles['admin']);
		$this->assertEquals(0,$this->user->roles['webmaster']);
		$this->assertEquals(1,$this->user->roles['commercial']);
    	$this->user->setDroits(110);
    	$this->assertEquals(0,$this->user->isAdmin());
    	$this->assertEquals(1,$this->user->isWebmaster());
    	$this->assertEquals(1,$this->user->isCommercial());
    	$this->assertEquals(0,$this->user->roles['admin']);
		$this->assertEquals(1,$this->user->roles['webmaster']);
		$this->assertEquals(1,$this->user->roles['commercial']);
    	$this->user->setDroits(111);
    	$this->assertEquals(1,$this->user->isAdmin());
    	$this->assertEquals(1,$this->user->isWebmaster());
    	$this->assertEquals(1,$this->user->isCommercial());
    	$this->assertEquals(1,$this->user->roles['admin']);
		$this->assertEquals(1,$this->user->roles['webmaster']);
		$this->assertEquals(1,$this->user->roles['commercial']);  	
    	
    }
    
    
}