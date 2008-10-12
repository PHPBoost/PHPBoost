<?php
/*##################################################
 *                             menu.class.php
 *                            -------------------
*   begin                : July 08, 2008
 *   copyright           : (C) 2008 Viarre Régis
 *   email               : crowkait@phpboost.com
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

define('VERTICAL_MENU', 'vertical_menu');
define('HORIZONTAL_MENU', 'horizontal_menu');
define('TREE_MENU', 'tree_menu');
define('VERTICAL_SCROLLING_MENU', 'vertical_scrolling_menu');
define('HORIZONTAL_SCROLLING_MENU', 'horizontal_scrolling_menu');

class Menu extends MenuElement
{
	## Public Methods ##
	//Constructeur.
	function Menu($type = VERTICAL_SCROLLING_MENU)
	{
		static $id_menu = 0;
		
		$this->id_menu = $id_menu++;
		$this->type = $type;
		parent::MenuElement();
	}
	
	function add_array(&$menu_elements)
	{
		foreach($menu_elements as $element)
		{
			if( strtolower(get_class($template)) == 'menu' ) //Sous menu.
				$this->elements[] = $element;
			else
				$this->elements[] = $element;
		}
	}
	
	//Définition de l'identification du module.
	function add($element)
	{
		if( strtolower(get_class($template)) == 'menu' ) //Sous menu.
			$this->id_sub_menu++;
		$this->elements[] = $element;
	}
		
	//Affichage.
	function display($template = false)
	{
		if( !is_object($template) || strtolower(get_class($template)) != 'template' )
			$template_string = new Template('framework/menu/menu_' . $this->type . '.tpl');
		else
			$template_string = $template->copy();
			
		$template_string->assign_vars(array(
			'C_NEXT_MENU' => ($this->depth > 0) ? true : false,
			'C_FIRST_MENU' => ($this->depth == 0) ? true : false,
			'ID_SUB_MENU' => $this->id_sub_menu,
			'ID_MENU' => $this->id_menu,
			'URL_MENU' => $this->url,
			'TITLE_MENU' => $this->title,
			'IMAGE_MENU' => $this->image
		));
		
		foreach($this->elements as $element)
		{
			$template_string->assign_vars(array(
				'ELEMENT' => $element->display($template)
			));
		}
		
		return $template_string->parse(TEMPLATE_STRING_MODE);
	}
	
	
	## Private Methods ##
	
	## Private attributes ##
	var $id_sub_menu; //Identifiant des sous-menus.
	var $id_menu; //Identifiant du menu.
	var $type; //Type du menu.
	var $elements = array(); //Identifiant du menu.
	var $depth = 0; //Identifiant du menu.
}

?>
