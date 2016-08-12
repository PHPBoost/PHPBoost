<?php
/*##################################################
 *                          BBCodeUnparser.class.php
 *                            -------------------
 *   begin                : July 3 2008
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
 * @author Benoît Sautel <ben.popeye@phpboost.com>
 * @desc BBCode unparser. It converts a content using the PHPBoost HTML reference code (for example
 * coming from a database) to the PHPBoost BBCode syntax.
 */
class BBCodeUnparser extends ContentFormattingUnparser
{
	/**
	 * @desc Builds a BBCodeUnparser object
	 */
	public function __construct()
	{
		//We call the parent constructor
		parent::__construct();
	}

	/**
	 * @desc Unparses the content of the parser.
	 * Converts it from HTML syntax to BBcode syntax
	 */
	public function parse()
	{
		//Isolement du code source et du code HTML qui ne sera pas protégé
		$this->unparse_html(self::PICK_UP);
		$this->unparse_code(self::PICK_UP);
		
		$this->content = TextHelper::html_entity_decode($this->content);
		
		//Smilies
		$this->unparse_smilies();

		//Remplacement des balises simples
		$this->unparse_simple_tags();

		//Unparsage de la balise table.
		if (strpos($this->content, '<table class="formatter-table"') !== false)
		{
			$this->unparse_table();
		}

		//Unparsage de la balise table.
		if (strpos($this->content, '<li class="formatter-li"') !== false)
		{
			$this->unparse_list();
		}
		
		$this->unparse_code(self::REIMPLANT);
		$this->unparse_html(self::REIMPLANT);
	}

	/**
	 * @desc Unparse the smiley's code of the content of the parser.
	 * Replace the HTML code by the smiley code (for instance :) or :|)
	 */
	protected function unparse_smilies()
	{
		//Smilies
		$smileys_cache = SmileysCache::load()->get_smileys();
		if (!empty($smileys_cache))
		{
			//Création du tableau de remplacement
			foreach ($smileys_cache as $code => $infos)
			{
				$smiley_img_url[] = '`<img src="([^"]+)?/images/smileys/' . preg_quote($infos['url_smiley']) . '(.*) />`sU';
				$smiley_code[] = $code;
			}
			$this->content = preg_replace($smiley_img_url, $smiley_code, $this->content);
			foreach ($smileys_cache as $code => $infos)
			{
				$smiley_img_url[] = '`<img class="smiley" title="' . preg_quote($code) . '"(.*) />`sU';
				$smiley_code[] = $code;
			}
			$this->content = preg_replace($smiley_img_url, $smiley_code, $this->content);
		}
	}

