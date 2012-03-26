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

class DownloadExtensionPointProvider extends ExtensionPointProvider
{
	private $sql_querier;

    function __construct()
    {
		$this->sql_querier = PersistenceContext::get_sql();
        parent::__construct('download');
    }

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
	
	public function comments()
	{
		return new DownloadComments();
	}
	
	public function home_page()
	{
		return new DownloadHomePageExtensionPoint();
	}

	public function search()
	{
		return new DownloadSearchable();
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