<?php
/*##################################################
 *                        AbstractTemplate.class.php
 *                            -------------------
 *   begin                : June 18 2009
 *   copyright            : (C) 2009 Loïc Rouchon
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
 * @package io
 * @subpackage template
 * @author Loïc Rouchon <loic.rouchon@phpboost.com> Régis Viarre <crowkait@phpboost.com>
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

	protected function set_loader(TemplateLoader $loader)
	{
		$this->loader = $loader;
	}

	protected function set_renderer(TemplateRenderer $renderer)
	{
		$this->renderer = $renderer;
	}

	protected function set_data(TemplateData $data)
	{
		$this->data = $data;
	}

	/**
	 * (non-PHPdoc)
	 * @see kernel/framework/io/template/Template#assign_vars($array_vars)
	 */
	public function assign_vars(array $array_vars)
	{
		$this->data->assign_vars($array_vars);
	}

	/**
	 * (non-PHPdoc)
	 * @see kernel/framework/io/template/Template#assign_block_vars($block_name, $array_vars, $subtemplates)
	 */
	public function assign_block_vars($block_name, array $array_vars, array $subtemplates = array())
	{
		$this->data->assign_block_vars($block_name, $array_vars, $subtemplates);
	}

	/**
	 * (non-PHPdoc)
	 * @see kernel/framework/io/template/Template#copy()
	 */
	public function copy()
	{
		return clone $this;
	}

	/**
	 * (non-PHPdoc)
	 * @see kernel/framework/io/template/Template#display()
	 */
	public function display()
	{
		echo $this->renderer->render($this->data, $this->loader);
	}

	/**
	 * (non-PHPdoc)
	 * @see kernel/framework/io/template/Template#to_string()
	 */
	public function to_string()
	{
		return $this->renderer->render($this->data, $this->loader);
	}

	/**
	 * (non-PHPdoc)
	 * @see kernel/framework/io/template/Template#add_lang($lang)
	 */
	public function add_lang(array $lang)
	{
		$this->data->add_lang($lang);
	}

	/**
	 * (non-PHPdoc)
	 * @see kernel/framework/io/template/Template#add_subtemplate($identifier, $template)
	 */
	public function add_subtemplate($identifier, Template $template)
	{
		$this->data->add_subtemplate($identifier, $template);
	}

	/**
	 * (non-PHPdoc)
	 * @see kernel/framework/io/template/Template#get_data()
	 */
	public function get_data()
	{
		return $this->data;
	}
}
?>