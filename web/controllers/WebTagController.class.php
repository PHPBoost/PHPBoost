<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 16
 * @since       PHPBoost 4.1 - 2014 08 21
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class WebTagController extends DefaultModuleController
{
	private $keyword;

	private $comments_config;
	private $content_management_config;

	protected function get_template_to_use()
	{
		return new FileTemplate('web/WebSeveralItemsController.tpl');
	}

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$this->build_view($request);

		return $this->generate_response($request);
	}

	public function init()
	{
		$this->comments_config = CommentsConfig::load();
		$this->content_management_config = ContentManagementConfig::load();
	}

	public function build_view(HTTPRequestCustom $request)
	{
		$now = new Date();

		$authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, $this->config->are_descriptions_displayed_to_guests());
		$mode = $request->get_getstring('sort', $this->config->get_items_default_sort_mode());
		$field = $request->get_getstring('field', WebItem::SORT_FIELDS_URL_VALUES[$this->config->get_items_default_sort_field()]);

		$condition = 'WHERE relation.id_keyword = :id_keyword
		AND id_category IN :authorized_categories
		AND (published = 1 OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0)))';
		$parameters = array(
			'id_keyword' => $this->get_keyword()->get_id(),
			'authorized_categories' => $authorized_categories,
			'timestamp_now' => $now->get_timestamp()
		);

		$page = $request->get_getint('page', 1);
		$pagination = $this->get_pagination($condition, $parameters, $field, TextHelper::strtolower($mode), $page);

		$sort_mode = TextHelper::strtoupper($mode);
		$sort_mode = (in_array($sort_mode, array(WebItem::ASC, WebItem::DESC)) ? $sort_mode : $this->config->get_items_default_sort_mode());

		if (in_array($field, WebItem::SORT_FIELDS_URL_VALUES))
			$sort_field = array_search($field, WebItem::SORT_FIELDS_URL_VALUES);
		else
			$sort_field = $this->config->get_items_default_sort_field();

		$result = PersistenceContext::get_querier()->select('SELECT web.*, member.*, com.comments_number, notes.average_notes, notes.notes_number, note.note
		FROM ' . WebSetup::$web_table . ' web
		LEFT JOIN ' . DB_TABLE_KEYWORDS_RELATIONS . ' relation ON relation.module_id = \'web\' AND relation.id_in_module = web.id
		LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = web.author_user_id
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

		$this->view->put_all(array(
			'C_ITEMS'             => $result->get_rows_count() > 0,
			'C_SEVERAL_ITEMS'     => $result->get_rows_count() > 1,
			'C_GRID_VIEW'         => $this->config->get_display_type() == WebConfig::GRID_VIEW,
			'C_LIST_VIEW'         => $this->config->get_display_type() == WebConfig::LIST_VIEW,
			'C_TABLE_VIEW'        => $this->config->get_display_type() == WebConfig::TABLE_VIEW,
			'C_CONTROLS'          => CategoriesAuthorizationsService::check_authorizations()->moderation(),
			'C_FULL_ITEM_DISPLAY' => $this->config->is_full_item_displayed(),
			'CATEGORIES_PER_ROW'  => $this->config->get_categories_per_row(),
			'C_ENABLED_COMMENTS'  => $this->comments_config->module_comments_is_enabled('web'),
			'C_ENABLED_NOTATION'  => $this->content_management_config->module_notation_is_enabled('web'),
			'C_PAGINATION'        => $pagination->has_several_pages(),

			'ITEMS_PER_ROW' => $this->config->get_items_per_row(),
			'PAGINATION'    => $pagination->display(),
			'CATEGORY_NAME' => $this->get_keyword()->get_name()
		));

		while ($row = $result->fetch())
		{
			$item = new WebItem();
			$item->set_properties($row);

			$keywords = $item->get_keywords();
			$has_keywords = count($keywords) > 0;

			$this->view->assign_block_vars('items', array_merge($item->get_template_vars(), array(
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
		$form = new HTMLForm(__CLASS__, '', false);
		$form->set_css_class('options');

		$fieldset = new FormFieldsetHorizontal('filters', array('description' => $this->lang['common.sort.by']));
		$form->add_fieldset($fieldset);

		$sort_options = array(
			new FormFieldSelectChoiceOption($this->lang['common.creation.date'], WebItem::SORT_FIELDS_URL_VALUES[WebItem::SORT_DATE]),
			new FormFieldSelectChoiceOption($this->lang['common.title'], WebItem::SORT_FIELDS_URL_VALUES[WebItem::SORT_ALPHABETIC]),
			new FormFieldSelectChoiceOption($this->lang['web.config.sort.type.visits'], WebItem::SORT_FIELDS_URL_VALUES[WebItem::SORT_NUMBER_VISITS])
		);

		if ($this->comments_config->module_comments_is_enabled('web'))
			$sort_options[] = new FormFieldSelectChoiceOption($this->lang['common.sort.by.comments.number'], WebItem::SORT_FIELDS_URL_VALUES[WebItem::SORT_COMMENTS_NUMBER]);

		if ($this->content_management_config->module_notation_is_enabled('web'))
			$sort_options[] = new FormFieldSelectChoiceOption($this->lang['common.sort.by.best.note'], WebItem::SORT_FIELDS_URL_VALUES[WebItem::SORT_NOTATION]);

		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_fields', '', $field, $sort_options,
			array('events' => array('change' => 'document.location = "'. WebUrlBuilder::display_tag($this->get_keyword()->get_rewrited_name())->rel() . '" + HTMLForms.getField("sort_fields").getValue() + "/" + HTMLForms.getField("sort_mode").getValue();'))
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_mode', '', $mode,
			array(
				new FormFieldSelectChoiceOption($this->lang['common.sort.asc'], 'asc'),
				new FormFieldSelectChoiceOption($this->lang['common.sort.desc'], 'desc')
			),
			array('events' => array('change' => 'document.location = "' . WebUrlBuilder::display_tag($this->get_keyword()->get_rewrited_name())->rel() . '" + HTMLForms.getField("sort_fields").getValue() + "/" + HTMLForms.getField("sort_mode").getValue();'))
		));

		$this->view->put('SORT_FORM', $form->display());
	}

	private function get_keyword()
	{
		if ($this->keyword === null)
		{
			$rewrited_name = AppContext::get_request()->get_getstring('tag', '');
			if (!empty($rewrited_name))
			{
				try {
					$this->keyword = KeywordsService::get_keywords_manager()->get_keyword('WHERE rewrited_name=:rewrited_name', array('rewrited_name' => $rewrited_name));
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

	private function get_pagination($condition, $parameters, $field, $mode, $page)
	{
		$result = PersistenceContext::get_querier()->select_single_row_query('SELECT COUNT(*) AS items_number
		FROM '. WebSetup::$web_table .' web
		LEFT JOIN '. DB_TABLE_KEYWORDS_RELATIONS .' relation ON relation.module_id = \'web\' AND relation.id_in_module = web.id
		' . $condition, $parameters);

		$pagination = new ModulePagination($page, $result['items_number'], (int)WebConfig::load()->get_items_per_page());
		$pagination->set_url(WebUrlBuilder::display_tag($this->get_keyword()->get_rewrited_name(), $field, $mode, '%d'));

		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		return $pagination;
	}

	private function build_keywords_view($keywords)
	{
		$nbr_keywords = count($keywords);

		$i = 1;
		foreach ($keywords as $keyword)
		{
			$this->view->assign_block_vars('items.keywords', array(
				'C_SEPARATOR' => $i < $nbr_keywords,
				'NAME' => $keyword->get_name(),
				'URL' => WebUrlBuilder::display_tag($keyword->get_rewrited_name())->rel(),
			));
			$i++;
		}
	}

	private function check_authorizations()
	{
		if (!CategoriesAuthorizationsService::check_authorizations()->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response(HTTPRequestCustom $request)
	{
		$sort_field = $request->get_getstring('field', WebItem::SORT_FIELDS_URL_VALUES[$this->config->get_items_default_sort_field()]);
		$sort_mode = $request->get_getstring('sort', $this->config->get_items_default_sort_mode());
		$page = $request->get_getint('page', 1);
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->get_keyword()->get_name(), $this->lang['web.module.title'], $page);
		$graphical_environment->get_seo_meta_data()->set_description(StringVars::replace_vars($this->lang['web.seo.description.tag'], array('subject' => $this->get_keyword()->get_name())), $page);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(WebUrlBuilder::display_tag($this->get_keyword()->get_rewrited_name(), $sort_field, $sort_mode, $page));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['web.module.title'], WebUrlBuilder::home());
		$breadcrumb->add($this->get_keyword()->get_name(), WebUrlBuilder::display_tag($this->get_keyword()->get_rewrited_name(), $sort_field, $sort_mode, $page));

		return $response;
	}
}
?>
