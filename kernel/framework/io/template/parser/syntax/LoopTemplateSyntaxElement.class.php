<?php
/*##################################################
 *                    LoopTemplateSyntaxElement.class.php
 *                            -------------------
 *   begin                : July 10 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : horn@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

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
		if (strpos($loop_name, '.') === false)
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