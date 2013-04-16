<?php
/*##################################################
 *                        AbstractTemplate.class.php
 *                            -------------------
 *   begin                : June 18 2009
 *   copyright            : (C) 2009 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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
 * @author Loic Rouchon <loic.rouchon@phpboost.com> Régis Viarre <crowkait@phpboost.com>
 * @desc This class is a default implementation of the Template interface using a TemplateLoader,
 * a TemplateData and a TemplateParser.
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
	 * @desc Builds an AbstractTemplate from the different services it has to use.
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
	public function display()
	{
		echo $this->render();
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