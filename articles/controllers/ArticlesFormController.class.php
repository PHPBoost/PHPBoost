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
		$this->build_form($request);
                
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
		$this->lang = LangLoader::get('common', 'articles');
		$this->tpl = new FileTemplate('articles/ArticlesFormController.tpl');
		$this->tpl->add_lang($this->lang);
	}
	
	private function build_form($request)
	{
		$common_lang = LangLoader::get('common');
		
		$form = new HTMLForm(__CLASS__);
		
		$fieldset = new FormFieldsetHTML('articles', $this->lang['articles']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('title', $this->lang['articles.form.title'], $this->get_article()->get_title(), 
			array('required' => true)
		));

		if (!$this->is_contributor_member())
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
		$fieldset->add_field(ArticlesService::get_categories_manager()->get_select_categories_form_field('id_category', LangLoader::get_message('category', 'categories-common'), ($this->article->get_id() === null ? $request->get_getint('id_category', 0) : $this->get_article()->get_id_category()), $search_category_children_options));
		
		$fieldset->add_field(new FormFieldCheckbox('enable_description', $this->lang['articles.form.description_enabled'], $this->get_article()->get_description_enabled(), 
			array('description' => StringVars::replace_vars($this->lang['articles.form.description_enabled.description'], 
			array('number' => ArticlesConfig::load()->get_number_character_to_cut())), 
				'events' => array('click' => '
					if (HTMLForms.getField("enable_description").getValue()) {
						HTMLForms.getField("description").enable();
					} else { 
						HTMLForms.getField("description").disable();
					}'
		))));
		
		$fieldset->add_field(new FormFieldRichTextEditor('description', StringVars::replace_vars($this->lang['articles.form.description'],
			array('number' =>ArticlesConfig::load()->get_number_character_to_cut())), $this->get_article()->get_description(),
			array('rows' => 3, 'hidden' => !$this->get_article()->get_description_enabled())
		));
		
		$fieldset->add_field(new FormFieldRichTextEditor('contents', $common_lang['form.contents'], $this->get_article()->get_contents(),
			array('rows' => 15, 'required' => true)
		));
		
		$onclick_action = 'javascript:bbcode_page();';
		$fieldset->add_field(new FormFieldActionLink('add_page', $this->lang['articles.form.add_page'] , $onclick_action, PATH_TO_ROOT . '/articles/templates/images/pagebreak.png'));
		
		$other_fieldset = new FormFieldsetHTML('other', $common_lang['form.other']);
		$form->add_fieldset($other_fieldset);

		$other_fieldset->add_field(new FormFieldCheckbox('author_name_displayed', $this->lang['articles.form.author_name_displayed'], $this->get_article()->get_author_name_displayed()));

		$other_fieldset->add_field(new FormFieldCheckbox('notation_enabled', $this->lang['articles.form.notation_enabled'], $this->get_article()->get_notation_enabled()));
		
		$other_fieldset->add_field(new FormFieldUploadFile('picture', $this->lang['articles.form.picture'], $this->get_article()->get_picture()->relative(), array('description' => $this->lang['articles.form.picture.description'])));

		$other_fieldset->add_field(ArticlesService::get_keywords_manager()->get_form_field($this->get_article()->get_id(), 'keywords', $common_lang['form.keywords'],  
			array('description' => $this->lang['articles.form.keywords.description'])
		));

		$other_fieldset->add_field(new ArticlesFormFieldSelectSources('sources', $common_lang['form.sources'], $this->get_article()->get_sources()));

		if (!$this->is_contributor_member())
		{
			$publication_fieldset = new FormFieldsetHTML('publication', $common_lang['form.approbation']);
			$form->add_fieldset($publication_fieldset);

			$publication_fieldset->add_field(new FormFieldDateTime('date_created', $common_lang['form.date.creation'], $this->get_article()->get_date_created()));

			$publication_fieldset->add_field(new FormFieldSimpleSelectChoice('publishing_state', $common_lang['form.approbation'], $this->get_article()->get_publishing_state(),
				array(
					new FormFieldSelectChoiceOption($common_lang['form.approbation.not'], Article::NOT_PUBLISHED),
					new FormFieldSelectChoiceOption($common_lang['form.approbation.now'], Article::PUBLISHED_NOW),
					new FormFieldSelectChoiceOption($common_lang['form.approbation.date'], Article::PUBLISHED_DATE),
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

			$publication_fieldset->add_field(new FormFieldDateTime('publishing_start_date', $common_lang['form.date.start'], 
				($this->get_article()->get_publishing_start_date() === null ? new Date() : $this->get_article()->get_publishing_start_date()), 
				array('hidden' => ($this->get_article()->get_publishing_state() != Article::PUBLISHED_DATE))
			));

			$publication_fieldset->add_field(new FormFieldCheckbox('end_date_enable', $common_lang['form.date.end.enable'], $this->get_article()->end_date_enabled(), 
				array('hidden' => ($this->get_article()->get_publishing_state() != Article::PUBLISHED_DATE),
					'events' => array('click' => '
						if (HTMLForms.getField("end_date_enable").getValue()) {
							HTMLForms.getField("publishing_end_date").enable();
						} else { 
							HTMLForms.getField("publishing_end_date").disable();
						}'
				))
			));

			$publication_fieldset->add_field(new FormFieldDateTime('publishing_end_date', $common_lang['form.date.end'], 
				($this->get_article()->get_publishing_end_date() === null ? new date() : $this->get_article()->get_publishing_end_date()), 
				array('hidden' => !$this->get_article()->end_date_enabled())
			));
		}

		$this->build_contribution_fieldset($form);
		
		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());
		
		$this->form = $form;
		
		// Positionnement à la bonne page quand on édite un article avec plusieurs pages
		if ($this->get_article()->get_id() !== null)
		{
			$current_page = $request->get_getstring('page', '');
			
			$this->tpl->put('C_PAGE', !empty($current_page));
			
			if (!empty($current_page))
			{
				$article_contents = $this->article->get_contents();
				
				//If article doesn't begin with a page, we insert one
				if (substr(trim($article_contents), 0, 6) != '[page]')
				{
					$article_contents = '[page]&nbsp;[/page]' . $article_contents;
				}
				
				//Removing [page] bbcode
				$article_contents_clean = preg_split('`\[page\].+\[/page\](.*)`Us', $article_contents, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
				
				//Retrieving pages 
				preg_match_all('`\[page\]([^[]+)\[/page\]`U', $article_contents, $array_page);

				$page_name = (isset($array_page[1][$current_page-1]) && $array_page[1][$current_page-1] != '&nbsp;') ? $array_page[1][($current_page-1)] : '';
				
				$this->tpl->put('PAGE', TextHelper::to_js_string($page_name));
			}
		}
	}

	private function build_contribution_fieldset($form)
	{
		if ($this->get_article()->get_id() === null && $this->is_contributor_member())
		{
			$fieldset = new FormFieldsetHTML('contribution', LangLoader::get_message('contribution', 'user-common'));
			$fieldset->set_description(MessageHelper::display(LangLoader::get_message('contribution.explain', 'user-common'), MessageHelper::WARNING)->render());
			$form->add_fieldset($fieldset);

			$fieldset->add_field(new FormFieldRichTextEditor('contribution_description', LangLoader::get_message('contribution.description', 'user-common'), '', 
				array('description' => LangLoader::get_message('contribution.description.explain', 'user-common'))));
		}
	}

	private function is_contributor_member()
	{
		$id_category = AppContext::get_request()->get_getint('id_category', 0);
		return (!ArticlesAuthorizationsService::check_authorizations($id_category)->write() && ArticlesAuthorizationsService::check_authorizations($id_category)->contribution());
	}

	private function get_article()
	{
		if ($this->article === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try
				{
					$this->article = ArticlesService::get_article('WHERE articles.id=:id', array('id' => $id));
				}
				catch(RowNotFoundException $e)
				{
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->article = new Article();
				$this->article->init_default_properties();
			}
		}
		return $this->article;
	}

	private function check_authorizations()
	{
		$article = $this->get_article();
                
		if ($article->get_id() === null)
		{
			if (!$article->is_authorized_add())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			if (!$article->is_authorized_edit())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		if (AppContext::get_current_user()->is_readonly())
		{
			$controller = PHPBoostErrors::user_in_read_only();
			DispatchManager::redirect($controller);
		}
	}

	private function save()
	{
		$article = $this->get_article();
		
		$article->set_title($this->form->get_value('title'));
                $article->set_id_category($this->form->get_value('id_category')->get_raw_value());
		$article->set_description(($this->form->get_value('enable_description') ? $this->form->get_value('description') : ''));
		$article->set_contents($this->form->get_value('contents'));
		
		$author_name_displayed = $this->form->get_value('author_name_displayed') ? $this->form->get_value('author_name_displayed') : Article::AUTHOR_NAME_NOTDISPLAYED;
		$article->set_author_name_displayed($author_name_displayed);
		$notation_enabled = $this->form->get_value('notation_enabled') ? $this->form->get_value('notation_enabled') : Article::NOTATION_DISABLED;
		$article->set_notation_enabled($notation_enabled);
		$article->set_picture(new Url($this->form->get_value('picture')));
		
		$article->set_sources($this->form->get_value('sources'));
		
		if ($article->get_id() === null && $this->is_contributor_member())
		{
			$article->set_rewrited_title(Url::encode_rewrite($article->get_title()));
			$article->set_publishing_state(Article::NOT_PUBLISHED);
			$article->set_date_created(new Date());
			$article->clean_publishing_start_and_end_date();
		}
		else
		{
			$article->set_date_created($this->form->get_value('date_created'));
			$rewrited_title = $this->form->get_value('rewrited_title', '');
			$rewrited_title = $this->form->get_value('personalize_rewrited_title') && !empty($rewrited_title) ? $rewrited_title : Url::encode_rewrite($article->get_title());
			$article->set_rewrited_title($rewrited_title);

			$article->set_publishing_state($this->form->get_value('publishing_state')->get_raw_value());
			if ($article->get_publishing_state() == Article::PUBLISHED_DATE)
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
			$now = new Date();
			$article->set_date_updated($now);
			$id_article = $article->get_id();
			ArticlesService::update($article);
		}

		$this->contribution_actions($article, $id_article);
		
		ArticlesService::get_keywords_manager()->put_relations($id_article, $this->form->get_value('keywords'));

		Feed::clear_cache('articles');
	}

	private function contribution_actions(Article $article, $id_article)
	{
		if ($article->get_id() === null)
		{
			if ($this->is_contributor_member())
			{
				$contribution = new Contribution();
				$contribution->set_id_in_module($id_article);
				$contribution->set_description(stripslashes($this->form->get_value('contribution_description')));
				$contribution->set_entitled(StringVars::replace_vars($this->lang['articles.form.contribution_entitled'], array('module_name' => $this->lang['articles'], 'title' => $article->get_title())));
				$contribution->set_fixing_url(ArticlesUrlBuilder::edit_article($id_article)->relative());
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
		$article->set_id($id_article);
	}
	
	private function redirect()
	{
		$article = $this->get_article();
		$category = ArticlesService::get_categories_manager()->get_categories_cache()->get_category($article->get_id_category());

		if ($article->get_id() === null && $this->is_contributor_member() && !$article->is_published())
		{
			AppContext::get_response()->redirect(UserUrlBuilder::contribution_success());
		}
		elseif ($article->is_published())
		{
			AppContext::get_response()->redirect(ArticlesUrlBuilder::display_article($category->get_id(), $category->get_rewrited_name(), $article->get_id(), $article->get_rewrited_title()));
		}
		else
		{
			AppContext::get_response()->redirect(ArticlesUrlBuilder::display_pending_articles());
		}
	}

	private function build_response(View $tpl)
	{
		$article = $this->get_article();
		
		$response = new SiteDisplayResponse($tpl);
		$graphical_environment = $response->get_graphical_environment();
		
		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['articles'], ArticlesUrlBuilder::home());

		if ($article->get_id() === null)
		{
			$breadcrumb->add($this->lang['articles.add'], ArticlesUrlBuilder::add_article($article->get_id_category()));
			$graphical_environment->set_page_title($this->lang['articles.add']);
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['articles.add']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(ArticlesUrlBuilder::add_article($article->get_id_category()));
		}
		else
		{
			$categories = array_reverse(ArticlesService::get_categories_manager()->get_parents($article->get_id_category(), true));
			foreach ($categories as $id => $category)
			{
				if ($category->get_id() != Category::ROOT_CATEGORY)
					$breadcrumb->add($category->get_name(), ArticlesUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
			}
			$breadcrumb->add($article->get_title(), ArticlesUrlBuilder::display_article($category->get_id(), $category->get_rewrited_name(), $article->get_id(), $article->get_rewrited_title()));

			$breadcrumb->add($this->lang['articles.edit'], ArticlesUrlBuilder::edit_article($article->get_id()));
			$graphical_environment->set_page_title($this->lang['articles.edit']);
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['articles.edit']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(ArticlesUrlBuilder::edit_article($article->get_id()));
		}

		return $response;
	}
}
?>
