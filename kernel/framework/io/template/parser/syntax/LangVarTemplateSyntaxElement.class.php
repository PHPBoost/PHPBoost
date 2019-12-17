<?php
/**
 * @package     IO
 * @subpackage  Template\parser\syntax
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 10 01
*/

class LangVarTemplateSyntaxElement extends AbstractTemplateSyntaxElement
{
    public static function is_element(StringInputStream $input)
    {
        return $input->assert_next('@(?:H\|)?[a-z0-9A-Z_][\w_.]*');
    }

    public function parse(TemplateSyntaxParserContext $context, StringInputStream $input, StringOutputStream $output)
    {
        $matches = array();
        if ($input->consume_next('@(?P<html>H\|)?(?P<msg>[a-z0-9A-Z_][\w_.]*)', '', $matches))
        {
            $is_html = $matches['html'];
            $msg = $matches['msg'];
            $function = $is_html ? 'i18nraw' : 'i18n';
            $output->write(TemplateSyntaxElement::FUNCTIONS . '->' . $function . '(\'' . $msg . '\')');
        }
        else
        {
            throw new TemplateRenderingException('invalid simple variable name', $input);
        }
    }
}
?>
