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
    
    public function testGetRelations() {
    	
    }
    
    public function testAddRelation() {
    	$this->_formation = new formation(1);
    	$this->_formation->addRelation(2);
    	$relations = $this->_formation->getRelations();
    	$test = 0;
    	foreach ($relations as $relation) {
    		if ($relation['id_formation'] == 2)
    			$test = 1;
    	}
    	$this->assertEquals ($test, 1);    	
    }
    
    public function testDelRelation() {
        $this->_formation = new formation(1);
    	$this->_formation->delRelation(2);
    	$relations = $this->_formation->getRelations();
    	$test = 0;
    	foreach ($relations as $relation) {
    		if ($relation['id_formation'] == 2)
    			$test = 1;
    	}
    	$this->assertEquals ($test, 0);    	
    }
    
    public function testRelationEntreLaMemeFormation() {
    	$this->_formation = new formation(1);
    	$this->_formation->addRelation(1);
    	$relations = $this->_formation->getRelations();
    	$test = 0;
    	foreach ($relations as $relation) {
    		if ($relation['id_formation'] == 1)
    			$test = 1;
    	}
       	$this->_formation->delRelation(1);
    	$this->assertEquals ($test, 0);    	
    }

    public function testGetPlusLoin() {
    		
    }
    
    public function testAddPlusLoin() {
    	$this->_formation = new formation(1);
    	$this->_formation->addPlusLoin(2);
    	$plusLoins = $this->_formation->getPlusLoins();
    	$test = 0;
    	foreach ($plusLoins as $plusLoin) {
    		if ($plusLoin['id_formation'] == 2)
    			$test = 1;
    	}
    	$this->assertEquals ($test, 1);    	
    }
    
    public function testDelPlusLoin() {
        $this->_formation = new formation(1);
    	$this->_formation->delPlusLoin(2);
    	$plusLoins = $this->_formation->getPlusLoins();
    	$test = 0;
    	foreach ($plusLoins as $plusLoin) {
    		if ($plusLoin['id_formation'] == 2)
    			$test = 1;
    	}
    	$this->assertEquals ($test, 0);    	
    }
    
    public function testPlusLoinEntreLaMemeFormation() {
    	$this->_formation = new formation(1);
    	$this->_formation->addPlusLoin(1);
    	$plusLoins = $this->_formation->getPlusLoins();
    	$test = 0;
    	foreach ($plusLoins as $plusLoin) {
    		if ($plusLoin['id_formation'] == 1)
    			$test = 1;
    	}
       	$this->_formation->delPlusLoin(1);
    	$this->assertEquals ($test, 0);    	
    }
    
}