<?php
/*##################################################
 *                    ArrayContentTemplateSyntaxElement.class.php
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

class ArrayContentTemplateSyntaxElement extends AbstractTemplateSyntaxElement
{
	public function parse(TemplateSyntaxParserContext $context, StringInputStream $input, StringOutputStream $output)
	{
		$this->register($context, $input, $output);
        while (!$input->assert_next('\s*\]'))
        {
            $this->process_key();
            $this->process_value();
            if ($input->consume_next('\s*,\s*'))
            {
                $output->write(', ');
            }
            else if (!$input->assert_next('\s*\]\s*'))
            {
                throw new TemplateRenderingException('invalid array definition, missing "," or "]"', $input);
            }
        }
	}

	private function process_key()
	{
		$matches = array();
		if ($this->input->consume_next('\s*(?P<key>(?:[0-9]+)|(?:\'[^\']+\'))\s*:\s*', '', $matches))
		{
			$this->output->write($matches['key'] . '=>');
		}
	}

	private function process_value()
	{
		$this->parse_elt(new ExpressionContentTemplateSyntaxElement());
	}
}
?>