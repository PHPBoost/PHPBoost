<?php
/**
 * @package     IO
 * @subpackage  Template\parser\syntax
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 18
 * @since       PHPBoost 3.0 - 2010 07 08
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class TextTemplateSyntaxElement extends AbstractTemplateSyntaxElement
{
	private bool $ended = false;
	private bool $escaped = false;

	public function parse(TemplateSyntaxParserContext $context, StringInputStream $input, StringOutputStream $output): void
	{
		$this->register($context, $input, $output);
		$this->do_parse();
	}

	private function do_parse(): void
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

	private function process_char(string $char): void
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

	private function process_escaped_char(string $char): void
	{
		if (!in_array($char, ['\\', '{', '}', '#', '$']))
		{
			$this->write('\\');
		}
		$this->escaped = false;
		$this->write($char);
	}

	private function write(string $char): void
	{
		$this->output->write(addcslashes($char, '\\\''));
	}

	private function parse_text(string $current): ?TemplateSyntaxElement
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

	private function build_statement_elt(): ?TemplateSyntaxElement
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
