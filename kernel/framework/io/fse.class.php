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

define('OPEN_NOW', true);
define('OPEN_AFTER', false);

// FileSystemElement
class FileSystemElement
{
	## Public Attributes ##
	var $path;
	var $is_open;
	
	## Public Methods ##
	// Constructeur
	function FileSystemElement($path)
	{
		$this->path = $path;
		$this->is_open = false;
	}
	
	// initialisation avant l'ouverture
	function open()
	{
		if ($this->is_open)
			return;
		
		$this->is_open = true;
	}
	
	// initialisation avant l'accès à un attribut de l'objet
	function get()
	{
		if (!$this->is_open)
			$this->open();
	}
	
	// après une écriture il faut forcer la relecture pour mettre à jour les attributs
	function write()
	{
		$this->is_open = false;
		$this->open();
	}
	
	// renvoie le nom de l'élément
	function get_name($full_path = false, $no_extension = false)
	{
	    if ($full_path)
	       return $this->path;
	    
		$path = trim($this->path, '/');
		$parts = explode('/', $path);
		$name =$parts[count($parts) - 1];
		
		if ($no_extension)
		    return substr($name, 0, strrpos($name, '.'));
		
		return $name;
	}
	
	//Change le CHMOD de l'élément du système de fichiers
	function change_chmod($chmod)
	{
		if (!empty($this->path))
			@chmod($this->path, $chmod);
	}
	
	// fonction virtuelle
	function delete() { }
}

?>
