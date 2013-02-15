<?php
/*##################################################
 *		                         NewsFormController.class.php
 *                            -------------------
 *   begin                : February 13, 2013
 *   copyright            : (C) 2013 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

class NewsFormController extends ModuleController
{
	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var FormButtonSubmit
	 */
	private $submit_button;
	
	private $lang;
	
	private $news;
	
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->build_form();
		
		$tpl = new StringTemplate('# INCLUDE FORM #');
		$tpl->add_lang($this->lang);
		
		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
		}
		
		$tpl->put('FORM', $this->form->display());
		
		return $this->generate_response($tpl);
	}
	
	private function init()
	{
		$this->lang = LangLoader::get('common', 'news');
	}
	
	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);
		
		$fieldset = new FormFieldsetHTML('news', $this->lang['news']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldTextEditor('title', $this->lang['news.form.title'], $this->get_news()->get_title(), array('required' => true)));

		$search_category_children_options = new SearchCategoryChildrensOptions();
		$search_category_children_options->add_authorisations_bits(NewsAuthorizationsService::AUTHORIZATIONS_READ);
		$search_category_children_options->add_authorisations_bits(NewsAuthorizationsService::AUTHORIZATIONS_CONTRIBUTION);
		$fieldset->add_field(NewsService::get_categories_manager()->get_select_categories_form_field('id_cat', $this->lang['news.form.category'], $this->get_news()->get_id_cat(), $search_category_children_options));
		
		$fieldset->add_field(new FormFieldRichTextEditor('contents', $this->lang['news.form.contents'], $this->get_news()->get_contents(), array('required' => true)));
		
		$fieldset->add_field(new FormFieldCheckbox('enable_short_contents', $this->lang['news.form.short_contents.enabled'], $this->get_news()->get_short_contents_enabled(), 
			array('events' => array('click' => '
			if (HTMLForms.getField("enable_short_contents").getValue()) {
				$("'.__CLASS__.'_short_contents").appear();
			} else { 
				$("'.__CLASS__.'_short_contents").fade();
			}'))
		));
		
		$fieldset->add_field(new FormFieldRichTextEditor('short_contents', $this->lang['news.form.short_contents'], $this->get_news()->get_short_contents(), array('hidden' => !$this->get_news()->get_short_contents_enabled())));
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('approbation', $this->lang['news.form.approbation'], $this->get_news()->get_approbation(),
			array(
				new FormFieldSelectChoiceOption($this->lang['news.form.approbation.not'], News::NOT_APPROVAL),
				new FormFieldSelectChoiceOption($this->lang['news.form.approbation.now'], News::APPROVAL_NOW),
				new FormFieldSelectChoiceOption($this->lang['news.form.approbation.date'], News::APPROVAL_DATE),
			),
			array('events' => array('click' => '
			if (HTMLForms.getField("approbation").getValue()) {
				$("'.__CLASS__.'_start_date").appear();
				$("'.__CLASS__.'_end_date").appear();
			} else { 
				$("'.__CLASS__.'_start_date").fade();
				$("'.__CLASS__.'_end_date").fade();
			}'))
		));
		
		$fieldset->add_field(new FormFieldDateTime('start_date', $this->lang['news.form.date.start'], $this->get_news()->get_start_date(), array('hidden' => ($this->get_news()->get_approbation() !== News::APPROVAL_DATE))));
		
		$fieldset->add_field(new FormFieldDateTime('end_date', $this->lang['news.form.date.end'], $this->get_news()->get_end_date(), array('hidden' => ($this->get_news()->get_approbation() !== News::APPROVAL_DATE))));
		
		$fieldset->add_field(new FormFieldDateTime('creation_date', $this->lang['news.form.date.creation'], $this->get_news()->get_creation_date()));
		
		// Contribution fieldset
		$this->build_contribution_fieldset();
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());
		
		$this->form = $form;
	}
	
	private function build_contribution_fieldset()
	{
		if ($this->is_contributor_member())
		{
			$fieldset = new FormFieldsetHTML('contribution', $this->lang['news.form.contribution']);
			$fieldset->set_description(MessageHelper::display($this->lang['news.form.contribution.explain'], MessageHelper::WARNING));
			$form->add_fieldset($fieldset);
			
			$fieldset->add_field(new FormFieldRichTextEditor('contribution_description', $this->lang['news.form.contribution.description'], '', array('description' => $this->lang['news.form.contribution.description.explain'])));
		}
	}
	
	private function is_contributor_member()
	{
		return (empty($this->get_news()->get_id()) && !NewsAuthorizationsService::check_authorizations()->write() && NewsAuthorizationsService::check_authorizations()->contribution());
	}
	
	private function get_news()
	{
		if ($this->news === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try {
					$this->news = NewsService::get_news('WHERE id=:id', array('id' => $id));
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
   					DispatchManager::redirect($error_controller);
				}
			}
			
			$this->news = new News();
		}
		return $this->news;
	}
	
	private function check_authorizations()
	{
		$news = $this->get_news();
		
		if (!empty($news->get_id()))
		{
			if (!NewsAuthorizationsService::check_authorizations()->write() && !NewsAuthorizationsService::check_authorizations()->contribution())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
	   			DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			if (!(NewsAuthorizationsService::check_authorizations($news->get_id_cat())->moderation() || (NewsAuthorizationsService::check_authorizations($news->get_id_cat())->write() && $news->get_user_id() == AppContext::get_current_user()->get_id())))
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
	   			DispatchManager::redirect($error_controller);
			}
		}
	}
	
	private function generate_response(View $tpl)
	{
		$response = new NewsDisplayResponse();
		$response->set_page_title($this->lang['news.add']);
		return $response->display($tpl);
	}
}
?>