<?php
/*##################################################
 *                       ArticlesFormController.class.php
 *                            -------------------
 *   begin                : February 27, 2013
 *   copyright            : (C) 2013 Patrick DUBEAU
 *   email                : daaxwizeman@gmail.com
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

/**
 * @author Patrick DUBEAU <daaxwizeman@gmail.com>
 */
class ArticlesFormController extends ModuleController
{
	private $tpl;
	private $lang;
	private $form;
	private $submit_button;
	private $article;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->check_authorizations();
		$this->build_form();
                
		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->redirect();
		}
		
		$this->tpl->put('FORM', $this->form->display());
		return $this->build_response($this->tpl);
	}

	private function init()
	{
		$this->lang = LangLoader::get('articles-common', 'articles');
		$this->tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$this->tpl->add_lang($this->lang);
	}
	
	private function build_form()
	{
		$form = new HTMLForm(__CLASS__);
		
		$fieldset = new FormFieldsetHTML('articles', $this->lang['articles']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('title', $this->lang['articles.form.title'], $this->get_article()->get_title(), 
			array('required' => true)
		));

		if(!$this->is_contributor_member())
		{
			$fieldset->add_field(new FormFieldCheckbox('personalize_rewrited_title', $this->lang['articles.form.rewrited_title.personalize'], $this->get_article()->rewrited_title_is_personalized(),
				array('events' => array('click' =>'
					if (HTMLForms.getField("personalize_rewrited_title").getValue()) {
						HTMLForms.getField("rewrited_title").enable();
					} else { 
						HTMLForms.getField("rewrited_title").disable();
					}'
				))
			));

			$fieldset->add_field(new FormFieldTextEditor('rewrited_title', $this->lang['articles.form.rewrited_title'], $this->get_article()->get_rewrited_title(),
				array('description' => $this->lang['articles.form.rewrited_title.description'],
				      'hidden' => !$this->get_article()->rewrited_title_is_personalized()),
				array(new FormFieldConstraintRegex('`^[a-z0-9\-]+$`i'))
			));
		}

		$search_category_children_options = new SearchCategoryChildrensOptions();
		$search_category_children_options->add_authorizations_bits(Category::READ_AUTHORIZATIONS);
		$search_category_children_options->add_authorizations_bits(Category::CONTRIBUTION_AUTHORIZATIONS);
		$fieldset->add_field(ArticlesService::get_categories_manager()->get_select_categories_form_field('id_category', $this->lang['articles.form.category'], $this->get_article()->get_id_category(), $search_category_children_options));
		
		$fieldset->add_field(new FormFieldMultiLineTextEditor('description', $this->lang['articles.form.description'], $this->get_article()->get_description()));
		
		$fieldset->add_field(new FormFieldRichTextEditor('contents', $this->lang['articles.form.contents'], $this->get_article()->get_contents(),
			array('rows' => 15, 'required' => true)
		));
		
		// @todo : ajouter le lien pour l'ajout de page (balise [page][/page])
		
		$other_fieldset = new FormFieldsetHTML('other', $this->lang['articles.form.other']);
		$form->add_fieldset($other_fieldset);

		$other_fieldset->add_field(new FormFieldCheckbox('author_name_displayed', $this->lang['articles.form.author_name_displayed'], $this->get_article()->get_author_name_displayed()));

		$other_fieldset->add_field(new FormFieldCheckbox('notation_enabled', $this->lang['articles.form.notation_enabled'], $this->get_article()->get_notation_enabled()));

		$image_preview_request = new AjaxRequest(PATH_TO_ROOT . '/kernel/framework/ajax/dispatcher.php?url=/image/preview/', 
			'function(response){
				if (response.responseJSON.url) {
					$(\'preview_picture\').src = response.responseJSON.url;
		}}');
		$image_preview_request->add_event_callback(AjaxRequest::ON_CREATE, 'function(response){ $(\'preview_picture\').src = PATH_TO_ROOT + \'/templates/'. get_utheme() .'/images/loading_mini.gif\';}');
		$image_preview_request->add_param('image', 'HTMLForms.getField(\'picture\').getValue()');
		$other_fieldset->add_field(new FormFieldTextEditor('picture', $this->lang['articles.form.picture'], $this->get_article()->get_picture()->relative(), 
			array('events' => array('change' => $image_preview_request->render())
		)));
		$other_fieldset->add_field(new FormFieldFree('preview_picture', $this->lang['articles.form.picture.preview'], '<img id="preview_picture" src="'. $this->get_article()->get_picture()->rel() .'" alt="" style="vertical-align:top" />'));

		$other_fieldset->add_field(new FormFieldMultipleAutocompleter('keywords', $this->lang['articles.form.keywords'], $this->get_article()->get_keywords(), 
			array('description' => $this->lang['articles.form.keywords.description'], 'file' => TPL_PATH_TO_ROOT . '/articles/ajax/tag/')
		));

		$other_fieldset->add_field(new ArticlesFormFieldSelectSources('sources', $this->lang['articles.form.sources'], $this->get_article()->get_sources(),
			array('description' => $this->lang['articles.form.sources.description'])
		));

		if(!$this->is_contributor_member())
		{
			$publication_fieldset = new FormFieldsetHTML('publication', $this->lang['articles.form.publication']);
			$form->add_fieldset($publication_fieldset);

			$publication_fieldset->add_field(new FormFieldDateTime('date_created', $this->lang['articles.form.date.created'], $this->get_article()->get_date_created()));

			$publication_fieldset->add_field(new FormFieldSimpleSelectChoice('publishing_state', $this->lang['articles.form.publishing_state'], $this->get_article()->get_publishing_state(),
				array(
					new FormFieldSelectChoiceOption($this->lang['articles.form.not_published'], Articles::NOT_PUBLISHED),
					new FormFieldSelectChoiceOption($this->lang['articles.form.published_now'], Articles::PUBLISHED_NOW),
					new FormFieldSelectChoiceOption($this->lang['articles.form.published_date'], Articles::PUBLISHED_DATE),
				),
				array('events' => array('change' => '
				if (HTMLForms.getField("publishing_state").getValue() == 2) {
					$("'.__CLASS__.'_publishing_start_date_field").appear();
					HTMLForms.getField("end_date_enable").enable();
				} else { 
					$("'.__CLASS__.'_publishing_start_date_field").fade();
					HTMLForms.getField("end_date_enable").disable();
				}'))
			));

			$publication_fieldset->add_field(new FormFieldDateTime('publishing_start_date', $this->lang['articles.form.publishing_start_date'], $this->get_article()->get_publishing_start_date(), 
				array('hidden' => ($this->get_article()->get_publishing_state() != Articles::PUBLISHED_DATE))
			));

			$publication_fieldset->add_field(new FormFieldCheckbox('end_date_enable', $this->lang['articles.form.end.date.enable'], $this->get_article()->end_date_enabled(), 
				array('hidden' => ($this->get_article()->get_publishing_state() != Articles::PUBLISHED_DATE),
					'events' => array('click' => '
						if (HTMLForms.getField("end_date_enable").getValue()) {
							HTMLForms.getField("publishing_end_date").enable();
						} else { 
							HTMLForms.getField("publishing_end_date").disable();
						}'
				))
			));

			$publication_fieldset->add_field(new FormFieldDateTime('publishing_end_date', $this->lang['articles.form.publishing_end_date'], $this->get_article()->get_publishing_end_date(), 
				array('hidden' => !$this->get_article()->end_date_enabled())
			));
		}

		$this->build_contribution_fieldset($form);

		$form->add_button(new FormButtonReset());
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);

		$this->form = $form;
	}

	private function build_contribution_fieldset($form)
	{
		if ($this->is_contributor_member())
		{
			$fieldset = new FormFieldsetHTML('contribution', $this->lang['articles.form.contribution']);
			$fieldset->set_description(MessageHelper::display($this->lang['articles.form.contribution.explain'], MessageHelper::WARNING)->render());
			$form->add_fieldset($fieldset);

			$fieldset->add_field(new FormFieldRichTextEditor('contribution_description', $this->lang['articles.form.contribution.description'], '', array('description' => $this->lang['articles.form.contribution.description.explain'])));
		}
	}

	private function is_contributor_member()
	{
		return ($this->get_article()->get_id() === null && !ArticlesAuthorizationsService::check_authorizations()->write() && ArticlesAuthorizationsService::check_authorizations()->contribution());
	}

	private function get_article()
	{
		if($this->article === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if(!empty($id))
			{
				try
				{
					$this->article = ArticlesService::get_article('WHERE id=:id', array('id' => $id));
				}
				catch(RowNotFoundException $e)
				{
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->article = new Articles();
				$this->article->init_default_properties();
			}
		}
		return $this->article;
	}

	private function check_authorizations()
	{
		$article = $this->get_article();
                
		if($article->get_id() === null)
		{
			if (!ArticlesAuthorizationsService::check_authorizations()->write() && !ArticlesAuthorizationsService::check_authorizations()->contribution())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			if (!(ArticlesAuthorizationsService::check_authorizations($article->get_id_category())->moderation() || ArticlesAuthorizationsService::check_authorizations($article->get_id_category())->write() || 
				ArticlesAuthorizationsService::check_authorizations($article->get_id_category())->contribution() && $article->get_author_user()->get_id() == AppContext::get_current_user()->get_id()))
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
	}

	private function save()
	{
		$article = $this->get_article();
		
		$article->set_title($this->form->get_value('title'));
		$article->set_id_category($this->form->get_value('id_category')->get_raw_value());
		$article->set_description($this->form->get_value('description'));
		$article->set_contents($this->form->get_value('contents'));
		
		$author_name_displayed = $this->form->get_value('author_name_displayed') ? $this->form->get_value('author_name_displayed') : Articles::AUTHOR_NAME_NOTDISPLAYED;
		$article->set_author_name_displayed($author_name_displayed);
		$notation_enabled = $this->form->get_value('notation_enabled') ? $this->form->get_value('notation_enabled') : Articles::NOTATION_DISABLED;
		$article->set_notation_enabled($notation_enabled);

		$picture = $this->form->get_value('picture');
		if(!empty($picture))
		{
			$article->set_picture(new Url($picture));
		}

		$article->set_sources($this->form->get_value('sources'));

		if ($this->is_contributor_member())
		{
			$article->set_rewrited_title(Url::encode_rewrite($article->get_title()));
			$article->set_publishing_state(Articles::NOT_PUBLISHED);
			$article->set_date_created(new Date());
			$article->clean_start_and_end_date();
		}
		else
		{
			$article->set_date_created($this->form->get_value('date_created'));
			$rewrited_title = $this->form->get_value('personalize_rewrited_title') ? $this->form->get_value('rewrited_title') : Url::encode_rewrite($article->get_title());
			$article->set_rewrited_title($rewrited_title);

			$article->set_publishing_state($this->form->get_value('publishing_state')->get_raw_value());
			if ($article->get_publishing_state() == Articles::PUBLISHED_DATE)
			{
				$article->set_publishing_start_date($this->form->get_value('publishing_start_date'));

				if ($this->form->get_value('end_date_enable'))
				{
					$article->set_publishing_end_date($this->form->get_value('publishing_end_date'));
				}
				else
				{
					$article->clean_publishing_end_date();
				}
			}
			else
			{
				$article->clean_publishing_start_and_end_date();
			}
		}

		if ($article->get_id() === null)
		{
			$article->set_author_user(AppContext::get_current_user());
			$id_article = ArticlesService::add($article);
		}
		else
		{
			$id_article = $article->get_id();
			ArticlesService::update($article);
		}

		$this->contribution_actions($article, $id_article);
		
		$this->save_keywords($id_article);

		Feed::clear_cache('articles');
	}

	private function contribution_actions(Articles $article, $id_article)
	{
		if ($article->get_id() === null)
		{
			if ($this->is_contributor_member())
			{
				$contribution = new Contribution();
				$contribution->set_id_in_module($id_article);
				$contribution->set_description(stripslashes($article->get_description()));
				$contribution->set_entitled(StringVars::replace_vars($this->lang['articles.form.contribution_entitled'], array('title', $article->get_title())));
				$contribution->set_fixing_url(ArticlesUrlBuilder::edit_article($id_article));
				$contribution->set_poster_id(AppContext::get_current_user()->get_attribute('user_id'));
				$contribution->set_module('articles');
				$contribution->set_auth(
					Authorizations::capture_and_shift_bit_auth(
						ArticlesService::get_categories_manager()->get_heritated_authorizations($article->get_id_category(), Category::MODERATION_AUTHORIZATIONS, Authorizations::AUTH_CHILD_PRIORITY),
						Category::MODERATION_AUTHORIZATIONS, Contribution::CONTRIBUTION_AUTH_BIT
					)
				);
				ContributionService::save_contribution($contribution);
			}
		}
		else
		{
			$corresponding_contributions = ContributionService::find_by_criteria('articles', $id_article);
			if (count($corresponding_contributions) > 0)
			{
				$article_contribution = $corresponding_contributions[0];
				$article_contribution->set_status(Event::EVENT_STATUS_PROCESSED);

				ContributionService::save_contribution($article_contribution);
			}
		}
	}
	
	private function save_keywords($id_article)
	{
		$keywords = $this->form->get_value('keywords');
		
		$bdd_keywords = ArticlesKeywordsService::get_keywords();
		
		$new_keywords = new ArticlesKeywords();
		//We delete all relations in edition
		if ($this->get_article()->get_id() !== null)
		{
			ArticlesKeywordsService::delete_all_keywords_relation($id_article);
		}
		
		foreach ($keywords as $keyword)
		{
			//If the keyword is not in the articles_keywords_table, we add it (+ relation), else we create a relation
			if (!(in_array($keyword, $bdd_keywords)))
			{
				$new_keywords->set_name($keyword);
				$new_keywords->set_rewrited_name(Url::encode_rewrite($keyword));
				ArticlesKeywordsService::add($new_keywords, $id_article);
			}
			else
			{
				$id_keyword = array_search($keyword, $bdd_keywords);
				ArticlesKeywordsService::add_relation($id_keyword, $id_article);
			}
		}
	}
	
	private function redirect()
	{
		$article = $this->get_article();
		$category = ArticlesService::get_categories_manager()->get_categories_cache()->get_category($article->get_id_category());

		if ($this->is_contributor_member() && !$article->is_published())
                {
                        AppContext::get_response()->redirect(UserUrlBuilder::contribution_success()->absolute());
                }
                elseif ($article->is_published())
                {
                        AppContext::get_response()->redirect(ArticlesUrlBuilder::display_article($category->get_id(), $category->get_rewrited_name(), $article->get_id(), $article->get_rewrited_title()));
                }
                else
                {
                        AppContext::get_response()->redirect(ArticlesUrlBuilder::home());
                }
	}				

	private function build_response(View $tpl)
	{
		$article = $this->get_article();
		$category = ArticlesService::get_categories_manager()->get_categories_cache()->get_category($article->get_id_category());

		$response = new ArticlesDisplayResponse();	
		$response->add_breadcrumb_link($this->lang['articles'], ArticlesUrlBuilder::home());

		if ($this->get_article()->get_id() === null)
		{
			$response->add_breadcrumb_link($this->lang['articles.add'], ArticlesUrlBuilder::add_article());
			$response->set_page_title($this->lang['articles.add']);
		}
		else
		{
			$categories = array_reverse(ArticlesService::get_categories_manager()->get_parents($this->get_article()->get_id_category(), true));
			foreach ($categories as $id => $category)
			{
				if ($id != Category::ROOT_CATEGORY)
					$response->add_breadcrumb_link($category->get_name(), ArticlesUrlBuilder::display_category($id, $category->get_rewrited_name()));
			}
			$category = $categories[$this->get_article()->get_id_category()];
			$response->add_breadcrumb_link($this->get_article()->get_title(), ArticlesUrlBuilder::display_article($category->get_id(), $category->get_rewrited_name(), $this->get_article()->get_id(), $this->get_article()->get_rewrited_title()));

			$response->add_breadcrumb_link($this->lang['articles.edit'], ArticlesUrlBuilder::edit_article($article->get_id()));
			$response->set_page_title($this->lang['articles.edit']);
		}

		return $response->display($tpl);
	}
}
?>