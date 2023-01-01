<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 01 15
 * @since       PHPBoost 1.2 - 2005 08 17
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

require_once('../admin/admin_begin.php');

$lang = LangLoader::get_all_langs('gallery');

define('TITLE', $lang['gallery.management']);
require_once('../admin/admin_header.php');

$request = AppContext::get_request();

$id_category = $request->get_getint('cat', 0);
$idpics = $request->get_getint('id', 0);
$del = $request->get_getint('del', 0);
$move = $request->get_getint('move', 0);

$Gallery = new Gallery();
$config = GalleryConfig::load();

$view = new FileTemplate('gallery/admin_gallery_management.tpl');
$view->add_lang($lang);

if (!empty($idpics) && $move) //Déplacement d'une image.
{
	AppContext::get_session()->csrf_get_protect(); //Protection csrf

	$Gallery->Move_pics($idpics, $move);

	//Régénération du cache des photos aléatoires.
	GalleryMiniMenuCache::invalidate();
	GalleryCategoriesCache::invalidate();

	$view->put('MESSAGE_HELPER', MessageHelper::display(LangLoader::get_message('warning.process.success', 'warning-lang'), MessageHelper::SUCCESS, 4));
}
elseif (!empty($del)) //Suppression d'une image.
{
	AppContext::get_session()->csrf_get_protect(); //Protection csrf

	$Gallery->Del_pics($del);

	//Régénération du cache des photos aléatoires.
	GalleryMiniMenuCache::invalidate();
	GalleryCategoriesCache::invalidate();

	$view->put('MESSAGE_HELPER', MessageHelper::display(LangLoader::get_message('warning.process.success', 'warning-lang'), MessageHelper::SUCCESS, 4));
}

if (!empty($id_category))
{
	try {
		$category = CategoriesService::get_categories_manager('gallery')->get_categories_cache()->get_category($id_category);
	} catch (CategoryNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}
}
else
{
	$category = CategoriesService::get_categories_manager('gallery')->get_categories_cache()->get_category(Category::ROOT_CATEGORY);
}

$subcategories = CategoriesService::get_categories_manager('gallery')->get_categories_cache()->get_children($category->get_id(), CategoriesService::get_authorized_categories($category->get_id()));
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

$view->put_all(array(
	'C_ITEMS'                    => $nbr_pics > 0,
	'C_IS_CATEGORY'              => !empty($id_category),
	'C_DISPLAY_NO_ITEMS_MESSAGE' => $category->get_id() != Category::ROOT_CATEGORY,
	'C_SUBCATEGORIES_PAGINATION' => $pagination->has_several_pages(),

	'CATEGORY_NAME'            => $category->get_name(),
	'SUBCATEGORIES_PAGINATION' => $pagination->display(),
	'COLUMNS_NUMBER'           => $config->get_columns_number(),
	'COLSPAN'                  => min($nbr_column_cats, $config->get_columns_number()),
	'CATEGORY_ID'              => $id_category,
	'ARRAY_JS'                 => '',
	'JS_ITEMS_NUMBER'          => 0,
	'MAX_START'                => 0,
	'START_THUMB'              => 0,
	'ITEMS_NUMBER'             => $nbr_pics,

	'U_PARENT_CATEGORY' => $category->get_id_parent(),
));

##### Catégorie disponibles #####
$nbr_cat_displayed = 0;
if ($total_cat > 0)
{
	$view->assign_block_vars('cat', array(
	));

	$i = 0;
	foreach ($subcategories as $id => $cat)
	{
		$nbr_cat_displayed++;

		if ($nbr_cat_displayed > $pagination->get_display_from() && $nbr_cat_displayed <= ($pagination->get_display_from() + $pagination->get_number_items_per_page()))
		{
			//On genère le tableau pour $config->get_columns_number() colonnes
			$multiple_x = $i / $nbr_column_cats;
			$display_tr_start = is_int($multiple_x);
			$i++;
			$multiple_x = $i / $nbr_column_cats;
			$display_tr_end = is_int($multiple_x);

			$category_thumbnail = $cat->get_thumbnail()->rel();
			$elements_number = $cat->get_elements_number();

			$view->assign_block_vars('cat.list', array(
				'C_THUMBNAILS'  => !empty($category_thumbnail),
				'CATEGORY_ID'   => $cat->get_id(),
				'CATEGORY_NAME' => $cat->get_name(),

				'ITEMS_NUMBER'        => $elements_number['pics_aprob'],
				'HIDDEN_ITEMS_NUMBER' => $elements_number['pics_unaprob'],

				'U_THUMBNAIL' => $category_thumbnail,
			));
		}
	}

	//Création des cellules du tableau si besoin est.
	while (!is_int($i/$nbr_column_cats))
	{
		$i++;
		$view->assign_block_vars('cat.end_td', array(
			'COLUMN_WIDTH_PICS' => $column_width_pics,
			'C_DISPLAY_TR_END' => (is_int($i/$nbr_column_cats))
		));
	}
}

