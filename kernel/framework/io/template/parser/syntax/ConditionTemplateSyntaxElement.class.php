<?php
/**
 * @package     IO
 * @subpackage  Template\parser\syntax
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 07 10
*/

class ConditionTemplateSyntaxElement extends AbstractTemplateSyntaxElement
{
	private $ended = false;

	public static function is_element(StringInputStream $input)
	{
		return $input->assert_next('#\sIF\s');
	}

	public function parse(TemplateSyntaxParserContext $context, StringInputStream $input, StringOutputStream $output)
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

	private function process_start()
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

	private function process_end()
	{
		$this->ended = $this->input->consume_next('#\s*END(?:\s*IF)?\s*#');
		$this->output->write('\';}' . TemplateSyntaxElement::RESULT . '.=\'');
	}

	private function process_content()
	{
		$this->process_condition();
		if ($this->input->consume_next('#\sELSE\s#'))
		{
			$this->output->write('\';}else{' . TemplateSyntaxElement::RESULT . '.=\'');
			$this->process_condition();
		}
	}

	private function process_condition()
	{
        $this->parse_elt(new TextTemplateSyntaxElement());
	}

	private function missing_end()
	{
		throw new TemplateRenderingException('Missing condition end', $this->input);
	}
}
?>
