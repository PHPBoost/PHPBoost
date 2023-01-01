<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 05 24
 * @since       PHPBoost 5.1 - 2018 04 16
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

abstract class AbstractSocialNetworkExternalAuthentication implements ExternalAuthentication
{
	/**
	 * {@inheritdoc}
	 */
	abstract public function get_authentication_id();

	/**
	 * {@inheritdoc}
	 */
	public function get_authentication_name()
	{
		return StringVars::replace_vars(LangLoader::get_message('sn.sign.in.label', 'common', 'SocialNetworks'), array('name' => $this->get_social_network()->get_name()));
	}

	/**
	 * @return SocialNetwork class
	 */
	abstract protected function get_social_network();

	/**
	 * {@inheritdoc}
	 */
	public function authentication_actived()
	{
		return SocialNetworksConfig::load()->is_authentication_available($this->get_authentication_id());
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_image_renderer_html()
	{
		$view = new FileTemplate('SocialNetworks/auth_image_render.tpl');
		$view->put_all(array(
			'C_DISPLAY_NAME' => strpos(REWRITED_SCRIPT, 'user') !== false && strpos(REWRITED_SCRIPT, 'login') !== false,
			'ICON_NAME'      => $this->get_social_network()->get_icon_name(),
			'NAME'           => $this->get_authentication_name()
		));
		return $view->render();
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_css_class()
	{
		return 'social-connect ' . $this->get_social_network()->get_css_class();
	}

	/**
	 * {@inheritdoc}
	 */
	abstract public function get_authentication();

	/**
	 * @desc Delete the Social Network session token
	 */
	public function delete_session_token()
	{
		if (isset($_SESSION[$this->get_authentication_id() . '_token']))
			unset($_SESSION[$this->get_authentication_id() . '_token']);
	}
}
?>
