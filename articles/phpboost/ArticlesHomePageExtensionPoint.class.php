<?php
/*##################################################
 *                     ArticlesHomePageExtensionPoint.class.php
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

class ArticlesHomePageExtensionPoint implements HomePageExtensionPoint
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
		global $ARTICLES_LANG;
		
		load_module_lang('articles');
		
		return $ARTICLES_LANG['articles'];
	}
	
	private function get_view()
	{
		global $idartcat, $Session, $User, $invisible, $Cache, $ARTICLES_CAT, $CONFIG_ARTICLES, $LANG, $ARTICLES_LANG, $Bread_crumb;
		
		require_once(PATH_TO_ROOT . '/articles/articles_begin.php'); 
		
		//checking authorization
		if (!$User->check_auth($ARTICLES_CAT[$idartcat]['auth'], AUTH_ARTICLES_READ))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
		
		$now = new Date(DATE_NOW, TIMEZONE_AUTO);
		$Pagination = new DeprecatedPagination();
		
		if ($idartcat > 0)
		{
			if (!isset($ARTICLES_CAT[$idartcat]) || $ARTICLES_CAT[$idartcat]['visible'] == 0)
			{
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			}

			$cat_links = '';
			//$cat_links .= ' <a href="articles' . url('.php?cat=' . $idartcat, '-' . $idartcat . '.php') . '">' . $ARTICLES_CAT[$idartcat]['name'] . '</a>';
			$clause_cat = " WHERE ac.id_parent = '" . $idartcat . "' AND ac.visible = 1";
		}
		else //Racine.
		{
			//$cat_links = ' <a href="articles.php">' . $ARTICLES_LANG['title_articles'] . '</a>';
			$clause_cat = " WHERE ac.id_parent = '0' AND ac.visible = 1";
		}

		$tpl = new FileTemplate('articles/articles_cat.tpl');

		$nbr_articles = $this->sql_querier->query("SELECT COUNT(*) FROM " . DB_TABLE_ARTICLES . " WHERE visible = 1 AND idcat = '" . $idartcat . "' AND start <= '" . $now->get_timestamp() . "' AND (end >= '" . $now->get_timestamp() . "' OR end = 0)", __LINE__, __FILE__);
		$nbr_articles_invisible = $this->sql_querier->query("SELECT COUNT(*) FROM " . DB_TABLE_ARTICLES . " WHERE idcat = '" . $idartcat . "' AND user_id != -1 AND (visible = 0 OR (start > '" . $now->get_timestamp() . "' AND (end <= '" . $now->get_timestamp() . "' OR start = 0)))", __LINE__, __FILE__);
		$rewrite_title = Url::encode_rewrite($ARTICLES_CAT[$idartcat]['name']);

		$get_sort = retrieve(GET, 'sort', '');
		$get_mode = retrieve(GET, 'mode', '');
		$selected_fields = array(
			'alpha' => '',
			'view' => '',
			'date' => '',
			'com' => '',
			'note' => '',
			'author'=>'',
			'asc' => '',
			'desc' => '',
		);

		switch ($get_sort)
		{
			case 'alpha' :
				$sort = 'title';
				$selected_fields['alpha'] = ' selected="selected"';
				break;
			case 'com' :
				$sort = 'com.number_comments';
				$selected_fields['com'] = ' selected="selected"';
				break;
			case 'date' :
				$sort = 'timestamp';
				$selected_fields['date'] = ' selected="selected"';
				break;
			case 'view' :
				$sort = 'views';
				$selected_fields['view'] = ' selected="selected"';
				break;
			case 'note' :
				$sort = 'note.average_notes';
				$selected_fields['note'] = ' selected="selected"';
				break;
			case 'author' :
				$sort = 'a.user_id';
				$selected_fields['author'] = ' selected="selected"';
				break;
			default :
				$sort = 'timestamp';
				$selected_fields['date'] = ' selected="selected"';
		}

		$mode = ($get_mode == 'asc') ? 'ASC' : 'DESC';
		if ($mode == 'ASC')
		$selected_fields['asc'] = ' selected="selected"';
		else
		$selected_fields['desc'] = ' selected="selected"';
		
		$group_color = User::get_group_color($User->get_attribute('user_groups'), $User->get_attribute('level'));
		$array_class = array('member', 'modo', 'admin');

		$tpl->put_all(array(
			'C_WRITE'=> $User->check_auth($ARTICLES_CAT[$idartcat]['auth'], AUTH_ARTICLES_WRITE),
			'C_MODERATE' => $User->check_auth($ARTICLES_CAT[$idartcat]['auth'], AUTH_ARTICLES_MODERATE),
			'C_ADD' => $User->check_auth($ARTICLES_CAT[$idartcat]['auth'], AUTH_ARTICLES_CONTRIBUTE) || $User->check_auth($ARTICLES_CAT[$idartcat]['auth'], AUTH_ARTICLES_WRITE),
			'C_EDIT' => $User->check_auth($ARTICLES_CAT[$idartcat]['auth'], AUTH_ARTICLES_MODERATE) || $User->check_auth($ARTICLES_CAT[$idartcat]['auth'], AUTH_ARTICLES_WRITE) ,
			'C_ARTICLES_WAITING' => $User->check_auth($ARTICLES_CAT[$idartcat]['auth'], AUTH_ARTICLES_MODERATE),
			'IDCAT' => $idartcat,
			'SELECTED_ALPHA' => $selected_fields['alpha'],
			'SELECTED_COM' => $selected_fields['com'],
			'SELECTED_DATE' => $selected_fields['date'],
			'SELECTED_VIEW' => $selected_fields['view'],
			'SELECTED_NOTE' => $selected_fields['note'],
			'SELECTED_AUTHOR' => $selected_fields['author'],
			'SELECTED_ASC' => $selected_fields['asc'],
			'SELECTED_DESC' => $selected_fields['desc'],
			'TARGET_ON_CHANGE_ORDER' => PATH_TO_ROOT . '/articles/' . url('articles.php?cat=' . $idartcat . ($invisible ? '&invisible=1&' : '&'), 'articles-' . $idartcat . '.php?' . ($invisible ? 'invisible=1&' : '')),
			'L_CAT_NAME' => $idartcat > 0 ? $ARTICLES_CAT[$idartcat]['name'] : $ARTICLES_LANG['title_articles'],
			'L_DATE' => $LANG['date'],
			'L_VIEW' => $LANG['views'],
			'L_NOTE' => $LANG['note'],
			'L_COM' => $LANG['com'],
			'L_DESC' => $LANG['desc'],
			'L_ASC' => $LANG['asc'],
			'L_TITLE'=> $LANG['title'],
			'L_EDIT' => $LANG['edit'],
			'L_ADD' => $ARTICLES_LANG['articles_add'],
			'L_DELETE' => $LANG['delete'],
			'L_WRITTEN' => $LANG['written_by'],
			'L_ARTICLES' => $ARTICLES_LANG['articles'],
			'L_AUTHOR' => $ARTICLES_LANG['author'],
			'L_ORDER_BY' => $ARTICLES_LANG['order_by'],
			'L_ALERT_DELETE_ARTICLE' => $ARTICLES_LANG['alert_delete_article'],
			'L_TOTAL_ARTICLE' => $nbr_articles > 0 ? sprintf($ARTICLES_LANG['nbr_articles_info'], $nbr_articles) : '',
			'L_NO_ARTICLES' => $nbr_articles == 0 && $idartcat > 0 ? $ARTICLES_LANG['none_article'] : '',
			'L_ARTICLES_INDEX' => $ARTICLES_LANG['title_articles'],
			'L_ARTICLES_WAITING' => $ARTICLES_LANG['waiting_articles'],
			'L_CATEGORIES' => ($ARTICLES_CAT[$idartcat]['order'] >= 0) ? $ARTICLES_LANG['sub_categories'] : $LANG['categories'],
			'U_ADD' => PATH_TO_ROOT . url('/articles/management.php?new=1&amp;cat=' . $idartcat),
			'U_EDIT'=> !empty($idartcat) ? PATH_TO_ROOT . url('/articles/admin_articles_cat.php?edit='.$idartcat) : PATH_TO_ROOT . url('/articles/admin_articles_config.php'),
			'U_ARTICLES_WAITING'=> PATH_TO_ROOT . '/articles/articles.php?invisible=1&amp;cat='.$idartcat,
			'L_ARTICLES_WAINTING' => $ARTICLES_LANG['waiting_articles'],
			'U_ARTICLES_ALPHA_TOP' => url('.php?sort=alpha&amp;mode=desc&amp;cat=' . $idartcat, '-' . $idartcat . '+' . $rewrite_title . '.php?sort=alpha&amp;mode=desc'),
			'U_ARTICLES_ALPHA_BOTTOM' => url('.php?sort=alpha&amp;mode=asc&amp;cat=' . $idartcat, '-' . $idartcat . '+' . $rewrite_title . '.php?sort=alpha&amp;mode=asc'),
			'U_ARTICLES_DATE_TOP' => url('.php?sort=date&amp;mode=desc&amp;cat=' . $idartcat, '-' . $idartcat . '+' . $rewrite_title . '.php?sort=date&amp;mode=desc'),
			'U_ARTICLES_DATE_BOTTOM' => url('.php?sort=date&amp;mode=asc&amp;cat=' . $idartcat, '-' . $idartcat . '+' . $rewrite_title . '.php?sort=date&amp;mode=asc'),
			'U_ARTICLES_VIEW_TOP' => url('.php?sort=view&amp;mode=desc&amp;cat=' . $idartcat, '-' . $idartcat . '+' . $rewrite_title . '.php?sort=view&amp;mode=desc'),
			'U_ARTICLES_VIEW_BOTTOM' => url('.php?sort=view&amp;mode=asc&amp;cat=' . $idartcat, '-' . $idartcat . '+' . $rewrite_title . '.php?sort=view&amp;mode=asc'),
			'U_ARTICLES_NOTE_TOP' => url('.php?sort=note&amp;mode=desc&amp;cat=' . $idartcat, '-' . $idartcat . '+' . $rewrite_title . '.php?sort=note&amp;mode=desc'),
			'U_ARTICLES_NOTE_BOTTOM' => url('.php?sort=note&amp;mode=asc&amp;cat=' . $idartcat, '-' . $idartcat . '+' . $rewrite_title . '.php?sort=note&amp;mode=asc'),
			'U_ARTICLES_COM_TOP' => url('.php?sort=com&amp;mode=desc&amp;cat=' . $idartcat, '-' . $idartcat . '+' . $rewrite_title . '.php?sort=com&amp;mode=desc'),
			'U_ARTICLES_COM_BOTTOM' => url('.php?sort=com&amp;mode=asc&amp;cat=' . $idartcat, '-' . $idartcat . '+' . $rewrite_title . '.php?sort=com&amp;mode=asc')
		));

		$unget = (!empty($get_sort) && !empty($mode)) ? '?sort=' . $get_sort . '&amp;mode=' . $get_mode : '';

		//On créé une pagination si le nombre de fichiers est trop important.

		$Pagination = new DeprecatedPagination();

		//Catégories non autorisées.
		$unauth_cats_sql = array();
		foreach ($ARTICLES_CAT as $id => $key)
		{
			if (!$User->check_auth($ARTICLES_CAT[$id]['auth'], AUTH_ARTICLES_READ))
			$unauth_cats_sql[] = $id;
		}
		$nbr_unauth_cats = count($unauth_cats_sql);
		$clause_unauth_cats = ($nbr_unauth_cats > 0) ? " AND ac.id NOT IN (" . implode(', ', $unauth_cats_sql) . ")" : '';

		##### Catégories disponibles #####
		
		$result = $this->sql_querier->query_while("SELECT ac.id, ac.name, ac.auth, ac.description, ac.image, ac.nbr_articles_visible AS nbr_articles
		FROM " . DB_TABLE_ARTICLES_CAT . " ac
		" . $clause_cat . $clause_unauth_cats . "
		ORDER BY ac.id_parent, ac.c_order
		" . $this->sql_querier->limit($Pagination->get_first_msg($CONFIG_ARTICLES['nbr_cat_max'], 'pcat'), $CONFIG_ARTICLES['nbr_cat_max']), __LINE__, __FILE__);

		$total_cat = 0;
		while ($row = $this->sql_querier->fetch_assoc($result))
		{
			$tpl->assign_block_vars('cat_list', array(
				'IDCAT' => $row['id'],
				'CAT' => $row['name'],
				'DESC' => FormatingHelper::second_parse($row['description']),
				'ICON_CAT' => !empty($row['image']) ? '<a href="articles' . url('.php?cat=' . $row['id'], '-' . $row['id'] . '+' . Url::encode_rewrite($row['name']) . '.php') . '"><img src="' . $row['image'] . '" alt="" class="valign_middle" /></a><br />' : '',
				'U_CAT' => url('.php?cat=' . $row['id'], '-' . $row['id'] . '+' . Url::encode_rewrite($row['name']) . '.php'),
				'L_NBR_ARTICLES' => sprintf($ARTICLES_LANG['nbr_articles_info'], $row['nbr_articles']),
			));
			$total_cat++;
		}
		
		if ($total_cat > 0)
		{
			$nbr_column_cats = ($total_cat > $CONFIG_ARTICLES['nbr_column']) ? $CONFIG_ARTICLES['nbr_column'] : $total_cat;
			$nbr_column_cats = !empty($nbr_column_cats) ? $nbr_column_cats : 1;
			$column_width_cats = floor(100/$nbr_column_cats);
			
			$tpl->put_all(array(
				'C_ARTICLES_CAT' => true,
				'COLUMN_WIDTH_CAT' => $column_width_cats,
				'PAGINATION_CAT' => $Pagination->display('articles' . url('.php' . (!empty($unget) ? $unget . '&amp;' : '?') . 'cat=' . $idartcat . '&amp;pcat=%d', '-' . $idartcat . '-0+' . $rewrite_title . '.php?pcat=%d' . $unget), $total_cat , 'pcat', $CONFIG_ARTICLES['nbr_cat_max'], 3)
			));
		}
			
		$this->sql_querier->query_close($result);

		if ($nbr_articles > 0 || $invisible)
		{
			$tpl->put_all(array(
				'C_ARTICLES_LINK' => true,
				'PAGINATION' => $Pagination->display('articles' . url('.php' . (!empty($unget) ? $unget . '&amp;' : '?') . 'cat=' . $idartcat . '&amp;p=%d', '-' . $idartcat . '-0-%d+' . $rewrite_title . '.php' . $unget), $nbr_articles , 'p', $CONFIG_ARTICLES['nbr_articles_max'], 3),
				'CAT' => $ARTICLES_CAT[$idartcat]['name']
			));
			
			$notation = new Notation();
			$notation->set_module_name('articles');
			$notation->set_notation_scale($CONFIG_ARTICLES['note_max']);

			$result = $this->sql_querier->query_while("SELECT a.id, a.title, a.description, a.icon, a.timestamp, a.views, a.user_id, m.user_id, m.login, m.level, note.average_notes, note.number_notes, com.number_comments
			FROM " . DB_TABLE_ARTICLES . " a
			LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = a.user_id
			LEFT JOIN " . DB_TABLE_COMMENTS_TOPIC . " com ON com.id_in_module = a.id AND com.module_id = 'articles'
			LEFT JOIN " . DB_TABLE_AVERAGE_NOTES . " note ON note.id_in_module = a.id AND note.module_name = 'articles'
			WHERE a.visible = 1 AND a.idcat = '" . $idartcat .	"' AND a.start <= '" . $now->get_timestamp() . "' AND (a.end >= '" . $now->get_timestamp() . "' OR a.end = 0)
			ORDER BY " . $sort . " " . $mode .
			$this->sql_querier->limit($Pagination->get_first_msg($CONFIG_ARTICLES['nbr_articles_max'], 'p'), $CONFIG_ARTICLES['nbr_articles_max']), __LINE__, __FILE__);

			while ($row = $this->sql_querier->fetch_assoc($result))
			{
				//On reccourci le lien si il est trop long.
				$fichier = (strlen($row['title']) > 45 ) ? substr(TextHelper::html_entity_decode($row['title']), 0, 45) . '...' : $row['title'];
				
				$notation->set_id_in_module($row['id']);
				
				$tpl->assign_block_vars('articles', array(
					'NAME' => $row['title'],
					'ICON' => !empty($row['icon']) ? '<a href="articles' . url('.php?id=' . $row['id'] . '&amp;cat=' . $idartcat, '-' . $idartcat . '-' . $row['id'] . '+' . Url::encode_rewrite($fichier) . '.php') . '"><img src="' . $row['icon'] . '" alt="" class="valign_middle" /></a>' : '',
					'CAT' => $ARTICLES_CAT[$idartcat]['name'],
					'DATE' => gmdate_format('date_format_short', $row['timestamp']),
					'COMPT' => $row['views'],
					'NOTE' => ($row['number_notes'] > 0) ? NotationService::display_static_image($notation, $row['average_notes']) : $LANG['no_note'],
					'COM' => empty($row['number_comments']) ? '0' : $row['number_comments'],
					'DESCRIPTION' => FormatingHelper::second_parse($row['description']),
					'U_ARTICLES_PSEUDO' => '<a href="' . UserUrlBuilder::profile($row['user_id'])->absolute() . '" class="' . $array_class[$row['level']] . '"' . (!empty($group_color) ? ' style="color:' . $group_color . '"' : '') . '>' . TextHelper::wordwrap_html($row['login'], 19) . '</a>',
					'U_ARTICLES_LINK' => url('.php?id=' . $row['id'] . '&amp;cat=' . $idartcat, '-' . $idartcat . '-' . $row['id'] . '+' . Url::encode_rewrite($fichier) . '.php'),
					'U_ARTICLES_LINK_COM' => url('.php?cat=' . $idartcat . '&amp;id=' . $row['id'] . '&amp;com=0', '-' . $idartcat . '-' . $row['id'] . '.php?com=0') . '#comments_list',
					'U_ADMIN_EDIT_ARTICLES' => url('management.php?edit=' . $row['id']),
					'U_ADMIN_DELETE_ARTICLES' => url('management.php?del=' . $row['id'] . '&amp;token=' . $Session->get_token()),
				));
			}

			if($invisible && ($User->check_auth($ARTICLES_CAT[$idartcat]['auth'], AUTH_ARTICLES_MODERATE)))
			{
				$tpl->put_all(array(
					'C_INVISIBLE' => true,
					'L_ARTICLES_WAINTING' => $ARTICLES_LANG['publicate_articles'],
					'L_NO_ARTICLES_WAITING' => ($nbr_articles_invisible == 0) ? $ARTICLES_LANG['no_articles_waiting'] : '',
					'U_ARTICLES_WAITING' => 'articles.php?cat='.$idartcat,
				));


				$result = $this->sql_querier->query_while("SELECT a.id, a.title, a.icon, a.timestamp, a.views, a.user_id, a.description, m.user_id, m.login, m.level, note.average_notes, note.number_notes, com.number_comments
				FROM " . DB_TABLE_ARTICLES . " a
				LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = a.user_id
				LEFT JOIN " . DB_TABLE_COMMENTS_TOPIC . " com ON com.id_in_module = a.id AND com.module_id = 'articles'
				LEFT JOIN " . DB_TABLE_AVERAGE_NOTES . " note ON note.id_in_module = a.id AND note.module_name = 'articles'
				WHERE a.visible = 0 OR (a.start > '" . $now->get_timestamp() . "' AND (a.end <= '" . $now->get_timestamp() . "' OR a.start = 0)) AND a.idcat = '" . $idartcat .	"'  AND a.user_id != -1
				ORDER BY " . $sort . " " . $mode .
				$this->sql_querier->limit($Pagination->get_first_msg($CONFIG_ARTICLES['nbr_articles_max'], 'p'), $CONFIG_ARTICLES['nbr_articles_max']), __LINE__, __FILE__);

				while ($row = $this->sql_querier->fetch_assoc($result))
				{
					//On reccourci le lien si il est trop long.
					$fichier = (strlen($row['title']) > 45 ) ? substr(TextHelper::html_entity_decode($row['title']), 0, 45) . '...' : $row['title'];
					
					$notation->set_id_in_module($row['id']);
					
					$tpl->assign_block_vars('articles_invisible', array(
						'NAME' => $row['title'],
						'ICON' => !empty($row['icon']) ? '<a href="articles' . url('.php?id=' . $row['id'] . '&amp;cat=' . $idartcat, '-' . $idartcat . '-' . $row['id'] . '+' . Url::encode_rewrite($fichier) . '.php') . '"><img src="' . $row['icon'] . '" alt="" class="valign_middle" /></a>' : '',
						'CAT' => $ARTICLES_CAT[$idartcat]['name'],
						'DATE' => gmdate_format('date_format_short', $row['timestamp']),
						'COMPT' => $row['views'],
						'NOTE' => ($row['number_notes'] > 0) ? NotationService::display_static_image($notation, $row['average_notes']) : $LANG['no_note'],
						'COM' => empty($row['number_comments']) ? '0' : $row['number_comments'],
						'DESCRIPTION' => FormatingHelper::second_parse($row['description']),
						'U_ARTICLES_PSEUDO'=>'<a href="' . UserUrlBuilder::profile($row['user_id'])->absolute() . '" class="' . $array_class[$row['level']] . '"' . (!empty($group_color) ? ' style="color:' . $group_color . '"' : '') . '>' . TextHelper::wordwrap_html($row['login'], 19) . '</a>',
						'U_ARTICLES_LINK' => url('.php?id=' . $row['id'] . '&amp;cat=' . $idartcat, '-' . $idartcat . '-' . $row['id'] . '+' . Url::encode_rewrite($fichier) . '.php'),
						'U_ARTICLES_LINK_COM' => url('.php?cat=' . $idartcat . '&amp;id=' . $row['id'] . '&amp;com=%s', '-' . $idartcat . '-' . $row['id'] . '.php?com=0'),
						'U_ADMIN_EDIT_ARTICLES' => url('management.php?edit=' . $row['id']),
						'U_ADMIN_DELETE_ARTICLES' => url('management.php?del=' . $row['id'] . '&amp;token=' . $Session->get_token()),
					));
				}
			}
			$this->sql_querier->query_close($result);
		}
		return $tpl;
	}
}
?>