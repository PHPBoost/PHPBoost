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

import('menu/menu');

import('menu/content/content_menu');
import('menu/links/links_menu');
import('menu/mini/mini_menu');
import('menu/module_mini/module_mini_menu');

define('MOVE_UP',   -1);
define('MOVE_DOWN',  1);

/**
 * @author Loïc Rouchon horn@phpboost.com
 * @desc This service manage kernel menus by adding the persistance to menus objects.
 * It also provides all moving and disabling methods to change the website appearance.
 * @static
 * @package core
 */
class MenuService
{
    /**
     * @desc
     * @param $block
     * @param $enabled
     * @return unknown_type
     */
    function get_menu_list($class = MENU__CLASS, $block = BLOCK_POSITION__ALL, $enabled = MENU_ENABLE_OR_NOT)
    {
        global $Sql;
        
        $query = "SELECT id, object, block, position, enabled FROM " . PREFIX . "menuss";
        
        $conditions = array();
        if ($class != MENU__CLASS)
            $conditions[] = "class='" . strtolower($class) . "'";
        if ($block != BLOCK_POSITION__ALL)
            $conditions[] = "block='" . $block . "'";
        if ($enabled !== MENU_ENABLE_OR_NOT)
            $conditions[] .= "enabled='" . $enabled . "'";
        
        if (count($conditions) > 0)
            $query .= " WHERE " . implode(' AND ', $conditions);
        
        $menus = array();
        $result = $Sql->query_while ($query . ";", __LINE__, __FILE__);
        
        while ($row = $Sql->fetch_assoc($result))
            $menus[] = MenuService::_load($row);
            
        $Sql->query_close($result);
        
        return $menus;
    }
    
    /**
     * @desc
     * @return unknown_type
     */
    function get_menus_map()
    {
        global $Sql;
        
        // Initialize the map by using the value of the 9 constants used for blocks positions
        $menus = MenuService::_initialize_menus_map();
        
        $query = "
            SELECT id, object, block, position, enabled
            FROM " . PREFIX . "menuss
            ORDER BY position ASC
        ;";
        $result = $Sql->query_while ($query, __LINE__, __FILE__);
        while ($row = $Sql->fetch_assoc($result))
        {
            if ($row['enabled'] != MENU_ENABLED)
                $menus[BLOCK_POSITION__NOT_ENABLED][] = MenuService::_load($row);
            else
                $menus[$row['block']][] = MenuService::_load($row);
        }
        $Sql->query_close($result);
        
        return $menus;
    }
    
    
    /**
     * @desc Retrieve a Menu Object from the database by its id
     * @param int $id the id of the Menu to retrieve from the database
     * @return Menu the requested Menu if it exists else, null
     */
    function load($id)
    {
        global $Sql;
        $result = $Sql->query_array('menuss', 'id', 'object', 'block', 'position', 'enabled', "WHERE id='" . $id . "'", __LINE__, __FILE__);
        
        if ($result === false)
            return null;
        
        return MenuService::_load($result);
    }
    
    /**
     * @desc Retrieve a Menu Object from the database by its title
     * @param string $title the title of the Menu to retrieve from the database
     * @return Menu the requested Menu if it exists else, false
     */
    function load_by_title($title)
    {
        global $Sql;
        $result = $Sql->query_array('menuss', 'id', 'object', 'block', 'position', 'enabled', "WHERE title='" . addslashes($title) . "'", __LINE__, __FILE__);
        
        if ($result === false)
            return false;
        
        return MenuService::_load($result);
    }
    
