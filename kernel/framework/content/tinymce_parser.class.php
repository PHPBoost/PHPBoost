<?php
/*##################################################
*                             tinymce_parser.class.php
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

require_once(PATH_TO_ROOT . '/kernel/framework/content/parser.class.php');

class TinyMCEParser extends ContentParser
{
	function TinyMCEParser()
	{
		
	}
	
	function parse()
	{
		$this->parsed_content = $this->content;
		
		$this->_preparse_tinymce($this->parsed_content);
		
		$this->_unparse_html(PICK_UP);
		$this->_unparse_code(PICK_UP);

		//Smiley.
		@include(PATH_TO_ROOT . '/cache/smileys.php');
		if(!empty($_array_smiley_code) )
		{
			//Création du tableau de remplacement
			foreach($_array_smiley_code as $code => $img)
			{	
				$smiley_img_url[] = '`<img src="../images/smileys/' . preg_quote($img) . '(.*) />`sU';
				$smiley_code[] = $code;
			}	
			$this->parsed_content = preg_replace($smiley_img_url, $smiley_code, $this->parsed_content);
		}
		
		//Remplacement des caractères de word
		$array_str = array( 
			"\t", '[b]', '[/b]', '[i]', '[/i]', '[s]', '[/s]', '€', '‚', 'ƒ',
			'„', '…', '†', '‡', 'ˆ', '‰', 'Š', '‹', 'Œ', 'Ž',
			'‘', '’', '“', '”', '•', '–', '—',  '˜', '™', 'š',
			'›', 'œ', 'ž', 'Ÿ', '<li class="bb_li">', '</table>', '<tr class="bb_table_row">', '</th>'
		);
		$array_str_replace = array( 
			'&nbsp;&nbsp;&nbsp;', '<strong>', '</strong>', '<em>', '</em>', '<strike>', '</strike>', '&#8364;', '&#8218;', '&#402;', '&#8222;',
			'&#8230;', '&#8224;', '&#8225;', '&#710;', '&#8240;', '&#352;', '&#8249;', '&#338;', '&#381;',
			'&#8216;', '&#8217;', '&#8220;', '&#8221;', '&#8226;', '&#8211;', '&#8212;', '&#732;', '&#8482;',
			'&#353;', '&#8250;', '&#339;', '&#382;', '&#376;', '<li>', '</tbody></table>', '<tr>', '</caption>'
		);	
		$this->parsed_content = str_replace($array_str, $array_str_replace, $this->parsed_content);
		
		//Remplacement des balises imbriquées.	
		$this->_parse_imbricated('<span class="text_blockquote">', '`<span class="text_blockquote">(.*):</span><div class="blockquote">(.*)</div>`sU', '[quote=$1]$2[/quote]', $this->parsed_content);
		$this->_parse_imbricated('<span class="text_hide">', '`<span class="text_hide">(.*):</span><div class="hide" onclick="bb_hide\(this\)"><div class="hide2">(.*)</div></div>`sU', '[hide]$2[/hide]', $this->parsed_content);
		$this->_parse_imbricated('<div class="indent">', '`<div class="indent">(.+)</div>`sU', '<blockquote>$1</blockquote>', $this->parsed_content);
		
		//Balise size
		$this->parsed_content = preg_replace_callback('`<span style="font-size: ([0-9]+)px;">(.*)</span>`isU', create_function('$size', 'if( $size[1] >= 36 ) $fontsize = 7;	elseif( $size[1] <= 12 ) $fontsize = 1;	else $fontsize = min(($size[1] - 6)/2, 7); return \'<font size="\' . $fontsize . \'">\' . $size[2] . \'</font>\';'), $this->parsed_content);
	
		//Preg_replace.
		$array_preg = array( 
			'`<img src="[^"]+" alt="[^"]*" class="smiley" />`i',
			'`<img src="([^"]+)" alt="" class="valign_([^"]+)?" />`i',
			'`<table class="bb_table"( style="([^"]+)")?>`i', 
			'`<td class="bb_table_col"( colspan="[^"]+")?( rowspan="[^"]+")?( style="[^"]+")?>`i',
			'`<th class="bb_table_head"[^>]?>`i',
			'`<span style="color:(.*);">(.*)</span>`isU',
			'`<span style="background-color:(.*);">(.*)</span>`isU',
			'`<span style="text-decoration: underline;">(.*)</span>`isU',
			'`<span style="font-family: ([ a-z0-9,_-]+);">(.*)</span>`isU',
			'`<p style="text-align:(left|center|right|justify)">(.*)</p>`isU',
			'`<p class="float_(left|right)">(.*)</p>`isU',
			'`<span id="([a-z0-9_-]+)">(.*)</span>`isU',
			'`<acronym title="([^"]+)" class="bb_acronym">(.*)</acronym>`isU',
			'`<ul( style="[^"]+")? class="bb_ul">`i',
			'`<ol( style="[^"]+")? class="bb_ol">`i',
			'`<h3 class="title1">(.*)</h3>`isU',
			'`<h3 class="title2">(.*)</h3>`isU',
			'`<h4 class="stitle1">(.*)</h4>`isU',
			'`<h4 class="stitle2">(.*)</h4>`isU',
			'`<span class="(success|question|notice|warning|error)">(.*)</span>`isU',
			'`<object type="application/x-shockwave-flash" data="([^"]+)" width="([^"]+)" height="([^"]+)">(.*)</object>`isU',
			'`<object type="application/x-shockwave-flash" data="\.\./kernel/data/dewplayer\.swf\?son=(.*)" width="200" height="20">(.*)</object>`isU',
			'`<object type="application/x-shockwave-flash" data="\.\./kernel/data/movieplayer\.swf\?movie=(.*)" width="([^"]+)" height="([^"]+)">(.*)</object>`isU'
		);
		$array_preg_replace = array( 
			"$1",
			"<img src=\"$1\" alt=\"\" align=\"$2\" />",
			"<table border=\"0\"$1><tbody>",
			"<td$1$2$3>", 
			"<caption>", 
			"<font color=\"$1\">$2</font>",
			"<span style=\"background-color: $1\">$2</font>",
			"<u>$1</u>",	
			"<font color=\"$1\">$2</font>",
			"<p align=\"$1\">$2</p>",
			"[float=$1]$2[/float]",
			"<a title=\"$1\" name=\"$1\">$2</a>",
			"[acronym=$1]$2[/acronym]",
			"<ul>",
			"<ol>",
			"<h1>$1</h1>",
			"<h2>$1</h2>",
			"<h3>$1</h3>",
			"<h4>$1</h4>",
			"[style=$1]$2[/style]",
			"<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0\" width=\"$2\" height=\"$3\"><param name=\"movie\" value=\"$1\" /><param name=\"quality\" value=\"high\" /><param name=\"menu\" value=\"false\" /><param name=\"wmode\" value=\"\" /><embed src=\"$1\" wmode=\"\" quality=\"high\" menu=\"false\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" type=\"application/x-shockwave-flash\" width=\"$2\" height=\"$3\"></embed></object>",
			"[sound]$1[/sound]",
			"[movie=$2,$3]$1[/movie]"
		);	
		$this->parsed_content = preg_replace($array_preg, $array_preg_replace, $this->parsed_content);
		
		$this->parsed_content = htmlentities($this->parsed_content);
		$this->unparse_code(REIMPLANT);
		$this->_unparse_html(REIMPLANT);
	}
	
	//Préparation avant le parsage, avec l'éditeur WYSIWYG.
	function _preparse_tinymce()
	{
		$this->parsed_content = str_replace(array('&nbsp;&nbsp;&nbsp;', '&gt;', '&lt;', '<br />', '<br>'), array("\t", '&amp;gt;', '&amp;lt;', "\r\n", "\r\n"), $this->parsed_content); //Permet de poster de l'html.
		$this->parsed_content = html_entity_decode($this->parsed_content); //On remplace toutes les entitées html.

		//Balise size
		$this->parsed_content = preg_replace_callback('`<font size="([0-9]+)">(.+)</font>`isU', create_function('$size', 'return \'[size=\' . (6 + (2*$size[1])) . \']\' . $size[2] . \'[/size]\';'), $this->parsed_content);
		//Balise image
		$this->parsed_content = preg_replace_callback('`<img src="([^"]+)"(?: border="[^"]*")? alt="[^"]*"(?: hspace="[^"]*")?(?: vspace="[^"]*")?(?: width="[^"]*")?(?: height="[^"]*")?(?: align="(top|middle|bottom)")? />`is', create_function('$img', '$align = \'\'; if( !empty($img[2]) ) $align = \'=\' . $img[2]; return \'[img\' . $align . \']\' . $img[1] . \'[/img]\';'), $this->parsed_content);

		$array_preg = array(
			'`<strong>(.+)</strong>`isU',
			'`<em>(.+)</em>`isU',
			'`<u>(.+)</u>`isU',
			'`<strike>(.+)</strike>`isU',
			'`<a href="([^"]+)">(.+)</a>`isU',
			'`<sub>(.+)</sub>`isU',
			'`<sup>(.+)</sup>`isU',
			'`<font color="([^"]+)">(.+)</font>`isU',
			'`<font style="background-color: ([^"]+)">(.+)</font>`isU',
			'`<span style="background-color: ([^"]+)">(.+)</span>`isU',
			'`<p style="background-color: ([^"]+)">(.+)</p>`isU',
			'`<font face="([^"]+)">(.+)</font>`isU',
			'`<p align="([a-z]+)">(.+)</p>`isU',
			'`<div style="text-align: ([a-z]+)">(.+)</div>`isU',
			'`<a(?: class="[^"]+")?(?: title="[^"]+" )? name="([^"]+)">(.*)</a>`isU',
			'`<blockquote>(.+)</blockquote>`isU',
			'`<ul>(.+)</ul>`isU',
			'`<ol>(.+)</ol>`isU',
			'`<li>(.+)</li>`isU',
			'`</?font([^>]+)>`i',
			'`<h1>(.+)</h1>`isU',
			'`<h2>(.+)</h2>`isU',
			'`<h3>(.+)</h3>`isU',
			'`<h4>(.+)</h4>`isU',
			'`<h5>(.+)</h5>`isU',
			'`<h6>(.+)</h6>`isU',
			'`<td( colspan="[^"]+")?( rowspan="[^"]+")?>`is',
			'`<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="([^"]+)%?" height="([^"]+)%?"><param name="movie" value="([^"]+)"(.*)</object>`isU',
			'`<span[^>]*>`i',
			'`<p[^r>]*>`i'
		);
		$array_preg_replace = array(
			'[b]$1[/b]',
			'[i]$1[/i]',
			'[u]$1[/u]',
			'[s]$1[/s]',
			'[url=$1]$2[/url]',
			'[sub]$1[/sub]',
			'[sup]$1[/sup]',
			'[color=$1]$2[/color]',
			'[bgcolor=$1]$2[/bgcolor]',
			'[bgcolor=$1]$2[/bgcolor]',
			'[bgcolor=$1]$2[/bgcolor]',
			'[font=$1]$2[/font]',
			'[align=$1]$2[/align]',
			'[align=$1]$2[/align]',
			'[anchor=$1]$2[/anchor]',
			'[indent]$1[/indent]',
			'[list]$1[/list]',
			'[list=ordered]$1[/list]',
			'[*]$1',
			'',
			'[title=1]$1[/title]',
			'[title=2]$1[/title]',
			'[stitle=1]$1[/stitle]',
			'[stitle=2]$1[/stitle]',
			'[size=10]$1[/size]',
			'[size=8]$1[/size]',
			'[col$1$2]',
			'[swf=$1,$2]$3[/swf]',
			'',
			''
		);
	   
		$this->parsed_content = preg_replace($array_preg, $array_preg_replace, $this->parsed_content);	

		//Préparse de la balise table.
		$this->parsed_content = preg_replace_callback('`<table(?: border="[^"]+")?(?: cellspacing="[^"]+")?(?: cellpadding="[^"]+")?(?: height="[^"]+")?(?: width="([^"]+)")?(?: align="[^"]+")?(?: summary="[^"]+")?(?: style="([^"]+)")?[^>]*>`i', array('Content', 'parse_tinymce_table'), $this->parsed_content);
		
		$array_str = array( 
			'</span>', '<address>', '</address>', '<pre>', '</pre>', '<blockquote>', '</blockquote>', '</p>',
			'<caption>', '</caption>', '<tbody>', '</tbody>', '<tr>', '</tr>', '</td>', '</table>', '&lt;', '&gt;', 
		);
		$array_str_replace = array( 
			'', '', '', '[pre]', '[/pre]', '[indent]', '[/indent]', "\r\n\r\n",
			'[row][head]', '[/head][/row]', '', '', '[row]', '[/row]', '[/col]', '[/table]', '<', '>', 
		);
		
		$this->parsed_content = str_replace($array_str, $array_str_replace, $this->parsed_content);
	}

	//Parse la balise table de tinymce pour le bbcode.
	function _parse_tinymce_table($matches)
	{
		$prop = ''; 
		$matches[2] = !empty($matches[2]) ? str_replace('\'', '', $matches[2]) : '';
		if( !empty($matches[1]) && empty($matches[2]) ) 
			$prop .= ' style="width:' . $matches[1] . 'px"';
		if( empty($matches[1]) && !empty($matches[2]) ) 
			$prop .= ' style="' . $matches[2] . '"';
		if( !empty($matches[1]) && !empty($matches[2]) ) 
			$prop .= ' style="width:' . $matches[1] . 'px;' . $matches[2] . '"';
			
		return '[table' . $prop . ']';
	}
}

?>