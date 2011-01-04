<?php
/*##################################################
 *                    VariableTemplateSyntaxElement.class.php
 *                            -------------------
 *   begin                : July 0810 2010
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

class VariableTemplateSyntaxElement extends AbstractTemplateSyntaxElement
{
	public static function is_element(StringInputStream $input)
	{
		return $input->assert_next('\s*(?:@(?:H\|)?)?(?:[a-z0-9A-Z_]\w+\.)*[a-z0-9A-Z_]\w+\s*');
	}

	public function parse(TemplateSyntaxParserContext $context, StringInputStream $input, StringOutputStream $output)
	{
        $this->register($context, $input, $output);
		$input->consume_next('\s*');
		$element = null;
		if (LangVarTemplateSyntaxElement::is_element($input))
		{
			$element = new LangVarTemplateSyntaxElement();
		}
		elseif (LoopVarTemplateSyntaxElement::is_element($input))
		{
			$element = new LoopVarTemplateSyntaxElement();
		}
		elseif (SimpleVarTemplateSyntaxElement::is_element($input))
		{
			$element = new SimpleVarTemplateSyntaxElement();
		}
		else
		{
			throw new TemplateRenderingException('invalid variable', $input);
		}
		$this->parse_elt($element);
		$input->consume_next('\s*');
	}
}
?>