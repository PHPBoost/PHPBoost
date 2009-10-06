<?php
/*##################################################
 *                        shoutbox_interface.class.php
 *                            -------------------
 *   begin                : July 7, 2008
 *   copyright            : (C) 2008 Régis Viarre
 *   email                : crowkait@phpboost.com
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
###################################################*/

// Inclusion du fichier contenant la classe ModuleInterface
import('modules/module_interface');

// Classe ForumInterface qui hérite de la classe ModuleInterface
class ShoutboxInterface extends ModuleInterface
{
    ## Public Methods ##
    function ShoutboxInterface() //Constructeur de la classe ForumInterface
    {
        parent::__construct('shoutbox');
    }
    
    //Récupération du cache.
	function get_cache()
	{
		$shoutbox_config = 'global $CONFIG_SHOUTBOX;' . "\n";
			
		//Récupération du tableau linéarisé dans la bdd.
		$CONFIG_SHOUTBOX = unserialize($this->sql_querier->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'shoutbox'", __LINE__, __FILE__));
		$CONFIG_SHOUTBOX = is_array($CONFIG_SHOUTBOX) ? $CONFIG_SHOUTBOX : array();
		
		if (isset($CONFIG_SHOUTBOX['shoutbox_forbidden_tags']))
			$CONFIG_SHOUTBOX['shoutbox_forbidden_tags'] = unserialize($CONFIG_SHOUTBOX['shoutbox_forbidden_tags']);
		
		$shoutbox_config .= '$CONFIG_SHOUTBOX = ' . var_export($CONFIG_SHOUTBOX, true) . ';' . "\n";
		
		return $shoutbox_config;
	}

	//Actions journalière.
	function on_changeday()
	{
		global $Cache, $CONFIG_SHOUTBOX;
		
		$Cache->load('shoutbox'); //$CONFIG_SHOUTBOX en global.

		if ($CONFIG_SHOUTBOX['shoutbox_max_msg'] != -1)
		{
			//Suppression des messages en surplus dans la shoutbox.
			$this->sql_querier->query_inject("SELECT @compt := id AS compt
			FROM " . PREFIX . "shoutbox
			ORDER BY id DESC
			" . $this->sql_querier->limit(0, $CONFIG_SHOUTBOX['shoutbox_max_msg']), __LINE__, __FILE__);
			$this->sql_querier->query_inject("DELETE FROM " . PREFIX . "shoutbox WHERE id < @compt", __LINE__, __FILE__);
		}
	}
}

?>