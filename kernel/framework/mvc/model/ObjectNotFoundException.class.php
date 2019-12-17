<?php
/**
 * @package     MVC
 * @subpackage  Model
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 10 02
*/

class ObjectNotFoundException extends Exception
{
    public function __construct($classname, $object_id)
    {
        parent::__construct('object #' . $object_id . ' of class "' . $classname . '" was not found');
    }
}
?>
