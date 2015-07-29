<?php
/*##################################################
 *                              upload.class.php
 *                            -------------------
 *   begin                : January 27, 2007
 *   copyright            : (C) 2007 Viarre RÃ©gis
 *   email                : crowkait@phpboost.com
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
 * @author RÃ©gis VIARRE <crowkait@phpboost.com
 * @desc This class provides you methods to upload easily files to the ftp.
 * @package {@package}
 */
class Upload
{
	private $error = ''; 
	private $base_directory;
	private $extension = '';
	private $filename = '';
	private $original_filename = '';
	private $size = 0;
	private $contentCheck = true;
	
	const UNIQ_NAME = true;
	const NO_UNIQ_NAME = false;
	const CHECK_EXIST = true;
	const DELETE_ON_ERROR = true;
	const NO_DELETE_ON_ERROR = false;
	
	/**
	 * @desc constructor
	 * @param string $base_directory Set the directory to upload the files.
	 */
	public function __construct($base_directory = 'upload')
	{
		$this->base_directory = $base_directory;
	}
	
	/**
	 * @desc Uploads a file.
	 * @param string $filepostname Name in the input file formulary.
	 * @param string $regexp Regular expression to specify file format.
	 * @param boolean $uniq_name If true assign a new name if a file with same name already exist. Otherwise previous file is overwrite.
	 * @param int $weight_max The maximum file size.
	 * @param boolean $check_exist If true verify if a file with the same name exist on the ftp. Otherwise previous file is overwrite.
	 * @return boolean True if the file has been succefully uploaded. Error code if an error occured.
	 */
	public function file($filepostname, $regexp = '', $uniq_name = false, $weight_max = 100000000, $check_exist = true)
	{
		$file = $_FILES[$filepostname];
		$this->size = $file['size'];
		$this->original_filename = $file['name'];
		
		if (!empty($file) && $this->size > 0)
		{
			if (($this->size/1024) <= $weight_max)
			{
				//RÃ©cupÃ©ration des infos sur le fichier Ã  traiter.
				$this->generate_file_info($uniq_name);
				if ($this->check_file_path($regexp))
				{
					if (!$check_exist || !file_exists($this->base_directory . $this->filename)) //Autorisation d'Ã©craser le fichier?
					{
						$this->error = self::error_manager($file['error']);
						if (empty($this->error))
						{
							if (!empty($this->filename) && !empty($file['tmp_name'])) 
							{
								if ($this->contentCheck && !$this->check_file_content($file['tmp_name'])) 
								{
									$this->error = 'e_upload_php_code';
									return false;
								} 
									
								if (!move_uploaded_file($file['tmp_name'], $this->base_directory . $this->filename)) 
								{
									$this->error = 'e_upload_error';
								} 
								else 
								{
									return true;
								}
							}
							else 
							{
								$this->error = 'e_upload_error';
							}
						}
					}
					else
					{
						$this->error = 'e_upload_already_exist';
					}
				}
				else
					$this->error = 'e_upload_invalid_format';
			}
			else
				$this->error = 'e_upload_max_weight';
		}
		else
			$this->error = 'e_upload_error';
			
		return false;
	}
		
	/**
	 * @desc Checks whether an image is compliant to an maximum width and height, otherwise is $delete value is true delete it. 
	 * @param string $filepath Path to the image.
	 * @param int $width_max Max width
	 * @param int $height_max Max height
	 * @param boolean $delete
	 * @return string Error code.
	 */
	public function check_img($width_max, $height_max, $delete = true)
	{
		$error = '';
		if (!empty($this->filename))
		{
			$filepath = $this->base_directory . $this->filename;
		
			list($width, $height, $ext) = function_exists('getimagesize') ? @getimagesize($filepath) : array(0, 0, 0);
			if ($width > $width_max || $height > $height_max ) //Hauteur et largeur max.
			{
				$error = 'e_upload_max_dimension';
			}
	
			if (!empty($error) && $delete)
			{
				@unlink($filepath);
			}
		}
		
		return $error;
	}
	