	/**
	 * @desc Unparsed the simple tags of the content of the parser.
	 * The simple tags are the ones which are processable in a few lines
	 */
	protected function unparse_simple_tags()
	{
		$array_str = array(
			'<br />', '<strong>', '</strong>', '<em>', '</em>', '<strike>', '</strike>', '<s>', '</s>', '<sup>', '</sup>',
			'<sub>', '</sub>', '<pre>', '</pre>'
			);
		$array_str_replace = array(
			' ', '[b]', '[/b]', '[i]', '[/i]', '[s]', '[/s]', '[s]', '[/s]', '[sup]', '[/sup]', '[sub]', '[/sub]', '[pre]', '[/pre]'
		);
		$this->content = str_replace($array_str, $array_str_replace, $this->content);
		
		$array_preg = array(
			'`<span style="text-decoration: underline;">(.*)</span>`isU',
			'`<span style="color:([^;]+);">(.*)</span>`isU',
			'`<span style="background-color:([^;]+);">(.*)</span>`isU',
			'`<span style="font-size: ([0-9]+)px;">(.*)</span>`isU',
			'`<span style="font-family: ([ a-z0-9,_-]+);">(.*)</span>`isU',
			'`<p style="text-align:(left|center|right|justify)">(.*)</p>`isU',
			'`<p class="float-(left|right)">(.*)</p>`isU',
			'`<span id="([a-z0-9_-]+)" class="anchor"></span>`isU',
			'`<span id="([a-z0-9_-]+)">(.*)</span>`isU',
			'`<acronym title="([^"]+)" class="formatter-acronym">(.*)</acronym>`isU',
			'`<abbr title="([^"]+)">(.*)</abbr>`isU',
			'`<a href="mailto:(.*)">(.*)</a>`isU',
			'`<a(?: title="([^"]+)?")? href="([^"]+)"(?: target="([^"]+)")?>(.*)</a>`isU',
			'`<h1 class="formatter-title">(.*)</h1>`isU',
			'`<h2 class="formatter-title">(.*)</h2>`isU',
			'`<h3 class="formatter-title">(.*)</h3>`isU',
			'`<h4 class="formatter-title">(.*)</h4>`isU',
			'`<h5 class="formatter-title">(.*)</h5>`isU',
			'`<h6 class="formatter-title">(.*)</h6>`isU',
			'`<span class="(success|question|notice|warning|error)">(.*)</span>`isU',
			'`<hr(?: class="([^"]+)?")? />`isU',
			'`<audio controls><source src="(.*)" /></audio>`isU',
			'`<script><!--\s{1,5}insertSoundPlayer\("([^"]+)"\);\s{1,5}--></script>`sU',
			'`\[\[MEDIA\]\]insertSoundPlayer\(\'([^\']+)\'\);\[\[/MEDIA\]\]`sU',
			'`<object type="application/x-shockwave-flash" data="(?:\.\.)?/(?:kernel|includes)/data/movieplayer\.swf" width="([^"]+)" height="([^"]+)">(?:\s|(?:<br />))*<param name="FlashVars" value="flv=(.+)&width=[0-9]+&height=[0-9]+" />.*</object>`isU',
			'`<script><!--\s{1,5}insertMoviePlayer\("([^"]+)", (\d{1,3}), (\d{1,3})\);\s{1,5}--></script>`sU',
			'`\[\[MEDIA\]\]insertMoviePlayer\(\'([^\']+)\', (\d{1,3}), (\d{1,3})\);\[\[/MEDIA\]\]`sU',
			'`<object type="application/x-shockwave-flash" data="([^"]+)" width="([^"]+)" height="([^"]+)">(.*)</object>`isU',
			'`<script><!--\s{1,5}insertSwfPlayer\("([^"]+)", (\d{1,3}), (\d{1,3})\);\s{1,5}--></script>`sU',
			'`\[\[MEDIA\]\]insertSwfPlayer\(\'([^\']+)\', (\d{1,3}), (\d{1,3})\);\[\[/MEDIA\]\]`sU',
			'`\[\[MEDIA\]\]insertYoutubePlayer\(\'([^\']+)\', (\d{1,3}), (\d{1,3})\);\[\[/MEDIA\]\]`sU',
			'`\[\[MATH\]\](.+)\[\[/MATH\]\]`sU',
			'`<a href="([^"]+)" rel="lightbox\[2\]">(.*)</a>`isU',
			'`<a href="([^"]+)" data-lightbox="formatter">(.*)</a>`isU',
		);

		$array_preg_replace = array(
			"[u]$1[/u]",
			"[color=$1]$2[/color]",
			"[bgcolor=$1]$2[/bgcolor]",
			"[size=$1]$2[/size]",
			"[font=$1]$2[/font]",
			"[align=$1]$2[/align]",
			"[float=$1]$2[/float]",
			"[anchor=$1][/anchor]",
			"[anchor=$1]$2[/anchor]",
			"[acronym=$1]$2[/acronym]",
			"[acronym=$1]$2[/acronym]",
			"[mail=$1]$2[/mail]",
			"[url=$2]$4[/url]",
			"[title=1]$1[/title]",
			"[title=1]$1[/title]",
			"[title=2]$1[/title]",
			"[title=3]$1[/title]",
			"[title=4]$1[/title]",
			"[title=5]$1[/title]",
			"[style=$1]$2[/style]",
			"[line]",
			"[sound]$1[/sound]",
			"[sound]$1[/sound]",
			"[sound]$1[/sound]",
			"[movie=$1,$2]$3[/movie]",
			"[movie=$2,$3]$1[/movie]",
			"[movie=$2,$3]$1[/movie]",
			"[swf=$2,$3]$1[/swf]",
			"[swf=$2,$3]$1[/swf]",
			"[swf=$2,$3]$1[/swf]",
			"[youtube=$2,$3]$1[/youtube]",
			"[math]$1[/math]",
			"[lightbox=$1]$2[/lightbox]",
			"[lightbox=$1]$2[/lightbox]"
		);
		$this->content = preg_replace($array_preg, $array_preg_replace, $this->content);
			
		##Nested tags
		//Quotes
		$this->_parse_imbricated('<div class="formatter-container formatter-blockquote"><span class="formatter-title">', '`<div class="formatter-container formatter-blockquote"><span class="formatter-title">(.*) :</span><div class="formatter-content">(.*)</div></div>`isU', '[quote]$2[/quote]', $this->content);
		$this->_parse_imbricated('<div class="formatter-container formatter-blockquote"><span class="formatter-title title-perso">', '`<div class="formatter-container formatter-blockquote"><span class="formatter-title title-perso">(.*) :</span><div class="formatter-content">(.*)</div></div>`isU', '[quote=$1]$2[/quote]', $this->content);

		//Hidden bloc
		$this->_parse_imbricated('<div class="formatter-container formatter-hide" onclick="bb_hide(this)"><span class="formatter-title">', '`<div class="formatter-container formatter-hide" onclick="bb_hide\(this\)"><span class="formatter-title">(.*) :</span><div class="formatter-content">(.*)</div></div>`sU', '[hide]$2[/hide]', $this->content);
		$this->_parse_imbricated('<div class="formatter-container formatter-hide" onclick="bb_hide(this)"><span class="formatter-title title-perso">', '`<div class="formatter-container formatter-hide" onclick="bb_hide\(this\)"><span class="formatter-title title-perso">(.*) :</span><div class="formatter-content">(.*)</div></div>`sU', '[hide=$1]$2[/hide]', $this->content);

		//Block
		$this->_parse_imbricated('<div class="formatter-container formatter-block"', '`<div class="formatter-container formatter-block">(.+)</div>`sU', '[block]$1[/block]', $this->content);
		$this->_parse_imbricated('<div class="formatter-container formatter-block" style=', '`<div class="formatter-container formatter-block" style="([^"]+)">(.+)</div>`sU', '[block style="$1"]$2[/block]', $this->content);

		//Indentation
		$this->_parse_imbricated('<div class="indent">', '`<div class="indent">(.+)</div>`sU', '[indent]$1[/indent]', $this->content);
		
		##Callbacks
		//Image
		$this->content = preg_replace_callback('`<img src="([^"]+)"(?: alt="([^"]+)?")?(?: title="([^"]+)?")?(?: style="([^"]+)?")?(?: class="([^"]+)?")? />`iU', array($this, 'unparse_img'), $this->content);

		//Fieldset
		while (preg_match('`<fieldset class="formatter-container formatter-fieldset" style="([^"]*)"><legend>(.*)</legend><div class="formatter-content">(.+)</div></fieldset>`sU', $this->content))
		{
			$this->content = preg_replace_callback('`<fieldset class="formatter-container formatter-fieldset" style="([^"]*)"><legend>(.*)</legend><div class="formatter-content">(.+)</div></fieldset>`sU', array($this, 'unparse_fieldset'), $this->content);
		}

		//Wikipedia link
		$this->content = preg_replace_callback('`<a href="http://([a-z]+).wikipedia.org/wiki/([^"]+)" class="wikipedia-link">(.*)</a>`sU', array($this, 'unparse_wikipedia_link'), $this->content);
		
		// Feed
		$this->content = preg_replace('`\[\[FEED([^\]]*)\]\](.+)\[\[/FEED\]\]`U', '[feed$1]$2[/feed]', $this->content);
	}

