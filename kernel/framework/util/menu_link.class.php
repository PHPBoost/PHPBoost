<?php
/*##################################################
 *                             menu_link.class.php
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

import('util/menu_element');

/**
 * @author loic
 * @desc A Simple menu link
 * @package util
 * @subpackage menu
 */
class MenuLink extends MenuElement
{
	## Public Methods ##
	// .
	/**
     * @desc Constructor
     * @param string $title Menu title
     * @param string $url Destination url
     * @param string $image Menu's image url relative to the website root or absolute
	 */
	function MenuLink($title, $url, $image = '')
	{
		parent::MenuElement($title, $url, $image);
	}
	
	/**
     * @desc Display the menu
     * @param Template $template the template to use
     * @return string the menu parsed in xHTML
     */
	function display($template)
	{
        parent::_assign($template);
		return $template->parse(TEMPLATE_STRING_MODE);
	}
	
	## Private Methods ##
	
	## Private attributes ##
}

?>
