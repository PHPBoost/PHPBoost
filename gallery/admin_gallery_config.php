<?php
/*##################################################
 *                               admin_gallery_config.php
 *                            -------------------
 *   begin                : August,17 2005
 *   copyright            : (C) 2005 Viarre R�gis
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
$gallery_config = GalleryConfig::load();

//Si c'est confirm� on execute
if (!empty($_POST['valid']))
{
	$config_note_max = $gallery_config->get_note_max();
	$note_max = isset($_POST['note_max']) ? max(1, NumberHelper::numeric($_POST['note_max'])) : '5';
	
	if ($config_note_max != $note_max)
		$Sql->query_inject("UPDATE " . PREFIX . "gallery SET note = note * " . ($note_max/$config_note_max), __LINE__, __FILE__);
		
	$gallery_config->set_width(isset($_POST['width']) ? NumberHelper::numeric($_POST['width']) : '150');
	$gallery_config->set_height(isset($_POST['height']) ? NumberHelper::numeric($_POST['height']) : '150');
	$gallery_config->set_width_max(isset($_POST['width_max']) ? NumberHelper::numeric($_POST['width_max']) : '800');
	$gallery_config->set_height_max(isset($_POST['height_max']) ? NumberHelper::numeric($_POST['height_max']) : '600');
	$gallery_config->set_weight_max(isset($_POST['weight_max']) ? NumberHelper::numeric($_POST['weight_max']) : '1024');
	$gallery_config->set_quality(isset($_POST['quality']) ? NumberHelper::numeric($_POST['quality']) : '80');
	$gallery_config->set_trans(isset($_POST['trans']) ? NumberHelper::numeric($_POST['trans']) : '40');
	$gallery_config->set_logo(TextHelper::strprotect(retrieve(POST, 'logo', ''), TextHelper::HTML_PROTECT, TextHelper::ADDSLASHES_NONE));
	$gallery_config->set_activ_logo(isset($_POST['activ_logo']) ? NumberHelper::numeric($_POST['activ_logo']) : '0');
	$gallery_config->set_d_width(isset($_POST['d_width']) ? NumberHelper::numeric($_POST['d_width']) : '5');
	$gallery_config->set_d_height(isset($_POST['d_height']) ? NumberHelper::numeric($_POST['d_height']) : '5');
	$gallery_config->set_nbr_columns(isset($_POST['nbr_column']) ? NumberHelper::numeric($_POST['nbr_column']) : '4');
	$gallery_config->set_nbr_pics_max(isset($_POST['nbr_pics_max']) ? NumberHelper::numeric($_POST['nbr_pics_max']) : '16');
	$gallery_config->set_note_max($note_max);
	$gallery_config->set_activ_title(isset($_POST['activ_title']) ? NumberHelper::numeric($_POST['activ_title']) : '0');
	$gallery_config->set_activ_com(isset($_POST['activ_com']) ? NumberHelper::numeric($_POST['activ_com']) : '0');
	$gallery_config->set_activ_note(isset($_POST['activ_note']) ? NumberHelper::numeric($_POST['activ_note']) : '0');
	$gallery_config->set_display_nbr_note(isset($_POST['display_nbrnote']) ? NumberHelper::numeric($_POST['display_nbrnote']) : '0');
	$gallery_config->set_activ_view(isset($_POST['activ_view']) ? NumberHelper::numeric($_POST['activ_view']) : '0');
	$gallery_config->set_activ_user(isset($_POST['activ_user']) ? NumberHelper::numeric($_POST['activ_user']) : '0');
	$gallery_config->set_limit_member(!empty($_POST['limit_member']) ? NumberHelper::numeric($_POST['limit_member']) : '0');
	$gallery_config->set_limit_modo(!empty($_POST['limit_modo']) ? NumberHelper::numeric($_POST['limit_modo']) : '0');
	$gallery_config->set_display_pics(!empty($_POST['display_pics']) ? NumberHelper::numeric($_POST['display_pics']) : '0');
	$gallery_config->set_scroll_type(!empty($_POST['scroll_type']) ? NumberHelper::numeric($_POST['scroll_type']) : 0);
	$gallery_config->set_nbr_pics_mini(!empty($_POST['nbr_pics_mini']) ? NumberHelper::numeric($_POST['nbr_pics_mini']) : 8);
	$gallery_config->set_speed_mini_pics(retrieve(POST, 'speed_mini_pics', 6));
	$gallery_config->set_authorization(Authorizations::build_auth_array_from_form(READ_CAT_GALLERY, WRITE_CAT_GALLERY, EDIT_CAT_GALLERY));
	
	GalleryConfig::save();
	
	###### R�g�n�ration du cache #######
	$Cache->Generate_module_file('gallery');

	AppContext::get_response()->redirect(HOST . REWRITED_SCRIPT);
}
elseif (!empty($_POST['gallery_cache'])) //Suppression des miniatures.
{
	$Gallery = new Gallery();

	$Cache->load('gallery');

	$Gallery->Clear_cache(); //Recr�action miniatures, et inscrustation du logo sur image.
	$Gallery->Count_cat_pics(); //Recompte le nombre d'images de chaque cat�gories

	$Cache->Generate_module_file('gallery');

	AppContext::get_response()->redirect('/gallery/admin_gallery_config.php');
}
else
{
	$Template->set_filenames(array(
		'admin_gallery_config'=> 'gallery/admin_gallery_config.tpl'
	));

	$Cache->load('gallery');
	
	$config_activ_title = $gallery_config->get_activ_title();
	$config_activ_view = $gallery_config->get_activ_view();
	$config_activ_note = $gallery_config->get_activ_note();
	$config_display_nbr_note = $gallery_config->get_display_nbr_note();
	$config_activ_com = $gallery_config->get_activ_com();
	$config_activ_user = $gallery_config->get_activ_user();
	$config_activ_logo = $gallery_config->get_activ_logo();
	$config_display_pics = $gallery_config->get_display_pics();
	$config_speed_mini_pics = $gallery_config->get_speed_mini_pics();
	$config_scroll_type = $gallery_config->get_scroll_type();

	//Vitesse de d�filement des miniatures.
	$speed_mini_pics = '';
	for ($i = 1; $i <= 10; $i++)
	{
		$selected = ($config_speed_mini_pics == $i) ? ' selected="selected"' : '';
		$speed_mini_pics .= '<option value="' . $i . '"' . $selected . '>' . $i . '</option>';
	}

	//Type de d�filemennt
	$scroll_types = '';
	$array_scroll = array($LANG['static_scroll'], $LANG['vertical_dynamic_scroll'], $LANG['horizontal_dynamic_scroll'], $LANG['no_scroll']);
	foreach ($array_scroll as $key => $name)
	{
		$selected = ($config_scroll_type == $key) ? ' selected="selected"' : '';
		$scroll_types .= '<option value="' . $key . '"' . $selected . '>' . $name . '</option>';
	}

	$Template->put_all(array(
		'WIDTH' => $gallery_config->get_width(),
		'HEIGHT' => $gallery_config->get_height(),
		'WIDTH_MAX' => $gallery_config->get_width_max(),
		'HEIGHT_MAX' => $gallery_config->get_height_max(),
		'WEIGHT_MAX' => $gallery_config->get_weight_max(),
		'QUALITY' => $gallery_config->get_quality(),
		'TRANS' => $gallery_config->get_trans(),
		'LOGO' => $gallery_config->get_logo(),
		'D_WIDTH' => $gallery_config->get_d_width(),
		'D_HEIGHT' => $gallery_config->get_d_height(),
		'NBR_COLUMN' => $gallery_config->get_nbr_columns(),
		'NBR_PICS_MAX' => $gallery_config->get_nbr_pics_max(),
		'NOTE_MAX' => $gallery_config->get_note_max(),
		'LIMIT_USER' => $gallery_config->get_limit_member(),
		'LIMIT_MODO' => $gallery_config->get_limit_modo(),
		'TITLE_ENABLED' => ($config_activ_title == 1) ? 'checked="checked"' : '',
		'TITLE_DISABLED' => ($config_activ_title == 0) ? 'checked="checked"' : '',
		'VIEW_ENABLED' => ($config_activ_view == 1) ? 'checked="checked"' : '',
		'VIEW_DISABLED' => ($config_activ_view == 0) ? 'checked="checked"' : '',
		'NOTE_ENABLED' => ($config_activ_note == 1) ? 'checked="checked"' : '',
		'NOTE_DISABLED' => ($config_activ_note == 0) ? 'checked="checked"' : '',
		'NBRNOTE_ENABLED' => ($config_display_nbr_note == 1) ? 'checked="checked"' : '',
		'NBRNOTE_DISABLED' => ($config_display_nbr_note == 0) ? 'checked="checked"' : '',
		'COM_ENABLED' => ($config_activ_com == 1) ? 'checked="checked"' : '',
		'COM_DISABLED' => ($config_activ_com == 0) ? 'checked="checked"' : '',
		'USER_ENABLED' => ($config_activ_user == 1) ? 'checked="checked"' : '',
		'USER_DISABLED' => ($config_activ_user == 0) ? 'checked="checked"' : '',
		'LOGO_ENABLED' => ($config_activ_logo == 1) ? 'checked="checked"' : '',
		'LOGO_DISABLED' => ($config_activ_logo == 0) ? 'checked="checked"' : '',
		'DISPLAY_PICS_NEW_PAGE' => ($config_display_pics == 0) ? 'checked="checked"' : '',
		'DISPLAY_PICS' => ($config_display_pics == 1) ? 'checked="checked"' : '',
		'DISPLAY_PICS_POPUP' => ($config_display_pics == 2) ? 'checked="checked"' : '',
		'DISPLAY_PICS_POPUP_FULL' => ($config_display_pics == 3) ? 'checked="checked"' : '',
		'NBR_PICS_MINI' => $gallery_config->get_nbr_pics_mini(),
		'SPEED_MINI_PICS' => $speed_mini_pics,
		'SCROLL_TYPES' => $scroll_types,
		'L_UNAUTH' => $LANG['unauthorized'],
		'L_UNLIMITED' => $LANG['illimited'],
		'L_REQUIRE_HEIGHT' => $LANG['require_height'],
		'L_REQUIRE_HEIGHT_MAX' => $LANG['require_height_max'],
		'L_REQUIRE_WIDTH' => $LANG['require_width'],
		'L_REQUIRE_WIDTH_MAX' => $LANG['require_width_max'],
		'L_REQUIRE_WEIGHT_MAX' => $LANG['require_weight_max'],
		'L_REQUIRE_ROW' => $LANG['require_row'],
		'L_REQUIRE_IMG_P' => $LANG['require_img_p'],
		'L_REQUIRE_QUALITY' => $LANG['require_quality'],
		'L_GALLERY_MANAGEMENT' => $LANG['gallery_management'],
		'L_GALLERY_PICS_ADD' => $LANG['gallery_pics_add'],
		'L_GALLERY_CAT_MANAGEMENT' => $LANG['gallery_cats_management'],
		'L_GALLERY_CAT_ADD' => $LANG['gallery_cats_add'],
		'L_GALLERY_CONFIG' => $LANG['gallery_config'],
		'L_CONFIG_CONFIG' => $LANG['config_main'],
		'L_REQUIRE' => $LANG['require'],
		'L_HEIGHT_MAX' => $LANG['height_max'],
		'L_HEIGHT_MAX_EXPLAIN' => $LANG['height_max_explain'],
		'L_WIDTH_MAX' => $LANG['width_max'],
		'L_WIDTH_MAX_EXPLAIN' => $LANG['width_max_explain'],
		'L_HEIGHT_MAX_THUMB' => $LANG['height_max_thumb'],
		'L_HEIGHT_MAX_THUMB_EXPLAIN' => $LANG['height_max_thumb_explain'],
		'L_WIDTH_MAX_THUMB' => $LANG['width_max_thumb'],
		'L_WIDTH_MAX_THUMB_EXPLAIN' => $LANG['width_max_thumb_explain'],
		'L_WEIGHT_MAX' => $LANG['weight_max'],
		'L_WEIGHT_MAX_EXPLAIN' => $LANG['weight_max_explain'],
		'L_QUALITY_THUMB' => $LANG['quality_thumb'],
		'L_QUALITY_THUMB_EXPLAIN' => $LANG['quality_thumb_explain'],
		'L_NBR_COLUMN' => $LANG['nbr_column'],
		'L_NBR_COLUMN_EXPLAIN' => $LANG['nbr_column_explain'],
		'L_NBR_PICS_MAX' => $LANG['nbr_pics_max'],
		'L_YES' => $LANG['yes'],
		'L_NO' => $LANG['no'],
		'L_DISPLAY_OPTION' => $LANG['display_option'],
		'L_DISPLAY_MODE' => $LANG['display_mode'],
		'L_NEW_PAGE' => $LANG['new_page'],
		'L_RESIZE' => $LANG['resize'],
		'L_POPUP' => $LANG['popup'],
		'L_POPUP_FULL' => $LANG['popup_full'],
		'L_TITLE_ENABLED' => $LANG['title_enabled'],
		'L_TITLE_ENABLED_EXPLAIN' => $LANG['title_enabled_explain'],
		'L_IMG_POSTER' => $LANG['img_poster'],
		'L_IMG_POSTER_EXPLAIN' => $LANG['img_poster_explain'],
		'L_ACTIV' => $LANG['activ'],
		'L_UNACTIV' => $LANG['unactiv'],
		'L_COMPT_VIEWS' => $LANG['compt_views'],
		'L_COMPT_VIEWS_EXPLAIN' => $LANG['compt_views_explain'],
		'L_ACTIV_COM' => $LANG['activ_com'],
		'L_ACTIV_NOTE' => $LANG['activ_note'],
		'L_DISPLAY_NBRNOTE' => $LANG['display_nbrnote'],
		'L_NOTE_MAX' => $LANG['note_max'],
		'L_NOTE_MAX_EXPLAIN' => $LANG['note_max_explain'],
		'L_IMG_PROTECT' => $LANG['img_protect'],
		'L_ACTIV_LOGO' => $LANG['activ_logo'],
		'L_ACTIV_LOGO_EXPLAIN' => $LANG['activ_logo_explain'],
		'L_LOGO_URL' => $LANG['logo_url'],
		'L_LOGO_URL_EXPLAIN' => $LANG['logo_url_explain'],
		'L_LOGO_TRANS' => $LANG['logo_trans'],
		'L_LOGO_TRANS_EXPLAIN' => $LANG['logo_trans_explain'],
		'L_WIDTH_BOTTOM_RIGHT' => $LANG['width_bottom_right'],
		'L_WIDTH_BOTTOM_RIGHT_EXPLAIN' => $LANG['width_bottom_right_explain'],
		'L_HEIGHT_BOTTOM_RIGHT' => $LANG['height_bottom_right'],
		'L_HEIGHT_BOTTOM_RIGHT_EXPLAIN' => $LANG['height_bottom_right_explain'],
		'L_UPLOAD_PICS' => $LANG['upload_pic'],
		'L_NUMBER_IMG' => $LANG['nbr_img'],
		'L_NUMBER_IMG_EXPLAIN' => $LANG['nbr_img_explain'],
		'L_NUMBER_IMG_MODO' => $LANG['nbr_img_modo'],
		'L_NUMBER_IMG_MODO_EXPLAIN' => $LANG['nbr_img_modo_explain'],
		'L_THUMBNAILS_SCROLLING' => $LANG['thumbnails_scolling'],
		'L_NBR_PICS_MINI' => $LANG['nbr_pics_mini'],
		'L_SPEED_MINI_PICS' => $LANG['speed_mini_pics'],
		'L_SPEED_MINI_PICS_EXPLAIN' => $LANG['speed_mini_pics_explain'],
		'L_SCROLL_TYPE' => $LANG['scrool_type'],
		'L_CACHE' => $LANG['cache'],
		'L_EXPLAIN_GALLERY_CACHE' => $LANG['explain_gallery_cache'],
		'L_UNIT_PX' => $LANG['unit_pixels'],
		'L_UNIT_KO' => $LANG['unit_kilobytes'],
		'L_COLUMN' => $LANG['column'],
		'L_EMPTY' => $LANG['empty'],
		'L_UPDATE' => $LANG['update'],
		'L_SUBMIT' => $LANG['submit'],
		'L_RESET' => $LANG['reset']
	));

	$Template->pparse('admin_gallery_config');
}

require_once('../admin/admin_footer.php');

?>