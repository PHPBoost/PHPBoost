<?php
/**
*
* panel_begin.php
*
* @author              alain091
* @copyright        (C) 2008-2009 Alain Gandon
* @license             GPL
*
*/

defined('PHPBOOST') OR die('PHPBoost non installé');
	
load_module_lang('panel'); //Chargement de la langue du module.

$Bread_crumb->add($LANG['title_panel'], 'panel.php');
define('TITLE', $LANG['title_panel']);

$locations = array (10 => 'top', 20 => 'aboveleft', 30 => 'aboveright', 40 => 'center', 50 => 'belowleft', 60 => 'belowright', 70 => 'bottom');

