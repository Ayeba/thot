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
	public $nom = '';
	private $criteres = NULL;
	public $image = '';
	public $sousTitre = '';
	public $description = '';
	public $objectifs = '';
	public $preRequis = '';
	public $code = '';
	public $programme = '';
	public $dureeJours = 0;
	public $dureeHeures = 0;
	public $plus = '';
	public $tarifInter = 0;
	public $tarifCp = 0;
	
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
			$query = 'SELECT nom_formation,soustitre,description,objectifs,prerequis,code,programme,dureejours,dureeheures,plus,tarifinter,tarifcp,image FROM formations WHERE id_formation = '.$id;
			$result = self::$db->query($query);
			$ligne = $result->fetch(PDO::FETCH_ASSOC);	
			$this->nom = $ligne['nom_formation'];
			$this->sousTitre = $ligne['soustitre'];
			$this->description = $ligne['description'];
			$this->objectifs = $ligne['objectifs'];
			$this->preRequis = $ligne['prerequis'];
			$this->code = $ligne['code'];
			$this->programme = $ligne['programme'];
			$this->dureeJours = $ligne['dureejours'];
			$this->dureeHeures = $ligne['dureeheures'];
			$this->plus = $ligne['plus'];
			$this->tarifInter = $ligne['tarifinter'];
			$this->tarifCp = $ligne['tarifcp'];
			$this->image = $ligne['image'];
		}
	}
	

/**
* enregistre / met à jour la formation courante en base de données
*
* @param array $values un tableau 2D contenant les informations à enregistrer
*/	
	
	public function save($values) {
		if ($this->id != 0) {
			//update
			$query = "UPDATE formations SET nom_formation = :nom,soustitre = :soustitre, description = :description, objectifs = :objectifs, prerequis= :prerequis, code = :code, programme = :programme, dureejours = :dureejours, dureeheures = :dureeheures, plus = :plus, tarifinter = :tarifinter, tarifcp = :tarifcp, image = :image WHERE id_formation = ".$this->id;
			$stmt = self::$db->prepare($query);
			$stmt->bindParam(':nom', $values['nom']);
			$stmt->bindParam(':soustitre', $values['sousTitre']);
			$stmt->bindParam(':description', $values['description']);
			$stmt->bindParam(':objectifs', $values['objectifs']);
			$stmt->bindParam(':prerequis', $values['preRequis']);
			$stmt->bindParam(':code', $values['code']);
			$stmt->bindParam(':programme', $values['programme']);
			$stmt->bindParam(':dureejours', $values['dureeJours']);
			$stmt->bindParam(':dureeheures', $values['dureeHeures']);
			$stmt->bindParam(':plus', $values['plus']);
			$stmt->bindParam(':tarifinter', $values['tarifInter']);
			$stmt->bindParam(':tarifcp', $values['tarifCp']);
			$stmt->bindParam(':image', $values['image']);
			$stmt->execute();
		}
		else {
			//create
			$query = "INSERT INTO formations(nom_formation,soustitre,description,objectifs,prerequis,code,programme,dureejours,dureeheures,plus,tarifinter,tarifcp,image) VALUES (:nom,:soustitre,:description,:objectifs,:prerequis,:code,:programme,:dureejours,:dureeheures,:plus,:tarifinter,:tarifcp,:image)";
			$stmt = self::$db->prepare($query);
			$stmt->bindParam(':nom', $values['nom']);
			$stmt->bindParam(':soustitre', $values['sousTitre']);
			$stmt->bindParam(':description', $values['description']);
			$stmt->bindParam(':objectifs', $values['objectifs']);
			$stmt->bindParam(':prerequis', $values['preRequis']);
			$stmt->bindParam(':code', $values['code']);
			$stmt->bindParam(':programme', $values['programme']);
			$stmt->bindParam(':dureejours', $values['dureeJours']);
			$stmt->bindParam(':dureeheures', $values['dureeHeures']);
			$stmt->bindParam(':plus', $values['plus']);
			$stmt->bindParam(':tarifinter', $values['tarifInter']);
			$stmt->bindParam(':tarifcp', $values['tarifCp']);
			$stmt->bindParam(':image', $values['image']);
			$stmt->execute();
			$newId = self::$db->lastInsertId();
			$this->id = $newId;
		}
		$this->nom = $values['nom'];
		$this->sousTitre = $values['sousTitre'];
		$this->description = $values['description'];
		$this->objectifs = $values['objectifs'];
		$this->preRequis = $values['preRequis'];
		$this->code = $values['code'];
		$this->programme = $values['programme'];
		$this->dureeJours = $values['dureeJours'];
		$this->dureeHeures = $values['dureeHeures'];
		$this->plus = $values['plus'];
		$this->tarifInter = $values['tarifInter'];
		$this->tarifCp = $values['tarifCp'];
		$this->image = $values['image'];
		$this->setCriteres($values['criteres']);
	}
	