	private function unparse_img($matches)
	{
		$alt = !empty($matches[2]) ? ' alt="' . $matches[2] . '"' : '';
		$title = !empty($matches[3]) ? ' title="' . $matches[3] . '"' : '';
		$style = !empty($matches[4]) ? ' style="' . $matches[4] . '"' : '';
		$class = !empty($matches[5]) ? ' class="' . $matches[5] . '"' : '';

		return '[img' . $alt . $title . $style . $class. ']' . $matches[1] . '[/img]';
	}

	/**
	 * @desc Unparses the table tag
	 */
	protected function unparse_table()
	{
		//On boucle pour parcourir toutes les imbrications
		while (strpos($this->content, '<table class="formatter-table"') !== false)
		{
			$this->content = preg_replace('`<table class="formatter-table"([^>]*)>(.*)</table>`sU', '[table$1]$2[/table]', $this->content);
		}
		while (strpos($this->content, '<tr class="formatter-table-row"') !== false)
		{
			$this->content = preg_replace('`<tr class="formatter-table-row"([^>]*)>(.*)</tr>`sU', '[row$1]$2[/row]', $this->content);
		}
		while (strpos($this->content, '<th class="formatter-table-head"') !== false)
		{
			$this->content = preg_replace('`<th class="formatter-table-head"([^>]*)>(.*)</th>`sU', '[head$1]$2[/head]', $this->content);
		}
		while (strpos($this->content, '<td class="formatter-table-col"') !== false)
		{
			$this->content = preg_replace('`<td class="formatter-table-col"([^>]*)>(.*)</td>`sU', '[col$1]$2[/col]', $this->content);
		}
	}

	/**
	 * @desc Unparses the list tag
	 */
	protected function unparse_list()
	{
		//On boucle tant qu'il y a de l'imbrication
		while (strpos($this->content, '<ul class="formatter-ul">') !== false)
		{
			$this->content = preg_replace('`<ul( style="[^"]+")? class="formatter-ul">(.*)</ul>`sU', '[list$1]$2[/list]', $this->content);
		}
		while (strpos($this->content, '<ol class="formatter-ol">') !== false)
		{
			$this->content = preg_replace('`<ol( style="[^"]+")? class="formatter-ol">(.*)</ol>`sU', '[list=ordered$1]$2[/list]', $this->content);
		}
		while (strpos($this->content, '<li class="formatter-li">') !== false)
		{
			$this->content = preg_replace('`<li class="formatter-li">(.*)</li>`isU', '[*]$1', $this->content);
		}
	}

	/**
	 * @desc Callback which allows to unparse the fieldset tag
	 * @param string[] $matches Content matched by a regular expression
	 * @return string The string in which the fieldset tag are parsed
	 */
	protected function unparse_fieldset($matches)
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
	 * @desc Callback which allows to unparse the Wikipedia tag
	 * @param string[] $matches Content matched by a regular expression
	 * @return string The string in which the wikipedia tag are parsed
	 */
	protected function unparse_wikipedia_link($matches)
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
}
?>
