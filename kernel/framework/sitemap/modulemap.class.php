<?php
/*##################################################
 *                                modulemap.class.php
 *                            -------------------
 *   begin                : June 16 th 2008
 *   copyright          : (C) 2008 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
 *
 *   Module_map
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

include_once(PATH_TO_ROOT . '/kernel/framework/sitemap/sitemaplink.class.php');
include_once(PATH_TO_ROOT . '/kernel/framework/sitemap/sitemapsection.class.php');
include_once(PATH_TO_ROOT . '/kernel/framework/sitemap/sitemapexportconfig.class.php');

define('SITE_MAP_AUTH_GUEST', false);
define('SITE_MAP_AUTH_USER', true);

class Module_map
{
	##  Public methods  ##
	function Module_map($name = '')
	{
		$this->sub_sections = array();
		$this->name = $name;
	}
	
	//Name setter
	function Set_name($name)
	{
		$this->name = $name;
	}
	
	//Name getter
	function Get_name()
	{
		return $this->name;
	}
	
	//Description setter (warning it's not protected for XML displaying but usefulless in sitemap.xml)
	function Set_description($description)
	{
		$this->description = $description;
	}
	
	//Description getter
	function Get_description()
	{
		return $this->description;
	}
	
	//Adds an element at the end of the list
	function Push_element($link)
	{
		$this->sub_sections[] = $link;
	}
	
	//Removes the latest inserted element
	function Pop_element()
	{
		return array_pop($this->sub_section);
	}
	
	//Exports the sitemap (according to a configuration of templates). It returns a string
	function Export(&$export_config)
	{
		//We get the stream in which we are going to write
		$template = $export_config->Get_module_map_stream();
		
		$template->Assign_vars(array(
			'MODULE_NAME' => htmlspecialchars($this->name, ENT_QUOTES),
			'MODULE_DESCRIPTION' => $this->description
			));
		
		foreach($this->sub_sections as $sub_section)
		{
			$template->Assign_block_vars('children', array(
				'CHILD_CODE' => $sub_section->Export($export_config, 1)
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
