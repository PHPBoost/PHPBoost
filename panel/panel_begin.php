<?php
/**
*
* panel_begin.php
*
* @author              alain091
* @copyright        (C) 2008 Alain GANDON based on Guestbook_begin
* @license             GPL
*
*/

defined('PHPBOOST') OR die('PHPBoost non installé');
	
load_module_lang('panel'); //Chargement de la langue du module.

$Bread_crumb->add($LANG['title_panel'], 'panel.php');
define('TITLE', $LANG['title_panel']);

?>