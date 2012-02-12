<?php
/*##################################################
 *                      file_system_element.class.php
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

define('OPEN_NOW', true);
define('OPEN_AFTER', false);

/**
 * @package io
 * @subpackage filesystem 
 * @abstract
 * @author Benoît Sautel <ben.popeye@phpboost.com> Nicolas Duhamel <akhenathon2@gmail.com>
 * @desc This class represents any file system element.
 */
class FileSystemElement
{
    /**
     * @desc Builds a FileSystemElement object from the path of the element.
     * @param string $path Path of the element
     */
    function FileSystemElement($path)
    {
        $this->path = $path;
        $this->is_open = false;
    }

    /**
     * @desc Allows you to know if the file system element exists.
     * @return bool True if the file exists, else, false.
     */
    function exists()
    {
        return file_exists($this->path);
    }

    /**
     * @desc Opens the file system element.
     */
    function open()
    {
        if ($this->is_open)
        {
            return;
        }

        $this->is_open = true;
    }

    /**
     * @desc Initializes the file system element just before to be read.
     */
    function get()
    {
        if (!$this->is_open)
        {
            $this->open();
        }
    }

    /**
     * @abstract
     * @desc Does the necessary treatment to apply at each writing operation
     * @return unknown_type
     */
    function write()
    {
        $this->is_open = false;
        $this->open();
    }

    /**
     * @desc Returns the element name.
     * @param bool $full_path True if you want the full path or false if you just want a relative path.
     * @param bool $no_extension False if you want the name of the file with the extension and true without.
     * @return string The element name.
     */
    function get_name($full_path = false, $no_extension = false)
    {
        if ($full_path)
        {
            return $this->path;
        }
         
        $path = trim($this->path, '/');
        $parts = explode('/', $path);
        $name =$parts[count($parts) - 1];

        if ($no_extension)
        {
            return substr($name, 0, strrpos($name, '.'));
        }

        return $name;
    }

    /**
     * @desc Changes the chmod of the element.
     * @param int $chmod The new chmod of the file. Put a 0 at the begening of the number to indicate to the PHP parser that it's an octal value.
     */
    function change_chmod($chmod)
    {
        if (!empty($this->path))
        {
            @chmod($this->path, $chmod);
        }
    }

    /**
     * @abstract
     * @desc Deletes the element
     */
    function delete() { }

    ## Public Attributes ##
    /**
     * @var string Path of the file system element
     */
    var $path;

    /**
     * @var bool Indicates whether the file system element is open or closed.
     */
    var $is_open;

}

?>
