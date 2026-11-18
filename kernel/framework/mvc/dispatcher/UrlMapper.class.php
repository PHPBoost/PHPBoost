<?php
/**
 * Call the controller method matching an url
 * @package     MVC
 * @subpackage  Dispatcher
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 03
 * @since       PHPBoost 3.0 - 2009 10 17
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

interface UrlMapper
{
    /**
     * Returns true if the UrlDispatcherItem match the url
     * @param string $url the to match
     * @return boolean true if the UrlDispatcherItem match the url
     */
    public function match(string $url): bool;

    /**
     * Call the controller method if the url match and if the method exists
     */
    public function call(): void;
}
?>
