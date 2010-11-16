<?php
/*##################################################
 *                               admin_media_config.php
 *                            -------------------
 *   begin               	: October 20, 2008
 *   copyright        	: (C) 2007 Geoffrey ROGUELON
 *   email               	: liaght@gmail.com
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

require_once('../admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');
require_once('media_begin.php');

if (!empty($_POST['valid']))
{
	$Session->csrf_get_protect();

	// Paramètre afichage.
	$display_array = retrieve(POST, 'activ', 0, TARRAY);
	$activ = is_array($display_array) ? array_sum($display_array) : 0;

	// Génère le tableau de configuration.
	$config_media = array (
		'pagin' => max(1, retrieve(POST, 'pagin', $MEDIA_CONFIG['pagin'], TINTEGER)),
		'nbr_column' => max(1, retrieve(POST, 'num_cols', $MEDIA_CONFIG['nbr_column'], TINTEGER)),
		'note_max' => max(1, retrieve(POST, 'note', $MEDIA_CONFIG['note_max'], TINTEGER)),
		'width' => max(1, retrieve(POST, 'width', $MEDIA_CONFIG['width'], TINTEGER)),
		'height' => max(1, retrieve(POST, 'height', $MEDIA_CONFIG['height'], TINTEGER)),
		'root' => array(
			'id_parent' => -1,
			'order' => 1,
			'name' => stripslashes(retrieve(POST, 'media_name', $MEDIA_CATS[0]['name'])),
			'desc' => stripslashes(retrieve(POST, 'contents', $MEDIA_CATS[0]['desc'], TSTRING_PARSE)),
			'visible' => true,
			'image' => 'media.png',
			'num_media' => $MEDIA_CATS[0]['num_media'],
			'mime_type' => retrieve(POST, 'mime_type', $MEDIA_CATS[0]['mime_type'], TINTEGER),
			'active' => $activ,
			'auth' => Authorizations::build_auth_array_from_form(MEDIA_AUTH_READ, MEDIA_AUTH_CONTRIBUTION, MEDIA_AUTH_WRITE),
		)
	);

	// Met à jour les notes des fichiers.
	if ($MEDIA_CONFIG['note_max'] != $config_media['note_max'] && !empty($MEDIA_CONFIG))
	{
		$Sql->Query_inject("UPDATE ".PREFIX."media SET note = note * " . ($config_media['note_max']/$MEDIA_CONFIG['note_max']), __LINE__, __FILE__);
	}

	$Sql->query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes(serialize($config_media)) . "' WHERE name = 'media'", __LINE__, __FILE__);

	$Cache->Generate_module_file('media');

	AppContext::get_response()->redirect(HOST . SCRIPT);
}
else
{
	$Template->Set_filenames(array('admin_media_config'=> 'media/admin_media_config.tpl'));

	$Template->Assign_vars(array(
		'L_CONFIG_GENERAL' => $MEDIA_LANG['config_general'],
		'L_MODULE_NAME' => $MEDIA_LANG['module_name'],
		'L_MODULE_NAME_EXPLAIN' => $MEDIA_LANG['module_name_explain'],
		'MODULE_NAME' => $MEDIA_CATS[0]['name'],
		'L_MODULE_DESC' => $MEDIA_LANG['module_desc'],
		'KERNEL_EDITOR'	=> display_editor(),
		'CONTENTS' => FormatingHelper::unparse($MEDIA_CATS[0]['desc']),
		'L_CONFIG_DISPLAY' => $MEDIA_LANG['config_display'],
		'L_NBR_COLS' => $MEDIA_LANG['nbr_cols'],
		'NBR_COLS' => $MEDIA_CONFIG['nbr_column'],
		'L_PAGINATION' => $MEDIA_LANG['pagination'],
		'PAGINATION' => $MEDIA_CONFIG['pagin'],
		'L_NOTE' => $MEDIA_LANG['note'],
		'NOTE' => $MEDIA_CONFIG['note_max'],
		'L_WIDTH_MAX' => $LANG['width_max'],
		'WIDTH_MAX' => $MEDIA_CONFIG['width'],
		'L_HEIGHT_MAX' => $LANG['height_max'],
		'HEIGHT_MAX' => $MEDIA_CONFIG['height'],
		'L_MIME_TYPE' => $MEDIA_LANG['mime_type'],
		'L_TYPE_BOTH' => $MEDIA_LANG['type_both'],
		'TYPE_BOTH' => $MEDIA_CATS[0]['mime_type'] == MEDIA_TYPE_BOTH ? ' checked="checked"' : '',
		'L_TYPE_MUSIC' => $MEDIA_LANG['type_music'],
		'TYPE_MUSIC' => $MEDIA_CATS[0]['mime_type'] == MEDIA_TYPE_MUSIC ? ' checked="checked"' : '',
		'L_TYPE_VIDEO' => $MEDIA_LANG['type_video'],
		'TYPE_VIDEO' => $MEDIA_CATS[0]['mime_type'] == MEDIA_TYPE_VIDEO ? ' checked="checked"' : '',
		'L_IN_MEDIA' => $MEDIA_LANG['display_in_media'],
		'L_IN_LIST' => $MEDIA_LANG['display_in_list'],
		'L_DISPLAY_COM' => $MEDIA_LANG['display_com'],
		'COM_LIST' => ($MEDIA_CATS[0]['active'] & MEDIA_DL_COM) !== 0 ? 'checked="checked"' : '',
		'COM_MEDIA' => ($MEDIA_CATS[0]['active'] & MEDIA_DV_COM) !== 0 ? 'checked="checked"' : '',
		'L_DISPLAY_NOTE' => $MEDIA_LANG['display_note'],
		'NOTE_LIST' => ($MEDIA_CATS[0]['active'] & MEDIA_DL_NOTE) !== 0 ? 'checked="checked"' : '',
		'NOTE_MEDIA' => ($MEDIA_CATS[0]['active'] & MEDIA_DV_NOTE) !== 0 ? 'checked="checked"' : '',
		'L_DISPLAY_USER' => $MEDIA_LANG['display_poster'],
		'USER_LIST' => ($MEDIA_CATS[0]['active'] & MEDIA_DL_USER) !== 0 ? 'checked="checked"' : '',
		'USER_MEDIA' => ($MEDIA_CATS[0]['active'] & MEDIA_DV_USER) !== 0 ? 'checked="checked"' : '',
		'L_DISPLAY_COUNTER' => $MEDIA_LANG['display_view'],
		'COUNTER_LIST' => ($MEDIA_CATS[0]['active'] & MEDIA_DL_COUNT) !== 0 ? 'checked="checked"' : '',
		'COUNTER_MEDIA' => ($MEDIA_CATS[0]['active'] & MEDIA_DV_COUNT) !== 0 ? 'checked="checked"' : '',
		'L_DISPLAY_DATE' => $MEDIA_LANG['display_date'],
		'DATE_LIST' => ($MEDIA_CATS[0]['active'] & MEDIA_DL_DATE) !== 0 ? 'checked="checked"' : '',
		'DATE_MEDIA' => ($MEDIA_CATS[0]['active'] & MEDIA_DV_DATE) !== 0 ? 'checked="checked"' : '',
		'L_DISPLAY_DESC' => $MEDIA_LANG['display_desc'],
		'DESC_LIST' => ($MEDIA_CATS[0]['active'] & MEDIA_DL_DESC) !== 0 ? 'checked="checked"' : '',
		'DESC_MEDIA' => ($MEDIA_CATS[0]['active'] & MEDIA_DV_DESC) !== 0 ? 'checked="checked"' : '',
		'L_CONFIG_AUTH' => $MEDIA_LANG['config_auth'],
		'L_CONFIG_AUTH_EXPLAIN' => $MEDIA_LANG['config_auth_explain'],
		'L_AUTH_READ' => $MEDIA_LANG['auth_read'],
		'AUTH_READ' => Authorizations::generate_select(MEDIA_AUTH_READ, $MEDIA_CATS[0]['auth']),
		'L_AUTH_CONTRIBUTE' => $MEDIA_LANG['auth_contrib'],
		'AUTH_CONTRIBUTE' => Authorizations::generate_select(MEDIA_AUTH_CONTRIBUTION, $MEDIA_CATS[0]['auth']),
		'L_AUTH_WRITE' => $MEDIA_LANG['auth_write'],
		'AUTH_WRITE' => Authorizations::generate_select(MEDIA_AUTH_WRITE, $MEDIA_CATS[0]['auth']),
		'L_REQUIRE' => $MEDIA_LANG['require'],
		'L_UPDATE' => $LANG['update'],
		'L_PREVIEW' => $LANG['preview'],
		'L_RESET' => $LANG['reset']
	));

	require_once('admin_media_menu.php');
	
	$Template->Pparse('admin_media_config');
}

require_once('../admin/admin_footer.php');

?>