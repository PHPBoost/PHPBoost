<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 18
 * @since       PHPBoost 4.1 - 2014 08 21
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class WebDisplayCategoryController extends ModuleController
{
	private $lang;
	private $tpl;
	private $config;
	private $comments_config;
	private $content_management_config;

	private $category;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->check_authorizations();

		$this->build_view($request);

		return $this->generate_response($request);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'web');
		$this->tpl = new FileTemplate('web/WebSeveralItemsController.tpl');
		$this->tpl->add_lang($this->lang);
		$this->config = WebConfig::load();
		$this->comments_config = CommentsConfig::load();
		$this->content_management_config = ContentManagementConfig::load();
	}

	private function build_view(HTTPRequestCustom $request)
	{
		$now = new Date();

		$mode = $request->get_getstring('sort', $this->config->get_items_default_sort_mode());
		$field = $request->get_getstring('field', WebLink::SORT_FIELDS_URL_VALUES[$this->config->get_items_default_sort_field()]);
		$page = $request->get_getint('page', 1);
		$subcategories_page = $request->get_getint('subcategories_page', 1);

		$subcategories = CategoriesService::get_categories_manager()->get_categories_cache()->get_children($this->get_category()->get_id(), CategoriesService::get_authorized_categories($this->get_category()->get_id(), $this->config->are_descriptions_displayed_to_guests()));
		$subcategories_pagination = $this->get_subcategories_pagination(count($subcategories), $this->config->get_categories_per_page(), $field, $mode, $page, $subcategories_page);

		$nbr_cat_displayed = 0;
		foreach ($subcategories as $id => $category)
		{
			$nbr_cat_displayed++;

			if ($nbr_cat_displayed > $subcategories_pagination->get_display_from() && $nbr_cat_displayed <= ($subcategories_pagination->get_display_from() + $subcategories_pagination->get_number_items_per_page()))
			{
				$category_thumbnail = $category->get_thumbnail()->rel();

				$this->tpl->assign_block_vars('sub_categories_list', array(
					'C_CATEGORY_THUMBNAIL' => !empty($category_thumbnail),
					'C_SEVERAL_ITEMS'      => $category->get_elements_number() > 1,
					'CATEGORY_ID'          => $category->get_id(),
					'CATEGORY_NAME'        => $category->get_name(),
					'ITEMS_NUMBER'         => $category->get_elements_number(),
					'U_CATEGORY_THUMBNAIL' => $category_thumbnail,
					'U_CATEGORY'           => WebUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name())->rel()
				));
			}
		}

		$condition = 'WHERE id_category = :id_category
		AND (approbation_type = 1 OR (approbation_type = 2 AND start_date < :timestamp_now AND (end_date > :timestamp_now OR end_date = 0)))';
		$parameters = array(
			'id_category' => $this->get_category()->get_id(),
			'timestamp_now' => $now->get_timestamp()
		);

		$pagination = $this->get_pagination($condition, $parameters, $field, TextHelper::strtolower($mode), $page, $subcategories_page);

		$sort_mode = TextHelper::strtoupper($mode);
		$sort_mode = (in_array($sort_mode, array(WebLink::ASC, WebLink::DESC)) ? $sort_mode : $this->config->get_items_default_sort_mode());

		if (in_array($field, WebLink::SORT_FIELDS_URL_VALUES))
			$sort_field = array_search($field, WebLink::SORT_FIELDS_URL_VALUES);
		else
			$sort_field = $this->config->get_items_default_sort_field();

		$result = PersistenceContext::get_querier()->select('SELECT web.*, member.*, com.number_comments, notes.average_notes, notes.number_notes, note.note
		FROM '. WebSetup::$web_table .' web
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = web.author_user_id
		LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' com ON com.id_in_module = web.id AND com.module_id = \'web\'
		LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = web.id AND notes.module_name = \'web\'
		LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = web.id AND note.module_name = \'web\' AND note.user_id = :user_id
		' . $condition . '
		ORDER BY web.privileged_partner DESC, ' . $sort_field . ' ' . $sort_mode . '
		LIMIT :items_per_page OFFSET :display_from', array_merge($parameters, array(
			'user_id' => AppContext::get_current_user()->get_id(),
			'items_per_page' => $pagination->get_number_items_per_page(),
			'display_from' => $pagination->get_display_from()
		)));

		$category_description = FormatingHelper::second_parse($this->get_category()->get_description());

		$this->tpl->put_all(array(
			'C_ITEMS' => $result->get_rows_count() > 0,
			'C_SEVERAL_ITEMS' => $result->get_rows_count() > 1,
			'C_GRID_VIEW' => $this->config->get_display_type() == WebConfig::GRID_VIEW,
			'C_LIST_VIEW' => $this->config->get_display_type() == WebConfig::LIST_VIEW,
			'C_TABLE_VIEW' => $this->config->get_display_type() == WebConfig::TABLE_VIEW,
			'C_FULL_ITEM_DISPLAY' => $this->config->is_full_item_displayed(),
			'C_CATEGORY_DESCRIPTION' => !empty($category_description),
			'CATEGORIES_PER_ROW' => $this->config->get_categories_per_row(),
			'ITEMS_PER_ROW' => $this->config->get_items_per_row(),
			'C_ENABLED_COMMENTS' => $this->comments_config->module_comments_is_enabled('web'),
			'C_ENABLED_NOTATION' => $this->content_management_config->module_notation_is_enabled('web'),
			'C_MODERATE' => CategoriesAuthorizationsService::check_authorizations($this->get_category()->get_id())->moderation(),
			'C_PAGINATION' => $pagination->has_several_pages(),
			'C_CATEGORY' => true,
			'C_ROOT_CATEGORY' => $this->get_category()->get_id() == Category::ROOT_CATEGORY,
			'C_HIDE_NO_ITEM_MESSAGE' => $this->get_category()->get_id() == Category::ROOT_CATEGORY && ($nbr_cat_displayed != 0 || !empty($category_description)),
			'C_SUB_CATEGORIES' => $nbr_cat_displayed > 0,
			'C_SUBCATEGORIES_PAGINATION' => $subcategories_pagination->has_several_pages(),
			'SUBCATEGORIES_PAGINATION' => $subcategories_pagination->display(),
			'PAGINATION' => $pagination->display(),
			'ID_CAT' => $this->get_category()->get_id(),
			'CATEGORY_NAME' => $this->get_category()->get_name(),
			'U_CATEGORY_THUMBNAIL' => $this->get_category()->get_thumbnail()->rel(),
			'CATEGORY_DESCRIPTION' => $category_description,
			'U_EDIT_CATEGORY' => $this->get_category()->get_id() == Category::ROOT_CATEGORY ? WebUrlBuilder::configuration()->rel() : CategoriesUrlBuilder::edit_category($this->get_category()->get_id())->rel()
		));

		while ($row = $result->fetch())
		{
			$weblink = new WebLink();
			$weblink->set_properties($row);

			$keywords = $weblink->get_keywords();
			$has_keywords = count($keywords) > 0;

			$this->tpl->assign_block_vars('weblinks', array_merge($weblink->get_array_tpl_vars(), array(
				'C_KEYWORDS' => $has_keywords
			)));

			if ($has_keywords)
				$this->build_keywords_view($keywords);
		}
		$result->dispose();
		$this->build_sorting_form($field, TextHelper::strtolower($sort_mode));
	}

	private function build_sorting_form($field, $mode)
	{
		$common_lang = LangLoader::get('common');

		$form = new HTMLForm(__CLASS__, '', false);
		$form->set_css_class('options no-style');

		$fieldset = new FormFieldsetHorizontal('filters', array('description' => $common_lang['sort_by']));
		$form->add_fieldset($fieldset);

		$sort_options = array(
			new FormFieldSelectChoiceOption($common_lang['form.date.creation'], WebLink::SORT_FIELDS_URL_VALUES[WebLink::SORT_DATE]),
			new FormFieldSelectChoiceOption($common_lang['form.name'], WebLink::SORT_FIELDS_URL_VALUES[WebLink::SORT_ALPHABETIC]),
			new FormFieldSelectChoiceOption($this->lang['config.sort_type.visits'], WebLink::SORT_FIELDS_URL_VALUES[WebLink::SORT_NUMBER_VISITS])
		);

		if ($this->comments_config->module_comments_is_enabled('web'))
			$sort_options[] = new FormFieldSelectChoiceOption($common_lang['sort_by.comments.number'], WebLink::SORT_FIELDS_URL_VALUES[WebLink::SORT_NUMBER_COMMENTS]);

		if ($this->content_management_config->module_notation_is_enabled('web'))
			$sort_options[] = new FormFieldSelectChoiceOption($common_lang['sort_by.best.note'], WebLink::SORT_FIELDS_URL_VALUES[WebLink::SORT_NOTATION]);

		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_fields', '', $field, $sort_options,
			array('events' => array('change' => 'document.location = "'. WebUrlBuilder::display_category($this->category->get_id(), $this->category->get_rewrited_name())->rel() .'" + HTMLForms.getField("sort_fields").getValue() + "/" + HTMLForms.getField("sort_mode").getValue();'))
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_mode', '', $mode,
			array(
				new FormFieldSelectChoiceOption($common_lang['sort.asc'], 'asc'),
				new FormFieldSelectChoiceOption($common_lang['sort.desc'], 'desc')
			),
			array('events' => array('change' => 'document.location = "' . WebUrlBuilder::display_category($this->category->get_id(), $this->category->get_rewrited_name())->rel() . '" + HTMLForms.getField("sort_fields").getValue() + "/" + HTMLForms.getField("sort_mode").getValue();'))
		));

		$this->tpl->put('SORT_FORM', $form->display());
	}

	private function get_pagination($condition, $parameters, $field, $mode, $page, $subcategories_page)
	{
		$weblinks_number = WebService::count($condition, $parameters);

		$pagination = new ModulePagination($page, $weblinks_number, (int)WebConfig::load()->get_items_per_page());
		$pagination->set_url(WebUrlBuilder::display_category($this->get_category()->get_id(), $this->get_category()->get_rewrited_name(), $field, $mode, '%d', $subcategories_page));

		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		return $pagination;
	}

	private function get_subcategories_pagination($subcategories_number, $categories_per_page, $field, $mode, $page, $subcategories_page)
	{
		$pagination = new ModulePagination($subcategories_page, $subcategories_number, (int)$categories_per_page);
		$pagination->set_url(WebUrlBuilder::display_category($this->get_category()->get_id(), $this->get_category()->get_rewrited_name(), $field, $mode, $page, '%d'));

		if ($pagination->current_page_is_empty() && $subcategories_page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		return $pagination;
	}

	private function get_category()
	{
		if ($this->category === null)
		{
			$id = AppContext::get_request()->get_getint('id_category', 0);
			if (!empty($id))
			{
				try {
					$this->category = CategoriesService::get_categories_manager()->get_categories_cache()->get_category($id);
				} catch (CategoryNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
   					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->category = CategoriesService::get_categories_manager()->get_categories_cache()->get_category(Category::ROOT_CATEGORY);
			}
		}
		return $this->category;
	}

	private function build_keywords_view($keywords)
	{
		$nbr_keywords = count($keywords);

		$i = 1;
		foreach ($keywords as $keyword)
		{
			$this->tpl->assign_block_vars('weblinks.keywords', array(
				'C_SEPARATOR' => $i < $nbr_keywords,
				'NAME' => $keyword->get_name(),
				'URL' => WebUrlBuilder::display_tag($keyword->get_rewrited_name())->rel(),
			));
			$i++;
		}
	}

	private function check_authorizations()
	{
		if (AppContext::get_current_user()->is_guest())
		{
			if (($this->config->are_descriptions_displayed_to_guests() && (!Authorizations::check_auth(RANK_TYPE, User::MEMBER_LEVEL, $this->get_category()->get_authorizations(), Category::READ_AUTHORIZATIONS) || $this->config->get_display_type() == WebConfig::DISPLAY_ALL_CONTENT)) || (!$this->config->are_descriptions_displayed_to_guests() && !CategoriesAuthorizationsService::check_authorizations($this->get_category()->get_id())->read()))
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			if (!CategoriesAuthorizationsService::check_authorizations($this->get_category()->get_id())->read())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
	}

	private function generate_response(HTTPRequestCustom $request)
	{
		$sort_field = $request->get_getstring('field', WebLink::SORT_FIELDS_URL_VALUES[$this->config->get_items_default_sort_field()]);
		$sort_mode = $request->get_getstring('sort', $this->config->get_items_default_sort_mode());
		$page = $request->get_getint('page', 1);
		$response = new SiteDisplayResponse($this->tpl);

		$graphical_environment = $response->get_graphical_environment();

		if ($this->get_category()->get_id() != Category::ROOT_CATEGORY)
			$graphical_environment->set_page_title($this->get_category()->get_name(), $this->lang['module_title'], $page);
		else
			$graphical_environment->set_page_title($this->lang['module_title'], '', $page);

		$description = $this->get_category()->get_description();
		if (empty($description))
			$description = StringVars::replace_vars($this->lang['web.seo.description.root'], array('site' => GeneralConfig::load()->get_site_name())) . ($this->get_category()->get_id() != Category::ROOT_CATEGORY ? ' ' . LangLoader::get_message('category', 'categories-common') . ' ' . $this->get_category()->get_name() : '');
		$graphical_environment->get_seo_meta_data()->set_description($description, $page);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(WebUrlBuilder::display_category($this->get_category()->get_id(), $this->get_category()->get_rewrited_name(), $sort_field, $sort_mode, $page));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'], WebUrlBuilder::home());

		$categories = array_reverse(CategoriesService::get_categories_manager()->get_parents($this->get_category()->get_id(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), WebUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name(), $sort_field, $sort_mode, ($category->get_id() == $this->get_category()->get_id() ? $page : 1)));
		}

		return $response;
	}

	public static function get_view()
	{
		$object = new self();
		$object->init();
		$object->check_authorizations();
		$object->build_view(AppContext::get_request());
		return $object->tpl;
	}
}
?>