/**
* supprime la formation en db. Remet l'id de l'objet courant à 0
*
*/	
	
	public function delete() {
		if ($this->id != 0) {
			$query = "DELETE FROM formations WHERE id_formation = ".$this->id;
			$stmt = self::$db->exec($query);
		}
		$this->id = 0;
		$this->nom = '';
		$this->sousTitre = '';
		$this->description = '';
		$this->objectifs = '';
		$this->preRequis = '';
		$this->code = '';
		$this->programme = '';
		$this->dureeJours = 0;
		$this->dureeHeures = 0;
		$this->plus = '';
		$this->tarifInter = 0;
		$this->tarifCp = 0;
		$this->image = '';
	}
 	
	
/**
* met à jour les critères de tri de la formation
*
* @param array $criteres la liste des id des critères à enregistrer
*/	
	private function setCriteres($criteres) {
		$query = 'DELETE FROM has_critere WHERE formation_id = '.$this->id;
		self::$db->exec($query);
		
		$query = 'INSERT INTO has_critere(formation_id,critere_id) VALUES ('.$this->id.',:critere)';
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
			$query = "SELECT critere_id FROM has_critere WHERE formation_id = ".$this->id;
			$result = self::$db->query($query);
			while ($ligne = $result->fetch(PDO::FETCH_ASSOC))
				$this->criteres[] = $ligne['critere_id'];
		}
		return $this->criteres;
	}

/**
* renvoie un tableau 2D contenant la liste des dates à venir et lieux de la formation
*
* @return array un tableau 2D contenant la liste des dates et lieux de la formation
*/		

	public function getDates() {
		$query = "SELECT ville_id,nom_ville,date FROM dates,villes WHERE formation_id = ".$this->id." AND dates.ville_id = villes.id_ville AND date > NOW() ORDER BY nom_ville";
		$result = self::$db->query($query);
		return $result->fetchAll(PDO::FETCH_ASSOC);
	}

	
/**
* ajoute une nouvelle date pour la formation dans la ville id
*
* @param int $ville l'id de la ville de la nouvelle date
* @param string $date la date au format AAAA-MM-JJ
*/		

	public function addDate($ville,$date){
		$query = "INSERT INTO dates(formation_id,ville_id,date) VALUES (".$this->id.",:ville,:date)";
		$stmt = self::$db->prepare($query);
		$stmt->bindParam(':ville', $ville);
		$stmt->bindParam(':date', $date);
		return ($stmt->execute());		
	}

/**
* retire une date pour la formation dans la ville id
*
* @param int $ville l'id de la ville de la date
* @param string $date la date au format AAAA-MM-JJ
*/		

	public function delDate($ville,$date){
		$query = "DELETE FROM dates WHERE formation_id = ".$this->id." AND ville_id = :ville AND date = :date";
		$stmt = self::$db->prepare($query);
		$stmt->bindParam(':ville', $ville);
		$stmt->bindParam(':date', $date);
		return ($stmt->execute());		
	}
	
	
