<?php
/*##################################################
 *                              calendar_interface.class.php
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

if (defined('PHPBOOST') !== true) exit;

// Inclusion du fichier contenant la classe ModuleInterface
import('modules/module_interface');

// Classe ForumInterface qui hérite de la classe ModuleInterface
class CalendarInterface extends ModuleInterface
{
    ## Public Methods ##
    function CalendarInterface() //Constructeur de la classe ForumInterface
    {
        parent::__construct('calendar');
    }
    
	//Récupération du cache.
	function get_cache()
	{
		$code = 'global $CONFIG_CALENDAR;' . "\n";
			
		//Récupération du tableau linéarisé dans la bdd.
		$CONFIG_CALENDAR = unserialize($this->sql_querier->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'calendar'", __LINE__, __FILE__));
		$CONFIG_CALENDAR = is_array($CONFIG_CALENDAR) ? $CONFIG_CALENDAR : array();
		
		$code .= '$CONFIG_CALENDAR = ' . var_export($CONFIG_CALENDAR, true) . ';' . "\n";
		
		return $code;
	}

	//Actions journalière.
	/*
	function on_changeday()
	{
	}
	*/
}

?>