<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 05
 * @since       PHPBoost 1.2 - 2005 08 17
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

require_once('../admin/admin_begin.php');
load_module_lang('gallery'); // loading of the module lang
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

$Gallery = new Gallery();
$config = GalleryConfig::load();

$request = AppContext::get_request();

$idcat = $request->get_getint('cat', 0);
$idcat_post = $request->get_postint('idcat_post', 0);
$add_pic = $request->get_getint('add', 0);
$nbr_pics_post = $request->get_postint('nbr_pics', 0);

$valid = $request->get_postvalue('valid', false);

$tpl = new FileTemplate('gallery/admin_gallery_add.tpl');

if (isset($_FILES['gallery'])) // Upload
{
	$dir = 'pics/';
	$authorized_pictures_extensions = FileUploadConfig::load()->get_authorized_picture_extensions();
	$error = '';

	if (!empty($authorized_pictures_extensions))
	{
		$Upload = new Upload($dir);
		$Upload->disableContentCheck();

		$idpic = 0;
		if (!$Upload->file('gallery', '`\.(' . implode('|', array_map('preg_quote', $authorized_pictures_extensions)) . ')+$`iu', Upload::UNIQ_NAME, $config->get_max_weight()))
			$error = $Upload->get_error();
	}
	else
		$error = 'e_upload_invalid_format';

	if ($error != '') // Error, then stop here
		$tpl->put('message_helper', MessageHelper::display($LANG[$error], MessageHelper::WARNING));
	else
	{
		$path = $dir . $Upload->get_filename();
		$error = $Upload->check_img($config->get_max_width(), $config->get_max_height(), Upload::DELETE_ON_ERROR);
		if (!empty($error)) // Error, then stop here
			$tpl->put('message_helper', MessageHelper::display($LANG[$error], MessageHelper::WARNING));
		else
		{
			// Saving the picture in database.
			$Gallery->Resize_pics($path);
			if ($Gallery->get_error() != '')
				$tpl->put('message_helper', MessageHelper::display($LANG[$Gallery->get_error()], MessageHelper::WARNING));

			$name = TextHelper::strprotect($request->get_postvalue('name', ''));
			foreach ($Upload->get_files_parameters() as $parameters)
			{
				$idpic = $Gallery->Add_pics($idcat_post, $name, $parameters['path'], AppContext::get_current_user()->get_id());
				if ($Gallery->get_error() != '')
					$tpl->put('message_helper', MessageHelper::display($LANG[$Gallery->get_error()], MessageHelper::WARNING));
			}

			// Regenerate cache of mini module
			GalleryMiniMenuCache::invalidate();
			GalleryCategoriesCache::invalidate();
		}
	}

	if (empty($error) && !$Gallery->get_error())
		$tpl->put('message_helper', MessageHelper::display(LangLoader::get_message('process.success', 'status-messages-common'), MessageHelper::SUCCESS, 4));
}
elseif ($valid && !empty($nbr_pics_post)) // Massive addition through ftp
{
	for ($i = 1; $i <= $nbr_pics_post; $i++)
	{
		$activ = trim($request->get_postvalue($i . 'activ', ''));
		$uniq = trim($request->get_postvalue($i . 'uniq', ''));
		if ($activ && !empty($uniq)) // Selected
		{
			$name = TextHelper::strprotect($request->get_postvalue($i . 'name', ''));
			$cat = NumberHelper::numeric($request->get_postint($i . 'cat', 0));
			$del = NumberHelper::numeric($request->get_postint($i . 'del', 0));

			if ($del)
			{
				$file = new File('pics/' . $uniq);
				$file->delete();
			}
			else
				$Gallery->Add_pics($cat, $name, $uniq, AppContext::get_current_user()->get_id());

		}
	}

	// Regenerate cache of mini module
	GalleryMiniMenuCache::invalidate();
	GalleryCategoriesCache::invalidate();

	$tpl->put('message_helper', MessageHelper::display(LangLoader::get_message('process.success', 'status-messages-common'), MessageHelper::SUCCESS, 4));
}

