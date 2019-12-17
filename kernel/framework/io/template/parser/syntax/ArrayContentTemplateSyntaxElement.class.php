<?php
/**
 * @package     IO
 * @subpackage  Template\parser\syntax
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 09 05
*/

class ArrayContentTemplateSyntaxElement extends AbstractTemplateSyntaxElement
{
	public function parse(TemplateSyntaxParserContext $context, StringInputStream $input, StringOutputStream $output)
	{
		$this->register($context, $input, $output);
        while (!$input->assert_next('\s*\]'))
        {
            $this->process_key();
            $this->process_value();
            if ($input->consume_next('\s*,\s*'))
            {
                $output->write(', ');
            }
            else if (!$input->assert_next('\s*\]\s*'))
            {
                throw new TemplateRenderingException('invalid array definition, missing "," or "]"', $input);
            }
        }
	}

	private function process_key()
	{
		$matches = array();
		if ($this->input->consume_next('\s*(?P<key>(?:[0-9]+)|(?:\'[^\']+\'))\s*:\s*', '', $matches))
		{
			$this->output->write($matches['key'] . '=>');
		}
	}

	private function process_value()
	{
		$this->parse_elt(new ExpressionContentTemplateSyntaxElement());
	}
}
?>
