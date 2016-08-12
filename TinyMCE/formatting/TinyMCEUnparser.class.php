<?php
/*##################################################
 *                          TinyMCEUnparser.class.php
 *                            -------------------
 *   begin                : August 10, 2008
 *   copyright            : (C) 2008 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
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

/**
 * @package {@package}
 * @desc This class enables to translate the content formatting from the PHPBoost standard one to
 * the TinyMCE one. The PHPBoost one is historically the one corresponding to the BBCode
 * translation in HTML and is now the reference.
 * TinyMCE has a particular syntax and it must be respected if we want to make a formatting which
 * can be edited after having beeing written, enough what using a WYSIWYG editor hasn't any advantage.
 * @author Benoit Sautel ben.popeye@phpboost.com
 * @see the TinyMCEParser class which makes the reverse operation.
 */
class TinyMCEUnparser extends ContentFormattingUnparser
{
	private static $fonts_array = array(
		'andale mono' => '\'andale mono\', monospace',
		'arial' => 'arial, helvetica, sans-serif',
		'arial black' => '\'arial black\', sans-serif',
		'book antiqua' => '\'book antiqua\', palatino, serif',
		'comic sans ms' => '\'comic sans ms\', sans-serif',
		'courier new' => '\'courier new\', courier, monospace',
		'georgia' => 'georgia, palatino, serif',
		'helvetica' => 'helvetica, arial, sans-serif',
		'impact' => 'impact, sans-serif',
		'symbol' => 'symbol',
		'tahoma' => 'tahoma, arial, helvetica, sans-serif',
		'terminal' => 'terminal, monaco, monospace',
		'times new roman' => '\'times new roman\', times, serif',
		'trebuchet ms' => '\'trebuchet ms\', geneva, sans-serif',
		'verdana' => 'verdana, geneva, sans-serif',
		'webdings' => 'webdings',
		'wingdings' => 'wingdings, \'zapf dingbats\''
	);
	
	/**
	 * @desc Builds a TinyMCEUnparser object
	 */
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * @desc Unparses the content of the parser. It goes from the PHPBoost reference formatting syntax
	 * to the TinyMCE one.
	 */
	public function parse()
	{
		//The URL must be absolute otherwise TinyMCE won't be able to display images for instance.
		$this->content = Url::html_convert_root_relative2absolute($this->content, $this->path_to_root);
		
		//Extracting HTML and code tags
		$this->unparse_html(self::PICK_UP);
		$this->unparse_code(self::PICK_UP);
		
		//Smilies
		$this->unparse_smilies();
		
		//Remplacement des caractères de word
		$array_str = array(
			"\t", '[b]', '[/b]', '[i]', '[/i]', '[s]', '[/s]', '€', '‚', 'ƒ',
			'„', '…', '†', '‡', 'ˆ', '‰', 'Š', '‹', 'Œ', 'Ž',
			'‘', '’', '“', '”', '•', '–', '—',  '˜', '™', 'š',
			'›', 'œ', 'ž', 'Ÿ'
		);

		$array_str_replace = array(
			'&nbsp;&nbsp;&nbsp;', '<strong>', '</strong>', '<em>', '</em>', '<s>', '</s>', '&#8364;', '&#8218;', '&#402;', '&#8222;',
			'&#8230;', '&#8224;', '&#8225;', '&#710;', '&#8240;', '&#352;', '&#8249;', '&#338;', '&#381;',
			'&#8216;', '&#8217;', '&#8220;', '&#8221;', '&#8226;', '&#8211;', '&#8212;', '&#732;', '&#8482;',
			'&#353;', '&#8250;', '&#339;', '&#382;', '&#376;'
		);

		$this->content = str_replace($array_str, $array_str_replace, $this->content);

		//Replacing <br /> by a paragraph
		if (!empty($this->content))
		{
			$this->content = preg_replace(
				array(
					'`\s*</p>`i',
					'`<p>\s*`i',
				),
				array(
					'</p>',
					'<p>',
				),
				'<p>' . $this->content . '</p>'
			);
		}
		
		//Unparsing tags unsupported by TinyMCE, those are in BBCode
		$this->unparse_bbcode_tags();
		//Unparsing tags supported by TinyMCE
		$this->unparse_tinymce_formatting();

		//If we don't protect the HTML code inserted into the tags code and HTML TinyMCE will parse it!
		if (!empty($this->array_tags['html_unparse']))
		{
			$this->array_tags['html_unparse'] = array_map(array('TinyMCEUnparser', 'clear_html_and_code_tag'), $this->array_tags['html_unparse']);
		}
		if (!empty($this->array_tags['code_unparse']))
		{
			$this->array_tags['code_unparse'] = array_map(array($this, 'clear_html_and_code_tag'), $this->array_tags['code_unparse']);
		}

		//reimplanting html and code tags
		$this->unparse_code(self::REIMPLANT);
		$this->unparse_html(self::REIMPLANT);
	}
	
