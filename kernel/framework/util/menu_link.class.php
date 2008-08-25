<?php
/*##################################################
 *                             menu_link.class.php
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

require_once(PATH_TO_ROOT . '/kernel/framework/util/menu_element.class.php');

class MenuLink extends MenuElement
{
	## Public Methods ##
	//Constructeur.
	function MenuLink()
	{
		parent::MenuElement();
	}
	
	//Affichage.
	function display(&$template)
	{
		$template_string = $template->copy();		
		
		$template_string->Assign_vars(array(
			'C_LINK' => true,
			'TITLE' => $this->title,
			'IMAGE' => $this->image,
			'URL' => $this->url
		));
		
		return $template_string->parse(TEMPLATE_STRING_MODE);
	}
	
	## Private Methods ##
	
	## Private attributes ##
	var $url; //Identifiant du menu.
}

?>
