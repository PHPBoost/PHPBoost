<?php
/**
 * This loader is to use when you load a template whose source is directly a PHP string
 * and not a file. It supports caching and saves cache files in the /cache/tpl directory, using
 * a md5 hash to distinguish eache string input.
 * @package     IO
 * @subpackage  Template\loader
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 02 06
*/

class CachedStringTemplateLoader implements TemplateLoader
{
	private $content = '';
	private $cache_file_path = '';

	/**
	 * Constructs a {@link CachedStringTemplateLoader} from the source string.
	 * @param string $content The template's source as a string.
	 */
	public function __construct($content)
	{
		$this->content = $content;
		$this->compute_cache_file_path();
	}

	private function compute_cache_file_path()
	{
		$this->cache_file_path = PATH_TO_ROOT . '/cache/tpl/string-' . md5($this->content) . '.php';
	}

	/**
	 * {@inheritdoc}
	 */
	public function load()
	{
		if (!$this->file_cache_exists())
		{
			$content = $this->get_parsed_content();
			$this->generate_cache_file($content);
			return $content;
		}

		return file_get_contents($this->cache_file_path);
	}

	private function file_cache_exists()
	{
		return file_exists($this->cache_file_path) && @filesize($this->cache_file_path) !== 0;
	}

	private function generate_cache_file()
	{
		try
		{
			$cache_file = new File($this->cache_file_path);
			$cache_file->open(File::WRITE);
			$cache_file->lock();
			$cache_file->write($this->get_parsed_content());
			$cache_file->unlock();
			$cache_file->close();
			$cache_file->change_chmod(0666);
		}
		catch(IOException $ex)
		{
			throw new TemplateLoadingException('The template file cache couldn\'t been written due to this problem: ' . $ex->getMessage());
		}
	}

	private function get_parsed_content()
	{
		$parser = new TemplateSyntaxParser();
		return $parser->parse($this->content);
	}

	/**
	 * {@inheritdoc}
	 */
	public function supports_caching()
	{
		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_cache_file_path()
	{
		if (!$this->file_cache_exists())
		{
			$content = $this->get_parsed_content();
			$this->generate_cache_file($content);
		}
		return $this->cache_file_path;
	}
}
?>
