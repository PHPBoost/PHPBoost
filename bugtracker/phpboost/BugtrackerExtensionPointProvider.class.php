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
	
	/**
	* @method Recuperation du cache
	*/
	function get_cache()
	{
		global $Sql;
		
		$config_bugs = 'global $BUGS_CONFIG;' . "\n";
		
		//Récupération du tableau linéarisé dans la bdd.
		$BUGS_CONFIG = unserialize($Sql->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'bugtracker'", __LINE__, __FILE__));
		$BUGS_CONFIG = is_array($BUGS_CONFIG) ? $BUGS_CONFIG : array();
		
		$config_bugs .= '$BUGS_CONFIG = ' . var_export($BUGS_CONFIG, true) . ';' . "\n\n";
		
		return $config_bugs;	
	}
	
	// Recherche
	/**
	 *  Renvoie le formulaire de recherche du bugtracker
	 */
	function get_search_form($args = null)
	{
		global $Cache, $User, $Errorh, $LANG, $BUGS_CONFIG;
		
		require_once(PATH_TO_ROOT . '/kernel/begin.php');
		include_once('bugtracker_constants.php');
		load_module_lang('bugtracker');
		$Cache->load('bugtracker');
		
		//Autorisation sur le module.
		if (!$User->check_auth($BUGS_CONFIG['auth'], BUG_READ_AUTH_BIT)) //Accès non autorisé!
			$Errorh->handler('e_auth', E_USER_REDIRECT);
		
		import('io/template');
		$Tpl = new Template('bugtracker/bugtracker_search_form.tpl');
		
		if ( !isset($args['BugtrackerWhere']) || !in_array($args['BugtrackerWhere'], array('title', 'contents', 'all')) )
			$args['BugtrackerWhere'] = 'all';

		$Tpl->assign_vars(Array(
			'L_WHERE' 				=> $LANG['bugs.search.where'],
			'IS_TITLE_CHECKED' 		=> $args['BugtrackerWhere'] == 'title' ? ' checked="checked"' : '' ,
			'IS_CONTENTS_CHECKED' 	=> $args['BugtrackerWhere'] == 'contents' ? ' checked="checked"' : '' ,
			'IS_ALL_CHECKED' 		=> $args['BugtrackerWhere'] == 'all' ? ' checked="checked"' : '' ,
			'L_TITLE' 				=> $LANG['bugs.search.where.title'],
			'L_CONTENTS' 			=> $LANG['bugs.search.where.contents']
		));
		
		return $Tpl->parse(TEMPLATE_STRING_MODE);
	}
	
	/**
	 *  Renvoie la liste des arguments de la méthode <GetSearchRequest>
	 */
	function get_search_args()
	{
		return Array('BugtrackerWhere');
	}
	
	/**
	 *  Renvoie la requête de recherche dans le bugtracker
	 */
	function get_search_request($args = null)
	{
		global $Sql;
		
		import('util/date');
		$now = new Date(DATE_NOW, TIMEZONE_AUTO);
		
		$weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;
		if ( !isset($args['BugtrackerWhere']) || !in_array($args['BugtrackerWhere'], array('title', 'contents', 'all')) )
			$args['BugtrackerWhere'] = 'all';
		
		if ( $args['BugtrackerWhere'] == 'all' )
			$req = "SELECT ".
			$args['id_search']." AS `id_search`,
			id AS `id_content`,	title, contents,
			( 4 * MATCH(title) AGAINST('".$args['search']."') + MATCH(contents) AGAINST('".$args['search']."') ) / 5 * " . $weight . " AS `relevance`,
			CONCAT('" . PATH_TO_ROOT . "/bugtracker/bugtracker.php?view=true&amp;id=',id) AS `link`
			FROM " . PREFIX . "bugtracker
			WHERE ( MATCH(title) AGAINST('".$args['search']."') OR MATCH(contents) AGAINST('".$args['search']."') )" .
			"ORDER BY relevance DESC" . $Sql->limit(0, BUGTRACKER_MAX_SEARCH_RESULTS);
		if ( $args['BugtrackerWhere'] == 'contents' )
			$req = "SELECT ".
			$args['id_search']." AS `id_search`,
			id AS `id_content`, title, contents,
			MATCH(contents) AGAINST('".$args['search']."') * " . $weight . " AS `relevance`,
			CONCAT('" . PATH_TO_ROOT . "/bugtracker/bugtracker.php?view=true&amp;id=',id) AS `link`
			FROM " . PREFIX . "bugtracker
			WHERE MATCH(contents) AGAINST('".$args['search']."')" .
			"ORDER BY relevance DESC" . $Sql->limit(0, BUGTRACKER_MAX_SEARCH_RESULTS);
		else
			$req = "SELECT ".
			$args['id_search']." AS `id_search`,
			id AS `id_content`,	title,
			((MATCH(title) AGAINST('".$args['search']."') )* " . $weight . ") AS `relevance`,
			CONCAT('" . PATH_TO_ROOT . "/bugtracker/bugtracker.php?view=true&amp;id=',id) AS `link`
			FROM " . PREFIX . "bugtracker
			WHERE MATCH(title) AGAINST('".$args['search']."')" .
			"ORDER BY relevance DESC" . $Sql->limit(0, BUGTRACKER_MAX_SEARCH_RESULTS);

		return $req;
	}

	public function home_page()
	{
		return new BugtrackerHomePageExtensionPoint();
	}
	
}
?>
