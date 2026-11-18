<?php
/**
 * This class is a default implementation of the Template interface using a TemplateLoader,
 * a TemplateData and a TemplateParser.
 * @package     IO
 * @subpackage  Template
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 18
 * @since       PHPBoost 3.0 - 2009 06 18
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      mipel <mipel@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

abstract class AbstractTemplate implements Template
{
	protected TemplateLoader $loader;
	protected TemplateRenderer $renderer;
	protected TemplateData $data;

	/**
	 * Builds an AbstractTemplate from the different services it has to use.
	 * @param TemplateLoader $loader The loader
	 * @param TemplateRenderer $renderer The renderer
	 * @param TemplateData $data The data
	 */
	public function __construct(TemplateLoader $loader, TemplateRenderer $renderer, TemplateData $data)
	{
		$this->set_loader($loader);
		$this->set_renderer($renderer);
		$this->set_data($data);
	}

	private function set_loader(TemplateLoader $loader): void
	{
		$this->loader = $loader;
	}

	private function set_renderer(TemplateRenderer $renderer): void
	{
		$this->renderer = $renderer;
	}

	public function enable_strict_mode(): void
	{
		$this->data->enable_strict_mode();
	}

	public function disable_strict_mode(): void
	{
		$this->data->disable_strict_mode();
	}

	public function put(string $key, mixed $value): static
	{
		$this->data->put($key, $value);
		return $this;
	}

	public function put_all(array $vars): static
	{
		$this->data->put_all($vars);
		return $this;
	}

	public function assign_vars(array $array_vars): static
	{
		$this->data->put_all($array_vars);
		return $this;
	}

	public function assign_block_vars(string $block_name, array $array_vars, array $subtemplates = []): static
	{
		$this->data->assign_block_vars($block_name, $array_vars, $subtemplates);
		return $this;
	}

	public function add_subtemplate(string $identifier, Template $template): static
	{
		$this->data->put($identifier, $template);
		return $this;
	}

	public function __clone(): void
	{
		$this->data = clone $this->data;
	}

	public function render(): string
	{
		return $this->renderer->render($this->data, $this->loader);
	}

	public function display(bool $reorder_js = false): void
	{
		if ($reorder_js)
        {
			$this->render_with_reordered_js();
        }
		else
        {
			echo $this->render();
        }
	}

	private function render_with_reordered_js(): void
	{
		$generated_page = $this->render();

		$js_variables_definition = $included_js = $all_js = '';
		$array_match_js = [];

		if (!preg_match('`post\.php|edit`', REWRITED_SCRIPT))
		{
			$array_match_js[] = '`<script(?: type="text/javascript")? src="([^"]*)"(?: type="text/javascript")?></script>`isU';
			$array_match_js[] = '`<script(?: type="text/javascript")?>(?:<!--)?(.*)(?:-->)?</script>`isU';

			preg_match_all($array_match_js[0], $generated_page, $matches);
			foreach ($matches[1] as $value)
            {
				$included_js .= '<script src="' . $value . '"></script>';
			}

			preg_match_all($array_match_js[1], $generated_page, $matches);
			foreach ($matches[1] as $key => $value)
            {
				if ($key == 0)
                {
					$js_variables_definition = $value;
                }
				else
                {
					$all_js .= $value;
                }
			}

			$all_js = str_replace(['<!--', '-->'], '', $all_js);
		}

		$generated_page = preg_replace($array_match_js, '', $generated_page);
		$generated_page = str_replace('</body>', '<script>' . $js_variables_definition . '</script>' . $included_js . '<script>' . $all_js . '</script></body>', $generated_page);

		// Minifying html
		$generated_page = trim(preg_replace(['`([\t]+|<!-- .*?-->)`s', '`(\r\n)+|(\n)+|\n// .*\n`su'], ['', "\n"], $generated_page));

		echo $generated_page;
	}

	public function add_lang(array $lang): void
	{
		$this->renderer->add_lang($lang);
	}

	public function set_data(TemplateData $data): void
	{
		$this->data = $data;
	}

	public function get_data(): TemplateData
	{
		return $this->data;
	}

	public function get_pictures_data_path(): string
    {
		return $this->loader->get_pictures_data_path();
	}
}
?>
