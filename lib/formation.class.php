<?php


/** 
 * Fichier contenant la classe formation
 * 
 * 
 * @package lib 
 * @author Romain BOURDON <romain@ayeba.fr> 
 */



/** 
 * La classe formation représente les formations présentes dans le catalogue Ayeba
 * Elle permet de manipuler les formations et de leur associer des critères de tri.
 * @package lib 
 * @author Romain BOURDON <romain@ayeba.fr> 
 */


class formation {
	public $id;
	public $nom;
	private $criteres = NULL;
	
	static $db;
	
	
/**
* constructeur
*
* @param int $id l'id de la formation à charger, 0 si création d'une nouvelle formation
*/
	
	public function __construct($id = 0) {		
		$this->id = $id;
		if (self::$db == NULL) {
			$db = new mypdo();
			self::$db = $db;
		}
			
		if ($id != 0) {
			$id = (int)$id;
			$query = 'SELECT nom_formation FROM formations WHERE id_formation = '.$id;
			$result = self::$db->query($query);
			$ligne = $result->fetch(PDO::FETCH_ASSOC);	
			$this->nom = $ligne['nom_formation'];
		}
	}
	

/**
* enregistre/met à jour la formation courante en base de données
*
* @param array $values un tableau 2D contenant les informations à enregistrer
*/	
	
	public function save($values) {
		if ($this->id != 0) {
			//update
			$query = "UPDATE formations SET nom_formation = :nom WHERE id_formation = ".$this->id;
			$stmt = self::$db->prepare($query);
			$stmt->bindParam(':nom', $values['nom']);
			$stmt->execute();
			$this->nom = $values['nom'];
		}
		else {
			//create
			$query = "INSERT INTO formations(nom_formation) VALUES (:nom)";
			$stmt = self::$db->prepare($query);
			$stmt->bindParam(':nom', $values['nom']);
			$stmt->execute();
			$newId = self::$db->lastInsertId();
			$this->nom = $values['nom'];
			$this->id = $newId;
		}
		$this->setCriteres($values['criteres']);
	}
	
/**
* met à jour les critères de tri de la formation
*
* @param array $criteres la liste des id des critères à enregistrer
*/	
	private function setCriteres($criteres) {
		$query = 'DELETE FROM is_critere WHERE formation_id = '.$this->id;
		self::$db->exec($query);
		
		$query = 'INSERT INTO is_critere(formation_id,critere_id) VALUES ('.$this->id.',:critere)';
		$stmt = self::$db->prepare($query);
		$newCritere = '';
		$stmt->bindParam(':critere', $newCritere);
		foreach ($criteres as $critere) {
			$newCritere = $critere;
			$stmt->execute();		
		}
		$this->criteres = $criteres;
	}
	

/**
* renvoie la liste des criteres associés à la formation
*
* @return array un tableau contenant les id des critères associés à la formation
*/	
	
	public function getCriteres() {
		if ($this->criteres == NULL) {
			$query = "SELECT critere_id FROM is_critere WHERE formation_id = ".$this->id;
			$result = self::$db->query($query);
			while ($ligne = $result->fetch(PDO::FETCH_ASSOC))
				$this->criteres[] = $ligne['critere_id'];
		}
		return $this->criteres;
	}
			
	
}