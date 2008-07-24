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
		parent::ContentParser();
	}
	
	//Fonction qui parse le contenu
	function parse()
	{
		global $Member;
		
		$this->parsed_content = $this->content;
		
		//On enlève toutes les entités HTML rajoutées par TinyMCE
		$this->parsed_content = html_entity_decode($this->parsed_content);
		
		//On supprime d'abord toutes les occurences de balises CODE que nous réinjecterons à la fin pour ne pas y toucher
		if( !in_array('code', $this->forbidden_tags) )
			$this->_pick_up_tag('code', '=[a-z0-9-]+(?:,(?:0|1)(?:,0|1)?)?');
		
		//On prélève tout le code HTML afin de ne pas l'altérer
		if( $Member->check_auth($this->html_auth, 1) )
			$this->_pick_up_tag('html');
		
		//On casse toutes les balises HTML (sauf celles qui ont été prélevées dans le code et la balise HTML)
		$this->parsed_content = htmlspecialchars($this->parsed_content, ENT_NOQUOTES);
		
		echo $this->parsed_content . '<hr />';
		
		//Modification de quelques tags HTML envoyés par YinyMCE
		$this->parsed_content = str_replace(array('&amp;nbsp;&amp;nbsp;&amp;nbsp;', '&amp;gt;', '&amp;lt;', '&lt;br /&gt;', '&lt;br&gt;', '&amp;nbsp;'), array("\t", '&gt;', '&lt;', "\r\n", "\r\n", ' '), $this->parsed_content);
		
		//Balise size
		$this->parsed_content = preg_replace_callback('`&lt;font size="([0-9]+)"&gt;(.+)&lt;/font&gt;`isU', create_function('$size', 'return \'<span style="font-size: \' . (8 + (3*$size[1])) . \'px;">\' . $size[2] . \'</span>\' . "\n<br />";'), $this->parsed_content);
		
		//Balise pre
		if( strpos($this->parsed_content, '&lt;pre&gt;') !== false )
		{
			$this->parsed_content = preg_replace('`&lt;/pre&gt;\s*&lt;pre&gt;`isU', "\n", $this->parsed_content);
		}
		
		//Balise image
		$this->parsed_content = preg_replace_callback('`&lt;img src="([^"]+)"(?: border="[^"]*")? alt="[^"]*"(?: hspace="[^"]*")?(?: vspace="[^"]*")?(?: width="[^"]*")?(?: height="[^"]*")?(?: align="(top|middle|bottom)")? /&gt;`is', create_function('$img', '$align = \'\'; if( !empty($img[2]) ) $align = \'=\' . $img[2]; return \'<img src="\' . $img[1] . \'" alt="" class="valign_"\' . $align . \' />\';'), $this->parsed_content);
		
		//Balise indent
		$this->_parse_imbricated('&lt;blockquote&gt;', '`&lt;blockquote&gt;(.+)&lt;/blockquote&gt;`isU', '<div class="indent">$1</div>');

		//Transformation des tableaux
		$content_contains_table = false;
		while( preg_match('`&lt;table([^&]*)&gt;(.+)&lt;/table&gt;`is', $this->parsed_content) )
		{
			$this->parsed_content = preg_replace_callback('`&lt;table([^&]*)&gt;(.+)&lt;/table&gt;`isU', array('TinyMCEParser', '_parse_table_tag'), $this->parsed_content);
			$content_contains_table = true;
		}
		
		if( $content_contains_table )
		{
			$this->parsed_content = preg_replace('`&lt;tr&gt;(.*)&lt;/tr&gt;`isU', '<tr class="bb_table_row">$1</tr>', $this->parsed_content);
			
			//Rows
			while( preg_match('`&lt;td|h([^&]*)&gt;(.+)&lt;/td|h&gt;`is', $this->parsed_content) )
			{
				$this->parsed_content = preg_replace_callback('`&lt;(td)([^&]*)&gt;(.+)&lt;/td&gt;`isU', array('TinyMCEParser', '_parse_col_tag'), $this->parsed_content);
				$this->parsed_content = preg_replace_callback('`&lt;(th)([^&]*)&gt;(.+)&lt;/th&gt;`isU', array('TinyMCEParser', '_parse_col_tag'), $this->parsed_content);
				$content_contains_table = true;
			}
		}
		
		$array_preg = array(
			'`&lt;div&gt;(.+)&lt;/div&gt;`isU',
			'`&lt;p&gt;(.+)&lt;/p&gt;`isU',
			'`&lt;strong&gt;(.+)&lt;/strong&gt;`isU',
			'`&lt;em&gt;(.+)&lt;/em&gt;`isU',
			'`&lt;u&gt;(.+)&lt;/u&gt;`isU',
			'`&lt;strike&gt;(.+)&lt;/strike&gt;`isU',
			'`&lt;a href="([^"]+)"&gt;(.+)&lt;/a&gt;`isU',
			'`&lt;sub&gt;(.+)&lt;/sub&gt;`isU',
			'`&lt;sup&gt;(.+)&lt;/sup&gt;`isU',
			'`&lt;pre&gt;(.+)&lt;/pre&gt;`isU',
			'`&lt;font color="([^"]+)"&gt;(.+)&lt;/font&gt;`isU',
			'`&lt;font style="background-color: ([^"]+)"&gt;(.+)&lt;/font&gt;`isU',
			'`&lt;span style="background-color: ([^"]+)"&gt;(.+)&lt;/span&gt;`isU',
			'`&lt;p style="background-color: ([^"]+)"&gt;(.+)&lt;/p&gt;`isU',
			'`&lt;font face="([^"]+)"&gt;(.+)&lt;/font&gt;`isU',
			'`&lt;div align="([a-z]+)"&gt;(.+)&lt;/div&gt;`isU',
			'`&lt;div style="text-align: ([a-z]+)"&gt;(.+)&lt;/div&gt;`isU',
			'`&lt;a(?: class="[^"]+")?(?: title="[^"]+" )? name="([^"]+)"&gt;(.*)&lt;/a&gt;`isU',
			'`&lt;ul&gt;(.+)&lt;/ul&gt;`isU',
			'`&lt;ol&gt;(.+)&lt;/ol&gt;`isU',
			'`&lt;li&gt;(.*)&lt;/li&gt;`isU',
			'`&lt;/?font([^&]+)&gt;`i',
			'`&lt;h1&gt;(.+)&lt;/h1&gt;`isU',
			'`&lt;h2&gt;(.+)&lt;/h2&gt;`isU',
			'`&lt;h3&gt;(.+)&lt;/h3&gt;`isU',
			'`&lt;h4&gt;(.+)&lt;/h4&gt;`isU',
			'`&lt;h5&gt;(.+)&lt;/h5&gt;`isU',
			'`&lt;h6&gt;(.+)&lt;/h6&gt;`isU',
			'`&lt;object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="([^"]+)%?" height="([^"]+)%?"&gt;&lt;param name="movie" value="([^"]+)"(.*)&lt;/object&gt;`isU',
			'`&lt;span[^&]*&gt;`i',
			'`&lt;p[^&]*&gt;`i'
		);
		$array_preg_replace = array(
			'$1' . "\n<br />",
			'$1' . "\n<br />",
			'<strong>$1</strong>',
			'<em>$1</em>',
			'<span style="text-decoration: underline;">$1</span>',
			'<strike>$1</strike>',
			'<a href="$1">$2</a>',
			'<sub>$1</sub>',
			'<sup>$1</sup>',
			'<pre>$1</pre>',
			'<span style="color:$1;">$2</span>',
			'<span style="background-color:$1;">$2</span>',
			'<span style="background-color:$1;">$2</span>',
			'<span style="background-color:$1;">$2</span>',
			'<span style="font-family: $1;">$2</span>',
			'<div style="text-align:$1">$2</div>',
			'<div style="text-align:$1">$2</div>',
			'<span id="$1">$2</span>',
			'<ul class="bb_ul">$1</ul>',
			'<ol class="bb_ol">$1</ol>',
			'<li class="bb_li">$1</li>',
			'',
			'<h3 class="title1">$1</h3>',
			'<h3 class="title2">$1</h3>',
			'<br /><h4 class="stitle1">$1</h4><br />',
			'<br /><h4 class="stitle2">$1</h4><br />',
			'<span style="font-size: 10px;">$1</span>',
			'<span style="font-size: 8px;">$1</span>',
			'<object type="application/x-shockwave-flash" data="$3" width="$1" height="$2">
		<param name="allowScriptAccess" value="never" />
		<param name="play" value="true" />
		<param name="movie" value="$3" />
		<param name="menu" value="false" />
		<param name="quality" value="high" />
		<param name="scalemode" value="noborder" />
		<param name="wmode" value="transparent" />
		<param name="bgcolor" value="#000000" />
		</object>',
			'',
			''
		);
	   
		$this->parsed_content = preg_replace($array_preg, $array_preg_replace, $this->parsed_content);	
		
		$array_str = array( 
			'&lt;address&gt;', '&lt;/address&gt;', '&lt;caption&gt;', '&lt;/caption&gt;', '&lt;tbody&gt;', '&lt;/tbody&gt;'
		);
		
		$this->parsed_content = str_replace($array_str, '', $this->parsed_content);
		
		//Smilies
		@include(PATH_TO_ROOT . '/cache/smileys.php');
		if( !empty($_array_smiley_code) )
		{
			//Création du tableau de remplacement.
			foreach($_array_smiley_code as $code => $img)
			{
				$smiley_code[] = '`(?<!&[a-z]{4}|&[a-z]{5}|&[a-z]{6}|")(' . str_replace('\'', '\\\\\\\'', preg_quote($code)) . ')`';
				$smiley_img_url[] = '<img src="../images/smileys/' . $img . '" alt="' . addslashes($code) . '" class="smiley" />';
			}
			$this->parsed_content = preg_replace($smiley_code, $smiley_img_url, $this->parsed_content);
		}
		
		//On remet le code HTML mis de côté
		if( $Member->Check_auth($this->html_auth, 1) && !empty($this->array_tags['html']) )
		{
			$this->array_tags['html'] = array_map(create_function('$string', 'return str_replace("[html]", "<!-- START HTML -->\n", str_replace("[/html]", "\n<!-- END HTML -->", $string));'), $this->array_tags['html']);
			$this->_reimplant_tag('html');
		}
		
		//On réinsère les fragments de code qui ont été prévelevés pour ne pas les considérer
		if( !in_array('code', $this->forbidden_tags) && !empty($this->array_tags['code']) )
		{
			$this->array_tags['code'] = array_map(create_function('$string', 'return preg_replace(\'`^\[code(=.+)?\](.+)\[/code\]$`isU\', \'[[CODE$1]]$2[[/CODE]]\', $string);'), $this->array_tags['code']);
			$this->_reimplant_tag('code');
		}
	}
	
	//Unparser
	function unparse()
	{
		$this->parsed_content = $this->content;
		
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
	}
	
	## Protected ##

	//Parse la balise table de tinymce pour le bbcode.
	/*static*/ function _parse_table_tag($matches)
	{
		$table_properties = $matches[1];
		$style_properties = '';
		
		$temp_array = array();
		
		//Border ?
		if( strpos($table_properties, 'border') !== false )
		{
			preg_match('`border="([0-9]+)"`iU', $table_properties, $temp_array);
			$style_properties .= 'border:' . $temp_array[1] . 'px;';
		}
		
		//Width ?
		if( strpos($table_properties, 'width') !== false )
		{
			preg_match('`width="([0-9]+)"`iU', $table_properties, $temp_array);
			$style_properties .= 'width:' . $temp_array[1] . 'px;';
		}
		
		//Height ?
		if( strpos($table_properties, 'height') !== false )
		{
			preg_match('`height="([0-9]+)"`iU', $table_properties, $temp_array);
			$style_properties .= 'height:' . $temp_array[1] . 'px;';
		}
		
		//Alignment
		if( strpos($table_properties, 'align') !== false )
		{
			preg_match('`align="([^"]+)"`iU', $table_properties, $temp_array);
			if( $temp_array[1] == 'center' )
				$style_properties .= 'margin:auto;';
			elseif( $temp_array[1] == 'right' )
				$style_properties .= 'margin-left:auto;';
			//$style_properties .= 'text-align:' . $temp_array[1] . ';';
		}
		
		//Style ?
		if( strpos($table_properties, 'style') !== false )
		{
			preg_match('`style="([^"])"`iU', $table_properties, $temp_array);
			$style_properties .= $temp_array[1];
		}
		
		return '<table class="bb_table"' . (!empty($style_properties) ? ' style="' . $style_properties . '"' : '') . '>' . $matches[2] . '</table>';
	}
	
	//Parse la balise table de tinymce pour le bbcode.
	/*static*/ function _parse_col_tag($matches)
	{
		$col_properties = $matches[2];
		$col_new_properties = '';
		$col_style = '';
		
		$temp_array = array();
		
		//Colspan ?
		if( strpos($col_properties, 'colspan') !== false )
		{
			preg_match('`colspan="([0-9]+)"`iU', $col_properties, $temp_array);
			$col_new_properties .= ' colspan="' . $temp_array[1] . '"';
		}
		
		//Rowspan ?
		if( strpos($col_properties, 'rowspan') !== false )
		{
			preg_match('`rowspan="([0-9]+)"`iU', $col_properties, $temp_array);
			$col_new_properties .= ' rowspan="' . $temp_array[1] . '"';
		}
		
		//Alignment
		if( strpos($col_properties, 'align') !== false )
		{
			preg_match('`align="([^"]+)"`iU', $col_properties, $temp_array);
			$col_style .= 'text-align:' . $temp_array[1] . ';';
		}
		
		//Style ?
		if( strpos($col_properties, 'style') !== false )
		{
			preg_match('`style="([^"])"`iU', $col_properties, $temp_array);
			$col_style .= ' style="' . $temp_array[1] . ' ' . $col_style . '"';
		}
		elseif( !empty($col_style) )
			$col_style = ' style="' . $col_style . '"';
		
		return '<' . $matches[1] . ' class="bb_table"' . $col_new_properties . ' ' . $col_style . '>' . $matches[3] . '</' . $matches[1] . '>';
	}
}

?>