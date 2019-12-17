<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 11 01
 * @since       PHPBoost 4.0 - 2013 02 13
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Mipel <mipel@phpboost.com>
*/

class NewsConfig extends AbstractConfigData
{
	const NUMBER_NEWS_PER_PAGE = 'number_news_per_page';
	const COLUMNS_NUMBER_DISPLAY_NEWS = 'number_columns_display_news';

	const DISPLAY_CONDENSED_ENABLED = 'display_condensed_enabled';
	const DESCRIPTIONS_DISPLAYED_TO_GUESTS = 'descriptions_displayed_to_guests';
	const NUMBER_CHARACTER_TO_CUT = 'number_character_to_cut';

	const NEWS_SUGGESTIONS_ENABLED = 'news_suggestions_enabled';
	const AUTHOR_DISPLAYED = 'author_displayed';
	const NB_VIEW_ENABLED = 'nb_view_enabled';

    const DEFAULT_CONTENTS = 'default_contents';

	const DISPLAY_TYPE = 'display_type';
	const DISPLAY_GRID_VIEW = 'grid';
	const DISPLAY_LIST_VIEW = 'list';

	const DEFERRED_OPERATIONS = 'deferred_operations';

	const AUTHORIZATIONS = 'authorizations';

	public function get_number_news_per_page()
	{
		return $this->get_property(self::NUMBER_NEWS_PER_PAGE);
	}

	public function set_number_news_per_page($number_news_per_page)
	{
		$this->set_property(self::NUMBER_NEWS_PER_PAGE, $number_news_per_page);
	}

	public function get_number_columns_display_news()
	{
		return $this->get_property(self::COLUMNS_NUMBER_DISPLAY_NEWS);
	}

	public function set_number_columns_display_news($number_columns_display_news)
	{
		$this->set_property(self::COLUMNS_NUMBER_DISPLAY_NEWS, $number_columns_display_news);
	}

	public function get_display_condensed_enabled()
	{
		return $this->get_property(self::DISPLAY_CONDENSED_ENABLED);
	}

	public function set_display_condensed_enabled($display_condensed_enabled)
	{
		$this->set_property(self::DISPLAY_CONDENSED_ENABLED, $display_condensed_enabled);
	}

	public function display_descriptions_to_guests()
	{
		$this->set_property(self::DESCRIPTIONS_DISPLAYED_TO_GUESTS, true);
	}

	public function hide_descriptions_to_guests()
	{
		$this->set_property(self::DESCRIPTIONS_DISPLAYED_TO_GUESTS, false);
	}

	public function are_descriptions_displayed_to_guests()
	{
		return $this->get_property(self::DESCRIPTIONS_DISPLAYED_TO_GUESTS);
	}

	public function get_number_character_to_cut()
	{
		return $this->get_property(self::NUMBER_CHARACTER_TO_CUT);
	}

	public function set_number_character_to_cut($number)
	{
		$this->set_property(self::NUMBER_CHARACTER_TO_CUT, $number);
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

	public function get_nb_view_enabled()
	{
		return $this->get_property(self::NB_VIEW_ENABLED);
	}

	public function set_nb_view_enabled($nb_view_enabled)
	{
		$this->set_property(self::NB_VIEW_ENABLED, $nb_view_enabled);
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
			self::NUMBER_NEWS_PER_PAGE => 10,
			self::COLUMNS_NUMBER_DISPLAY_NEWS => 1,
			self::DISPLAY_CONDENSED_ENABLED => false,
			self::DESCRIPTIONS_DISPLAYED_TO_GUESTS => false,
			self::NUMBER_CHARACTER_TO_CUT => 150,
			self::NEWS_SUGGESTIONS_ENABLED => true,
			self::AUTHOR_DISPLAYED => true,
			self::NB_VIEW_ENABLED => false,
            self::DEFAULT_CONTENTS => '',
			self::DISPLAY_TYPE => self::DISPLAY_LIST_VIEW,
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
