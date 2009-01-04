<?php
/*##################################################
 *                                modulemap.class.php
 *                            -------------------
 *   begin                : June 16 th 2008
 *   copyright          : (C) 2008 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
 *
 *   ModuleMap
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

import('sitemap/sitemaplink');
import('sitemap/sitemapsection');
import('sitemap/sitemapexportconfig');

define('SITE_MAP_AUTH_GUEST', false);
define('SITE_MAP_AUTH_USER', true);

class ModuleMap
{
	##  Public methods  ##
	function ModuleMap($name = '')
	{
		$this->sub_sections = array();
		$this->name = $name;
	}
	
	//Name setter
	function set_name($name)
	{
		$this->name = $name;
	}
	
	//Name getter
	function get_name()
	{
		return $this->name;
	}
	
	//Description setter (warning it's not protected for XML displaying but usefulless in sitemap.xml)
	function set_description($description)
	{
		$this->description = $description;
	}
	
	//Description getter
	function get_description()
	{
		return $this->description;
	}
	
	//Adds an element at the end of the list
	function push_element($link)
	{
		$this->sub_sections[] = $link;
	}
	
	//Removes the latest inserted element
	function pop_element()
	{
		return array_pop($this->sub_section);
	}
	
	//Exports the sitemap (according to a configuration of templates). It returns a string
	function export(&$export_config)
	{
		//We get the stream in which we are going to write
		$template = $export_config->get_module_map_stream();
		
		$template->assign_vars(array(
			'MODULE_NAME' => htmlspecialchars($this->name, ENT_QUOTES),
			'MODULE_DESCRIPTION' => $this->description
			));
		
		foreach ($this->sub_sections as $sub_section)
		{
			$template->assign_block_vars('children', array(
				'CHILD_CODE' => $sub_section->export($export_config, 1)
				));
		}
		return $template->parse(TEMPLATE_STRING_MODE);
	}
	
	## Private elements ##
	//Module name
	var $name;	
	//description
	var $description;
	//list of sub sections or links
	var $sub_sections;
}

?>
