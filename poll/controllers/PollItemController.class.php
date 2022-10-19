<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      xela <xela@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 14
 * @since       PHPBoost 6.0 - 2020 05 14
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class PollItemController extends DefaultDisplayItemController
{
	private $vote_form;
	private $vote = array();

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->check_authorizations();
		$this->update_views_number();
		$this->build_view();
		$this->build_vote_form();

		if ($this->get_item()->user_is_empowered_to_vote())
		{
			if ($this->submit_button->has_been_submited() && $this->vote_form->validate())
			{
				$this->save_vote();

				AppContext::get_response()->redirect(ItemsUrlBuilder::display($this->get_item()->get_category()->get_id(), $this->get_item()->get_category()->get_rewrited_name(), $this->get_item()->get_id(), $this->get_item()->get_rewrited_title(), self::$module_id));
			}
		}

		$this->view->put_all(array(
			'C_PUBLISHED' 	      => $this->get_item()->is_published(),
			'C_MORE_OPTIONS'      => true,
			'C_ENABLED_COUNTDOWN' => $this->get_item()->is_published() && $this->get_item()->end_date_enabled() && $this->get_item()->get_countdown_display() > 0,
			'COUNTDOWN'	      => PollCountdownService::display($this->get_item()),
			'VOTE_FORM' 	      => $this->vote_form->display(),
			'VOTES_RESULT'        => PollVotesResultService::display($this->get_item())
		));

		return $this->generate_response();
	}

	private function build_vote_form()
	{
		$vote_form = new HTMLForm(self::$module_id . '_vote_form', '', $this->get_item()->user_has_vote_authorization() && AppContext::get_current_user()->is_guest() && !$this->get_item()->user_has_voted());

		$fieldset = new FormFieldsetHTML(self::$module_id);
		$vote_form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldFree('question', $this->lang['poll.form.question'], FormatingHelper::second_parse($this->get_item()->get_question())));

		$answers_list = array();
		$i = 1;
		foreach ($this->get_item()->get_answers_list() as $answer)
		{
			switch ($this->get_item()->get_answers_type())
			{
				case 1:
					$answers_list['single'][] = new FormFieldRadioChoiceOption($answer, $i, array('disable' => !$this->get_item()->user_is_empowered_to_vote()));
					break;
				case 2;
					$answers_list['multiple'][] = new FormFieldMultipleCheckboxOption($i, $answer, array('disable' => !$this->get_item()->user_is_empowered_to_vote()));
					break;
			}
			$i++;
		}

		if ($this->get_item()->get_answers_type() == 1)
		{
			$fieldset->add_field(new FormFieldRadioChoice('single_vote', $this->lang['poll.vote.single.choice'], array(), $answers_list['single'],
				array('required' => $this->get_item()->user_is_empowered_to_vote())
			));
		}
		elseif ($this->get_item()->get_answers_type() == 2)
		{
			$fieldset->add_field(new FormFieldMultipleCheckbox('multiple_vote', $this->lang['poll.vote.multiple.choice'], array(), $answers_list['multiple'],
				array('required' => $this->get_item()->user_is_empowered_to_vote())
			));
		}

		$fieldset = new FormFieldsetHTML('poll_warnings');
		$vote_form->add_fieldset($fieldset);
		if (!$this->get_item()->is_closed())
		{
			if ($this->get_item()->is_published())
			{
				if ($this->get_item()->user_has_vote_authorization())
				{
					if (!$this->get_item()->user_has_voted())
					{
						$this->submit_button = new FormButtonDefaultSubmit($this->lang['poll.vote.submit']);
						$vote_form->add_button($this->submit_button);
					}
					else
						$fieldset->set_description(MessageHelper::display($this->lang['poll.message.already.voted'], MessageHelper::SUCCESS)->render());
				}
				else
					$fieldset->set_description(MessageHelper::display($this->lang['poll.message.not.allowed'], MessageHelper::WARNING)->render());
			}
		}

		$this->vote_form = $vote_form;
	}

	private function save_vote()
	{
		if ($this->get_item()->get_answers_type() == 1)
			$this->vote[] = $this->vote_form->get_value('single_vote')->get_label();
		elseif ($this->get_item()->get_answers_type() == 2)
		{
			foreach ($this->vote_form->get_value('multiple_vote') as $object)
			{
				$this->vote[] = $object->get_label();
			}
		}

		self::get_items_manager()->update_votes($this->retrieve_vote(), $this->get_item()->get_votes_number(), $this->get_item()->get_id());
		self::get_items_manager()->insert_voter($this->get_item()->get_id());
		self::get_items_manager()->set_cookie($this->get_item()->get_id());
	}

	//Si pas encore de vote, retourne un tableau avec pour clés les réponses et pour valeurs 0
	private function init_vote()
	{
		if (!$this->get_item()->has_votes())
		{
			return array_fill_keys($this->get_item()->get_answers_list(), 0);
		}
		else
		{
			return $this->get_item()->get_votes();
		}
	}

	private function retrieve_vote()
	{
		$result_vote = $this->init_vote();
		foreach ($this->vote as $answer)
		{
			if (isset($result_vote[$answer]))
			{
				$result_vote[$answer]++;
			}
		}
		return $result_vote;
	}

	protected function get_template_to_use()
	{
		return new FileTemplate('poll/PollItemController.tpl');
	}
}
?>
