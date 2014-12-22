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
 * expressionContent = function | variable | constant
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
 * @desc
 * @author Benoit sautel <ben.popeye@gmail.com>, Loic Rouchon horn@phpboost.com
 */
class TemplateSyntaxParserTest extends PHPBoostUnitTestCase
{
	public function test_parse_text()
	{
		$input = 'this is a simple text';
		$output = '<?php $_result=\'this is a simple text\'; ?>';
		$this->assert_parse($output, $input);
	}

	public function test_php()
	{
		$input = 'this is a <?php simple(); ?> text';
		$output = '<?php $_result=\'this is a \';$_ob_length=ob_get_length();' .
        'simple();if (ob_get_length()>$_ob_length){$_content=ob_get_clean();$_result.=substr($_content, $_ob_length);echo substr($_content, 0, $_ob_length);}$_result.=\' text\'; ?>';
		$this->assert_parse($output, $input);
	}

	public function test_char_to_escape()
	{
		$input = 'this is a simpl\' text';
		$output = '<?php $_result=\'this is a simpl\\\' text\'; ?>';
		$this->assert_parse($output, $input);
	}

	public function test_line_breaks()
	{
		$input = 'this is a simple
	text';
		$output = '<?php $_result=\'this is a simple
	text\'; ?>';
		$this->assert_parse($output, $input);
	}

	public function test_simple_vars()
	{
		$input = 'this is {a} sim{pl}e text';
		$output = '<?php $_result=\'this is \' . $_data->get(\'a\') . ' .
			'\' sim\' . $_data->get(\'pl\') . \'e text\'; ?>';
		$this->assert_parse($output, $input);
	}

    public function test_lang_var()
    {
        $input = 'this is a {@lang.var}';
        $output = '<?php $_result=\'this is a \' . $_functions->i18n(\'lang.var\') . \'\'; ?>';
        $this->assert_parse($output, $input);
    }

    public function test_html_lang_var()
    {
        $input = 'this is a {@H|html.lang.var}';
        $output = '<?php $_result=\'this is a \' . $_functions->i18nraw(\'html.lang.var\') . \'\'; ?>';
        $this->assert_parse($output, $input);
    }

	public function test_loop_vars()
	{
		$input = 'this is {a} sim{pl}e
# START tex #
	# START tex.ext #
		{tex.ext.text}
	# END #
# END #';
		$output = '<?php $_result=\'this is \' . $_data->get(\'a\') . ' .
			'\' sim\' . $_data->get(\'pl\') . \'e
\';foreach($_data->get_block(\'tex\') as $_tmp_tex){$_result.=\'
	\';foreach($_data->get_block_from_list(\'ext\', $_tmp_tex) as $_tmp_tex_ext){$_result.=\'
		\' . $_data->get_from_list(\'text\', $_tmp_tex_ext) . \'
	\';}$_result.=\'
\';}$_result.=\'\'; ?>';
		$this->assert_parse($output, $input);
	}


    public function test_loop_vars_outside_loop_scope()
    {
        $input = '# START loop1 #
    # START loop1.loop2 #
        {loop1.loop3.MYVAR}
    # END #
# END #';
        try
        {
            $this->assert_parse(null, $input);
            $this->fail('expecting exception TemplateRenderingException');
        } catch (TemplateRenderingException $ex)
        {
            // Successful
        }
    }

	public function test_condition()
	{
		$input = '
# IF condition #
	coucou
# END #';
		$output = '<?php $_result=\'
\';if ($_data->is_true($_data->get(\'condition\'))){$_result.=\'
	coucou
\';}$_result.=\'\'; ?>';
		$this->assert_parse($output, $input);
	}

	public function test_negative_condition()
	{
		$input = '
# IF NOT condition #
	coucou
# END #';
		$output = '<?php $_result=\'
\';if (!$_data->is_true($_data->get(\'condition\'))){$_result.=\'
	coucou
\';}$_result.=\'\'; ?>';
		$this->assert_parse($output, $input);
	}

	public function test_condition_and_else()
	{
		$input = '
# IF condition #
	coucou
# ELSE #
	hello
# END #';
		$output = '<?php $_result=\'
\';if ($_data->is_true($_data->get(\'condition\'))){$_result.=\'
	coucou
\';}else{$_result.=\'
	hello
\';}$_result.=\'\'; ?>';
		$this->assert_parse($output, $input);
	}

