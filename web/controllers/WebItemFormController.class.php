<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 10 20
 * @since       PHPBoost 4.1 - 2014 08 21
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class WebItemFormController extends ModuleController
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
	private $form_lang;

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
		$this->lang = LangLoader::get('common', 'web');
		$this->form_lang = LangLoader::get('form-lang');
	}

	private function build_form(HTTPRequestCustom $request)
	{
		$form = new HTMLForm(__CLASS__);
		$form->set_layout_title($this->item->get_id() === null ? $this->lang['web.add.item'] : ($this->lang['web.edit.item'] . ': ' . $this->item->get_title()));

		$fieldset = new FormFieldsetHTML('web', $this->form_lang['form.parameters']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('title', $this->form_lang['form.title'], $this->item->get_title(),
			array('required' => true)
		));

		if (CategoriesService::get_categories_manager()->get_categories_cache()->has_categories())
		{
			$search_category_children_options = new SearchCategoryChildrensOptions();
			$search_category_children_options->add_authorizations_bits(Category::CONTRIBUTION_AUTHORIZATIONS);
			$search_category_children_options->add_authorizations_bits(Category::WRITE_AUTHORIZATIONS);
			$fieldset->add_field(CategoriesService::get_categories_manager()->get_select_categories_form_field('id_category', $this->form_lang['form.category'], $this->item->get_id_category(), $search_category_children_options));
		}

		$fieldset->add_field(new FormFieldUrlEditor('website_url', $this->form_lang['form.url'], $this->item->get_website_url()->absolute(),
			array('required' => true)
		));

		$fieldset->add_field(new FormFieldRichTextEditor('content', $this->form_lang['form.description'], $this->item->get_content(),
			array('rows' => 15, 'required' => true)
		));

		$fieldset->add_field(new FormFieldCheckbox('summary_enabled', $this->form_lang['form.enable.summary'], $this->item->is_summary_enabled(),
			array(
				'description' => StringVars::replace_vars($this->form_lang['form.summary.clue'], array('number' => WebConfig::AUTO_CUT_CHARACTERS_NUMBER)),
				'events' => array('click' => '
					if (HTMLForms.getField("summary_enabled").getValue()) {
						HTMLForms.getField("summary").enable();
					} else {
						HTMLForms.getField("summary").disable();
					}'
				)
			)
		));

		$fieldset->add_field(new FormFieldRichTextEditor('summary', $this->form_lang['form.description'], $this->item->get_summary(),
			array('hidden' => ($request->is_post_method() ? !$request->get_postbool(__CLASS__ . '_summary_enabled', false) : !$this->item->is_summary_enabled()))
		));

		$options_fieldset = new FormFieldsetHTML('options', $this->form_lang['form.options']);
		$form->add_fieldset($options_fieldset);

		$options_fieldset->add_field(new FormFieldThumbnail('thumbnail', $this->form_lang['form.picture'], $this->item->get_thumbnail()->relative(), WebItem::THUMBNAIL_URL));

		$options_fieldset->add_field(new FormFieldCheckbox('partner', $this->lang['web.form.partner'], $this->item->is_partner(), array(
			'events' => array('click' => '
				if (HTMLForms.getField("partner").getValue()) {
					HTMLForms.getField("partner_thumbnail").enable();
					HTMLForms.getField("privileged_partner").enable();
					if (HTMLForms.getField("partner_thumbnail").getValue())
						jQuery("#' . __CLASS__ . '_partner_thumbnail_preview").show();
				} else {
					HTMLForms.getField("partner_thumbnail").disable();
					jQuery("#' . __CLASS__ . '_partner_thumbnail_preview").hide();
					HTMLForms.getField("privileged_partner").disable();
				}'
			)
		)));

		$options_fieldset->add_field(new FormFieldUploadPictureFile('partner_thumbnail', $this->lang['web.form.partner.thumbnail'], $this->item->get_partner_thumbnail()->relative(),
			array('hidden' => ($request->is_post_method() ? !$request->get_postbool(__CLASS__ . '_partner', false) : !$this->item->is_partner()))
		));

		$options_fieldset->add_field(new FormFieldCheckbox('privileged_partner', $this->lang['web.form.privileged.partner'], $this->item->is_privileged_partner(),
			array(
				'description' => $this->lang['web.form.privileged.partner.clue'],
				'hidden' => ($request->is_post_method() ? !$request->get_postbool(__CLASS__ . '_partner', false) : !$this->item->is_partner())
			))
		);

		$options_fieldset->add_field(KeywordsService::get_keywords_manager()->get_form_field($this->item->get_id(), 'keywords', $this->form_lang['form.keywords'],
			array('description' => $this->form_lang['form.keywords.clue']))
		);

		if (CategoriesAuthorizationsService::check_authorizations($this->item->get_id_category())->moderation())
		{
			$publication_fieldset = new FormFieldsetHTML('publication', $this->form_lang['form.publication']);
			$form->add_fieldset($publication_fieldset);

			$publication_fieldset->add_field(new FormFieldDateTime('creation_date', $this->form_lang['form.creation.date'], $this->item->get_creation_date(),
				array('required' => true)
			));

			if (!$this->item->is_published())
			{
				$publication_fieldset->add_field(new FormFieldCheckbox('update_creation_date', $this->form_lang['form.update.date'], false,
					array('hidden' => $this->item->get_status() != WebItem::NOT_PUBLISHED)
				));
			}

			$publication_fieldset->add_field(new FormFieldSimpleSelectChoice('published', $this->form_lang['form.publication'], $this->item->get_publishing_state(),
				array(
					new FormFieldSelectChoiceOption($this->form_lang['form.publication.draft'], WebItem::NOT_PUBLISHED),
					new FormFieldSelectChoiceOption($this->form_lang['form.publication.now'], WebItem::PUBLISHED),
					new FormFieldSelectChoiceOption($this->form_lang['form.publication.deffered'], WebItem::DEFERRED_PUBLICATION),
				),
				array(
					'events' => array('change' => '
						if (HTMLForms.getField("published").getValue() == 2) {
							jQuery("#' . __CLASS__ . '_publishing_start_date_field").show();
							HTMLForms.getField("end_date_enabled").enable();
							if (HTMLForms.getField("end_date_enabled").getValue()) {
								HTMLForms.getField("publishing_end_date").enable();
							}
						} else {
							jQuery("#' . __CLASS__ . '_publishing_start_date_field").hide();
							HTMLForms.getField("end_date_enabled").disable();
							HTMLForms.getField("publishing_end_date").disable();
						}'
					)
				)
			));

			$publication_fieldset->add_field($publishing_start_date = new FormFieldDateTime('publishing_start_date', $this->form_lang['form.start.date'], ($this->item->get_publishing_start_date() === null ? new Date() : $this->item->get_publishing_start_date()),
				array('hidden' => ($request->is_post_method() ? ($request->get_postint(__CLASS__ . '_publication_state', 0) != WebItem::DEFERRED_PUBLICATION) : ($this->item->get_publishing_state() != WebItem::DEFERRED_PUBLICATION)))
			));

			$publication_fieldset->add_field(new FormFieldCheckbox('end_date_enabled', $this->form_lang['form.enable.end.date'], $this->item->is_end_date_enabled(),
				array(
					'hidden' => ($request->is_post_method() ? ($request->get_postint(__CLASS__ . '_publication_state', 0) != WebItem::DEFERRED_PUBLICATION) : ($this->item->get_publishing_state() != WebItem::DEFERRED_PUBLICATION)),
					'events' => array('click' => '
						if (HTMLForms.getField("end_date_enabled").getValue()) {
							HTMLForms.getField("publishing_end_date").enable();
						} else {
							HTMLForms.getField("publishing_end_date").disable();
						}'
					)
				)
			));

			$publication_fieldset->add_field($publishing_end_date = new FormFieldDateTime('publishing_end_date', $this->form_lang['form.end.date'], ($this->item->get_publishing_end_date() === null ? new Date() : $this->item->get_publishing_end_date()),
				array('hidden' => ($request->is_post_method() ? !$request->get_postbool(__CLASS__ . '_end_date_enabled', false) : !$this->item->is_end_date_enabled()))
			));

			$publishing_end_date->add_form_constraint(new FormConstraintFieldsDifferenceSuperior($publishing_start_date, $publishing_end_date));
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
		$contribution = LangLoader::get('contribution-lang');
		if ($this->item->get_id() === null && $this->is_contributor_member())
		{
			$fieldset = new FormFieldsetHTML('contribution', $contribution['contribution.contribution']);
			$fieldset->set_description(MessageHelper::display($contribution['contribution.extended.warning'], MessageHelper::WARNING)->render());
			$form->add_fieldset($fieldset);

			$fieldset->add_field(new FormFieldRichTextEditor('contribution_description', $contribution['contribution.description'], '',
				array('description' => $contribution['contribution.description.clue'])
			));
		}
		elseif ($this->item->is_published() && $this->item->is_authorized_to_edit() && !AppContext::get_current_user()->check_level(User::ADMINISTRATOR_LEVEL))
		{
			$fieldset = new FormFieldsetHTML('member_edition', $contribution['contribution.member.edition']);
			$fieldset->set_description(MessageHelper::display($contribution['contribution.edition.warning'], MessageHelper::WARNING)->render());
			$form->add_fieldset($fieldset);

			$fieldset->add_field(new FormFieldRichTextEditor('edition_description', $contribution['contribution.edition.description'], '',
				array('description' => $contribution['contribution.edition.description.clue'])
			));
		}
	}

	private function is_contributor_member()
	{
		return (!CategoriesAuthorizationsService::check_authorizations()->write() && CategoriesAuthorizationsService::check_authorizations()->contribution());
	}

	private function get_item()
	{
		if ($this->item === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try {
					$this->item = WebService::get_item('WHERE web.id=:id', array('id' => $id));
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->is_new_item = true;
				$this->item = new WebItem();
				$this->item->init_default_properties(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY));
			}
		}
		return $this->item;
	}

	private function check_authorizations()
	{
		$this->item = $this->get_item();

		if ($this->item->get_id() === null)
		{
			if (!$this->item->is_authorized_to_add())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			if (!$this->item->is_authorized_to_edit())
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
		$this->item->set_title($this->form->get_value('title'));
		$this->item->set_rewrited_title(Url::encode_rewrite($this->item->get_title()));

		if (CategoriesService::get_categories_manager()->get_categories_cache()->has_categories())
			$this->item->set_id_category($this->form->get_value('id_category')->get_raw_value());

		$this->item->set_website_url(new Url($this->form->get_value('website_url')));
		$this->item->set_content($this->form->get_value('content'));
		$this->item->set_summary(($this->form->get_value('summary_enabled') ? $this->form->get_value('summary') : ''));
		$this->item->set_thumbnail($this->form->get_value('thumbnail'));

		$this->item->set_partner($this->form->get_value('partner'));
		if ($this->form->get_value('partner'))
		{
			$this->item->set_partner_thumbnail(new Url($this->form->get_value('partner_thumbnail')));
			$this->item->set_privileged_partner($this->form->get_value('privileged_partner'));
		}

		if (!CategoriesAuthorizationsService::check_authorizations($this->item->get_id_category())->moderation())
		{
			if ($this->item->get_id() === null )
				$this->item->set_creation_date(new Date());

			$this->item->clean_publishing_start_and_end_date();

			if (CategoriesAuthorizationsService::check_authorizations($this->item->get_id_category())->contribution() && !CategoriesAuthorizationsService::check_authorizations($this->item->get_id_category())->write())
				$this->item->set_publishing_state(WebItem::NOT_PUBLISHED);
		}
		else
		{
			if ($this->form->get_value('update_creation_date'))
			{
				$this->item->set_creation_date(new Date());
			}
			else
			{
				$this->item->set_creation_date($this->form->get_value('creation_date'));
			}
			$this->item->set_publishing_state($this->form->get_value('published')->get_raw_value());
			if ($this->item->get_publishing_state() == WebItem::DEFERRED_PUBLICATION)
			{
				$deferred_operations = $this->config->get_deferred_operations();

				$old_publishing_start_date = $this->item->get_publishing_start_date();
				$publishing_start_date = $this->form->get_value('publishing_start_date');
				$this->item->set_publishing_start_date($publishing_start_date);

				if ($old_publishing_start_date !== null && $old_publishing_start_date->get_timestamp() != $publishing_start_date->get_timestamp() && in_array($old_publishing_start_date->get_timestamp(), $deferred_operations))
				{
					$key = array_search($old_publishing_start_date->get_timestamp(), $deferred_operations);
					unset($deferred_operations[$key]);
				}

				if (!in_array($publishing_start_date->get_timestamp(), $deferred_operations))
					$deferred_operations[] = $publishing_start_date->get_timestamp();

				if ($this->form->get_value('end_date_enabled'))
				{
					$old_publishing_end_date = $this->item->get_publishing_end_date();
					$publishing_end_date = $this->form->get_value('publishing_end_date');
					$this->item->set_publishing_end_date($publishing_end_date);

					if ($old_publishing_end_date !== null && $old_publishing_end_date->get_timestamp() != $publishing_end_date->get_timestamp() && in_array($old_publishing_end_date->get_timestamp(), $deferred_operations))
					{
						$key = array_search($old_publishing_end_date->get_timestamp(), $deferred_operations);
						unset($deferred_operations[$key]);
					}

					if (!in_array($publishing_end_date->get_timestamp(), $deferred_operations))
						$deferred_operations[] = $publishing_end_date->get_timestamp();
				}
				else
				{
					$this->item->clean_publishing_end_date();
				}

				$this->config->set_deferred_operations($deferred_operations);
				WebConfig::save();
			}
			else
			{
				$this->item->clean_publishing_start_and_end_date();
			}
		}

		if ($this->item->get_id() === null)
		{
			$id = WebService::add($this->item);
			HooksService::execute_hook_action('edit', self::$module_id, $id, $this->item->get_title(), $this->item->get_content(), $this->item->get_author_user());
		}
		else
		{
			$this->item->set_update_date(new Date());
			$id = $this->item->get_id();
			WebService::update($this->item);
			HooksService::execute_hook_action('edit', self::$module_id, $id, $this->item->get_title(), $this->item->get_content(), $this->item->get_author_user());
		}

		$this->contribution_actions($this->item, $id);

		KeywordsService::get_keywords_manager()->put_relations($id, $this->form->get_value('keywords'));

		WebService::clear_cache();
	}

	private function contribution_actions(WebItem $item, $id)
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
				$contribution->set_fixing_url(WebUrlBuilder::edit($id)->relative());
				$contribution->set_poster_id(AppContext::get_current_user()->get_id());
				$contribution->set_module('web');
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
			$corresponding_contributions = ContributionService::find_by_criteria('web', $id);
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
		$category = $this->item->get_category();

		if ($this->is_new_item && $this->is_contributor_member() && !$this->item->is_published())
		{
			DispatchManager::redirect(new UserContributionSuccessController());
		}
		elseif ($this->item->is_published())
		{
			if ($this->is_new_item)
				AppContext::get_response()->redirect(WebUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $this->item->get_id(), $this->item->get_rewrited_title()), StringVars::replace_vars($this->lang['web.message.success.add'], array('title' => $this->item->get_title())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : WebUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $this->item->get_id(), $this->item->get_rewrited_title())), StringVars::replace_vars($this->lang['web.message.success.edit'], array('title' => $this->item->get_title())));
		}
		else
		{
			if ($this->is_new_item)
				AppContext::get_response()->redirect(WebUrlBuilder::display_pending(), StringVars::replace_vars($this->lang['web.message.success.add'], array('title' => $this->item->get_title())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : WebUrlBuilder::display_pending()), StringVars::replace_vars($this->lang['web.message.success.edit'], array('title' => $this->item->get_title())));
		}
	}

	private function generate_response(View $view)
	{
		$location_id = $this->item->get_id() ? 'web-edit-'. $this->item->get_id() : '';

		$response = new SiteDisplayResponse($view, $location_id);
		$graphical_environment = $response->get_graphical_environment();

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['web.module.title'], WebUrlBuilder::home());

		if ($this->item->get_id() === null)
		{
			$breadcrumb->add($this->lang['web.add.item'], WebUrlBuilder::add($this->item->get_id_category()));
			$graphical_environment->set_page_title($this->lang['web.add.item']);
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['web.add.item'], $this->lang['web.module.title']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(WebUrlBuilder::add($this->item->get_id_category()));
		}
		else
		{
			if (!AppContext::get_session()->location_id_already_exists($location_id))
				$graphical_environment->set_location_id($location_id);

			$graphical_environment->set_page_title($this->lang['web.edit.item']);
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['web.edit.item'], $this->lang['web.module.title']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(WebUrlBuilder::edit($this->item->get_id()));

			$categories = array_reverse(CategoriesService::get_categories_manager()->get_parents($this->item->get_id_category(), true));
			foreach ($categories as $id => $category)
			{
				if ($category->get_id() != Category::ROOT_CATEGORY)
					$breadcrumb->add($category->get_name(), WebUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
			}
			$category = $this->item->get_category();
			$breadcrumb->add($this->item->get_title(), WebUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $this->item->get_id(), $this->item->get_rewrited_title()));
			$breadcrumb->add($this->lang['web.edit.item'], WebUrlBuilder::edit($this->item->get_id()));
		}

		return $response;
	}
}
?>
