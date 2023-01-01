<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2018 04 19
 * @since       PHPBoost 5.1 - 2018 01 08
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class FacebookExternalAuthentication extends AbstractSocialNetworkExternalAuthentication
{
	public function get_authentication_id()
	{
		return FacebookSocialNetwork::SOCIAL_NETWORK_ID;
	}

	protected function get_social_network()
	{
		return new FacebookSocialNetwork();
	}

	public function get_authentication()
	{
		return new FacebookAuthenticationMethod();
	}
}
?>
