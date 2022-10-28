<?php
/**
 * @package     Content
 * @subpackage  Item\controllers
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 10 28
 * @since       PHPBoost 6.0 - 2020 01 22
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor xela <xela@phpboost.com>
*/

class DefaultSeveralItemsController extends AbstractItemController
{
	protected $sort_field;
	protected $sort_mode;
	protected $page;
	protected $subcategories_page;
	protected $summary_displayed_to_guests;

	protected $sql_condition;
	protected $sql_parameters = array();

	protected $page_title;
	protected $customized_page_title;
	protected $page_description;
	protected $current_url;
	protected $pagination_url;
	protected $url_without_sorting_parameters;
	protected $display_published_items_list = false;

	protected $category;
	protected $keyword;
	protected $member;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->check_authorizations();
		$this->build_view();

		return $this->generate_response();
	}

	protected function init()
	{
		$requested_sort_field = $this->request->get_getstring('field', '');
		$requested_sort_mode = $this->request->get_getstring('sort', '');

		$this->sort_field = (in_array($requested_sort_field, array_keys($this->module_item->get_sorting_fields_list())) ? $requested_sort_field : $this->config->get_items_default_sort_field());
		$this->sort_mode = (in_array(TextHelper::strtoupper($requested_sort_mode), array(Item::ASC, Item::DESC)) ? $requested_sort_mode : $this->config->get_items_default_sort_mode());
		$this->page = $this->request->get_getint('page', 1);
		$this->subcategories_page = $this->request->get_getint('subcategories_page', 1);
		$this->summary_displayed_to_guests = ($this->module_item->content_field_enabled() && $this->module_item->summary_field_enabled() ? $this->config->get_summary_displayed_to_guests() : true);

		$now = new Date();
		$this->sql_parameters['timestamp_now'] = $now->get_timestamp();

		if (TextHelper::strstr($this->request->get_current_url(), '/tag/'))
		{
			if (self::get_module_configuration()->has_categories())
			{
				$this->sql_condition = 'WHERE keywords_relations.id_keyword = :id_keyword
				AND id_category IN :authorized_categories
				AND (published = ' . Item::PUBLISHED . (self::get_module_configuration()->feature_is_enabled('deferred_publication') ? ' OR (published = ' . Item::DEFERRED_PUBLICATION . ' AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0))' : '') . ')';

				$this->sql_parameters['authorized_categories'] = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, $this->summary_displayed_to_guests);
			}
			else
			{
				$this->sql_condition = 'WHERE keywords_relations.id_keyword = :id_keyword
				AND (published = ' . Item::PUBLISHED . (self::get_module_configuration()->feature_is_enabled('deferred_publication') ? ' OR (published = ' . Item::DEFERRED_PUBLICATION . ' AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0))' : '') . ')';
			}

			$this->sql_parameters['id_keyword'] = $this->get_keyword()->get_id();

			$this->page_title = $this->get_keyword()->get_name();
			$this->page_description = StringVars::replace_vars($this->lang['items.seo.description.tag'], array('subject' => $this->get_keyword()->get_name()));
			$this->current_url = ItemsUrlBuilder::display_tag($this->get_keyword()->get_rewrited_name(), self::$module_id, $requested_sort_field, $requested_sort_mode, $this->page);
			$this->pagination_url = ItemsUrlBuilder::display_tag($this->get_keyword()->get_rewrited_name(), self::$module_id, $this->sort_field, $this->sort_mode, '%d');
			$this->url_without_sorting_parameters = ItemsUrlBuilder::display_tag($this->get_keyword()->get_rewrited_name(), self::$module_id);

			$this->view->put('C_KEYWORD_ITEMS_LIST', true);
		}
		else if (TextHelper::strstr($this->request->get_current_url(), '/member/'))
		{
			if (self::get_module_configuration()->has_categories())
			{
				$this->sql_condition = 'WHERE id_category IN :authorized_categories
				AND author_user_id = :user_id
				AND (published = ' . Item::PUBLISHED . (self::get_module_configuration()->feature_is_enabled('deferred_publication') ? ' OR (published = ' . Item::DEFERRED_PUBLICATION . ' AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0))' : '') . ')';

				$this->sql_parameters['authorized_categories'] = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, $this->summary_displayed_to_guests);
			}
			else
			{
				$this->sql_condition = 'WHERE author_user_id = :user_id
				AND (published = ' . Item::PUBLISHED . (self::get_module_configuration()->feature_is_enabled('deferred_publication') ? ' OR (published = ' . Item::DEFERRED_PUBLICATION . ' AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0))' : '') . ')';
			}

			$this->sql_parameters['user_id'] = $this->get_member()->get_id();

			$this->page_title = $this->is_current_member_displayed() ? $this->lang['my.items'] : $this->lang['member.items'] . ' ' . $this->get_member()->get_display_name();
			$this->page_description = StringVars::replace_vars($this->lang['items.seo.description.member'], array('author' => $this->get_member()->get_display_name()));
			$this->current_url = ItemsUrlBuilder::display_member_items($this->get_member()->get_id(), self::$module_id, $requested_sort_field, $requested_sort_mode, $this->page);
			$this->pagination_url = ItemsUrlBuilder::display_member_items($this->get_member()->get_id(), self::$module_id, $this->sort_field, $this->sort_mode, '%d');
			$this->url_without_sorting_parameters = ItemsUrlBuilder::display_member_items($this->get_member()->get_id(), self::$module_id);

			$this->view->put_all(array(
				'C_MEMBER_ITEMS' => true,
				'C_MY_ITEMS'     => $this->is_current_member_displayed(),
				'MEMBER_NAME'    => $this->get_member()->get_display_name()
			));
		}
		else if (TextHelper::strstr($this->request->get_current_url(), '/pending/'))
		{
			if (self::get_module_configuration()->has_categories())
			{
				$this->sql_condition = 'WHERE id_category IN :authorized_categories
				' . (!CategoriesAuthorizationsService::check_authorizations()->moderation() ? ' AND author_user_id = :user_id' : '') . '
				AND (published = ' . Item::NOT_PUBLISHED . (self::get_module_configuration()->feature_is_enabled('deferred_publication') ? ' OR (published = ' . Item::DEFERRED_PUBLICATION . ' AND (publishing_start_date > :timestamp_now OR (publishing_end_date != 0 AND publishing_end_date < :timestamp_now)))' : '') . ')';

				$this->sql_parameters['authorized_categories'] = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, $this->summary_displayed_to_guests);
			}
			else
			{
				$this->sql_condition = 'WHERE ' . (!ItemsAuthorizationsService::check_authorizations()->moderation() ? 'author_user_id = :user_id AND ' : '') . '
				(published = ' . Item::NOT_PUBLISHED . (self::get_module_configuration()->feature_is_enabled('deferred_publication') ? ' OR (published = ' . Item::DEFERRED_PUBLICATION . ' AND (publishing_start_date > :timestamp_now OR (publishing_end_date != 0 AND publishing_end_date < :timestamp_now)))' : '') . ')';
			}

			$this->sql_parameters['user_id'] = AppContext::get_current_user()->get_id();

			$this->page_title = $this->lang['pending.items'];
			$this->page_description = $this->lang['items.seo.description.pending'];
			$this->current_url = ItemsUrlBuilder::display_pending(self::$module_id, $requested_sort_field, $requested_sort_mode, $this->page);
			$this->pagination_url = ItemsUrlBuilder::display_pending(self::$module_id, $this->sort_field, $this->sort_mode, '%d');
			$this->url_without_sorting_parameters = ItemsUrlBuilder::display_pending(self::$module_id);

			$this->view->put('C_PENDING', true);
		}
		else
		{
			$this->display_published_items_list = true;

			if (self::get_module_configuration()->has_categories())
			{
				$this->sql_condition = 'WHERE' . ($this->module_item->sub_categories_displayed() || $this->get_category()->get_id() != Category::ROOT_CATEGORY ? ' id_category = :id_category
				AND' : '') . ' (published = ' . Item::PUBLISHED . (self::get_module_configuration()->feature_is_enabled('deferred_publication') ? ' OR (published = ' . Item::DEFERRED_PUBLICATION . ' AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0))' : '') . ')';

				$this->sql_parameters['id_category'] = $this->get_category()->get_id();

				$this->page_title = $this->category->get_id() != Category::ROOT_CATEGORY ? $this->category->get_name() : self::get_module_configuration()->get_name();
				$this->page_description = method_exists($this->category, 'get_description') ? $this->category->get_description() : '';
				if (!$this->page_description)
					$this->page_description = StringVars::replace_vars($this->lang['items.seo.description.root'], array('site' => GeneralConfig::load()->get_site_name())) . ($this->category->get_id() != Category::ROOT_CATEGORY ? ' ' . $this->lang['category.category'] . ' ' . $this->category->get_name() : '');
				$this->current_url = ItemsUrlBuilder::display_category($this->category->get_id(), $this->category->get_rewrited_name(), self::$module_id, $requested_sort_field, $requested_sort_mode, $this->page);
				$this->pagination_url = ItemsUrlBuilder::display_category($this->category->get_id(), $this->category->get_rewrited_name(), self::$module_id, $this->sort_field, $this->sort_mode, '%d', $this->subcategories_page);
				$this->url_without_sorting_parameters = ItemsUrlBuilder::display_category($this->category->get_id(), $this->category->get_rewrited_name(), self::$module_id, true);

				$this->view->put_all(array(
					'C_ENABLED_CATEGORIES' => !$this->module_item->sub_categories_displayed(),
					'C_SYNDICATION'        => true,
					'U_SYNDICATION'        => SyndicationUrlBuilder::rss(self::$module_id, $this->get_category()->get_id())->rel()
				));

				$this->build_categories_listing_view();
			}
			else
			{
				$this->sql_condition = 'WHERE (published = ' . Item::PUBLISHED . (self::get_module_configuration()->feature_is_enabled('deferred_publication') ? ' OR (published = ' . Item::DEFERRED_PUBLICATION . ' AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0))' : '') . ')';

				$this->page_title = self::get_module_configuration()->get_name();
				$this->page_description = StringVars::replace_vars($this->lang['items.seo.description.root'], array('site' => GeneralConfig::load()->get_site_name()));
				$this->current_url = ItemsUrlBuilder::display_category(Category::ROOT_CATEGORY, 'root', self::$module_id, $requested_sort_field, $requested_sort_mode, $this->page);
				$this->pagination_url = ItemsUrlBuilder::display_category(Category::ROOT_CATEGORY, 'root', self::$module_id, $this->sort_field, $this->sort_mode, '%d');
				$this->url_without_sorting_parameters = ItemsUrlBuilder::display_category(Category::ROOT_CATEGORY, 'root', self::$module_id, true);
			}
		}
	}

	protected function get_category()
	{
		if ($this->category === null)
		{
			$id = $this->request->get_getstring('id_category', Category::ROOT_CATEGORY);
			try {
				$this->category = CategoriesService::get_categories_manager(self::$module->get_id())->get_categories_cache()->get_category($id);
			} catch (CategoryNotFoundException $e) {
				$this->display_unexisting_page();
			}
		}
		return $this->category;
	}

	protected function get_keyword()
	{
		if ($this->keyword === null)
		{
			try {
				$this->keyword = KeywordsService::get_keywords_manager()->get_keyword('WHERE rewrited_name=:rewrited_name', array('rewrited_name' => $this->request->get_getstring('tag', '')));
			} catch (RowNotFoundException $e) {
				$this->display_unexisting_page();
			}
		}
		return $this->keyword;
	}

	protected function get_member()
	{
		if ($this->member === null)
		{
			$this->member = UserService::get_user($this->request->get_getint('user_id', AppContext::get_current_user()->get_id()));
			if (!$this->member)
				$this->display_unexisting_page();
		}
		return $this->member;
	}

	protected function is_current_member_displayed()
	{
		return $this->member && $this->member->get_id() == AppContext::get_current_user()->get_id();
	}

	protected function build_view()
	{
		$pagination = $this->get_pagination();
		$sorting_fields_list = $this->module_item->get_sorting_fields_list();
		$sort_field = $sorting_fields_list[$this->sort_field];
		$items = self::get_items_manager()->get_items($this->sql_condition, $this->sql_parameters, $pagination->get_number_items_per_page(), $pagination->get_display_from(), $sort_field['database_field'], TextHelper::strtoupper($this->sort_mode), $this->keyword !== null);
		$controls_displayed = false;

		foreach ($items as $item)
		{
			$this->view->assign_block_vars('items', $item->get_template_vars());

			if ($item->is_authorized_to_edit() || $item->is_authorized_to_delete())
				$controls_displayed = true;

			if (self::get_module_configuration()->feature_is_enabled('keywords'))
			{
				foreach ($item->get_keywords() as $keyword)
				{
					$this->view->assign_block_vars('items.keywords', $item->get_template_keyword_vars($keyword));
				}
			}

			if (self::get_module_configuration()->feature_is_enabled('sources'))
			{
				foreach ($item->get_sources() as $name => $url)
				{
					$this->view->assign_block_vars('items.sources', $item->get_template_source_vars($name));
				}
			}
		}

		$this->view->put_all(array(
			'C_ITEMS'         => !empty($items),
			'C_CONTROLS'      => $controls_displayed,
			'C_SEVERAL_ITEMS' => count($items) > 1,
			'C_PAGINATION'    => $pagination->has_several_pages(),

			'PAGINATION'    => $pagination->display(),
			'CATEGORY_NAME' => $this->keyword !== null ? $this->get_keyword()->get_name() : ($this->category !== null ? $this->get_category()->get_name() : ''),
			'SORTING_FORM'  => $this->build_sorting_form()
		));
	}

	protected function build_sorting_form()
	{
		$form = new HTMLForm(self::$module_id . '_sorting_form', '', false);
		$form->set_css_class('options');

		$fieldset = new FormFieldsetHorizontal('sorting',
			array(
			'description' => $this->lang['common.sort.by'],
			'css_class' => 'grouped-inputs'
			)
		);
		$form->add_fieldset($fieldset);

		$fields_list = $this->module_item->get_sorting_field_options();
		if (TextHelper::strstr($this->request->get_current_url(), '/member/'))
			unset($fields_list['author']);

		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_field', '', $this->sort_field, $fields_list,
			array(
				'select_to_list' => true,
				'events'         => array('change' => 'document.location = "' . $this->url_without_sorting_parameters->rel() . '" + HTMLForms.getField("sort_field").getValue() + "/' . $this->sort_mode . '"' . ($this->page > 1 ? ' + "/' . $this->page . '"' : '') . ';')
			)
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_mode', '', $this->sort_mode, $this->module_item->get_sorting_mode_options(),
			array(
				'select_to_list' => true,
				'events'         => array('change' => 'document.location = "' . $this->url_without_sorting_parameters->rel() . '" + HTMLForms.getField("sort_field").getValue() + "/" + HTMLForms.getField("sort_mode").getValue()' . ($this->page > 1 ? ' + "/' . $this->page . '"' : '') . ';')
			)
		));

		return $form->display();
	}

	protected function build_categories_listing_view()
	{
		$displayed_categories_number = 0;
		if ($this->module_item->sub_categories_displayed())
		{
			$subcategories = CategoriesService::get_categories_manager()->get_categories_cache()->get_children($this->get_category()->get_id(), CategoriesService::get_authorized_categories($this->get_category()->get_id(), $this->summary_displayed_to_guests));
			$subcategories_pagination = $this->get_subcategories_pagination(count($subcategories));

			foreach ($subcategories as $id => $category)
			{
				$displayed_categories_number++;

				if ($displayed_categories_number > $subcategories_pagination->get_display_from() && $displayed_categories_number <= ($subcategories_pagination->get_display_from() + $subcategories_pagination->get_number_items_per_page()))
				{
					$thumbnail_properties = array();
					if (method_exists($category, 'get_thumbnail'))
					{
						$category_thumbnail = $category->get_thumbnail()->rel();
						$thumbnail_properties = array(
							'C_CATEGORY_THUMBNAIL' => !empty($category_thumbnail),
							'U_CATEGORY_THUMBNAIL' => $category_thumbnail
						);
					}

					$this->view->assign_block_vars('sub_categories_list', array_merge($thumbnail_properties, array(
						'C_SEVERAL_ITEMS' => $category->get_elements_number() > 1,

						'CATEGORY_ID'        => $category->get_id(),
						'CATEGORY_NAME'      => $category->get_name(),
						'CATEGORY_PARENT_ID' => $category->get_id_parent(),
						'CATEGORY_SUB_ORDER' => $category->get_order(),
						'ITEMS_NUMBER'       => $category->get_elements_number(),

						'U_CATEGORY' => ItemsUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name(), self::$module_id)->rel(),
					)));
				}
			}

			$this->view->put_all(array(
				'C_SUB_CATEGORIES'           => $displayed_categories_number > 0,
				'C_SUBCATEGORIES_PAGINATION' => $subcategories_pagination->has_several_pages(),
				'SUBCATEGORIES_PAGINATION'   => $subcategories_pagination->display()
			));
		}

		$category_description = '';
		if (method_exists($this->get_category(), 'get_description'))
		{
			$category_description = FormatingHelper::second_parse($this->get_category()->get_description());
			$this->view->put_all(array(
				'C_CATEGORY_DESCRIPTION' => !empty($category_description),
				'CATEGORY_DESCRIPTION'   => $category_description
			));
		}

		if (method_exists($this->get_category(), 'get_thumbnail'))
		{
			$category_thumbnail = $this->get_category()->get_thumbnail()->rel();
			$this->view->put_all(array(
				'C_CATEGORY_THUMBNAIL' => !empty($category_thumbnail),
				'U_CATEGORY_THUMBNAIL' => $category_thumbnail
			));
		}

		$this->view->put_all(array(
			'C_CATEGORY'             => true,
			'C_ROOT_CATEGORY'        => $this->get_category()->get_id() == Category::ROOT_CATEGORY,
			'C_HIDE_NO_ITEM_MESSAGE' => $this->get_category()->get_id() == Category::ROOT_CATEGORY && ($displayed_categories_number != 0 || !empty($category_description)),

			'CATEGORY_ID'        => $this->get_category()->get_id(),
			'CATEGORY_NAME'      => $this->get_category()->get_name(),
			'CATEGORY_PARENT_ID' => $this->get_category()->get_id_parent(),
			'CATEGORY_SUB_ORDER' => $this->get_category()->get_order(),

			'U_EDIT_CATEGORY' => $this->get_category()->get_id() == Category::ROOT_CATEGORY ? ModulesUrlBuilder::configuration()->rel() : CategoriesUrlBuilder::edit($this->get_category()->get_id(), self::$module_id)->rel()
		));
	}

	protected function get_pagination()
	{
		if ($this->keyword)
		{
			$result = self::get_items_manager()->count_items_having_keyword($this->sql_condition, $this->sql_parameters);
			$items_number = $result['items_number'];
		}
		else
			$items_number = self::get_items_manager()->count($this->sql_condition, $this->sql_parameters);

		$pagination = new ModulePagination($this->page, $items_number, $this->config->get_items_per_page());
		$pagination->set_url($this->pagination_url);

		if ($pagination->current_page_is_empty() && $this->page > 1)
			$this->display_unexisting_page();

		return $pagination;
	}

	protected function get_subcategories_pagination($subcategories_number)
	{
		$pagination = new ModulePagination($this->subcategories_page, $subcategories_number, $this->config->get_categories_per_page());
		$pagination->set_url(ItemsUrlBuilder::display_category($this->category->get_id(), $this->category->get_rewrited_name(), self::$module_id, $this->sort_field, $this->sort_mode, $this->page, '%d'));

		if ($pagination->current_page_is_empty() && $this->subcategories_page > 1)
			$this->display_unexisting_page();

		return $pagination;
	}

	protected function get_template_url()
	{
		return 'framework/content/items/ModuleSeveralItemsController.tpl';
	}

	protected function check_authorizations()
	{
		$authorizations = self::get_module_configuration()->has_categories() ? CategoriesAuthorizationsService::check_authorizations($this->get_category()->get_id(), self::$module->get_id()) : ItemsAuthorizationsService::check_authorizations(self::$module->get_id());

		if ($this->category !== null)
			return ((AppContext::get_current_user()->is_guest() && $this->summary_displayed_to_guests && Authorizations::check_auth(RANK_TYPE, User::MEMBER_LEVEL, $this->get_category()->get_authorizations(), Category::READ_AUTHORIZATIONS)) || $authorizations->read()) ? true : $this->display_user_not_authorized_page();
		else if ($this->keyword !== null)
			return $authorizations->read() ? true : $this->display_user_not_authorized_page();
		else
			return ($authorizations->write() || $authorizations->contribution() || $authorizations->moderation()) ? true : $this->display_user_not_authorized_page();
	}

	protected function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->customized_page_title ? $this->customized_page_title : $this->page_title, (!self::get_module_configuration()->has_categories() || $this->category !== null && $this->category->get_id() == Category::ROOT_CATEGORY ? '' : self::get_module_configuration()->get_name()), $this->page);
		if ($this->page_description)
			$graphical_environment->get_seo_meta_data()->set_description($this->page_description, $this->page);
		$graphical_environment->get_seo_meta_data()->set_canonical_url($this->current_url);

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add(self::get_module_configuration()->get_name(), ModulesUrlBuilder::home());

		if (self::get_module_configuration()->has_categories() && $this->category && $this->category->get_id() != Category::ROOT_CATEGORY)
		{
			$sort_field = ($this->sort_field != $this->config->get_items_default_sort_field() || $this->sort_mode != $this->config->get_items_default_sort_mode()) ? $this->sort_field : '';
			$sort_mode = $this->sort_mode != $this->config->get_items_default_sort_mode() ? $this->sort_mode : '';

			$categories = array_reverse(CategoriesService::get_categories_manager()->get_parents($this->category->get_id(), true));
			foreach ($categories as $id => $category)
			{
				if ($category->get_id() != Category::ROOT_CATEGORY)
					$breadcrumb->add($category->get_name(), ItemsUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name(), self::$module_id, $sort_field, $sort_mode, ($category->get_id() == $this->category->get_id() ? $this->page : 1)));
			}
		}
		else if (!$this->display_published_items_list)
			$breadcrumb->add($this->page_title, $this->current_url);

		if ($this->customized_page_title)
			$breadcrumb->add($this->customized_page_title, $this->current_url);

		return $response;
	}

	public static function get_view($module_id = '')
	{
		$object = new self($module_id);
		$object->check_authorizations();
		$object->init();
		$object->build_view();
		return $object->view;
	}
}
?>
