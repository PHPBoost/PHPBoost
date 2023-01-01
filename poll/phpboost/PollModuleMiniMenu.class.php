<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      xela <xela@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 15
 * @since       PHPBoost 6.0 - 2020 05 14
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class PollModuleMiniMenu extends ModuleMiniMenu
{
	const MODULE_ID = 'poll';

	public $lang;
	public $item;
	public $view;

	private $vote_form;
	private $vote = array();

	public function get_default_block()
	{
		return self::BLOCK_POSITION__LEFT;
	}

	public function get_menu_id()
	{
		return 'poll-module-mini';
	}

	public function get_menu_title()
	{
		return ModulesManager::get_module(self::MODULE_ID)->get_configuration()->get_name();
	}

	public function default_is_enabled()
	{
		return true;
	}

	public function is_displayed()
	{
		return !Url::is_current_url(self::MODULE_ID) && ItemsAuthorizationsService::check_authorizations(self::MODULE_ID)->read();
	}

	// $msg_return = array(key of var lang => const MessageHelper::[SUCCESS, WARNING etc])
	public function get_menu_content($item_id = 0, array $msg_return = array())
	{
		$this->view = new FileTemplate('poll/PollModuleMiniMenu.tpl');
		MenuService::assign_positions_conditions($this->view, $this->get_block());
		$this->lang = LangLoader::get_all_langs(self::MODULE_ID);
		$this->view->add_lang($this->lang);
		$cache = PollMiniMenuCache::load();

		$items_for_polls_displaying = $cache->get_polls_displaying();
		$items_for_polls_not_displaying = $cache->get_polls_not_displaying(); //evolution
		$previous_item_id = '';
		$next_item_id = '';
		$vote_form_and_result_displaying = true;

		if (AppContext::get_current_user()->is_guest())
		{
			$this->view->put_all(array(
				'C_DISPLAYING_POLLS_MAP' => !empty($items_for_polls_displaying),
				'C_MULTIPLE_POLL_ITEMS'  => $cache->get_number_polls_displaying() > 1
			));

			if (!empty($items_for_polls_displaying))
			{
				$polls_displayed_number = 0;
				$max_nb_poll_links = min($cache->get_number_polls_displaying(), 10);
				foreach ($items_for_polls_displaying as $id => $poll_properties)
				{
					if ($polls_displayed_number < $max_nb_poll_links)
					{
						$this->item = new PollItem();
						$this->item->set_properties($poll_properties);
						$url = ItemsUrlBuilder::display($this->item->get_id_category(), $this->item->get_category()->get_rewrited_name(), $this->item->get_id(), $this->item->get_rewrited_title(), $module_id = self::MODULE_ID);

						$this->view->assign_block_vars('polls_map', array('TITLE' => $this->item->get_title(), 'U_ITEM' => $url->rel()));
					}
					$polls_displayed_number++;
				}
			}
		}
		else
		{
			if (empty($item_id))
			{
				if (!empty($items_for_polls_displaying))
				{
					$random_item_id = $this->get_random_item_id(array_keys($items_for_polls_displaying));
					$this->item = new PollItem();
					$this->item->set_properties($items_for_polls_displaying[$random_item_id]);

					$previous_item_id = $this->get_previous_item_id($random_item_id, array_keys($items_for_polls_displaying));
					$next_item_id = $this->get_next_item_id($random_item_id, array_keys($items_for_polls_displaying));
				}
				else
				{
					$vote_form_and_result_displaying = false;
				}
			}
			else
			{
				if (isset($items_for_polls_displaying[$item_id]))
				{
					$this->item = new PollItem();
					$this->item->set_properties($items_for_polls_displaying[$item_id]);
				}
				else
					$this->item = ItemsService::get_items_manager(self::MODULE_ID)->get_item($item_id);

				$previous_item_id = $this->get_previous_item_id($item_id, array_keys($items_for_polls_displaying));
				$next_item_id = $this->get_next_item_id($item_id, array_keys($items_for_polls_displaying));
			}

			if(!empty($msg_return))
			{
				$lang_var = key($msg_return);
				$type = $msg_return[$lang_var];
				$this->view->put('POLL_MINI_MSG', MessageHelper::display($this->lang[$lang_var], $type, 7));
			}

			if ($vote_form_and_result_displaying)
			{
				$this->view->put_all(array(
					'C_VOTE_FORM_AND_RESULTS' => true,
					'C_ENABLED_COUNTDOWN' 	  => $this->item->is_published() && $this->item->end_date_enabled() && $this->item->get_countdown_display() > 0,
					'COUNTDOWN'				  => PollCountdownService::display($this->item),
					'VOTE_FORM'               => $this->build_vote_form($this->item, $previous_item_id, $next_item_id)->display(),
					'VOTES_RESULT'            => PollVotesResultService::display($this->item)
				));
			}
		}

		return $this->view->render();
	}

	public function get_previous_item_id(int $current_item_id, array $array)
	{
		$current_item_id_key = $this->get_item_id_key($current_item_id, $array);

		if (array_key_exists($current_item_id_key-1, $array))
		return $array[$current_item_id_key-1];
	}

	public function get_next_item_id(int $current_item_id, array $array)
	{
		$current_item_id_key = $this->get_item_id_key($current_item_id, $array);

		if (array_key_exists($current_item_id_key+1, $array))
		return $array[$current_item_id_key+1];
	}

	public function get_item_id_key(int $item_id, array $array)
	{
		return array_search($item_id, $array);
	}

	public function get_random_item_id(array $poll_ids)
	{
		return $poll_ids[array_rand($poll_ids)];
	}

	public function build_vote_form(Item $item, $previous_item_id = '', $next_item_id = '')
	{
		$vote_form = new HTMLForm(self::MODULE_ID . '_vote_form', '', false);
		$vote_form->set_css_class('cell-form form-mini');
		$fieldset = new FormFieldsetHTML(self::MODULE_ID);
		$vote_form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldFree('question', '', FormatingHelper::second_parse($item->get_question()),
			array('class' => 'no-label align-center text-strong')
		));

		$answers_list = array();
		$i = 1;
		foreach ($item->get_answers_list() as $answer)
		{
			switch ($item->get_answers_type())
			{
				case 1:
					$answers_list['single'][] = new FormFieldRadioChoiceOption($answer, $i);
					break;
				case 2;
					$answers_list['multiple'][] = new FormFieldMultipleCheckboxOption($i, $answer);
					break;
			}
			$i++;
		}

		if ($item->get_answers_type() == 1)
		{
			$fieldset->add_field(new FormFieldRadioChoice('single_vote', $this->lang['poll.vote.single.choice'], array(),
			$answers_list['single'],
			array('class' => 'full-label')
			));
		}
		elseif ($item->get_answers_type() == 2)
		{
			$fieldset->add_field(new FormFieldMultipleCheckbox('multiple_vote', $this->lang['poll.vote.multiple.choice'], array(),
			$answers_list['multiple'],
			array('class' => 'full-label')
			));
		}

		$fieldset->add_field(new FormFieldHidden('item_id', $item->get_id()));

		if (!empty($previous_item_id))
			$vote_form->add_button(new FormButtonButtonCssImg($previous_item_id, 'poll_mini_previous_nav(); return false;', 'previous', 'fas fa-arrow-alt-circle-left', '', 'previous', '', 'previous_id'));

		$submit_button = new FormButtonSubmit($this->lang['poll.vote.submit'], 'submit', 'poll_submit(); return false;');
		$vote_form->add_button($submit_button);

		if (!empty($next_item_id))
			$vote_form->add_button(new FormButtonButtonCssImg($next_item_id, 'poll_mini_next_nav(); return false;', 'next', 'fas fa-arrow-alt-circle-right', '', 'next', '', 'next_id'));

		return $vote_form;
	}
}
?>
