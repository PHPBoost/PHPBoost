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
		parent::__construct(true, false);
	}
	
	 /**
	 * @method Return the search form.
	 * @param string[] $args (optional) Search arguments
	 */
	public function get_search_form($args = null)
	{
		//Load module lang
		$lang = LangLoader::get('bugtracker_common', 'bugtracker');
		
		//Creation of the template
		$tpl = new FileTemplate('bugtracker/BugtrackerSearchForm.tpl');
		$tpl->add_lang($lang);
		
		$where = !empty($args['BugtrackerWhere']) ? TextHelper::strprotect($args['BugtrackerWhere']) : 'all';
		
		$tpl->put_all(Array(
			'IS_TITLE_CHECKED' 		=> $where == 'title' ? ' checked="checked"' : '' ,
			'IS_CONTENTS_CHECKED' 	=> $where == 'contents' ? ' checked="checked"' : '' ,
			'IS_ALL_CHECKED' 		=> $where == 'all' ? ' checked="checked"' : ''
		));
		
		return $tpl->render();
	}
	
	 /**
	 *  @method Get the args list of the <get_search_args> method
	 */
	public function get_search_args()
	{
		return Array('BugtrackerWhere');
	}
	
	 /**
	 * @method Return the search request.
	 * @param string[] $args Search arguments
	 */
	public function get_search_request($args)
	{
		$where = !empty($args['BugtrackerWhere']) ? TextHelper::strprotect($args['BugtrackerWhere']) : 'all';
		
		if ($where == 'all')
			$request = "SELECT ".
			$args['id_search']." AS `id_search`,
			id AS `id_content`,
			title,
			( 2 * FT_SEARCH_RELEVANCE(title, '".$args['search']."') +
			FT_SEARCH_RELEVANCE(contents, '".$args['search']."') ) / 3 AS `relevance`,
			CONCAT('" . PATH_TO_ROOT . "/bugtracker/index.php?url=/detail/',id) AS `link`
			FROM " . PREFIX . "bugtracker
			WHERE ( FT_SEARCH(title, '".$args['search']."') OR MATCH(contents, '".$args['search']."') )
			ORDER BY relevance DESC " . $this->sql_querier->limit(0, BugtrackerConfig::BUGTRACKER_MAX_SEARCH_RESULTS);
		if ($where == 'contents')
			$request = "SELECT ".
			$args['id_search']." AS `id_search`,
			id AS `id_content`,
			title,
			FT_SEARCH_RELEVANCE(contents, '".$args['search']."') AS `relevance`,
			CONCAT('" . PATH_TO_ROOT . "/bugtracker/index.php?url=/detail/',id) AS `link`
			FROM " . PREFIX . "bugtracker
			WHERE FT_SEARCH(contents, '".$args['search']."')
			ORDER BY relevance DESC " . $this->sql_querier->limit(0, BugtrackerConfig::BUGTRACKER_MAX_SEARCH_RESULTS);
		else
			$request = "SELECT ".
			$args['id_search']." AS `id_search`,
			id AS `id_content`,
			title,
			(FT_SEARCH_RELEVANCE(title, '".$args['search']."')) AS `relevance`,
			CONCAT('" . PATH_TO_ROOT . "/bugtracker/index.php?url=/detail/',id) AS `link`
			FROM " . PREFIX . "bugtracker
			WHERE FT_SEARCH(title, '".$args['search']."')
			ORDER BY relevance DESC " . $this->sql_querier->limit(0, BugtrackerConfig::BUGTRACKER_MAX_SEARCH_RESULTS);
		
		return $request;
	}
}
?>
