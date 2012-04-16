<?php
/*##################################################
 *		                  FaqConfig.class.php
 *                            -------------------
 *   begin                : September 25, 2011
 *   copyright            : (C) 2011 Patrick DUBEAU
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
 * This class contains the configuration of the faq module.
 * @author Patrick Dubeau <daaxwizeman@gmail.com>
 *
 */
class FaqConfig extends AbstractConfigData
{
	const FAQ_NAME = 'faq_name';
	const NUMBER_COLUMNS = 'number_columns';
	const DISPLAY_MODE = 'display_mode';
	const ROOT_CAT_DISPLAY_MODE = 'root_cat_display_mode';
	const ROOT_CAT_DESCRIPTION = 'root_cat_description';
	const AUTHORIZATIONS = 'authorizations';
	
	public function get_faq_name()
	{
		return $this->get_property(self::FAQ_NAME);
	}
	
	public function set_faq_name($value) 
	{
		$this->set_property(self::FAQ_NAME, $value);
	}
	
	public function get_number_columns()
	{
		return $this->get_property(self::NUMBER_COLUMNS);
	}
	
	public function set_number_columns($value) 
	{
		$this->set_property(self::NUMBER_COLUMNS, $value);
	}
	
	public function get_display_mode()
	{
		return $this->get_property(self::DISPLAY_MODE);
	}
	
	public function set_display_mode($value) 
	{
		$this->set_property(self::DISPLAY_MODE, $value);
	}
	
	public function get_authorizations()
	{
		return $this->get_property(self::AUTHORIZATIONS);
	}
	
	public function set_authorizations(Array $array)
	{
		$this->set_property(self::AUTHORIZATIONS, $array);
	}
	
	//Config de la catégorie racine
	public function get_root_cat_display_mode()
	{
		return $this->get_property(self::ROOT_CAT_DISPLAY_MODE);
	}
	
	public function set_root_cat_display_mode($value) 
	{
		$this->set_property(self::ROOT_CAT_DISPLAY_MODE, $value);
	}
	
	public function get_root_cat_description()
	{
		return $this->get_property(self::ROOT_CAT_DESCRIPTION);
	}
	
	public function set_root_cat_description($value) 
	{
		$this->set_property(self::ROOT_CAT_DESCRIPTION, $value);
	}
	
	public function get_default_values()
	{
		return array(
			self::AUTHORIZATIONS => array('r-1' => 1, 'r0' => 1),
			self::FAQ_NAME => LangLoader::get_message('faq.config.faq_name', 'faq_config', 'faq'),
			self::NUMBER_COLUMNS => 4,
			self::DISPLAY_MODE => 'inline',
			self::ROOT_CAT_DISPLAY_MODE => 0,
			self::ROOT_CAT_DESCRIPTION => LangLoader::get_message('faq.config.root_cat_description', 'faq_config', 'faq')
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