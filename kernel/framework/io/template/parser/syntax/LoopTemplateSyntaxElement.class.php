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
	private $input;
	private $output;
	private $ended = false;

	public static function is_element(StringInputStream $input)
	{
		return $input->assert_next('#\sSTART\s+(?:\w+\.)*\w+\s#');
	}
	
	public function parse(StringInputStream $input, StringOutputStream $output)
	{
		$this->input = $input;
		$this->output = $output;
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
		$loop_expression = $matches['loop'];
		$loop_var = '$_tmp_' . str_replace('.', '_', $matches['loop']);
		$this->output->write('\'; foreach ($_data->get_block(\'' . $loop_expression .
			'\') as ' . $loop_var . ') { $_result.=\'');
	}

	private function process_end()
	{
		$this->ended = $this->input->consume_next('#\sEND(?P<loop>\s+(?:\w+\.)*\w+)?\s#');
		$this->output->write('\';} $_result.=\'');
	}

	private function process_content()
	{
		$element = new BaseTemplateSyntaxElement();
		$element->parse($this->input, $this->output);
	}

	private function loop_end()
	{
		throw new TemplateParserException('Missing loop end', $this->input);
	}
}
?>