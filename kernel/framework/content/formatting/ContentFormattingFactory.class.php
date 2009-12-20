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
 * @desc This class is an abstraction of the formatting language specific factories.
 */
class ContentFormattingFactory
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
    public static function get_parser($editor = self::DEFAULT_LANGUAGE)
    {
        return self::get_factory($editor)->get_parser();
    }

    /**
     * @return ContentUnparser
     */
    public static function get_unparser($editor = self::DEFAULT_LANGUAGE)
    {
        return self::get_factory($editor)->get_unparser();
    }

    /**
     * @return ContentSecondParser
     */
    public static function get_second_parser($editor = self::DEFAULT_LANGUAGE)
    {
        return self::get_factory($editor)->get_second_parser();
    }

    /**
     * @return ContentEditors
     */
    public static function get_editor($editor = self::DEFAULT_LANGUAGE)
    {
        return self::get_factory($editor)->get_editor();
    }

    /**
     * @param string $editor The editor
     * @return AbstractContentFormattingFactory
     */
    private static function get_factory($editor = self::DEFAULT_LANGUAGE)
    {
        if (!empty(self::$factories[$editor]))
        {
            return self::$factories[$editor];
        }
        else
        {
            $factory = self::create_factory($editor);
            self::$factories[$editor] = $factory;
            return $factory;
        }
    }

    /**
     * @desc Returns the name of the editor of the current user (chosen in its profile).
     * @return string The editor used by the current user.
     */
    private static function get_default_editor()
    {
        return AppContext::get_user()->get_attribute('user_editor');
    }

    /**
     * @param string $editor
     * @return AbstractContentFormattingFactory
     */
    private static function create_factory($editor)
    {
        switch (self::get_existing_editor($editor))
        {
            case self::BBCODE_LANGUAGE:
                return new BBCodeParserFactory();
            case self::TINYMCE_LANGUAGE:
                return new TinyMCEParserFactory();
            default:
                return self::create_factory(self::get_default_editor());
        }
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