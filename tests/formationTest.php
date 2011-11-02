<?php

require_once '../conf/config.php';
require_once '../lib/formation.class.php';
require_once '../lib/mypdo.php';
ob_start();
class formationTest extends PHPUnit_Framework_TestCase {
    private $_formation;
    
    protected function setUp() {
    //	$db = new mypdo();
    //	$this->_formation = new formation();
    }

    public function testDefault() {
    	
    }
    
    public function testAddDate() {
    	$this->_formation = new formation(1);
    	$this->_formation->addDate(1,'2020-10-10');
    	$dates = $this->_formation->getDates();
    	$this->assertEquals (in_array(array('ville_id'=>1,'nom_ville'=>'Bordeaux','date'=>'2020-10-10'),$dates),true);
    }
    
    public function testDelDate() {
    	$this->_formation = new formation(1);
    	$this->_formation->delDate(1,'2020-10-10');
    	$dates = $this->_formation->getDates();
    	$this->assertEquals (in_array(array('ville_id'=>1,'nom_ville'=>'Bordeaux','date'=>'10/10/2020'),$dates),false);
    	
    }
    
}