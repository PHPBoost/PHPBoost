<?php
/*##################################################
 *                      PHPTemplateSyntaxElement.class.php
 *                            -------------------
 *   begin                : September 10 2010
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

class PHPTemplateSyntaxElement extends AbstractTemplateSyntaxElement
{
	private $ended = false;
	private $escaped = false;

	public function parse(TemplateSyntaxParserContext $context, StringInputStream $input, StringOutputStream $output)
	{
        $this->register($context, $input, $output);
		$this->do_parse();
	}

	private function do_parse()
	{
		$matches = array();
		if ($this->input->consume_next('\?php\s*(?P<php>[^\s].*[^\*])\s*\?>', 'Us', $matches))
		{
			$this->process_php($matches['php']);
		}
		else
		{
			throw new TemplateRenderingException('Missing php code ends: "?>"', $this->input);
		}
	}

	private function process_php($php)
	{
        $this->output->write('\';$_ob_length=ob_get_length();');
        $this->output->write($php);
        $this->output->write('if(ob_get_length()>$_ob_length){$_content=ob_get_clean();' . TemplateSyntaxElement::RESULT .
            '.=substr($_content, $_ob_length);echo substr($_content, 0, $_ob_length);}');
        $this->output->write(TemplateSyntaxElement::RESULT . '.=\'');
	}
}
?>