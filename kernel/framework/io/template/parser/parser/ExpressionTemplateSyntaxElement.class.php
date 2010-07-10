<?php
/*##################################################
 *                    EmptyTemplateSyntaxElement.class.php
 *                            -------------------
 *   begin                : July 08 2010
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

class ExpressionTemplateSyntaxElement extends AbstractTemplateSyntaxElement
{
	private $input;
	private $output;
	private $ended = false;

	public function parse(StringInputStream $input, StringOutputStream $output)
	{
		$this->input = $input;
		$this->output = $output;
		$this->do_parse();
	}

	private function do_parse()
	{
		$this->process_expression_start();
		$this->process_expression_content();
		$this->process_expression_end();
		if (!$this->ended)
		{
			$this->prematured_expression_end();
		}
	}

	private function process_expression_start()
	{
		$this->output->write('\' . ');
	}

	private function process_expression_end()
	{
		$this->ended = $this->input->next() == '}';
		$this->output->write(' . \'');
	}

	private function process_expression_content()
	{
		$element = null;
		if ($this->is_function())
		{
			throw new NotYetImplementedException();
		}
		elseif (VariableTemplateSyntaxElement::is_element($this->input))
		{
			$element = new VariableTemplateSyntaxElement();
		}
		elseif ($this->is_constant())
		{	
			throw new NotYetImplementedException();
		}
		else
		{
			throw new DomainException('bad expression statement', 0);
		}
		$element->parse($this->input, $this->output);
	}

	private function prematured_expression_end()
	{
		throw new OutOfBoundsException('Missing expression end \'}\'', 0);
	}

	private function is_function()
	{
		return $this->input->assert_next('(?:\w+::)?\w+\(');
	}

	private function is_constant()
	{
		return $this->input->assert_next('// TODO');
	}
}
?>