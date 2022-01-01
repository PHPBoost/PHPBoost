<?php
/**
 * This class is the basis of all the formatting processings that exist in PHPBoost.
 * @package     Content
 * @subpackage  Formatting\parser
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2019 11 14
 * @since       PHPBoost 2.0 - 2007 11 29
 * @contributor Loic ROUCHON <horn@phpboost.com>
 * @contributor Benoit SAUTEL <ben.popeye@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

abstract class AbstractParser implements FormattingParser
{
	const PICK_UP = true;
	const REIMPLANT = false;
	/**
	 * @var string Content of the parser
	 */
	protected $content = '';
	/**
	 * @var string[] List of the tags which have been picked up by the parser
	 */
	protected $array_tags = array();
	/**
	 * @var string Path to root of the page in which has been written the content to parse.
	 */
	protected $path_to_root = PATH_TO_ROOT;

	/**
	 * @var string Path of the page in which has been written the content to parse.
	 */
	protected $page_path = '';

	/**
	 * @var string[] List of the tags to add from a module. Allows to add a tag [link] from pages or wiki from example
	 */
	protected $module_special_tags = array();

	/**
	 * Builds a Parser object.
	 */
	public function __construct()
	{
		$this->content = '';
		$this->page_path = $_SERVER['PHP_SELF'];
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_content()
	{
		return trim($this->content);
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_content($content)
	{
		$this->content = $content;
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_path_to_root($path)
	{
		$this->path_to_root = $path;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_path_to_root()
	{
		return $this->path_to_root;
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_page_path($page_path)
	{
		$this->page_path = $page_path;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_page_path()
	{
		return $this->page_path;
	}

	/**
	 * {@inheritdoc}
	 */
	public function add_module_special_tag($pattern, $replacement)
	{
		$this->module_special_tags[$pattern] = $replacement;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_module_special_tags()
	{
		return $this->module_special_tags;
	}

	/**
	 * Parses a nested tag
	 * @param string $match The regular expression which matches the tag to replace
	 * @param string $regex The regular expression which matches the replacement
	 * @param string $replace The replacement syntax.
	 */
	protected function _parse_imbricated($match, $regex, $replace)
	{
		$nbr_match = TextHelper::substr_count($this->content, $match);
		for ($i = 0; $i < $nbr_match; $i++)
		{
			$this->content = preg_replace($regex, $replace, $this->content);
		}
	}
	
	/**
	 * Parses tag args to get allowed ones
	 * @param string $matches The regular expression which matches the tag args
	 * @param string $allowed_args The args that are allowed to be present
	 */
	protected static function parse_tag_args($matches, $allowed_args = array())
	{
		$args = explode(' ', trim($matches));
		$result = array();
		foreach ($args as $arg)
		{
			$param = array();
			if (!preg_match('`([a-z]+)="([^"]+)"`uU', $arg, $param))
			{
				break;
			}
			$name = $param[1];
			$value = $param[2];
			if (in_array($name, $allowed_args))
			{
				$result[$name] = $value;
			}
		}
		return $result;
	}
}
?>
