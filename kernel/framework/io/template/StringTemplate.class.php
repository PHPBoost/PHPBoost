<?php
/*##################################################
 *                        StringTemplate.class.php
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
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 * @desc This class enables you to handle a template whose input is not a file but directly a string.
 * To be always as efficient as possible, it uses cache if it evaluates that it could be faster.
 * But when string templates are cached, they are saved on the filesystem and use some disk space. It's the reason
 * why there is an option enabling to forbid it to cache a template if you think that it's not required to have
 * a big efficiency. It will be the case for instance when you know that a string template will be used only once a month.
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
	 * @desc Constructs a StringTemplate
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
			if (strlen($content) > 200)
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