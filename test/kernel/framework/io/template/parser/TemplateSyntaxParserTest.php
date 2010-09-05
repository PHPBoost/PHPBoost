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
 * @desc
 * @author Benoit sautel <ben.popeye@gmail.com>, Loic Rouchon horn@phpboost.com
 */
class TemplateSyntaxParserTest extends PHPBoostUnitTestCase
{
	public function test_parse_text()
	{
		$input = 'this is a simple text';
		$output = '<?php $_result=\'this is a simple text\'; ?>';
		$this->assert_parse($input, $output);
	}

	public function test_parse_text_with_char_to_escape()
	{
		$input = 'this is a simpl\' text';
		$output = '<?php $_result=\'this is a simpl\\\' text\'; ?>';
		$this->assert_parse($input, $output);
	}

	public function test_parse_text_with_line_breaks()
	{
		$input = 'this is a simple
	text';
		$output = '<?php $_result=\'this is a simple
	text\'; ?>';
		$this->assert_parse($input, $output);
	}

	public function test_parse_text_with_simple_vars()
	{
		$input = 'this is {a} sim{pl}e text';
		$output = '<?php $_result=\'this is \' . $_data->get_var(\'a\') . ' .
			'\' sim\' . $_data->get_var(\'pl\') . \'e text\'; ?>';
		$this->assert_parse($input, $output);
	}

	public function test_parse_text_with_loop_vars()
	{
		$input = 'this is {a} sim{p.l}e
# START tex #
	# START tex.ext #
		{tex.ext.text}
	# END #
# END #';
		$output = '<?php $_result=\'this is \' . $_data->get_var(\'a\') . ' .
			'\' sim\' . $_data->get_var_from_list(\'l\', $_tmp_p[\'vars\']) . \'e
\'; foreach ($_data->get_block(\'tex\') as $_tmp_tex) { $_result.=\'
	\'; foreach ($_data->get_block(\'tex.ext\') as $_tmp_tex_ext) { $_result.=\'
		\' . $_data->get_var_from_list(\'text\', $_tmp_tex_ext[\'vars\']) . \'
	\';} $_result.=\'
\';} $_result.=\'\'; ?>';
		$this->assert_parse($input, $output);
	}

	public function test_parse_text_with_condition()
	{
		$input = '
# IF condition #
	coucou
# END #';
		$output = '<?php $_result=\'
\'; if ($_data->get_var(\'condition\')) { $_result.=\'
	coucou
\';} $_result.=\'\'; ?>';
		$this->assert_parse($input, $output);
	}

	public function test_parse_text_with_negative_condition()
	{
		$input = '
# IF NOT condition #
	coucou
# END #';
		$output = '<?php $_result=\'
\'; if (!$_data->get_var(\'condition\')) { $_result.=\'
	coucou
\';} $_result.=\'\'; ?>';
		$this->assert_parse($input, $output);
	}

	public function test_parse_text_with_condition_and_else()
	{
		$input = '
# IF condition #
	coucou
# ELSE #
	hello
# END #';
		$output = '<?php $_result=\'
\'; if ($_data->get_var(\'condition\')) { $_result.=\'
	coucou
\';} else { $_result.=\'
	hello
\';} $_result.=\'\'; ?>';
		$this->assert_parse($input, $output);
	}

	public function test_parse_text_with_loop_and_conditions()
	{
		$input = 'this is {a} sim{p.l}e
# START tex #
	# IF tex.coucou #
		# START tex.ext #
			{tex.ext.text}
		# END #
	# ELSE #
		pas de {coucou}
	# END #
# END #';
		$output = '<?php $_result=\'this is \' . $_data->get_var(\'a\') . ' .
			'\' sim\' . $_data->get_var_from_list(\'l\', $_tmp_p[\'vars\']) . \'e
\'; foreach ($_data->get_block(\'tex\') as $_tmp_tex) { $_result.=\'
	\'; if ($_data->get_var_from_list(\'coucou\', $_tmp_tex[\'vars\'])) { $_result.=\'
		\'; foreach ($_data->get_block(\'tex.ext\') as $_tmp_tex_ext) { $_result.=\'
			\' . $_data->get_var_from_list(\'text\', $_tmp_tex_ext[\'vars\']) . \'
		\';} $_result.=\'
	\';} else { $_result.=\'
		pas de \' . $_data->get_var(\'coucou\') . \'
	\';} $_result.=\'
\';} $_result.=\'\'; ?>';
		$this->assert_parse($input, $output);
	}

