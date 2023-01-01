<?php
/**
 * @package     IO
 * @subpackage  Template\parser\syntax
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 07 08
*/

class ExpressionContentTemplateSyntaxElement extends AbstractTemplateSyntaxElement
{
	private $ended = false;

	public function parse(TemplateSyntaxParserContext $context, StringInputStream $input, StringOutputStream $output)
	{
        $this->register($context, $input, $output);
		$this->do_parse();
	}

	private function do_parse()
	{
		$element = null;
		if (ArrayTemplateSyntaxElement::is_element($this->input))
		{
            $element = new ArrayTemplateSyntaxElement();
		}
		elseif (FunctionTemplateSyntaxElement::is_element($this->input))
		{
			$element = new FunctionTemplateSyntaxElement();
		}
		elseif (ConstantTemplateSyntaxElement::is_element($this->input))
		{
			$element = new ConstantTemplateSyntaxElement();
		}
		elseif (VariableTemplateSyntaxElement::is_element($this->input))
		{
			$element = new VariableTemplateSyntaxElement();
		}
		else
		{
			throw new TemplateRenderingException('bad expression statement', $this->input);
		}
		$this->parse_elt($element);
	}
}
?>
