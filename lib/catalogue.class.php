<?php

class catalogue {
	
	static $db = NULL;
	static private $familles;
	static private $famillesCriteres;
	
	public function __construct() {
		if (self::$db == NULL) {
			$db = new mypdo();
			self::$db = $db;
		}
	}
	
	public function listAll ($order = 'alpha') {
		switch ($order) {
			case 'id' :
				$orderBy = 'ORDER BY id_formation';
				break;
			case 'aplha' :
			default :
				$orderBy = 'ORDER BY nom_formation';	
		}
		$query = "SELECT nom_formation, id_formation FROM formations ".$orderBy;
		$result = self::$db->query($query);
		$lignes = $result->fetchall();
		return $lignes;
	}

	public function listByCritere ($critere, $order = 'alpha') {
		switch ($order) {
			case 'id' :
				$orderBy = 'ORDER BY id_formation';
				break;
			case 'aplha' :
			default :
				$orderBy = 'ORDER BY nom_formation';	
		}
		$critere = (int)$critere;
		$query = "SELECT nom_formation, id_formation FROM formations, is_critere WHERE id_formation = formation_id AND critere_id = ".$critere." ".$orderBy;
		$result = self::$db->query($query);
		$lignes = $result->fetchall();
		return $lignes;
	}
	
	
	/**
* méthode statique qui renvoie la liste des familles de critères existant en base
*
* @return array un tableau où chaque cellule 'id_famille' contient le nom de la famille
*/	
	
	static public function getFamilles() {
		if (self::$familles == NULL) {
			$query = "SELECT id_famille,nom_famille FROM famille_critere";
			$result = self::$db->query($query);
			while ($ligne = $result->fetch(PDO::FETCH_ASSOC))
				self::$familles[$ligne['id_famille']] = $ligne['nom_famille'];
		}
		return self::$familles;
	}

	
	
	
	static public function getCritereById ($id) {
		$id = (int)$id;
		$query = "SELECT nom_critere FROM critere WHERE id_critere = ".$id;
		$result = self::$db->query($query);
		$nom = '';
		while ($ligne = $result->fetch(PDO::FETCH_ASSOC))
				$nom = $ligne['nom_critere'];
		return $nom;
	}
	
	
/**
* méthode statique qui renvoie la correspondance entre familles et critères
* sous la forme d'un tableau 3D
* tab[id_famille][nom] = nom de la famille
* tab[id_famille][criteres][id_critere] = nom du critere
*
* @return array un tableau à 3 dimensions
*/		
	
	static public function getAllFamillesCriteres() {
		if (self::$famillesCriteres == NULL) {
			$query = "SELECT nom_critere, nom_famille, id_critere, id_famille FROM famille_critere, critere, belongs_to WHERE id_famille = famille_id AND id_critere = critere_id ORDER BY nom_famille,nom_critere";
			$result = self::$db->query($query);
			while ($ligne = $result->fetch(PDO::FETCH_ASSOC)) {
					self::$famillesCriteres[$ligne['id_famille']]['nom'] = $ligne['nom_famille'];
					self::$famillesCriteres[$ligne['id_famille']]['criteres'][$ligne['id_critere']] = $ligne['nom_critere'];
			}
		}
		return self::$famillesCriteres;
	}
}