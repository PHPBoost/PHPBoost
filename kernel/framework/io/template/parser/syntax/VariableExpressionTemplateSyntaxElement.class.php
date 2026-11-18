<?php
/**
 * @package     IO
 * @subpackage  Template\parser\syntax
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 18
 * @since       PHPBoost 3.0 - 2010 09 05
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class VariableExpressionTemplateSyntaxElement extends AbstractTemplateSyntaxElement
{
	private bool $ended = false;

	public function parse(TemplateSyntaxParserContext $context, StringInputStream $input, StringOutputStream $output): void
	{
        $this->register($context, $input, $output);
		$this->do_parse();
	}

	private function do_parse(): void
	{
		$this->process_expression_start();
		$this->process_expression_content();
		$this->process_expression_end();
		if (!$this->ended)
		{
			$this->missing_expression_end();
		}
	}

	private function process_expression_start()
	{
		$this->output->write('\' . ');
	}

	private function process_expression_end(): void
	{
		$this->ended = $this->input->next() == '}';
		$this->output->write(' . \'');
	}

	private function process_expression_content(): void
	{
		$this->parse_elt(new VariableTemplateSyntaxElement());
	}

	private function missing_expression_end(): void
	{
		throw new TemplateRenderingException('Missing expression end \'}\'', $this->input);
	}
}
?>
