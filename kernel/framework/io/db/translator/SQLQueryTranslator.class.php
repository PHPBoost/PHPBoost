<?php
/**
 * Translates the generic query <code>$query</code> into the mysql specific dialect
 * @package     IO
 * @subpackage  DB\translator
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 10 02
*/

interface SQLQueryTranslator
{
	/**
	 * @param string $query the query to translate
	 * @return string the translated query
	 */
	function translate($query);
}
?>
