<?php
/*##################################################
*                             tinymce_unparser.class.php
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

require_once(PATH_TO_ROOT . '/kernel/framework/content/content_unparser.class.php');

class TinyMCEUnparser extends ContentUnparser
{
	function TinyMCEParser()
	{
		parent::ContentParser();
	}
	
	//Unparser
	function unparse()
	{
		$this->parsed_content = $this->content;

		//Extracting HTML and code tags
		$this->_unparse_html(PICK_UP);
		$this->_unparse_code(PICK_UP);
		
		//Si on n'est pas à la racine du site plus un dossier, on remplace les liens relatifs générés par le BBCode
		if( PATH_TO_ROOT != '..' )
		{
			$this->parsed_content = str_replace('"' . PATH_TO_ROOT . '/', '"../', $this->parsed_content);
		}
		
		//Smilies
		$this->_unparse_smilies();
		
		//Remplacement des caractères de word
		$array_str = array( 
			"\t", '[b]', '[/b]', '[i]', '[/i]', '[s]', '[/s]', '€', '‚', 'ƒ',
			'„', '…', '†', '‡', 'ˆ', '‰', 'Š', '‹', 'Œ', 'Ž',
			'‘', '’', '“', '”', '•', '–', '—',  '˜', '™', 'š',
			'›', 'œ', 'ž', 'Ÿ', '<li class="bb_li">', '</table>', '<tr class="bb_table_row">'
		);
		
		$array_str_replace = array( 
			'&nbsp;&nbsp;&nbsp;', '<strong>', '</strong>', '<em>', '</em>', '<strike>', '</strike>', '&#8364;', '&#8218;', '&#402;', '&#8222;',
			'&#8230;', '&#8224;', '&#8225;', '&#710;', '&#8240;', '&#352;', '&#8249;', '&#338;', '&#381;',
			'&#8216;', '&#8217;', '&#8220;', '&#8221;', '&#8226;', '&#8211;', '&#8212;', '&#732;', '&#8482;',
			'&#353;', '&#8250;', '&#339;', '&#382;', '&#376;', '<li>', '</tbody></table>', '<tr>'
		);
		
		$this->parsed_content = str_replace($array_str, $array_str_replace, $this->parsed_content);
		
		//Replacing <br /> by a paragraph
		$this->parsed_content = str_replace('<br />', "</p>\n<p>", '<p>' . $this->parsed_content . '</p>');
		
		//If we don't protect the HTML code inserted into the tags code and HTML TinyMCE will parse it!
		if( !empty($this->array_tags['html_unparse']) )
			$this->array_tags['html_unparse'] = array_map(create_function('$var', 'return htmlspecialchars($var, ENT_NOQUOTES);'), $this->array_tags['html_unparse']);
		if( !empty($this->array_tags['code_unparse']) )
			$this->array_tags['code_unparse'] = array_map(create_function('$var', 'return htmlspecialchars($var, ENT_NOQUOTES);'), $this->array_tags['code_unparse']);
		
		//Unparsing tags supported by TinyMCE
		$this->_unparse_tinymce_tags();
		//Unparsing tags unsupported by TinyMCE, those are in BBCode
		$this->_unparse_bbcode_tags();
		
		//Reimplanting html and code tags
		$this->_unparse_code(REIMPLANT);
		$this->_unparse_html(REIMPLANT);
		echo $this->parsed_content;
	}
	
	## Protected ##
	//Function which replaces the image code for smilies by the text code
	function _unparse_smilies()
	{
		@include(PATH_TO_ROOT . '/cache/smileys.php');
		if( !empty($_array_smiley_code) )
		{
			//Création du tableau de remplacement
			foreach($_array_smiley_code as $code => $img)
			{	
				$smiley_img_url[] = '`<img src="../images/smileys/' . preg_quote($img) . '(.*) />`sU';
				$smiley_code[] = $code;
			}	
			$this->parsed_content = preg_replace($smiley_img_url, $smiley_code, $this->parsed_content);
		}
	}
	
	//
	function _unparse_tinymce_tags()
	{	
		//Preg_replace.
		$array_preg = array(
			'`<img src="([^"]+)" alt="" class="valign_([^"]+)?" />`i',
			'`<table class="bb_table"( style="([^"]+)")?>`i', 
			'`<td class="bb_table_col"( colspan="[^"]+")?( rowspan="[^"]+")?( style="[^"]+")?>`i',
			'`<th class="bb_table_col"( colspan="[^"]+")?( rowspan="[^"]+")?( style="[^"]+")?>`i',
			'`<span style="color:(.*);">(.*)</span>`isU',
			'`<span style="background-color:(.*);">(.*)</span>`isU',
			'`<span style="text-decoration: underline;">(.*)</span>`isU',
			'`<span style="font-family: ([ a-z0-9,_-]+);">(.*)</span>`isU',
			'`<p style="text-align:(left|center|right|justify)">(.*)</p>`isU',
			'`<span id="([a-z0-9_-]+)">(.*)</span>`isU',
			'`<ul( style="[^"]+")? class="bb_ul">`i',
			'`<ol( style="[^"]+")? class="bb_ol">`i',
			'`<h3 class="title1">(.*)</h3>`isU',
			'`<h3 class="title2">(.*)</h3>`isU',
			'`<h4 class="stitle1">(.*)</h4>`isU',
			'`<h4 class="stitle2">(.*)</h4>`isU',
			'`<object type="application/x-shockwave-flash" data="([^"]+)" width="([^"]+)" height="([^"]+)">(.*)</object>`isU'
		);
		$array_preg_replace = array( 
			"<img src=\"$1\" alt=\"\" align=\"$2\" />",
			"<table border=\"0\"$1><tbody>",
			"<td$1$2$3>", 
			"<th$1$2$3>", 
			"<font color=\"$1\">$2</font>",
			"<span style=\"background-color: $1\">$2</font>",
			"<u>$1</u>",	
			"<font color=\"$1\">$2</font>",
			"<p align=\"$1\">$2</p>",
			"<a title=\"$1\" name=\"$1\">$2</a>",
			"<ul$1>",
			"<ol$1>",
			"<h1>$1</h1>",
			"<h2>$1</h2>",
			"<h3>$1</h3>",
			"<h4>$1</h4>",
			"<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0\" width=\"$2\" height=\"$3\"><param name=\"movie\" value=\"$1\" /><param name=\"quality\" value=\"high\" /><param name=\"menu\" value=\"false\" /><param name=\"wmode\" value=\"\" /><embed src=\"$1\" wmode=\"\" quality=\"high\" menu=\"false\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" type=\"application/x-shockwave-flash\" width=\"$2\" height=\"$3\"></embed></object>"
		);	
		
		$this->parsed_content = preg_replace($array_preg, $array_preg_replace, $this->parsed_content);
		
		//indent
		$this->_parse_imbricated('<div class="indent">', '`<div class="indent">(.+)</div>`sU', '<blockquote>$1</blockquote>', $this->parsed_content);
		
		//Balise size
		$this->parsed_content = preg_replace_callback('`<span style="font-size: ([0-9]+)px;">(.*)</span>`isU', create_function('$size', 'if( $size[1] >= 36 ) $fontsize = 7;	elseif( $size[1] <= 12 ) $fontsize = 1;	else $fontsize = min(($size[1] - 6)/2, 7); return \'<font size="\' . $fontsize . \'">\' . $size[2] . \'</font>\';'), $this->parsed_content);
	}
	
	//Function which manages the whole tags which doesn't not exist in TinyMCE
	function _unparse_bbcode_tags()
	{
		//Quote
		$this->_parse_imbricated('<span class="text_blockquote">', '`<span class="text_blockquote">(.*):</span><div class="blockquote">(.*)</div>`sU', '[quote=$1]$2[/quote]', $this->parsed_content);
		//Hide
		$this->_parse_imbricated('<span class="text_hide">', '`<span class="text_hide">(.*):</span><div class="hide" onclick="bb_hide\(this\)"><div class="hide2">(.*)</div></div>`sU', '[hide]$2[/hide]', $this->parsed_content);
		
		$array_preg = array(
			'`<object type="application/x-shockwave-flash" data="\.\./kernel/data/dewplayer\.swf\?son=(.*)" width="200" height="20">(.*)</object>`isU',
			'`<object type="application/x-shockwave-flash" data="\.\./kernel/data/movieplayer\.swf\?movie=(.*)" width="([^"]+)" height="([^"]+)">(.*)</object>`isU',
			'`<span class="(success|question|notice|warning|error)">(.*)</span>`isU',
			'`<acronym title="([^"]+)" class="bb_acronym">(.*)</acronym>`isU',
			'`<p class="float_(left|right)">(.*)</p>`isU',
			'`\[\[MATH\]\](.*)\[\[/MATH\]\]`isU',
			'`\[\[CODE\]\](.*)\[\[/CODE\]\]`isU',
			'`\[\[CODE=([^\]])\]\](.*)\[\[/CODE\]\]`isU',
		);
		$array_preg_replace = array(
			"[sound]$1[/sound]",
			"[movie=$2,$3]$1[/movie]",
			"[style=$1]$2[/style]",
			"[acronym=$1]$2[/acronym]",
			"[float=$1]$2[/float]",
			"[math]$1[/math]",
			"[code]$1[/code]",
			"[code=$1]$2[/code]",
		);
		
		$this->parsed_content = preg_replace($array_preg, $array_preg_replace, $this->parsed_content);
	}
}

?>