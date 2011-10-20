<?php

require_once '../conf/config.php';
require_once '../lib/catalogue.class.php';
require_once '../lib/mypdo.php';

class catalogueTest extends PHPUnit_Framework_TestCase
{
    private $_catalogue;


    protected function setUp() {
    //	$db = new mypdo();
    	$this->_catalogue = new catalogue();
    }


    public function testListAll() {
        $tab = $this->_catalogue->listAll();
        $this->assertGreaterThan(2,count($tab));
    }
    
    public function testListAllOrdreDefault() {
        $tab = $this->_catalogue->listAll();
        foreach ($tab as $cell) {
        $tabNom[] = $cell['nom_formation'];	
        } 
        $tab2Nom = $tabNom;
        sort($tab2Nom);
        $this->assertEquals($tabNom,$tab2Nom);
    }
    
    public function testListAllOrdreAlpha() {
        $tab = $this->_catalogue->listAll('alpha');
        foreach ($tab as $cell) {
        $tabNom[] = $cell['nom_formation'];	
        } 
        $tab2Nom = $tabNom;
        sort($tab2Nom);
        $this->assertEquals($tabNom,$tab2Nom);
    }
    
    public function testListAllOrdreId() {
        $tab = $this->_catalogue->listAll('id');
        foreach ($tab as $cell) {
        $tabNom[] = $cell['id_formation'];	
        } 
        $tab2Nom = $tabNom;
        sort($tab2Nom);
        $this->assertEquals($tabNom,$tab2Nom);
    }
    
    public function testListAllOrdreWrongParam() {
        $tab = $this->_catalogue->listAll('toto');
        foreach ($tab as $cell) {
        $tabNom[] = $cell['nom_formation'];	
        } 
        $tab2Nom = $tabNom;
        sort($tab2Nom);
        $this->assertEquals($tabNom,$tab2Nom);
    }
    
    
}