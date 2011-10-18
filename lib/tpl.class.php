<?php
/** 
 * Fichier contenant la classe tpl
 * 
 * 
 * @package lib 
 * @author Romain BOURDON <romain@ayeba.fr> 
 */


/** 
 * La classe tpl permet de charger des gabarits d'affichage (templates)
 * et d'y remplacer des variables par des valeurs
 * les varaibles doivent être de la forme {variable}
 * @package lib 
 * @author Romain BOURDON <romain@ayeba.fr> 
 */

class tpl {
	
	private $content = '';
	

/**
* constructeur
*
* @param string $pathToTpl le nom (chemin inclus) du gabarit à charger
*/	
	public function __construct($pathToTpl = '') {
		if ($pathToTpl != '') {
			if ($content = file_get_contents($pathToTpl)) {
				$this->content = $content;
			}
			else {
				throw new Exception('file not found', 'fnf');
			}
		}
		
	}

/**
* permet d'ajouter du contenu à la fin au gabarit courant
*
* @param string $fileToAdd le nom du fichier à ajouter
* 
*/	
	
	public function appendTpl($fileToAdd = '') {
		if ($fileToAdd != '') {
			if ($content = file_get_contents($fileToAdd)) {
				$this->content .= $this->content;
			}
			else {
				throw new Exception('file not found', 'fnf');
			}
		}
	}
	

/**
* permet d'ajouter du contenu au début du gabarit courant
*
* @param string $fileToAdd le nom du fichier à ajouter
* 
*/	
	
	public function prependTpl($fileToAdd = '') {
		if ($fileToAdd != '') {
			if ($content = file_get_contents($fileToAdd)) {
				$this->content = $content.$this->content;
			}
			else {
				throw new Exception('file not found', 'fnf');
			}
		}
	}

	
	
/**
* permet de remplacer une variable par sa valeur au sein du gabarit
*
* @param string $name le nom de la variable à rechercher (sans les {})
* @param string $value le contenu qui remplace la variable
* 
*/		
	
	public function loadVar($name,$value) {
		$result = str_replace('{'.$name.'}',$value,$this->content);
		$this->content = $result;
	}
	
/**
* méthode qui envoie le gabarit traité vers la sortie standard
* elle nettoie au préalable toutes les variables qui n'ont pas été remplacées
* 
* 
*/	
	
	public function output() {
	$result = preg_replace('%\{(.)*\}%','',$this->content);
	echo $result;
	}

	
/**
* méthode qui renvoie le contenu actuel du gabarit
* @return string le contenu du gabarit
* 
*/	
	
	
	public function getContent() {
		return $this->content;
	}
	
}

