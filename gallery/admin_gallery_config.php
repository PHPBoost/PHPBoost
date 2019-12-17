<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 05
 * @since       PHPBoost 1.2 - 2005 08 17
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

require_once('../admin/admin_begin.php');
load_module_lang('gallery'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');
$config = GalleryConfig::load();

$request = AppContext::get_request();

$valid = $request->get_postvalue('valid', false);
$gallery_cache = $request->get_postvalue('gallery_cache', false);

$tpl = new FileTemplate('gallery/admin_gallery_config.tpl');

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
	CategoriesService::get_categories_manager('gallery', 'idcat')->regenerate_cache();

	###### Régénération du cache de la gallery #######
	GalleryMiniMenuCache::invalidate();

	$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 4));
}
elseif ($gallery_cache) //Suppression des miniatures.
{
	//Recréaction miniatures, et inscrustation du logo sur image.
	$Gallery = new Gallery();
	$Gallery->Clear_cache();

	GalleryMiniMenuCache::invalidate();

	$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('process.success', 'status-messages-common'), MessageHelper::SUCCESS, 4));
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
	GalleryConfig::STATIC_SCROLL => $LANG['static_scroll'],
	GalleryConfig::VERTICAL_DYNAMIC_SCROLL => $LANG['vertical_dynamic_scroll'],
	GalleryConfig::HORIZONTAL_DYNAMIC_SCROLL => $LANG['horizontal_dynamic_scroll'],
	GalleryConfig::NO_SCROLL => $LANG['no_scroll']
);

foreach ($array_scroll as $key => $name)
{
	$selected = ($config->get_scroll_type() == $key) ? ' selected="selected"' : '';
	$scroll_types .= '<option value="' . $key . '"' . $selected . '>' . $name . '</option>';
}

