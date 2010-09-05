<?php
/*##################################################
 *                    ExpressionContentTemplateSyntaxElement.class.php
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

class ExpressionContentTemplateSyntaxElement extends AbstractTemplateSyntaxElement
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
		$element = null;
		if (ArrayTemplateSyntaxElement::is_element($this->input))
		{
            $element = new ArrayTemplateSyntaxElement();
		}
		elseif (FunctionTemplateSyntaxElement::is_element($this->input))
		{
			$element = new FunctionTemplateSyntaxElement();
		}
		elseif (VariableTemplateSyntaxElement::is_element($this->input))
		{
			$element = new VariableTemplateSyntaxElement();
		}
		elseif (ConstantTemplateSyntaxElement::is_element($this->input))
		{	
			$element = new ConstantTemplateSyntaxElement();
		}
		else
		{
			throw new TemplateParserException('bad expression statement', $this->input);
		}
		$element->parse($this->input, $this->output);
	}
}
?>