<?php
/*##################################################
*                             content_second_parser.class.php
*                            -------------------
*   begin                : August 10, 2008
*   copyright          : (C) 2008 Benoit Sautel
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

//Classe de gestion du contenu
class ContentSecondParser extends Parser
{
	######## Public #######
	//Constructeur
	function ContentSecondParser()
	{
		parent::Parser();
	}
	
	//Parse temps réel => détection des balisses [code]  et remplacement, coloration si contient du code php.
	//This function exists whatever type of content you have because it's use to finish parsing of a recorded string
	function second_parse()
	{
		global $LANG;
		
		$this->parsed_content = $this->content;
		
		//Balise code
		if( strpos($this->parsed_content, '[[CODE') !== false )
			$this->parsed_content = preg_replace_callback('`\[\[CODE(?:=([a-z0-9-]+))?(?:,(0|1)(,0|1)?)?\]\](.+)\[\[/CODE\]\]`sU', array(&$this, '_callback_highlight_code'), $this->parsed_content);
		
		//Balise latex.
		if( strpos($this->parsed_content, '[[MATH]]') !== false )
			$this->parsed_content = preg_replace_callback('`\[\[MATH\]\](.+)\[\[/MATH\]\]`sU', array(&$this, '_math_code'), $this->parsed_content);
	}
	
	## Private ##
	
	//Coloration syntaxique suivant le langage, tracé des lignes si demandé.
	function _highlight_code($contents, $language, $line_number) 
	{
		if( $language != '' )
		{
			include_once(PATH_TO_ROOT . '/kernel/framework/content/geshi/geshi.php');
			$Geshi =& new GeSHi($contents, $language);
			
			if( $line_number ) //Affichage des numéros de lignes.
				$Geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);

			$contents = $Geshi->parse_code();
		}
		else
		{
			$highlight = highlight_string($contents, true);
			$font_replace = str_replace(array('<font ', '</font>'), array('<span ', '</span>'), $highlight);
			$contents = preg_replace('`color="(.*?)"`', 'style="color:$1"', $font_replace);
		}
		
		return $contents ;
	} 

	//Fonction appliquée aux balises [code] temps réel.
	function _callback_highlight_code($matches)
	{
		global $LANG;
		
		$line_number = !empty($matches[2]);
		$display_info_code = empty($matches[3]);

		$contents = $this->_highlight_code($matches[4], $matches[1], $line_number);

		if( $display_info_code && !empty($matches[1]) )
			$contents = '<span class="text_code">' . sprintf($LANG['code_langage'], strtoupper($matches[1])) . '</span><div class="code">' . $contents .'</div>';
		else
			$contents = '<span class="text_code">' . $LANG['code_tag'] . '</span><div class="code" style="margin-top:3px;">'. $contents .'</div>';
			
		return $contents;
	}

	//Fonction appliquée aux balises [math] temps réel, formules matématiques.
	function _math_code($matches)
	{
		$matches[1] = str_replace('<br />', '', $matches[1]);
		$matches = mathfilter(html_entity_decode($matches[1]), 12);

		return $matches;
	}
}
?>