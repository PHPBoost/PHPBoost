<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      xela <xela@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 01
 * @since       PHPBoost 6.0 - 2020 05 14
*/

class PollAuthorizationsService extends CategoriesAuthorizationsService
{
	public function vote()
	{
		return $this->is_authorized(PollConfig::VOTE_AUTHORIZATIONS);
	}

	public function display_votes_result()
	{
		return $this->is_authorized(PollConfig::DISPLAY_VOTES_RESULT_AUTHORIZATIONS);
	}
}
?>