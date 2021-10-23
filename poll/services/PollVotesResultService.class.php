<?php
/**
 * @copyright   &copy; 2005-2021 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      xela <xela@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 01 09
 * @since       PHPBoost 6.0 - 2021 01 08
*/

class PollVotesResultService
{
	public static function display(Item $item)
	{
		if (PollAuthorizationsService::check_authorizations()->display_votes_result())
		{
		  $tpl = new FileTemplate('poll/PollVotesResult.tpl');
		  $tpl->add_lang(LangLoader::get('common', 'poll'));
		  
			$total_votes_number = $item->get_votes_number();
			$tpl->put_all(array(
				'C_HAS_VOTES' => $item->has_votes(),
				'TOTAL_VOTES_NUMBER' => $total_votes_number
				));

			foreach ($item->get_votes() as $answer => $votes_number)
			{
				$percentage = round($votes_number * 100 / $total_votes_number, 0);
				$tpl->assign_block_vars('votes_result', array(
					'ANSWER' 		  => $answer,
					'VOTES_NUMBER' 	  => $votes_number,
					'PERCENTAGE' 	  => $percentage
				));
			}
			
			return $tpl;
		}
	}
}
?>
