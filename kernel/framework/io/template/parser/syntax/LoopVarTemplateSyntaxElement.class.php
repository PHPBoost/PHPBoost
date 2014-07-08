<?php
/*##################################################
 *                    LoopVarTemplateSyntaxElement.class.php
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