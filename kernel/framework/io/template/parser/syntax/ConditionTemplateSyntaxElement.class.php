<?php
/*##################################################
 *                    ConditionTemplateSyntaxElement.class.php
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

class ConditionTemplateSyntaxElement extends AbstractTemplateSyntaxElement
{
	private $input;
	private $output;
	private $ended = false;

	public static function is_element(StringInputStream $input)
	{
		return $input->assert_next('#\sIF\s');
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
			$this->missing_end();
		}
	}

	private function process_start()
	{
		$this->input->consume_next('#\sIF\s+');
		$this->output->write('\'; if (');
		if ($this->input->consume_next('NOT\s+'))
		{
			$this->output->write('!');
		}
		$condition = new ExpressionContentTemplateSyntaxElement();
		$condition->parse($this->input, $this->output);
		if (!$this->input->consume_next('\s*#'))
		{
			throw new TemplateParserException('invalid condition statement', $this->input);
		}
		$this->output->write(') { $_result.=\'');
	}

	private function process_end()
	{
		$this->ended = $this->input->consume_next('#\s*END(?:\s*IF)?\s*#');
		$this->output->write('\';} $_result.=\'');
	}

	private function process_content()
	{
		$this->process_condition();
		if ($this->input->consume_next('#\sELSE\s#'))
		{
			$this->output->write('\';} else { $_result.=\'');
			$this->process_condition();
		}
	}

	private function process_condition()
	{
		$element = new BaseTemplateSyntaxElement();
		$element->parse($this->input, $this->output);
	}

	private function missing_end()
	{
		throw new TemplateParserException('Missing condition end', $this->input);
	}
}
?>