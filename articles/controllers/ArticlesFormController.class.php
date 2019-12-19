<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Patrick DUBEAU <daaxwizeman@gmail.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 19
 * @since       PHPBoost 4.0 - 2013 02 27
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ArticlesFormController extends ModuleController
{
	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var FormButtonSubmit
	 */
	private $submit_button;

	private $tpl;

	private $lang;
	private $common_lang;

	private $article;
	private $is_new_article;

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

		$this->tpl->put_all(array(
			'FORM' => $this->form->display(),
			'C_TINYMCE_EDITOR' => AppContext::get_current_user()->get_editor() == 'TinyMCE'
		));

		return $this->build_response($this->tpl);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'articles');
		$this->tpl = new FileTemplate('articles/ArticlesFormController.tpl');
		$this->tpl->add_lang($this->lang);
		$this->common_lang = LangLoader::get('common');
	}

	private function build_form(HTTPRequestCustom $request)
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTMLHeading('articles', $this->get_article()->get_id() === null ? $this->lang['articles.add.item'] : $this->lang['articles.edit.item']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('title', $this->common_lang['form.title'], $this->get_article()->get_title(),
			array('required' => true)
		));

		if (CategoriesAuthorizationsService::check_authorizations($this->get_article()->get_id_category())->moderation())
		{
			$fieldset->add_field(new FormFieldCheckbox('personalize_rewrited_title', $this->common_lang['form.rewrited_name.personalize'], $this->get_article()->rewrited_title_is_personalized(),
				array(
					'events' => array('click' =>'
						if (HTMLForms.getField("personalize_rewrited_title").getValue()) {
							HTMLForms.getField("rewrited_title").enable();
						} else {
							HTMLForms.getField("rewrited_title").disable();
						}'
					)
				)
			));

			$fieldset->add_field(new FormFieldTextEditor('rewrited_title', $this->common_lang['form.rewrited_name'], $this->get_article()->get_rewrited_title(),
				array('description' => $this->common_lang['form.rewrited_name.description'],
				      'hidden' => !$this->get_article()->rewrited_title_is_personalized()),
				array(new FormFieldConstraintRegex('`^[a-z0-9\-]+$`iu'))
			));
		}

		if (CategoriesService::get_categories_manager()->get_categories_cache()->has_categories())
		{
			$search_category_children_options = new SearchCategoryChildrensOptions();
			$search_category_children_options->add_authorizations_bits(Category::CONTRIBUTION_AUTHORIZATIONS);
			$search_category_children_options->add_authorizations_bits(Category::WRITE_AUTHORIZATIONS);
			$fieldset->add_field(CategoriesService::get_categories_manager()->get_select_categories_form_field('id_category', $this->common_lang['form.category'], $this->get_article()->get_id_category(), $search_category_children_options));
		}

		$fieldset->add_field(new FormFieldCheckbox('enable_description', $this->lang['articles.decription.enabled'], $this->get_article()->get_description_enabled(),
			array('description' => StringVars::replace_vars($this->lang['articles.decription.enabled.annex'],
			array(
				'number' => ArticlesConfig::load()->get_number_character_to_cut())),
				'events' => array('click' => '
					if (HTMLForms.getField("enable_description").getValue()) {
						HTMLForms.getField("description").enable();
					} else {
						HTMLForms.getField("description").disable();
					}'
		))));

		$fieldset->add_field(new FormFieldRichTextEditor('description', StringVars::replace_vars($this->lang['articles.decription'],
			array('number' =>ArticlesConfig::load()->get_number_character_to_cut())), $this->get_article()->get_description(),
			array('rows' => 3, 'hidden' => !$this->get_article()->get_description_enabled())
		));

		$fieldset->add_field(new FormFieldRichTextEditor('contents', $this->common_lang['form.contents'], $this->get_article()->get_contents(),
			array('rows' => 15, 'required' => true)
		));

		$fieldset->add_field(new FormFieldActionLink('add_page', $this->lang['articles.add.page'] , 'javascript:bbcode_page();', 'fa-pagebreak'));

		if ($this->get_article()->get_author_name_displayed() == true)
		{
			$fieldset->add_field(new FormFieldCheckbox('author_custom_name_enabled', $this->common_lang['form.author_custom_name_enabled'], $this->get_article()->is_author_custom_name_enabled(),
				array(
					'events' => array('click' => '
						if (HTMLForms.getField("author_custom_name_enabled").getValue()) {
							HTMLForms.getField("author_custom_name").enable();
						} else {
							HTMLForms.getField("author_custom_name").disable();
						}'
					)
				)
			));

			$fieldset->add_field(new FormFieldTextEditor('author_custom_name', $this->common_lang['form.author_custom_name'], $this->get_article()->get_author_custom_name(), array(
				'hidden' => !$this->get_article()->is_author_custom_name_enabled(),
			)));
		}

		$other_fieldset = new FormFieldsetHTML('other', $this->common_lang['form.other']);
		$form->add_fieldset($other_fieldset);

		$other_fieldset->add_field(new FormFieldCheckbox('author_name_displayed', LangLoader::get_message('config.author_displayed', 'admin-common'), $this->get_article()->get_author_name_displayed()));

		$other_fieldset->add_field(new FormFieldUploadPictureFile('picture', $this->common_lang['form.picture'], $this->get_article()->get_picture()->relative()));

		$other_fieldset->add_field(KeywordsService::get_keywords_manager()->get_form_field($this->get_article()->get_id(), 'keywords', $this->common_lang['form.keywords'],
			array('description' => $this->common_lang['form.keywords.description'])
		));

		$other_fieldset->add_field(new FormFieldSelectSources('sources', $this->common_lang['form.sources'], $this->get_article()->get_sources()));

		if (CategoriesAuthorizationsService::check_authorizations($this->get_article()->get_id_category())->moderation())
		{
			$publication_fieldset = new FormFieldsetHTML('publication', $this->common_lang['form.approbation']);
			$form->add_fieldset($publication_fieldset);

			$publication_fieldset->add_field(new FormFieldDateTime('date_created', $this->common_lang['form.date.creation'], $this->get_article()->get_date_created(),
				array('required' => true)
			));

			if (!$this->get_article()->is_published())
			{
				$publication_fieldset->add_field(new FormFieldCheckbox('update_creation_date', $this->common_lang['form.update.date.creation'], false,
					array('hidden' => $this->get_article()->get_status() != Article::NOT_PUBLISHED)
				));
			}

			$publication_fieldset->add_field(new FormFieldSimpleSelectChoice('publishing_state', $this->common_lang['form.approbation'], $this->get_article()->get_publishing_state(),
				array(
					new FormFieldSelectChoiceOption($this->common_lang['form.approbation.not'], Article::NOT_PUBLISHED),
					new FormFieldSelectChoiceOption($this->common_lang['form.approbation.now'], Article::PUBLISHED_NOW),
					new FormFieldSelectChoiceOption($this->common_lang['status.approved.date'], Article::PUBLISHED_DATE),
				),
				array(
					'events' => array('change' => '
						if (HTMLForms.getField("publishing_state").getValue() == 2) {
							jQuery("#' . __CLASS__ . '_publishing_start_date_field").show();
							HTMLForms.getField("end_date_enable").enable();
						} else {
							jQuery("#' . __CLASS__ . '_publishing_start_date_field").hide();
							HTMLForms.getField("end_date_enable").disable();
						}'
					)
				)
			));

			$publication_fieldset->add_field(new FormFieldDateTime('publishing_start_date', $this->common_lang['form.date.start'],
				($this->get_article()->get_publishing_start_date() === null ? new Date() : $this->get_article()->get_publishing_start_date()),
				array('hidden' => ($this->get_article()->get_publishing_state() != Article::PUBLISHED_DATE))
			));

			$publication_fieldset->add_field(new FormFieldCheckbox('end_date_enable', $this->common_lang['form.date.end.enable'], $this->get_article()->end_date_enabled(),
				array(
					'hidden' => ($this->get_article()->get_publishing_state() != Article::PUBLISHED_DATE),
					'events' => array('click' => '
						if (HTMLForms.getField("end_date_enable").getValue()) {
							HTMLForms.getField("publishing_end_date").enable();
						} else {
							HTMLForms.getField("publishing_end_date").disable();
						}'
					)
				)
			));

			$publication_fieldset->add_field(new FormFieldDateTime('publishing_end_date', $this->common_lang['form.date.end'],
				($this->get_article()->get_publishing_end_date() === null ? new date() : $this->get_article()->get_publishing_end_date()),
				array('hidden' => !$this->get_article()->end_date_enabled())
			));
		}

		$this->build_contribution_fieldset($form);

		$fieldset->add_field(new FormFieldHidden('referrer', $request->get_url_referrer()));

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
				if (TextHelper::substr(trim($article_contents), 0, 6) != '[page]')
				{
					$article_contents = '[page]&nbsp;[/page]' . $article_contents;
				}

				//Removing [page] bbcode
				$article_contents_clean = preg_split('`\[page\].+\[/page\](.*)`usU', $article_contents, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

				//Retrieving pages
				preg_match_all('`\[page\]([^[]+)\[/page\]`uU', $article_contents, $array_page);

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
		return (!CategoriesAuthorizationsService::check_authorizations()->write() && CategoriesAuthorizationsService::check_authorizations()->contribution());
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
				$this->is_new_article = true;
				$this->article = new Article();
				$this->article->init_default_properties(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY));
			}
		}
		return $this->article;
	}

	private function check_authorizations()
	{
		$article = $this->get_article();

		if ($article->get_id() === null)
		{
			if (!$article->is_authorized_to_add())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			if (!$article->is_authorized_to_edit())
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

		if (CategoriesService::get_categories_manager()->get_categories_cache()->has_categories())
			$article->set_id_category($this->form->get_value('id_category')->get_raw_value());

		$article->set_description(($this->form->get_value('enable_description') ? $this->form->get_value('description') : ''));
		$article->set_contents($this->form->get_value('contents'));

		$author_name_displayed = $this->form->get_value('author_name_displayed') ? $this->form->get_value('author_name_displayed') : Article::AUTHOR_NAME_NOTDISPLAYED;
		$article->set_author_name_displayed($author_name_displayed);
		$article->set_picture(new Url($this->form->get_value('picture')));

		if ($this->get_article()->get_author_name_displayed() == true)
			$article->set_author_custom_name(($this->form->get_value('author_custom_name') && $this->form->get_value('author_custom_name') !== $article->get_author_user()->get_display_name() ? $this->form->get_value('author_custom_name') : ''));

		$article->set_sources($this->form->get_value('sources'));

		if (!CategoriesAuthorizationsService::check_authorizations($article->get_id_category())->moderation())
		{
			if ($article->get_id() === null)
				$article->set_date_created(new Date());

			$article->set_rewrited_title(Url::encode_rewrite($article->get_title()));
			$article->clean_publishing_start_and_end_date();

			if (CategoriesAuthorizationsService::check_authorizations($article->get_id_category())->contribution() && !CategoriesAuthorizationsService::check_authorizations($article->get_id_category())->write())
				$article->set_publishing_state(Article::NOT_PUBLISHED);
		}
		else
		{
			if ($this->form->get_value('update_creation_date'))
			{
				$article->set_date_created(new Date());
			}
			else
			{
				$article->set_date_created($this->form->get_value('date_created'));
			}

			$rewrited_title = $this->form->get_value('rewrited_title', '');
			$rewrited_title = $this->form->get_value('personalize_rewrited_title') && !empty($rewrited_title) ? $rewrited_title : Url::encode_rewrite($article->get_title());
			$article->set_rewrited_title($rewrited_title);

			$article->set_publishing_state($this->form->get_value('publishing_state')->get_raw_value());
			if ($article->get_publishing_state() == Article::PUBLISHED_DATE)
			{
				$config = ArticlesConfig::load();
				$deferred_operations = $config->get_deferred_operations();

				$old_start_date = $article->get_publishing_start_date();
				$start_date = $this->form->get_value('publishing_start_date');
				$article->set_publishing_start_date($start_date);

				if ($old_start_date !== null && $old_start_date->get_timestamp() != $start_date->get_timestamp() && in_array($old_start_date->get_timestamp(), $deferred_operations))
				{
					$key = array_search($old_start_date->get_timestamp(), $deferred_operations);
					unset($deferred_operations[$key]);
				}

				if (!in_array($start_date->get_timestamp(), $deferred_operations))
					$deferred_operations[] = $start_date->get_timestamp();

				if ($this->form->get_value('end_date_enable'))
				{
					$old_end_date = $article->get_publishing_end_date();
					$end_date = $this->form->get_value('publishing_end_date');
					$article->set_publishing_end_date($end_date);

					if ($old_end_date !== null && $old_end_date->get_timestamp() != $end_date->get_timestamp() && in_array($old_end_date->get_timestamp(), $deferred_operations))
					{
						$key = array_search($old_end_date->get_timestamp(), $deferred_operations);
						unset($deferred_operations[$key]);
					}

					if (!in_array($end_date->get_timestamp(), $deferred_operations))
						$deferred_operations[] = $end_date->get_timestamp();
				}
				else
				{
					$article->clean_publishing_end_date();
				}

				$config->set_deferred_operations($deferred_operations);
				ArticlesConfig::save();
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

		KeywordsService::get_keywords_manager()->put_relations($id_article, $this->form->get_value('keywords'));

		ArticlesService::clear_cache();
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
				$contribution->set_entitled($article->get_title());
				$contribution->set_fixing_url(ArticlesUrlBuilder::edit_article($id_article)->relative());
				$contribution->set_poster_id(AppContext::get_current_user()->get_id());
				$contribution->set_module('articles');
				$contribution->set_auth(
					Authorizations::capture_and_shift_bit_auth(
						CategoriesService::get_categories_manager()->get_heritated_authorizations($article->get_id_category(), Category::MODERATION_AUTHORIZATIONS, Authorizations::AUTH_CHILD_PRIORITY),
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
				foreach ($corresponding_contributions as $contribution)
				{
					$contribution->set_status(Event::EVENT_STATUS_PROCESSED);
					ContributionService::save_contribution($contribution);
				}
			}
		}
		$article->set_id($id_article);
	}

	private function redirect()
	{
		$article = $this->get_article();
		$category = $article->get_category();

		if ($this->is_new_article && $this->is_contributor_member() && !$article->is_published())
		{
			DispatchManager::redirect(new UserContributionSuccessController());
		}
		elseif ($article->is_published())
		{
			if ($this->is_new_article)
				AppContext::get_response()->redirect(ArticlesUrlBuilder::display_article($category->get_id(), $category->get_rewrited_name(), $article->get_id(), $article->get_rewrited_title(), AppContext::get_request()->get_getint('page', 1)), StringVars::replace_vars($this->lang['articles.message.success.add'], array('title' => $article->get_title())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : ArticlesUrlBuilder::display_article($category->get_id(), $category->get_rewrited_name(), $article->get_id(), $article->get_rewrited_title(), AppContext::get_request()->get_getint('page', 1))), StringVars::replace_vars($this->lang['articles.message.success.edit'], array('title' => $article->get_title())));
		}
		else
		{
			if ($this->is_new_article)
				AppContext::get_response()->redirect(ArticlesUrlBuilder::display_pending_articles(), StringVars::replace_vars($this->lang['articles.message.success.add'], array('title' => $article->get_title())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : ArticlesUrlBuilder::display_pending_articles()), StringVars::replace_vars($this->lang['articles.message.success.edit'], array('title' => $article->get_title())));
		}
	}

	private function build_response(View $tpl)
	{
		$article = $this->get_article();

		$location_id = $article->get_id() ? 'article-edit-'. $article->get_id() : '';

		$response = new SiteDisplayResponse($tpl, $location_id);
		$graphical_environment = $response->get_graphical_environment();

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['articles.module.title'], ArticlesUrlBuilder::home());

		if ($article->get_id() === null)
		{
			$breadcrumb->add($this->lang['articles.add.item'], ArticlesUrlBuilder::add_article($article->get_id_category()));
			$graphical_environment->set_page_title($this->lang['articles.add.item'], $this->lang['articles.module.title']);
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['articles.add.item']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(ArticlesUrlBuilder::add_article($article->get_id_category()));
		}
		else
		{
			$categories = array_reverse(CategoriesService::get_categories_manager()->get_parents($article->get_id_category(), true));
			foreach ($categories as $id => $category)
			{
				if ($category->get_id() != Category::ROOT_CATEGORY)
					$breadcrumb->add($category->get_name(), ArticlesUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
			}
			$breadcrumb->add($article->get_title(), ArticlesUrlBuilder::display_article($category->get_id(), $category->get_rewrited_name(), $article->get_id(), $article->get_rewrited_title()));

			$breadcrumb->add($this->lang['articles.edit.item'], ArticlesUrlBuilder::edit_article($article->get_id()));

			if (!AppContext::get_session()->location_id_already_exists($location_id))
				$graphical_environment->set_location_id($location_id);

			$graphical_environment->set_page_title($this->lang['articles.edit.item'], $this->lang['articles.module.title']);
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['articles.edit.item']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(ArticlesUrlBuilder::edit_article($article->get_id()));
		}

		return $response;
	}
}
?>
