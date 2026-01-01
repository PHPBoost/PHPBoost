<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2025 02 20
 * @since       PHPBoost 5.1 - 2018 04 10
*/

class TwitterSocialNetwork extends AbstractSocialNetwork
{
	const SOCIAL_NETWORK_ID = 'x-twitter';

	public function get_name()
	{
		return 'X (twitter)';
	}

	public function get_icon_name()
	{
		return self::SOCIAL_NETWORK_ID;
	}

	public function get_content_sharing_url()
	{
		return 'https://x.com/intent/post?url=' . (rawurlencode(HOST . REWRITED_SCRIPT)) . (defined('TITLE') ? '&text=' . rawurlencode(TITLE) : '');
	}

	public function get_external_authentication()
	{
		return new TwitterExternalAuthentication();
	}

	public function get_identifiers_creation_url()
	{
		return 'https://developer.x.com/en/apps';
	}
}
?>
