<?php
/*##################################################
 *                     ContentFormattingService.class.php
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
 * @package {@package}
 * @author Benoît Sautel <ben.popeye@phpboost.com>
 * @desc This class contains the default content formatting factory that must be used if you want
 * a formatting factory having the default settings.
 */
class ContentFormattingService
{
	const BBCODE_LANGUAGE = 'bbcode';
	const TINYMCE_LANGUAGE = 'tinymce';
	const DEFAULT_LANGUAGE = 'default';

	/**
	 * @var AbstractContentFormattingFactory
	 */
	private $default_factory;

	/**
	 * @desc Returns the content formatting factory corresponding to the default configuration 
	 * @return ContentFormattingFactory
	 */
	public function get_default_factory()
	{
		if ($this->default_factory === null)
		{
			$this->default_factory = $this->create_factory($this->get_user_editor());
		}
		return $this->default_factory;
	}

	/**
	 * @desc Creates a factory for the given language
	 * @param string $language
	 * @return ContentFormattingFactory
	 */
	public function create_factory($language = '')
	{
		switch ($this->get_existing_editor($language))
		{
			case self::BBCODE_LANGUAGE:
				return new BBCodeFormattingFactory();
			case self::TINYMCE_LANGUAGE:
				return new TinyMCEFormattingFactory();
			default:
				return $this->create_factory($this->get_user_editor());
		}
	}

	/**
	 * @desc Returns the name of the editor of the current user (chosen in its profile).
	 * @return string The editor used by the current user.
	 */
	public function get_user_editor()
	{
		return AppContext::get_user()->get_attribute('user_editor');
	}

	/**
	 * @desc Returns the parser to use in the default configuration
	 * @return FormattingParser
	 */
	public function get_default_parser()
	{
		return $this->get_default_factory()->get_parser();
	}

	/**
	 * @desc Returns the unparser to use in the default configuration
	 * @return FormattingParser
	 */
	public function get_default_unparser()
	{
		return $this->get_default_factory()->get_unparser();
	}

	/**
	 * @desc Returns the second parser to use in the default configuration
	 * @return FormattingParser
	 */
	public function get_default_second_parser()
	{
		return $this->get_default_factory()->get_second_parser();
	}

	/**
	 * @desc Returns the editor displayer that you have to display beside the associated HTML textarea
	 * if you use the default configuration.
	 * @return ContentEditor
	 */
	public function get_default_editor()
	{
		return ContentFormattingConfig::load()->get_default_editor();
	}

    /**
     * @param string $editor
     * @return string
     */
    private function get_existing_editor($editor)
    {
        if (in_array($editor, array(self::BBCODE_LANGUAGE, self::TINYMCE_LANGUAGE)))
        {
            return $editor;
        }
        else
        {
            return $this->get_default_editor();
        }
    }
    
    /**
     * @desc Returns the map of all the formatting types supported by the PHPBoost formatting editors and parsers.
     * The keys of the map are the tags identifiers and the values the tags names.
     * @return string[] The map
     */
    public function get_available_tags()
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