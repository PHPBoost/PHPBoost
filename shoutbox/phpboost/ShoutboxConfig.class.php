<?php
/*##################################################
 *		             ShoutboxConfig.class.php
 *                            -------------------
 *   begin                : August 10, 2010
 *   copyright            : (C) 2010 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Comments Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Comments Public License for more details.
 *
 * You should have received a copy of the GNU Comments Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 */
class ShoutboxConfig extends AbstractConfigData
{
	const ITEMS_NUMBER_PER_PAGE = 'items_number_per_page';
	const REFRESH_DELAY = 'refresh_delay';
	const MAX_MESSAGES_NUMBER = 'max_messages_number';
	const FORBIDDEN_FORMATTING_TAGS = 'forbidden_formatting_tags';
	const MAX_LINKS_NUMBER_PER_MESSAGE = 'max_links_number_per_message';
	const AUTHORIZATIONS = 'authorizations';
	
	public function get_items_number_per_page()
	{
		return $this->get_property(self::ITEMS_NUMBER_PER_PAGE);
	}
	
	public function set_items_number_per_page($number)
	{
		$this->set_property(self::ITEMS_NUMBER_PER_PAGE, $number);
	}
	
	public function get_refresh_delay()
	{
		return $this->get_property(self::REFRESH_DELAY);
	}
	
	public function set_refresh_delay($delay)
	{
		$this->set_property(self::REFRESH_DELAY, $delay);
	}
	
	public function get_max_messages_number()
	{
		return $this->get_property(self::MAX_MESSAGES_NUMBER);
	}
	
	public function set_max_messages_number($nbr_messages)
	{
		$this->set_property(self::MAX_MESSAGES_NUMBER, $nbr_messages);
	}
	
	public function get_forbidden_formatting_tags()
	{
		return $this->get_property(self::FORBIDDEN_FORMATTING_TAGS);
	}
	
	public function set_forbidden_formatting_tags(Array $array)
	{
		$this->set_property(self::FORBIDDEN_FORMATTING_TAGS, $array);
	}
	
	public function get_max_links_number_per_message()
	{
		return $this->get_property(self::MAX_LINKS_NUMBER_PER_MESSAGE);
	}
	
	public function set_max_links_number_per_message($nbr_links)
	{
		$this->set_property(self::MAX_LINKS_NUMBER_PER_MESSAGE, $nbr_links);
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
			self::ITEMS_NUMBER_PER_PAGE => 20,
			self::REFRESH_DELAY => 60000,
			self::MAX_MESSAGES_NUMBER => 100,
			self::FORBIDDEN_FORMATTING_TAGS => array(
				'title', 'style', 'url', 'img','quote',
				'hide', 'list', 'color', 'bgcolor', 'font',
				'size', 'align', 'float', 'sup', 'sub',
				'indent', 'pre',' table', 'swf', 'movie',
				'sound', 'code', 'math', 'anchor', 'acronym'
			),
			self::MAX_LINKS_NUMBER_PER_MESSAGE => 2,
			self::AUTHORIZATIONS => array ('r-1' => 1, 'r0' => 3, 'r1' => 7)
		);
	}
	
	/**
	 * Returns the configuration.
	 * @return ShoutboxConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'shoutbox', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('shoutbox', self::load(), 'config');
	}
}
?>
