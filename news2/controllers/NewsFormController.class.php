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
		
		$this->check_authorizations();
		
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
		
		$fieldset->add_field(new FormFieldTextEditor('name', $this->lang['news.form.name'], $this->get_news()->get_name(), array('required' => true)));

		if (!$this->is_contributor_member())
		{
			$fieldset->add_field(new FormFieldCheckbox('personalize_rewrited_name', $this->lang['news.form.rewrited_name.personalize'], $this->get_news()->rewrited_name_is_personalized(), array(
			'events' => array('click' => '
			if (HTMLForms.getField("personalize_rewrited_name").getValue()) {
				HTMLForms.getField("rewrited_name").enable();
			} else { 
				HTMLForms.getField("rewrited_name").disable();
			}'
			))));
			
			$fieldset->add_field(new FormFieldTextEditor('rewrited_name', $this->lang['news.form.rewrited_name'], $this->get_news()->get_rewrited_name(), array(
				'description' => $this->lang['news.form.rewrited_name.description'], 
				'hidden' => !$this->get_news()->rewrited_name_is_personalized()
			), array(new FormFieldConstraintRegex('`^[a-z0-9\-]+$`i'))));
		}
		
		$search_category_children_options = new SearchCategoryChildrensOptions();
		$search_category_children_options->add_authorisations_bits(Category::READ_AUTHORIZATIONS);
		$search_category_children_options->add_authorisations_bits(Category::CONTRIBUTION_AUTHORIZATIONS);
		$fieldset->add_field(NewsService::get_categories_manager()->get_select_categories_form_field('id_cat', $this->lang['news.form.category'], $this->get_news()->get_id_cat(), $search_category_children_options));
		
		$fieldset->add_field(new FormFieldRichTextEditor('contents', $this->lang['news.form.contents'], $this->get_news()->get_contents(), array('rows' => 15, 'required' => true)));
		
		$fieldset->add_field(new FormFieldCheckbox('enable_short_contents', $this->lang['news.form.short_contents.enabled'], $this->get_news()->get_short_contents_enabled(), 
			array('description' => $this->lang['news.form.short_contents.enabled.description'], 'events' => array('click' => '
			if (HTMLForms.getField("enable_short_contents").getValue()) {
				HTMLForms.getField("short_contents").enable();
			} else { 
				HTMLForms.getField("short_contents").disable();
			}'))
		));
		
		$fieldset->add_field(new FormFieldRichTextEditor('short_contents', $this->lang['news.form.short_contents'], $this->get_news()->get_short_contents(), array('hidden' => !$this->get_news()->get_short_contents_enabled())));

		if (!$this->is_contributor_member())
		{
			$fieldset->add_field(new FormFieldSimpleSelectChoice('approbation_type', $this->lang['news.form.approbation'], $this->get_news()->get_approbation_type(),
				array(
					new FormFieldSelectChoiceOption($this->lang['news.form.approbation.not'], News::NOT_APPROVAL),
					new FormFieldSelectChoiceOption($this->lang['news.form.approbation.now'], News::APPROVAL_NOW),
					new FormFieldSelectChoiceOption($this->lang['news.form.approbation.date'], News::APPROVAL_DATE),
				),
				array('events' => array('change' => '
				if (HTMLForms.getField("approbation_type").getValue() == 2) {
					$("'.__CLASS__.'_start_date_field").appear();
					$("'.__CLASS__.'_end_date_field").appear();
				} else { 
					$("'.__CLASS__.'_start_date_field").fade();
					$("'.__CLASS__.'_end_date_field").fade();
				}'))
			));
			
			$fieldset->add_field(new FormFieldDateTime('start_date', $this->lang['news.form.date.start'], $this->get_news()->get_start_date(), array('hidden' => ($this->get_news()->get_approbation_type() != News::APPROVAL_DATE))));
			
			$fieldset->add_field(new FormFieldDateTime('end_date', $this->lang['news.form.date.end'], $this->get_news()->get_end_date(), array('hidden' => ($this->get_news()->get_approbation_type() != News::APPROVAL_DATE))));
		}
		$fieldset->add_field(new FormFieldDateTime('creation_date', $this->lang['news.form.date.creation'], $this->get_news()->get_creation_date()));

		$this->build_contribution_fieldset($form);
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());
		
		$this->form = $form;
	}
	
	private function build_contribution_fieldset($form)
	{
		if ($this->is_contributor_member())
		{
			$fieldset = new FormFieldsetHTML('contribution', $this->lang['news.form.contribution']);
			$fieldset->set_description(MessageHelper::display($this->lang['news.form.contribution.explain'], MessageHelper::WARNING)->render());
			$form->add_fieldset($fieldset);
			
			$fieldset->add_field(new FormFieldRichTextEditor('contribution_description', $this->lang['news.form.contribution.description'], '', array('description' => $this->lang['news.form.contribution.description.explain'])));
		}
	}
	
	private function is_contributor_member()
	{
		return ($this->get_news()->get_id() === null && !NewsAuthorizationsService::check_authorizations()->write() && NewsAuthorizationsService::check_authorizations()->contribution());
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
			else
			{
				$this->news = new News();
				$this->news->init_default_properties();
			}
		}
		return $this->news;
	}
	
	private function check_authorizations()
	{
		$news = $this->get_news();
		
		if ($news->get_id() === null)
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
	
	private function save()
	{
		$news = $this->get_news();
		
		$news->set_name($this->form->get_value('name'));
		$news->set_id_cat($this->form->get_value('id_cat')->get_raw_value());
		$news->set_contents($this->form->get_value('contents'));
		$news->set_short_contents(($this->form->get_value('enable_short_contents') ? $this->form->get_value('short_contens') : ''));
		$news->set_creation_date($this->form->get_value('creation_date'));
		
		if ($this->is_contributor_member())
		{
			$news->set_rewrited_name(Url::encode_rewrite($news->get_name()));
			$news->set_approbation_type(News::NOT_APPROVAL);
		}
		else
		{
			$rewrited_name = $this->form->get_value('personalize_rewrited_name') ? $this->form->get_value('rewrited_name') : Url::encode_rewrite($news->get_name());
			$news->set_rewrited_name($rewrited_name);
			
			$news->set_approbation_type($this->form->get_value('approbation_type')->get_raw_value());
			if ($news->get_approbation_type() == News::APPROVAL_DATE)
			{
				$news->set_start_date($this->form->get_value('start_date'));
				$news->set_end_date($this->form->get_value('end_date'));
			}
			else
			{
				$news->clean_start_and_end_date();
			}
		}
		
		if ($news->get_id() === null)
		{
			$news->set_author_user_id(AppContext::get_current_user()->get_id());
			NewsService::add($news);
		}
		else
		{
			NewsService::update($news);
		}
		
		$this->contribution_actions($news);
		
		Feed::clear_cache('news');
	}
	
	private function contribution_actions(News $news)
	{
		if ($news->get_id() === null)
		{
			if ($this->is_contributor_member())
			{
				$contribution = new Contribution();
				$contribution->set_id_in_module($news->get_id());
				$contribution->set_description(stripslashes($news->get_short_content()));
				$contribution->set_entitled(sprintf($NEWS_LANG['contribution_entitled'], $news->get_name()));
				$contribution->set_fixing_url(NewsUrlBuilder::edit_news($news->get_id()));
				$contribution->set_poster_id(AppContext::get_current_user()->get_attribute('user_id'));
				$contribution->set_module('news');
				$contribution->set_auth(
					Authorizations::capture_and_shift_bit_auth(
						NewsService::get_categories_manager()->get_heritated_authorizations($news->get_id_cat(), Category::MODERATION_AUTHORIZATIONS, Authorizations::AUTH_CHILD_PRIORITY),
						Category::MODERATION_AUTHORIZATIONS, Contribution::CONTRIBUTION_AUTH_BIT
					)
				);
				ContributionService::save_contribution($contribution);

				AppContext::get_response()->redirect(UserUrlBuilder::contribution_success()->absolute());
			}
		}
		else
		{
			$corresponding_contributions = ContributionService::find_by_criteria('news', $news->get_id());
			if (count($corresponding_contributions) > 0)
			{
				$news_contribution = $corresponding_contributions[0];
				$news_contribution->set_status(Event::EVENT_STATUS_PROCESSED);

				ContributionService::save_contribution($news_contribution);
			}
		}
	}
	
	private function generate_response(View $tpl)
	{
		$news = $this->get_news();
		$category = NewsService::get_categories_manager()->get_categories_cache()->get_category($news->get_id_cat());
		
		$response = new NewsDisplayResponse();
		$response->add_breadcrumb_link($this->lang['news'], NewsUrlBuilder::home());
			
		if ($this->get_news()->get_id() === null)
		{
			$response->add_breadcrumb_link($this->lang['news.add'], NewsUrlBuilder::add_news());
			$response->set_page_title($this->lang['news.add']);
		}
		else
		{
			$categories = array_reverse(NewsService::get_categories_manager()->get_parents($this->get_news()->get_id_cat(), true));
			foreach ($categories as $id => $category)
			{
				if ($id != Category::ROOT_CATEGORY)
					$response->add_breadcrumb_link($category->get_name(), NewsUrlBuilder::display_category($category->get_rewrited_name()));
			}
			$category = $categories[$this->get_news()->get_id()];
			$response->add_breadcrumb_link($this->get_news()->get_name(), NewsUrlBuilder::display_news($category->get_rewrited_name(), $this->get_news()->get_id(), $this->get_news()->get_rewrited_name()));
			
			$response->add_breadcrumb_link($this->lang['news.edit'], NewsUrlBuilder::edit_news($news->get_id()));
			$response->set_page_title($this->lang['news.edit']);
		}
		
		return $response->display($tpl);
	}
}
?>