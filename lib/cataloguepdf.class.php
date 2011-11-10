<?php

/** 
 * Fichier contenant la classe cataloguePdf
 * 
 * 
 * @package lib 
 * @author Romain BOURDON <romain@ayeba.fr> 
 */


/** 
 * La classe cataloguePdf permet de générer l'export d'une selection de formations au format PDF
 * @package lib 
 * @author Romain BOURDON <romain@ayeba.fr> 
 */
 
class cataloguePdf {
	
	public $idPdfCatalogue;
	public $selectionId = 0;
	public $selection;
	public $fpdi;
	private $couverture;
	private $couverture34;
	private $modeleFormation;
	private $modeleSommaire;
	private $elementsBefore = array();
	private $elementsAfter = array();
	private $titre = '';
	public $dateMod = '';
	public $commentaire = '';
	public $nomCatalogue = '';
	
	static $db;
	
	public function __construct($idCataloguePdf = 0) {
		
		if (self::$db == NULL) {
			$db = new mypdo();
			self::$db = $db;
		}	
		
		if ($idCataloguePdf != 0) {
			$query = "SELECT * FROM pdf_catalogue WHERE id_pdf_catalogue = ".(int)$idCataloguePdf;
			$result = self::$db->query($query);
			if ($catalogue = $result->fetch(PDO::FETCH_ASSOC)) {
				$this->idPdfCatalogue = $idCataloguePdf;
				
				$this->setSelection($catalogue['selection_id']);
				$this->setTitre($catalogue['titre']);
				$this->setCouverture12($catalogue['couverture']);
				$this->setCouverture34($catalogue['couverture34']);
				$this->elementsBefore = unserialize($catalogue['elements_before']);
				$this->elementsAfter = unserialize($catalogue['elements_after']);
				$this->setModeleFormation($catalogue['modele_formation']);
				$this->setModeleSommaire($catalogue['modele_sommaire']);
				$this->dateMod = $catalogue['datemod'];
				$this->commentaire = $catalogue['commentaire'];
				$this->nomCatalogue = $catalogue['nom_pdf_catalogue'];
				
			}

		}
		else {
			$this->couverture['id_element'] = 0;
			$this->couverture34['id_element'] = 0;
			$this->modeleFormation['id_element'] = 0;
			$this->modeleSommaire['id_element'] = 0;
		}
	}

	
/**
* permet de sauvegarder le catalogue en db
*
*/		
	
	public function save() {
		if ($this->idPdfCatalogue == 0) {
			$query = "INSERT INTO pdf_catalogue(nom_pdf_catalogue,selection_id,couverture,couverture34,modele_formation,modele_sommaire,elements_before,elements_after,titre,commentaire,datemod)
						VALUES (:nom_pdf_catalogue,:selection_id,:couverture,:couverture34,:modele_formation,:modele_sommaire,:elements_before,:elements_after,:titre,:commentaire,NOW())";
		}
		else {
			$query = "UPDATE pdf_catalogue SET nom_pdf_catalogue = :nom_pdf_catalogue, selection_id = :selection_id, couverture = :couverture, couverture34 = :couverture34, modele_formation = :modele_formation, modele_sommaire = :modele_sommaire, elements_before = :elements_before, elements_after = :elements_after, titre = :titre, commentaire = :commentaire, datemod = NOW() WHERE id_pdf_catalogue = ".$this->idPdfCatalogue;
		}
		
		$stmt = self::$db->prepare($query);
		$stmt->bindParam(':nom_pdf_catalogue', $this->nomCatalogue);
		$stmt->bindParam(':selection_id', $this->selectionId);
		$stmt->bindParam(':couverture', $this->couverture['id_element']);
		$stmt->bindParam(':couverture34', $this->couverture34['id_element']);
		$stmt->bindParam(':modele_formation', $this->modeleFormation['id_element']);
		$stmt->bindParam(':modele_sommaire', $this->modeleSommaire['id_element']);
		$elementsBefore = serialize($this->elementsBefore);
		$stmt->bindParam(':elements_before', $elementsBefore);
		$elementsAfter = serialize($this->elementsAfter);
		$stmt->bindParam(':elements_after', $elementsAfter);
		$stmt->bindParam(':titre', $this->titre);
		$stmt->bindParam(':commentaire', $this->commentaire);
		$stmt->execute();
		if ($this->idPdfCatalogue == 0) {
			$id = self::$db->lastInsertId();
			$this->idPdfCatalogue = $id;
		}
		
	}
	
/**
* permet de détruire le catalogue en db et l'objet courant
*
*/		
	
