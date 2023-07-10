<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 07 10
 * @since       PHPBoost 2.0 - 2008 02 01
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

define('CACHE_TIME', SearchConfig::load()->get_cache_lifetime());
define('CACHE_TIMES_USED', SearchConfig::load()->get_cache_max_uses());

class Search
{
	public $id_search = array();
	private $cache;
	private $search;
	private $modules;
	private $modules_conditions;
	private $id_user;
	private $errors;
	private $db_querier;

	//----------------------------------------------------------------- PUBLIC
	//----------------------------------------------------------- Constructors

	/**
	 * @desc Builds a search object.
	 * Query Complexity: 6 + k / 10 database queries. (k represent the number of
	 * module without search cache)
	 * @param string $search the string to search
	 * @param mixed[] $modules Modules in which we gonna search with their search params.
	 * This argument is an array which keys are module id's and values are arrays
	 * containing the specialized search arguments for a particular module.
	 */
	public function __construct($search = '', $modules = array())
	{
		$this->errors = 0;
		$this->search = md5($search); // Generating a search id;
		$this->modules = $modules;
		$this->id_search = array();
		$this->cache = array();

		$this->id_user = AppContext::get_current_user()->get_id();
		$this->modules_conditions = $this->get_modules_conditions($this->modules);

		$this->db_querier = PersistenceContext::get_querier();

		// Deletes old results from cache

		// Here, 3 queries in order to avoid a multi-table delete which is not portable
		// or 2 queries with a NOT IN (a bit long)

		// Lists old results to delete
		$nbIdsToDelete = 0;
		$idsToDelete = array();
		$result = $this->db_querier->select('SELECT id_search
		FROM ' . SearchSetup::$search_index_table . '
		WHERE last_search_use <= :time OR times_used >= :times_used', array(
			'time' => (time() - (CACHE_TIME * 60)),
			'times_used' => CACHE_TIMES_USED
		));
		while ($row = $result->fetch())
		{
			$idsToDelete[] = $row['id_search'];
			$nbIdsToDelete++;
		}
		$result->dispose();

		// Deletes old results
		if ($nbIdsToDelete > 0)
		{
			$this->db_querier->delete(SearchSetup::$search_index_table, 'WHERE id_search IN :ids_list', array('ids_list' => $idsToDelete));
			$this->db_querier->delete(SearchSetup::$search_results_table, 'WHERE id_search IN :ids_list', array('ids_list' => $idsToDelete));
		}

		// Don't compute anything if no text is searched
		// (useful for based-id searches)
		if ($this->search != '')
		{
			// Checks cache results meta-inf
			$result = $this->db_querier->select("SELECT id_search, module
			FROM " . SearchSetup::$search_index_table . "
			WHERE search=:search AND id_user=:id_user" . ($this->modules_conditions != '' ? " AND " . $this->modules_conditions : ''), array(
				'search' => $this->search,
				'id_user' => $this->id_user
			));
			while ($row = $result->fetch())
			{   // retrieves cache result meta-inf
				array_push($this->cache, $row['module']);
				$this->id_search[$row['module']] = $row['id_search'];
			}
			$result->dispose();

			// Updates cache results meta-inf
			if (count($this->id_search) > 0)
			{
				$this->db_querier->inject("UPDATE " . SearchSetup::$search_index_table . " SET times_used=times_used+1, last_search_use='" . time() . "' WHERE id_search IN (" . implode(',', $this->id_search) . ");");
			}

