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
	static private $villes;
	

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
		$lignes = $result->fetchall(PDO::FETCH_ASSOC);
		return $lignes;
	}

	
/**
* renvoie toutes les formations du catalogue tri�es sauf celle pr�cis�e
*
* @param int $id l'id de la formation � ne pas renvoyer
* @param string $order le tri � effectuer sur les formations
* @return array un tableau 2D contenant les noms et le ids des formations
*/	
	
	public function listAllExcept ($id,$order = 'alpha') {
		switch ($order) {
			case 'id' :
				$orderBy = ' ORDER BY id_formation';
				break;
			case 'aplha' :
			default :
				$orderBy = ' ORDER BY nom_formation';	
		}
		$query = "SELECT nom_formation, id_formation FROM formations WHERE id_formation != ".(int)$id.$orderBy;
		$result = self::$db->query($query);
		$lignes = $result->fetchall(PDO::FETCH_ASSOC);
		return $lignes;
	}
	
	
/**
* renvoie toutes les formations du catalogue tri�es et ayant le status sp�cifi� 
*
* @param int $status le status que les formations doivent avoir
* @param string $order le tri � effectuer sur les formations
* @return array un tableau 2D contenant les noms et le ids des formations
*/	
	
	public function listByStatus ($status, $order = 'alpha') {
		switch ($order) {
			case 'id' :
				$orderBy = 'ORDER BY id_formation';
				break;
			case 'aplha' :
			default :
				$orderBy = 'ORDER BY nom_formation';	
		}
		$query = "SELECT nom_formation, id_formation FROM formations WHERE status = ".(int)$status.' '.$orderBy;
		$result = self::$db->query($query);
		$lignes = $result->fetchall(PDO::FETCH_ASSOC);
		return $lignes;
	}
	
	
/**
* renvoie toutes les formations du catalogue tri�es et ayant le status sp�cifi� sauf celle pr�cis�e
*
* @param int $status le status que les formations doivent avoir
* @param int $id l'id de la formation � ne pas renvoyer
* @param string $order le tri � effectuer sur les formations
* @return array un tableau 2D contenant les noms et le ids des formations
*/	
	
	public function listByStatusExcept ($status, $id,$order = 'alpha') {
		switch ($order) {
			case 'id' :
				$orderBy = ' ORDER BY id_formation';
				break;
			case 'aplha' :
			default :
				$orderBy = ' ORDER BY nom_formation';	
		}
		$query = "SELECT nom_formation, id_formation FROM formations WHERE id_formation != ".(int)$id." AND status = ".(int)$status.' '.$orderBy;
		$result = self::$db->query($query);
		$lignes = $result->fetchall(PDO::FETCH_ASSOC);
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
		$query = "SELECT nom_formation, id_formation FROM formations, has_critere WHERE id_formation = formation_id AND status = 2 AND  critere_id = ".$critere." ".$orderBy;
		$result = self::$db->query($query);
		$lignes = $result->fetchall(PDO::FETCH_ASSOC);
		return $lignes;
	}

	
	 public function updateByCritere ($critere, $update) {
	 	$critere = (int)$critere;
	 	$query = "DELETE FROM has_critere WHERE critere_id = ".$critere;
	 	self::$db->query($query);
	 	$query = "INSERT INTO has_critere(critere_id,formation_id) VALUES (".$critere.",:formation_id)";
	 	$formation_id = 0;
	 	$stmt = self::$db->prepare($query);
		$stmt->bindParam(':formation_id', $formation_id);
		foreach ($update as $formation_id) {
			$stmt->execute();
		}
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
	
	
/**
* m�thode statique qui renvoie la liste des villes existantes en base
*
* @return array un tableau o� chaque cellule 'id_ville' contient le nom de la ville
*/	
	
	static public function getVilles() {
		if (self::$villes == NULL) {
			$query = "SELECT id_ville,nom_ville FROM villes ORDER BY nom_ville";
			$result = self::$db->query($query);
			while ($ligne = $result->fetch(PDO::FETCH_ASSOC))
				self::$villes[$ligne['id_ville']] = $ligne['nom_ville'];
		}
		return self::$villes;
	}
	

/**
* m�thode statique qui cr�� une nouvelle ville
*
* @param $nomVille le nom de la nouvelle ville
* @return l'id de la nouvelle ville
*/	
	
	static public function addVille($nomVille) {
		$liste = self::getVilles();
		if (!in_array($nomVille,$liste)) {
			$nomVille = ucfirst($nomVille);
			$query = "INSERT INTO villes(nom_ville) VALUES (:nom)";
			$stmt = self::$db->prepare($query);
			$stmt->bindParam(':nom', $nomVille);
			$stmt->execute();
			self::$villes = NULL;
			return self::$db->lastInsertId();
		}
		return false;			
	}
	
	

/**
* m�thode statique qui cr�� un nouveau crit�re
*
* @param $nomCritere ne nom du nouveau crit�re
* @param $idFamille l'id de la famille auquel il doit appartenir
* @return l'id du novueau crit�re
*/	
	
	static public function addCritere($nomCritere, $idFamille) {
		$familleCriteres = self::getAllFamillesCriteres();
		if (!in_array($nomCritere,$familleCriteres[$idFamille]['criteres']) AND $nomCritere != ''){
			$query = "INSERT INTO critere(nom_critere) VALUES (:nom_critere)";
			$stmt = self::$db->prepare($query);
			$stmt->bindParam(':nom_critere', $nomCritere);
			$stmt->execute();
			self::$famillesCriteres = NULL;
			$newId = self::$db->lastInsertId();
			$query = "INSERT INTO belongs_to(critere_id,famille_id) VALUES (".$newId.",".(int)$idFamille.')';
			self::$db->exec($query);
			return $newId;
		}		
	}	
	
	
	
/**
* m�thode statique qui supprime crit�re
*
* @param $idCritere
*/
	static public function delCritere($idCritere) {
		$query = "DELETE FROM critere WHERE id_critere = ".(int)$idCritere;
		self::$db->exec($query);
	}
	
}