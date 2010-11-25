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
 * template = (variableExpression | expression | condition | loop | include | text)*
 * variableExpression = "{", variable, "}"
 * expression = "${", expressionContent, "}"
 * condition = "# IF ", "NOT "?, expression, "#", template, ("# ELSE #, template)?, "# ENDIF #"
 * loop = "# START ", expression, " #", template, "# END (?:name)? #"
 * include = "# INCLUDE ", name, " #"
 * text = .+
 * expressionContent = array | function | variable | constant
 * function = "\(\w+::\)?\w+\(", parameters, "\)"
 * parameters = expressionContent | (expressionContent, (",", expressionContent)+)
 * variable = simpleVar | loopVar
 * constant = "'.+'" | [0-9]+ | array
 * array = "array(", arrayContent, ")"
 * arrayContent = arrayElement | (arrayElement, (",", arrayElement)+)
 * arrayElement = expressionContent | ("'\w+'\s*=>\s*", expressionContent)
 * simpleVar = "\w+"
 * loopVar = "(\w+\.)+\w+"
 */

/**
 * @package {@package}
 * @desc
 * @author Benoit sautel <ben.popeye@gmail.com>, Loic Rouchon horn@phpboost.com
 */
class TemplateSyntaxParser implements TemplateParser
{
	/**
	 * @var StringInputStream
	 */
	private $input;
	/**
	 * @var StringOutputStream
	 */
	private $output;

	public function parse($content)
	{
		$this->input = new StringInputStream($content);
		$this->output = new StringOutputStream();
		$this->output->write('<?php ' . TemplateSyntaxElement::RESULT . '=\'');
		$this->do_parse();
		$this->output->write('\'; ?>');
		return $this->output->to_string();
	}

	private function do_parse()
	{
		$template_element = new TextTemplateSyntaxElement();
		$template_element->parse(new TemplateSyntaxParserContext(), $this->input, $this->output);
		if ($this->input->has_next())
		{
			throw new TemplateRenderingException('Unknown statement', $this->input);
		}
	}
}
?>