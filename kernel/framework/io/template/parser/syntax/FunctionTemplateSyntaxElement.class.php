<?php
/*##################################################
 *                    FunctionTemplateSyntaxElement.class.php
 *                            -------------------
 *   begin                : September 04 2010
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

class FunctionTemplateSyntaxElement extends AbstractTemplateSyntaxElement
{
	private static $renderer_methods;

	public static function __static()
	{
		self::$renderer_methods = array('resources', 'i18n', 'i18njs', 'i18nraw', 'i18njsraw', 'escape', 'escapejs', 'set');
	}

	public static function is_element(StringInputStream $input)
	{
		return $input->assert_next('\s*(?:\w+::)?\w+\(\s*');
	}

    public function parse(StringInputStream $input, StringOutputStream $output)
    {
        $matches = array();
        if ($input->consume_next('(?:(?P<class>\w+)::)?(?P<method>\w+)\(', '', $matches))
        {
            $class = $matches['class'];
            $method = $matches['method'];
            $this->check_method_call($class, $method, $input, $output);
            $this->write($class, $method, $output);
            $this->parameters($input, $output);
            $this->end($input, $output);
        }
        else
        {
            throw new TemplateParserException('invalid function call', $input);
        }
    }

    private function check_method_call($class, $method, StringInputStream $input, StringOutputStream $output)
    {
        if (empty($class))
        {
            if (!in_array($method, self::$renderer_methods))
            {
                throw new TemplateParserException('Unauthorized method call. Only ' . implode(', ', self::$renderer_methods) .
                   ' functions calls and static methods calls are allowed', $input);
            }
            else
            {
                $output->write('$_functions->');
            }
        }
    }
    
    private function write($class, $method, StringOutputStream $output)
    {
        if (!empty($class))
        {
            if (strtoupper($class) != 'PHP')
            {
                $output->write($class . '::');
            }
        }
        $output->write($method . '(');
    }

	private function parameters(StringInputStream $input, StringOutputStream $output)
	{
		$parameters = new ParametersTemplateSyntaxElement();
		$parameters->parse($input, $output);
	}

	private function end(StringInputStream $input, StringOutputStream $output)
	{
		if (!$input->consume_next('\)'))
		{
			throw new TemplateParserException('invalid function call: missing enclosing parenthesis', $input);
		}
		$output->write(')');
	}
}
?>