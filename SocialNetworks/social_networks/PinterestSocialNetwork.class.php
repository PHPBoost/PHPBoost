<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2018 09 20
 * @since       PHPBoost 5.1 - 2018 04 10
*/

class PinterestSocialNetwork extends AbstractSocialNetwork
{
	const SOCIAL_NETWORK_ID = 'pinterest';

	public function get_name()
	{
		return 'Pinterest';
	}

	public function get_icon_name()
	{
		return self::SOCIAL_NETWORK_ID . '-p';
	}

	public function get_content_sharing_url()
	{
		return 'http://pinterest.com/pin/create/link/?url=' . (rawurlencode(HOST . REWRITED_SCRIPT));
	}
}
?>
