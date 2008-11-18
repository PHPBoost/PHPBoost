<?php
/*##################################################
 *                                sitemapsection.class.php
 *                            -------------------
 *   begin                : June 16 th 2008
 *   copyright          : (C) 2008 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
 *
 *   SitemapSection
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

class SitemapSection
{
	##  Public methods  ##
	function SitemapSection($name = '')
	{
		$this->section_name = $name;
		$this->sub_sections = array();
	}
	
	//Name getter
	function get_name($name)
	{
		return $name;
	}
	
	//Name setter
	function set_name($name)
	{
		$this->section_name = $name;
	}
	
	//Adds an element to the section (section or link => polymorphism)
	function push_element($element)
	{
		$this->sub_sections[] = $element;
	}
	
	//Deletes an element to the section (section or link => polymorphism)
	function pop_element()
	{
		return array_pop($this->sub_sections);
	}
	
	//Method which exports the section into the stream $template
	function export(&$export_config, $depth = 1)
	{
		//We get the stream in which we are going to write
		$template = $export_config->get_section_stream();
		
		if (is_string($this->section_name))
			$template->assign_vars(array(
				'C_SECTION_NAME_IS_STRING' => !empty($this->section_name),
				'SECTION_NAME' => $this->section_name,
				'DEPTH' => $depth
			));
		elseif (is_object($this->section_name) && strtolower(get_class($this->section_name)) == 'sitemap_link')
			$template->assign_vars(array(
					'C_SECTION_NAME_IS_LINK' => true,
					'LINK_CODE' => $this->section_name->export($export_config),
					'DEPTH' => $depth
				));
		
		foreach ($this->sub_sections as $sub_section)
		{
			$template->assign_block_vars('children', array(
				'CHILD_CODE' => $sub_section->export($export_config, $depth + 1)
			));
		}
		return $template->parse(TEMPLATE_STRING_MODE);
	}
	
	## Private elements ##
	//Name of the section (can be a string or a link)
	var $section_name;
	//List of links or subsections (polymorphism)
	var $sub_sections;
}

?>
