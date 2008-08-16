<?php
/*##################################################
*                             parser.class.php
*                            -------------------
*   begin                : November 29, 2007
*   copyright          : (C) 2007 Régis Viarre, Benoit Sautel, Loïc Rouchon
*   email                : crowkait@phpboost.com, ben.popeye@phpboost.com, horn@phpboost.com
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

//Classe de gestion du contenu
class Parser
{
	######## Public #######
	//Constructeur
	function Parser()
	{
		global $CONFIG;
		$this->html_auth =& $CONFIG['html_auth'];
		$this->forbidden_tags =& $CONFIG['forbidden_tags'];
		$this->content = '';
		$this->parsed_content = '';
	}

	//Fonction qui renvoie le contenu traité
	function get_content($addslashes = ADD_SLASHES)
	{
		if( $addslashes )
			return addslashes(trim($this->content));
		else
			return trim($this->content);
	}
	
	//Fonction de chargement de texte
	function set_content($content, $stripslashes = PARSER_DO_NOT_STRIP_SLASHES)
	{
		if( $stripslashes )
			$this->content = stripslashes($content);
		else
			$this->content = $content;
	}
	
	function set_html_auth($array_auth)
	{
		global $CONFIG;
		if( is_array($array_auth) )
			$this->auth = $array_auth;
		else
			$this->auth =& $CONFIG['html_auth'];
	}
	
	function get_parsed_content($addslashes = true)
	{
		if( $addslashes )
			return addslashes(trim($this->parsed_content));
		else
			return trim($this->parsed_content);
	}
	
	function set_forbidden_tags($forbidden_tags)
	{
		if( is_array($forbidden_tags) )
			$this->forbidden_tags = $forbidden_tags;
	}
	
	function get_forbidden_tags()
	{
		return $this->forbidden_tags;
	}	
	
	####### Protected #######
	//This array should be static
	var $tag = array('b', 'i', 'u', 's', 'title', 'stitle', 'style', 'url', 
	'img', 'quote', 'hide', 'list', 'color', 'bgcolor', 'font', 'size', 'align', 'float', 'sup', 
	'sub', 'indent', 'pre', 'table', 'swf', 'movie', 'sound', 'code', 'math', 'anchor', 'acronym'); //Balises supportées.
	var $content = '';
	var $array_tags = array();
	var $parsed_content;
	var $html_auth = array();
	var $forbidden_tags = array();
	
	//Remplacement recursif des balises imbriquées.
	function _parse_imbricated($match, $regex, $replace)
	{
		$nbr_match = substr_count($this->parsed_content, $match);
		for($i = 0; $i <= $nbr_match; $i++)
			$this->parsed_content = preg_replace($regex, $replace, $this->parsed_content); 
	}
}

?>