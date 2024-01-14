<?php
/**
 * This class contains all the data related to the user accounts configuration.
 * @package     PHPBoost
 * @subpackage  Config
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2024 01 14
 * @since       PHPBoost 3.0 - 2009 10 28
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
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

	const AUTOMATIC_USER_ACCOUNTS_VALIDATION     = '1';
	const MAIL_USER_ACCOUNTS_VALIDATION          = '2';
	const ADMINISTRATOR_USER_ACCOUNTS_VALIDATION = '3';
    const ADMINISTRATOR_ACCOUNTS_VALIDATION_EMAIL = 'administrator_accounts_validation_email';

	const DISPLAY_TYPE = 'display_type';
	const TABLE_VIEW = 'table_view';
	const GRID_VIEW = 'grid_view';
	const ITEMS_PER_PAGE = 'items_per_page';
	const ITEMS_PER_ROW = 'items_per_row';

	const NO_AVATAR_URL = '/templates/__default__/images/no_avatar.webp';


	public function get_display_type()
	{
		return $this->get_property(self::DISPLAY_TYPE);
	}

	public function set_display_type($value)
	{
		$this->set_property(self::DISPLAY_TYPE, $value);
	}

	public function get_items_per_page()
	{
		return $this->get_property(self::ITEMS_PER_PAGE);
	}

	public function set_items_per_page($value)
	{
		$this->set_property(self::ITEMS_PER_PAGE, $value);
	}

	public function get_items_per_row()
	{
		return $this->get_property(self::ITEMS_PER_ROW);
	}

	public function set_items_per_row($value)
	{
		$this->set_property(self::ITEMS_PER_ROW, $value);
	}

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
	 * Send email to administrators if activation by administrator is selected
	 * @return bool 0 if selected, 1 if not
	 */
	public function get_administrator_accounts_validation_email()
	{
		return $this->get_property(self::ADMINISTRATOR_ACCOUNTS_VALIDATION_EMAIL);
	}

	/**
	 * Sets the send of an email to administrators if activation by administrator is selected
	 * @param bool 0 if selected, 1 if not
	 */
	public function set_administrator_accounts_validation_email($method)
	{
		$this->set_property(self::ADMINISTRATOR_ACCOUNTS_VALIDATION_EMAIL, $method);
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
	 * Returns the default avatar URL
	 * @return string The URL
	 */
	public function get_default_avatar_name()
	{
		return $this->get_property(self::DEFAULT_AVATAR_URL_PROPERTY);
	}

	/**
	 * Sets the default avatar URL
	 * @param string $url The URL of the default avatar
	 */
	public function set_default_avatar_name($url)
	{
		$this->set_property(self::DEFAULT_AVATAR_URL_PROPERTY, $url);
	}

	/**
	 * Tells whether the default avatar is set or not
	 * @return bool true if it is, false otherwise
	 */
	public function is_default_avatar_enabled()
	{
		return !empty($this->get_default_avatar());
	}

	/**
	 * Returns the default avatar proper URL adapted to user template if exists
	 * @return string The URL
	 */
	public function get_default_avatar()
	{
		return ($this->get_default_avatar_name() == FormFieldThumbnail::DEFAULT_VALUE) ? Url::to_rel(FormFieldThumbnail::get_default_thumbnail_url(self::NO_AVATAR_URL)) : Url::to_rel($this->get_default_avatar_name());
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
	 * Returns the max weight of avatars to use in form fields (converted in kilobytes)
	 * @return int The weight in kilobytes
	 */
	public function get_max_avatar_weight_in_kb()
	{
		return $this->get_property(self::MAX_AVATAR_WEIGHT_PROPERTY) * 1024;
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
			self::DISPLAY_TYPE                               => self::TABLE_VIEW,
			self::ITEMS_PER_PAGE                             => 25,
			self::ITEMS_PER_ROW                              => 2,
			self::REGISTRATION_ENABLED_PROPERTY              => FormFieldCheckbox::CHECKED,
			self::MEMBER_ACCOUNTS_VALIDATION_METHOD_PROPERTY => self::AUTOMATIC_USER_ACCOUNTS_VALIDATION,
            self::ADMINISTRATOR_ACCOUNTS_VALIDATION_EMAIL    => false,
			self::WELCOME_MESSAGE_PROPERTY                   => LangLoader::get_message('user.site.member.message', 'user-lang'),
			self::REGISTRATION_AGREEMENT_PROPERTY            => LangLoader::get_message('user.registration.agreement', 'user-lang'),
			self::UNACTIVATED_ACCOUNTS_TIMEOUT_PROPERTY      => 20,
			self::ENABLE_AVATAR_UPLOAD_PROPERTY              => FormFieldCheckbox::CHECKED,
			self::ENABLE_AVATAR_AUTO_RESIZING                => $server_configuration->has_gd_library() ? FormFieldCheckbox::CHECKED : FormFieldCheckbox::UNCHECKED,
			self::DEFAULT_AVATAR_URL_PROPERTY                => FormFieldThumbnail::DEFAULT_VALUE,
			self::MAX_AVATAR_WIDTH_PROPERTY                  => 120,
			self::MAX_AVATAR_HEIGHT_PROPERTY                 => 120,
			self::MAX_AVATAR_WEIGHT_PROPERTY                 => 20,
			self::AUTH_READ_MEMBERS                          => array('r0' => 1, 'r1' => 1),
			self::DEFAULT_LANG                               => 'english',
			self::DEFAULT_THEME                              => 'base',
			self::MAX_PRIVATE_MESSAGES_NUMBER                => 50,
			self::ALLOW_USERS_TO_CHANGE_DISPLAY_NAME         => true,
			self::ALLOW_USERS_TO_CHANGE_EMAIL                => true
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
