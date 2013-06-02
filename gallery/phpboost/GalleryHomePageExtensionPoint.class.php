<?php
/*##################################################
 *                     GalleryHomePageExtensionPoint.class.php
 *                            -------------------
 *   begin                : February 10, 2012
 *   copyright            : (C) 2012 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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

class GalleryHomePageExtensionPoint implements HomePageExtensionPoint
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
		global $LANG;
		
		load_module_lang('gallery');
		
		return $LANG['gallery'];
	}
	
	private function get_view()
	{
		global $CONFIG_GALLERY, $Cache, $CAT_GALLERY, $Bread_crumb, $LANG, $User, $g_idcat, $g_idpics, $g_sort, $g_idpics, $g_type, $g_notes, $g_views, $g_mode, $Session;
		
		require_once(PATH_TO_ROOT . '/gallery/gallery_begin.php');
		
		$Template = new FileTemplate('gallery/gallery.tpl');

		$comments_topic = new GalleryCommentsTopic();

		$Gallery = new Gallery();
		
		if (!empty($g_idcat))
		{
			if (!isset($CAT_GALLERY[$g_idcat]) || $CAT_GALLERY[$g_idcat]['aprob'] == 0)
				AppContext::get_response()->redirect(PATH_TO_ROOT .'/gallery/gallery' . url('.php?error=unexist_cat', '', '&'));
	
			$cat_links = '';
			foreach ($CAT_GALLERY as $id => $array_info_cat)
			{
				if ($id > 0)
				{
					if ($CAT_GALLERY[$g_idcat]['id_left'] >= $array_info_cat['id_left'] && $CAT_GALLERY[$g_idcat]['id_right'] <= $array_info_cat['id_right'] && $array_info_cat['level'] <= $CAT_GALLERY[$g_idcat]['level'])
						$cat_links .= '&raquo; <a href="' . GalleryUrlBuilder::get_link_cat($id) . '">' . $array_info_cat['name'] . '</a>';
				}
			}
			$clause_cat = " WHERE gc.id_left > '" . $CAT_GALLERY[$g_idcat]['id_left'] . "' AND gc.id_right < '" . $CAT_GALLERY[$g_idcat]['id_right'] . "' AND gc.level = '" . ($CAT_GALLERY[$g_idcat]['level'] + 1) . "' AND gc.aprob = 1";
		}
		else //Racine.
		{
			$cat_links = '';
			$clause_cat = " WHERE gc.level = '0' AND gc.aprob = 1";
			$CAT_GALLERY[0]['auth'] = $CONFIG_GALLERY['auth_root'];
			$CAT_GALLERY[0]['aprob'] = 1;
			$CAT_GALLERY[0]['name'] = $LANG['root'];
			$CAT_GALLERY[0]['level'] = -1;
		}
	
		//Niveau d'autorisation de la catégorie
		if (!$User->check_auth($CAT_GALLERY[$g_idcat]['auth'], READ_CAT_GALLERY))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	
		$nbr_pics = $this->sql_querier->query("SELECT COUNT(*) FROM " . PREFIX . "gallery WHERE idcat = '" . $g_idcat . "' AND aprob = 1", __LINE__, __FILE__);
		$total_cat = $this->sql_querier->query("SELECT COUNT(*) FROM " . PREFIX . "gallery_cats gc " . $clause_cat, __LINE__, __FILE__);
	
		//Gestion erreur.
		$get_error = retrieve(GET, 'error', '');
		if ($get_error == 'unexist_cat')
			$Template->put('message_helper', MessageHelper::display(LangLoader::get_message('e_unexist_cat', 'errors'), E_USER_NOTICE));
	
		//On crée une pagination si le nombre de catégories est trop important.
	
		$Pagination = new DeprecatedPagination();
	
		//Colonnes des catégories.
		$nbr_column_cats = ($total_cat > $CONFIG_GALLERY['nbr_column']) ? $CONFIG_GALLERY['nbr_column'] : $total_cat;
		$nbr_column_cats = !empty($nbr_column_cats) ? $nbr_column_cats : 1;
		$column_width_cats = floor(100/$nbr_column_cats);
	
		//Colonnes des images.
		$nbr_column_pics = ($nbr_pics > $CONFIG_GALLERY['nbr_column']) ? $CONFIG_GALLERY['nbr_column'] : $nbr_pics;
		$nbr_column_pics = !empty($nbr_column_pics) ? $nbr_column_pics : 1;
		$column_width_pics = floor(100/$nbr_column_pics);
	
		$is_admin = $User->check_level(User::ADMIN_LEVEL) ? true : false;
		$is_modo = ($User->check_auth($CAT_GALLERY[$g_idcat]['auth'], EDIT_CAT_GALLERY)) ? true : false;
	
		$module_data_path = $Template->get_pictures_data_path();
		$rewrite_title = Url::encode_rewrite($CAT_GALLERY[$g_idcat]['name']);
	
		//Ordonnement.
		$array_order = array('name' => $LANG['name'], 'date' => $LANG['date'], 'views' => $LANG['views'], 'notes' => $LANG['notes'], 'com' => $LANG['com_s']);
		foreach ($array_order as $type => $name)
		{
			$Template->assign_block_vars('order', array(
				'ORDER_BY' => '<a href="' . PATH_TO_ROOT . '/gallery/gallery' . url('.php?sort=' . $type . '_desc&amp;cat=' . $g_idcat, '-' . $g_idcat . '+' . $rewrite_title . '.php?sort=' . $type . '_desc') . '" style="background-image:url(' . $module_data_path . '/images/' . $type . '.png);">' . $name . '</a>'
			));
		}
	
		$Template->put_all(array(
			'ARRAY_JS' => '',
			'NBR_PICS' => 0,
			'MAX_START' => 0,
			'START_THUMB' => 0,
			'END_THUMB' => 0,
			'SID' => SID,
			'THEME' => get_utheme(),
			'LANG' => get_ulang(),
			'PAGINATION' => $Pagination->display('gallery' . url('.php?p=%d&amp;cat=' . $g_idcat . '&amp;id=' . $g_idpics . '&amp;' . $g_sort, '-' . $g_idcat . '-' . $g_idpics . '-%d.php?&' . $g_sort), $total_cat, 'p', $CONFIG_GALLERY['nbr_pics_max'], 3),
			'COLUMN_WIDTH_CATS' => $column_width_cats,
			'COLUMN_WIDTH_PICS' => $column_width_pics,
			'CAT_ID' => $g_idcat,
			'DISPLAY_MODE' => $CONFIG_GALLERY['display_pics'],
			'GALLERY' => !empty($g_idcat) ? $CAT_GALLERY[$g_idcat]['name'] : $LANG['gallery'],
			'HEIGHT_MAX' => $CONFIG_GALLERY['height'],
			'WIDTH_MAX' => $column_width_pics,
			'MODULE_DATA_PATH' => $module_data_path,
			'ADD_PICS' => $User->check_auth($CAT_GALLERY[$g_idcat]['auth'], WRITE_CAT_GALLERY) ? '<a href="' . GalleryUrlBuilder::get_link_cat_add($g_idcat) . '"><img src="'. PATH_TO_ROOT.'/templates/' . get_utheme() . '/images/' . get_ulang() . '/add.png" alt="" class="valign_middle" title="' . $LANG['gallery_pics_add'] .'"/></a>' : '',
			'L_CONFIRM_DEL_FILE' => $LANG['confim_del_file'],
			'L_APROB' => $LANG['aprob'],
			'L_UNAPROB' => $LANG['unaprob'],
			'L_FILE_FORBIDDEN_CHARS' => $LANG['file_forbidden_chars'],
			'L_TOTAL_IMG' => sprintf($LANG['total_img_cat'], $nbr_pics),
			'L_ADD_IMG' => $LANG['add_pic'],
			'L_GALLERY' => $LANG['gallery'],
			'L_CATEGORIES' => ($CAT_GALLERY[$g_idcat]['level'] >= 0) ? $LANG['sub_album'] : $LANG['album'],
			'L_NAME' => $LANG['name'],
			'L_EDIT' => $LANG['edit'],
			'L_MOVETO' => $LANG['moveto'],
			'L_DELETE' => $LANG['delete'],
			'L_SUBMIT' => $LANG['submit'],
			'L_ALREADY_VOTED' => $LANG['already_vote'],
			'L_ORDER_BY' => $LANG['orderby'] . (isset($LANG[$g_type]) ? ' ' . strtolower($LANG[$g_type]) : ''),
			'L_DIRECTION' => $LANG['direction'],
			'L_DISPLAY' => $LANG['display'],
			'U_INDEX' => url('.php'),
			'U_GALLERY_CAT_LINKS' => $cat_links,
			'U_BEST_VIEWS' => '<a class="small_link" href="'. PATH_TO_ROOT.'/gallery/gallery' . url('.php?views=1&amp;cat=' . $g_idcat, '-' . $g_idcat . '.php?views=1') . '" style="background-image:url(' . $module_data_path . '/images/views.png);">' . $LANG['best_views'] . '</a>',
			'U_BEST_NOTES' => '<a class="small_link" href="'. PATH_TO_ROOT.'/gallery/gallery' . url('.php?notes=1&amp;cat=' . $g_idcat, '-' . $g_idcat . '.php?notes=1') . '" style="background-image:url(' . $module_data_path . '/images/notes.png);">' . $LANG['best_notes'] . '</a>',
			'U_ASC' => '<a class="small_link" href="'. PATH_TO_ROOT.'/gallery/gallery' . url('.php?cat=' . $g_idcat . '&amp;sort=' . $g_type . '_' . 'asc', '-' . $g_idcat . '.php?sort=' . $g_type . '_' . 'asc') . '" style="background-image:url(' . $module_data_path . '/images/up.png);">' . $LANG['asc'] . '</a>',
			'U_DESC' => '<a class="small_link" href="'. PATH_TO_ROOT.'/gallery/gallery' . url('.php?cat=' . $g_idcat . '&amp;sort=' . $g_type . '_' . 'desc', '-' . $g_idcat . '.php?sort=' . $g_type . '_' . 'desc') . '" style="background-image:url(' . $module_data_path . '/images/down.png);">' . $LANG['desc'] . '</a>',
		));
	
		//Catégories non autorisées.
		$unauth_cats_sql = array();
		$unauth_cats = array();
		foreach ($CAT_GALLERY as $idcat => $key)
		{
			if ($idcat > 0 && $CAT_GALLERY[$idcat]['aprob'] == 1)
			{
				if (!$User->check_auth($CAT_GALLERY[$idcat]['auth'], READ_CAT_GALLERY))
				{
					$clause_level = !empty($g_idcat) ? ($CAT_GALLERY[$idcat]['level'] == ($CAT_GALLERY[$g_idcat]['level'] + 1)) : ($CAT_GALLERY[$idcat]['level'] == 0);
					if ($clause_level)
						$unauth_cats_sql[] = $idcat;
					$unauth_cats[] = $idcat;
				}
			}
		}
		$nbr_unauth_cats = count($unauth_cats_sql);
		$clause_unauth_cats = ($nbr_unauth_cats > 0) ? " AND gc.id NOT IN (" . implode(', ', $unauth_cats_sql) . ")" : '';
	
		##### Catégorie disponibles #####
		if ($total_cat > 0 && $nbr_unauth_cats < $total_cat && empty($g_idpics))
		{
			$Template->put_all(array(
				'C_GALLERY_CATS' => true,
				'EDIT_CAT' => $is_admin ? '<a href="'. PATH_TO_ROOT.'/gallery/admin_gallery_cat.php"><img class="valign_middle" src="'. PATH_TO_ROOT.'/templates/' . get_utheme() .  '/images/' . get_ulang() . '/edit.png" alt="" title="' . $LANG['gallery_cats_management'] .'"/></a>' : ''
			));
	
			$j = 0;
			$result = $this->sql_querier->query_while ("SELECT gc.id, gc.name, gc.contents, gc.status, (gc.nbr_pics_aprob + gc.nbr_pics_unaprob) AS nbr_pics, gc.nbr_pics_unaprob, g.path
			FROM " . PREFIX . "gallery_cats gc
			LEFT JOIN " . PREFIX . "gallery g ON g.idcat = gc.id AND g.aprob = 1
			" . $clause_cat . $clause_unauth_cats . "
			GROUP BY gc.id
			ORDER BY gc.id_left
			" . $this->sql_querier->limit($Pagination->get_first_msg($CONFIG_GALLERY['nbr_pics_max'], 'p'), $CONFIG_GALLERY['nbr_pics_max']), __LINE__, __FILE__);
			while ($row = $this->sql_querier->fetch_assoc($result))
			{
				//Si la miniature n'existe pas (cache vidé) on regénère la miniature à partir de l'image en taille réelle.
				if (!file_exists('pics/thumbnails/' . $row['path']))
					$Gallery->Resize_pics('pics/' . $row['path']); //Redimensionnement + création miniature
	
				$Template->assign_block_vars('cat_list', array(
					'IDCAT' => $row['id'],
					'CAT' => $row['name'],
					'DESC' => $row['contents'],
					'IMG' => !empty($row['path']) ? '<img src="'. PATH_TO_ROOT.'/gallery/pics/thumbnails/' . $row['path'] . '" alt="" />' : '',
					'EDIT' => $is_admin ? '<a href="'. PATH_TO_ROOT.'/gallery/admin_gallery_cat.php?id=' . $row['id'] . '"><img class="valign_middle" src="'. PATH_TO_ROOT.'/templates/' . get_utheme() .  '/images/' . get_ulang() . '/edit.png" alt="" title="' . $LANG['cat_edit'] . '"/></a>' : '',
					'LOCK' => ($row['status'] == 0) ? '<img class="valign_middle" src="'. PATH_TO_ROOT.'/templates/' . get_utheme() . '/images/readonly.png" alt="" title="' . $LANG['gallery_lock'] . '" />' : '',
					'OPEN_TR' => is_int($j++/$nbr_column_cats) ? '<tr>' : '',
					'CLOSE_TR' => is_int($j/$nbr_column_cats) ? '</tr>' : '',
					'L_NBR_PICS' => sprintf($LANG['nbr_pics_info'], $row['nbr_pics']),
					'U_CAT' => GalleryUrlBuilder::get_link_cat($row['id'],$row['name'])
				));
			}
			$this->sql_querier->query_close($result);
	
			//Création des cellules du tableau si besoin est.
			while (!is_int($j/$nbr_column_cats))
			{
				$Template->assign_block_vars('end_table_cats', array(
					'TD_END' => '<td style="margin:15px 0px;width:' . $nbr_column_cats . '%">&nbsp;</td>',
					'TR_END' => (is_int(++$j/$nbr_column_cats)) ? '</tr>' : ''
				));
			}
		}
	
		##### Affichage des photos #####
		if ($nbr_pics > 0)
		{
			switch ($g_type)
			{
				case 'name' :
				$sort_type = 'g.name';
				break;
				case 'date' :
				$sort_type = 'g.timestamp';
				break;
				case 'views' :
				$sort_type = 'g.views';
				break;
				case 'notes' :
				$sort_type = 'note.average_notes';
				break;
				case 'com' :
				$sort_type = 'com.number_comments';
				break;
				default :
				$sort_type = 'g.timestamp';
			}
			switch ($g_mode)
			{
				case 'desc' :
				$sort_mode = 'DESC';
				break;
				case 'asc' :
				$sort_mode = 'ASC';
				break;
				default:
				$sort_mode = 'DESC';
			}
			$g_sql_sort = ' ORDER BY ' . $sort_type . ' ' . $sort_mode;
			if ($g_views)
				$g_sql_sort = ' ORDER BY g.views DESC';
			elseif ($g_notes)
				$g_sql_sort = ' ORDER BY note.average_notes DESC';
	
			$Template->put_all(array(
				'C_GALLERY_PICS' => true,
				'EDIT' => $is_admin ? '<a href="'. PATH_TO_ROOT.'/gallery/admin_gallery_cat.php' . (!empty($g_idcat) ? '?id=' . $g_idcat : '') . '"><img class="valign_middle" src="'. PATH_TO_ROOT.'/templates/' . get_utheme() .  '/images/' . get_ulang() . '/edit.png" alt="" title="' . $LANG['cat_edit'] . '"/></a>' : ''
			));
	
			//Liste des catégories.
			$array_cat_list = array(0 => '<option value="-1" %s>' . $LANG['root'] . '</option>');
			$result = $this->sql_querier->query_while("SELECT id, level, name
			FROM " . PREFIX . "gallery_cats
			WHERE aprob = 1
			ORDER BY id_left", __LINE__, __FILE__);
			while ($row = $this->sql_querier->fetch_assoc($result))
			{
				if (!in_array($row['id'], $unauth_cats))
				{
					$margin = ($row['level'] > 0) ? str_repeat('--------', $row['level']) : '--';
					$array_cat_list[$row['id']] = $User->check_auth($CAT_GALLERY[$row['id']]['auth'], EDIT_CAT_GALLERY) ? '<option value="' . $row['id'] . '" %s>' . $margin . ' ' . $row['name'] . '</option>' : '';
				}
			}
			$this->sql_querier->query_close($result);
	
	
	
			//Affichage d'une photo demandée.
			if (!empty($g_idpics))
			{
				$result = $this->sql_querier->query_while("SELECT g.*, m.login
					FROM " . PREFIX . "gallery g
					LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = g.user_id
					LEFT JOIN " . DB_TABLE_COMMENTS_TOPIC . " com ON com.id_in_module = g.id AND com.module_id = 'gallery'
					LEFT JOIN " . DB_TABLE_AVERAGE_NOTES . " note ON note.id_in_module = g.id AND note.module_name = 'gallery'
					WHERE g.idcat = '" . $g_idcat . "' AND g.id = '" . $g_idpics . "' AND g.aprob = 1
					" . $g_sql_sort . "
					" . $this->sql_querier->limit(0, 1), __LINE__, __FILE__);
				$info_pics = $this->sql_querier->fetch_assoc($result);
				if (!empty($info_pics['id']))
				{
					//Affichage miniatures.
					$id_previous = 0;
					$id_next = 0;
					$nbr_pics_display_before = floor(($nbr_column_pics - 1)/2); //Nombres de photos de chaque côté de la miniature de la photo affichée.
					$nbr_pics_display_after = ($nbr_column_pics - 1) - floor($nbr_pics_display_before);
					list($i, $reach_pics_pos, $pos_pics, $thumbnails_before, $thumbnails_after, $start_thumbnails, $end_thumbnails) = array(0, false, 0, 0, 0, $nbr_pics_display_before, $nbr_pics_display_after);
					$array_pics = array();
					$array_js = 'var array_pics = new Array();';
					$result = $this->sql_querier->query_while("SELECT g.id, g.idcat, g.path
					FROM " . PREFIX . "gallery g
					LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = g.user_id
					LEFT JOIN " . DB_TABLE_COMMENTS_TOPIC . " com ON com.id_in_module = g.id AND com.module_id = 'gallery'
					LEFT JOIN " . DB_TABLE_AVERAGE_NOTES . " note ON note.id_in_module = g.id AND note.module_name = 'gallery'
					WHERE g.idcat = '" . $g_idcat . "' AND g.aprob = 1
					" . $g_sql_sort, __LINE__, __FILE__);
					while ($row = $this->sql_querier->fetch_assoc($result))
					{
						//Si la miniature n'existe pas (cache vidé) on regénère la miniature à partir de l'image en taille réelle.
						if (!file_exists(PATH_TO_ROOT . '/gallery/pics/thumbnails/' . $row['path']))
							$Gallery->Resize_pics(PATH_TO_ROOT . '/gallery/pics/' . $row['path']); //Redimensionnement + création miniature
	
						//Affichage de la liste des miniatures sous l'image.
						$array_pics[] = '<td class="row2" style="text-align:center;height:' . ($CONFIG_GALLERY['height'] + 16) . 'px"><span id="thumb' . $i . '"><a href="gallery' . url('.php?cat=' . $row['idcat'] . '&amp;id=' . $row['id'] . '&amp;sort=' . $g_sort, '-' . $row['idcat'] . '-' . $row['id'] . '.php?sort=' . $g_sort) . '#pics_max' . '"><img src="pics/thumbnails/' . $row['path'] . '" alt="" / ></a></span></td>';
	
						if ($row['id'] == $g_idpics)
						{
							$reach_pics_pos = true;
							$pos_pics = $i;
						}
						else
						{
							if (!$reach_pics_pos)
							{
								$thumbnails_before++;
								$id_previous = $row['id'];
							}
							else
							{
								$thumbnails_after++;
								if (empty($id_next))
									$id_next = $row['id'];
							}
						}
						$array_js .= 'array_pics[' . $i . '] = new Array();' . "\n";
						$array_js .= 'array_pics[' . $i . '][\'link\'] = \'' . GalleryUrlBuilder::get_link_item($row['idcat'],$row['id']) . '#pics_max' . "';\n";
						$array_js .= 'array_pics[' . $i . '][\'path\'] = \'' . $row['path'] . "';\n";
						$i++;
					}
					$this->sql_querier->query_close($result);
	
					//Liste des catégories.
					$cat_list = '';
					foreach ($array_cat_list as $key_cat => $option_value)
						$cat_list .= ($key_cat == $info_pics['idcat']) ? sprintf($option_value, 'selected="selected"') : sprintf($option_value, '');
	
					$activ_note = ($CONFIG_GALLERY['activ_note'] == 1 && $User->check_level(User::MEMBER_LEVEL) );
					if ($activ_note)
					{
						//Affichage notation.
						$notation = new Notation();
						$notation->set_module_name('gallery');
						$notation->set_id_in_module($info_pics['id']);
						$notation->set_notation_scale($CONFIG_GALLERY['note_max']);
					}

					if ($thumbnails_before < $nbr_pics_display_before)
						$end_thumbnails += $nbr_pics_display_before - $thumbnails_before;
					if ($thumbnails_after < $nbr_pics_display_after)
						$start_thumbnails += $nbr_pics_display_after - $thumbnails_after;
	
					$html_protected_name = $info_pics['name'];
	
					$comments_topic->set_id_in_module($info_pics['id']);
					$comments_topic->set_url(new Url('/gallery/gallery.php?cat='. $g_idcat .'&id=' . $g_idpics . '&com=0'));
						
					//Affichage de l'image et de ses informations.
					$Template->put_all(array(
						'C_GALLERY_PICS_MAX' => true,
						'C_GALLERY_PICS_MODO' => $is_modo ? true : false,
						'C_ACTIV_POSTOR' => $CONFIG_GALLERY['activ_user'],
						'C_ACTIV_VIEW' => $CONFIG_GALLERY['activ_view'],
						'C_ACTIV_TITLE' => $CONFIG_GALLERY['activ_title'],
						'C_ACTIV_COM' => $CONFIG_GALLERY['activ_com'],
						'C_ACTIV_NOTE' => $CONFIG_GALLERY['activ_note'],
						'ID' => $info_pics['id'],
						'IMG_MAX' => '<img src="' . PATH_TO_ROOT . '/gallery/show_pics' . url('.php?id=' . $g_idpics . '&amp;cat=' . $g_idcat) . '" alt="" />',
						'NAME' => '<span id="fi_' . $info_pics['id'] . '">' . stripslashes($info_pics['name']) . '</span> <span id="fi' . $info_pics['id'] . '"></span>',
						'POSTOR' => '<a class="small_link" href="'. UserUrlBuilder::profile($info_pics['user_id'])->absolute() .'">' . $info_pics['login'] . '</a>',
						'DATE' => gmdate_format('date_format_short', $info_pics['timestamp']),
						'VIEWS' => ($info_pics['views'] + 1),
						'DIMENSION' => $info_pics['width'] . ' x ' . $info_pics['height'],
						'SIZE' => NumberHelper::round($info_pics['weight']/1024, 1),
						'COM' => '<a href="'. GalleryUrlBuilder::get_link_item($info_pics['idcat'],$info_pics['id'],0,$g_sort) .'#comments_list">'. CommentsService::get_number_and_lang_comments('gallery', $info_pics['id']) . '</a>',
						'KERNEL_NOTATION' => $activ_note ? NotationService::display_active_image($notation) : '',
						'COLSPAN' => ($CONFIG_GALLERY['nbr_column'] + 2),
						'CAT' => $cat_list,
						'RENAME' => $html_protected_name,
						'RENAME_CUT' => $html_protected_name,
						'IMG_APROB' => get_ulang() . '/' . (($info_pics['aprob'] == 1) ? 'unvisible.png' : 'visible.png'),
						'ARRAY_JS' => $array_js,
						'NBR_PICS' => ($i - 1),
						'MAX_START' => ($i - 1) - $nbr_column_pics,
						'START_THUMB' => (($pos_pics - $start_thumbnails) > 0) ? ($pos_pics - $start_thumbnails) : 0,
						'END_THUMB' => ($pos_pics + $end_thumbnails),
						'L_KB' => $LANG['unit_kilobytes'],
						'L_INFORMATIONS' => $LANG['informations'],
						'L_NAME' => $LANG['name'],
						'L_POSTOR' => $LANG['postor'],
						'L_VIEWS' => $LANG['views'],
						'L_ADD_ON' => $LANG['add_on'],
						'L_DIMENSION' => $LANG['dimension'],
						'L_SIZE' => $LANG['size'],
						'L_NOTE' => $LANG['note'],
						'L_COM' => $LANG['com'],
						'L_EDIT' => $LANG['edit'],
						'L_APROB_IMG' => ($info_pics['aprob'] == 1) ? $LANG['unaprob'] : $LANG['aprob'],
						'L_THUMBNAILS' => $LANG['thumbnails'],
						'U_DEL' => url('gallery.php?del=' . $info_pics['id'] . '&amp;token=' . $Session->get_token() . '&amp;cat=' . $g_idcat),
						'U_MOVE' => url('gallery.php?id=' . $info_pics['id'] . '&amp;token=' . $Session->get_token() . '&amp;move=\' + this.options[this.selectedIndex].value'),
						'U_PREVIOUS' => ($pos_pics > 0) ? '<a href="' . GalleryUrlBuilder::get_link_item($g_idcat,$id_previous) . '#pics_max"><img src="'. PATH_TO_ROOT.'/templates/' . get_utheme() . '/images/left.png" alt="" class="valign_middle" /></a> <a href="' . GalleryUrlBuilder::get_link_item($g_idcat,$id_previous) . '#pics_max">' . $LANG['previous'] . '</a>' : '',
						'U_NEXT' => ($pos_pics < ($i - 1)) ? '<a href="' . GalleryUrlBuilder::get_link_item($g_idcat,$id_next) . '#pics_max">' . $LANG['next'] . '</a> <a href="' . GalleryUrlBuilder::get_link_item($g_idcat,$id_next) . '#pics_max"><img src="'. PATH_TO_ROOT.'/templates/' . get_utheme() . '/images/right.png" alt="" class="valign_middle" /></a>' : '',
						'U_LEFT_THUMBNAILS' => (($pos_pics - $start_thumbnails) > 0) ? '<span id="display_left"><a href="javascript:display_thumbnails(\'left\')"><img src="'. PATH_TO_ROOT.'/templates/' . get_utheme() . '/images/left.png" class="valign_middle" alt="" /></a></span>' : '<span id="display_left"></span>',
						'U_RIGHT_THUMBNAILS' => (($pos_pics - $start_thumbnails) <= ($i - 1) - $nbr_column_pics) ? '<span id="display_right"><a href="javascript:display_thumbnails(\'right\')"><img src="'. PATH_TO_ROOT.'/templates/' . get_utheme() . '/images/right.png" class="valign_middle" alt="" /></a></span>' : '<span id="display_right"></span>'
					));
	
					//Affichage de la liste des miniatures sous l'image.
					$i = 0;
					foreach ($array_pics as $pics)
					{
						if ($i >= ($pos_pics - $start_thumbnails) && $i <= ($pos_pics + $end_thumbnails))
						{
							$Template->assign_block_vars('list_preview_pics', array(
								'PICS' => $pics
							));
						}
						$i++;
					}
	
					//Commentaires
					if (isset($_GET['com']))
					{
						$Template->put_all(array(
							'COMMENTS' => CommentsService::display($comments_topic)->render()
						));
					}
				}
			}
			else
			{
				//On crée une pagination si le nombre de photos est trop important.
	
				$Pagination = new DeprecatedPagination();
	
				$sort = retrieve(GET, 'sort', '');
				$Template->put_all(array(
					'C_GALLERY_MODO' => $is_modo ? true : false,
					'PAGINATION_PICS' => $Pagination->display(PATH_TO_ROOT . '/gallery/gallery' . url('.php?pp=%d' . (!empty($sort) ? '&amp;sort=' . $sort : '') . '&amp;cat=' . $g_idcat, '-' . $g_idcat . '+' . $rewrite_title . '.php?pp=%d'), $nbr_pics, 'pp', $CONFIG_GALLERY['nbr_pics_max'], 3),
					'L_EDIT' => $LANG['edit'],
					'L_VIEW' => $LANG['view'],
					'L_VIEWS' => $LANG['views']
				));
	
	
				$notation = new Notation();
				$notation->set_module_name('gallery');
				$notation->set_notation_scale($CONFIG_GALLERY['note_max']);
				
				$is_connected = $User->check_level(User::MEMBER_LEVEL);
				$j = 0;
				$result = $this->sql_querier->query_while("SELECT g.id, g.idcat, g.name, g.path, g.timestamp, g.aprob, g.width, g.height, g.user_id, g.views, g.aprob, m.login
				FROM " . PREFIX . "gallery g
				LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = g.user_id
				LEFT JOIN " . DB_TABLE_COMMENTS_TOPIC . " com ON com.id_in_module = g.id AND com.module_id = 'gallery'
				LEFT JOIN " . DB_TABLE_AVERAGE_NOTES . " note ON note.id_in_module = g.id AND note.module_name = 'gallery'
				WHERE g.idcat = '" . $g_idcat . "' AND g.aprob = 1
				" . $g_sql_sort . "
				" . $this->sql_querier->limit($Pagination->get_first_msg($CONFIG_GALLERY['nbr_pics_max'], 'pp'), $CONFIG_GALLERY['nbr_pics_max']), __LINE__, __FILE__);
				while ($row = $this->sql_querier->fetch_assoc($result))
				{
					//Si la miniature n'existe pas (cache vidé) on regénère la miniature à partir de l'image en taille réelle.
					if (!file_exists(PATH_TO_ROOT . '/gallery/pics/thumbnails/' . $row['path']))
						$Gallery->Resize_pics(PATH_TO_ROOT . '/gallery/pics/' . $row['path']); //Redimensionnement + création miniature
					
					//Affichage de l'image en grand.
					if ($CONFIG_GALLERY['display_pics'] == 3) //Ouverture en popup plein écran.
					{
						$display_link = PATH_TO_ROOT.'/gallery/show_pics' . url('.php?id=' . $row['id'] . '&amp;cat=' . $row['idcat']) . '" rel="lightbox[1]" onmousedown="increment_view(' . $row['id'] . ');" title="' . str_replace('"', '', stripslashes($row['name']));
						$display_name = PATH_TO_ROOT.'/gallery/show_pics' . url('.php?id=' . $row['id'] . '&amp;cat=' . $row['idcat']) . '" rel="lightbox[2]" onmousedown="increment_view(' . $row['id'] . ');" title="' . str_replace('"', '', stripslashes($row['name']));
					}
					elseif ($CONFIG_GALLERY['display_pics'] == 2) //Ouverture en popup simple.
						$display_name = $display_link = 'javascript:increment_view(' . $row['id'] . ');display_pics_popup(\'' . PATH_TO_ROOT . '/gallery/show_pics' . url('.php?id=' . $row['id'] . '&amp;cat=' . $row['idcat']) . '\', \'' . $row['width'] . '\', \'' . $row['height'] . '\')';
					elseif ($CONFIG_GALLERY['display_pics'] == 1) //Ouverture en agrandissement simple.
						$display_name = $display_link = 'javascript:increment_view(' . $row['id'] . ');display_pics(' . $row['id'] . ', \'' . PATH_TO_ROOT . '/gallery/show_pics' . url('.php?id=' . $row['id'] . '&amp;cat=' . $row['idcat']) . '\')';
					else //Ouverture nouvelle page.
						$display_name = $display_link = url('gallery.php?cat=' . $row['idcat'] . '&amp;id=' . $row['id'], 'gallery-' . $row['idcat'] . '-' . $row['id'] . '.php') . '#pics_max';
					
					//Liste des catégories.
					$cat_list = '';
					foreach ($array_cat_list as $key_cat => $option_value)
						$cat_list .= ($key_cat == $row['idcat']) ? sprintf($option_value, 'selected="selected"') : sprintf($option_value, '');
		
					$activ_note = ($CONFIG_GALLERY['activ_note'] == 1 && $is_connected );

					$notation->set_id_in_module($row['id']);
					
					$comments_topic->set_id_in_module($row['id']);
					
					$html_protected_name = $row['name'];
					$Template->assign_block_vars('pics_list', array(
						'ID' => $row['id'],
						'APROB' => $row['aprob'],
						'IMG' => '<img src="'. PATH_TO_ROOT.'/gallery/pics/thumbnails/' . $row['path'] . '" alt="' . str_replace('"', '', stripslashes($row['name'])) . '" class="gallery_image" />',
						'PATH' => $row['path'],
						'NAME' => ($CONFIG_GALLERY['activ_title'] == 1) ? '<a class="small_link" href="' . $display_name . '"><span id="fi_' . $row['id'] . '">' . TextHelper::wordwrap_html(stripslashes($row['name']), 22, ' ') . '</span></a> <span id="fi' . $row['id'] . '"></span>' : '<span id="fi_' . $row['id'] . '"></span></a> <span id="fi' . $row['id'] . '"></span>',
						'POSTOR' => ($CONFIG_GALLERY['activ_user'] == 1) ? '<br />' . $LANG['by'] . (!empty($row['login']) ? ' <a class="small_link" href="'. UserUrlBuilder::profile($row['user_id'])->absolute() .'">' . $row['login'] . '</a>' : ' ' . $LANG['guest']) : '',
						'VIEWS' => ($CONFIG_GALLERY['activ_view'] == 1) ? '<br /><span id="gv' . $row['id'] . '">' . $row['views'] . '</span> <span id="gvl' . $row['id'] . '">' . ($row['views'] > 1 ? $LANG['views'] : $LANG['view']) . '</span>' : '',
						'COM' => $CONFIG_GALLERY['activ_com'] == 1 ? '<br /><a href="'. PATH_TO_ROOT .'/gallery/gallery' . url('.php?cat=' . $row['idcat'] . '&amp;id=' . $row['id'] . '&amp;com=0', '-' . $row['idcat'] . '-' . $row['id'] . '.php?com=0') .'#comments_list">'. CommentsService::get_number_and_lang_comments('gallery', $row['id']) . '</a>' : '',
						'KERNEL_NOTATION' => $activ_note ? NotationService::display_active_image($notation) : '',
						'CAT' => $cat_list,
						'RENAME' => $html_protected_name,
						'RENAME_CUT' => $html_protected_name,
						'IMG_APROB' => get_ulang() . '/' . (($row['aprob'] == 1) ? 'unvisible.png' : 'visible.png'),
						'OPEN_TR' => is_int($j++/$nbr_column_pics) ? '<tr>' : '',
						'CLOSE_TR' => is_int($j/$nbr_column_pics) ? '</tr>' : '',
						'L_APROB_IMG' => ($row['aprob'] == 1) ? $LANG['unaprob'] : $LANG['aprob'],
						'U_DEL' => url('gallery.php?del=' . $row['id'] . '&amp;token=' . $Session->get_token() . '&amp;cat=' . $g_idcat),
						'U_MOVE' => url('gallery.php?id=' . $row['id'] . '&amp;token=' . $Session->get_token() . '&amp;move=\' + this.options[this.selectedIndex].value'),
						'U_DISPLAY' => $display_link
					));
				}
				$this->sql_querier->query_close($result);
	
				//Création des cellules du tableau si besoin est.
				while (!is_int($j/$nbr_column_pics))
				{
					$Template->assign_block_vars('end_table', array(
						'TD_END' => '<td style="margin:15px 0px;width:' . $column_width_pics . '%">&nbsp;</td>',
						'TR_END' => (is_int(++$j/$nbr_column_pics)) ? '</tr>' : ''
					));
				}
			}
		}
	
		return $Template;
	}
}
?>