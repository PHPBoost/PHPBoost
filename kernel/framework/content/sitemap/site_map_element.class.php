<?php
/*##################################################
 *                        site_map_element.class.php
 *                            -------------------
 *   begin                : February 3rd 2009
 *   copyright            : (C) 2009 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
 *
 *
 *
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @abstract
 * @author Benoît Sautel <ben.popeye@phpboost.com>
 * @desc This abstract is the root of every object which can be contained by a SiteMap object.
 * Some SiteMapElements objects can contain one or many SiteMapElement objects therefore the elements 
 * can be represented by a tree an each element has a depth in the tree.
 */
class SiteMapElement
{
    /**
     * @desc Build a SiteMapElement object
     * @param $name string Name of the object
     */
    function SiteMapElement($name, $depth = 1)
    {
        $this->name = $name;

        $this->set_depth($depth);
    }
    
    /**
     * @desc Return the depth of the element in the tree 
     * @return int depth
     */
    function get_depth()
    {
        return $this->depth;
    }

    /**
     * @desc Set the depth of the element
     * @param $depth
     */
    function set_depth($depth)
    {
        $this->depth = $depth;
    }

    /**
     * @desc Exports the element
     * @param $export_config SiteMapExportConfig Export configuration
     * @param $depth int Depth of the element
     * @return int
     */
    function export(&$export_config)
    {
    }

    ## Private elements ##
    /**
     * @var int Depth of the element in the elements tree
     */
    var $depth = 1;
}

?>
