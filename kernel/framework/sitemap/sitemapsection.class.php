<?php
/*##################################################
 *                                sitemapsection.class.php
 *                            -------------------
 *   begin                : June 16 th 2008
 *   copyright          : (C) 2008 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
 *
 *   Sitemap_section
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

//Should implement an interface in PHP 5

class Sitemap_section
{
	##  Public methods  ##
	function Sitemap_section($name = '')
	{
		$this->section_name = $name;
		$this->sub_sections = array();
	}
	
	//Name getter
	function Get_name($name)
	{
		return $name;
	}
	
	//Name setter
	function Set_name($name)
	{
		$this->section_name = $name;
	}
	
	//Add an element to the section (section or link => polymorphism)
	function Add_element($element)
	{
		array_push($this->sub_sections, $element);
	}
	
	//Method which exports the section into the stream $template
	function Export(&$export_config)
	{
		//We get the stream in which we are going to write
		$template = $export_config->Get_section_stream();
		
		$template->Assign_vars(array(
			'C_SECTION_NAME' => !empty($this->section_name),
			'SECTION_NAME' => $this->section_name
		));
		
		foreach($this->sub_sections as $sub_section)
		{
			$template->Assign_block_vars('children', array(
				'CHILD_CODE' => $sub_section->Export($export_config),
			));
		}
		return $template->Tparse(TEMPLATE_STRING_MODE);
	}
	
	## Private elements ##
	//Name of the section
	var $section_name;
	//List of links or subsections (polymorphism)
	var $sub_sections;
}

?>