    public function test_function_call_without_parameters()
    {
        $input = 'this is a simple ${Function::call()}';
        $output = '<?php $_result=\'this is a simple \' . Function::call() . \'\'; ?>';
        $this->assert_parse($input, $output);
    }

    public function test_function_call_with_a_parameter()
    {
        $input = 'this is a simple ${Function::call(coucou)}';
        $output = '<?php $_result=\'this is a simple \' . Function::call($_data->get_var(\'coucou\')) . \'\'; ?>';
        $this->assert_parse($input, $output);
    }

    public function test_function_call_with_a_constant_parameter()
    {
        $input = 'this is a simple ${Function::call(\'coucou\')}';
        $output = '<?php $_result=\'this is a simple \' . Function::call(\'coucou\') . \'\'; ?>';
        $this->assert_parse($input, $output);
    }

    public function test_function_call_with_a_numeric_constant_parameter()
    {
        $input = 'this is a simple ${Function::call(42)}';
        $output = '<?php $_result=\'this is a simple \' . Function::call(42) . \'\'; ?>';
        $this->assert_parse($input, $output);
    }

    public function test_function_call_with_a_floating_constant_parameter()
    {
        $input = 'this is a simple ${Function::call(42.37)}';
        $output = '<?php $_result=\'this is a simple \' . Function::call(42.37) . \'\'; ?>';
        $this->assert_parse($input, $output);
    }

    public function test_function_call_with_mixed_parameters()
    {
        $input = 'this is a simple ${Function::call(42, \'coucou\',
        42.37)}';
        $output = '<?php $_result=\'this is a simple \' . Function::call(42, \'coucou\', 42.37) . \'\'; ?>';
        $this->assert_parse($input, $output);
    }

    public function test_imbricated_functions_call()
    {
        $input = 'this is a simple ${Function::call(SubFunction::callAgain())}';
        $output = '<?php $_result=\'this is a simple \' . Function::call(SubFunction::callAgain()) . \'\'; ?>';
        $this->assert_parse($input, $output);
    }

    public function test_include_tpl()
    {
        $input = 'this is a simple # INCLUDE tpl #';
        $output = '<?php $_result=\'this is a simple \';' . "\n" .
'$_subtemplate = $_data->get_subtemplate(\'tpl\');if ($_subtemplate !== null){$_result.=$_subtemplate->to_string();}' . "\n" .
'$_result.=\'\'; ?>';
        $this->assert_parse($input, $output);
    }

    public function test_simple_block_include_tpl()
    {
        $input = 'this is a simple # INCLUDE block.tpl #';
        $output = '<?php $_result=\'this is a simple \';' . "\n" .
'$_subtemplate = $_data->get_subtemplate_from_list(\'tpl\', $_tmp_block[\'subtemplates\']);if ($_subtemplate !== null){$_result.=$_subtemplate->to_string();}' . "\n" .
'$_result.=\'\'; ?>';
        $this->assert_parse($input, $output);
    }

    public function test_imbricated_block_include_tpl()
    {
        $input = 'this is a simple # INCLUDE block.imbricated.tpl #';
        $output = '<?php $_result=\'this is a simple \';' . "\n" .
'$_subtemplate = $_data->get_subtemplate_from_list(\'tpl\', $_tmp_imbricated[\'subtemplates\']);if ($_subtemplate !== null){$_result.=$_subtemplate->to_string();}' . "\n" .
'$_result.=\'\'; ?>';
        $this->assert_parse($input, $output);
    }
	
	private function assert_parse($input, $expected_output)
	{
		$parser = new TemplateSyntaxParser();
		$output = $parser->parse($input);
		$this->assertEquals($expected_output, $output);
	}
}
?>