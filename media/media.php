<?php
/*##################################################
 *                              media.php
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

require_once('../kernel/begin.php');
require_once('media_begin.php');

// Display caterories and media files.
if (empty($id_media) && $id_cat >= 0)
{
	bread_crumb($id_cat);
	
	define('TITLE', $MEDIA_CATS[$id_cat]['name']);

	require_once('../kernel/header.php');
		
	$modulesLoader = AppContext::get_extension_provider_service();
	$module = $modulesLoader->get_provider('media');
	if ($module->has_extension_point(HomePageExtensionPoint::EXTENSION_POINT))
	{
		echo $module->get_extension_point(HomePageExtensionPoint::EXTENSION_POINT)->get_home_page()->get_view()->display();
	}
}
// Display the media file.
elseif ($id_media > 0)
{
	$tpl = new FileTemplate('media/media.tpl');
	
	$result = $Sql->query_while("SELECT v.*, mb.login, mb.user_groups, mb.level, notes.average_notes, notes.number_notes, note.note
	FROM " . PREFIX . "media AS v
	LEFT JOIN " . DB_TABLE_MEMBER . " AS mb ON v.iduser = mb.user_id
	LEFT JOIN " . DB_TABLE_AVERAGE_NOTES . " notes ON notes.id_in_module = v.id AND notes.module_name = 'media'
	LEFT JOIN " . DB_TABLE_NOTE . " note ON note.id_in_module = v.id AND note.module_name = 'media' AND note.user_id = " . AppContext::get_current_user()->get_id() . "
	WHERE v.id = '" . $id_media . "'", __LINE__, __FILE__);
	
	$media = $Sql->fetch_assoc($result);
	$Sql->query_close($result);
	
	if (empty($media) || ($media['infos'] & MEDIA_STATUS_UNVISIBLE) !== 0)
	{
		$controller = new UserErrorController(LangLoader::get_message('error', 'errors'), 
            $LANG['e_unexist_media']);
        DispatchManager::redirect($controller);
	}
	elseif (!$User->check_auth($MEDIA_CATS[$media['idcat']]['auth'], MEDIA_AUTH_READ))
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
        DispatchManager::redirect($error_controller);
	}

	bread_crumb($media['idcat']);
	$Bread_crumb->add($media['name'], url('media.php?id=' . $id_media, 'media-' . $id_media . '-' . $media['idcat'] . '+' . Url::encode_rewrite($media['name']) . '.php'));

	define('TITLE', $media['name']);
	require_once('../kernel/header.php');

	//MAJ du compteur.
	$Sql->query_inject("UPDATE " . LOW_PRIORITY . " " . PREFIX . "media SET counter = counter + 1 WHERE id = " . $id_media, __LINE__, __FILE__);

	$notation = new Notation();
	$notation->set_module_name('media');
	$notation->set_notation_scale($MEDIA_CONFIG['note_max']);
	$notation->set_id_in_module($id_media);
	$notation->set_number_notes($media['number_notes']);
	$notation->set_average_notes($media['average_notes']);
	$notation->set_user_already_noted(!empty($media['note']));
	$nbr_notes = $media['number_notes'];
	
	$group_color = User::get_group_color($media['user_groups'], $media['level']);
	
	$tpl->put_all(array(
		'C_DISPLAY_MEDIA' => true,
		'C_MODO' => $User->check_level(User::MODERATOR_LEVEL),
		'ID_MEDIA' => $id_media,
		'NAME' => $media['name'],
		'CONTENTS' => FormatingHelper::second_parse($media['contents']),
		'COUNT' => $media['counter'],
		'KERNEL_NOTATION' => NotationService::display_active_image($notation),
		'HITS' => ((int)$media['counter']+1) > 1 ? sprintf($MEDIA_LANG['n_times'], ((int)$media['counter']+1)) : sprintf($MEDIA_LANG['n_time'], ((int)$media['counter']+1)),
		'NUM_NOTES' => (int)$nbr_notes > 1 ? sprintf($MEDIA_LANG['num_notes'], (int)$nbr_notes) : sprintf($MEDIA_LANG['num_note'], (int)$nbr_notes),
		'U_COM' => '<a href="'. PATH_TO_ROOT .'/media/media' . url('.php?id=' . $id_media . '&amp;com=0', '-' . $id_media . '-' . $media['idcat'] . '+' . Url::encode_rewrite($media['name']) . '.php?com=0') .'#comments_list">'. CommentsService::get_number_and_lang_comments('media', $id_media) . '</a>',
		'L_DATE' => $LANG['date'],
		'L_SIZE' => $LANG['size'],
		'L_MEDIA_INFOS' => $MEDIA_LANG['media_infos'],
		'DATE' => gmdate_format('date_format', $media['timestamp']),
		'L_MODO_PANEL' => $LANG['modo_panel'],
		'L_EDIT' => $LANG['edit'],
		'L_DELETE' => $LANG['delete'],
		'L_UNAPROBED' => $MEDIA_LANG['unaprobed_media_short'],
		'HEIGHT_P' => $media['height'] + 50,
		'L_VIEWED' => $LANG['view'],
		'L_BY' => $LANG['by'],
		'BY' => !empty($media['login']) ? '<a href="' . UserUrlBuilder::profile($media['iduser'])->absolute() . '" class="'.UserService::get_level_class($media['level']).'"' . (!empty($group_color) ? ' style="color:' . $group_color . '"' : '') . '>' . $media['login'] . '</a>' : $LANG['guest'],
		'L_CONFIRM_DELETE_MEDIA' => str_replace('\'', '\\\'', $MEDIA_LANG['confirm_delete_media']),
		'U_UNVISIBLE_MEDIA' => url('media_action.php?unvisible=' . $id_media . '&amp;token=' . $Session->get_token()),
		'U_EDIT_MEDIA' => url('media_action.php?edit=' . $id_media),
		'U_DELETE_MEDIA' => url('media_action.php?del=' . $id_media . '&amp;token=' . $Session->get_token()),
		'U_POPUP_MEDIA' => url('media_popup.php?id=' . $id_media),
		'C_DISPLAY' => (($MEDIA_CATS[$media['idcat']]['active'] & MEDIA_DV_DATE) !== 0 || ($MEDIA_CATS[$media['idcat']]['active'] & MEDIA_DV_USER) !== 0 || ($MEDIA_CATS[$media['idcat']]['active'] & MEDIA_DV_COUNT) !== 0 || ($MEDIA_CATS[$media['idcat']]['active'] & MEDIA_DV_NOTE) !== 0),
		'A_COM' => ($MEDIA_CATS[$media['idcat']]['active'] & MEDIA_DV_COM) !== 0,
		'A_NOTE' => ($MEDIA_CATS[$media['idcat']]['active'] & MEDIA_DV_NOTE) !== 0,
		'A_USER' => ($MEDIA_CATS[$media['idcat']]['active'] & MEDIA_DV_USER) !== 0,
		'A_COUNTER' => ($MEDIA_CATS[$media['idcat']]['active'] & MEDIA_DV_COUNT) !== 0,
		'A_DATE' => ($MEDIA_CATS[$media['idcat']]['active'] & MEDIA_DV_DATE) !== 0,
		'A_DESC' => ($MEDIA_CATS[$media['idcat']]['active'] & MEDIA_DV_DESC) !== 0,
		'L_CONFIRM_DELETE_FILE' => str_replace('\'', '\\\'', $MEDIA_LANG['confirm_delete_media'])
	));
	
	if (empty($mime_type_tpl[$media['mime_type']]))
	{
		$media_tpl = new FileTemplate('media/format/media_other.tpl');
	}
	else
	{
		$media_tpl = new FileTemplate('media/' . $mime_type_tpl[$media['mime_type']]);
	}
	
	$media_tpl->put_all(array(
		'URL' => $media['url'],
		'MIME' => $media['mime_type'],
		'WIDTH' => $media['width'],
		'HEIGHT' => $media['height']
	));
		
	$tpl->put('media_format', $media_tpl);
	
	//Affichage commentaires.
	if (isset($_GET['com']))
	{
		$comments_topic = new MediaCommentsTopic();
		$comments_topic->set_id_in_module($id_media);
		$comments_topic->set_url(new Url('/media/media.php?id='. $id_media . '&com=0'));
		$tpl->put_all(array(
			'COMMENTS' => CommentsService::display($comments_topic)->render()
		));
	}
	$tpl->display();
}

require_once('../kernel/footer.php');

?>