<?php
/*##################################################
*                         tinymce_unparser.class.php
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

import('content/parser/content_unparser');

/**
 * This class enables to translate the content formatting from the PHPBoost standard one to
 * the TinyMCE one. The PHPBoost one is historically the one corresponding to the BBCode 
 * translation in HTML and is now the reference.
 * TinyMCE has a particular syntax and it must be respected if we want to make a formatting which
 * can be edited after having beeing written, enough what using a WYSIWYG editor hasn't any advantage.
 * @author Benoit Sautel ben.popeye@phpboost.com
 */
class TinyMCEUnparser extends ContentUnparser
{
	/**
	 * Constructor of this kind of parser
	 */
	function TinyMCEUnparser()
	{
		parent::ContentUnparser();
	}
	
	/**
	 * Unparses the content of the parser. It goes from the PHPBoost reference formatting syntax
	 * to the TinyMCE one.
	 */
	function unparse()
	{		
		//Extracting HTML and code tags
		$this->_unparse_html(PICK_UP);
		$this->_unparse_code(PICK_UP);
		
		//Si on n'est pas à la racine du site plus un dossier, on remplace les liens relatifs générés par le BBCode
		if (PATH_TO_ROOT != '..')
		{
			$this->content = str_replace('"' . PATH_TO_ROOT . '/', '"../', $this->content);
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
		
		$this->content = str_replace($array_str, $array_str_replace, $this->content);
		
		//Replacing <br /> by a paragraph
		$this->content = str_replace('<br />', "</p>\n<p>", '<p>' . $this->content . '</p>');
		
		//If we don't protect the HTML code inserted into the tags code and HTML TinyMCE will parse it!
		if (!empty($this->array_tags['html_unparse']))
			$this->array_tags['html_unparse'] = array_map(array('TinyMCEUnparser', '_clear_html_and_code_tag'), $this->array_tags['html_unparse']);
		if (!empty($this->array_tags['code_unparse']))
			$this->array_tags['code_unparse'] = array_map(array('TinyMCEUnparser', '_clear_html_and_code_tag'), $this->array_tags['code_unparse']);
		
		//Unparsing tags supported by TinyMCE
		$this->_unparse_tinymce_formatting();
		//Unparsing tags unsupported by TinyMCE, those are in BBCode
		$this->_unparse_bbcode_tags();
		
		//Reimplanting html and code tags
		$this->_unparse_code(REIMPLANT);
		$this->_unparse_html(REIMPLANT);
	}
	
	## Protected ##
	/**
	 * Replaces the image code for smilies by the text code 
	 */
	function _unparse_smilies()
	{
		@include(PATH_TO_ROOT . '/cache/smileys.php');
		if (!empty($_array_smiley_code))
		{
			//Création du tableau de remplacement
			foreach ($_array_smiley_code as $code => $img)
			{	
				$smiley_img_url[] = '`<img src="../images/smileys/' . preg_quote($img) . '(.*) />`sU';
				$smiley_code[] = $code;
			}	
			$this->content = preg_replace($smiley_img_url, $smiley_code, $this->content);
		}
	}
	
	/**
	 * Function which unparses only the tags supported by TinyMCE
	 */ 
	function _unparse_tinymce_formatting()
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
			'`<p style="text-align:(left|center|right|justify)">(.*)</p>`isU',
			'`<span id="([a-z0-9_-]+)">(.*)</span>`isU',
			'`<ul( style="[^"]+")? class="bb_ul">`i',
			'`<ol( style="[^"]+")? class="bb_ol">`i',
			"`<h3 class=\"title1\">(.*)</h3>(?:[\s]*<br />){0,}`isU",
			"`<h3 class=\"title2\">(.*)</h3>(?:[\s]*<br />){0,}`isU",
			"`[\s]*<br /><h4 class=\"stitle1\">(.*)</h4><br />[\s]*<br />[\s]*`isU",
			"`[\s]*<br /><h4 class=\"stitle2\">(.*)</h4><br />[\s]*<br />[\s]*`isU",
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
			"<p style=\"text-align: $1;\">$2</p>",
			"<a title=\"$1\" name=\"$1\">$2</a>",
			"<ul$1>",
			"<ol$1>",
			"<h1>$1</h1>",
			"<h2>$1</h2>",
			"<h3>$1</h3>",
			"<h4>$1</h4>",
			"<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0\" width=\"$2\" height=\"$3\"><param name=\"movie\" value=\"$1\" /><param name=\"quality\" value=\"high\" /><param name=\"menu\" value=\"false\" /><param name=\"wmode\" value=\"\" /><embed src=\"$1\" wmode=\"\" quality=\"high\" menu=\"false\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" type=\"application/x-shockwave-flash\" width=\"$2\" height=\"$3\"></embed></object>"
		);	
		
