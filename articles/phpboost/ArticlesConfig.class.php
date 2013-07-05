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
	const NOTATION_SCALE = 'notation_scale';
	const COMMENTS_ENABLED = 'comments_enable'; 
	const DISPLAY_TYPE = 'display_type';
	const DISPLAY_MOSAIC = 'mosaic';
	const DISPLAY_LIST = 'list';
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
	
	public function get_display_type()
	{
		return $this->get_property(self::DISPLAY_TYPE);
	}

	public function set_display_type($display_type)
	{
		$this->set_property(self::DISPLAY_TYPE, $display_type);
	}
	
	public function get_notation_scale()
	{
		return $this->get_property(self::NOTATION_SCALE);
	}
	
	public function set_notation_scale($notation_scale) 
	{
		$this->set_property(self::NOTATION_SCALE, $notation_scale);
	}
	
	public function get_comments_enabled()
	{
		return $this->get_property(self::COMMENTS_ENABLED);
	}

	public function set_comments_enabled($comments_enabled)
	{
		$this->set_property(self::COMMENTS_ENABLED, $comments_enabled);
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
			self::NUMBER_ARTICLES_PER_PAGE => 10,
			self::NUMBER_CATEGORIES_PER_PAGE => 10,
			self::COMMENTS_ENABLED => true,
			self::NOTATION_SCALE => 5,
			self::DISPLAY_TYPE => self::DISPLAY_MOSAIC,
			self::AUTHORIZATIONS => array('r1' => 13, 'r0' => 5, 'r-1' => 1)
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