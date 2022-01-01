<?php
/**
 * @package     MVC
 * @subpackage  Model
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 10 02
*/

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