    /**
     * @desc save a Menu in the database
     * @param Menu $menu The Menu to save
     * @return bool true if the save have been correctly done
     */
    function save(&$menu)
    {
        global $Sql;
        
        if (($block = $menu->get_block()) != MENU_NOT_ENABLED && ($block_position = $menu->get_block_position()) == -1)
        {
            $block_position_query = "SELECT MAX(position) + 1 FROM " . PREFIX . "menuss WHERE block='" . $block. "'";
            $block_position = (int) $Sql->query($block_position_query, __LINE__, __FILE__);
        }
        
        $query = '';
        $id_menu = $menu->get_id();
        if ($id_menu > 0)
        {   // We only have to update the element
            $query = "
            UPDATE " . PREFIX . "menuss SET
                    title='" . addslashes($menu->get_title()) . "',
                    object='" . serialize($menu) . "',
                    class='" . strtolower(get_class($menu)) . "',
                    enabled='" . $menu->is_enabled() . "',
                    block='" . $block . "',
                    position='" . $menu->get_block_position() . "'
            WHERE id='" . $id_menu . "';";
        }
        else
        {   // We have to insert the element in the database
            $query = "
                INSERT INTO " . PREFIX . "menuss (title,object,class,enabled,block,position)
                VALUES (
                    '" . addslashes($menu->get_title()) . "',
                    '" . serialize($menu) . "',
                    '" . strtolower(get_class($menu)) . "',
                    '" . $menu->is_enabled() . "',
                    '" . $block . "',
                    '" . $block_position . "'
                );";
        }
        $Sql->query_inject($query, __LINE__, __FILE__);
        
        return true;
    }

    /**
     * @desc Delete a Menu from the database
     * @param mixed $menu The (Menu) Menu or its (int) id to delete from the database
     */
    function delete(&$menu)
    {
        global $Sql;
        $id_menu = is_numeric($menu) ? $menu : (is_object($menu) ? $menu->get_id() : 0);
        if ($id_menu > 0)
            $Sql->query_inject("DELETE FROM " . PREFIX . "menuss WHERE id='" . $id_menu . "';" , __LINE__, __FILE__);
    }

    
    /**
     * @desc Enable a menu
     * @param Menu $menu the menu to enable
     */
    function enable(&$menu)
    {
        $menu->enabled(MENU_ENABLED);
        // Commputes the new Menu position and save it
        MenuService::move($menu, $menu->get_block());
    }
    
    /**
     * @desc Disable a menu
     * @param Menu $menu the menu to disable
     */
    function disable(&$menu)
    {
        $menu->enabled(MENU_NOT_ENABLED);
        MenuService::save($menu);
    }
    
    /**
     * @desc Move a menu into a block. Enable or disable it according to the destination block
     * @param Menu $menu the menu to move
     * @param int $block the destination block
     */
    function move(&$menu, $block)
    {
        global $Sql;
        
        if ($menu->is_enabled())
        {   // Updates the previous block position counter
            $update_query = "
                UPDATE " . PREFIX ."menuss
                SET position=position - 1
                WHERE block='" . $menu->get_block() . "' AND position>'" . $menu->get_block_position() . "';";
            $Sql->query_inject($update_query, __LINE__, __FILE__);
        }
        else
        {   // Enables the menu if not
            $menu->enabled();
        }
        
        // Disables the menu if the destination block is the NOT_ENABLED block position
        if ($block == BLOCK_POSITION__NOT_ENABLED)
            $menu->enabled(MENU_NOT_ENABLED);
        
        // If not enabled, we do not move it so we can restore its position by reactivating it
        if ($menu->is_enabled())
        {   // Moves the menu into the destination block
            $menu->set_block($block);
            
            // Computes the new block position for the menu
            $position_query = "SELECT MAX(position) + 1 FROM " . PREFIX ."menuss WHERE block='" . $menu->get_block() . "' AND enabled='1';";
            $menu->set_block_position((int) $Sql->query($position_query, __LINE__, __FILE__));
        }
        
        MenuService::save($menu);
    }
    
