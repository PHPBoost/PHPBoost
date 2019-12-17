<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 10 19
 * @since       PHPBoost 4.1 - 2015 02 02
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class MediaConfig extends AbstractConfigData
{
	const ITEMS_NUMBER_PER_PAGE = 'items_number_per_page';
	const CATEGORIES_NUMBER_PER_PAGE = 'categories_number_per_page';
	const COLUMNS_NUMBER_PER_LINE = 'columns_number_per_line';
	const AUTHOR_DISPLAYED = 'author_displayed';
	const MAX_VIDEO_WIDTH = 'max_video_width';
	const MAX_VIDEO_HEIGHT = 'max_video_height';
	const ROOT_CATEGORY_DESCRIPTION = 'root_category_description';
	const ROOT_CATEGORY_CONTENT_TYPE = 'root_category_content_type';
	const AUTHORIZATIONS = 'authorizations';

	const CONTENT_TYPE_MUSIC_AND_VIDEO = 0;
	const CONTENT_TYPE_MUSIC = 1;
	const CONTENT_TYPE_VIDEO = 2;

	public function get_items_number_per_page()
	{
		return $this->get_property(self::ITEMS_NUMBER_PER_PAGE);
	}

	public function set_items_number_per_page($value)
	{
		$this->set_property(self::ITEMS_NUMBER_PER_PAGE, $value);
	}

	public function get_categories_number_per_page()
	{
		return $this->get_property(self::CATEGORIES_NUMBER_PER_PAGE);
	}

	public function set_categories_number_per_page($value)
	{
		$this->set_property(self::CATEGORIES_NUMBER_PER_PAGE, $value);
	}

	public function get_columns_number_per_line()
	{
		return $this->get_property(self::COLUMNS_NUMBER_PER_LINE);
	}

	public function set_columns_number_per_line($value)
	{
		$this->set_property(self::COLUMNS_NUMBER_PER_LINE, $value);
	}

	public function display_author()
	{
		$this->set_property(self::AUTHOR_DISPLAYED, true);
	}

	public function hide_author()
	{
		$this->set_property(self::AUTHOR_DISPLAYED, false);
	}

	public function is_author_displayed()
	{
		return $this->get_property(self::AUTHOR_DISPLAYED);
	}

	public function get_max_video_width()
	{
		return $this->get_property(self::MAX_VIDEO_WIDTH);
	}

	public function set_max_video_width($value)
	{
		$this->set_property(self::MAX_VIDEO_WIDTH, $value);
	}

	public function get_max_video_height()
	{
		return $this->get_property(self::MAX_VIDEO_HEIGHT);
	}

	public function set_max_video_height($value)
	{
		$this->set_property(self::MAX_VIDEO_HEIGHT, $value);
	}

	public function get_root_category_description()
	{
		return $this->get_property(self::ROOT_CATEGORY_DESCRIPTION);
	}

	public function set_root_category_description($value)
	{
		$this->set_property(self::ROOT_CATEGORY_DESCRIPTION, $value);
	}

	public function get_root_category_content_type()
	{
		return $this->get_property(self::ROOT_CATEGORY_CONTENT_TYPE);
	}

	public function set_root_category_content_type($value)
	{
		$this->set_property(self::ROOT_CATEGORY_CONTENT_TYPE, $value);
	}

	public function is_root_category_content_type_music_and_video()
	{
		return $this->get_property(self::ROOT_CATEGORY_CONTENT_TYPE) == self::CONTENT_TYPE_MUSIC_AND_VIDEO;
	}

	public function is_root_category_content_type_music()
	{
		return $this->get_property(self::ROOT_CATEGORY_CONTENT_TYPE) == self::CONTENT_TYPE_MUSIC;
	}

	public function is_root_category_content_type_video()
	{
		return $this->get_property(self::ROOT_CATEGORY_CONTENT_TYPE) == self::CONTENT_TYPE_VIDEO;
	}

	public function get_authorizations()
	{
		return $this->get_property(self::AUTHORIZATIONS);
	}

	public function set_authorizations(Array $authorizations)
	{
		$this->set_property(self::AUTHORIZATIONS, $authorizations);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return array(
			self::ITEMS_NUMBER_PER_PAGE => 25,
			self::CATEGORIES_NUMBER_PER_PAGE => 10,
			self::COLUMNS_NUMBER_PER_LINE => 2,
			self::AUTHOR_DISPLAYED => true,
			self::MAX_VIDEO_WIDTH => 900,
			self::MAX_VIDEO_HEIGHT => 570,
			self::ROOT_CATEGORY_DESCRIPTION => LangLoader::get_message('root_category_description', 'config', 'media'),
			self::ROOT_CATEGORY_CONTENT_TYPE => self::CONTENT_TYPE_MUSIC_AND_VIDEO,
			self::AUTHORIZATIONS => array('r-1' => 1, 'r0' => 5, 'r1' => 13)
		);
	}

	/**
	 * Returns the configuration.
	 * @return MediaConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'media', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('media', self::load(), 'config');
	}
}
?>
