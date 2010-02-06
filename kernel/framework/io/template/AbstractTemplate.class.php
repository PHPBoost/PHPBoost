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
class AbstractTemplate implements Template
{
	protected $identifier = '';
	/**
	 * @var TemplateLoader
	 */
	protected $loader;
	/**
	 * @var TemplateParser
	 */
	protected $display_parser;
	/**
	 * @var TemplateParser
	 */
	protected $to_string_parser;
	/**
	 * @var TemplateData
	 */
	protected $data;

	/**
	 * @desc Builds a Template object.
	 * @param string $identifier Path of your TPL file.  Uses depends of the TemplateLoader that will be used. By default its represent the template file path
	 */
	public function __construct($identifier, $auto_load_vars = self::AUTO_LOAD_FREQUENT_VARS)
	{
		$this->data = new DefaultTemplateData();

		if ($identifier != null)
		{
			$this->identifier = $identifier;

			if ($auto_load_vars === self::AUTO_LOAD_FREQUENT_VARS)
			{
				$this->data->auto_load_frequent_vars();
			}
		}
	}

	protected function set_loader(TemplateLoader $loader)
	{
		$this->loader = $loader;
	}
	
	protected function set_display_parser(TemplateParser $parser)
	{
		$this->display_parser = $parser;
	}
	
	protected function set_to_string_parser(TemplateParser $parser)
	{
		$this->to_string_parser = $parser;
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
        echo $this->to_string_parser->parse($this, $this->loader);
	}

	public function to_string()
	{
		return $this->to_string_parser->parse($this, $this->loader);
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

	/**
	 * @desc returns the subtemplate identified by the $identifier tag
	 * @param string $identifier the template identifier
	 * @return Template the subtemplate identified by the $identifier tag
	 */
	public function get_subtemplate($identifier)
	{
		return $this->data->get_subtemplate($identifier);
	}

	/**
	 * @desc returns the subtemplate identified by the $identifier tag
	 * @param string $identifier the template identifier
	 * @param Template[string] $list the template list in which we will the search for the
	 * template identifier
	 * @return Template the subtemplate identified by the $identifier tag
	 */
	public function get_subtemplate_from_list($identifier, $list)
	{
		return $this->data->get_subtemplate_from_list($identifier, $list);
	}


	/**
	 * @desc returns the block "blockname" in the template block list
	 * @param string $blockname the blockname of the block to retrieve
	 * @return mixed[] the requested block
	 */
	public function get_block($blockname)
	{
		return $this->data->get_block($blockname);
	}

	/**
	 * @desc returns the block "blockname" in the parent_block
	 * @param string $blockname the blockname of the block to retrieve
	 * @param mixed[] $parent_block the parent block in which $blockname will be searched for
	 * @return mixed[] the requested block
	 */
	public function get_block_from_list($blockname, $parent_block)
	{
		return $this->data->get_block_from_list($blockname, $parent_block);
	}

	/**
	 * @desc Returns true if the variable $varname exists and is not considered as false
	 * @param string $varname the name of the variable to check if it is true
	 * @return bool true if the variable $varname exists and is not considered as false
	 */
	public function is_true($varname)
	{
		return $this->data->is_true($varname);
	}

	/**
	 * @desc Returns true if the variable $varname exists and is not considered as false
	 * @param string $varname the name of the variable to check if it is true
	 * @param mixed[] $list the array in which we varname will be searched for
	 * @return bool true if the variable $varname exists and is not considered as false
	 */
	public function is_true_from_list($varname, $list)
	{
		return $this->data->is_true_from_list($varname, $list);
	}

	/**
	 * @desc Returns the $varname variable content searched in from the $list
	 * Special operations will be done if the variable is not registered in $list. If $varname begins with
	 * <ul>
	 *	<li><E_: the variable will be search without its prefix and will be escaped using <code>htmlspecialchars()</code></li>
	 *	<li><J_: the variable will be search without its prefix and will be escaped using <code>TextHelper::to_js_string()</code></li>
	 *	<li><L_: the variable will be search without its prefix in every languages maps registered using <code>Template->add_lang($language)</code></li>
	 *	<li><EL_: the variable will be search without its prefix like languages variables and will be escaped using <code>htmlspecialchars()</code></li>
	 *	<li><JL_: the variable will be search without its prefix like languages variables and will be escaped using <code>TextHelper::to_js_string()</code></li>
	 * </ul>
	 * Each time one of these operation is requested, the variable is registered in order to speed up next calls. If nothing is found, then an empty string is returned
	 * @param string $varname the name of the variable to retrieve
	 * @param mixed[] $list the list in which the variable will be searched for
	 * @return string the $varname variable content
	 */

	public function get_var($varname)
	{
		return $this->data->get_var($varname);
	}

	public function get_var_from_list($varname, &$list)
	{
		return $this->data->get_var_from_list($varname, $list);
	}

	public function get_js_var($varname)
	{
		return $this->data->get_js_var($varname);
	}

	public function get_js_var_from_list($varname, &$list)
	{
		return $this->data->get_js_var_from_list($varname, $list);
	}

	public function get_js_lang_var($varname)
	{
		return $this->data->get_js_lang_var($varname);
	}

	public function get_js_lang_var_from_list($varname, &$list)
	{
		return $this->data->get_js_var_from_list($varname, $list);
	}

	public function get_htmlescaped_lang_var($varname)
	{
		return $this->data->get_htmlescaped_lang_var($varname);
	}

	public function get_htmlescaped_lang_var_from_list($varname, &$list)
	{
		return $this->data->get_htmlescaped_lang_var_from_list($varname, $list);
	}

	public function get_htmlescaped_var($varname)
	{
		return $this->data->get_htmlescaped_var($varname);
	}

	public function get_htmlescaped_var_from_list($varname, &$list)
	{
		return $this->data->get_htmlescaped_var_from_list($varname, $list);
	}

	public function get_lang_var($varname)
	{
		return $this->data->get_lang_var($varname);
	}

	public function get_lang_var_from_list($varname, &$list)
	{
		return $this->data->get_lang_var_from_list($varname, $list);
	}

	public function auto_load_frequent_vars()
	{
		$this->data->auto_load_frequent_vars();
	}
}
?>