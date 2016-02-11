<?php
/*##################################################
 *                               MediaConfig.class.php
 *                            -------------------
 *   begin                : February 2, 2015
 *   copyright            : (C) 2015 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */

class MediaConfig extends AbstractConfigData
{
	const ITEMS_NUMBER_PER_PAGE = 'items_number_per_page';
	const CATEGORIES_NUMBER_PER_PAGE = 'categories_number_per_page';
	const COLUMNS_NUMBER_PER_LINE = 'columns_number_per_line';
	const AUTHOR_DISPLAYED = 'author_displayed';
	const COMMENTS_ENABLED = 'comments_enabled';
	const NOTATION_ENABLED = 'notation_enabled';
	const NOTATION_SCALE = 'notation_scale';
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
	
	public function enable_comments()
	{
		$this->set_property(self::COMMENTS_ENABLED, true);
	}
	
	public function disable_comments()
	{
		$this->set_property(self::COMMENTS_ENABLED, false);
	}
	
	public function are_comments_enabled()
	{
		return $this->get_property(self::COMMENTS_ENABLED);
	}
	
	public function enable_notation()
	{
		$this->set_property(self::NOTATION_ENABLED, true);
	}
	
	public function disable_notation()
	{
		$this->set_property(self::NOTATION_ENABLED, false);
	}
	
	public function is_notation_enabled()
	{
		return $this->get_property(self::NOTATION_ENABLED);
	}
	
	public function get_notation_scale()
	{
		return $this->get_property(self::NOTATION_SCALE);
	}
	
	public function set_notation_scale($value)
	{
		$this->set_property(self::NOTATION_SCALE, $value);
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
			self::COMMENTS_ENABLED => true,
			self::NOTATION_ENABLED => true,
			self::NOTATION_SCALE => 5,
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