	public function test_loop_and_conditions()
	{
		$input = 'this is {a} sim{pl}e
# START tex #
	# IF tex.coucou #
		# START tex.ext #
			{tex.ext.text}
		# END #
	# ELSE #
		pas de {coucou}
	# END #
# END #';
		$output = '<?php $_result=\'this is \' . $_data->get(\'a\') . ' .
			'\' sim\' . $_data->get(\'pl\') . \'e
\';foreach($_data->get_block(\'tex\') as $_tmp_tex){$_result.=\'
	\';if ($_data->is_true($_data->get_from_list(\'coucou\', $_tmp_tex))){$_result.=\'
		\';foreach($_data->get_block_from_list(\'ext\', $_tmp_tex) as $_tmp_tex_ext){$_result.=\'
			\' . $_data->get_from_list(\'text\', $_tmp_tex_ext) . \'
		\';}$_result.=\'
	\';}else{$_result.=\'
		pas de \' . $_data->get(\'coucou\') . \'
	\';}$_result.=\'
\';}$_result.=\'\'; ?>';
		$this->assert_parse($output, $input);
	}

	public function test_tpl_function()
	{
		$input = 'this is a simple ${call()}';
		try
		{
			$this->assert_parse(null, $input);
			$this->fail('unauthorized method call not detected');
		}
		catch (TemplateRenderingException $ex)
		{
			// Successful
		}
	}

	public function test_function_call()
	{
		$input = 'this is a simple #{resources(\'main\')}';
		$output = '<?php $_result=\'this is a simple \';$_functions->resources(\'main\');$_result=\'\'; ?>';
		$this->assert_parse($output, $input);
	}

	public function test_function()
	{
		$input = 'this is a simple ${resources(\'main\')}';
		$output = '<?php $_result=\'this is a simple \' . $_functions->resources(\'main\') . \'\'; ?>';
		$this->assert_parse($output, $input);
	}

	public function test_php_function()
	{
		$input = 'this is a simple ${PHP::strlen(\'toto\')}';
		$output = '<?php $_result=\'this is a simple \' . strlen(\'toto\') . \'\'; ?>';
		$this->assert_parse($output, $input);
	}

	public function test_unexisting_php_function()
	{
		$input = 'this is a simple ${PHP::strlen2()}';
		try
		{
			$this->assert_parse(null, $input);
			$this->fail('expecting exception TemplateRenderingException');
		} catch (TemplateRenderingException $ex)
		{
			// Successful
		}
	}

	public function test_unexisting_static_method()
	{
		$input = 'this is a simple ${Static::method()}';
		try
		{
			$this->assert_parse(null, $input);
			$this->fail('expecting exception TemplateRenderingException');
		} catch (TemplateRenderingException $ex)
		{
			// Successful
		}
	}

	public function test_function_without_parameters()
	{
		$input = 'this is a simple ${AppContext::get_uid()}';
		$output = '<?php $_result=\'this is a simple \' . AppContext::get_uid() . \'\'; ?>';
		$this->assert_parse($output, $input);
	}

	public function test_function_with_a_parameter()
	{
		$input = 'this is a simple ${AppContext::get_uid(coucou)}';
		$output = '<?php $_result=\'this is a simple \' . AppContext::get_uid($_data->get(\'coucou\')) . \'\'; ?>';
		$this->assert_parse($output, $input);
	}

	public function test_function_with_a_constant_parameter()
	{
		$input = 'this is a simple ${AppContext::get_uid(\'coucou\')}';
		$output = '<?php $_result=\'this is a simple \' . AppContext::get_uid(\'coucou\') . \'\'; ?>';
		$this->assert_parse($output, $input);
	}

	public function test_function_with_a_numeric_constant_parameter()
	{
		$input = 'this is a simple ${AppContext::get_uid(42)}';
		$output = '<?php $_result=\'this is a simple \' . AppContext::get_uid(42) . \'\'; ?>';
		$this->assert_parse($output, $input);
	}

	public function test_function_with_a_floating_constant_parameter()
	{
		$input = 'this is a simple ${AppContext::get_uid(42.37)}';
		$output = '<?php $_result=\'this is a simple \' . AppContext::get_uid(42.37) . \'\'; ?>';
		$this->assert_parse($output, $input);
	}

