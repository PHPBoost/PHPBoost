<?php
/**
 * This class enables to translate the content formatting from the PHPBoost standard one to
 * the TinyMCE one. The PHPBoost one is historically the one corresponding to the BBCode
 * translation in HTML and is now the reference.
 * TinyMCE has a particular syntax and it must be respected if we want to make a formatting which
 * can be edited after having beeing written, enough what using a WYSIWYG editor hasn't any advantage.
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 02 19
 * @since       PHPBoost 2.0 - 2008 08 10
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
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
	 * @desc Unparses the content of the parser. It goes from the PHPBoost reference formatting syntax
	 * to the TinyMCE one.
	 */
	public function parse()
	{
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
					'`\s*</p>`iu',
					'`<p>\s*`iu',
				),
				array(
					'</p>',
					'<p>',
				),
				'<p>' . $this->content . '</p>'
			);
		}

		//Module eventual special tags replacement
		$this->unparse_module_special_tags();

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
		$smiley_img_url = $smiley_code = array();
		foreach (SmileysCache::load()->get_smileys() as $code => $infos)
		{
			$smiley_img_url[] = '`<img src="([^"]+)?/images/smileys/' . preg_quote($infos['url_smiley']) . '(.*) />`suU';
			$smiley_code[] = $code;
		}
		if (!empty($smiley_img_url))
			$this->content = preg_replace($smiley_img_url, $smiley_code, $this->content);
	}

	/**
	 * @desc Function which unparses only the tags supported by TinyMCE
	 */
	private function unparse_tinymce_formatting()
	{
		//Preg_replace.
		$array_preg = array(
			'`<span id="([a-z0-9_-]+)"></span>`isuU',
			'`<span id="([a-z0-9_-]+)" class="anchor"></span>`isuU',
			'`<span id="([a-z0-9_-]+)">(.*)</span>`isuU',
			'`<h1(?: class="([^"]+)?")?>(.*)</h1>(?:[\s]*){0,}`isuU',
			'`<h2(?: class="([^"]+)?")?>(.*)</h2>(?:[\s]*){0,}`isuU',
			'`<h3(?: class="([^"]+)?")?>(.*)</h3>(?:[\s]*){0,}`isuU',
			'`<h4(?: class="([^"]+)?")?>(.*)</h4>(?:[\s]*){0,}`isuU',
			'`<h5(?: class="([^"]+)?")?>(.*)</h5>(?:[\s]*){0,}`isuU',
			'`<h6(?: class="([^"]+)?")?>(.*)</h6>(?:[\s]*){0,}`isuU',
			'`<span style="background-color: ?([^;]+);?">(.+)</span>`isuU',
			'`<span style="color: ?([^;]+);?">(.+)</span>`isuU',
			'`<p style="text-align: ?(left|center|right|justify);?">(.*)</p>`isuU',
			'`<td(?: class="([^"]+)?")?></td>`isuU',
			'`<th(?: class="([^"]+)?")?></th>`isuU'
			);
			$array_preg_replace = array(
			"<a class=\"offload\" id=\"$1\"></a>",
			"<a class=\"offload\" id=\"$1\"></a>",
			"<a class=\"offload\" aria-label=\"$1\" name=\"$1\">$2</a>",
			"<h1>$2</h1>",
			"<h1>$2</h1>",
			"<h2>$2</h2>",
			"<h3>$2</h3>",
			"<h4>$2</h4>",
			"<h5>$2</h5>",
			'<span style="background-color: $1;">$2</span>',
			'<span style="color: $1;">$2</span>',
			"<p style=\"text-align: $1;\">$2</p>",
			'<td> </td>',
			'<th> </th>'
			);

			$this->content = preg_replace($array_preg, $array_preg_replace, $this->content);

			//Tableaux
			while (preg_match('`<table class="table formatter-table"( style="([^"]+)")?>`iu', $this->content))
			{
				$this->content = preg_replace('`<table class="table formatter-table"( style="([^"]+)")?>`iu', "<table border=\"0\"$1><tbody>", $this->content);
				$this->content = preg_replace('`</table>`iu', "</tbody></table>", $this->content);
				$this->content = preg_replace('`<td class="formatter-table-col"( colspan="[^"]+")?( rowspan="[^"]+")?( style="[^"]+")?>`iu', "<td$1$2$3>", $this->content);
				$this->content = preg_replace('`<tr class="formatter-table-row"( style="[^"]+")?>`iu', "<tr$1>", $this->content);
				$this->content = preg_replace('`<td class="formatter-table-head"( colspan="[^"]+")?( rowspan="[^"]+")?( style="[^"]+")?>`iu', "<td$1$2$3>", $this->content);
			}

			//Listes
			while (preg_match('`<ul( style="[^"]+")? class="formatter-ul">`iu', $this->content))
			{
				$this->content = preg_replace('`<ul( style="[^"]+")? class="formatter-ul">`iu', "<ul$1>", $this->content);
			}
			while (preg_match('`<ol( style="[^"]+")? class="formatter-ol">`iu', $this->content))
			{
				$this->content = preg_replace('`<ol( style="[^"]+")? class="formatter-ol">`iu', "<ol$1>", $this->content);
			}
			while (preg_match('`<li( style="[^"]+")? class="formatter-li">`iu', $this->content))
			{
				$this->content = preg_replace('`<li( style="[^"]+")? class="formatter-li">`iu', "<li$1>", $this->content);
			}

			//Trait horizontal
			$this->content = preg_replace('`<hr(?: class="([^"]+)?")? />`iu', '<hr />', $this->content);

			//Balise size
			$this->content = preg_replace_callback('`<span style="font-size: ([0-9-]+)px;">(.+)</span>`isuU', array($this, 'unparse_size_tag'), $this->content);

			//Citations
			$this->content = preg_replace('`<blockquote class="formatter-container formatter-blockquote"><span class="formatter-title">(.*)</span><div class="formatter-content">(.*)</div></blockquote>`isuU', "\n" . '<blockquote><p>$2</p></blockquote>', $this->content);
			$this->_parse_imbricated('<blockquote class="formatter-container formatter-blockquote"><span class="formatter-title">', '`<blockquote class="formatter-container formatter-blockquote"><span class="formatter-title">(.*)</span><div class="formatter-content">(.*)</div></blockquote>`isuU', '<blockquote><p>$2</p></blockquote>', $this->content);
			$this->_parse_imbricated('<blockquote class="formatter-container formatter-blockquote"><span class="formatter-title title-perso">', '`<blockquote class="formatter-container formatter-blockquote"><span class="formatter-title title-perso">(.*) :</span><div class="formatter-content">(.*)</div></blockquote>`isuU', '[quote=$1]$2[/quote]', $this->content);
			
			//Balise indentation
			$this->content = preg_replace('`(?:<p>\s*</p>)?\s*<p>\s*<div class="indent">(.+)</div>\s*</p>`isuU', "\n" . '<p style="padding-left: 30px;">$1</p>', $this->content);

			//Police
			$this->content = preg_replace_callback('`<span style="font-family: ([ a-z0-9,_-]+);">(.*)</span>`isuU', array($this, 'unparse_font'), $this->content );

			//Image
			$this->content = preg_replace_callback('`<img src="([^"]+)"(?: alt="([^"]+)?")?(?: style="([^"]+)?")?(?: class="([^"]+)?")? />`isuU', array($this, 'unparse_img'), $this->content );

			// Feed
			$this->content = preg_replace('`\[\[FEED([^\]]*)\]\](.+)\[\[/FEED\]\]`uU', '[feed$1]$2[/feed]', $this->content);
	}

	/**
	 * @desc Manages the whole tags which doesn't not exist in TinyMCE
	 */
	private function unparse_bbcode_tags()
	{
		$array_preg = array(
			'`<acronym class="formatter-acronym">(.*)</acronym>`isuU',
			'`<acronym title="([^"]+)?" class="formatter-acronym">(.*)</acronym>`isuU',
			'`<abbr class="formatter-abbr">(.*)</abbr>`isuU',
			'`<abbr title="([^"]+)?" class="formatter-abbr">(.*)</abbr>`isuU',
			'`<a href="mailto:(.*)">(.*)</a>`isuU',
			'`<audio controls><source src="(.*)" /></audio>`isuU',
			'`\[\[MEDIA\]\]insertSoundPlayer\(\'([^\']+)\'\);\[\[/MEDIA\]\]`suU',
			'`<p class="float-(left|right)">\[\[MEDIA\]\]insertMoviePlayer\(\'([^\']+)\', (\d{1,3}), (\d{1,3})\);\[\[/MEDIA\]\]</p>`suU',
			'`<p style="text-align: ?center;?">\[\[MEDIA\]\]insertMoviePlayer\(\'([^\']+)\', (\d{1,3}), (\d{1,3})\);\[\[/MEDIA\]\]</p>`suU',
			'`\[\[MEDIA\]\]insertMoviePlayer\(\'([^\']+)\', (\d{1,3}), (\d{1,3})\);\[\[/MEDIA\]\]`suU',
			'`<p class="float-(left|right)">\[\[MEDIA\]\]insertMoviePlayer\(\'([^\']+)\', (\d{1,3}), (\d{1,3}), \'([^\']+)\'\);\[\[/MEDIA\]\]</p>`suU',
			'`<p style="text-align: ?center;?">\[\[MEDIA\]\]insertMoviePlayer\(\'([^\']+)\', (\d{1,3}), (\d{1,3}), \'([^\']+)\'\);\[\[/MEDIA\]\]</p>`suU',
			'`\[\[MEDIA\]\]insertMoviePlayer\(\'([^\']+)\', (\d{1,3}), (\d{1,3}), \'([^\']+)\'\);\[\[/MEDIA\]\]`suU',
			'`<p class="float-(left|right)"><object type="application/x-shockwave-flash" data="([^"]+)" width="([^"]+)" height="([^"]+)">(.*)</object></p>`isuU',
			'`<p style="text-align: ?center;?"><object type="application/x-shockwave-flash" data="([^"]+)" width="([^"]+)" height="([^"]+)">(.*)</object></p>`isuU',
			'`<object type="application/x-shockwave-flash" data="([^"]+)" width="([^"]+)" height="([^"]+)">(.*)</object>`isuU',
			'`<p class="float-(left|right)">\[\[MEDIA\]\]insertSwfPlayer\(\'([^\']+)\', (\d{1,3}), (\d{1,3})\);\[\[/MEDIA\]\]</p>`suU',
			'`<p style="text-align: ?center;?">\[\[MEDIA\]\]insertSwfPlayer\(\'([^\']+)\', (\d{1,3}), (\d{1,3})\);\[\[/MEDIA\]\]</p>`suU',
			'`\[\[MEDIA\]\]insertSwfPlayer\(\'([^\']+)\', (\d{1,3}), (\d{1,3})\);\[\[/MEDIA\]\]`suU',
			'`\[\[MEDIA\]\]insertYoutubePlayer\(\'([^\']+)\', (\d{1,3}), (\d{1,3})\);\[\[/MEDIA\]\]`suU',
			'`\[\[MEDIA\]\]insertDailymotionPlayer\(\'([^\']+)\', (\d{1,3}), (\d{1,3})\);\[\[/MEDIA\]\]`suU',
			'`\[\[MEDIA\]\]insertVimeoPlayer\(\'([^\']+)\', (\d{1,3}), (\d{1,3})\);\[\[/MEDIA\]\]`suU',
			'`<!-- START HTML -->' . "\n" . '(.+)' . "\n" . '<!-- END HTML -->`isuU',
			'`\[\[MATH\]\](.+)\[\[/MATH\]\]`suU',
			'`<p class="float-(left|right)">(.*)</p>`isuU',
			'`<a href="([^"]+)" rel="lightbox\[2\]"(?: class="formatter-lightbox")?>(.*)</a>`isuU',
			'`<a href="([^"]+)" data-lightbox="formatter"(?: class="formatter-lightbox")?>(.*)</a>`isuU',
			'`<figure>(.*)<figcaption>(.*)</figcation></figure>`isuU',
			'`\[\[MEMBER\]\](.+)\[\[/MEMBER\]\]`suU',
			'`\[\[TEASER\]\](.+)\[\[/TEASER\]\]`suU',
			'`\[\[MODERATOR\]\](.+)\[\[/MODERATOR\]\]`suU',
			'`<span class="emoji-tag">(.*)</span>`isuU',
		);

		$array_preg_replace = array(
			"[acronym]$1[/acronym]",
			"[acronym=$1]$2[/acronym]",
			"[abbr]$1[/abbr]",
			"[abbr=$1]$2[/abbr]",
			"[mail=$1]$2[/mail]",
			"[sound]$1[/sound]",
			"[sound]$1[/sound]",
			"<video style=\"float: $1;\" width=\"$3\" height=\"$4\" controls=\"controls\"><source src=\"$2\" /></video>",
			"<video style=\"display: block; margin-left: auto; margin-right: auto;\" width=\"$2\" height=\"$3\" controls=\"controls\"><source src=\"$1\" /></video>",
			"<video width=\"$2\" height=\"$3\" controls=\"controls\"><source src=\"$1\" /></video>",
			"[movie=$2,$3,$4]$1[/movie]",
			"[movie=$2,$3,$4]$1[/movie]",
			"[movie=$2,$3,$4]$1[/movie]",
			"<video style=\"float: $1;\" width=\"$3\" height=\"$4\" controls=\"controls\"><source src=\"$2\" /></video>",
			"<video style=\"display: block; margin-left: auto; margin-right: auto;\" width=\"$2\" height=\"$3\" controls=\"controls\"><source src=\"$1\" /></video>",
			"<video width=\"$2\" height=\"$3\" controls=\"controls\"><source src=\"$1\" /></video>",
			"<video style=\"float: $1;\" width=\"$3\" height=\"$4\" controls=\"controls\"><source src=\"$2\" /></video>",
			"<video style=\"display: block; margin-left: auto; margin-right: auto;\" width=\"$2\" height=\"$3\" controls=\"controls\"><source src=\"$1\" /></video>",
			"<video width=\"$2\" height=\"$3\" controls=\"controls\"><source src=\"$1\" /></video>",
			"<iframe src=\"$1\" width=\"$2\" height=\"$3\" allowfullscreen=\"allowfullscreen\"></iframe>",
			"<iframe src=\"$1\" width=\"$2\" height=\"$3\" allowfullscreen=\"allowfullscreen\"></iframe>",
			"<iframe src=\"$1\" width=\"$2\" height=\"$3\" allowfullscreen=\"allowfullscreen\"></iframe>",
			"[html]$1[/html]",
			"[math]$1[/math]",
			"[float=$1]$2[/float]",
			"[lightbox=$1]$2[/lightbox]",
			"[lightbox=$1]$2[/lightbox]",
			"[figure=$2]$1[/figure]",
			"[member]$1[/member]",
			"[moderator]$1[/moderator]",
			"[teaser]$1[/teaser]",
			"$1",
		);

		$this->content = preg_replace($array_preg, $array_preg_replace, $this->content);

		##Callbacks
		//FA Icon
		$this->content = preg_replace_callback('`<i class="fa([blrsd])? fa-([a-z0-9-]+)( [a-z0-9- ]+)?"(?: style="([^"]+)?")?(?: aria-hidden="true")?(?: title="([^"]+)?")?></i>`iuU', array($this, 'unparse_fa_tag'), $this->content);

		##Remplacement des balises imbriquées

		//Texte caché
		$this->_parse_imbricated('<div class="formatter-container formatter-hide no-js"><span class="formatter-title">', '`<div class="formatter-container formatter-hide no-js"><span class="formatter-title">(.*) :</span><div class="formatter-content">(.*)</div></div>`suU', '[hide]$2[/hide]', $this->content);
		$this->_parse_imbricated('<div class="formatter-container formatter-hide no-js"><span class="formatter-title title-perso">', '`<div class="formatter-container formatter-hide" onclick="bb_hide\(this\)"><span class="formatter-title title-perso">(.*) :</span><div class="formatter-content">(.*)</div></div>`suU', '[hide=$1]$2[/hide]', $this->content);

		//Bloc HTML
		$this->_parse_imbricated('<div class="formatter-container formatter-block"', '`<div class="formatter-container formatter-block">(.+)</div>`suU', '[block]$1[/block]', $this->content);
		$this->_parse_imbricated('<div class="formatter-container formatter-block style=', '`<div class="formatter-container formatter-block style="([^"]+)">(.+)</div>`suU', '[block style="$1"]$2[/block]', $this->content);

		//Bloc de formulaire
		while (preg_match('`<fieldset class="formatter-container formatter-fieldset" style="([^"]*)"><legend>(.*)</legend><div class="formatter-content">(.+)</div></fieldset>`suU', $this->content))
		{
			$this->content = preg_replace_callback('`<fieldset class="formatter-container formatter-fieldset" style="([^"]*)"><legend>(.*)</legend><div class="formatter-content">(.+)</div></fieldset>`suU', array($this, 'unparse_fieldset'), $this->content);
		}

		//Liens Wikipédia
		$this->content = preg_replace_callback('`<a href="https?://([a-z]+).wikipedia.org/wiki/([^"]+)" class="wikipedia-link offload">(.*)</a>`suU', array($this, 'unparse_wikipedia_tag'), $this->content);

		//Div
		while (preg_match('`<div id="([^"]*)" class="([^"]*)" style="([^"]*)">(.+)</div>`suU', $this->content))
		{
			$this->content = preg_replace_callback('`<div id="([^"]*)" class="([^"]*)" style="([^"]*)">(.+)</div>`suU', array($this, 'unparse_container'), $this->content);
		}
	}

	/**
	 * @desc Handler which clears the HTML code which is in the code and HTML tags
	 * @param string $var variable to clear
	 * @return the clean content
	 */
	private static function clear_html_and_code_tag($var)
	{
		$var = str_replace("\n", '<br />', $var);
		return TextHelper::htmlspecialchars($var, ENT_NOQUOTES);
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

	private function unparse_img($matches)
	{
		$img_pathinfo = pathinfo($matches[1]);
		$file_array = explode('.', $img_pathinfo['filename']);
		$img_name = $file_array[0];
		$alt = !empty($matches[2]) ? $matches[2] : $img_name;
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
				}
			}

			if (!empty($style))
			{
				$style = ' style="' . $style . '"';
			}
		}
		return '<img src="' . $matches[1] . '" alt="' . $alt . '"' . $style . ' ' . $params . '/>';
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

	/**
	 * @desc Unparses the div tag to BBCode syntax (it doesn't exist
	 * in TinyMCE).
	 * @param string[] $matches The matched elements
	 * @return string The corresponding BBCode syntax
	 */
	private function unparse_container($matches)
	{
		$id = '';
		$class = '';
		$style = '';

		if (!empty($matches[1]))
		{
			$id = ' id="' . $matches[1] . '"';
		}

		if (!empty($matches[2]))
		{
			$class = ' class="' . $matches[2] . '"';
		}

		if (!empty($matches[3]))
		{
			$style = ' style="' . $matches[3] . '"';
		}

		if (!empty($id) || !empty($class) || !empty($style))
		{
			return '[container' . $id . $class . $style . ']' . $matches[4] . '[/container]';
		}
		else
		{
			return '[container]' . $matches[3] . '[/container]';
		}
	}
}
?>
