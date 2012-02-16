<?php
/*##################################################
 *                     DownloadHomePageExtensionPoint.class.php
 *                            -------------------
 *   begin                : February 06, 2012
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

class DownloadHomePageExtensionPoint implements HomePageExtensionPoint
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
		global $DOWNLOAD_LANG;
		
		return $DOWNLOAD_LANG['title_download'];
	}
	
	private function get_view()
	{
		global $DOWNLOAD_LANG, $LANG, $DOWNLOAD_CATS, $Session, $category_id, $auth_read, $auth_write, $auth_contribution, $notation;
		
		require_once(PATH_TO_ROOT . '/download/download_begin.php');
		
		$download_config = DownloadConfig::load();
		
		$tpl = new FileTemplate('download/download.tpl');
		
		$now = new Date(DATE_NOW, TIMEZONE_AUTO);
		
		$tpl->put_all(array(
			'C_ADMIN' => $auth_write,
			'C_DOWNLOAD_CAT' => true,
			'C_ADD_FILE' => $auth_write || $auth_contribution,
			'C_DESCRIPTION' => !empty($DOWNLOAD_CATS[$category_id]['contents']) || ($category_id == 0 && !empty($download_config->get_root_contents())),
			'IDCAT' => $category_id,
			'TITLE' => sprintf($DOWNLOAD_LANG['title_download'] . ($category_id > 0 ? ' - ' . $DOWNLOAD_CATS[$category_id]['name'] : '')),
			'DESCRIPTION' => $category_id > 0 ? FormatingHelper::second_parse($DOWNLOAD_CATS[$category_id]['contents']) : FormatingHelper::second_parse($download_config->get_root_contents()),
			'L_ADD_FILE' => $DOWNLOAD_LANG['add_file'],
			'U_ADMIN_CAT' => $category_id > 0 ? url('admin_download_cat.php?edit=' . $category_id) : url('admin_download_cat.php'),
			'U_ADD_FILE' => url('management.php?new=1&amp;idcat=' . $category_id)
		));
		
		//let's check if there are some subcategories
		$num_subcats = 0;
		foreach ($DOWNLOAD_CATS as $id => $value)
		{
			if ($id != 0 && $value['id_parent'] == $category_id)
				$num_subcats ++;
		}
	
		//listing of subcategories
		if ($num_subcats > 0)
		{
			$tpl->put_all(array(
				'C_SUB_CATS' => true
			));	
			
			$i = 1;
			
			foreach ($DOWNLOAD_CATS as $id => $value)
			{
				//List of children categories
				if ($id != 0 && $value['visible'] && $value['id_parent'] == $category_id && (empty($value['auth']) || $User->check_auth($value['auth'], DOWNLOAD_READ_CAT_AUTH_BIT)))
				{
					if ( $i % $download_config->get_number_columns() == 1 )
						$tpl->assign_block_vars('row', array());
						
					$tpl->assign_block_vars('row.list_cats', array(
						'ID' => $id,
						'NAME' => $value['name'],
						'WIDTH' => floor(100 / (float)$download_config->get_number_columns()),
						'SRC' => $value['icon'],
						'IMG_NAME' => addslashes($value['name']),
						'NUM_FILES' => sprintf(((int)$value['num_files'] > 1 ? $DOWNLOAD_LANG['num_files_plural'] : $DOWNLOAD_LANG['num_files_singular']), (int)$value['num_files']),
						'U_CAT' => url('download.php?cat=' . $id, 'category-' . $id . '+' . Url::encode_rewrite($value['name']) . '.php'),
						'U_ADMIN_CAT' => url('admin_download_cat.php?edit=' . $id),
						'C_CAT_IMG' => !empty($value['icon'])
					));
						
					$i++;
				}
			}
		}
		
		//Contenu de la catégorie	
		$nbr_files = (int)$this->sql_querier->query("SELECT COUNT(*) FROM " . PREFIX . "download WHERE visible = 1 AND approved = 1 AND idcat = '" . $category_id . "' AND start <= '" . $now->get_timestamp() . "' AND (end >= '" . $now->get_timestamp() . "' OR end = 0)", __LINE__, __FILE__);
		if ($nbr_files > 0)
		{
			$get_sort = retrieve(GET, 'sort', '');	
			$get_mode = retrieve(GET, 'mode', '');
			$selected_fields = array(
				'alpha' => '',
				'size' => '',
				'date' => '',
				'hits' => '',
				'note' => '',
				'asc' => '',
				'desc' => ''
				);
			
			switch ($get_sort)
			{
				case 'alpha' : 
				$sort = 'title';
				$selected_fields['alpha'] = ' selected="selected"';
				break;	
				case 'size' : 
				$sort = 'size';
				$selected_fields['size'] = ' selected="selected"';
				break;			
				case 'date' : 
				$sort = 'timestamp';
				$selected_fields['date'] = ' selected="selected"';
				break;		
				case 'hits' : 
				$sort = 'count';
				$selected_fields['hits'] = ' selected="selected"';
				break;		
				case 'note' :
				$sort = 'average_notes';
				$selected_fields['note'] = ' selected="selected"';
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
			
			$unget = (!empty($get_sort) && !empty($mode)) ? '?sort=' . $get_sort . '&amp;mode=' . $get_mode : '';
			
			$tpl->put_all(array(
				'L_FILE' => $DOWNLOAD_LANG['file'],
				'L_ALPHA' => $DOWNLOAD_LANG['sort_alpha'],
				'L_SIZE' => $LANG['size'],
				'L_DATE' => $LANG['date'],
				'L_DOWNLOAD' => $DOWNLOAD_LANG['download'],
				'L_POPULARITY' => $DOWNLOAD_LANG['popularity'],
				'L_DESC' => $LANG['desc'],
				'L_ASC' => $LANG['asc'],
				'L_NOTE' => $LANG['note'],
				'L_ORDER_BY' => $DOWNLOAD_LANG['order_by'],
				'L_CONFIRM_DELETE_FILE' => str_replace('\'', '\\\'', $DOWNLOAD_LANG['confirm_delete_file']),
				'SELECTED_ALPHA' => $selected_fields['alpha'],
				'SELECTED_SIZE' => $selected_fields['size'],
				'SELECTED_DATE' => $selected_fields['date'],
				'SELECTED_HITS' => $selected_fields['hits'],
				'SELECTED_NOTE' => $selected_fields['note'],
				'SELECTED_ASC' => $selected_fields['asc'],
				'SELECTED_DESC' => $selected_fields['desc']
			));
				
			//On crée une pagination si le nombre de fichiers est trop important.
			$Pagination = new DeprecatedPagination();
			
			$tpl->put_all(array(
				'PAGINATION' => $Pagination->display(url('download.php' . (!empty($unget) ? $unget . '&amp;' : '?') . 'cat=' . $category_id . '&amp;p=%d', 'category-' . $category_id . '-%d.php' . $unget), $nbr_files, 'p', $download_config->get_nbr_file_max(), 3),
				'C_FILES' => true,
				'TARGET_ON_CHANGE_ORDER' => ServerEnvironmentConfig::load()->is_url_rewriting_enabled() ? 'category-' . $category_id . '.php?' : 'download.php?cat=' . $category_id . '&'
			));
	
			$result = $this->sql_querier->query_while("SELECT d.id, d.title, d.timestamp, d.size, d.count, d.image, d.short_contents
			FROM " . PREFIX . "download d
			LEFT JOIN " . DB_TABLE_AVERAGE_NOTES . " notes ON d.id = notes.id_in_module
			WHERE visible = 1 AND approved = 1 AND idcat = '" . $category_id . "' AND start <= '" . $now->get_timestamp() . "' AND (end >= '" . $now->get_timestamp() . "' OR end = 0)
			ORDER BY " . $sort . " " . $mode . 
			$this->sql_querier->limit($Pagination->get_first_msg($download_config->get_nbr_file_max(), 'p'), $download_config->get_nbr_file_max()), __LINE__, __FILE__);
			while ($row = $this->sql_querier->fetch_assoc($result))
			{
				$notation->set_id_in_module($row['id']);
				
				$tpl->assign_block_vars('file', array(			
					'NAME' => $row['title'],
					'IMG_NAME' => str_replace('"', '\"', $row['title']),
					'C_DESCRIPTION' => !empty($row['short_contents']),
					'DESCRIPTION' => FormatingHelper::second_parse($row['short_contents']),
					'DATE' => sprintf($DOWNLOAD_LANG['add_on_date'], gmdate_format('date_format_short', $row['timestamp'])),
					'COUNT_DL' => sprintf($DOWNLOAD_LANG['downloaded_n_times'], $row['count']),
					'NOTE' => NotationService::display_static_image($notation),
					'SIZE' => ($row['size'] >= 1) ? NumberHelper::round($row['size'], 1) . ' ' . $LANG['unit_megabytes'] : (NumberHelper::round($row['size'], 1) * 1024) . ' ' . $LANG['unit_kilobytes'],
					'C_IMG' => !empty($row['image']),
					'IMG' => $row['image'],
					'U_DOWNLOAD_LINK' => url('download.php?id=' . $row['id'], 'download-' . $row['id'] . '+' . Url::encode_rewrite($row['title']) . '.php'),
					'U_ADMIN_EDIT_FILE' => url('management.php?edit=' . $row['id']),
					'U_ADMIN_DELETE_FILE' => url('management.php?del=' . $row['id'] . '&amp;token=' . $Session->get_token()),
					'U_COM_LINK' => '<a href="'. PATH_TO_ROOT .'/download/download' . url('.php?id=' . $row['id'] . '&amp;com=0', '-' . $row['id'] . '+' . Url::encode_rewrite($row['title']) . '.php?com=0') .'">'. CommentsService::get_number_and_lang_comments('download', $row['id']) . '</a>'
				));
			}
			$this->sql_querier->query_close($result);
		}
		else
		{
			$tpl->put_all(array(
				'L_NO_FILE_THIS_CATEGORY' => $DOWNLOAD_LANG['none_download'],
				'C_NO_FILE' => true
			));
		}
			
		return $tpl;
	}
}
?>