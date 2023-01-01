<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2018 09 20
 * @since       PHPBoost 5.1 - 2018 09 19
*/

class SkypeSocialNetwork extends AbstractSocialNetwork
{
	const SOCIAL_NETWORK_ID = 'skype';

	public function get_name()
	{
		return 'Skype';
	}

	public function get_content_sharing_url()
	{
		return 'https://web.skype.com/share?url=' . (rawurlencode(HOST . REWRITED_SCRIPT)) . (defined('TITLE') ? '&text=' . rawurlencode(TITLE) : '');
	}

	public function is_desktop_only()
	{
		return true;
	}
}
?>
