<?php
/*##################################################
 *                              BugtrackerSearchable.class.php
 *                            -------------------
 *   begin                : April 27, 2012
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

class BugtrackerSearchable extends AbstractSearchableExtensionPoint
{
	private $sql_querier;

	public function __construct()
	{
		$this->sql_querier = PersistenceContext::get_sql();
		parent::__construct(true, true);
	}
	
	public function get_search_form($args=null)
	/**
	 *  Renvoie le formulaire de recherche du forum
	 */
	{
		global $User, $Cache, $LANG;

		$Tpl = new FileTemplate('bugtracker/bugtracker_search_form.tpl');
		
		require_once(PATH_TO_ROOT . '/kernel/begin.php');
		load_module_lang('bugtracker');
		$Cache->load('bugtracker');
		$bugtracker_config = BugtrackerConfig::load();
		
		//Récupération des paramètres de configuration
		$authorizations = $bugtracker_config->get_authorizations();
		
		//Autorisation sur le module.
		if (!$User->check_auth($authorizations, BugtrackerConfig::BUG_READ_AUTH_BIT)) //Accès non autorisé!
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
		
		$search = $args['search'];
		$where = !empty($args['BugtrackerWhere']) ? TextHelper::strprotect($args['BugtrackerWhere']) : 'all';
		$colorate_result = !empty($args['BugtrackerColorate_result']) ? true : false;
		
		$Tpl->put_all(Array(
			'L_WHERE' 				=> $LANG['bugs.search.where'],
			'IS_TITLE_CHECKED' 		=> $where == 'title' ? ' checked="checked"' : '' ,
			'IS_CONTENTS_CHECKED' 	=> $where == 'contents' ? ' checked="checked"' : '' ,
			'IS_ALL_CHECKED' 		=> $where == 'all' ? ' checked="checked"' : '' ,
			'L_TITLE' 				=> $LANG['bugs.search.where.title'],
			'L_CONTENTS' 			=> $LANG['bugs.search.where.contents']
		));
		
		return $Tpl->render();
	}

	public function get_search_args()
	/**
	 *  Renvoie la liste des arguments de la méthode <get_search_args>
	 */
	{
		return Array('BugtrackerWhere');
	}
	
	public function get_search_request($args)
	/**
	*  Renvoie la requete de recherche
	*/
	{
		global $Cache;
		
		$Cache->load('bugtracker');
		
		$weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;
		
		$where = !empty($args['BugtrackerWhere']) ? TextHelper::strprotect($args['BugtrackerWhere']) : 'all';
		
		if ( $where == 'all' )
			$request = "SELECT ".
			$args['id_search']." AS `id_search`,
			id AS `id_content`,
			title,
			( 4 * FT_SEARCH_RELEVANCE(title, '".$args['search']."') +
			FT_SEARCH_RELEVANCE(contents, '".$args['search']."') ) / 5 * " . $weight . " AS `relevance`,
			CONCAT('" . PATH_TO_ROOT . "/bugtracker/bugtracker.php?view&amp;id=',id) AS `link`
			FROM " . PREFIX . "bugtracker
			WHERE ( FT_SEARCH(title, '".$args['search']."') OR MATCH(contents, '".$args['search']."') )
			ORDER BY relevance DESC " . $this->sql_querier->limit(0, BUGTRACKER_MAX_SEARCH_RESULTS);
		if ( $where == 'contents' )
			$request = "SELECT ".
			$args['id_search']." AS `id_search`,
			id AS `id_content`,
			title,
			FT_SEARCH_RELEVANCE(contents, '".$args['search']."') * " . $weight . " AS `relevance`,
			CONCAT('" . PATH_TO_ROOT . "/bugtracker/bugtracker.php?view&amp;id=',id) AS `link`
			FROM " . PREFIX . "bugtracker
			WHERE FT_SEARCH(contents, '".$args['search']."')
			ORDER BY relevance DESC " . $this->sql_querier->limit(0, BUGTRACKER_MAX_SEARCH_RESULTS);
		else
			$request = "SELECT ".
			$args['id_search']." AS `id_search`,
			id AS `id_content`,
			title,
			((FT_SEARCH_RELEVANCE(title, '".$args['search']."') )* " . $weight . ") AS `relevance`,
			CONCAT('" . PATH_TO_ROOT . "/bugtracker/bugtracker.php?view&amp;id=',id) AS `link`
			FROM " . PREFIX . "bugtracker
			WHERE FT_SEARCH(title, '".$args['search']."')
			ORDER BY relevance DESC " . $this->sql_querier->limit(0, BUGTRACKER_MAX_SEARCH_RESULTS);
		
		return $request;
	}
}
?>
