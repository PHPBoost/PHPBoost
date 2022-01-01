<?php
/**
 * Manages the HTTP file upload
 * @package     IO
 * @subpackage  HTTP\upload
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2019 10 24
 * @since       PHPBoost 3.0 - 2010 01 24
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class FileUploadService
{
	/**
	 *
	 * @param string $varname
	 * @return UploadedFile
	 * @throws UploadedFileTooLargeException if the file is too big
	 * @throws Exception if an unknown error occurs
	 */
	public static function retrieve_file($varname)
	{
		$properties = $_FILES[$varname];

		if (!is_array($properties['error']))
		{
			switch($properties['error'])
			{
				case UPLOAD_ERR_OK:
					return self::build_file($properties);
				case UPLOAD_ERR_INI_SIZE:
				case UPLOAD_ERR_FORM_SIZE:
					throw new UploadedFileTooLargeException($varname, $properties['name']);
					break;
				default:
					throw new Exception('The file of the field <i>' . $varname . '</i> of the HTTP request couldn\'t be uploaded');
					break;
			}
		}
		else
		{
			$files = array();
			foreach ($properties['name'] as $id => $element)
			{
				if ($properties['error'][$id] == UPLOAD_ERR_OK)
					$files[] = new UploadedFile($properties['name'][$id], $properties['type'][$id], $properties['size'][$id], $properties['tmp_name'][$id]);
			}
			return $files;
		}
	}

	/**
	 * @return UploadedFile
	 */
	private static function build_file(array $properties)
	{
		return $file = new UploadedFile($properties['name'], $properties['type'], $properties['size'], $properties['tmp_name']);
	}
}
?>
