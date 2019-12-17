<?php
/**
 * @package     IO
 * @subpackage  Template\parser\syntax
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 10 28
 * @since       PHPBoost 3.0 - 2010 07 10
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class LoopTemplateSyntaxElement extends AbstractTemplateSyntaxElement
{
	private $ended = false;

	public static function is_element(StringInputStream $input)
	{
		return $input->assert_next('#\sSTART\s+(?:\w+\.)*\w+\s#');
	}

	public function parse(TemplateSyntaxParserContext $context, StringInputStream $input, StringOutputStream $output)
	{
        $this->register($context, $input, $output);
		$this->do_parse();
	}

	private function do_parse()
	{
		$this->process_start();
		$this->process_content();
		$this->process_end();
		if (!$this->ended)
		{
			$this->loop_end();
		}
	}

	private function process_start()
	{
		$matches = array();
		$this->input->consume_next('#\sSTART\s+(?P<loop>(?:\w+\.)*\w+)\s#', '', $matches);
		$loop_name = $matches['loop'];
		$this->context->enter_loop($loop_name);

		$exploded = explode('.', $loop_name);
		$name = array_pop($exploded);

		$loop_var = $this->get_tmp_var_name($loop_name);
		$this->output->write('\';foreach(' . TemplateSyntaxElement::DATA . '->');
		if (TextHelper::strpos($loop_name, '.') === false)
		{
			$this->output->write('get_block(\'' . $name . '\')');
		}
		else
		{
			$parent_loop = $this->get_tmp_var_name(implode('.', $exploded));
			$this->output->write('get_block_from_list(\'' . $name . '\', ' . $parent_loop . ')');
		}
		$this->output->write(' as ' . $loop_var . '){' . TemplateSyntaxElement::RESULT . '.=\'');
	}

	private function get_tmp_var_name($loop_name)
	{
		return '$_tmp_' . str_replace('.', '_', $loop_name);
	}

	private function process_end()
	{
		$this->ended = $this->input->consume_next('#\sEND(?P<loop>\s+(?:\w+\.)*\w+)?\s#');
		$this->context->exit_loop();
		$this->output->write('\';}' . TemplateSyntaxElement::RESULT . '.=\'');
	}

	private function process_content()
	{
		$this->parse_elt(new TextTemplateSyntaxElement());
	}

	private function loop_end()
	{
		throw new TemplateRenderingException('Missing loop end', $this->input);
	}
}
?>
