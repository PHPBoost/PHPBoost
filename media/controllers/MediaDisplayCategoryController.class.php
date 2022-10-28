<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 10 28
 * @since       PHPBoost 4.1 - 2015 02 04
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class MediaDisplayCategoryController extends DefaultModuleController
{
	private $category;

	protected function get_template_to_use()
	{
		return new FileTemplate('media/MediaSeveralItemsController.tpl');
	}

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->build_view();

		return $this->generate_response();
	}

	private function build_view()
	{
		require_once(PATH_TO_ROOT . '/media/media_constant.php');
		$comments_config = CommentsConfig::load();
		$content_management_config = ContentManagementConfig::load();

		// Category content
		$page = AppContext::get_request()->get_getint('p', 1);
		$subcategories_page = AppContext::get_request()->get_getint('subcategories_page', 1);
		$get_sort = retrieve(GET, 'sort', '');
		$get_mode = retrieve(GET, 'mode', '');
		$mode = ($get_mode == 'asc') ? 'ASC' : 'DESC';
		$unget = (!empty($get_sort) && !empty($mode)) ? '?sort=' . $get_sort . '&amp;mode=' . $get_mode : '';

		$subcategories = CategoriesService::get_categories_manager('media')->get_categories_cache()->get_children($this->get_category()->get_id(), CategoriesService::get_authorized_categories($this->get_category()->get_id(), true, 'media'));

		$subcategories_pagination = new ModulePagination($subcategories_page, count($subcategories), $this->config->get_categories_per_page());
		$subcategories_pagination->set_url(new Url('/media/media.php' . (!empty($unget) ? $unget . '&amp;' : '?') . 'cat=' . $this->get_category()->get_id() . '&amp;p=' . $page . '&amp;subcategories_page=%d'));

		if ($subcategories_pagination->current_page_is_empty() && $subcategories_page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		$categories_number = 0;
		foreach ($subcategories as $id => $category)
		{
			$categories_number++;

			if ($categories_number > $subcategories_pagination->get_display_from() && $categories_number <= ($subcategories_pagination->get_display_from() + $subcategories_pagination->get_number_items_per_page()))
			{
				$category_thumbnail = $category->get_thumbnail()->rel();

				$this->view->assign_block_vars('sub_categories_list', array(
					'C_CATEGORY_THUMBNAIL' => !empty($category_thumbnail),
					'C_SEVERAL_ITEMS'      => $category->get_elements_number() > 1,

					'CATEGORY_ID'     	 => $category->get_id(),
					'CATEGORY_NAME'   	 => $category->get_name(),
					'CATEGORY_PARENT_ID' => $category->get_id_parent(),
					'CATEGORY_SUB_ORDER' => $category->get_order(),
					'ITEMS_NUMBER'    	 => sprintf($category->get_elements_number()),

					'U_CATEGORY_THUMBNAIL' => $category_thumbnail,
					'U_CATEGORY'           => MediaUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name())->rel()
				));
			}
		}

		$category_description = FormatingHelper::second_parse($this->get_category()->get_description());

		$this->view->put_all(array(
			'C_ROOT_CATEGORY'            => $this->get_category()->get_id() == Category::ROOT_CATEGORY,
			'C_CATEGORY_DESCRIPTION'     => $category_description,
			'C_SUB_CATEGORIES'           => $categories_number > 0,
			'C_CONTROLS'                 => CategoriesAuthorizationsService::check_authorizations($this->get_category()->get_id())->moderation(),
			'C_SUBCATEGORIES_PAGINATION' => $subcategories_pagination->has_several_pages(),

			'SUBCATEGORIES_PAGINATION' => $subcategories_pagination->display(),
			'CATEGORIES_PER_ROW'       => $this->config->get_categories_per_row(),
			'ITEMS_PER_ROW'            => $this->config->get_items_per_row(),
			'CATEGORY_NAME'            => $this->get_category()->get_id() == Category::ROOT_CATEGORY ? $this->lang['media.module.title'] : $this->get_category()->get_name(),
			'CATEGORY_PARENT_ID'   	   => $this->get_category()->get_id_parent(),
			'CATEGORY_SUB_ORDER'   	   => $this->get_category()->get_order(),
			'CATEGORY_DESCRIPTION'     => $category_description,
			'CATEGORY_ID'              => $this->get_category()->get_id(),

			'U_EDIT_CATEGORY' => $this->get_category()->get_id() == Category::ROOT_CATEGORY ? MediaUrlBuilder::configuration()->rel() : CategoriesUrlBuilder::edit($this->get_category()->get_id(), 'media')->rel(),
		));

		$selected_fields = array('date' => '', 'alpha' => '', 'views' => '', 'note' => '', 'com' => '', 'asc' => '', 'desc' => '');

		switch ($get_sort)
		{
			default:
			case 'date':
				$sort = 'creation_date';
				$selected_fields['date'] = ' selected="selected"';
				break;
			case 'alpha':
				$sort = 'title';
				$selected_fields['alpha'] = ' selected="selected"';
				break;
			case 'views':
				$sort = 'views_number';
				$selected_fields['views'] = ' selected="selected"';
				break;
			case 'note':
				$sort = 'average_notes';
				$selected_fields['note'] = ' selected="selected"';
				break;
			case 'com':
				$sort = 'com.comments_number';
				$selected_fields['com'] = ' selected="selected"';
				break;
		}

		if ($mode == 'DESC')
			$selected_fields['desc'] = ' selected="selected"';
		else
			$selected_fields['asc'] = ' selected="selected"';

		$this->view->put_all(array(
			'C_ENABLED_NOTATION' => $content_management_config->module_notation_is_enabled('media'),
			'C_ENABLED_COMMENTS' => $comments_config->module_comments_is_enabled('media'),

			'SELECTED_ALPHA' => $selected_fields['alpha'],
			'SELECTED_DATE'  => $selected_fields['date'],
			'SELECTED_VIEWS' => $selected_fields['views'],
			'SELECTED_NOTE'  => $selected_fields['note'],
			'SELECTED_COM'   => $selected_fields['com'],
			'SELECTED_ASC'   => $selected_fields['asc'],
			'SELECTED_DESC'  => $selected_fields['desc']
		));

		$condition = 'WHERE id_category = :id_category AND published = :status';
		$parameters = array(
			'id_category' => $this->get_category()->get_id(),
			'status' => MEDIA_STATUS_APPROVED
		);

		// Pagination creation for a too big amount of items
		$mediafiles_number = MediaService::count($condition, $parameters);
		$pagination = new ModulePagination($page, $mediafiles_number, $this->config->get_items_per_page());
		$pagination->set_url(new Url('/media/media.php' . (!empty($unget) ? $unget . '&amp;' : '?') . 'cat=' . $this->get_category()->get_id() . '&amp;p=%d&amp;subcategories_page=' . $subcategories_page));

		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		$result = PersistenceContext::get_querier()->select("SELECT
			v.id, v.author_user_id, v.title, v.creation_date, v.views_number, v.published, v.thumbnail, v.content,
			mb.display_name, mb.user_groups, mb.level, mb.user_id,
			notes.notes_number, notes.average_notes,
			com.comments_number
			FROM " . PREFIX . "media AS v
			LEFT JOIN " . DB_TABLE_MEMBER . " AS mb ON v.author_user_id = mb.user_id
			LEFT JOIN " . DB_TABLE_AVERAGE_NOTES . " notes ON v.id = notes.id_in_module AND notes.module_name = 'media'
			LEFT JOIN " . DB_TABLE_COMMENTS_TOPIC . " com ON v.id = com.id_in_module AND com.module_id = 'media'
			" . $condition . "
			ORDER BY " . $sort . " " . $mode . "
			LIMIT :number_items_per_page OFFSET :display_from", array_merge($parameters, array(
				'number_items_per_page' => $pagination->get_number_items_per_page(),
				'display_from' => $pagination->get_display_from()
		)));

		$this->view->put_all(array(
			'C_ITEMS'         => $result->get_rows_count() > 0,
			'C_SEVERAL_ITEMS' => $result->get_rows_count() > 1,
			'C_NO_ITEM'       => $result->get_rows_count() == 0 && $this->get_category()->get_id() != Category::ROOT_CATEGORY,
			'C_GRID_VIEW'     => $this->config->get_display_type() == MediaConfig::GRID_VIEW,
			'C_LIST_VIEW'     => $this->config->get_display_type() == MediaConfig::LIST_VIEW,
			'C_PAGINATION'    => $pagination->has_several_pages(),
			'PAGINATION'      => $pagination->display(),
			'CHANGE_ORDER'    => ServerEnvironmentConfig::load()->is_url_rewriting_enabled() ? 'media-0-' . $this->get_category()->get_id() . '.php?' : 'media.php?cat=' . $this->get_category()->get_id() . '&',
		));

		while ($row = $result->fetch())
		{
			$notation = new Notation();
			$notation->set_module_name('media');
			$notation->set_id_in_module($row['id']);
			$notation->set_notes_number($row['notes_number']);
			$notation->set_average_notes($row['average_notes']);
			$notation->set_user_already_noted(!empty($media['note']));

			$group_color = User::get_group_color($row['user_groups'], $row['level']);

			$poster_infos = array();
			if (!empty($row['thumbnail']))
			{
				$poster_type = new FileType(new File($row['thumbnail']));
				$picture_url = new Url($row['thumbnail']);

				$poster_infos = array(
					'C_HAS_THUMBNAIL' => $poster_type->is_picture(),
					'U_THUMBNAIL'       => $picture_url->rel()
				);
			}

			$date = new Date($row['creation_date'], Timezone::SERVER_TIMEZONE);

			$summary = TextHelper::cut_string(@strip_tags(FormatingHelper::second_parse($row['content']), '<br><br/>'), $this->config->get_characters_number_to_cut());

			$this->view->assign_block_vars('items', array_merge(
				$poster_infos,
				Date::get_array_tpl_vars($date, 'date'),
				array(
				'C_NEW_CONTENT'        => ContentManagementConfig::load()->module_new_content_is_enabled_and_check_date('media', $row['creation_date']),
				'C_CONTENT'            => !empty($row['content']),
				'C_AUTHOR_DISPLAYED'   => $this->config->is_author_displayed(),
				'C_AUTHOR_EXISTS'      => !empty($row['display_name']),
				'C_AUTHOR_GROUP_COLOR' => !empty($group_color),

				'ID'                  => $row['id'],
				'TITLE'               => $row['title'],
				'IMG_TITLE'           => str_replace('"', '\"', $row['title']),
				'SUMMARY'             => FormatingHelper::second_parse($summary),
				'AUTHOR_DISPLAY_NAME' => $row['display_name'],
				'AUTHOR_LEVEL_CLASS'  => UserService::get_level_class($row['level']),
				'AUTHOR_GROUP_COLOR'  => $group_color,
				'VIEWS_NUMBER'        => $row['views_number'],
				'COMMENTS_NUMBER'     => CommentsService::get_comments_number('media', $row['id']),
				'KERNEL_NOTATION'     => NotationService::display_static_image($notation),

				'U_AUTHOR_PROFILE' => UserUrlBuilder::profile($row['user_id'])->rel(),
				'U_ITEM'           => PATH_TO_ROOT . '/media/' . url('media.php?id=' . $row['id'], 'media-' . $row['id'] . '-' . $this->get_category()->get_id() . '+' . Url::encode_rewrite($row['title']) . '.php'),
				'U_STATUS' 		   => PATH_TO_ROOT . url('/media/media_action.php?invisible=' . $row['id'] . '&amp;token=' . AppContext::get_session()->get_token()),
				'U_EDIT'     	   => PATH_TO_ROOT . url('/media/media_action.php?edit=' . $row['id']),
				'U_DELETE'   	   => PATH_TO_ROOT . url('/media/media_action.php?del=' . $row['id'] . '&amp;token=' . AppContext::get_session()->get_token())
			)));
		}
		$result->dispose();
	}

	private function get_category()
	{
		if ($this->category === null)
		{
			$id = AppContext::get_request()->get_getint('cat', 0);
			if (!empty($id))
			{
				try {
					$this->category = CategoriesService::get_categories_manager('media')->get_categories_cache()->get_category($id);
				} catch (CategoryNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->category = CategoriesService::get_categories_manager('media')->get_categories_cache()->get_category(Category::ROOT_CATEGORY);
			}
		}
		return $this->category;
	}

	private function check_authorizations()
	{
		$id_cat = $this->get_category()->get_id();
		if (!CategoriesAuthorizationsService::check_authorizations($id_cat)->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response()
	{
		$page = AppContext::get_request()->get_getint('p', 1);
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();

		if ($this->get_category()->get_id() != Category::ROOT_CATEGORY)
			$graphical_environment->set_page_title($this->get_category()->get_name(), $this->lang['media.module.title'], $page);
		else
			$graphical_environment->set_page_title($this->lang['media.module.title'], '', $page);

		$description = $this->get_category()->get_description();
		if (empty($description))
			$description = StringVars::replace_vars($this->lang['media.seo.description.root'], array('site' => GeneralConfig::load()->get_site_name())) . ($this->get_category()->get_id() != Category::ROOT_CATEGORY ? ' ' . LangLoader::get_message('category.category', 'category-lang') . ' ' . $this->get_category()->get_name() : '');
		$graphical_environment->get_seo_meta_data()->set_description($description, $page);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(MediaUrlBuilder::display_category($this->get_category()->get_id(), $this->get_category()->get_rewrited_name(), $page));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['media.module.title'], MediaUrlBuilder::home());

		$categories = array_reverse(CategoriesService::get_categories_manager('media')->get_parents($this->get_category()->get_id(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), MediaUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name(), ($category->get_id() == $this->get_category()->get_id() ? $page : 1)));
		}

		return $response;
	}

	public static function get_view()
	{
		$object = new self();
		$object->check_authorizations();
		$object->build_view();
		return $object->view;
	}
}
?>
