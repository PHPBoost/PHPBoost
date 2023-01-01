<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 15
 * @since       PHPBoost 1.2 - 2005 08 17
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

require_once('../admin/admin_begin.php');

$lang = LangLoader::get_all_langs('gallery');

define('TITLE', $lang['gallery.config.module.title']);
require_once('../admin/admin_header.php');
$config = GalleryConfig::load();

$request = AppContext::get_request();

$valid = $request->get_postvalue('valid', false);
$gallery_cache = $request->get_postvalue('gallery_cache', false);

$view = new FileTemplate('gallery/admin_gallery_config.tpl');
$view->add_lang($lang);

//Si c'est confirmé on execute
if ($valid)
{
	$config->set_mini_max_width(retrieve(POST, 'mini_max_width', 150));
	$config->set_mini_max_height(retrieve(POST, 'mini_max_height', 150));
	$config->set_max_width(retrieve(POST, 'max_width', 800));
	$config->set_max_height(retrieve(POST, 'max_height', 600));
	$config->set_max_weight(retrieve(POST, 'max_weight', 1024));
	$config->set_quality(retrieve(POST, 'quality', 80));
	if (retrieve(POST, 'logo_enabled', ''))
		$config->enable_logo();
	else
		$config->disable_logo();
	$config->set_logo(TextHelper::strprotect(retrieve(POST, 'logo', ''), TextHelper::HTML_PROTECT, TextHelper::ADDSLASHES_NONE));
	$config->set_logo_transparency(retrieve(POST, 'logo_transparency', 40));
	$config->set_logo_horizontal_distance(retrieve(POST, 'logo_horizontal_distance', 5));
	$config->set_logo_vertical_distance(retrieve(POST, 'logo_vertical_distance', 5));
	$config->set_categories_number_per_page(retrieve(POST, 'categories_number_per_page', 10));
	$config->set_columns_number(retrieve(POST, 'columns_number', 4));
	$config->set_pics_number_per_page(retrieve(POST, 'pics_number_per_page', 16));

	if (retrieve(POST, 'title_enabled', ''))
		$config->enable_title();
	else
		$config->disable_title();
	if (retrieve(POST, 'notes_number_displayed', ''))
		$config->display_notes_number();
	else
		$config->hide_notes_number();
	if (retrieve(POST, 'views_counter_enabled', ''))
		$config->enable_views_counter();
	else
		$config->disable_views_counter();
	if (retrieve(POST, 'author_displayed', ''))
		$config->enable_author_display();
	else
		$config->disable_author_display();
	$config->set_member_max_pics_number(retrieve(POST, 'member_max_pics_number', 0));
	$config->set_moderator_max_pics_number(retrieve(POST, 'moderator_max_pics_number', 0));
	$config->set_pics_enlargement_mode(retrieve(POST, 'pics_enlargement_mode', GalleryConfig::FULL_SCREEN));
	$config->set_scroll_type(retrieve(POST, 'scroll_type', GalleryConfig::VERTICAL_DYNAMIC_SCROLL));
	$config->set_pics_number_in_mini(retrieve(POST, 'pics_number_in_mini', 8));
	$config->set_mini_pics_speed(retrieve(POST, 'mini_pics_speed', 6));
	$config->set_authorizations(Authorizations::build_auth_array_from_form(Category::READ_AUTHORIZATIONS, Category::WRITE_AUTHORIZATIONS, Category::MODERATION_AUTHORIZATIONS, Category::CATEGORIES_MANAGEMENT_AUTHORIZATIONS));

	GalleryConfig::save();
	CategoriesService::get_categories_manager('gallery')->regenerate_cache();

	###### Régénération du cache de la gallery #######
	GalleryMiniMenuCache::invalidate();

	HooksService::execute_hook_action('edit_config', 'gallery', array('title' => StringVars::replace_vars($lang['form.module.title'], array('module_name' => ModulesManager::get_module('gallery')->get_configuration()->get_name())), 'url' => GalleryUrlBuilder::configuration()->rel()));

	$view->put('MESSAGE_HELPER', MessageHelper::display(LangLoader::get_message('warning.success.config', 'warning-lang'), MessageHelper::SUCCESS, 4));
}
elseif ($gallery_cache) //Suppression des miniatures.
{
	//Recréaction miniatures, et inscrustation du logo sur image.
	$Gallery = new Gallery();
	$Gallery->Clear_cache();

	GalleryMiniMenuCache::invalidate();

	$view->put('MESSAGE_HELPER', MessageHelper::display(LangLoader::get_message('warning.process.success', 'warning-lang'), MessageHelper::SUCCESS, 4));
}