if ($nbr_pics > 0 && empty($idpics))
{
	$nbr_pics_category = PersistenceContext::get_querier()->count(GallerySetup::$gallery_table, 'WHERE id_category = :id_category', array('id_category' => $id_category));
}

##### Affichage des photos #####
$view->assign_block_vars('pics', array(
	'C_PICS_MAX' => $nbr_pics == 0 || !empty($idpics),
	'C_EDIT'     => !empty($id_category),

	'COLSPAN'       => isset($nbr_pics_category) ? min($nbr_pics_category, $config->get_columns_number()) : 1,
	'ID'            => $idpics,
	'ID_CATEGORY'   => $id_category,
	'CATEGORY_NAME' => $category->get_name(),

	'U_EDIT_CATEGORY' => CategoriesUrlBuilder::edit($id_category, 'gallery')->rel()
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

	$view->put_all(array(
		'C_PAGINATION' => $pagination->has_several_pages(),
		'PAGINATION'   => $pagination->display(),
	));

	if (!empty($idpics))
	{
		$info_pics = array();
		try {
			$info_pics = PersistenceContext::get_querier()->select_single_row_query("SELECT
				g.id, g.id_category, g.name, g.user_id, g.views, g.width, g.height, g.weight, g.timestamp, g.aprob,
				m.display_name, m.level, m.user_groups
			FROM " . GallerySetup::$gallery_table . " g
			LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = g.user_id
			WHERE g.id_category = :id_category AND g.id = :id", array(
				'id_category' => $id_category,
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
			$result = PersistenceContext::get_querier()->select("SELECT g.id, g.name, g.id_category, g.path
			FROM " . GallerySetup::$gallery_table . " g
			LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = g.user_id
			WHERE g.id_category = :id_category", array(
				'id_category' => $id_category
			));
			while ($row = $result->fetch())
			{
				//Si la miniature n'existe pas (cache vidé) on regénère la miniature à partir de l'image en taille réelle.
				if (!file_exists('pics/thumbnails/' . $row['path']))
					$Gallery->Resize_pics('pics/' . $row['path']); //Redimensionnement + création miniature

				//Affichage de la liste des miniatures sous l'image.
				$array_pics[] = array(
					'C_CURRENT_ITEM' => $row['id'] == $idpics,

					'HEIGHT' => ($config->get_mini_max_height() + 16),
					'ID'     => $i,
					'URL'    => 'admin_gallery.php?cat=' . $row['id_category'] . '&amp;id=' . $row['id'] . '#pics_max',
					'NAME'   => stripslashes($row['name']),
					'PATH'   => $row['path']
				);

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
				$array_js .= 'array_pics[' . $i . '][\'link\'] = \'.php?cat=' . $row['id_category'] . '&amp;id=' . $row['id'] . '#pics_max' . "';\n";
				$array_js .= 'array_pics[' . $i . '][\'path\'] = \'' . $row['path'] . "';\n";
				$i++;
			}
			$result->dispose();

			if ($thumbnails_before < $nbr_pics_display_before)
				$end_thumbnails += $nbr_pics_display_before - $thumbnails_before;
			if ($thumbnails_after < $nbr_pics_display_after)
				$start_thumbnails += $nbr_pics_display_after - $thumbnails_after;

			$view->put_all(array(
				'ARRAY_JS'        => $array_js,
				'JS_ITEMS_NUMBER' => ($i - 1),
				'MAX_START'       => ($i - 1) - $nbr_column_pics,
				'START_THUMB'     => (($pos_pics - $start_thumbnails) > 0) ? ($pos_pics - $start_thumbnails) : 0,
			));

			//Liste des catégories.
			$search_category_children_options = new SearchCategoryChildrensOptions();
			$search_category_children_options->add_authorizations_bits(Category::READ_AUTHORIZATIONS);
			$search_category_children_options->add_authorizations_bits(Category::WRITE_AUTHORIZATIONS);
			$categories_tree = CategoriesService::get_categories_manager('gallery')->get_select_categories_form_field($info_pics['id'] . 'cat', '', $info_pics['id_category'], $search_category_children_options);
			$method = new ReflectionMethod('AbstractFormFieldChoice', 'get_options');
			$method->setAccessible(true);
			$categories_tree_options = $method->invoke($categories_tree);
			$cat_list = '';
			foreach ($categories_tree_options as $option)
			{
				$cat_list .= $option->display()->render();
			}

			$group_color = User::get_group_color($info_pics['user_groups'], $info_pics['level']);

			$date = new Date($info_pics['timestamp'], Timezone::SERVER_TIMEZONE);

			//Affichage de l'image et de ses informations.
			$view->assign_block_vars('pics.pics_max', array_merge(Date::get_array_tpl_vars($date, 'date'), array(
				'C_APPROVED'         => $info_pics['aprob'],
				'C_PREVIOUS'         => ($pos_pics > 0),
				'C_NEXT'             => ($pos_pics < ($i - 1)),
				'C_LEFT_THUMBNAILS'  => ($pos_pics - $start_thumbnails),
				'C_RIGHT_THUMBNAILS' => (($pos_pics - $start_thumbnails) <= ($i - 1) - $nbr_column_pics),
				'C_AUTHOR_EXISTS' => !empty($info_pics['display_name']),
				'C_AUTHOR_GROUP_COLOR' => !empty($group_color),

				'ID'                  => $info_pics['id'],
				'ID_CATEGORY'         => $id_category,
				'ID_PREVIOUS'         => $id_previous,
				'ID_NEXT'             => $id_next,
				'TOKEN'               => AppContext::get_session()->get_token(),
				'PICTURE_NAME'        => stripslashes($info_pics['name']),
				'AUTHOR_DISPLAY_NAME' => $info_pics['display_name'],
				'AUTHOR_LEVEL_CLASS'  => UserService::get_level_class($info_pics['level']),
				'AUTHOR_GROUP_COLOR'  => $group_color,
				'VIEWS_NUMBER'        => ($info_pics['views'] + 1),
				'DIMENSION'           => $info_pics['width'] . ' x ' . $info_pics['height'],
				'SIZE'                => NumberHelper::round($info_pics['weight']/1024, 1),
				'COLSPAN'             => min(($i + 2), ($config->get_columns_number() + 2)),
				'COLSPAN_THUMBNAIL'   => (int)($pos_pics > 0) + (int)($pos_pics < ($i - 1)),
				'CATEGORIES_LIST'     => $cat_list,
				'RENAME'              => addslashes($info_pics['name']),
				'RENAME_CUT'          => addslashes($info_pics['name']),

				'U_AUTHOR_PROFILE' => UserUrlBuilder::profile($info_pics['user_id'])->rel(),
			)));

			//Affichage de la liste des miniatures sous l'image.
			$i = 0;
			foreach ($array_pics as $pics)
			{
				if ($i >= ($pos_pics - $start_thumbnails) && $i <= ($pos_pics + $end_thumbnails))
				{
					$view->assign_block_vars('pics.pics_max.list_preview_pics', $pics);
				}
				$i++;
			}
		}
	}
	else
	{
		$j = 0;
		$result = PersistenceContext::get_querier()->select("SELECT
			g.id, g.id_category, g.name, g.path, g.timestamp, g.aprob, g.width, g.height,
			m.display_name, m.user_id, m.level, m.user_groups
		FROM " . GallerySetup::$gallery_table . " g
		LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = g.user_id
		WHERE g.id_category = :id_category
		ORDER BY g.timestamp
		LIMIT :number_items_per_page OFFSET :display_from", array(
			'id_category' => $id_category,
			'number_items_per_page' => $pagination->get_number_items_per_page(),
			'display_from' => $pagination->get_display_from()
		));
		while ($row = $result->fetch())
		{
			//Si la miniature n'existe pas (cache vidé) on regénère la miniature à partir de l'image en taille réelle.
			if (!file_exists('pics/thumbnails/' . $row['path']))
				$Gallery->Resize_pics('pics/' . $row['path']); //Redimensionnement + création miniature

			$name_cut = (TextHelper::strlen(TextHelper::html_entity_decode($row['name'])) > 22) ? TextHelper::htmlspecialchars(TextHelper::substr(TextHelper::html_entity_decode($row['name']), 0, 22)) . '...' : $row['name'];

			//On reccourci le nom s'il est trop long pour éviter de déformer l'administration.
			$name = !empty($row['name']) ? TextHelper::html_entity_decode($row['name']) : $row['path'];
			$name = TextHelper::strlen($name) > 20 ? TextHelper::substr($name, 0, 20) . '...' : $name;

			//On genère le tableau pour x colonnes
			$display_tr_start = is_int($j / $nbr_column_pics);
			$j++;
			$display_tr_end = is_int($j / $nbr_column_pics);

			//Affichage de l'image en grand.
			if ($config->get_pics_enlargement_mode() == GalleryConfig::FULL_SCREEN) //Ouverture en popup plein écran.
				$display_link = HOST . DIR . '/gallery/show_pics' . url('.php?id=' . $row['id'] . '&amp;cat=' . $row['id_category']);
			elseif ($config->get_pics_enlargement_mode() == GalleryConfig::POPUP) //Ouverture en popup simple.
				$display_link = 'javascript:display_pics_popup(\'' . HOST . DIR . '/gallery/show_pics' . url('.php?id=' . $row['id'] . '&amp;cat=' . $row['id_category']) . '\', \'' . $row['width'] . '\', \'' . $row['height'] . '\')';
			elseif ($config->get_pics_enlargement_mode() == GalleryConfig::RESIZE) //Ouverture en agrandissement simple.
				$display_link = 'javascript:display_pics(' . $row['id'] . ', \'' . HOST . DIR . '/gallery/show_pics' . url('.php?id=' . $row['id'] . '&amp;cat=' . $row['id_category']) . '\', 0)';
			else //Ouverture nouvelle page.
				$display_link = 'admin_gallery.php?cat=' . $row['id_category'] . '&amp;id=' . $row['id'] . '#pics_max';

			//Liste des catégories.
			$search_category_children_options = new SearchCategoryChildrensOptions();
			$search_category_children_options->add_authorizations_bits(Category::READ_AUTHORIZATIONS);
			$search_category_children_options->add_authorizations_bits(Category::WRITE_AUTHORIZATIONS);
			$categories_tree = CategoriesService::get_categories_manager('gallery')->get_select_categories_form_field($row['id'] . 'cat', '', $row['id_category'], $search_category_children_options);
			$method = new ReflectionMethod('AbstractFormFieldChoice', 'get_options');
			$method->setAccessible(true);
			$categories_tree_options = $method->invoke($categories_tree);
			$cat_list = '';
			foreach ($categories_tree_options as $option)
			{
				$cat_list .= $option->display()->render();
			}

			$group_color = User::get_group_color($row['user_groups'], $row['level']);

			$view->assign_block_vars('pics.list', array(
				'C_APPROVED'           => $row['aprob'],
				'C_AUTHOR_EXISTS'      => !empty($row['display_name']),
				'C_AUTHOR_GROUP_COLOR' => !empty($group_color),

				'ID'                  => $row['id'],
				'ALT_NAME'            => $name,
				'PATH'                => $row['path'],
				'NAME'                => stripslashes($name_cut),
				'TITLE'               => stripslashes($row['name']),
				'PROTECTED_TITLE'     => addslashes($row['name']),
				'PROTECTED_NAME'      => addslashes($name_cut),
				'CATEGORIES_LIST'     => $cat_list,
				'AUTHOR_DISPLAY_NAME' => $row['display_name'],
				'AUTHOR_LEVEL_CLASS'  => UserService::get_level_class($row['level']),
				'AUTHOR_GROUP_COLOR'  => $group_color,

				'U_ITEM'           => $display_link,
				'U_AUTHOR_PROFILE' => UserUrlBuilder::profile($row['user_id'])->rel(),
			));
		}
		$result->dispose();
	}
}

$view->display();

require_once('../admin/admin_footer.php');

?>
