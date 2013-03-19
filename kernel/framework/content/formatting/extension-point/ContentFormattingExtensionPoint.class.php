<?php
/*##################################################
 *                           ContentFormattingExtensionPoint.class.php
 *                            -------------------
 *   begin                : October 11, 2011
 *   copyright            : (C) 2011 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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

interface ContentFormattingExtensionPoint extends ExtensionPoint
{
	const EXTENSION_POINT = 'content_formatting';

	function get_name();
	
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
	
	/**
	 * @desc Sets the forbidden tags.
	 * @param string[] $tags The list of the forbidden tags (each tag is represented by its string identifier)
	 */
	function set_forbidden_tags(array $tags);

	/**
	 * @desc Returns the list of the forbidden tags
	 * @return string[] The list of the forbidden tags (each tag is represented by its string identifier)
	 */
	public function get_forbidden_tags();

	/**
	 * @desc Adds a forbidden tag
	 * @param string $tag The tag name
	 */
	function add_forbidden_tag($tag);
	
	function add_forbidden_tags(array $tags);
	
	function set_html_auth(array $array_auth);
	
	function get_html_auth();
}
?>