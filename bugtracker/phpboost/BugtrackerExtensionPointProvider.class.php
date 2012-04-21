<?php
/*##################################################
 *                              BugtrackerExtensionPointProvider.class.php
 *                            -------------------
 *   begin                : April 16, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
 *
 *  
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/
 
define('BUGTRACKER_MAX_SEARCH_RESULTS', 50);

class BugtrackerExtensionPointProvider extends ExtensionPointProvider
{
    public function __construct() //Constructeur de la classe
    {
		$this->sql_querier = PersistenceContext::get_sql();
        parent::__construct('bugtracker');
    }
	
	//Deprecated
	function get_cache()
	{
		$config_bugtracker = unserialize($this->sql_querier->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'module-bugtracker-config'", __LINE__, __FILE__));

		$string = 'global $CONFIG_BUGTRACKER;' . "\n\n" . '$CONFIG_BUGTRACKER = array();' . "\n\n";
		$string .= '$CONFIG_BUGTRACKER = ' . var_export($config_bugtracker, true) . ';' . "\n\n";

		return $string;
	}

	public function home_page()
	{
		return new BugtrackerHomePageExtensionPoint();
	}
	
	public function search()
	{
		return new BugtrackerSearchable();
	}
}
?>
