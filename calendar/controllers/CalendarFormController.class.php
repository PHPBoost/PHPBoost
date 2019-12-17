<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 12 16
 * @since       PHPBoost 4.0 - 2013 02 25
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CalendarFormController extends ModuleController
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
	private $config;

	private $event;
	private $is_new_event;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->check_authorizations();

		$this->build_form($request);

		$tpl = new StringTemplate('# INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();
			$this->redirect();
		}

		$tpl->put('FORM', $this->form->display());

		return $this->generate_response($tpl);
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'calendar');
		$this->config = CalendarConfig::load();
	}

	private function build_form(HTTPRequestCustom $request)
	{
		$common_lang = LangLoader::get('common');
		$date_lang = LangLoader::get('date-common');
		$event_content = $this->get_event()->get_content();

		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTMLHeading('event', $this->lang['calendar.titles.event']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('title', $common_lang['form.title'], $event_content->get_title(), array('required' => true)));

		if (CategoriesService::get_categories_manager()->get_categories_cache()->has_categories())
		{
			$search_category_children_options = new SearchCategoryChildrensOptions();
			$search_category_children_options->add_authorizations_bits(Category::CONTRIBUTION_AUTHORIZATIONS);
			$search_category_children_options->add_authorizations_bits(Category::WRITE_AUTHORIZATIONS);
			$fieldset->add_field(CategoriesService::get_categories_manager()->get_select_categories_form_field('category_id', LangLoader::get_message('category', 'categories-common'), $event_content->get_category_id(), $search_category_children_options));
		}

		$fieldset->add_field(new FormFieldRichTextEditor('contents', $common_lang['form.contents'], $event_content->get_contents(), array('rows' => 15, 'required' => true)));

		$fieldset->add_field(new FormFieldUploadPictureFile('picture', $this->lang['calendar.labels.picture'], $event_content->get_picture()->relative()));

		$fieldset->add_field($start_date = new FormFieldDateTime('start_date', $this->lang['calendar.labels.start_date'], $this->get_event()->get_start_date(), array('required' => true, 'five_minutes_step' => true)));

		$fieldset->add_field($end_date = new FormFieldDateTime('end_date', $this->lang['calendar.labels.end_date'], $this->get_event()->get_end_date(), array('required' => true, 'five_minutes_step' => true)));

		$form->add_constraint(new FormConstraintFieldsDifferenceSuperior($start_date, $end_date));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('repeat_type', $this->lang['calendar.labels.repeat_type'], $event_content->get_repeat_type(),
			array(
				new FormFieldSelectChoiceOption($this->lang['calendar.labels.repeat.never'], CalendarEventContent::NEVER),
				new FormFieldSelectChoiceOption($date_lang['every_day'], CalendarEventContent::DAILY),
				new FormFieldSelectChoiceOption($date_lang['every_week'], CalendarEventContent::WEEKLY),
				new FormFieldSelectChoiceOption($date_lang['every_month'], CalendarEventContent::MONTHLY),
				new FormFieldSelectChoiceOption($date_lang['every_year'], CalendarEventContent::YEARLY),
			),
			array('events' => array('change' => '
			if (HTMLForms.getField("repeat_type").getValue() != "' . CalendarEventContent::NEVER . '") {
				HTMLForms.getField("repeat_number").enable();
			} else {
				HTMLForms.getField("repeat_number").disable();
			}'))
		));

		$fieldset->add_field(new FormFieldNumberEditor('repeat_number', $this->lang['calendar.labels.repeat_number'], $event_content->get_repeat_number(),
			array('min' => 1, 'max' => 150, 'hidden' => $event_content->get_repeat_type() == CalendarEventContent::NEVER),
			array(new FormFieldConstraintIntegerRange(1, 150))
		));

		$unserialized_value = @unserialize($event_content->get_location());
		$location_value = $unserialized_value !== false ? $unserialized_value : $event_content->get_location();

		$location = '';
		if (is_array($location_value) && isset($location_value['address']))
			$location = $location_value['address'];
		else if (!is_array($location_value))
			$location = $location_value;

		if ($this->config->is_googlemaps_available())
		{
			$fieldset->add_field(new GoogleMapsFormFieldMapAddress('location', $this->lang['calendar.labels.location'], $location, array(
				'events' => array('blur' => '
				if (HTMLForms.getField("location").getValue()) {
					HTMLForms.getField("map_displayed").enable();
				} else {
					HTMLForms.getField("map_displayed").disable();
				}'
			))));

			$fieldset->add_field(new FormFieldCheckbox('map_displayed', $this->lang['calendar.labels.map_displayed'], $event_content->is_map_displayed(),
				array('hidden' => !$location)
			));
		}
		else
			$fieldset->add_field(new FormFieldShortMultiLineTextEditor('location', $this->lang['calendar.labels.location'], $location));

		$fieldset->add_field(new FormFieldCheckbox('registration_authorized', $this->lang['calendar.labels.registration_authorized'], $event_content->is_registration_authorized(), array(
			'events' => array('click' => '
			if (HTMLForms.getField("registration_authorized").getValue()) {
				HTMLForms.getField("max_registered_members").enable();
				HTMLForms.getField("last_registration_date_enabled").enable();
				jQuery("#' . __CLASS__ . '_register_authorizations").show();
			} else {
				HTMLForms.getField("max_registered_members").disable();
				HTMLForms.getField("last_registration_date_enabled").disable();
				jQuery("#' . __CLASS__ . '_register_authorizations").hide();
			}'
		))));

		$fieldset->add_field(new FormFieldNumberEditor('max_registered_members', $this->lang['calendar.labels.max_registered_members'], $event_content->get_max_registered_members(),
			array('description' => $this->lang['calendar.labels.max_registered_members.explain'], 'hidden' => !$event_content->is_registration_authorized()),
			array(new FormFieldConstraintRegex('`^[0-9]+$`iu'))
		));

		$fieldset->add_field(new FormFieldCheckbox('last_registration_date_enabled', $this->lang['calendar.labels.last_registration_date_enabled'], $event_content->is_last_registration_date_enabled(),array(
			'hidden' => !$event_content->is_registration_authorized(), 'events' => array('click' => '
			if (HTMLForms.getField("last_registration_date_enabled").getValue()) {
				HTMLForms.getField("last_registration_date").enable();
			} else {
				HTMLForms.getField("last_registration_date").disable();
			}'
		))));

		$fieldset->add_field(new FormFieldDateTime('last_registration_date', $this->lang['calendar.labels.last_registration_date'], $event_content->get_last_registration_date(), array(
			'hidden' => !$event_content->is_last_registration_date_enabled())
		));

		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($this->lang['calendar.authorizations.display_registered_users'], CalendarEventContent::DISPLAY_REGISTERED_USERS_AUTHORIZATION),
			new VisitorDisabledActionAuthorization($this->lang['calendar.authorizations.register'], CalendarEventContent::REGISTER_AUTHORIZATION)
		));
		$auth_settings->build_from_auth_array($event_content->get_register_authorizations());
		$auth_setter = new FormFieldAuthorizationsSetter('register_authorizations', $auth_settings, array('hidden' => !$event_content->is_registration_authorized()));
		$fieldset->add_field($auth_setter);

		if (CategoriesAuthorizationsService::check_authorizations($event_content->get_category_id())->moderation())
		{
			if ($this->get_event()->get_id() !== null)
				$fieldset->add_field(new FormFieldCheckbox('cancelled', $this->lang['calendar.labels.cancel'], $this->get_event()->get_content()->is_cancelled()));

			$fieldset->add_field(new FormFieldCheckbox('approved', $common_lang['form.approve'], $event_content->is_approved()));
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
		if ($this->get_event()->get_id() === null && $this->is_contributor_member())
		{
			$fieldset = new FormFieldsetHTML('contribution', LangLoader::get_message('contribution', 'user-common'));
			$fieldset->set_description(MessageHelper::display($this->lang['calendar.labels.contribution.explain'] . ' ' . LangLoader::get_message('contribution.explain', 'user-common'), MessageHelper::WARNING)->render());
			$form->add_fieldset($fieldset);

			$fieldset->add_field(new FormFieldRichTextEditor('contribution_description', LangLoader::get_message('contribution.description', 'user-common'), '', array('description' => LangLoader::get_message('contribution.description.explain', 'user-common'))));
		}
	}

	private function is_contributor_member()
	{
		return (!CategoriesAuthorizationsService::check_authorizations()->write() && CategoriesAuthorizationsService::check_authorizations()->contribution());
	}

	private function get_event()
	{
		if ($this->event === null)
		{
			$request = AppContext::get_request();
			$id = $request->get_getint('id', 0);
			if (!empty($id))
			{
				try {
					$this->event = CalendarService::get_event('WHERE id_event = :id', array('id' => $id));
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->is_new_event = true;
				$this->event = new CalendarEvent();
				$this->event->init_default_properties($request->get_getint('year', date('Y')), $request->get_getint('month', date('n')), $request->get_getint('day', date('j')));

				$event_content = new CalendarEventContent();
				$event_content->init_default_properties($request->get_getint('id_category', Category::ROOT_CATEGORY));

				$this->event->set_content($event_content);
			}
		}
		return $this->event;
	}

	private function check_authorizations()
	{
		$event = $this->get_event();

		if ($event->get_id() === null)
		{
			if (!$event->is_authorized_to_add())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			if (!$event->is_authorized_to_edit())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		if (AppContext::get_current_user()->is_readonly())
		{
			$error_controller = PHPBoostErrors::user_in_read_only();
			DispatchManager::redirect($error_controller);
		}
	}

	private function save()
	{
		$event = $this->get_event();
		$event_content = $event->get_content();

		$event_content->set_title($this->form->get_value('title'));
		$event_content->set_rewrited_title(Url::encode_rewrite($this->form->get_value('title')));

		if (CategoriesService::get_categories_manager()->get_categories_cache()->has_categories())
			$event_content->set_category_id($this->form->get_value('category_id')->get_raw_value());

		$event_content->set_contents($this->form->get_value('contents'));
		$event_content->set_picture(new Url($this->form->get_value('picture')));
		$event_content->set_location($this->form->get_value('location'));

		if ($this->config->is_googlemaps_available())
		{
			if ($this->form->get_value('map_displayed'))
				$event_content->display_map();
			else
				$event_content->hide_map();
		}

		if (CategoriesAuthorizationsService::check_authorizations($event_content->get_category_id())->moderation())
		{
			if ($event->get_id() !== null && $this->form->get_value('cancelled'))
				$event_content->cancel();
			if ($event->get_id() !== null && !$this->form->get_value('cancelled'))
				$event_content->uncancel();

			if ($this->form->get_value('approved'))
				$event_content->approve();
			else
				$event_content->unapprove();
		}
		else if (CategoriesAuthorizationsService::check_authorizations($event_content->get_category_id())->contribution() && !CategoriesAuthorizationsService::check_authorizations($event_content->get_category_id())->write())
			$event_content->unapprove();

		if ($this->form->get_value('registration_authorized'))
		{
			$event_content->authorize_registration();
			$event_content->set_max_registered_members($this->form->get_value('max_registered_members'));

			if ($this->form->get_value('last_registration_date_enabled') && $this->form->get_value('last_registration_date') !== null)
			{
				$event_content->enable_last_registration_date();
				$event_content->set_last_registration_date($this->form->get_value('last_registration_date'));
			}
			else
			{
				$event_content->disable_last_registration_date();
				$event_content->set_last_registration_date(null);
			}

			$event_content->set_register_authorizations($this->form->get_value('register_authorizations', $event_content->get_register_authorizations())->build_auth_array());
		}
		else
			$event_content->unauthorize_registration();

		$event_content->set_repeat_type($this->form->get_value('repeat_type')->get_raw_value());

		if ($event_content->get_repeat_type() != CalendarEventContent::NEVER)
			$event_content->set_repeat_number($this->form->get_value('repeat_number'));

		$event->set_start_date($this->form->get_value('start_date'));
		$event->set_end_date($this->form->get_value('end_date'));

		if ($event->get_id() === null)
		{
			$id_content = CalendarService::add_event_content($event_content);
			$event_content->set_id($id_content);

			$event->set_content($event_content);

			$id_event = CalendarService::add_event($event);

			if ($event->get_content()->is_repeatable())
			{
				$new_start_date = $event->get_start_date();
				$new_end_date = $event->get_end_date();

				for ($i = 1 ; $i <= $event->get_content()->get_repeat_number() ; $i++)
				{
					$e = new CalendarEvent();
					$e->set_content($event->get_content());
					$e->set_parent_id($id_event);

					$e = $this->set_event_start_and_end_date($e, $new_start_date, $new_end_date);

					CalendarService::add_event($e);

					$new_start_date = $e->get_start_date();
					$new_end_date = $e->get_end_date();
				}
			}
		}
		else
		{
			CalendarService::update_event_content($event_content);
			$id_event = CalendarService::update_event($event);

			if ($event->get_content()->is_repeatable() || $event_content->is_repeatable() && ($event->get_content()->get_repeat_number() != $event_content->get_repeat_number() || $event->get_content()->get_repeat_type() != $event_content->get_repeat_type()))
			{
				$events_list = CalendarService::get_serie_events($event_content->get_id());

				$new_start_date = $event->get_start_date();
				$new_end_date = $event->get_end_date();

				$i = 0;
				foreach ($events_list as $id => $e)
				{
					if ($id != $id_event)
					{
						$e->set_content($event_content);

						$e = $this->set_event_start_and_end_date($e, $new_start_date, $new_end_date);

						if ($i <= $event_content->get_repeat_number())
							CalendarService::update_event($e);
						else
							CalendarService::delete_event('WHERE id_event = :id', array('id' => $e->get_id()));

						$new_start_date = $e->get_start_date();
						$new_end_date = $e->get_end_date();
					}
					$i++;
				}

				if ($i < $event_content->get_repeat_number())
				{
					for ($j = $i ; $j <= $event_content->get_repeat_number() ; $j++)
					{
						$e = new CalendarEvent();
						$e->set_content($event_content);
						$e->set_parent_id($id_event);

						$e = $this->set_event_start_and_end_date($e, $new_start_date, $new_end_date);

						CalendarService::add_event($e);

						$new_start_date = $e->get_start_date();
						$new_end_date = $e->get_end_date();
					}
				}
			}
		}

		$this->contribution_actions($event, $id_event);

		CalendarService::clear_cache();
	}

	private function set_event_start_and_end_date(CalendarEvent $event, $new_start_date, $new_end_date)
	{
		switch ($event->get_content()->get_repeat_type())
		{
			case CalendarEventContent::DAILY:
				$new_start_date->add_days(1);
				$new_end_date->add_days(1);
				$event->set_start_date($new_start_date);
				$event->set_end_date($new_end_date);
				break;
			case CalendarEventContent::WEEKLY:
				$new_start_date->add_weeks(1);
				$new_end_date->add_weeks(1);
				$event->set_start_date($new_start_date);
				$event->set_end_date($new_end_date);
				break;
			case CalendarEventContent::MONTHLY:
				$new_start_month = $new_start_date->get_month() + 1;
				if ($new_start_month > 12)
				{
					$new_start_date->set_month(1);
					$new_start_date->set_year($new_start_date->get_year() + 1);
				}
				else
					$new_start_date->set_month($new_start_month);
				$new_end_month = $new_end_date->get_month() + 1;
				if ($new_end_month > 12)
				{
					$new_end_date->set_month(1);
					$new_end_date->set_year($new_end_date->get_year() + 1);
				}
				else
					$new_end_date->set_month($new_end_month);
				$event->set_start_date($new_start_date);
				$event->set_end_date($new_end_date);
				break;
			case CalendarEventContent::YEARLY:
				$new_start_date->set_year($new_start_date->get_year() + 1);
				$new_end_date->set_year($new_end_date->get_year() + 1);
				$event->set_start_date($new_start_date);
				$event->set_end_date($new_end_date);
				break;
			default :
				$event->set_start_date($new_start_date);
				$event->set_end_date($new_end_date);
				break;
		}

		return $event;
	}

	private function contribution_actions(CalendarEvent $event, $id_event)
	{
		if ($this->is_contributor_member() && $event->get_id() === null)
		{
			$contribution = new Contribution();
			$contribution->set_id_in_module($id_event);
			$contribution->set_description(stripslashes($this->form->get_value('contribution_description')));
			$contribution->set_entitled($event->get_content()->get_title());
			$contribution->set_fixing_url(CalendarUrlBuilder::edit_event($id_event)->relative());
			$contribution->set_poster_id(AppContext::get_current_user()->get_id());
			$contribution->set_module('calendar');
			$contribution->set_auth(
				Authorizations::capture_and_shift_bit_auth(
					CategoriesService::get_categories_manager()->get_heritated_authorizations($event->get_content()->get_category_id(), Category::MODERATION_AUTHORIZATIONS, Authorizations::AUTH_CHILD_PRIORITY),
					Category::MODERATION_AUTHORIZATIONS, Contribution::CONTRIBUTION_AUTH_BIT
				)
			);
			ContributionService::save_contribution($contribution);
		}
		else
		{
			$corresponding_contributions = ContributionService::find_by_criteria('calendar', $id_event);
			if (count($corresponding_contributions) > 0)
			{
				foreach ($corresponding_contributions as $contribution)
				{
					$contribution->set_status(Event::EVENT_STATUS_PROCESSED);
					ContributionService::save_contribution($contribution);
				}
			}
		}
		$event->set_id($id_event);
	}

	private function redirect()
	{
		$event = $this->get_event();
		$category = $event->get_content()->get_category();

		if ($this->is_new_event && $this->is_contributor_member() && !$event->get_content()->is_approved())
		{
			DispatchManager::redirect(new UserContributionSuccessController());
		}
		elseif ($event->get_content()->is_approved())
		{
			if ($this->is_new_event)
				AppContext::get_response()->redirect(CalendarUrlBuilder::home($event->get_start_date()->get_year(), $event->get_start_date()->get_month(), $event->get_start_date()->get_day() , true), StringVars::replace_vars($this->lang['calendar.message.success.add'], array('title' => $event->get_content()->get_title())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : CalendarUrlBuilder::home($event->get_start_date()->get_year(), $event->get_start_date()->get_month(), $event->get_start_date()->get_day() , true)), StringVars::replace_vars($this->lang['calendar.message.success.edit'], array('title' => $event->get_content()->get_title())));
		}
		else
		{
			if ($this->is_new_event)
				AppContext::get_response()->redirect(CalendarUrlBuilder::display_pending_events(), StringVars::replace_vars($this->lang['calendar.message.success.add'], array('title' => $event->get_content()->get_title())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : CalendarUrlBuilder::display_pending_events()), StringVars::replace_vars($this->lang['calendar.message.success.edit'], array('title' => $event->get_content()->get_title())));
		}
	}

	private function generate_response(View $tpl)
	{
		$event = $this->get_event();

		$location_id = $event->get_id() ? 'calendar-edit-'. $event->get_id() : '';

		$response = new SiteDisplayResponse($tpl, $location_id);
		$graphical_environment = $response->get_graphical_environment();

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'], CalendarUrlBuilder::home());

		if ($event->get_id() === null)
		{
			$graphical_environment->set_page_title($this->lang['calendar.titles.add_event'], $this->lang['module_title']);
			$breadcrumb->add($this->lang['calendar.titles.add_event'], CalendarUrlBuilder::add_event());
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['calendar.titles.add_event']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(CalendarUrlBuilder::add_event());
		}
		else
		{
			if (!AppContext::get_session()->location_id_already_exists($location_id))
				$graphical_environment->set_location_id($location_id);

			$graphical_environment->set_page_title($this->lang['calendar.titles.event_edition'], $this->lang['module_title']);

			$category = $event->get_content()->get_category();
			$breadcrumb->add($event->get_content()->get_title(), CalendarUrlBuilder::display_event($category->get_id(), $category->get_rewrited_name(), $event->get_id(), $event->get_content()->get_rewrited_title()));

			$breadcrumb->add($this->lang['calendar.titles.event_edition'], CalendarUrlBuilder::edit_event($event->get_id()));
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['calendar.titles.event_edition']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(CalendarUrlBuilder::edit_event($event->get_id()));
		}

		return $response;
	}
}
?>