			// Adds modules missing in cache
			if (count($modules) > count($this->cache))
			{
				$nbReqInsert = 0;
				$reqInsert = '';

				foreach ($modules as $module_name => $options)
				{
					if (!$this->is_in_cache($module_name) && !isset($this->id_search[$module_name]))
					{
						$reqInsert .= "('" . $this->id_user . "','" . $module_name . "','" . $this->search . "','" . md5(implode('|', $options)) . "','" . time() . "', '0'),";
						// Executes 10 insertions
						if ($nbReqInsert == 10)
						{
							$reqInsert = "INSERT INTO " . SearchSetup::$search_index_table .
								" (id_user, module, search, options, last_search_use, times_used) VALUES " . rtrim($reqInsert, ',');
							$this->db_querier->inject($reqInsert);
							$reqInsert = '';
							$nbReqInsert = 0;
						}
						else
						{
							$nbReqInsert++;
						}
					}
				}

				// Executes last insertions queries
				if ($nbReqInsert > 0)
				{
					$this->db_querier->inject("INSERT INTO " . SearchSetup::$search_index_table . " (id_user, module, search, options, last_search_use, times_used) VALUES " . TextHelper::substr($reqInsert, 0, TextHelper::strlen($reqInsert) - 1) . "");
				}

				// Checks and retrieves cache meta-informations
				$result = $this->db_querier->select("SELECT id_search, module
				FROM " . SearchSetup::$search_index_table . "
				WHERE search=:search AND id_user=:id_user" . ($this->modules_conditions != '' ? " AND " . $this->modules_conditions : ''), array(
					'search' => $this->search,
					'id_user' => $this->id_user
				));
				while ($row = $result->fetch())
				{   // Ajout des résultats s'ils font partie de la liste des modules à traiter
					$this->id_search[$row['module']] = $row['id_search'];
				}
				$result->dispose();
			}
		}
	}


	/**
	 * @desc Puts results from the search results identified by the $id_search parameter
	 * in the $results parameter and returns the number of results.
	 * Query complexity: 2 queries.
	 * @param string[] &$results the results returned
	 * @param int $id_search the search id
	 * @param int $nb_lines the number of lines to return
	 * @param int $offset the offset from which return results
	 * @return int The number of results
	 */
	public function get_results_by_id(&$results, $id_search = 0, $nb_lines = 0, $offset = 0)
	{
		$results = array();

		// Building request
		$reqResults = "SELECT module, id_content, title, relevance, link
						FROM " . SearchSetup::$search_index_table . " idx, " . SearchSetup::$search_results_table . " rst
						WHERE idx.id_search = '" . $id_search . "' AND rst.id_search = '" . $id_search . "'
						AND id_user = '".$this->id_user."' ORDER BY relevance DESC ";
		if ($nb_lines > 0)
		{
			$reqResults .= 'LIMIT ' . $nb_lines . ' OFFSET ' . $offset;
		}

		// Retrieves results
		$result = $this->db_querier->select($reqResults);
		while ($row = $result->fetch())
		{
			$results[] = $row;
		}
		$nbResults = $result->get_rows_count();
		$result->dispose();

		return $nbResults;
	}


	/**
	 * @desc Puts results from the search results in the $results parameter and
	 * returns the number of results.
	 * Query complexity: 1 query.
	 * @param string[] &$results the results returned
	 * @param string[] $module_ids the modules ids from which retrieve results
	 * @param int $nb_lines the number of lines to return
	 * @param int $offset the offset from which return results
	 * @return int The number of results
	 */
	public function get_results(&$results, $module_ids, $nb_lines = 0, $offset = 0 )
	{
		$results = array();
		$num_modules = 0;
		$modules_conditions = '';

		// Builds search conditions
		foreach ($module_ids as $module_id)
		{
			// Checks search cache.
			if (array_key_exists($module_id, $this->id_search))
			{
				// Search conditions
				if ($num_modules > 0)
				{
					$modules_conditions .= ", ";
				}
				$modules_conditions .= $this->id_search[$module_id];
				$num_modules++;
			}
		}

		// Builds search results retrieval request
		$reqResults  = "SELECT module, id_content, title, relevance, link
						FROM " . SearchSetup::$search_index_table . " idx, " . SearchSetup::$search_results_table . " rst
						WHERE (idx.id_search = rst.id_search) ";
		if ($modules_conditions != '')
		{
			$reqResults .= " AND rst.id_search  IN (" . $modules_conditions . ")";
		}
		$reqResults .= " ORDER BY relevance DESC ";
		if ( $nb_lines > 0 )
		{
			$reqResults .= 'LIMIT ' . $nb_lines . ' OFFSET ' . $offset;
		}

		// Executes search
		$result = $this->db_querier->select($reqResults);
		while ($row = $result->fetch())
		{
			$results[] = $row;
		}
		$nbResults = $result->get_rows_count();

		$result->dispose();

		return $nbResults;
	}


	/**
	 * @desc Inserts search results in the database cache in order to speed up next searches.
	 * Query complexity: 1 + k / 10 queries. (k represent the number of results to insert in the database)
	 * @param mixed[] $requestAndResults This parameters is an array with keys that are
	 * modules ids and values that could be both a SQL query or a results array.
	 */
	public function insert_results($requestAndResults)
	{
		$nbReqSEARCH = 0;
		$reqSEARCH = "";
		$results = array();

		// Checks results in cache
		foreach ($requestAndResults as $module_name => $request)
		{
			if (!empty($request))
			{
				if (!is_array($request))
				{
					if (!$this->is_in_cache($module_name))
					{   // If results are not in cache, adds them
						if ($nbReqSEARCH > 0)
						{
							$reqSEARCH .= " UNION ";
						}

						$reqSEARCH .= "(".trim( $request, ' ;' ).")";
						$nbReqSEARCH++;
					}
				}
				else
				{
					$results += $requestAndResults[$module_name];
				}
			}
		}

		$nbResults = count($results);
		// If there is many results to insert
		if (($nbReqSEARCH > 0) || ($nbResults > 0))
		{
			$nbReqInsert = 0;
			$reqInsert = '';

			// Group insertions by 10
			for ($nbReqInsert = 0; $nbReqInsert < $nbResults; $nbReqInsert++)
			{
				$row = $results[$nbReqInsert];
				if ($nbReqInsert > 0)
				{
					$reqInsert .= ',';
				}
				$reqInsert .= " ('".$row['id_search']."','".$row['id_content']."','".addslashes($row['title'])."',";
				$reqInsert .= "'".$row['relevance']."','".$row['link']."')";
			}

			if (!empty($reqSEARCH))
			{   // Inserts results
				$result = $this->db_querier->select($reqSEARCH);
				while ($row = $result->fetch())
				{
					if ($nbReqInsert > 0)
					{
						$reqInsert .= ',';
					}
					$reqInsert .= " ('".$row['id_search']."','".$row['id_content']."','".addslashes($row['title'])."',";
					$reqInsert .= "'".$row['relevance']."','".$row['link']."')";
					$nbReqInsert++;
				}
				$result->dispose();
			}

			// Executes last insertions
			if ($nbReqInsert > 0)
			{
				$this->db_querier->inject("INSERT INTO " . SearchSetup::$search_results_table . " VALUES ".$reqInsert);
			}
		}
	}


	/**
	 * @desc Returns true if the id_search is in cache, else, false.
	 * @param int $id_search the search id to check.
	 * @return bool true if the id_search is in cache, else, false.
	 */
	public function is_search_id_in_cache($id_search)
	{
		if (in_array($id_search, $this->id_search))
		{
			return true;
		}

		$id = $this->db_querier->count(SearchSetup::$search_index_table, 'WHERE id_search = :id_search AND id_user = :id_user', array('id_search' => $id_search, 'id_user' => $this->id_user));
		if ($id == 1)
		{
			// Search is already in cache, we update it.
			$reqUpdate  = "UPDATE " . SearchSetup::$search_index_table . " SET times_used=times_used+1, last_search_use='" . time() . "' WHERE id_search = '" . $id_search . "' AND id_user = '" . $this->id_user . "';";
			$this->db_querier->inject($reqUpdate);

			return true;
		}
		return false;
	}


	/**
	 * @desc Returns true if the module results are in cache, else, false.
	 * @param string $module_id the module to check.
	 * @return bool true if the module results are in cache, else, false.
	 */
	public function is_in_cache($module_id)
	{
		return in_array($module_id, $this->cache);
	}


	/**
	 * @desc Returns the list of the modules ids present in the cache
	 * @return string[] the list of the modules present in the cache
	 */
	public function modules_in_cache()
	{
		return array_keys($this->id_search);
	}

	/**
	 * @desc Returns the search id
	 * @return int the search id
	 */
	public function get_ids()
	{
		return $this->id_search;
	}


	/**
	 * @desc Builds modules search conditions on the meta-information cache
	 * @param mixed[string] $modules An array with modules ids keys and values
	 * containings modules specifics options.
	 * @return string The condition to put in a query WHERE clause
	 */
	private function get_modules_conditions($modules)
	/**
	 *  Génère les conditions de la clause WHERE pour limiter les requêtes
	 *  aux seuls modules avec les bonnes options de recherches concernés.
	 */
	{
		$nbModules = count($modules);
		$modules_conditions = '';
		if ($nbModules > 0)
		{
			$modules_conditions .= " ( ";
			$i = 0;
			foreach ($modules as $module_id => $options)
			{
				$modules_conditions .= "( module='" . $module_id . "' AND options='" . md5(implode('|', $options)) . "' )";

				if ($i < ($nbModules - 1))
				{
					$modules_conditions .= " OR ";
				}
				else
				{
					$modules_conditions .= " ) ";
				}
				$i++;
			}
		}

		return $modules_conditions;
	}
}
?>
