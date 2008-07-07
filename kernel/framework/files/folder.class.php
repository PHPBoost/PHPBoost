<?php
/*##################################################
 *                             folder.class.php
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

require_once(PATH_TO_ROOT . 'kernel/framework/file/fse.class.php');
require_once(PATH_TO_ROOT . 'kernel/framework/file/file.class.php');

// gestion des dossiers
class Folder extends FileSystemElement
{
	var $files = array();
	var $folders = array();
	
	//constructeur
	function Folder($path, $readnow = false)
	{
		parent::init($path);
		
		if( @file_exists($this->path) )
		{
			if( !@is_dir($this->path) )
				return false;
			
			if($readnow)
				$this->readfolder();
		}
		else if( !@mkdir($this->path) )
			return false;
			
		return true;
	}
	
	// fonction privée qui lit le dossier
	/* private */ 
	function readfolder()
	{
		if( !$this->is_read )
		{
			$dir = $files = array();
			if( $dh = @opendir($this->path) )
	        {       
	            while( !is_bool($res = readdir($dh)) )
	            {
					if( $res == '.' || $res == '..' )
						continue;
					
					if( is_file($res) )
						$this->files[] = new File($res);
	                else
						$this->folders[] = new Folder($res);
	            }
	            closedir($dh);
	        }
			
			$this->is_read = true;
		}
	}
	
	// retourne la liste de tout les fichiers correspondant au motif $regex
	function get_files($regex = '')
	{
		// lit le dossier si ça n'a pas encore été fait
		$this->readfolder();
		
		if( empty($regex) )
			return $this->files;
		else
		{
			$ret = array();
			foreach( $this->files as $file )
				if( preg_match($regex, $file) )
					$ret[] = $file;
			return $ret;
		}
	}
	
	// retourne la liste de tout les dossiers correspondant au motif $regex
	function get_folders($regex = '')
	{
		// lit le dossier si ça n'a pas encore été fait
		$this->readfolder();
		
		if( empty($regex) )
			return $this->folders;
		else
		{
			$ret = array();
			foreach( $this->folders as $folder )
				if( preg_match($regex, $folder) )
					$ret[] = $folder;
			return $ret;
		}
	}
	
	// retourne le premier dossier ou false si il n'y en a pas
	function get_first_folder()
	{
		// lit le dossier si ça n'a pas encore été fait
		$this->readfolder();
		
		if( isset($folders[0]) )
			return $folders[0];
		else
			return false;
	}
}

?>