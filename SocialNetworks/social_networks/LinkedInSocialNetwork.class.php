<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 09 20
 * @since       PHPBoost 5.1 - 2018 04 10
*/

class LinkedInSocialNetwork extends AbstractSocialNetwork
{
	const SOCIAL_NETWORK_ID = 'linkedin';

	public function get_name()
	{
		return 'LinkedIn';
	}

	public function get_icon_name()
	{
		return self::SOCIAL_NETWORK_ID . '-in';
	}

	public function get_content_sharing_url()
	{
		return 'https://www.linkedin.com/shareArticle?mini=true&url=' . (rawurlencode(HOST . REWRITED_SCRIPT)) . (defined('TITLE') ? '&title=' . rawurlencode(TITLE) : '') . '&source='. HOST;
	}

	public function get_external_authentication()
	{
		return new LinkedInExternalAuthentication();
	}

	public function get_identifiers_creation_url()
	{
		return 'https://www.linkedin.com/secure/developer';
	}
}
?>
