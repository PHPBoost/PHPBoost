<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Geoffrey ROGUELON <liaght@gmail.com>
 * @version     PHPBoost 6.0 - last update: 2022 09 03
 * @since       PHPBoost 2.0 - 2008 10 20
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

require_once('../kernel/begin.php');
require_once('media_begin.php');

// Display caterories and media files.
if (empty($id_media))
{
	bread_crumb($id_cat);

	$category = CategoriesService::get_categories_manager('media')->get_categories_cache()->get_category($id_cat);
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
	$view = new FileTemplate('media/MediaItemController.tpl');
	$lang = LangLoader::get_all_langs('media');
	$view->add_lang($lang);
	$config = MediaConfig::load();
	$comments_config = CommentsConfig::load();
	$content_management_config = ContentManagementConfig::load();

	try {
		$media = PersistenceContext::get_querier()->select_single_row_query("SELECT v.*, mb.user_id, mb.display_name, mb.user_groups, mb.level, notes.average_notes, notes.notes_number, note.note
		FROM " . PREFIX . "media AS v
		LEFT JOIN " . DB_TABLE_MEMBER . " AS mb ON v.author_user_id = mb.user_id
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

	if (($media['published'] & MEDIA_STATUS_INVISIBLE) !== 0)
	{
		$controller = new UserErrorController(LangLoader::get_message('warning.error', 'warning-lang'),
		$lang['e_unexist_media']);
		DispatchManager::redirect($controller);
	}
	elseif (!CategoriesAuthorizationsService::check_authorizations($media['id_category'])->read())
	{
		$error_controller = PHPBoostErrors::user_not_authorized();
		DispatchManager::redirect($error_controller);
	}

	bread_crumb($media['id_category']);
	$Bread_crumb->add($media['title'], url('media.php?id=' . $id_media, 'media-' . $id_media . '-' . $media['id_category'] . '+' . Url::encode_rewrite($media['title']) . '.php'));

	define('TITLE', $media['title']);
	define('DESCRIPTION', TextHelper::cut_string(@strip_tags(FormatingHelper::second_parse(stripslashes($media['content'])), '<br><br/>'), 150));
	require_once('../kernel/header.php');

	// Update views_number
	PersistenceContext::get_querier()->inject("UPDATE " . PREFIX . "media SET views_number = views_number + 1 WHERE id = :id", array('id' => $id_media));

	$notation = new Notation();
	$notation->set_module_name('media');
	$notation->set_id_in_module($id_media);
	$notation->set_notes_number($media['notes_number']);
	$notation->set_average_notes($media['average_notes']);
	$notation->set_user_already_noted(!empty($media['note']));

	$group_color = User::get_group_color($media['user_groups'], $media['level']);

	$date = new Date($media['creation_date'], Timezone::SERVER_TIMEZONE);

	$content = FormatingHelper::second_parse(stripslashes($media['content']));
	$rich_content = HooksService::execute_hook_display_action('media', $content, $media);

	$view->put_all(array_merge(
		Date::get_array_tpl_vars($date, 'date'),
		array(
			'C_ROOT_CATEGORY'      => $media['id_category'] == Category::ROOT_CATEGORY,
			'C_CONTROLS'           => CategoriesAuthorizationsService::check_authorizations($media['id_category'])->moderation(),
			'C_ENABLED_NOTATION'   => $content_management_config->module_notation_is_enabled('media'),
			'C_ENABLED_COMMENTS'   => $comments_config->module_comments_is_enabled('media'),
			'C_NEW_CONTENT'        => ContentManagementConfig::load()->module_new_content_is_enabled_and_check_date('media', $media['creation_date']),
			'C_AUTHOR_DISPLAYED'   => $config->is_author_displayed(),
			'C_AUTHOR_EXISTS'      => !empty($media['display_name']),
			'C_AUTHOR_GROUP_COLOR' => !empty($group_color),
			'C_HAS_THUMBNAIL'	   => !empty($media['thumbnail']),

			'ID'                  => $id_media,
			'TITLE'               => $media['title'],
			'CONTENT'             => $rich_content,
			'KERNEL_NOTATION'     => NotationService::display_active_image($notation),
			'VIEWS_NUMBER'        => (int)$media['views_number']+1,
			'COMMENTS_NUMBER'     => CommentsService::get_comments_number('media', $id_media),
			'AUTHOR_DISPLAY_NAME' => $media['display_name'],
			'AUTHOR_LEVEL_CLASS'  => UserService::get_level_class($media['level']),
			'AUTHOR_GROUP_COLOR'  => $group_color,
			'CATEGORY_ID'         => $media['id_category'],
			'CATEGORY_NAME'       => $media['id_category'] == Category::ROOT_CATEGORY ? $lang['media.module.title'] : CategoriesService::get_categories_manager('media')->get_categories_cache()->get_category($media['id_category'])->get_name(),
			
			'U_STATUS' 		   => url('media_action.php?invisible=' . $id_media . '&amp;token=' . AppContext::get_session()->get_token()),
			'U_EDIT'      	   => url('media_action.php?edit=' . $id_media),
			'U_DELETE'    	   => url('media_action.php?del=' . $id_media . '&amp;token=' . AppContext::get_session()->get_token()),
			'U_AUTHOR_PROFILE' => UserUrlBuilder::profile($media['user_id'])->rel(),
			'U_EDIT_CATEGORY'  => $media['id_category'] == Category::ROOT_CATEGORY ? MediaUrlBuilder::configuration()->rel() : CategoriesUrlBuilder::edit($media['id_category'], 'media')->rel(),
			'U_THUMBNAIL'      => Url::to_rel($media['thumbnail'])
		)
	));

	if (empty($mime_type_tpl[$media['mime_type']]))
	{
		$media_tpl = new FileTemplate('media/format/media_other.tpl');
	}
	else
	{
		$media_tpl = new FileTemplate('media/' . $mime_type_tpl[$media['mime_type']]);
	}

	if (!empty($media['thumbnail']))
	{
		$poster_type = new FileType(new File($media['thumbnail']));
		$picture_url = new Url($media['thumbnail']);

		$media_tpl->put_all(array(
			'C_POSTER' => $poster_type->is_picture(),
			'POSTER' => $picture_url->rel()
		));
	}

	// Media from websites
	$pathinfo = pathinfo($media['file_url']);
	$media_id = $pathinfo['basename'];
	$dirname = $pathinfo['dirname'];

	foreach($host_players as $domain => $player)
	{
		if(strpos($dirname, $domain) !== false)
		{
			// Youtube
			$watch = 'watch?v=';
			if(strpos($media_id, $watch) !== false) {
				$media_id = substr_replace($media_id, '', 0, 8);
				list($media_id) = explode('&', $media_id);
			}

			// Odysee
			$odysee_player =  strpos($dirname, 'odysee') !== false;
			$odysee_dl_link = strpos($dirname, 'download') !== false;
			$odysee_embed_link = strpos($dirname, 'embed') !== false;
			if($odysee_player) {
				if($odysee_dl_link || $odysee_embed_link) {
					$explode = explode('/', $dirname);
			        $media_id = $explode[5] . '/' . $media_id; // add video title in final url
				}
				else {
					$controller = new UserErrorController(LangLoader::get_message('warning.error', 'warning-lang'),
					$lang['e_bad_url_odysee']);
					DispatchManager::redirect($controller);
				}
			}

			// Peertube
			$peertube_link = $config->get_peertube_constant();
			$peertube_host = explode('/', $peertube_link);
			$peertube_host_player = explode('.', $peertube_host[2]);
			$sliced_name = array_slice($peertube_host_player, 0, -1);
			$peertube_player = implode('.', $sliced_name);
			$player_is_peertube =  strpos($dirname, $peertube_player) !== false;
			$peertube_watch_link = strpos($dirname, '/w') !== false;
			$peertube_embed_link = strpos($dirname, 'embed') !== false;
			
			if($player_is_peertube) {
				if(!$peertube_embed_link && !$peertube_watch_link) {
					$controller = new UserErrorController(LangLoader::get_message('warning.error', 'warning-lang'),
					$lang['e_bad_url_peertube']);
					DispatchManager::redirect($controller);
				}
			}

			// Twitch
			$twitch_player = strpos($dirname, 'twitch') !== false;
			$parent = pathinfo(GeneralConfig::load()->get_site_url());
			$parent = $parent['basename'];
			if($twitch_player) {
				$media_tpl->put_all(array(
					'C_TWITCH' => true,
					'PARENT' => $parent
				));
			}

			// Soundcloud
			$soundcloud_player = strpos($dirname, 'soundcloud') !== false;
			if($soundcloud_player) {
				$explode = explode('/', $dirname);
				$soundcloud_type = end($explode);

				$media_tpl->put_all(array(
					'C_SOUNDCLOUD' => $soundcloud_player,

					'SOUNDCLOUD_TYPE' => $soundcloud_player ? $soundcloud_type : '',
				));
			}

			// All
			$media_tpl->put_all(array(
				'PLAYER' => $player
			));
		}
	}

	$media_tpl->put_all(array(
		'FILE_URL' => Url::to_rel($media['file_url']),
		'MIME' => $media['mime_type'],
		'WIDTH' => $media['width'],
		'HEIGHT' => $media['height'],
		'MEDIA_ID' => $media_id
	));

	$view->put('MEDIA_FORMAT', $media_tpl);

	// Comments display
	if (AppContext::get_request()->get_getint('com', 0) == 0)
	{
		$comments_topic = new MediaCommentsTopic();
		$comments_topic->set_id_in_module($id_media);
		$comments_topic->set_url(new Url('/media/media.php?id='. $id_media . '&com=0'));
		$view->put_all(array(
			'COMMENTS' => CommentsService::display($comments_topic)->render()
		));
	}
	$view->display();
}

require_once('../kernel/footer.php');

?>
