<?php
/*##################################################
 *                               admin_gallery_config.php
 *                            -------------------
 *   begin                : August,17 2005
 *   copyright            : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
 *
 *
###################################################
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
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

//Si c'est confirmé on execute
if (!empty($_POST['valid']))
{
	$Cache->load('gallery');

	$config_gallery = array();
	$config_gallery['width'] = isset($_POST['width']) ? numeric($_POST['width']) : '150';
	$config_gallery['height'] = isset($_POST['height']) ? numeric($_POST['height']) : '150';
	$config_gallery['width_max'] = isset($_POST['width_max']) ? numeric($_POST['width_max']) : '640';
	$config_gallery['height_max'] = isset($_POST['height_max']) ? numeric($_POST['height_max']) : '640';
	$config_gallery['weight_max'] = isset($_POST['weight_max']) ? numeric($_POST['weight_max']) : '1024';
	$config_gallery['quality'] = isset($_POST['quality']) ? numeric($_POST['quality']) : '80';
	$config_gallery['trans'] = isset($_POST['trans']) ? numeric($_POST['trans']) : '40';
	$config_gallery['logo'] = strprotect(retrieve(POST, 'logo', ''), HTML_PROTECT, ADDSLASHES_NONE);
	$config_gallery['activ_logo'] = isset($_POST['activ_logo']) ? numeric($_POST['activ_logo']) : '0';
	$config_gallery['d_width'] = isset($_POST['d_width']) ? numeric($_POST['d_width']) : '5';
	$config_gallery['d_height'] = isset($_POST['d_height']) ? numeric($_POST['d_height']) : '5';
	$config_gallery['nbr_column'] = isset($_POST['nbr_column']) ? numeric($_POST['nbr_column']) : '4';
	$config_gallery['nbr_pics_max'] = isset($_POST['nbr_pics_max']) ? numeric($_POST['nbr_pics_max']) : '16';
	$config_gallery['note_max'] = isset($_POST['note_max']) ? max(1, numeric($_POST['note_max'])) : '5';
	$config_gallery['activ_title'] = isset($_POST['activ_title']) ? numeric($_POST['activ_title']) : '0';
	$config_gallery['activ_com'] = isset($_POST['activ_com']) ? numeric($_POST['activ_com']) : '0';
	$config_gallery['activ_note'] = isset($_POST['activ_note']) ? numeric($_POST['activ_note']) : '0';
	$config_gallery['display_nbrnote'] = isset($_POST['display_nbrnote']) ? numeric($_POST['display_nbrnote']) : '0';
	$config_gallery['activ_view'] = isset($_POST['activ_view']) ? numeric($_POST['activ_view']) : '0';
	$config_gallery['activ_user'] = isset($_POST['activ_user']) ? numeric($_POST['activ_user']) : '0';
	$config_gallery['limit_member'] = !empty($_POST['limit_member']) ? numeric($_POST['limit_member']) : '0';
	$config_gallery['limit_modo'] = !empty($_POST['limit_modo']) ? numeric($_POST['limit_modo']) : '0';
	$config_gallery['display_pics'] = !empty($_POST['display_pics']) ? numeric($_POST['display_pics']) : '0';
	$config_gallery['scroll_type'] = !empty($_POST['scroll_type']) ? numeric($_POST['scroll_type']) : 0;
	$config_gallery['nbr_pics_mini'] = !empty($_POST['nbr_pics_mini']) ? numeric($_POST['nbr_pics_mini']) : 8;
	$config_gallery['speed_mini_pics'] = retrieve(POST, 'speed_mini_pics', 6);
	$config_gallery['auth_root'] = !empty($CONFIG_GALLERY['auth_root']) ? stripslashes(serialize($CONFIG_GALLERY['auth_root'])) : serialize(array());

	$Sql->query_inject("UPDATE " . DB_TABLE_CONFIGS . " SET value = '" . addslashes(serialize($config_gallery)) . "' WHERE name = 'gallery'", __LINE__, __FILE__);

	if ($CONFIG_GALLERY['note_max'] != $config_gallery['note_max'])
		$Sql->query_inject("UPDATE " . PREFIX . "gallery SET note = note * " . ($config_gallery['note_max']/$CONFIG_GALLERY['note_max']), __LINE__, __FILE__);

	###### Régénération du cache de la gallery #######
	$Cache->Generate_module_file('gallery');

	redirect(HOST . SCRIPT);
}
elseif (!empty($_POST['gallery_cache'])) //Suppression des miniatures.
{
	$Gallery = new Gallery();

	$Cache->load('gallery');

	$Gallery->Clear_cache(); //Recréaction miniatures, et inscrustation du logo sur image.
	$Gallery->Count_cat_pics(); //Recompte le nombre d'images de chaque catégories

	$Cache->Generate_module_file('gallery');

	redirect('/gallery/admin_gallery_config.php');
}
else
{
	$Template->set_filenames(array(
		'admin_gallery_config'=> 'gallery/admin_gallery_config.tpl'
	));

	$Cache->load('gallery');

	$CONFIG_GALLERY['activ_pop'] = !isset($CONFIG_GALLERY['activ_pop']) ? 0 : $CONFIG_GALLERY['activ_pop'];
	$CONFIG_GALLERY['activ_title'] = !isset($CONFIG_GALLERY['activ_title']) ? 1 : $CONFIG_GALLERY['activ_title'];
	$CONFIG_GALLERY['activ_view'] = !isset($CONFIG_GALLERY['activ_view']) ? 1 : $CONFIG_GALLERY['activ_view'];
	$CONFIG_GALLERY['activ_note'] = !isset($CONFIG_GALLERY['activ_note']) ? 1 : $CONFIG_GALLERY['activ_note'];
	$CONFIG_GALLERY['display_nbrnote'] = !isset($CONFIG_GALLERY['display_nbrnote']) ? 1 : $CONFIG_GALLERY['display_nbrnote'];
	$CONFIG_GALLERY['activ_com'] = !isset($CONFIG_GALLERY['activ_com']) ? 1 : $CONFIG_GALLERY['activ_com'];
	$CONFIG_GALLERY['activ_user'] = !isset($CONFIG_GALLERY['activ_user']) ? 1 : $CONFIG_GALLERY['activ_user'];
	$CONFIG_GALLERY['activ_logo'] = !isset($CONFIG_GALLERY['activ_logo']) ? 1 : $CONFIG_GALLERY['activ_logo'];
	$CONFIG_GALLERY['display_pics'] = !isset($CONFIG_GALLERY['display_pics']) ? 0 : $CONFIG_GALLERY['display_pics'];
	$CONFIG_GALLERY['speed_mini_pics'] = !isset($CONFIG_GALLERY['speed_mini_pics']) ? 6 : $CONFIG_GALLERY['speed_mini_pics'];
	$CONFIG_GALLERY['scroll_type'] = !isset($CONFIG_GALLERY['scroll_type']) ? 1 : $CONFIG_GALLERY['scroll_type'];

	//Vitesse de défilement des miniatures.
	$speed_mini_pics = '';
	for ($i = 1; $i <= 10; $i++)
	{
		$selected = ($CONFIG_GALLERY['speed_mini_pics'] == $i) ? ' selected="selected"' : '';
		$speed_mini_pics .= '<option value="' . $i . '"' . $selected . '>' . $i . '</option>';
	}

	//Type de défilemennt
	$scroll_types = '';
	$array_scroll = array($LANG['static_scroll'], $LANG['vertical_dynamic_scroll'], $LANG['horizontal_dynamic_scroll'], $LANG['no_scroll']);
	foreach ($array_scroll as $key => $name)
	{
		$selected = ($CONFIG_GALLERY['scroll_type'] == $key) ? ' selected="selected"' : '';
		$scroll_types .= '<option value="' . $key . '"' . $selected . '>' . $name . '</option>';
	}

	$Template->assign_vars(array(
		'WIDTH' => isset($CONFIG_GALLERY['width']) ? $CONFIG_GALLERY['width'] : '150',
		'HEIGHT' => isset($CONFIG_GALLERY['height']) ? $CONFIG_GALLERY['height'] : '150',
		'WIDTH_MAX' => isset($CONFIG_GALLERY['width_max']) ? $CONFIG_GALLERY['width_max'] : '640',
		'HEIGHT_MAX' => isset($CONFIG_GALLERY['height_max']) ? $CONFIG_GALLERY['height_max'] : '640',
		'WEIGHT_MAX' => isset($CONFIG_GALLERY['weight_max']) ? $CONFIG_GALLERY['weight_max'] : '1024',
		'QUALITY' => isset($CONFIG_GALLERY['quality']) ? $CONFIG_GALLERY['quality'] : '80',
		'TRANS' => isset($CONFIG_GALLERY['trans']) ? $CONFIG_GALLERY['trans'] : '40',
		'LOGO' => isset($CONFIG_GALLERY['logo']) ? $CONFIG_GALLERY['logo'] : 'logo.jpg',
		'D_WIDTH' => isset($CONFIG_GALLERY['d_width']) ? $CONFIG_GALLERY['d_width'] : '5',
		'D_HEIGHT' => isset($CONFIG_GALLERY['d_height']) ? $CONFIG_GALLERY['d_height'] : '5',
		'NBR_COLUMN' => isset($CONFIG_GALLERY['nbr_column']) ? $CONFIG_GALLERY['nbr_column'] : '4',
		'NBR_PICS_MAX' => isset($CONFIG_GALLERY['nbr_pics_max']) ? $CONFIG_GALLERY['nbr_pics_max'] : '16',
		'NOTE_MAX' => isset($CONFIG_GALLERY['note_max']) ? $CONFIG_GALLERY['note_max'] : '5',
		'LIMIT_USER' => isset($CONFIG_GALLERY['limit_member']) ? $CONFIG_GALLERY['limit_member'] : '10',
		'LIMIT_MODO' => isset($CONFIG_GALLERY['limit_modo']) ? $CONFIG_GALLERY['limit_modo'] : '25',
		'TITLE_ENABLED' => ($CONFIG_GALLERY['activ_title'] == 1) ? 'checked="checked"' : '',
		'TITLE_DISABLED' => ($CONFIG_GALLERY['activ_title'] == 0) ? 'checked="checked"' : '',
		'VIEW_ENABLED' => ($CONFIG_GALLERY['activ_view'] == 1) ? 'checked="checked"' : '',
		'VIEW_DISABLED' => ($CONFIG_GALLERY['activ_view'] == 0) ? 'checked="checked"' : '',
		'NOTE_ENABLED' => ($CONFIG_GALLERY['activ_note'] == 1) ? 'checked="checked"' : '',
		'NOTE_DISABLED' => ($CONFIG_GALLERY['activ_note'] == 0) ? 'checked="checked"' : '',
		'NBRNOTE_ENABLED' => ($CONFIG_GALLERY['display_nbrnote'] == 1) ? 'checked="checked"' : '',
		'NBRNOTE_DISABLED' => ($CONFIG_GALLERY['display_nbrnote'] == 0) ? 'checked="checked"' : '',
		'COM_ENABLED' => ($CONFIG_GALLERY['activ_com'] == 1) ? 'checked="checked"' : '',
		'COM_DISABLED' => ($CONFIG_GALLERY['activ_com'] == 0) ? 'checked="checked"' : '',
		'USER_ENABLED' => ($CONFIG_GALLERY['activ_user'] == 1) ? 'checked="checked"' : '',
		'USER_DISABLED' => ($CONFIG_GALLERY['activ_user'] == 0) ? 'checked="checked"' : '',
		'LOGO_ENABLED' => ($CONFIG_GALLERY['activ_logo'] == 1) ? 'checked="checked"' : '',
		'LOGO_DISABLED' => ($CONFIG_GALLERY['activ_logo'] == 0) ? 'checked="checked"' : '',
		'DISPLAY_PICS_NEW_PAGE' => ($CONFIG_GALLERY['display_pics'] == 0) ? 'checked="checked"' : '',
		'DISPLAY_PICS' => ($CONFIG_GALLERY['display_pics'] == 1) ? 'checked="checked"' : '',
		'DISPLAY_PICS_POPUP' => ($CONFIG_GALLERY['display_pics'] == 2) ? 'checked="checked"' : '',
		'DISPLAY_PICS_POPUP_FULL' => ($CONFIG_GALLERY['display_pics'] == 3) ? 'checked="checked"' : '',
		'NBR_PICS_MINI' => isset($CONFIG_GALLERY['nbr_pics_mini']) ? $CONFIG_GALLERY['nbr_pics_mini'] : '8',
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