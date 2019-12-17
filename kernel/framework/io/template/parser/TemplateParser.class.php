<?php
/**
 * This interfaces represents a class that is able to parse a template source and transform it
 * to a syntax that the PHP engine is able to run.
 * @package     IO
 * @subpackage  Template\parser
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 06 18
*/

interface TemplateParser
{
	/**
	 * Parses the $content string.
	 * @param string $content The content to parse
	 * @return The parsed content
	 */
	function parse($content);
}
?>
