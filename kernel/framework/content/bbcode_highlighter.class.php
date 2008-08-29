<?php
/*##################################################
 *                        bbcode_highlighter.class.php
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
define('BBCODE_TAG_COLOR', '#0000FF');
define('BBCODE_PARAM_COLOR', '#7B00FF');
define('BBCODE_PARAM_NAME_COLOR', '#FF0000');
define('BBCODE_LIST_ITEM_COLOR', '#00AF07');

//Classe de gestion du contenu
class BBCodeHighlighter extends Parser
{
	######## Public #######
	//Constructeur
	function BBCodeHighlighter()
	{
		parent::Parser();
	}
	
	//Highlights the content of the parser
	function highlight($inline_code = false)
	{
		$this->parsed_content = $this->content;
		
		//Protection of html code
		$this->parsed_content = htmlspecialchars($this->parsed_content, ENT_NOQUOTES);

		//Line tag
		$this->parsed_content = str_replace('[line]', '<span style="color:' . BBCODE_TAG_COLOR . ';">[line]</span>', $this->parsed_content);
		$this->parsed_content = str_replace('[*]', '<span style="color:' . BBCODE_LIST_ITEM_COLOR . ';">[*]</span>', $this->parsed_content);

		//Simple tags (whitout parameter)
		$simple_tags = array('b', 'i', 'u', 's', 'sup', 'sub', 'pre', 'math', 'quote', 'block', 'fieldset', 'sound', 'url', 'img', 'mail', 'code',  'tr', 'html', 'row', 'indent', 'hide', 'mail');

		foreach($simple_tags as $tag)
			$this->parsed_content = preg_replace('`\[' . $tag . '\](.+)\[/' . $tag . '\]`isU', '<span style="color:' . BBCODE_TAG_COLOR . ';">[' . $tag . ']</span>$1<span style="color:' . BBCODE_TAG_COLOR . ';">[/' . $tag . ']</span>', $this->parsed_content);

		//Tags which take a parameter : [tag=parameter]content[/tag]
		$tags_with_simple_property = array('img', 'color', 'bgcolor', 'size', 'font', 'align', 'float', 'anchor', 'acronym', 'title', 'stitle', 'style', 'url', 'mail', 'code', 'quote', 'movie', 'swf', 'mail');

		foreach($tags_with_simple_property as $tag)
			$this->parsed_content = preg_replace('`\[' . $tag . '=([^\]]+)\](.+)\[/' . $tag . '\]`isU', '<span style="color:' . BBCODE_TAG_COLOR . ';">[' . $tag . '</span>=<span style="color:' . BBCODE_PARAM_COLOR . ';">$1</span><span style="color:' . BBCODE_TAG_COLOR . ';">]</span>$2<span style="color:' . BBCODE_TAG_COLOR . ';">[/' . $tag . ']</span>', $this->parsed_content);

		//Tags which take several parameters. The syntax is the same as XML parameters
		$tags_with_many_parameters = array('table', 'col', 'head', 'list', 'fieldset', 'block');

		foreach($tags_with_many_parameters as $tag)
			$this->parsed_content = preg_replace_callback('`\[(' . $tag . ')([^\]]*)\](.+)\[/' . $tag . '\]`isU', array('BBCodeHighlighter', '_highlight_bbcode_tag_with_many_parameters'), $this->parsed_content);
		
		if( !$inline_code )
			$this->parsed_content = '<pre>' . $this->parsed_content . '</pre>';
		else
			$this->parsed_content = '<pre style="display:inline;">' . $this->parsed_content . '</pre>';
	}

	## Private ##
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