	/**
	 * Check the file content for php tags to avoid illegal uploads
	 * @param tmp file path
	 */
	private function check_file_content($file) {
		$file_content = file_get_contents($file);
		
		if (strpos($file_content, "<?php") === false) {
			return true;
		}
		if (strpos($file_content, "<?") === false) {
			return true;
		}

		//Fail to validate, remove tmp file
		@unlink($file);
		return false;
	}
	
	/**
	 * @desc Checks the validity of a file with regular expression.
	 * @param string $filename The file name
	 * @param string $regexp Regular expression
	 * @return boolean The result of the regular expression test.
	 */
	private function check_file_path($regexp)
	{
		if (!empty($regexp))
		{
			//Valide, sinon supprimÃ©
			if (preg_match($regexp, $this->original_filename) && strpos($this->original_filename, '.php') === false
				 && strpos($this->original_filename, '.phtml') === false) {//Valide, sinon supprimÃ©
				return true;
			}
			return false;
		}
		return true;
	}
	
	/**
	 * @desc Generates a unique file name. Completes informations on the file.
	 * @param string $filename The filename
	 * @param boolean $uniq_name
	 */
	private function generate_file_info($uniq_name)
	{
		$filename = $this->original_filename;
		
		$this->extension = strtolower(substr(strrchr($filename, '.'), 1));
		if (strrpos($filename, '.') !== FALSE)
		{
			$filename = substr($filename, 0, strrpos($filename, '.'));
		}
		$filename = str_replace('.', '_', $filename);
		$filename = self::clean_filename($filename);

		if ($uniq_name)
		{
			$filename_tmp = $filename;
			if (!empty($this->extension))
				$filename_tmp .= '.' . $this->extension;
			$filename1 = $filename;
			while (file_exists($this->base_directory . $filename_tmp))
			{
				$filename1 = $filename . '_' . KeyGenerator::generate_key(5);
				$filename_tmp = $filename1;
				if (!empty($this->extension))
					$filename_tmp .= '.' . $this->extension;
			}
			$filename = $filename1;
		}

		if (!empty($this->extension))
			$filename .= '.' . $this->extension;
		$this->filename = $filename;
	}
	
	/**
	 * @desc Clean the url, replace special characters with underscore.
	 * @param string The file name.
	 * @return string The cleaned file name.
	 */
	private static function clean_filename($string)
	{
		$string = strtolower(TextHelper::html_entity_decode($string));
		$string = strtr($string, ' àáâãäåçèéêëìíîïðòóôõöùúûüýÿ', '-aaaaaaceeeeiiiioooooouuuuyy');
		$string = preg_replace('`([^a-z0-9-]|[\s])`', '_', $string);
		$string = preg_replace('`[_]{2,}`', '_', $string);
		$string = trim($string, ' _');
		
		return $string;
	}
	
	/**
	 * @desc Manages error code during upload.
	 * @param string $error
	 * @return string Error code.
	 */
	private static function error_manager($error)
	{
		switch ($error)
		{
			//Ok
			case 0:
				return '';
			break;
			//Fichier trop volumineux.
			case 1:
			case 2: 
				return 'e_upload_max_weight';
			break;
			//Upload partiel.
			case 3: 
				return 'e_upload_error';
			break;
			default:
				return 'e_upload_error';
		}
	}
	
	/**
	 * @desc Returns filesize in human readable representation.
	 * @param int $round The number of decimal points
	 * @return float filesize
	 */
	public function get_human_readable_size($round = 1) 
	{ 
		return (float)NumberHelper::round($this->size/1024, $round); 
	}
	
	public function disableContentCheck() {
		$this->contentCheck = false;
	}
	
	public function get_error() { return $this->error; }
	public function get_extension() { return $this->extension; }
	public function get_original_filename() { return $this->original_filename; }
	public function get_filename() { return $this->filename; }
	public function get_size() { return $this->size; }
}
?>