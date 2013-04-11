<?php
/*##################################################
 *                        FileUploadService.class.php
 *                            -------------------
 *   begin                : January 24 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 * @desc Manages the HTTP file upload
 * @package {@package}
 */
class FileUploadService
{
	/**
	 * @desc
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