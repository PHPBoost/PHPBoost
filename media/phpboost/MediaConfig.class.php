<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 10
 * @since       PHPBoost 4.1 - 2015 02 02
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class MediaConfig extends AbstractConfigData
{
	const CATEGORIES_NUMBER_PER_PAGE = 'categories_number_per_page';
	const CATEGORIES_NUMBER_PER_ROW = 'categories_number_per_row';
	const ITEMS_NUMBER_PER_PAGE = 'items_number_per_page';
	const ITEMS_NUMBER_PER_ROW = 'items_number_per_row';
	const AUTHOR_DISPLAYED = 'author_displayed';
	const MAX_VIDEO_WIDTH = 'max_video_width';
	const MAX_VIDEO_HEIGHT = 'max_video_height';
	const ROOT_CATEGORY_DESCRIPTION = 'root_category_description';
	const ROOT_CATEGORY_CONTENT_TYPE = 'root_category_content_type';
	const AUTHORIZATIONS = 'authorizations';

	const DISPLAY_TYPE = 'display_type';
	const GRID_VIEW = 'grid_view';
	const LIST_VIEW = 'list_view';

	const CONTENT_TYPE_MUSIC_AND_VIDEO = 0;
	const CONTENT_TYPE_MUSIC = 1;
	const CONTENT_TYPE_VIDEO = 2;

	public function get_categories_number_per_page()
	{
		return $this->get_property(self::CATEGORIES_NUMBER_PER_PAGE);
	}

	public function set_categories_number_per_page($value)
	{
		$this->set_property(self::CATEGORIES_NUMBER_PER_PAGE, $value);
	}

	public function get_categories_number_per_row()
	{
		return $this->get_property(self::CATEGORIES_NUMBER_PER_ROW);
	}

	public function set_categories_number_per_row($value)
	{
		$this->set_property(self::CATEGORIES_NUMBER_PER_ROW, $value);
	}

	public function get_items_number_per_page()
	{
		return $this->get_property(self::ITEMS_NUMBER_PER_PAGE);
	}

	public function set_items_number_per_page($value)
	{
		$this->set_property(self::ITEMS_NUMBER_PER_PAGE, $value);
	}

	public function get_items_number_per_row()
	{
		return $this->get_property(self::ITEMS_NUMBER_PER_ROW);
	}

	public function set_items_number_per_row($value)
	{
		$this->set_property(self::ITEMS_NUMBER_PER_ROW, $value);
	}

	public function get_display_type()
	{
		return $this->get_property(self::DISPLAY_TYPE);
	}

	public function set_display_type($display_type)
	{
		$this->set_property(self::DISPLAY_TYPE, $display_type);
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
			self::CATEGORIES_NUMBER_PER_PAGE => 10,
			self::CATEGORIES_NUMBER_PER_ROW => 2,
			self::ITEMS_NUMBER_PER_PAGE => 25,
			self::ITEMS_NUMBER_PER_ROW => 2,
			self::DISPLAY_TYPE => self::GRID_VIEW,
			self::AUTHOR_DISPLAYED => true,
			self::MAX_VIDEO_WIDTH => 900,
			self::MAX_VIDEO_HEIGHT => 570,
			self::ROOT_CATEGORY_DESCRIPTION => CategoriesService::get_default_root_category_description('media'),
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
