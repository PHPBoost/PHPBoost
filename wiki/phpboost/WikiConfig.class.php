<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 10 29
 * @since       PHPBoost 4.0 - 2013 06 30
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class WikiConfig extends AbstractConfigData
{
	const STICKY_MENU = 'sticky_menu';
	const WIKI_NAME = 'wiki_name';
	const NUMBER_ARTICLES_ON_INDEX = 'number_articles_on_index';
	const HITS_COUNTER = 'hits_counter';
	const DISPLAY_CATEGORIES_ON_INDEX = 'display_categories_on_index';
	const INDEX_TEXT = 'index_text';
	const AUTHORIZATIONS = 'authorizations';

	public function enable_sticky_menu()
	{
		$this->set_property(self::STICKY_MENU, true);
	}

	public function disable_sticky_menu()
	{
		$this->set_property(self::STICKY_MENU, false);
	}

	public function is_sticky_menu_enabled()
	{
		return $this->get_property(self::STICKY_MENU);
	}

	public function get_wiki_name()
	{
		return $this->get_property(self::WIKI_NAME);
	}

	public function set_wiki_name($value)
	{
		$this->set_property(self::WIKI_NAME, $value);
	}

	public function get_number_articles_on_index()
	{
		return $this->get_property(self::NUMBER_ARTICLES_ON_INDEX);
	}

	public function set_number_articles_on_index($value)
	{
		$this->set_property(self::NUMBER_ARTICLES_ON_INDEX, $value);
	}

	public function display_categories_on_index()
	{
		$this->set_property(self::DISPLAY_CATEGORIES_ON_INDEX, true);
	}

	public function hide_categories_on_index()
	{
		$this->set_property(self::DISPLAY_CATEGORIES_ON_INDEX, false);
	}

	public function are_categories_displayed_on_index()
	{
		return $this->get_property(self::DISPLAY_CATEGORIES_ON_INDEX);
	}

	public function enable_hits_counter()
	{
		$this->set_property(self::HITS_COUNTER, true);
	}

	public function disable_hits_counter()
	{
		$this->set_property(self::HITS_COUNTER, false);
	}

	public function is_hits_counter_enabled()
	{
		return $this->get_property(self::HITS_COUNTER);
	}

	public function get_index_text()
	{
		return $this->get_property(self::INDEX_TEXT);
	}

	public function set_index_text($value)
	{
		$this->set_property(self::INDEX_TEXT, $value);
	}

	public function get_authorizations()
	{
		return $this->get_property(self::AUTHORIZATIONS);
	}

	public function set_authorizations(Array $array)
	{
		$this->set_property(self::AUTHORIZATIONS, $array);
	}

	public function get_default_values()
	{
		return array(
			self::STICKY_MENU => true,
			self::WIKI_NAME => LangLoader::get_message('wiki_name', 'config', 'wiki'),
			self::NUMBER_ARTICLES_ON_INDEX => 0,
			self::DISPLAY_CATEGORIES_ON_INDEX => false,
			self::HITS_COUNTER => true,
			self::INDEX_TEXT => LangLoader::get_message('index_text', 'config', 'wiki'),
			self::AUTHORIZATIONS => array('r-1' => 5137, 'r0' => 5395, 'r1' => 8191)
		);
	}

	/**
	 * Returns the configuration.
	 * @return DownloadConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'wiki', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('wiki', self::load(), 'config');
	}
}
?>
