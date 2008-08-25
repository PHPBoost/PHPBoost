<?php
/*##################################################
 *                             menu_element.class.php
 *                            -------------------
*   begin                : July 08, 2008
 *   copyright          : (C) 2008 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 * 
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

class MenuElement
{
	## Public Methods ##
	//Constructeur.
	function MenuElement()
	{
	}
	
	//Définit l'image de l'élément.
	function set_image(&$image)
	{
		$this->image = $image;
	}
	
	//Définit le titre de l'élément.
	function set_title(&$title)
	{
		$this->title = $title;
	}
		
	//Définit l'adresse.
	function set_url(&$url)
	{
		$this->url = $url;
	}
	
	//Récupère l'image de l'élément.
	function get_image()
	{
		return $this->image;
	}
	
	//Récupère le titre de l'élément.
	function get_title()
	{
		return $this->title;
	}
	
	//Récupération de l'adresse.
	function get_url()
	{
		return $this->url;
	}
	
	## Private Methods ##
	
	## Private attributes ##
	var $title = ''; //titre de l'élément.
	var $image = ''; //Image de l'élément.
	var $url = ''; //Image de l'élément.
}

?>
