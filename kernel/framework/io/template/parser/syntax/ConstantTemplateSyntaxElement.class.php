<?php
/*##################################################
 *                    ConstantTemplateSyntaxElementTemplateSyntaxElement.class.php
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

class ConstantTemplateSyntaxElement extends AbstractTemplateSyntaxElement
{
	public static function is_element(StringInputStream $input)
	{
		return $input->assert_next('(?:[0-9]+(?:\.[0-9]+)?)|(?:\'[^\']*\')|(?:true)|(?:false)');
	}

	public function parse(TemplateSyntaxParserContext $context, StringInputStream $input, StringOutputStream $output)
	{
		$matches = array();
		if ($input->consume_next('(?P<constant>(?:[0-9]+(?:\.[0-9]+)?)|(?:\'[^\']*\')|(?:true)|(?:false))', '', $matches))
		{
			$constant = $matches['constant'];
			$output->write($constant);
		}
		else
		{
			throw new TemplateRenderingException('invalid constant variable', $input);
		}
	}
}
?>