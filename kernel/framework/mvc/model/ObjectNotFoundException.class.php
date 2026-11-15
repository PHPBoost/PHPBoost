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

class ObjectNotFoundException extends Exception
{
    public function __construct(string $classname, int|string $object_id)
    {
        parent::__construct('object #' . $object_id . ' of class "' . $classname . '" was not found');
    }
}
?>
