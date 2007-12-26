<?php
/*##################################################
 *                                function.php
 *                            -------------------
 *   begin                : June 13, 2005
 *   copyright          : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
 *   Function 2.0.0 
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

define('HTML_NO_PROTECT', false);
define('MAGIC_QUOTES_UNACTIV', false);

//Passe à la moulinette les entrées (chaînes) utilisateurs.
function securit($var, $html_protect = true)
{
	$var = trim($var);
	
	//Protection contre les balises html.
	if( $html_protect ) 
		$var = strip_tags(htmlentities($var));
	
	//On échappe les ' si la fonction magic_quotes_gpc() n'est pas activée sur le serveur.
	if( MAGIC_QUOTES == false )
		$var = addslashes($var);

	return (string) $var;
}

//Vérifie les entrées numeriques.
function numeric($var, $type = 'int')
{
	if( is_numeric($var) ) //Retourne un nombre
	{
		if( $type === 'float')
			return (float)$var; //Nbr virgule flottante.
		else
			return (int)$var; //Nombre entier
	}
	else
		return 0;
}

//Si register_globals activé, suppression des variables qui trainent.
function securit_register_globals()
{	
	@ini_set('register_globals', 0);
	$_SESSION = array();
	$GLOBALS = array();
	
	$input = array_merge(
		array_keys($_GET),
		array_keys($_POST),
		array_keys($_COOKIE),
		array_keys($_SERVER),
		array_keys($_SESSION),
		array_keys($_ENV),
		array_keys($_FILES)
	);

	$not_unset = array(
		'GLOBALS' => true,
		'_GET' => true,
		'_POST' => true,
		'_COOKIE' => true,
		'_FILES' => true,
		'_REQUEST' => true,
		'_SERVER' => true,
		'_ENV' => true,
		'_SESSION' => true
	);
	
	foreach($input as $varname)
	{
		if( isset($not_unset[$varname]) ) //Tentative de hack.
			exit;
	}
	unset($not_unset);
	unset($input);
}

//Securisation nom d'utilisateur.
function clean_user($var)
{
	$var = substr($var, 0, 25);
	$var = trim(htmlentities($var));
	$var = strip_tags($var);	
	
	//On échappe les '  et " si la fonction magic_quotes_gpc() n'est pas activée sur le serveur.
	if( MAGIC_QUOTES == false )
		$var = addslashes($var);

	return (string) $var;
}

//Découpage avec retour à la ligne, d'une chaîne, en prenant compte les entités html.
function wordwrap_html($str, $lenght, $cut_char = '<br />', $boolean = true)
{
	$str = wordwrap(html_entity_decode($str), $lenght, $cut_char, $boolean);
	return str_replace('&lt;br /&gt;', '<br />', strip_tags(htmlentities($str)));	
}

//Découpe d'une chaîne, en prenant compte les entités html.
function substr_html($str, $start, $end = '')
{
	if( $end == '' )
		return htmlentities(substr(html_entity_decode($str), $start));
	else
		return htmlentities(substr(html_entity_decode($str), $start, $end));
}

//Transforme des données de la base de données en données utilisable pour la génération du cache.
function sql_to_cache($str)
{
	return str_replace('\'', '\\\'', str_replace('\\', '\\\\', $str));
}

//Récupération de la page de démarrage du site.
function get_start_page()
{
	global $CONFIG;
	
	$start_page = (substr($CONFIG['start_page'], 0, 1) == '/') ? transid(HOST . DIR . $CONFIG['start_page']) : $CONFIG['start_page'];
	return $start_page;
}

//On parse le contenu: bbcode => xhtml.
function parse($contents, $forbidden_tags = array(), $html_protect = true, $magic_quotes_activ = true)
{
	global $LANG;
	
	$contents = ' ' . trim($contents) . ' '; //Ajout des espaces multiples, et ajout d'espaces pour éviter l'absence de parsage lorsqu'un séparateur de mot est éxigé.
				
	//On échappe les ' si la fonction magic_quotes_gpc() n'est pas activée sur le serveur.
	if( MAGIC_QUOTES == false || !$magic_quotes_activ )
		$contents = addslashes($contents);	

	if( $html_protect )
	{
		//Protection des données.	
		$contents = htmlentities($contents);
		$contents = strip_tags($contents);
		$contents = nl2br($contents);
	}

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
		$contents = preg_replace($smiley_code	, $smiley_img_url, $contents);
	}	
	
	//Str_replace, compatibilité xhtml (les plus courants).
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
		'img' => '`\[img\](((\./|\.\./)|([\w]+://))+[^,\n\r\t\f]+\.(jpg|jpeg|bmp|gif|png|tiff|svg))\[/img\]`iU',
		'color' => '`\[color=((white|black|red|green|blue|yellow|purple|orange|maroon)|(#[0-9a-f]{6}))\](.+)\[/color\]`isU',
		'size' => '`\[size=(([0-9]{1})|([0-4]+[0-9]?))\](.+)\[/size\]`isU',
		'align' => '`\[align=(left|center|right)\](.+)\[/align\]`isU',
		'float' => '`\[float=(left|right)\](.+)\[/float\]`isU',
		'anchor' => '`\[anchor=([a-z0-9_-]+)\](.+)\[/anchor\]`isU',
		'title' => '`\[title=([1-2])\](.+)\[/title\]`iU',
		'stitle' => '`\[stitle=([1-2])\](.+)\[/stitle\]`iU',
		'style' => '`\[style=(success|question|notice|warning|error)\](.+)\[/style\]`isU',
		'swf' => '`\[swf=([0-6][0-9]{0,2}),([0-6][0-9]{0,2})\](([\w]+?)://([^,\n\r\t\f]+))\[/swf\]`iU',
		'movie' => '`\[movie=([0-6][0-9]{0,2}),([0-6][0-9]{0,2})\]([^\n\r\t\f]+)\[/movie\]`iU',
		'sound' => '`\[sound\]([^\n\r\t\f]+)\[/sound\]`iU',
		'url' => '`\[url\]([\w]+?://([^,\n\r\t\f]+))\[/url\]`iU',
		'url1' => '`\[url\]((www|ftp)\.([^,\n\r\t\f]+))\[/url\]`iU',
		'url2' => '`\[url=(((\./|\.\./)|([\w]+://))+[^,\n\r\t\f]+)\]([^\n\r\t\f]+)\[/url\]`iU',
		'url3' => '`\[url=((www|ftp)\.([^,\n\r\t\f]+))\]([^\n\r\t\f]+)\[/url\]`iU',
		'url4' => '`(\s)((ftp|https?)+://[^ ,\n\r\t\f<]+)`i', 
		'url5' => '`(\s)(www\.[^ ,\n\r\t\f<]+)`i',
		'mail' => '`(\s)([a-zA-Z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,6})\s`i'
	);	 	
	$array_preg_replace = array( 
		'b' => "<strong>$1</strong>",
		'i' => "<em>$1</em>",
		'u' => "<span style=\\\"text-decoration: underline;\\\">$1</span>",
		's' => "<strike>$1</strike>",		
		'sup' => '<sup>$1</sup>',
		'sub' => '<sub>$1</sub>',
		'img' => "<img src=\\\"$1\\\" alt=\\\"\\\" />",
		'color' => "<span style=\\\"color:$1;\\\">$4</span>",
		'size' => "<span style=\\\"font-size: $1px;\\\">$4</span>",
		'align' => "<p style=\\\"text-align:$1\\\">$2</p>",
		'float' => "<p class=\\\"float_$1\\\">$2</p>",	
		'anchor' => "<span id=\\\"$1\\\">$2</span>",
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
	parse_imbricated('[quote]', '`\[quote\](.+)\[/quote\]`sU', '<span class="text_blockquote">' . $LANG['quotation'] . ':</span><div class="blockquote">$1</div>', $contents);
	parse_imbricated('[quote=', '`\[quote=([^\]]+)\](.+)\[/quote\]`sU', '<span class="text_blockquote">$1:</span><div class="blockquote">$2</div>', $contents);
	parse_imbricated('[hide]', '`\[hide\](.+)\[/hide\]`sU', '<span class="text_hide">' . $LANG['hide'] . ':</span><div class="hide" onclick="bb_hide(this)"><div class="hide2">$1</div></div>', $contents);
	parse_imbricated('[indent]', '`\[indent\](.+)\[/indent\]`sU', '<div class="indent">$1</div>', $contents);

	 //Parsage de la balise table.
	if( strpos($contents, '[table') !== false )
		parse_table($contents);
	
	//Parsage de la balise list.
	if( strpos($contents, '[list') !== false )
		parse_list($contents);
		
	return trim($contents);
}

//On unparse le contenu: xhtml => bbcode. 
function unparse($contents)
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

	//Remplacement des balises imbriquées.	
	parse_imbricated('<span class="text_blockquote">', '`<span class="text_blockquote">(.*):</span><div class="blockquote">(.*)</div>`sU', '[quote=$1]$2[/quote]', $contents);
	parse_imbricated('<span class="text_hide">', '`<span class="text_hide">(.*):</span><div class="hide" onclick="bb_hide\(this\)"><div class="hide2">(.*)</div></div>`sU', '[hide]$2[/hide]', $contents);
	parse_imbricated('<div class="indent">', '`<div class="indent">(.+)</div>`sU', '[indent]$1[/indent]', $contents);
		
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
	'`<img src="([^?\n\r\t].*)" alt="(.*)" />`isU',
	'`<span style="color:(.*);">(.*)</span>`isU',
	'`<span style="text-decoration: underline;">(.*)</span>`isU',
	'`<sup>(.+)</sup>`isU',
	'`<sub>(.+)</sub>`isU',
	'`<span style="font-size: ([0-9]+)px;">(.*)</span>`isU',
	'`<p style="text-align:(left|center|right)">(.*)</p>`isU',
	'`<p class="float_(left|right)">(.*)</p>`isU',
	'`<span id="([a-z0-9_-]+)">(.*)</span>`isU',
	'`<a href="mailto:(.*)">(.*)</a>`isU',
	'`<a href="(.*)">(.*)</a>`isU',
	'`<h3 class="title(.*)">(.*)</h3>`isU',
	'`<h4 class="stitle(.*)">(.*)</h4>`isU',
	'`<span class="(success|question|notice|warning|error)">(.*)</span>`isU',
	'`<object type="application/x-shockwave-flash" data="\.\./includes/data/dewplayer\.swf\?son=(.*)" width="200" height="20">(.*)</object>`isU',
	'`<object type="application/x-shockwave-flash" data="\.\./includes/data/movieplayer\.swf\?movie=(.*)" width="(.*)" height="(.*)">(.*)</object>`isU',
	'`<object type="application/x-shockwave-flash" data="(.*)" width="(.*)" height="(.*)">(.*)</object>`isU',
	);
	$array_preg_replace = array( 
	"[img]$1[/img]",
	"[color=$1]$2[/color]",
	"[u]$1[/u]",	
	'[sup]$1[/sup]',
	'[sub]$1[/sub]',
	"[size=$1]$2[/size]",
	"[align=$1]$2[/align]",
	"[float=$1]$2[/float]",
	"[anchor=$1]$2[/anchor]",
	"$1",
	"[url=$1]$2[/url]",
	"[title=$1]$2[/title]",
	"[stitle=$1]$2[/stitle]",
	"[style=$1]$2[/style]",
	"[sound]$1[/sound]",
	"[movie=$2,$3]$1[/movie]",
	"[swf=$2,$3]$1[/swf]",
	);	
	$contents = preg_replace($array_preg, $array_preg_replace, $contents);
	
	//Unparsage de la balise table.
	if( strpos($contents, '<table') !== false )
		unparse_table($contents);

	//Unparsage de la balise table.
	if( strpos($contents, '<li') !== false )
		unparse_list($contents);
	
	return $contents;
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
			split_imbricated_table($contents[$i]);
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
				parse_imbricated_table($contents[$i]);
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
	split_imbricated_table($contents);
	parse_imbricated_table($contents);
	//On remet les tableaux invalides tels qu'ils étaient avant
	$contents = str_replace(array('[\col', '[\row', '[\/col', '[\/row', '[\head', '[\/head'), array('[col', '[row', '[/col', '[/row', '[head', '[/head'), $contents);
	$contents = addslashes($contents);
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
			split_imbricated_list($contents[$i]);
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
				parse_imbricated_list($contents[$i]);
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
			if( $i % 4 !== 1 && $i % 4 !== 2 )
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
		split_imbricated_list($contents);
		parse_imbricated_list($contents);
	}
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

//Vérifie que le message ne contient pas plus de x liens
function check_nbr_links($contents, $max_nbr)
{
	if( $max_nbr == -1 )
		return true;
	elseif( substr_count($contents, 'http://') > $max_nbr )
		return false;
	
	return true;
}

//Coloration en xhtml du code PHP.
function xhtml_highlight_string($contents) 
{
	$highlight = highlight_string($contents, true);
	$font_replace = str_replace(array('<font ', '</font>'), array('<span ', '</span>'), $highlight);
	$contents = preg_replace('`color="(.*?)"`', 'style="color: \\1"', $font_replace);
	
	return $contents ;
} 

//Fonction appliquée aux balises [code] temps réel.
function highlight_code($matches)
{
	$matches[1] = str_replace('<br />', '', $matches[1]);
	$matches = '<span class="text_code">Code:</span><div class="code">'. xhtml_highlight_string(html_entity_decode(unparse($matches[1]))) .'</div>';
	
	return $matches;
}

//Fonction appliquée aux balises [math] temps réel, formules matématiques.
function math_code($matches)
{
	$matches[1] = str_replace('<br />', '', $matches[1]);
	$matches = mathfilter(html_entity_decode($matches[1]), 12);

	return $matches;
}

//Parse temps réel => détection des balisses [code]  et remplacement, coloration si contient du code php.
function second_parse($contents)
{
	//Balise code.
	if( strpos($contents, '[code]') !== false )
		$contents = preg_replace_callback('`\[code\](.+)\[/code\]`isU', 'highlight_code', $contents);
	
	//Balise latex.
	if( strpos($contents, '[math]') !== false )
		$contents = preg_replace_callback('`\[math\](.+)\[/math\]`isU', 'math_code', $contents);

	return $contents;
}		

//Transmet le session_id et le user_id à traver l'url pour les connexions sans cookies. Permet le support de l'url rewritting!
function transid($url, $mod_rewrite = '', $esperluette = '&amp;')
{
	global $CONFIG;
	global $session;
	
	if( $session->session_mod == 0 )
	{	
		if( $CONFIG['rewrite'] == 1 && !empty($mod_rewrite) ) //Activation du mod rewrite => cookies activés.
			return $mod_rewrite;	
		else
			return $url;
	}	
	elseif( $session->session_mod == 1 )
		return $url . ((strpos($url, '?') === false) ? '?' : $esperluette) . 'sid=' . $session->session['session_id'] . $esperluette . 'suid=' . $session->session['user_id'];	
}

//Nettoie l'url de tous les caractères spéciaux, accents, etc....
function url_encode_rewrite($string)
{
	$string = strtolower(html_entity_decode($string));

	$chars_special = array(' ', 'é', 'è', 'ê', 'à', 'â', 'ù', 'ü', 'û', 'ï', 'î', 'ô', 'ç');
	$chars_replace = array('-', 'e', 'e', 'e', 'a', 'a', 'u', 'u', 'u', 'i', 'i', 'o', 'c');
	$string = str_replace($chars_special, $chars_replace, $string);

	$string = preg_replace('`([^a-z0-9]|[\s])`', '-', $string);
	$string = preg_replace('`[-]{2,}`', '-', $string);
	$string = trim($string, ' -');
	
	return $string;
}

//Converti une chaîne au format $LANG['date_format'] (ex:d/m/y) en timestamp, si la date saisie est valide sinon retourne 0.
function strtotimestamp($str, $date_format)
{
	list($month, $day, $year) = array(0, 0, 0);
	$array_timestamp = explode('/', $str);
	$array_date = explode('/', $date_format);
	for($i = 0; $i < 3; $i++)
	{
		switch($array_date[$i])
		{
			case 'd':
			$day = (isset($array_timestamp[$i])) ? numeric($array_timestamp[$i]) : 0;
			break;
			case 'm':
			$month = (isset($array_timestamp[$i])) ? numeric($array_timestamp[$i]) : 0;
			break;
			case 'y':
			$year = (isset($array_timestamp[$i])) ? numeric($array_timestamp[$i]) : 0;
			break;
		}
	}

	//Vérification du format de la date.
	if( checkdate($month, $day, $year) )
		$timestamp = @mktime(0, 0, 1, $month, $day, $year);
	else
		$timestamp = 0;
		
	return $timestamp;
}

//Converti une chaîne au format $LANG['date_format'] (ex:DD/MM/YYYY) en type DATE, si la date saisie est valide sinon retourne 0000-00-00.
function strtodate($str, $date_format)
{
	list($month, $day, $year) = array(0, 0, 0);
	$array_date = explode('/', $str);
	$array_format_date = explode('/', $date_format);
	for($i = 0; $i < 3; $i++)
	{
		switch($array_format_date[$i])
		{
			case 'DD':
			$day = (isset($array_date[$i])) ? numeric($array_date[$i]) : 0;
			break;
			case 'MM':
			$month = (isset($array_date[$i])) ? numeric($array_date[$i]) : 0;
			break;
			case 'YYYY':
			$year = (isset($array_date[$i])) ? numeric($array_date[$i]) : 0;
			break;
		}
	}
	
	//Vérification du format de la date.
	if( checkdate($month, $day, $year) )
		$date = $year . '-' . $month . '-' . $day;
	else
		$date = '0000-00-00';
		
	return $date;
}

//Suppression d'un fichier avec gestion des erreurs.
function delete_file($file)
{
	global $LANG;
	
	if( function_exists('unlink') )
		if( file_exists($file) )
			return @unlink($file); //On supprime le fichier.
	else
		return false;
}

//Fonction récursive de suppression de dossier.
function delete_directory($dir_path, $path)
{
	$dir = dir($path);
	if( !is_object($dir) ) 
		return false;
		
	while( $file = $dir->read() ) 
	{
		if( $file != '.' && $file != '..' )
		{			
			$path_file = $path . '/' . $file;
			if( is_file($path_file) )
				if( !@unlink($path_file) )
					return false;
			elseif( is_dir($path_file) )
			{	
				delete_directory($dir_path, $path_file);
				if( !@rmdir($path_file) )
					return false;
			}
		}
	}
	
	//Fermeture du dossier et suppression de celui-ci.
	if( !$file )
	{	
		$dir->close();
		if( @rmdir($dir_path) )
			return true;
	}	
	return false;
}

//Arrondi nbr au nbr de décimal voulu
function arrondi($nombre, $dec)
{
	return trim(number_format($nombre, $dec, '.', ''));
}

//Remplacement de la fonction file_get_contents.
function file_get_contents_emulate($filename, $incpath = false, $resource_context = null)
{
	if( false === $fh = fopen($filename, 'rb', $incpath) ) 
	{
		user_error('file_get_contents_emulate() failed to open stream: No such file or directory', E_USER_WARNING);
		return false;
	}

	clearstatcache();
	if( $fsize = @filesize($filename) ) 
		$data = fread($fh, $fsize);
	else 
	{
		$data = '';
		while( !feof($fh) )
			$data .= fread($fh, 8192);
	}
	fclose($fh);
	return $data;
}

//Emulation de la fonction PHP5 html_entity_decode().		
if( !function_exists('html_entity_decode') ) 
{
	function html_entity_decode($string, $quote_style = ENT_COMPAT, $charset = null)
	{
		if( !is_int($quote_style) ) 
		{
			user_error('html_entity_decode() expects parameter 2 to be long, ' .
			gettype($quote_style) . ' given', E_USER_WARNING);
			return;
		}

		$trans_tbl = array_flip(get_html_translation_table(HTML_ENTITIES));

		// Add single quote to translation table;
		$trans_tbl['&#039;'] = '\'';

		// Not translating double quotes
		if( $quote_style & ENT_NOQUOTES ) 
			unset($trans_tbl['&quot;']); // Remove double quote from translation table
		return strtr($string, $trans_tbl);
	}
}

//Emulation de la fonction PHP5 htmlspecialchars_decode().	
if( !function_exists('htmlspecialchars_decode') ) 
{
    function htmlspecialchars_decode($string, $quote_style = null)
    {
        // Sanity check
        if( !is_scalar($string) ) 
		{
            user_error('htmlspecialchars_decode() expects parameter 1 to be string, ' . gettype($string) . ' given', E_USER_WARNING);
            return;
        }

        if( !is_int($quote_style) && $quote_style !== null ) 
		{
            user_error('htmlspecialchars_decode() expects parameter 2 to be integer, ' . gettype($quote_style) . ' given', E_USER_WARNING);
            return;
        }

        // Init
        $from = array('&amp;', '&lt;', '&gt;');
        $to = array('&', '<', '>');
        
        // The function does not behave as documented
        // This matches the actual behaviour of the function
        if( $quote_style & ENT_COMPAT || $quote_style & ENT_QUOTES ) 
		{
            $from[] = '&quot;';
            $to[] = '"';
            
            $from[] = '&#039;';
            $to[] = "'";
        }

        return str_replace($from, $to, $string);
    }
}

//Emulation de la fonction PHP5 array_combine
if( !function_exists('array_combine') ) 
{
    function array_combine($keys, $values)
    {
        if (!is_array($keys)) 
		{
            user_error('array_combine() expects parameter 1 to be array, ' .
                gettype($keys) . ' given', E_USER_WARNING);
            return;
        }

        if (!is_array($values)) 
		{
            user_error('array_combine() expects parameter 2 to be array, ' .
                gettype($values) . ' given', E_USER_WARNING);
            return;
        }

        $key_count = count($keys);
        $value_count = count($values);
        if ($key_count !== $value_count) {
            user_error('array_combine() Both parameters should have equal number of elements', E_USER_WARNING);
            return false;
        }

        if ($key_count === 0 || $value_count === 0) 
		{
            user_error('array_combine() Both parameters should have number of elements at least 0', E_USER_WARNING);
            return false;
        }

        $keys = array_values($keys);
        $values  = array_values($values);

        $combined = array();
        for ($i = 0; $i < $key_count; $i++) 
		{
            $combined[$keys[$i]] = $values[$i];
        }

        return $combined;
    }
}


?>