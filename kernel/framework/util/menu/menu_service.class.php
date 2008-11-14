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

import('util/menu/menu');
import('core/menu_manager');

class MenuService
{
    //Method returning the contribution when we know its integer identifier
    /*static*/ function find_by_id($id_contrib)
    {
        global $Sql;

        $result = $Sql->query_while("SELECT id, entitled, fixing_url, module, current_status, creation_date, fixing_date, auth, poster_id, fixer_id, id_in_module, identifier, type, poster_member.login poster_login, fixer_member.login fixer_login, description
        FROM " . PREFIX . EVENTS_TABLE_NAME . " c
        LEFT JOIN ".PREFIX."member poster_member ON poster_member.user_id = c.poster_id
        LEFT JOIN ".PREFIX."member fixer_member ON fixer_member.user_id = c.poster_id
        WHERE id = '" . $id_contrib . "' AND contribution_type = '" . CONTRIBUTION_TYPE . "'
        ORDER BY creation_date DESC", __LINE__, __FILE__);

        $properties = $Sql->fetch_assoc($result);

        if( (int)$properties['id'] > 0 )
        {
            $contribution = new Contribution();
            $contribution->build($properties['id'], $properties['entitled'], $properties['description'], $properties['fixing_url'], $properties['module'], $properties['current_status'], new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $properties['creation_date']), new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $properties['fixing_date']), unserialize($properties['auth']), $properties['poster_id'], $properties['fixer_id'], $properties['id_in_module'], $properties['identifier'], $properties['type'], $properties['poster_login'], $properties['fixer_login']);
            return $contribution;
        }
        else
        return null;
    }

    //Function which returns all the contributions of the table (we can force it to be ordered)
    /*static*/ function get_all($criteria = 'creation_date', $order = 'desc')
    {
        global $Sql;

        $array_result = array();

        //On liste les alertes
        $result = $Sql->query_while("SELECT id, entitled, fixing_url, auth, current_status, module, creation_date, fixing_date, poster_id, fixer_id, poster_member.login poster_login, fixer_member.login fixer_login, identifier, id_in_module, type, description
        FROM " . PREFIX . EVENTS_TABLE_NAME . " c
        LEFT JOIN ".PREFIX."member poster_member ON poster_member.user_id = c.poster_id
        LEFT JOIN ".PREFIX."member fixer_member ON fixer_member.user_id = c.poster_id
        WHERE contribution_type = " . CONTRIBUTION_TYPE . "
        ORDER BY " . $criteria . " " . strtoupper($order), __LINE__, __FILE__);
        while( $row = $Sql->fetch_assoc($result) )
        {
            $contri = new Contribution();

            $contri->build($row['id'], $row['entitled'], $row['description'], $row['fixing_url'], $row['module'], $row['current_status'], new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $row['creation_date']), new Date(DATE_TIMESTAMP, TIMEZONE_SYSTEM, $row['fixing_date']), unserialize($row['auth']), $row['poster_id'], $row['fixer_id'], $row['id_in_module'], $row['identifier'], $row['type'], $row['poster_login'], $row['fixer_login']);
            $array_result[] = $contri;
        }

        $Sql->query_close($result);

        return $array_result;
    }

    //Function which builds a list of the contributions matching the required criteria(s)
    /*static*/ function find_by_title($module, $id_in_module = null, $type = null, $identifier = null, $poster_id = null, $fixer_id = null)
    {
        global $Sql;

    }

    //Creation or update of a contribution (in database)
    /*static*/ function save(&$menu)
    {
        global $Sql;
        $query = '';
        $id_menu = $menu->get_id();
        if( $id_menu == 0 )
        {   // We have to insert the element in the database
            $query = "INSERT INTO " . PREFIX . "menus (name, contents, type)
            VALUES ('" . addslashes($menu->get_title()) . "', '" . serialize($menu) . "',  '" . MENU_LINKS . "');";
        }
        else
        {   // We only have to update the element
            $query = "UPDATE " . PREFIX . "links_menu SET
                name='" . addslashes($menu->get_title()) . "',
                contents='" . serialize($menu) . "'
            WHERE id='" . $id_menu . "';";
        }
        $Sql->query_inject($query, __LINE__, __FILE__);
    }

    //Deleting a contribution in the database
    /*static*/ function delete(&$menu)
    {

    }

    /*static*/ function generate_cache()
    {
        //        global $Cache;
        //        $Cache->generate_file('member');
    }


    /*static*/ function _get_itype(&$menu)
    {
        if( of_class($menu, MENU_LINK__CLASS) )
            return I_MENU_LINK;
        switch( $menu->get_type() )
        {
            case TREE_MENU:
                return I_TREE_MENU;
            case VERTICAL_MENU:
                return I_VERTICAL_MENU;
            case HORIZONTAL_MENU:
                return I_HORIZONTAL;
            case VERTICAL_SCROLLING_MENU:
                return I_VERTICAL_SCROLLING_MENU;
            case HORIZONTAL_SCROLLING_MENU:
                return I_HORIZONTAL_SCROLLING_MENU;
            default:
                return I_VERTICAL_SCROLLING_MENU;
        }
    }

    /*static*/ function _itype_to_stype($itype)
    {
        switch( $itype )
        {
            case I_TREE_MENU:
                return TREE_MENU;
            case I_VERTICAL_MENU:
                return VERTICAL_MENU;
            case I_HORIZONTAL_MENU:
                return HORIZONTAL_MENU;
            case I_VERTICAL_SCROLLING_MENU:
                return VERTICAL_SCROLLING_MENU;
            case I_HORIZONTAL_SCROLLING_MENU:
                return HORIZONTAL_SCROLLING_MENU;
            default:
                return VERTICAL_SCROLLING_MENU;
        }
    }
}
?>