	/**
	 * @desc Replaces the image code for smilies by the text code
	 */
	private function unparse_smilies()
	{
		$this->content = preg_replace('`<img src="[\./]*/images/smileys/([^"]+)" alt="([^"]+)" class="smiley" />`i',
		'<img title="$2" src="' . PATH_TO_ROOT . '/images/smileys/$1" alt="$2" border="0" />', $this->content);
		
		$this->content = preg_replace('`<img src="[\./]*/images/smileys/([^"]+)" title="([^"]+)" alt="([^"]+)" class="smiley" />`i',
		'<img title="$2" src="' . PATH_TO_ROOT . '/images/smileys/$1" alt="$3" border="0" />', $this->content);
	}

	/**
	 * @desc Function which unparses only the tags supported by TinyMCE
	 */
	private function unparse_tinymce_formatting()
	{
		//Preg_replace.
		$array_preg = array(
			'`<span id="([a-z0-9_-]+)"></span>`isU',
			'`<span id="([a-z0-9_-]+)" class="anchor"></span>`isU',
			'`<span id="([a-z0-9_-]+)">(.*)</span>`isU',
			'`<h1(?: class="([^"]+)?")?>(.*)</h1>(?:[\s]*){0,}`isU',
			'`<h2(?: class="([^"]+)?")?>(.*)</h2>(?:[\s]*){0,}`isU',
			'`<h3(?: class="([^"]+)?")?>(.*)</h3>(?:[\s]*){0,}`isU',
			'`<h4(?: class="([^"]+)?")?>(.*)</h4>(?:[\s]*){0,}`isU',
			'`<h5(?: class="([^"]+)?")?>(.*)</h5>(?:[\s]*){0,}`isU',
			'`<h6(?: class="([^"]+)?")?>(.*)</h6>(?:[\s]*){0,}`isU',
			'`<span style="background-color:([^;]+);">(.+)</span>`isU',
			'`<span style="color:([^;]+);">(.+)</span>`isU',
			'`<p style="text-align:(left|center|right|justify)">(.*)</p>`isU',
			'`<object type="application/x-shockwave-flash" data="([^"]+)" width="([^"]+)" height="([^"]+)">(.*)</object>`isU',
			'`<td(?: class="([^"]+)?")?></td>`isU',
			'`<th(?: class="([^"]+)?")?></th>`isU'
			);
			$array_preg_replace = array(
			"<a id=\"$1\"></a>",
			"<a id=\"$1\"></a>",
			"<a title=\"$1\" name=\"$1\">$2</a>",
			"<h1>$2</h1>",
			"<h1>$2</h1>",
			"<h2>$2</h2>",
			"<h3>$2</h3>",
			"<h4>$2</h4>",
			"<h5>$2</h5>",
			'<span style="background-color: $1;">$2</span>',
			'<span style="color: $1;">$2</span>',
			"<p style=\"text-align: $1;\">$2</p>",
			"<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0\" width=\"$2\" height=\"$3\"><param name=\"movie\" value=\"$1\" /><param name=\"quality\" value=\"high\" /><param name=\"menu\" value=\"false\" /><param name=\"wmode\" value=\"\" /><embed src=\"$1\" wmode=\"\" quality=\"high\" menu=\"false\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" type=\"application/x-shockwave-flash\" width=\"$2\" height=\"$3\"></embed></object>",
			'<td> </td>',
			'<th> </th>'
			);

			$this->content = preg_replace($array_preg, $array_preg_replace, $this->content);

			//Tableaux
			while (preg_match('`<table class="formatter-table"( style="([^"]+)")?>`i', $this->content))
			{
				$this->content = preg_replace('`<table class="formatter-table"( style="([^"]+)")?>`i', "<table border=\"0\"$1><tbody>", $this->content);
				$this->content = preg_replace('`</table>`i', "</tbody></table>", $this->content);
				$this->content = preg_replace('`<td class="formatter-table-col"( colspan="[^"]+")?( rowspan="[^"]+")?( style="[^"]+")?>`i', "<td$1$2$3>", $this->content);
				$this->content = preg_replace('`<tr class="formatter-table-row"( style="[^"]+")?>`i', "<tr$1>", $this->content);
				$this->content = preg_replace('`<th class="formatter-table-head"( colspan="[^"]+")?( rowspan="[^"]+")?( style="[^"]+")?>`i', "<th$1$2$3>", $this->content);
			}

			//Listes
			while (preg_match('`<ul( style="[^"]+")? class="formatter-ul">`i', $this->content))
			{
				$this->content = preg_replace('`<ul( style="[^"]+")? class="formatter-ul">`i', "<ul$1>", $this->content);
			}
			while (preg_match('`<ol( style="[^"]+")? class="formatter-ol">`i', $this->content))
			{
				$this->content = preg_replace('`<ol( style="[^"]+")? class="formatter-ol">`i', "<ol$1>", $this->content);
			}
			while (preg_match('`<li( style="[^"]+")? class="formatter-li">`i', $this->content))
			{
				$this->content = preg_replace('`<li( style="[^"]+")? class="formatter-li">`i', "<li$1>", $this->content);
			}
			
			//Trait horizontal
			$this->content = preg_replace('`<hr(?: class="([^"]+)?")? />`i', '<hr />', $this->content);

			//Balise size
			$this->content = preg_replace_callback('`<span style="font-size: ([0-9-]+)px;">(.+)</span>`isU', array($this, 'unparse_size_tag'), $this->content);

			//Citations
			$this->content = preg_replace('`<div class="formatter-container formatter-blockquote"><span class="formatter-title">' . LangLoader::get_message('quotation', 'main') . ':</span><div class="formatter-content">(.*)</div></div>`isU', "\n" . '<blockquote><p>$1</p></blockquote>', $this->content);
			$this->_parse_imbricated('<div class="formatter-container formatter-blockquote"><span class="formatter-title">', '`<div class="formatter-container formatter-blockquote"><span class="formatter-title">(.*) :</span><div class="formatter-content">(.*)</div></div>`isU', '[quote]$2[/quote]', $this->content);
			$this->_parse_imbricated('<div class="formatter-container formatter-blockquote"><span class="formatter-title title-perso">', '`<div class="formatter-container formatter-blockquote"><span class="formatter-title title-perso">(.*) :</span><div class="formatter-content">(.*)</div></div>`isU', '[quote=$1]$2[/quote]', $this->content);

			//Balise indentation
			$this->content = preg_replace('`(?:<p>\s*</p>)?\s*<p>\s*<div class="indent">(.+)</div>\s*</p>`isU', "\n" . '<p style="padding-left: 30px;">$1</p>', $this->content);

			//Police
			$this->content = preg_replace_callback('`<span style="font-family: ([ a-z0-9,_-]+);">(.*)</span>`isU', array($this, 'unparse_font'), $this->content );
			
			//Image
			$this->content = preg_replace_callback('`<img src="([^"]+)"(?: alt="([^"]+)")?(?: style="([^"]*)")? />`isU', array($this, 'unparse_img'), $this->content );
			
			// Feed
			$this->content = preg_replace('`\[\[FEED([^\]]*)\]\](.+)\[\[/FEED\]\]`U', '[feed$1]$2[/feed]', $this->content);
	}

