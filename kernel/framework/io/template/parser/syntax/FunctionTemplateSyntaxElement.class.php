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
	public static function is_element(StringInputStream $input)
	{
		return $input->assert_next('\s*(?:\w+::)?\w+\(\s*');
	}

	public function parse(TemplateSyntaxParserContext $context, StringInputStream $input, StringOutputStream $output)
	{
        $this->register($context, $input, $output);
		$matches = array();
		if ($input->consume_next('(?:(?P<class>\w+)::)?(?P<method>\w+)\(', '', $matches))
		{
			$class = $matches['class'];
			$method = $matches['method'];
			$this->check_method_call($class, $method);
			$this->write($class, $method);
			$this->parameters();
			$this->end();
		}
		else
		{
			throw new TemplateRenderingException('invalid function call', $input);
		}
	}

	private function check_method_call($class, $method)
	{
		if (empty($class))
		{
			if (!method_exists('TemplateFunctions', $method))
			{
				throw new TemplateRenderingException('Unauthorized method call. Only ' . implode(', ', get_class_methods('TemplateFunctions')) .
                    ' functions calls and static methods calls are allowed', $this->input);
			}
		}
		elseif ($this->is_php_function($class))
		{
			if (!function_exists($method))
			{
                throw new TemplateRenderingException('PHP function ' . $method . '() does not exist', $this->input);
			}
		}
		elseif (!method_exists($class, $method))
		{
			throw new TemplateRenderingException('Static method ' . $class . '::' . $method . '() does not exist', $this->input);
		}
	}

	private function write($class, $method)
	{
		if (!empty($class))
		{
			if (!$this->is_php_function($class))
			{
				$this->output->write($class . '::');
			}
		}
		else
		{
			$this->output->write(TemplateSyntaxElement::FUNCTIONS . '->');
		}
		$this->output->write($method . '(');
	}

	private function parameters()
	{
		$this->parse_elt(new ParametersTemplateSyntaxElement());
	}

	private function end()
	{
		if (!$this->input->consume_next('\)'))
		{
			throw new TemplateRenderingException('invalid function call: missing enclosing parenthesis', $this->input);
		}
		$this->output->write(')');
	}

	private function is_php_function($prefix)
	{
		return strtoupper($prefix) == 'PHP';
	}
}

class InvalidTemplateFunctionCallException extends TemplateRenderingException {}

?>