	public function test_function_with_boolean_constant_parameters()
	{
		$input = 'this is a simple ${AppContext::get_uid(true, false)}';
		$output = '<?php $_result=\'this is a simple \' . AppContext::get_uid(true, false) . \'\'; ?>';
		$this->assert_parse($output, $input);
	}

	public function test_function_with_mixed_parameters()
	{
		$input = 'this is a simple ${AppContext::get_uid(42, \'coucou\',
        42.37)}';
		$output = '<?php $_result=\'this is a simple \' . AppContext::get_uid(42, \'coucou\', 42.37) . \'\'; ?>';
		$this->assert_parse($output, $input);
	}

	public function test_imbricated_functions_call()
	{
		$input = 'this is a simple ${AppContext::get_uid(AppContext::get_uid())}';
		$output = '<?php $_result=\'this is a simple \' . AppContext::get_uid(AppContext::get_uid()) . \'\'; ?>';
		$this->assert_parse($output, $input);
	}

	public function test_include_tpl()
	{
		$input = 'this is a simple # INCLUDE tpl #';
		$output = '<?php $_result=\'this is a simple \';' .
'$_subtpl=$_data->get(\'tpl\');if ($_subtpl !== null){$_result.=$_subtpl->render();}' .
'$_result.=\'\'; ?>';
		$this->assert_parse($output, $input);
	}

	public function test_includefile_tpl()
	{
		$input = 'this is a simple # INCLUDEFILE my/file.tpl #';
		$output = '<?php $_result=\'this is a simple \';' .
'$_subtpl=new FileTemplate(\'my/file.tpl\');$_subtpl->set_data($_data);$_result.=$_subtpl->render();' .
'$_result.=\'\'; ?>';
		$this->assert_parse($output, $input);
	}

	public function test_simple_block_include_tpl()
	{
		$input = 'this is a simple # INCLUDE block.tpl #';
		$output = '<?php $_result=\'this is a simple \';' .
'$_subtpl=$_data->get_from_list(\'tpl\', $_tmp_block);if ($_subtpl !== null){$_result.=$_subtpl->render();}' .
'$_result.=\'\'; ?>';
		$this->assert_parse($output, $input);
	}

	public function test_imbricated_block_include_tpl()
	{
		$input = 'this is a simple # INCLUDE block.imbricated.tpl #';
		$output = '<?php $_result=\'this is a simple \';' .
'$_subtpl=$_data->get_from_list(\'tpl\', $_tmp_imbricated);if ($_subtpl !== null){$_result.=$_subtpl->render();}' .
'$_result.=\'\'; ?>';
		$this->assert_parse($output, $input);
	}

	public function test_parse_empty_array()
	{
		$input = 'this is a simple ${AppContext::get_uid([])}';
		$output = '<?php $_result=\'this is a simple \' . AppContext::get_uid(array()) . \'\'; ?>';
		$this->assert_parse($output, $input);
	}

	public function test_parse_one_parameter_array()
	{
		$input = 'this is a simple ${AppContext::get_uid([\'coucou\'])}';
		$output = '<?php $_result=\'this is a simple \' . AppContext::get_uid(array(\'coucou\')) . \'\'; ?>';
		$this->assert_parse($output, $input);
	}

	public function test_parse_multi_parameters_array()
	{
		$input = 'this is a simple ${AppContext::get_uid([\'coucou\', COUCOU, 42.3])}';
		$output = '<?php $_result=\'this is a simple \' . AppContext::get_uid(array(\'coucou\', $_data->get(\'COUCOU\'), 42.3)) . \'\'; ?>';
		$this->assert_parse($output, $input);
	}

	public function test_parse_one_parameter_map()
	{
		$input = 'this is a simple ${AppContext::get_uid([\'key\': \'coucou\'])}';
		$output = '<?php $_result=\'this is a simple \' . AppContext::get_uid(array(\'key\'=>\'coucou\')) . \'\'; ?>';
		$this->assert_parse($output, $input);
	}

	private function assert_parse($expected, $input)
	{
		$parser = new TemplateSyntaxParser();
		$output = $parser->parse($input);
		$this->assertEquals($expected, $output);
	}
}
?>