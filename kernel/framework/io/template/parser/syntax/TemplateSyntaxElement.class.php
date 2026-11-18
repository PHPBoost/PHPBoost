<?php
/**
 * @package     IO
 * @subpackage  Template\parser\syntax
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 18
 * @since       PHPBoost 3.0 - 2010 06 20
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
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
	function parse(TemplateSyntaxParserContext $context, StringInputStream $input, StringOutputStream $output): void;
}

?>
