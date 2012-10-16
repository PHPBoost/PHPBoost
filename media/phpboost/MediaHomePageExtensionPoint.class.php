<?php
/*##################################################
 *                     MediaHomePageExtensionPoint.class.php
 *                            -------------------
 *   begin                : February 07, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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

class MediaHomePageExtensionPoint implements HomePageExtensionPoint
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
		global $Cache, $MEDIA_CATS;
		
		$Cache->load('media');
		
		return $MEDIA_CATS[0]['name'];
	}
	
	private function get_view()
	{
		global $MEDIA_CATS, $LANG, $MEDIA_LANG, $MEDIA_CONFIG, $Cache, $id_cat, $id_media, $User, $auth_write, $Session, $Bread_crumb, $level;
		
		require_once(PATH_TO_ROOT . '/media/media_begin.php');
		
		$tpl = new FileTemplate('media/media.tpl');
		
		//if the category doesn't exist or is not visible
		if (empty($MEDIA_CATS[$id_cat]) || $MEDIA_CATS[$id_cat]['visible'] === false || !$User->check_auth($MEDIA_CATS[$id_cat]['auth'], MEDIA_AUTH_READ))
		{
			$controller = PHPBoostErrors::unexisting_category();
	        DispatchManager::redirect($controller);
		}
	
		$i = 1;
		//List of children categories
		foreach ($MEDIA_CATS as $id => $array)
		{
			if ($id != 0 && $array['visible'] && $array['id_parent'] == $id_cat && $User->check_auth($array['auth'], MEDIA_AUTH_READ))
			{
				if ($i % $MEDIA_CONFIG['nbr_column'] == 1)
				{
					$tpl->assign_block_vars('row', array());
				}
	
				$tpl->assign_block_vars('row.list_cats', array(
					'ID' => $id,
					'NAME' => $array['name'],
					'WIDTH' => floor(100 / (float)$MEDIA_CONFIG['nbr_column']),
					'SRC' => !empty($array['image']) ? $array['image'] : 'media_mini.png',
					'IMG_NAME' => addslashes($array['name']),
					'NUM_MEDIA' => ($array['active'] & MEDIA_NBR) !== 0 ? sprintf(($array['num_media'] > 1 ? $MEDIA_LANG['num_medias'] : $MEDIA_LANG['num_media']), $array['num_media']) : '',
					'U_CAT' => PATH_TO_ROOT . url('/media/media.php?cat=' . $id, '/media/media-0-' . $id . '+' . Url::encode_rewrite($array['name']) . '.php'),
					'U_ADMIN_CAT' => PATH_TO_ROOT . url('/media/admin_media_cats.php?edit=' . $id)
				));
	
				$i++;
			}
		}
	
		$tpl->put_all(array(
			'C_CATEGORIES' => true,
			'L_EDIT' => $LANG['edit'],
			'L_MODO_PANEL' => $LANG['modo_panel'],
			'L_DELETE' => $LANG['delete'],
			'L_UNAPROBED' => $MEDIA_LANG['unaprobed_media_short'],
			'TITLE' => $MEDIA_CATS[$id_cat]['name'],
			'C_ADMIN' => $User->check_level(User::ADMIN_LEVEL),
			'C_MODO' => $User->check_level(User::MODERATOR_LEVEL),
			'U_ADMIN_CAT' => $id_cat == 0 ? PATH_TO_ROOT . '/media/admin_media_config.php' : PATH_TO_ROOT . '/media/admin_media_cats.php?edit=' . $id_cat,
			'C_ADD_FILE' => $User->check_auth($MEDIA_CATS[$id_cat]['auth'], MEDIA_AUTH_WRITE) || $User->check_auth($MEDIA_CATS[$id_cat]['auth'], MEDIA_AUTH_CONTRIBUTION),
			'U_ADD_FILE' => PATH_TO_ROOT . '/media/media_action.php?add=' . $id_cat,
			'L_ADD_FILE' => $MEDIA_LANG['add_media'],
			'C_DESCRIPTION' => !empty($MEDIA_CATS[$id_cat]['desc']),
			'DESCRIPTION' => FormatingHelper::second_parse($MEDIA_CATS[$id_cat]['desc']),
			'C_SUB_CATS' => $i > 1,
			'ID_CAT' => $id_cat
		));
	
		//Contenu de la catégorie
		if ($MEDIA_CATS[$id_cat]['num_media'] > 0)
		{
			$get_sort = retrieve(GET, 'sort', '');
			$get_mode = retrieve(GET, 'mode', '');
			$mode = ($get_mode == 'asc') ? 'ASC' : 'DESC';
			$unget = (!empty($get_sort) && !empty($mode)) ? '?sort=' . $get_sort . '&amp;mode=' . $get_mode : '';
			$selected_fields = array('alpha' => '', 'date' => '', 'nbr' => '', 'note' => '', 'com' => '', 'asc' => '', 'desc' => '');
	
			switch ($get_sort)
			{
				case 'alpha':
					$sort = 'name';
					$selected_fields['alpha'] = ' selected="selected"';
					break;
				default:
				case 'date':
					$sort = 'timestamp';
					$selected_fields['date'] = ' selected="selected"';
					break;
				case 'nbr':
					$sort = 'counter';
					$selected_fields['nbr'] = ' selected="selected"';
					break;
				case 'note':
					$sort = 'average_notes';
					$selected_fields['note'] = ' selected="selected"';
					break;
				case 'com':
					$sort = 'com.number_comments';
					$selected_fields['com'] = ' selected="selected"';
					break;
			}
	
			if ($mode == 'ASC')
			{
				$selected_fields['asc'] = ' selected="selected"';
			}
			else
			{
				$selected_fields['desc'] = ' selected="selected"';
			}
	
			$tpl->put_all(array(
				'L_ALPHA' => $MEDIA_LANG['sort_title'],
				'L_DATE' => $LANG['date'],
				'L_NBR' => $MEDIA_LANG['sort_popularity'],
				'L_NOTE' => $LANG['note'],
				'L_COM' => $LANG['com'],
				'L_DESC' => $LANG['desc'],
				'L_ASC' => $LANG['asc'],
				'L_ORDER_BY' => $LANG['orderby'],
				'L_CONFIRM_DELETE_FILE' => str_replace('\'', '\\\'', $MEDIA_LANG['confirm_delete_media']),
				'SELECTED_ALPHA' => $selected_fields['alpha'],
				'SELECTED_DATE' => $selected_fields['date'],
				'SELECTED_NBR' => $selected_fields['nbr'],
				'SELECTED_NOTE' => $selected_fields['note'],
				'SELECTED_COM' => $selected_fields['com'],
				'SELECTED_ASC' => $selected_fields['asc'],
				'SELECTED_DESC' => $selected_fields['desc'],
				'A_COM' => ($MEDIA_CATS[$id_cat]['active'] & MEDIA_DL_COM) !== 0,
				'A_NOTE' => ($MEDIA_CATS[$id_cat]['active'] & MEDIA_DL_NOTE) !== 0,
				'A_USER' => ($MEDIA_CATS[$id_cat]['active'] & MEDIA_DL_USER) !== 0,
				'A_COUNTER' => ($MEDIA_CATS[$id_cat]['active'] & MEDIA_DL_COUNT) !== 0,
				'A_DATE' => ($MEDIA_CATS[$id_cat]['active'] & MEDIA_DL_DATE) !== 0,
				'A_DESC' => ($MEDIA_CATS[$id_cat]['active'] & MEDIA_DL_DESC) !== 0,
				'A_BLOCK' => ($MEDIA_CATS[$id_cat]['active'] & (MEDIA_DL_DATE + MEDIA_DL_COUNT + MEDIA_DL_COM + MEDIA_DL_NOTE + MEDIA_DL_USER)) !== 0
			));
	
			//On crée une pagination si le nombre de fichiers est trop important.
			
			$Pagination = new DeprecatedPagination();
	
			$tpl->put_all(array(
				'PAGINATION' => $Pagination->display(url('/media/media.php' . (!empty($unget) ? $unget . '&amp;' : '?') . 'cat=' . $id_cat . '&amp;p=%d', 'media-0-' . $id_cat . '-%d' . '+' . Url::encode_rewrite($MEDIA_CATS[$id_cat]['name']) . '.php' . $unget), $MEDIA_CATS[$id_cat]['num_media'], 'p', $MEDIA_CONFIG['pagin'], 3),
				'C_FILES' => true,
				'TARGET_ON_CHANGE_ORDER' => ServerEnvironmentConfig::load()->is_url_rewriting_enabled() ? 'media-0-' . $id_cat . '.php?' : 'media.php?cat=' . $id_cat . '&'
			));
	
			$result = $this->sql_querier->query_while("SELECT v.id, v.iduser, v.name, v.timestamp, v.counter, v.infos, v.contents, mb.login, mb.level, notes.average_notes, com.number_comments
				FROM " . PREFIX . "media AS v
				LEFT JOIN " . DB_TABLE_MEMBER . " AS mb ON v.iduser = mb.user_id
				LEFT JOIN " . DB_TABLE_AVERAGE_NOTES . " notes ON v.id = notes.id_in_module
				LEFT JOIN " . DB_TABLE_COMMENTS_TOPIC . " com ON v.id = com.id_in_module AND com.module_id = 'web'
				WHERE idcat = '" . $id_cat . "' AND infos = '" . MEDIA_STATUS_APROBED . "'
				ORDER BY " . $sort . " " . $mode .
				$this->sql_querier->limit($Pagination->get_first_msg($MEDIA_CONFIG['pagin'], 'p'), $MEDIA_CONFIG['pagin']), __LINE__, __FILE__);
	
			$notation = new Notation();
			$notation->set_module_name('media');
			$notation->set_notation_scale($MEDIA_CONFIG['note_max']);
			
			while ($row = $this->sql_querier->fetch_assoc($result))
			{
				$notation->set_id_in_module($row['id']);
				
				$tpl->assign_block_vars('file', array(
					'NAME' => $row['name'],
					'IMG_NAME' => str_replace('"', '\"', $row['name']),
					'C_DESCRIPTION' => !empty($row['contents']),
					'DESCRIPTION' => FormatingHelper::second_parse($row['contents']),
					'POSTER' => !empty($row['login']) ? sprintf($MEDIA_LANG['media_added_by'], $row['login'], UserUrlBuilder::profile($row['iduser'])->absolute(), $level[$row['level']]) : $LANG['guest'],
					'DATE' => sprintf($MEDIA_LANG['add_on_date'], gmdate_format('date_format_short', $row['timestamp'])),
					'COUNT' => sprintf($MEDIA_LANG['view_n_times'], $row['counter']),
					'NOTE' => NotationService::display_static_image($notation),
					'U_MEDIA_LINK' => url('/media/media.php?id=' . $row['id'], 'media-' . $row['id'] . '-' . $id_cat . '+' . Url::encode_rewrite($row['name']) . '.php'),
					'U_ADMIN_UNVISIBLE_MEDIA' => PATH_TO_ROOT . url('/media/media_action.php?unvisible=' . $row['id'] . '&amp;token=' . $Session->get_token()),
					'U_ADMIN_EDIT_MEDIA' => PATH_TO_ROOT . url('/media/media_action.php?edit=' . $row['id']),
					'U_ADMIN_DELETE_MEDIA' => PATH_TO_ROOT . url('/media/media_action.php?del=' . $row['id'] . '&amp;token=' . $Session->get_token()),
					'U_COM_LINK' => '<a href="'. PATH_TO_ROOT .'/media/media' . url('.php?id=' . $row['id'] . '&amp;com=0', '-' . $row['id'] . '-' . $id_cat . '+' . Url::encode_rewrite($row['name']) . '.php?com=0') .'">'. CommentsService::get_number_and_lang_comments('media', $row['id']) . '</a>'
				));
			}
	
			$this->sql_querier->query_close($result);
		}
		else
		{
			$tpl->put_all(array(
				'L_NO_FILE_THIS_CATEGORY' => $MEDIA_LANG['none_media'],
				'C_NO_FILE' => true
			));
		}

		return $tpl;
	}
}
?>