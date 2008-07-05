<?php
/*##################################################
*                             bbcode_parser.class.php
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

class BBCodeParser extends ContentParser
{
	function BBCodeParser()
	{
		parent::ContentParser();
	}
	
	//On parse le contenu: bbcode => xhtml.
	function parse()
	{
		global $LANG, $Member;
		
		$this->parsed_content = $this->content;
		
		//On supprime d'abord toutes les occurences de balises CODE que nous réinjecterons à la fin pour ne pas y toucher
		if( !in_array('code', $this->forbidden_tags) )
			$this->_pick_up_tag('code', '=[a-z0-9-]+(?:,(?:0|1)(?:,0|1)?)?');
		
		//On prélève tout le code HTML afin de ne pas l'altérer
		if( $Member->check_auth($this->html_auth, 1) )
			$this->_pick_up_tag('html');
		
		//Ajout des espaces pour éviter l'absence de parsage lorsqu'un séparateur de mot est éxigé
		$this->parsed_content = ' ' . $this->parsed_content . ' ';

		//Protection : suppression du code html
		$this->parsed_content = htmlspecialchars($this->parsed_content, ENT_NOQUOTES);
		$this->parsed_content = strip_tags($this->parsed_content);
		$this->parsed_content = preg_replace('`&amp;((?:#[0-9]{2,4})|(?:[a-z0-9]{2,6}));`i', "&$1;", $this->parsed_content);
		
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
		
		//Remplacement des caractères de word.
		$array_str = array( 
			'€', '‚', 'ƒ', '„', '…', '†', '‡', 'ˆ', '‰',
			'Š', '‹', 'Œ', 'Ž', '‘', '’', '“', '”', '•',
			'–', '—',  '˜', '™', 'š', '›', 'œ', 'ž', 'Ÿ'
		);
		
		$array_str_replace = array( 
			'&#8364;', '&#8218;', '&#402;', '&#8222;', '&#8230;', '&#8224;', '&#8225;', '&#710;', '&#8240;',
			'&#352;', '&#8249;', '&#338;', '&#381;', '&#8216;', '&#8217;', '&#8220;', '&#8221;', '&#8226;',
			'&#8211;', '&#8212;', '&#732;', '&#8482;', '&#353;', '&#8250;', '&#339;', '&#382;', '&#376;'
		);		
		$this->parsed_content = str_replace($array_str, $array_str_replace, $this->parsed_content);
		
		//Preg_replace.
		$array_preg = array( 
			'b' => '`\[b\](.+)\[/b\]`isU',
			'i' => '`\[i\](.+)\[/i\]`isU',
			'u' => '`\[u\](.+)\[/u\]`isU',
			's' => '`\[s\](.+)\[/s\]`isU',
			'sup' => '`\[sup\](.+)\[/sup\]`isU',
			'sub' => '`\[sub\](.+)\[/sub\]`isU',
			'img' => '`\[img(?:=(top|middle|bottom))?\](((\./|\.\./)|([\w]+://))+[^,\n\r\t\f]+\.(jpg|jpeg|bmp|gif|png|tiff|svg))\[/img\]`iU',
			'color' => '`\[color=((?:white|black|red|green|blue|yellow|purple|orange|maroon|pink)|(?:#[0-9a-f]{6}))\](.+)\[/color\]`isU',
			'bgcolor' => '`\[bgcolor=((?:white|black|red|green|blue|yellow|purple|orange|maroon|pink)|(?:#[0-9a-f]{6}))\](.+)\[/bgcolor\]`isU',
			'size' => '`\[size=([1-9]|(?:[1-4][0-9]))\](.+)\[/size\]`isU',
			'font' => '`\[font=(arial|times|courier(?: new)?|impact|geneva|optima)\](.+)\[/font\]`isU',
			'pre' => '`\[pre\](.+)\[/pre\]`isU',
			'align' => '`\[align=(left|center|right|justify)\](.+)\[/align\]`isU',
			'float' => '`\[float=(left|right)\](.+)\[/float\]`isU',
			'anchor' => '`\[anchor=([a-z_][a-z0-9_]*)\](.*)\[/anchor\]`isU',
			'acronym' => '`\[acronym=([^\n[\]<]+)\](.*)\[/acronym\]`isU',
			'title' => '`\[title=([1-2])\](.+)\[/title\]`iU',
			'stitle' => '`\[stitle=([1-2])\](.+)\[/stitle\]`iU',
			'style' => '`\[style=(success|question|notice|warning|error)\](.+)\[/style\]`isU',
			'swf' => '`\[swf=([0-6][0-9]{0,2}),([0-6][0-9]{0,2})\]((?:(\./|\.\./)|([\w]+://))+[^,\n\r\t\f]+)\[/swf\]`iU',
			'movie' => '`\[movie=([0-6][0-9]{0,2}),([0-6][0-9]{0,2})\]([^\n\r\t\f]+)\[/movie\]`iU',
			'sound' => '`\[sound\]((?:(?:\.?\./)+|(?:https?|ftps?)+://([a-z0-9-]+\.)*[a-z0-9-]+\.[a-z]{2,4})+(?:[a-z0-9~_-]+/)*[a-z0-9_-]+\.mp3)\[/sound\]`iU',
			'url' => '`\[url\]((?:(?:\.?\./)+|(?:https?|ftps?)+://(?:[a-z0-9-]+\.)*[a-z0-9-]+(?:\.[a-z]{2,4})?/?)(?:[a-z0-9~_-]+/)*[a-z0-9_+.:?/=#%@&;,-]*)\[/url\]`isU',
			'url2' => '`\[url\]((?:www\.(?:[a-z0-9-]+\.)*[a-z0-9-]+(?:\.[a-z]{2,4})?/?)(?:[a-z0-9~_-]+/)*[a-z0-9_+.:?/=#%@&;,-]*)\[/url\]`isU',
			'url3' => '`\[url=((?:(?:\.?\./)+|(?:https?|ftps?)+://(?:[a-z0-9-]+\.)*[a-z0-9-]+(?:\.[a-z]{2,4})?/?)(?:[a-z0-9~_-]+/)*[a-z0-9_+.:?/=#%@&;,-]*)\]([^\n\r\t\f]+)\[/url\]`isU',
			'url4' => '`\[url=((?:www\.(?:[a-z0-9-]+\.)*[a-z0-9-]+(?:\.[a-z]{2,4})?/?)(?:[a-z0-9~_-]+/)*[a-z0-9_+.:?/=#%@&;,-]*)\]([^\n\r\t\f]+)\[/url\]`iU',
			'url5' => '`(\s+)((?:(?:\.?\./)+|(?:https?|ftps?)+://(?:[a-z0-9-]+\.)*[a-z0-9-]+(?:\.[a-z]{2,4})?/?)(?:[a-z0-9~_-]+/)*[a-z0-9_+.:?/=#%@&;,-]*)(\s+)`isU', 
			'url6' => '`(\s+)((?:www\.(?:[a-z0-9-]+\.)*[a-z0-9-]+(?:\.[a-z]{2,4})?/?)(?:[a-z0-9~_-]+/)*[a-z0-9_+.:?/=#%@&;,-]*)(\s+)`i',
			'mail' => '`(\s+)([a-zA-Z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4})(\s+)`i',
			'mail2' => '`\[mail=([a-zA-Z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4})\]([^\n\r\t\f]+)\[/mail\]`i'
		);
		$array_preg_replace = array( 
			'b' => "<strong>$1</strong>",
			'i' => "<em>$1</em>",
			'u' => "<span style=\"text-decoration: underline;\">$1</span>",
			's' => "<strike>$1</strike>",		
			'sup' => '<sup>$1</sup>',
			'sub' => '<sub>$1</sub>',
			'img' => "<img src=\"$2\" alt=\"\" class=\"valign_$1\" />",
			'color' => "<span style=\"color:$1;\">$2</span>",
			'bgcolor' => "<span style=\"background-color:$1;\">$2</span>",
			'size' => "<span style=\"font-size: $1px;\">$2</span>",
			'font' => "<span style=\"font-family: $1;\">$2</span>",
			'pre' => "<pre>$1</pre>",
			'align' => "<p style=\"text-align:$1\">$2</p>",
			'float' => "<p class=\"float_$1\">$2</p>",	
			'anchor' => "<span id=\"$1\">$2</span>",
			'acronym' => "<acronym title=\"$1\" class=\"bb_acronym\">$2</acronym>",
			'title' => "<h3 class=\"title$1\">$2</h3>",
			'stitle' => "<br /><h4 class=\"stitle$1\">$2</h4><br />",
			'style' => "<span class=\"$1\">$2</span>",
			'swf' => "<object type=\"application/x-shockwave-flash\" data=\"$3\" width=\"$1\" height=\"$2\">
		<param name=\"allowScriptAccess\" value=\"never\" />
		<param name=\"play\" value=\"true\" />
		<param name=\"movie\" value=\"$3\" />
		<param name=\"menu\" value=\"false\" />
		<param name=\"quality\" value=\"high\" />
		<param name=\"scalemode\" value=\"noborder\" />
		<param name=\"wmode\" value=\"transparent\" />
		<param name=\"bgcolor\" value=\"#000000\" />
		</object>",
			'movie' => "<object type=\"application/x-shockwave-flash\" data=\"../kernel/data/movieplayer.swf?movie=$3\" width=\"$1\" height=\"$2\">
		<param name=\"allowScriptAccess\" value=\"never\" />
		<param name=\"play\" value=\"true\" />
		<param name=\"movie\" value=\"$1\" />
		<param name=\"menu\" value=\"false\" />
		<param name=\"quality\" value=\"high\" />
		<param name=\"scalemode\" value=\"noborder\" />
		<param name=\"wmode\" value=\"transparent\" />
		<param name=\"bgcolor\" value=\"#FFFFFF\" />
		</object>",
			'sound' => "<object type=\"application/x-shockwave-flash\" data=\"../kernel/data/dewplayer.swf?son=$1\" width=\"200\" height=\"20\">
		<param name=\"allowScriptAccess\" value=\"never\" />
		<param name=\"play\" value=\"true\" />
		<param name=\"movie\" value=\"../kernel/data/dewplayer.swf?son=$1\" />
		<param name=\"menu\" value=\"false\" />
		<param name=\"quality\" value=\"high\" />
		<param name=\"scalemode\" value=\"noborder\" />
		<param name=\"wmode\" value=\"transparent\" />
		<param name=\"bgcolor\" value=\"#FFFFFF\" />
		</object>",
			'url' => "<a href=\"$1\">$1</a>",
			'url2' => "<a href=\"http://$1\">$1</a>",
			'url3' => "<a href=\"$1\">$2</a>",
			'url4' => "<a href=\"http://$1\">$2</a>",
			'url5' => "$1<a href=\"$2\">$2</a>$3", 
			'url6' => "$1<a href=\"http://$2\">$2</a>$3",
			'mail' => "$1<a href=\"mailto:$2\">$2</a>$3",
			'mail2' => "<a href=\"mailto:$1\">$2</a>"
		);

		$parse_line = true;
		
		//Suppression des remplacements des balises interdites.
		if( !empty($this->forbidden_tags) )
		{
			//Si on interdit les liens, on ajoute toutes les manières par lesquelles elles peuvent passer
			if( in_array('url', $this->forbidden_tags) )
			{
				$this->forbidden_tags[] = 'url2';
				$this->forbidden_tags[] = 'url3';
				$this->forbidden_tags[] = 'url4';
				$this->forbidden_tags[] = 'url5';
				$this->forbidden_tags[] = 'url6';
			}
			if( in_array('mail', $this->forbidden_tags) )
				$this->forbidden_tags[] = 'mail2';
			
			$other_tags = array('table', 'code', 'math', 'quote', 'hide', 'indent', 'list'); 
			foreach($this->forbidden_tags as $key => $tag)
			{	
				if( in_array($tag, $other_tags) )
				{
					$array_preg[$tag] = '`\[' . $tag . '.*\](.+)\[/' . $tag . '\]`isU';
					$array_preg_replace[$tag] = "$1";
				}
				elseif( $tag == 'line' )
				{
					$parse_line = false;
				}
				else
				{	
					unset($array_preg[$tag]);
					unset($array_preg_replace[$tag]);
				}
			}	
		}
		
		//Remplacement : on parse les balises classiques
		$this->parsed_content = preg_replace($array_preg, $array_preg_replace, $this->parsed_content);
		
		//Line tag
		if( $parse_line )
			$this->parsed_content = str_replace('[line]', '<hr class="bb_hr" />', $this->parsed_content);
		
		//Interprétation des sauts de ligne
		$this->parsed_content = nl2br($this->parsed_content);
		
		//Tableaux
		if( strpos($this->parsed_content, '[table') !== false )
			$this->_parse_table();
		
		//Listes
		if( strpos($this->parsed_content, '[list') !== false )
			$this->_parse_list();
		
		##### //Fonction de parsage des balises imbriquées générique à faire #####
		//Parsage des balises imbriquées.
		$this->_parse_imbricated('[quote]', '`\[quote\](.+)\[/quote\]`sU', '<span class="text_blockquote">' . $LANG['quotation'] . ':</span><div class="blockquote">$1</div>', $this->parsed_content);
		$this->_parse_imbricated('[quote=', '`\[quote=([^\]]+)\](.+)\[/quote\]`sU', '<span class="text_blockquote">$1:</span><div class="blockquote">$2</div>', $this->parsed_content);
		$this->_parse_imbricated('[hide]', '`\[hide\](.+)\[/hide\]`sU', '<span class="text_hide">' . $LANG['hide'] . ':</span><div class="hide" onclick="bb_hide(this)"><div class="hide2">$1</div></div>', $this->parsed_content);
		$this->_parse_imbricated('[indent]', '`\[indent\](.+)\[/indent\]`sU', '<div class="indent">$1</div>', $this->parsed_content);
		
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
	
		//On unparse le contenu xHTML => BBCode
	function unparse()
	{
		$this->parsed_content = $this->content;
		
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
			
		//Remplacement des balises imbriquées.	
		$this->_parse_imbricated('<span class="text_blockquote">', '`<span class="text_blockquote">(.*):</span><div class="blockquote">(.*)</div>`sU', '[quote=$1]$2[/quote]', $this->parsed_content);
		$this->_parse_imbricated('<span class="text_hide">', '`<span class="text_hide">(.*):</span><div class="hide" onclick="bb_hide\(this\)"><div class="hide2">(.*)</div></div>`sU', '[hide]$2[/hide]', $this->parsed_content);
		$this->_parse_imbricated('<div class="indent">', '`<div class="indent">(.+)</div>`sU', '[indent]$1[/indent]', $this->parsed_content);
			
		//Str_replace.
		$array_str = array( 
			'<br />', '<strong>', '</strong>', '<em>', '</em>', '<strike>', '</strike>', '<hr class="bb_hr" />', '&#8364;', '&#8218;', '&#402;', '&#8222;',
			'&#8230;', '&#8224;', '&#8225;', '&#710;', '&#8240;', '&#352;', '&#8249;', '&#338;', '&#381;',
			'&#8216;', '&#8217;', '&#8220;', '&#8221;', '&#8226;', '&#8211;', '&#8212;', '&#732;', '&#8482;',
			'&#353;', '&#8250;', '&#339;', '&#382;', '&#376;'
		);
		
		$array_str_replace = array( 
			'', '[b]', '[/b]', '[i]', '[/i]', '[s]', '[/s]', '[line]', '€', '‚', 'ƒ',
			'„', '…', '†', '‡', 'ˆ', '‰', 'Š', '‹', 'Œ', 'Ž',
			'‘', '’', '“', '”', '•', '–', '—',  '˜', '™', 'š',
			'›', 'œ', 'ž', 'Ÿ'
		);	
		$this->parsed_content = str_replace($array_str, $array_str_replace, $this->parsed_content);

		//Preg_replace.
		$array_preg = array( 
			'`<img src="([^?\n\r\t].*)" alt="[^"]*"(?: class="[^"]+")? />`iU',
			'`<span style="color:([^;]+);">(.*)</span>`isU',
			'`<span style="background-color:([^;]+);">(.*)</span>`isU',
			'`<span style="text-decoration: underline;">(.*)</span>`isU',
			'`<sup>(.+)</sup>`isU',
			'`<sub>(.+)</sub>`isU',
			'`<span style="font-size: ([0-9]+)px;">(.*)</span>`isU',
			'`<span style="font-family: ([ a-z0-9,_-]+);">(.*)</span>`isU',
			'`<pre>(.*)</pre>`isU',
			'`<p style="text-align:(left|center|right|justify)">(.*)</p>`isU',
			'`<p class="float_(left|right)">(.*)</p>`isU',
			'`<span id="([a-z0-9_-]+)">(.*)</span>`isU',
			'`<acronym title="([^"]+)" class="bb_acronym">(.*)</acronym>`isU',
			'`<a href="mailto:(.*)">(.*)</a>`isU',
			'`<a href="([^"]+)">(.*)</a>`isU',
			'`<h3 class="title([1-2]+)">(.*)</h3>`isU',
			'`<h4 class="stitle([1-2]+)">(.*)</h4>`isU',
			'`<span class="(success|question|notice|warning|error)">(.*)</span>`isU',
			'`<object type="application/x-shockwave-flash" data="\.\./kernel/data/dewplayer\.swf\?son=(.*)" width="200" height="20">(.*)</object>`isU',
			'`<object type="application/x-shockwave-flash" data="\.\./kernel/data/movieplayer\.swf\?movie=(.*)" width="([^"]+)" height="([^"]+)">(.*)</object>`isU',
			'`<object type="application/x-shockwave-flash" data="([^"]+)" width="([^"]+)" height="([^"]+)">(.*)</object>`isU',
			'`<!-- START HTML -->' . "\n" . '(.+)' . "\n" . '<!-- END HTML -->`isU'
		);
		
		$array_preg_replace = array( 
			"[img]$1[/img]",
			"[color=$1]$2[/color]",
			"[bgcolor=$1]$2[/bgcolor]",
			"[u]$1[/u]",	
			"[sup]$1[/sup]",
			"[sub]$1[/sub]",
			"[size=$1]$2[/size]",
			"[font=$1]$2[/font]",
			"[pre]$1[/pre]",
			"[align=$1]$2[/align]",
			"[float=$1]$2[/float]",
			"[anchor=$1]$2[/anchor]",
			"[acronym=$1]$2[/acronym]",
			"$1",
			"[url=$1]$2[/url]",
			"[title=$1]$2[/title]",
			"[stitle=$1]$2[/stitle]",
			"[style=$1]$2[/style]",
			"[sound]$1[/sound]",
			"[movie=$2,$3]$1[/movie]",
			"[swf=$2,$3]$1[/swf]",
			"[html]$1[/html]"
		);	
		$this->parsed_content = preg_replace($array_preg, $array_preg_replace, $this->parsed_content);

		//Unparsage de la balise table.
		if( strpos($this->parsed_content, '<table') !== false )
			$this->_unparse_table();

		//Unparsage de la balise table.
		if( strpos($this->parsed_content, '<li') !== false )
			$this->_unparse_list();
		
		$this->_unparse_code(REIMPLANT);
		$this->_unparse_html(REIMPLANT);
	}
}

?>