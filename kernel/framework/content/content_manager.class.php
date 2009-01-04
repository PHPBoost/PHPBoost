<?php
/*##################################################
*                         content_service.class.php
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

class ContentManager
{
	function ContentManager($language_type = false)
	{
		if ($language_type !== false)
			$this->set_language($language_type);
	}
	
	function set_language($language_type = DEFAULT_LANGUAGE)
	{
		//If the language type is specified and correct
		if (in_array($language_type, array(BBCODE_LANGUAGE, TINYMCE_LANGUAGE)))
			$this->language_type = $language_type;			
		else
			$this->language_type = DEFAULT_LANGUAGE;	
	}
	
	//Function which returns the current language
	function get_language()
	{
		return $this->language_type;
	}
	
	//Function which builds an object parser and returns it
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
	
	//Function which builds an object unparser and returns it
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
	
	//Function which builds an object unparser and returns it
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
	
	//Returns the user editor
	function get_user_editor()
	{
		global $User;
		return $User->get_attribute('user_editor');
	}
	
	//Returns the list of the available tags in the parser
	/*static*/ function get_available_tags()
	{
		return array('b', 'i', 'u', 's', 'title', 'style', 'url', 
				'img', 'quote', 'hide', 'list', 'color', 'bgcolor', 'font', 'size', 'align', 'float', 'sup', 
				'sub', 'indent', 'pre', 'table', 'swf', 'movie', 'sound', 'code', 'math', 'anchor', 'acronym', 'block',
				'fieldset', 'mail', 'line', 'wikipedia'
				);
	}
	
	## Private ##
	//Language type
	var $language_type = DEFAULT_LANGUAGE;
}

?>
