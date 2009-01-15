<?php
/**
 * bugstracker_interface.class.php
 * 
 * @package      Bugstracker
 * @author         alain91
 * @copyright   (c) 2008-2009 Alain Gandon
 * @license        GPL
 *
 */

defined('PHPBOOST') or die('PHPBoost non installé');
 
import('modules/module_interface');

class BugstrackerInterface extends ModuleInterface
{

	/**
	* @method Constructor
	*/
    function BugstrackerInterface()
    {
        parent::ModuleInterface('bugstracker');
    }
    
	/**
	* @method Recuperation du cache
	*/
	function get_cache()
	{
		global $Sql;
	
		//Configuration
		$config = unserialize($Sql->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'bugstracker'", __LINE__, __FILE__));

		$string = 'global $CONFIG_BUGS;' . "\n\n";
		$string .= '$CONFIG_BUGS = ' . var_export($config, true) . ';' . "\n\n";
			
		return $string;
	}
}

?>
