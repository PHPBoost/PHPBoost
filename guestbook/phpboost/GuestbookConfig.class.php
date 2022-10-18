<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2019 11 19
 * @since       PHPBoost 3.0 - 2011 02 01
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class GuestbookConfig extends AbstractConfigData
{
	const ITEMS_PER_PAGE = 'items_per_page';
	const FORBIDDEN_TAGS = 'forbidden_tags';
	const MAX_LINKS_NUMBER_PER_MESSAGE_ENABLED = 'max_links_number_per_message_enabled';
	const MAXIMUM_LINKS_MESSAGE = 'maximum_links_message';
	const AUTHORIZATIONS = 'authorizations';

	/**
	 * @method Get items per page
	 */
	public function get_items_per_page()
	{
		return $this->get_property(self::ITEMS_PER_PAGE);
	}

	/**
	 * @method Set items per page
	 * @params int $value Number of items to display per page
	 */
	public function set_items_per_page($value)
	{
		$this->set_property(self::ITEMS_PER_PAGE, $value);
	}

	/**
	 * @method Get forbidden tags
	 */
	public function get_forbidden_tags()
	{
		return $this->get_property(self::FORBIDDEN_TAGS);
	}

	/**
	 * @method Set forbidden tags
	 * @params string[] $array Array of forbidden tags
	 */
	public function set_forbidden_tags(Array $array)
	{
		$this->set_property(self::FORBIDDEN_TAGS, $array);
	}

	public function enable_max_links_number_per_message()
	{
			$this->set_property(self::MAX_LINKS_NUMBER_PER_MESSAGE_ENABLED, true);
	}

	public function disable_max_links_number_per_message()
	{
			$this->set_property(self::MAX_LINKS_NUMBER_PER_MESSAGE_ENABLED, false);
	}

	public function is_max_links_number_per_message_enabled()
	{
			return $this->get_property(self::MAX_LINKS_NUMBER_PER_MESSAGE_ENABLED);
	}

	/**
	 * @method Get maximum links number in a message
	 */
	public function get_maximum_links_message()
	{
		return $this->get_property(self::MAXIMUM_LINKS_MESSAGE);
	}

	/**
	 * @method Set maximum links number in a message
	 * @params int $value Links number
	 */
	public function set_maximum_links_message($value)
	{
		$this->set_property(self::MAXIMUM_LINKS_MESSAGE, $value);
	}

	/**
	 * @method Get authorizations
	 */
	public function get_authorizations()
	{
		return $this->get_property(self::AUTHORIZATIONS);
	}

	/**
	 * @method Set authorizations
	 * @params string[] $array Array of authorizations
	 */
	public function set_authorizations(Array $array)
	{
		$this->set_property(self::AUTHORIZATIONS, $array);
	}

	/**
	 * @method Get default values.
	 */
	public function get_default_values()
	{
		return array(
			self::ITEMS_PER_PAGE => 10,
			self::FORBIDDEN_TAGS => array('movie', 'sound', 'code', 'math', 'mail', 'html', 'feed'),
			self::MAX_LINKS_NUMBER_PER_MESSAGE_ENABLED => false,
			self::MAXIMUM_LINKS_MESSAGE => 1,
			self::AUTHORIZATIONS => array('r-1' => 3, 'r0' => 3, 'r1' => 7)
		);
	}

	/**
	 * @method Load the guestbook configuration.
	 * @return GuestbookConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'guestbook', 'config');
	}

	/**
	 * Saves the guestbook configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('guestbook', self::load(), 'config');
	}
}
?>
