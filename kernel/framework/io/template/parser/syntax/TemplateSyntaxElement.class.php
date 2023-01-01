<?php
/**
 * @package     IO
 * @subpackage  Template\parser\syntax
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 06 20
*/

interface TemplateSyntaxElement
{
    const RESULT = '$_result';
    const DATA = '$_data';
    const FUNCTIONS = '$_functions';

	/**
	 * @param TemplateSyntaxParserContext $context
	 * @param StringInputStream $input
	 * @param StringOutputStream $output
	 */
	function parse(TemplateSyntaxParserContext $context, StringInputStream $input, StringOutputStream $output);
}

?>
