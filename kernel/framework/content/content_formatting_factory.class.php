<?php
/*##################################################
 *                         content_formatting_factory.class.php
 *                            -------------------
 *   begin                : July 3 2008
 *   copyright            : (C) 2008 Benoit Sautel
 *   email                :  ben.popeye@phpboost.com
 *
 *
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

define('BBCODE_LANGUAGE', 'bbcode');
define('TINYMCE_LANGUAGE', 'tinymce');
define('DEFAULT_LANGUAGE', 'default');

/**
 * @author Benoît Sautel <ben.popeye@phpboost.com>
 * @desc This class is approximatively a factory which provides objets capable to format some content.
 * The text formatting uses a syntax, PHPBoost supports both the BBCode syntax and a WYSIWYG tool syntax (TinyMCE).
 * You can choose the formatting type you want to deal with.
 */
class ContentFormattingFactory
{
    /**
     * @desc Build a ContentFormattingFactoryy object
     * @param string $language_type The language in which must work the factory. One of the following elements:
     * <ul>
     * 	<li>BBCODE_LANGUAGE if you want to force the BBCode language</li>
     * 	<li>TINYMCE_LANGUAGE if you want to force the TinyMCE formatting editor</li>
     * 	<li>DEFAULT_LANGUAGE if you want to use the default language of the site. If the user changed it, it will be
     * the language he chosed, otherwise it will be the website default language.</li>
     * </ul>
     */
    function ContentFormattingFactory($language_type = false)
    {
        if ($language_type !== false)
        {
            $this->set_language($language_type);
        }
    }

    /**
     * @desc Change the language of the factory
     * @param string $language_type The language in which must work the factory. One of the following elements:
     * <ul>
     * 	<li>BBCODE_LANGUAGE if you want to force the BBCode language</li>
     * 	<li>TINYMCE_LANGUAGE if you want to force the TinyMCE formatting editor</li>
     * 	<li>DEFAULT_LANGUAGE if you want to use the default language of the site. If the user changed it, it will be
     * the language he chosed, otherwise it will be the website default language.</li>
     * </ul>
     */
    function set_language($language_type = DEFAULT_LANGUAGE)
    {
        //If the language type is specified and correct
        if (in_array($language_type, array(BBCODE_LANGUAGE, TINYMCE_LANGUAGE)))
        {
            $this->language_type = $language_type;
        }
        else
        {
            $this->language_type = DEFAULT_LANGUAGE;
        }
    }

    /**
     * @desc Return the language of the factory
     * @return string Language (BBCODE_LANGUAGE, TINYMCE_LANGUAGE or DEFAULT_LANGUAGE)
     */
    function get_language()
    {
        return $this->language_type;
    }

    /**
     * @desc Return a parser object which will work in the language you chose.
     * @return ContentParser The parser to use to parse you formatting
     */
    function get_parser()
    {
        global $CONFIG;
        switch ($this->language_type)
        {
            case BBCODE_LANGUAGE:
                import('content/parser/bbcode_parser');
                return new BBCodeParser();
            case TINYMCE_LANGUAGE:
                import('content/parser/tinymce_parser');
                return new TinyMCEParser();
            default:
                if ($this->get_user_editor() == TINYMCE_LANGUAGE)
                {
                    import('content/parser/tinymce_parser');
                    return new TinyMCEParser();
                }
                else
                {
                    import('content/parser/bbcode_parser');
                    return new BBCodeParser();
                }
        }
    }

    /**
     * @desc Return a unparser object which will work in the language you chose.
     * @return ContentUnparser The unparser to use to unparse you formatting
     */
    function get_unparser()
    {
        global $CONFIG;
        switch ($this->language_type)
        {
            case BBCODE_LANGUAGE:
                import('content/parser/bbcode_unparser');
                return new BBCodeUnparser();
            case TINYMCE_LANGUAGE:
                import('content/parser/tinymce_unparser');
                return new TinyMCEUnparser();
            default:
                if ($this->get_user_editor() == TINYMCE_LANGUAGE)
                {
                    import('content/parser/tinymce_unparser');
                    return new TinyMCEUnparser();
                }
                else
                {
                    import('content/parser/bbcode_unparser');
                    return new BBCodeUnparser();
                }
        }
    }

    /**
     * @desc Return a second parser object which will work in the language you chose.
     * @return ContentSecondParser The second parser to use just before displaying you formatted text
     */
    function get_second_parser()
    {
        import('content/parser/content_second_parser');
        return new ContentSecondParser();
    }

    //Function which builds an object editor and returns it
    function get_editor()
    {
        switch ($this->language_type)
        {
            case BBCODE_LANGUAGE:
                import('content/editor/bbcode_editor');
                return new BBCodeEditor();
            case TINYMCE_LANGUAGE:
                import('content/editor/bbcode_editor');
                return new BBCodeEditor();
            default:
                if ($this->get_user_editor() == TINYMCE_LANGUAGE)
                {
                    import('content/editor/tinymce_editor');
                    return new TinyMCEEditor();
                }
                else
                {
                    import('content/editor/bbcode_editor');
                    return new BBCodeEditor();
                }
        }
    }

    /**
     * @desc Return an editor object which will display the editor corresponding to the language you chose.
     * @return ContentParser The editor to use.
     */
    function get_user_editor()
    {
        global $User;
        return $User->get_attribute('user_editor');
    }

    /**
     * @static
 	 * @desc Return the list of all the formatting types supported by the PHPBoost formatting editors and parsers.
     */
    /*static*/ function get_available_tags()
    {
        return array('b', 'i', 'u', 's', 'title', 'style', 'url',
		'img', 'quote', 'hide', 'list', 'color', 'bgcolor', 'font', 'size', 'align', 'float', 'sup', 
		'sub', 'indent', 'pre', 'table', 'swf', 'movie', 'sound', 'code', 'math', 'anchor', 'acronym', 'block',
		'fieldset', 'mail', 'line', 'wikipedia', 'html'
		);
    }

    ## Private ##
    /**
     * @var string Language type
     */
    var $language_type = DEFAULT_LANGUAGE;
}

?>