//Vitesse de défilement des miniatures.
$mini_pics_speed = '';
for ($i = 1; $i <= 10; $i++)
{
	$selected = ($config->get_mini_pics_speed() == $i) ? ' selected="selected"' : '';
	$mini_pics_speed .= '<option value="' . $i . '"' . $selected . '>' . $i . '</option>';
}

//Type de défilemennt
$scroll_types = '';
$array_scroll = array(
	GalleryConfig::STATIC_SCROLL => $lang['gallery.static.scroll'],
	GalleryConfig::VERTICAL_DYNAMIC_SCROLL => $lang['gallery.vertical.scroll'],
	GalleryConfig::HORIZONTAL_DYNAMIC_SCROLL => $lang['gallery.horizontal.scroll'],
	GalleryConfig::NO_SCROLL => $lang['gallery.no.scroll']
);

foreach ($array_scroll as $key => $name)
{
	$selected = ($config->get_scroll_type() == $key) ? ' selected="selected"' : '';
	$scroll_types .= '<option value="' . $key . '"' . $selected . '>' . $name . '</option>';
}

$view->put_all(array(
	'C_LOGO_ENABLED'           => $config->is_logo_enabled(),
	'C_TITLE_ENABLED'          => $config->is_title_enabled(),
	'C_NOTES_NUMBER_DISPLAYED' => $config->are_notes_number_displayed(),
	'C_VIEWS_COUNTER_ENABLED'  => $config->is_views_counter_enabled(),
	'C_AUTHOR_DISPLAYED'       => $config->is_author_displayed(),
	'C_DISPLAY_NEW_PAGE'       => $config->get_pics_enlargement_mode() == GalleryConfig::NEW_PAGE,
	'C_DISPLAY_RESIZING'       => $config->get_pics_enlargement_mode() == GalleryConfig::RESIZE,
	'C_DISPLAY_POPUP'          => $config->get_pics_enlargement_mode() == GalleryConfig::POPUP,
	'C_DISPLAY_FULL_SCREEN'    => $config->get_pics_enlargement_mode() == GalleryConfig::FULL_SCREEN,

	'THUMBNAIL_MAX_WIDTH'      => $config->get_mini_max_width(),
	'THUMBNAIL_MAX_HEIGHT'     => $config->get_mini_max_height(),
	'MAX_WIDTH'                => $config->get_max_width(),
	'MAX_HEIGHT'               => $config->get_max_height(),
	'MAX_WEIGHT'               => $config->get_max_weight(),
	'QUALITY'                  => $config->get_quality(),
	'LOGO'                     => $config->get_logo(),
	'LOGO_TRANSPARENCY'        => $config->get_logo_transparency(),
	'LOGO_HORIZONTAL_DISTANCE' => $config->get_logo_horizontal_distance(),
	'LOGO_VERTICAL_DISTANCE'   => $config->get_logo_vertical_distance(),
	'CATEGORIES_PER_PAGE'      => $config->get_categories_number_per_page(),
	'COLUMNS_NUMBER'           => $config->get_columns_number(),
	'ITEMS_PER_PAGE'           => $config->get_pics_number_per_page(),
	'MEMBER_ITEMS_NUMBER'      => $config->get_member_max_pics_number(),
	'MODERATOR_ITEMS_NUMBER'   => $config->get_moderator_max_pics_number(),
	'THUMBNAILS_NUMBER'        => $config->get_pics_number_in_mini(),
	'SCROLLING_SPEED'          => $mini_pics_speed,
	'SCROLLING_TYPES'          => $scroll_types,
	'NEW_PAGE'                 => GalleryConfig::NEW_PAGE,
	'RESIZE'                   => GalleryConfig::RESIZE,
	'POPUP'                    => GalleryConfig::POPUP,
	'FULL_SCREEN'              => GalleryConfig::FULL_SCREEN,
	'AUTH_READ'                => Authorizations::generate_select(Category::READ_AUTHORIZATIONS, $config->get_authorizations()),
	'AUTH_WRITE'               => Authorizations::generate_select(Category::WRITE_AUTHORIZATIONS, $config->get_authorizations()),
	'AUTH_MODERATION'          => Authorizations::generate_select(Category::MODERATION_AUTHORIZATIONS, $config->get_authorizations()),
	'AUTH_MANAGE_CATEGORIES'   => Authorizations::generate_select(Category::CATEGORIES_MANAGEMENT_AUTHORIZATIONS, $config->get_authorizations()),
));

$view->display();

require_once('../admin/admin_footer.php');

?>
