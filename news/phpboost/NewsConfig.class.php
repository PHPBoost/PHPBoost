<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 03 09
 * @since       PHPBoost 4.0 - 2013 02 13
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Mipel <mipel@phpboost.com>
*/

class NewsConfig extends AbstractConfigData
{
	const ITEMS_PER_PAGE = 'items_per_page';
	const ITEMS_PER_ROW = 'items_per_row';

	const FULL_ITEM_DISPLAY = 'full_item_display';
	const DISPLAY_SUMMARY_TO_GUESTS = 'display_summary_to_guests';
	const CHARACTERS_NUMBER_TO_CUT = 'characters_number_to_cut';

	const NEWS_SUGGESTIONS_ENABLED = 'news_suggestions_enabled';
	const AUTHOR_DISPLAYED = 'author_displayed';
	const VIEWS_NUMBER = 'views_number';

	const DEFAULT_CONTENTS = 'default_contents';

	const DISPLAY_TYPE = 'display_type';
	const GRID_VIEW = 'grid_view';
	const LIST_VIEW = 'list_view';

	const DEFERRED_OPERATIONS = 'deferred_operations';

	const AUTHORIZATIONS = 'authorizations';

	public function get_items_per_page()
	{
		return $this->get_property(self::ITEMS_PER_PAGE);
	}

	public function set_items_per_page($items_per_page)
	{
		$this->set_property(self::ITEMS_PER_PAGE, $items_per_page);
	}

	public function get_items_per_row()
	{
		return $this->get_property(self::ITEMS_PER_ROW);
	}

	public function set_items_per_row($items_per_row)
	{
		$this->set_property(self::ITEMS_PER_ROW, $items_per_row);
	}

	public function get_full_item_display()
	{
		return $this->get_property(self::FULL_ITEM_DISPLAY);
	}

	public function set_full_item_display($full_item_display)
	{
		$this->set_property(self::FULL_ITEM_DISPLAY, $full_item_display);
	}

	public function display_summary_to_guests()
	{
		$this->set_property(self::DISPLAY_SUMMARY_TO_GUESTS, true);
	}

	public function hide_summary_to_guests()
	{
		$this->set_property(self::DISPLAY_SUMMARY_TO_GUESTS, false);
	}

	public function is_summary_displayed_to_guests()
	{
		return $this->get_property(self::DISPLAY_SUMMARY_TO_GUESTS);
	}

	public function get_characters_number_to_cut()
	{
		return $this->get_property(self::CHARACTERS_NUMBER_TO_CUT);
	}

	public function set_characters_number_to_cut($number)
	{
		$this->set_property(self::CHARACTERS_NUMBER_TO_CUT, $number);
	}

	public function get_news_suggestions_enabled()
	{
		return $this->get_property(self::NEWS_SUGGESTIONS_ENABLED);
	}

	public function set_news_suggestions_enabled($news_suggestions_enabled)
	{
		$this->set_property(self::NEWS_SUGGESTIONS_ENABLED, $news_suggestions_enabled);
	}

	public function get_author_displayed()
	{
		return $this->get_property(self::AUTHOR_DISPLAYED);
	}

	public function set_author_displayed($author_displayed)
	{
		$this->set_property(self::AUTHOR_DISPLAYED, $author_displayed);
	}

	public function get_views_number()
	{
		return $this->get_property(self::VIEWS_NUMBER);
	}

	public function set_views_number($views_number)
	{
		$this->set_property(self::VIEWS_NUMBER, $views_number);
	}

	public function get_display_type()
	{
		return $this->get_property(self::DISPLAY_TYPE);
	}

	public function set_display_type($display_type)
	{
		$this->set_property(self::DISPLAY_TYPE, $display_type);
	}

	public function get_default_contents()
	{
		return $this->get_property(self::DEFAULT_CONTENTS);
	}

	public function set_default_contents($value)
	{
		$this->set_property(self::DEFAULT_CONTENTS, $value);
	}

	public function get_authorizations()
	{
		return $this->get_property(self::AUTHORIZATIONS);
	}

	public function set_authorizations(Array $authorizations)
	{
		$this->set_property(self::AUTHORIZATIONS, $authorizations);
	}

	public function get_deferred_operations()
	{
		return $this->get_property(self::DEFERRED_OPERATIONS);
	}

	public function set_deferred_operations(Array $deferred_operations)
	{
		$this->set_property(self::DEFERRED_OPERATIONS, $deferred_operations);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return array(
			self::ITEMS_PER_PAGE => 10,
			self::ITEMS_PER_ROW => 1,
			self::DISPLAY_SUMMARY_TO_GUESTS => false,
			self::CHARACTERS_NUMBER_TO_CUT => 150,
			self::NEWS_SUGGESTIONS_ENABLED => true,
			self::AUTHOR_DISPLAYED => true,
			self::VIEWS_NUMBER => true,
			self::DEFAULT_CONTENTS => '',
			self::DISPLAY_TYPE => self::LIST_VIEW,
			self::FULL_ITEM_DISPLAY => true,
			self::AUTHORIZATIONS => array('r-1' => 1, 'r0' => 5, 'r1' => 13),
			self::DEFERRED_OPERATIONS => array()
		);
	}

	/**
	 * Returns the configuration.
	 * @return NewsConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'news', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('news', self::load(), 'config');
	}
}
?>
