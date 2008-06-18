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

class Sitemap_export_config
{
	##  Public methods  ##
	function Sitemap_export_config($module_map_file, $section_file, $link_file)
	{
		$this->module_map_file = $module_map_file;
		$this->section_file = $section_file;
		$this->link_file = $link_file;
	}
	
	//Method which returns a module map stream
	function Get_module_map_stream()
	{
		return new Template($this->module_map_file);
	}
	
	//Method which returns a module section stream
	function Get_section_stream()
	{
		return new Template($this->section_file);
	}
	
	//Method which returns a link stream
	function Get_link_stream()
	{
		return new Template($this->link_file);
	}
	
	## Private elements ##
	//Name of templates
	var $module_map_file;
	var $section_file;
	var $link_file;
}

?>