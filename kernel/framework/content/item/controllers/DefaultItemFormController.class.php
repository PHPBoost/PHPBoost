<?php
/**
 * @package     Content
 * @subpackage  Item\controllers
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 14
 * @since       PHPBoost 6.0 - 2020 05 16
 * @contributor xela <xela@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DefaultItemFormController extends AbstractItemController
{
	/**
	 * @var HTMLForm
	 */
	protected $form;
	/**
	 * @var FormButtonSubmit
	 */
	protected $submit_button;
	/**
	 * @var Item
	 */
	protected $item;

	protected $item_class;
	protected $is_new_item;

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

		$this->view->put('CONTENT', $this->form->display());

		return $this->generate_response();
	}

	protected function init()
	{
		$this->get_item();
		$this->item_class = self::get_module_configuration()->get_item_name();
	}

	protected function get_item()
	{
		if ($this->item === null)
		{
			$id = $this->request->get_getint('id', 0);
			if (!empty($id))
			{
				try {
					$this->item = self::get_items_manager()->get_item($id);
				} catch (RowNotFoundException $e) {
					$this->display_unexisting_page();
				}
			}
			else
			{
				$item_class = self::get_module_configuration()->get_item_name();
				$this->is_new_item = true;
				$this->item = new $item_class(self::$module_id);
				$this->item->init_default_properties($this->request->get_getint('id_category', Category::ROOT_CATEGORY));
			}
		}
		return $this->item;
	}

	protected function check_authorizations()
	{
		if (($this->is_new_item && !$this->get_item()->is_authorized_to_add()) || (!$this->is_new_item && !$this->get_item()->is_authorized_to_edit()))
			$this->display_user_not_authorized_page();
		if (AppContext::get_current_user()->is_readonly())
			$this->display_user_in_read_only_page();
	}

	protected function build_form()
	{
		$form = new HTMLForm(self::$module_id . '_form');
		$form->set_layout_title($this->is_new_item ? $this->lang['item.add'] : ($this->lang['item.edition']));

		$fieldset = new FormFieldsetHTML(self::$module_id, $this->lang['form.parameters']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor($this->item_class::get_title_label(), $this->lang['form.' . $this->item_class::get_title_label()], $this->get_item()->get_title(),
			array('required' => true)
		));

		if ((self::get_module_configuration()->has_categories() && CategoriesAuthorizationsService::check_authorizations($this->get_item()->get_id_category(), self::$module_id)->moderation()) || (!self::get_module_configuration()->has_categories() && ItemsAuthorizationsService::check_authorizations(self::$module_id)->moderation()))
		{
			$fieldset->add_field(new FormFieldCheckbox('personalize_rewrited_' . $this->item_class::get_title_label(), $this->lang['form.rewrited.title.personalize'], $this->get_item()->rewrited_title_is_personalized(),
				array(
					'events' => array('click' =>'
						if (HTMLForms.getField("personalize_rewrited_' . $this->item_class::get_title_label() . '").getValue()) {
							HTMLForms.getField("rewrited_' . $this->item_class::get_title_label() . '").enable();
						} else {
							HTMLForms.getField("rewrited_' . $this->item_class::get_title_label() . '").disable();
						}'
					)
				)
			));

			$fieldset->add_field(new FormFieldTextEditor('rewrited_' . $this->item_class::get_title_label(), $this->lang['form.rewrited.title'], $this->get_item()->get_rewrited_title(),
				array('description' => $this->lang['form.rewrited.title.clue'], 'hidden' => ($this->request->is_post_method() ? !$this->request->get_postbool(self::$module_id . '_form_personalize_rewrited_' . $this->item_class::get_title_label(), false) : !$this->get_item()->rewrited_title_is_personalized())),
				array(new FormFieldConstraintRegex('`^[a-z0-9\-]+$`iu'))
			));
		}

		if (self::get_module_configuration()->has_categories() && CategoriesService::get_categories_manager()->get_categories_cache()->has_categories())
		{
			$search_category_children_options = new SearchCategoryChildrensOptions();
			$search_category_children_options->add_authorizations_bits(Category::CONTRIBUTION_AUTHORIZATIONS);
			$search_category_children_options->add_authorizations_bits(Category::WRITE_AUTHORIZATIONS);
			$fieldset->add_field(CategoriesService::get_categories_manager()->get_select_categories_form_field('id_category', $this->lang['form.category'], $this->get_item()->get_id_category(), $search_category_children_options));
		}

		$this->build_pre_content_fields($fieldset);

		if ($this->get_item()->content_field_enabled())
		{
			$fieldset->add_field(new FormFieldRichTextEditor($this->item_class::get_content_label(), $this->lang['form.content'], $this->get_item()->get_content(),
				array('rows' => 15, 'required' => $this->get_item()->content_field_required())
			));
		}

		$this->build_post_content_fields($fieldset);

		$this->build_options_fieldset($form);

		$this->build_publication_fieldset($form);

		$this->build_contribution_fieldset($form);

		$fieldset->add_field(new FormFieldHidden('referrer', $this->request->get_url_referrer()));

		$this->submit_button = new FormButtonDefaultSubmit();
		$form->add_button($this->submit_button);
		$form->add_button(new FormButtonReset());

		$this->form = $form;
	}

	protected function build_pre_content_fields(FormFieldset $fieldset)
	{
		$this->get_additional_attributes_fields($fieldset, 'attribute_pre_content_field_parameters');
	}

	protected function build_post_content_fields(FormFieldset $fieldset)
	{
		if (self::get_module_configuration()->has_rich_items())
		{
			if ($this->get_item()->content_field_enabled() && $this->get_item()->summary_field_enabled())
			{
				$fieldset->add_field(new FormFieldCheckbox('summary_enabled', $this->lang['form.enable.summary'], $this->get_item()->is_summary_enabled(),
					array('description' => StringVars::replace_vars($this->lang['form.summary.clue'], array('number' => $this->config->get_auto_cut_characters_number())), 'events' => array('click' => '
					if (HTMLForms.getField("summary_enabled").getValue()) {
						HTMLForms.getField("summary").enable();
					} else {
						HTMLForms.getField("summary").disable();
					}'))
				));

				$fieldset->add_field(new FormFieldRichTextEditor('summary', $this->lang['form.summary'], $this->get_item()->get_summary(),
					array('required' => true, 'hidden' => ($this->request->is_post_method() ? !$this->request->get_postbool(self::$module_id . '_form_summary_enabled', false) : !$this->get_item()->is_summary_enabled()))
				));
			}

			if ($this->config->get_author_displayed() && $this->get_item()->author_custom_name_field_enabled())
			{
				$fieldset->add_field(new FormFieldCheckbox('author_custom_name_enabled', $this->lang['form.enable.author.custom.name'], $this->get_item()->is_author_custom_name_enabled(),
					array('events' => array('click' => '
					if (HTMLForms.getField("author_custom_name_enabled").getValue()) {
						HTMLForms.getField("author_custom_name").enable();
					} else {
						HTMLForms.getField("author_custom_name").disable();
					}'))
				));

				$fieldset->add_field(new FormFieldTextEditor('author_custom_name', $this->lang['form.author.custom.name'], $this->get_item()->get_author_custom_name(),
					array('required' => true, 'hidden' => ($this->request->is_post_method() ? !$this->request->get_postbool(self::$module_id . '_form_author_custom_name_enabled', false) : !$this->get_item()->is_author_custom_name_enabled()))
				));
			}
		}
		$this->get_additional_attributes_fields($fieldset, 'attribute_post_content_field_parameters');
	}

	protected function build_options_fieldset(HTMLForm $form)
	{
		$fieldset = new FormFieldsetHTML('options', $this->lang['form.options']);
		$this->get_additional_attributes_fields($fieldset, 'attribute_options_field_parameters');

		if (self::get_module_configuration()->feature_is_enabled('keywords'))
			$fieldset->add_field(KeywordsService::get_keywords_manager()->get_form_field($this->get_item()->get_id(), 'keywords', $this->lang['form.keywords'],
				array('description' => $this->lang['form.keywords.clue'])
			));

		if (self::get_module_configuration()->feature_is_enabled('sources'))
			$fieldset->add_field(new FormFieldSelectSources('sources', $this->lang['form.sources'], $this->get_item()->get_sources()));

		if ($fieldset->get_fields())
		{
			$form->add_fieldset($fieldset);
		}
	}

	protected function build_publication_fieldset(HTMLForm $form)
	{
		if ((self::get_module_configuration()->has_categories() && CategoriesAuthorizationsService::check_authorizations($this->get_item()->get_id_category(), self::$module_id)->moderation()) || (!self::get_module_configuration()->has_categories() && ItemsAuthorizationsService::check_authorizations(self::$module_id)->moderation()))
		{
			$publication_fieldset = new FormFieldsetHTML('publication', $this->lang['form.publication']);
			$form->add_fieldset($publication_fieldset);

			$publication_fieldset->add_field(new FormFieldDateTime('creation_date', $this->lang['form.creation.date'], $this->get_item()->get_creation_date(),
				array('required' => true)
			));

			if (!$this->is_new_item && !$this->get_item()->is_published())
			{
				$publication_fieldset->add_field(new FormFieldCheckbox('update_creation_date', $this->lang['form.update.creation.date'], FormFieldCheckbox::UNCHECKED,
					array('hidden' => $this->get_item()->get_status() != Item::NOT_PUBLISHED)
				));
			}

			if (self::get_module_configuration()->feature_is_enabled('deferred_publication'))
			{
				$publication_fieldset->add_field(new FormFieldSimpleSelectChoice('publishing_state', $this->lang['form.publication'], $this->get_item()->get_publishing_state(),
					array(
						new FormFieldSelectChoiceOption($this->lang['form.publication.draft'], Item::NOT_PUBLISHED),
						new FormFieldSelectChoiceOption($this->lang['form.publication.now'], Item::PUBLISHED),
						new FormFieldSelectChoiceOption($this->lang['form.publication.deffered'], Item::DEFERRED_PUBLICATION),
					),
					array(
						'events' => array('change' => '
							if (HTMLForms.getField("publishing_state").getValue() == 2) {
								jQuery("#' . self::$module_id . '_form_publishing_start_date_field").show();
								HTMLForms.getField("end_date_enabled").enable();
								if (HTMLForms.getField("end_date_enabled").getValue()) {
									HTMLForms.getField("publishing_end_date").enable();
								}
							} else {
								jQuery("#' . self::$module_id . '_form_publishing_start_date_field").hide();
								HTMLForms.getField("end_date_enabled").disable();
								HTMLForms.getField("publishing_end_date").disable();
							}'
						)
					)
				));

				$publication_fieldset->add_field($publishing_start_date = new FormFieldDateTime('publishing_start_date', $this->lang['form.start.date'],
					($this->get_item()->get_publishing_start_date() === null ? new Date() : $this->get_item()->get_publishing_start_date()),
					array('hidden' => ($this->request->is_post_method() ? ($this->request->get_postint(self::$module_id . '_form_publishing_state', 0) != Item::DEFERRED_PUBLICATION) : ($this->get_item()->get_publishing_state() != Item::DEFERRED_PUBLICATION)))
				));

				$publication_fieldset->add_field(new FormFieldCheckbox('end_date_enabled', $this->lang['form.enable.end.date'], $this->get_item()->end_date_enabled(),
					array(
						'hidden' => ($this->request->is_post_method() ? ($this->request->get_postint(self::$module_id . '_form_publishing_state', 0) != Item::DEFERRED_PUBLICATION) : ($this->get_item()->get_publishing_state() != Item::DEFERRED_PUBLICATION)),
						'events' => array('click' => '
							if (HTMLForms.getField("end_date_enabled").getValue()) {
								HTMLForms.getField("publishing_end_date").enable();
							} else {
								HTMLForms.getField("publishing_end_date").disable();
							}'
						)
					)
				));

				$publication_fieldset->add_field($publishing_end_date = new FormFieldDateTime('publishing_end_date', $this->lang['form.end.date'],
					($this->get_item()->get_publishing_end_date() === null ? new date() : $this->get_item()->get_publishing_end_date()),
					array('hidden' => ($this->request->is_post_method() ? !$this->request->get_postbool(self::$module_id . '_form_end_date_enabled', false) : !$this->get_item()->end_date_enabled()))
				));

				$publishing_end_date->add_form_constraint(new FormConstraintFieldsDifferenceSuperior($publishing_start_date, $publishing_end_date));
			}
			else
			{
				$fieldset->add_field(new FormFieldCheckbox('publishing_state', $this->lang['form.approve'], $this->get_item()->get_publishing_state()));
			}
		}
	}

	protected function get_additional_attributes_fields(FormFieldset $fieldset, $attribute_field)
	{
		foreach ($this->get_item()->get_additional_attributes_list() as $id => $attribute)
		{
			if (isset($attribute[$attribute_field]))
			{
				$parameters = $attribute[$attribute_field];
				$field_class = $parameters['field_class'];

				if (!$this->is_new_item)
				{
					$parameters['value'] = ($this->get_item()->get_additional_property($id) instanceof Url) ? $this->get_item()->get_additional_property($id)->relative() : $this->get_item()->get_additional_property($id);
				}

				$ref_class = new ReflectionClass($field_class);
				unset($parameters['field_class']);
				array_unshift($parameters, $id);
				$field_instance = $ref_class->newInstanceArgs(array_values($parameters));
				$fieldset->add_field($field_instance);
			}
		}
	}

	protected function save()
	{
		$this->get_item()->set_title($this->form->get_value($this->item_class::get_title_label()));

		if (self::get_module_configuration()->has_categories() && CategoriesService::get_categories_manager()->get_categories_cache()->has_categories())
			$this->get_item()->set_id_category($this->form->get_value('id_category')->get_raw_value());

		if ($this->get_item()->content_field_enabled())
		{
			$this->get_item()->set_content($this->form->get_value($this->item_class::get_content_label()));
		}

		if (self::get_module_configuration()->has_rich_items())
		{
			if ($this->get_item()->content_field_enabled() && $this->get_item()->summary_field_enabled())
				$this->get_item()->set_summary(($this->form->get_value('summary_enabled') ? $this->form->get_value('summary') : ''));

			if ($this->config->get_author_displayed() && $this->get_item()->author_custom_name_field_enabled())
				$this->get_item()->set_author_custom_name(($this->form->get_value('author_custom_name') && $this->form->get_value('author_custom_name') !== $this->get_item()->get_author_user()->get_display_name() ? $this->form->get_value('author_custom_name') : ''));
		}

		foreach ($this->get_item()->get_additional_attributes_list() as $id => $attribute)
		{
			$has_value = false;
			foreach (array('attribute_pre_content_field_parameters', 'attribute_post_content_field_parameters', 'attribute_options_field_parameters') as $attribute_field)
			{
				if (isset($attribute[$attribute_field]))
				{
					$value = (preg_match('/Choice/', $attribute[$attribute_field]['field_class'])) ? $this->form->get_value($id)->get_raw_value() : $this->form->get_value($id);
					$value = ($attribute['is_url'] ? new Url($value) : $value);
					$has_value = true;
				}
			}

			if ($has_value)
				$this->get_item()->set_additional_property($id, $value);
		}

		if (self::get_module_configuration()->feature_is_enabled('sources'))
			$this->get_item()->set_sources($this->form->get_value('sources'));

		if ((self::get_module_configuration()->has_categories() && !CategoriesAuthorizationsService::check_authorizations($this->get_item()->get_id_category(), self::$module_id)->moderation()) || (!self::get_module_configuration()->has_categories() && !ItemsAuthorizationsService::check_authorizations(self::$module_id)->moderation()))
		{
			$this->get_item()->set_rewrited_title(Url::encode_rewrite($this->get_item()->get_title()));
			$this->get_item()->clean_publishing_start_and_end_date();

			if ((self::get_module_configuration()->has_categories() && !CategoriesAuthorizationsService::check_authorizations($this->get_item()->get_id_category(), self::$module_id)->write()) || (!self::get_module_configuration()->has_categories() && !ItemsAuthorizationsService::check_authorizations(self::$module_id)->write()))
				$this->get_item()->set_publishing_state(Item::NOT_PUBLISHED);
		}
		else
		{
			$rewrited_title = $this->form->get_value('rewrited_' . $this->item_class::get_title_label(), '');
			$rewrited_title = $this->form->get_value('personalize_rewrited_' . $this->item_class::get_title_label()) && !empty($rewrited_title) ? $rewrited_title : Url::encode_rewrite($this->get_item()->get_title());
			$this->get_item()->set_rewrited_title($rewrited_title);

			if (!$this->is_new_item && $this->form->get_value('update_creation_date'))
				$this->get_item()->set_creation_date(new Date());
			else
				$this->get_item()->set_creation_date($this->form->get_value('creation_date'));

			if (self::get_module_configuration()->feature_is_enabled('deferred_publication'))
			{
				$this->get_item()->set_publishing_state($this->form->get_value('publishing_state')->get_raw_value());
				if ($this->get_item()->get_publishing_state() == Item::DEFERRED_PUBLICATION)
				{
					$deferred_operations = $this->config->get_deferred_operations();

					$old_start_date = $this->get_item()->get_publishing_start_date();
					$start_date = $this->form->get_value('publishing_start_date');
					$this->get_item()->set_publishing_start_date($start_date);

					if ($old_start_date !== null && $old_start_date->get_timestamp() != $start_date->get_timestamp() && in_array($old_start_date->get_timestamp(), $deferred_operations))
					{
						$key = array_search($old_start_date->get_timestamp(), $deferred_operations);
						unset($deferred_operations[$key]);
					}

					if (!in_array($start_date->get_timestamp(), $deferred_operations))
						$deferred_operations[] = $start_date->get_timestamp();

					if ($this->form->get_value('end_date_enabled'))
					{
						$old_end_date = $this->get_item()->get_publishing_end_date();
						$end_date = $this->form->get_value('publishing_end_date');
						$this->get_item()->set_publishing_end_date($end_date);

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
						$this->get_item()->clean_publishing_end_date();
					}

					$this->config->set_deferred_operations($deferred_operations);
					$configuration_class_name = self::get_module_configuration()->get_configuration_name();
					$configuration_class_name::save();
				}
				else
				{
					$this->get_item()->clean_publishing_start_and_end_date();
				}
			}
			else
			{
				$this->get_item()->set_publishing_state((int)$this->form->get_value('publishing_state'));
			}
		}

		if ($this->is_new_item)
		{
			$id = self::get_items_manager()->add($this->get_item());
			$this->get_item()->set_id($id);

			if (!$this->is_contributor_member())
				HooksService::execute_hook_action('add', self::$module_id, array_merge($this->get_item()->get_properties(), array('item_url' => $this->get_item()->get_item_url())));
		}
		else
		{
			$this->get_item()->set_update_date(new Date());
			self::get_items_manager()->update($this->get_item());

			if (!$this->is_contributor_member())
				HooksService::execute_hook_action('edit', self::$module_id, array_merge($this->get_item()->get_properties(), array('item_url' => $this->get_item()->get_item_url())));
		}

		$this->contribution_actions($this->get_item());

		if (self::get_module_configuration()->feature_is_enabled('keywords'))
			KeywordsService::get_keywords_manager()->put_relations($this->get_item()->get_id(), $this->form->get_value('keywords'));

		self::get_items_manager()->clear_cache();
	}

	protected function is_contributor_member()
	{
		return (self::get_module_configuration()->has_contribution() && ((self::get_module_configuration()->has_categories() && !CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, self::$module_id)->write() && CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, self::$module_id)->contribution()) || (!self::get_module_configuration()->has_categories() && !ItemsAuthorizationsService::check_authorizations(self::$module_id)->write() && ItemsAuthorizationsService::check_authorizations(self::$module_id)->contribution())));
	}

	protected function build_contribution_fieldset($form)
	{
		if ($this->is_new_item && $this->is_contributor_member())
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

	protected function contribution_actions(Item $item)
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
			$contribution->set_fixing_url(ItemsUrlBuilder::edit($item->get_id())->relative());
			$contribution->set_poster_id(AppContext::get_current_user()->get_id());
			$contribution->set_module(self::$module_id);

			if (self::get_module_configuration()->has_categories())
			{
				$contribution->set_auth(
					Authorizations::capture_and_shift_bit_auth(
						CategoriesService::get_categories_manager()->get_heritated_authorizations($item->get_id_category(), Category::MODERATION_AUTHORIZATIONS, Authorizations::AUTH_CHILD_PRIORITY),
						Category::MODERATION_AUTHORIZATIONS, Contribution::CONTRIBUTION_AUTH_BIT
					)
				);
			}
			else
			{
				$contribution->set_auth(
					Authorizations::capture_and_shift_bit_auth(
						$this->config->get_authorizations(),
						Item::MODERATION_AUTHORIZATIONS, Contribution::CONTRIBUTION_AUTH_BIT
					)
				);
			}

			ContributionService::save_contribution($contribution);
			HooksService::execute_hook_action($this->is_new_item ? 'add_contribution' : 'edit_contribution', self::$module_id, array_merge($contribution->get_properties(), $this->get_item()->get_properties(), array('item_url' => $this->get_item()->get_item_url())));
		}
		else
		{
			$corresponding_contributions = ContributionService::find_by_criteria(self::$module_id, $item->get_id());
			if (!$this->is_contributor_member() && count($corresponding_contributions) > 0)
			{
				foreach ($corresponding_contributions as $contribution)
				{
					$contribution->set_status(Event::EVENT_STATUS_PROCESSED);
					ContributionService::save_contribution($contribution);
				}
				HooksService::execute_hook_action('process_contribution', self::$module_id, array_merge($contribution->get_properties(), $this->get_item()->get_properties(), array('item_url' => $this->get_item()->get_item_url())));
			}
		}
	}

	protected function redirect()
	{
		if ($this->is_new_item && $this->is_contributor_member() && !$this->get_item()->is_published())
		{
			DispatchManager::redirect(new UserContributionSuccessController());
		}
		elseif ($this->get_item()->is_published())
		{
			if ($this->is_new_item)
			{
				if (self::get_module_configuration()->has_categories())
					AppContext::get_response()->redirect(ItemsUrlBuilder::display($this->get_item()->get_category()->get_id(), $this->get_item()->get_category()->get_rewrited_name(), $this->get_item()->get_id(), $this->get_item()->get_rewrited_title(), self::$module_id), StringVars::replace_vars($this->lang['items.message.success.add'], array('title' => $this->get_item()->get_title())));
				else
					AppContext::get_response()->redirect(ItemsUrlBuilder::display_item($this->get_item()->get_id(), $this->get_item()->get_rewrited_title(), self::$module_id), StringVars::replace_vars($this->lang['items.message.success.add'], array('title' => $this->get_item()->get_title())));
			}
			else
			{
				if (self::get_module_configuration()->has_categories())
					AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : ItemsUrlBuilder::display($this->get_item()->get_category()->get_id(), $this->get_item()->get_category()->get_rewrited_name(), $this->get_item()->get_id(), $this->get_item()->get_rewrited_title(), self::$module_id)), StringVars::replace_vars($this->lang['items.message.success.edit'], array('title' => $this->get_item()->get_title())));
				else
					AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : ItemsUrlBuilder::display_item($this->get_item()->get_id(), $this->get_item()->get_rewrited_title(), self::$module_id)), StringVars::replace_vars($this->lang['items.message.success.edit'], array('title' => $this->get_item()->get_title())));
			}
		}
		else
		{
			if ($this->is_new_item)
				AppContext::get_response()->redirect(ItemsUrlBuilder::display_pending(self::$module_id), StringVars::replace_vars($this->lang['items.message.success.add'], array('title' => $this->get_item()->get_title())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : ItemsUrlBuilder::display_pending(self::$module_id)), StringVars::replace_vars($this->lang['items.message.success.edit'], array('title' => $this->get_item()->get_title())));
		}
	}

	/**
	 * @return Response
	 */
	protected function generate_response()
	{
		$location_id = $this->get_item()->get_id() ? self::$module_id . '-edit-' . $this->get_item()->get_id() : '';

		$response = new SiteDisplayResponse($this->view, $location_id);
		$graphical_environment = $response->get_graphical_environment();

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add(self::get_module_configuration()->get_name(), ModulesUrlBuilder::home());

		if ($this->get_item()->get_id() === null)
		{
			$breadcrumb->add($this->lang['item.add'], ItemsUrlBuilder::add(self::get_module_configuration()->has_categories() ? $this->get_item()->get_id_category() : Category::ROOT_CATEGORY));
			$graphical_environment->set_page_title($this->lang['item.add'], self::get_module_configuration()->get_name());
			$graphical_environment->get_seo_meta_data()->set_canonical_url(ItemsUrlBuilder::add(self::get_module_configuration()->has_categories() ? $this->get_item()->get_id_category() : Category::ROOT_CATEGORY, self::$module_id));
		}
		else
		{
			if (self::get_module_configuration()->has_categories())
			{
				$categories = array_reverse(CategoriesService::get_categories_manager()->get_parents($this->get_item()->get_id_category(), true));
				foreach ($categories as $id => $category)
				{
					if ($category->get_id() != Category::ROOT_CATEGORY)
						$breadcrumb->add($category->get_name(), CategoriesUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), self::$module_id));
				}
				$breadcrumb->add($this->get_item()->get_title(), ItemsUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $this->get_item()->get_id(), $this->get_item()->get_rewrited_title(), self::$module_id));
			}
			else
				$breadcrumb->add($this->get_item()->get_title(), ItemsUrlBuilder::display_item($this->get_item()->get_id(), $this->get_item()->get_rewrited_title(), self::$module_id));

			$breadcrumb->add($this->lang['item.edit'], ItemsUrlBuilder::edit($this->get_item()->get_id(), self::$module_id));

			if (!AppContext::get_session()->location_id_already_exists($location_id))
				$graphical_environment->set_location_id($location_id);

			$graphical_environment->set_page_title(($this->lang['item.edition'] . ': ' . $this->get_item()->get_title()), self::get_module_configuration()->get_name());
			$graphical_environment->get_seo_meta_data()->set_canonical_url(ItemsUrlBuilder::edit($this->get_item()->get_id(), self::$module_id));
		}

		return $response;
	}
}
?>
