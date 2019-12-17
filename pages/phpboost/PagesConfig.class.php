<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2017 08 23
 * @since       PHPBoost 3.0 - 2012 03 02
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class PagesConfig extends AbstractConfigData
{
	const COUNT_HITS_ACTIVATED  = 'count_hits_activated';
	const COMMENTS_ACTIVATED    = 'comments_activated';
	const AUTHORIZATIONS        = 'authorizations';
	const LEFT_COLUMN_DISABLED  = 'left_column_disabled';
	const RIGHT_COLUMN_DISABLED = 'right_column_disabled';

	public function get_count_hits_activated()
	{
		return $this->get_property(self::COUNT_HITS_ACTIVATED);
	}

	public function set_count_hits_activated($value)
	{
		$this->set_property(self::COUNT_HITS_ACTIVATED, $value);
	}

	public function get_comments_activated()
	{
		return $this->get_property(self::COMMENTS_ACTIVATED);
	}

	public function set_comments_activated($value)
	{
		$this->set_property(self::COMMENTS_ACTIVATED, $value);
	}

	public function get_authorizations()
	{
		return $this->get_property(self::AUTHORIZATIONS);
	}

	public function set_authorizations(Array $array)
	{
		$this->set_property(self::AUTHORIZATIONS, $array);
	}

	public function is_left_column_disabled()
	{
		return $this->get_property(self::LEFT_COLUMN_DISABLED);
	}

	public function set_left_column_disabled($value)
	{
		$this->set_property(self::LEFT_COLUMN_DISABLED, $value);
	}

	public function is_right_column_disabled()
	{
		return $this->get_property(self::RIGHT_COLUMN_DISABLED);
	}

	public function set_right_column_disabled($value)
	{
		$this->set_property(self::RIGHT_COLUMN_DISABLED, $value);
	}

	public function get_default_values()
	{
		return array(
			self::COUNT_HITS_ACTIVATED => true,
			self::COMMENTS_ACTIVATED => true,
			self::AUTHORIZATIONS => array('r-1' => 5, 'r0' => 7, 'r1' => 7, 'r2' => 7),
			self::LEFT_COLUMN_DISABLED => false,
			self::RIGHT_COLUMN_DISABLED => false,
		);
	}

	/**
	 * Returns the configuration.
	 * @return PagesConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'pages', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('pages', self::load(), 'config');
	}
}
?>
