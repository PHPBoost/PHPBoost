<?php
/*##################################################
 *                     DefaultTemplateRenderer.class.php
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
 * @desc This template renderer is able to deal with both loader which cache and don't cache.
 * When the loader supports caching, it includes the cached file to use APC's optimization, but
 * if it doesn't support it, it simply evals the template code.
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 */
class DefaultTemplateRenderer implements TemplateRenderer
{
	/**
	 * @var TemplateFunctions
	 */
	private $functions;

	public function __construct()
	{
		$this->functions = new TemplateFunctions();
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function render(TemplateData $data, TemplateLoader $loader)
	{
		if ($loader->supports_caching())
		{
			return $this->parse($loader, $data);
		}
		else
		{
			return $this->execute($loader, $data);
		}
	}

    /**
     * {@inheritDoc}
     */
    public function add_lang(array $lang)
    {
    	$this->functions->add_language_maps($lang);
    }

	private function parse(TemplateLoader $loader, TemplateData $data)
	{
		$_result = '';
		$_functions = $this->functions;
		$_data = $data;
		include $loader->get_cache_file_path();
		return $_result;
	}

	private function execute(TemplateLoader $loader, TemplateData $data)
	{
		$_result = '';
		$_functions = $this->functions;
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
