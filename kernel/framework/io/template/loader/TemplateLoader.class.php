<?php
/*##################################################
 *                          TemplateLoader.class.php
 *                            -------------------
 *   begin                : June 18 2009
 *   copyright            : (C) 2009 Loic Rouchon, Benoit Sautel
 *   email                : loic.rouchon@phpboost.com, ben.popeye@phpboost.com
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
 * @desc This class is responsible to load a template and transform it in the PHPBoost parsed template
 * syntax.
 * Normally a loader is able to load a template and provide its parsed form. The parsing is done by 
 * a TemplateParser it embeds and is a quite heavy operation. To be more efficient, we've introduced 
 * the parsed files caching which enables us to parse each file only once and then reuse the cache.
 * Even if the {@link TemplateLoader} interface knows the cache notion, that doesn't mean that the 
 * implementation has to support caching. It's the reason why there is the {@link supports_caching()} method.  
 * @author Benoit Sautel <ben.popeye@phpboost.com>, Loic Rouchon <loic.rouchon@phpboost.com>
 */
interface TemplateLoader
{
	/**
	 * @desc Loads the template.
	 * @return string Returns the parsed template ready to be executed by the PHP engine.
	 * @throws TemplateLoadingException If the template cannot been loaded
	 */
	function load();
	
	/**
	 * @desc Tells whether the loader supports caching. If it supports it, its {@link get_cache_file_path()}
	 *  will have to return a non-empty value.
	 * @return boolean true if it supports caching, false otherwise.
	 */
	function supports_caching();
	
	/**
	 * @desc Returns the path of the cache file that can be directly executed by the include PHP instruction.
	 * This method must be called only if the loader supports caching, the {@link supports_caching()}
	 * enabled you to know that.
	 * @return string The path of the cache file.
	 * @throws TemplateLoadingException If the template cannot been loaded or cached
	 */
	function get_cache_file_path();
}
?>