	public function delete() {
		$query = "DELETE FROM pdf_catalogue WHERE id_pdf_catalogue = ".$this->idPdfCatalogue;
		self::$db->exec($query);
		unset($this);
	}
	
	
/**
* permet de choisir ou changer la selection associée au catalogue pdf 
*
* @param int $idSelection l'id de la selection
*/	
	
	public function setSelection($idSelection) {
		selection::$db = self::$db;
		$this->selection = new selection($idSelection);
		$this->selectionId = $idSelection;
	}	
	
/**
* retourne la selection associée au catalogue pdf 
*
* @return $array la selection
*/	
	
	public function getSelection() {
		return ($this->selection);
	}	
	
		
	
	
	
/**
* permet de définir les 1ere et 2eme de couverture 
* L'element fourni peut avoir une ou deux pages. Si iln'y en a qu'une, un page blanche est insérée en deuxième de couverture
*
* @param int $idElement l'id de l'element à mettre
* @return bool TRUE si tout va bien sinon FALSE
*/	
	
	public function setCouverture12($idElement) {
		if ($element = $this->getElementInfos($idElement)) {
		$this->couverture = $element;
		return TRUE;
		}
		return FALSE;
		
	}
	
	
	
/**
* retourne la couverture associée au catalogue pdf 
*
* @return $array la couverture
*/	
	
	public function getCouverture12() {
		return ($this->couverture);
	}	

	
	
/**
* permet de définir les 3eme et 4eme de couverture
* l'element fourni peut avoir une ou deux pages. Si il n'en a qu'une, la dernière page insérée en 'after' devient la
* 3eme de couverture. 
*
* @param int $idElement l'id de l'element à mettre
* @return bool TRUE si tout va bien sinon FALSE
*/
	
	public function setCouverture34($idElement) {
		if ($element = $this->getElementInfos($idElement)) {
		$this->couverture34 = $element;
		return TRUE;
		}
		return FALSE;
		
	}
	

/**
* retourne la 3eme et 4eme de couverture associée au catalogue pdf 
*
* @return $array la 3eme et 4eme de couverture
*/	
	
	public function getCouverture34() {
		return ($this->couverture34);
	}		
	
	
/**
* permet de définir un modèle à utiliser pour les pages de formations
*
* @param int $idElement l'id de l'element à utiliser
* @return bool TRUE si tout va bien sinon FALSE
*/		
	
	public function setModeleFormation($idElement) {
		if ($element = $this->getElementInfos($idElement)) {
			$this->modeleFormation = $element;
			return TRUE;
		}
		return FALSE;
	}

	
/**
* retourne le modele de formation associée au catalogue pdf 
*
* @return $array le modele
*/	
	
	public function getModeleFormation() {
		return ($this->modeleFormation);
	}		
	
	
/**
* permet de définir un modèle à utiliser pour le sommaire
*
* @param int $idElement l'id de l'element à utiliser
* @return bool TRUE si tout va bien sinon FALSE
*/		
	
	public function setModeleSommaire($idElement) {
		if ($element = $this->getElementInfos($idElement)) {
			$this->modeleSommaire = $element;
			return TRUE;
		}
		return FALSE;
	}
	
	
/**
* retourne le modèle de sommaire associée au catalogue pdf 
*
* @return $array le modele
*/	
	
	public function getModeleSommaire() {
		return ($this->modeleSommaire);
	}		
	
/**
* permet de définir le titre à afficher sur la couverture
*
* @param string $titre le titre à afficher
* @param int $x sa position x dans la page
* @param int $y sa position y dans la page
*/		
	
	public function SetTitre($titre) {
		if ($titre != '') {
			$this->titre = $titre;
		}
	}

	
/**
* retourne le titre associée au catalogue pdf 
*
* @return $string le titre
*/	
	
	public function getTitre() {
		return ($this->titre);
	}		

	
/**
* retourne les pages de début de catalogue
*
* @return $array la liste des pages
*/	
	
	public function getElementsBefore() {
		return ($this->elementsBefore);
	}		
	
	
/**
* retourne les pages de fin de catalogue
*
* @return $array la liste des pages
*/	
	
	public function getElementsAfter() {
		return ($this->elementsAfter);
	}		

	
	
/**
* permet de générer la page PDF d'une formation.
* 
* @param int $formationFromSelection l'id de la formation
*/		
	
