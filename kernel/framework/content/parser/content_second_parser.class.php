<?php
/*##################################################
 *                        content_second_parser.class.php
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

import('content/parser/parser');

//Classe de gestion du contenu
class ContentSecondParser extends Parser
{
	######## Public #######
	//Constructeur
	function ContentSecondParser()
	{
		parent::Parser();
	}

	//Parse temps réel => détection des balises [code]  et remplacement, coloration si contient du code php.
	//This function exists whatever type of content you have because it's use to finish parsing of a recorded string
	function second_parse()
	{
		global $LANG;

		$this->parsed_content = str_replace('../includes/data', PATH_TO_ROOT . '/kernel/data', $this->content);

		wordwrap_html($this->content, 95, "\n", false); //Ajoute des retours à la ligne, pour éviter de trop déformer les forms.
		
		//Balise code
		if( strpos($this->content, '[[CODE') !== false )
			$this->content = preg_replace_callback('`\[\[CODE(?:=([a-z0-9-]+))?(?:,(0|1)(?:,(0|1))?)?\]\](.+)\[\[/CODE\]\]`sU', array(&$this, '_callback_highlight_code'), $this->content);

		//Balise latex.
		if( strpos($this->content, '[[MATH]]') !== false )
			$this->content = preg_replace_callback('`\[\[MATH\]\](.+)\[\[/MATH\]\]`sU', array(&$this, '_math_code'), $this->content);
	}

	## Private ##

	//Coloration syntaxique suivant le langage, tracé des lignes si demandé.
	function _highlight_code($contents, $language, $line_number, $inline_code)
	{
		//BBCode PHPBoost
		if( strtolower($language) == 'bbcode' )
		{
			import('content/parser/bbcode_highlighter');
			$bbcode_highlighter = new BBCodeHighlighter();
			$bbcode_highlighter->set_content($contents, PARSER_DO_NOT_STRIP_SLASHES);
			$bbcode_highlighter->highlight($inline_code);
			$contents = $bbcode_highlighter->get_content(DO_NOT_ADD_SLASHES);
		}
		//Templates PHPBoost
		elseif( strtolower($language) == 'tpl' || strtolower($language) == 'template' )
		{
			import('content/parser/template_highlighter');
			require_once(PATH_TO_ROOT . '/kernel/framework/content/geshi/geshi.php');
			
			$template_highlighter = new TemplateHighlighter();
			$template_highlighter->set_content($contents, PARSER_DO_NOT_STRIP_SLASHES);
			$template_highlighter->highlight($line_number ? GESHI_NORMAL_LINE_NUMBERS : GESHI_NO_LINE_NUMBERS, $inline_code);
			$contents = $template_highlighter->get_content(DO_NOT_ADD_SLASHES);
		}
		elseif( $language != '' )
		{
			require_once(PATH_TO_ROOT . '/kernel/framework/content/geshi/geshi.php');
			$Geshi =& new GeSHi($contents, $language);
				
			if( $line_number ) //Affichage des numéros de lignes.
				$Geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
			
			//No container if we are in an inline tag
			if( $inline_code )
				$Geshi->set_header_type(GESHI_HEADER_NONE);

			$contents = '<pre style="display:inline;">' . $Geshi->parse_code() . '</pre>';
		}
		else
		{
			$highlight = highlight_string($contents, true);
			$font_replace = str_replace(array('<font ', '</font>'), array('<span ', '</span>'), $highlight);
			$contents = preg_replace('`color="(.*?)"`', 'style="color:$1"', $font_replace);
		}

		return $contents;
	}

	//Fonction appliquée aux balises [code] temps réel.
	function _callback_highlight_code($matches)
	{
		global $LANG;
		
		//Chargement de la librairie mathématique
		require_once(PATH_TO_ROOT . '/kernel/framework/content/mathpublisher.php');

		$line_number = !empty($matches[2]);
		$inline_code = !empty($matches[3]);
		$contents = $this->_highlight_code($matches[4], $matches[1], $line_number, $inline_code);

		if( !$inline_code && !empty($matches[1]) )
			$contents = '<span class="text_code">' . sprintf($LANG['code_langage'], strtoupper($matches[1])) . '</span><div class="code">' . $contents .'</div>';
		elseif( $inline_code )
			$contents = $contents;
		else
			$contents = '<span class="text_code">' . $LANG['code_tag'] . '</span><div class="code">' . $contents . '</div>';
			
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