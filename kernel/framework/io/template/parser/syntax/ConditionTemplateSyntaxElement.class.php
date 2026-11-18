<?php
/**
 * @package     IO
 * @subpackage  Template\parser\syntax
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 18
 * @since       PHPBoost 3.0 - 2010 07 10
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ConditionTemplateSyntaxElement extends AbstractTemplateSyntaxElement
{
	private bool $ended = false;

	public static function is_element(StringInputStream $input): bool
	{
		return $input->assert_next('#\sIF\s');
	}

	public function parse(TemplateSyntaxParserContext $context, StringInputStream $input, StringOutputStream $output): void
	{
        $this->register($context, $input, $output);
		$this->process_start();
		$this->process_content();
		$this->process_end();
		if (!$this->ended)
		{
			$this->missing_end();
		}
	}

	private function process_start(): void
	{
		$this->input->consume_next('#\sIF\s+');
		$this->output->write('\';if (');
		if ($this->input->consume_next('NOT\s+'))
		{
			$this->output->write('!');
		}
        $this->output->write(TemplateSyntaxElement::DATA . '->is_true(');
		$this->parse_elt(new ExpressionContentTemplateSyntaxElement());
		if (!$this->input->consume_next('\s*#'))
		{
			throw new TemplateRenderingException('invalid condition statement', $this->input);
		}
		$this->output->write(')){' . TemplateSyntaxElement::RESULT . '.=\'');
	}

	private function process_end(): void
	{
		$this->ended = $this->input->consume_next('#\s*END(?:\s*IF)?\s*#');
		$this->output->write('\';}' . TemplateSyntaxElement::RESULT . '.=\'');
	}

	private function process_content(): void
	{
		$this->process_condition();
		// Handle ELSEIF blocks
		while ($this->input->consume_next('#\sELSEIF\s+'))
		{
			$this->output->write('\';}elseif (');
			if ($this->input->consume_next('NOT\s+'))
			{
				$this->output->write('!');
			}
			$this->output->write(TemplateSyntaxElement::DATA . '->is_true(');
			$this->parse_elt(new ExpressionContentTemplateSyntaxElement());
			if (!$this->input->consume_next('\s*#'))
			{
				throw new TemplateRenderingException('invalid elseif statement', $this->input);
			}
			$this->output->write(')){' . TemplateSyntaxElement::RESULT . '.=\'');
			$this->process_condition();
		}
		// Handle final ELSE
		if ($this->input->consume_next('#\sELSE\s#'))
		{
			$this->output->write('\';}else{' . TemplateSyntaxElement::RESULT . '.=\'');
			$this->process_condition();
		}
	}

	private function process_condition(): void
	{
        $this->parse_elt(new TextTemplateSyntaxElement());
	}

	private function missing_end(): void
	{
		throw new TemplateRenderingException('Missing condition end', $this->input);
	}
}
?>
