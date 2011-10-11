<?php
/*##################################################
 *		                  WebConfig.class.php
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
 * This class contains the configuration of the web module.
 * @author Patrick Dubeau <daaxwizeman@gmail.com>
 *
 */
class WebConfig extends AbstractConfigData
{	
	public function get_max_nbr_weblinks()
	{
		return $this->get_property('nbr_web_max');
	}
	
	public function set_max_nbr_weblinks($weblinks) 
	{
		$this->set_property('nbr_web_max', $weblinks);
	}
	
	public function get_max_nbr_category()
	{
		return $this->get_property('nbr_cat_max');
	}
	
	public function set_max_nbr_category($cat) 
	{
		$this->set_property('nbr_cat_max', $cat);
	}
	
	public function get_number_columns()
	{
		return $this->get_property('nbr_column');
	}
	
	public function set_number_columns($nbr_cols) 
	{
		$this->set_property('nbr_column', $nbr_cols);
	}
	
	public function get_note_max()
	{
		return $this->get_property('note_max');
	}
	
	public function set_note_max($note) 
	{
		$this->set_property('note_max', $note);
	}
	
	public function get_default_values()
	{
		return array(
			'nbr_web_max' => 10,
			'nbr_cat_max' => 10,
			'nbr_column' => 2,
			'note_max' => 5
		);
	}
	
	/**
	 * Returns the configuration.
	 * @return WebConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'web', 'main');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('web', self::load(), 'main');
	}
}
?>