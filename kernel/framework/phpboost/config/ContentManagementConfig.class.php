<?php
/**
 * @package     PHPBoost
 * @subpackage  Config
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2019 04 29
 * @since       PHPBoost 3.0 - 2010 07 07
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

class ContentManagementConfig extends AbstractConfigData
{
	const ANTI_FLOOD_ENABLED               = 'anti_flood';
	const ANTI_FLOOD_DURATION              = 'anti_flood_duration';
	const USED_CAPTCHA_MODULE              = 'used_captcha_module';
	const NEW_CONTENT_ENABLED              = 'new_content';
	const NEW_CONTENT_DURATION             = 'new_content_duration';
	const NEW_CONTENT_UNAUTHORIZED_MODULES = 'new_content_unauthorized_modules';
	const NOTATION_ENABLED                 = 'new_notation';
	const NOTATION_SCALE                   = 'notation_scale';
	const NOTATION_UNAUTHORIZED_MODULES    = 'notation_unauthorized_modules';
	const CONTENT_SHARING_ENABLED          = 'content_sharing_enabled';
	const CONTENT_SHARING_EMAIL_ENABLED    = 'content_sharing_email_enabled';
	const CONTENT_SHARING_PRINT_ENABLED    = 'content_sharing_print_enabled';
	const CONTENT_SHARING_SMS_ENABLED      = 'content_sharing_sms_enabled';
	const OPENGRAPH_ENABLED                = 'opengraph_enabled';
	const SITE_DEFAULT_PICTURE_URL         = 'site_default_picture_url';
	const ID_CARD_ENABLED     			   = 'id_card';
	const ID_CARD_UNAUTHORIZED_MODULES     = 'id_card_unauthorized_modules';

	public function is_anti_flood_enabled()
	{
		return $this->get_property(self::ANTI_FLOOD_ENABLED);
	}

	public function set_anti_flood_enabled($enabled)
	{
		$this->set_property(self::ANTI_FLOOD_ENABLED, $enabled);
	}

	public function get_anti_flood_duration()
	{
		return $this->get_property(self::ANTI_FLOOD_DURATION);
	}

	public function set_anti_flood_duration($duration)
	{
		$this->set_property(self::ANTI_FLOOD_DURATION, $duration);
	}

	public function get_used_captcha_module()
	{
		return $this->get_property(self::USED_CAPTCHA_MODULE);
	}

	public function set_used_captcha_module($module)
	{
		$this->set_property(self::USED_CAPTCHA_MODULE, $module);
	}

	public function is_new_content_enabled()
	{
		return $this->get_property(self::NEW_CONTENT_ENABLED);
	}

	public function set_new_content_enabled($enabled)
	{
		$this->set_property(self::NEW_CONTENT_ENABLED, $enabled);
	}

	public function get_new_content_duration()
	{
		return $this->get_property(self::NEW_CONTENT_DURATION);
	}

	public function set_new_content_duration($duration)
	{
		$this->set_property(self::NEW_CONTENT_DURATION, $duration);
	}

	public function get_new_content_unauthorized_modules()
	{
		return $this->get_property(self::NEW_CONTENT_UNAUTHORIZED_MODULES);
	}

	public function set_new_content_unauthorized_modules(array $modules)
	{
		$this->set_property(self::NEW_CONTENT_UNAUTHORIZED_MODULES, $modules);
	}

	public function is_notation_enabled()
	{
		return $this->get_property(self::NOTATION_ENABLED);
	}

	public function set_notation_enabled($enabled)
	{
		$this->set_property(self::NOTATION_ENABLED, $enabled);
	}

	public function get_notation_scale()
	{
		return $this->get_property(self::NOTATION_SCALE);
	}

	public function set_notation_scale($scale)
	{
		$this->set_property(self::NOTATION_SCALE, $scale);
	}

	public function get_notation_unauthorized_modules()
	{
		return $this->get_property(self::NOTATION_UNAUTHORIZED_MODULES);
	}

	public function set_notation_unauthorized_modules(array $modules)
	{
		$this->set_property(self::NOTATION_UNAUTHORIZED_MODULES, $modules);
	}

	public function is_content_sharing_enabled()
	{
		return $this->get_property(self::CONTENT_SHARING_ENABLED);
	}

	public function set_content_sharing_enabled($enabled)
	{
		$this->set_property(self::CONTENT_SHARING_ENABLED, $enabled);
	}

	public function is_content_sharing_email_enabled()
	{
		return $this->get_property(self::CONTENT_SHARING_EMAIL_ENABLED);
	}

	public function set_content_sharing_email_enabled($enabled)
	{
		$this->set_property(self::CONTENT_SHARING_EMAIL_ENABLED, $enabled);
	}

	public function is_content_sharing_print_enabled()
	{
		return $this->get_property(self::CONTENT_SHARING_PRINT_ENABLED);
	}

	public function set_content_sharing_print_enabled($enabled)
	{
		$this->set_property(self::CONTENT_SHARING_PRINT_ENABLED, $enabled);
	}

	public function is_content_sharing_sms_enabled()
	{
		return $this->get_property(self::CONTENT_SHARING_SMS_ENABLED);
	}

	public function set_content_sharing_sms_enabled($enabled)
	{
		$this->set_property(self::CONTENT_SHARING_SMS_ENABLED, $enabled);
	}

	public function is_opengraph_enabled()
	{
		return $this->get_property(self::OPENGRAPH_ENABLED);
	}

	public function set_opengraph_enabled($enabled)
	{
		$this->set_property(self::OPENGRAPH_ENABLED, $enabled);
	}

	public function get_site_default_picture_url()
	{
		return new Url($this->get_property(self::SITE_DEFAULT_PICTURE_URL));
	}

	public function set_site_default_picture_url($url)
	{
		$this->set_property(self::SITE_DEFAULT_PICTURE_URL, $url);
	}

	public function module_new_content_is_enabled($module_id)
	{
		return $this->is_new_content_enabled() && !in_array($module_id, $this->get_new_content_unauthorized_modules());
	}

	public function module_new_content_is_enabled_and_check_date($module_id, $date)
	{
		return $this->module_new_content_is_enabled($module_id) && $this->check_date($date);
	}

	private function check_date($date)
	{
		return (time() - $date) <= $this->get_new_content_duration()*86400;
	}

	public function module_notation_is_enabled($module_id)
	{
		return $this->is_notation_enabled() && !in_array($module_id, $this->get_notation_unauthorized_modules());
	}

	public function is_id_card_enabled()
	{
		return $this->get_property(self::ID_CARD_ENABLED);
	}

	public function set_id_card_enabled($enabled)
	{
		$this->set_property(self::ID_CARD_ENABLED, $enabled);
	}

	public function get_id_card_unauthorized_modules()
	{
		return $this->get_property(self::ID_CARD_UNAUTHORIZED_MODULES);
	}

	public function set_id_card_unauthorized_modules(array $modules)
	{
		$this->set_property(self::ID_CARD_UNAUTHORIZED_MODULES, $modules);
	}

	public function module_id_card_is_enabled($module_id)
	{
		return $this->is_id_card_enabled() && !in_array($module_id, $this->get_id_card_unauthorized_modules());
	}

	protected function get_default_values()
	{
		return array(
			self::ANTI_FLOOD_ENABLED               => false,
			self::ANTI_FLOOD_DURATION              => 7,
			self::USED_CAPTCHA_MODULE              => 'QuestionCaptcha',
			self::NEW_CONTENT_ENABLED              => true,
			self::NEW_CONTENT_DURATION             => 5,
			self::NEW_CONTENT_UNAUTHORIZED_MODULES => array(),
			self::NOTATION_ENABLED                 => true,
			self::NOTATION_SCALE                   => 5,
			self::NOTATION_UNAUTHORIZED_MODULES    => array(),
			self::CONTENT_SHARING_ENABLED          => true,
			self::CONTENT_SHARING_EMAIL_ENABLED    => true,
			self::CONTENT_SHARING_PRINT_ENABLED    => true,
			self::CONTENT_SHARING_SMS_ENABLED      => true,
			self::OPENGRAPH_ENABLED                => true,
			self::SITE_DEFAULT_PICTURE_URL         => '',
			self::ID_CARD_ENABLED              	   => true,
			self::ID_CARD_UNAUTHORIZED_MODULES => array(),
		);
	}

	/**
	 * Returns the configuration.
	 * @return ContentManagementConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'kernel', 'content-management');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('kernel', self::load(), 'content-management');
	}
}
?>
