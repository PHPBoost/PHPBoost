<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2018 09 20
 * @since       PHPBoost 5.1 - 2018 04 10
*/

class FacebookSocialNetwork extends AbstractSocialNetwork
{
	const SOCIAL_NETWORK_ID = 'facebook';

	public function get_name()
	{
		return 'Facebook';
	}

	public function get_icon_name()
	{
		return self::SOCIAL_NETWORK_ID . '-f';
	}

	public function get_content_sharing_url()
	{
		return 'https://www.facebook.com/share.php?u=' . (rawurlencode(HOST . REWRITED_SCRIPT));
	}

	public function get_external_authentication()
	{
		return new FacebookExternalAuthentication();
	}

	public function get_identifiers_creation_url()
	{
		return 'https://developers.facebook.com';
	}
}
?>
