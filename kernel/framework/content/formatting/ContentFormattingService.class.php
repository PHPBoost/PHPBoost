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
 * @author Benoit Sautel <ben.popeye@phpboost.com>
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
     * @param string $id_module
     */
    public function uninstall_editor($id_module)
    {
    	$editors = $this->get_available_editors();
		
		if (count($editors) > 1)
		{
			$default_editor = ContentFormattingConfig::load()->get_default_editor();
			if ($default_editor !== $id_module)
			{
				PersistenceContext::get_querier()->update(DB_TABLE_MEMBER, array('editor' => $default_editor), 
					'WHERE editor=:old_editor', array('old_editor' => $id_module
				));
				return null;
			}
			else
			{
				return LangLoader::get_message('is_default_editor', 'editor-common');
			}
		}
		return LangLoader::get_message('last_editor_installed', 'editor-common');
    }
    
    /**
     * @desc Returns the map of all the formatting types supported by the PHPBoost formatting editors and parsers.
     * The keys of the map are the tags identifiers and the values the tags names.
     * @return string[] The map
     */
    public function get_available_tags()
    {
        $editor_lang = LangLoader::get('editor-common');
        return array(
        	'b' => $editor_lang['format_bold'],
        	'i' => $editor_lang['format_italic'],
        	'u' => $editor_lang['format_underline'],
        	's' => $editor_lang['format_strike'],
        	'title' => $editor_lang['format_title'],
        	'style' => $editor_lang['format_style'],
        	'url' => $editor_lang['format_url'],
        	'img' => $editor_lang['format_img'],
        	'quote' => $editor_lang['format_quote'],
        	'hide' => $editor_lang['format_hide'],
        	'list' => $editor_lang['format_list'],
        	'color' => $editor_lang['format_color'],
        	'bgcolor' => $editor_lang['format_bgcolor'],
        	'font' => $editor_lang['format_font'],
        	'size' => $editor_lang['format_size'],
        	'align' => $editor_lang['format_align'],
        	'float' => $editor_lang['format_float'],
        	'sup' => $editor_lang['format_sup'], 
			'sub' => $editor_lang['format_sub'],
        	'indent' => $editor_lang['format_indent'],
        	'pre' => $editor_lang['format_pre'],
        	'table' => $editor_lang['format_table'],
        	'swf' => $editor_lang['format_flash'],
        	'movie' => $editor_lang['format_movie'],
        	'sound' => $editor_lang['format_sound'],
        	'code' => $editor_lang['format_code'],
        	'math' => $editor_lang['format_math'],
        	'anchor' => $editor_lang['format_anchor'],
        	'acronym' => $editor_lang['format_acronym'],
        	'block' => $editor_lang['format_block'],
			'fieldset' => $editor_lang['format_fieldset'],
        	'mail' => $editor_lang['format_mail'],
        	'line' => $editor_lang['format_line'],
        	'wikipedia' => $editor_lang['format_wikipedia'],
        	'html' => $editor_lang['format_html'],
        	'feed' => $editor_lang['format_feed'],
        	'youtube' => $editor_lang['format_youtube'],
        	'lightbox' => $editor_lang['format_lightbox'],
        	'charmap' => $editor_lang['format_charmap'],
        	'insertdatetime' => $editor_lang['format_insertdatetime']
        );
    }
}
?>