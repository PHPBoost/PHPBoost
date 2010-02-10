<?php
/*##################################################
 *                       EvalTemplateRenderer.class.php
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
 * @desc This template renderer is an universal one which can work in any situation, but under
 * certain conditions it exists more efficient ways to render a template.
 * This one asks to the loader the template under its PHP parsed form and executes this code.
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 */
class EvalTemplateRenderer implements TemplateRenderer
{
	/**
	 * (non-PHPdoc)
	 * @see kernel/framework/io/template/renderer/TemplateRenderer#render($data, $loader)
	 */
	public function render(TemplateData $data, TemplateLoader $loader)
	{
		$_data = $data;

		eval($this->get_code_to_eval($loader));
		return $_result;
	}
	
	private function get_code_to_eval(TemplateLoader $loader)
	{
		$template_code = $loader->load();
		// Removes the <?php and the ? > tags
		return substr($template_code, 6, strlen($template_code) - 9);
	}
}


?>