<?php
/**
 * This class is a highlighter for the PHPBoost BBCode language.
 * It supplies the highlighted code written in XHTML.
 * @package     Content
 * @subpackage  Formatting\parser
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 01 13
 * @since       PHPBoost 2.0 - 2008 08 29
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor xela <xela@phpboost.com>
*/

class BBCodeHighlighter extends AbstractParser
{
	const BBCODE_HIGHLIGHTER_INLINE_CODE = true;
	const BBCODE_HIGHLIGHTER_BLOCK_CODE = false;
	/**
	 * @var string color of a bbcode tag
	 */
	private static $bbcode_tag_color = '#0000FF';
	/**
	 * @var string Color of a bbcode param
	 */
	private static $bbcode_param_color = '#7B00FF';
	/**
	 * @var string Color of a bbcode param name
	 */
	private static $bbcode_param_name_color = '#FF0000';
	/**
	 * @var string Color of the list tag
	 */
	private static $bbcode_list_item_color = '#00AF07';

	/**
	 * Builds a BBCodeHighlighter objet
	 */
	public function __construct()
	{
		//We call the parent constructor
		parent::__construct();
	}

	/**
	 * Highlights the content of the parser.
	 * @param bool $inline_code If you want that the code make a new HTML paragraph, use BBCODE_HIGHLIGHTER_BLOCK_CODE
	 * (default parameter) and if you want that it would be integrated in a line, use BBCODE_HIGHLIGHTER_INLINE_CODE
	 * @return void You can get the result by calling the get_content method
	 */
	public function parse($inline_code = self::BBCODE_HIGHLIGHTER_BLOCK_CODE)
	{
		//Protection of html code
		$this->content = TextHelper::htmlspecialchars($this->content);

		//Line tag
		$this->content = str_replace('[line]', '<span style="color:' . self::$bbcode_tag_color . ';">[line]</span>', $this->content);
		$this->content = str_replace('[*]', '<span style="color:' . self::$bbcode_list_item_color . ';">[*]</span>', $this->content);

		//Simple tags (whitout parameter)
		$simple_tags = array('b', 'i', 'u', 's', 'p', 'sup', 'sub', 'pre', 'math', 'quote', 'block', 'fieldset', 'sound', 'url', 'img', 'mail', 'code',  'tr', 'html', 'row', 'indent', 'hide', 'mail', 'fa', 'member', 'moderator', 'container');

		foreach ($simple_tags as $tag)
		{
			while (preg_match('`\[' . $tag . '\](.*)\[/' . $tag . '\]`isuU', $this->content))
			{
				$this->content = preg_replace('`\[' . $tag . '\](.*)\[/' . $tag . '\]`isuU', '<span style="color:' . self::$bbcode_tag_color . ';">/[/' . $tag . '/]/</span>$1<span style="color:' . self::$bbcode_tag_color . ';">/[//' . $tag . '/]/</span>', $this->content);
			}
		}

		//Tags which take a parameter : [tag=parameter]content[/tag]
		$tags_with_simple_property = array('img', 'color', 'bgcolor', 'size', 'font', 'align', 'float', 'anchor', 'acronym', 'title', 'stitle', 'style', 'url', 'mail', 'code', 'quote', 'movie', 'swf', 'youtube', 'fa');

		foreach ($tags_with_simple_property as $tag)
		{
			while (preg_match('`\[' . $tag . '=([^\]]+)\](.*)\[/' . $tag . '\]`isuU', $this->content))
			{
				$this->content = preg_replace('`\[' . $tag . '=([^\]]+)\](.*)\[/' . $tag . '\]`isuU', '<span style="color:' . self::$bbcode_tag_color . ';">/[/' . $tag . '</span>=<span style="color:' . self::$bbcode_param_color . ';">$1</span><span style="color:' . self::$bbcode_tag_color . ';">/]/</span>$2<span style="color:' . self::$bbcode_tag_color . ';">/[//' . $tag . '/]/</span>', $this->content);
			}
		}

		//Tags which take several parameters. The syntax is the same as XML parameters
		$tags_with_many_parameters = array('table', 'col', 'head', 'list', 'fieldset', 'block', 'wikipedia', 'container');

		foreach ($tags_with_many_parameters as $tag)
		{
			while (preg_match('`\[(' . $tag . ')([^\]]*)\](.*)\[/' . $tag . '\]`isuU', $this->content))
			{
				$this->content = preg_replace_callback('`\[(' . $tag . ')([^\]]*)\](.*)\[/' . $tag . '\]`isuU', array($this, 'highlight_bbcode_tag_with_many_parameters'), $this->content);
			}
		}

		if ($inline_code == self::BBCODE_HIGHLIGHTER_BLOCK_CODE)
		{
			$this->content = '<pre>' . $this->content . '</pre>';
		}
		else
		{
			$this->content = '<pre style="display:inline;">' . $this->content . '</pre>';
		}

		//Te be able to handle the nested tags, we replaced [ by /[/, we do the reverse replacement now
		$this->content = str_replace(array('/[/', '/]/'), array('[', ']'), $this->content);
	}

	/**
	 * Callback which highlights the parameters of a complex tag
	 * @param string[] $matches elements matched by the regular expression
	 * @return string The complex tag highlighted
	 */
	private function highlight_bbcode_tag_with_many_parameters($matches)
	{
		$content = $matches[3];
		$tag_name = $matches[1];

		$matches[2] = preg_replace('`([a-z]+)="([^"]*)"`isuU', '<span style="color:' . self::$bbcode_param_name_color . '">$1</span>=<span style="color:' . self::$bbcode_param_color . '">"$2"</span>', $matches[2]);

		return '<span style="color:' . self::$bbcode_tag_color . '">/[/' . $tag_name . '</span>' .$matches[2] . '<span style="color:' . self::$bbcode_tag_color . '">/]/</span>' . $content . '<span style="color:' . self::$bbcode_tag_color . '">/[//' . $tag_name . '/]/</span>';
	}
}
?>
