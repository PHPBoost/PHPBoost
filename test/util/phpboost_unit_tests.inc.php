<?php
/*##################################################
 *                        phpboost_unit_tests.inc.php
 *                            -------------------
 *   begin                : November 29, 2009
 *   copyright            : (C) 2009 Rouchon Loic
 *   email                : loic.rouchon@phpboost.com
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

function list_tu($directory)
{
	$tus = list_tu_recursive($directory, true);
	sort($tus);
	return $tus;
}

function list_tu_recursive($directory, $recursive = false)
{
	$files = array();
	$folder = new Folder($directory);
	foreach ($folder->get_files('`^.+Test\.php$`') as $file)
	{
		$files[] = preg_replace('`^[\./]*kernel/`', '', $file->get_path());
	}

	if ($recursive)
	{
		foreach ($folder->get_folders() as $folder)
		{
			$files = array_merge($files, list_tu_recursive($folder->get_path(), true));
		}
	}
	return $files;
}

function list_test_suite($directory)
{
	$directory_name = preg_replace('`^[\./]*kernel`', '', $directory);
	$directories = list_test_suite_recursive($directory, true);
	$directories[] = '/' . trim($directory_name, '/');
	sort($directories);
	return $directories;
}

function list_test_suite_recursive($directory, $recursive = false)
{
	$folders = array();
	$folder = new Folder($directory);
	foreach ($folder->get_folders('`^[^.].+$`') as $folder)
	{
		$folders[] = preg_replace('`^[\./]*kernel`', '', $folder->get_path());
		if ($recursive)
		{
			$folders = array_merge($folders, list_test_suite_recursive($folder->get_path(), true));
		}
	}
	return $folders;
}

?>