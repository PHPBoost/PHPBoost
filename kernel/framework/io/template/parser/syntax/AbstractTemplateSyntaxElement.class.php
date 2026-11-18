<?php
/**
 * @package     IO
 * @subpackage  Template\parser\syntax
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 3.0 - 2010 06 10
 * @author      Loic ROUCHON <horn@phpboost.com>
*/

abstract class AbstractTemplateSyntaxElement implements TemplateSyntaxElement
{
    protected TemplateSyntaxParserContext $context;
    protected StringInputStream $input;
    protected StringOutputStream $output;

    protected function register(TemplateSyntaxParserContext $context, StringInputStream $input, StringOutputStream $output): void
    {
        $this->context = $context;
        $this->input = $input;
        $this->output = $output;
    }

    protected function parse_elt(TemplateSyntaxElement $element): void
    {
        $element->parse($this->context, $this->input, $this->output);
    }
}

?>
