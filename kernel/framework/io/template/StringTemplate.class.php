<?php
/**
 * This class enables you to handle a template whose input is not a file but directly a string.
 * To be always as efficient as possible, it uses cache if it evaluates that it could be faster.
 * But when string templates are cached, they are saved on the filesystem and use some disk space. It's the reason
 * why there is an option enabling to forbid it to cache a template if you think that it's not required to have
 * a big efficiency. It will be the case for instance when you know that a string template will be used only once a month.
 * @package     IO
 * @subpackage  Template
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 10 28
 * @since       PHPBoost 3.0 - 2010 02 06
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class StringTemplate extends AbstractTemplate
{
	/**
	 * @var bool It chooses if it has to cache or not the string according to its length.
	 */
	const DONT_USE_CACHE = false;
	/**
	 * @var bool Forbids it to cache this string template.
	 */
	const USE_CACHE_IF_FASTER = true;

	/**
	 * Constructs a StringTemplate
	 * @param string $content The content of the template (a string containing PHPBoost's template engine syntax).
	 * @param bool $use_cache Controls if it has or not to use cache
	 * @param bool $auto_load_vars Tells whether it has to load or not the most common variables.
	 */
	public function __construct($content, $use_cache = self::USE_CACHE_IF_FASTER)
	{
		$data = new DefaultTemplateData();
		$data->auto_load_frequent_vars();
		$renderer = new DefaultTemplateRenderer();

		if ($this->has_to_cache($content, $use_cache))
		{
			$loader = new CachedStringTemplateLoader($content);
		}
		else
		{
			$loader = new StringTemplateLoader($content);
		}
		parent::__construct($loader, $renderer, $data);
	}

	private function has_to_cache($content, $use_cache)
	{
		if ($use_cache == self::DONT_USE_CACHE)
		{
			return false;
		}
		else
		{
			if (TextHelper::strlen($content) > 200)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	}
}
?>
