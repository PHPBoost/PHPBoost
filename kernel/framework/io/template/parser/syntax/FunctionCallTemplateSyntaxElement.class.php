<?php
/*##################################################
 *                    FunctionCallTemplateSyntaxElement.class.php
 *                            -------------------
 *   begin                : September 11 2010
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

class FunctionCallTemplateSyntaxElement extends AbstractTemplateSyntaxElement
{
    private $ended = false;
    private $begin_pos;

    public function parse(TemplateSyntaxParserContext $context, StringInputStream $input, StringOutputStream $output)
    {
        $this->register($context, $input, $output);
        $this->begin_pos = $input->tell();
        $this->do_parse();
    }

    private function do_parse()
    {
        $this->process_expression_start();
        $this->process_expression_content();
        $this->process_expression_end();
        if (!$this->ended)
        {
            $this->missing_expression_end();
        }
    }

    private function process_expression_start()
    {
        $this->input->consume_next('\{');
        $this->output->write('\';');
    }

    private function process_expression_end()
    {
        $this->ended = $this->input->next() == '}';
        $this->output->write(';' . TemplateSyntaxElement::RESULT . '=\'');
    }

    private function process_expression_content()
    {
    	try
    	{
	        $this->parse_elt(new FunctionTemplateSyntaxElement());
    	}
    	catch (InvalidTemplateFunctionCallException $ex)
    	{
    		$encountered = $this->encountered();
            throw new TemplateRenderingException('Invalid function call, expecting #{aFunction()} but was ' . $encountered, $this->input);
    	}
    }

    private function missing_expression_end()
    {
        throw new TemplateRenderingException('Missing function call expression end \'}\'', $this->input);
    }
    
    private function encountered()
    {
    	$this->input->consume_next('.*\}', 'U');
        $end_pos = $this->input->tell();
        $length = $end_pos - $this->begin_pos;
    	return $this->input->to_string(-$length, $length + 1);
    }
}
?>