	public function addPageFormation($formationFromSelection) {
		$border = 0;
		
		if ($formationFromSelection != NULL) {
			$this->fpdi->AddPage();
			if (isset($this->modeleFormation['fichier_element'])) {
				$this->fpdi->setSourceFile($this->modeleFormation['fichier_element']);
				$tplidx = $this->fpdi->ImportPage(1); 
				$this->fpdi->useTemplate($tplidx);
			}
			
			$formation = new formation($formationFromSelection);	
			$criteres = $formation->getCriteres();
			$dates = $formation->getDates();
			$famillesCriteres = catalogue::getAllFamillesCriteres();		
			
			
			//image
			if ($formation->image != '') {
				if (is_file('media/formation_img/'.$formation->image)) {
					$this->fpdi->Image('media/formation_img/small'.$formation->image,120,40);
				}
			}
			
			
			$this->fpdi->setY(40);
			$this->fpdi->SetFont('Arial','B',16);
			$this->fpdi->MultiCell(90,5,$formation->nom,$border);
			
			$this->fpdi->setY($this->fpdi->GetY()+5);
			$this->fpdi->SetFont('Arial','',14);
			$this->fpdi->MultiCell(100,5,$formation->sousTitre,$border);
			
			$this->fpdi->setY($this->fpdi->GetY()+5);
			$this->fpdi->SetFont('Arial','',10);
			$this->fpdi->MultiCell(100,5,$formation->description,$border);
			
			//bloc à droite
			//
			$actualY  = $this->fpdi->GetY();
			$this->fpdi->SetFont('Arial','',10);
			$this->fpdi->setXY(120,100);
			$this->fpdi->MultiCell(80,5,"Le plus : ".$formation->plus,$border,'C');
			
			$this->fpdi->setY($this->fpdi->GetY()+5);
			$this->fpdi->SetFont('Arial','B',10);
			$this->fpdi->setx(120);
			$this->fpdi->Cell(50,5,'code : ',$border,0);
			$this->fpdi->SetFont('Arial','',10);
			$this->fpdi->Cell(100,5,$formation->code,$border,2);
			
			$pluriel = '';
			if ($formation->dureeJours > 1)
				$pluriel = 's';
			$this->fpdi->SetFont('Arial','B',10);
			$this->fpdi->setx(120);
			$this->fpdi->Cell(50,5,'durée : ',$border,0);
			$this->fpdi->SetFont('Arial','',10);
			$this->fpdi->Cell(100,5,$formation->dureeJours.' jour'.$pluriel.' ('.$formation->dureeHeures.' heures)',$border,2);
			
			if ($formation->tarifInter != 0) {
				$this->fpdi->SetFont('Arial','B',10);
				$this->fpdi->setx(120);
				$this->fpdi->Cell(50,5,'tarif inter : ',$border,0);
				$this->fpdi->SetFont('Arial','',10);
				$this->fpdi->Cell(100,5,$formation->tarifInter.' Euros HT',$border,2);
			}
			if ($formation->tarifInter != 0) {
				$this->fpdi->SetFont('Arial','B',10);
				$this->fpdi->setx(120);
				$this->fpdi->Cell(50,5,'cours particulier : ',$border,0);
				$this->fpdi->SetFont('Arial','',10);
				$this->fpdi->Cell(100,5,$formation->tarifCp.' Euros HT',$border,2);
			}
			
			$this->fpdi->setx(120);
			$this->fpdi->SetFont('Arial','B',10);
			$this->fpdi->Cell(50,5,'intra : ',$border,0);
			$this->fpdi->SetFont('Arial','',10);
			$this->fpdi->Cell(100,5,'nous consulter',$border,2);
			
			
			if (sizeof($dates) > 0) {
				$this->fpdi->setx(120);
				$this->fpdi->SetFont('Arial','B',10);
				$this->fpdi->Cell(50,5,'Dates inter ',$border,2);
				
				$ville_id = '';
				$done = 0; 
				//setlocale(LC_TIME, "fr_FR");
				foreach ($dates as $date) {
					if ($ville_id != $date['ville_id']) {
						$ville_id = $date['ville_id'];
						$this->fpdi->setx(120);
						$this->fpdi->Cell(50,5,'     '.$date['nom_ville'],$border,0);
					}
					$timeStamp = strtotime($date['date']);
					$this->fpdi->Cell(100,5,strftime("%d/%m/%Y",$timeStamp),$border,2);
				}
					
			}
				
			
			$this->fpdi->setY($actualY);
			//
			//fin du bloc à droite
			
			
			//public
			$famillePublic = 4;
			$listPublic = $virgule = '';
			foreach ($criteres as $critere) {
				if (isset($famillesCriteres[$famillePublic]['criteres'][$critere])) {
					$listPublic .= $virgule.$famillesCriteres[$famillePublic]['criteres'][$critere];
					$virgule = ', ';
				}
			}
			$this->fpdi->setY($this->fpdi->GetY()+5);
			$this->fpdi->SetFont('Arial','B',10);
			$this->fpdi->MultiCell(100,5,'public : ',$border);
			$this->fpdi->SetFont('Arial','',10);
			$this->fpdi->MultiCell(100,5,$listPublic,$border);
				
			
			
			$this->fpdi->setY($this->fpdi->GetY()+5);
			$this->fpdi->SetFont('Arial','B',10);
			$this->fpdi->MultiCell(100,5,'pré-requis : ',$border);
			$this->fpdi->SetFont('Arial','',10);
			$this->fpdi->MultiCell(100,5,$formation->preRequis,$border);
			
			$this->fpdi->setY($this->fpdi->GetY()+5);
			$this->fpdi->SetFont('Arial','B',10);
			$this->fpdi->MultiCell(100,5,'objectifs : ',$border);
			$this->fpdi->SetFont('Arial','',10);
			$this->fpdi->MultiCell(100,5,$formation->objectifs,$border);
			
			
			$this->fpdi->setY($this->fpdi->GetY()+5);
			$this->fpdi->SetFont('Arial','B',10);
			$this->fpdi->MultiCell(100,5,'PROGRAMME ',$border);
			$this->fpdi->SetFont('Arial','',10);
			$this->fpdi->MultiCell(190,5,$formation->programme,$border);
			
			
			
		}
	}
	
/**
* renvoie une tableau contenant toutes les informations d'un element pdf
* 
* @param int $idElement l'id de l'element 
*/	
		
