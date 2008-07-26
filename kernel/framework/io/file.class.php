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

require_once(PATH_TO_ROOT . '/kernel/framework/io/fse.class.php');

define('ERASE', false);
define('ADD', true);

define('READ_WRITE', 0x3);
define('READ', 0x1);
define('WRITE', 0x2);
// fonction de gestion des fichiers
class File extends FileSystemElement
{
	## Private Attributes ##
	var $lines = array();
	var $contents;
	var $mode;
	
	## Public Methods ##	
	// Constructeur
	function File($path, $mode = READ_WRITE, $whenopen = OPEN_AFTER)
	{
		parent::FileSystemElement($path);
		
		$this->mode = $mode;
		
		if( @file_exists($this->path) )
		{
			if( !@is_file($this->path) )
				return false;
			
			if( $whenopen == OPEN_NOW )
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
		
		if($this->mode & READ)
		{
			$this->contents = file_get_contents($this->path);
			$this->lines = explode("\n", $this->contents);
		}
	}
	
	// renvoie le contenu du fichier en commençant à l'octet $start
	function get_contents($start = 0, $len = -1)
	{
		if($this->mode & READ)
		{
			parent::get();
			
			if( !$start && $len == -1 )
				return $this->contents;
			else if( $len == -1 )
				return substr($this->contents, $start);
			else
				return substr($this->contents, $start, $len);
		}
		else
			user_error('File '.$this->path.' is open in read only');
	}
	
	// renvoie le contenu du fichier sous forme de tableau
	function get_lines($start = 0, $n = -1)
	{
		if($this->mode & READ)
		{
			parent::get();
			
			if( !$start && $n == -1 )
				return $this->lines;
			else if( $n == -1 )
				return array_slice($this->lines, $start);
			else
				return array_slice($this->lines, $start, $n);
		}
		else
			user_error('File '.$this->path.' is open in read only');
	}
	
	// écrit $data dans le fichier, soit en écrasant les données ( par défaut ), soit passant en troisième paramètre la constante ADD
	function write($data, $what = ERASE)
	{
		if($this->mode & WRITE)
		{	
			if( !($fp = @fopen($this->path, ( $what == ADD ) ? 'a' : 'w')) )
				return false;
			
			$bytes_to_write = strlen($data);
			$bytes_written = 0;
			while ( $bytes_written < $bytes_to_write )
			{
				// on écrit par bloc de 4Ko
				$bytes = fwrite($fp, substr($data, $bytes_written, 4096));

				if ( $bytes === false || $bytes == 0 )
					break;

				$bytes_written += $bytes;
				
				// on fait une petit pause pour ne pas consommer toute la CPU d'un centième de seconde
				usleep(100);
			}
			
			fclose($fp);
			
			parent::write();
			
			return ( $bytes_written == $bytes_to_write ) ? true : false;
		}
		else
			user_error('File '.$this->path.' is open in read only');
	}
	
	// supprime le fichier
	function delete()
	{
		if( !@unlink($this->path) ) // Empty the file if it couldn't delete it
            $this->write("");
	}
	
	## Private Methods ##	
}

?>