<?php
/*##################################################
 *                               gallery.php
 *                            -------------------
 *   begin                : August 12, 2005
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
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

require_once('../kernel/begin.php');
require_once('../gallery/gallery_begin.php');
require_once('../kernel/header.php');

$config = GalleryConfig::load();

$g_idpics = retrieve(GET, 'id', 0);
$g_del = retrieve(GET, 'del', 0);
$g_move = retrieve(GET, 'move', 0);
$g_add = retrieve(GET, 'add', false);
$g_page = retrieve(GET, 'p', 1);
$g_views = retrieve(GET, 'views', false);
$g_notes = retrieve(GET, 'notes', false);
$g_sort = retrieve(GET, 'sort', '');
$g_sort = !empty($g_sort) ? 'sort=' . $g_sort : '';

//Récupération du mode d'ordonnement.
if (preg_match('`([a-z]+)_([a-z]+)`', $g_sort, $array_match))
{
	$g_type = $array_match[1];
	$g_mode = $array_match[2];
}
else
	list($g_type, $g_mode) = array('date', 'desc');

$Gallery = new Gallery();

if (!empty($g_del)) //Suppression d'une image.
{
	if (AppContext::get_current_user()->is_readonly())
	{
		$controller = PHPBoostErrors::user_in_read_only();
		DispatchManager::redirect($controller);
	}
	
	AppContext::get_session()->csrf_get_protect(); //Protection csrf

	$Gallery->Del_pics($g_del);

	//Régénération du cache des photos aléatoires.
	GalleryMiniMenuCache::invalidate();
	GalleryCategoriesCache::invalidate();
	
	AppContext::get_response()->redirect('/gallery/gallery' . url('.php?cat=' . $id_category, '-' . $id_category . '.php', '&'));
}
elseif (!empty($g_idpics) && $g_move) //Déplacement d'une image.
{
	if (AppContext::get_current_user()->is_readonly())
	{
		$controller = PHPBoostErrors::user_in_read_only();
		DispatchManager::redirect($controller);
	}
	
	AppContext::get_session()->csrf_get_protect(); //Protection csrf

	$g_move = max($g_move, 0);
	$Gallery->Move_pics($g_idpics, $g_move);

	//Régénération du cache des photos aléatoires.
	GalleryMiniMenuCache::invalidate();
	GalleryCategoriesCache::invalidate();

	AppContext::get_response()->redirect('/gallery/gallery' . url('.php?cat=' . $g_move, '-' . $g_move . '.php', '&'));
}
elseif (isset($_FILES['gallery'])) //Upload
{
	if (AppContext::get_current_user()->is_readonly())
	{
		$controller = PHPBoostErrors::user_in_read_only();
		DispatchManager::redirect($controller);
	}
	
	//Niveau d'autorisation de la catégorie, accès en écriture.
	if (!GalleryAuthorizationsService::check_authorizations($id_category)->write())
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
	}

	//Niveau d'autorisation de la catégorie, accès en écriture.
	if (!$Gallery->auth_upload_pics(AppContext::get_current_user()->get_id(), AppContext::get_current_user()->get_level()))
		AppContext::get_response()->redirect('/gallery/gallery' . url('.php?add=1&cat=' . $id_category . '&error=upload_limit', '-' . $id_category . '.php?add=1&error=upload_limit', '&') . '#message_helper');

	$dir = 'pics/';

	$authorized_pictures_extensions = FileUploadConfig::load()->get_authorized_picture_extensions();
	$error = '';
	
	if (!empty($authorized_pictures_extensions))
	{
		$Upload = new Upload($dir);

		$idpic = 0;
		$idcat_post = retrieve(POST, '_cat', 0);
		$name_post = retrieve(POST, 'name', '', TSTRING_AS_RECEIVED);

		if (!$Upload->file('gallery', '`([a-z0-9()_-])+\.(' . implode('|', array_map('preg_quote', $authorized_pictures_extensions)) . ')+$`i', Upload::UNIQ_NAME, $config->get_max_weight()))
			$error = $Upload->get_error();
	}
	else
		$error = 'e_upload_invalid_format';
		
	if ($error != '') //Erreur, on arrête ici
	{
		AppContext::get_response()->redirect(GalleryUrlBuilder::get_link_cat_add($id_category, $error) . '#message_helper');
	}
	else
	{
		$path = $dir . $Upload->get_filename();
		$error = $Upload->check_img($config->get_max_width(), $config->get_max_height(), Upload::DELETE_ON_ERROR);
		if (!empty($error)) //Erreur, on arrête ici
			AppContext::get_response()->redirect(GalleryUrlBuilder::get_link_cat_add($id_category,$error) . '#message_helper');
		else
		{
			//Enregistrement de l'image dans la bdd.
			$Gallery->Resize_pics($path);
			if ($Gallery->get_error() != '')
				AppContext::get_response()->redirect(GalleryUrlBuilder::get_link_cat_add($id_category,$Upload->get_error()) . '#message_helper');

			$idpic = $Gallery->Add_pics($idcat_post, $name_post, $Upload->get_filename(), AppContext::get_current_user()->get_id());
			if ($Gallery->get_error() != '')
				AppContext::get_response()->redirect(GalleryUrlBuilder::get_link_cat_add($id_category,$Upload->get_error()) . '#message_helper');

			//Régénération du cache des photos aléatoires.
			GalleryMiniMenuCache::invalidate();
			GalleryCategoriesCache::invalidate();
		}
	}

	AppContext::get_response()->redirect(Url::to_absolute('/gallery/gallery' . url('.php?add=1&cat=' . $idcat_post . '&id=' . $idpic, '-' . $idcat_post . '-' . $idpic . '.php?add=1', '&')));
}
elseif ($g_add)
{
	if (AppContext::get_current_user()->is_readonly())
	{
		$controller = PHPBoostErrors::user_in_read_only();
		DispatchManager::redirect($controller);
	}
	
	$categories = GalleryService::get_categories_manager()->get_categories_cache()->get_categories();
	$tpl = new FileTemplate('gallery/gallery_add.tpl');

	//Niveau d'autorisation de la catégorie, accès en écriture.
	if (!GalleryAuthorizationsService::check_authorizations($id_category)->write())
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
	}
	
	$cat_links = '';
	foreach ($categories as $category)
	{
		if ($category->get_id() != Category::ROOT_CATEGORY && $category->get_id_parent() == $categories[$id_category]->get_id_parent())
			$cat_links .= ' <a href="' . GalleryUrlBuilder::get_link_cat($category->get_id()) . '">' . $category->get_name() . '</a> &raquo;';
	}

	//Gestion erreur.
	$get_error = retrieve(GET, 'error', '');
	$array_error = array('e_upload_invalid_format', 'e_upload_max_weight', 'e_upload_max_dimension', 'e_upload_error', 'e_upload_php_code', 'e_upload_failed_unwritable', 'e_upload_already_exist', 'e_unlink_disabled', 'e_unsupported_format', 'e_unabled_create_pics', 'e_error_resize', 'e_no_graphic_support', 'e_unabled_incrust_logo', 'delete_thumbnails', 'upload_limit');
	if (in_array($get_error, $array_error))
		$tpl->put('message_helper', MessageHelper::display(LangLoader::get_message($get_error, 'errors'), MessageHelper::WARNING));

	$module_data_path = $tpl->get_pictures_data_path();

	//Aficchage de la photo uploadée.
	if (!empty($g_idpics))
	{
		try {
			$imageup = PersistenceContext::get_querier()->select_single_row(GallerySetup::$gallery_table, array('idcat', 'name', 'path'), 'WHERE id = :id', array('id' => $g_idpics));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_element();
			DispatchManager::redirect($error_controller);
		}
		
		$tpl->assign_block_vars('image_up', array(
			'NAME' => stripslashes($imageup['name']),
			'IMG' => '<a href="gallery.php?cat=' . $imageup['idcat'] . '&amp;id=' . $g_idpics . '#pics_max"><img src="pics/' . $imageup['path'] . '" alt="' . $imageup['name'] . '" /></a>',
			'L_SUCCESS_UPLOAD' => $LANG['success_upload_img'],
			'U_CAT' => '<a href="gallery.php?cat=' . $imageup['idcat'] . '">' . $categories[$imageup['idcat']]->get_name() . '</a>'
		));
	}

	//Affichage du quota d'image uploadée.
	$category_authorizations = GalleryService::get_categories_manager()->get_heritated_authorizations($id_category, Category::WRITE_AUTHORIZATIONS, Authorizations::AUTH_PARENT_PRIORITY);
	$quota = isset($category_authorizations['r-1']) ? ($category_authorizations['r-1'] != '3') : true;
	if ($quota)
	{
		switch (AppContext::get_current_user()->get_level())
		{
			case 2:
			$l_pics_quota = $LANG['illimited'];
			break;
			case 1:
			$l_pics_quota = $config->get_moderator_max_pics_number();
			break;
			default:
			$l_pics_quota = $config->get_member_max_pics_number();
		}
		$nbr_upload_pics = $Gallery->get_nbr_upload_pics(AppContext::get_current_user()->get_id());

		$tpl->assign_block_vars('image_quota', array(
			'L_IMAGE_QUOTA' => sprintf($LANG['image_quota'], $nbr_upload_pics, $l_pics_quota)
		));
	}
	
	$search_category_children_options = new SearchCategoryChildrensOptions();
	$search_category_children_options->add_authorizations_bits(Category::WRITE_AUTHORIZATIONS);
	
	$tpl->put_all(array(
		'CAT_ID' => $id_category,
		'GALLERY' => !empty($id_category) ? $categories[$id_category]->get_name() : $LANG['gallery'],
		'CATEGORIES_TREE' => GalleryService::get_categories_manager()->get_select_categories_form_field('cat', LangLoader::get_message('form.category', 'common'), $id_category, $search_category_children_options)->display()->render(),
		'WIDTH_MAX' => $config->get_max_width(),
		'HEIGHT_MAX' => $config->get_max_height(),
		'WEIGHT_MAX' => $config->get_max_weight(),
		'IMG_FORMAT' => 'JPG, PNG, GIF',
		'L_IMG_FORMAT' => $LANG['img_format'],
		'L_WIDTH_MAX' => $LANG['width_max'],
		'L_HEIGHT_MAX' => $LANG['height_max'],
		'L_WEIGHT_MAX' => $LANG['weight_max'],
		'L_ADD_IMG' => $LANG['add_pic'],
		'L_GALLERY' => $LANG['gallery'],
		'L_GALLERY_INDEX' => $LANG['gallery_index'],
		'L_CATEGORIES' => $LANG['categories'],
		'L_NAME' => $LANG['name'],
		'L_UNIT_PX' => LangLoader::get_message('unit.pixels', 'common'),
		'L_UNIT_KO' => LangLoader::get_message('unit.kilobytes', 'common'),
		'L_UPLOAD' => $LANG['upload_img'],
		'U_GALLERY_CAT_LINKS' => $cat_links,
		'U_GALLERY_ACTION_ADD' => GalleryUrlBuilder::get_link_cat_add($id_category,null,AppContext::get_session()->get_token()),
		'U_INDEX' => url('.php')
	));

	$tpl->display();
}
else
{
	$module = AppContext::get_extension_provider_service()->get_provider('gallery');
	if ($module->has_extension_point(HomePageExtensionPoint::EXTENSION_POINT))
	{
		echo $module->get_extension_point(HomePageExtensionPoint::EXTENSION_POINT)->get_home_page()->get_view()->display();
	}
}

require_once('../kernel/footer.php');

?>
