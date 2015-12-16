<?php
/*##################################################
 *		                   ArticlesConfig.class.php
 *                            -------------------
 *   begin                : February 27, 2013
 *   copyright            : (C) 2013 Patrick DUBEAU
 *   email                : daaxwizeman@gmail.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Patrick DUBEAU <daaxwizeman@gmail.com>
 */
class ArticlesConfig extends AbstractConfigData
{    
	const NUMBER_ARTICLES_PER_PAGE = 'number_articles_per_page';
	const NUMBER_CATEGORIES_PER_PAGE = 'number_categories_per_page';
	const NUMBER_COLS_DISPLAY_CATS = 'number_cols_display_cats';
	const NUMBER_CHARACTER_TO_CUT = 'number_character_to_cut';

	const CATS_ICON_ENABLED = 'cats_icon_enabled';
	const DESCRIPTIONS_DISPLAYED_TO_GUESTS = 'descriptions_displayed_to_guests';
	const NOTATION_ENABLED = 'notation_enabled';
	const NOTATION_SCALE = 'notation_scale';
	const COMMENTS_ENABLED = 'comments_enable'; 
	const DATE_UPDATED_DISPLAYED = 'date_updated_displayed';
	const ROOT_CATEGORY_DESCRIPTION = 'root_category_description';
        
	const DISPLAY_TYPE = 'display_type';
	const DISPLAY_MOSAIC = 'mosaic';
	const DISPLAY_LIST = 'list';
	
	const DEFERRED_OPERATIONS = 'deferred_operations';
        
	const AUTHORIZATIONS = 'authorizations';
	
	public function get_number_articles_per_page()
	{
		return $this->get_property(self::NUMBER_ARTICLES_PER_PAGE);
	}
	
	public function set_number_articles_per_page($number) 
	{
		$this->set_property(self::NUMBER_ARTICLES_PER_PAGE, $number);
	}
	
	public function get_number_categories_per_page()
	{
		return $this->get_property(self::NUMBER_CATEGORIES_PER_PAGE);
	}
	
	public function set_number_categories_per_page($number) 
	{
		$this->set_property(self::NUMBER_CATEGORIES_PER_PAGE, $number);
	}
	
	public function get_number_cols_display_cats()
	{
		return $this->get_property(self::NUMBER_COLS_DISPLAY_CATS);
	}

	public function set_number_cols_display_cats($number) 
	{
		$this->set_property(self::NUMBER_COLS_DISPLAY_CATS, $number);
	}

	public function get_number_character_to_cut()
	{
		return $this->get_property(self::NUMBER_CHARACTER_TO_CUT);
	}

	public function set_number_character_to_cut($number)
	{
		$this->set_property(self::NUMBER_CHARACTER_TO_CUT, $number);
	}
	
	public function get_display_type()
	{
		return $this->get_property(self::DISPLAY_TYPE);
	}

	public function set_display_type($display_type)
	{
		$this->set_property(self::DISPLAY_TYPE, $display_type);
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
	
	public function set_notation_scale($notation_scale) 
	{
		$this->set_property(self::NOTATION_SCALE, $notation_scale);
	}
	
	public function enable_cats_icon() 
	{
		$this->set_property(self::CATS_ICON_ENABLED, true);
	}

	public function disable_cats_icon() {
		$this->set_property(self::CATS_ICON_ENABLED, false);
	}
	
	public function are_cats_icon_enabled()
	{
		return $this->get_property(self::CATS_ICON_ENABLED);
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
	
	public function get_date_updated_displayed()
	{
		return $this->get_property(self::DATE_UPDATED_DISPLAYED);
	}

	public function set_date_updated_displayed($date_updated_displayed)
	{
		$this->set_property(self::DATE_UPDATED_DISPLAYED, $date_updated_displayed);
	}
	
	public function get_root_category_description()
	{
		return $this->get_property(self::ROOT_CATEGORY_DESCRIPTION);
	}
	
	public function set_root_category_description($value)
	{
		$this->set_property(self::ROOT_CATEGORY_DESCRIPTION, $value);
	}
	
	public function get_authorizations()
	{
		return $this->get_property(self::AUTHORIZATIONS);
	}
	
	public function set_authorizations(Array $array)
	{
		$this->set_property(self::AUTHORIZATIONS, $array);
	}
	
	public function get_deferred_operations()
	{
		return $this->get_property(self::DEFERRED_OPERATIONS);
	}
	
	public function set_deferred_operations(Array $deferred_operations)
	{
		$this->set_property(self::DEFERRED_OPERATIONS, $deferred_operations);
	}
	
	public function get_default_values()
	{
		return array(
			self::NUMBER_ARTICLES_PER_PAGE => 10,
			self::NUMBER_CATEGORIES_PER_PAGE => 10,
			self::NUMBER_COLS_DISPLAY_CATS => 2,
			self::NUMBER_CHARACTER_TO_CUT => 128,
			self::CATS_ICON_ENABLED => false,
			self::DESCRIPTIONS_DISPLAYED_TO_GUESTS => false,
			self::COMMENTS_ENABLED => true,
			self::DATE_UPDATED_DISPLAYED => false,
			self::NOTATION_ENABLED => true,
			self::NOTATION_SCALE => 5,
			self::DISPLAY_TYPE => self::DISPLAY_MOSAIC,
			self::ROOT_CATEGORY_DESCRIPTION => LangLoader::get_message('root_category_description', 'config', 'articles'),
			self::AUTHORIZATIONS => array('r-1' => 1, 'r0' => 5, 'r1' => 13),
			self::DEFERRED_OPERATIONS => array()
		);
	}
	
	/**
	 * Returns the configuration.
	 * @return ArticlesConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'articles', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('articles', self::load(), 'config');
	}
}
?>