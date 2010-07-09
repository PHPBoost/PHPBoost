<?php
/*##################################################
 *                        TemplateSyntaxParser.class.php
 *                            -------------------
 *   begin                : June 17 2010
 *   copyright            : (C) 2010 Benoit Sautel, Loic Rouchon
 *   email                : ben.popeye@phpboost.com, horn@phpboost.com
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


/*
 * template = (expression | condition | loop | text)*
 * expression = "{", (function | variable), "}"
 * condition = "# IF ", "NOT "?, expression, "#", template, ("# ELSE #, template)?, "# ENDIF #"
 * loop = "# START ", expression, "#", template, "# END (?:name)? #"
 * text = .+
 * function = "\(\w+::\)?\w+\(", parameters, "\)"
 * parameters = parameter | (parameter, (",", parameter)+)
 * parameter = function | variable
 * variable = simplevar | blockvar
 * simplevar = "\w+"
 * blockvar = "(\w+\.)+\w+"
 * 
 */


/**
 * @package {@package}
 * @desc 
 * @author Benoit sautel <ben.popeye@gmail.com>, Loic Rouchon horn@phpboost.com
 */
class TemplateSyntaxParser
{
	/**
	 * @var StringInputStream
	 */
	private $input;
	/**
	 * @var StringOutputStream
	 */
	private $output;
	
	public function parse(StringInputStream $input)
	{
		$this->input = $input;
		$this->output = new StringOutputStream();
		$this->doParse();
		return $this->output->to_string();
	}
	
	private function doParse()
	{
		while ($this->input->has_next())
		{
			$element = null;
			$current = $this->input->next();
			if ($current == '{')
			{
				$element = $this->build_expression_elt();
			}
			elseif ($current == '#')
			{
				$element = $this->build_statement_elt();
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
		if ($this->input->assert_next('\s*IF\s'))
		{
			return $this->build_condition_elt();
		}
		elseif ($this->input->assert_next('\s*START\s'))
		{
			return $this->build_loop_elt();
		}
		else
		{
			throw new Exception('Bad Statement');
		}
	}
	
	private function build_condition_elt()
	{
		return new EmptyTemplateSyntaxElement();
	}
	
	private function build_loop_elt()
	{	
		return new EmptyTemplateSyntaxElement();
	}
}
?>