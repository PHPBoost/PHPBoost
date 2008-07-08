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
class FileSystemElement
{
	## Public Attributes ##
	var $path;
	var $is_open;
	
	## Public Methods ##	
	// c'est un peu le constructeur de cette classe mais comme php n'appelle pas automatique les classes parent on doit le faire explicitement avec parent::init()
	function init($path)
	{
		$this->path = $path;
		$this->is_open = false;
	}
	
	// initialisation avant l'ouverture
	function open()
	{
		if($this->is_open)
			return;
		
		$this->is_open = true;
	}
	
	// initialisation avant l'accès à un attribut de l'objet
	function get()
	{
		if(!$this->is_open)
			$this->open();
	}
	
	// après une écriture il faut forcer la relecture pour mettre à jour les attributs
	function write()
	{
		$this->is_open = false;
		$this->open();
	}
	
	// fonction virtuelle
	function delete() { }
}

?>
