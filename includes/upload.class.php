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
define('DELETE_ON_ERROR', true);
define('NO_DELETE_ON_ERROR', false);
define('UNIQ_NAME', true);
define('NO_CHECK_EXIST', false);
define('CHECK_EXIST', true);
define('NO_UNIQ_NAME', false);

class Upload
{
	var $base_directory; //Répertoire de destination des fichiers.
	var $extension = array(); //Extension des fichiers.
	var $filename = array(); //Nom des fichiers.
	var $error = ''; //Gestion des erreurs
	
	//Constructeur
	function Upload($base_directory = 'upload')
	{
		$this->base_directory = $base_directory;
		return;
	}

	//Récupère les informations du fichier à uploader.
	function get_info_file($filepostname, $filename, $uniq_name)
	{		
		$this->extension[$filepostname] = strtolower(substr(strrchr($filename, '.'), 1));
		$this->filename[$filepostname] = !empty($uniq_name) ? $uniq_name . '.' . $this->extension[$filepostname] : $filename;	
		
		return;
	}
	
	//Vérification de la conformité du fichier
	function check_file($filename, $regexp)
	{
		if( !empty($regexp) )
		{
			if( preg_match($regexp, $filename) && !preg_match('`\.php`', $filename) ) //Valide, sinon supprimé
				return true;
			return false;
		}
		return true;
	}
	
	//Upload d'un fichier
	function upload_file($filepostname, $regexp = '', $uniq_name = false, $check_exist = true, $weight_max = 100000000)
	{		
		global $LANG;
		
		$file = $_FILES[$filepostname];
		if( !empty($file) && $file['size'] > 0 )
		{	
			if( ($file['size']/1024) <= $weight_max )
			{
				//Récupération des infos sur le fichier à traiter.
				$gen_uniq_name = ($uniq_name) ? md5(uniqid(mt_rand(), true)) : '';
				$this->get_info_file($filepostname, $file['name'], $gen_uniq_name);			
				if( $this->check_file($file['name'], $regexp) )
				{					
					if( !$check_exist || !is_file($this->base_directory . $this->filename[$filepostname]) ) //Autorisation d'écraser le fichier?
					{
						$this->error = $this->error_upload_manager($file['error']);
						if( empty($this->error) )
						{
							if( empty($this->filename[$filepostname]) || empty($file['tmp_name']) || !move_uploaded_file($file['tmp_name'], $this->base_directory . $this->filename[$filepostname]) )
								$this->error = 'e_upload_error';
							else
								return true;
						}
					}
					else
						$error = 'e_upload_already_exist';
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
	  
	//Upload de tout les fichiers.
	function upload_files($regexp = '')
	{	
		foreach($_FILES as $file => $array )
			$this->upload_file($file['name'], '', $regexp);
		
		return;
	}
	
	//Gestion des erreurs d'upload.
	function error_upload_manager($error)
	{
		switch($error)
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
	
	//Validation des images, supprime l'image en cas d'erreur sir $delete à true.
	function validate_img($filepath, $width_max, $height_max, $delete = true)
	{
		$error = '';		
		list($width, $height, $ext) = function_exists('getimagesize') ? @getimagesize($filepath) : array(0, 0, 0);
		if( $width > $width_max || $height > $height_max  ) //Hauteur et largeur max.
			$error = 'e_upload_max_dimension';

		if( !empty($error) && $delete )
			@unlink($filepath);
			
		return $error;
	}
}

?>