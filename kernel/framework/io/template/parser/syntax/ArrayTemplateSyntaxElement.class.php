<?php
/*##################################################
 *                    ArrayTemplateSyntaxElement.class.php
 *                            -------------------
 *   begin                : September 05 2010
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

class ArrayTemplateSyntaxElement extends AbstractTemplateSyntaxElement
{
	public static function is_element(StringInputStream $input)
	{
		return $input->assert_next('\s*\[');
	}

	public function parse(StringInputStream $input, StringOutputStream $output)
	{
		if ($input->consume_next('\s*\['))
		{
			$output->write('array(');
			$this->content($input, $output);
			$this->end($input, $output);
		}
		else
		{
			throw new TemplateParserException('invalid array', $input);
		}
	}

	private function content(StringInputStream $input, StringOutputStream $output)
	{
		$parameters = new ArrayContentTemplateSyntaxElement();
		$parameters->parse($input, $output);
	}

	private function end(StringInputStream $input, StringOutputStream $output)
	{
		if (!$input->consume_next('\]\s*'))
		{
			throw new TemplateParserException('invalid array: missing enclosing parenthesis', $input);
		}
		$output->write(')');
	}
}
?>