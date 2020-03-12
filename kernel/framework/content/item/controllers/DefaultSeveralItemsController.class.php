<?php
/**
 * @package     Content
 * @subpackage  Item\controllers
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 03 12
 * @since       PHPBoost 5.3 - 2020 01 22
*/

class DefaultSeveralItemsController extends AbstractItemController
{
	protected $sort_field;
	protected $sort_mode;
	protected $page;
	protected $subcategories_page;

	protected $sql_condition;
	protected $sql_parameters = array();

	protected $page_title;
	protected $page_description;
	protected $current_url;
	protected $pagination_url;
	protected $url_without_sorting_parameters;

	protected $category;
	protected $keyword;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init($request);
		$this->check_authorizations();
		$this->build_view();

		return $this->generate_response();
	}

	protected function init(HTTPRequestCustom $request)
	{
		$requested_sort_field = $request->get_getstring('field', '');
		$requested_sort_mode = $request->get_getstring('sort', '');
		
		$this->sort_field = (in_array($requested_sort_field, array_keys(Item::get_sorting_fields_list())) ? $requested_sort_field : $this->config->get_items_default_sort_field());
		$this->sort_mode = (in_array(TextHelper::strtoupper($requested_sort_mode), array(Item::ASC, Item::DESC)) ? $requested_sort_mode : $this->config->get_items_default_sort_mode());
		$this->page = $request->get_getint('page', 1);
		$this->subcategories_page = $request->get_getint('subcategories_page', 1);

		$now = new Date();
		$this->sql_parameters['timestamp_now'] = $now->get_timestamp();
		
		if (TextHelper::strstr($request->get_current_url(), '/tag/'))
		{
			$this->sql_condition = 'WHERE keywords_relations.id_keyword = :id_keyword
			AND id_category IN :authorized_categories
			AND (published = ' . Item::PUBLISHED . (self::get_module()->get_configuration()->feature_is_enabled('deferred_publication') ? ' OR (published = ' . Item::DEFERRED_PUBLICATION . ' AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0))' : '') . ')';
			
			$this->sql_parameters['id_keyword'] = $this->get_keyword()->get_id();
			$this->sql_parameters['authorized_categories'] = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, $this->config->get_summary_displayed_to_guests());
			
			$this->page_title = $this->get_keyword()->get_name();
			$this->page_description = StringVars::replace_vars($this->items_lang['items.seo.description.tag'], array('subject' => $this->get_keyword()->get_name()));
			$this->current_url = ItemsUrlBuilder::display_tag($this->get_keyword()->get_rewrited_name(), self::$module_id, $requested_sort_field, $requested_sort_mode, $this->page);
			$this->pagination_url = ItemsUrlBuilder::display_tag($this->get_keyword()->get_rewrited_name(), self::$module_id, $requested_sort_field, $requested_sort_mode, '%d');
			$this->url_without_sorting_parameters = ItemsUrlBuilder::display_tag($this->get_keyword()->get_rewrited_name(), self::$module_id);
		}
		else if (TextHelper::strstr($request->get_current_url(), '/pending/'))
		{
			$this->sql_condition = 'WHERE id_category IN :authorized_categories
			' . (!CategoriesAuthorizationsService::check_authorizations()->moderation() ? ' AND author_user_id = :user_id' : '') . '
			AND (published = ' . Item::NOT_PUBLISHED . (self::get_module()->get_configuration()->feature_is_enabled('deferred_publication') ? ' OR (published = ' . Item::DEFERRED_PUBLICATION . ' AND (publishing_start_date > :timestamp_now OR (publishing_end_date != 0 AND publishing_end_date < :timestamp_now)))' : '') . ')';
			
			$this->sql_parameters['user_id'] = AppContext::get_current_user()->get_id();
			$this->sql_parameters['authorized_categories'] = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, $this->config->get_summary_displayed_to_guests());
			
			$this->page_title = $this->items_lang['items.pending'];
			$this->page_description = $this->items_lang['items.seo.description.pending'];
			$this->current_url = ItemsUrlBuilder::display_pending(self::$module_id, $requested_sort_field, $requested_sort_mode, $this->page);
			$this->pagination_url = ItemsUrlBuilder::display_pending(self::$module_id, $requested_sort_field, $requested_sort_mode, '%d');
			$this->url_without_sorting_parameters = ItemsUrlBuilder::display_pending(self::$module_id);
			
			$this->view->put('C_PENDING', true);
		}
		else
		{
			$this->sql_condition = 'WHERE id_category = :id_category
			AND (published = ' . Item::PUBLISHED . (self::get_module()->get_configuration()->feature_is_enabled('deferred_publication') ? ' OR (published = ' . Item::DEFERRED_PUBLICATION . ' AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0))' : '') . ')';
			
			$this->sql_parameters['id_category'] = $this->get_category()->get_id();
			
			$this->page_title = $this->category->get_id() != Category::ROOT_CATEGORY ? $this->category->get_name() : self::get_module()->get_configuration()->get_name();
			$this->page_description = method_exists($this->category, 'get_description') ? $this->category->get_description() : '';
			if (!$this->page_description)
				$this->page_description = StringVars::replace_vars($this->items_lang['items.seo.description.root'], array('site' => GeneralConfig::load()->get_site_name())) . ($this->category->get_id() != Category::ROOT_CATEGORY ? ' ' . LangLoader::get_message('category', 'categories-common') . ' ' . $this->category->get_name() : '');
			$this->current_url = ItemsUrlBuilder::display_category($this->category->get_id(), $this->category->get_rewrited_name(), self::$module_id, $requested_sort_field, $requested_sort_mode, $this->page);
			$this->pagination_url = ItemsUrlBuilder::display_category($this->category->get_id(), $this->category->get_rewrited_name(), self::$module_id, $requested_sort_field, $requested_sort_mode, '%d', $this->subcategories_page);
			$this->url_without_sorting_parameters = ItemsUrlBuilder::display_category($this->category->get_id(), $this->category->get_rewrited_name(), self::$module_id);
			
			$this->view->put_all(array(
				'C_SYNDICATION' => true,
				'U_SYNDICATION' => SyndicationUrlBuilder::rss(self::$module_id, $this->get_category()->get_id())->rel()
			));
			
			$this->build_categories_listing_view();
		}
	}

	protected function get_category()
	{
		if ($this->category === null)
		{
			$id = AppContext::get_request()->get_getstring('id_category', Category::ROOT_CATEGORY);
			try {
				$this->category = CategoriesService::get_categories_manager(self::get_module()->get_id())->get_categories_cache()->get_category($id);
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
				$this->keyword = KeywordsService::get_keywords_manager()->get_keyword('WHERE rewrited_name=:rewrited_name', array('rewrited_name' => AppContext::get_request()->get_getstring('tag', '')));
			} catch (RowNotFoundException $e) {
				$this->display_unexisting_page();
			}
		}
		return $this->keyword;
	}

	protected function build_view()
	{
		$pagination = $this->get_pagination();
		$sorting_fields_list = Item::get_sorting_fields_list();
		$sort_field = $sorting_fields_list[$this->sort_field];
		$items = self::get_items_manager()->get_items($this->sql_condition, $this->sql_parameters, $pagination->get_number_items_per_page(), $pagination->get_display_from(), $sort_field['database_field'], TextHelper::strtoupper($this->sort_mode), $this->keyword !== null);
		
		$this->view->put_all(array(
			'C_ITEMS'         => !empty($items),
			'C_SEVERAL_ITEMS' => count($items) > 1,
			'C_PAGINATION'    => $pagination->has_several_pages(),
			'PAGINATION'      => $pagination->display(),
			'CATEGORY_NAME'   => $this->keyword !== null ? $this->get_keyword()->get_name() : ($this->category !== null ? $this->get_category()->get_name() : ''),
			'SORTING_FORM'    => $this->build_sorting_form()
		));
		
		foreach ($items as $item)
		{
			$this->view->assign_block_vars('items', $item->get_template_vars());
		}
	}

	protected function build_sorting_form()
	{
		$form = new HTMLForm(__CLASS__, '', false);
		$form->set_css_class('options');

		$fieldset = new FormFieldsetHorizontal('sorting',
			array(
			'description' => LangLoader::get_message('sort_by', 'common'),
			'css_class' => 'grouped-inputs'
			)
		);
		$form->add_fieldset($fieldset);

		$item_class_name = self::get_module()->get_configuration()->get_item_name();
		$select_change_redirect = 'document.location = "' . $this->url_without_sorting_parameters->rel() . '" + HTMLForms.getField("sort_field").getValue() + "/" + HTMLForms.getField("sort_mode").getValue();';
		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_field', '', $this->sort_field, $item_class_name::get_sorting_field_options(),
			array(
				'select_to_list' => true, 
				'events'         => array('change' => $select_change_redirect)
			)
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_mode', '', $this->sort_mode, $item_class_name::get_sorting_mode_options(),
			array(
				'select_to_list' => true, 
				'events'         => array('change' => $select_change_redirect)
			)
		));

		return $form->display();
	}

	protected function build_categories_listing_view()
	{
		$subcategories = CategoriesService::get_categories_manager()->get_categories_cache()->get_children($this->get_category()->get_id(), CategoriesService::get_authorized_categories($this->get_category()->get_id(), $this->config->get_summary_displayed_to_guests()));
		$subcategories_pagination = $this->get_subcategories_pagination(count($subcategories));

		$displayed_categories_number = 0;
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
					'CATEGORY_ID'     => $category->get_id(),
					'CATEGORY_NAME'   => $category->get_name(),
					'ITEMS_NUMBER'    => $category->get_elements_number(),
					'U_CATEGORY'      => ItemsUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name(), self::$module_id)->rel(),
				)));
			}
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
			'C_CATEGORY'                 => true,
			'C_ROOT_CATEGORY'            => $this->get_category()->get_id() == Category::ROOT_CATEGORY,
			'C_HIDE_NO_ITEM_MESSAGE'     => $this->get_category()->get_id() == Category::ROOT_CATEGORY && ($displayed_categories_number != 0 || !empty($category_description)),
			'C_SUB_CATEGORIES'           => $displayed_categories_number > 0,
			'C_SUBCATEGORIES_PAGINATION' => $subcategories_pagination->has_several_pages(),
			'CATEGORY_ID'                => $this->get_category()->get_id(),
			'CATEGORY_NAME'              => $this->get_category()->get_name(),
			'SUBCATEGORIES_PAGINATION'   => $subcategories_pagination->display(),
			'U_EDIT_CATEGORY'            => $this->get_category()->get_id() == Category::ROOT_CATEGORY ? ModulesUrlBuilder::configuration()->rel() : CategoriesUrlBuilder::edit_category($this->get_category()->get_id())->rel(),
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

	protected function get_template_to_use()
	{
		return new FileTemplate('framework/content/items/ModuleSeveralItemsController.tpl');
	}

	protected function check_authorizations()
	{
		$authorizations = self::get_module()->get_configuration()->has_categories() ? CategoriesAuthorizationsService::check_authorizations($this->get_category()->get_id(), self::$module_id) : ItemsAuthorizationsService::check_authorizations(self::$module_id);
		
		if ($this->category !== null)
			return ((AppContext::get_current_user()->is_guest() && $this->config->get_summary_displayed_to_guests() && Authorizations::check_auth(RANK_TYPE, User::MEMBER_LEVEL, $this->get_category()->get_authorizations(), Category::READ_AUTHORIZATIONS)) || $authorizations->read()) ? true : $this->display_user_not_authorized_page();
		else if ($this->keyword !== null)
			return $authorizations->read() ? true : $this->display_user_not_authorized_page();
		else
			return ($authorizations->write() || $authorizations->contribution() || $authorizations->moderation()) ? true : $this->display_user_not_authorized_page();
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->page_title, ($this->category !== null && $this->category->get_id() == Category::ROOT_CATEGORY ? '' : self::get_module()->get_configuration()->get_name()), $this->page);
		$graphical_environment->get_seo_meta_data()->set_description($this->page_description, $this->page);
		$graphical_environment->get_seo_meta_data()->set_canonical_url($this->current_url);

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add(self::get_module()->get_configuration()->get_name(), ModulesUrlBuilder::home());

		if ($this->category !== null)
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
		else
			$breadcrumb->add($this->page_title, $this->current_url);

		return $response;
	}

	public static function get_view($module_id = '')
	{
		$object = new self($module_id);
		$object->check_authorizations();
		$object->init(AppContext::get_request());
		$object->build_view();
		return $object->view;
	}
}
?>