	public function getElementInfos($idElement) {
		$query = "SELECT id_element,nom_element,commentaire,texte_sommaire,fichier_element,nb_pages,pdf_categorie_id, titre_x, titre_y FROM pdf_elements WHERE id_element = ".(int)$idElement;
		$result = self::$db->query($query);
		if ($ligne = $result->fetch(PDO::FETCH_ASSOC)) {
			if (is_file('media/tpl_pdf/'.$ligne['fichier_element'])) {
				$ligne['fichier_element'] = 'media/tpl_pdf/'.$ligne['fichier_element'];
				return $ligne;
			}
			return FALSE;
		}
		return FALSE;
	}
	
/**
* ajoute une element pdf dans la partie before
* 
* @param int $idElement l'id de l'element 
* @param int $poids sa position dans la partie before
*/		
	
	
	public function addBefore($idElement,$poids){
		if ($element = $this->getElementInfos($idElement))
		$this->elementsBefore[$poids] = $element;
	}
	
	
/**
* ajoute une element pdf dans la partie after
* 
* @param int $idElement l'id de l'element 
* @param int $poids sa position dans la partie after
*/		
	
	public function addAfter($idElement,$poids){
		if ($element = $this->getElementInfos($idElement))
		$this->elementsAfter[$poids] = $element;
	}
	
	
/**
* genère le contenu pdf du sommaire
* 
*/		
		
	public function createSommaire() {
		$border = 0;
		$posPage = 2;
		$points = ' ..........................................................................................................................................................................................';
		
		$this->fpdi->AddPage();
		if (isset($this->modeleSommaire['fichier_element'])) {
			$this->fpdi->setSourceFile($this->modeleSommaire['fichier_element']);
			$tplidx = $this->fpdi->ImportPage(1); 
			$this->fpdi->useTemplate($tplidx);
		}
		$this->fpdi->setXY(65,80);
		$this->fpdi->SetFont('Arial','B',16);
		$this->fpdi->MultiCell(80,5,"SOMMAIRE",$border,'C');
		$this->fpdi->setY(110);
		
		//page en before
		foreach ($this->elementsBefore as $elementBefore) {
			$newLine['page'] = $posPage;
			$newLine['texte'] = $elementBefore['texte_sommaire'].$points;
			$sommaire[] = $newLine;
			$posPage += $elementBefore['nb_pages'];
		} 
		

		//formations
		$newLine['page'] = '';
		$newLine['texte'] = '';
		$sommaire[] = $newLine;		
		
		$newLine['page'] = '';
		$newLine['texte'] = 'Quelques formations';
		$sommaire[] = $newLine;
	
		foreach ($this->selection->formations as $formation) {
			$newLine['page'] = $posPage;
			$newLine['texte'] = '        '.$formation['nom_formation'].$points;
			$sommaire[] = $newLine;
			$posPage += 1;
		}
		
		$newLine['page'] = '';
		$newLine['texte'] = '';
		$sommaire[] = $newLine;	
		
		//page en after
		foreach ($this->elementsAfter as $elementAfter) {
			$newLine['page'] = $posPage;
			$newLine['texte'] = $elementAfter['texte_sommaire'].$points;
			$sommaire[] = $newLine;
			$posPage += $elementAfter['nb_pages'];
		} 
		
		//affichage du sommaire
		$this->fpdi->SetFillColor(255,255,255);
		foreach ($sommaire as $ligne) {
			$x = $this->fpdi->getX();
			$this->fpdi->SetFont('Arial','B',10);
			$this->fpdi->Cell(185,5,$ligne['texte'],$border,0,'L');
			$this->fpdi->SetFont('Arial','',10);
			$this->fpdi->Cell(5,5,$ligne['page'],$border,0,'R',TRUE);
			$this->fpdi->Cell(20,5,'',$border,2,'R',TRUE);
			$this->fpdi->setX($x);
		}
		
		
	}

	
/**
* genère le pdf
* si un nom de fichier est fourni, le pdf est généré soius la forme d'un fichier sur le serveur
* sinon, il est envoyé vers la sortie standard
*
* @param string $filename un nom de fichier
*/	
	
