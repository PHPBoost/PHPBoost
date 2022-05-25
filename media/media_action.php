<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Geoffrey ROGUELON <liaght@gmail.com>
 * @version     PHPBoost 6.0 - last update: 2022 05 25
 * @since       PHPBoost 2.0 - 2008 10 20
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

require_once('../kernel/begin.php');
require_once('media_begin.php');

if (AppContext::get_current_user()->is_readonly())
{
	$controller = PHPBoostErrors::user_in_read_only();
	DispatchManager::redirect($controller);
}

$view = new FileTemplate('media/media_action.tpl');
$lang = LangLoader::get_all_langs('media');
$view->add_lang($lang);

$config = MediaConfig::load();
$request = AppContext::get_request();

$submit = $request->get_postvalue('submit', false);

$invisible = (int)retrieve(GET, 'invisible', 0, TINTEGER);
$add = (int)retrieve(GET, 'add', 0, TINTEGER);
$edit = (int)retrieve(GET, 'edit', 0, TINTEGER);
$delete = (int)retrieve(GET, 'del', 0, TINTEGER);

// File status modification
if ($invisible > 0)
{
	AppContext::get_session()->csrf_get_protect();

	try {
		$media = PersistenceContext::get_querier()->select_single_row(PREFIX . 'media', array('*'), 'WHERE id=:id', array('id' => $invisible));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	// Errors management
	if (empty($media))
	{
		$controller = new UserErrorController(LangLoader::get_message('warning.error', 'warning-lang'), $lang['e_unexist_media']);
		DispatchManager::redirect($controller);
	}
	else if (!CategoriesAuthorizationsService::check_authorizations($media['id_category'])->moderation())
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
	}

	bread_crumb($media['id_category']);
	$Bread_crumb->add($media['title'], url('media.php?id=' . $media['id'], 'media-' . $media['id'] . '-' . $media['id_category'] . '+' . Url::encode_rewrite($media['title']) . '.php'));
	$Bread_crumb->add($lang['media.hide.item'], url('media_action.php?invisible=' . $media['id'] . '&amp;token=' . AppContext::get_session()->get_token()));

	define('TITLE', $lang['media.management']);

	PersistenceContext::get_querier()->update(PREFIX . 'media', array('published' => MEDIA_STATUS_INVISIBLE), 'WHERE id=:id', array('id' => $invisible));

	require_once('../kernel/header.php');

	AppContext::get_response()->redirect('media' . url('.php?cat=' . $media['id_category'], '-0-' . $media['id_category'] . '.php'));
}
// Delete a file
else if ($delete > 0)
{
	AppContext::get_session()->csrf_get_protect();

	try {
		$media = PersistenceContext::get_querier()->select_single_row(PREFIX . 'media', array('*'), 'WHERE id=:id', array('id' => $delete));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	if (empty($media))
	{
		$controller = new UserErrorController(LangLoader::get_message('warning.error', 'warning-lang'), $lang['e_unexist_media']);
		DispatchManager::redirect($controller);
	}
	else if (!CategoriesAuthorizationsService::check_authorizations($media['id_category'])->moderation())
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
	}

	PersistenceContext::get_querier()->delete(PREFIX . 'media', 'WHERE id=:id', array('id' => $delete));

	NotationService::delete_notes_id_in_module('media', $delete);

	CommentsService::delete_comments_topic_module('media', $delete);

	// Feeds Regeneration
	Feed::clear_cache('media');

	MediaCategoriesCache::invalidate();
	HooksService::execute_hook_action('delete', 'media', $media);

	$category = CategoriesService::get_categories_manager('media')->get_categories_cache()->get_category($media['id_category']);
	bread_crumb($media['id_category']);
	$Bread_crumb->add($lang['media.delete.item'], url('media.php?cat=' . $media['id_category'], 'media-0-' . $media['id_category'] . '+' . $category->get_rewrited_name() . '.php'));

	define('TITLE', $lang['media.delete.item']);
	require_once('../kernel/header.php');

	AppContext::get_response()->redirect('media' . url('.php?cat=' . $media['id_category'], '-0-' . $media['id_category'] . '.php'));
}
// Add/edit form
else if ($add >= 0 && !$submit || $edit > 0)
{
	$editor = AppContext::get_content_formatting_service()->get_default_editor();
	$editor->set_identifier('content');

	$view->put_all(array(
		'C_ADD_MEDIA' => true,
		'C_AUTH_UPLOAD' => FileUploadConfig::load()->is_authorized_to_access_interface_files(),
		'U_TARGET' => url('media_action.php'),
		'KERNEL_EDITOR' => $editor->display(),
	));

	// Build of the musical categories table
	$categories = CategoriesService::get_categories_manager('media')->get_categories_cache()->get_categories();
	$js_id_music = array();
	foreach ($categories as $cat)
	{
		if ($cat->get_content_type() == MediaConfig::CONTENT_TYPE_MUSIC)
			$js_id_music[] = $cat->get_id();
	}

	$search_category_children_options = new SearchCategoryChildrensOptions();
	$search_category_children_options->add_authorizations_bits(Category::CONTRIBUTION_AUTHORIZATIONS);
	$search_category_children_options->add_authorizations_bits(Category::WRITE_AUTHORIZATIONS);

	$media = '';

	// Edit
	if ($edit > 0)
	{
		try {
			$media = PersistenceContext::get_querier()->select_single_row(PREFIX . 'media', array('*'), 'WHERE id=:id', array('id' => $edit));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		if (!CategoriesAuthorizationsService::check_authorizations($media['id_category'])->moderation())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}

		bread_crumb($media['id_category']);

		$categories_tree = CategoriesService::get_categories_manager('media')->get_select_categories_form_field('id_category', '', $media['id_category'], $search_category_children_options);
		$method = new ReflectionMethod('AbstractFormFieldChoice', 'get_options');
		$method->setAccessible(true);
		$categories_tree_options = $method->invoke($categories_tree);
		$categories_list = '';
		foreach ($categories_tree_options as $option)
		{
			$categories_list .= $option->display()->render();
		}

		$view->put_all(array(
			'C_EDIT' => true,
			'C_MUSIC' => in_array($media['mime_type'], $mime_type['audio']),
			'C_APPROVAL' => ($media['published'] & MEDIA_STATUS_APPROVED) === 0,
			'C_CONTRIBUTION' => 0,
			'ITEM_ID' => $media['id'],
			'TITLE' => $media['title'],
			'C_CATEGORIES' => CategoriesService::get_categories_manager('media')->get_categories_cache()->has_categories(),
			'CATEGORIES' => $categories_list,
			'WIDTH' => $media['width'],
			'HEIGHT' => $media['height'],
			'U_MEDIA' => $media['file_url'],
			'POSTER' => $media['thumbnail'],
			'CONTENT' => FormatingHelper::unparse(stripslashes($media['content'])),
			'APPROVED' => ($media['published'] & MEDIA_STATUS_APPROVED) !== 0 ? ' checked="checked"' : '',
			'JS_ID_MUSIC' => '"' . implode('", "', $js_id_music) . '"',
		));

		$location_id = 'media-edit-'. $edit;
	}
	// Add
	else if (($write = CategoriesAuthorizationsService::check_authorizations()->write()) || CategoriesAuthorizationsService::check_authorizations()->contribution())
	{
		bread_crumb($add);

		$editor = AppContext::get_content_formatting_service()->get_default_editor();
		$editor->set_identifier('counterpart');

		$categories_tree = CategoriesService::get_categories_manager('media')->get_select_categories_form_field('id_category', '', Category::ROOT_CATEGORY, $search_category_children_options);
		$method = new ReflectionMethod('AbstractFormFieldChoice', 'get_options');
		$method->setAccessible(true);
		$categories_tree_options = $method->invoke($categories_tree);
		$categories_list = '';
		foreach ($categories_tree_options as $option)
		{
			$categories_list .= $option->display()->render();
		}

		$view->put_all(array(
			'C_CONTRIBUTION' => !$write,
			'CONTRIBUTION_EDITOR' => $editor->display(),
			'IDEDIT' => 0,
			'TITLE' => '',
			'C_CATEGORIES' => CategoriesService::get_categories_manager('media')->get_categories_cache()->has_categories(),
			'CATEGORIES' => $categories_list,
			'WIDTH' => '800',
			'HEIGHT' => '450',
			'U_MEDIA' => '',
			'POSTER' => '',
			'CONTENT' => '',
			'APPROVED' => 'checked="checked"',
			'C_APPROVAL' => false,
			'JS_ID_MUSIC' => '"' . implode('", "', $js_id_music) . '"',
			'C_MUSIC' => $config->is_root_category_content_type_music()
		));
	}
	else
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
	}

	if (!empty($media))
	{
		$Bread_crumb->add($media['title'], url('media.php?id=' . $media['id'], 'media-' . $media['id'] . '-' . $media['id_category'] . '+' . Url::encode_rewrite($media['title']) . '.php'));
		$Bread_crumb->add($lang['media.edit.item'], url('media_action.php?edit=' . $media['id']));
		define('TITLE', $lang['media.edit.item']);
	}
	else
	{
		$Bread_crumb->add($lang['media.add.item'], url('media_action.php'));
		define('TITLE', $lang['media.add.item']);
	}

	require_once('../kernel/header.php');
}
// Processing the form
elseif ($submit)
{
	AppContext::get_session()->csrf_get_protect();

	$media = array(
		'idedit' => (int)retrieve(POST, 'idedit', 0, TINTEGER),
		'title' => stripslashes(retrieve(POST, 'title', '', TSTRING)),
		'id_category' => CategoriesService::get_categories_manager('media')->get_categories_cache()->has_categories() ? retrieve(POST, 'id_category', 0, TINTEGER) : Category::ROOT_CATEGORY,
		'width' => min(retrieve(POST, 'width', $config->get_max_video_width(), TINTEGER), $config->get_max_video_width()),
		'height' => min(retrieve(POST, 'height', $config->get_max_video_height(), TINTEGER), $config->get_max_video_height()),
		'file_url' => new Url(retrieve(POST, 'u_media', '', TSTRING)),
		'thumbnail' => new Url(retrieve(POST, 'thumbnail', '', TSTRING)),
		'content' => retrieve(POST, 'content', '', TSTRING_PARSE),
		'approved' => (bool)retrieve(POST, 'approved', false, TBOOL),
		'contrib' => (bool)retrieve(POST, 'contrib', false, TBOOL),
		'counterpart' => retrieve(POST, 'counterpart', '', TSTRING_PARSE)
	);

	$category = CategoriesService::get_categories_manager('media')->get_categories_cache()->get_category($media['id_category']);
	bread_crumb($media['id_category']);

	if ($media['idedit'])
	{
		$Bread_crumb->add($media['title'], url('media.php?id=' . $media['idedit'], 'media-' . $media['idedit'] . '-' . $media['id_category'] . '+' . Url::encode_rewrite($media['title']) . '.php'));
		$Bread_crumb->add($lang['media.edit.item'], url('media_action.php?edit=' . $media['idedit']));
		define('TITLE', $lang['media.edit.item']);
	}
	else
	{
		$Bread_crumb->add($lang['media.add.item'], url('media_action.php?add=' . $media['id_category']));
		define('TITLE', $lang['media.add.item']);
	}

	require_once('../kernel/header.php');

	if ($category->get_content_type() == MediaConfig::CONTENT_TYPE_MUSIC)
	{
		$mime_type = $mime_type['audio'];
		$host_ok = $host_ok['audio'];
	}
	else if ($category->get_content_type() == MediaConfig::CONTENT_TYPE_VIDEO)
	{
		$mime_type = $mime_type['video'];
		$host_ok = $host_ok['video'];
	}
	else
	{
		$mime_type = array_merge($mime_type['audio'], $mime_type['video']);
		$host_ok = array_merge($host_ok['audio'], $host_ok['video']);
	}

	if (!empty($media['file_url']))
	{
		$url_media = preg_replace('`\?.*`u', '', $media['file_url']->relative());
		$pathinfo = pathinfo($url_media);
		$url_parsed = parse_url($media['file_url']->relative());

		if (isset($url_parsed['host']) && in_array($url_parsed['host'], $host_ok) && (strpos($pathinfo['dirname'], 'soundcloud') !== false) && in_array('audio/host', $mime_type))
		{
			$media['mime_type'] = 'audio/host';
		}
		else if ($media['file_url']->relative())
		{
			if (!empty($pathinfo['extension']))
			{
				if (array_key_exists($pathinfo['extension'], $mime_type))
				{
					$media['mime_type'] = $mime_type[$pathinfo['extension']];
				}
				else
				{
					$controller = new UserErrorController(LangLoader::get_message('warning.error', 'warning-lang'), $lang['e_mime_disable_media']);
					DispatchManager::redirect($controller);
				}
			}
			else if (isset($url_parsed['host']) && in_array($url_parsed['host'], $host_ok) && in_array('video/host', $mime_type))
			{
				$media['mime_type'] = 'video/host';
			}
			else if (function_exists('get_headers') && ($headers = get_headers($media['file_url']->relative(), 1)) && !empty($headers['Content-Type']))
			{
				if (!is_array($headers['Content-Type']) && in_array($headers['Content-Type'], $mime_type))
				{
					$media['mime_type'] = $headers['Content-Type'];
				}
				else if (is_array($headers['Content-Type']))
				{
					foreach ($headers['Content-Type'] as $type)
					{
						if (in_array($type, $mime_type))
						{
							$media['mime_type'] = $type;
						}
					}

					if (empty($media['mime_type']))
					{
						$controller = new UserErrorController(LangLoader::get_message('warning.error', 'warning-lang'), $lang['e_mime_disable_media']);
						DispatchManager::redirect($controller);
					}
				}
				else
				{
					$controller = new UserErrorController(LangLoader::get_message('warning.error', 'warning-lang'), $lang['e_mime_disable_media']);
					DispatchManager::redirect($controller);
				}
			}
			else
			{
				$controller = new UserErrorController(LangLoader::get_message('warning.error', 'warning-lang'), $lang['e_mime_unknow_media']);
				DispatchManager::redirect($controller);
			}
		}
		else
		{
			$controller = new UserErrorController(LangLoader::get_message('warning.error', 'warning-lang'), $lang['e_link_invalid_media']);
			DispatchManager::redirect($controller);
		}
	}
	else
	{
		$controller = new UserErrorController(LangLoader::get_message('warning.error', 'warning-lang'), (empty($media['file_url']) ? $lang['e_link_empty_media'] : $lang['e_link_invalid_media']));
		DispatchManager::redirect($controller);
	}

	// Edit
	if ($media['idedit'] && CategoriesAuthorizationsService::check_authorizations($media['id_category'])->moderation())
	{
		$properties = array(
			'id_category'    => $media['id_category'],
			'title'          => $media['title'],
			'content'        => $media['content'],
			'file_url'       => $media['file_url']->relative(),
			'thumbnail'      => $media['thumbnail']->relative(),
			'mime_type'      => $media['mime_type'],
			'published'      => (CategoriesAuthorizationsService::check_authorizations($media['id_category'])->write() ? MEDIA_STATUS_APPROVED : 0),
			'width'          => $media['width'],
			'height'         => $media['height']
		);

		PersistenceContext::get_querier()->update(PREFIX . "media", $properties, 'WHERE id = :id', array('id' => $media['idedit']));

		$properties['id'] = $media['idedit'];

		if ($media['approved'])
		{
			$corresponding_contributions = ContributionService::find_by_criteria('media', $media['idedit']);

			if (count($corresponding_contributions) > 0)
			{
				foreach ($corresponding_contributions as $contribution)
				{
					$contribution->set_status(Event::EVENT_STATUS_PROCESSED);
					ContributionService::save_contribution($contribution);
				}
				HooksService::execute_hook_action('process_contribution', 'media', array_merge($contribution->get_properties(), $properties, array('url' => Url::to_rel('/media/' . url('media.php?id=' . $media['idedit'], 'media-' . $media['idedit'] . '-' . $media['id_category'] . '+' . Url::encode_rewrite($media['title']) . '.php')))));
			}
		}

		// Feeds Regeneration
		Feed::clear_cache('media');

		MediaCategoriesCache::invalidate();

		HooksService::execute_hook_action('edit', 'media', array_merge($properties, array('url' => Url::to_rel('/media/' . url('media.php?id=' . $media['idedit'], 'media-' . $media['idedit'] . '-' . $media['id_category'] . '+' . Url::encode_rewrite($media['title']) . '.php')))));

		AppContext::get_response()->redirect('media' . url('.php?id=' . $media['idedit']));
	}
	// Add
	else if (!$media['idedit'] && (($auth_write = CategoriesAuthorizationsService::check_authorizations($media['id_category'])->write()) || CategoriesAuthorizationsService::check_authorizations($media['id_category'])->contribution()))
	{
		$properties = array(
			'id_category'    => $media['id_category'],
			'author_user_id' => AppContext::get_current_user()->get_id(),
			'creation_date'  => time(),
			'title'          => $media['title'],
			'content'        => $media['content'],
			'file_url'       => $media['file_url']->relative(),
			'thumbnail'      => $media['thumbnail']->relative(),
			'mime_type'      => $media['mime_type'],
			'published'      => (CategoriesAuthorizationsService::check_authorizations($media['id_category'])->write() ? MEDIA_STATUS_APPROVED : 0),
			'width'          => $media['width'],
			'height'         => $media['height']
		);

		$result = PersistenceContext::get_querier()->insert(PREFIX . "media", $properties);

		$new_id_media = $result->get_last_inserted_id();
		$properties['id'] = $new_id_media;

		// Feeds Regeneration
		Feed::clear_cache('media');

		MediaCategoriesCache::invalidate();

		if (!$auth_write)
		{
			$media_contribution = new Contribution();
			$media_contribution->set_id_in_module($new_id_media);
			$media_contribution->set_description(stripslashes($media['counterpart']));
			$media_contribution->set_entitled($media['title']);
			$media_contribution->set_fixing_url('/media/media_action.php?edit=' . $new_id_media);
			$media_contribution->set_poster_id(AppContext::get_current_user()->get_id());
			$media_contribution->set_module('media');
			$media_contribution->set_auth(
				Authorizations::capture_and_shift_bit_auth(
					CategoriesService::get_categories_manager('media')->get_heritated_authorizations($media['id_category'], Category::MODERATION_AUTHORIZATIONS, Authorizations::AUTH_CHILD_PRIORITY),
					Category::MODERATION_AUTHORIZATIONS, Contribution::CONTRIBUTION_AUTH_BIT
				)
			);

			ContributionService::save_contribution($media_contribution);
			HooksService::execute_hook_action('add_contribution', 'media', array_merge($media_contribution->get_properties(), $properties, array('url' => Url::to_rel('/media/' . url('media.php?id=' . $new_id_media, 'media-' . $new_id_media . '-' . $media['id_category'] . '+' . Url::encode_rewrite($media['title']) . '.php')))));

			DispatchManager::redirect(new UserContributionSuccessController());
		}
		else
		{
			HooksService::execute_hook_action('add', 'media', array_merge($properties, array('url' => Url::to_rel('/media/' . url('media.php?id=' . $new_id_media, 'media-' . $new_id_media . '-' . $media['id_category'] . '+' . Url::encode_rewrite($media['title']) . '.php')))));
			AppContext::get_response()->redirect('media' . url('.php?id=' . $new_id_media));
		}
	}
	else
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
	}
}
else
{
	$error_controller = PHPBoostErrors::unexisting_page();
	DispatchManager::redirect($error_controller);
}

$view->display();

require_once('../kernel/footer.php');

?>
