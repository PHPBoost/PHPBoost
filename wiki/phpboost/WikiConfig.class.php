<?php
/*##################################################
 *		                  WikiConfig.class.php
 *                            -------------------
 *   begin                : June 30, 2013
 *   copyright            : (C) 2013 j1.seth
 *   email                : j1.seth@phpboost.com
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
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */
class WikiConfig extends AbstractConfigData
{
	const WIKI_NAME = 'wiki_name';
	const NUMBER_ARTICLES_ON_INDEX = 'number_articles_on_index';
	const HITS_COUNTER = 'hits_counter';
	const DISPLAY_CATEGORIES_ON_INDEX = 'display_categories_on_index';
	const INDEX_TEXT = 'index_text';
	const AUTHORIZATIONS = 'authorizations';
	
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
			self::WIKI_NAME => LangLoader::get_message('wiki_name', 'config', 'wiki'),
			self::NUMBER_ARTICLES_ON_INDEX => 0,
			self::DISPLAY_CATEGORIES_ON_INDEX => false,
			self::HITS_COUNTER => true,
			self::INDEX_TEXT => LangLoader::get_message('index_text', 'config', 'wiki'),
			self::AUTHORIZATIONS => array('r-1' => 1041, 'r0' => 1299, 'r1' => 4095),
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