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
 * @author Beno�t Sautel <ben.popeye@phpboost.com>
 * @desc This class contains the default content formatting factory that must be used if you want
 * a formatting factory having the default settings.
 */
class ContentFormattingService
{
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
		$editor = $this->get_existing_editor($language);
		return ContentFormattingProvidersService::create_factory($editor);
	}

	/**
	 * @desc Returns the name of the editor of the current user (chosen in its profile).
	 * @return string The editor used by the current user.
	 */
	public function get_user_editor()
	{
		return AppContext::get_current_user()->get_editor();
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
		return $this->get_default_factory()->get_editor();
	}

    /**
     * @param string $editor
     * @return string
     */
    private function get_existing_editor($editor)
    {
        if (in_array($editor, self::get_editors_identifier()))
        {
            return $editor;
        }
        else
        {
            return ContentFormattingConfig::load()->get_default_editor();
        }
    }
    
    public function get_editors_identifier()
    {
    	return array_keys(ContentFormattingProvidersService::get_editors());
    }
    
    public function get_available_editors()
    {
    	$available_editors = array();
    	foreach (ContentFormattingProvidersService::get_editors() as $id => $provider)
    	{
    		$available_editors[$id] = $provider->get_name();
    	}
    	return $available_editors;
    }
    
    /**
     * @desc Returns the map of all the formatting types supported by the PHPBoost formatting editors and parsers.
     * The keys of the map are the tags identifiers and the values the tags names.
     * @return string[] The map
     */
    public function get_available_tags()
    {
        $bbcode_lang = LangLoader::get('editor-common');
        return array(
        	'b' => $bbcode_lang['format_bold'],
        	'i' => $bbcode_lang['format_italic'],
        	'u' => $bbcode_lang['format_underline'],
        	's' => $bbcode_lang['format_strike'],
        	'title' => $bbcode_lang['format_title'],
        	'style' => $bbcode_lang['format_style'],
        	'url' => $bbcode_lang['format_url'],
        	'img' => $bbcode_lang['format_img'],
        	'quote' => $bbcode_lang['format_quote'],
        	'hide' => $bbcode_lang['format_hide'],
        	'list' => $bbcode_lang['format_list'],
        	'color' => $bbcode_lang['format_color'],
        	'bgcolor' => $bbcode_lang['format_bgcolor'],
        	'font' => $bbcode_lang['format_font'],
        	'size' => $bbcode_lang['format_size'],
        	'align' => $bbcode_lang['format_align'],
        	'float' => $bbcode_lang['format_float'],
        	'sup' => $bbcode_lang['format_sup'], 
			'sub' => $bbcode_lang['format_sub'],
        	'indent' => $bbcode_lang['format_indent'],
        	'pre' => $bbcode_lang['format_pre'],
        	'table' => $bbcode_lang['format_table'],
        	'swf' => $bbcode_lang['format_flash'],
        	'movie' => $bbcode_lang['format_movie'],
        	'sound' => $bbcode_lang['format_sound'],
        	'code' => $bbcode_lang['format_code'],
        	'math' => $bbcode_lang['format_math'],
        	'anchor' => $bbcode_lang['format_anchor'],
        	'acronym' => $bbcode_lang['format_acronym'],
        	'block' => $bbcode_lang['format_block'],
			'fieldset' => $bbcode_lang['format_fieldset'],
        	'mail' => $bbcode_lang['format_mail'],
        	'line' => $bbcode_lang['format_line'],
        	'wikipedia' => $bbcode_lang['format_wikipedia'],
        	'html' => $bbcode_lang['format_html'],
        	'feed' => $bbcode_lang['format_feed'],
        	'youtube' => $bbcode_lang['format_youtube'],
        	'lightbox' => $bbcode_lang['format_lightbox']
        );
    }
}
?>