<?php
/*##################################################
 *                           busines_object.class.php
 *                            -------------------
 *   begin                : October 2, 2009
 *   copyright            : (C) 2009 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
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

abstract class BusinessObject implements PropertiesMapInterface
{
    public function populate($properties_map)
    {
        foreach ($properties_map as $property => $value)
        {
            $setter = 'set_' . $property;
            $this->$setter($value);
        }
    }
    
    public function get_raw_value($properties_list)
    {
        $properties_map = array();
        foreach ($properties_list as $property)
        {
            $getter = 'get_' . $property;
            $properties_map[$property] = $this->$getter();
        }
        return $properties_map;
    }
}
?>