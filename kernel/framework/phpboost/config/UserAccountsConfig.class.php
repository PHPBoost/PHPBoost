<?php
/**
 * This class contains all the data related to the user accounts configuration.
 * @package     PHPBoost
 * @subpackage  Config
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 01 13
 * @since       PHPBoost 3.0 - 2009 10 28
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class UserAccountsConfig extends AbstractConfigData
{
	/**
	 * Name of the property indicating if member accounts have to be validated and how.
	 */
	const MEMBER_ACCOUNTS_VALIDATION_METHOD_PROPERTY = 'member_accounts_validation_method';
	/**
	 * Name of the property containing the welcome message visible at the entry of the member zone
	 */
	const WELCOME_MESSAGE_PROPERTY = 'welcome_message';
	/**
	 * Name of the property indicating if guests can register
	 */
	const REGISTRATION_ENABLED_PROPERTY = 'registration_enabled';
	/**
	 * Name of the property containing the registration agreement that user must accept
	 * to register on the site
	 */
	const REGISTRATION_AGREEMENT_PROPERTY = 'registration_agreement';
	/**
	 * Name of the property containing the time (in days) after which a member account
	 * which hasn't been activated will be automatically removed.
	 */
	const UNACTIVATED_ACCOUNTS_TIMEOUT_PROPERTY = 'unactivated_accounts_timeout';

	/**
	 * Name of the property indicating if users can upload on the server their avatar
	 */
	const ENABLE_AVATAR_UPLOAD_PROPERTY = 'enable_avatar_upload';

	/**
	 * Name of the property indicating whether avatars' automatic resizing is enabled or not
	 */
	const ENABLE_AVATAR_AUTO_RESIZING = 'enable_avatar_auto_resizing';

	/**
	 * Name of the property indicating if the default avatar is enable (users who don't have a
	 * specific avatar will have the default one).
	 */
	const DEFAULT_AVATAR_ENABLED_PROPERTY = 'default_avatar_enabled';

	/**
	 * Name of the property indicating the URL of the default avatar
	 */
	const DEFAULT_AVATAR_URL_PROPERTY = 'default_avatar_url';

	/**
	 * Name of the property indicating the maximum avatar width (in pixels).
	 */
	const MAX_AVATAR_WIDTH_PROPERTY = 'max_avatar_width';

	/**
	 * Name of the property indicating the maximum avatar height (in pixels).
	 */
	const MAX_AVATAR_HEIGHT_PROPERTY = 'max_avatar_height';

	/**
	 * Name of the property containing the max size of avatars
	 */
	const MAX_AVATAR_WEIGHT_PROPERTY = 'max_avatar_weight';

	/**
	 * Name of the property containing the authorization read member all
	 */
	const AUTH_READ_MEMBERS = 'auth_read_members';
	const AUTH_READ_MEMBERS_BIT = 1;

	const DEFAULT_LANG = 'default_lang';

	const DEFAULT_THEME = 'default_theme';

	const MAX_PRIVATE_MESSAGES_NUMBER = 'max_pm_number';

	const ALLOW_USERS_TO_CHANGE_DISPLAY_NAME = 'allow_users_to_change_display_name';
	const ALLOW_USERS_TO_CHANGE_EMAIL = 'allow_users_to_change_email';

	const AUTOMATIC_USER_ACCOUNTS_VALIDATION = '1';
	const MAIL_USER_ACCOUNTS_VALIDATION = '2';
	const ADMINISTRATOR_USER_ACCOUNTS_VALIDATION = '3';

	/**
	 * Tells how the member accounts are activated
	 * @return int 0 if there is no activation, 1 if the member activates its account thanks to the
	 * mail it receives, 2 if the administrator has to approbate it.
	 */
	public function get_member_accounts_validation_method()
	{
		return $this->get_property(self::MEMBER_ACCOUNTS_VALIDATION_METHOD_PROPERTY);
	}

	/**
	 * Sets the method used to validate the member accounts
	 * @param int $method 0 if there is no activation, 1 if the member activates its account
	 * thanks to the mail it receives, 2 if the administrator has to approbate it.
	 */
	public function set_member_accounts_validation_method($method)
	{
		$this->set_property(self::MEMBER_ACCOUNTS_VALIDATION_METHOD_PROPERTY, $method);
	}

	/**
	 * Returns the welcome message
	 * @return string the message
	 */
	public function get_welcome_message()
	{
		return $this->get_property(self::WELCOME_MESSAGE_PROPERTY);
	}

	/**
	 * Sets the welcome message displayed on the member profile main page.
	 * @param string $message The welcome message
	 */
	public function set_welcome_message($message)
	{
		$this->set_property(self::WELCOME_MESSAGE_PROPERTY, $message);
	}

	/**
	 * Tells whether the member registration is enabled
	 * @return bool true if it is, false otherwise.
	 */
	public function is_registration_enabled()
	{
		return $this->get_property(self::REGISTRATION_ENABLED_PROPERTY);
	}

	/**
	 * Sets the boolean indicating if the registration is enabled
	 * @param bool $enabled true if enabled, false otherwise
	 */
	public function set_registration_enabled($enabled)
	{
		$this->set_property(self::REGISTRATION_ENABLED_PROPERTY, $enabled);
	}

	/**
	 * Enables the member registration
	 */
	public function enable_registration()
	{
		$this->set_registration_enabled(true);
	}

	/**
	 * Disables the member registration
	 */
	public function disable_registration()
	{
		$this->set_registration_enabled(false);
	}

	/**
	 * Gets the agreement that users must accept to register
	 * @return unknown_type
	 */
	public function get_registration_agreement()
	{
		return $this->get_property(self::REGISTRATION_AGREEMENT_PROPERTY);
	}

	/**
	 * Sets the agreement that users mut accept to register
	 * @param $agreement The agreement
	 */
	public function set_registration_agreement($agreement)
	{
		$this->set_property(self::REGISTRATION_AGREEMENT_PROPERTY, $agreement);
	}

	/**
	 * Tells whether users can upload their avatar
	 * @return bool true if they can, false otherwise
	 */
	public function is_avatar_upload_enabled()
	{
		return $this->get_property(self::ENABLE_AVATAR_UPLOAD_PROPERTY);
	}

	/**
	 * Sets the boolean indicating if avatars can be uploaded on the server
	 * @param bool $enabled true if enabled, false otherwise
	 */
	public function set_avatar_upload_enabled($enabled)
	{
		$this->set_property(self::ENABLE_AVATAR_UPLOAD_PROPERTY, $enabled);
	}

	/**
	 * Tells whether the scaling is enabled avatars
	 * @return bool true if they can, false otherwise
	 */
	public function is_avatar_auto_resizing_enabled()
	{
		return $this->get_property(self::ENABLE_AVATAR_AUTO_RESIZING);
	}

	/**
	 * Sets the boolean value indicating whether the avatars should be resized automatically
	 * @param bool $enabled true if enabled, false otherwise
	 */
	public function set_avatar_auto_resizing_enabled($enabled)
	{
		$this->set_property(self::ENABLE_AVATAR_AUTO_RESIZING, $enabled);
	}

	/**
	 * Lets users upload their avatar
	 */
	public function enable_avatar_upload()
	{
		$this->set_avatar_upload_enabled(true);
	}

	/**
	 * Forbid users to upload their avatar
	 */
	public function disable_avatar_upload()
	{
		$this->set_avatar_upload_enabled(false);
	}

	/**
	 * Returns the time after which the member accounts which haven't been activated are removed
	 * @return int The time (in days)
	 */
	public function get_unactivated_accounts_timeout()
	{
		return $this->get_property(self::UNACTIVATED_ACCOUNTS_TIMEOUT_PROPERTY);
	}

	/**
	 * Sets the duration of the unactivated accounts timeout
	 * @param int $duration The duration (in days)
	 */
	public function set_unactivated_accounts_timeout($duration)
	{
		$this->set_property(self::UNACTIVATED_ACCOUNTS_TIMEOUT_PROPERTY, $duration);
	}

	/**
	 * Tells whether the default avatar is enabled
	 * @return bool true if it's enabled, false otherwise
	 */
	public function is_default_avatar_enabled()
	{
		return $this->get_property(self::DEFAULT_AVATAR_ENABLED_PROPERTY);
	}

	/**
	 * Sets the boolean indicating if the default avatar is enabled when a user hasn't its own one.
	 * @param true $enabled true if enabled, false otherwise
	 */
	public function set_default_avatar_name_enabled($enabled)
	{
		$this->set_property(self::DEFAULT_AVATAR_ENABLED_PROPERTY, $enabled);
	}

	/**
	 * Enables the default avatar for users who don't have their own one
	 */
	public function enable_default_avatar()
	{
		$this->set_default_avatar_name_enabled(true);
	}

	/**
	 * Disables the default avatar for users who don't have their own one
	 */
	public function disable_default_avatar()
	{
		$this->set_default_avatar_name_enabled(false);
	}

	/**
	 * Returns the default avatar URL
	 * @return string sThe URL
	 */
	public function get_default_avatar_name()
	{
		return $this->get_property(self::DEFAULT_AVATAR_URL_PROPERTY);
	}

	/**
	 * Sets the default avatar URL
	 * @param tring $url The URL of the default avatar
	 */
	public function set_default_avatar_name($url)
	{
		if (empty($url))
		{
			$url = 'no_avatar.png';
		}
		$this->set_property(self::DEFAULT_AVATAR_URL_PROPERTY, $url);
	}

	/**
	 * Returns the max width of avatars
	 * @return int The width in pixels
	 */
	public function get_max_avatar_width()
	{
		return $this->get_property(self::MAX_AVATAR_WIDTH_PROPERTY);
	}

	/**
	 * Sets the max width of avatars
	 * @param int $width The width in pixels
	 */
	public function set_max_avatar_width($width)
	{
		$this->set_property(self::MAX_AVATAR_WIDTH_PROPERTY, $width);
	}

	/**
	 * Returns the max height of avatars
	 * @return int The height in pixels
	 */
	public function get_max_avatar_height()
	{
		return $this->get_property(self::MAX_AVATAR_HEIGHT_PROPERTY);
	}

	/**
	 * Sets the max height of avatars
	 * @param int $height The height in pixels
	 */
	public function set_max_avatar_height($height)
	{
		$this->set_property(self::MAX_AVATAR_HEIGHT_PROPERTY, $height);
	}

	/**
	 * Returns the max weight of avatars
	 * @return int The weight in kilobytes
	 */
	public function get_max_avatar_weight()
	{
		return $this->get_property(self::MAX_AVATAR_WEIGHT_PROPERTY);
	}

	/**
	 * Sets the max weight of avatars
	 * @param int $height The weight in kilobytes
	 */
	public function set_max_avatar_weight($weight)
	{
		$this->set_property(self::MAX_AVATAR_WEIGHT_PROPERTY, $weight);
	}

	/**
	 * Returns the authorization to read members all
	 * @return array The array of authorizations.
	 */
	public function get_auth_read_members()
	{
		return $this->get_property(self::AUTH_READ_MEMBERS);
	}

	/**
	 * Sets the authorization to read members all
	 * @param array The array of authorizations.
	 */
	public function set_auth_read_members($auth)
	{
		$this->set_property(self::AUTH_READ_MEMBERS, $auth);
	}

	public function get_default_lang()
	{
		return $this->get_property(self::DEFAULT_LANG);
	}

	public function set_default_lang($lang)
	{
		$this->set_property(self::DEFAULT_LANG, $lang);
	}

	public function get_default_theme()
	{
		return $this->get_property(self::DEFAULT_THEME);
	}

	public function set_default_theme($theme)
	{
		$this->set_property(self::DEFAULT_THEME, $theme);
	}

	public function get_max_private_messages_number()
	{
		return $this->get_property(self::MAX_PRIVATE_MESSAGES_NUMBER);
	}

	public function set_max_private_messages_number($number)
	{
		$this->set_property(self::MAX_PRIVATE_MESSAGES_NUMBER, $number);
	}

	public function are_users_allowed_to_change_display_name()
	{
		return $this->get_property(self::ALLOW_USERS_TO_CHANGE_DISPLAY_NAME);
	}

	public function set_allow_users_to_change_display_name($enabled)
	{
		$this->set_property(self::ALLOW_USERS_TO_CHANGE_DISPLAY_NAME, $enabled);
	}

	public function are_users_allowed_to_change_email()
	{
		return $this->get_property(self::ALLOW_USERS_TO_CHANGE_EMAIL);
	}

	public function set_allow_users_to_change_email($enabled)
	{
		$this->set_property(self::ALLOW_USERS_TO_CHANGE_EMAIL, $enabled);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		$server_configuration = new ServerConfiguration();

		return array(
			self::REGISTRATION_ENABLED_PROPERTY => FormFieldCheckbox::CHECKED,
			self::MEMBER_ACCOUNTS_VALIDATION_METHOD_PROPERTY => self::AUTOMATIC_USER_ACCOUNTS_VALIDATION,
			self::WELCOME_MESSAGE_PROPERTY => LangLoader::get_message('site_config_msg_mbr', 'main'),
			self::REGISTRATION_AGREEMENT_PROPERTY => LangLoader::get_message('register_agreement', 'main'),
			self::UNACTIVATED_ACCOUNTS_TIMEOUT_PROPERTY => 20,
			self::ENABLE_AVATAR_UPLOAD_PROPERTY => FormFieldCheckbox::CHECKED,
			self::ENABLE_AVATAR_AUTO_RESIZING => $server_configuration->has_gd_library() ? FormFieldCheckbox::CHECKED : FormFieldCheckbox::UNCHECKED,
			self::DEFAULT_AVATAR_ENABLED_PROPERTY => FormFieldCheckbox::CHECKED,
			self::DEFAULT_AVATAR_URL_PROPERTY => 'no_avatar.png',
			self::MAX_AVATAR_WIDTH_PROPERTY => 120,
			self::MAX_AVATAR_HEIGHT_PROPERTY => 120,
			self::MAX_AVATAR_WEIGHT_PROPERTY => 20,
			self::AUTH_READ_MEMBERS => array('r0' => 1, 'r1' => 1),
			self::DEFAULT_LANG => 'english',
			self::DEFAULT_THEME => 'base',
			self::MAX_PRIVATE_MESSAGES_NUMBER => 50,
			self::ALLOW_USERS_TO_CHANGE_DISPLAY_NAME => false,
			self::ALLOW_USERS_TO_CHANGE_EMAIL => false
		);
	}

	/**
	 * Returns the configuration.
	 * @return UserAccountsConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'kernel', 'user-accounts');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('kernel', self::load(), 'user-accounts');
	}
}
?>
