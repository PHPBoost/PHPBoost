<?php
/*##################################################
 *                                sitemapexportconfig.class.php
 *                            -------------------
 *   begin                : June 16 th 2008
 *   copyright          : (C) 2008 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
 *
 *   Site_map_export_config
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
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

class SitemapExportConfig
{
	##  Public methods  ##
	function SitemapExportConfig($module_map_file, $section_file, $link_file)
	{
		//If we receive a string it's the path of the template, otherwise it's already the Template object
		$this->module_map_file = is_string($module_map_file) ? new Template($module_map_file) : $module_map_file;
		$this->section_file = is_string($section_file) ? new Template($section_file) : $section_file;
		$this->link_file = is_string($link_file) ? new Template($link_file) : $link_file;
	}
	
	//Method which returns a module map stream
	function get_module_map_stream()
	{
		return $this->module_map_file->copy();
	}
	
	//Method which returns a module section stream
	function get_section_stream()
	{
		return $this->section_file->copy();
	}
	
	//Method which returns a link stream
	function get_link_stream()
	{
		return $this->link_file->copy();
	}
	
	## Private elements ##
	//Templates objects
	var $module_map_file;
	var $section_file;
	var $link_file;
}

?>