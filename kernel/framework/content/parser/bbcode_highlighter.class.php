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

import('content/parser/parser');

//Colors of each language elements
//For a BBCode tag
define('BBCODE_TAG_COLOR', '#0000FF');
//For a BBCode tag parameter (value)
define('BBCODE_PARAM_COLOR', '#7B00FF');
//For a BBCode param name
define('BBCODE_PARAM_NAME_COLOR', '#FF0000');
//For the BBCode list tag
define('BBCODE_LIST_ITEM_COLOR', '#00AF07');

//Inline or block code (HTML context)
define('BBCODE_HIGHLIGHTER_INLINE_CODE', true);
define('BBCODE_HIGHLIGHTER_BLOCK_CODE', false);

/**
 * @package content
 * @subpackage parser
 * @author Benoît Sautel <ben.popeye@phpboost.com>
 * @desc This class is a highlighter for the PHPBoost BBCode language.
 * It supplies the highlighted code written in XHTML.
 */
class BBCodeHighlighter extends Parser
{
	######## Public #######
	/**
	 * @desc Builds a BBCodeHighlighter objet
	 */
	function BBCodeHighlighter()
	{
		//We call the parent constructor
		parent::Parser();
	}
	
	/**
	 * @desc Highlights the content of the parser.
	 * @param bool $inline_code If you want that the code make a new HTML paragraph, use BBCODE_HIGHLIGHTER_BLOCK_CODE
	 * (default parameter) and if you want that it would be integrated in a line, use BBCODE_HIGHLIGHTER_INLINE_CODE
	 * @return void You can get the result by calling the get_content method
	 */
	function parse($inline_code = BBCODE_HIGHLIGHTER_BLOCK_CODE)
	{		
		//Protection of html code
		$this->content = htmlspecialchars($this->content);

		//Line tag
		$this->content = str_replace('[line]', '<span style="color:' . BBCODE_TAG_COLOR . ';">[line]</span>', $this->content);
		$this->content = str_replace('[*]', '<span style="color:' . BBCODE_LIST_ITEM_COLOR . ';">[*]</span>', $this->content);

		//Simple tags (whitout parameter)
		$simple_tags = array('b', 'i', 'u', 's', 'sup', 'sub', 'pre', 'math', 'quote', 'block', 'fieldset', 'sound', 'url', 'img', 'mail', 'code',  'tr', 'html', 'row', 'indent', 'hide', 'mail');

		foreach ($simple_tags as $tag)
		{
			while (preg_match('`\[' . $tag . '\](.*)\[/' . $tag . '\]`isU', $this->content))
			{
				$this->content = preg_replace('`\[' . $tag . '\](.*)\[/' . $tag . '\]`isU', '<span style="color:' . BBCODE_TAG_COLOR . ';">/[/' . $tag . '/]/</span>$1<span style="color:' . BBCODE_TAG_COLOR . ';">/[//' . $tag . '/]/</span>', $this->content);
			}
		}

		//Tags which take a parameter : [tag=parameter]content[/tag]
		$tags_with_simple_property = array('img', 'color', 'bgcolor', 'size', 'font', 'align', 'float', 'anchor', 'acronym', 'title', 'stitle', 'style', 'url', 'mail', 'code', 'quote', 'movie', 'swf', 'mail');

		foreach ($tags_with_simple_property as $tag)
		{
			while (preg_match('`\[' . $tag . '=([^\]]+)\](.*)\[/' . $tag . '\]`isU', $this->content))
			{
				$this->content = preg_replace('`\[' . $tag . '=([^\]]+)\](.*)\[/' . $tag . '\]`isU', '<span style="color:' . BBCODE_TAG_COLOR . ';">/[/' . $tag . '</span>=<span style="color:' . BBCODE_PARAM_COLOR . ';">$1</span><span style="color:' . BBCODE_TAG_COLOR . ';">/]/</span>$2<span style="color:' . BBCODE_TAG_COLOR . ';">/[//' . $tag . '/]/</span>', $this->content);
			}
		}

		//Tags which take several parameters. The syntax is the same as XML parameters
		$tags_with_many_parameters = array('table', 'col', 'head', 'list', 'fieldset', 'block', 'wikipedia');

		foreach ($tags_with_many_parameters as $tag)
		{
			while (preg_match('`\[(' . $tag . ')([^\]]*)\](.*)\[/' . $tag . '\]`isU', $this->content))
			{
				$this->content = preg_replace_callback('`\[(' . $tag . ')([^\]]*)\](.*)\[/' . $tag . '\]`isU', array(&$this, '_highlight_bbcode_tag_with_many_parameters'), $this->content);
			}
		}
		
		if (!$inline_code)
		{
			$this->content = '<pre>' . $this->content . '</pre>';
		}
		else
		{
			$this->content = '<pre style="display:inline;">' . $this->content . '</pre>';
		}
		
		//Te be able to handle the nested tags, we replaced [ by /[/, we do the reverse replacement now		
		$this->content = str_replace(array('/[/', '/]/'), array('[', ']'), $this->content);
	}

	## Private ##
	/**
	 * @desc Callback which highlights the parameters of a complex tag
	 * @param string[] $matches elements matched by the regular expression 
	 * @return string The complex tag highlighted
	 */
	function _highlight_bbcode_tag_with_many_parameters($matches)
	{
		$content = $matches[3];
		$tag_name = $matches[1];

		$matches[2] = preg_replace('`([a-z]+)="([^"]*)"`isU', '<span style="color:' . BBCODE_PARAM_NAME_COLOR . '">$1</span>=<span style="color:' . BBCODE_PARAM_COLOR . '">"$2"</span>', $matches[2]);

		return '<span style="color:' . BBCODE_TAG_COLOR . '">/[/' . $tag_name . '</span>' .$matches[2] . '<span style="color:' . BBCODE_TAG_COLOR . '">/]/</span>' . $content . '<span style="color:' . BBCODE_TAG_COLOR . '">/[//' . $tag_name . '/]/</span>';
	}
}
?>