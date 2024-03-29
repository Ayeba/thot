<?php
/** 
 * Fichier contenant les fonctions de gestion de formulaire
 * 
 * 
 * @package inc 
 * @author Romain BOURDON <romain@ayeba.fr> 
 */



/**
* formate une liste d'entrées pour les afficher sous la forme d'un select multiple
* si $selected est renseigné, les entrée correspondantes sont pré-selectionnées
*
* @param array $lignes un tableau contenant les id et les noms à afficher
* @param string $name le nom du select généré
* @param array $selected optionnel, un tableau cotenant les entrées qui doivent être pré-selectionnées
* @return bool le résultat de la fonction mail()
*/

function genMultSelect($lignes,$name,$selected=NULL) {
	$formated = '';
	foreach ($lignes as $id=>$nom) {
		if ($selected != NULL AND in_array($id,$selected))
			$formated .= '<option value=\''.$id.'\' selected>'.$nom.'</option>';
		else
			$formated .= '<option value=\''.$id.'\'>'.$nom.'</option>';
	}
	return '<select name="'.$name.'" multiple="multiple" >'.$formated.'</select>';
}



function genCheckBox($aff,$value,$name,$checked=0) {
	if ($checked == 1)
		$checked = 'checked="checked"';
	else
		$checked = '';
	return '<div><input type="checkbox" name="'.$name.'" value="'.$value.'" '.$checked.' /> '.$aff.'</div>';
}

function genCheckBoxFormation($aff,$value,$name,$checked=0) {
	if ($checked == 1)
		$checked = 'checked="checked"';
	else
		$checked = '';
	return '<div><input type="checkbox" name="'.$name.'" value="'.$value.'" '.$checked.' /> <a href="show_formation.php?id='.$value.'">'.$aff.'</a></div>';
}


function genMultCheckBox($lignes,$name,$selected=NULL) {
	$formated = '';
	foreach ($lignes as $id=>$nom) {
		if ($selected != NULL AND in_array($id,$selected))
			$formated .= genCheckBox($nom,$id,$name,1);
		else
			$formated .= genCheckBox($nom,$id,$name,0);
	}
	return $formated;
}

function genSelect($list,$name,$selected=NULL) {
	$formated = '<select name="'.$name.'">';
	foreach ($list as $id=>$name) {
		if ($id == $selected)
			$affSelected = 'selected';
		else 
			$affSelected = '';
		$formated .= '<option value="'.$id.'" '.$affSelected.'>'.$name.'</option>';
	}
	$formated .='</select>';
	return $formated;
}

function genSelectStatus($list,$selected = NULL) {
	$formated = '<select name="status">';
	$formated .= '<option value="-1">toutes</option>';
	foreach ($formationStatus as $id=>$name) {
		if ($id == $selected)
			$affSelected = 'selected';
		else 
			$affSelected = '';
		$formated .= '<option value="'.$id.'" '.$affSelected.'>'.$name.'</option>';
	}
	$formated .='</select>';
	return $formated;
}


