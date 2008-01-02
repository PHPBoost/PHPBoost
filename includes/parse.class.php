<?php
/*##################################################
 *                             parse.class.php
 *                            -------------------
 *   begin                : November 29, 2007
 *   copyright          : (C) 2007 Régis Viarre
 *   email                : crowkait@phpboost.com
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

//Fonction d'importation/exportation de base de donnée.
class Parse
{
    var $editors = array('bbcode', 'tinymce'); //Editeurs texte supportés.
    var $user_editor = 'bbcode'; //Editeur texte du membre.
	var $balise = array('b', 'i', 'u', 's',	'title', 'stitle', 'style', 'url', 
	'img', 'quote', 'hide', 'list', 'color', 'bgcolor', 'font', 'size', 'align', 'float', 'sup', 
	'sub', 'indent', 'pre', 'table', 'swf', 'movie', 'sound', 'code', 'math', 'anchor', 'acronym'); //Balises supportées.
	
	//On vérifie que le répertoire cache existe et est inscriptible
    function Parse($user_editor)
    {
		$this->user_editor = in_array($user_editor, $this->editors) ? $user_editor : 'bbcode';
    }
    
	############################## Parsage ##############################
	//Préparation avant le parsage, avec l'éditeur WYSIWYG.
	function preparse_tinymce(&$contents)
	{
		$contents = str_replace(array('&nbsp;&nbsp;&nbsp;', '&gt;', '&lt;', '<br />', '<br>'), array("\t", '&amp;gt;', '&amp;lt;', "\r\n", "\r\n"), $contents); //Permet de poster de l'html.
		$contents = html_entity_decode($contents); //On remplace toutes les entitées html.

		//Balise size
		$contents = preg_replace_callback('`<font size="([0-9]+)">(.+)</font>`isU', create_function('$size', 'return \'[size=\' . (6 + (2*$size[1])) . \']\' . $size[2] . \'[/size]\';'), $contents);
		//Balise image
		$contents = preg_replace_callback('`<img src="([^"]+)"(?: border="[^"]*")? alt="[^"]*"(?: hspace="[^"]*")?(?: vspace="[^"]*")?(?: width="[^"]*")?(?: height="[^"]*")?(?: align="(top|middle|bottom)")? />`is', create_function('$img', '$align = \'\'; if( !empty($img[2]) ) $align = \'=\' . $img[2]; return \'[img\' . $align . \']\' . $img[1] . \'[/img]\';'), $contents);

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
		$contents = preg_replace($array_preg, $array_preg_replace, $contents);	

		//Préparse de la balise table.
		$contents = preg_replace_callback('`<table(?: border="[^"]+")?(?: cellspacing="[^"]+")?(?: cellpadding="[^"]+")?(?: height="[^"]+")?(?: width="([^"]+)")?(?: align="[^"]+")?(?: summary="[^"]+")?(?: style="([^"]+)")?[^>]*>`i', array('Parse', 'parse_tinymce_table'), $contents);
		
		$array_str = array( 
			'</span>', '<address>', '</address>', '<pre>', '</pre>', '<blockquote>', '</blockquote>', '</p>',
			'<caption>', '</caption>', '<tbody>', '</tbody>', '<tr>', '</tr>', '</td>', '</table>', '&lt;', '&gt;', 
		);
		$array_str_replace = array( 
			'', '', '', '[pre]', '[/pre]', '[indent]', '[/indent]', "\r\n\r\n",
			'[row][head]', '[/head][/row]', '', '', '[row]', '[/row]', '[/col]', '[/table]', '<', '>', 
		);		
		$contents = str_replace($array_str, $array_str_replace, $contents);
	}

	//Parse la balise table de tinymce pour le bbcode.
	function parse_tinymce_table($matches)
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

	//On parse le contenu: bbcode => xhtml.
	function parse_content($contents, $forbidden_tags, $html_protect, $magic_quotes_activ)
	{
		global $LANG;
				
		$contents = ' ' . trim(stripslashes($contents)) . ' '; //Ajout des espaces pour éviter l'absence de parsage lorsqu'un séparateur de mot est éxigé. Suppression des backslash ajoutés par magic_quotes_gpc.
		if( $this->user_editor == 'tinymce' ) //Préparse pour tinymce.
			$this->preparse_tinymce($contents);
		
		//On échappe les guillemets et apostrophes.
		$contents = addslashes($contents);	

		if( $html_protect ) //Protection des données.
		{
			$contents = htmlspecialchars($contents);
			$contents = strip_tags($contents);
		}
		$contents = nl2br($contents);
		$contents = preg_replace('`&amp;((?:#[0-9]{2,4})|(?:[a-z0-9]{2,6}));`i', "&$1;", $contents);
		
		//Smiley.
		@include('../cache/smileys.php');
		if( !empty($_array_smiley_code) )
		{	
			//Création du tableau de remplacement.
			foreach($_array_smiley_code as $code => $img)
			{	
				$smiley_code[] = '`(?<!&[a-z]{4}|&[a-z]{5}|&[a-z]{6}|")(' . str_replace('\'', '\\\\\\\'', preg_quote($code)) . ')`';
				$smiley_img_url[] = '<img src="../images/smileys/' . $img . '" alt="' . addslashes($code) . '" class="smiley" />';
			}
			$contents = preg_replace($smiley_code, $smiley_img_url, $contents);
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
		$contents = str_replace($array_str, $array_str_replace, $contents);

		//Preg_replace.
		$array_preg = array( 
			'b' => '`\[b\](.+)\[/b\]`isU',
			'i' => '`\[i\](.+)\[/i\]`isU',
			'u' => '`\[u\](.+)\[/u\]`isU',
			's' => '`\[s\](.+)\[/s\]`isU',
			'sup' => '`\[sup\](.+)\[/sup\]`isU',
			'sub' => '`\[sub\](.+)\[/sub\]`isU',
			'img' => '`\[img(?:=(top|middle|bottom))?\]((?:(\./|\.\./)|([\w]+://))+[^,\n\r\t\f]+\.(jpg|jpeg|bmp|gif|png|tiff|svg))\[/img\]`iU',
			'color' => '`\[color=((white|black|red|green|blue|yellow|purple|orange|maroon)|(#[0-9a-f]{6}))\](.+)\[/color\]`isU',
			'bgcolor' => '`\[bgcolor=((white|black|red|green|blue|yellow|purple|orange|maroon)|(#[0-9a-f]{6}))\](.+)\[/bgcolor\]`isU',
			'size' => '`\[size=(([0-9]{1})|([0-4]+[0-9]?))\](.+)\[/size\]`isU',
			'font' => '`\[font=([ a-z0-9,_-]+)\](.+)\[/font\]`isU',
			'pre' => '`\[pre\](.+)\[/pre\]`isU',
			'align' => '`\[align=(left|center|right|justify)\](.+)\[/align\]`isU',
			'float' => '`\[float=(left|right)\](.+)\[/float\]`isU',
			'anchor' => '`\[anchor=([a-z0-9_-]+)\](.*)\[/anchor\]`isU',
			'acronym' => '`\[acronym=([a-z0-9_-]+)\](.*)\[/acronym\]`isU',
			'title' => '`\[title=([1-2])\](.+)\[/title\]`iU',
			'stitle' => '`\[stitle=([1-2])\](.+)\[/stitle\]`iU',
			'style' => '`\[style=(success|question|notice|warning|error)\](.+)\[/style\]`isU',
			'swf' => '`\[swf=([0-6][0-9]{0,2}),([0-6][0-9]{0,2})\]((?:(\./|\.\./)|([\w]+://))+[^,\n\r\t\f]+)\[/swf\]`iU',
			'movie' => '`\[movie=([0-6][0-9]{0,2}),([0-6][0-9]{0,2})\]([^\n\r\t\f]+)\[/movie\]`iU',
			'sound' => '`\[sound\]([^\n\r\t\f]+)\[/sound\]`iU',
			'url' => '`\[url\]([\w]+?://([^\n\r\t\f]+))\[/url\]`iU',
			'url1' => '`\[url\]((www|ftp)\.([^\n\r\t\f]+))\[/url\]`iU',
			'url2' => '`\[url=(((\./|\.\./)|([\w]+://))+[^\n\r\t\f]+)\]([^\n\r\t\f]+)\[/url\]`iU',
			'url3' => '`\[url=((www|ftp)\.([^\n\r\t\f]+))\]([^\n\r\t\f]+)\[/url\]`iU',
			'url4' => '`(\s)((ftp|https?)+://[^ ,\n\r\t\f<\\\]+)`i', 
			'url5' => '`(\s)(www\.[^ ,\n\r\t\f<\\\]+)`i',
			'mail' => '`(\s)([a-zA-Z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,6})\s`i'
		);	 	
		$array_preg_replace = array( 
			'b' => "<strong>$1</strong>",
			'i' => "<em>$1</em>",
			'u' => "<span style=\\\"text-decoration: underline;\\\">$1</span>",
			's' => "<strike>$1</strike>",		
			'sup' => '<sup>$1</sup>',
			'sub' => '<sub>$1</sub>',
			'img' => "<img src=\\\"$2\\\" alt=\\\"\\\" class=\\\"valign_$1\\\" />",
			'color' => "<span style=\\\"color:$1;\\\">$4</span>",
			'bgcolor' => "<span style=\\\"background-color:$1;\\\">$4</span>",
			'size' => "<span style=\\\"font-size: $1px;\\\">$4</span>",
			'font' => "<span style=\\\"font-family: $1;\\\">$2</span>",
			'pre' => "<pre>$1</pre>",
			'align' => "<p style=\\\"text-align:$1\\\">$2</p>",
			'float' => "<p class=\\\"float_$1\\\">$2</p>",	
			'anchor' => "<span id=\\\"$1\\\">$2</span>",
			'acronym' => "<acronym title=\\\"$1\\\" class=\\\"bb_acronym\\\">$2</acronym>",
			'title' => "<h3 class=\\\"title$1\\\">$2</h3>",
			'stitle' => "<br /><h4 class=\\\"stitle$1\\\">$2</h4><br />",
			'style' => "<span class=\\\"$1\\\">$2</span>",
			'swf' => "<object type=\\\"application/x-shockwave-flash\\\" data=\\\"$3\\\" width=\\\"$1\\\" height=\\\"$2\\\">
				<param name=\\\"allowScriptAccess\\\" value=\\\"never\\\" />
				<param name=\\\"play\\\" value=\\\"true\\\" />
				<param name=\\\"movie\\\" value=\\\"$3\\\" />
				<param name=\\\"menu\\\" value=\\\"false\\\" />
				<param name=\\\"quality\\\" value=\\\"high\\\" />
				<param name=\\\"scalemode\\\" value=\\\"noborder\\\" />
				<param name=\\\"wmode\\\" value=\\\"transparent\\\" />
				<param name=\\\"bgcolor\\\" value=\\\"#000000\\\" />
			</object>",
			'movie' => "<object type=\\\"application/x-shockwave-flash\\\" data=\\\"../includes/data/movieplayer.swf?movie=$3\\\" width=\\\"$1\\\" height=\\\"$2\\\">
				<param name=\\\"allowScriptAccess\\\" value=\\\"never\\\" />
				<param name=\\\"play\\\" value=\\\"true\\\" />
				<param name=\\\"movie\\\" value=\\\"$1\\\" />
				<param name=\\\"menu\\\" value=\\\"false\\\" />
				<param name=\\\"quality\\\" value=\\\"high\\\" />
				<param name=\\\"scalemode\\\" value=\\\"noborder\\\" />
				<param name=\\\"wmode\\\" value=\\\"transparent\\\" />
				<param name=\\\"bgcolor\\\" value=\\\"#FFFFFF\\\" />
			</object>",
			'sound' => "<object type=\\\"application/x-shockwave-flash\\\" data=\\\"../includes/data/dewplayer.swf?son=$1\\\" width=\\\"200\\\" height=\\\"20\\\">
				<param name=\\\"allowScriptAccess\\\" value=\\\"never\\\" />
				<param name=\\\"play\\\" value=\\\"true\\\" />
				<param name=\\\"movie\\\" value=\\\"../includes/data/dewplayer.swf?son=$1\\\" />
				<param name=\\\"menu\\\" value=\\\"false\\\" />
				<param name=\\\"quality\\\" value=\\\"high\\\" />
				<param name=\\\"scalemode\\\" value=\\\"noborder\\\" />
				<param name=\\\"wmode\\\" value=\\\"transparent\\\" />
				<param name=\\\"bgcolor\\\" value=\\\"#FFFFFF\\\" />
			</object>",
			'url' => "<a href=\\\"$1\\\">$1</a>",
			'url1' => "<a href=\\\"http://$1\\\">$1</a>",
			'url2' => "<a href=\\\"$1\\\">$5</a>",
			'url3' => "<a href=\\\"http://$1\\\">$4</a>",
			'url4' => "$1<a href=\\\"$2\\\">$2</a>", 
		    'url5' => "$1<a href=\\\"http://$2\\\">$2</a>",
		    'mail' => "$1<a href=\\\"mailto:$2\\\">$2</a>"
		);		

		//Suppression des remplacements des balises interdites.
		$other_balise = array('table', 'code', 'math', 'quote', 'hide', 'indent', 'list'); 
		foreach($forbidden_tags as $key => $balise)
		{	
			if( in_array($balise, $other_balise) )
			{
				$array_preg[$balise] = '`\[' . $balise . '.*\](.+)\[/' . $balise . '\]`isU';
				$array_preg_replace[$balise] = "$1";
			}
			else
			{	
				unset($array_preg[$balise]);
				unset($array_preg_replace[$balise]);
			}
		}	
		//Remplacement
		$contents = preg_replace($array_preg, $array_preg_replace, $contents);	

		//Parsage des balises imbriquées.	
		$this->parse_imbricated('[quote]', '`\[quote\](.+)\[/quote\]`sU', '<span class="text_blockquote">' . $LANG['quotation'] . ':</span><div class="blockquote">$1</div>', $contents);
		$this->parse_imbricated('[quote=', '`\[quote=([^\]]+)\](.+)\[/quote\]`sU', '<span class="text_blockquote">$1:</span><div class="blockquote">$2</div>', $contents);
		$this->parse_imbricated('[hide]', '`\[hide\](.+)\[/hide\]`sU', '<span class="text_hide">' . $LANG['hide'] . ':</span><div class="hide" onclick="bb_hide(this)"><div class="hide2">$1</div></div>', $contents);
		$this->parse_imbricated('[indent]', '`\[indent\](.+)\[/indent\]`sU', '<div class="indent">$1</div>', $contents);

		 //Parsage de la balise table.
		if( strpos($contents, '[table') !== false )
			$this->parse_table($contents);
		
		//Parsage de la balise list.
		if( strpos($contents, '[list') !== false )
			$this->parse_list($contents);

		return trim($contents);
	}

	//Remplacement recursif des balises imbriquées.
	function parse_imbricated($match, $regex, $replace, &$contents)
	{
		$nbr_match = substr_count($contents, $match);
		for($i = 0; $i <= $nbr_match; $i++)
			$contents = preg_replace($regex, $replace, $contents); 
	}

	//Fonction pour éclater la chaîne selon les tableaux (gestion de l'imbrication infinie)
	function split_imbricated_table(&$contents)
	{
		$contents = preg_split('`\[table( style="[^"]+")?\]((?:[^[]|\[(?!/?table(?: style="[^"]+")?\])|(?R))+)\[/table\]`', $contents, -1, PREG_SPLIT_DELIM_CAPTURE);

		//1 élément représente les inter tableaux, un le style du tableau et l'autre le contenu
		$nbr_occur = count($contents);
		for($i = 0; $i < $nbr_occur; $i++)
		{
			if( ($i % 3) === 2 && preg_match('`\[table(?: style="[^"]+")?\].+\[/table\]`s', $contents[$i]) )
			{
				//C'est le contenu d'un tableau, il contient un sous tableau donc on éclate
				$this->split_imbricated_table($contents[$i]);
			}
		}
	}

	//Fonction qui parse les tableaux dans l'ordre inverse à l'ordre hiérarchique
	function parse_imbricated_table(&$contents)
	{
		if( is_array($contents) )
		{
			$string_contents = '';
			$nbr_occur = count($contents);
			for($i = 0; $i < $nbr_occur; $i++)
			{
				//Si c'est le contenu d'un tableau on le parse
				if( $i % 3 === 2 )
				{
					//On parse d'abord les sous tableaux éventuels
					$this->parse_imbricated_table($contents[$i]);
					//On parse le tableau concerné (il doit commencer par [row] puis [col] ou [head] et se fermer pareil moyennant espaces et retours à la ligne sinon il n'est pas valide)
					if( preg_match('`^(?:\s|<br />)*\[row\](?:\s|<br />)*\[(?:col|head)(?: colspan="[0-9]+")?(?: rowspan="[0-9]+")?(?: style="[^"]+")?\].*\[/(?:col|head)\](?:\s|<br />)*\[/row\](?:\s|<br />)*$`sU', $contents[$i]) )
					{
						
						//On nettoie les caractères éventuels (espaces ou retours à la ligne) entre les différentes cellules du tableau pour éviter les erreurs xhtml
						$contents[$i] = preg_replace_callback('`^(\s|<br />)+\[row\]`U', create_function('$var', 'return str_replace("<br />", "", $var[0]);'), $contents[$i]);
						$contents[$i] = preg_replace_callback('`\[/row\](\s|<br />)+$`U', create_function('$var', 'return str_replace("<br />", "", $var[0]);'), $contents[$i]);
						$contents[$i] = preg_replace_callback('`\[/row\](\s|<br />)+\[row\]`U', create_function('$var', 'return str_replace("<br />", "", $var[0]);'), $contents[$i]);
						$contents[$i] = preg_replace_callback('`\[row\](\s|<br />)+\[col.*\]`Us', create_function('$var', 'return str_replace("<br />", "", $var[0]);'), $contents[$i]);
						$contents[$i] = preg_replace_callback('`\[row\](\s|<br />)+\[head[^]]*\]`U', create_function('$var', 'return str_replace("<br />", "", $var[0]);'), $contents[$i]);
						$contents[$i] = preg_replace_callback('`\[/col\](\s|<br />)+\[col.*\]`Us', create_function('$var', 'return str_replace("<br />", "", $var[0]);'), $contents[$i]);
						$contents[$i] = preg_replace_callback('`\[/col\](\s|<br />)+\[head[^]]*\]`U', create_function('$var', 'return str_replace("<br />", "", $var[0]);'), $contents[$i]);
						$contents[$i] = preg_replace_callback('`\[/head\](\s|<br />)+\[col.*\]`Us', create_function('$var', 'return str_replace("<br />", "", $var[0]);'), $contents[$i]);
						$contents[$i] = preg_replace_callback('`\[/head\](\s|<br />)+\[head[^]]*\]`U', create_function('$var', 'return str_replace("<br />", "", $var[0]);'), $contents[$i]);
						$contents[$i] = preg_replace_callback('`\[/head\](\s|<br />)+\[/row\]`U', create_function('$var', 'return str_replace("<br />", "", $var[0]);'), $contents[$i]);
						$contents[$i] = preg_replace_callback('`\[/col\](\s|<br />)+\[/row\]`U', create_function('$var', 'return str_replace("<br />", "", $var[0]);'), $contents[$i]);
						//Parsage de row, col et head
						$contents[$i] = preg_replace('`\[row\](.*)\[/row\]`sU', '<tr class="bb_table_row">$1</tr>', $contents[$i]);
						$contents[$i] = preg_replace('`\[col((?: colspan="[0-9]+")?(?: rowspan="[0-9]+")?(?: style="[^"]+")?)?\](.*)\[/col\]`sU', '<td class="bb_table_col"$1>$2</td>', $contents[$i]);
						$contents[$i] = preg_replace('`\[head((?: colspan="[0-9]+")?(?: style="[^"]+")?)?\](.*)\[/head\]`sU', '<th class="bb_table_head"$1>$2</th>', $contents[$i]);
						//parsage réussi (tableau valide), on rajoute le tableau devant
						$contents[$i] = '<table class="bb_table"' . $contents[$i - 1] . '>' . $contents[$i] . '</table>';
					}
					else
					{
						//le tableau n'est pas valide, on met des balises temporaires afin qu'elles ne soient pas parsées au niveau inférieur
						$contents[$i] = str_replace(array('[col', '[row', '[/col', '[/row', '[head', '[/head'), array('[\col', '[\row', '[\/col', '[\/row', '[\head', '[\/head'), $contents[$i]);
						$contents[$i] = '[table' . $contents[$i - 1] . ']' . $contents[$i] . '[/table]';
					}
				}
				//On concatène la chaîne finale si ce n'est pas le style du tableau
				if( $i % 3 !== 1 )
					$string_contents .= $contents[$i];
			}
			$contents = $string_contents;
		}
	}

	function parse_table(&$contents)
	{
		//On supprime les éventuels quote qui ont été transformés en leur entité html
		$contents = preg_replace_callback('`\[(?:table|col|row|head)(?: colspan=\\\&quot;[0-9]+\\\&quot;)?(?: rowspan=\\\&quot;[0-9]+\\\&quot;)?( style=\\\&quot;(?:[^&]+)\\\&quot;)?\]`U', create_function('$matches', 'return str_replace(\'\\\&quot;\', \'"\', $matches[0]);'), $contents);
		$contents = stripslashes($contents);
		$this->split_imbricated_table($contents);
		$this->parse_imbricated_table($contents);
		//On remet les tableaux invalides tels qu'ils étaient avant
		$contents = str_replace(array('[\col', '[\row', '[\/col', '[\/row', '[\head', '[\/head'), array('[col', '[row', '[/col', '[/row', '[head', '[/head'), $contents);
		$contents = addslashes($contents);
	}
	
	//Fonction pour éclater les listes (imbrication infinie)
	function split_imbricated_list(&$contents)
	{
		$contents = preg_split('`\[list(?:=((?:un)?ordered))?(?: style="([^"]+)")?\]((?:[^[]|\[(?!/?list(=(?:un)?ordered)?( style="[^"]+")?\])|(?R))+)\[/list\]`', $contents, -1, PREG_SPLIT_DELIM_CAPTURE);
		//1 élément représente le texte inter listes, un deuxième le type de liste (ul ou ol), le troisième le style éventuel de la liste et le quatrième le contenu de la liste
		$nbr_occur = count($contents);
		for( $i = 0; $i < $nbr_occur; $i++)
		{
			if( $i % 4 === 3 && preg_match('`\[list(=(?:un)?ordered)?( style="[^"]+")?\](\s|<br />)*\[\*\].*\[/list\]`s', $contents[$i]) )
			{
				//C'est le contenu du tableau, on travaille dessus
				$this->split_imbricated_list($contents[$i]);
			}
		}
	}

	//Fonction qui parse les listes
	function parse_imbricated_list(&$contents)
	{
		if( is_array($contents) )
		{
			$string_contents = '';
			$nbr_occur = count($contents);
			for($i = 0; $i < $nbr_occur; $i++)
			{
				//Si c'est le contenu d'une liste on le parse
				if( $i % 4 === 3 )
				{
					//On parse d'abord les sous listes éventuelles
					$this->parse_imbricated_list($contents[$i]);
					if( strpos($contents[$i], '[*]') !== false ) //Si il contient au moins deux éléments
					{				
						//Nettoyage des listes (retours à la ligne)
						$contents[$i] = preg_replace_callback('`\[\*\]((?:\s|<br />)+)`', create_function('$var', 'return str_replace("<br />", "", $var[0]);'), $contents[$i]);
						$contents[$i] = preg_replace_callback('`((?:\s|<br />)+)\[\*\]`', create_function('$var', 'return str_replace("<br />", "", $var[0]);'), $contents[$i]);
						$list_tag = $contents[$i - 2] == 'ordered' ? 'ol' : 'ul';
						$list_style = !empty($contents[$i - 1]) ? ' style="' . $contents[$i - 1] . '"' : '';
						$contents[$i] = preg_replace_callback('`^((?:\s|<br />)*)\[\*\]`U', create_function('$var', 'return str_replace("<br />", "", str_replace("[*]", "<li class=\"bb_li\">", $var[0]));'), $contents[$i]);
						$contents[$i] = '<' . $list_tag . $list_style . ' class="bb_' . $list_tag . '">' . str_replace('[*]', '</li><li class="bb_li">', $contents[$i]) . '</li></' . $list_tag . '>';
					}
				}
				//On concatène la chaîne finale si ce n'est pas le style ou le type de tableau
				if( ($i % 4 !== 1) && ($i % 4 !== 2) )
					$string_contents .= $contents[$i];
			}
			$contents = $string_contents;
		}
	}

	function parse_list(&$contents)
	{
		//On nettoie les guillemets échappés
		$contents = preg_replace_callback('`\[list(?:=(?:un)?ordered)?( style=\\\&quot;[^&]+\\\&quot;)?\]`U', create_function('$matches', 'return str_replace(\'\\\&quot;\', \'"\', $matches[0]);'), $contents);
		//on travaille dessus
		if( preg_match('`\[list(=(?:un)?ordered)?( style="[^"]+")?\](\s|<br />)*\[\*\].*\[/list\]`s', $contents) )
		{
			$this->split_imbricated_list($contents);
			$this->parse_imbricated_list($contents);
		}
	}
	
	############################## Unparsage ##############################
	//On unparse le contenu.
	function unparse_content($contents, $editor_unparse)
	{
		//Smiley.
		@include('../cache/smileys.php');
		if(!empty($_array_smiley_code) )
		{
			//Création du tableau de remplacement.
			foreach($_array_smiley_code as $code => $img)
			{	
				$smiley_img_url[] = '`<img src="../images/smileys/' . preg_quote($img) . '(.*) />`sU';
				$smiley_code[] = $code;
			}	
			$contents = preg_replace($smiley_img_url, $smiley_code, $contents);
		}
			
		if( $this->user_editor == 'tinymce' && $editor_unparse ) //Préparse pour tinymce.
		{
			//Remplacement des caractères de word.			
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
			$contents = str_replace($array_str, $array_str_replace, $contents);
			
			//Remplacement des balises imbriquées.	
			$this->parse_imbricated('<span class="text_blockquote">', '`<span class="text_blockquote">(.*):</span><div class="blockquote">(.*)</div>`sU', '[quote=$1]$2[/quote]', $contents);
			$this->parse_imbricated('<span class="text_hide">', '`<span class="text_hide">(.*):</span><div class="hide" onclick="bb_hide\(this\)"><div class="hide2">(.*)</div></div>`sU', '[hide]$2[/hide]', $contents);
			$this->parse_imbricated('<div class="indent">', '`<div class="indent">(.+)</div>`sU', '<blockquote>$1</blockquote>', $contents);
			
			//Balise size
			$contents = preg_replace_callback('`<span style="font-size: ([0-9]+)px;">(.*)</span>`isU', create_function('$size', 'if( $size[1] >= 36 ) $fontsize = 7;	elseif( $size[1] <= 12 ) $fontsize = 1;	else $fontsize = min(($size[1] - 6)/2, 7); return \'<font size="\' . $fontsize . \'">\' . $size[2] . \'</font>\';'), $contents);
		
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
				'`<object type="application/x-shockwave-flash" data="\.\./includes/data/dewplayer\.swf\?son=(.*)" width="200" height="20">(.*)</object>`isU',
				'`<object type="application/x-shockwave-flash" data="\.\./includes/data/movieplayer\.swf\?movie=(.*)" width="([^"]+)" height="([^"]+)">(.*)</object>`isU'
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
			$contents = preg_replace($array_preg, $array_preg_replace, $contents);
			
			$contents = htmlentities($contents);
		}
		else
		{		
			//Remplacement des balises imbriquées.	
			$this->parse_imbricated('<span class="text_blockquote">', '`<span class="text_blockquote">(.*):</span><div class="blockquote">(.*)</div>`sU', '[quote=$1]$2[/quote]', $contents);
			$this->parse_imbricated('<span class="text_hide">', '`<span class="text_hide">(.*):</span><div class="hide" onclick="bb_hide\(this\)"><div class="hide2">(.*)</div></div>`sU', '[hide]$2[/hide]', $contents);
			$this->parse_imbricated('<div class="indent">', '`<div class="indent">(.+)</div>`sU', '[indent]$1[/indent]', $contents);
				
			//Str_replace.
			$array_str = array( 
			'<br />', '<strong>', '</strong>', '<em>', '</em>', '<strike>', '</strike>', '&#8364;', '&#8218;', '&#402;', '&#8222;',
			'&#8230;', '&#8224;', '&#8225;', '&#710;', '&#8240;', '&#352;', '&#8249;', '&#338;', '&#381;',
			'&#8216;', '&#8217;', '&#8220;', '&#8221;', '&#8226;', '&#8211;', '&#8212;', '&#732;', '&#8482;',
			'&#353;', '&#8250;', '&#339;', '&#382;', '&#376;'
			);	
			$array_str_replace = array( 
			'', '[b]', '[/b]', '[i]', '[/i]', '[s]', '[/s]', '€', '‚', 'ƒ',
			'„', '…', '†', '‡', 'ˆ', '‰', 'Š', '‹', 'Œ', 'Ž',
			'‘', '’', '“', '”', '•', '–', '—',  '˜', '™', 'š',
			'›', 'œ', 'ž', 'Ÿ'
			);	
			$contents = str_replace($array_str, $array_str_replace, $contents);

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
				'`<object type="application/x-shockwave-flash" data="\.\./includes/data/dewplayer\.swf\?son=(.*)" width="200" height="20">(.*)</object>`isU',
				'`<object type="application/x-shockwave-flash" data="\.\./includes/data/movieplayer\.swf\?movie=(.*)" width="([^"]+)" height="([^"]+)">(.*)</object>`isU',
				'`<object type="application/x-shockwave-flash" data="([^"]+)" width="([^"]+)" height="([^"]+)">(.*)</object>`isU'
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
				"[swf=$2,$3]$1[/swf]"
			);	
			$contents = preg_replace($array_preg, $array_preg_replace, $contents);

			//Unparsage de la balise table.
			if( strpos($contents, '<table') !== false )
				$this->unparse_table($contents);

			//Unparsage de la balise table.
			if( strpos($contents, '<li') !== false )
				$this->unparse_list($contents);
		}
		
		return $contents;
	}

	function unparse_table(&$contents)
	{
		//Preg_replace.
		$array_preg = array( 
		'`<table class="bb_table"([^>]*)>(.*)</table>`sU',
		'`<tr class="bb_table_row">(.*)</tr>`sU',
		'`<th class="bb_table_head"([^>]*)>(.*)</th>`sU',
		'`<td class="bb_table_col"([^>]*)>(.*)</td>`sU'
		);
		$array_preg_replace = array( 
		'[table$1]$2[/table]',
		'[row]$1[/row]',
		'[head$1]$2[/head]',
		'[col$1]$2[/col]'
		);	
		$contents = preg_replace($array_preg, $array_preg_replace, $contents);
	}

	//Retour
	function unparse_list(&$contents)
	{
	    while( preg_match('`<(?:u|o)l( style="[^"]+")? class="bb_(?:u|o)l">(.+)</(?:u|o)l>`sU', $contents) )
	    {
	        $contents = preg_replace('`<ul( style="[^"]+")? class="bb_ul">(.+)</ul>`sU', '[list$1]$2[/list]', $contents);
	        $contents = preg_replace('`<ol( style="[^"]+")? class="bb_ol">(.+)</ol>`sU', '[list=ordered$1]$2[/list]', $contents);
	        $contents = preg_replace('`<li class="bb_li">(.+)</li>`isU', '[*]$1', $contents);
	    }
	}
}

?>