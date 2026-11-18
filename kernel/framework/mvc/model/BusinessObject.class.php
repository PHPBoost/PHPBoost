<?php
/**
 * @package     MVC
 * @subpackage  Model
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 03
 * @since       PHPBoost 3.0 - 2009 10 02
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

abstract class BusinessObject implements PropertiesMapInterface
{
    public function populate(array $properties_map): void
    {
        foreach ($properties_map as $property => $value)
        {
            $setter = 'set_' . $property;
            $this->$setter($value);
        }
    }

    public function get_raw_value(array $properties_list): array
    {
        $properties_map = [];
        foreach ($properties_list as $property)
        {
            $getter = 'get_' . $property;
            $properties_map[$property] = $this->$getter();
        }
        return $properties_map;
    }
}
?>