	/**
	 * @desc Manages the whole tags which doesn't not exist in TinyMCE
	 */
	private function unparse_bbcode_tags()
	{
		$array_preg = array(
			'`<abbr title="([^"]+)">(.*)</abbr>`isU',
			'`<a href="mailto:(.*)">(.*)</a>`isU',
			'`<audio controls><source src="(.*)" /></audio>`isU',
			'`\[\[MEDIA\]\]insertSoundPlayer\(\'([^\']+)\'\);\[\[/MEDIA\]\]`sU',
			'`<p class="float-(left|right)"><object type="application/x-shockwave-flash" data="(?:\.\.)?/(?:kernel|includes)/data/movieplayer\.swf" width="([^"]+)" height="([^"]+)">(?:\s|(?:<br />))*<param name="FlashVars" value="flv=(.+)&width=[0-9]+&height=[0-9]+" />.*</object></p>`isU',
			'`<p style="text-align:center"><object type="application/x-shockwave-flash" data="(?:\.\.)?/(?:kernel|includes)/data/movieplayer\.swf" width="([^"]+)" height="([^"]+)">(?:\s|(?:<br />))*<param name="FlashVars" value="flv=(.+)&width=[0-9]+&height=[0-9]+" />.*</object></p>`isU',
			'`<object type="application/x-shockwave-flash" data="(?:\.\.)?/(?:kernel|includes)/data/movieplayer\.swf" width="([^"]+)" height="([^"]+)">(?:\s|(?:<br />))*<param name="FlashVars" value="flv=(.+)&width=[0-9]+&height=[0-9]+" />.*</object>`isU',
			'`<p class="float-(left|right)">\[\[MEDIA\]\]insertMoviePlayer\(\'([^\']+)\', (\d{1,3}), (\d{1,3})\);\[\[/MEDIA\]\]</p>`sU',
			'`<p style="text-align:center">\[\[MEDIA\]\]insertMoviePlayer\(\'([^\']+)\', (\d{1,3}), (\d{1,3})\);\[\[/MEDIA\]\]</p>`sU',
			'`\[\[MEDIA\]\]insertMoviePlayer\(\'([^\']+)\', (\d{1,3}), (\d{1,3})\);\[\[/MEDIA\]\]`sU',
			'`<p class="float-(left|right)"><object type="application/x-shockwave-flash" data="([^"]+)" width="([^"]+)" height="([^"]+)">(.*)</object></p>`isU',
			'`<p style="text-align:center"><object type="application/x-shockwave-flash" data="([^"]+)" width="([^"]+)" height="([^"]+)">(.*)</object></p>`isU',
			'`<object type="application/x-shockwave-flash" data="([^"]+)" width="([^"]+)" height="([^"]+)">(.*)</object>`isU',
			'`<p class="float-(left|right)">\[\[MEDIA\]\]insertSwfPlayer\(\'([^\']+)\', (\d{1,3}), (\d{1,3})\);\[\[/MEDIA\]\]</p>`sU',
			'`<p style="text-align:center">\[\[MEDIA\]\]insertSwfPlayer\(\'([^\']+)\', (\d{1,3}), (\d{1,3})\);\[\[/MEDIA\]\]</p>`sU',
			'`\[\[MEDIA\]\]insertSwfPlayer\(\'([^\']+)\', (\d{1,3}), (\d{1,3})\);\[\[/MEDIA\]\]`sU',
			'`\[\[MEDIA\]\]insertYoutubePlayer\(\'([^\']+)\', (\d{1,3}), (\d{1,3})\);\[\[/MEDIA\]\]`sU',
			'`<!-- START HTML -->' . "\n" . '(.+)' . "\n" . '<!-- END HTML -->`isU',
			'`\[\[MATH\]\](.+)\[\[/MATH\]\]`sU',
			'`<p class="float-(left|right)">(.*)</p>`isU',
			'`<a href="([^"]+)" rel="lightbox\[2\]">(.*)</a>`isU',
			'`<a href="([^"]+)" data-lightbox="formatter">(.*)</a>`isU',
		);

		$array_preg_replace = array(
			"[acronym=$1]$2[/acronym]",
			"[mail=$1]$2[/mail]",
			"[sound]$1[/sound]",
			"[sound]$1[/sound]",
			"<video style=\"float: $1;\" width=\"$2\" height=\"$3\" controls=\"controls\"><source src=\"$4\" /></video>",
			"<video style=\"display: block; margin-left: auto; margin-right: auto;\" width=\"$1\" height=\"$2\" controls=\"controls\"><source src=\"$3\" /></video>",
			"<video width=\"$2\" height=\"$3\" controls=\"controls\"><source src=\"$4\" /></video>",
			"<video style=\"float: $1;\" width=\"$3\" height=\"$4\" controls=\"controls\"><source src=\"$2\" /></video>",
			"<video style=\"display: block; margin-left: auto; margin-right: auto;\" width=\"$2\" height=\"$3\" controls=\"controls\"><source src=\"$1\" /></video>",
			"<video width=\"$2\" height=\"$3\" controls=\"controls\"><source src=\"$1\" /></video>",
			"<video style=\"float: $1;\" width=\"$3\" height=\"$4\" controls=\"controls\"><source src=\"$2\" /></video>",
			"<video style=\"display: block; margin-left: auto; margin-right: auto;\" width=\"$2\" height=\"$3\" controls=\"controls\"><source src=\"$1\" /></video>",
			"<video width=\"$2\" height=\"$3\" controls=\"controls\"><source src=\"$1\" /></video>",
			"<video style=\"float: $1;\" width=\"$3\" height=\"$4\" controls=\"controls\"><source src=\"$2\" /></video>",
			"<video style=\"display: block; margin-left: auto; margin-right: auto;\" width=\"$2\" height=\"$3\" controls=\"controls\"><source src=\"$1\" /></video>",
			"<video width=\"$2\" height=\"$3\" controls=\"controls\"><source src=\"$1\" /></video>",
			"<iframe src=\"$1\" width=\"$2\" height=\"$3\" allowfullscreen=\"allowfullscreen\"></iframe>",
			"[html]$1[/html]",
			"[math]$1[/math]",
			"[float=$1]$2[/float]",
			"[lightbox=$1]$2[/lightbox]",
			"[lightbox=$1]$2[/lightbox]"
		);

		$this->content = preg_replace($array_preg, $array_preg_replace, $this->content);

		##Remplacement des balises imbriquées

		//Texte caché
		$this->_parse_imbricated('<div class="formatter-container formatter-hide" onclick="bb_hide(this)"><span class="formatter-title">', '`<div class="formatter-container formatter-hide" onclick="bb_hide\(this\)"><span class="formatter-title">(.*) :</span><div class="formatter-content">(.*)</div></div>`sU', '[hide]$2[/hide]', $this->content);
		$this->_parse_imbricated('<div class="formatter-container formatter-hide" onclick="bb_hide(this)"><span class="formatter-title title-perso">', '`<div class="formatter-container formatter-hide" onclick="bb_hide\(this\)"><span class="formatter-title title-perso">(.*) :</span><div class="formatter-content">(.*)</div></div>`sU', '[hide=$1]$2[/hide]', $this->content);

		//Bloc HTML
		$this->_parse_imbricated('<div class="formatter-container formatter-block"', '`<div class="formatter-container formatter-block">(.+)</div>`sU', '[block]$1[/block]', $this->content);
		$this->_parse_imbricated('<div class="formatter-container formatter-block style=', '`<div class="formatter-container formatter-block style="([^"]+)">(.+)</div>`sU', '[block style="$1"]$2[/block]', $this->content);

		//Bloc de formulaire
		while (preg_match('`<fieldset class="formatter-container formatter-fieldset" style="([^"]*)"><legend>(.*)</legend><div class="formatter-content">(.+)</div></fieldset>`sU', $this->content))
		{
			$this->content = preg_replace_callback('`<fieldset class="formatter-container formatter-fieldset" style="([^"]*)"><legend>(.*)</legend><div class="formatter-content">(.+)</div></fieldset>`sU', array($this, 'unparse_fieldset'), $this->content);
		}

		//Liens Wikipédia
		$this->content = preg_replace_callback('`<a href="http://([a-z]+).wikipedia.org/wiki/([^"]+)" class="wikipedia-link">(.*)</a>`sU', array($this, 'unparse_wikipedia_link'), $this->content);


	}

