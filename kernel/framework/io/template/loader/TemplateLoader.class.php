<?php
/**
 * This class is responsible to load a template and transform it in the PHPBoost parsed template
 * syntax.
 * Normally a loader is able to load a template and provide its parsed form. The parsing is done by
 * a TemplateParser it embeds and is a quite heavy operation. To be more efficient, we've introduced
 * the parsed files caching which enables us to parse each file only once and then reuse the cache.
 * Even if the {@link TemplateLoader} interface knows the cache notion, that doesn't mean that the
 * implementation has to support caching. It's the reason why there is the {@link supports_caching()} method.
 * @package     IO
 * @subpackage  Template\loader
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2009 06 18
 * @contributor Benoit SAUTEL <ben.popeye@phpboost.com>
*/

interface TemplateLoader
{
	/**
	 * Loads the template.
	 * @return string Returns the parsed template ready to be executed by the PHP engine.
	 * @throws TemplateLoadingException If the template cannot been loaded
	 */
	function load();

	/**
	 * Tells whether the loader supports caching. If it supports it, its {@link get_cache_file_path()}
	 *  will have to return a non-empty value.
	 * @return boolean true if it supports caching, false otherwise.
	 */
	function supports_caching();

	/**
	 * Returns the path of the cache file that can be directly executed by the include PHP instruction.
	 * This method must be called only if the loader supports caching, the {@link supports_caching()}
	 * enabled you to know that.
	 * @return string The path of the cache file.
	 * @throws TemplateLoadingException If the template cannot been loaded or cached
	 */
	function get_cache_file_path();
}
?>
