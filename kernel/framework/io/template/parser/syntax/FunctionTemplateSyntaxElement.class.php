<?php
/**
 * @package     IO
 * @subpackage  Template\parser\syntax
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 10 28
 * @since       PHPBoost 3.0 - 2010 09 04
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

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
		return TextHelper::strtoupper($prefix) == 'PHP';
	}
}

class InvalidTemplateFunctionCallException extends TemplateRenderingException {}

?>
