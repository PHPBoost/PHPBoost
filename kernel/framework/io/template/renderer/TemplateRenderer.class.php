<?php
/*##################################################
 *                          TemplateRenderer.class.php
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
 * @desc Represents a template renderer as its names shows. Its able to get the result of the template
 * interpration from a TemplateLoader which gives it the template source and a TemplateData which 
 * contains the data to assign in the template.
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 *
 */
interface TemplateRenderer
{
	/**
	 * @desc Returns the result of the interpretation of a template
	 * @param TemplateData $data The data
	 * @param TemplateLoader $loader The loader to use
	 * @return string The parsed template
	 */
	function render(TemplateData $data, TemplateLoader $loader);

    /**
     * @desc Adds a lang map to the template map list in which template variables beginning by L_ will be searched for of not already registered
     * @param string[string] $lang the language map
     */
    function add_lang(array $lang);
}
?>