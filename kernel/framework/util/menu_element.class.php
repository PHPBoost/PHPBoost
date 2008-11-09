<?php
/*##################################################
 *                             menu_element.class.php
 *                            -------------------
 *   begin                : July 08, 2008
 *   copyright            : (C) 2008 Régis Viarre; Loïc Rouchon
 *   email                : crowkait@phpboost.com; horn@phpboost.com
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


// Abstract class : Do not instanciate it
//      Menu and MenuLink classes are based on this class
//      use, on of these

class MenuElement
{
	## Public Methods ##
	// Constructor.
	function MenuElement($title, $url, $image = '')
	{
       $this->title = $title;
       $this->url = $url;
       $this->image = $image;
	}
	
	## Setters ##
	function set_image(&$image) { $this->image = $image; }
	function set_title(&$title) { $this->title = $title; }
	function set_url(&$url) { $this->url = $url; }
	
	## Getters ##
	function get_image() { return strpos($this->image, '://') > 0 ? $this->image : PATH_TO_ROOT . $this->image; }
	function get_title() { return $this->title; }
	function get_url() { return $this->url; }
	
	## Private Methods ##
	
	// Assign tpl vars
    function _assign(&$template)
    {
        $template->assign_vars(array(
            'C_IMG' => !empty($this->image),
            'TITLE' => $this->title,
            'IMG' => $this->get_image(),
            'URL' => $this->url
        ));
    }
	
	## Private attributes ##
	var $title = '';   // Element's title
    var $url = '';     // Element's url
	var $image = '';   // Element's image
}

?>