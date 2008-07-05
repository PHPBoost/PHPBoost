<?php
/*##################################################
*                             content.class.php
*                            -------------------
*   begin                : July 3 2008
*   copyright          : (C) 2008 Benoit Sautel
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

class Content
{
	function Content($language_type = false)
	{
		if( $language_type !== false )
			$this->set_language($language_type);
	}
	
	function set_language($language_type = DEFAULT_LANGUAGE)
	{
		//If the language type is specified and correct
		if( in_array($language_type, array(BBCODE_LANGUAGE, TINYMCE_LANGUAGE)) )
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
		switch($this->language_type)
		{
			case BBCODE_LANGUAGE:
				require_once(PATH_TO_ROOT . '/kernel/framework/content/bbcode_parser.class.php');
				return new BBCodeParser();
			case TINYMCE_LANGUAGE:
				require_once(PATH_TO_ROOT . '/kernel/framework/content/tinymce_parser.class.php');
				return new TinyMCEParser();
			default:
				if( $CONFIG['editor'] == TINYMCE_LANGUAGE )
				{
					require_once(PATH_TO_ROOT . '/kernel/framework/content/bbcode_parser.class.php');
					return new BBCodeParser();
				}
				else
				{
					require_once(PATH_TO_ROOT . '/kernel/framework/content/tinymce_parser.class.php');
					return new TinyMCEParser();
				}
		}
	}
	
	//Function which builds an object editor and returns it
	function get_editor()
	{
		global $CONFIG;
		switch($this->language_type)
		global $Member;
		
		switch($this->language_type)
		{
			case BBCODE_LANGUAGE:
			case TINYMCE_LANGUAGE:
			case BBCODE_LANGUAGE:
				require_once(PATH_TO_ROOT . '/kernel/framework/content/bbcode_editor.class.php');
				return new BBCodeEditor();
			case TINYMCE_LANGUAGE:
				require_once(PATH_TO_ROOT . '/kernel/framework/content/bbcode_editor.class.php');
				return new BBCodeEditor();
			default:
				if( $Member->Get_attribute('user_editor') == BBCODE_LANGUAGE )
				{
					require_once(PATH_TO_ROOT . '/kernel/framework/content/bbcode_editor.class.php');
					return new BBCodeEditor();
				}
				else
				{
					require_once(PATH_TO_ROOT . '/kernel/framework/content/tinymce_editor.class.php');
					return new TinyMCEEditor();
				}
		}
	}
	
	## Private ##
	//Language type
	var $language_type = DEFAULT_LANGUAGE;
}

?>
