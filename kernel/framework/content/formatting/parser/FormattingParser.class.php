<?php
/*##################################################
 *                         FormattingParser.class.php
 *                            -------------------
 *   begin                : December 20, 2009
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

interface FormattingParser
{
    const DONT_ADD_SLASHES = false;
    const ADD_SLASHES = true;
    const DONT_STRIP_SLASHES = false;
    const STRIP_SLASHES = true;

    /**
     * Parses the content of the parser.
     */
    function parse();

    /**
     * @desc Returns the content of the parser. If you called a method which parses the content, this content will be parsed.
     * @param bool $addslashes FormattingParser::ADD_SLASHES if you want to escape the slashes in your string
     * (you often save a parsed content into the database when you parse it), otherwise FormattingParser::DONT_ADD_SLASHES.
     * @return string The content of the parser.
     */
    function get_content($addslashes = FormattingParser::ADD_SLASHES);

    /**
     * @desc Sets the content of the parser. When you will call a parse method, it will deal with this content.
     * @param string $content Content
     * @param bool $stripslashes FormattingParser::DONT_STRIP_SLASHES if you don't want to strip slashes before adding it to the parser,
     * otherwise FormattingParser::STRIP_SLASHES.
     */
    function set_content($content, $stripslashes = FormattingParser::DONT_STRIP_SLASHES);

    /**
     * Sets the reference path for relative URL
     * @param string $path Path
     */
    function set_path_to_root($path);

    /**
     * Returns the path to root attribute.
     * @return string The path
     */
    function get_path_to_root();

    /**
     * Sets the page path
     * @param string $page_path Page path
     */
    function set_page_path($page_path);

    /**
     * Returns the page path
     * @return string path
     */
    function get_page_path();
}

?>