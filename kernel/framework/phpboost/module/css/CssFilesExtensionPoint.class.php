<?php
/*##################################################
 *                           CssFilesExtensionPoint.class.php
 *                            -------------------
 *   begin                : October 06, 2011
 *   copyright            : (C) 2011 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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

interface CssFilesExtensionPoint extends ExtensionPoint
{
	const EXTENSION_POINT = 'css_files';

	/**
	 * @return array css files containing in the templates folder module
	 */
	function get_css_files_always_displayed();
	
	/**
	 * @return array css files containing in the templates folder module
	 */
	function get_css_files_running_module_displayed();
}
?>