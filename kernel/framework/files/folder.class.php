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
	## Public Attributes ##
	var $files = array();
	var $folders = array();
	
	## Public Methods ##	
	//Constructeur
	function Folder($path, $readnow = false)
	{
		parent::FileSystemElement($path);
		
		if( @file_exists($this->path) )
		{
			if( !@is_dir($this->path) )
				return false;
			
			if( $readnow )
				$this->open();
		}
		else if( !@mkdir($this->path) )
			return false;
			
		return true;
	}
	
	// ouvre le dossier et initialise les objets pour un parcours en profondeur ultérieur
	function open()
	{
		parent::open();
		
		$this->files = $this->folders = array();
		if( $dh = @opendir($this->path) )
	    {       
	        while( !is_bool($fse_name = readdir($dh)) )
	        {
				if( $fse_name == '.' || $fse_name == '..' )
					continue;
					
				if( is_file($res) )
					$this->files[] = new File($fse_name);
	            else
					$this->folders[] = new Folder($fse_name);
	        }
	        closedir($dh);
	    }
	}
	// retourne la liste de tout les fichiers correspondant au motif $regex
	function get_files($regex = '')
	{
		parent::get();
		
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
		parent::get();
		
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
		parent::get();
		
		if( isset($folders[0]) )
			return $folders[0];
		else
			return false;
	}
	
	// supprime le dossier récursivement
	function delete()
	{
		$fs = array_merge($files, $folders);
		
		foreach($fs as $fse)
			$fse->delete();
			
		rmdir($this->path);
	}
	## Private Methods ##	
}

?>