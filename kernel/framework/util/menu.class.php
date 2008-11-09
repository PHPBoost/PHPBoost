<?php
/*##################################################
 *                             menu.class.php
 *                            -------------------
 *   begin               : July 08, 2008
 *   copyright           : (C) 2008 Régis Viarre; Loïc Rouchon
 *   email               : crowkait@phpboost.com; horn@phpboost.com
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
require_once(PATH_TO_ROOT . '/kernel/framework/util/menu_link.class.php');

## Menu types ##
define('VERTICAL_MENU', 'vertical');
define('HORIZONTAL_MENU', 'horizontal');
define('TREE_MENU', 'tree');
define('VERTICAL_SCROLLING_MENU', 'vertical_scrolling');
define('HORIZONTAL_SCROLLING_MENU', 'horizontal_scrolling');

/**
 * @author loic
 * @desc Create a Menu with children.
 * Children could be Menu or MenuLink objects
 */
class Menu extends MenuElement
{
	## Public Methods ##
	/**
	 * @desc Constructor
	 * @param string $title Menu title
	 * @param string $url Destination url
	 * @param string $image Menu's image url relative to the website root or absolute
	 * @param string $type Menu's type
	 */
	function Menu($title, $url, $image = '', $type = VERTICAL_SCROLLING_MENU)
	{
		$this->id = get_uid(); // Set a unique ID
		
		// Set the menu type
		$menu_types = array(VERTICAL_MENU, HORIZONTAL_MENU, TREE_MENU, VERTICAL_SCROLLING_MENU, HORIZONTAL_SCROLLING_MENU);
		$this->type = in_array($type, $menu_types) ? $type : VERTICAL_SCROLLING_MENU;
		
		// Build the menu element on witch is based the menu
		parent::MenuElement($title, $url, $image);
	}
	
	/**
	 * @desc Add a list of MenuLink or (sub)Menu to the current one
	 * @param &MenuElement[] &$menu_elements A reference to a list of MenuLink and / or Menu to add
	 */
	function add_array(&$menu_elements)
	{
		foreach($menu_elements as $element)
			$this->add($element);
	}
	
	/**
	 * @desc Add a single MenuLink or (sub) Menu
	 * @param MenuElement $element the MenuLink or Menu to add
	 */
	function add($element)
	{
	    if( get_class($element) == get_class($this) )
	       $element->_parent($this->type);
		$this->elements[] = $element;
	}
	
	/**
	 * @desc Display the menu
	 * @param Template $template the template to use
	 * @return string the menu parsed in xHTML
	 */
	function display($template = false)
	{
		if( !is_object($template) || strtolower(get_class($template)) != 'template' )
			$tpl = new Template('framework/menu/menu_' . $this->type . '.tpl');
		else
			$tpl = $template->copy();
        $original_tpl = $tpl->copy();
			
		foreach($this->elements as $element)
		{   // We use a new Tpl to avoid overwrite issues
			$tpl->assign_block_vars('elements', array('DISPLAY' => $element->display($original_tpl->copy())));
		}
		
		parent::_assign($tpl);
        $tpl->assign_vars(array(
            'C_MENU' => true,
            'C_NEXT_MENU' => ($this->depth > 0) ? true : false,
            'C_FIRST_MENU' => ($this->depth == 0) ? true : false,
            'DEPTH' => $this->depth,
            'ID' => $this->id
        ));
        
		return $tpl->parse(TEMPLATE_STRING_MODE);
	}
	
	
	## Private Methods ##
	
	/**
	 * @desc Increase the Menu Depth and set the menu type to its parent one
	 * @access protected
	 * @param string $type the type of the menu
	 */
	function _parent($type)
	{
	    $this->depth++;
	    $this->type = $type;
	    foreach( $this->elements as $element )
	    {
	        if( get_class($element) == get_class($this) )
                $element->_parent($type);
	    }
	}
	
	## Private attributes ##
	/**
	 * @access protected
	 * @var int menu's unique identifier
	 */
	var $id;
	
	/**
     * @access protected
	 * @var string menu's type
	 */
	var $type;
	
	/**
     * @access protected
	 * @var MenuElement[] Direct menu children list
	 */
	var $elements = array();
	
	/**
     * @access protected
	 * @var int Menu's depth
	 */
	var $depth = 0;
}

?>
