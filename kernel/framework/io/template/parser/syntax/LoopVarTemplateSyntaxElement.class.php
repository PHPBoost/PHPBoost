<?php
/**
 * @package     IO
 * @subpackage  Template\parser\syntax
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 07 10
*/

class LoopVarTemplateSyntaxElement extends AbstractTemplateSyntaxElement
{
	public static function is_element(StringInputStream $input)
	{
		return $input->assert_next('(?:\w+\.)+\w+');
	}

	public function parse(TemplateSyntaxParserContext $context, StringInputStream $input, StringOutputStream $output)
	{
		$this->register($context, $input, $output);
		$matches = array();
		if ($input->consume_next('(?P<loop>\w+(?:\.\w+)*)\.(?P<var>\w+)', '', $matches))
		{
			$loop_name =  $matches['loop'];
            $varname =  $matches['var'];
			$this->check_loop($loop_name, $varname);
			$loop_var = '$_tmp_' . str_replace('.', '_', $loop_name);
			$output->write(TemplateSyntaxElement::DATA . '->get_from_list(\'' . $varname . '\', ' . $loop_var . ')');
		}
		else
		{
			throw new TemplateRenderingException('invalid loop variable name', $input);
		}
	}

	private function check_loop($loop, $var)
	{
		if (!$this->context->is_in_loop($loop))
		{
			throw new TemplateRenderingException('Variable {' . $loop . '.' . $var .
                '} is outsite of loop "' . $loop . '" scope.' . "\n" . 'loops scopes: ' .
			    '[' . implode(', ', $this->context->loops_scopes()) . ']', $this->input);
		}
	}
}
?>
