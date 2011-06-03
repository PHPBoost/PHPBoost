<?php
/*##################################################
 *                          downloadExtensionPointProvider.class.php
 *                            -------------------
 *   begin                : June 22, 2008
 *   copyright            : (C) 2008 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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

define('DOWNLOAD_MAX_SEARCH_RESULTS', 100);


// Class DownloadInterface
//  Provides download module services to the kernel and extern modules
class DownloadExtensionPointProvider extends ExtensionPointProvider
{
	private $sql_querier;

    ## Public Methods ##
    function __construct()
    {
		$this->sql_querier = PersistenceContext::get_sql();
        parent::__construct('download');
    }

	//Récupération du cache.
	function get_cache()
	{
		global $LANG, $Cache;

		$code = 'global $DOWNLOAD_CATS;' . "\n" . 'global $CONFIG_DOWNLOAD;' . "\n\n";

		//Récupération du tableau linéarisé dans la bdd.
		$CONFIG_DOWNLOAD = unserialize($this->sql_querier->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'download'", __LINE__, __FILE__));

		$code .= '$CONFIG_DOWNLOAD = ' . var_export($CONFIG_DOWNLOAD, true) . ';' . "\n";

		//Liste des catégories et de leurs propriétés
		$code .= '$DOWNLOAD_CATS = array();' . "\n\n";

		//Racine
		$code .= '$DOWNLOAD_CATS[0] = ' . var_export(array('name' => $LANG['root'], 'auth' => $CONFIG_DOWNLOAD['global_auth']) ,true) . ';' . "\n\n";

		$result = $this->sql_querier->query_while("SELECT id, id_parent, c_order, auth, name, visible, icon, num_files, contents
		FROM " . PREFIX . "download_cat
		ORDER BY id_parent, c_order", __LINE__, __FILE__);
		while ($row = $this->sql_querier->fetch_assoc($result))
		{
			$code .= '$DOWNLOAD_CATS[' . $row['id'] . '] = ' .
			var_export(array(
			'id_parent' => $row['id_parent'],
			'order' => $row['c_order'],
			'name' => $row['name'],
			'contents' => $row['contents'],
			'visible' => (bool)$row['visible'],
			'icon' => $row['icon'],
			'description' => $row['contents'],
			'num_files' => $row['num_files'],
			'auth' => unserialize($row['auth'])
			), true)
			. ';' . "\n";
		}

		return $code;
	}

	public function scheduled_jobs()
	{
		return new DownloadScheduledJobs();
	}

	public function feeds()
	{
		return new DownloadFeedProvider();
	}

	function get_search_request($args)
    /**
     *  Renvoie la requête de recherche
     */
    {
        global $Cache;
        $weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;

		$Cache->load('download');

        $cats = new DownloadCats();
        $auth_cats = array();
        $cats->build_children_id_list(0, $auth_cats);

        $auth_cats = !empty($auth_cats) ? " AND d.idcat IN (" . implode($auth_cats, ',') . ") " : '';

        $request = "SELECT " . $args['id_search'] . " AS id_search,
            d.id AS id_content,
            d.title AS title,
            ( 3 * FT_SEARCH(d.title, '" . $args['search'] . "') +
            2 * FT_SEARCH_RELEVANCE(d.short_contents, '" . $args['search'] . "') +
            FT_SEARCH_RELEVANCE(d.contents, '" . $args['search'] . "') ) / 6 * " . $weight . " AS relevance, "
            . $this->sql_querier->concat("'" . PATH_TO_ROOT . "/download/download.php?id='","d.id") . " AS link
            FROM " . PREFIX . "download d
            WHERE ( FT_SEARCH(d.title, '" . $args['search'] . "') OR
            FT_SEARCH(d.short_contents, '" . $args['search'] . "') OR
            FT_SEARCH(d.contents, '" . $args['search'] . "') )" . $auth_cats
            . " ORDER BY relevance DESC " . $this->sql_querier->limit(0, DOWNLOAD_MAX_SEARCH_RESULTS);

        return $request;

    }

    /**
     * @desc Return the array containing the result's data list
     * @param &string[][] $args The array containing the result's id list
     * @return string[] The array containing the result's data list
     */
    function compute_search_results($args)
    {
        $results_data = array();

        $results =& $args['results'];
        $nb_results = count($results);

        $ids = array();
        for ($i = 0; $i < $nb_results; $i++)
            $ids[] = $results[$i]['id_content'];

        $request = "SELECT id, idcat, title, short_contents, url, note, image, count, timestamp, nbr_com
            FROM " . PREFIX . "download
            WHERE id IN (" . implode(',', $ids) . ")";

        $request_results = $this->sql_querier->query_while ($request, __LINE__, __FILE__);
        while ($row = $this->sql_querier->fetch_assoc($request_results))
        {
            $results_data[] = $row;
        }
        $this->sql_querier->query_close($request_results);

        return $results_data;
    }

    /**
     *  @desc Return the string to print the result
     *  @param &string[] $result_data the result's data
     *  @return string[] The string to print the result of a search element
     */
    function parse_search_result($result_data)
    {
        global $Cache, $LANG, $DOWNLOAD_LANG, $CONFIG_DOWNLOAD;
        $Cache->load('download');

        load_module_lang('download'); //Chargement de la langue du module.
        $tpl = new FileTemplate('download/download_generic_results.tpl');


        $date = new Date(DATE_TIMESTAMP, TIMEZONE_USER, $result_data['timestamp']);
         //Notes

        $tpl->put_all(array(
            'L_ADDED_ON' => sprintf($DOWNLOAD_LANG['add_on_date'], $date->format(DATE_FORMAT_TINY, TIMEZONE_USER)),
            'U_LINK' => url(PATH_TO_ROOT . '/download/download.php?id=' . $result_data['id']),
            'U_IMG' => $result_data['image'],
            'E_TITLE' => TextHelper::strprotect($result_data['title']),
            'TITLE' => $result_data['title'],
            'SHORT_DESCRIPTION' => FormatingHelper::second_parse($result_data['short_contents']),
            'L_NB_DOWNLOADS' => $DOWNLOAD_LANG['downloaded'] . ' ' . sprintf($DOWNLOAD_LANG['n_times'], $result_data['count']),
            'L_NB_COMMENTS' => $result_data['nbr_com'] > 1 ? sprintf($DOWNLOAD_LANG['num_com'], $result_data['nbr_com']) : sprintf($DOWNLOAD_LANG['num_coms'], $result_data['nbr_com']),
            'L_MARK' => $result_data['note'] > 0 ? Note::display_img($result_data['note'], $CONFIG_DOWNLOAD['note_max'], 5) : ('<em>' . $LANG['no_note'] . '</em>')
        ));

        return $tpl->render();
    }

    ## Private ##
    function _check_cats_auth($id_cat, $list)
    {
        global $DOWNLOAD_CATS, $CONFIG_DOWNLOAD;

        if ($id_cat == 0)
        {
            if (Authorizations::check_auth(RANK_TYPE, GUEST_LEVEL, $CONFIG_DOWNLOAD['global_auth'], DOWNLOAD_READ_CAT_AUTH_BIT))
                $list[] = 0;
            else
                return;
        }
        else
        {
			if (!empty($DOWNLOAD_CATS[$id_cat]))
			{
				$auth = !empty($DOWNLOAD_CATS[$id_cat]['auth']) ? $DOWNLOAD_CATS[$id_cat]['auth'] : $CONFIG_DOWNLOAD['global_auth'];
				if (Authorizations::check_auth(RANK_TYPE, GUEST_LEVEL, $auth, DOWNLOAD_READ_CAT_AUTH_BIT))
					$list[] = $id_cat;
            }
			else
                return;
        }

        $keys = array_keys($DOWNLOAD_CATS);
        $num_cats = count($DOWNLOAD_CATS);

        $properties = array();
        for ($j = 0; $j < $num_cats; $j++)
        {
            $id = $keys[$j];
            $properties = $DOWNLOAD_CATS[$id];

            if ($properties['id_parent'] == $id_cat)
            {
                $this_auth = is_array($properties['auth']) ? Authorizations::check_auth(RANK_TYPE, GUEST_LEVEL, $properties['auth'], DOWNLOAD_READ_CAT_AUTH_BIT) :  Authorizations::check_auth(RANK_TYPE, GUEST_LEVEL, $CONFIG_DOWNLOAD['global_auth'], DOWNLOAD_READ_CAT_AUTH_BIT);

                if ($this_auth)
                {
                    $list[] = $id;
                    $this->_check_cats_auth($id, $list);
                }
            }
        }
    }

	function get_cat()
	{
		$result = $this->sql_querier->query_while("SELECT *
	            FROM " . PREFIX . "download_cat", __LINE__, __FILE__);
			$data = array();
		while ($row = $this->sql_querier->fetch_assoc($result)) {
			$data[$row['id']] = $row['name'];
		}
		$this->sql_querier->query_close($result);
		return $data;
	}
}

?>