<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Geoffrey ROGUELON <liaght@gmail.com>
 * @version     PHPBoost 6.0 - last update: 2023 10 03
 * @since       PHPBoost 2.0 - 2008 10 20
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

require_once('../kernel/begin.php');

if (!CategoriesAuthorizationsService::check_authorizations()->moderation())
{
	$error_controller = PHPBoostErrors::user_not_authorized();
	DispatchManager::redirect($error_controller);
}

require_once('media_begin.php');
$config = MediaConfig::load();
$items_per_page = $config->get_items_per_page();

$view = new FileTemplate('media/moderation_media.tpl');
$view->add_lang(LangLoader::get_all_langs('media'));

$Bread_crumb->add(LangLoader::get_message('media.module.title', 'common', 'media'), url('media.php'));
$Bread_crumb->add(LangLoader::get_message('user.moderation.panel', 'user-lang'), url('moderation_media.php'));
$request = AppContext::get_request();

$submit = $request->get_postvalue('submit', false);
$filter = $request->get_postvalue('filter', false);

define('TITLE', LangLoader::get_message('user.moderation.panel', 'user-lang'));
require_once('../kernel/header.php');

if ($submit)
{
	AppContext::get_session()->csrf_get_protect();

	$action = $request->get_array('action');
	$show = $hide = $disapproved = $delete = array();

	if (!empty($action))
	{
		foreach ($action as $key => $value)
		{
			if ($value == 'visible')
				$show[] = $key;
			elseif ($value == 'invisible')
				$hide[] = $key;
			elseif ($value == 'disapproved')
				$disapproved[] = $key;
			elseif ($value == 'delete')
				$delete[] = $key;
		}

		if (!empty($show))
		{
			foreach ($show as $key)
			{
				PersistenceContext::get_querier()->update(PREFIX . 'media', array('published' => MEDIA_STATUS_APPROVED), 'WHERE id=:id', array('id' => $key));
			}
		}

		if (!empty($hide))
		{
			foreach ($hide as $key)
			{
				PersistenceContext::get_querier()->update(PREFIX . 'media', array('published' => MEDIA_STATUS_INVISIBLE), 'WHERE id=:id', array('id' => $key));
			}
		}

		if (!empty($disapproved))
		{
			foreach ($disapproved as $key)
			{
				PersistenceContext::get_querier()->update(PREFIX . 'media', array('published' => MEDIA_STATUS_DISAPPROVED), 'WHERE id=:id', array('id' => $key));
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
		AppContext::get_response()->redirect(url('moderation_media.php'));
}
else
{
	// Filters
	$js_array = array();
	$authorized_categories = CategoriesService::get_authorized_categories(!empty($id_category) ? $id_category : Category::ROOT_CATEGORY);

	if ($filter)
	{
		$state = retrieve(POST, 'state', 'all', TSTRING);
		$id_category = (int)retrieve(POST, 'id_category', 0, TINTEGER);
		$sub_cats = (bool)retrieve(POST, 'sub_cats', false, TBOOL);

		if ($state == "visible")
			$db_where = MEDIA_STATUS_APPROVED;
		elseif ($state == 'invisible')
			$db_where = MEDIA_STATUS_INVISIBLE;
		elseif ($state == 'disapproved')
			$db_where = MEDIA_STATUS_DISAPPROVED;
		else
			$db_where = null;
	}
	else
	{
		$id_category = 0;
		$db_where = null;
		$sub_cats = true;
	}

	$items_number = PersistenceContext::get_querier()->count(PREFIX . "media", 'WHERE ' . ($sub_cats && !empty($authorized_categories) ? 'id_category IN :authorized_categories' : 'id_category = :id_category') . (is_null($db_where) ? '' : ' AND published = :published'), array('authorized_categories' => $authorized_categories, 'id_category' => (!empty($id_category) ? $id_category : 0), 'published' => $db_where));

	$categories_cache = CategoriesService::get_categories_manager('media')->get_categories_cache();

	// Pagination if items number > items per page.
	$page = AppContext::get_request()->get_getint('p', 1);
	$pagination = new ModulePagination($page, $items_number, $items_per_page);
	$pagination->set_url(new Url('/media/moderation_media.php?p=%d'));

	if ($pagination->current_page_is_empty() && $page > 1)
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	$result = PersistenceContext::get_querier()->select("SELECT media.*, media_cats.name AS name
		FROM " . MediaSetup::$media_table . " media
		LEFT JOIN " . MediaSetup::$media_cats_table . " media_cats ON media_cats.id = media.id_category
		WHERE " . ($sub_cats && !empty($authorized_categories) ? 'id_category IN :authorized_categories' : 'id_category = :id_category') . (is_null($db_where) ? '' : ' AND published = :published') . "
		ORDER BY :published ASC, creation_date DESC
		LIMIT :items_per_page OFFSET :display_from", array(
			'authorized_categories' => $authorized_categories,
			'id_category' => (!empty($id_category) ? $id_category : 0),
			'published' => $db_where,
			'items_per_page' => $pagination->get_number_items_per_page(),
			'display_from' => $pagination->get_display_from()
	));

	while ($row = $result->fetch())
	{
		$js_array[] = $row['id'];

		$view->assign_block_vars('items', array(
			'CATEGORY_NAME' => $categories_cache->category_exists($row['id_category']) ? $row['name'] : LangLoader::get_message('common.unknown', 'common-lang'),

			'ID'          => $row['id'],
			'TITLE'       => $row['title'],
			'COLOR'       => $row['published'] == MEDIA_STATUS_INVISIBLE ? 'bgc warning' : ($row['published'] == MEDIA_STATUS_APPROVED ? 'bgc success' : 'bgc error'),
			'VISIBLE'     => $row['published'] == MEDIA_STATUS_APPROVED ? ' checked="checked"' : '',
			'INVISIBLE'   => $row['published'] == MEDIA_STATUS_INVISIBLE ? ' checked="checked"' : '',
			'DISAPPROVED' => $row['published'] == MEDIA_STATUS_DISAPPROVED ? ' checked="checked"' : '',

			'U_CATEGORY' => url('media.php?cat=' . $row['id_category']),
			'U_ITEM' 	 => url('media.php?id=' . $row['id'], 'media-' . $row['id'] . '-' . $row['id_category'] . '-' . Url::encode_rewrite($row['title']) . '.php'),
			'U_EDIT' 	 => url('media_action.php?edit=' . $row['id']),
		));
	}
	$result->dispose();

	$view->put_all(array(
		'C_DISPLAY'    => true,
		'C_PAGINATION' => $pagination->has_several_pages(),
		'C_NO_ITEM'    => $items_number > 0 ? 0 : 1,

		'SELECTED_ALL'         => is_null($db_where) ? ' selected ="selected"' : '',
		'SELECTED_VISIBLE'     => $db_where === MEDIA_STATUS_APPROVED ? ' selected="selected"' : '',
		'SELECTED_INVISIBLE'   => $db_where === MEDIA_STATUS_INVISIBLE ? ' selected="selected"'   : '',
		'SELECTED_DISAPPROVED' => $db_where === MEDIA_STATUS_DISAPPROVED ? ' selected="selected"' : '',
		'SUB_CATS'             => is_null($sub_cats) ? ' checked  ="checked"' : ($sub_cats ? ' checked="checked"' : ''),
		'PAGINATION'           => $pagination->display(),
		'JS_ARRAY'             => '"' . implode('", "', $js_array) . '"'
	));
}

$view->display();

require_once('../kernel/footer.php');

?>
