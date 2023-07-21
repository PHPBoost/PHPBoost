<?php
/**
 * This class provides you methods to upload easily files to the ftp.
 * @package     IO
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 07 12
 * @since       PHPBoost 1.6 - 2007 01 27
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor janus57 <janus57@janus57.fr>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
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
	private $isMultiple = false;
	private $files_parameters = array();

	const UNIQ_NAME = true;
	const NO_UNIQ_NAME = false;
	const CHECK_EXIST = true;
	const DELETE_ON_ERROR = true;
	const NO_DELETE_ON_ERROR = false;

	/**
	 * constructor
	 * @param string $base_directory Set the directory to upload the files.
	 */
	public function __construct($base_directory = 'upload')
	{
		$this->base_directory = $base_directory;
	}

	/**
	 * Uploads a file.
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
		if (isset($file['name']) && isset($file['name'][0]) && !empty($file['name'][0]))
		{
			$this->isMultiple = is_array($file['name']);
			$files_number = $this->isMultiple ? count(array_keys($file['name'])) : 1;
			
			if ($this->isMultiple)
			{
				for ($i = 1 ; $i <= $files_number ; $i++)
				{
					$this->original_filename = $file['name'][$i - 1];
					if (!$this->check_file_path($regexp))
					{
						$this->error = 'e_upload_invalid_format';
						return false;
					}
				}
			}
			
			for ($i = 1 ; $i <= $files_number ; $i++)
			{
				$this->size = $this->isMultiple ? $file['size'][$i - 1] : $file['size'];
				$total_size = $this->isMultiple ? array_sum($file['size']) : $file['size'];
				$this->original_filename = $this->isMultiple ? $file['name'][$i - 1] : $file['name'];
				
				if ($this->size > 0)
				{
					if (($total_size/1024) <= ($files_number * $weight_max))
					{
						//Récupération des infos sur le fichier à traiter.
						$this->generate_file_info($uniq_name);
						if ($this->check_file_path($regexp))
						{
							if (!$check_exist || !file_exists($this->base_directory . $this->filename)) //Autorisation d'écraser le fichier?
							{
								$this->error = self::error_manager($this->isMultiple ? $file['error'][$i - 1] : $file['error']);
								if (empty($this->error))
								{
									$tmp_name = $this->isMultiple ? $file['tmp_name'][$i - 1] : $file['tmp_name'];
									if (!empty($this->filename) && !empty($tmp_name))
									{
										if ($this->contentCheck && !$this->check_file_content($tmp_name))
										{
											$this->error = 'e_upload_php_code';
											return false;
										}
										
										if (($this->size/1024) <= $weight_max)
										{
											if (!move_uploaded_file($tmp_name, $this->base_directory . $this->filename))
											{
												$this->error = 'e_upload_error';
											}
											else
											{
												$this->files_parameters[] = array(
													'name'      => $this->original_filename, 
													'path'      => $this->filename,
													'size'      => $this->get_human_readable_size(),
													'extension' => $this->extension
												);
											}
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
			}
		}
		else
			$this->error = 'e_upload_no_selected_file';
		
		return $this->error ? false : true;
	}

	/**
	 * Checks whether an image is compliant to an maximum width and height, otherwise is $delete value is true delete it.
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

		if (TextHelper::strpos($file_content, "<?php") === false) {
			return true;
		}
		if (TextHelper::strpos($file_content, "<?") === false) {
			return true;
		}

		//Fail to validate, remove tmp file
		@unlink($file);
		return false;
	}

	/**
	 * Checks the validity of a file with regular expression.
	 * @param string $filename The file name
	 * @param string $regexp Regular expression
	 * @return boolean The result of the regular expression test.
	 */
	private function check_file_path($regexp)
	{
		if (!empty($regexp))
		{
			//Valide, sinon supprimé
			if (preg_match($regexp, $this->original_filename) && TextHelper::strpos($this->original_filename, '.php') === false && TextHelper::strpos($this->original_filename, '.phtml') === false) {//Valide, sinon supprimé
				return true;
			}
			return false;
		}
		return true;
	}

	/**
	 * Generates a unique file name. Completes informations on the file.
	 * @param string $filename The filename
	 * @param boolean $uniq_name
	 */
	private function generate_file_info($uniq_name)
	{
		$filename = $this->original_filename;

		$this->extension = TextHelper::strtolower(TextHelper::substr(TextHelper::strrchr($filename, '.'), 1));
		if (TextHelper::strrpos($filename, '.') !== FALSE)
		{
			$filename = TextHelper::mb_substr($filename, 0, TextHelper::strrpos($filename, '.'));
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
	 * Clean the url, replace special characters with underscore.
	 * @param string The file name.
	 * @return string The cleaned file name.
	 */
	private static function clean_filename($string)
	{
		$string = mb_convert_encoding(TextHelper::html_entity_decode($string), 'ISO-8859-1', 'UTF-8');
		$string = TextHelper::strtolower(strtr($string, mb_convert_encoding('²ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ()[]\'"~$&%*@ç!?;,:/\^¨€{}<>|+.- #', 'ISO-8859-1', 'UTF-8'),  '2aaaaaaaaaaaaooooooooooooeeeeeeeecciiiiiiiiuuuuuuuuynn    __      c  ___    e       -_ '));
		$string = str_replace(' ', '', $string);
		$string = str_replace('___', '_', $string);
		$string = str_replace('__', '_', $string);
		$string = trim($string,'_');

		return $string;
	}

	/**
	 * Manages error code during upload.
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
	 * Returns filesize in human readable representation.
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
	public function get_files_parameters() { return $this->files_parameters; }
}
?>
