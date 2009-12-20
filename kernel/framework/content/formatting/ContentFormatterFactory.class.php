<?php
/*##################################################
 *                     ContentFormatterFactory.class.php
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

/**
 * @package content
 * @subpackage formatting
 * @author Benoît Sautel <ben.popeye@phpboost.com>
 * @desc
 */

class ContentFormatterFactory
{
	const BBCODE_LANGUAGE = 'bbcode';
	const TINYMCE_LANGUAGE = 'tinymce';
	const DEFAULT_LANGUAGE = 'default';

	/**
	 * @var AbstractContentFormattingFactory
	 */
	private static $factories = array();

	/**
	 * @return ContentParser
	 */
	public static function get_parser()
	{
		return self::get_factory()->get_parser();
	}

	/**
	 * @return ContentUnparser
	 */
	public static function get_unparser()
	{
		return self::get_factory()->get_unparser();
	}

	/**
	 * @return ContentSecondParser
	 */
	public static function get_second_parser()
	{
		return self::get_factory()->get_second_parser();
	}

	/**
	 * @return ContentEditors
	 */
	public static function get_editor()
	{
		return self::get_factory()->get_editor();
	}

	/**
	 * @param string $editor The editor
	 * @return AbstractContentFormattingFactory
	 */
	private static function get_factory($editor = self::DEFAULT_LANGUAGE)
	{
		switch ($editor)
		{
			case self::BBCODE_LANGUAGE:
				return new BBCodeContentFormattingFactory();
				break;
			case self::TINYMCE_LANGUAGE:
				return new TinyMCEContentFormattingFactory();
				break;
			case self::DEFAULT_LANGUAGE:
			default:
				return self::get_factory(self::get_default_editor());
				break;
		}
	}


	/**
	 * @desc Builds a ContentFormattingFactoryy object
	 * @param string $language_type The language in which must work the factory. One of the following elements:
	 * <ul>
	 * 	<li>BBCODE_LANGUAGE if you want to force the BBCode language</li>
	 * 	<li>TINYMCE_LANGUAGE if you want to force the TinyMCE formatting editor</li>
	 * 	<li>DEFAULT_LANGUAGE if you want to use the default language of the site. If the user changed it, it will be
	 * the language he chosed, otherwise it will be the website default language.</li>
	 * </ul>
	 */

	/**
	 * @desc Returns the name of the editor of the current user (chosen in its profile).
	 * @return string The editor used by the current user.
	 */
	private static function get_default_editor()
	{
		return AppContext::get_user()->get_attribute('user_editor');
	}

	/**
	 * @static
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
        	'html' => $LANG['format_html']
		);
	}
}
?>