	/**
	 * @desc Handler which clears the HTML code which is in the code and HTML tags
	 * @param string $var variable to clear
	 * @return the clean content
	 */
	private static function clear_html_and_code_tag($var)
	{
		$var = str_replace("\n", '<br />', $var);
		return TextHelper::htmlentities($var, ENT_NOQUOTES);
	}

	/**
	 * @desc Unparses the fieldset tag to BBCode syntax (it doesn't exist
	 * in TinyMCE).
	 * @param string[] $matches The matched elements
	 * @return string The corresponding BBCode syntax
	 */
	private function unparse_fieldset($matches)
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
	 * @desc Unparses the wikipedia links (exists only in BBCode), so it
	 * uses the BBCode syntax.
	 * @param string[] $matches The matched elements
	 * @return string The corresponding BBCode syntax
	 */
	private function unparse_wikipedia_link($matches)
	{
		//On est dans la langue par défaut
		if ($matches[1] == LangLoader::get_message('wikipedia_subdomain', 'editor-common'))
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
	
	private function unparse_img($matches)
	{
		$alt = !empty($matches[2]) ? $matches[2] : '';
		$style = '';
		$params = '';
		if (isset($matches[3])) {
			foreach (explode(';', $matches[3]) as $style_att)
			{
				$exp = explode(':', $style_att);
				if (count($exp) < 2)
				{
					continue;
				}
				$value = trim($exp[1]);
				switch (trim($exp[0]))
				{
					case 'width':
						$params .= 'width="' . str_replace('px', '', $value) . '" ';
						break;
					case 'height':
						$params .= 'height="' . str_replace('px', '', $value) . '" ';
						break;
					case 'border':
						$style .= 'border:' . $value . ';';
						break;
				}
			}
			
			if (!empty($style))
			{
				$style = ' style="' . $style . '"';
			}
		}
		return '<img' . $style . ' src="' . $matches[1] . '" alt="' . $alt . '" ' . $params . '/>';
	}

	/**
	 * @desc Computes the correct TinyMCE syntax for changing the font.
	 * The problem is that in BBCode we only use one font, and to be compatible (and it's not so bad),
	 * TinyMCE uses three or four similar fonts. This method enables to map these two different expressions.
	 * @param string[] $matches The matched elements
	 * @return string The correct TinyMCE formatting
	 */
	private function unparse_font($matches)
	{
		if (!empty(self::$fonts_array[$matches[1]]))
		{
			return '<span style="font-family: ' . self::$fonts_array[$matches[1]] . ';">' . $matches[2] . '</span>';
		}
		else
		{
			return $matches[2];
		}
	}

	/**
	 * @desc Processes the size tag. PHPBoost and TinyMCE don't work similary.
	 * PHPBoost needs to have a size in pixels, whereas TinyMCE explains it differently,
	 * with a name associated to each size (for instance xx-small, medium, x-large...).
	 * This method converts from PHPBoost to TinyMCE.
	 * @param string[] $matches The matched elements.
	 * @return string The good PHPBoost syntax.
	 */
	private function unparse_size_tag($matches)
	{
		$size = 0;
		//We retrieve the size (in pt)
		switch ((int)$matches[1])
		{
			case 5:
				$size = '5pt';
				break;
			case 10:
				$size = '10pt';
				break;
			case 15:
				$size = '15pt';
				break;
			case 20:
				$size = '20pt';
				break;
			case 25:
				$size = '25pt';
				break;
			case 30:
				$size = '30pt';
				break;
			case 35:
				$size = '35pt';
				break;
			case 40:
				$size = '40pt';
				break;
			case 45:
				$size = '45pt';
				break;
			default:
				$size = '';
		}
		//If the size is known, we put the HTML code and convert the size into pixels
		if (!empty($size))
		return '<span style="font-size: ' . $size . ';">' . $matches[2] . '</span>';
		else
		return $matches[2];
	}
}
?>