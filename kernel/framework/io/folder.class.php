<?php
/*##################################################
 *                              folder.class.php
 *                            -------------------
 *   begin                : July 06, 2008
 *   copyright            : (C) 2008 Nicolas Duhamel
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

import('io/fse');
import('io/file');

//Dossier
class Folder extends FileSystemElement
{
	## Private Attributes ##
	var $files = array();
	var $folders = array();
	
	## Public Methods ##	
	//Constructeur
	function Folder($path, $whenopen = OPEN_AFTER)
	{
		parent::FileSystemElement(rtrim($path, '/'));
		
		if (@file_exists($this->path))
		{
			if (!@is_dir($this->path))
				return false;
			
			if ($whenopen == OPEN_NOW)
				$this->open();
		}
		else if (!@mkdir($this->path))
			return false;
			
		return true;
	}
	
	// ouvre le dossier et initialise les objets pour un parcours en profondeur ultérieur
	function open()
	{
		parent::open();
		
		$this->files = $this->folders = array();
		if ($dh = @opendir($this->path))
	    {       
	        while (!is_bool($fse_name = readdir($dh)))
	        {
				if ($fse_name == '.' || $fse_name == '..')
					continue;
					
				if (is_file($this->path . '/' . $fse_name))
					$this->files[] = new File($this->path . '/' . $fse_name);
	            else
					$this->folders[] = new Folder($this->path . '/' . $fse_name);
	        }
	        closedir($dh);
	    }
	}
	// retourne la liste de tout les fichiers correspondant au motif $regex
	function get_files($regex = '')
	{
		parent::get();
		
		if (empty($regex))
		{
			$ret = array();
			foreach ($this->files as $file)
				$ret[] = $file;
			return $ret;
		}
		else
		{
			$ret = array();
			foreach ($this->files as $file)
				if (preg_match($regex, $file->path))
					$ret[] = $file;
			return $ret;
		}
	}
	
	// retourne la liste de tout les dossiers correspondant au motif $regex
	function get_folders($regex = '')
	{
		parent::get();
		
		if (empty($regex))
		{
			$ret = array();
			foreach ($this->folders as $folder)
				$ret[] = $folder;
			return $ret;
		}
		else
		{
			$ret = array();
			foreach ($this->folders as $folder)
				if (preg_match($regex, $folder->path))
					$ret[] = $folder;
			return $ret;
		}
	}
	
	// retourne le premier dossier ou false si il n'y en a pas
	function get_first_folder()
	{
		parent::get();
		
		if (isset($this->folders[0]))
			return $this->folders[0];
		else
			return false;
	}
	
	// Renvoie le contenu du dossier
	function get_all_content()
	{
		return array_merge($this->get_files(), $this->get_folders());
	}
	
	// supprime le dossier récursivement
	function delete()
	{
		$this->open();
		
		$fs = array_merge($this->files, $this->folders);
		
		foreach ($fs as $fse)
			$fse->delete();
			
		rmdir($this->path);
	}
	## Private Methods ##	
}

?>