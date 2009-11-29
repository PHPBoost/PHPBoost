<?php
/*##################################################
 *                        phpboost_unit_tests.inc.php
 *                            -------------------
 *   begin                : November 29, 2009
 *   copyright            : (C) 2009 Rouchon Lo�c
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

function list_tu($directory, $recursive = false) {
	$files = array();
	$folder = new Folder($directory);
	foreach ($folder->get_files('`^(?:(?:UT)|(?:ut_)).+\.class\.php$`') as $file) {
		$files[] = preg_replace('`^[\./]*kernel/framework/`', '', $file->get_name(true));
	}

	if ($recursive) {
		foreach ($folder->get_folders() as $folder) {
			$files = array_merge($files, list_tu($folder->get_name(true), true));
		}
	}
	return $files;
}

?>