<?php
/*##################################################
 *                   FileIncludeTemplateRenderer.class.php
 *                            -------------------
 *   begin                : February 10, 2010
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
 * @desc This template renderer uses the fact that APC can optimize a PHP file inclusion, it's 
 * the fastest way to render a template that is cached on the filesystem. This last constraint 
 * means that the TemplateLoader it's gonna work with is a 
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 */
class FileIncludeTemplateRenderer implements TemplateRenderer
{
	/**
	 * (non-PHPdoc)
	 * @see kernel/framework/io/template/renderer/TemplateRenderer#render($data, $loader)
	 */
	public function render(TemplateData $data, TemplateLoader $loader)
	{
		if (!$loader instanceof CacherTemplateLoader)
		{
			throw new TemplateLoaderException('A FileIncludeTemplateRenderer must work with an instance of the CacherTemplateLoader interface');
		}
		
		$_data = $data;

		include $loader->get_cache_file_path();
		
		return $_result;
	}
}


?>