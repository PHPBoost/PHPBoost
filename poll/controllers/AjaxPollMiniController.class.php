<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      xela <xela@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 06 15
 * @since       PHPBoost 6.0 - 2020 05 14
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class AjaxPollMiniController extends AbstractController
{
	const MODULE_ID = 'poll';
	const FORM_HTML_ID = self::MODULE_ID . '_vote_form';

	protected $request;
	protected $item;
	protected $answers_type;
	protected $answers_list;
	protected $vote = array();

	public function execute(HTTPRequestCustom $request)
	{
		$this->init($request);
		$data = $this->request->get_string('sendFormData', '');
		$previous_item_id = $this->request->get_int('sendNavData_previous', '');
		$next_item_id = $this->request->get_int('sendNavData_next', '');

		$json_response = array();

		if (!empty($previous_item_id) || !empty($next_item_id))
		{
			$json_response = array(
				'validated' => 0,
				'message'   => '',
				'html'      => $this->set_view($this->get_pollminimenu(), !empty($previous_item_id) ? $previous_item_id : $next_item_id)
			);
		}
		else
		{
			$this->item = ItemsService::get_items_manager('poll')->get_item($this->get_sended_item_id());
			$this->answers_type = $this->item->get_answers_type();
			$this->answers_list = $this->item->get_answers_list();

			if ($this->item->user_is_empowered_to_vote())
			{
				if ($this->form_has_been_submited() && $this->form_is_validated())
				{
					$this->save_vote();

					$json_response = array(
						'validated' => 1,
						'message'   => LangLoader::get_message('poll.vote.saved', 'common', 'poll'),
						'html'      => $this->set_view($this->get_pollminimenu(), '', array('poll.vote.saved' => MessageHelper::SUCCESS))
					);

				}
				elseif ($this->form_has_been_submited() && !$this->form_is_validated())
				{
					$json_response = array(
						'validated' => 0,
						'message'   => LangLoader::get_message('poll.mini.have.to.choose', 'common', 'poll'),
						'html'      => $this->set_view($this->get_pollminimenu(), $this->get_sended_item_id(), array('poll.mini.have.to.choose' => MessageHelper::NOTICE))
					);
				}
				elseif (!$this->form_has_been_submited() && !$this->form_is_validated())
				{
					$json_response = array(
						'validated' => 0,
						'message'   => LangLoader::get_message('poll.mini.form.error', 'common', 'poll'),
						'html' 	    => $this->set_view($this->get_pollminimenu(), $this->get_sended_item_id(), array('poll.mini.form.error' => MessageHelper::WARNING))
					);
				}
			}
		}

		return new JSONResponse($json_response);
	}

	protected function init(HTTPRequestCustom $request)
	{
		$this->request = $request;
	}

	private function save_vote()
	{
		if ($this->answers_type == 1)
		{
			$this->vote[] = $this->get_sended_single_vote();
		}
		elseif ($this->answers_type == 2)
		{
			$this->vote = $this->get_sended_multiple_vote();
		}

		$items_manager = ItemsService::get_items_manager('poll');
		$items_manager->update_votes($this->retrieve_vote(), $this->item->get_votes_number(), $this->item->get_id());
		$items_manager->insert_voter($this->item->get_id());
		$items_manager->set_cookie($this->item->get_id());
		PollMiniMenuCache::invalidate();
	}

	//Si pas encore de vote, retourne un tableau avec pour clés les réponses et pour valeurs 0
	private function init_vote()
	{
		if (!$this->item->has_votes())
		{
			return array_fill_keys($this->answers_list, 0);
		}
		else
		{
			return $this->item->get_votes();
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

	protected function get_sended_data()
	{
		parse_str($this->request->get_string('sendFormData', ''), $parse);
		return $parse;
	}

	protected function get_sended_item_id()
	{
		//TODO : Verif de lid à faire
		return $this->get_sended_data()[self::FORM_HTML_ID . '_item_id'];
	}

	protected function form_has_been_submited()
	{
		return $this->get_sended_data()[self::FORM_HTML_ID . '_submit'] == 'on';
	}

	protected function form_is_validated()
	{
		if ($this->answers_type == 1)
		{
			return !empty($this->get_sended_data()[self::FORM_HTML_ID . '_single_vote']);
		}
		elseif ($this->answers_type == 2)
		{
			$count_answers_list = count($this->answers_list);
			$multiple_int_vote = array();

			for ($i=1; $i<=$count_answers_list; $i++)
			{
				if ( isset($this->get_sended_data()[self::FORM_HTML_ID . '_multiple_vote_' . $i]) && $this->get_sended_data()[self::FORM_HTML_ID . '_multiple_vote_' . $i] == 'on' )
				{
					$multiple_int_vote[] = $i;
				}
			}

			return !empty($multiple_int_vote);
		}
		else
		{
			return false;
		}
	}

	protected function get_sended_single_vote()
	{
		$single_vote = $this->get_sended_data()[self::FORM_HTML_ID . '_single_vote'];
		return $this->answers_list[$single_vote-1];
	}

	protected function get_sended_multiple_vote()
	{
		$count_answers_list = count($this->answers_list);

		$multiple_int_vote = array();
		for ($i=1; $i<=$count_answers_list; $i++)
		{
			if ( isset($this->get_sended_data()[self::FORM_HTML_ID . '_multiple_vote_' . $i]) && $this->get_sended_data()[self::FORM_HTML_ID . '_multiple_vote_' . $i] == 'on' )
			{
				$multiple_int_vote[] = $i;
			}
		}

		$multiple_vote = array();
		foreach ($multiple_int_vote as $int_vote)
		{
			$multiple_vote[] = $this->answers_list[$int_vote-1];
		}

		return $multiple_vote;
	}

	protected function set_view(ModuleMiniMenu $moduleminimenu, $item_id, $msg_return = array())
	{
		return $moduleminimenu->get_menu_content((int)$item_id, $msg_return);
	}

	protected function get_pollminimenu()
	{
		return new PollModuleMiniMenu();
	}
}
?>
