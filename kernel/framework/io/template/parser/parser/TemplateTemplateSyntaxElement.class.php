<?php
/*##################################################
 *                    TemplateTemplateSyntaxElement.class.php
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

class TemplateTemplateSyntaxElement extends AbstractTemplateSyntaxElement
{
	/**
	 * @var StringInputStream
	 */
	private $input;
	/**
	 * @var StringOutputStream
	 */
	private $output;

	public function parse(StringInputStream $input, StringOutputStream $output)
	{
		$this->input = $input;
		$this->output = $output;
		$this->do_parse();
	}

	private function do_parse()
	{
		while ($this->input->has_next())
		{
			$element = null;
			$current = $this->input->next();
			if ($current == '{' && $this->input->assert_next('[^\s]'))
			{
				$element = $this->build_expression_elt();
			}
			elseif ($current == '#' && $this->input->assert_next('[\s]'))
			{
				$element = $this->build_statement_elt();
				if ($element === null)
				{	// every other statement if processed at a higher level
					return;
				}
			}
			else
			{
				$element = $this->build_text_elt();
			}
			$element->parse($this->input, $this->output);
		}
	}

	private function build_expression_elt()
	{
		return new ExpressionTemplateSyntaxElement();
	}

	private function build_text_elt()
	{
		$this->input->move(-1);
		return new TextTemplateSyntaxElement();
	}

	private function build_statement_elt()
	{
		$this->input->move(-1);
		if (ConditionTemplateSyntaxElement::is_element($this->input))
		{
			return $this->build_condition_elt();
		}
		elseif (LoopTemplateSyntaxElement::is_element($this->input))
		{
			return $this->build_loop_elt();
		}
		return null;
	}

	private function build_condition_elt()
	{
		return new ConditionTemplateSyntaxElement();
	}

	private function build_loop_elt()
	{
		return new LoopTemplateSyntaxElement();
	}
}
?>