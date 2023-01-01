<?php
/**
 * @package     PHPBoost
 * @subpackage  Config
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 10 19
 * @since       PHPBoost 3.0 - 2010 08 09
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FileUploadConfig extends AbstractConfigData
{
	const AUTHORIZATION_ENABLE_INTERFACE_FILES = 'authorization_enable_interface_files';
	const MAXIMUM_SIZE_UPLOAD = 'maximum_size_upload';
	const ENABLE_BANDWIDTH_PROTECTION = 'enable_bandwidth_protect';
	const DISPLAY_FILE_THUMBNAIL = 'display_file_thumbnail';
	const AUTHORIZED_EXTENSIONS = 'authorized_extensions';
	const AUTH_FILES_BIT = 0x01;

	public function get_authorization_enable_interface_files()
	{
		return $this->get_property(self::AUTHORIZATION_ENABLE_INTERFACE_FILES);
	}

	public function set_authorization_enable_interface_files(array $array)
	{
		$this->set_property(self::AUTHORIZATION_ENABLE_INTERFACE_FILES, $array);
	}

	public function is_authorized_to_access_interface_files()
	{
		return AppContext::get_current_user()->check_auth($this->get_authorization_enable_interface_files(), FileUploadConfig::AUTH_FILES_BIT);
	}

	public function get_maximum_size_upload()
	{
		return $this->get_property(self::MAXIMUM_SIZE_UPLOAD);
	}

	public function set_maximum_size_upload($size)
	{
		$this->set_property(self::MAXIMUM_SIZE_UPLOAD, $size);
	}

	public function get_enable_bandwidth_protect()
	{
		return $this->get_property(self::ENABLE_BANDWIDTH_PROTECTION);
	}

	public function set_enable_bandwidth_protect($value)
	{
		$this->set_property(self::ENABLE_BANDWIDTH_PROTECTION, $value);
	}

	public function get_display_file_thumbnail()
	{
		return $this->get_property(self::DISPLAY_FILE_THUMBNAIL);
	}

	public function set_display_file_thumbnail($value)
	{
		$this->set_property(self::DISPLAY_FILE_THUMBNAIL, $value);
	}

	public function get_authorized_extensions()
	{
		return $this->get_property(self::AUTHORIZED_EXTENSIONS);
	}

	public function set_authorized_extensions(array $array)
	{
		$this->set_property(self::AUTHORIZED_EXTENSIONS, $array);
	}

	public function get_authorized_picture_extensions()
	{
		$pictures_extensions = array('jpg', 'jpeg', 'bmp', 'gif', 'png', 'webp');
		$authorized_pictures_extensions = array();

		foreach ($pictures_extensions as $extension)
		{
			if (in_array($extension, $this->get_authorized_extensions()))
				$authorized_pictures_extensions[] = $extension;
		}

		return $authorized_pictures_extensions;
	}

	public function get_default_values()
	{
		return array(
			self::AUTHORIZATION_ENABLE_INTERFACE_FILES => array('r0' => 1, 'r1' => 1),
			self::MAXIMUM_SIZE_UPLOAD => 512,
			self::ENABLE_BANDWIDTH_PROTECTION => true,
			self::DISPLAY_FILE_THUMBNAIL => true,
			self::AUTHORIZED_EXTENSIONS => array(
				'jpg', 'jpeg', 'bmp', 'gif', 'png', 'webp', 'tif', 'svg', 'ico', 'nef',
				'rar', 'zip', 'gz', '7z',
				'txt', 'doc', 'docx', 'pdf', 'ppt', 'xls', 'odt', 'odp', 'ods', 'odg', 'odc', 'odf', 'odb', 'xcf', 'csv',
				'mp3','ogg', 'mpg', 'mov', 'wav', 'wmv', 'midi', 'mng', 'qt', 'mp4', 'mkv', 'webm',
				'ttf', 'tex', 'rtf', 'psd', 'iso'
			)
		);
	}

	/**
	 * Returns the configuration.
	 * @return FileUploadConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'kernel', 'file-upload-config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('kernel', self::load(), 'file-upload-config');
	}
}
?>
