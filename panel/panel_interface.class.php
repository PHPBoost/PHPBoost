<?php
/**
 *                              panel_interface.class.php
 *                            -------------------
 *   begin                : October 14, 2008
 *   copyright         : (C) 2008 Alain GANDON
 *   email                : 
 *
 *
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 **/

if( defined('PHPBOOST') !== true) exit;

// Inclusion du fichier contenant la classe ModuleInterface
require_once(PATH_TO_ROOT . '/kernel/framework/modules/module_interface.class.php');

// Classe qui hérite de la classe ModuleInterface
class PanelInterface extends ModuleInterface
{
    ## Public Methods ##
    /**
	* @method  Constructeur de l'objet
	*/
    function PanelInterface() //Constructeur de la classe
    {
        parent::ModuleInterface('panel');
    }
    
    /**
	*  @method  Mise à jour du cache
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