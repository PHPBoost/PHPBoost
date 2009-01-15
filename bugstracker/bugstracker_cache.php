<?php
/**
 *                                bugstracker_cache.php
 * 
 * @package     Bugstracker
 * @author         alain91
 * @copyright   (c) 2008-2009 Alain Gandon
 * @license        GPL
 *  
 */

defined('PHPBOOST') or die('PHPBoost non installé');

//Configuration des news
function generate_module_file_bugstracker()
{
	global $sql;

	$config_lines = 'global $CONFIG_BUGS;' . "\n";
	
	//Récupération du tableau linéarisé dans la bdd.
	$CONFIG_BUGS = unserialize($sql->query("SELECT value FROM ".PREFIX."configs WHERE name = 'bugstracker'", __LINE__, __FILE__));
	$CONFIG_BUGS = is_array($CONFIG_BUGS) ? $CONFIG_BUGS : array();
	foreach($CONFIG_BUGS as $key => $value)
		$config_lines .= '$CONFIG_BUGS[\'' . $key . '\'] = ' . var_export($value, true) . ';' . "\n";
	
	return $config_lines;
}

?>