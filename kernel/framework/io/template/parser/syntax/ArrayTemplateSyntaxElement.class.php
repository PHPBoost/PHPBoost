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

	public function parse(TemplateSyntaxParserContext $context, StringInputStream $input, StringOutputStream $output)
	{
        $this->register($context, $input, $output);
		if ($input->consume_next('\s*\['))
		{
			$output->write('array(');
			$this->content();
			$this->end();
		}
		else
		{
			throw new TemplateRenderingException('invalid array', $input);
		}
	}

	private function content()
	{
		$this->parse_elt(new ArrayContentTemplateSyntaxElement());
	}

	private function end()
	{
		if (!$this->input->consume_next('\]\s*'))
		{
			throw new TemplateRenderingException('invalid array: missing enclosing parenthesis', $this->input);
		}
		$this->output->write(')');
	}
}
?>