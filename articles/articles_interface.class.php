<?php
/*##################################################
 *                              articles_interface.class.php
 *                            -------------------
 *   begin                : April 9, 2008
 *   copyright            : (C) 2008 Loïc Rouchon
 *   email                : horn@phpboost.com
 *
 *
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

if (defined('PHPBOOST') !== true) exit;

// Inclusion du fichier contenant la classe ModuleInterface
import('modules/module_interface');

define('ARTICLES_MAX_SEARCH_RESULTS', 100);

// Classe ForumInterface qui hérite de la classe ModuleInterface
class ArticlesInterface extends ModuleInterface
{
	## Public Methods ##
	function ArticlesInterface() //Constructeur de la classe ForumInterface
	{
		parent::ModuleInterface('articles');
	}

	//Récupération du cache.
	function get_cache()
	{
		global $Sql;

		$config_articles = 'global $CONFIG_ARTICLES;' . "\n";

		//Récupération du tableau linéarisé dans la bdd.
		$CONFIG_ARTICLES = unserialize($Sql->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'articles'", __LINE__, __FILE__));
		$CONFIG_ARTICLES = is_array($CONFIG_ARTICLES) ? $CONFIG_ARTICLES : array();

		if (isset($CONFIG_ARTICLES['auth_root']))
		$CONFIG_ARTICLES['auth_root'] = unserialize($CONFIG_ARTICLES['auth_root']);

		$config_articles .= '$CONFIG_ARTICLES = ' . var_export($CONFIG_ARTICLES, true) . ';' . "\n";

		$cat_articles = 'global $CAT_ARTICLES;' . "\n";
		$result = $Sql->query_while("SELECT id, id_left, id_right, level, name, aprob, auth
		FROM " . PREFIX . "articles_cats
		ORDER BY id_left", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			if (empty($row['auth']))
			$row['auth'] = serialize(array());

			$cat_articles .= '$CAT_ARTICLES[\'' . $row['id'] . '\'][\'id_left\'] = ' . var_export($row['id_left'], true) . ';' . "\n";
			$cat_articles .= '$CAT_ARTICLES[\'' . $row['id'] . '\'][\'id_right\'] = ' . var_export($row['id_right'], true) . ';' . "\n";
			$cat_articles .= '$CAT_ARTICLES[\'' . $row['id'] . '\'][\'level\'] = ' . var_export($row['level'], true) . ';' . "\n";
			$cat_articles .= '$CAT_ARTICLES[\'' . $row['id'] . '\'][\'name\'] = ' . var_export($row['name'], true) . ';' . "\n";
			$cat_articles .= '$CAT_ARTICLES[\'' . $row['id'] . '\'][\'aprob\'] = ' . var_export($row['aprob'], true) . ';' . "\n";
			$cat_articles .= '$CAT_ARTICLES[\'' . $row['id'] . '\'][\'auth\'] = ' . var_export(unserialize($row['auth']), true) . ';' . "\n";
		}
		$Sql->query_close($result);

		return $config_articles . "\n" . $cat_articles;
	}

	//Changement de jour.
	function on_changeday()
	{
		global $Sql;

		//Publication des articles en attente pour la date donnée.
		$result = $Sql->query_while("SELECT id, start, end
		FROM " . PREFIX . "articles
		WHERE visible != 0", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			if ($row['start'] <= time() && $row['start'] != 0)
			$Sql->query_inject("UPDATE " . PREFIX . "articles SET visible = 1, start = 0 WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);
			if ($row['end'] <= time() && $row['end'] != 0)
			$Sql->query_inject("UPDATE " . PREFIX . "articles SET visible = 0, start = 0, end = 0 WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);
		}
	}

	function get_search_request($args = null)
	{
		global $Sql, $Cache, $CONFIG_ARTICLES, $CAT_ARTICLES, $User, $LANG;
		$Cache->load('articles');
		require_once(PATH_TO_ROOT . '/articles/articles_constants.php');
        
        $weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;
        
		//Catégories non autorisées.
		$unauth_cats_sql = array();
		foreach ($CAT_ARTICLES as $idcat => $key)
		{
			if ($CAT_ARTICLES[$idcat]['aprob'] == 1)
			{
				if (!$User->check_auth($CAT_ARTICLES[$idcat]['auth'], READ_CAT_ARTICLES))
				{
					$clause_level = !empty($g_idcat) ? ($CAT_ARTICLES[$idcat]['level'] == ($CAT_ARTICLES[$g_idcat]['level'] + 1)) : ($CAT_ARTICLES[$idcat]['level'] == 0);
					if ($clause_level)
					$unauth_cats_sql[] = $idcat;
				}
			}
		}
		$clause_unauth_cats = (count($unauth_cats_sql) > 0) ? " AND gc.id NOT IN (" . implode(', ', $unauth_cats_sql) . ")" : '';

		$request = "SELECT
				" . $args['id_search'] . " AS id_search,
	             a.id AS id_content,
	             a.title AS title,
	             ( 2 * MATCH(a.title) AGAINST('" . $args['search'] . "') + MATCH(a.contents) AGAINST('" . $args['search'] . "') ) / 3 * " . $weight . " AS relevance, "
	             . $Sql->concat("'" . PATH_TO_ROOT . "/articles/articles.php?id='","a.id","'&amp;cat='","a.idcat") . " AS link
            FROM " . PREFIX . "articles a
            LEFT JOIN " . PREFIX . "articles_cats ac ON ac.id = a.idcat
            WHERE
            	a.visible = 1 AND ((ac.aprob = 1 AND ac.auth LIKE '%s:3:\"r-1\";i:1;%') OR a.idcat = 0)
            	AND (MATCH(a.title) AGAINST('" . $args['search'] . "') OR MATCH(a.contents) AGAINST('" . $args['search'] . "'))
            ORDER BY relevance DESC " . $Sql->limit(0, $CONFIG_ARTICLES['nbr_articles_max']);

		return $request;
	}

	function get_feed_data_struct($idcat = 0)
	{
		global $Cache, $Sql, $LANG, $CONFIG, $CONFIG_ARTICLES, $CAT_ARTICLES;
		$Cache->load('articles');

		require_once(PATH_TO_ROOT . '/articles/articles_constants.php');
		import('content/syndication/feed_data');
		$data = new FeedData();

		import('util/date');
        $date = new Date();
        
        $data->set_title($LANG['xml_articles_desc']);
        $data->set_date($date);
        $data->set_link(trim(HOST, '/') . '/' . trim($CONFIG['server_path'], '/') . '/' . 'articles/syndication.php?idcat=' . $idcat);
        $data->set_host(HOST);
        $data->set_desc($LANG['xml_articles_desc']);
        $data->set_lang($LANG['xml_lang']);
        $data->set_auth_bit(READ_CAT_ARTICLES);
        
        $result = $Sql->query_while("SELECT a.id, a.idcat, a.title, a.contents, a.timestamp, a.icon, ac.auth
        FROM " . PREFIX . "articles a
        LEFT JOIN " . PREFIX . "articles_cats ac ON ac.id = a.idcat
        WHERE a.visible = 1 AND (ac.aprob = 1 OR a.idcat = 0)
        ORDER BY a.timestamp DESC
        " . $Sql->limit(0, 2 * $CONFIG_ARTICLES['nbr_articles_max']), __LINE__, __FILE__);
        
        // Generation of the feed's items
        while ($row = $Sql->fetch_assoc($result))
        {
            $item = new FeedItem();
            // Rewriting
            if ( $CONFIG['rewrite'] == 1 )
            $rewrited_title = '-' . $row['idcat'] . '-' . $row['id'] .  '+' . url_encode_rewrite($row['title']) . '.php';
            else
            $rewrited_title = '.php?cat=' . $row['idcat'] . '&amp;id=' . $row['id'];
            $link = HOST . DIR . '/articles/articles' . $rewrited_title;
            
            // XML text's protection
            $contents = htmlspecialchars(html_entity_decode(strip_tags($row['contents'])));
            
            $date = new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $row['timestamp']);
            
            $item->set_title(htmlspecialchars(html_entity_decode($row['title'])));
            $item->set_link($link);
            $item->set_guid($link);
            $item->set_desc(( strlen($contents) > 500 ) ?  substr($contents, 0, 500) . '...[' . $LANG['next'] . ']' : $contents);
            $item->set_date($date);
            $item->set_image_url($row['icon']);
            $item->set_auth($row['idcat'] == 0 ? $CONFIG_ARTICLES['auth_root'] : unserialize($row['auth']));
            
            $data->add_item($item);
        }
        $Sql->query_close($result);
		
		return $data;
	}
}

?>