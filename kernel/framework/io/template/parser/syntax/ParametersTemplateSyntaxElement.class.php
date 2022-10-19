<?php
/**
 * @package     IO
 * @subpackage  Template\parser\syntax
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 09 04
*/

class ParametersTemplateSyntaxElement extends AbstractTemplateSyntaxElement
{
    public function parse(TemplateSyntaxParserContext $context, StringInputStream $input, StringOutputStream $output)
	{
        $this->register($context, $input, $output);
		while (!$input->assert_next('\s*\)\s*'))
		{
			$this->parse_elt(new ExpressionContentTemplateSyntaxElement());
            if ($input->consume_next('\s*,\s*'))
            {
                $output->write(', ');
            }
            else if (!$input->assert_next('\s*\)\s*'))
            {
                throw new TemplateRenderingException('invalid function call, missing "," or ")"', $input);
            }
		}
	}
}
?>
