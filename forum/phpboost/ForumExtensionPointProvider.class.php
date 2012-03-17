<?php
/*##################################################
 *                     ForumExtensionPointProvider.class.php
 *                            -------------------
 *   begin                : Februar 24, 2008
 *   copyright            : (C) 2007 Régis Viarre, Loic Rouchon
 *   email                : crowkait@phpboost.com, loic.rouchon@phpboost.com
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

define('FORUM_MAX_SEARCH_RESULTS', 50);

class ForumExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct() //Constructeur de la classe ForumInterface
	{
		parent::__construct('forum');
	}

	//Récupération du cache.
	function get_cache()
	{
		$sql_querier = PersistenceContext::get_sql();
		//Configuration du forum
		$forum_config = 'global $CONFIG_FORUM;' . "\n";

		//Récupération du tableau linéarisé dans la bdd.
		$CONFIG_FORUM = unserialize($sql_querier->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'forum'", __LINE__, __FILE__));
		$CONFIG_FORUM['auth'] = unserialize($CONFIG_FORUM['auth']);

		$forum_config .= '$CONFIG_FORUM = ' . var_export($CONFIG_FORUM, true) . ';' . "\n";

		//Liste des catégories du forum
		$i = 0;
		$forum_cats = 'global $CAT_FORUM;' . "\n";
		$result = $sql_querier->query_while("SELECT id, id_left, id_right, level, name, url, status, aprob, auth, aprob
		FROM " . PREFIX . "forum_cats
		ORDER BY id_left", __LINE__, __FILE__);
		while ($row = $sql_querier->fetch_assoc($result))
		{
			if (empty($row['auth']))
			$row['auth'] = serialize(array());

			$forum_cats .= '$CAT_FORUM[\'' . $row['id'] . '\'][\'id_left\'] = ' . var_export($row['id_left'], true) . ';' . "\n";
			$forum_cats .= '$CAT_FORUM[\'' . $row['id'] . '\'][\'id_right\'] = ' . var_export($row['id_right'], true) . ';' . "\n";
			$forum_cats .= '$CAT_FORUM[\'' . $row['id'] . '\'][\'level\'] = ' . var_export($row['level'], true) . ';' . "\n";
			$forum_cats .= '$CAT_FORUM[\'' . $row['id'] . '\'][\'name\'] = ' . var_export($row['name'], true) . ';' . "\n";
			$forum_cats .= '$CAT_FORUM[\'' . $row['id'] . '\'][\'status\'] = ' . var_export($row['status'], true) . ';' . "\n";
			$forum_cats .= '$CAT_FORUM[\'' . $row['id'] . '\'][\'aprob\'] = ' . var_export($row['aprob'], true) . ';' . "\n";
			$forum_cats .= '$CAT_FORUM[\'' . $row['id'] . '\'][\'url\'] = ' . var_export($row['url'], true) . ';' . "\n";
			$forum_cats .= '$CAT_FORUM[\'' . $row['id'] . '\'][\'auth\'] = ' . var_export(unserialize($row['auth']), true) . ';' . "\n";
		}
		$sql_querier->query_close($result);

		return $forum_config . "\n" . $forum_cats;
	}

	public function scheduled_jobs()
	{
		return new ForumScheduledJobs();
	}

	public function user()
	{
		return new ForumUserExtensionPoint();
	}
	
	public function css_files()
	{
		return new ForumCssFilesExtensionPoint();
	}

	public function search()
	{
		return new ForumSearchable();
	}

	public function feeds()
	{
		return new ForumFeedProvider();
	}
	
	public function home_page()
	{
		return new ForumHomePageExtensionPoint();
	}
}
?>
