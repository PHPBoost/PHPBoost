<?php
/*##################################################
 *                               FaqDisplayPendingFaqQuestionsController.class.php
 *                            -------------------
 *   begin                : September 2, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */
 
class FaqDisplayPendingFaqQuestionsController extends ModuleController
{
	private $tpl;
	private $lang;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();
		
		$this->init();
		
		$this->build_view($request);
		
		return $this->generate_response();
	}
	
	public function init()
	{
		$this->lang = LangLoader::get('common', 'faq');
		$this->tpl = new FileTemplate('faq/FaqDisplaySeveralFaqQuestionsController.tpl');
		$this->tpl->add_lang($this->lang);
	}
	
	public function build_view(HTTPRequestCustom $request)
	{
		$authorized_categories = FaqService::get_authorized_categories(Category::ROOT_CATEGORY);
		$mode = $request->get_getstring('sort', FaqUrlBuilder::DEFAULT_SORT_MODE);
		$field = $request->get_getstring('field', FaqUrlBuilder::DEFAULT_SORT_FIELD);
		
		$sort_mode = ($mode == 'asc') ? 'ASC' : 'DESC';
		switch ($field)
		{
			case 'question':
				$sort_field = FaqQuestion::SORT_ALPHABETIC;
				break;
			default:
				$sort_field = FaqQuestion::SORT_DATE;
				break;
		}
		
		$result = PersistenceContext::get_querier()->select('SELECT *
		FROM '. FaqSetup::$faq_table .' faq
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = faq.author_user_id
		WHERE approved = 0
		AND faq.id_category IN :authorized_categories
		' . (!FaqAuthorizationsService::check_authorizations()->moderation() ? ' AND faq.author_user_id = :user_id' : '') . '
		ORDER BY ' . $sort_field . ' ' . $sort_mode, array(
			'authorized_categories' => $authorized_categories,
			'user_id' => AppContext::get_current_user()->get_id()
		));
		
		$this->tpl->put_all(array(
			'C_QUESTIONS' => $result->get_rows_count() > 0,
			'C_PENDING' => true,
			'C_MORE_THAN_ONE_QUESTION' => $result->get_rows_count() > 1,
			'C_DISPLAY_TYPE_ANSWERS_HIDDEN' => FaqConfig::load()->is_display_type_answers_hidden(),
			'QUESTIONS_NUMBER' => $result->get_rows_count()
		));
		
		while ($row = $result->fetch())
		{
			$faq_question = new FaqQuestion();
			$faq_question->set_properties($row);
			
			$this->tpl->assign_block_vars('questions', $faq_question->get_array_tpl_vars());
		}
		$result->dispose();
		$this->build_sorting_form($field, $mode);
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
				new FormFieldSelectChoiceOption($common_lang['form.date.creation'], 'date'),
				new FormFieldSelectChoiceOption($this->lang['faq.form.question'], 'question')
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
		if (!(FaqAuthorizationsService::check_authorizations()->write() || FaqAuthorizationsService::check_authorizations()->contribution() || FaqAuthorizationsService::check_authorizations()->moderation()))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}
	
	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->tpl);
		
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['faq.pending'], $this->lang['module_title']);
		$graphical_environment->get_seo_meta_data()->set_description($this->lang['faq.seo.description.pending']);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(FaqUrlBuilder::display_pending());
		
		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'], FaqUrlBuilder::home());
		$breadcrumb->add($this->lang['faq.pending'], FaqUrlBuilder::display_pending());
		
		return $response;
	}
}
?>