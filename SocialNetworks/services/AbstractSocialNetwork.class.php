<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 09 20
 * @since       PHPBoost 5.1 - 2018 04 13
*/

abstract class AbstractSocialNetwork implements SocialNetwork
{
	const SOCIAL_NETWORK_ID = '';

	/**
	 * {@inheritdoc}
	 */
	public function get_name()
	{
		return '';
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_icon_name()
	{
		return static::SOCIAL_NETWORK_ID;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_css_class()
	{
		return static::SOCIAL_NETWORK_ID;
	}

	/**
	 * {@inheritdoc}
	 */
	public function has_content_sharing_url()
	{
		return $this->get_content_sharing_url() != '';
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_content_sharing_url()
	{
		return '';
	}

	/**
	 * {@inheritdoc}
	 */
	public function has_mobile_content_sharing_url()
	{
		return $this->get_mobile_content_sharing_url() != '';
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_mobile_content_sharing_url()
	{
		return '';
	}

	/**
	 * {@inheritdoc}
	 */
	public function is_mobile_only()
	{
		return false;
	}

	/**
	 * {@inheritdoc}
	 */
	public function is_desktop_only()
	{
		return false;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_share_image_renderer_html()
	{
		$tpl = new FileTemplate('SocialNetworks/share_image_render.tpl');
		$tpl->put_all(array(
			'NAME' => $this->get_name(),
			'ICON_NAME' => $this->get_icon_name(),
		));
		return $tpl->render();
	}

	/**
	 * {@inheritdoc}
	 */
	public function has_authentication()
	{
		return $this->get_external_authentication() !== false && $this->get_external_authentication() instanceof ExternalAuthentication;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_external_authentication()
	{
		return false;
	}

	/**
	 * {@inheritdoc}
	 */
	public function authentication_identifiers_needed()
	{
		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function authentication_client_secret_needed()
	{
		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_identifiers_creation_url()
	{
		return '';
	}

	/**
	 * {@inheritdoc}
	 */
	public function callback_url_needed()
	{
		return true;
	}
}
?>