/**
* renvoie un tableau 2D contenant la liste des formations en relation
*
* @return array un tableau 2D contenant la liste des formations en relation
*/		

	public function getRelations() {
		$query = "SELECT nom_formation, relation_formation_id as id_formation FROM en_relation,formations WHERE formation_id = ".$this->id." AND en_relation.relation_formation_id = formations.id_formation";
		$result = self::$db->query($query);
		return $result->fetchAll(PDO::FETCH_ASSOC);
	}	

	
/**
* ajoute une nouvelle relation pour la formation 
*
* @param int $formation_id l'id de la formation à mettre en relation
*/		

	public function addRelation($formation_id){
		if ($formation_id != $this->id) {
			$query = "INSERT INTO en_relation(formation_id,relation_formation_id) VALUES (".$this->id.",:formation_id)";
			$stmt = self::$db->prepare($query);
			$stmt->bindParam(':formation_id', $formation_id);
			return ($stmt->execute());		
		}
		return false;
	}

	
/**
* met à jour les relations de la formation 
*
* @param int $relations un talbeau contenant les id des formations à mettre en relation
*/		

	public function updateRelations($relations){
		$query = "DELETE FROM en_relation WHERE formation_id = ".$this->id;
		self::$db->exec($query);
		foreach ($relations as $relation) {
			$this->addRelation($relation);			
		}
	}
	

	
	
/**
* retire une relation pour la formation
*
* @param int $formation_id l'id de la formation à retirer de la relation
*/		

	public function delRelation($formation_id){
		$query = "DELETE FROM en_relation WHERE formation_id = ".$this->id." AND relation_formation_id = :formation_id";
		$stmt = self::$db->prepare($query);
		$stmt->bindParam(':formation_id', $formation_id);
		return ($stmt->execute());		
	}
	



/**
* renvoie un tableau 2D contenant la liste des formations "plus loin"
*
* @return array un tableau 2D contenant la liste des formations "plus loin"
*/		

	public function getPlusLoins() {
		$query = "SELECT nom_formation, plusloin_formation_id as id_formation FROM plus_loin,formations WHERE formation_id = ".$this->id." AND plus_loin.plusloin_formation_id = formations.id_formation";
		$result = self::$db->query($query);
		return $result->fetchAll(PDO::FETCH_ASSOC);
	}	

	
/**
* ajoute un nouveau "plus loin" pour la formation 
*
* @param int $formation_id l'id de la formation à mettre en "plus loin"
*/		

	public function addPlusLoin($formation_id){
		if ($formation_id != $this->id) {
			$query = "INSERT INTO plus_loin(formation_id,plusloin_formation_id) VALUES (".$this->id.",:formation_id)";
			$stmt = self::$db->prepare($query);
			$stmt->bindParam(':formation_id', $formation_id);
			return ($stmt->execute());		
		}
		return false;
	}

	
/**
* met à jour les "plus loin" de la formation 
*
* @param int $plusLoins un talbeau contenant les id des formations à mettre en "plus loin"
*/		

	public function updatePlusLoins($plusLoins){
		$query = "DELETE FROM plus_loin WHERE formation_id = ".$this->id;
		self::$db->exec($query);
		foreach ($plusLoins as $plusLoin) {
			$this->addPlusLoin($plusLoin);			
		}
	}
	

		
/**
* retire un "plus loin" pour la formation
*
* @param int $formation_id l'id de la formation à retirer de "plus loin"
*/		

	public function delPlusLoin($formation_id){
		$query = "DELETE FROM plus_loin WHERE formation_id = ".$this->id." AND plusloin_formation_id = :formation_id";
		$stmt = self::$db->prepare($query);
		$stmt->bindParam(':formation_id', $formation_id);
		return ($stmt->execute());		
	}
	
}
	
