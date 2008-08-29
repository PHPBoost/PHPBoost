<?php
/*##################################################
 *                       template_highlighter.class.php
 *                            -------------------
 *   begin                : August 29, 2008
 *   copyright            : (C) 2008 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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

require_once(PATH_TO_ROOT . '/kernel/framework/content/parser.class.php');

//Couleurs pour la coloration du BBCode
define('TPL_BRACES_STYLE', 'color:#7F3300;');
define('TPL_VARIABLE_STYLE', 'color:#FF6600; font-weight: bold;');
define('TPL_NESTED_VARIABLE_STYLE', 'color:#8F5211;');
define('TPL_SHARP_STYLE', 'color:#9915AF; font-weight: bold;');
define('TPL_KEYWORD_STYLE', 'color:#000066; font-weight: bold;');

//Classe de gestion du contenu
class TemplateHighlighter extends Parser
{
	######## Public #######
	//Constructeur
	function TemplateHighlighter()
	{
		parent::Parser();
	}

	//Highlights the content of the parser
	function highlight($line_number = GESHI_NO_LINE_NUMBERS)
	{
		$this->parsed_content = $this->content;
		
		//The template language of PHPBoost contains HTML. We first ask to highlight the html code.
		require_once(PATH_TO_ROOT . '/kernel/framework/content/geshi/geshi.php');
		$Geshi =& new GeSHi($this->parsed_content, 'html');
				
		if( $line_number ) //Affichage des numéros de lignes.
			$Geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);

		$this->parsed_content = $Geshi->parse_code();
		
		//Now we highlight the specific syntax of PHPBoost templates
		
		//Conditionnal block
		$this->parsed_content = preg_replace('`# IF( NOT)? ((?:\w+\.)*)(\w+) #`i', '<span style="' . TPL_SHARP_STYLE . '">#</span> <span style="' . TPL_KEYWORD_STYLE . '">IF$1</span> <span style="' . TPL_NESTED_VARIABLE_STYLE . '">$2</span><span style="' . TPL_VARIABLE_STYLE . '">$3</span> <span style="' . TPL_SHARP_STYLE . '">#</span>', $this->parsed_content);
		$this->parsed_content = str_replace('# ELSE #', '<span style="' . TPL_SHARP_STYLE . '">#</span> <span style="' . TPL_KEYWORD_STYLE . '">ELSE</span> <span style="' . TPL_SHARP_STYLE . '">#</span>', $this->parsed_content);
		$this->parsed_content = str_replace('# ENDIF #', '<span style="' . TPL_SHARP_STYLE . '">#</span> <span style="' . TPL_KEYWORD_STYLE . '">ENDIF</span> <span style="' . TPL_SHARP_STYLE . '">#</span>', $this->parsed_content);
		
		//Loops
		$this->parsed_content = preg_replace('`# START ((?:\w+\.)*)(\w+) #`i', '<span style="' . TPL_SHARP_STYLE . '">#</span> <span style="' . TPL_KEYWORD_STYLE . '">START</span> <span style="' . TPL_NESTED_VARIABLE_STYLE . '">$1</span><span style="' . TPL_VARIABLE_STYLE . '">$2</span> <span style="' . TPL_SHARP_STYLE . '">#</span>', $this->parsed_content);
		$this->parsed_content = preg_replace('`# END ((?:\w+\.)*)(\w+) #`i', '<span style="' . TPL_SHARP_STYLE . '">#</span> <span style="' . TPL_KEYWORD_STYLE . '">END</span> <span style="' . TPL_NESTED_VARIABLE_STYLE . '">$1</span><span style="' . TPL_VARIABLE_STYLE . '">$2</span> <span style="' . TPL_SHARP_STYLE . '">#</span>', $this->parsed_content);
		
		//Inclusions
		$this->parsed_content = preg_replace('`# INCLUDE ([\w]+) #`', '<span style="' . TPL_SHARP_STYLE . '">#</span> <span style="' . TPL_KEYWORD_STYLE . '">INCLUDE </span> <span style="' . TPL_VARIABLE_STYLE . '">$1</span> <span style="' . TPL_SHARP_STYLE . '">#</span>', $this->parsed_content);
		
		//Simple variable
		$this->parsed_content = preg_replace('`{([\w]+)}`i', '<span style="' . TPL_BRACES_STYLE . '">{</span><span style="' . TPL_VARIABLE_STYLE . '">$1</span><span style="' . TPL_BRACES_STYLE . '">}</span>', $this->parsed_content);
		//Loop variable
		$this->parsed_content = preg_replace('`{((?:[\w]+\.)+)([\w]+)}`i', '<span style="' . TPL_BRACES_STYLE . '">{</span><span style="' . TPL_NESTED_VARIABLE_STYLE . '">$1</span><span style="' . TPL_VARIABLE_STYLE . '">$2</span><span style="' . TPL_BRACES_STYLE . '">}</span>', $this->parsed_content);
		
		$this->parsed_content = '<pre>' . $this->parsed_content . '</pre>';
	}
}
?>