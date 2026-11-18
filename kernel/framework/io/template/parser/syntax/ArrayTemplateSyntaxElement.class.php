<?php
/**
 * @package     IO
 * @subpackage  Template\parser\syntax
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 18
 * @since       PHPBoost 3.0 - 2010 09 05
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ArrayTemplateSyntaxElement extends AbstractTemplateSyntaxElement
{
    public static function is_element(StringInputStream $input): bool
    {
        return $input->assert_next('\s*\[');
    }

    public function parse(TemplateSyntaxParserContext $context, StringInputStream $input, StringOutputStream $output): void
    {
        $this->register($context, $input, $output);
        if ($input->consume_next('\s*\['))
        {
            $output->write('[');
            $this->content();
            $this->end();
        }
        else
        {
            throw new TemplateRenderingException('invalid array', $input);
        }
    }

    private function content(): void
    {
        $this->parse_elt(new ArrayContentTemplateSyntaxElement());
    }

    private function end(): void
    {
        if (!$this->input->consume_next('\]\s*'))
        {
            throw new TemplateRenderingException('invalid array: missing enclosing "]"', $this->input);
        }
        $this->output->write(']');
    }
}
?>
