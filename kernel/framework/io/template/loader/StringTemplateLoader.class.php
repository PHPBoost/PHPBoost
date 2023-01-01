<?php
/**
 * This loader is a very simple one. Its input is a string containing the template source.
 * It doesn't supports caching so it always parses the input source.
 * @package     IO
 * @subpackage  Template\loader
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 02 06
*/

class StringTemplateLoader implements TemplateLoader
{
	private $content = '';
	private $hashed_content;
	/**
	 * @var DataStore
	 */
	private static $parsing_cache = null;

	public static function __static()
	{
		self::$parsing_cache = new RAMDataStore();
	}

	/**
	 * Constructs the {@link StringTemplateLoader} from the input source.
	 * @param string $content The input source
	 */
	public function __construct($content)
	{
		$this->content = $content;
		$this->hashed_content = md5($content);
	}

	/**
	 * {@inheritdoc}
	 */
	public function load()
	{
		if (self::is_cached($this->hashed_content))
		{
			return self::get_cached_template($this->hashed_content);
		}
		else
		{
			$parser = new TemplateSyntaxParser();
			$parsed_content = $parser->parse($this->content);
			self::register_cached_template($this->hashed_content, $parsed_content);
			return $parsed_content;
		}
	}

	private static function is_cached($hashed_content)
	{
		return self::$parsing_cache->contains($hashed_content);
	}

	private static function get_cached_template($hashed_content)
	{
		return self::$parsing_cache->get($hashed_content);
	}

	private static function register_cached_template($hashed_content, $parsed_content)
	{
		self::$parsing_cache->store($hashed_content, $parsed_content);
	}

	/**
	 * {@inheritdoc}
	 */
	public function supports_caching()
	{
		return false;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_cache_file_path()
	{
		return null;
	}
}

?>
