<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 20
 * @since       PHPBoost 4.0 - 2013 02 25
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class CalendarItemFormController extends ModuleController
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
		$this->lang = LangLoader::get('common', 'calendar');
		$this->config = CalendarConfig::load();
	}

	private function build_form(HTTPRequestCustom $request)
	{
		$common_lang = LangLoader::get('common');
		$date_lang = LangLoader::get('date-common');
		$item_content = $this->get_item()->get_content();

		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTMLHeading('event', $item_content === null ? $this->lang['calendar.event.add'] : $this->lang['calendar.event.edit']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldTextEditor('title', $common_lang['form.title'], $item_content->get_title(), array('required' => true)));

		if (CategoriesService::get_categories_manager()->get_categories_cache()->has_categories())
		{
			$search_category_children_options = new SearchCategoryChildrensOptions();
			$search_category_children_options->add_authorizations_bits(Category::CONTRIBUTION_AUTHORIZATIONS);
			$search_category_children_options->add_authorizations_bits(Category::WRITE_AUTHORIZATIONS);
			$fieldset->add_field(CategoriesService::get_categories_manager()->get_select_categories_form_field('category_id', LangLoader::get_message('category', 'categories-common'), $item_content->get_id_category(), $search_category_children_options));
		}

		$fieldset->add_field(new FormFieldRichTextEditor('content', $common_lang['form.content'], $item_content->get_content(), array('rows' => 15, 'required' => true)));

		$fieldset->add_field(new FormFieldUploadPictureFile('thumbnail', $this->lang['calendar.labels.thumbnail'], $item_content->get_thumbnail()->relative()));

		$fieldset->add_field($start_date = new FormFieldDateTime('start_date', $this->lang['calendar.labels.start.date'], $this->get_item()->get_start_date(), array('required' => true, 'five_minutes_step' => true)));

		$fieldset->add_field($end_date = new FormFieldDateTime('end_date', $this->lang['calendar.labels.end.date'], $this->get_item()->get_end_date(), array('required' => true, 'five_minutes_step' => true)));

		$end_date->add_form_constraint(new FormConstraintFieldsDifferenceSuperior($start_date, $end_date));

		$fieldset->add_field(new FormFieldSimpleSelectChoice('repeat_type', $this->lang['calendar.labels.repeat.type'], $item_content->get_repeat_type(),
			array(
				new FormFieldSelectChoiceOption($this->lang['calendar.labels.repeat.never'], CalendarItemContent::NEVER),
				new FormFieldSelectChoiceOption($date_lang['every_day'], CalendarItemContent::DAILY),
				new FormFieldSelectChoiceOption($date_lang['every_week'], CalendarItemContent::WEEKLY),
				new FormFieldSelectChoiceOption($date_lang['every_month'], CalendarItemContent::MONTHLY),
				new FormFieldSelectChoiceOption($date_lang['every_year'], CalendarItemContent::YEARLY),
			),
			array('events' => array('change' => '
			if (HTMLForms.getField("repeat_type").getValue() != "' . CalendarItemContent::NEVER . '") {
				HTMLForms.getField("repeat_number").enable();
			} else {
				HTMLForms.getField("repeat_number").disable();
			}'))
		));

		$fieldset->add_field(new FormFieldNumberEditor('repeat_number', $this->lang['calendar.labels.repeat.number'], $item_content->get_repeat_number(),
			array('min' => 1, 'max' => 150, 'hidden' => ($request->is_post_method() ? ($request->get_poststring(__CLASS__ . '_repeat_type', '') == CalendarItemContent::NEVER) : $item_content->get_repeat_type() == CalendarItemContent::NEVER)),
			array(new FormFieldConstraintIntegerRange(1, 150))
		));

		$unserialized_value = @unserialize($item_content->get_location());
		$location_value = $unserialized_value !== false ? $unserialized_value : $item_content->get_location();

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

			$fieldset->add_field(new FormFieldCheckbox('map_displayed', $this->lang['calendar.labels.map.displayed'], $item_content->is_map_displayed(),
				array('hidden' => !$location)
			));
		}
		else
			$fieldset->add_field(new FormFieldShortMultiLineTextEditor('location', $this->lang['calendar.labels.location'], $location));

		$fieldset->add_field(new FormFieldCheckbox('registration_authorized', $this->lang['calendar.labels.registration.authorized'], $item_content->is_registration_authorized(), array(
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

		$fieldset->add_field(new FormFieldNumberEditor('max_registered_members', $this->lang['calendar.labels.max.registered.members'], $item_content->get_max_registered_members(),
			array('description' => $this->lang['calendar.labels.max.registered.members.explain'], 'hidden' => ($request->is_post_method() ? !$request->get_postbool(__CLASS__ . '_registration_authorized', false) : !$item_content->is_registration_authorized())),
			array(new FormFieldConstraintRegex('`^[0-9]+$`iu'))
		));

		$fieldset->add_field(new FormFieldCheckbox('last_registration_date_enabled', $this->lang['calendar.labels.last.registration.date.enabled'], $item_content->is_last_registration_date_enabled(),array(
			'hidden' => ($request->is_post_method() ? !$request->get_postbool(__CLASS__ . '_registration_authorized', false) : !$item_content->is_registration_authorized()), 'events' => array('click' => '
			if (HTMLForms.getField("last_registration_date_enabled").getValue()) {
				HTMLForms.getField("last_registration_date").enable();
			} else {
				HTMLForms.getField("last_registration_date").disable();
			}'
		))));

		$fieldset->add_field($last_registration_date = new FormFieldDateTime('last_registration_date', $this->lang['calendar.labels.last.registration.date'], $item_content->get_last_registration_date(), array(
			'hidden' => ($request->is_post_method() ? !$request->get_postbool(__CLASS__ . '_last_registration_date_enabled', false) : !$item_content->is_last_registration_date_enabled()))
		));

		$last_registration_date->add_form_constraint(new FormConstraintFieldsDifferenceInferior($start_date, $last_registration_date));

		$auth_settings = new AuthorizationsSettings(array(
			new ActionAuthorization($this->lang['calendar.authorizations.display.registered.users'], CalendarItemContent::DISPLAY_REGISTERED_USERS_AUTHORIZATION),
			new VisitorDisabledActionAuthorization($this->lang['calendar.authorizations.register'], CalendarItemContent::REGISTER_AUTHORIZATION)
		));
		$auth_settings->build_from_auth_array($item_content->get_register_authorizations());
		$auth_setter = new FormFieldAuthorizationsSetter('register_authorizations', $auth_settings, array('hidden' => ($request->is_post_method() ? !$request->get_postbool(__CLASS__ . '_registration_authorized', false) : !$item_content->is_registration_authorized())));
		$fieldset->add_field($auth_setter);

		if ($this->get_item()->get_id() !== null)
			$fieldset->add_field(new FormFieldCheckbox('cancelled', $this->lang['calendar.labels.cancel'], $this->get_item()->get_content()->is_cancelled()));

		if (CategoriesAuthorizationsService::check_authorizations($item_content->get_id_category())->moderation())
			$fieldset->add_field(new FormFieldCheckbox('approved', $common_lang['form.approve'], $item_content->is_approved()));

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
		if ($this->get_item()->get_id() === null && $this->is_contributor_member())
		{
			$fieldset = new FormFieldsetHTML('contribution', $user_common['contribution']);
			$fieldset->set_description(MessageHelper::display($user_common['contribution.extended.explain'], MessageHelper::WARNING)->render());
			$form->add_fieldset($fieldset);

			$fieldset->add_field(new FormFieldRichTextEditor('contribution_description', $user_common['contribution.description'], '', array('description' => $user_common['contribution.description.explain'])));
		}
		elseif ($this->get_item()->is_authorized_to_edit() && !AppContext::get_current_user()->check_level(User::ADMIN_LEVEL))
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

	private function get_item()
	{
		if ($this->item === null)
		{
			$request = AppContext::get_request();
			$id = $request->get_getint('id', 0);
			if (!empty($id))
			{
				try {
					$this->item = CalendarService::get_item('WHERE id_event = :id', array('id' => $id));
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->is_new_item = true;
				$this->item = new CalendarItem();
				$this->item->init_default_properties($request->get_getint('year', date('Y')), $request->get_getint('month', date('n')), $request->get_getint('day', date('j')));

				$item_content = new CalendarItemContent();
				$item_content->init_default_properties($request->get_getint('id_category', Category::ROOT_CATEGORY));

				$this->item->set_content($item_content);
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
			$error_controller = PHPBoostErrors::user_in_read_only();
			DispatchManager::redirect($error_controller);
		}
	}

	private function save()
	{
		$item = $this->get_item();
		$item_content = $item->get_content();

		$item_content->set_title($this->form->get_value('title'));
		$item_content->set_rewrited_title(Url::encode_rewrite($this->form->get_value('title')));

		if (CategoriesService::get_categories_manager()->get_categories_cache()->has_categories())
			$item_content->set_id_category($this->form->get_value('category_id')->get_raw_value());

		$item_content->set_content($this->form->get_value('content'));
		$item_content->set_thumbnail(new Url($this->form->get_value('thumbnail')));
		$item_content->set_location($this->form->get_value('location'));

		if ($this->config->is_googlemaps_available())
		{
			if ($this->form->get_value('map_displayed'))
				$item_content->display_map();
			else
				$item_content->hide_map();
		}

		if ($item->get_id() !== null && $this->form->get_value('cancelled'))
			$item_content->cancel();
		if ($item->get_id() !== null && !$this->form->get_value('cancelled'))
			$item_content->uncancel();

		if (CategoriesAuthorizationsService::check_authorizations($item_content->get_id_category())->moderation())
		{
			if ($this->form->get_value('approved'))
				$item_content->approve();
			else
				$item_content->unapprove();
		}
		else if (CategoriesAuthorizationsService::check_authorizations($item_content->get_id_category())->contribution() && !CategoriesAuthorizationsService::check_authorizations($item_content->get_id_category())->write())
			$item_content->unapprove();

		if ($this->form->get_value('registration_authorized'))
		{
			$item_content->authorize_registration();
			$item_content->set_max_registered_members($this->form->get_value('max_registered_members'));

			if ($this->form->get_value('last_registration_date_enabled') && $this->form->get_value('last_registration_date') !== null)
			{
				$item_content->enable_last_registration_date();
				$item_content->set_last_registration_date($this->form->get_value('last_registration_date'));
			}
			else
			{
				$item_content->disable_last_registration_date();
				$item_content->set_last_registration_date(null);
			}

			$item_content->set_register_authorizations($this->form->get_value('register_authorizations', $item_content->get_register_authorizations())->build_auth_array());
		}
		else
			$item_content->unauthorize_registration();

		$item_content->set_repeat_type($this->form->get_value('repeat_type')->get_raw_value());

		if ($item_content->get_repeat_type() != CalendarItemContent::NEVER)
			$item_content->set_repeat_number($this->form->get_value('repeat_number'));

		$item->set_start_date($this->form->get_value('start_date'));
		$item->set_end_date($this->form->get_value('end_date'));

		if ($item->get_id() === null)
		{
			$id_content = CalendarService::add_item_content($item_content);
			$item_content->set_id($id_content);

			$item->set_content($item_content);

			$item_id = CalendarService::add_item($item);

			if ($item->get_content()->is_repeatable())
			{
				$new_start_date = $item->get_start_date();
				$new_end_date = $item->get_end_date();

				for ($i = 1 ; $i <= $item->get_content()->get_repeat_number() ; $i++)
				{
					$e = new CalendarItem();
					$e->set_content($item->get_content());
					$e->set_parent_id($item_id);

					$e = $this->set_event_start_and_end_date($e, $new_start_date, $new_end_date);

					CalendarService::add_item($e);

					$new_start_date = $e->get_start_date();
					$new_end_date = $e->get_end_date();
				}
			}
		}
		else
		{
			CalendarService::update_item_content($item_content);
			$item_id = CalendarService::update_item($item);

			if ($item->get_content()->is_repeatable() || $item_content->is_repeatable() && ($item->get_content()->get_repeat_number() != $item_content->get_repeat_number() || $item->get_content()->get_repeat_type() != $item_content->get_repeat_type()))
			{
				$items_list = CalendarService::get_serie_items($item_content->get_id());

				$new_start_date = $item->get_start_date();
				$new_end_date = $item->get_end_date();

				$i = 0;
				foreach ($items_list as $id => $e)
				{
					if ($id != $item_id)
					{
						$e->set_content($item_content);

						$e = $this->set_event_start_and_end_date($e, $new_start_date, $new_end_date);

						if ($i <= $item_content->get_repeat_number())
							CalendarService::update_item($e);
						else
							CalendarService::delete_item('WHERE id_event = :id', array('id' => $e->get_id()));

						$new_start_date = $e->get_start_date();
						$new_end_date = $e->get_end_date();
					}
					$i++;
				}

				if ($i < $item_content->get_repeat_number())
				{
					for ($j = $i ; $j <= $item_content->get_repeat_number() ; $j++)
					{
						$e = new CalendarItem();
						$e->set_content($item_content);
						$e->set_parent_id($item_id);

						$e = $this->set_event_start_and_end_date($e, $new_start_date, $new_end_date);

						CalendarService::add_item($e);

						$new_start_date = $e->get_start_date();
						$new_end_date = $e->get_end_date();
					}
				}
			}
		}

		$this->contribution_actions($item, $item_id);

		CalendarService::clear_cache();
	}

	private function set_event_start_and_end_date(CalendarItem $item, $new_start_date, $new_end_date)
	{
		switch ($item->get_content()->get_repeat_type())
		{
			case CalendarItemContent::DAILY:
				$new_start_date->add_days(1);
				$new_end_date->add_days(1);
				$item->set_start_date($new_start_date);
				$item->set_end_date($new_end_date);
				break;
			case CalendarItemContent::WEEKLY:
				$new_start_date->add_weeks(1);
				$new_end_date->add_weeks(1);
				$item->set_start_date($new_start_date);
				$item->set_end_date($new_end_date);
				break;
			case CalendarItemContent::MONTHLY:
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
				$item->set_start_date($new_start_date);
				$item->set_end_date($new_end_date);
				break;
			case CalendarItemContent::YEARLY:
				$new_start_date->set_year($new_start_date->get_year() + 1);
				$new_end_date->set_year($new_end_date->get_year() + 1);
				$item->set_start_date($new_start_date);
				$item->set_end_date($new_end_date);
				break;
			default :
				$item->set_start_date($new_start_date);
				$item->set_end_date($new_end_date);
				break;
		}

		return $item;
	}

	private function contribution_actions(CalendarItem $item, $item_id)
	{
		if($this->is_contributor_member())
		{
			$contribution = new Contribution();
			$contribution->set_id_in_module($item_id);
			if ($item->get_id() === null)
				$contribution->set_description(stripslashes($this->form->get_value('contribution_description')));
			else
				$contribution->set_description(stripslashes($this->form->get_value('edition_description')));

			$contribution->set_entitled($item->get_content()->get_title());
			$contribution->set_fixing_url(CalendarUrlBuilder::edit_event($item_id)->relative());
			$contribution->set_poster_id(AppContext::get_current_user()->get_id());
			$contribution->set_module('calendar');
			$contribution->set_auth(
				Authorizations::capture_and_shift_bit_auth(
					CategoriesService::get_categories_manager()->get_heritated_authorizations($item->get_content()->get_id_category(), Category::MODERATION_AUTHORIZATIONS, Authorizations::AUTH_CHILD_PRIORITY),
					Category::MODERATION_AUTHORIZATIONS, Contribution::CONTRIBUTION_AUTH_BIT
				)
			);
			ContributionService::save_contribution($contribution);
		}
		else
		{
			$corresponding_contributions = ContributionService::find_by_criteria('calendar', $item_id);
			if (count($corresponding_contributions) > 0)
			{
				foreach ($corresponding_contributions as $contribution)
				{
					$contribution->set_status(Event::EVENT_STATUS_PROCESSED);
					ContributionService::save_contribution($contribution);
				}
			}
		}
		$item->set_id($item_id);
	}

	private function redirect()
	{
		$item = $this->get_item();
		$category = $item->get_content()->get_category();

		if ($this->is_new_item && $this->is_contributor_member() && !$item->get_content()->is_approved())
		{
			DispatchManager::redirect(new UserContributionSuccessController());
		}
		elseif ($item->get_content()->is_approved())
		{
			if ($this->is_new_item)
				AppContext::get_response()->redirect(CalendarUrlBuilder::home($item->get_start_date()->get_year(), $item->get_start_date()->get_month(), $item->get_start_date()->get_day() , true), StringVars::replace_vars($this->lang['calendar.message.success.add'], array('title' => $item->get_content()->get_title())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : CalendarUrlBuilder::home($item->get_start_date()->get_year(), $item->get_start_date()->get_month(), $item->get_start_date()->get_day() , true)), StringVars::replace_vars($this->lang['calendar.message.success.edit'], array('title' => $item->get_content()->get_title())));
		}
		else
		{
			if ($this->is_new_item)
				AppContext::get_response()->redirect(CalendarUrlBuilder::display_pending_events(), StringVars::replace_vars($this->lang['calendar.message.success.add'], array('title' => $item->get_content()->get_title())));
			else
				AppContext::get_response()->redirect(($this->form->get_value('referrer') ? $this->form->get_value('referrer') : CalendarUrlBuilder::display_pending_events()), StringVars::replace_vars($this->lang['calendar.message.success.edit'], array('title' => $item->get_content()->get_title())));
		}
	}

	private function generate_response(View $view)
	{
		$item = $this->get_item();

		$location_id = $item->get_id() ? 'item-edit-'. $item->get_id() : '';

		$response = new SiteDisplayResponse($view, $location_id);
		$graphical_environment = $response->get_graphical_environment();

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module.title'], CalendarUrlBuilder::home());

		if ($item->get_id() === null)
		{
			$graphical_environment->set_page_title($this->lang['calendar.event.add'], $this->lang['module.title']);
			$breadcrumb->add($this->lang['calendar.event.add'], CalendarUrlBuilder::add_event());
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['calendar.event.add']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(CalendarUrlBuilder::add_event());
		}
		else
		{
			if (!AppContext::get_session()->location_id_already_exists($location_id))
				$graphical_environment->set_location_id($location_id);

			$graphical_environment->set_page_title($this->lang['calendar.event.edit'], $this->lang['module.title']);

			$category = $item->get_content()->get_category();
			$breadcrumb->add($item->get_content()->get_title(), CalendarUrlBuilder::display_event($category->get_id(), $category->get_rewrited_name(), $item->get_id(), $item->get_content()->get_rewrited_title()));

			$breadcrumb->add($this->lang['calendar.event.edit'], CalendarUrlBuilder::edit_event($item->get_id()));
			$graphical_environment->get_seo_meta_data()->set_description($this->lang['calendar.event.edit']);
			$graphical_environment->get_seo_meta_data()->set_canonical_url(CalendarUrlBuilder::edit_event($item->get_id()));
		}

		return $response;
	}
}
?>
