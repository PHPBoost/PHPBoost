<?php
/**
 * @package     IO
 * @subpackage  Template\parser\syntax
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 07 08
*/

class ExpressionTemplateSyntaxElement extends AbstractTemplateSyntaxElement
{
	private $ended = false;

	public function parse(TemplateSyntaxParserContext $context, StringInputStream $input, StringOutputStream $output)
	{
        $this->register($context, $input, $output);
		$this->do_parse();
	}

	private function do_parse()
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
        $this->input->consume_next('\{');
        $this->output->write('\' . ');
	}

	private function process_expression_end()
	{
		$this->ended = $this->input->next() == '}';
		$this->output->write(' . \'');
	}

	private function process_expression_content()
	{
		$this->parse_elt(new ExpressionContentTemplateSyntaxElement());
	}

	private function missing_expression_end()
	{
		throw new TemplateRenderingException('Missing expression end \'}\'', $this->input);
	}
}
?>
