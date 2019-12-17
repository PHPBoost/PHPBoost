<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Geoffrey ROGUELON <liaght@gmail.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 07
 * @since       PHPBoost 2.0 - 2008 10 20
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

require_once('../kernel/begin.php');

if (!CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, 'media', 'idcat')->moderation())
{
	$error_controller = PHPBoostErrors::user_not_authorized();
	DispatchManager::redirect($error_controller);
}

require_once('media_begin.php');
$NUMBER_ELEMENTS_PER_PAGE = 25;

$tpl = new FileTemplate('media/moderation_media.tpl');

$Bread_crumb->add(LangLoader::get_message('module_title', 'common', 'media'), url('media.php'));
$Bread_crumb->add($LANG['modo_panel'], url('moderation_media.php'));
$request = AppContext::get_request();

$submit = $request->get_postvalue('submit', false);
$filter = $request->get_postvalue('filter', false);

define('TITLE', $LANG['modo_panel']);
require_once('../kernel/header.php');

if ($submit)
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

		MediaCategoriesCache::invalidate();

		AppContext::get_response()->redirect(url('moderation_media.php'));
	}
	else
	{
		AppContext::get_response()->redirect(url('moderation_media.php'));
	}
}
else
{
	// Filtre pour le panneau de modération.
	$js_array = array();
	$authorized_categories = CategoriesService::get_authorized_categories(!empty($cat) ? $cat : Category::ROOT_CATEGORY, true, 'media', 'idcat');

	if ($filter)
	{
		$state = retrieve(POST, 'state', 'all', TSTRING);
		$cat = (int)retrieve(POST, 'idcat', 0, TINTEGER);
		$sub_cats = (bool)retrieve(POST, 'sub_cats', false, TBOOL);

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
	}
	else
	{
		$cat = 0;
		$db_where = null;
		$sub_cats = true;
	}

	$nbr_media = PersistenceContext::get_querier()->count(PREFIX . "media", 'WHERE ' . ($sub_cats && !empty($authorized_categories) ? 'idcat IN :authorized_categories' : 'idcat = :idcat') . (is_null($db_where) ? '' : ' AND infos = :infos'), array('authorized_categories' => $authorized_categories, 'idcat' => (!empty($cat) ? $cat : 0), 'infos' => $db_where));

	$categories_cache = CategoriesService::get_categories_manager('media', 'idcat')->get_categories_cache();

	//On crée une pagination si le nombre de fichier est trop important.
	$page = AppContext::get_request()->get_getint('p', 1);
	$pagination = new ModulePagination($page, $nbr_media, $NUMBER_ELEMENTS_PER_PAGE);
	$pagination->set_url(new Url('/media/moderation_media.php?p=%d'));

	if ($pagination->current_page_is_empty() && $page > 1)
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	$result = PersistenceContext::get_querier()->select("SELECT media.*, media_cats.name AS cat_name
		FROM " . MediaSetup::$media_table . " media
		LEFT JOIN " . MediaSetup::$media_cats_table . " media_cats ON media_cats.id = media.idcat
		WHERE " . ($sub_cats && !empty($authorized_categories) ? 'idcat IN :authorized_categories' : 'idcat = :idcat') . (is_null($db_where) ? '' : ' AND infos = :infos') . "
		ORDER BY infos ASC, timestamp DESC
		LIMIT :number_items_per_page OFFSET :display_from", array(
			'authorized_categories' => $authorized_categories,
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
			'CAT' => $categories_cache->category_exists($row['idcat']) ? $row['cat_name'] : $LANG['unknown'],
			'U_CAT' => url('media.php?cat=' . $row['idcat']),
			'COLOR' => $row['infos'] == MEDIA_STATUS_UNVISIBLE ? 'bgc warning' : ($row['infos'] == MEDIA_STATUS_APROBED ? 'bgc success' : 'bgc error'),
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
		'L_INCLUDE_SUB_CATS' => $MEDIA_LANG['include_sub_cats'],
		'SUB_CATS' => is_null($sub_cats) ? ' checked="checked"' : ($sub_cats ? ' checked="checked"' : ''),
		'L_MODO_PANEL' => $LANG['modo_panel'],
		'L_NAME' => $LANG['name'],
		'L_VISIBLE' => $MEDIA_LANG['show_media_short'],
		'L_UNVISIBLE' => $MEDIA_LANG['hide_media_short'],
		'L_UNAPROBED' => $MEDIA_LANG['unaprobed_media_short'],
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
		'JS_ARRAY' => '"' . implode('", "', $js_array) . '"'
	));
}

$tpl->display();

require_once('../kernel/footer.php');

?>
