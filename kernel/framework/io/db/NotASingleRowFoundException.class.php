<?php
/**
 * @package     IO
 * @subpackage  DB
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 10 01
*/

class NotASingleRowFoundException extends SQLQuerierException
{
    public function __construct(SelectQueryResult $query_result)
    {
        parent::__construct('multiple rows have been found but the query expect only one result<br />-> ' .
        $query_result->get_query() . '<br />' . var_export($query_result->get_parameters(), true));
    }
}
?>
