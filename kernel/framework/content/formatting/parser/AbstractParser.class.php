<?php
/**
 * This class is the basis of all the formatting processings that exist in PHPBoost.
 * @package     Content
 * @subpackage  Formatting\parser
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 11
 * @since       PHPBoost 2.0 - 2007 11 29
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

abstract class AbstractParser implements FormattingParser
{
	const PICK_UP = true;
	const REIMPLANT = false;

	protected mixed $content = '';
	/** List of the tags which have been picked up by the parser */
	protected array $array_tags = [];
	/** Path to root of the page in which has been written the content to parse. */
	protected string $path_to_root = PATH_TO_ROOT;
	/** Path of the page in which has been written the content to parse. */
	protected string $page_path = '';
	/** List of the tags to add from a module. Allows to add a tag [link] from pages or wiki from example */
	protected array $module_special_tags = [];

	/**
	 * Builds a Parser object.
	 */
	public function __construct()
	{
		$this->content = '';
		$this->page_path = $_SERVER['PHP_SELF'];
	}

	public function get_content(): mixed
	{
		return trim($this->content);
	}

	public function set_content(mixed $content): void
	{
		$this->content = $content;
	}

	public function set_path_to_root(string $path): void
	{
		$this->path_to_root = $path;
	}

	public function get_path_to_root(): string 
	{
		return $this->path_to_root;
	}

	public function set_page_path(string $page_path): void
	{
		$this->page_path = $page_path;
	}

	public function get_page_path(): string
	{
		return $this->page_path;
	}

	public function add_module_special_tag(string $pattern, string $replacement): void
	{
		$this->module_special_tags[$pattern] = $replacement;
	}

	public function get_module_special_tags(): array
	{
		return $this->module_special_tags;
	}

	/**
	 * Parses a nested tag
	 * @param string $match The regular expression which matches the tag to replace
	 * @param string $regex The regular expression which matches the replacement
	 * @param string $replace The replacement syntax.
	 */
	protected function _parse_imbricated(string $match, string $regex, string $replace): void
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
	 * @param array $allowed_args The args that are allowed to be present
	 */
	protected static function parse_tag_args(string $matches, array $allowed_args = []): array
	{
		$args = explode(' ', trim($matches));
		$result = [];
		foreach ($args as $arg)
		{
			$param = [];
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
