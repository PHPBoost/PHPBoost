<?php
/*##################################################
*                             parser.class.php
*                            -------------------
*   begin                : November 29, 2007
*   copyright            : (C) 2007 Régis Viarre, Benoit Sautel, Loïc Rouchon
*   email                : crowkait@phpboost.com, ben.popeye@phpboost.com, loic.rouchon@phpboost.com
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

//Constantes utilisées
define('DO_NOT_ADD_SLASHES', false);
define('ADD_SLASHES', true);
define('PARSER_DO_NOT_STRIP_SLASHES', false);
define('PARSER_STRIP_SLASHES', true);
define('PICK_UP', true);
define('REIMPLANT', false);

/**
 * @package content
 * @subpackage parser
 * @author Benoît Sautel <ben.popeye@phpboost.com>
 * @desc This class is the basis of all the formatting processings that exist in PHPBoost.
 */
class Parser
{
	/**
	 * @var string Content of the parser
	 */
	protected $content = '';
	/**
	 * @var string[] List of the tags which have been picked up by the parser
	 */
	protected $array_tags = array();
	/**
	 * @var string Path to root of the page in which has been written the content to parse.
	 */
	protected $path_to_root = PATH_TO_ROOT;
	
	/**
	 * @var string Path of the page in which has been written the content to parse.
	 */
	protected $page_path = '';
	
	/**
	 * @desc Builds a Parser object. 
	 */
	public function __construct()
	{
		$this->content = '';
		$this->page_path = $_SERVER['PHP_SELF'];
	}

	/**
	 * @desc Returns the content of the parser. If you called a method which parses the content, this content will be parsed.
	 * @param bool $addslashes ADD_SLASHES if you want to escape the slashes in your string 
	 * (you often save a parsed content into the database when you parse it), otherwise DO_NOT_ADD_SLASHES.
	 * @return string The content of the parser.
	 */
	public function get_content($addslashes = ADD_SLASHES)
	{
		if ($addslashes)
		{
			return addslashes(trim($this->content));
		}
		else
		{
			return trim($this->content);
		}
	}
	
	/**
	 * @desc Sets the content of the parser. When you will call a parse method, it will deal with this content. 
	 * @param string $content Content
	 * @param bool $stripslashes PARSER_DO_NOT_STRIP_SLASHES if you don't want to strip slashes before adding it to the parser, 
	 * otherwise PARSER_STRIP_SLASHES.
	 */
	public function set_content($content, $stripslashes = PARSER_DO_NOT_STRIP_SLASHES)
	{
		if ($stripslashes)
		{
			$this->content = stripslashes($content);
		}
		else
		{
			$this->content = $content;
		}
	}
	
	/**
	 * Sets the reference path for relative URL
	 * @param string $path Path 
	 */
	public function set_path_to_root($path)
	{
		$this->path_to_root = $path;	
	}
	
	/**
	 * Returns the path to root attribute.
	 * @return string The path
	 */
	public function get_path_to_root()
	{
		return $this->path_to_root;
	}
	
	/**
	 * Sets the page path
	 * @param string $page_path Page path
	 */
	public function set_page_path($page_path)
	{
		$this->page_path = $page_path;
	}
	
	/**
	 * Returns the page path
	 * @return string path
	 */
	public function get_page_path()
	{
		return $this->page_path;
	}
		
	/**
	 * @desc Parses a nested tag
	 * @param string $match The regular expression which matches the tag to replace
	 * @param string $regex The regular expression which matches the replacement
	 * @param string $replace The replacement syntax.
	 */
	protected function _parse_imbricated($match, $regex, $replace)
	{
		$nbr_match = substr_count($this->content, $match);
		for ($i = 0; $i <= $nbr_match; $i++)
		{
			$this->content = preg_replace($regex, $replace, $this->content); 
		}
	}
}

?>