<?php
/**
 * Manages the HTTP file upload
 * @package     IO
 * @subpackage  HTTP\upload
 * @category    Framework
 * @copyright   &copy; 2005-2019 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.2 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 01 24
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

	/**
	 * @return UploadedFile
	 */
	private static function build_file(array $properties)
	{
		return $file = new UploadedFile($properties['name'], $properties['type'], $properties['size'], $properties['tmp_name']);
	}
}
?>
