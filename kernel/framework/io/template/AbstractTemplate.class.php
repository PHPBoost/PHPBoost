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
	 * @desc Builds a Template object.
	 * @param string $identifier Path of your TPL file.  Uses depends of the TemplateLoader that will be used. By default its represent the template file path
	 */
	public function __construct(TemplateLoader $loader, TemplateRenderer $renderer, TemplateData $data, $auto_load_vars = self::AUTO_LOAD_FREQUENT_VARS)
	{
		$this->set_loader($loader);
		$this->set_renderer($renderer);
		$this->set_data($data);

		if ($auto_load_vars === self::AUTO_LOAD_FREQUENT_VARS)
		{
			$this->data->auto_load_frequent_vars();
		}
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
	 * @desc Assigns some simple template vars.  Those variables will be accessed in your template with the {var_name} syntax.
	 * @param string[] $array_vars A map var_name => var_value. Generally, var_name is written in caps characters.
	 */
	public function assign_vars(array $array_vars)
	{
		$this->data->assign_vars($array_vars);
	}

	/**
	 * @desc Assigns a template block. A block represents a loop and has a name which be used in your template file to indicate which loop you want to browse.
	 * To know what syntax to use to browse a loop, see the class description, there are examples.
	 * @param string $block_name Block name.
	 * @param string[] $array_vars A map var_name => var_value. Generally, var_name is written in caps characters.
	 */
	public function assign_block_vars($block_name, array $array_vars, array $subtemplates = array())
	{
		$this->data->assign_block_vars($block_name, $array_vars, $subtemplates);
	}

	/**
	 * @desc Clones this object.
	 * @return Template A clone of this object.
	 */
	public function copy()
	{
		return clone $this;
	}

	/**
	 * @desc Returns the template identifier
	 * @return string the template identifier
	 */
	public function get_identifier()
	{
		return $this->identifier;
	}

	/**
	 * @desc Parses the file. It will use the variables you assigned.
	 * @param mixed $parser In its default behaviour (self::TEMPLATE_PARSER_ECHO), this class write what it parses in the PHP standard output.
	 * If you want to retrieve the parsed content and not to write it, use the self::TEMPLATE_PARSER_STRING variable.
	 * @return string The TemplateParser resource (depends of the template parser)
	 */
	public function display()
	{
		echo $this->renderer->render($this->data, $this->loader);
	}

	public function to_string()
	{
		return $this->renderer->render($this->data, $this->loader);
	}

	/**
	 * @desc add a lang map to the template map list in which template variables beginning by L_ will be searched for of not already registered
	 * @param string[string] $lang the language map
	 */
	public function add_lang(array $lang)
	{
		$this->data->add_lang($lang);
	}

	/**
	 * @desc add a subtemplate that could be used using the following template code <code># include identifier #</code>
	 * @param string $identifier the identifier
	 * @param Template $template the template
	 */
	public function add_subtemplate($identifier, Template $template)
	{
		$this->data->add_subtemplate($identifier, $template);
	}

	public function auto_load_frequent_vars()
	{
		$this->data->auto_load_frequent_vars();
	}

	public function get_data()
	{
		return $this->data;
	}
}
?>