$tpl->put_all(array(
	'C_LOGO_ENABLED' => $config->is_logo_enabled(),
	'C_TITLE_ENABLED' => $config->is_title_enabled(),
	'C_NOTES_NUMBER_DISPLAYED' => $config->are_notes_number_displayed(),
	'C_VIEWS_COUNTER_ENABLED' => $config->is_views_counter_enabled(),
	'C_AUTHOR_DISPLAYED' => $config->is_author_displayed(),
	'C_DISPLAY_PICS_NEW_PAGE' => $config->get_pics_enlargement_mode() == GalleryConfig::NEW_PAGE,
	'C_DISPLAY_PICS_RESIZE' => $config->get_pics_enlargement_mode() == GalleryConfig::RESIZE,
	'C_DISPLAY_PICS_POPUP' => $config->get_pics_enlargement_mode() == GalleryConfig::POPUP,
	'C_DISPLAY_PICS_FULL_SCREEN' => $config->get_pics_enlargement_mode() == GalleryConfig::FULL_SCREEN,
	'MINI_MAX_WIDTH' => $config->get_mini_max_width(),
	'MINI_MAX_HEIGHT' => $config->get_mini_max_height(),
	'MAX_WIDTH' => $config->get_max_width(),
	'MAX_HEIGHT' => $config->get_max_height(),
	'MAX_WEIGHT' => $config->get_max_weight(),
	'QUALITY' => $config->get_quality(),
	'LOGO' => $config->get_logo(),
	'LOGO_TRANSPARENCY' => $config->get_logo_transparency(),
	'LOGO_HORIZONTAL_DISTANCE' => $config->get_logo_horizontal_distance(),
	'LOGO_VERTICAL_DISTANCE' => $config->get_logo_vertical_distance(),
	'CATEGORIES_NUMBER_PER_PAGE' => $config->get_categories_number_per_page(),
	'COLUMNS_NUMBER' => $config->get_columns_number(),
	'PICS_NUMBER_PER_PAGE' => $config->get_pics_number_per_page(),
	'MEMBER_MAX_PICS_NUMBER' => $config->get_member_max_pics_number(),
	'MODERATOR_MAX_PICS_NUMBER' => $config->get_moderator_max_pics_number(),
	'PICS_NUMBER_IN_MINI' => $config->get_pics_number_in_mini(),
	'MINI_PICS_SPEED' => $mini_pics_speed,
	'SCROLL_TYPES' => $scroll_types,
	'NEW_PAGE' => GalleryConfig::NEW_PAGE,
	'RESIZE' => GalleryConfig::RESIZE,
	'POPUP' => GalleryConfig::POPUP,
	'FULL_SCREEN' => GalleryConfig::FULL_SCREEN,
	'AUTH_READ' => Authorizations::generate_select(Category::READ_AUTHORIZATIONS, $config->get_authorizations()),
	'AUTH_WRITE' => Authorizations::generate_select(Category::WRITE_AUTHORIZATIONS, $config->get_authorizations()),
	'AUTH_MODERATION' => Authorizations::generate_select(Category::MODERATION_AUTHORIZATIONS, $config->get_authorizations()),
	'AUTH_MANAGE_CATEGORIES' => Authorizations::generate_select(Category::CATEGORIES_MANAGEMENT_AUTHORIZATIONS, $config->get_authorizations()),
	'L_AUTH_READ' => $LANG['auth_read'],
	'L_AUTH_WRITE' => $LANG['auth_upload'],
	'L_AUTH_MODERATION' => $LANG['auth_edit'],
	'L_UNAUTH' => $LANG['unauthorized'],
	'L_UNLIMITED' => $LANG['illimited'],
	'L_REQUIRE_MINI_MAX_HEIGHT' => $LANG['require_height'],
	'L_REQUIRE_MINI_MAX_WIDTH' => $LANG['require_width'],
	'L_REQUIRE_MAX_HEIGHT' => $LANG['require_height_max'],
	'L_REQUIRE_MAX_WIDTH' => $LANG['require_width_max'],
	'L_REQUIRE_MAX_WEIGHT' => $LANG['require_weight_max'],
	'L_REQUIRE_CAT_P' => $LANG['require_cat_p'],
	'L_REQUIRE_ROW' => $LANG['require_row'],
	'L_REQUIRE_IMG_P' => $LANG['require_img_p'],
	'L_REQUIRE_QUALITY' => $LANG['require_quality'],
	'L_GALLERY_MANAGEMENT' => LangLoader::get_message('gallery.management', 'common', 'gallery'),
	'L_GALLERY_PICS_ADD' => LangLoader::get_message('gallery.actions.add', 'common', 'gallery'),
	'L_GALLERY_CONFIG' => $LANG['gallery_config'],
	'L_CONFIG_CONFIG' => LangLoader::get_message('general-config', 'admin-config-common'),
	'L_REQUIRE' => LangLoader::get_message('form.explain_required_fields', 'status-messages-common'),
	'L_MINI_MAX_HEIGHT' => $LANG['height_max_thumb'],
	'L_MINI_MAX_HEIGHT_EXPLAIN' => $LANG['height_max_thumb_explain'],
	'L_MINI_MAX_WIDTH' => $LANG['width_max_thumb'],
	'L_MINI_MAX_WIDTH_EXPLAIN' => $LANG['width_max_thumb_explain'],
	'L_MAX_HEIGHT' => $LANG['height_max'],
	'L_MAX_HEIGHT_EXPLAIN' => $LANG['height_max_explain'],
	'L_MAX_WIDTH' => $LANG['width_max'],
	'L_MAX_WIDTH_EXPLAIN' => $LANG['width_max_explain'],
	'L_MAX_WEIGHT' => $LANG['weight_max'],
	'L_MAX_WEIGHT_EXPLAIN' => $LANG['weight_max_explain'],
	'L_QUALITY_THUMB' => $LANG['quality_thumb'],
	'L_QUALITY_THUMB_EXPLAIN' => $LANG['quality_thumb_explain'],
	'L_COLUMNS_NUMBER' => $LANG['nbr_column'],
	'L_COLUMNS_NUMBER_EXPLAIN' => $LANG['nbr_column_explain'],
	'L_PICS_NUMBER_PER_PAGE' => $LANG['nbr_pics_max'],
	'L_YES' => LangLoader::get_message('yes', 'common'),
	'L_NO' => LangLoader::get_message('no', 'common'),
	'L_DISPLAY_OPTION' => $LANG['display_option'],
	'L_DISPLAY_MODE' => $LANG['display_mode'],
	'L_NEW_PAGE' => $LANG['new_page'],
	'L_RESIZE' => $LANG['resize'],
	'L_POPUP' => $LANG['popup'],
	'L_POPUP_FULL' => $LANG['popup_full'],
	'L_TITLE_ENABLED' => $LANG['title_enabled'],
	'L_TITLE_ENABLED_EXPLAIN' => $LANG['title_enabled_explain'],
	'L_AUTHOR_DISPLAYED' => $LANG['img_poster'],
	'L_AUTHOR_DISPLAYED_EXPLAIN' => $LANG['img_poster_explain'],
	'L_ENABLED' => LangLoader::get_message('enabled', 'common'),
	'L_DISABLED' => LangLoader::get_message('disabled', 'common'),
	'L_VIEWS_COUNTER_ENABLED' => $LANG['compt_views'],
	'L_VIEWS_COUNTER_ENABLED_EXPLAIN' => $LANG['compt_views_explain'],
	'L_NOTES_NUMBER_DISPLAYED' => $LANG['display_nbrnote'],
	'L_IMG_PROTECT' => $LANG['img_protect'],
	'L_LOGO_ENABLED' => $LANG['activ_logo'],
	'L_LOGO_ENABLED_EXPLAIN' => $LANG['activ_logo_explain'],
	'L_LOGO_URL' => $LANG['logo_url'],
	'L_LOGO_URL_EXPLAIN' => $LANG['logo_url_explain'],
	'L_LOGO_TRANSPARENCY' => $LANG['logo_trans'],
	'L_LOGO_TRANSPARENCY_EXPLAIN' => $LANG['logo_trans_explain'],
	'L_WIDTH_BOTTOM_RIGHT' => $LANG['width_bottom_right'],
	'L_WIDTH_BOTTOM_RIGHT_EXPLAIN' => $LANG['width_bottom_right_explain'],
	'L_HEIGHT_BOTTOM_RIGHT' => $LANG['height_bottom_right'],
	'L_HEIGHT_BOTTOM_RIGHT_EXPLAIN' => $LANG['height_bottom_right_explain'],
	'L_UPLOAD_PICS' => $LANG['upload_pic'],
	'L_MEMBER_MAX_PICS_NUMBER' => $LANG['nbr_img'],
	'L_MEMBER_MAX_PICS_NUMBER_EXPLAIN' => $LANG['nbr_img_explain'],
	'L_MODERATOR_MAX_PICS_NUMBER' => $LANG['nbr_img_modo'],
	'L_MODERATOR_MAX_PICS_NUMBER_EXPLAIN' => $LANG['nbr_img_modo_explain'],
	'L_THUMBNAILS_SCROLLING' => $LANG['thumbnails_scolling'],
	'L_PICS_NUMBER_IN_MINI' => $LANG['nbr_pics_mini'],
	'L_MINI_PICS_SPEED' => $LANG['speed_mini_pics'],
	'L_MINI_PICS_SPEED_EXPLAIN' => $LANG['speed_mini_pics_explain'],
	'L_SCROLL_TYPE' => $LANG['scrool_type'],
	'L_CACHE' => LangLoader::get_message('cache_configuration', 'admin-cache-common'),
	'L_EXPLAIN_GALLERY_CACHE' => $LANG['explain_gallery_cache'],
	'L_UNIT_PX' => LangLoader::get_message('unit.pixels', 'common'),
	'L_UNIT_KO' => LangLoader::get_message('unit.kilobytes', 'common'),
	'L_COLUMN' => $LANG['column'],
	'L_EMPTY' => $LANG['empty'],
	'L_UPDATE' => $LANG['update'],
	'L_SUBMIT' => $LANG['submit'],
	'L_RESET' => $LANG['reset']
));

$tpl->display();

require_once('../admin/admin_footer.php');

?>
