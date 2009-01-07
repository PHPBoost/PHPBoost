<?php
/*##################################################
 *                          content_menu.class.php
 *                            -------------------
 *   begin                : November 15, 2008
 *   copyright            : (C) 2008 Loïc Rouchon
 *   email                : horn@phpboost.com
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

import('menu/menu');

define('CONTENT_MENU__CLASS','ContentMenu');

/**
 * @author Loïc Rouchon horn@phpboost.com
 * @desc
 * @package Menu
 * @subpackage ContentMenu
 */
class ContentMenu extends Menu
{
    ## Public Methods ##
    function ContentMenu($title)
    {
       parent::Menu($title);
    }
    
    ## Setters ##
    /**
     * @param string $content the content to set
     */
    function set_content($content) { $this->content = strparse($content, array(), DO_NOT_ADD_SLASHES); }
    
    ## Getters ##
    /**
     * @return string the menu content
     */
    function get_content() { return $this->content; }
    
    
    function display()
    {
        $tpl = new Template('framework/menus/content/display.tpl');
        $tpl->assign_vars(array(
        	'CONTENT' => second_parse($this->content)
        ));
        return $tpl->parse(TEMPLATE_STRING_MODE);
    }
    
    function cache_export()
    {
        return parent::cache_export_begin() . trim(var_export($this->display(), true), '\'') . parent::cache_export_end();
    }
    
    
    ## Private Attributes
    
    /**
     * @var string the menu's content
     */
    var $content = '';
    
}

?>