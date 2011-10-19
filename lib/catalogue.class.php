<?php

/** 
 * Fichier contenant la classe catalogue
 * 
 * 
 * @package lib 
 * @author Romain BOURDON <romain@ayeba.fr> 
 */



/** 
 * La classe catalogue repr�sente le catalogue de formations Ayeba
 * Elle permet de manipuler les familles, crit�res et de lister des formations
 * @package lib 
 * @author Romain BOURDON <romain@ayeba.fr> 
 */


class catalogue {
	
	static $db = NULL;
	static private $familles;
	static private $famillesCriteres;
	

/**
* constructeur
*
*/
	
	public function __construct() {
		if (self::$db == NULL) {
			$db = new mypdo();
			self::$db = $db;
		}
	}
	

/**
* renvoie toutes les formations du catalogue tri�es
*
* @param string $order le tri � effectuer sur les formations
* @return array un tableau 2D contenant les noms et le ids des formations
*/	
	
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

	
/**
* renvoie toutes les formations ayant un crit�re actif tri�es
*
* @param int $critere le crit�re pour lequel les formations doivent �tre activess
* @param string $order le tri � effectuer sur les formations
* @return array un tableau 2D contenant les noms et le ids des formations
*/	
	
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
		$query = "SELECT nom_formation, id_formation FROM formations, has_critere WHERE id_formation = formation_id AND critere_id = ".$critere." ".$orderBy;
		$result = self::$db->query($query);
		$lignes = $result->fetchall();
		return $lignes;
	}
	
	
/**
* m�thode statique qui renvoie la liste des familles de crit�res existant en base
*
* @return array un tableau o� chaque cellule 'id_famille' contient le nom de la famille
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

	
/**
* m�thode statique qui renvoie le nom d'un crit�re en fonction de son id
*
* @param int $id l'id � rechercher
* @return array un tableau o� chaque cellule 'id_famille' contient le nom de la famille
*/	
	
	
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
* m�thode statique qui renvoie la correspondance entre familles et crit�res
* sous la forme d'un tableau 3D
* tab[id_famille][nom] = nom de la famille
* tab[id_famille][criteres][id_critere] = nom du critere
*
* @return array un tableau � 3 dimensions
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