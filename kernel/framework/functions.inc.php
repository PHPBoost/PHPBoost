<?php
/*##################################################
 *                             functions.inc.php
 *                            -------------------
 *   begin                : June 13, 2005
 *   copyright            : (C) 2005 Régis Viarre, Loic Rouchon
 *   email                : crowkait@phpboost.com, loic.rouchon@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/


/**
 * @desc Return a SHA256 hash of the $str string [with a salt]
 * @param string $str the string to hash
 * @param mixed $salt If true, add the default salt : md5($str)
 * if a string, use this string as the salt
 * if false, do not use any salt
 * @return string a SHA256 hash of the $str string [with a salt]
 */
function strhash($str, $salt = true)
{
	if ($salt === true)
	{   // Default salt
		$str = md5($str) . $str;
	}
	elseif ($salt !== false)
	{   // Specific salt
		$str = $salt . $str;
	}
    return hash('sha256', $str);
}

/**
 * @desc Emulates the PHP file_get_contents_emulate.
 * @param string $filename File to read.
 * @param $incpath See the PHP documentation
 * @param $resource_context See the PHP documentation
 * @return string The file contents.
 */
function file_get_contents_emulate($filename, $incpath = false, $resource_context = null)
{
	if (false === ($fh = @fopen($filename, 'rb', $incpath)))
	{
		user_error('file_get_contents_emulate(\'' . $filename . '\')' .
			'failed to open stream: No such file or directory', E_USER_WARNING);
		return false;
	}

	clearstatcache();
	if ($fsize = @filesize($filename))
	{
		$data = fread($fh, $fsize);
	}
	else
	{
		$data = '';
		while (!feof($fh))
		{
			$data .= fread($fh, 8192);
		}
	}
	fclose($fh);
	return $data;
}

define('CLASS_IMPORT', '.class.php');
define('INC_IMPORT', '.inc.php');
define('LIB_IMPORT', '.lib.php');
define('PHP_IMPORT', '.php');
define('PLAIN_IMPORT', '');

/**
 * @desc Imports a class or a lib from the framework or from the root
 * @param string $path Path of the file to load without .class.php or .inc.php extension (for instance util/date)
 * if this path begins with "/", it won't be searched from the kernel framework but from
 * PHPBoost root
 * @param string $import_type the import type. Default is CLASS_IMPORT,
 * but you could also import a library by using LIB_IMPORT (file whose extension is .inc.php)
 * or INC_IMPORT to include a .inc.php file (for example the current file, functions.inc.php).
 */
function import($path, $import_type = CLASS_IMPORT)
{
	if (substr($path, 0, 1) !== '/')
	{
		$path = '/kernel/framework/' . $path;
	}
	if (!include_once(PATH_TO_ROOT . $path . $import_type))
	{
		Debug::fatal(new Exception('Can\'t load file ' . PATH_TO_ROOT . $path . $import_type));
	}
}

/**
 * @desc Requires a file
 * @param string $file the file to require with an absolute path from the website root
 * @param bool $once if false use require instead of require_once
 * @throws IOException Wether the file cannot be included because it doesn't exist
 */
function req($file, $once = true)
{
	$file = '/' . ltrim($file, '/');
	if (!file_exists($file))
	{
		throw new IOException('File to include does\'nt exist: ' . $file);
	}
	if ($once)
	{
		require_once PATH_TO_ROOT . $file ;
	}
	else
	{
		require PATH_TO_ROOT . $file ;
	}
}


/**
 * @desc Includes a file
 * @param string $file the file to include with an absolute path from the website root
 * @param bool $once if false use include instead of include_once
 * @return bool true if the file has been included with success else, false
 */
function inc($file, $once = true)
{
	$file = '/' . ltrim($file, '/');
	if (!file_exists($file))
	{
		return false;
	}
	if ($once)
	{
		return (include_once(PATH_TO_ROOT . $file)) !== false;
	}
	else
	{
		return (include(PATH_TO_ROOT . $file)) !== false;
	}
}

?>