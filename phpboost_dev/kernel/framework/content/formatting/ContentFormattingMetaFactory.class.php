<?php
/*##################################################
 *                     ContentFormattingFactory.class.php
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
 * @desc This class contains the default content formatting factory that must be used if you want
 * a formatting factory having the default settings.
 */
class ContentFormattingMetaFactory
{
	const BBCODE_LANGUAGE = 'bbcode';
	const TINYMCE_LANGUAGE = 'tinymce';
	const DEFAULT_LANGUAGE = 'default';

	/**
	 * @var AbstractContentFormattingFactory
	 */
	private static $instance;

	/**
	 * @desc Returns the content formatting factory corresponding to the default configuration 
	 * @return ContentFormattingFactory
	 */
	public static function get_default_factory()
	{
		if (self::$instance === null)
		{
			self::$instance = self::create_factory(self::get_user_language());
		}
		return self::$instance;
	}

	/**
	 * @desc Creates a factory for the given language
	 * @param string $language
	 * @return ContentFormattingFactory
	 */
	public static function create_factory($language)
	{
		switch (self::get_existing_editor($language))
		{
			case self::BBCODE_LANGUAGE:
				return new BBCodeFormattingFactory();
			case self::TINYMCE_LANGUAGE:
				return new TinyMCEFormattingFactory();
			default:
				return self::create_factory(self::get_user_language());
		}
	}

	/**
	 * @desc Returns the name of the editor of the current user (chosen in its profile).
	 * @return string The editor used by the current user.
	 */
	public static function get_user_language()
	{
		return AppContext::get_user()->get_attribute('user_editor');
	}

	/**
	 * @desc Returns the parser to use in the default configuration
	 * @return FormattingParser
	 */
	public static function get_default_parser()
	{
		return self::get_default_factory()->get_parser();
	}

	/**
	 * @desc Returns the unparser to use in the default configuration
	 * @return FormattingParser
	 */
	public static function get_default_unparser()
	{
		return self::get_default_factory()->get_unparser();
	}

	/**
	 * @desc Returns the second parser to use in the default configuration
	 * @return FormattingParser
	 */
	public static function get_default_second_parser()
	{
		return self::get_default_factory()->get_second_parser();
	}

	/**
	 * @desc Returns the editor displayer that you have to display beside the associated HTML textarea
	 * if you use the default configuration.
	 * @return ContentEditor
	 */
	public static function get_default_editor()
	{
		return self::get_default_factory()->get_editor();
	}

    /**
     * @param string $editor
     * @return string
     */
    private static function get_existing_editor($editor)
    {
        if (in_array($editor, array(self::BBCODE_LANGUAGE, self::TINYMCE_LANGUAGE)))
        {
            return $editor;
        }
        else
        {
            return self::get_default_editor();
        }
    }
}
?>