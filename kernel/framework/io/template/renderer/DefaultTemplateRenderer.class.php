<?php
/**
 * This template renderer is able to deal with both loader which cache and don't cache.
 * When the loader supports caching, it includes the cached file to use APC's optimization, but
 * if it doesn't support it, it simply evals the template code.
 * @package     IO
 * @subpackage  Template\renderer
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 10 28
 * @since       PHPBoost 3.0 - 2010 02 06
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
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
		return TextHelper::substr($template_code, 6, TextHelper::strlen($template_code) - 9);
	}
}


?>
