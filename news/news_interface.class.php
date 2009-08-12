<?php
/*##################################################
 *                              news_interface.class.php
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

// Inclusion du fichier contenant la classe ModuleInterface
import('modules/module_interface');

define('NEWS_MAX_SEARCH_RESULTS', 100);

// Classe ForumInterface qui hérite de la classe ModuleInterface
class NewsInterface extends ModuleInterface
{
    ## Public Methods ##
    function NewsInterface() //Constructeur de la classe ForumInterface
    {
        parent::ModuleInterface('news');
    }
    
    //Récupération du cache.
	function get_cache()
	{
		global $Sql;

		$news_config = 'global $CONFIG_NEWS;' . "\n";
		
		//Récupération du tableau linéarisé dans la bdd.
		$CONFIG_NEWS = unserialize($Sql->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'news'", __LINE__, __FILE__));
		
		$news_config .= '$CONFIG_NEWS = ' . var_export($CONFIG_NEWS, true) . ';' . "\n";

		return $news_config;
	}

	//Actions journalière.
	function on_changeday()
	{
		global $Sql;
		
		//Publication des news en attente pour la date donnée.
		$result = $Sql->query_while("SELECT id, start, end
		FROM " . PREFIX . "news
		WHERE visible != 0", __LINE__, __FILE__);
		while ($row = $Sql->fetch_assoc($result))
		{
			if ($row['start'] <= time() && $row['start'] != 0)
				$Sql->query_inject("UPDATE " . PREFIX . "news SET visible = 1, start = 0 WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);
			if ($row['end'] <= time() && $row['end'] != 0)
				$Sql->query_inject("UPDATE " . PREFIX . "news SET visible = 0, start = 0, end = 0 WHERE id = '" . $row['id'] . "'", __LINE__, __FILE__);
		}
	}
	
	function get_search_request($args)
    /**
     *  Renvoie la requête de recherche
     */
    {
        global $Sql;
        $weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;
        
        $request = "SELECT " . $args['id_search'] . " AS id_search,
            n.id AS id_content,
            n.title AS title,
            ( 2 * MATCH(n.title) AGAINST('" . $args['search'] . "') + (MATCH(n.contents) AGAINST('" . $args['search'] . "') + MATCH(n.extend_contents) AGAINST('" . $args['search'] . "')) / 2 ) / 3 * " . $weight . " AS relevance, "
            . $Sql->concat("'" . PATH_TO_ROOT . "/news/news.php?id='","n.id") . " AS link
            FROM " . PREFIX . "news n
            WHERE ( MATCH(n.title) AGAINST('" . $args['search'] . "') OR MATCH(n.contents) AGAINST('" . $args['search'] . "') OR MATCH(n.extend_contents) AGAINST('" . $args['search'] . "') )
                AND visible = 1 AND ('" . time() . "' > start AND ( end = 0 OR '" . time() . "' < end ) )
            ORDER BY relevance DESC " . $Sql->limit(0, NEWS_MAX_SEARCH_RESULTS);
        
        return $request;
    }
    
    function get_feeds_list()
    {
    	global $LANG, $Sql;
    	
    	import('content/syndication/feeds_list');
    	$feeds = new FeedsList();
    	
    	$cats_tree = new FeedsCat('news', 0, $LANG['root']);
    	
    	$result = $Sql->query_while("SELECT id, name FROM " . PREFIX . "news_cat ORDER BY name ASC", __LINE__, __FILE__);
    	while ($row = $Sql->fetch_assoc($result))
    	{
    		$cats_tree->add_child(new FeedsCat('news', $row['id'], $row['name']));
    	}
    	$Sql->query_close($result);
    	$feeds->add_feed($cats_tree, DEFAULT_FEED_NAME);
    	
    	return $feeds;
    }
    
    function get_feed_data_struct($idcat = 0, $name = '')
    {
        global $Cache, $Sql, $LANG, $CONFIG, $CONFIG_NEWS;
		
        import('content/syndication/feed_data');
        import('util/date');
        import('util/url');
        
        load_module_lang('news');
        
        $data = new FeedData();
        
        $data->set_title($LANG['xml_news_desc'] . ' ' . $CONFIG['server_name']);
        $data->set_date(new Date());
        $data->set_link(new Url('/syndication.php?m=news&amp;cat=' . $idcat));
        $data->set_host(HOST);
        $data->set_desc($LANG['xml_news_desc'] . ' ' . $CONFIG['server_name']);
        $data->set_lang($LANG['xml_lang']);
        
        // Load the new's config
        $Cache->load('news');
        
        // Last news
        $result = $Sql->query_while("SELECT id, title, contents, timestamp, img
            FROM " . PREFIX . "news
            WHERE visible = 1
            ORDER BY timestamp DESC"
			. $Sql->limit(0, 2 * $CONFIG_NEWS['pagination_news']), __LINE__, __FILE__);
        
        // Generation of the feed's items
        while ($row = $Sql->fetch_assoc($result))
        {
            $item = new FeedItem();
            
            $item->set_title($row['title']);
            // Rewriting
            $link = new Url('/news/news' . url('.php?id=' . $row['id'], '-0-' . $row['id'] .  '+' . url_encode_rewrite($row['title']) . '.php'));
            $item->set_link($link);
            $item->set_guid($link);
            $item->set_desc(second_parse($row['contents']));
            $item->set_date(new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $row['timestamp']));
            $item->set_image_url($row['img']);
            
            $data->add_item($item);
        }
        $Sql->query_close($result);
        
        return $data;
    }

	function get_cat()
	{
		global $Sql;
		
		$result = $Sql->query_while("SELECT *
	            FROM " . PREFIX . "news_cat", __LINE__, __FILE__);
			$data = array();
		while ($row = $Sql->fetch_assoc($result)) {
			$data[$row['id']] = $row['name'];
		}
		$Sql->query_close($result);
		return $data;
	}
	
	function get_home_page()
	{
		global $User, $Sql, $Cache, $Bread_crumb, $CONFIG_NEWS, $LANG, $Session;
		require_once(PATH_TO_ROOT . '/news/news_begin.php');
		
		$show_archive = retrieve(GET, 'arch', false);
		$is_admin = $User->check_level(ADMIN_LEVEL);
		
		$tpl_news = new Template('news/news.tpl');

		if ($CONFIG_NEWS['activ_edito'] == 1) //Affichage de l'édito
		{
			$tpl_news->assign_vars( array(
				'C_NEWS_EDITO' => true,
				'CONTENTS' => second_parse($CONFIG_NEWS['edito']),
				'TITLE' => $CONFIG_NEWS['edito_title']
			));
		}

		import('content/comments');
		import('content/syndication/feed');
		
		//On crée une pagination (si activé) si le nombre de news est trop important.
		import('util/pagination');
		$Pagination = new Pagination();
			
		//Pagination activée, sinon affichage lien vers les archives.
		if ($CONFIG_NEWS['activ_pagin'] == '1')
		{
			$show_pagin = $Pagination->display(PATH_TO_ROOT . '/news/news' . url('.php?p=%d', '-0-0-%d.php'), $CONFIG_NEWS['nbr_news'], 'p', $CONFIG_NEWS['pagination_news'], 3);
			$first_msg = $Pagination->get_first_msg($CONFIG_NEWS['pagination_news'], 'p');
		}
		elseif ($show_archive) //Pagination des archives.
		{
			$show_pagin = $Pagination->display(PATH_TO_ROOT . '/news/news' . url('.php?arch=1&amp;p=%d', '-0-0-%d.php?arch=1'), $CONFIG_NEWS['nbr_news'] - $CONFIG_NEWS['pagination_news'], 'p', $CONFIG_NEWS['pagination_arch'], 3);
			$first_msg = $CONFIG_NEWS['pagination_news'] + $Pagination->get_first_msg($CONFIG_NEWS['pagination_arch'], 'p');
			$CONFIG_NEWS['pagination_news'] = $CONFIG_NEWS['pagination_arch'];
		}
		else //Affichage du lien vers les archives.
		{
			$show_pagin = (($CONFIG_NEWS['nbr_news'] > $CONFIG_NEWS['pagination_news']) && ($CONFIG_NEWS['nbr_news'] != 0)) ? '<a href="' . PATH_TO_ROOT . '/news/news.php?arch=1" title="' . $LANG['display_archive'] . '">' . $LANG['display_archive'] . '</a>' : '';
			$first_msg = 0;
		}
			
		$tpl_news->assign_vars(array(
			'C_IS_ADMIN' => $is_admin,
			'C_NEWS_NAVIGATION_LINKS' => false,
			'L_SYNDICATION' => $LANG['syndication'],
			'PAGINATION' => $show_pagin,
			'L_ALERT_DELETE_NEWS' => $LANG['alert_delete_news'],
			'L_LAST_NEWS' => !$show_archive ? $LANG['last_news'] : $LANG['archive'],
	        'L_EDIT' => $LANG['edit'],
			'L_DELETE' => $LANG['delete'],
			'PATH_TO_ROOT' => TPL_PATH_TO_ROOT,
	        'THEME' => get_utheme(),
		    'FEED_MENU' => Feed::get_feed_menu(FEED_URL)
		));
		
		//Si les news en block sont activées on recupère la page.
		if ($CONFIG_NEWS['type'] == 1 && !$show_archive)
		{
			$tpl_news->assign_vars(array(
				'C_NEWS_BLOCK' => true
			));
			
			$column = ($CONFIG_NEWS['nbr_column'] > 1) ? true : false;
			if ($column)
			{
				$i = 0;
				$CONFIG_NEWS['nbr_column'] = !empty($CONFIG_NEWS['nbr_column']) ? $CONFIG_NEWS['nbr_column'] : 1;
				$column_width = floor(100/$CONFIG_NEWS['nbr_column']);

				$tpl_news->assign_vars(array(
					'C_NEWS_BLOCK_COLUMN' => true,
					'COLUMN_WIDTH' => $column_width
				));
			}
			
			$z = 0;
			list($admin, $del) = array('', '');
			$result = $Sql->query_while("SELECT n.contents, n.extend_contents, n.title, n.id, n.timestamp, n.user_id, n.img, n.alt, n.nbr_com, nc.id AS idcat, nc.icon, m.login
			FROM " . PREFIX . "news n
			LEFT JOIN " . PREFIX . "news_cat nc ON nc.id = n.idcat
			LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = n.user_id
			WHERE '" . time() . "' >= n.start AND ('" . time() . "' <= n.end OR n.end = 0) AND n.visible = 1
			ORDER BY n.timestamp DESC
			" . $Sql->limit($first_msg, $CONFIG_NEWS['pagination_news']), __LINE__, __FILE__);
			while ($row = $Sql->fetch_assoc($result))
			{
				//Séparation des news en colonnes si activé.
				$new_row = false;
				if ($column)
				{
					$new_row = (($i%$CONFIG_NEWS['nbr_column']) == 0 && $i > 0);
					$i++;
				}

				$tpl_news->assign_block_vars('news', array(
					'C_IMG' => !empty($row['img']),
					'C_ICON' => (!empty($row['icon']) && $CONFIG_NEWS['activ_icon'] == 1),
					'C_NEWS_ROW' => $new_row,
					'ID' => $row['id'],
					'IDCAT' => $row['idcat'],
					'ICON' => second_parse_url($row['icon']),
					'TITLE' => $row['title'],
					'CONTENTS' => second_parse($row['contents']),
					'EXTEND_CONTENTS' => (!empty($row['extend_contents']) ? '<a style="font-size:10px" href="' . PATH_TO_ROOT . '/news/news' . url('.php?id=' . $row['id'], '-0-' . $row['id'] . '.php') . '">[' . $LANG['extend_contents'] . ']</a><br /><br />' : ''),
					'IMG' => second_parse_url($row['img']),
					'IMG_DESC' => $row['alt'],
					'PSEUDO' => $CONFIG_NEWS['display_author'] ? $row['login'] : '',
					'DATE' => $CONFIG_NEWS['display_date'] ? $LANG['on'] . ': ' . gmdate_format('date_format_short', $row['timestamp']) : '',
					'TOKEN' => $Session->get_token(),
					'U_COM' => ($CONFIG_NEWS['activ_com'] == 1) ? Comments::com_display_link($row['nbr_com'], PATH_TO_ROOT . '/news/news' . url('.php?cat=0&amp;id=' . $row['id'] . '&amp;com=0', '-0-' . $row['id'] . '+' . url_encode_rewrite($row['title']) . '.php?com=0'), $row['id'], 'news') : '',
					'NEW_ROW' => $new_row,
					'U_USER_ID' => url('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php'),
					'U_NEWS_LINK' => url('.php?id=' . $row['id'], '-0-' . $row['id'] . '+' . url_encode_rewrite($row['title']) . '.php'),
	                'FEED_MENU' => Feed::get_feed_menu(FEED_URL)
				));
				$z++;
			}
			$Sql->query_close($result);
			
			if ($z == 0)
			{
				$tpl_news->assign_vars( array(
					'C_NEWS_NO_AVAILABLE' => true,
					'L_NO_NEWS_AVAILABLE' => $LANG['no_news_available']
				));
			}
		}
		else //News en liste
		{
			$tpl_news->assign_vars(array(
				'C_NEWS_LINK' => true
			));

			$column = ($CONFIG_NEWS['nbr_column'] > 1) ? true : false;
			if ($column)
			{
				$i = 0;
				$CONFIG_NEWS['nbr_column'] = !empty($CONFIG_NEWS['nbr_column']) ? $CONFIG_NEWS['nbr_column'] : 1;
				$column_width = floor(100/$CONFIG_NEWS['nbr_column']);

				$tpl_news->assign_vars(array(
					'C_NEWS_LINK_COLUMN' => true,
					'COLUMN_WIDTH' => $column_width
				));
			}
			
			$result = $Sql->query_while("SELECT n.id, n.title, n.timestamp, nc.id AS idcat, nc.icon
			FROM " . PREFIX . "news n
			LEFT JOIN " . PREFIX . "news_cat nc ON nc.id = n.idcat
			WHERE n.visible = 1
			ORDER BY n.timestamp DESC
			" . $Sql->limit($first_msg, $CONFIG_NEWS['pagination_news']), __LINE__, __FILE__);
			while ($row = $Sql->fetch_assoc($result))
			{
				//Séparation des news en colonnes si activé.
				$new_row = false;
				if ($column)
				{
					$new_row = (($i%$CONFIG_NEWS['nbr_column']) == 0 && $i > 0);
					$i++;
				}
				
				$tpl_news->assign_block_vars('list', array(
					'C_NEWS_ROW' => $new_row,
					'ICON' => ((!empty($row['icon']) && $CONFIG_NEWS['activ_icon'] == 1) ? '<a href="' . PATH_TO_ROOT . '/news/news' . url('.php?cat=' . $row['idcat'], '-' . $row['idcat'] . '.php') . '"><img class="valign_middle" src="' . $row['icon'] . '" alt="" /></a>' : ''),
					'DATE' => gmdate_format('date_format_tiny', $row['timestamp']),
					'TITLE' => $row['title'],
					'NEW_ROW' => $new_row,
					'U_NEWS' => PATH_TO_ROOT . '/news/news' . url('.php?id=' . $row['id'], '-0-' . $row['id'] . '+' . url_encode_rewrite($row['title']) . '.php')
				));
			}
			$Sql->query_close($result);
		}
		return $tpl_news->parse(TRUE);
	}
}

?>