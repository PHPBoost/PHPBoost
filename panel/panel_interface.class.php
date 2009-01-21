<?php
/**
 *   panel_interface.class.php
 *
 *  @copyright (C) 2008 Alain GANDON
 *  @email  alain091@gmail.com 
 * @license GPL
 *
 */

if( defined('PHPBOOST') !== true) exit;

import('modules/module_interface');

class PanelInterface extends ModuleInterface
{
    /**
	* @method  Constructeur de l'objet
	*/
    function PanelInterface()
    {
        parent::ModuleInterface('panel');
    }
    
    /**
	*  @method  Recuperation du cache
	*/
	function get_cache()
	{
		global $Sql;
		
		$panel_code = 'global $CONFIG_PANEL;' . "\n";
			
		//Récupération du tableau linéarisé dans la bdd.
		$CONFIG_PANEL = sunserialize($Sql->query("SELECT value FROM ".PREFIX."configs WHERE name = 'panel'", __LINE__, __FILE__));
		$CONFIG_PANEL = is_array($CONFIG_PANEL) ? $CONFIG_PANEL : array();
		ksort($CONFIG_PANEL);
		
		$panel_code .= '$CONFIG_PANEL = ' . var_export($CONFIG_PANEL, true) . ';' . "\n";
		
		return $panel_code;
	}

}

?>