    /**
     * @desc Change the menu position in a block
     * @param Menu $menu The menu to move
     * @param int $diff the direction to move it. positives integers move down, negatives, up.
     */
    function change_position(&$menu, $direction = MOVE_UP)
    {
        global $Sql;
        
        $block_position = $menu->get_block_position();
        $new_block_position = $block_position;
        $update_query = '';
        
        if ($direction > 0)
        {   // Moving the menu down
            $max_position_query = "SELECT MAX(position) FROM " . PREFIX . "menuss WHERE block='" . $menu->get_block() . "' AND enabled='1'";
            $max_position = $Sql->query($max_position_query, __LINE__, __FILE__);
            // Getting the max diff
            if (($new_block_position = ($menu->get_block_position() + $direction)) > $max_position)
                $new_block_position = $max_position;
            
            $update_query = "
                UPDATE " . PREFIX . "menuss SET position=position - 1
                WHERE
                    block='" . $menu->get_block() . "' AND
                    position BETWEEN '" . ($block_position + 1) . "' AND '" . $new_block_position . "'
            ";
        }
        else if ($direction < 0)
        {   // Moving the menu up
            
            // Getting the max diff
            if (($new_block_position = ($menu->get_block_position() + $direction)) < 0)
                $new_block_position = 0;
                            
            // Updating other menus
            $update_query = "
                UPDATE " . PREFIX . "menuss SET position=position + 1
                WHERE
                    block='" . $menu->get_block() . "' AND
                    position BETWEEN '" . ($block_position - 1) . "' AND '" . $new_block_postion . "'
            ";
        }
        
        if ($block_position != $new_block_position)
        {   // Updating other menus
            $Sql->query_inject($update_query, __LINE__, __FILE__);
            
            // Updating the current menu
            $menu->set_block_position($new_block_position);
            MenuService::save($menu);
        }
    }
    
    /**
     * @desc Generate the cache
     */
    function generate_cache()
    {
        // $MENUS global var initialization
        $cache_str = '$MENUS = array();';
        $cache_str .= '$MENUS[BLOCK_POSITION__HEADER] = \'\';';
        $cache_str .= '$MENUS[BLOCK_POSITION__SUB_HEADER] = \'\';';
        $cache_str .= '$MENUS[BLOCK_POSITION__TOP_CENTRAL] = \'\';';
        $cache_str .= '$MENUS[BLOCK_POSITION__BOTTOM_CENTRAL] = \'\';';
        $cache_str .= '$MENUS[BLOCK_POSITION__TOP_FOOTER] = \'\';';
        $cache_str .= '$MENUS[BLOCK_POSITION__FOOTER] = \'\';';
        $cache_str .= '$MENUS[BLOCK_POSITION__LEFT] = \'\';';
        $cache_str .= '$MENUS[BLOCK_POSITION__RIGHT] = \'\';';
        $cache_str .= 'global $User;' . "\n";
        
        $menus_map = MenuService::get_menus_map();
        
        foreach ($menus_map as $block => $block_menus)
        {
            if ($block != BLOCK_POSITION__NOT_ENABLED)
            {
                foreach ($block_menus as $menu)
                {
                    if ($menu->is_enabled())
                    {
                        $cache_str .= '$__menu=\'' . $menu->cache_export() . '\';' . "\n";
                        $cache_str .= '$MENUS[' . $menu->get_block() . '].=$__menu;' . "\n";
                    }
                }
            }
        }
        
        Cache::write('menuss', preg_replace(
                array('`<!--.*-->`u', '`\t*`', '`\s*\n\s*\n\s*`', '`[ ]{2,}`', '`>\s`', '`\n `', '`\'\.\'`'),
                array('', '', "\n", ' ', '> ', "\n", ''),
                $cache_str
            )
        );
    }
    
    /**
     * @return array[] initialize the menus map structure
     */
    function _initialize_menus_map()
    {
        return array(
            BLOCK_POSITION__HEADER => array(),
            BLOCK_POSITION__SUB_HEADER => array(),
            BLOCK_POSITION__TOP_CENTRAL => array(),
            BLOCK_POSITION__BOTTOM_CENTRAL => array(),
            BLOCK_POSITION__TOP_FOOTER => array(),
            BLOCK_POSITION__FOOTER => array(),
            BLOCK_POSITION__LEFT => array(),
            BLOCK_POSITION__RIGHT => array(),
            BLOCK_POSITION__NOT_ENABLED => array()
        );
    }
    
    /**
     * @desc Build a Menu object from a database result
     * @param string[key] $db_result the map from the database with the Menu id and serialized object
     * @return Menu the menu object from the serialized one
     */
    function _load($db_result)
    {
        $menu = unserialize($db_result['object']);
        
        // Synchronize the object and the database
        $menu->id($db_result['id']);
        $menu->enabled($db_result['enabled']);
        $menu->set_block($db_result['block']);
        $menu->set_block_position($db_result['position']);
        
        return $menu;
    }
}
?>