	public function output() {
		$this->fpdi = new myfpdi();
		$this->fpdi->AliasNbPages();
		//couverture12
		$this->fpdi->addPageNum = 0;
		if (isset($this->couverture['fichier_element']) AND $this->couverture['fichier_element'] != '') {
			$pageCount = $this->fpdi->setSourceFile($this->couverture['fichier_element']);
			if ($pageCount >= 1) {
					for ($i = 1; $i <= $pageCount; $i++) { 
						$tplidx = $this->fpdi->ImportPage($i); 
						$this->fpdi->AddPage(); 
						$this->fpdi->useTemplate($tplidx);
						if ($i == 1) {
							$this->fpdi->SetFont('Arial','B',16);
							$this->fpdi->SetXY($this->couverture['titre_x'],$this->couverture['titre_y']);
							$this->fpdi->multiCell(500,20,$this->titre,0);
						}
					}
					//si on n'a qu'une page, on en ajoute une deuxième vide pour la 2eme de couv
					if ($i == 2) {
						$this->fpdi->AddPage();
					}
			}
		}
		
		//sommaire
		$this->createSommaire();
		$this->fpdi->addPageNum = 1;
		
		//elementsBefore
		foreach ($this->elementsBefore as $elementBefore) {
			$pageCount = $this->fpdi->setSourceFile($elementBefore['fichier_element']);
			for ($i = 1; $i <= $pageCount; $i++) { 
				$tplidx = $this->fpdi->ImportPage($i); 
				//$s = $this->fpdi->getTemplatesize($tplidx); 
				$this->fpdi->AddPage(); 
				$this->fpdi->useTemplate($tplidx);
			}
		}
		
		//formations
		foreach ($this->selection->formations as $formation) {
			$this->addPageFormation($formation['id_formation']);
		}
		
		
		//elementsAfter
		foreach ($this->elementsAfter as $elementAfter) {
			$pageCount = $this->fpdi->setSourceFile($elementAfter['fichier_element']);
			for ($i = 1; $i <= $pageCount; $i++) { 
				$tplidx = $this->fpdi->ImportPage($i); 
				//$s = $this->fpdi->getTemplatesize($tplidx); 
				$this->fpdi->AddPage(); 
				$this->fpdi->useTemplate($tplidx);
			}
		}
		
		
		//couverture34
		
		if (isset($this->couverture34['fichier_element']) AND $this->couverture34['fichier_element'] != '') {
			$pageCount = $this->fpdi->setSourceFile($this->couverture34['fichier_element']);
			if ($pageCount >= 1) {
					for ($i = 1; $i <= $pageCount; $i++) { 
						$tplidx = $this->fpdi->ImportPage($i); 
						$this->fpdi->AddPage(); 
						$this->fpdi->addPageNum = 0;
						$this->fpdi->useTemplate($tplidx);
					}
			}
		}
		
		
		$this->fpdi->Output();
	}	
	
/**
 * méthode statique qui permet de récuperer l'ensemble des catalogues PDF existants en db
 * 
 */	

	static public function getCatalogues() {
		$query = "SELECT id_pdf_catalogue, nom_pdf_catalogue, datemod, commentaire FROM pdf_catalogue ORDER BY nom_pdf_catalogue";
		$result = self::$db->query($query); 
		return $result->fetchAll(PDO::FETCH_ASSOC);
	}
}