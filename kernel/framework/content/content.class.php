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

define('BBCODE_LANGAGE', 'bbcode');
define('TINYMCE_LANGAGE', 'tinymce');
define('DEFAULT_LANGAGE', 'default');

class Content
{
	function Content($langage_type = false)
	{
		if( $langage_type !== false )
			$this->set_langage($langage_type);
	}
	
	function set_langage($langage_type = false)
	{
		//If the langage type is specified and correct
		if( $langage_type !== false && !in_array($langage_type, array(BBCODE_LANGAGE, TINYMCE_LANGAGE)) )
			$this->langage_type = $langage_type;			
		else
			$this->langage_type = DEFAULT_LANGAGE;	
	}
	
	//Function which returns the current langage
	function get_langage()
	{
		return $this->langage_type;
	}
	
	//Function which builds an object parser and returns it
	function get_parser()
	{
		global $CONFIG;
		switch($this->langage_type)
		{
			case BBCODE_LANGAGE:
				require_once(PATH_TO_ROOT . '/kernel/framework/content/bbcode_parser.class.php');
				return new BBCodeParser();
			case TINYMCE_LANGAGE:
				require_once(PATH_TO_ROOT . '/kernel/framework/content/tinymce_parser.class.php');
				return new TinyMCEParser();
			default:
				if( $CONFIG['editor'] == TINYMCE_LANGAGE )
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
		switch($this->langage_type)
		{
			case BBCODE_LANGAGE:
			case TINYMCE_LANGAGE:
			default:
				return;
		}
	}
	
	## Private ##
	//Langage type
	var $langage_type = DEFAULT_LANGAGE;
}

?>