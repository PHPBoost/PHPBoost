<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2017 04 13
 * @since       PHPBoost 3.0 - 2011 10 08
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class PollModuleMiniMenu extends ModuleMiniMenu
{
	public function get_default_block()
	{
		return self::BLOCK_POSITION__RIGHT;
	}

	public function admin_display()
	{
		return '';
	}

	public function get_menu_id()
	{
		return 'module-mini-poll';
	}

	public function get_menu_title()
	{
		global $LANG;
		load_module_lang('poll');
		return $LANG['mini_poll'];
	}

	public function is_displayed()
	{
		$polls = PollMiniMenuCache::load()->get_polls();
		return !empty($polls) && !Url::is_current_url('/poll/') && PollAuthorizationsService::check_authorizations()->read();
	}

	public function get_menu_content()
	{
		global $LANG;
		$poll_config = PollConfig::load();
		$config_cookie_name = $poll_config->get_cookie_name();

		$polls = PollMiniMenuCache::load()->get_polls();

		//Chargement de la langue du module.
		load_module_lang('poll');
		$rand = array_rand($polls);
		$poll_mini = $polls[$rand]; //Sondage aléatoire.

		$tpl = new FileTemplate('poll/poll_mini.tpl');
		MenuService::assign_positions_conditions($tpl, $this->get_block());

		#####################Résultats######################
		//Si le cookie existe, on redirige vers les resulats, sinon on prend en compte le vote (vérification par ip plus tard).
		$array_cookie = array();
		if (AppContext::get_request()->has_cookieparameter($config_cookie_name))
		{
			$array_cookie = explode('/', AppContext::get_request()->get_cookie($config_cookie_name));
		}
		if (in_array($poll_mini['id'], $array_cookie))
		{
			$tpl->put_all(array(
				'L_VOTE' => ($poll_mini['total'] > 1) ? $LANG['poll_vote_s'] : $LANG['poll_vote']
			));

			$tpl->assign_block_vars('result', array(
				'QUESTION' => $poll_mini['question'],
				'VOTES' => $poll_mini['total'],
			));

			foreach ($poll_mini['votes'] as $answer => $width)
			{
				$tpl->assign_block_vars('result.answers', array(
					'ANSWERS' => $answer,
					'WIDTH' => NumberHelper::round($width, 0),
					'PERCENT' => $width
				));
			}
		}
		else
		{
			#####################Questions######################
			$tpl->put_all(array(
				'L_MINI_POLL' => $LANG['mini_poll'],
				'L_VOTE' => $LANG['poll_vote'],
				'L_POLL_RESULT' => $LANG['poll_result'],
				'U_POLL_RESULT' => url('.php?id=' . $poll_mini['id'] . '&amp;r=1', '-' . $poll_mini['id'] . '-1.php')
			));

			$tpl->assign_block_vars('question', array(
				'ID' => url('.php?id=' . $poll_mini['id'], '-' . $poll_mini['id'] . '.php'),
				'QUESTION' => $poll_mini['question']
			));

			$z = 0;
			if ($poll_mini['type'] == '1')
			{

				if (is_array($poll_mini['votes']))
				{
					// FIXME should always be an array, needs to patch cache generation
					foreach ($poll_mini['votes'] as $answer => $width)
					{
						$tpl->assign_block_vars('question.radio', array(
							'NAME' => $z,
							'ANSWERS' => $answer
						));
						$z++;
					}
				}
			}
			elseif ($poll_mini['type'] == '0')
			{
				foreach ($poll_mini['votes'] as $answer => $width)
				{
					$tpl->assign_block_vars('question.checkbox', array(
						'NAME' => $z,
						'ANSWERS' => $answer
					));
					$z++;
				}
			}
		}
		return $tpl->render();
	}
}
?>
