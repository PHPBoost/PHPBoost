<?php
/*##################################################
 *                    LangVarTemplateSyntaxElement.class.php
 *                            -------------------
 *   begin                : October 01 2010
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