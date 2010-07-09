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
 * variable = "\w+"
 *
 */

/**
 * @package {@package}
 * @desc
 * @author Benoit sautel <ben.popeye@gmail.com>, Loic Rouchon horn@phpboost.com
 */
class TemplateSyntaxParserTest extends PHPBoostUnitTestCase
{
	public function test_parse_text()
	{
		$input = 'this is a simple text';
		$output = 'this is a simple text';
		$this->assert_parse($input, $output);
	}

	public function test_parse_text_with_expressions()
	{
		$input = 'this is {a} sim{pl}e text';
		$output = 'this is  sime text';
		$this->assert_parse($input, $output);
	}

	private function assert_parse($input, $expected_output)
	{
		$parser = new TemplateSyntaxParser();
		$output = $parser->parse(new StringInputStream($input));
		$this->assertEquals($expected_output, $output);
	}
}
?>