<?php
/**
 * @package     IO
 * @subpackage  Template\parser\syntax
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 06 10
 * @contributor Loic ROUCHON <horn@phpboost.com>
*/

abstract class AbstractTemplateSyntaxElement implements TemplateSyntaxElement
{
	/**
     * @var TemplateSyntaxParserContext
     */
    protected $context;
    /**
     * @var StringInputStream
     */
    protected $input;
    /**
     * @var StringOutputStream
     */
    protected $output;

	protected function register(TemplateSyntaxParserContext $context, StringInputStream $input, StringOutputStream $output)
	{
        $this->context = $context;
        $this->input = $input;
        $this->output = $output;
	}

	protected function parse_elt(TemplateSyntaxElement $element)
	{
		$element->parse($this->context, $this->input, $this->output);
	}
}

?>
