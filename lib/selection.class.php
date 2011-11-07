<?php


/** 
 * Fichier contenant la classe selection
 * 
 * 
 * @package lib 
 * @author Romain BOURDON <romain@ayeba.fr> 
 */



/** 
 * La classe formation représente une selection de formations Ayeba.
 * A partir d'une seleciton, il est possible de générer un catalogue spécifique au format pdf
 * @package lib 
 * @author Romain BOURDON <romain@ayeba.fr> 
 */


class selection {
	
	private $id = 0;
	public $nom = '';
	public $commentaire = '';
	public $formations = array();
	public $dateMod = '';
	
	static $db;
	

/**
* constructeur
*
* @param int $id l'id de la selection à charger, 0 si création d'une nouvelle selection
*/
	
	public function __construct($id = 0) {
		
		$this->id = $id;
			
		if (self::$db == NULL) {
			$db = new mypdo();
			self::$db = $db;
		}
		
		
		if ($id != 0) {
			$id = (int)$id;
			$query = 'SELECT nom_selection, commentaire, datemod FROM selections WHERE id_selection = '.$id;
			$result = self::$db->query($query);
			$ligne = $result->fetch(PDO::FETCH_ASSOC);	
			$this->nom = $ligne['nom_selection'];
			$this->commentaire = $ligne['commentaire'];
			$this->dateMod = $ligne['datemod'];
			$this->id = $id;
			$this->getFormations();
		}
	}

/**
* rafraichit le contenu de la selection depuis la bae de données
* et met à jour l'attribut $formations
*
*/
	
	public function getFormations() {
		if ($this->id != 0) {
			$query = "SELECT formation_id as id_formation, nom_formation FROM in_selection,formations WHERE in_selection.selection_id = ".$this->id." AND status = 2 AND formation_id = id_formation ORDER BY nom_formation";
			$result = self::$db->query($query);
			$this->formations = $result->fetchall(PDO::FETCH_ASSOC);
		}
	}
	

/**
* enregistre ou met à jour la selection en base de données :
*  nom et commentaire 
*
*/
	
	public function save() {
		if ($this->id != 0) {
			$query = "UPDATE selections SET nom_selection = :nom_selection, commentaire = :commentaire, datemod = NOW() WHERE id_selection = ".$this->id;
		}
		else {
			$query = "INSERT INTO selections(nom_selection,commentaire,datemod) VALUES (:nom_selection,:commentaire, NOW())";
		}
		$stmt = self::$db->prepare($query);
		$stmt->bindParam(':nom_selection', $this-> nom);
		$stmt->bindParam(':commentaire', $this->commentaire);
		$stmt->execute();
	}
	
	
/**
* met à jour les formations associées à la selection
*
* @param array $formations un tableau contenant les id des formations à mettre dans la selection
*/	
	
	public function updateFormations($formations) {
		$query = "DELETE FROM in_selection WHERE selection_id = ".$this->id;
		$result = self::$db->exec($query);
		if ($formations != NULL AND sizeof($formations) > 0) {
			$formation = '';
			$query = "INSERT INTO in_selection (selection_id,formation_id) VALUES (".$this->id.",:formation_id)";
			$stmt = self::$db->prepare($query);
			$stmt->bindParam(':formation_id', $formation);
			foreach ($formations as $formation) {
				$stmt->execute();
			}
			$this->formations = $formations;
			$query = "UPDATE selections SET datemod = NOW() WHERE id_selection = ".$this->id;
			self::$db->exec($query);
		}
	}

	
	
/**
* supprime la selection en db. Remet l'id de l'objet courant à 0
*
*/	
	
	public function delete() {
		if ($this->id != 0) {
			$query = "DELETE FROM selections WHERE id_selection = ".$this->id;
			$stmt = self::$db->exec($query);
		}
		$this->id = 0;
		$this->nom = '';
		$this->commentaire = '';
		$this->dateMod = '';
		$this->formations = NULL;
		$this->formations = array();
	}

/**
* méthode statique qui renvoie la liste des selections existantes
* sous la forme d'un tableau 2D (nom, id, commentaire et date de modification)
*
* @return array un tableau 2D contenant les selections existantes
*/		
	
	static function getSelections() {
		$query = "SELECT id_selection, nom_selection, commentaire, datemod FROM selections ORDER BY nom_selection";
		$result = self::$db->query($query);
		$all =  $result->fetchall(PDO::FETCH_ASSOC);
		foreach ($all as $cell=>$one) {
			$query = "SELECT count(formation_id) as num FROM in_selection WHERE selection_id = ".$one['id_selection'];
			$result = self::$db->query($query);
			$rows = $result->fetch();
			$all[$cell]['num'] = $rows[0]['num'];
		}
		return $all;
		
	}
	
	
}