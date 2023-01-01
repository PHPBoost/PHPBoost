<?php
/**
 * @package     IO
 * @subpackage  Template\parser\syntax
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 09 11
*/

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