// Display of the uploaded picture
if (!empty($add_pic))
{
	$categories = CategoriesService::get_categories_manager('gallery', 'idcat')->get_categories_cache()->get_categories();

	try {
		$imageup = PersistenceContext::get_querier()->select_single_row(GallerySetup::$gallery_table, array('idcat', 'name', 'path'), 'WHERE id = :id', array('id' => $add_pic));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_element();
		DispatchManager::redirect($error_controller);
	}

	$tpl->assign_block_vars('image_up', array(
		'NAME' => stripslashes($imageup['name']),
		'PATH' => $imageup['path'],
		'U_IMG' => 'admin_gallery.php?cat=' . $imageup['idcat'] . '&amp;id=' . $add_pic . '#pics_max',
		'L_SUCCESS_UPLOAD' => $LANG['success_upload_img'],
		'CATNAME' => $categories[$imageup['idcat']]->get_name(),
		'U_CAT' => 'admin_gallery.php?cat=' . $imageup['idcat']
	));
}

$tpl->put_all(array(
	'MAX_WIDTH' => $config->get_max_width(),
	'MAX_HEIGHT' => $config->get_max_height(),
	'MAX_FILE_SIZE' => $config->get_max_weight() * 1024,
	// 'MAX_FILE_SIZE_TEXT' => File::get_formated_size($config->get_max_weight()),
	'MAX_FILE_SIZE_TEXT' => ($config->get_max_weight() / 1024) . ' ' . LangLoader::get_message('unit.megabytes', 'common'),
	'IMG_HEIGHT_MAX' => $config->get_mini_max_height()+10,
	'ALLOWED_EXTENSIONS' => implode('", "',FileUploadConfig::load()->get_authorized_picture_extensions()),
	'L_GALLERY_MANAGEMENT' => LangLoader::get_message('gallery.management', 'common', 'gallery'),
	'L_GALLERY_PICS_ADD' => LangLoader::get_message('gallery.actions.add', 'common', 'gallery'),
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
	foreach ($image_folder_path->get_files('`.*\.(png|jpg|bmp|gif|jpeg|tiff)$`iu') as $image)
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
		$categories_tree = CategoriesService::get_categories_manager('gallery', 'idcat')->get_select_categories_form_field('category', '', $idcat, $search_category_children_options);
		$method = new ReflectionMethod('AbstractFormFieldChoice', 'get_options');
		$method->setAccessible(true);
		$categories_tree_options = $method->invoke($categories_tree);
		$categories_list = '';
		foreach ($categories_tree_options as $option)
		{
			$categories_list .= $option->display()->render();
		}

		$root_categories_tree = CategoriesService::get_categories_manager('gallery', 'idcat')->get_select_categories_form_field('root_cat', '', $idcat, $search_category_children_options);
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
			$display_tr_start = is_int($j / $nbr_column_pics);
			$j++;
			$display_tr_end = is_int($j / $nbr_column_pics);

			//On raccourci le nom du fichier pour ne pas déformer l'administration.
			$name = TextHelper::strlen($pics) > 20 ? TextHelper::substr($pics, 0, 20) . '...' : $pics;

			//Si la miniature n'existe pas (cache vidé) on regénère la miniature à partir de l'image en taille réelle.
			if (!file_exists('pics/thumbnails/' . $pics) && file_exists('pics/' . $pics))
				$Gallery->Resize_pics('pics/' . $pics); //Redimensionnement + création miniature

			$categories_tree = CategoriesService::get_categories_manager('gallery', 'idcat')->get_select_categories_form_field($j . 'cat', '', Category::ROOT_CATEGORY, $search_category_children_options);
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
				'NAME' => $pics,
				'UNIQ_NAME' => $pics,
				'C_DISPLAY_TR_START' => $display_tr_start,
				'C_DISPLAY_TR_END' => $display_tr_end,
				'CATEGORIES' => $categories_list,
			));
		}

		//Création des cellules du tableau si besoin est.
		while (!is_int($j/$nbr_column_pics))
		{
			$j++;
			$tpl->assign_block_vars('end_td_pics', array(
				'COLUMN_WIDTH_PICS' => $column_width_pics,
				'C_DISPLAY_TR_END' => (is_int($j/$nbr_column_pics))
			));
		}
	}
}

$tpl->put('C_IMG', $j);

$tpl->display();

require_once('../admin/admin_footer.php');

?>
