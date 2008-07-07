<?php
/*##################################################
 *                             fse.class.php
 *                            -------------------
 *   begin                : July 06, 2008
 *   copyright          : (C) 2008 Nicolas Duhamel
 *   email                : akhenathon2@gmail.com
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

// FileSystemElement
class FSE
{
	var $path;
	var $is_read = false;
	
	// initialise le path
	function init($path)
	{
		$this->path = $path;
	}
	
	// fonction générique qui ouvre soit le fichier soit le dossier en appellant les fonctions spécifiques
	function open()
	{
		global $Errorh;
		
		if( get_class($this) == 'File' )
			$this->readfile();
		else if( get_class($this) == 'Folder' )
			$this->readfolder();
		else
			user_error('Only class File or Folder must be inherit FileSystemElement', E_USER_ERROR);
	}
	
	// qui permet de supprimer fichier et dossier ( récursivement si il s'agit d'un dossier )
	function del()
	{
		global $Errorh;
		
		if( get_class($this) == 'File' )
			unlink($this->path);
		else if( get_class($this) == 'Folder' )
		{
			if(!$this->is_read)
				$this->readfolder();
			
			foreach($this->get_files as $file)
				unlink($file->path);
				
			foreach($this->get_folders as $folder)
				$folder->del();
			
			rmdir($this->path);
		}
		else
			user_error('Only class File or Folder must be inherit FileSystemElement', E_USER_ERROR);
	}
}

?>