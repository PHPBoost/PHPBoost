<?php
/*##################################################
 *		                  DownloadConfig.class.php
 *                            -------------------
 *   begin                : June 29, 2013
 *   copyright            : (C) 2013 julienseth78
 *   email                : julienseth78@phpboost.com
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
 * @author Julien BRISWALTER <julienseth78@phpboost.com>
 */
class DownloadConfig extends AbstractConfigData
{
	const MAX_FILES_NUMBER_PER_PAGE = 'max_files_number_per_page';
	const COLUMNS_NUMBER = 'columns_number';
	const NOTATION_SCALE = 'notation_scale';
	const ROOT_CONTENTS = 'root_contents';
	const AUTHORIZATIONS = 'authorizations';
	
	public function get_max_files_number_per_page()
	{
		return $this->get_property(self::MAX_FILES_NUMBER_PER_PAGE);
	}
	
	public function set_max_files_number_per_page($value) 
	{
		$this->set_property(self::MAX_FILES_NUMBER_PER_PAGE, $value);
	}
	
	public function get_columns_number()
	{
		return $this->get_property(self::COLUMNS_NUMBER);
	}
	
	public function set_columns_number($value) 
	{
		$this->set_property(self::COLUMNS_NUMBER, $value);
	}
	
	public function get_notation_scale()
	{
		return $this->get_property(self::NOTATION_SCALE);
	}
	
	public function set_notation_scale($value) 
	{
		$this->set_property(self::NOTATION_SCALE, $value);
	}
	
	public function get_root_contents()
	{
		return $this->get_property(self::ROOT_CONTENTS);
	}
	
	public function set_root_contents($value) 
	{
		$this->set_property(self::ROOT_CONTENTS, $value);
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
			self::MAX_FILES_NUMBER_PER_PAGE => 10,
			self::COLUMNS_NUMBER => 2,
			self::NOTATION_SCALE => 5,
			self::ROOT_CONTENTS => LangLoader::get_message('root_contents', 'config', 'download'),
			self::AUTHORIZATIONS => array('r-1' => 1, 'r0' => 5, 'r1' => 7),
		);
	}
	
	/**
	 * Returns the configuration.
	 * @return DownloadConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'download', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('download', self::load(), 'config');
	}
}
?>