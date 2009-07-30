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

import('io/filesystem/file_system_element');
import('io/filesystem/file');

/**
 * @package io
 * @subpackage filesystem
 * @author Benoît Sautel <ben.popeye@phpboost.com> Nicolas Duhamel <akhenathon2@gmail.com>
 * @desc This class allows you to handle very easily a folder on the serveur.
 */
class Folder extends FileSystemElement
{
    /**
     * @desc Builds a Folder object.
     * @param string $path Path of the folder.
     * @param bool $whenopen OPEN_AFTER if you want to synchronyse you with the folder only when it's necessary or OPEN_NOW if you want to open it now.
     */
    function Folder($path, $whenopen = OPEN_AFTER)
    {
        parent::FileSystemElement(rtrim($path, '/'));

        if (@file_exists($this->path))
        {
            if (!@is_dir($this->path))
            {
                return false;
            }
            	
            if ($whenopen == OPEN_NOW)
            {
                $this->open();
            }
        }
        else if (!@mkdir($this->path))
        {
            return false;
        }
        	
        return true;
    }

    /**
     * @desc Opens the folder.
     */
    function open()
    {
        parent::open();

        $this->files = $this->folders = array();
        if ($dh = @opendir($this->path))
        {
            while (!is_bool($fse_name = readdir($dh)))
            {
                if ($fse_name == '.' || $fse_name == '..')
                {
                    continue;
                }
                	
                if (is_file($this->path . '/' . $fse_name))
                {
                    $this->files[] = new File($this->path . '/' . $fse_name);
                }
                else
                {
                    $this->folders[] = new Folder($this->path . '/' . $fse_name);
                }
            }
            closedir($dh);
        }
    }

    /**
     * @desc Lists the files contained in this folder.
     * @param string $regex PREG which describes the pattern the files you want to list must match. If you want all of them, don't use this parameter.
     * @return File[] The files list.
     */
    function get_files($regex = '')
    {
        parent::get();

        $ret = array();
        if (empty($regex))
        {
            foreach ($this->files as $file)
            {
                $ret[] = $file;
            }
        }
        else
        {
            foreach ($this->files as $file)
            {
                if (preg_match($regex, $file->get_name()))
                {
                    $ret[] = $file;
                }
            }
        }
        return $ret;
    }

    /**
     * @desc Lists the folders contained in this folder.
     * @param string $regex PREG which describes the pattern the folders you want to list must match. If you want all of them, don't use this parameter.
     * @return Folder[] The folders list.
     */
    function get_folders($regex = '')
    {
        parent::get();
        if (empty($regex))
        {
            $ret = array();
            foreach ($this->folders as $folder)
            {
                $ret[] = $folder;
            }
            return $ret;
        }
        else
        {
            $ret = array();
            foreach ($this->folders as $folder)
            {
                if (preg_match($regex, $folder->get_name()))
                {
                    $ret[] = $folder;
                }
            }
            return $ret;
        }
    }

    /**
     * @desc Returns the first folder present in this folder
     * @return Folder The first folder of this folder or null if it doesn't contain any folder.
     */
    function get_first_folder()
    {
        parent::get();

        if (isset($this->folders[0]))
        {
            return $this->folders[0];
        }
        else
        {
            return null;
        }
    }

    /**
     * @desc Returns all the file system elements contained by the folder.
     * @return FileSystemElement[] The list of the file system element contained in this folder.
     */
    function get_all_content()
    {
        return array_merge($this->get_files(), $this->get_folders());
    }

    /**
     * @desc Deletes the folder and all what it contains.
     * @return True if deleted successfully.
     */
    function delete()
    {
        $this->open();

        $fs = array_merge($this->files, $this->folders);

        foreach ($fs as $fse)
        {
            $fse->delete();
        }
        	
        if (!@rmdir($this->path))
        {
        	return false;
        }
        return true;
    }

    ## Private Attributes ##
    /**
     * @var File[] List of the files contained by this folder.
     */
    var $files = array();

    /**
     * @var Folder[] List of the folders contained by this folder.
     */
    var $folders = array();
}

?>