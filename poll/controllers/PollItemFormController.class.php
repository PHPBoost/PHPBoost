<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      xela <xela@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 06
 * @since       PHPBoost 6.0 - 2020 05 14
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class PollItemFormController extends DefaultItemFormController
{
	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->check_authorizations();
		$this->build_form();
		$this->build_countdown_field();

		if ($this->get_item()->has_votes())
		{
			$question_saved = $this->get_item()->get_question();
			$answers_type_saved = $this->get_item()->get_answers_type();
			$answers_saved = $this->get_item()->get_answers();
		}

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->save();

			if ($this->get_item()->has_votes())
			{
				$this->save_force_changes_poll($question_saved, $answers_type_saved, $answers_saved);
			}

			$this->redirect();
		}

		$this->view->put('CONTENT', $this->form->display());

		return $this->generate_response();
	}

	protected function build_pre_content_fields(FormFieldset $fieldset)
	{
		if ($this->get_item()->has_votes())
			$fieldset->set_description(MessageHelper::display($this->lang['poll.form.force.changes.poll'], MessageHelper::WARNING)->render());

		parent::build_pre_content_fields($fieldset);

		$fieldset->add_field(new FormFieldCheckbox('close_poll', $this->lang['poll.form.close.poll'], $this->is_new_item ? FormFieldCheckbox::UNCHECKED : $this->get_item()->get_close_poll(), array('hidden' => $this->is_new_item ? true : false)));
		$fieldset->add_field(new FormFieldCheckbox('display_poll_in_mini_module', $this->lang['poll.form.display.poll.in.mini'], $this->is_new_item ? FormFieldCheckbox::CHECKED : $this->get_item()->is_in_mini_module_map()));
	}

	protected function build_countdown_field()
	{
		$publication_fieldset = $this->form->get_fieldset_by_id('publication');
		$publication_fieldset->add_field(new FormFieldRadioChoice(
		'countdown_display',
		$this->lang['poll.form.countdown.parameters'],
		(string) $this->get_item()->get_countdown_display(),
		array(
			new FormFieldRadioChoiceOption($this->lang['poll.form.countdown.without.seconds'], $this->get_item()::COUNTDOWN_DISPLAY_WITHOUT_S),
			new FormFieldRadioChoiceOption($this->lang['poll.form.countdown.with.seconds'], $this->get_item()::COUNTDOWN_DISPLAY_WITH_S),
			new FormFieldRadioChoiceOption($this->lang['poll.form.countdown.not.displaying'], $this->get_item()::COUNTDOWN_NO_DISPLAY)
		),
		array(
			'hidden' 		=> !($this->get_item()->get_publishing_state() == Item::DEFERRED_PUBLICATION && $this->get_item()->get_publishing_end_date()),
			'description' 	=> $this->lang['poll.form.countdown.parameters.clue']
		)
		));

		$publication_publishing_state_field = $this->form->get_field_by_id('publishing_state');
		$publication_publishing_state_field_handler =
		'if (HTMLForms.getField("publishing_state").getValue() == 2)
		{
			jQuery("#' . self::$module_id . '_form_publishing_start_date_field").show();
			HTMLForms.getField("end_date_enabled").enable();
			if (HTMLForms.getField("end_date_enabled").getValue())
			{
				HTMLForms.getField("publishing_end_date").enable();
				HTMLForms.getField("countdown_display").enable();
			}
		}
		else
		{
			jQuery("#' . self::$module_id . '_form_publishing_start_date_field").hide();
			HTMLForms.getField("end_date_enabled").disable();
			HTMLForms.getField("publishing_end_date").disable();
			HTMLForms.getField("countdown_display").disable();
		}';
		$publication_publishing_state_field->add_event('change', $publication_publishing_state_field_handler);

		$publication_end_date_field = $this->form->get_field_by_id('end_date_enabled');
		$publication_end_date_field_handler =
		'if (HTMLForms.getField("end_date_enabled").getValue())
		{
			HTMLForms.getField("publishing_end_date").enable();
			HTMLForms.getField("countdown_display").enable();
		}
		else
		{
			HTMLForms.getField("publishing_end_date").disable();
			HTMLForms.getField("countdown_display").disable();
		}';
		$publication_end_date_field->add_event('click', $publication_end_date_field_handler);
	}

	protected function save_force_changes_poll($question_saved, $answers_type_saved, $answers_saved)
	{
		if ($this->form->get_value('question') !== $question_saved
			|| $this->form->get_value('answers_type')->get_raw_value() !== $answers_type_saved
			|| $this->form->get_value('answers') !== $answers_saved)
		{
			self::get_items_manager()->update_votes(array(), -1,  $this->get_item()->get_id());
			self::get_items_manager()->delete_voters($this->get_item()->get_id());
		}
	}

	protected function save()
	{
        $this->get_item()->set_close_poll($this->form->get_value('close_poll', FormFieldCheckbox::UNCHECKED));
		$this->get_item()->set_countdown_display($this->form->get_value('countdown_display')->get_raw_value());

		parent::save();

		if ($this->form->get_value('display_poll_in_mini_module') == FormFieldCheckbox::CHECKED)
			$this->get_item()->set_item_in_mini_module_map();
		else
			$this->get_item()->unset_item_in_mini_module_map();
		
		PollMiniMenuCache::invalidate();
	}
}
?>
