<?php

/** 
 * fichier de configuration de l'application
 * 
 * 
 * @package  
 * @author 
 */
 
    
    define('SQL_SERVER','localhost');
    define('SQL_DB','formation');
    define('SQL_USER','root');
    define('SQL_PASS','root');
    

   ini_set('display_errors','on');
   
   
   $formationStatus[0] = 'brouillon';
   $formationStatus[1] = 'en attente de validation';
   $formationStatus[2] = 'publi&eacute;e';
   