<?php
/*##################################################
 *                               FaqConfig.class.php
 *                            -------------------
 *   begin                : September 2, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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
 * @author Julien BRISWALTER <julienseth78@phpboost.com>
 */

class FaqConfig extends AbstractConfigData
{
	const COLUMNS_NUMBER_PER_LINE = 'columns_number_per_line';
	const DISPLAY_TYPE = 'display_type';
	const ROOT_CATEGORY_DESCRIPTION = 'root_category_description';
	const AUTHORIZATIONS = 'authorizations';
	
	const DISPLAY_TYPE_INLINE = 'inline';
	const DISPLAY_TYPE_BLOCK = 'block';
	
	public function get_columns_number_per_line()
	{
		return $this->get_property(self::COLUMNS_NUMBER_PER_LINE);
	}
	
	public function set_columns_number_per_line($value)
	{
		$this->set_property(self::COLUMNS_NUMBER_PER_LINE, $value);
	}
	
	public function get_display_type()
	{
		return $this->get_property(self::DISPLAY_TYPE);
	}
	
	public function set_display_type($value) 
	{
		$this->set_property(self::DISPLAY_TYPE, $value);
	}
	
	public function is_display_type_inline()
	{
		return $this->get_property(self::DISPLAY_TYPE) == self::DISPLAY_TYPE_INLINE;
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
			self::COLUMNS_NUMBER_PER_LINE => 4,
			self::DISPLAY_TYPE => self::DISPLAY_TYPE_INLINE,
			self::ROOT_CATEGORY_DESCRIPTION => LangLoader::get_message('root_category_description', 'config', 'faq'),
			self::AUTHORIZATIONS => array('r-1' => 1, 'r0' => 5, 'r1' => 13)
		);
	}
	
	/**
	 * Returns the configuration.
	 * @return FaqConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'faq', 'config');
	}
	
	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('faq', self::load(), 'config');
	}
}
?>
