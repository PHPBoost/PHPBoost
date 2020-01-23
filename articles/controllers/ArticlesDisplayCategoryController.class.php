<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 23
 * @since       PHPBoost 4.0 - 2013 05 13
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ArticlesDisplayCategoryController extends AbstractItemController
{
	private $category;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->build_view($request);

		return $this->generate_response($request);
	}

	private function build_view(HTTPRequestCustom $request)
	{
		$now = new Date();
		$mode = $request->get_getstring('sort', $this->config->get_items_default_sort_mode());
		$field = $request->get_getstring('field', Article::SORT_FIELDS_URL_VALUES[$this->config->get_items_default_sort_field()]);
		$page = $request->get_getint('page', 1);
		$subcategories_page = $request->get_getint('subcategories_page', 1);

		$sort_mode = TextHelper::strtoupper($mode);
		$sort_mode = (in_array($sort_mode, array(Article::ASC, Article::DESC)) ? $sort_mode : $this->config->get_items_default_sort_mode());

		$this->build_categories_listing_view($now, $field, TextHelper::strtolower($sort_mode), $page, $subcategories_page);
		$this->build_articles_listing_view($now, $field, TextHelper::strtolower($sort_mode), $page, $subcategories_page);
		$this->build_sorting_form($field, TextHelper::strtolower($sort_mode));
	}

	private function build_articles_listing_view(Date $now, $field, $sort_mode, $page, $subcategories_page)
	{
		if (in_array($field, Article::SORT_FIELDS_URL_VALUES))
			$sort_field = array_search($field, Article::SORT_FIELDS_URL_VALUES);
		else
			$sort_field = $this->config->get_items_default_sort_field();

		$condition = 'WHERE id_category = :id_category
		AND (published = 1 OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0)))';
		$parameters = array(
			'id_category' => $this->get_category()->get_id(),
			'timestamp_now' => $now->get_timestamp()
		);

		$pagination = $this->get_pagination($condition, $parameters, $field, $sort_mode, $page, $subcategories_page);

		$items = self::get_items_manager()->get_items($pagination->get_number_items_per_page(), $pagination->get_display_from(), $sort_field, $sort_mode, $condition, $parameters);

		$this->view->put_all(array(
			'C_ITEMS'            => !empty($items),
			'C_SEVERAL_ITEMS'    => count($items) > 1,
			'C_GRID_VIEW'        => $this->config->get_display_type() == ArticlesConfig::GRID_VIEW,
			'C_LIST_VIEW'        => $this->config->get_display_type() == ArticlesConfig::LIST_VIEW,
			'C_SORTING_FORM'     => true,
			'C_PAGINATION'       => $pagination->has_several_pages(),
			'CATEGORIES_PER_ROW' => $this->config->get_categories_per_row(),
			'ITEMS_PER_ROW'      => $this->config->get_items_per_row(),
			'PAGINATION'         => $pagination->display(),
			'ID_CAT'             => $this->get_category()->get_id(),
			'U_EDIT_CATEGORY'    => $this->get_category()->get_id() == Category::ROOT_CATEGORY ? ModulesUrlBuilder::configuration()->rel() : CategoriesUrlBuilder::edit_category($this->get_category()->get_id())->rel()
		));

		foreach ($items as $item)
		{
			$this->build_keywords_view($item);

			$this->view->assign_block_vars('articles', $item->get_array_tpl_vars());

			foreach ($item->get_sources() as $name => $url)
			{
				$this->view->assign_block_vars('articles.sources', $item->get_array_tpl_source_vars($name));
			}
		}
	}

	private function build_categories_listing_view(Date $now, $field, $mode, $page, $subcategories_page)
	{
		$subcategories = CategoriesService::get_categories_manager()->get_categories_cache()->get_children($this->get_category()->get_id(), CategoriesService::get_authorized_categories($this->get_category()->get_id(), $this->config->get_summary_displayed_to_guests()));
		$subcategories_pagination = $this->get_subcategories_pagination(count($subcategories), $this->config->get_categories_per_page(), $field, $mode, $page, $subcategories_page);

		$nbr_cat_displayed = 0;
		foreach ($subcategories as $id => $category)
		{
			$nbr_cat_displayed++;

			if ($nbr_cat_displayed > $subcategories_pagination->get_display_from() && $nbr_cat_displayed <= ($subcategories_pagination->get_display_from() + $subcategories_pagination->get_number_items_per_page()))
			{
				$category_thumbnail = $category->get_thumbnail()->rel();

				$this->view->assign_block_vars('sub_categories_list', array(
					'C_CATEGORY_THUMBNAIL' => !empty($category_thumbnail),
					'C_SEVERAL_ITEMS' => $category->get_elements_number() > 1,
					'CATEGORY_ID' => $category->get_id(),
					'CATEGORY_NAME' => $category->get_name(),
					'U_CATEGORY_THUMBNAIL' => $category_thumbnail,
					'ARTICLES_NUMBER' => $category->get_elements_number(),
					'U_CATEGORY' => ArticlesUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name())->rel()
				));
			}
		}

		$category_description = FormatingHelper::second_parse($this->get_category()->get_description());

		$this->view->put_all(array(
			'C_CATEGORY' => true,
			'C_ROOT_CATEGORY' => $this->get_category()->get_id() == Category::ROOT_CATEGORY,
			'C_HIDE_NO_ITEM_MESSAGE' => $this->get_category()->get_id() == Category::ROOT_CATEGORY && ($nbr_cat_displayed != 0 || !empty($category_description)),
			'C_CATEGORY_DESCRIPTION' => !empty($category_description),
			'C_SUB_CATEGORIES' => $nbr_cat_displayed > 0,
			'C_SUBCATEGORIES_PAGINATION' => $subcategories_pagination->has_several_pages(),
			'CATEGORY_NAME' => $this->get_category()->get_name(),
			'U_CATEGORY_THUMBNAIL' => $this->get_category()->get_thumbnail()->rel(),
			'CATEGORY_DESCRIPTION' => $category_description,
			'SUBCATEGORIES_PAGINATION' => $subcategories_pagination->display(),
			'NUMBER_CATS_COLUMNS' => $this->config->get_categories_per_row()
		));
	}

	private function build_sorting_form($field, $mode)
	{
		$common_lang = LangLoader::get('common');

		$form = new HTMLForm(__CLASS__, '', false);
		$form->set_css_class('options');

		$fieldset = new FormFieldsetHorizontal('filters', array('description' => $common_lang['sort_by'], 'css_class' => 'grouped-inputs'));
		$form->add_fieldset($fieldset);

		$sort_options = array(
			new FormFieldSelectChoiceOption($common_lang['form.date.creation'], Article::SORT_FIELDS_URL_VALUES[Article::SORT_DATE]),
			new FormFieldSelectChoiceOption($common_lang['form.title'], Article::SORT_FIELDS_URL_VALUES[Article::SORT_ALPHABETIC]),
			new FormFieldSelectChoiceOption($common_lang['sort_by.views.number'], Article::SORT_FIELDS_URL_VALUES[Article::SORT_VIEWS_NUMBER]),
			new FormFieldSelectChoiceOption($common_lang['author'], Article::SORT_FIELDS_URL_VALUES[Article::SORT_AUTHOR])
		);

		if (in_array('comments', $this->enabled_features))
			$sort_options[] = new FormFieldSelectChoiceOption($common_lang['sort_by.comments.number'], Article::SORT_FIELDS_URL_VALUES[Article::SORT_COMMENTS_NUMBER]);

		if (in_array('notation', $this->enabled_features))
			$sort_options[] = new FormFieldSelectChoiceOption($common_lang['sort_by.best.note'], Article::SORT_FIELDS_URL_VALUES[Article::SORT_NOTATION]);

		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_fields', '', $field, $sort_options,
			array(
				'class' => 'grouped-element',
				'events' => array('change' => 'document.location = "'. ArticlesUrlBuilder::display_category($this->category->get_id(), $this->category->get_rewrited_name())->rel() .'" + HTMLForms.getField("sort_fields").getValue() + "/" + HTMLForms.getField("sort_mode").getValue();')
			)
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_mode', '', $mode,
			array(
				new FormFieldSelectChoiceOption($common_lang['sort.asc'], 'asc'),
				new FormFieldSelectChoiceOption($common_lang['sort.desc'], 'desc')
			),
			array(
				'class' => 'grouped-element',
				'events' => array('change' => 'document.location = "' . ArticlesUrlBuilder::display_category($this->category->get_id(), $this->category->get_rewrited_name())->rel() . '" + HTMLForms.getField("sort_fields").getValue() + "/" + HTMLForms.getField("sort_mode").getValue();')
			)
		));

		$this->view->put('SORTING_FORM', $form->display());
	}

	private function get_category()
	{
		if ($this->category === null)
		{
			$id = AppContext::get_request()->get_getstring('id_category', Category::ROOT_CATEGORY);
			try {
				$this->category = CategoriesService::get_categories_manager('articles')->get_categories_cache()->get_category($id);
			} catch (CategoryNotFoundException $e) {
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			}
		}
		return $this->category;
	}

	private function build_keywords_view(Article $article)
	{
		$keywords = $article->get_keywords();
		$nbr_keywords = count($keywords);
		$this->view->put('C_KEYWORDS', $nbr_keywords > 0);

		$i = 1;
		foreach ($keywords as $keyword)
		{
			$this->view->assign_block_vars('keywords', array(
				'C_SEPARATOR' => $i < $nbr_keywords,
				'NAME' => $keyword->get_name(),
				'URL' => ArticlesUrlBuilder::display_tag($keyword->get_rewrited_name())->rel(),
			));
			$i++;
		}
	}

	private function get_pagination($condition, $parameters, $field, $mode, $page, $subcategories_page)
	{
		$number_articles = self::get_items_manager()->count($condition, $parameters);

		$pagination = new ModulePagination($page, $number_articles, (int)ArticlesConfig::load()->get_items_per_page());
		$pagination->set_url(ArticlesUrlBuilder::display_category($this->category->get_id(), $this->category->get_rewrited_name(), $field, $mode, '%d', $subcategories_page));

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
		$pagination->set_url(ArticlesUrlBuilder::display_category($this->category->get_id(), $this->category->get_rewrited_name(), $field, $mode, $page, '%d'));

		if ($pagination->current_page_is_empty() && $subcategories_page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		return $pagination;
	}

	protected function get_template_to_use()
	{
		return new FileTemplate('articles/ArticlesDisplaySeveralArticlesController.tpl');
	}

	protected function check_authorizations()
	{
		if (AppContext::get_current_user()->is_guest())
		{
			if (($this->config->get_summary_displayed_to_guests() && !Authorizations::check_auth(RANK_TYPE, User::MEMBER_LEVEL, $this->get_category()->get_authorizations(), Category::READ_AUTHORIZATIONS)) || (!$this->config->get_summary_displayed_to_guests() && !CategoriesAuthorizationsService::check_authorizations($this->get_category()->get_id())->read()))
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
		$sort_field = $request->get_getstring('field', Article::SORT_FIELDS_URL_VALUES[$this->config->get_items_default_sort_field()]);
		$sort_mode = $request->get_getstring('sort', $this->config->get_items_default_sort_mode());
		$page = $request->get_getint('page', 1);
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();

		if ($this->category->get_id() != Category::ROOT_CATEGORY)
			$graphical_environment->set_page_title($this->category->get_name(), self::get_module()->get_configuration()->get_name(), $page);
		else
			$graphical_environment->set_page_title(self::get_module()->get_configuration()->get_name(), '', $page);

		$description = $this->category->get_description();
		if (empty($description))
			$description = StringVars::replace_vars($this->lang['articles.seo.description.root'], array('site' => GeneralConfig::load()->get_site_name())) . ($this->category->get_id() != Category::ROOT_CATEGORY ? ' ' . LangLoader::get_message('category', 'categories-common') . ' ' . $this->category->get_name() : '');
		$graphical_environment->get_seo_meta_data()->set_description($description, $page);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(ArticlesUrlBuilder::display_category($this->category->get_id(), $this->category->get_rewrited_name(), $sort_field, $sort_mode, $page));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add(self::get_module()->get_configuration()->get_name(), ModulesUrlBuilder::home());

		$categories = array_reverse(CategoriesService::get_categories_manager()->get_parents($this->category->get_id(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), ArticlesUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name(), $sort_field, $sort_mode, ($category->get_id() == $this->category->get_id() ? $page : 1)));
		}

		return $response;
	}

	public static function get_view($module_id = '')
	{
		$object = new self($module_id);
		$object->check_authorizations();
		$object->build_view(AppContext::get_request());
		return $object->view;
	}
}
?>
