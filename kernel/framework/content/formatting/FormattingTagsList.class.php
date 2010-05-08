<?php
/*##################################################
 *                       FormmatingTagsList.class.php
 *                            -------------------
 *   begin                : December 20, 2000
 *   copyright            : (C) 2009 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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

/**
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 * @desc This class provides the list of the formmating rules which can be used.
 */
class FormattingTagsList
{
    /**
     * @desc Returns the map of all the formatting types supported by the PHPBoost formatting editors and parsers.
     * The keys of the map are the tags identifiers and the values the tags names.
     * @return string[] The map
     */
    public static function get_available_tags()
    {
        global $LANG;
        return array(
        	'b' => $LANG['format_bold'],
        	'i' => $LANG['format_italic'],
        	'u' => $LANG['format_underline'],
        	's' => $LANG['format_strike'],
        	'title' => $LANG['format_title'],
        	'style' => $LANG['format_style'],
        	'url' => $LANG['format_url'],
        	'img' => $LANG['format_img'],
        	'quote' => $LANG['format_quote'],
        	'hide' => $LANG['format_hide'],
        	'list' => $LANG['format_list'],
        	'color' => $LANG['format_color'],
        	'bgcolor' => $LANG['format_bgcolor'],
        	'font' => $LANG['format_font'],
        	'size' => $LANG['format_size'],
        	'align' => $LANG['format_align'],
        	'float' => $LANG['format_float'],
        	'sup' => $LANG['format_sup'], 
			'sub' => $LANG['format_sub'],
        	'indent' => $LANG['format_indent'],
        	'pre' => $LANG['format_pre'],
        	'table' => $LANG['format_table'],
        	'swf' => $LANG['format_flash'],
        	'movie' => $LANG['format_movie'],
        	'sound' => $LANG['format_sound'],
        	'code' => $LANG['format_code'],
        	'math' => $LANG['format_math'],
        	'anchor' => $LANG['format_anchor'],
        	'acronym' => $LANG['format_acronym'],
        	'block' => $LANG['format_block'],
			'fieldset' => $LANG['format_fieldset'],
        	'mail' => $LANG['format_mail'],
        	'line' => $LANG['format_line'],
        	'wikipedia' => $LANG['format_wikipedia'],
        	'html' => $LANG['format_html'],
        	'feed' => $LANG['format_feed']
        );
    }
}
?>