<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2019 12 19
 * @since       PHPBoost 5.1 - 2018 09 19
*/

class ViberSocialNetwork extends AbstractSocialNetwork
{
	const SOCIAL_NETWORK_ID = 'viber';

	public function get_name()
	{
		return 'Viber';
	}

	public function get_mobile_content_sharing_url()
	{
		return 'viber://forward?text=' . (rawurlencode((defined('TITLE') ? TITLE . ' ' : '') . HOST . REWRITED_SCRIPT));
	}

	public function is_mobile_only()
	{
		return true;
	}
}
?>
