<?php

/** 
 * Fichier contenant la classe selectionPdf
 * 
 * 
 * @package lib 
 * @author Romain BOURDON <romain@ayeba.fr> 
 */


/** 
 * La classe selectionPdf permet de générer l'export d'une selection de formations au format PDF
 * @package lib 
 * @author Romain BOURDON <romain@ayeba.fr> 
 */
 
class selectionPdf {
	
	public $selection;
	public $fpdi;
	public $couverture;
	public $couverture34;
	public $modeleFormation;
	public $modeleSommaire;
	public $elementsBefore = array();
	public $elementsAfter = array();
	public $titre = '';
	public $titreXY = array('x'=>0,'y'=>0);
	
	static $db;
	
	public function __construct($idSelection = 0) {
		
		if (self::$db == NULL) {
			$db = new mypdo();
			self::$db = $db;
		}
		
		if ($idSelection != 0) {
			selection::$db = self::$db;
			$this->selection = new selection($idSelection);
		}		
		$this->fpdi = new myfpdi();
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
* permet de définir le titre à afficher sur la couverture
*
* @param string $titre le titre à afficher
* @param int $x sa position x dans la page
* @param int $y sa position y dans la page
*/		
	
	public function SetTitre($titre,$x = 0,$y = 0) {
		if ($titre != '') {
			$this->titre = $titre;
			if ($x != 0)
				$this->titreXY['x'] = $x;
			if ($y != 0)
				$this->titreXY['y'] = $y;
		}
	}
	

/**
* permet de générer la page PDF d'une formation.
* 
* @param int $formationFromSelection l'id de la formation
*/		
	
	public function addPageFormation($formationFromSelection) {
		if ($formationFromSelection != NULL) {
			$this->fpdi->AddPage();
			if (isset($this->modeleFormation['fichier_element'])) {
				$this->fpdi->setSourceFile($this->modeleFormation['fichier_element']);
				$tplidx = $this->fpdi->ImportPage(1); 
				$this->fpdi->useTemplate($tplidx);
			}
			
			$formation = new formation($formationFromSelection);	
			$criteres = $formation->getCriteres();
			$famillesCriteres = catalogue::getAllFamillesCriteres();		
			$border = 0;
			
			
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
		$query = "SELECT nom_element,commentaire,texte_sommaire,fichier_element,nb_pages,pdf_categorie_id FROM pdf_elements WHERE id_element = ".(int)$idElement;
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
							$this->fpdi->SetXY($this->titreXY['x'],$this->titreXY['y']);
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
}