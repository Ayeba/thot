<?php

require_once '../conf/config.php';
require_once '../lib/formation.class.php';
require_once '../lib/mypdo.php';
ob_start();
class formationTest extends PHPUnit_Framework_TestCase {
    private $_formation;
    
    protected function setUp() {
    //	$db = new mypdo();
    	$this->_formation = new formation();
    }

    public function testDefault() {
    	
    }
    
}