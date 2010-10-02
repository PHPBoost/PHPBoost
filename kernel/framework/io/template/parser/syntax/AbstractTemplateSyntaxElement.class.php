<?php
/*##################################################
 *                    AbstractTemplateSyntaxElement.class.php
 *                            -------------------
 *   begin                : June 20 2010
 *   copyright            : (C) 2010 Benoit Sautel, Loic Rouchon
 *   email                : ben.popeye@phpboost.com, horn@phpboost.com
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

abstract class AbstractTemplateSyntaxElement implements TemplateSyntaxElement
{
	/**
     * @var TemplateSyntaxParserContext
     */
    protected $context;
    /**
     * @var StringInputStream
     */
    protected $input;
    /**
     * @var StringOutputStream
     */
    protected $output;
	
	protected function register(TemplateSyntaxParserContext $context, StringInputStream $input, StringOutputStream $output)
	{
        $this->context = $context;
        $this->input = $input;
        $this->output = $output;
	}
	
	protected function parse_elt(TemplateSyntaxElement $element)
	{
		$element->parse($this->context, $this->input, $this->output);
	}
}

?>