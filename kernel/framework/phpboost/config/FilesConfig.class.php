<?php
/*##################################################
 *		             FilesConfig.class.php
 *                            -------------------
 *   begin                : August 09, 2010
 *   copyright            : (C) 2010 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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
 * @author Kévin MASSY <soldier.weasel@gmail.com>
 */
class FilesConfig extends AbstractConfigData
{
	const AUTH_ACTIVATION_INTERFACE_FILES = 'auth_activation_interface_files';
	const MAX_SIZE_UPLOAD_MEMBERS = 'max_size_upload_members';
	const BANDWITCH_PROTECT = 'bandwidth_protect';
	const AUTH_EXTENSION = 'auth_extension';
	
	public function get_auth_activation_interface_files()
	{
		return $this->get_property(self::AUTH_ACTIVATION_INTERFACE_FILES);
	}
	
	public function set_auth_activation_interface_files(Array $array)
	{
		$this->set_property(self::AUTH_ACTIVATION_INTERFACE_FILES, $array);
	}
	
	public function get_max_size_upload_members()
	{
		return $this->get_property(self::MAX_SIZE_UPLOAD_MEMBERS);
	}
	
	public function set_max_size_upload_members($size)
	{
		$this->set_property(self::MAX_SIZE_UPLOAD_MEMBERS, $size);
	}
	
	public function get_bandwidth_protect()
	{
		return $this->get_property(self::BANDWITCH_PROTECT);
	}
	
	public function set_bandwidth_protect($value)
	{
		$this->set_property(self::BANDWITCH_PROTECT, $value);
	}
	
	public function get_auth_extension()
	{
		return $this->get_property(self::AUTH_EXTENSION);
	}
	
	public function set_auth_extension(Array $array)
	{
		$this->set_property(self::AUTH_EXTENSION, $array);
	}
	
	public function get_default_values()
	{
		return array(
			self::AUTH_ACTIVATION_INTERFACE_FILES => array('r0' => '1', 'r1' => '1'),
			self::MAX_SIZE_UPLOAD_MEMBERS => '512',
			self::BANDWITCH_PROTECT => true,
			self::AUTH_EXTENSION => array(
				'0' => 'jpg', '1' => 'jpeg', '2' => 'bmp', '3' => 'gif', '4' => 'png', 
				'5' => 'tif', '6' => 'svg', '7' => 'ico', '8' => 'rar', '9' => 'zip', 
				'10' => 'gz', '11' => 'txt', '12' => 'doc', '13' => 'docx', '14' => 'pdf',
				'15' => 'ppt', '16' => 'xls', '17' => 'odt', '18' => 'odp', '19' => 'ods',
				'20' => 'odg', '21' => 'odc', '22' => 'odf', '23' => 'odb', '24' => 'xcf',
				'25' => 'flv', '26' => 'mp3', '27' => 'ogg', '28' => 'mpg', '29' => 'mov',
				'30' => 'swf', '31' => 'wav', '32' => 'wmv', '33' => 'midi', '34' => 'mng',
				'35' => 'qt', '36' => 'c', '37' => 'h','38' => 'cpp', '39' => 'java', 
				'40' => 'py', '41' => 'css', '42' => 'html', '43' => 'xml', '44' => 'ttf', 
				'45' => 'tex', '46' => 'rtf', '47' => 'psd'
			)			
		);
	}

	/**
	 * Returns the configuration.
	 * @return CommentsConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'kernel', 'files-config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('kernel', self::load(), 'files-config');
	}
}
?>