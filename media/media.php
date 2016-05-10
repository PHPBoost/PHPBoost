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
if (empty($id_media))
{
	bread_crumb($id_cat);
	
	$category = MediaService::get_categories_manager()->get_categories_cache()->get_category($id_cat);
	define('TITLE', $category->get_name());

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
	$config = MediaConfig::load();
	
	try {
		$media = PersistenceContext::get_querier()->select_single_row_query("SELECT v.*, mb.display_name, mb.groups, mb.level, notes.average_notes, notes.number_notes, note.note
		FROM " . PREFIX . "media AS v
		LEFT JOIN " . DB_TABLE_MEMBER . " AS mb ON v.iduser = mb.user_id
		LEFT JOIN " . DB_TABLE_AVERAGE_NOTES . " notes ON notes.id_in_module = v.id AND notes.module_name = 'media'
		LEFT JOIN " . DB_TABLE_NOTE . " note ON note.id_in_module = v.id AND note.module_name = 'media' AND note.user_id = :user_id
		WHERE v.id = :id", array(
			'user_id' => AppContext::get_current_user()->get_id(),
			'id' => $id_media
		));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_page();
   		DispatchManager::redirect($error_controller);
	}
	
	if (($media['infos'] & MEDIA_STATUS_UNVISIBLE) !== 0)
	{
		$controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), 
		$LANG['e_unexist_media']);
		DispatchManager::redirect($controller);
	}
	elseif (!MediaAuthorizationsService::check_authorizations($media['idcat'])->read())
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
	}

	bread_crumb($media['idcat']);
	$Bread_crumb->add($media['name'], url('media.php?id=' . $id_media, 'media-' . $id_media . '-' . $media['idcat'] . '+' . Url::encode_rewrite($media['name']) . '.php'));

	define('TITLE', $media['name']);
	require_once('../kernel/header.php');

	//MAJ du compteur.
	PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "media SET counter = counter + 1 WHERE id = :id", array('id' => $id_media));

	$notation = new Notation();
	$notation->set_module_name('media');
	$notation->set_notation_scale($config->get_notation_scale());
	$notation->set_id_in_module($id_media);
	$notation->set_number_notes($media['number_notes']);
	$notation->set_average_notes($media['average_notes']);
	$notation->set_user_already_noted(!empty($media['note']));
	$nbr_notes = $media['number_notes'];
	
	$group_color = User::get_group_color($media['groups'], $media['level']);
	
	$tpl->put_all(array(
		'ID' => $id_media,
		'C_DISPLAY_MEDIA' => true,
		'C_ROOT_CATEGORY' => $media['idcat'] == Category::ROOT_CATEGORY,
		'C_MODO' => MediaAuthorizationsService::check_authorizations($media['idcat'])->moderation(),
		'C_DISPLAY_NOTATION' => $config->is_notation_enabled(),
		'C_DISPLAY_COMMENTS' => $config->are_comments_enabled(),
		'ID_MEDIA' => $id_media,
		'NAME' => $media['name'],
		'CONTENTS' => FormatingHelper::second_parse(stripslashes($media['contents'])),
		'COUNT' => $media['counter'],
		'KERNEL_NOTATION' => NotationService::display_active_image($notation),
		'HITS' => ((int)$media['counter']+1) > 1 ? sprintf($MEDIA_LANG['n_times'], ((int)$media['counter']+1)) : sprintf($MEDIA_LANG['n_time'], ((int)$media['counter']+1)),
		'U_COM' => PATH_TO_ROOT .'/media/media' . url('.php?id=' . $id_media . '&amp;com=0', '-' . $id_media . '-' . $media['idcat'] . '+' . Url::encode_rewrite($media['name']) . '.php?com=0') .'#comments-list',
		'L_COM' => CommentsService::get_number_and_lang_comments('media', $id_media),
		'L_DATE' => LangLoader::get_message('date', 'date-common'),
		'L_SIZE' => $LANG['size'],
		'L_MEDIA_INFOS' => $MEDIA_LANG['media_infos'],
		'DATE' => Date::to_format($media['timestamp'], Date::FORMAT_DAY_MONTH_YEAR_HOUR_MINUTE),
		'L_MODO_PANEL' => $LANG['modo_panel'],
		'L_UNAPROBED' => $MEDIA_LANG['unaprobed_media_short'],
		'HEIGHT_P' => $media['height'] + 50,
		'L_VIEWED' => $LANG['view'],
		'L_BY' => $LANG['by'],
		'BY' => !empty($media['display_name']) ? '<a href="' . UserUrlBuilder::profile($media['iduser'])->rel() . '" class="'.UserService::get_level_class($media['level']).'"' . (!empty($group_color) ? ' style="color:' . $group_color . '"' : '') . '>' . $media['display_name'] . '</a>' : $LANG['guest'],
		'U_UNVISIBLE_MEDIA' => url('media_action.php?unvisible=' . $id_media . '&amp;token=' . AppContext::get_session()->get_token()),
		'U_EDIT_MEDIA' => url('media_action.php?edit=' . $id_media),
		'U_DELETE_MEDIA' => url('media_action.php?del=' . $id_media . '&amp;token=' . AppContext::get_session()->get_token()),
		'U_POPUP_MEDIA' => url('media_popup.php?id=' . $id_media),
		'CATEGORY_NAME' => $media['idcat'] == Category::ROOT_CATEGORY ? LangLoader::get_message('module_title', 'common', 'media') : MediaService::get_categories_manager()->get_categories_cache()->get_category($media['idcat'])->get_name(),
		'U_EDIT_CATEGORY' => $media['idcat'] == Category::ROOT_CATEGORY ? MediaUrlBuilder::configuration()->rel() : MediaUrlBuilder::edit_category($media['idcat'])->rel()
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
	if (AppContext::get_request()->get_getint('com', 0) == 0)
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
