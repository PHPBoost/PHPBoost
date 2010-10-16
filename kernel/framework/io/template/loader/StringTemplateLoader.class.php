<?php
/*##################################################
 *                        StringTemplateLoader.class.php
 *                            -------------------
 *   begin                : February 6, 2010
 *   copyright            : (C) 2010 Benoit Sautel
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
 * @desc This loader is a very simple one. Its input is a string containing the template source.
 * It doesn't supports caching so it always parses the input source.
 * @author Benoit Sautel <ben.popeye@phpboost.com>
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
	 * @desc Constructs the {@link StringTemplateLoader} from the input source.
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