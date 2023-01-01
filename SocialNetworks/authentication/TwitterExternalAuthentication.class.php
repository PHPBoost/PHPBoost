<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2018 04 19
 * @since       PHPBoost 5.1 - 2018 04 10
*/

class TwitterExternalAuthentication extends AbstractSocialNetworkExternalAuthentication
{
	public function get_authentication_id()
	{
		return TwitterSocialNetwork::SOCIAL_NETWORK_ID;
	}

	protected function get_social_network()
	{
		return new TwitterSocialNetwork();
	}

	public function get_authentication()
	{
		return new TwitterAuthenticationMethod();
	}
}
?>