		$this->content = preg_replace($array_preg, $array_preg_replace, $this->content);
		
		//Trait horizontal
		$this->content = str_replace('<hr class="bb_hr" />', '<hr />', $this->content);
		
		//Balise size
		$this->content = preg_replace_callback('`<span style="font-size: ([0-9]+)px;">(.*)</span>`isU', create_function('$size', 'if ($size[1] >= 36) $fontsize = 7;	elseif ($size[1] <= 12) $fontsize = 1;	else $fontsize = min(($size[1] - 6)/2, 7); return \'<font size="\' . $fontsize . \'">\' . $size[2] . \'</font>\';'), $this->content);
		
		//Citations
		$this->_parse_imbricated('<span class="text_blockquote">', '`<span class="text_blockquote">(.*):</span><div class="blockquote">(.*)</div>`isU', '<blockquote>$2</blockquote>', $this->content);
		
		//Balise indentation
		$this->content = preg_replace_callback('`((?:<div class="indent">)+)(.+)((?:</div>)+)`isU', array(&$this, '_unparse_indent'), $this->content);
		
		//Police
		$this->content = preg_replace_callback('`<span style="font-family: ([ a-z0-9,_-]+);">(.*)</span>`isU', array(&$this, '_unparse_font'), $this->content );
	}
	
	/**
	 * Manages the whole tags which doesn't not exist in TinyMCE
	 */
	function _unparse_bbcode_tags()
	{		
		$array_preg = array(
			'`<p class="float_(left|right)">(.*)</p>`isU',
			'`<acronym title="([^"]+)" class="bb_acronym">(.*)</acronym>`isU',
			'`<a href="mailto:(.*)">(.*)</a>`isU',
			'`<span class="(success|question|notice|warning|error)">(.*)</span>`isU',
			'`<object type="application/x-shockwave-flash" data="\.\./kernel/data/dewplayer\.swf\?son=(.*)" width="200" height="20">(.*)</object>`isU',
			'`<object type="application/x-shockwave-flash" data="\.\./(?:kernel|includes)/data/movieplayer\.swf" width="([^"]+)" height="([^"]+)">(?:\s|(?:<br />))*<param name="FlashVars" value="flv=(.+)&width=[0-9]+&height=[0-9]+" />.*</object>`isU',
			'`<object type="application/x-shockwave-flash" data="([^"]+)" width="([^"]+)" height="([^"]+)">(.*)</object>`isU',
			'`<!-- START HTML -->' . "\n" . '(.+)' . "\n" . '<!-- END HTML -->`isU',
			'`\[\[MATH\]\](.+)\[\[/MATH\]\]`sU'
		);
		
		$array_preg_replace = array( 
			"[float=$1]$2[/float]",
			"[acronym=$1]$2[/acronym]",
			"[mail=$1]$2[/mail]",
			"[style=$1]$2[/style]",
			"[sound]$1[/sound]",
			"[movie=$1,$2]$3[/movie]",
			"[swf=$2,$3]$1[/swf]",
			"[html]$1[/html]",
			"[math]$1[/math]"
		);
		
		$this->content = preg_replace($array_preg, $array_preg_replace, $this->content);
		
		##Remplacement des balises imbriquées
		
		//Texte caché
		$this->_parse_imbricated('<span class="text_hide">', '`<span class="text_hide">(.*):</span><div class="hide" onclick="bb_hide\(this\)"><div class="hide2">(.*)</div></div>`sU', '[hide]$2[/hide]', $this->content);
		
		//Bloc HTML
		$this->_parse_imbricated('<div class="bb_block"', '`<div class="bb_block">(.+)</div>`sU', '[block]$1[/block]', $this->content);
		$this->_parse_imbricated('<div class="bb_block" style=', '`<div class="bb_block" style="([^"]+)">(.+)</div>`sU', '[block style="$1"]$2[/block]', $this->content);
		
		//Bloc de formulaire
		$this->content = preg_replace_callback('`<fieldset class="bb_fieldset" style="([^"]*)"><legend>(.*)</legend>(.+)</fieldset>`sU', array(&$this, '_unparse_fieldset'), $this->content);

		//Liens Wikipédia
		$this->content = preg_replace_callback('`<a href="http://([a-z]+).wikipedia.org/wiki/([^"]+)" class="wikipedia_link">(.*)</a>`sU', array(&$this, '_unparse_wikipedia_link'), $this->content);
	}
	
	/**
	 * Handler which clears the HTML code which is in the code and HTML tags
	 *
	 * @param string $var variable to clear
	 * @return the clean content
	 */
	function _clear_html_and_code_tag($var)
	{
		$var = str_replace("\n", '<br />', $var);
		return htmlentities($var, ENT_NOQUOTES);
	}

	/**
	 * Unparses the fieldset tag to BBCode syntax (it doesn't exist
	 * in TinyMCE).
	 *
	 * @param string[] $matches The matched elements
	 * @return string The corresponding BBCode syntax
	 */
	function _unparse_fieldset($matches)
	{
		$style = '';
		$legend = '';
		
		if (!empty($matches[1]))
		{
			$style = ' style="' . $matches[1] . '"';
		}
		
		if (!empty($matches[2]))
		{
			$legend = ' legend="' . $matches[2] . '"';
		}
		
		if (!empty($legend) || !empty($style))
		{ 
			return '[fieldset' . $legend . $style . ']' . $matches[3] . '[/fieldset]';
		}
		else
		{
			return '[fieldset]' . $matches[3] . '[/fieldset]';
		} 
	}
	
	/**
	 * Unparses the wikipedia links (exists only in BBCode), so it
	 * uses the BBCode syntax.
	 * @param string[] $matches The matched elements
	 * @return string The corresponding BBCode syntax 
	 */
	function _unparse_wikipedia_link($matches)
	{
		global $LANG;
		
		//On est dans la langue par défaut
		if ($matches[1] == $LANG['wikipedia_subdomain'])
		{
			$lang = '';
		}
		else
		{
			$lang = $matches[1];
		}
			
		//L'intitulé du lien est différent du nom de l'article
		if ($matches[2] != $matches[3])
		{
			$page_name = $matches[2];
		}
		else
		{
			$page_name = '';
		}
		
		return '[wikipedia' . (!empty($page_name) ? ' page="' . $page_name . '"' : '') . (!empty($lang) ? ' lang="' . $lang . '"' : '') . ']' . $matches[3] . '[/wikipedia]';
	}
	
	/**
	 * Unparses the indentation tag. TinyMCE supports it but not with a similar way,
	 * it we nest indentation, it returns only one indentation containing directly the good margin,
	 * whereas in BBCode we must nest the indent tags.
	 *
	 * @param string[] $matches The matched elements
	 * @return string The correct TinyMCE syntax
	 */
	function _unparse_indent($matches)
	{
		//Combien de fois c'est indenté ?
		$nbr_indent = substr_count($matches[1], '<div class="indent">');
		
		//Combien de fois c'est fermé ?
		$nbr_closed_indent_tags = substr_count($matches[3], '</div>');
		
		//L'expression régulière ne capture que le dernier div fermant. Les autres ont à la fin du contenu central, on les traite à la main
		while (substr($matches[2], -6) == '</div>')
		{
		    //On le supprime et on incrémente de 1 le nombre de div fermants
		    $matches[2] = substr($matches[2], 0, -6);
		    $nbr_closed_indent_tags++;
		}
		
		//Si ça a été ouvert et fermé le même nombre de fois
		if ($nbr_indent == $nbr_indent)
		{
			return '<p style="padding-left: ' . (30 * $nbr_indent) . 'px;">' . $matches[2] . '</p>';
		}
		//Si c'est plus fermé qu'ouvert c'est que d'autres balises sont fermées, on prend en enlève $nbr_indent - 1
		elseif ($nbr_indent < $nbr_closed_indent_tags)
		{
			return '<p style="padding-left: ' . (30 * $nbr_indent) . 'px;">' . $matches[2] . str_repeat('</p>', $nbr_closed_indent_tags - $nbr_indent + 1);
		}
		//Sinon c'est une situation anormale, on renvoie ce qu'on a reçu
		else
		{
			return $matches[1] . $matches[2] . $matches[3];
		}
	}
	
	/**
	 * Computes the correct TinyMCE syntax for changing the font.
	 * The problem is that in BBCode we only use one font, and to be compatible (and it's not so bad),
	 * TinyMCE uses three or four similar fonts. This method enables to map these two different expressions.
	 *
	 * @param string[] $matches The matched elements
	 * @return string The correct TinyMCE formatting
	 */
	function _unparse_font($matches)
	{
		static $fonts_array = array(
			'geneva' => 'trebuchet ms,geneva',
			'optima' => 'comic sans ms,sans-serif',
			'arial' => 'arial,helvetica,sans-serif',
			'courier new' => 'courier new,courier'
		);
		
		if (!empty($fonts_array[$matches[1]]))
		{
			return '<span style="font-family: ' . $fonts_array[$matches[1]] . ';">' . $matches[2] . '</span>';
		}
		else
		{
			return $matches[2];
		}
	}
}

?>