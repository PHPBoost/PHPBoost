<?php
/*##################################################
 *                        ContentParserFactory.class.php
 *                            -------------------
 *   begin                : July 3 2008
 *   copyright            : (C) 2008 Benoit Sautel
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
 * @package content
 * @subpackage formatting/factory
 * @author Benoît Sautel <ben.popeye@phpboost.com>
 * @desc This class is approximatively a factory which provides objets capable to format some content.
 * The text formatting uses a syntax, PHPBoost supports both the BBCode syntax and a WYSIWYG tool syntax (TinyMCE).
 * You can choose the formatting type you want to deal with.
 */
interface ContentParserFactory
{
    /**
     * @desc Returns a parser which will work in the language you chose.
     * @return FormattingParser The parser to use to parse you formatting
     */
    function get_parser();

    /**
     * @desc Returns a unparser which will work in the language you chose.
     * @return FormattingParser The unparser to use to unparse you formatting
     */
    function get_unparser();

    /**
     * @desc Returns a second parser which will work in the language you chose.
     * @return FormattingParser The second parser to use just before displaying you formatted text
     */
    function get_second_parser();

    /**
     * @desc Returns an editor object which will display the editor corresponding to the language you chose.
     * @return ContentEditor The editor to use.
     */
    function get_editor();
}

?>
