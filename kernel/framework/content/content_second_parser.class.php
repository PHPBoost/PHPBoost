<?php
/*##################################################
*                       content_second_parser.class.php
*                            -------------------
*   begin                : August 10, 2008
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
define('BBCODE_TAG_COLOR', '#0000FF');
define('BBCODE_PARAM_COLOR', '#7B00FF');
define('BBCODE_PARAM_NAME_COLOR', '#FF0000');
define('BBCODE_LIST_ITEM_COLOR', '#00AF07');

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
		if( strtolower($language) == 'bbcode' )
		{
			$contents = ContentSecondParser::_highlight_bbcode($contents);
		}
		elseif( $language != '' )
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
	
	//Function which highlights the bbcode code
	/*static*/ function _highlight_bbcode($content)
	{
		//Protection of html code
		$content = htmlspecialchars($content, ENT_NOQUOTES);
		
		//Line tag
		$content = str_replace('[line]', '<span style="color:' . BBCODE_TAG_COLOR . ';">[line]</span>', $content);
		$content = str_replace('[*]', '<span style="color:' . BBCODE_LIST_ITEM_COLOR . ';">[*]</span>', $content);
		
		//Simple tags (whitout parameter)
		$simple_tags = array('b', 'i', 'u', 's', 'sup', 'sub', 'pre', 'math', 'quote', 'block', 'fieldset', 'sound', 'url', 'img', 'mail', 'code',  'tr', 'html', 'row', 'indent', 'hide', 'mail');
		
		foreach($simple_tags as $tag)
			$content = preg_replace('`\[' . $tag . '\](.+)\[/' . $tag . '\]`isU', '<span style="color:' . BBCODE_TAG_COLOR . ';">[' . $tag . ']</span>$1<span style="color:' . BBCODE_TAG_COLOR . ';">[/' . $tag . ']</span>', $content);
		
		//Tags which take a parameter : [tag=parameter]content[/tag]
		$tags_with_simple_property = array('img', 'color', 'bgcolor', 'size', 'font', 'align', 'float', 'anchor', 'acronym', 'title', 'stitle', 'style', 'url', 'mail', 'code', 'quote', 'movie', 'swf', 'mail');
		
		foreach($tags_with_simple_property as $tag)
			$content = preg_replace('`\[' . $tag . '=([^\]]+)\](.+)\[/' . $tag . '\]`isU', '<span style="color:' . BBCODE_TAG_COLOR . ';">[' . $tag . '</span>=<span style="color:' . BBCODE_PARAM_COLOR . ';">$1</span><span style="color:' . BBCODE_TAG_COLOR . ';">]</span>$2<span style="color:' . BBCODE_TAG_COLOR . ';">[/' . $tag . ']</span>', $content);
		
		//Tags which take several parameters. The syntax is the same as XML parameters
		$tags_with_many_parameters = array('table', 'col', 'head', 'list', 'fieldset', 'block');
		
		foreach($tags_with_many_parameters as $tag)
			$content = preg_replace_callback('`\[(' . $tag . ')([^\]]*)\](.+)\[/' . $tag . '\]`isU', array('ContentSecondParser', '_highlight_bbcode_tag_with_many_parameters'), $content);
		
		return '<pre>' . $content . '</pre>';
	}
	
	//Function which highlights the parameters of a complex tag
	/*static*/ function _highlight_bbcode_tag_with_many_parameters($matches)
	{
		$content = $matches[3];
		$tag_name = $matches[1];
		
		$matches[2] = preg_replace('`([a-z]+)="([^"]*)"`isU', '<span style="color:' . BBCODE_PARAM_NAME_COLOR . '">$1</span>=<span style="color:' . BBCODE_PARAM_COLOR . '">"$2"</span>', $matches[2]);
		
		return '<span style="color:' . BBCODE_TAG_COLOR . '">[' . $tag_name . '</span>' .$matches[2] . '<span style="color:' . BBCODE_TAG_COLOR . '">]</span>' . $content . '<span style="color:' . BBCODE_TAG_COLOR . '">[/' . $tag_name . ']</span>';
	}
}
?>