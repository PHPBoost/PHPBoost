<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 11 17
 * @since       PHPBoost 5.2 - 2020 06 15
*/

class PagesItemFormController extends ModuleController
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
	private $common_lang;

	private $config;

	private $item;
	private $is_new_item;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->check_authorizations();

		$this->build_form($request);

		$view = new StringTemplate('# INCLUDE FORM #');
		$view->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->redirect();
		}

		$view->put('FORM', $this->form->display());

		return $this->generate_response($view);
	}

	private function init()
	{
		$this->config = PagesConfig::load();
		$this->lang = LangLoader::get('common', 'pages');
		$this->common_lang = LangLoader::get('common');
	}

	private function build_form(HTTPRequestCustom $request)
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTMLHeading('pages',  $this->get_page()->get_id() === null ? $this->lang['pages.add'] : $this->lang['pages.edit'] . $this->get_page()->get_title());
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('title', $this->common_lang['form.name'], $this->get_page()->get_title(), array('required' => true)));

		if (CategoriesAuthorizationsService::check_authorizations($this->get_page()->get_id_category())->moderation())
		{
			$fieldset->add_field(new FormFieldCheckbox('personalize_rewrited_title', $this->common_lang['form.rewrited_name.personalize'], $this->get_page()->rewrited_title_is_personalized(),
			array(
				'events' => array('click' => '
				if (HTMLForms.getField("personalize_rewrited_title").getValue()) {
					HTMLForms.getField("rewrited_title").enable();
				} else {
					HTMLForms.getField("rewrited_title").disable();
				}'
			))));

			$fieldset->add_field(new FormFieldTextEditor('rewrited_title', $this->common_lang['form.rewrited_name'], $this->get_page()->get_rewrited_title(), array(
				'description' => $this->common_lang['form.rewrited_name.description'],
				'hidden' => ($request->is_post_method() ? !$request->get_postbool(__CLASS__ . '_personalize_rewrited_title', false) : !$this->get_page()->rewrited_title_is_personalized())
			), array(new FormFieldConstraintRegex('`^[a-z0-9\-]+$`iu'))));
		}

		if (CategoriesService::get_categories_manager()->get_categories_cache()->has_categories())
		{
			$search_category_children_options = new SearchCategoryChildrensOptions();
			$search_category_children_options->add_authorizations_bits(Category::CONTRIBUTION_AUTHORIZATIONS);
			$search_category_children_options->add_authorizations_bits(Category::WRITE_AUTHORIZATIONS);
			$fieldset->add_field(CategoriesService::get_categories_manager()->get_select_categories_form_field('id_category', $this->common_lang['form.category'], $this->get_page()->get_id_category(), $search_category_children_options));
		}

		$fieldset->add_field(new FormFieldRichTextEditor('content', $this->common_lang['form.description'], $this->get_page()->get_content(),
			array('rows' => 15, 'required' => true)
		));

		$fieldset->add_field(new FormFieldCheckbox('author_display', LangLoader::get_message('config.author.displayed', 'admin-common'), $this->get_page()->get_author_display(),
			array('description' => $this->lang['author.display.explain'],
				'events' => array('click' => '
					if (HTMLForms.getField("author_display").getValue()) {
						HTMLForms.getField("author_custom_name_enabled").enable();
						if (HTMLForms.getField("author_custom_name_enabled").getValue()) {
							HTMLForms.getField("author_custom_name").enable();
						}
					} else {
						HTMLForms.getField("author_custom_name_enabled").disable();
						HTMLForms.getField("author_custom_name").disable();
					}')
			)
		));

		$fieldset->add_field(new FormFieldCheckbox('author_custom_name_enabled', $this->common_lang['form.author_custom_name_enabled'], $this->get_page()->is_author_custom_name_enabled(),
			array(
				'hidden' => ($request->is_post_method() ? !$request->get_postbool(__CLASS__ . '_author_display', false) : !$this->get_page()->get_author_display()),
				'events' => array('click' => '
					if (HTMLForms.getField("author_custom_name_enabled").getValue()) {
						HTMLForms.getField("author_custom_name").enable();
					} else {
						HTMLForms.getField("author_custom_name").disable();
					}')
			)
		));

		$fieldset->add_field(new FormFieldTextEditor('author_custom_name', $this->common_lang['form.author_custom_name'], $this->get_page()->get_author_custom_name(), array(
			'hidden' => ($request->is_post_method() ? !$request->get_postbool(__CLASS__ . '_author_custom_name_enabled', false) : !$this->get_page()->is_author_custom_name_enabled() || !$this->get_page()->get_author_display())
		)));

		$other_fieldset = new FormFieldsetHTML('other', $this->common_lang['form.other']);
		$form->add_fieldset($other_fieldset);

		$other_fieldset->add_field(new FormFieldUploadPictureFile('thumbnail', $this->common_lang['form.picture'], $this->get_page()->get_thumbnail()->relative()));

		$other_fieldset->add_field(KeywordsService::get_keywords_manager()->get_form_field($this->get_page()->get_id(), 'keywords', $this->common_lang['form.keywords'], array('description' => $this->common_lang['form.keywords.description'])));

		$other_fieldset->add_field(new FormFieldSelectSources('sources', $this->common_lang['form.sources'], $this->get_page()->get_sources()));

		if (CategoriesAuthorizationsService::check_authorizations($this->get_page()->get_id_category())->moderation())
		{
			$publication_fieldset = new FormFieldsetHTML('publication', $this->common_lang['form.approbation']);
			$form->add_fieldset($publication_fieldset);

			$publication_fieldset->add_field(new FormFieldDateTime('creation_date', $this->common_lang['form.date.creation'], $this->get_page()->get_creation_date(),
				array('required' => true)
			));

			if (!$this->get_page()->is_published())
			{
				$publication_fieldset->add_field(new FormFieldCheckbox('update_creation_date', $this->common_lang['form.update.date.creation'], false,
					array('hidden' => $this->get_page()->get_status() != Page::NOT_APPROVAL)
				));
			}

			$publication_fieldset->add_field(new FormFieldSimpleSelectChoice('approbation_type', $this->common_lang['form.approbation'], $this->get_page()->get_publication(),
				array(
					new FormFieldSelectChoiceOption($this->common_lang['form.approbation.not'], Page::NOT_APPROVAL),
					new FormFieldSelectChoiceOption($this->common_lang['form.approbation.now'], Page::APPROVAL_NOW),
					new FormFieldSelectChoiceOption($this->common_lang['status.approved.date'], Page::APPROVAL_DATE),
				),
				array(
					'events' => array('change' => '
						if (HTMLForms.getField("approbation_type").getValue() == 2) {
							jQuery("#' . __CLASS__ . '_start_date_field").show();
							HTMLForms.getField("end_date_enabled").enable();
							if (HTMLForms.getField("end_date_enabled").getValue()) {
								HTMLForms.getField("end_date").enable();
							}
						} else {
							jQuery("#' . __CLASS__ . '_start_date_field").hide();
							HTMLForms.getField("end_date_enabled").disable();
							HTMLForms.getField("end_date").disable();
						}'
					)
				)
			));

			$publication_fieldset->add_field($start_date = new FormFieldDateTime('start_date', $this->common_lang['form.date.start'], ($this->get_page()->get_start_date() === null ? new Date() : $this->get_page()->get_start_date()),
				array('hidden' => ($request->is_post_method() ? ($request->get_postint(__CLASS__ . '_approbation_type', 0) != Page::APPROVAL_DATE) : $this->get_page()->get_publication() != Page::APPROVAL_DATE))
			));

			$publication_fieldset->add_field(new FormFieldCheckbox('end_date_enabled', $this->common_lang['form.date.end.enable'], $this->get_page()->is_end_date_enabled(),
				array(
					'hidden' => ($request->is_post_method() ? ($request->get_postint(__CLASS__ . '_approbation_type', 0) != Page::APPROVAL_DATE) : ($this->get_page()->get_publication() != Page::APPROVAL_DATE)),
					'events' => array('click' => '
						if (HTMLForms.getField("end_date_enabled").getValue()) {
							HTMLForms.getField("end_date").enable();
						} else {
							HTMLForms.getField("end_date").disable();
						}'
					)
				)
			));

			$publication_fieldset->add_field($end_date = new FormFieldDateTime('end_date', $this->common_lang['form.date.end'], ($this->get_page()->get_end_date() === null ? new Date() : $this->get_page()->get_end_date()),
				array('hidden' => ($request->is_post_method() ? !$request->get_postbool(__CLASS__ . '_end_date_enabled', false) : !$this->get_page()->is_end_date_enabled()))
			));

			$end_date->add_form_constraint(new FormConstraintFieldsDifferenceSuperior($start_date, $end_date));
		}

		$this->build_contribution_fieldset($form);

		$fieldset->add_field(new FormFieldHidden('referrer', $request->get_url_referrer()));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	private function build_contribution_fieldset($form)
	{
		$user_common = LangLoader::get('user-common');
		if ($this->get_page()->get_id() === null && $this->is_contributor_member())
		{
			$fieldset = new FormFieldsetHTML('contribution', $user_common['contribution']);
			$fieldset->set_description(MessageHelper::display($user_common['contribution.extended.explain'], MessageHelper::WARNING)->render());
			$form->add_fieldset($fieldset);

			$fieldset->add_field(new FormFieldRichTextEditor('contribution_description', $user_common['contribution.description'], '', array('description' => LangLoader::get_message('contribution.description.explain', 'user-common'))));
		}
		elseif ($this->get_page()->is_published() && $this->get_page()->is_authorized_to_edit() && !AppContext::get_current_user()->check_level(User::ADMIN_LEVEL))
		{
			$fieldset = new FormFieldsetHTML('member_edition', $user_common['contribution.member.edition']);
			$fieldset->set_description(MessageHelper::display($user_common['contribution.member.edition.explain'], MessageHelper::WARNING)->render());
			$form->add_fieldset($fieldset);

			$fieldset->add_field(new FormFieldRichTextEditor('edition_description', $user_common['contribution.member.edition.description'], '',
				array('description' => $user_common['contribution.member.edition.description.desc'])
			));
		}
	}

	private function is_contributor_member()
	{
		return (!CategoriesAuthorizationsService::check_authorizations()->write() && CategoriesAuthorizationsService::check_authorizations()->contribution());
	}

	private function get_page()
	{
		if ($this->item === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try {
					$this->item = PagesService::get_page('WHERE pages.id=:id', array('id' => $id));
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->is_new_item = true;
				$this->item = new Page();
				$this->item->init_default_properties(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY));
			}
		}
		return $this->item;
	}

	private function check_authorizations()
	{
		$item = $this->get_page();

		if ($item->get_id() === null)
		{
			if (!$item->is_authorized_to_add())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			if (!$item->is_authorized_to_edit())
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
		$item = $this->get_page();

		$item->set_title($this->form->get_value('title'));

		$rewrited_title = $this->form->get_value('rewrited_title', '');
		$rewrited_title = $this->form->get_value('personalize_rewrited_title') && !empty($rewrited_title) ? $rewrited_title : Url::encode_rewrite($item->get_title());
		$item->set_rewrited_title($rewrited_title);

		if (CategoriesService::get_categories_manager()->get_categories_cache()->has_categories())
			$item->set_id_category($this->form->get_value('id_category')->get_raw_value());

		$item->set_content($this->form->get_value('content'));
		$item->set_thumbnail(new Url($this->form->get_value('thumbnail')));

		$item->set_author_display($this->form->get_value('author_display'));
		if($this->form->get_value('author_display'))
			$item->set_author_custom_name(($this->form->get_value('author_custom_name') && $this->form->get_value('author_custom_name') !== $item->get_author_user()->get_display_name() ? $this->form->get_value('author_custom_name') : ''));

		$item->set_sources($this->form->get_value('sources'));

		if (!CategoriesAuthorizationsService::check_authorizations($item->get_id_category())->moderation())
		{
			$item->clean_start_and_end_date();

			if (CategoriesAuthorizationsService::check_authorizations($item->get_id_category())->contribution() && !CategoriesAuthorizationsService::check_authorizations($item->get_id_category())->write())
				$item->set_publication(Page::NOT_APPROVAL);
		}
		else
		{

			if ($this->form->get_value('update_creation_date'))
			{
				$item->set_creation_date(new Date());
			}
			else
			{
				$item->set_creation_date($this->form->get_value('creation_date'));
			}

			$item->set_publication($this->form->get_value('approbation_type')->get_raw_value());
			if ($item->get_publication() == Page::APPROVAL_DATE)
			{
				$deferred_operations = $this->config->get_deferred_operations();

				$old_start_date = $item->get_start_date();
				$start_date = $this->form->get_value('start_date');
				$item->set_start_date($start_date);

				if ($old_start_date !== null && $old_start_date->get_timestamp() != $start_date->get_timestamp() && in_array($old_start_date->get_timestamp(), $deferred_operations))
				{
					$key = array_search($old_start_date->get_timestamp(), $deferred_operations);
					unset($deferred_operations[$key]);
				}

				if (!in_array($start_date->get_timestamp(), $deferred_operations))
					$deferred_operations[] = $start_date->get_timestamp();

				if ($this->form->get_value('end_date_enabled'))
				{
					$old_end_date = $item->get_end_date();
					$end_date = $this->form->get_value('end_date');
					$item->set_end_date($end_date);

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
					$item->clean_end_date();
				}

				$this->config->set_deferred_operations($deferred_operations);
				PagesConfig::save();
			}
			else
			{
				$item->clean_start_and_end_date();
			}
		}

		if ($this->is_new_item)
		{
			$id = PagesService::add($item);
		}
		else
		{
			$item->set_updated_date(new Date());
			$id = $item->get_id();
			PagesService::update($item);
		}

		$this->contribution_actions($item, $id);

		KeywordsService::get_keywords_manager()->put_relations($id, $this->form->get_value('keywords'));

	}

	private function contribution_actions(Page $item, $id)
	{
		if ($this->is_contributor_member())
		{
			$contribution = new Contribution();
			$contribution->set_id_in_module($id);
			if ($item->get_id() === null)
				$contribution->set_description(stripslashes($this->form->get_value('contribution_description')));
			else
				$contribution->set_description(stripslashes($this->form->get_value('edition_description')));

			$contribution->set_entitled($item->get_title());
			$contribution->set_fixing_url(PagesUrlBuilder::edit_item($id)->relative());
			$contribution->set_poster_id(AppContext::get_current_user()->get_id());
			$contribution->set_module('pages');
			$contribution->set_auth(
				Authorizations::capture_and_shift_bit_auth(
					CategoriesService::get_categories_manager()->get_heritated_authorizations($item->get_id_category(), Category::MODERATION_AUTHORIZATIONS, Authorizations::AUTH_CHILD_PRIORITY),
					Category::MODERATION_AUTHORIZATIONS, Contribution::CONTRIBUTION_AUTH_BIT
				)
			);
			ContributionService::save_contribution($contribution);
		}
		else
		{
			$corresponding_contributions = ContributionService::find_by_criteria('pages', $id);
			if (count($corresponding_contributions) > 0)
			{
				foreach ($corresponding_contributions as $contribution)
				{
					$contribution->set_status(Event::EVENT_STATUS_PROCESSED);
					ContributionService::save_contribution($contribution);
				}
			}
		}
		$item->set_id($id);
	}

	private function redirect()
	{
		$item = $this->get_page();
		$category = $item->get_category();

		if ($this->is_new_item && $this->is_contributor_member() && !$item->is_published())
		{
			DispatchManager::redirect(new UserContributionSuccessController());
		}
		elseif ($item->is_published())
		{
			if ($this->is_new_item)
				AppContext::get_response()->redirect(PagesUrlBuilder::display_item($category->get_id(), $category->get_rewrited_name(), $item->get_id(), $item->get_rewrited_title()), StringVars::replace_vars($this->lang['pages.message.success.add'], array('title' => $item->get_title())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : PagesUrlBuilder::display_item($category->get_id(), $category->get_rewrited_name(), $item->get_id(), $item->get_rewrited_title())), StringVars::replace_vars($this->lang['pages.message.success.edit'], array('title' => $item->get_title())));
		}
		else
		{
			if ($this->is_new_item)
				AppContext::get_response()->redirect(PagesUrlBuilder::display_pending(), StringVars::replace_vars($this->lang['pages.message.success.add'], array('title' => $item->get_title())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : PagesUrlBuilder::display_pending()), StringVars::replace_vars($this->lang['pages.message.success.edit'], array('title' => $item->get_title())));
		}
	}

	private function generate_response(View $view)
	{
		$item = $this->get_page();

		$location_id = $item->get_id() ? 'pages-edit-'. $item->get_id() : '';

		$response = new SiteDisplayResponse($view, $location_id);
		$graphical_environment = $response->get_graphical_environment();

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module.title'], PagesUrlBuilder::home());

		if ($item->get_id() === null)
		{
			$graphical_environment->set_page_title($this->lang['pages.add'], $this->lang['module.title']);
			$breadcrumb->add($this->lang['pages.add'], PagesUrlBuilder::add_item($item->get_id_category()));
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['pages.add']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(PagesUrlBuilder::add_item($item->get_id_category()));
		}
		else
		{
			if (!AppContext::get_session()->location_id_already_exists($location_id))
				$graphical_environment->set_location_id($location_id);

			$graphical_environment->set_page_title($this->lang['pages.edit'], $this->lang['module.title']);
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['pages.edit']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(PagesUrlBuilder::edit_item($item->get_id()));

			$categories = array_reverse(CategoriesService::get_categories_manager()->get_parents($item->get_id_category(), true));
			foreach ($categories as $id => $category)
			{
				if ($category->get_id() != Category::ROOT_CATEGORY)
					$breadcrumb->add($category->get_name(), PagesUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
			}
			$category = $item->get_category();
			$breadcrumb->add($item->get_title(), PagesUrlBuilder::display_item($category->get_id(), $category->get_rewrited_name(), $item->get_id(), $item->get_rewrited_title()));
			$breadcrumb->add($this->lang['pages.edit'], PagesUrlBuilder::edit_item($item->get_id()));
		}

		return $response;
	}
}
?>
