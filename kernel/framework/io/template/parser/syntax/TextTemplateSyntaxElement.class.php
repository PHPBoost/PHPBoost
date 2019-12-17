<?php
/**
 * @package     IO
 * @subpackage  Template\parser\syntax
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 07 08
*/

class TextTemplateSyntaxElement extends AbstractTemplateSyntaxElement
{
	private $ended = false;
	private $escaped = false;

	public function parse(TemplateSyntaxParserContext $context, StringInputStream $input, StringOutputStream $output)
	{
		$this->register($context, $input, $output);
		$this->do_parse();
	}

	private function do_parse()
	{
		while ($this->input->has_next() && !$this->ended)
		{
			$current = $this->input->next();
			if (!$this->escaped)
			{
				$this->process_char($current);
			}
			else
			{
				$this->process_escaped_char($current);
			}
		}
	}

	private function process_char($char)
	{
		if ($char == '\\' && $this->input->assert_next('[\\{}$#]'))
		{
			$this->escaped = true;
		}
		else
		{
			$element = $this->parse_text($char);
			if ($element != null)
			{
				$this->parse_elt($element);
			}
			elseif (!$this->ended)
			{
				$this->write($char);
			}
		}
	}

	private function process_escaped_char($char)
	{
		if (!in_array($char, array('\\', '{', '}', '#', '$')))
		{
			$this->write('\\');
		}
		$this->escaped = false;
		$this->write($char);
	}

	private function write($char)
	{
		$this->output->write(addcslashes($char, '\\\''));
	}

	private function parse_text($current)
	{
		if ($current == '{' && $this->input->assert_next('(?:@(?:H\|)?)?(?:\w+\.)*\w+\}'))
		{
			return new VariableExpressionTemplateSyntaxElement();
		}
		elseif ($current == '$' && $this->input->assert_next('\{'))
		{
			return new ExpressionTemplateSyntaxElement();
		}
		elseif ($current == '#' && $this->input->assert_next('\{'))
		{
			return new FunctionCallTemplateSyntaxElement();
		}
		elseif ($current == '#' && $this->input->assert_next('[\s]'))
		{
			return $this->build_statement_elt();
		}
		elseif ($current == '<' && $this->input->assert_next('\?php'))
		{
			return new PHPTemplateSyntaxElement();
		}
		return null;
	}

	private function build_statement_elt()
	{
		$this->input->move(-1);
		if (ConditionTemplateSyntaxElement::is_element($this->input))
		{
			return new ConditionTemplateSyntaxElement();
		}
		elseif (LoopTemplateSyntaxElement::is_element($this->input))
		{
			return new LoopTemplateSyntaxElement();
		}
		elseif (IncludeTemplateSyntaxElement::is_element($this->input))
		{
			return new IncludeTemplateSyntaxElement();
		}
		else
		{
			$this->ended = true;
			return null;
		}
	}
}
?>
