<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 12
 * @since       PHPBoost 4.0 - 2014 09 02
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FaqDisplayPendingFaqQuestionsController extends ModuleController
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
		$this->lang = LangLoader::get('common', 'faq');
		$this->tpl = new FileTemplate('faq/FaqDisplaySeveralFaqQuestionsController.tpl');
		$this->tpl->add_lang($this->lang);
		$this->config = FaqConfig::load();
	}

	public function build_view(HTTPRequestCustom $request)
	{
		$config = FaqConfig::load();
		$authorized_categories = CategoriesService::get_authorized_categories();
		$mode = $request->get_getstring('sort', $this->config->get_items_default_sort_mode());
		$field = $request->get_getstring('field', FaqQuestion::SORT_FIELDS_URL_VALUES[$this->config->get_items_default_sort_field()]);

		$sort_mode = TextHelper::strtoupper($mode);
		$sort_mode = (in_array($sort_mode, array(FaqQuestion::ASC, FaqQuestion::DESC)) ? $sort_mode : $this->config->get_items_default_sort_mode());

		if (in_array($field, FaqQuestion::SORT_FIELDS_URL_VALUES))
			$sort_field = array_search($field, FaqQuestion::SORT_FIELDS_URL_VALUES);
		else
			$sort_field = FaqQuestion::SORT_DATE;

		$result = PersistenceContext::get_querier()->select('SELECT *
		FROM '. FaqSetup::$faq_table .' faq
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = faq.author_user_id
		WHERE approved = 0
		AND faq.id_category IN :authorized_categories
		' . (!CategoriesAuthorizationsService::check_authorizations()->moderation() ? ' AND faq.author_user_id = :user_id' : '') . '
		ORDER BY ' . $sort_field . ' ' . $sort_mode, array(
			'authorized_categories' => $authorized_categories,
			'user_id' => AppContext::get_current_user()->get_id()
		));

		$this->tpl->put_all(array(
			'C_QUESTIONS' => $result->get_rows_count() > 0,
			'C_PENDING'   => true,
			'C_MORE_THAN_ONE_QUESTION' => $result->get_rows_count() > 1,
			'C_DISPLAY_TYPE_BASIC'     => $config->get_display_type() == FaqConfig::DISPLAY_TYPE_BASIC,
			'C_DISPLAY_CONTROLS'       => $config->are_control_buttons_displayed(),
			'QUESTIONS_NUMBER'         => $result->get_rows_count()
		));

		while ($row = $result->fetch())
		{
			$faq_question = new FaqQuestion();
			$faq_question->set_properties($row);

			$this->tpl->assign_block_vars('questions', $faq_question->get_array_tpl_vars());
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
				new FormFieldSelectChoiceOption($common_lang['form.date.creation'], FaqQuestion::SORT_FIELDS_URL_VALUES[FaqQuestion::SORT_DATE]),
				new FormFieldSelectChoiceOption($this->lang['faq.form.question'], FaqQuestion::SORT_FIELDS_URL_VALUES[FaqQuestion::SORT_ALPHABETIC])
			),
			array('events' => array('change' => 'document.location = "'. FaqUrlBuilder::display_pending()->rel() . '" + HTMLForms.getField("sort_fields").getValue() + "/" + HTMLForms.getField("sort_mode").getValue();'))
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('sort_mode', '', $mode,
			array(
				new FormFieldSelectChoiceOption($common_lang['sort.asc'], 'asc'),
				new FormFieldSelectChoiceOption($common_lang['sort.desc'], 'desc')
			),
			array('events' => array('change' => 'document.location = "' . FaqUrlBuilder::display_pending()->rel() . '" + HTMLForms.getField("sort_fields").getValue() + "/" + HTMLForms.getField("sort_mode").getValue();'))
		));

		$this->tpl->put('SORT_FORM', $form->display());
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
		$sort_field = $request->get_getstring('field', FaqQuestion::SORT_FIELDS_URL_VALUES[$this->config->get_items_default_sort_field()]);
		$sort_mode = $request->get_getstring('sort', $this->config->get_items_default_sort_mode());
		$response = new SiteDisplayResponse($this->tpl);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['faq.questions.pending'], $this->lang['faq.module.title']);
		$graphical_environment->get_seo_meta_data()->set_description($this->lang['faq.seo.description.pending']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(FaqUrlBuilder::display_pending($sort_field, $sort_mode));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['faq.module.title'], FaqUrlBuilder::home());
		$breadcrumb->add($this->lang['faq.questions.pending'], FaqUrlBuilder::display_pending($sort_field, $sort_mode));

		return $response;
	}
}
?>
