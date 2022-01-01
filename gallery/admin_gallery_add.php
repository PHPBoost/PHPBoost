<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 15
 * @since       PHPBoost 1.2 - 2005 08 17
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

require_once('../admin/admin_begin.php');

$lang = LangLoader::get_all_langs('gallery');

define('TITLE', $lang['gallery.add.items']);
require_once('../admin/admin_header.php');

$Gallery = new Gallery();
$config = GalleryConfig::load();

$request = AppContext::get_request();

$id_category = $request->get_getint('cat', 0);
$id_category_post = $request->get_postint('id_category_post', 0);
$add_pic = $request->get_getint('add', 0);
$nbr_pics_post = $request->get_postint('nbr_pics', 0);

$valid = $request->get_postvalue('valid', false);

$view = new FileTemplate('gallery/admin_gallery_add.tpl');
$view->add_lang($lang);

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
		$view->put('MESSAGE_HELPER', MessageHelper::display($lang[$error], MessageHelper::WARNING));
	else
	{
		$path = $dir . $Upload->get_filename();
		$error = $Upload->check_img($config->get_max_width(), $config->get_max_height(), Upload::DELETE_ON_ERROR);
		if (!empty($error)) // Error, then stop here
			$view->put('MESSAGE_HELPER', MessageHelper::display($lang[$error], MessageHelper::WARNING));
		else
		{
			// Saving the picture in database.
			$Gallery->Resize_pics($path);
			if ($Gallery->get_error() != '')
				$view->put('MESSAGE_HELPER', MessageHelper::display($lang[$Gallery->get_error()], MessageHelper::WARNING));

			$name = TextHelper::strprotect($request->get_postvalue('name', ''));
			foreach ($Upload->get_files_parameters() as $parameters)
			{
				$idpic = $Gallery->Add_pics($id_category_post, $name, $parameters['path'], AppContext::get_current_user()->get_id());
				if ($Gallery->get_error() != '')
					$view->put('MESSAGE_HELPER', MessageHelper::display($lang[$Gallery->get_error()], MessageHelper::WARNING));
			}

			// Regenerate cache of mini module
			GalleryMiniMenuCache::invalidate();
			GalleryCategoriesCache::invalidate();
		}
	}

	if (empty($error) && !$Gallery->get_error())
		$view->put('MESSAGE_HELPER', MessageHelper::display(LangLoader::get_message('warning.process.success', 'warning-lang'), MessageHelper::SUCCESS, 4));
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

	$view->put('MESSAGE_HELPER', MessageHelper::display(LangLoader::get_message('warning.process.success', 'warning-lang'), MessageHelper::SUCCESS, 4));
}

// Display of the uploaded picture
if (!empty($add_pic))
{
	$categories = CategoriesService::get_categories_manager('gallery')->get_categories_cache()->get_categories();

	try {
		$imageup = PersistenceContext::get_querier()->select_single_row(GallerySetup::$gallery_table, array('id_category', 'name', 'path'), 'WHERE id = :id', array('id' => $add_pic));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_element();
		DispatchManager::redirect($error_controller);
	}

	$view->assign_block_vars('image_up', array(
		'NAME'          => stripslashes($imageup['name']),
		'PATH'          => $imageup['path'],
		'CATEGORY_NAME' => $categories[$imageup['id_category']]->get_name(),

		'U_ITEM'     => 'admin_gallery.php?cat=' . $imageup['id_category'] . '&amp;id=' . $add_pic . '#pics_max',
		'U_CATEGORY' => 'admin_gallery.php?cat=' . $imageup['id_category'],
	));
}

$view->put_all(array(
	'MAX_WIDTH'          => $config->get_max_width(),
	'MAX_HEIGHT'         => $config->get_max_height(),
	'MAX_FILE_SIZE'      => $config->get_max_weight() * 1024,
	'MAX_FILE_SIZE_TEXT' => ($config->get_max_weight() / 1024) . ' ' . LangLoader::get_message('common.unit.megabytes', 'common-lang'),
	'ALLOWED_EXTENSIONS' => implode('", "',FileUploadConfig::load()->get_authorized_picture_extensions()),
));

//Affichage photos
$dir = 'pics/';
if (is_dir($dir)) //Si le dossier existe
{
	$array_pics = array();
	$image_folder_path = new Folder('./pics/');
	foreach ($image_folder_path->get_files('`.*\.(png|webp|jpg|bmp|gif|jpeg|tiff)$`iu') as $image)
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
		$categories_tree = CategoriesService::get_categories_manager('gallery')->get_select_categories_form_field('category', '', $id_category, $search_category_children_options);
		$method = new ReflectionMethod('AbstractFormFieldChoice', 'get_options');
		$method->setAccessible(true);
		$categories_tree_options = $method->invoke($categories_tree);
		$categories_list = '';
		foreach ($categories_tree_options as $option)
		{
			$categories_list .= $option->display()->render();
		}

		$root_categories_tree = CategoriesService::get_categories_manager('gallery')->get_select_categories_form_field('root_cat', '', $id_category, $search_category_children_options);
		$root_categories_tree_options = $method->invoke($root_categories_tree);
		$root_categories_list = '';
		foreach ($root_categories_tree_options as $option)
		{
			$root_categories_list .= $option->display()->render();
		}

		$view->put_all(array(
			'ITEMS_NUMBER'         => $nbr_pics,
			'CATEGORIES_LIST'      => $categories_list,
			'ROOT_CATEGORIES_LIST' => $root_categories_list,
		));

		$j = 0;
		foreach ($array_pics as  $key => $pics)
		{
			$height = 150;
			$width = 150;
			if (function_exists('getimagesize')) //On verifie l'existence de la fonction getimagesize.
			{
				// On recupère la hauteur et la largeur de l'image.
				list($width_source, $height_source) = @getimagesize($dir . $pics);

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

			$categories_tree = CategoriesService::get_categories_manager('gallery')->get_select_categories_form_field($j . 'cat', '', Category::ROOT_CATEGORY, $search_category_children_options);
			$method = new ReflectionMethod('AbstractFormFieldChoice', 'get_options');
			$method->setAccessible(true);
			$categories_tree_options = $method->invoke($categories_tree);
			$categories_list = '';
			foreach ($categories_tree_options as $option)
			{
				$categories_list .= $option->display()->render();
			}

			$view->assign_block_vars('list', array(
				'ID' => $j,
				'NAME' => $pics,
				'UNIQ_NAME' => $pics,
				'CATEGORIES_LIST' => $categories_list,
			));
		}
	}
}

$view->put('C_ITEMS', $j);

$view->display();

require_once('../admin/admin_footer.php');

?>
