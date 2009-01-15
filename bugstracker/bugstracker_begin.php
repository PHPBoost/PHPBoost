<?php
/**
 * bugtracker_begin.php
 * 
 * @package      Bugstracker
 * @author         alain91
 * @copyright   (c) 2008-2009 Alain Gandon
 * @license        GPL
 *
 */
 
defined('PHPBOOST') or die('PHPBoost non installé');

define('ITEMS_PER_PAGE',	10);
define('MAX_LINKS',			3);

define('PARAM_SEVERITY',	10);
define('PARAM_STATUS',		20);
define('PARAM_COMPONENT',	30);
define('PARAM_TARGET',		40);

define('CREATE_ACCESS',		0x1);
define('MODIFY_ACCESS',		0x2);
define('DELETE_ACCESS',		0x4);
define('COMMENT_ACCESS',	0X8);
define('LIST_ACCESS',		0x10);
define('VIEW_ACCESS',		0x20);

$Cache->load('bugstracker');
load_module_lang('bugstracker'); //Chargement de la langue du module.

$Bread_crumb->add($LANG['module_title'], 'bugstracker.php');
define('TITLE', $LANG['module_title']);

class Lang
{
	function get($value)
	{
		global $LANG;
		
		if (is_string($value)) {
			if (!empty($LANG[$value]))
				return $LANG[$value];
			return $value;
		}
		return 'invalid_value';
	}
	
}
?>