<?php
/**
 * @package     IO
 * @subpackage  Template\parser\syntax
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 18
 * @since       PHPBoost 3.0 - 2010 09 11
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FunctionCallTemplateSyntaxElement extends AbstractTemplateSyntaxElement
{
    private bool $ended = false;
    private int $begin_pos = 0;

    public function parse(TemplateSyntaxParserContext $context, StringInputStream $input, StringOutputStream $output): void
    {
        $this->register($context, $input, $output);
        $this->begin_pos = $input->tell();
        $this->do_parse();
    }

    private function do_parse(): void
    {
        $this->process_expression_start();
        $this->process_expression_content();
        $this->process_expression_end();
        if (!$this->ended)
        {
            $this->missing_expression_end();
        }
    }

    private function process_expression_start(): void
    {
        $this->input->consume_next('\{');
        $this->output->write('\';');
    }

    private function process_expression_end(): void
    {
        $this->ended = $this->input->next() == '}';
        $this->output->write(';' . TemplateSyntaxElement::RESULT . '=\'');
    }

    private function process_expression_content(): void
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

    private function missing_expression_end(): void
    {
        throw new TemplateRenderingException('Missing function call expression end \'}\'', $this->input);
    }

    private function encountered(): string
    {
        $this->input->consume_next('.*\}', 'U');
        $end_pos = $this->input->tell();
        $length = $end_pos - $this->begin_pos;
        return $this->input->to_string(-$length, $length + 1);
    }
}
?>
