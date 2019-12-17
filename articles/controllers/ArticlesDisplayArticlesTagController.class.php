<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 02
 * @since       PHPBoost 4.0 - 2013 06 13
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ArticlesDisplayArticlesTagController extends ModuleController
{
	private $lang;
	private $view;
	private $keyword;

	private $config;
	private $comments_config;
	private $content_management_config;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$this->build_view($request);

		return $this->generate_response($request);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'articles');
		$this->view = new FileTemplate('articles/ArticlesDisplaySeveralArticlesController.tpl');
		$this->view->add_lang($this->lang);
		$this->config = ArticlesConfig::load();

		$this->comments_config = CommentsConfig::load();
		$this->content_management_config = ContentManagementConfig::load();
	}

	private function get_keyword()
	{
		if ($this->keyword === null)
		{
			$rewrited_name = AppContext::get_request()->get_getstring('tag', '');
			if (!empty($rewrited_name))
			{
				try {
					$this->keyword = ArticlesService::get_keywords_manager()->get_keyword('WHERE rewrited_name=:rewrited_name', array('rewrited_name' => $rewrited_name));
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			}
		}
		return $this->keyword;
	}

	private function build_view(HTTPRequestCustom $request)
	{
		$now = new Date();

		$mode = $request->get_getstring('sort', $this->config->get_items_default_sort_mode());
		$field = $request->get_getstring('field', Article::SORT_FIELDS_URL_VALUES[$this->config->get_items_default_sort_field()]);

		$sort_mode = TextHelper::strtoupper($mode);
		$sort_mode = (in_array($sort_mode, array(Article::ASC, Article::DESC)) ? $sort_mode : $this->config->get_items_default_sort_mode());

		if (in_array($field, Article::SORT_FIELDS_URL_VALUES))
			$sort_field = array_search($field, Article::SORT_FIELDS_URL_VALUES);
		else
			$sort_field = $this->config->get_items_default_sort_field();

		$authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, $this->config->are_descriptions_displayed_to_guests());

		$condition = 'WHERE relation.id_keyword = :id_keyword
		AND id_category IN :authorized_categories
		AND (published = 1 OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0)))';
		$parameters = array(
			'id_keyword' => $this->get_keyword()->get_id(),
			'authorized_categories' => $authorized_categories,
			'timestamp_now' => $now->get_timestamp()
		);

		$page = $request->get_getint('page', 1);
		$pagination = $this->get_pagination($condition, $parameters, $field, TextHelper::strtolower($sort_mode), $page);

		$result = PersistenceContext::get_querier()->select('SELECT articles.*, member.*, com.number_comments, notes.number_notes, notes.average_notes, note.note
		FROM ' . ArticlesSetup::$articles_table . ' articles
		LEFT JOIN ' . DB_TABLE_KEYWORDS_RELATIONS . ' relation ON relation.module_id = \'articles\' AND relation.id_in_module = articles.id
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = articles.author_user_id
		LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' com ON com.id_in_module = articles.id AND com.module_id = \'articles\'
		LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = articles.id AND notes.module_name = \'articles\'
		LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = articles.id AND note.module_name = \'articles\' AND note.user_id = ' . AppContext::get_current_user()->get_id() . '
		' . $condition . '
		ORDER BY ' .$sort_field . ' ' . $sort_mode . '
		LIMIT :number_items_per_page OFFSET :display_from', array_merge($parameters, array(
			'number_items_per_page' => $pagination->get_number_items_per_page(),
			'display_from' => $pagination->get_display_from()
		)));

		$this->build_sorting_form($field, TextHelper::strtolower($sort_mode));

		$number_columns_display_per_line = $this->config->get_number_cols_display_per_line();

		$this->view->put_all(array(
			'C_ARTICLES' => $result->get_rows_count() > 0,
			'C_MORE_THAN_ONE_ARTICLE' => $result->get_rows_count() > 1,
			'C_PAGINATION' => $pagination->has_several_pages(),
			'PAGINATION' => $pagination->display(),
			'C_NO_ARTICLE_AVAILABLE' => $result->get_rows_count() == 0,
			'C_MOSAIC' => $this->config->get_display_type() == ArticlesConfig::DISPLAY_MOSAIC,
			'C_ARTICLES_CAT' => false,
			'C_COMMENTS_ENABLED' => $this->comments_config->module_comments_is_enabled('articles'),
			'C_NOTATION_ENABLED' => $this->content_management_config->module_notation_is_enabled('articles'),
			'C_ARTICLES_FILTERS' => true,
			'CATEGORY_NAME' => $this->get_keyword()->get_name(),
			'C_SEVERAL_COLUMNS' => $number_columns_display_per_line > 1,
			'COLUMNS_NUMBER' => $number_columns_display_per_line
		));

		while ($row = $result->fetch())
		{
			$article = new Article();
			$article->set_properties($row);

			$this->build_keywords_view($article);

			$this->view->assign_block_vars('articles', $article->get_array_tpl_vars());

			foreach ($article->get_sources() as $name => $url)
			{
				$this->tpl->assign_block_vars('articles.sources', $article->get_array_tpl_source_vars($name));
			}
		}
		$result->dispose();
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

	private function build_sorting_form($field, $mode)
	{
		$common_lang = LangLoader::get('common');

		$form = new HTMLForm(__CLASS__, '', false);
		$form->set_css_class('options');

		$fieldset = new FormFieldsetHorizontal('filters', array('description' => $common_lang['sort_by']));
		$form->add_fieldset($fieldset);

		$sort_options = array(
			new FormFieldSelectChoiceOption($common_lang['form.date.creation'], Article::SORT_FIELDS_URL_VALUES[Article::SORT_DATE]),
			new FormFieldSelectChoiceOption($common_lang['form.title'], Article::SORT_FIELDS_URL_VALUES[Article::SORT_ALPHABETIC]),
			new FormFieldSelectChoiceOption($common_lang['sort_by.number_views'], Article::SORT_FIELDS_URL_VALUES[Article::SORT_NUMBER_VIEWS]),
			new FormFieldSelectChoiceOption($common_lang['author'], Article::SORT_FIELDS_URL_VALUES[Article::SORT_AUTHOR])
		);

		if ($this->comments_config->module_comments_is_enabled('articles'))
			$sort_options[] = new FormFieldSelectChoiceOption($common_lang['sort_by.number_comments'], Article::SORT_FIELDS_URL_VALUES[Article::SORT_NUMBER_COMMENTS]);

		if ($this->content_management_config->module_notation_is_enabled('articles'))
			$sort_options[] = new FormFieldSelectChoiceOption($common_lang['sort_by.best_note'], Article::SORT_FIELDS_URL_VALUES[Article::SORT_NOTATION]);

		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_fields', '', $field, $sort_options,
			array('events' => array('change' => 'document.location = "'. ArticlesUrlBuilder::display_tag($this->get_keyword()->get_rewrited_name())->rel() .'" + HTMLForms.getField("sort_fields").getValue() + "/" + HTMLForms.getField("sort_mode").getValue();'))
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_mode', '', $mode,
			array(
				new FormFieldSelectChoiceOption($common_lang['sort.asc'], 'asc'),
				new FormFieldSelectChoiceOption($common_lang['sort.desc'], 'desc')
			),
			array('events' => array('change' => 'document.location = "' . ArticlesUrlBuilder::display_tag($this->get_keyword()->get_rewrited_name())->rel() . '" + HTMLForms.getField("sort_fields").getValue() + "/" + HTMLForms.getField("sort_mode").getValue();'))
		));

		$this->view->put('FORM', $form->display());
	}

	private function get_pagination($condition, $parameters, $field, $mode, $page)
	{
		$result = PersistenceContext::get_querier()->select_single_row_query('SELECT COUNT(*) AS nbr_articles
		FROM '. ArticlesSetup::$articles_table .' articles
		LEFT JOIN '. DB_TABLE_KEYWORDS_RELATIONS .' relation ON relation.module_id = \'articles\' AND relation.id_in_module = articles.id
		' . $condition, $parameters);

		$pagination = new ModulePagination($page, $result['nbr_articles'], ArticlesConfig::load()->get_number_articles_per_page());
		$pagination->set_url(ArticlesUrlBuilder::display_tag($this->get_keyword()->get_rewrited_name(), $field, $mode, '%d'));

		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		return $pagination;
	}

	private function check_authorizations()
	{
		if (!(CategoriesAuthorizationsService::check_authorizations()->read()))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response(HTTPRequestCustom $request)
	{
		$sort_field = $request->get_getstring('field', Article::SORT_FIELDS_URL_VALUES[$this->config->get_items_default_sort_field()]);
		$sort_mode = $request->get_getstring('sort', $this->config->get_items_default_sort_mode());
		$page = $request->get_getint('page', 1);
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->get_keyword()->get_name(), $this->lang['articles.module.title'], $page);
		$graphical_environment->get_seo_meta_data()->set_description(StringVars::replace_vars($this->lang['articles.seo.description.tag'], array('subject' => $this->get_keyword()->get_name())), $page);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(ArticlesUrlBuilder::display_tag($this->get_keyword()->get_rewrited_name(), $sort_field, $sort_mode, $page));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['articles.module.title'], ArticlesUrlBuilder::home());
		$breadcrumb->add($this->get_keyword()->get_name(), ArticlesUrlBuilder::display_tag($this->get_keyword()->get_rewrited_name(), $sort_field, $sort_mode, $page));

		return $response;
	}
}
?>
