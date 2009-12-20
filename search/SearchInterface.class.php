<?php
/*##################################################
 *                              search_interface.class.php
 *                            -------------------
 *   begin                : July 7, 2008
 *   copyright            : (C) 2008 Régis Viarre
 *   email                : crowkait@phpboost.com
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

// Inclusion du fichier contenant la classe ModuleInterface


// Classe ForumInterface qui hérite de la classe ModuleInterface
class SearchInterface extends ModuleInterface
{
    ## Public Methods ##
    function SearchInterface() //Constructeur de la classe ForumInterface
    {
        parent::__construct('search');
    }
    
    //Récupération du cache.
	function get_cache()
	{
		//Configuration
		$search_config = unserialize($this->sql_querier->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'search'", __LINE__, __FILE__));
		
		return 'global $SEARCH_CONFIG;' . "\n" . '$SEARCH_CONFIG = '.var_export($search_config, true).';';	
	}

	//Actions journalières
	function on_changeday()
	{
		// Délestage du cache des recherches
		$this->sql_querier->query_inject("TRUNCATE " . PREFIX . "search_results", __LINE__, __FILE__);
		$this->sql_querier->query_inject("TRUNCATE " . PREFIX . "search_index", __LINE__, __FILE__);
	}
}

?>