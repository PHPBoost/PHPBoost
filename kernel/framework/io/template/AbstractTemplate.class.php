<?php
/**
 * This class is a default implementation of the Template interface using a TemplateLoader,
 * a TemplateData and a TemplateParser.
 * @package     IO
 * @subpackage  Template
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 11 14
 * @since       PHPBoost 3.0 - 2009 06 18
 * @contributor Regis VIARRE <crowkait@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

abstract class AbstractTemplate implements Template
{
	/**
	 * @var TemplateLoader
	 */
	protected $loader;
	/**
	 * @var TemplateRenderer
	 */
	protected $renderer;
	/**
	 * @var TemplateData
	 */
	protected $data;

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

	private function set_loader(TemplateLoader $loader)
	{
		$this->loader = $loader;
	}

	private function set_renderer(TemplateRenderer $renderer)
	{
		$this->renderer = $renderer;
	}

	/**
	 * {@inheritdoc}
	 */
	public function enable_strict_mode()
	{
		$this->data->enable_strict_mode();
	}

	/**
	 * {@inheritdoc}
	 */
	public function disable_strict_mode()
	{
		$this->data->disable_strict_mode();
	}

	/**
	 * {@inheritdoc}
	 */
	public function put($key, $value)
	{
		$this->data->put($key, $value);
		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function put_all(array $vars)
	{
		$this->data->put_all($vars);
		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function assign_vars(array $array_vars)
	{
		$this->data->put_all($array_vars);
		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function assign_block_vars($block_name, array $array_vars, array $subtemplates = array())
	{
		$this->data->assign_block_vars($block_name, $array_vars, $subtemplates);
		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function add_subtemplate($identifier, Template $template)
	{
		$this->data->put($identifier, $template);
		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function __clone()
	{
		$this->data = clone $this->data;
	}

	/**
	 * {@inheritdoc}
	 */
	public function render()
	{
		return $this->renderer->render($this->data, $this->loader);
	}

	/**
	 * {@inheritdoc}
	 */
	public function display($reorder_js = false)
	{
		if ($reorder_js)
			$this->render_with_reordered_js();
		else
			echo $this->render();
	}

	/**
	 * {@inheritdoc}
	 */
	private function render_with_reordered_js()
	{
		$generated_page = $this->render();

		$js_variables_definition = $included_js = $all_js = '';
		$array_match_js = array();

		if (!preg_match('`post\.php|edit`', REWRITED_SCRIPT))
		{
			$array_match_js[] = '`<script(?: type="text/javascript")? src="([^"]*)"(?: type="text/javascript")?></script>`isU';
			$array_match_js[] = '`<script(?: type="text/javascript")?>(?:<!--)?(.*)(?:-->)?</script>`isU';

			preg_match_all($array_match_js[0], $generated_page, $matches);
			foreach ($matches[1] as $value) {
				$included_js .= '<script src="' . $value . '"></script>';
			}

			preg_match_all($array_match_js[1], $generated_page, $matches);
			foreach ($matches[1] as $key => $value) {
				if ($key == 0)
					$js_variables_definition = $value;
				else
					$all_js .= $value;
			}

			$all_js = str_replace(array('<!--', '-->'), '', $all_js);
		}

		$generated_page = preg_replace($array_match_js, '', $generated_page);
		$generated_page = str_replace('</body>', '<script>' . $js_variables_definition . '</script>' . $included_js . '<script>' . $all_js . '</script></body>', $generated_page);

		// Minifying html
		$generated_page = trim(preg_replace(array('`([\t]+|<!-- .*?-->)`s', '`(\r\n)+|(\n)+|\n// .*\n`su'), array('', "\n"), $generated_page));

		echo $generated_page;
	}

	/**
	 * {@inheritdoc}
	 */
	public function add_lang(array $lang)
	{
		$this->renderer->add_lang($lang);
	}

	/**
	 * {@inheritdoc}
	 */
	public function set_data(TemplateData $data)
	{
		$this->data = $data;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_data()
	{
		return $this->data;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_pictures_data_path() {
		return $this->loader->get_pictures_data_path();
	}
}
?>
