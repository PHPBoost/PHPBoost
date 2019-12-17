<?php
/**
 * BBCode unparser. It converts a content using the PHPBoost HTML reference code (for example
 * coming from a database) to the PHPBoost BBCode syntax.
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 11 19
 * @since       PHPBoost 5.1 - 2017 03 09
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class OldBBCodeUnparser extends ContentFormattingUnparser
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

		//Module eventual special tags replacement
		$this->unparse_module_special_tags();

		//Remplacement des balises simples
		$this->unparse_simple_tags();

		//Unparsage de la balise table.
		if (TextHelper::strpos($this->content, '<table class="formatter-table"') !== false)
		{
			$this->unparse_table();
		}

		//Unparsage de la balise table.
		if (TextHelper::strpos($this->content, '<li class="formatter-li"') !== false)
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
				$smiley_img_url[] = '`<img src="([^"]+)?/images/smileys/' . preg_quote($infos['url_smiley']) . '(.*) />`suU';
				$smiley_code[] = $code;
			}
			$this->content = preg_replace($smiley_img_url, $smiley_code, $this->content);
			foreach ($smileys_cache as $code => $infos)
			{
				$smiley_img_url[] = '`<img class="smiley" title="' . preg_quote($code) . '"(.*) />`suU';
				$smiley_code[] = $code;
			}
			$this->content = preg_replace($smiley_img_url, $smiley_code, $this->content);
		}
	}

	/**
	 * @desc Unparsed module special tags if any.
	 * The special tags are [link] for module pages or wiki for example.
	 */
	protected function unparse_module_special_tags()
	{
		foreach ($this->get_module_special_tags() as $pattern => $replacement)
			$this->content = preg_replace($pattern, $replacement, $this->content);
	}

	/**
	 * @desc Unparsed the simple tags of the content of the parser.
	 * The simple tags are the ones which are processable in a few lines
	 */
	protected function unparse_simple_tags()
	{
		$array_preg = array(
			'`<span style="text-decoration: ?underline;?">(.*)</span>`isuU',
			'`<span style="color: ?([^;]+);">(.*)</span>`isuU',
			'`<span style="background-color: ?([^;]+);">(.*)</span>`isuU',
			'`<span style="font-size: ?([0-9]+)px;?">(.*)</span>`isuU',
			'`<span style="font-family: ([ a-z0-9,_-]+);?">(.*)</span>`isuU',
			'`<p style="text-align: ?(left|center|right|justify);?">(.*)</p>`isuU',
			'`<p class="float-(left|right)">(.*)</p>`isuU',
			'`<span id="([a-z0-9_-]+)" class="anchor"></span>`isuU',
			'`<span id="([a-z0-9_-]+)">(.*)</span>`isuU',
			'`<acronym class="formatter-acronym">(.*)</acronym>`isuU',
			'`<acronym title="([^"]+)?" class="formatter-acronym">(.*)</acronym>`isuU',
			'`<abbr class="formatter-abbr">(.*)</abbr>`isuU',
			'`<abbr title="([^"]+)?" class="formatter-abbr">(.*)</abbr>`isuU',
			'`<a href="mailto:(.*)">(.*)</a>`isuU',
			'`<a(?: title="([^"]+)?")? href="([^"]+)"(?: target="([^"]+)")?>(.*)</a>`isuU',
			'`<h1 class="formatter-title">(.*)</h1>`isuU',
			'`<h2 class="formatter-title">(.*)</h2>`isuU',
			'`<h3 class="formatter-title">(.*)</h3>`isuU',
			'`<h4 class="formatter-title">(.*)</h4>`isuU',
			'`<h5 class="formatter-title">(.*)</h5>`isuU',
			'`<h6 class="formatter-title">(.*)</h6>`isuU',
			'`<span class="message-helper (success|question|notice|warning|error)">(.*)</span>`isuU',
			'`<hr(?: class="([^"]+)?")? />`isuU',
			'`<audio controls><source src="(.*)" /></audio>`isuU',
			'`<script><!--\s{1,5}insertSoundPlayer\("([^"]+)"\);\s{1,5}--></script>`suU',
			'`\[\[MEDIA\]\]insertSoundPlayer\(\'([^\']+)\'\);\[\[/MEDIA\]\]`suU',
			'`<script><!--\s{1,5}insertMoviePlayer\("([^"]+)", (\d{1,3}), (\d{1,3})\);\s{1,5}--></script>`suU',
			'`\[\[MEDIA\]\]insertMoviePlayer\(\'([^\']+)\', (\d{1,3}), (\d{1,3})\);\[\[/MEDIA\]\]`suU',
			'`\[\[MEDIA\]\]insertMoviePlayer\(\'([^\']+)\', (\d{1,3}), (\d{1,3}), \'([^\']+)\'\);\[\[/MEDIA\]\]`suU',
			'`<object type="application/x-shockwave-flash" data="([^"]+)" width="([^"]+)" height="([^"]+)">(.*)</object>`isuU',
			'`<script><!--\s{1,5}insertSwfPlayer\("([^"]+)", (\d{1,3}), (\d{1,3})\);\s{1,5}--></script>`suU',
			'`\[\[MEDIA\]\]insertSwfPlayer\(\'([^\']+)\', (\d{1,3}), (\d{1,3})\);\[\[/MEDIA\]\]`suU',
			'`\[\[MEDIA\]\]insertYoutubePlayer\(\'([^\']+)\', (\d{1,3}), (\d{1,3})\);\[\[/MEDIA\]\]`suU',
			'`\[\[MATH\]\](.+)\[\[/MATH\]\]`suU',
			'`<a href="([^"]+)" rel="lightbox\[2\]"(?: class="formatter-lightbox")?>(.*)</a>`isuU',
			'`<a href="([^"]+)" data-lightbox="formatter"(?: class="formatter-lightbox")?>(.*)</a>`isuU',
			'`\[\[MEMBER\]\](.+)\[\[/MEMBER\]\]`suU',
			'`\[\[MODERATOR\]\](.+)\[\[/MODERATOR\]\]`suU',
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
			"[abbr]$1[/abbr]",
			"[abbr=$1]$2[/abbr]",
			"[abbr]$1[/abbr]",
			"[abbr=$1]$2[/abbr]",
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
			"[movie=$2,$3]$1[/movie]",
			"[movie=$2,$3]$1[/movie]",
			"[movie=$2,$3,$4]$1[/movie]",
			"[movie=$2,$3]$1[/movie]",
			"[movie=$2,$3]$1[/movie]",
			"[movie=$2,$3]$1[/movie]",
			"[movie=$2,$3]$1[/movie]",
			"[math]$1[/math]",
			"[lightbox=$1]$2[/lightbox]",
			"[lightbox=$1]$2[/lightbox]",
			"[member]$1[/member]",
			"[moderator]$1[/moderator]",
		);
		$this->content = preg_replace($array_preg, $array_preg_replace, $this->content);

		$array_str = array(
			'<br />', '<strong>', '</strong>', '<em>', '</em>', '<strike>', '</strike>', '<s>', '</s>', '<p>', '</p>', '<sup>', '</sup>',
			'<sub>', '</sub>', '<pre>', '</pre>'
			);
		$array_str_replace = array(
			'&#13;', '[b]', '[/b]', '[i]', '[/i]', '[s]', '[/s]', '[s]', '[/s]',  '[p]', '[/p]', '[sup]', '[/sup]', '[sub]', '[/sub]', '[pre]', '[/pre]'
		);
		$this->content = str_replace($array_str, $array_str_replace, $this->content);

		##Nested tags
		//Quotes
		$this->_parse_imbricated('<div class="formatter-container formatter-blockquote"><span class="formatter-title">', '`<div class="formatter-container formatter-blockquote"><span class="formatter-title">(.*) :</span><div class="formatter-content">(.*)</div></div>`isuU', '[quote]$2[/quote]', $this->content);
		$this->_parse_imbricated('<div class="formatter-container formatter-blockquote"><span class="formatter-title title-perso">', '`<div class="formatter-container formatter-blockquote"><span class="formatter-title title-perso">(.*) :</span><div class="formatter-content">(.*)</div></div>`isuU', '[quote=$1]$2[/quote]', $this->content);

		//Hidden bloc
		$this->_parse_imbricated('<div class="formatter-container formatter-hide no-js"><span class="formatter-title">', '`<div class="formatter-container formatter-hide no-js"><span class="formatter-title">(.*) :</span><div class="formatter-content">(.*)</div></div>`suU', '[hide]$2[/hide]', $this->content);
		$this->_parse_imbricated('<div class="formatter-container formatter-hide no-js"><span class="formatter-title title-perso">', '`<div class="formatter-container formatter-hide no-js"><span class="formatter-title title-perso">(.*) :</span><div class="formatter-content">(.*)</div></div>`suU', '[hide=$1]$2[/hide]', $this->content);

		//Block
		$this->_parse_imbricated('<div class="formatter-container formatter-block"', '`<div class="formatter-container formatter-block">(.+)</div>`suU', '[block]$1[/block]', $this->content);
		$this->_parse_imbricated('<div class="formatter-container formatter-block" style=', '`<div class="formatter-container formatter-block" style="([^"]+)">(.+)</div>`suU', '[block style="$1"]$2[/block]', $this->content);

		//Container
		while (preg_match('`<div id="([^"]*)" class="([^"]*)" style="([^"]*)">(.+)</div>`suU', $this->content))
		{
			$this->content = preg_replace_callback('`<div id="([^"]*)" class="([^"]*)" style="([^"]*)">(.+)</div>`suU', array($this, 'unparse_container'), $this->content);
		}

		##Callbacks
		//Image
		$this->content = preg_replace_callback('`<img src="([^"]+)"(?: alt="([^"]+)?")?(?: title="([^"]+)?")?(?: style="([^"]+)?")?(?: class="([^"]+)?")? />`iuU', array($this, 'unparse_img'), $this->content);

		//FA Icon
		$this->content = preg_replace_callback('`<i class="fa([blrs])? fa-([a-z0-9-]+)( [a-z0-9- ]+)?"(?: aria-hidden="true" title="([^"]+)?")?></i>`iuU', array($this, 'unparse_fa'), $this->content);

		//Fieldset
		while (preg_match('`<fieldset class="formatter-container formatter-fieldset" style="([^"]*)"><legend>(.*)</legend><div class="formatter-content">(.+)</div></fieldset>`suU', $this->content))
		{
			$this->content = preg_replace_callback('`<fieldset class="formatter-container formatter-fieldset" style="([^"]*)"><legend>(.*)</legend><div class="formatter-content">(.+)</div></fieldset>`suU', array($this, 'unparse_fieldset'), $this->content);
		}

		//Wikipedia link
		$this->content = preg_replace_callback('`<a href="http://([a-z]+).wikipedia.org/wiki/([^"]+)" class="wikipedia-link">(.*)</a>`suU', array($this, 'unparse_wikipedia_link'), $this->content);

		//Indentation
		$this->_parse_imbricated('<div class="indent">', '`<div class="indent">(.+)</div>`suU', '[indent]$1[/indent]', $this->content);

		// Feed
		$this->content = preg_replace('`\[\[FEED([^\]]*)\]\](.+)\[\[/FEED\]\]`uU', '[feed$1]$2[/feed]', $this->content);
	}

	private function unparse_img($matches)
	{
		$img_pathinfo = pathinfo($matches[1]);
		$file_array = explode('.', $img_pathinfo['filename']);
		$img_name = $file_array[0];
		$alt = !empty($matches[2]) && $matches[2] != $img_name ? ' alt="' . $matches[2] . '"' : '';
		$title = !empty($matches[3]) && $matches[3] != $img_name ? ' title="' . $matches[3] . '"' : '';
		$style = !empty($matches[4]) ? ' style="' . $matches[4] . '"' : '';
		$class = !empty($matches[5]) ? ' class="' . $matches[5] . '"' : '';

		return '[img' . $alt . $title . $style . $class . ']' . $matches[1] . '[/img]';
	}

	private function unparse_fa($matches)
	{
		$fa_code = "";
		$special_fa = in_array($matches[1], array('b', 'l', 'r', 's'));
		$options_list = isset($matches[3]) ? $matches[3] : '';

		if ( !empty($options_list) ) {
			$options = explode(' ', $options_list);
			foreach ($options as $index => $option) {
				if (!empty($option)) {
					if ( $index == 1 && empty($fa_code) ) {
						$fa_code = "=" . ($special_fa ? 'fa' . $matches[1] . ',' : '') . $option;
					} else {
						if ( !empty($fa_code) ) { $fa_code = $fa_code . ","; }
						$fa_code = $fa_code . $option;
					}
				}
			}
		}

		return '[fa' . $fa_code . ']' . $matches[2] . '[/fa]';
	}

	/**
	 * @desc Unparses the table tag
	 */
	protected function unparse_table()
	{
		//On boucle pour parcourir toutes les imbrications
		while (TextHelper::strpos($this->content, '<table class="formatter-table"') !== false)
		{
			$this->content = preg_replace('`<table class="formatter-table"([^>]*)>(.*)</table>`suU', '[table$1]$2[/table]', $this->content);
		}
		while (TextHelper::strpos($this->content, '<tr class="formatter-table-row"') !== false)
		{
			$this->content = preg_replace('`<tr class="formatter-table-row"([^>]*)>(.*)</tr>`suU', '[row$1]$2[/row]', $this->content);
		}
		while (TextHelper::strpos($this->content, '<th class="formatter-table-head"') !== false)
		{
			$this->content = preg_replace('`<th class="formatter-table-head"([^>]*)>(.*)</th>`suU', '[head$1]$2[/head]', $this->content);
		}
		while (TextHelper::strpos($this->content, '<td class="formatter-table-col"') !== false)
		{
			$this->content = preg_replace('`<td class="formatter-table-col"([^>]*)>(.*)</td>`suU', '[col$1]$2[/col]', $this->content);
		}
	}

	/**
	 * @desc Unparses the list tag
	 */
	protected function unparse_list()
	{
		//On boucle tant qu'il y a de l'imbrication
		while (TextHelper::strpos($this->content, '<ul class="formatter-ul">') !== false)
		{
			$this->content = preg_replace('`<ul( style="[^"]+")? class="formatter-ul">(.*)</ul>`suU', '[list$1]$2[/list]', $this->content);
		}
		while (TextHelper::strpos($this->content, '<ol class="formatter-ol">') !== false)
		{
			$this->content = preg_replace('`<ol( style="[^"]+")? class="formatter-ol">(.*)</ol>`suU', '[list=ordered$1]$2[/list]', $this->content);
		}
		while (TextHelper::strpos($this->content, '<li class="formatter-li">') !== false)
		{
			$this->content = preg_replace('`<li class="formatter-li">(.*)</li>`isuU', '[*]$1', $this->content);
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

	/**
	 * @desc Callback which allows to unparse the div tag
	 * @param string[] $matches Content matched by a regular expression
	 * @return string The string in which the fieldset tag are parsed
	 */
	protected function unparse_container($matches)
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
			return '[container]' . $matches[4] . '[/container]';
		}
	}
}
?>
