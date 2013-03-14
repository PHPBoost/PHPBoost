<?php
/*##################################################
 *                              CalendarFormController.class.php
 *                            -------------------
 *   begin                : February 25, 2013
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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
	
	private $event;
	
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
			$this->lang = LangLoader::get('calendar_common', 'calendar');
	}
	
	private function build_form()
	{
		$form = new HTMLForm('event');
		
		$fieldset = new FormFieldsetHTML('event', $this->lang['calendar.titles.event']);
		$form->add_fieldset($fieldset);
		
		$fieldset->add_field(new FormFieldTextEditor('title', $this->lang['calendar.labels.title'], $this->get_event()->get_title(), array('required' => true)));

		$search_category_children_options = new SearchCategoryChildrensOptions();
		$search_category_children_options->add_authorizations_bits(Category::READ_AUTHORIZATIONS);
		$search_category_children_options->add_authorizations_bits(Category::CONTRIBUTION_AUTHORIZATIONS);
		$fieldset->add_field(CalendarService::get_categories_manager()->get_select_categories_form_field('id_cat', $this->lang['calendar.labels.category'], $this->get_event()->get_id_cat(), $search_category_children_options));
		
		$fieldset->add_field(new FormFieldRichTextEditor('contents', $this->lang['calendar.labels.contents'], $this->get_event()->get_contents(), array('rows' => 15, 'required' => true)));
		
		$fieldset->add_field(new FormFieldTextEditor('location', $this->lang['calendar.labels.location'], $this->get_event()->get_location()));
		
		$fieldset->add_field(new FormFieldDateTime('start_date', $this->lang['calendar.labels.start_date'], $this->get_event()->get_start_date(), array('required' => true)));
		
		$fieldset->add_field(new FormFieldDateTime('end_date', $this->lang['calendar.labels.end_date'], $this->get_event()->get_end_date(), array('required' => true)));
		
		$fieldset->add_field(new FormFieldSimpleSelectChoice('repeat_type', $this->lang['calendar.labels.repeat_type'], $this->get_event()->get_repeat_type(),
			array(
				new FormFieldSelectChoiceOption($this->lang['calendar.labels.repeat.never'], CalendarEvent::NEVER),
				new FormFieldSelectChoiceOption($this->lang['calendar.labels.repeat.daily'], CalendarEvent::DAILY),
				new FormFieldSelectChoiceOption($this->lang['calendar.labels.repeat.daily_not_weekend'], CalendarEvent::DAILY_NOT_WEEKEND),
				new FormFieldSelectChoiceOption($this->lang['calendar.labels.repeat.weekly'], CalendarEvent::WEEKLY),
				new FormFieldSelectChoiceOption($this->lang['calendar.labels.repeat.monthly'], CalendarEvent::MONTHLY),
				new FormFieldSelectChoiceOption($this->lang['calendar.labels.repeat.yearly'], CalendarEvent::YEARLY),
			),
			array('events' => array('change' => '
			if (HTMLForms.getField("repeat_type").getValue() != "' . CalendarEvent::NEVER . '") {
				HTMLForms.getField("repeat_number").enable();
			} else {
				HTMLForms.getField("repeat_number").disable();
			}'))
		));
		
		$fieldset->add_field(new FormFieldTextEditor('repeat_number', $this->lang['calendar.labels.repeat_number'], $this->get_event()->get_repeat_number(), array(
			'class' => 'text', 'maxlength' => 3, 'size' => 3, 'required' => false, 'hidden' => !$this->get_event()->is_repeatable()),
			array(new FormFieldConstraintRegex('`^[0-9]+$`i'))
		));
		
		$fieldset->add_field(new FormFieldCheckbox('registration_authorized', $this->lang['calendar.labels.registration_authorized'], $this->get_event()->get_registration_authorized(),array(
			'events' => array('click' => '
			if (HTMLForms.getField("registration_authorized").getValue()) {
				HTMLForms.getField("max_registred_members").enable();
			} else {
				HTMLForms.getField("max_registred_members").disable();
			}'
		))));
		
		$fieldset->add_field(new FormFieldTextEditor('max_registred_members', $this->lang['calendar.labels.max_registred_members'], $this->get_event()->get_max_registred_members(), array(
			'class' => 'text', 'description' => $this->lang['calendar.labels.max_registred_members.explain'], 'maxlength' => 3, 'size' => 3, 'required' => false, 'hidden' => !$this->get_event()->is_registration_authorized()),
			array(new FormFieldConstraintRegex('`^[0-9]+$`i'))
		));

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
			$fieldset = new FormFieldsetHTML('contribution', $this->lang['calendar.labels.contribution']);
			$fieldset->set_description(MessageHelper::display($this->lang['calendar.labels.contribution.explain'], MessageHelper::WARNING)->render());
			$form->add_fieldset($fieldset);
			
			$fieldset->add_field(new FormFieldRichTextEditor('contribution_description', $this->lang['calendar.labels.contribution.description'], '', array('description' => $this->lang['calendar.labels.contribution.description.explain'])));
		}
	}
	
	private function is_contributor_member()
	{
		return ($this->get_event()->get_id() === null && !CalendarAuthorizationsService::check_authorizations()->write() && CalendarAuthorizationsService::check_authorizations()->contribution());
	}
	
	private function get_event()
	{
		if ($this->event === null)
		{
			$id = AppContext::get_request()->get_getint('id', 0);
			if (!empty($id))
			{
				try {
					$this->event = CalendarService::get_event('WHERE id=:id', array('id' => $id));
				} catch (RowNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->event = new CalendarEvent();
				$this->event->init_default_properties();
			}
		}
		return $this->event;
	}
	
	private function check_authorizations()
	{
		$event = $this->get_event();
		
		if ($event->get_id() === null)
		{
			if (!CalendarAuthorizationsService::check_authorizations()->write() && !CalendarAuthorizationsService::check_authorizations()->contribution())
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
		else
		{
			if (!(CalendarAuthorizationsService::check_authorizations($event->get_id_cat())->moderation() || (CalendarAuthorizationsService::check_authorizations($event->get_id_cat())->write() && $event->get_user_id() == AppContext::get_current_user()->get_id())))
			{
				$error_controller = PHPBoostErrors::user_not_authorized();
				DispatchManager::redirect($error_controller);
			}
		}
	}
	
	private function save()
	{
		$event = $this->get_event();
		
		$event->set_title($this->form->get_value('title'));
		$event->set_id_cat($this->form->get_value('id_cat')->get_raw_value());
		$event->set_contents($this->form->get_value('contents'));
		
		$event->set_location($this->form->get_value('location'));
		
		$event->set_start_date($this->form->get_value('start_date'));
		$event->set_end_date($this->form->get_value('end_date'));
		
		$event->set_repeat_type($this->form->get_value('repeat_type')->get_raw_value());
		
		if ($event->is_repeatable())
		{
			$event->set_repeat_number($this->form->get_value('repeat_number'));
		}
		
		$event->set_registration_authorized($this->form->get_value('registration_authorized') ? true : 0);
		
		if ($event->is_registration_authorized())
		{
			$event->set_max_registred_members($this->form->get_value('max_registred_members'));
		}
		
		if ($event->get_id() === null)
		{
			$event->set_author_id(AppContext::get_current_user()->get_id());
			CalendarService::add($event);
		}
		else
		{
			CalendarService::update($event);
		}
		
		$this->contribution_actions($event);
		
		Feed::clear_cache('calendar');
		
		$array_time = explode('-', $event->get_start_date()->to_date());
		
		AppContext::get_response()->redirect(CalendarUrlBuilder::home($array_time[0] . '/'. $array_time[1])->absolute());
	}
	
	private function contribution_actions(CalendarEvent $event)
	{
		if ($event->get_id() === null)
		{
			if ($this->is_contributor_member())
			{
				$contribution = new Contribution();
				$contribution->set_id_in_module($event->get_id());
				$contribution->set_description(stripslashes($event->get_contents()));
				$contribution->set_entitled('[' . $this->lang['calendar.titles.event'] . '] ' . $event->get_title());
				$contribution->set_fixing_url(CalendarUrlBuilder::edit_event($event->get_id()));
				$contribution->set_poster_id(AppContext::get_current_user()->get_id());
				$contribution->set_module('calendar');
				$contribution->set_auth(
					Authorizations::capture_and_shift_bit_auth(
						CalendarService::get_categories_manager()->get_heritated_authorizations($event->get_id_cat(), Category::MODERATION_AUTHORIZATIONS, Authorizations::AUTH_CHILD_PRIORITY),
						Category::MODERATION_AUTHORIZATIONS, Contribution::CONTRIBUTION_AUTH_BIT
					)
				);
				ContributionService::save_contribution($contribution);

				AppContext::get_response()->redirect(UserUrlBuilder::contribution_success()->absolute());
			}
		}
		else
		{
			$corresponding_contributions = ContributionService::find_by_criteria('calendar', $event->get_id());
			if (count($corresponding_contributions) > 0)
			{
				$event_contribution = $corresponding_contributions[0];
				$event_contribution->set_status(CalendarEvent::EVENT_STATUS_PROCESSED);

				ContributionService::save_contribution($event_contribution);
			}
		}
	}
	
	private function generate_response(View $tpl)
	{
		$event = $this->get_event();
		$category = CalendarService::get_categories_manager()->get_categories_cache()->get_category($event->get_id_cat());
		
		$response = new CalendarDisplayResponse();
		$response->add_breadcrumb_link($this->lang['calendar.module_title'], CalendarUrlBuilder::home());
		
		if ($event->get_id() === null)
		{
			$response->add_breadcrumb_link($this->lang['calendar.titles.add_event'], CalendarUrlBuilder::add_event());
			$response->set_page_title($this->lang['calendar.titles.add_event']);
		}
		else
		{
			$categories = array_reverse(CalendarService::get_categories_manager()->get_parents($event->get_id_cat(), true));
			foreach ($categories as $id => $category)
			{
				if ($id != Category::ROOT_CATEGORY)
					$response->add_breadcrumb_link($category->get_name(), CalendarUrlBuilder::display_category($id, $category->get_rewrited_name()));
			}
			$category = $categories[$event->get_id()];
			$response->add_breadcrumb_link($event->get_title(), CalendarUrlBuilder::display_event($category->get_rewrited_name(), $event->get_id(), $event->get_title()));
			
			$response->add_breadcrumb_link($this->lang['calendar.titles.edit_event'], CalendarUrlBuilder::edit_event($event->get_id()));
			$response->set_page_title($this->lang['calendar.titles.edit_event']);
		}
		
		return $response->display($tpl);
	}
}
?>