<?php
/*##################################################
 *                               admin_gallery_add.php
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

$Cache->load('gallery');
$Gallery = new Gallery();
$config = GalleryConfig::load();

$idcat = !empty($_GET['cat']) ? NumberHelper::numeric($_GET['cat']) : 0;
$idcat_post = !empty($_POST['idcat_post']) ? NumberHelper::numeric($_POST['idcat_post']) : 0;
$add_pic = !empty($_GET['add']) ? NumberHelper::numeric($_GET['add']) : 0;
$nbr_pics_post = !empty($_POST['nbr_pics']) ? NumberHelper::numeric($_POST['nbr_pics']) : 0;

if (isset($_FILES['gallery']) && isset($_POST['idcat_post'])) //Upload
{
	$dir = 'pics/';
	$Upload = new Upload($dir);
	$Upload->disableContentCheck();

	$idpic = 0;
	$Upload->file('gallery', '`([a-z0-9()_-])+\.(jpg|jpeg|gif|png)+$`i', Upload::UNIQ_NAME, $config->get_max_weight());
	if ($Upload->get_error() != '') //Erreur, on arrête ici
		AppContext::get_response()->redirect('/gallery/admin_gallery_add.php?error=' . $Upload->get_error() . ($idcat_post ? '&cat=' . $idcat_post : '') . '#message_helper');
	else
	{
		$path = $dir . $Upload->get_filename();
		$error = $Upload->check_img($config->get_max_width(), $config->get_max_height(), Upload::DELETE_ON_ERROR);
		if (!empty($error)) //Erreur, on arrête ici
			AppContext::get_response()->redirect('/gallery/admin_gallery_add.php?error=' . $error . ($idcat_post ? '&cat=' . $idcat_post : '') . '#message_helper');
		else
		{
			//Enregistrement de l'image dans la bdd.
			$Gallery->Resize_pics($path);
			if ($Gallery->get_error() != '')
				AppContext::get_response()->redirect('/gallery/admin_gallery_add.php?error=' . $Gallery->get_error() . ($idcat_post ? '&cat=' . $idcat_post : '') . '#message_helper');

			$name = !empty($_POST['name']) ? TextHelper::strprotect($_POST['name']) : '';
			$idpic = $Gallery->Add_pics($idcat_post, $name, $Upload->get_filename(), AppContext::get_current_user()->get_id());
			if ($Gallery->get_error() != '')
				AppContext::get_response()->redirect('/gallery/admin_gallery_add.php?error=' . $Gallery->get_error() . ($idcat_post ? '&cat=' . $idcat_post : '') . '#message_helper');

			//Régénération du cache des photos aléatoires.
			$Cache->Generate_module_file('gallery');
		}
	}

	AppContext::get_response()->redirect('/gallery/admin_gallery_add.php?add=' . $idpic . ($idcat_post ? '&cat=' . $idcat_post : ''));
}
elseif (!empty($_POST['valid']) && !empty($nbr_pics_post)) //Ajout massif d'images par ftp.
{
	for ($i = 1; $i <= $nbr_pics_post; $i++)
	{
		$activ = !empty($_POST[$i . 'activ']) ? trim($_POST[$i . 'activ']) : '';
		$uniq = !empty($_POST[$i . 'uniq']) ? TextHelper::strprotect($_POST[$i . 'uniq']) : '';
		if ($activ && !empty($uniq)) //Sélectionné.
		{
			$name = !empty($_POST[$i . 'name']) ? TextHelper::strprotect($_POST[$i . 'name']) : 0;
			$cat = !empty($_POST[$i . 'cat']) ? NumberHelper::numeric($_POST[$i . 'cat']) : 0;
			$del = !empty($_POST[$i . 'del']) ? NumberHelper::numeric($_POST[$i . 'del']) : 0;

			if ($del)
			{
				$file = new File('pics/' . $uniq);
				$file->delete();
			}
			else
				$Gallery->Add_pics($cat, $name, $uniq, AppContext::get_current_user()->get_id());
	
		}
	}

	//Régénération du cache des photos aléatoires.
	$Cache->Generate_module_file('gallery');

	AppContext::get_response()->redirect('/gallery/admin_gallery_add.php');
}
else
{
	$tpl = new FileTemplate('gallery/admin_gallery_add.tpl');

	//Gestion erreur.
	$get_error = !empty($_GET['error']) ? trim($_GET['error']) : '';
	$array_error = array('e_upload_invalid_format', 'e_upload_max_weight', 'e_upload_max_dimension', 'e_upload_error', 'e_upload_php_code', 'e_upload_failed_unwritable', 'e_upload_already_exist', 'e_unlink_disabled', 'e_unsupported_format', 'e_unabled_create_pics', 'e_error_resize', 'e_no_graphic_support', 'e_unabled_incrust_logo', 'delete_thumbnails');
	if (in_array($get_error, $array_error))
		$tpl->put('message_helper', MessageHelper::display($LANG[$get_error], MessageHelper::WARNING));

	//Aficchage de la photo uploadée.
	if (!empty($add_pic))
	{
		$categories = GalleryService::get_categories_manager()->get_categories_cache()->get_categories();
		$imageup = $db_querier->select_single_row(GallerySetup::$gallery_table, array('idcat', 'name', 'path'), 'WHERE id = :id', array('id' => $add_pic));
		$tpl->assign_block_vars('image_up', array(
			'NAME' => $imageup['name'],
			'IMG' => '<a href="admin_gallery.php?cat=' . $imageup['idcat'] . '&amp;id=' . $add_pic . '#pics_max"><img src="pics/' . $imageup['path'] . '" alt="" /></a>',
			'L_SUCCESS_UPLOAD' => $LANG['success_upload_img'],
			'U_CAT' => '<a href="admin_gallery.php?cat=' . $imageup['idcat'] . '">' . $categories[$imageup['idcat']]->get_name() . '</a>'
		));
	}

	$tpl->put_all(array(
		'WIDTH_MAX' => $config->get_max_width(),
		'HEIGHT_MAX' => $config->get_max_height(),
		'WEIGHT_MAX' => $config->get_max_weight(),
		'AUTH_EXTENSION' => 'JPEG, GIF, PNG',
		'IMG_HEIGHT_MAX' => $config->get_mini_max_height()+10,
		'L_GALLERY_MANAGEMENT' => LangLoader::get_message('gallery.management', 'common', 'gallery'),
		'L_GALLERY_PICS_ADD' => LangLoader::get_message('gallery.actions.add', 'common', 'gallery'),
		'L_GALLERY_CAT_MANAGEMENT' => LangLoader::get_message('categories.management', 'categories-common'),
		'L_GALLERY_CAT_ADD' => LangLoader::get_message('category.add', 'categories-common'),
		'L_GALLERY_CONFIG' => $LANG['gallery_config'],
		'L_ADD_IMG' => $LANG['add_pic'],
		'L_WEIGHT_MAX' => $LANG['weight_max'],
		'L_HEIGHT_MAX' => $LANG['height_max'],
		'L_WIDTH_MAX' => $LANG['width_max'],
		'L_UPLOAD_IMG' => $LANG['upload_pics'],
		'L_AUTH_EXTENSION' => $LANG['auth_extension'],
		'L_IMG_DISPO_GALLERY' => $LANG['img_dispo'],
		'L_NAME' => $LANG['name'],
		'L_UNIT_PX' => LangLoader::get_message('unit.pixels', 'common'),
		'L_UNIT_KO' => LangLoader::get_message('unit.kilobytes', 'common'),
		'L_SELECT' => $LANG['select'],
		'L_SELECT_ALL_PICTURES' => $LANG['select_all_pictures'],
		'L_UNSELECT_ALL_PICTURES' => $LANG['unselect_all_pictures'],
		'L_GLOBAL_CAT_SELECTION' => $LANG['global_cat_selection'],
		'L_GLOBAL_CAT_SELECTION_EXPLAIN' => $LANG['global_cat_selection_explain'],
		'L_DELETE' => LangLoader::get_message('delete', 'common'),
		'L_SUBMIT' => $LANG['submit'],
		'L_NO_IMG' => $LANG['no_pics']
	));

	//Affichage photos
	$dir = 'pics/';
	if (is_dir($dir)) //Si le dossier existe
	{
		$array_pics = array();
		$image_folder_path = new Folder('./pics/');
		foreach ($image_folder_path->get_files('`.*\.(png|jpg|bmp|gif|jpeg|tiff)$`i') as $image)
			$array_pics[] = $image->get_name();

		if (is_array($array_pics))
		{
			$result = PersistenceContext::get_querier()->select("SELECT path
			FROM " . GallerySetup::$gallery_table);
			while ($row = $result->fetch())
			{
				//On recherche les clées correspondante à celles trouvée dans la bdd.
				$key = array_search($row['path'], $array_pics);
				if ($key !== false)
					unset($array_pics[$key]); //On supprime ces clées du tableau.
			}
			$result->dispose();

			//Colonnes des images.
			$nbr_pics = count($array_pics);
			$nbr_column_pics = ($nbr_pics > $config->get_columns_number()) ? $config->get_columns_number() : $nbr_pics;
			$nbr_column_pics = !empty($nbr_column_pics) ? $nbr_column_pics : 1;
			$column_width_pics = floor(100/$nbr_column_pics);
			$selectbox_width = floor(100-(10*$nbr_column_pics));
			
			//Liste des catégories.
			$search_category_children_options = new SearchCategoryChildrensOptions();
			$search_category_children_options->add_authorizations_bits(Category::READ_AUTHORIZATIONS);
			$search_category_children_options->add_authorizations_bits(Category::WRITE_AUTHORIZATIONS);
			$categories_tree = GalleryService::get_categories_manager()->get_select_categories_form_field('category', '', $idcat, $search_category_children_options);
			$method = new ReflectionMethod('AbstractFormFieldChoice', 'get_options');
			$method->setAccessible(true);
			$categories_tree_options = $method->invoke($categories_tree);
			$categories_list = '';
			foreach ($categories_tree_options as $option)
			{
				$categories_list .= $option->display()->render();
			}
			
			$root_categories_tree = GalleryService::get_categories_manager()->get_select_categories_form_field('root_cat', '', $idcat, $search_category_children_options);
			$root_categories_tree_options = $method->invoke($root_categories_tree);
			$root_categories_list = '';
			foreach ($root_categories_tree_options as $option)
			{
				$root_categories_list .= $option->display()->render();
			}
			
			$tpl->put_all(array(
				'NBR_PICS' => $nbr_pics,
				'COLUMN_WIDTH_PICS' => $column_width_pics,
				'SELECTBOX_WIDTH' => $selectbox_width,
				'CATEGORIES' => $categories_list,
				'ROOT_CATEGORIES' => $root_categories_list,
			));
			
			$j = 0;
			foreach ($array_pics as  $key => $pics)
			{
				$height = 150;
				$width = 150;
				if (function_exists('getimagesize')) //On verifie l'existence de la fonction getimagesize.
				{
					// On recupère la hauteur et la largeur de l'image.
					list($width_source, $height_source) = @getimagesize($rep . $pics);

					$height_max = 150;
					$width_max = 150;

					if (($width_source > $width_max) || ($height_source > $height_max))
					{
						if ($width_source > $height_source)
						{
							$ratio = $width_source / $height_source;
							$width = $width_max;
							$height = ceil($width / $ratio);
						}
						else
						{
							$ratio = $height_source / $width_source;
							$height = $height_max;
							$width = ceil($height / $ratio);
						}
					}
					else
					{
						$width = $width_source;
						$height = $height_source;
					}
				}

				//On genère le tableau pour x colonnes
				$tr_start = is_int($j / $nbr_column_pics) ? '<tr>' : '';
				$j++;
				$tr_end = is_int($j / $nbr_column_pics) ? '</tr>' : '';

				//On raccourci le nom du fichier pour ne pas déformer l'administration.
				$name = strlen($pics) > 20 ? substr($pics, 0, 20) . '...' : $pics;

				//Si la miniature n'existe pas (cache vidé) on regénère la miniature à partir de l'image en taille réelle.
				if (!file_exists('pics/thumbnails/' . $pics) && file_exists('pics/' . $pics))
					$Gallery->Resize_pics('pics/' . $pics); //Redimensionnement + création miniature
				
				$categories_tree = GalleryService::get_categories_manager()->get_select_categories_form_field($j . 'cat', '', Category::ROOT_CATEGORY, $search_category_children_options);
				$method = new ReflectionMethod('AbstractFormFieldChoice', 'get_options');
				$method->setAccessible(true);
				$categories_tree_options = $method->invoke($categories_tree);
				$categories_list = '';
				foreach ($categories_tree_options as $option)
				{
					$categories_list .= $option->display()->render();
				}
				
				$tpl->assign_block_vars('list', array(
					'ID' => $j,
					'THUMNAILS' => '<img src="pics/thumbnails/' .  $pics . '" alt="" />',
					'NAME' => $pics,
					'UNIQ_NAME' => $pics,
					'TR_START' => $tr_start,
					'TR_END' => $tr_end,
					'CATEGORIES' => $categories_list,
				));
			}

			//Création des cellules du tableau si besoin est.
			while (!is_int($j/$nbr_column_pics))
			{
				$j++;
				$tpl->assign_block_vars('end_td_pics', array(
					'TD_END' => '<td style="width:' . $column_width_pics . '%;padding:0">&nbsp;</td>',
					'TR_END' => (is_int($j/$nbr_column_pics)) ? '</tr>' : ''
				));
			}
		}
	}

	$tpl->put('C_IMG', $j);

	$tpl->display();
}

require_once('../admin/admin_footer.php');

?>