<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 02 28
 * @since       PHPBoost 5.1 - 2018 04 10
*/

class GoogleSocialNetwork extends AbstractSocialNetwork
{
	const SOCIAL_NETWORK_ID = 'google';

	public function get_name()
	{
		return 'Google';
	}

	public function get_external_authentication()
	{
		return new GoogleExternalAuthentication();
	}

	public function get_identifiers_creation_url()
	{
		return 'https://console.developers.google.com/project';
	}
}
?>
