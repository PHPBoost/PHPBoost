<?php
/*##################################################
 *                              upload.class.php
 *                            -------------------
 *   begin                : January 27, 2007
 *   copyright          : (C) 2007 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 * 
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

//Constantes de base.
define('DELETE_ON_ERROR', 		true);
define('NO_DELETE_ON_ERROR', 	false);
define('UNIQ_NAME', 			true);
define('CHECK_EXIST', 			true);
define('NO_UNIQ_NAME', 			false);

/**
 * @author Régis VIARRE <crowkait@phpboost.com
 * @desc This class provides you methods to upload easily files to the ftp.
 * @package io
 */
class Upload
{
	## Public Attributes ##
	var $error = ''; //Gestion des erreurs
	
	
	## Public Methods ##	
	/**
	 * @desc constructor
	 * @param string $base_directory Set the directory to upload the files.
	 */
	function Upload($base_directory = 'upload')
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
	function file($filepostname, $regexp = '', $uniq_name = false, $weight_max = 100000000, $check_exist = true)
	{		
		global $LANG;
		
		$file = $_FILES[$filepostname];
		if (!empty($file) && $file['size'] > 0)
		{	
			if (($file['size']/1024) <= $weight_max)
			{
				//Récupération des infos sur le fichier à traiter.
				$this->_generate_file_info($file['name'], $filepostname, $uniq_name);
				if ($this->_check_file($file['name'], $regexp))
				{					
					if (!$check_exist || !file_exists($this->base_directory . $this->filename[$filepostname])) //Autorisation d'écraser le fichier?
					{
						$this->error = $this->_error_manager($file['error']);
						if (empty($this->error))
						{
							if (empty($this->filename[$filepostname]) || empty($file['tmp_name']) || !move_uploaded_file($file['tmp_name'], $this->base_directory . $this->filename[$filepostname]))
								$this->error = 'e_upload_error';
							else
								return true;
						}
					}
					else
						$this->error = 'e_upload_already_exist';
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
	function validate_img($filepath, $width_max, $height_max, $delete = true)
	{
		$error = '';		
		list($width, $height, $ext) = function_exists('getimagesize') ? @getimagesize($filepath) : array(0, 0, 0);
		if ($width > $width_max || $height > $height_max ) //Hauteur et largeur max.
			$error = 'e_upload_max_dimension';

		if (!empty($error) && $delete)
			@unlink($filepath);
			
		return $error;
	}
	
	
	## Private Methods ##	
	/**
	 * @desc Checks the validity of a file with regular expression.
	 * @param string $filename The file name
	 * @param string $regexp Regular expression
	 * @return boolean The result of the regular expression test.
	 */
	function _check_file($filename, $regexp)
	{
		if (!empty($regexp))
		{
			if (preg_match($regexp, $filename) && strpos($filename, '.php') === false) //Valide, sinon supprimé
				return true;
			return false;
		}
		return true;
	}
	
	/**
	 * @desc Clean the url, replace special characters with underscore.
	 * @param string The file name.
	 * @return string The cleaned file name.
	 */
	function _clean_filename($string)
	{
		$string = strtolower($string);
		$string = strtr($string, ' éèêàâùüûïîôç', '-eeeaauuuiioc');
		$string = preg_replace('`([^a-z0-9]|[\s])`', '_', $string);
		$string = preg_replace('`[_]{2,}`', '_', $string);
		$string = trim($string, ' _');
		
		return $string;
	}
	
	/**
	 * @desc Generates a unique file name. Completes informations on the file.
	 * @param string $filename The filename
	 * @param string $filepostname The filename in the input file formular
	 * @param boolean $uniq_name
	 */
	function _generate_file_info($filename, $filepostname, $uniq_name)
	{
		$this->extension[$filepostname] = strtolower(substr(strrchr($filename, '.'), 1));
		if (strrpos($filename, '.') !== FALSE)
		{
			$filename = substr($filename, 0, strrpos($filename, '.'));
		}
		$filename = str_replace('.', '_', $filename);
		$filename = $this->_clean_filename($filename);

		if ($uniq_name)
		{
			$filename_tmp = $filename;
			if (!empty($this->extension[$filepostname]))
				$filename_tmp .= '.' . $this->extension[$filepostname];
			$filename1 = $filename;
			while (file_exists($this->base_directory . $filename_tmp))
			{
				$filename1 = $filename . '_' . substr(strhash(uniqid(mt_rand(), true)), 0, 5);
				$filename_tmp = $filename1;
				if (!empty($this->extension[$filepostname]))
					$filename_tmp .= '.' . $this->extension[$filepostname];
			}
			$filename = $filename1;
		}

		if (!empty($this->extension[$filepostname]))
			$filename .= '.' . $this->extension[$filepostname];
		$this->filename[$filepostname] = $filename;
	}
	
	/**
	 * @desc Manages error code during upload.
	 * @param string $error
	 * @return string Error code.
	 */
	function _error_manager($error)
	{
		switch ($error)
		{
			//Ok
			case 0:
				$error = '';
			break;
			//Fichier trop volumineux.
			case 1:
			case 2: 
				$error = 'e_upload_max_weight';
			break;
			//Upload partiel.
			case 3: 
				$error = 'e_upload_error';
			break;
			default:
				$error = 'e_upload_error';
		}
		return $error;
	}


	## Private Attributes ##
	var $base_directory; //Répertoire de destination des fichiers.
	var $extension = array(); //Extension des fichiers.
	var $filename = array(); //Nom des fichiers.
}

?>