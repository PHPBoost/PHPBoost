<?php
/*##################################################
 *                        SearchExtensionPointProvider.class.php
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



class SearchExtensionPointProvider extends ExtensionPointProvider
{
	/**
	 * @var CommonQuery
	 */
	private $querier;

	public function __construct()
	{
		$this->sql_querier = AppContext::get_sql_common_query();
		parent::__construct('search');
	}

	function get_cache()
	{
		$result = $this->querier->select_single_row(DB_TABLE_CONFIGS, array('value'), 'WHERE name=\'search\'');
		$search_config = unserialize($result['value']);
		return 'global $SEARCH_CONFIG;' . "\n" . '$SEARCH_CONFIG = '.var_export($search_config, true) . ';';
	}

	function on_changeday()
	{
		$this->querier->truncate(PREFIX . 'search_results');
		$this->querier->truncate(PREFIX . 'search_index');
	}
}

?>