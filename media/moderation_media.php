<?php
/*##################################################
 *              	 media_action.php
 *              	-------------------
 * begin        	: October 20, 2008
 * copyright    	: (C) 2007 Geoffrey ROGUELON
 * email        	: liaght@gmail.com
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
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 *
 ###################################################*/

require_once('../kernel/begin.php');

if (!AppContext::get_current_user()->check_level(User::MODERATOR_LEVEL))
{
	$error_controller = PHPBoostErrors::user_not_authorized();
	DispatchManager::redirect($error_controller);
}

require_once('media_begin.php');
$media_categories = new MediaCats();

$tpl = new FileTemplate('media/moderation_media.tpl');

$Bread_crumb->add($MEDIA_CATS[0]['name'], url('media.php'));
$Bread_crumb->add($LANG['modo_panel'], url('moderation_media.php'));

define('TITLE', $LANG['modo_panel']);
require_once('../kernel/header.php');

if (!empty($_POST['submit']))
{
	AppContext::get_session()->csrf_get_protect();

	$action = retrieve(POST, 'action', array(), TARRAY);
	$show = $hide = $unaprobed = $delete = array();

	if (!empty($action))
	{
		foreach ($action as $key => $value)
		{
			if ($value == 'visible')
			{
				$show[] = $key;
			}
			elseif ($value == 'unvisible')
			{
				$hide[] = $key;
			}
			elseif ($value == 'unaprobed')
			{
				$unaprobed[] = $key;
			}
			elseif ($value == 'delete')
			{
				$delete[] = $key;
			}
		}

		if (!empty($show))
		{
			foreach ($show as $key)
			{
				PersistenceContext::get_querier()->update(PREFIX . 'media', array('infos' => MEDIA_STATUS_APROBED), 'WHERE id=:id', array('id' => $key));
			}
		}
		
		if (!empty($hide))
		{
			foreach ($hide as $key)
			{
				PersistenceContext::get_querier()->update(PREFIX . 'media', array('infos' => MEDIA_STATUS_UNVISIBLE), 'WHERE id=:id', array('id' => $key));
			}
		}
		
		if (!empty($unaprobed))
		{
			foreach ($unaprobed as $key)
			{
				PersistenceContext::get_querier()->update(PREFIX . 'media', array('infos' => MEDIA_STATUS_UNAPROBED), 'WHERE id=:id', array('id' => $key));
			}
		}

		if (!empty($delete))
		{
			foreach ($delete as $key)
			{
				PersistenceContext::get_querier()->delete(PREFIX . 'media', 'WHERE id=:id', array('id' => $key));
				CommentsService::delete_comments_topic_module('media', $key);
				NotationService::delete_notes_id_in_module('media', $key);
			}
		}

		// Feeds Regeneration
		
		Feed::clear_cache('media');
		$media_categories->recount_media_per_cat();

		AppContext::get_response()->redirect(url('moderation_media.php'));
	}
	else
	{
		AppContext::get_response()->redirect(url('moderation_media.php'));
	}
}
elseif (!empty($_GET['recount']))
{
	// Feeds Regeneration
	
	Feed::clear_cache('media');
	$media_categories->recount_media_per_cat();
	
	AppContext::get_response()->redirect(url('moderation_media.php'));	
}
else
{
	// Filtre pour le panneau de mod�ration.
	$array_cats = $js_array = array();

	if (!empty($_POST['filter']))
	{
		$state = retrieve(POST, 'state', 'all', TSTRING);
		$cat = retrieve(POST, 'idcat', 0, TINTEGER);
		$sub_cats = retrieve(POST, 'sub_cats', false, TBOOL);
		
		if ($state == "visible")
		{
			$db_where = MEDIA_STATUS_APROBED;
		}
		elseif ($state == 'unvisible')
		{
			$db_where = MEDIA_STATUS_UNVISIBLE;
		}
		elseif ($state == 'unaprobed')
		{
			$db_where = MEDIA_STATUS_UNAPROBED;
		}
		else
		{
			$db_where = null;
		}
		
		if ($sub_cats)
		{
			$media_categories->build_children_id_list($cat, $array_cats, RECURSIVE_EXPLORATION, ADD_THIS_CATEGORY_IN_LIST, MEDIA_AUTH_READ);
		}
	}
	else
	{
		$cat = 0;
		$db_where = $sub_cats = null;
		$media_categories->build_children_id_list(0, $array_cats, RECURSIVE_EXPLORATION, ADD_THIS_CATEGORY_IN_LIST, MEDIA_AUTH_READ);
	}
	
	$nbr_media = PersistenceContext::get_querier()->count(PREFIX . "media", 'WHERE ' . (!empty($array_cats) ? 'idcat IN :array_cats' : 'idcat = :idcat') . (is_null($db_where) ? '' : ' AND infos = :infos'), array('array_cats' => $array_cats, 'idcat' => (!empty($cat) ? $cat : 0), 'infos' => $db_where));
	
	//On cr�e une pagination si le nombre de fichier est trop important.
	$page = AppContext::get_request()->get_getint('p', 1);
	$pagination = new ModulePagination($page, $nbr_media, NUM_MODO_MEDIA);
	$pagination->set_url(new Url('/media/moderation_media.php?p=%d'));

	if ($pagination->current_page_is_empty() && $page > 1)
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	$result = PersistenceContext::get_querier()->select("SELECT *
		FROM " . PREFIX . "media
		WHERE " . (!empty($array_cats) ? 'idcat IN :array_cats' : 'idcat = :idcat') . (is_null($db_where) ? '' : ' AND infos = :infos') . "
		ORDER BY infos ASC, timestamp DESC
		LIMIT :number_items_per_page OFFSET :display_from", array(
			'array_cats' => $array_cats,
			'idcat' => (!empty($cat) ? $cat : 0),
			'infos' => $db_where,
			'number_items_per_page' => $pagination->get_number_items_per_page(),
			'display_from' => $pagination->get_display_from()
	));

	while ($row = $result->fetch())
	{
		$js_array[] = $row['id'];

		$tpl->assign_block_vars('files', array(
			'ID' => $row['id'],
			'NAME' => $row['name'],
			'U_FILE' => url('media.php?id=' . $row['id'], 'media-' . $row['id'] . '-' . $row['idcat'] . '+' . Url::encode_rewrite($row['name']) . '.php'),
			'U_EDIT' => url('media_action.php?edit=' . $row['id']),
			'CAT' => !empty($MEDIA_CATS[$row['idcat']]) ? $MEDIA_CATS[$row['idcat']]['name'] : $LANG['unknown'],
			'U_CAT' => url('media.php?cat=' . $row['idcat']),
			'COLOR' => $row['infos'] == MEDIA_STATUS_UNVISIBLE ? '#FFEE99' : ($row['infos'] == MEDIA_STATUS_APROBED ? '#CCFFCC' : '#FFCCCC'),
			'SHOW' => $row['infos'] == MEDIA_STATUS_APROBED ? ' checked="checked"' : '',
			'HIDE' => $row['infos'] == MEDIA_STATUS_UNVISIBLE ? ' checked="checked"' : '',
			'UNAPROBED' => $row['infos'] == MEDIA_STATUS_UNAPROBED ? ' checked="checked"' : '',
		));
	}
	$result->dispose();

	$tpl->put_all(array(
		'C_DISPLAY' => true,
		'C_PAGINATION' => $pagination->has_several_pages(),
		'L_FILTER' => $MEDIA_LANG['filter'],
		'L_DISPLAY_FILE' => $MEDIA_LANG['display_file'],
		'L_ALL' => $MEDIA_LANG['all_file'],
		'SELECTED_ALL' => is_null($db_where) ? ' selected="selected"' : '',
		'L_FVISIBLE' => $MEDIA_LANG['visible'],
		'SELECTED_VISIBLE' => $db_where === MEDIA_STATUS_APROBED ? ' selected="selected"' : '',
		'L_FUNVISIBLE' => $MEDIA_LANG['unvisible'],
		'SELECTED_UNVISIBLE' => $db_where === MEDIA_STATUS_UNVISIBLE ? ' selected="selected"' : '',
		'L_FUNAPROBED' => $MEDIA_LANG['unaprobed'],
		'SELECTED_UNAPROBED' => $db_where === MEDIA_STATUS_UNAPROBED ? ' selected="selected"' : '',
		'L_CATEGORIES' => $MEDIA_LANG['from_cats'],
		'CATEGORIES_TREE' => $media_categories->build_select_form($cat, 'idcat', 'idcat', 0, MEDIA_AUTH_READ, $MEDIA_CATS[$cat]['auth']),
		'L_INCLUDE_SUB_CATS' => $MEDIA_LANG['include_sub_cats'],
		'SUB_CATS' => is_null($sub_cats) ? ' checked="checked"' : ($sub_cats ? ' checked="checked"' : ''),
		'L_MODO_PANEL' => $LANG['modo_panel'],
		'L_NAME' => $LANG['name'],
		'L_CATEGORY' => $LANG['category'],
		'L_VISIBLE' => $MEDIA_LANG['show_media_short'],
		'L_UNVISIBLE' => $MEDIA_LANG['hide_media_short'],
		'L_UNAPROBED' => $MEDIA_LANG['unaprobed_media_short'],
		'L_DELETE' => LangLoader::get_message('delete', 'common'),
		'C_NO_MODERATION' => $nbr_media > 0 ? 0 : 1,
		'L_NO_MODERATION' => $MEDIA_LANG['no_media_moderate'],
		'L_CONFIRM_DELETE_ALL' => str_replace('\'', '\\\'', $MEDIA_LANG['confirm_delete_media_all']),
		'L_LEGEND' => $MEDIA_LANG['legend'],
		'L_FILE_UNAPROBED' => $MEDIA_LANG['file_unaprobed'],
		'L_FILE_UNVISIBLE' => $MEDIA_LANG['file_unvisible'],
		'L_FILE_VISIBLE' => $MEDIA_LANG['file_visible'],
		'PAGINATION' => $pagination->display(),
		'L_SUBMIT' => $LANG['submit'],
		'L_RESET' => $LANG['reset'],
		'C_ADMIN' => AppContext::get_current_user()->check_level(User::ADMIN_LEVEL),
		'L_RECOUNT_MEDIA' => $MEDIA_LANG['recount_per_cat'],
		'JS_ARRAY' => '"' . implode('", "', $js_array) . '"'
	));
}

$tpl->display();

require_once('../kernel/footer.php');

?>