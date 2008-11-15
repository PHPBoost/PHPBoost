<?php
/*##################################################
 *                             menu_service.class.php
 *                            -------------------
 *   begin                : November 13, 2008
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

import('menus/menu');

import('menus/content/content_menu');
import('menus/links/links_menu');
import('menus/mini/mini_menu');
import('menus/modules_mini/modules_mini_menu');

class MenuService
{
    /**
     * @desc Retrieve a Menu Object from the database
     * @param string $title the title of the Menu to retrieve from the database
     * @return Menu the requested Menu if it exists else, false
     */
    /* static */ function load($title)
    {
        global $Sql;
        $result = $Sql->query_array('menuss', 'id', 'object', "WHERE `title`='" . addslashes($title) . "'", __LINE__, __FILE__);
        
        if( $result === false )
            return false;
        $object = unserialize($result['object']);
        $object->id($result['id']);
        
        return $object;
    }
    
    /**
     * @desc save a Menu in the database
     * @param Menu $menu The Menu to save
     * @return bool true if the save have been correctly done, false if a Menu with the same title already exists
     */
    /* static */ function save(&$menu)
    {
        global $Sql;
        
        if( $Sql->query("SELECT COUNT(*) FROM `" . PREFIX . "menuss` WHERE `title`='" . $menu->get_title() . "';", __LINE__, __FILE__) > 0 )
            return false;
        
        $query = '';
        $id_menu = $menu->get_id();
        if( $id_menu > 0 )
        {   // We only have to update the element
            $query = "
            UPDATE `" . PREFIX . "menuss` SET
                    `title`='" . addslashes($menu->get_title()) . "',
                    `object`='" . serialize($menu) . "',
                    `class`='" . get_class($menu) . "',
                    `enabled`='" . $menu->is_enabled() . "',
                    `block`='" . $menu->get_block_position() . "',
                    `position`='" . $menu->get_position() . "'
            WHERE id='" . $id_menu . "';";
        }
        else
        {   // We have to insert the element in the database
            $query = "
                INSERT INTO `" . PREFIX . "menuss` (`title`,`object`,`class`,`enabled`,`block`,`position`)
                VALUES (
                    '" . addslashes($menu->get_title()) . "',
                    '" . serialize($menu) . "',
                    '" . get_class($menu) . "',
                    '" . $menu->is_enabled() . "',
                    '" . $menu->get_block_position() . "',
                    '" . $menu->get_position() . "'
                );";
        }
        $Sql->query_inject($query, __LINE__, __FILE__);
        
        return true;
    }

    /**
     * @desc Delete a Menu from the database
     * @param Menu $menu The Menu to delete from the database
     */
    /*static*/ function delete(&$menu)
    {
        global $Sql;
        $id_menu = $menu->get_id();
        if( $id_menu > 0 )
            $Sql->query_inject("DELETE FROM `" . PREFIX . "menuss` WHERE `id`='" . $id_menu . "';" , __LINE__, __FILE__);
    }

    
    /**
     * @desc Generate the cache
     */
    /*static*/ function generate_cache()
    {
        global $Cache;
        $Cache->generate_file('menus');
    }
}
?>