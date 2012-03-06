<?php
/*##################################################
 *                     NewsHomePageExtensionPoint.class.php
 *                            -------------------
 *   begin                : January 27, 2012
 *   copyright            : (C) 2012 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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

class NewsHomePageExtensionPoint implements HomePageExtensionPoint
{
	private $sql_querier;

    public function __construct()
    {
        $this->sql_querier = PersistenceContext::get_sql();
	}
	
	public function get_home_page()
	{
		return new DefaultHomePage($this->get_title(), $this->get_view());
	}
	
	private function get_title()
	{
		global $NEWS_LANG;
		
		return $NEWS_LANG['title_news'];
	}
	
	private function get_view()
	{
		global $User, $Cache, $Bread_crumb, $NEWS_CAT, $NEWS_LANG, $LANG, $Session;

		// Begin.
		load_module_lang('news');
		$Cache->load('news');
		$news_config = NewsConfig::load();
		
		require_once PATH_TO_ROOT . '/news/news_constants.php';
		
		//Récupération des éléments de configuration
		$config_pagination_news = $news_config->get_pagination_news();
		$config_pagination_arch = $news_config->get_pagination_arch();
		$config_edito_title = $news_config->get_edito_title();
		$config_edito = $news_config->get_edito();
		$config_type = $news_config->get_type();
		$config_activ_pagin = $news_config->get_activ_pagin();
		$config_nbr_columns = $news_config->get_nbr_columns();
		$config_nbr_news = $news_config->get_nbr_news();
		$config_activ_edito = $news_config->get_activ_edito();
		$config_activ_com = $news_config->get_activ_com();
		$config_activ_icon = $news_config->get_activ_icon();
		$config_display_author = $news_config->get_display_author();
		$config_display_date = $news_config->get_display_date();
		$config_auth = $news_config->get_authorization();
		
		// Initialisation des imports.
		$now = new Date(DATE_NOW, TIMEZONE_AUTO);
		$Pagination = new DeprecatedPagination();

		// Classe des catégories.
		$news_cat = new NewsCats();

		// Variables d'archive
		$arch = retrieve(GET, 'arch', false);
		$cat = retrieve(GET, 'cat', 0);

		// Couleurs du login
		$level = array('', ' modo', ' admin');

		$filetpl = $config_type ? 'news/news_cat.tpl' :'news/news_list.tpl';

		$tpl = new FileTemplate($filetpl);

		$c_add = $User->check_auth($NEWS_CAT[$cat]['auth'], AUTH_NEWS_CONTRIBUTE) || $User->check_auth($NEWS_CAT[$cat]['auth'], AUTH_NEWS_WRITE);
		$c_writer = $User->check_auth($NEWS_CAT[$cat]['auth'], AUTH_NEWS_WRITE);

		$last_release = 0;

		if ($cat > 0)
			$where = " WHERE n.visible = 1 AND n.idcat = ".$cat." AND n.start <= '" . $now->get_timestamp() . "' AND (n.end >= '" . $now->get_timestamp() . "' OR n.end = 0)";
		else
			$where = " WHERE n.visible = 1 AND n.start <= '" . $now->get_timestamp() . "' AND (n.end >= '" . $now->get_timestamp() . "' OR n.end = 0)";

		$nbr_news = $this->sql_querier->query("SELECT COUNT(*) FROM " . DB_TABLE_NEWS . " n".$where, __LINE__, __FILE__);

		// Construction du tableau des catégories.
		$array_cat = array();
		if ($cat > 0)
		{
			$array_cat[] = $cat;
		}
		else
		{
			$news_cat->build_children_id_list($cat, $array_cat, RECURSIVE_EXPLORATION, DO_NOT_ADD_THIS_CATEGORY_IN_LIST, AUTH_NEWS_READ);
		}

		if ($nbr_news == 0)
		{
			$tpl->put_all(array(
				'C_NEWS_NO_AVAILABLE' => true,
				'L_LAST_NEWS' => $NEWS_LANG['last_news'],
				'L_NO_NEWS_AVAILABLE' => $NEWS_LANG['no_news_available']
			));
		}
		else
		{
			if ($config_activ_pagin) // Pagination activée, sinon affichage lien vers les archives.
			{
				$show_pagin = $Pagination->display(PATH_TO_ROOT . '/news/news' . url('.php?p=%d', '-0-0-%d.php'), $config_nbr_news, 'p', $config_pagination_news, 3);
				$first_msg = $Pagination->get_first_msg($config_pagination_news, 'p');
			}
			elseif ($arch) // Pagination des archives.
			{
				$show_pagin = $Pagination->display(PATH_TO_ROOT . '/news/news' . url('.php?arch=1&amp;p=%d', '-0-0-%d.php?arch=1'), $config_nbr_news - $config_pagination_news, 'p', $config_pagination_arch, 3);
				$first_msg = $config_pagination_news + $Pagination->get_first_msg($config_pagination_arch, 'p');
				$config_pagination_news = $config_pagination_arch;
			}
			else // Affichage du lien vers les archives.
			{
				$show_pagin = (($config_nbr_news > $config_pagination_news) && ($config_nbr_news != 0)) ? '<a href="' . PATH_TO_ROOT . '/news/news.php' . '?arch=1" title="' . $NEWS_LANG['display_archive'] . '">' . $NEWS_LANG['display_archive'] . '</a>' : '';
				$first_msg = 0;
			}
			$tpl->put_all(array('PAGINATION' => $show_pagin));

			if($config_type ==1 || $config_type ==0)
			{
				if ($config_nbr_columns > 1)
				{
					$i = 0;
					$config_nbr_columns = !empty($config_nbr_columns) ? $config_nbr_columns : 1;
					$column_width = floor(100 / $config_nbr_columns);

					$tpl->put_all(array(
						'C_NEWS_LINK_COLUMN' => true,
						'COLUMN_WIDTH' => $column_width
					));
				}
			}
			// News en bloc => news_cat.tpl
			if($config_type == 1)
			{

				$result = $this->sql_querier->query_while("SELECT n.contents, n.extend_contents, n.title, n.id, n.idcat, n.timestamp, n.start, n.user_id, n.img, n.alt, m.login, m.level
					FROM " . DB_TABLE_NEWS . " n
					LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = n.user_id
					".$where."
					ORDER BY n.timestamp DESC
					" . $this->sql_querier->limit($first_msg, $config_pagination_news), __LINE__, __FILE__);

				while ($row = $this->sql_querier->fetch_assoc($result))
				{

					if($User->check_auth($NEWS_CAT[$row['idcat']]['auth'], AUTH_NEWS_READ))
					{
						// Séparation des news en colonnes si activé.
						if ($config_nbr_columns > 1)
						{
							$new_row = (($i % $config_nbr_columns) == 0 && $i > 0);
							$i++;
						}
						else
						{
							$new_row = false;
						}

						$timestamp = new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, $row['timestamp']);
						$last_release = max($last_release, $row['start']);

						$comments_topic = new CommentsTopic();
						$comments_topic->set_module_id('news');
						$comments_topic->set_id_in_module($row['id']);
		
						$tpl->assign_block_vars('news', array(
							'ID' => $row['id'],
							'C_NEWS_ROW' => $new_row,
							'U_SYNDICATION' => SyndicationUrlBuilder::rss('news', $row['idcat'])->rel(),
							'U_LINK' => 'news' . url('.php?id=' . $row['id'], '-' . $row['idcat'] . '-' . $row['id'] . '+' . Url::encode_rewrite($row['title']) . '.php'),
							'TITLE' => $row['title'],
							'U_COM' => $config_activ_com ? '<a href="'. PATH_TO_ROOT .'/news/news' . url('.php?id=' . $row['id'] . '&amp;com=0', '-' . $row['idcat'] . '-' . $row['id'] . '+' . Url::encode_rewrite($row['title']) . '.php?com=0') .'">'. CommentsService::get_number_and_lang_comments($comments_topic) . '</a>' : '',
							'C_EDIT' =>  $User->check_auth($config_auth, AUTH_NEWS_MODERATE) || $User->check_auth($config_auth, AUTH_NEWS_WRITE) && $row['user_id'] == $User->get_attribute('user_id'),
							'C_DELETE' =>  $User->check_auth($config_auth, AUTH_NEWS_MODERATE),
							'C_IMG' => !empty($row['img']),
							'IMG' => FormatingHelper::second_parse_url($row['img']),
							'IMG_DESC' => $row['alt'],
							'C_ICON' => $config_activ_icon,
							'U_CAT' => !empty($row['idcat']) ? 'news' . url('.php?cat=' . $row['idcat'], '-' . $row['idcat'] . '+' . Url::encode_rewrite($NEWS_CAT[$row['idcat']]['name']) . '.php') : '',
							'ICON' => !empty($row['idcat']) ? FormatingHelper::second_parse_url($NEWS_CAT[$row['idcat']]['image']) : '',
							'CONTENTS' => FormatingHelper::second_parse($row['contents']),
							'EXTEND_CONTENTS' => !empty($row['extend_contents']) ? '<a style="font-size:10px" href="' . PATH_TO_ROOT . '/news/news' . url('.php?id=' . $row['id'], '-0-' . $row['id'] . '.php') . '" onclick="document.location = \'count.php?id='. $row['id'] .'\';">[' . $NEWS_LANG['extend_contents'] . ']</a><br /><br />' : '',
							'PSEUDO' => $config_display_author && !empty($row['login']) ? $row['login'] : '',
							'U_USER_ID' => UserUrlBuilder::profile($row['user_id'])->absolute(),
							'LEVEL' =>	isset($row['level']) ? $level[$row['level']] : '',
							'DATE' => $config_display_date ? sprintf($NEWS_LANG['on'], $timestamp->format(DATE_FORMAT_SHORT, TIMEZONE_AUTO)) : '',
							'FEED_MENU' => Feed::get_feed_menu(FEED_URL)
						));
					}
				}

				$this->sql_querier->query_close($result);
			}
			// News en list => news_list.tpl
			else
			{

				$result = $this->sql_querier->query_while("SELECT n.id, n.idcat, n.title, n.timestamp, n.start, n.nbr_com
					FROM " . DB_TABLE_NEWS . " n " . $where . "
					ORDER BY n.timestamp DESC" . $this->sql_querier->limit($first_msg, $config_pagination_news), __LINE__, __FILE__);

				while ($row = $this->sql_querier->fetch_assoc($result))
				{
					if($User->check_auth($NEWS_CAT[$row['idcat']]['auth'], AUTH_NEWS_READ))
					{
						// Séparation des news en colonnes si activé.
						if ($config_nbr_columns > 1)
						{
							$new_row = ($i % $config_nbr_columns) == 0 && $i > 0;
							$i++;
						}
						else
						{
							$new_row = false;
						}

						$timestamp = new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, $row['timestamp']);
						$last_release = max($last_release, $row['start']);

						$tpl->assign_block_vars('list', array(
							'ID' => $row['id'],
							'C_NEWS_ROW' => $new_row,
							'ICON' => $config_activ_icon ? FormatingHelper::second_parse_url($NEWS_CAT[$row['idcat']]['image']) : 0,
							'U_CAT' => 'news' . url('.php?cat=' . $row['idcat'], '-' . $row['idcat'] . '+' . Url::encode_rewrite($NEWS_CAT[$row['idcat']]['name']) . '.php'),
							'DATE' => $timestamp->format(DATE_FORMAT_TINY, TIMEZONE_AUTO),
							'U_NEWS' => 'news' . url('.php?id=' . $row['id'], '-' . $row['idcat'] . '-' . $row['id'] . '+' . Url::encode_rewrite($row['title']) . '.php'),
							'TITLE' => $row['title'],
							'C_COM' => $config_activ_com ? true : false,
							'COM' => $row['nbr_com']
						));
					}
				}

				$this->sql_querier->query_close($result);
			}
		}


		// Var commune
		$tpl->put_all(array(
			'L_ALERT_DELETE_NEWS' => $NEWS_LANG['alert_delete_news'],
			'U_SYNDICATION' => SyndicationUrlBuilder::rss('news', $cat)->rel(),
			'L_SYNDICATION' => $LANG['syndication'],
			'C_ADD_OR_WRITER' => $c_add || $c_writer,
			'C_ADD' => $c_add,
			'U_ADD' => url(PATH_TO_ROOT . '/news/management.php?new=1'),
			'L_ADD' => $NEWS_LANG['add_news'],
			'C_WRITER' => $c_writer,
			'L_WRITER' => $NEWS_LANG['waiting_news'],
			'C_ADMIN' => $User->check_level(User::ADMIN_LEVEL),
			'U_ADMIN' => $cat > 0 ? url('admin_news_cat.php?edit=' . $cat) : url('admin_news_config.php#preview_description'),
			'L_ADMIN' => $LANG['edit'],
			'L_EDIT' => $LANG['edit'],
			'L_DELETE' => $LANG['delete'],
			'L_LAST_NEWS' => $NEWS_LANG['last_news'],
			'FEED_MENU' => Feed::get_feed_menu(FEED_URL)
		));

		// Affichage de l'édito
		if($cat > 0)
		{
			$tpl->put_all(array(
				'C_EDITO' => !empty($NEWS_CAT[$cat]['desc']) ? true : false,
				'C_CAT' => true,
				'EDITO_NAME' => $NEWS_CAT[$cat]['name'],
				'EDITO_CONTENTS' => !empty($NEWS_CAT[$cat]['desc']) ? FormatingHelper::second_parse($NEWS_CAT[$cat]['desc']) : ''
			));
		}
		elseif($config_activ_edito)
		{
			$tpl->put_all(array(
				'C_EDITO' => true,
				'EDITO_NAME' => $config_edito_title,
				'EDITO_CONTENTS' => FormatingHelper::second_parse($config_edito)
			));
		}
		else
		{
			$tpl->put_all(array('C_EDITO' => false));
		}

		// Vérification de la date de parution des news.
		if (file_exists(NEWS_MASTER_0))
		{
			$date_cache = new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, filemtime(NEWS_MASTER_0));
			$date_release = new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, $last_release);

			if ($date_cache->get_timestamp() < $date_release->get_timestamp())
			{
				Feed::clear_cache('news');
			}
		}

		return $tpl;
	}
}
?>