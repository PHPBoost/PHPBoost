<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 09
 * @since       PHPBoost 4.1 - 2014 08 21
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class WebDisplayPendingWebLinksController extends ModuleController
{
	private $tpl;
	private $lang;
	private $config;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$this->build_view($request);

		return $this->generate_response($request);
	}

	public function init()
	{
		$this->lang = LangLoader::get('common', 'web');
		$this->tpl = new FileTemplate('web/WebDisplaySeveralWebLinksController.tpl');
		$this->tpl->add_lang($this->lang);
		$this->config = WebConfig::load();
	}

	public function build_view(HTTPRequestCustom $request)
	{
		$now = new Date();
		$comments_config = CommentsConfig::load();
		$content_management_config = ContentManagementConfig::load();
		$authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, $this->config->are_descriptions_displayed_to_guests());
		$mode = $request->get_getstring('sort', $this->config->get_items_default_sort_mode());
		$field = $request->get_getstring('field', WebLink::SORT_FIELDS_URL_VALUES[$this->config->get_items_default_sort_field()]);

		$condition = 'WHERE id_category IN :authorized_categories
		' . (!CategoriesAuthorizationsService::check_authorizations()->moderation() ? ' AND author_user_id = :user_id' : '') . '
		AND (approbation_type = 0 OR (approbation_type = 2 AND (start_date > :timestamp_now OR (end_date != 0 AND end_date < :timestamp_now))))';
		$parameters = array(
			'user_id' => AppContext::get_current_user()->get_id(),
			'authorized_categories' => $authorized_categories,
			'timestamp_now' => $now->get_timestamp()
		);

		$page = $request->get_getint('page', 1);
		$pagination = $this->get_pagination($condition, $parameters, $field, TextHelper::strtolower($mode), $page);

		$sort_mode = TextHelper::strtoupper($mode);
		$sort_mode = (in_array($sort_mode, array(WebLink::ASC, WebLink::DESC)) ? $sort_mode : $this->config->get_items_default_sort_mode());

		if (in_array($field, array(WebLink::SORT_FIELDS_URL_VALUES[WebLink::SORT_ALPHABETIC], WebLink::SORT_FIELDS_URL_VALUES[WebLink::SORT_DATE])))
			$sort_field = array_search($field, WebLink::SORT_FIELDS_URL_VALUES);
		else
			$sort_field = WebLink::SORT_DATE;

		$result = PersistenceContext::get_querier()->select('SELECT web.*, member.*, com.number_comments, notes.average_notes, notes.number_notes, note.note
		FROM '. WebSetup::$web_table .' web
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = web.author_user_id
		LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' com ON com.id_in_module = web.id AND com.module_id = \'web\'
		LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = web.id AND notes.module_name = \'web\'
		LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = web.id AND note.module_name = \'web\' AND note.user_id = :user_id
		' . $condition . '
		ORDER BY web.privileged_partner DESC, ' . $sort_field . ' ' . $sort_mode . '
		LIMIT :number_items_per_page OFFSET :display_from', array_merge($parameters, array(
			'number_items_per_page' => $pagination->get_number_items_per_page(),
			'display_from' => $pagination->get_display_from()
		)));

		$number_columns_display_per_line = $this->config->get_columns_number_per_line();

		$this->tpl->put_all(array(
			'C_WEBLINKS' => $result->get_rows_count() > 0,
			'C_MORE_THAN_ONE_WEBLINK' => $result->get_rows_count() > 1,
			'C_PENDING' => true,
			'C_CATEGORY_DISPLAYED_SUMMARY' => $this->config->is_category_displayed_summary(),
			'C_CATEGORY_DISPLAYED_TABLE' => $this->config->is_category_displayed_table(),
			'C_SEVERAL_COLUMNS' => $number_columns_display_per_line > 1,
			'COLUMNS_NUMBER' => $number_columns_display_per_line,
			'C_COMMENTS_ENABLED' => $comments_config->module_comments_is_enabled('web'),
			'C_NOTATION_ENABLED' => $content_management_config->module_notation_is_enabled('web'),
			'C_PAGINATION' => $pagination->has_several_pages(),
			'PAGINATION' => $pagination->display(),
			'TABLE_COLSPAN' => 3 + (int)$comments_config->module_comments_is_enabled('web') + (int)$content_management_config->module_notation_is_enabled('web')
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
		$form->set_css_class('options');

		$fieldset = new FormFieldsetHorizontal('filters', array('description' => $common_lang['sort_by']));
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_fields', '', $field,
			array(
				new FormFieldSelectChoiceOption($common_lang['form.date.creation'], WebLink::SORT_FIELDS_URL_VALUES[WebLink::SORT_DATE]),
				new FormFieldSelectChoiceOption($common_lang['form.name'], WebLink::SORT_FIELDS_URL_VALUES[WebLink::SORT_ALPHABETIC])
			),
			array('events' => array('change' => 'document.location = "'. WebUrlBuilder::display_pending()->rel() . '" + HTMLForms.getField("sort_fields").getValue() + "/" + HTMLForms.getField("sort_mode").getValue();'))
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_mode', '', $mode,
			array(
				new FormFieldSelectChoiceOption($common_lang['sort.asc'], 'asc'),
				new FormFieldSelectChoiceOption($common_lang['sort.desc'], 'desc')
			),
			array('events' => array('change' => 'document.location = "' . WebUrlBuilder::display_pending()->rel() . '" + HTMLForms.getField("sort_fields").getValue() + "/" + HTMLForms.getField("sort_mode").getValue();'))
		));

		$this->tpl->put('SORT_FORM', $form->display());
	}

	private function get_pagination($condition, $parameters, $field, $mode, $page)
	{
		$weblinks_number = WebService::count($condition, $parameters);

		$pagination = new ModulePagination($page, $weblinks_number, (int)WebConfig::load()->get_items_number_per_page());
		$pagination->set_url(WebUrlBuilder::display_pending($field, $mode, '%d'));

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
		if (!(CategoriesAuthorizationsService::check_authorizations()->write() || CategoriesAuthorizationsService::check_authorizations()->contribution() || CategoriesAuthorizationsService::check_authorizations()->moderation()))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response(HTTPRequestCustom $request)
	{
		$sort_field = $request->get_getstring('field', WebLink::SORT_FIELDS_URL_VALUES[$this->config->get_items_default_sort_field()]);
		$sort_mode = $request->get_getstring('sort', $this->config->get_items_default_sort_mode());
		$page = $request->get_getint('page', 1);
		$response = new SiteDisplayResponse($this->tpl);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['web.pending'], $this->lang['module_title'], $page);
		$graphical_environment->get_seo_meta_data()->set_description($this->lang['web.seo.description.pending'], $page);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(WebUrlBuilder::display_pending($sort_field, $sort_mode, $page));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'], WebUrlBuilder::home());
		$breadcrumb->add($this->lang['web.pending'], WebUrlBuilder::display_pending($sort_field, $sort_mode, $page));

		return $response;
	}
}
?>
