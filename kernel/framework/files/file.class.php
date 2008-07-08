<?php
/*##################################################
 *                             file.class.php
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

// fonction de gestion des fichiers
class File extends FileSystemElement
{
	## Public Attributes ##
	var $lines = array();
	var $contents;
	
	## Public Methods ##	
	// Constructeur
	function File($path, $opennow = false)
	{
		parent::init($path);
		
		if( @file_exists($this->path) )
		{
			if( !@is_file($this->path) )
				return false;
			
			if( $opennow )
				$this->open();
		}
		else if( !@touch($this->path) )
			return false;
			
		return true;
	}
	
	// lit le fichier et initialise les attributs
	function open()
	{
		parent::open();
		
		$this->lines[] = file($path);
		$this->contents = implode("\n", $this->lines);
	}
	
	// renvoie le contenu du fichier en commençant à l'octet $start
	function get_contents($start = 0, $len = -1)
	{
		parent::get();
		
		if( !$start && $len == -1 )
			return $this->contents;
		else if( $len == -1 )
			return substr($this->contents, $start);
		else
			return substr($this->contents, $start, $len);
	}
	
	// renvoie le contenu du fichier sous forme de tableau
	function get_lines($start = 0, $n = -1)
	{
		parent::get();
		
		if( !$start && $n == -1 )
			return $this->lines;
		else if( $n == -1 )
			return array_slice($this->lines, $start);
		else
			return array_slice($this->lines, $start, $n);
	}
	
	// écrit $data dans le fichier, soit en écrasant les données ( par défaut ), soit en ajoutant à la suite ( envoyer true en deuxième paramètre )
	function write($data, $add = false)
	{
		$mode = $add ? 'a' : 'w';
		
		if( !($fp = @fopen($file, $mode)) )
			return false;
		
		fwrite($data, $fp);
		fclose($fp);
		
		parent::write();
		
		return true;
	}
	
	// supprime le fichier
	function delete()
	{
		@unlink($this->path);
	}
	
	## Private Methods ##	
}

?>