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

class ArrayTemplateSyntaxElement extends AbstractTemplateSyntaxElement
{
	public static function is_element(StringInputStream $input)
	{
		return $input->assert_next('\s*\[');
	}

	public function parse(TemplateSyntaxParserContext $context, StringInputStream $input, StringOutputStream $output)
	{
        $this->register($context, $input, $output);
		if ($input->consume_next('\s*\['))
		{
			$output->write('array(');
			$this->content();
			$this->end();
		}
		else
		{
			throw new TemplateRenderingException('invalid array', $input);
		}
	}

	private function content()
	{
		$this->parse_elt(new ArrayContentTemplateSyntaxElement());
	}

	private function end()
	{
		if (!$this->input->consume_next('\]\s*'))
		{
			throw new TemplateRenderingException('invalid array: missing enclosing parenthesis', $this->input);
		}
		$this->output->write(')');
	}
}
?>
