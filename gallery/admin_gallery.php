<?php
/*##################################################
 *                               admin_gallery.php
 *                            -------------------
 *   begin                : August 17, 2005
 *   copyright            : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
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

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

require_once('../admin/admin_begin.php');
load_module_lang('gallery'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

$request = AppContext::get_request();

$id_category = $request->get_getint('cat', 0);
$idpics = $request->get_getint('id', 0);
$del = $request->get_getint('del', 0);
$move = $request->get_getint('move', 0);

$Gallery = new Gallery();
$config = GalleryConfig::load();

if (!empty($idpics) && $move) //Déplacement d'une image.
{
	AppContext::get_session()->csrf_get_protect(); //Protection csrf

	$Gallery->Move_pics($idpics, $move);

	//Régénération du cache des photos aléatoires.
	GalleryMiniMenuCache::invalidate();
	GalleryCategoriesCache::invalidate();

	AppContext::get_response()->redirect('/gallery/admin_gallery.php?cat=' . $move);
}
elseif (!empty($del)) //Suppression d'une image.
{
	AppContext::get_session()->csrf_get_protect(); //Protection csrf

	$Gallery->Del_pics($del);

	//Régénération du cache des photos aléatoires.
	GalleryMiniMenuCache::invalidate();
	GalleryCategoriesCache::invalidate();

	AppContext::get_response()->redirect('/gallery/admin_gallery.php?cat=' . $id_category);
}
else
{
	$tpl = new FileTemplate('gallery/admin_gallery_management.tpl');

	if (!empty($id_category))
	{
		try {
			$category = GalleryService::get_categories_manager()->get_categories_cache()->get_category($id_category);
		} catch (CategoryNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
	}
	else
	{
		$category = GalleryService::get_categories_manager()->get_categories_cache()->get_category(Category::ROOT_CATEGORY);
	}
	
	$subcategories = GalleryService::get_categories_manager()->get_categories_cache()->get_children($category->get_id(), GalleryService::get_authorized_categories($category->get_id()));
	$elements_number = $category->get_elements_number();
	
	$nbr_pics = $elements_number['pics_aprob'] + $elements_number['pics_unaprob'];
	$total_cat = count($subcategories);
	
	//On crée une pagination si le nombre de catégories est trop important.
	$page = AppContext::get_request()->get_getint('p', 1);
	$pagination = new ModulePagination($page, $total_cat, $config->get_categories_number_per_page());
	$pagination->set_url(new Url('/gallery/admin_gallery.php?p=%d'));
	
	if ($pagination->current_page_is_empty() && $page > 1)
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	//Colonnes des catégories.
	$nbr_column_cats = ($total_cat > $config->get_columns_number()) ? $config->get_columns_number() : $total_cat;
	$nbr_column_cats = !empty($nbr_column_cats) ? $nbr_column_cats : 1;
	$column_width_cats = floor(100/$nbr_column_cats);

	//Colonnes des images.
	$nbr_column_pics = ($nbr_pics > $config->get_columns_number()) ? $config->get_columns_number() : $nbr_pics;
	$nbr_column_pics = !empty($nbr_column_pics) ? $nbr_column_pics : 1;
	$column_width_pics = floor(100/$nbr_column_pics);
	
	$tpl->put_all(array(
		'C_DISPLAY_NO_PICTURES_MESSAGE' => $category->get_id() != Category::ROOT_CATEGORY,
		'C_PICTURES' => $nbr_pics > 0,
		'C_SUBCATEGORIES_PAGINATION' => $pagination->has_several_pages(),
		'SUBCATEGORIES_PAGINATION' => $pagination->display(),
		'COLUMN_WIDTH_CAT' => $column_width_cats,
		'COLUMN_WIDTH_PICS' => $column_width_pics,
		'COLSPAN' => min($nbr_pics, $config->get_columns_number()),
		'CAT_ID' => $id_category,
		'GALLERY' => !empty($id_category) ? $LANG['gallery'] . ' : ' . $category->get_name() : $LANG['gallery'],
		'HEIGHT_MAX' => ($config->get_mini_max_height() - 15),
		'ARRAY_JS' => '',
		'NBR_PICS' => 0,
		'MAX_START' => 0,
		'START_THUMB' => 0,
		'END_THUMB' => 0,
		'L_GALLERY_MANAGEMENT' => LangLoader::get_message('gallery.management', 'common', 'gallery'),
		'L_GALLERY_PICS_ADD' => LangLoader::get_message('gallery.actions.add', 'common', 'gallery'),
		'L_GALLERY_CONFIG' => $LANG['gallery_config'],
		'L_CONFIRM_DEL_FILE' => $LANG['confim_del_file'],
		'L_FILE_FORBIDDEN_CHARS' => $LANG['file_forbidden_chars'],
		'L_TOTAL_IMG' => sprintf($LANG['total_img_cat'], $nbr_pics),
		'L_ADD_IMG' => $LANG['add_pic'],
		'L_GALLERY' => $LANG['gallery'],
		'L_CATEGORIES' => ($category->get_id_parent() >= 0) ? $LANG['sub_album'] : $LANG['album'],
		'L_NAME' => $LANG['name'],
		'L_APROB' => $LANG['aprob'],
		'L_UNAPROB' => $LANG['unaprob'],
		'L_EDIT' => LangLoader::get_message('edit', 'common'),
		'L_MOVETO' => $LANG['moveto'],
		'L_DELETE' => LangLoader::get_message('delete', 'common'),
		'L_SUBMIT' => $LANG['submit']
	));

	##### Catégorie disponibles #####
	$nbr_cat_displayed = 0;
	if ($total_cat > 0)
	{
		$tpl->assign_block_vars('cat', array(
		));
		
		$i = 0;
		foreach ($subcategories as $id => $cat)
		{
			$nbr_cat_displayed++;
			
			if ($nbr_cat_displayed > $pagination->get_display_from() && $nbr_cat_displayed <= ($pagination->get_display_from() + $pagination->get_number_items_per_page()))
			{
				//On genère le tableau pour $config->get_columns_number() colonnes
				$multiple_x = $i / $nbr_column_cats;
				$tr_start = is_int($multiple_x) ? '<tr>' : '';
				$i++;
				$multiple_x = $i / $nbr_column_cats;
				$tr_end = is_int($multiple_x) ? '</tr>' : '';
				
				$category_image = $cat->get_image()->rel();
				$elements_number = $cat->get_elements_number();
				
				$tpl->assign_block_vars('cat.list', array(
					'C_IMG' => !empty($category_image),
					'IDCAT' => $cat->get_id(),
					'CAT' => $cat->get_name(),
					'IMG' => $category_image,
					'TR_START' => $tr_start,
					'TR_END' => $tr_end,
					'L_NBR_PICS' => sprintf($LANG['nbr_pics_info_admin'], $elements_number['pics_aprob'], $elements_number['pics_unaprob'])
				));
			}
		}

		//Création des cellules du tableau si besoin est.
		while (!is_int($i/$nbr_column_cats))
		{
			$i++;
			$tpl->assign_block_vars('cat.end_td', array(
				'TD_END' => '<td style="width:' . $column_width_cats . '%">&nbsp;</td>',
				'TR_END' => (is_int($i/$nbr_column_cats)) ? '</tr>' : ''
			));
		}
	}

	##### Affichage des photos #####
	$tpl->assign_block_vars('pics', array(
		'C_PICS_MAX' => $nbr_pics == 0 || !empty($idpics),
		'EDIT' => !empty($id_category) ? '<a href="' . GalleryUrlBuilder::edit_category($id_category)->rel() . '" title="' . LangLoader::get_message('edit', 'common') . '" class="fa fa-edit"></a>' : '',
		'PICS_MAX' => '<img src="show_pics.php?id=' . $idpics . '&amp;cat=' . $id_category . '" alt="' . $category->get_name() . '" />'
	));
	
	if ($nbr_pics > 0)
	{
		//On crée une pagination si le nombre de photos est trop important.
		$page = AppContext::get_request()->get_getint('pp', 1);
		$pagination = new ModulePagination($page, $nbr_pics, $config->get_pics_number_per_page());
		$pagination->set_url(new Url('/gallery/admin_gallery.php?cat=' . $id_category . '&amp;pp=%d'));
		
		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
		
		$tpl->put_all(array(
			'C_PAGINATION' => $pagination->has_several_pages(),
			'PAGINATION' => $pagination->display()
		));

		if (!empty($idpics))
		{
			$info_pics = array();
			try {
				$info_pics = PersistenceContext::get_querier()->select_single_row_query("SELECT g.id, g.idcat, g.name, g.user_id, g.views, g.width, g.height, g.weight, g.timestamp, g.aprob, m.display_name, m.level, m.groups
				FROM " . GallerySetup::$gallery_table . " g
				LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = g.user_id
				WHERE g.idcat = :idcat AND g.id = :id", array(
					'idcat' => $id_category,
					'id' => $idpics
				));
			} catch (RowNotFoundException $e) {}
			
			if ($info_pics && !empty($info_pics['id']))
			{
				//Affichage miniatures.
				$id_previous = 0;
				$id_next = 0;
				$nbr_pics_display_before = floor(($nbr_column_pics - 1)/2); //Nombres de photos de chaque côté de la miniature de la photo affichée.
				$nbr_pics_display_after = ($nbr_column_pics - 1) - floor($nbr_pics_display_before);
				list($i, $reach_pics_pos, $pos_pics, $thumbnails_before, $thumbnails_after, $start_thumbnails, $end_thumbnails) = array(0, false, 0, 0, 0, $nbr_pics_display_before, $nbr_pics_display_after);
				$array_pics = array();
				$array_js = 'var array_pics = new Array();';
				$result = PersistenceContext::get_querier()->select("SELECT g.id, g.name, g.idcat, g.path
				FROM " . GallerySetup::$gallery_table . " g
				LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = g.user_id
				WHERE g.idcat = :idcat", array(
					'idcat' => $id_category
				));
				while ($row = $result->fetch())
				{
					//Si la miniature n'existe pas (cache vidé) on regénère la miniature à partir de l'image en taille réelle.
					if (!file_exists('pics/thumbnails/' . $row['path']))
						$Gallery->Resize_pics('pics/' . $row['path']); //Redimensionnement + création miniature

					//Affichage de la liste des miniatures sous l'image.
					$array_pics[] = '<td class="center" style="height:' . ($config->get_mini_max_height() + 16) . 'px"><span id="thumb' . $i . '"><a href="admin_gallery.php?cat=' . $row['idcat'] . '&amp;id=' . $row['id'] . '#pics_max' . '"><img src="pics/thumbnails/' . $row['path'] . '" alt="' . $row['name'] . '" /></a></span></td>';

					if ($row['id'] == $idpics)
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
					$array_js .= 'array_pics[' . $i . '][\'link\'] = \'.php?cat=' . $row['idcat'] . '&amp;id=' . $row['id'] . '#pics_max' . "';\n";
					$array_js .= 'array_pics[' . $i . '][\'path\'] = \'' . $row['path'] . "';\n";
					$i++;
				}
				$result->dispose();

				if ($thumbnails_before < $nbr_pics_display_before)
					$end_thumbnails += $nbr_pics_display_before - $thumbnails_before;
				if ($thumbnails_after < $nbr_pics_display_after)
					$start_thumbnails += $nbr_pics_display_after - $thumbnails_after;

				$tpl->put_all(array(
					'ARRAY_JS' => $array_js,
					'NBR_PICS' => ($i - 1),
					'MAX_START' => ($i - 1) - $nbr_column_pics,
					'START_THUMB' => (($pos_pics - $start_thumbnails) > 0) ? ($pos_pics - $start_thumbnails) : 0,
					'END_THUMB' => ($pos_pics + $end_thumbnails),
					'L_INFORMATIONS' => $LANG['informations'],
					'L_NAME' => $LANG['name'],
					'L_POSTOR' => $LANG['postor'],
					'L_VIEWS' => $LANG['views'],
					'L_ADD_ON' => $LANG['add_on'],
					'L_DIMENSION' => $LANG['dimension'],
					'L_SIZE' => $LANG['size'],
					'L_EDIT' => LangLoader::get_message('edit', 'common'),
					'L_APROB' => $LANG['aprob'],
					'L_UNAPROB' => $LANG['unaprob'],
					'L_THUMBNAILS' => $LANG['thumbnails']
				));

				//Liste des catégories.
				$search_category_children_options = new SearchCategoryChildrensOptions();
				$search_category_children_options->add_authorizations_bits(Category::READ_AUTHORIZATIONS);
				$search_category_children_options->add_authorizations_bits(Category::WRITE_AUTHORIZATIONS);
				$categories_tree = GalleryService::get_categories_manager()->get_select_categories_form_field($info_pics['id'] . 'cat', '', $info_pics['idcat'], $search_category_children_options);
				$method = new ReflectionMethod('AbstractFormFieldChoice', 'get_options');
				$method->setAccessible(true);
				$categories_tree_options = $method->invoke($categories_tree);
				$cat_list = '';
				foreach ($categories_tree_options as $option)
				{
					$cat_list .= $option->display()->render();
				}
				
				$group_color = User::get_group_color($info_pics['groups'], $info_pics['level']);
				
				//Affichage de l'image et de ses informations.
				$tpl->assign_block_vars('pics.pics_max', array(
					'C_APPROVED' => $info_pics['aprob'],
					'C_PREVIOUS' => ($pos_pics > 0),
					'C_NEXT' => ($pos_pics < ($i - 1)),
					'C_LEFT_THUMBNAILS' => ($pos_pics - $start_thumbnails),
					'C_RIGHT_THUMBNAILS' => (($pos_pics - $start_thumbnails) <= ($i - 1) - $nbr_column_pics),
					'ID' => $info_pics['id'],
					'IMG' => '<img src="show_pics.php?id=' . $idpics . '&amp;cat=' . $id_category . '" alt="' . $info_pics['name'] . '" />',
					'PICTURE_NAME' => stripslashes($info_pics['name']),
					'NAME' => '<span id="fi_' . $info_pics['id'] . '">' . stripslashes($info_pics['name']) . '</span> <span id="fi' . $info_pics['id'] . '"></span>',
					'POSTOR' => '<a class="' . UserService::get_level_class($info_pics['level']) . '"' . (!empty($group_color) ? ' style="color:' . $group_color . '"' : '') . ' href="'. UserUrlBuilder::profile($info_pics['user_id'])->rel() .'">' . $info_pics['display_name'] . '</a>',
					'DATE' => Date::to_format($info_pics['timestamp'], Date::FORMAT_DAY_MONTH_YEAR),
					'VIEWS' => ($info_pics['views'] + 1),
					'DIMENSION' => $info_pics['width'] . ' x ' . $info_pics['height'],
					'SIZE' => NumberHelper::round($info_pics['weight']/1024, 1),
					'COLSPAN' => min(($i + 2), ($config->get_columns_number() + 2)),
					'COLSPAN_PICTURE' => (int)($pos_pics > 0) + (int)($pos_pics < ($i - 1)),
					'CAT' => $cat_list,
					'RENAME' => addslashes($info_pics['name']),
					'RENAME_CUT' => addslashes($info_pics['name']),
					'U_DEL' => 'php?del=' . $info_pics['id'] . '&amp;cat=' . $id_category . '&amp;token=' . AppContext::get_session()->get_token(),
					'U_MOVE' => '.php?id=' . $info_pics['id'] . '&amp;token=' . AppContext::get_session()->get_token() . '&amp;move=\' + this.options[this.selectedIndex].value',
					'U_PREVIOUS' => '<a href="admin_gallery.php?cat=' . $id_category . '&amp;id=' . $id_previous . '#pics_max" class="fa fa-arrow-left fa-2x"></a> <a href="admin_gallery.php?cat=' . $id_category . '&amp;id=' . $id_previous . '#pics_max">' . $LANG['previous'] . '</a>',
					'U_NEXT' => '<a href="admin_gallery.php?cat=' . $id_category . '&amp;id=' . $id_next . '#pics_max">' . $LANG['next'] . '</a> <a href="admin_gallery.php?cat=' . $id_category . '&amp;id=' . $id_next . '#pics_max" class="fa fa-arrow-right fa-2x"></a>'
				));

				//Affichage de la liste des miniatures sous l'image.
				$i = 0;
				foreach ($array_pics as $pics)
				{
					if ($i >= ($pos_pics - $start_thumbnails) && $i <= ($pos_pics + $end_thumbnails))
					{
						$tpl->assign_block_vars('pics.pics_max.list_preview_pics', array(
							'PICS' => $pics
						));
					}
					$i++;
				}
			}
		}
		else
		{
			$j = 0;
			$result = PersistenceContext::get_querier()->select("SELECT g.id, g.idcat, g.name, g.path, g.timestamp, g.aprob, g.width, g.height, m.display_name, m.user_id, m.level, m.groups
			FROM " . GallerySetup::$gallery_table . " g
			LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = g.user_id
			WHERE g.idcat = :idcat
			ORDER BY g.timestamp
			LIMIT :number_items_per_page OFFSET :display_from", array(
				'idcat' => $id_category,
				'number_items_per_page' => $pagination->get_number_items_per_page(),
				'display_from' => $pagination->get_display_from()
			));
			while ($row = $result->fetch())
			{
				//Si la miniature n'existe pas (cache vidé) on regénère la miniature à partir de l'image en taille réelle.
				if (!file_exists('pics/thumbnails/' . $row['path']))
					$Gallery->Resize_pics('pics/' . $row['path']); //Redimensionnement + création miniature

				$name_cut = (strlen(TextHelper::html_entity_decode($row['name'])) > 22) ? TextHelper::htmlentities(substr(TextHelper::html_entity_decode($row['name']), 0, 22)) . '...' : $row['name'];

				//On reccourci le nom s'il est trop long pour éviter de déformer l'administration.
				$name = TextHelper::html_entity_decode($row['name']);
				$name = strlen($name) > 20 ? substr($name, 0, 20) . '...' : $name;

				//On genère le tableau pour x colonnes
				$tr_start = is_int($j / $nbr_column_pics) ? '<tr>' : '';
				$j++;
				$tr_end = is_int($j / $nbr_column_pics) ? '</tr>' : '';

				//Affichage de l'image en grand.
				if ($config->get_pics_enlargement_mode() == GalleryConfig::FULL_SCREEN) //Ouverture en popup plein écran.
					$display_link = HOST . DIR . '/gallery/show_pics' . url('.php?id=' . $row['id'] . '&amp;cat=' . $row['idcat']);
				elseif ($config->get_pics_enlargement_mode() == GalleryConfig::POPUP) //Ouverture en popup simple.
					$display_link = 'javascript:display_pics_popup(\'' . HOST . DIR . '/gallery/show_pics' . url('.php?id=' . $row['id'] . '&amp;cat=' . $row['idcat']) . '\', \'' . $row['width'] . '\', \'' . $row['height'] . '\')';
				elseif ($config->get_pics_enlargement_mode() == GalleryConfig::RESIZE) //Ouverture en agrandissement simple.
					$display_link = 'javascript:display_pics(' . $row['id'] . ', \'' . HOST . DIR . '/gallery/show_pics' . url('.php?id=' . $row['id'] . '&amp;cat=' . $row['idcat']) . '\', 0)';
				else //Ouverture nouvelle page.
					$display_link = 'admin_gallery.php?cat=' . $row['idcat'] . '&amp;id=' . $row['id'] . '#pics_max';

				//Liste des catégories.
				$search_category_children_options = new SearchCategoryChildrensOptions();
				$search_category_children_options->add_authorizations_bits(Category::READ_AUTHORIZATIONS);
				$search_category_children_options->add_authorizations_bits(Category::WRITE_AUTHORIZATIONS);
				$categories_tree = GalleryService::get_categories_manager()->get_select_categories_form_field($row['id'] . 'cat', '', $row['idcat'], $search_category_children_options);
				$method = new ReflectionMethod('AbstractFormFieldChoice', 'get_options');
				$method->setAccessible(true);
				$categories_tree_options = $method->invoke($categories_tree);
				$cat_list = '';
				foreach ($categories_tree_options as $option)
				{
					$cat_list .= $option->display()->render();
				}
				
				$group_color = User::get_group_color($row['groups'], $row['level']);
				
				$tpl->assign_block_vars('pics.list', array(
					'C_APPROVED' => $row['aprob'],
					'ID' => $row['id'],
					'IMG' => '<img src="pics/thumbnails/' . $row['path'] . '" alt="' . $name . '" />',
					'PATH' => $row['path'],
					'NAME' => stripslashes($name_cut),
					'TITLE' => stripslashes($row['name']),
					'RENAME_FILE' => '<span id="fihref' . $row['id'] . '"><a href="javascript:display_rename_file(\'' . $row['id'] . '\', \'' . addslashes($row['name']) . '\', \'' . addslashes($name_cut) . '\');" title="' . LangLoader::get_message('edit', 'common') . '" class="fa fa-edit"></a></span>',
					'TR_START' => $tr_start,
					'TR_END' => $tr_end,
					'CAT' => $cat_list,
					'U_DISPLAY' => $display_link,
					'U_POSTOR' => $LANG['by'] . ' <a class="' . UserService::get_level_class($row['level']) . '"' . (!empty($group_color) ? ' style="color:' . $group_color . '"' : '') . ' href="'. UserUrlBuilder::profile($row['user_id'])->rel() .'">' . $row['display_name'] . '</a>',
				));
			}
			$result->dispose();

			//Création des cellules du tableau si besoin est.
			while (!is_int($j/$nbr_column_pics))
			{
				$j++;
				$tpl->assign_block_vars('pics.end_td_pics', array(
					'TD_END' => '<td style="width:' . $column_width_pics . '%">&nbsp;</td>',
					'TR_END' => (is_int($j/$nbr_column_pics)) ? '</tr>' : ''
				));
			}
		}
	}

	$tpl->display();
}

require_once('../admin/admin_footer.php');

?>
