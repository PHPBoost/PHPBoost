<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 04 20
 * @since       PHPBoost 4.0 - 2014 08 24
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DownloadItemFormController extends DefaultModuleController
{
	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->build_form($request);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->form->get_field_by_id('file_size')->set_hidden($this->is_file_size_automatic());
			$this->form->get_field_by_id('file_size_unit')->set_hidden($this->is_file_size_automatic());
			$this->save();
			$this->redirect();
		}

		$this->view->put('CONTENT', $this->form->display());

		return $this->generate_response($this->view);
	}

	private function build_form(HTTPRequestCustom $request)
	{
		$form = new HTMLForm(__CLASS__);
		$form->set_layout_title($this->get_item()->get_id() === null ? $this->lang['download.add.item'] : ($this->lang['download.edit.item']));

		$fieldset = new FormFieldsetHTML('download', $this->lang['form.parameters']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('title', $this->lang['form.title'], $this->get_item()->get_title(), array('required' => true)));

		if (CategoriesService::get_categories_manager()->get_categories_cache()->has_categories())
		{
			$search_category_children_options = new SearchCategoryChildrensOptions();
			$search_category_children_options->add_authorizations_bits(Category::CONTRIBUTION_AUTHORIZATIONS);
			$search_category_children_options->add_authorizations_bits(Category::WRITE_AUTHORIZATIONS);
			$fieldset->add_field(CategoriesService::get_categories_manager()->get_select_categories_form_field('id_category', $this->lang['form.category'], $this->get_item()->get_id_category(), $search_category_children_options));
		}

		$fieldset->add_field(new FormFieldUploadFile('file_url', $this->lang['form.url'], $this->get_item()->get_file_url()->relative(), array('required' => true)));

		$fieldset->add_field(new FormFieldCheckbox('determine_file_size_automatically_enabled', $this->lang['download.form.file.size.auto'], $this->is_file_size_automatic(),
			array(
				'events' => array('click' => '
					if (HTMLForms.getField("determine_file_size_automatically_enabled").getValue()) {
						HTMLForms.getField("file_size").disable();
						HTMLForms.getField("file_size_unit").disable();
					} else {
						HTMLForms.getField("file_size").enable();
						HTMLForms.getField("file_size_unit").enable();
					}'
				)
			)
		));

		if (!empty($this->get_item()->get_size()))
		{
			$formated_file_size = explode(' ', $this->get_item()->get_formated_size());
			$file_size = $formated_file_size[0];
			$file_size_unit = $formated_file_size[1];
		}
		else
			$file_size = $file_size_unit = 0;

		$fieldset->add_field(new FormFieldDecimalNumberEditor('file_size', $this->lang['download.form.file.size'], $file_size,
			array(
				'min' => 0, 'step' => 0.01, 'required' => true,
				'hidden' => ($request->is_post_method() ? $request->get_postbool(__CLASS__ . '_determine_file_size_automatically_enabled', false) : $this->is_file_size_automatic())
			)
		));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('file_size_unit', $this->lang['common.unit'], $file_size_unit,
			array(
				new FormFieldSelectChoiceOption('', ''),
				new FormFieldSelectChoiceOption($this->lang['common.unit.kilobytes'], $this->lang['common.unit.kilobytes']),
				new FormFieldSelectChoiceOption($this->lang['common.unit.megabytes'], $this->lang['common.unit.megabytes']),
				new FormFieldSelectChoiceOption($this->lang['common.unit.gigabytes'], $this->lang['common.unit.gigabytes'])
			),
			array(
				'required' => true,
				'hidden' => ($request->is_post_method() ? $request->get_postbool(__CLASS__ . '_determine_file_size_automatically_enabled', false) : $this->is_file_size_automatic())
			)
		));

		if ($this->get_item()->get_id() !== null && $this->get_item()->get_downloads_number() > 0)
		{
			$fieldset->add_field(new FormFieldCheckbox('reset_downloads_number', $this->lang['download.form.reset.downloads.number']));
		}

		$fieldset->add_field(new FormFieldRichTextEditor('content', $this->lang['form.description'], $this->get_item()->get_content(),
			array('rows' => 15, 'required' => true)
		));

		$fieldset->add_field(new FormFieldCheckbox('summary_enabled', $this->lang['form.enable.summary'], $this->get_item()->is_summary_enabled(),
			array(
				'description' => StringVars::replace_vars($this->lang['form.summary.clue'], array('number' => $this->config->get_auto_cut_characters_number())),
				'events' => array('click' => '
					if (HTMLForms.getField("summary_enabled").getValue()) {
						HTMLForms.getField("summary").enable();
					} else {
						HTMLForms.getField("summary").disable();
					}'
				)
			)
		));

		$fieldset->add_field(new FormFieldRichTextEditor('summary', $this->lang['form.summary'], $this->get_item()->get_summary(),
			array('hidden' => ($request->is_post_method() ? !$request->get_postbool(__CLASS__ . '_summary_enabled', false) : !$this->get_item()->is_summary_enabled()))
		));

		if ($this->config->is_author_displayed())
		{
			$fieldset->add_field(new FormFieldCheckbox('author_custom_name_enabled', $this->lang['form.enable.author.custom.name'], $this->get_item()->is_author_custom_name_enabled(),
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

			$fieldset->add_field(new FormFieldTextEditor('author_custom_name', $this->lang['form.author.custom.name'], $this->get_item()->get_author_custom_name(),
				array('hidden' => ($request->is_post_method() ? !$request->get_postbool(__CLASS__ . '_author_custom_name_enabled', false) : !$this->get_item()->is_author_custom_name_enabled()))
			));
		}

		$options_fieldset = new FormFieldsetHTML('options', $this->lang['form.options']);
		$form->add_fieldset($options_fieldset);

		$options_fieldset->add_field(new FormFieldThumbnail('thumbnail', $this->lang['form.thumbnail'], $this->get_item()->get_thumbnail()->relative(), DownloadItem::THUMBNAIL_URL));

		$options_fieldset->add_field(new FormFieldTextEditor('version_number', $this->lang['download.version'], $this->get_item()->get_version_number()));

		$options_fieldset->add_field(KeywordsService::get_keywords_manager()->get_form_field($this->get_item()->get_id(), 'keywords', $this->lang['form.keywords'],
			array('description' => $this->lang['form.keywords.clue'])
		));

		$options_fieldset->add_field(new FormFieldSelectSources('sources', $this->lang['form.sources'], $this->get_item()->get_sources()));

		if (DownloadAuthorizationsService::check_authorizations($this->get_item()->get_id_category())->moderation())
		{
			$publication_fieldset = new FormFieldsetHTML('publication', $this->lang['form.publication']);
			$form->add_fieldset($publication_fieldset);

			$publication_fieldset->add_field(new FormFieldDateTime('creation_date', $this->lang['form.creation.date'], $this->get_item()->get_creation_date(),
				array('required' => true)
			));

			if (!$this->get_item()->is_published())
			{
				$publication_fieldset->add_field(new FormFieldCheckbox('update_creation_date', $this->lang['form.update.creation.date'], false,
					array('hidden' => $this->get_item()->get_publishing_state() != DownloadItem::NOT_PUBLISHED)
				));
			}

			$publication_fieldset->add_field(new FormFieldSimpleSelectChoice('published', $this->lang['form.publication'], $this->get_item()->get_publishing_state(),
				array(
					new FormFieldSelectChoiceOption($this->lang['form.publication.draft'], DownloadItem::NOT_PUBLISHED),
					new FormFieldSelectChoiceOption($this->lang['form.publication.now'], DownloadItem::PUBLISHED),
					new FormFieldSelectChoiceOption($this->lang['form.publication.deffered'], DownloadItem::DEFERRED_PUBLICATION),
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

			$publication_fieldset->add_field($publishing_start_date = new FormFieldDateTime('publishing_start_date', $this->lang['form.start.date'], ($this->get_item()->get_publishing_start_date() === null ? new Date() : $this->get_item()->get_publishing_start_date()),
				array('hidden' => ($request->is_post_method() ? ($request->get_postint(__CLASS__ . '_publication_state', 0) != DownloadItem::DEFERRED_PUBLICATION) : ($this->get_item()->get_publishing_state() != DownloadItem::DEFERRED_PUBLICATION)))
			));

			$publication_fieldset->add_field(new FormFieldCheckbox('end_date_enabled', $this->lang['form.enable.end.date'], $this->get_item()->is_end_date_enabled(),
				array(
					'hidden' => ($request->is_post_method() ? ($request->get_postint(__CLASS__ . '_publication_state', 0) != DownloadItem::DEFERRED_PUBLICATION) : ($this->get_item()->get_publishing_state() != DownloadItem::DEFERRED_PUBLICATION)),
					'events' => array('click' => '
						if (HTMLForms.getField("end_date_enabled").getValue()) {
							HTMLForms.getField("publishing_end_date").enable();
						} else {
							HTMLForms.getField("publishing_end_date").disable();
						}'
					)
				)
			));

			$publication_fieldset->add_field($publishing_end_date = new FormFieldDateTime('publishing_end_date', $this->lang['form.end.date'], ($this->get_item()->get_publishing_end_date() === null ? new Date() : $this->get_item()->get_publishing_end_date()),
				array('hidden' => ($request->is_post_method() ? !$request->get_postbool(__CLASS__ . '_end_date_enabled', false) : !$this->get_item()->is_end_date_enabled()))
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
		if ($this->get_item()->get_id() === null && $this->is_contributor_member())
		{
			$fieldset = new FormFieldsetHTML('contribution', $this->lang['contribution.contribution']);
			$fieldset->set_description(MessageHelper::display($this->lang['contribution.extended.warning'], MessageHelper::WARNING)->render());
			$form->add_fieldset($fieldset);

			$fieldset->add_field(new FormFieldRichTextEditor('contribution_description', $this->lang['contribution.description'], '',
				array('description' => $this->lang['contribution.description.clue'])
			));
		}
		elseif ($this->get_item()->is_published() && $this->get_item()->is_authorized_to_edit() && $this->is_contributor_member())
		{
			$fieldset = new FormFieldsetHTML('member_edition', $this->lang['contribution.member.edition']);
			$fieldset->set_description(MessageHelper::display($this->lang['contribution.edition.warning'], MessageHelper::WARNING)->render());
			$form->add_fieldset($fieldset);

			$fieldset->add_field(new FormFieldRichTextEditor('edition_description', $this->lang['contribution.edition.description'], '',
				array('description' => $this->lang['contribution.edition.description.clue'])
			));
		}
	}

	private function is_contributor_member()
	{
		return (!DownloadAuthorizationsService::check_authorizations()->write() && DownloadAuthorizationsService::check_authorizations()->contribution());
	}

	private function is_file_size_automatic()
	{
		return $this->get_item()->get_id() === null || $this->get_item()->get_size() == 0 || ($this->get_item()->get_size() ==  Url::get_url_file_size($this->get_item()->get_file_url()));
	}

	private function get_item()
	{
		if ($this->item === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try {
					$this->item = DownloadService::get_item($id);
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->is_new_item = true;
				$this->item = new DownloadItem();
				$this->item->init_default_properties(AppContext::get_request()->get_getint('id_category', Category::ROOT_CATEGORY));
			}
		}
		return $this->item;
	}

	private function check_authorizations()
	{
		$item = $this->get_item();

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
		$item = $this->get_item();

		$item->set_title($this->form->get_value('title'));
		$item->set_rewrited_title(Url::encode_rewrite($item->get_title()));

		if (CategoriesService::get_categories_manager()->get_categories_cache()->has_categories())
			$item->set_id_category($this->form->get_value('id_category')->get_raw_value());

		$item->set_file_url(new Url($this->form->get_value('file_url')));
		$item->set_content($this->form->get_value('content'));
		$item->set_summary(($this->form->get_value('summary_enabled') ? $this->form->get_value('summary') : ''));
		$item->set_thumbnail($this->form->get_value('thumbnail'));
		$item->set_version_number($this->form->get_value('version_number'));

		if ($this->config->is_author_displayed())
			$item->set_author_custom_name(($this->form->get_value('author_custom_name') && $this->form->get_value('author_custom_name') !== $item->get_author_user()->get_display_name() ? $this->form->get_value('author_custom_name') : ''));

		if ($this->form->get_value('determine_file_size_automatically_enabled'))
		{
			$file_size = Url::get_url_file_size($item->get_file_url());
			$file_size = (empty($file_size) && $item->get_size()) ? $item->get_size() : $file_size;
		}
		else
		{
			$units = array($this->lang['common.unit.bytes'], $this->lang['common.unit.kilobytes'], $this->lang['common.unit.megabytes'], $this->lang['common.unit.gigabytes']);
			$power = array_search($this->form->get_value('file_size_unit')->get_raw_value(), $units);
			$file_size = (int)($this->form->get_value('file_size') * pow(1024, $power));
		}

		$item->set_size($file_size);

		$item->set_sources($this->form->get_value('sources'));

		if ($item->get_id() !== null && $item->get_downloads_number() > 0 && $this->form->get_value('reset_downloads_number'))
		{
			$item->set_downloads_number(0);
		}

		if (!DownloadAuthorizationsService::check_authorizations($item->get_id_category())->moderation())
		{
			$item->clean_publishing_start_and_end_date();

			if (DownloadAuthorizationsService::check_authorizations($item->get_id_category())->contribution() && !DownloadAuthorizationsService::check_authorizations($item->get_id_category())->write())
				$item->set_publishing_state(DownloadItem::NOT_PUBLISHED);
		}
		else
		{

			if ($this->form->get_value('update_creation_date'))
				$item->set_creation_date(new Date());
			else
				$item->set_creation_date($this->form->get_value('creation_date'));

			$item->set_publishing_state($this->form->get_value('published')->get_raw_value());
			if ($item->get_publishing_state() == DownloadItem::DEFERRED_PUBLICATION)
			{
				$deferred_operations = $this->config->get_deferred_operations();

				$old_publishing_start_date = $item->get_publishing_start_date();
				$publishing_start_date = $this->form->get_value('publishing_start_date');
				$item->set_publishing_start_date($publishing_start_date);

				if ($old_publishing_start_date !== null && $old_publishing_start_date->get_timestamp() != $publishing_start_date->get_timestamp() && in_array($old_publishing_start_date->get_timestamp(), $deferred_operations))
				{
					$key = array_search($old_publishing_start_date->get_timestamp(), $deferred_operations);
					unset($deferred_operations[$key]);
				}

				if (!in_array($publishing_start_date->get_timestamp(), $deferred_operations))
					$deferred_operations[] = $publishing_start_date->get_timestamp();

				if ($this->form->get_value('end_date_enabled'))
				{
					$old_publishing_end_date = $item->get_publishing_end_date();
					$publishing_end_date = $this->form->get_value('publishing_end_date');
					$item->set_publishing_end_date($publishing_end_date);

					if ($old_publishing_end_date !== null && $old_publishing_end_date->get_timestamp() != $publishing_end_date->get_timestamp() && in_array($old_publishing_end_date->get_timestamp(), $deferred_operations))
					{
						$key = array_search($old_publishing_end_date->get_timestamp(), $deferred_operations);
						unset($deferred_operations[$key]);
					}

					if (!in_array($publishing_end_date->get_timestamp(), $deferred_operations))
						$deferred_operations[] = $publishing_end_date->get_timestamp();
				}
				else
					$item->clean_publishing_end_date();

				$this->config->set_deferred_operations($deferred_operations);
				DownloadConfig::save();
			}
			else
				$item->clean_publishing_start_and_end_date();
		}

		if ($this->is_new_item)
		{
			$id = DownloadService::add($item);
			$item->set_id($id);

			if (!$this->is_contributor_member())
				HooksService::execute_hook_action('add', self::$module_id, array_merge($item->get_properties(), array('item_url' => $item->get_item_url())));
		}
		else
		{
			$item->set_update_date(new Date());
			DownloadService::update($item);

			if (!$this->is_contributor_member())
				HooksService::execute_hook_action('edit', self::$module_id, array_merge($item->get_properties(), array('item_url' => $item->get_item_url())));
		}

		$this->contribution_actions($item);

		KeywordsService::get_keywords_manager()->put_relations($item->get_id(), $this->form->get_value('keywords'));

		DownloadService::clear_cache();
	}

	private function contribution_actions(DownloadItem $item)
	{
		if ($this->is_contributor_member())
		{
			$contribution = new Contribution();
			$contribution->set_id_in_module($item->get_id());
			if ($this->is_new_item)
				$contribution->set_description(stripslashes($this->form->get_value('contribution_description')));
			else
				$contribution->set_description(stripslashes($this->form->get_value('edition_description')));

			$contribution->set_entitled($item->get_title());
			$contribution->set_fixing_url(DownloadUrlBuilder::edit($item->get_id())->relative());
			$contribution->set_poster_id(AppContext::get_current_user()->get_id());
			$contribution->set_module('download');
			$contribution->set_auth(
				Authorizations::capture_and_shift_bit_auth(
					CategoriesService::get_categories_manager()->get_heritated_authorizations($item->get_id_category(), Category::MODERATION_AUTHORIZATIONS, Authorizations::AUTH_CHILD_PRIORITY),
					Category::MODERATION_AUTHORIZATIONS, Contribution::CONTRIBUTION_AUTH_BIT
				)
			);
			ContributionService::save_contribution($contribution);
			HooksService::execute_hook_action($this->is_new_item ? 'add_contribution' : 'edit_contribution', self::$module_id, array_merge($contribution->get_properties(), $item->get_properties(), array('item_url' => $item->get_item_url())));
		}
		else
		{
			$corresponding_contributions = ContributionService::find_by_criteria('download', $item->get_id());
			if (count($corresponding_contributions) > 0)
			{
				foreach ($corresponding_contributions as $contribution)
				{
					$contribution->set_status(Event::EVENT_STATUS_PROCESSED);
					ContributionService::save_contribution($contribution);
				}
				HooksService::execute_hook_action('process_contribution', self::$module_id, array_merge($contribution->get_properties(), $item->get_properties(), array('item_url' => $item->get_item_url())));
			}
		}
	}

	private function redirect()
	{
		$item = $this->get_item();
		$category = $item->get_category();

		if ($this->is_new_item && $this->is_contributor_member() && !$item->is_published())
		{
			DispatchManager::redirect(new UserContributionSuccessController());
		}
		elseif ($item->is_published())
		{
			if ($this->is_new_item)
				AppContext::get_response()->redirect(DownloadUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $item->get_id(), $item->get_rewrited_title()), StringVars::replace_vars($this->lang['download.message.success.add'], array('title' => $item->get_title())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : DownloadUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $item->get_id(), $item->get_rewrited_title())), StringVars::replace_vars($this->lang['download.message.success.edit'], array('title' => $item->get_title())));
		}
		else
		{
			if ($this->is_new_item)
				AppContext::get_response()->redirect(DownloadUrlBuilder::display_pending(), StringVars::replace_vars($this->lang['download.message.success.add'], array('title' => $item->get_title())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : DownloadUrlBuilder::display_pending()), StringVars::replace_vars($this->lang['download.message.success.edit'], array('title' => $item->get_title())));
		}
	}

	private function generate_response(View $view)
	{
		$item = $this->get_item();

		$location_id = $item->get_id() ? 'download-edit-'. $item->get_id() : '';

		$response = new SiteDisplayResponse($view, $location_id);
		$graphical_environment = $response->get_graphical_environment();

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['download.module.title'], DownloadUrlBuilder::home());

		if ($item->get_id() === null)
		{
			$breadcrumb->add($this->lang['download.add.item'], DownloadUrlBuilder::add($item->get_id_category()));
			$graphical_environment->set_page_title($this->lang['download.add.item'], $this->lang['download.module.title']);
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['download.add.item']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(DownloadUrlBuilder::add($item->get_id_category()));
		}
		else
		{
			if (!AppContext::get_session()->location_id_already_exists($location_id))
				$graphical_environment->set_location_id($location_id);

			$graphical_environment->set_page_title($this->lang['download.edit.item'], $this->lang['download.module.title']);
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['download.edit.item']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(DownloadUrlBuilder::edit($item->get_id()));

			$categories = array_reverse(CategoriesService::get_categories_manager()->get_parents($item->get_id_category(), true));
			foreach ($categories as $id => $category)
			{
				if ($category->get_id() != Category::ROOT_CATEGORY)
					$breadcrumb->add($category->get_name(), DownloadUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name()));
			}
			$category = $item->get_category();
			$breadcrumb->add($item->get_title(), DownloadUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $item->get_id(), $item->get_rewrited_title()));
			$breadcrumb->add($this->lang['download.edit.item'], DownloadUrlBuilder::edit($item->get_id()));
		}

		return $response;
	}
}
?>
