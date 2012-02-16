<?php
/*##################################################
 *		                  DownloadConfig.class.php
 *                            -------------------
 *   begin                : February 16, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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

class DownloadConfig extends AbstractConfigData
{
	const AUTHORIZATION = 'authorization';
	
	public function get_nbr_file_max()
	{
		return $this->get_property('nbr_file_max');
	}
	
	public function set_nbr_file_max($nbr) 
	{
		$this->set_property('nbr_file_max', $nbr);
	}
	
	public function get_number_columns()
	{
		return $this->get_property('num_cols');
	}
	
	public function set_number_columns($num_cols) 
	{
		$this->set_property('num_cols', $num_cols);
	}
	
	public function get_note_max()
	{
		return $this->get_property('note_max');
	}
	
	public function set_note_max($note) 
	{
		$this->set_property('note_max', $note);
	}
	
	public function get_root_contents()
	{
		return $this->get_property('root_contents');
	}
	
	public function set_root_contents($content) 
	{
		$this->set_property('root_contents', $content);
	}
	
	public function get_authorization()
	{
		return $this->get_property(self::AUTHORIZATION);
	}
	
	public function set_authorization(Array $array)
	{
		$this->set_property(self::AUTHORIZATION, $array);
	}
	
	public function get_default_values()
	{
		return array(
			self::AUTHORIZATION => array('r-1' => 1, 'r0' => 5, 'r1' => 7),
			'nbr_file_max' => 10,
			'num_cols' => 2,
			'note_max' => 5,
			'root_contents' => 'Bienvenue dans l\'espace de téléchargement du site!'
		);
	}
	
	/**
	 * Returns the configuration.
	 * @return DownloadConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'download', 'main');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('download', self::load(), 'main');
	}
}
?>