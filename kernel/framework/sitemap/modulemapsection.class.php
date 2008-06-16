<?php
/*##################################################
 *                                modulemapsection.class.php
 *                            -------------------
 *   begin                : June 16 th 2008
 *   copyright          : (C) 2008 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
 *
 *   Module_map_section
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

class Module_map_section
{
	##  Public methods  ##
	function Module_map_section($name = '')
	{
		$this->section_name = $name;
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
	
	function Add_element($element)
	{
		array_push($this->sub_sections, $element);
	}
	
	## Private elements ##
	//Name of the section
	var $section_name;
	//List of links or subsections (polymorphism)
	var $sub_sections;
}

?>