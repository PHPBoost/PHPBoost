<?php
/**
 * @package     Content
 * @subpackage  Formatting\extension-point
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2011 10 11
*/

interface ContentFormattingExtensionPoint extends ExtensionPoint
{
	const EXTENSION_POINT = 'content_formatting';

	function get_name();

	/**
	 * Returns a parser which will work in the language you chose.
	 * @return FormattingParser The parser to use to parse you formatting
	 */
	function get_parser();

	/**
	 * Returns a unparser which will work in the language you chose.
	 * @return FormattingParser The unparser to use to unparse you formatting
	 */
	function get_unparser();

	/**
	 * Returns a second parser which will work in the language you chose.
	 * @return FormattingParser The second parser to use just before displaying you formatted text
	 */
	function get_second_parser();

	/**
	 * Returns an editor object which will display the editor corresponding to the language you chose.
	 * @return ContentEditor The editor to use.
	 */
	function get_editor();

	/**
	 * Sets the forbidden tags.
	 * @param string[] $tags The list of the forbidden tags (each tag is represented by its string identifier)
	 */
	function set_forbidden_tags(array $tags);

	/**
	 * Returns the list of the forbidden tags
	 * @return string[] The list of the forbidden tags (each tag is represented by its string identifier)
	 */
	public function get_forbidden_tags();

	/**
	 * Adds a forbidden tag
	 * @param string $tag The tag name
	 */
	function add_forbidden_tag($tag);

	function add_forbidden_tags(array $tags);

	function set_html_auth(array $array_auth);

	function get_html_auth();
}
?>
