<?php
/**
 * Call the controller method matching an url
 * @package     MVC
 * @subpackage  Dispatcher
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 10 17
*/

interface UrlMapper
{
    /**
     * Returns true if the UrlDispatcherItem match the url
     * @param string $url the to match
     * @return boolean true if the UrlDispatcherItem match the url
     */
    public function match($url);

    /**
     * Call the controller method if the url match and if the method exists
     * @param string $url the url
     * @throws NoUrlMatchException
     * @throws NoSuchControllerMethodException
     */
    public function call();
}
?>
