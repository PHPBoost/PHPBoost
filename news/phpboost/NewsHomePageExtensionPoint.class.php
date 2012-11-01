<?php
/*##################################################
 *                     NewsHomePageExtensionPoint.class.php
 *                            -------------------
 *   begin                : January 27, 2012
 *   copyright            : (C) 2012 Kevin MASSY
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
		
		load_module_lang('news');
		
		return $NEWS_LANG['news'];
	}
	
	private function get_view()
	{
		global $User, $Cache, $Bread_crumb, $NEWS_CONFIG, $NEWS_CAT, $NEWS_LANG, $LANG, $Session;

		// Begin.
		load_module_lang('news');
		$Cache->load('news');

		require_once PATH_TO_ROOT . '/news/news_constants.php';

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

		$filetpl = $NEWS_CONFIG['type'] ? 'news/news_block.tpl' :'news/news_list.tpl';

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
			if ($NEWS_CONFIG['activ_pagin']) // Pagination activée, sinon affichage lien vers les archives.
			{
				$show_pagin = $Pagination->display(PATH_TO_ROOT . '/news/news' . url('.php?p=%d', '-0-0-%d.php'), $NEWS_CONFIG['nbr_news'], 'p', $NEWS_CONFIG['pagination_news'], 3);
				$first_msg = $Pagination->get_first_msg($NEWS_CONFIG['pagination_news'], 'p');
			}
			elseif ($arch) // Pagination des archives.
			{
				$show_pagin = $Pagination->display(PATH_TO_ROOT . '/news/news' . url('.php?arch=1&amp;p=%d', '-0-0-%d.php?arch=1'), $NEWS_CONFIG['nbr_news'] - $NEWS_CONFIG['pagination_news'], 'p', $NEWS_CONFIG['pagination_arch'], 3);
				$first_msg = $NEWS_CONFIG['pagination_news'] + $Pagination->get_first_msg($NEWS_CONFIG['pagination_arch'], 'p');
				$NEWS_CONFIG['pagination_news'] = $NEWS_CONFIG['pagination_arch'];
			}
			else // Affichage du lien vers les archives.
			{
				$show_pagin = (($NEWS_CONFIG['nbr_news'] > $NEWS_CONFIG['pagination_news']) && ($NEWS_CONFIG['nbr_news'] != 0)) ? '<a href="' . PATH_TO_ROOT . '/news/news.php' . '?arch=1" title="' . $NEWS_LANG['display_archive'] . '">' . $NEWS_LANG['display_archive'] . '</a>' : '';
				$first_msg = 0;
			}
			$tpl->put_all(array('PAGINATION' => $show_pagin));

			// News en bloc => news_block.tpl
			if($NEWS_CONFIG['type'] == 1)
			{
				if ($NEWS_CONFIG['nbr_column'] > 1)
				{
					$i = 0;
					$NEWS_CONFIG['nbr_column'] = !empty($NEWS_CONFIG['nbr_column']) ? $NEWS_CONFIG['nbr_column'] : 1;
					$column_width = floor(100 / $NEWS_CONFIG['nbr_column']);

					$tpl->put_all(array(
						'C_NEWS_BLOCK_COLUMN' => true,
						'COLUMN_WIDTH' => $column_width
					));
				}
				
				$result = $this->sql_querier->query_while("SELECT n.contents, n.extend_contents, n.title, n.id, n.idcat, n.timestamp, n.start, n.user_id, n.img, n.alt, m.login, m.level
					FROM " . DB_TABLE_NEWS . " n
					LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = n.user_id
					".$where."
					ORDER BY n.timestamp DESC
					" . $this->sql_querier->limit($first_msg, $NEWS_CONFIG['pagination_news']), __LINE__, __FILE__);

				while ($row = $this->sql_querier->fetch_assoc($result))
				{

					if($User->check_auth($NEWS_CAT[$row['idcat']]['auth'], AUTH_NEWS_READ))
					{
						// Séparation des news en colonnes si activé.
						if ($NEWS_CONFIG['nbr_column'] > 1)
						{
							$new_row = (($i % $NEWS_CONFIG['nbr_column']) == 0 && $i > 0);
							$i++;
						}
						else
						{
							$new_row = false;
						}

						$timestamp = new Date(DATE_TIMESTAMP, TIMEZONE_AUTO, $row['timestamp']);
						$last_release = max($last_release, $row['start']);
		
						$tpl->assign_block_vars('news', array(
							'ID' => $row['id'],
							'C_NEWS_ROW' => $new_row,
							'U_SYNDICATION' => SyndicationUrlBuilder::rss('news', $row['idcat'])->rel(),
							'U_LINK' => PATH_TO_ROOT .'/news/news' . url('.php?id=' . $row['id'], '-' . $row['idcat'] . '-' . $row['id'] . '+' . Url::encode_rewrite($row['title']) . '.php'),
							'TITLE' => $row['title'],
							'U_COM' => $NEWS_CONFIG['activ_com'] ? '<a href="'. PATH_TO_ROOT .'/news/news' . url('.php?id=' . $row['id'] . '&amp;com=0', '-' . $row['idcat'] . '-' . $row['id'] . '+' . Url::encode_rewrite($row['title']) . '.php?com=0') .'#comments_list">'. CommentsService::get_number_and_lang_comments('news', $row['id']) . '</a>' : '',
							'C_EDIT' =>  $User->check_auth($NEWS_CONFIG['global_auth'], AUTH_NEWS_MODERATE) || $User->check_auth($NEWS_CONFIG['global_auth'], AUTH_NEWS_WRITE) && $row['user_id'] == $User->get_attribute('user_id'),
							'C_DELETE' =>  $User->check_auth($NEWS_CONFIG['global_auth'], AUTH_NEWS_MODERATE),
							'C_IMG' => !empty($row['img']),
							'IMG' => FormatingHelper::second_parse_url($row['img']),
							'IMG_DESC' => $row['alt'],
							'C_ICON' => $NEWS_CONFIG['activ_icon'] && !empty($row['idcat']),
							'U_CAT' => !empty($row['idcat']) ? PATH_TO_ROOT . '/news/news' . url('.php?cat=' . $row['idcat'], '-' . $row['idcat'] . '+' . Url::encode_rewrite($NEWS_CAT[$row['idcat']]['name']) . '.php') : '',
							'ICON' => !empty($row['idcat']) ? FormatingHelper::second_parse_url($NEWS_CAT[$row['idcat']]['image']) : '',
							'CONTENTS' => FormatingHelper::second_parse($row['contents']),
							'EXTEND_CONTENTS' => !empty($row['extend_contents']) ? '<a style="font-size:10px" href="' . PATH_TO_ROOT . '/news/news' . url('.php?id=' . $row['id'], '-0-' . $row['id'] . '.php') . '" onclick="document.location = \'count.php?id='. $row['id'] .'\';">[' . $NEWS_LANG['extend_contents'] . ']</a><br /><br />' : '',
							'PSEUDO' => $NEWS_CONFIG['display_author'] && !empty($row['login']) ? $row['login'] : '',
							'U_USER_ID' => UserUrlBuilder::profile($row['user_id'])->absolute(),
							'LEVEL' =>	isset($row['level']) ? $level[$row['level']] : '',
							'DATE' => $NEWS_CONFIG['display_date'] ? sprintf($NEWS_LANG['on'], $timestamp->format(DATE_FORMAT_SHORT, TIMEZONE_AUTO)) : '',
							'FEED_MENU' => Feed::get_feed_menu(FEED_URL)
						));
					}
				}

				$this->sql_querier->query_close($result);
			}
			// News en list => news_list.tpl
			else
			{
				if ($NEWS_CONFIG['nbr_column'] > 1)
				{
					$i = 0;
					$NEWS_CONFIG['nbr_column'] = !empty($NEWS_CONFIG['nbr_column']) ? $NEWS_CONFIG['nbr_column'] : 1;
					$column_width = floor(100 / $NEWS_CONFIG['nbr_column']);

					$tpl->put_all(array(
						'C_NEWS_LINK_COLUMN' => true,
						'COLUMN_WIDTH' => $column_width
					));
				}
				
				$result = $this->sql_querier->query_while("SELECT n.id, n.idcat, n.title, n.timestamp, n.start, com.number_comments
					FROM " . DB_TABLE_NEWS . " n 
					LEFT JOIN " . DB_TABLE_COMMENTS_TOPIC . " com ON com.id_in_module = n.id AND com.module_id = 'news'" . $where . "
					ORDER BY n.timestamp DESC" . $this->sql_querier->limit($first_msg, $NEWS_CONFIG['pagination_news']), __LINE__, __FILE__);

				while ($row = $this->sql_querier->fetch_assoc($result))
				{
					if($User->check_auth($NEWS_CAT[$row['idcat']]['auth'], AUTH_NEWS_READ))
					{
						// Séparation des news en colonnes si activé.
						if ($NEWS_CONFIG['nbr_column'] > 1)
						{
							$new_row = ($i % $NEWS_CONFIG['nbr_column']) == 0 && $i > 0;
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
							'ICON' => $NEWS_CONFIG['activ_icon'] ? FormatingHelper::second_parse_url($NEWS_CAT[$row['idcat']]['image']) : 0,
							'U_CAT' => PATH_TO_ROOT .'/news/news' . url('.php?cat=' . $row['idcat'], '-' . $row['idcat'] . '+' . Url::encode_rewrite($NEWS_CAT[$row['idcat']]['name']) . '.php'),
							'DATE' => $timestamp->format(DATE_FORMAT_TINY, TIMEZONE_AUTO),
							'U_NEWS' => PATH_TO_ROOT .'/news/news' . url('.php?id=' . $row['id'], '-' . $row['idcat'] . '-' . $row['id'] . '+' . Url::encode_rewrite($row['title']) . '.php'),
							'TITLE' => $row['title'],
							'C_COM' => $NEWS_CONFIG['activ_com'] ? true : false,
							'COM' => !empty($row['number_comments']) ? $row['number_comments'] : 0
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
			'L_NEWS_WAITING' => $NEWS_LANG['waiting_news'],
			'C_ADMIN' => $User->check_level(User::ADMIN_LEVEL),
			'U_ADMIN' => $cat > 0 ? url(PATH_TO_ROOT . '/news/admin_news_cat.php?edit=' . $cat) : url(PATH_TO_ROOT . '/news/admin_news_config.php#preview_description'),
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
		elseif($NEWS_CONFIG['activ_edito'])
		{
			$tpl->put_all(array(
				'C_EDITO' => true,
				'EDITO_NAME' => $NEWS_CONFIG['edito_title'],
				'EDITO_CONTENTS' => FormatingHelper::second_parse($NEWS_CONFIG['edito'])
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