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

require_once PATH_TO_ROOT . '/news/news_constant.php';

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

		//Récupération du tableau linéarisé dans la bdd.
		$news_config = unserialize($Sql->query("SELECT value FROM " . DB_TABLE_CONFIGS . " WHERE name = 'news'", __LINE__, __FILE__));

		$string = 'global $NEWS_CONFIG, $NEWS_CAT;' . "\n\n" . '$NEWS_CONFIG = $NEWS_CAT = array();' . "\n\n";		
		$string .= '$NEWS_CONFIG = ' . var_export($news_config, true) . ';' . "\n\n";

		//List of categories and their own properties
		$result = $Sql->query_while("SELECT id, id_parent, c_order, auth, name, visible, image, description
			FROM " . DB_TABLE_NEWS_CAT . "
			ORDER BY id_parent, c_order", __LINE__, __FILE__);

		while ($row = $Sql->fetch_assoc($result))
		{
			$string .= '$NEWS_CAT[' . $row['id'] . '] = ' .
				var_export(array(
					'id_parent' => (int)$row['id_parent'],
					'order' => (int)$row['c_order'],
					'name' => $row['name'],
					'desc' => $row['description'],
					'visible' => (bool)$row['visible'],
					'image' => !empty($row['image']) ? $row['image'] : '/news/news.png',
					'description' => $row['description'],
					'auth' => !empty($row['auth']) ? unserialize($row['auth']) : false
				), true) . ';' . "\n\n";
		}

		return $string;
	}

	//Actions journalière.
	/*function on_changeday()
	{
	
	}*/
	
	function get_search_request($args)
    /**
     *  Renvoie la requête de recherche
     */
    {
        global $Sql;

		import('util/date');
		$now = new Date(DATE_NOW, TIMEZONE_AUTO);

        $weight = isset($args['weight']) && is_numeric($args['weight']) ? $args['weight'] : 1;
        
        $request = "SELECT " . $args['id_search'] . " AS id_search,
            n.id AS id_content,
            n.title AS title,
            ( 2 * MATCH(n.title) AGAINST('" . $args['search'] . "') + (MATCH(n.contents) AGAINST('" . $args['search'] . "') + MATCH(n.extend_contents) AGAINST('" . $args['search'] . "')) / 2 ) / 3 * " . $weight . " AS relevance, "
            . $Sql->concat("'" . PATH_TO_ROOT . "/news/news.php?id='","n.id") . " AS link
            FROM " . DB_TABLE_NEWS . " n
            WHERE ( MATCH(n.title) AGAINST('" . $args['search'] . "') OR MATCH(n.contents) AGAINST('" . $args['search'] . "') OR MATCH(n.extend_contents) AGAINST('" . $args['search'] . "') )
                AND n.start <= '" . $now->get_timestamp() . "' AND n.visible = 1
            ORDER BY relevance DESC " . $Sql->limit(0, NEWS_MAX_SEARCH_RESULTS);
        
        return $request;
    }
    
	/**
     * @desc Return the list of the feeds available for this module. 
     * @return FeedsList The list
     */
    function get_feeds_list()
	{
        require_once PATH_TO_ROOT . '/news/news_cats.class.php';
        $news_cats = new NewsCats();
        return $news_cats->get_feeds_list();	    
	}
    
    function get_feed_data_struct($idcat = 0, $name = '')
    {
        global $Cache, $Sql, $LANG, $CONFIG, $NEWS_CONFIG, $NEWS_LANG;
		
        import('content/syndication/feed_data');
        import('util/date');
		$now = new Date(DATE_NOW, TIMEZONE_AUTO);
        import('util/url');
        
        load_module_lang('news');
        
        $data = new FeedData();
        
        $data->set_title($NEWS_LANG['xml_news_desc'] . ' ' . $CONFIG['server_name']);
        $data->set_date(new Date());
        $data->set_link(new Url('/syndication.php?m=news&amp;cat=' . $idcat));
        $data->set_host(HOST);
        $data->set_desc($NEWS_LANG['xml_news_desc'] . ' ' . $CONFIG['server_name']);
        $data->set_lang($LANG['xml_lang']);
        
        // Load the new's config
        $Cache->load('news');
        
		$array_cat = array();
		if (!empty($NEWS_CAT))
		{
			foreach($NEWS_CAT as $id => $value)
			{
				if ($User->check_auth($news_cat->auth($id), AUTH_NEWS_READ))
				{
					$array_cat[] = $id;
				}
			}
		}

		$where = !empty($array_cat) ? " AND idcat IN('" . implode(", ", $array_cat) . "')" : '';
		$NEWS_CONFIG['nbr_news'] = !empty($array_cat) ? $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_NEWS . " WHERE start <= '" . $now->get_timestamp() . "' AND (end >= '" . $now->get_timestamp() . "' OR end = 0) AND visible = 1" . $where, __LINE__, __FILE__) : 0;
		
		if ($NEWS_CONFIG['nbr_news'] > 0)
		{
	        // Last news
	        $result = $Sql->query_while("SELECT id, title, contents, timestamp, img
	            FROM " . DB_TABLE_NEWS . "
	            WHERE start <= '" . $now->get_timestamp() . "' AND visible = 1" . $where . "
	            ORDER BY timestamp DESC"
				. $Sql->limit(0, 2 * $NEWS_CONFIG['pagination_news']), __LINE__, __FILE__);
        
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
        }

        return $data;
    }
	
	function get_home_page()
	{
		global $User, $Sql, $Cache, $Bread_crumb, $NEWS_CONFIG, $NEWS_CAT, $NEWS_LANG, $LANG, $Session;

		// Begin
		//Chargement de la langue du module.
		load_module_lang('news');
		//Chargement du cache
		$Cache->load('news');
		//Css alternatif.
		defined('ALTERNATIVE_CSS') or define('ALTERNATIVE_CSS', 'news');
		defined('FEED_URL') or define('FEED_URL', '/syndication.php?m=news');

		$arch = retrieve(GET, 'arch', false);
		$level = array('', ' class="modo"', ' class="admin"');
		$is_admin = $User->check_level(ADMIN_LEVEL);

		$tpl_news = new Template('news/news.tpl');

		//Affichage de l'édito
		if ($NEWS_CONFIG['activ_edito'] == 1)
		{
			$tpl_news->assign_vars( array(
				'C_NEWS_EDITO' => true,
				'TITLE' => $NEWS_CONFIG['edito_title'],
				'CONTENTS' => second_parse($NEWS_CONFIG['edito'])
			));
		}

		import('util/date');
		$now = new Date(DATE_NOW, TIMEZONE_AUTO);
		import('content/comments');
		import('content/syndication/feed');
		//On crée une pagination (si activé) si le nombre de news est trop important.
		import('util/pagination');
		$Pagination = new Pagination();
		
		require_once PATH_TO_ROOT . '/news/news_cats.class.php';
		$news_cat = new NewsCats();
		
		$array_cat = array();
		if (!empty($NEWS_CAT))
		{
			foreach($NEWS_CAT as $id => $value)
			{
				if ($User->check_auth($news_cat->auth($id), AUTH_NEWS_READ))
				{
					$array_cat[] = $id;
				}
			}
		}

		$where = !empty($array_cat) ? " AND idcat IN('" . implode(", ", $array_cat) . "')" : '';

		$NEWS_CONFIG['nbr_news'] = !empty($array_cat) ? $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_NEWS . " WHERE start <= '" . $now->get_timestamp() . "' AND (end >= '" . $now->get_timestamp() . "' OR end = 0) AND visible = 1" . $where, __LINE__, __FILE__) : 0;
		
		//Pagination activée, sinon affichage lien vers les archives.
		if ($NEWS_CONFIG['activ_pagin'] == '1')
		{
			$show_pagin = $Pagination->display(PATH_TO_ROOT . '/news/news' . url('.php?p=%d', '-0-0-%d.php'), $NEWS_CONFIG['nbr_news'], 'p', $NEWS_CONFIG['pagination_news'], 3);
			$first_msg = $Pagination->get_first_msg($NEWS_CONFIG['pagination_news'], 'p');
		}
		elseif ($arch) //Pagination des archives.
		{
			$show_pagin = $Pagination->display(PATH_TO_ROOT . '/news/news' . url('.php?arch=1&amp;p=%d', '-0-0-%d.php?arch=1'), $NEWS_CONFIG['nbr_news'] - $NEWS_CONFIG['pagination_news'], 'p', $NEWS_CONFIG['pagination_arch'], 3);
			$first_msg = $NEWS_CONFIG['pagination_news'] + $Pagination->get_first_msg($NEWS_CONFIG['pagination_arch'], 'p');
			$NEWS_CONFIG['pagination_news'] = $NEWS_CONFIG['pagination_arch'];
		}
		else //Affichage du lien vers les archives.
		{
			$show_pagin = (($NEWS_CONFIG['nbr_news'] > $NEWS_CONFIG['pagination_news']) && ($NEWS_CONFIG['nbr_news'] != 0)) ? '<a href="' . PATH_TO_ROOT . '/news/news.php' . '?arch=1" title="' . $NEWS_LANG['display_archive'] . '">' . $NEWS_LANG['display_archive'] . '</a>' : '';
			$first_msg = 0;
		}
			
		$tpl_news->assign_vars(array(
			'C_IS_ADMIN' => $is_admin,
			'C_NEWS_NAVIGATION_LINKS' => false,
			'L_SYNDICATION' => $LANG['syndication'],
			'L_ALERT_DELETE_NEWS' => $NEWS_LANG['alert_delete_news'],
			'L_LAST_NEWS' => !$arch ? $NEWS_LANG['last_news'] : $NEWS_LANG['archive'],
	        'L_EDIT' => $LANG['edit'],
			'L_DELETE' => $LANG['delete'],
			'PAGINATION' => $show_pagin,
	        'THEME' => get_utheme(),
			'TOKEN' => $Session->get_token(),
		    'FEED_MENU' => Feed::get_feed_menu(FEED_URL),
			'PATH_TO_ROOT' => TPL_PATH_TO_ROOT
		));

		//Si les news en block sont activées on recupère la page.
		if ($NEWS_CONFIG['type'] == 1 && !$arch)
		{
			if ($NEWS_CONFIG['nbr_news'] == 0)
			{
				$tpl_news->assign_vars( array(
					'C_NEWS_NO_AVAILABLE' => true,
					'L_NO_NEWS_AVAILABLE' => $NEWS_LANG['no_news_available']
				));
			}
			else
			{
				$tpl_news->assign_vars(array(
					'C_NEWS_BLOCK' => true
				));
			
				$column = ($NEWS_CONFIG['nbr_column'] > 1) ? true : false;
				if ($column)
				{
					$i = 0;
					$NEWS_CONFIG['nbr_column'] = !empty($NEWS_CONFIG['nbr_column']) ? $NEWS_CONFIG['nbr_column'] : 1;
					$column_width = floor(100 / $NEWS_CONFIG['nbr_column']);

					$tpl_news->assign_vars(array(
						'C_NEWS_BLOCK_COLUMN' => true,
						'COLUMN_WIDTH' => $column_width
					));
				}

				$result = $Sql->query_while("SELECT n.contents, n.extend_contents, n.title, n.id, n.idcat, n.timestamp, n.user_id, n.img, n.alt, n.nbr_com, m.login, m.level
					FROM " . DB_TABLE_NEWS . " n
					LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = n.user_id
					WHERE n.start <= '" . $now->get_timestamp() . "' AND (n.end >= '" . $now->get_timestamp() . "' OR n.end = 0) AND n.visible = 1" . $where . "
					ORDER BY n.timestamp DESC
					" . $Sql->limit($first_msg, $NEWS_CONFIG['pagination_news']), __LINE__, __FILE__);

				while ($row = $Sql->fetch_assoc($result))
				{
					//Séparation des news en colonnes si activé.
					if ($column)
					{
						$new_row = (($i % $NEWS_CONFIG['nbr_column']) == 0 && $i > 0);
						$i++;
					}
					else
					{
						$new_row = false;
					}

					$timestamp = new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, $row['timestamp']);

					$tpl_news->assign_block_vars('news', array(
						'C_NEWS_ROW' => $new_row,
						'C_IMG' => !empty($row['img']),
						'C_ICON' => $NEWS_CONFIG['activ_icon'],
						'U_CAT' => 'news' . url('.php?cat=' . $row['idcat'], '-' . $row['idcat'] . '+' . url_encode_rewrite($NEWS_CAT[$row['idcat']]['name']) . '.php'),
						'U_NEWS_LINK' => 'news' . url('.php?id=' . $row['id'], '-' . $row['idcat'] . '-' . $row['id'] . '+' . url_encode_rewrite($row['title']) . '.php'),
						'ID' => $row['id'],
						'IDCAT' => $row['idcat'],
						'ICON' => second_parse_url($NEWS_CAT[$row['idcat']]['image']),
						'TITLE' => $row['title'],
						'CONTENTS' => second_parse($row['contents']),
						'EXTEND_CONTENTS' => !empty($row['extend_contents']) ? '<a style="font-size:10px" href="' . PATH_TO_ROOT . '/news/news' . url('.php?id=' . $row['id'], '-0-' . $row['id'] . '.php') . '">[' . $NEWS_LANG['extend_contents'] . ']</a><br /><br />' : '',
						'IMG' => second_parse_url($row['img']),
						'IMG_DESC' => $row['alt'],
						'PSEUDO' => $NEWS_CONFIG['display_author'] && !empty($row['login']) ? $row['login'] : '',
						'LEVEL' =>	$level[$row['level']],
						'U_USER_ID' => '../member/member' . url('.php?id=' . $row['user_id'], '-' . $row['user_id'] . '.php'),					
						'DATE' => $NEWS_CONFIG['display_date'] ? sprintf($NEWS_LANG['on'], $timestamp->format(DATE_FORMAT_SHORT, TIMEZONE_AUTO)) : '',
						'U_COM' => ($NEWS_CONFIG['activ_com'] == 1) ? Comments::com_display_link($row['nbr_com'], '../news/news' . url('.php?cat=0&amp;id=' . $row['id'] . '&amp;com=0', '-0-' . $row['id'] . '+' . url_encode_rewrite($row['title']) . '.php?com=0'), $row['id'], 'news') : '',
					    'FEED_MENU' => Feed::get_feed_menu(FEED_URL)
					));
				}

				$Sql->query_close($result);
			}
		}
		else //News en liste
		{
			if ($NEWS_CONFIG['nbr_news'] == 0)
			{
				$tpl_news->assign_vars( array(
					'C_NEWS_NO_AVAILABLE' => true,
					'L_NO_NEWS_AVAILABLE' => $NEWS_LANG['no_news_available']
				));
			}
			else
			{
				$tpl_news->assign_vars(array(
					'C_NEWS_LINK' => true,
					'NAME_NEWS' => $NEWS_LANG['last_news'],
				));

				$column = ($NEWS_CONFIG['nbr_column'] > 1) ? true : false;
				if ($column)
				{
					$i = 0;
					$NEWS_CONFIG['nbr_column'] = !empty($NEWS_CONFIG['nbr_column']) ? $NEWS_CONFIG['nbr_column'] : 1;
					$column_width = floor(100/$NEWS_CONFIG['nbr_column']);

					$tpl_news->assign_vars(array(
						'C_NEWS_LINK_COLUMN' => true,
						'COLUMN_WIDTH' => $column_width
					));
				}
			
				$result = $Sql->query_while("SELECT id, idcat, title, timestamp, nbr_com FROM " . DB_TABLE_NEWS . " WHERE start <= '" . $now->get_timestamp() . "' AND (end >= '" . $now->get_timestamp() . "' OR end = 0) AND visible = 1" . $where . " ORDER BY timestamp DESC" . $Sql->limit($first_msg, $NEWS_CONFIG['pagination_news']), __LINE__, __FILE__);

				while ($row = $Sql->fetch_assoc($result))
				{
					if ($User->check_auth($news_cat->auth($row['idcat']), AUTH_NEWS_READ))
					{
						//Séparation des news en colonnes si activé.
						$new_row = false;
						if ($column)
						{
							$new_row = (($i % $NEWS_CONFIG['nbr_column']) == 0 && $i > 0);
							$i++;
						}

						$timestamp = new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, $row['timestamp']);

						$tpl_news->assign_block_vars('list', array(
							'C_NEWS_ROW' => $new_row,
							'ICON' => $NEWS_CONFIG['activ_icon'] ? second_parse_url($NEWS_CAT[$row['idcat']]['image']) : 0,
							'U_CAT' => 'news' . url('.php?cat=' . $row['idcat'], '-' . $row['idcat'] . '.php'),
							'DATE' => $timestamp->format(DATE_FORMAT_TINY, TIMEZONE_AUTO),
							'TITLE' => $row['title'],
							'U_NEWS' => 'news' . url('.php?id=' . $row['id'], '-' . $row['idcat'] . '-' . $row['id'] . '+' . url_encode_rewrite($row['title']) . '.php'),
							'C_COM' => $NEWS_CONFIG['activ_com'] ? 1 : 0,
							'COM' => $row['nbr_com']
						));
					}
				}

				$Sql->query_close($result);
			}
		}

		return $tpl_news->parse(true);
	}
}

?>