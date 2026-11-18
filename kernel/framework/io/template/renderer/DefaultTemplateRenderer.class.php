<?php
/**
 * This template renderer is able to deal with both loader which cache and don't cache.
 * When the loader supports caching, it includes the cached file to use APC's optimization, but
 * if it doesn't support it, it simply evals the template code.
 * @package     IO
 * @subpackage  Template\renderer
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 18
 * @since       PHPBoost 3.0 - 2010 02 06
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class DefaultTemplateRenderer implements TemplateRenderer
{
	private TemplateFunctions $functions;

	public function __construct()
	{
		$this->functions = new TemplateFunctions();
	}

	public function render(TemplateData $data, TemplateLoader $loader): string
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

    public function add_lang(array $lang): void
    {
		$this->functions->add_language_maps($lang);
    }

	private function parse(TemplateLoader $loader, TemplateData $data): string
	{
		$_result = '';
		$_functions = $this->functions;
		$_data = $data;
		include $loader->get_cache_file_path();
		return $_result;
	}

	private function execute(TemplateLoader $loader, TemplateData $data): string
	{
		$_result = '';
		$_functions = $this->functions;
		$_data = $data;
		eval($this->get_code_to_eval($loader));
		return $_result;
	}

	private function get_code_to_eval(TemplateLoader $loader): string
	{
		$template_code = $loader->load();
		// Removes the <?php and the ? > tags
		return TextHelper::substr($template_code, 6, TextHelper::strlen($template_code) - 9);
	}
}
?>
