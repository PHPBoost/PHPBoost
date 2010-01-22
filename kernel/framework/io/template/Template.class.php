<?php
/*##################################################
 *                          template.class.php
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
 * @desc This class allows you to handle a template file.
 * Your template files should have the .tpl extension.
 * <h1>The PHPBoost template syntax</h1>
 * <h2>Simple variables</h2>
 * A simple variable is accessible with the {NAME} syntax where NAME is its template name. If the variable is not assigned, nothing will be displayed (no error message).
 * Simple variables are assigned by the assign_vars() method.
 * <h2>Loops</h2>
 * You can make some loops to repeat a pattern, those loops can be nested. A loop has a name (name) and each iteration contains some variables, for example, the variable VAR.
 * # START name #
 * My variable is {name.VAR}
 * # END name #
 * To nest loops, here is an example:
 * # START loop1 #
 * I write my loop1 var here: {loop1.VAR}.
 * 	# START loop1.loop2 #
 * I can write my loop2 var here: {loop1.loop2.VAR} but also my loop1 var of the parent loop: {loop1.VAR}.
 * 	# END loop1.loop2 #
 * # END loop1 #
 * To assign the variable, see the assign_block_vars() method which creates one iteration.
 * <h2>Conditions</h2>
 * When you want to display something only in particular case, you can use some condition tests.
 * # IF C_MY_TEST #
 * This text will be displayed only if the C_MY_TEST variable is true
 * # ENDIF #
 * You can nest some conditions.</li>
 * </ul>
 * To be more efficient, this class uses a cache and parses each file only once.
 * <h1>File paths</h1>
 * The web site can have several themes whose files aren't in the same folders. When you load a file, you just have to load the generic file and the good template file will
 * be loaded dinamically.
 * <h2>Kernel template file</h2>
 * When you want to load a kernel template file, the path you must indicate is only the name of the file, for example header.tpl loads /template/your_theme/header.tpl and
 * if it doesn't exist, it will load /template/default/header.tpl.
 * <h2>Module template file</h2>
 * When you want to load a module template file, you must indicate the name of you module and then the name of the file like this: module/file.tpl which will load the
 * /module/templates/file.tpl. If the user themes redefines the file.tpl file for the module module, the file templates/your_theme/modules/module/file.tpl will be loaded.
 * <h2>Menu template file</h2>
 * To load a file of a menu, use this kind of path: menus/my_menu/file.tpl which will load the /menus/my_menu/templates/file.tpl file.
 * <h2>Framework template file</h2>
 * To load a framework file, use a path like this: framework/package/file.tpl which will load /templates/your_theme/framework/package/file.tpl if the theme overrides it,
 * otherwise /templates/default/framework/package/file.tpl will be used.
 */
class Template
{
	protected $identifier = '';
	protected $langs = array();
	protected $vars = array();
	protected $blocks = array();
	protected $subtemplates = array();
	protected $loader;

	const AUTO_LOAD_FREQUENT_VARS = true;
	const DO_NOT_LOAD_FREQUENT_VARS = false;

	const FILE_TEMPLATE_LOADER = 0x01;
	const TEMPLATE_PARSER_ECHO = 0x01;
	const TEMPLATE_PARSER_STRING = 0x02;

	/**
	 * @desc Builds a Template object.
	 * @param string $identifier Path of your TPL file.  Uses depends of the TemplateLoader that will be used. By default its represent the template file path
	 */
	public function __construct($identifier = null, $auto_load_vars = self::AUTO_LOAD_FREQUENT_VARS, $loader = self::FILE_TEMPLATE_LOADER)
	{
		if ($identifier != null)
		{
			$this->identifier = $identifier;

			if ($auto_load_vars === self::AUTO_LOAD_FREQUENT_VARS)
			{
				$this->auto_load_frequent_vars();
			}

			if ($loader !== self::FILE_TEMPLATE_LOADER)
			{
				$this->set_loader($loader);
			}
			else
			{
				$this->set_loader(new FileTemplateLoader($this));
			}
		}
	}

	public function set_loader($loader)
	{
		$this->loader = $loader;
	}

	/**
	 * @desc Assigns some simple template vars.  Those variables will be accessed in your template with the {var_name} syntax.
	 * @param string[] $array_vars A map var_name => var_value. Generally, var_name is written in caps characters.
	 */
	public function assign_vars($array_vars)
	{
		foreach ($array_vars as $key => $val)
		{
			$this->vars[$key] = $val;
		}
	}

	/**
	 * @desc Assigns a template block. A block represents a loop and has a name which be used in your template file to indicate which loop you want to browse.
	 * To know what syntax to use to browse a loop, see the class description, there are examples.
	 * @param string $block_name Block name.
	 * @param string[] $array_vars A map var_name => var_value. Generally, var_name is written in caps characters.
	 */
	public function assign_block_vars($block_name, $array_vars, $subtemplates = array())
	{
		if (strpos($block_name, '.') !== false) //Bloc imbriqué.
		{
			$blocks = explode('.', $block_name);
			$blockcount = count($blocks) - 1;

			$str = &$this->blocks;
			for ($i = 0; $i < $blockcount; $i++)
			{
				$str = &$str[$blocks[$i]];
				$str = &$str[count($str) - 1];
			}
			$str[$blocks[$blockcount]][] = array(
				'vars' => $array_vars,
				'subtemplates' => $subtemplates
			);
		}
		else
		{
			$this->blocks[$block_name][] = array(
				'vars' => $array_vars,
				'subtemplates' => $subtemplates
			);
		}
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
	public function parse($parser = self::TEMPLATE_PARSER_ECHO)
	{
		if (!is_object($parser))
		{
			switch ($parser)
			{
				case self::TEMPLATE_PARSER_STRING:
					$parser = new TemplateParserString();
					break;
				case self::TEMPLATE_PARSER_ECHO:
				default:
					$parser = new TemplateParserEcho();
					break;
			}
		}

		return $parser->parse($this, $this->loader);
	}


	/**
	 * @desc add a lang map to the template map list in which template variables beginning by L_ will be searched for of not already registered
	 * @param string[string] $lang the language map
	 */
	public function add_lang($lang)
	{
		$this->langs[] = $lang;
	}

	/**
	 * @desc add a subtemplate that could be used using the following template code <code># include identifier #</code>
	 * @param string $identifier the identifier
	 * @param Template $template the template
	 */
	public function add_subtemplate($identifier, $template)
	{
		$this->subtemplates[$identifier] =& $template;
	}

	/**
	 * @desc returns the subtemplate identified by the $identifier tag
	 * @param string $identifier the template identifier
	 * @return Template the subtemplate identified by the $identifier tag
	 */
	public function get_subtemplate($identifier)
	{
		if (isset($this->subtemplates[$identifier]))
		{
			return $this->subtemplates[$identifier];
		}
		return null;
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
		if (isset($list[$identifier]))
		{
			return $list[$identifier];
		}
		return null;
	}


	/**
	 * @desc returns the block "blockname" in the template block list
	 * @param string $blockname the blockname of the block to retrieve
	 * @return mixed[] the requested block
	 */
	public function get_block($blockname)
	{
		return $this->get_block_from_list($blockname, $this->blocks);
	}

	/**
	 * @desc returns the block "blockname" in the parent_block
	 * @param string $blockname the blockname of the block to retrieve
	 * @param mixed[] $parent_block the parent block in which $blockname will be searched for
	 * @return mixed[] the requested block
	 */
	public function get_block_from_list($blockname, $parent_block)
	{
		if (isset($parent_block[$blockname]) && is_array($parent_block[$blockname]))
		{
			return $parent_block[$blockname];
		}
		return array();
	}

	/**
	 * @desc Returns true if the variable $varname exists and is not considered as false
	 * @param string $varname the name of the variable to check if it is true
	 * @return bool true if the variable $varname exists and is not considered as false
	 */
	public function is_true($varname)
	{
		return $this->is_true_from_list($varname, $this->vars);
	}

	/**
	 * @desc rReturns true if the variable $varname exists and is not considered as false
	 * @param string $varname the name of the variable to check if it is true
	 * @param mixed[] $list the array in which we varname will be searched for
	 * @return bool true if the variable $varname exists and is not considered as false
	 */
	public function is_true_from_list($varname, $list)
	{
		return isset($list[$varname]) && $list[$varname];
	}

	/**
	 * @desc Returns the $varname variable content
	 * @param string $varname the name of the variable to retrieve
	 * @return string the $varname variable content
	 * @see get_var_from_list($varname, $list)
	 */
	/**
	 * @desc Returns the $varname variable content searched in from the $list
	 * Special operations will be done if the variable is not registered in $list. If $varname begins with
	 * <ul>
	 *	<li><E_: the variable will be search without its prefix and will be escaped using <code>htmlspecialchars()</code></li>
	 *	<li><J_: the variable will be search without its prefix and will be escaped using <code>to_js_string()</code></li>
	 *	<li><L_: the variable will be search without its prefix in every languages maps registered using <code>Template->add_lang($language)</code></li>
	 *	<li><EL_: the variable will be search without its prefix like languages variables and will be escaped using <code>htmlspecialchars()</code></li>
	 *	<li><JL_: the variable will be search without its prefix like languages variables and will be escaped using <code>to_js_string()</code></li>
	 * </ul>
	 * Each time one of these operation is requested, the variable is registered in order to speed up next calls. If nothing is found, then an empty string is returned
	 * @param string $varname the name of the variable to retrieve
	 * @param mixed[] $list the list in which the variable will be searched for
	 * @return string the $varname variable content
	 */

	public function get_var($varname)
	{
		return $this->get_var_from_list($varname, $this->vars);
	}

	public function get_var_from_list($varname, &$list)
	{
		if (isset($list[$varname]))
		{
			return $list[$varname];
		}
		$empty_value = '';
		return $this->register_var($varname, $empty_value, $list);
	}

	public function get_js_var($varname)
	{
		return $this->get_js_var_from_list($varname, $this->vars);
	}

	public function get_js_var_from_list($varname, &$list)
	{
		$full_varname = 'J_' . $varname;
		if (!empty($list[$full_varname]))
		{
			return $list[$full_varname];
		}

		if (!isset($list[$varname]))
		{
			$list[$varname] = '';
		}
		return $this->register_var($full_varname, to_js_string($list[$varname]), $list);
	}

	public function get_js_lang_var($varname)
	{
		return $this->get_js_lang_var_from_list($varname, $this->vars);
	}

	public function get_js_lang_var_from_list($varname, &$list)
	{
		$full_varname = 'JL_' . $varname;
		if (!empty($list[$full_varname]))
		{
			return $list[$full_varname];
		}

		$lang_var = $this->get_lang_var_from_list($varname, $list);
		return $this->register_var($full_varname, to_js_string($lang_var), $list);
	}

	public function get_htmlescaped_lang_var($varname)
	{
		return $this->get_htmlescaped_lang_var_from_list($varname, $this->vars);
	}

	public function get_htmlescaped_lang_var_from_list($varname, &$list)
	{
		$full_varname = 'EL_' . $varname;
		if (!empty($list[$full_varname]))
		{
			return $list[$full_varname];
		}

		$lang_var = $this->get_lang_var_from_list($varname, $list);
		return $this->register_var($full_varname, htmlspecialchars($lang_var), $list);
	}

	public function get_htmlescaped_var($varname)
	{
		return $this->get_htmlescaped_var_from_list($varname, $this->vars);
	}

	public function get_htmlescaped_var_from_list($varname, &$list)
	{
		$full_varname = 'E_' . $varname;
		if (!empty($list[$full_varname]))
		{
			return $list[$full_varname];
		}

		if (!isset($list[$varname]))
		{
			$list[$varname] = '';
		}

		$value = htmlspecialchars($list[$varname]);
		return $this->register_var($full_varname, $value, $list);
	}

	public function get_lang_var($varname)
	{
		return $this->get_lang_var_from_list($varname, $this->vars);
	}

	public function get_lang_var_from_list($varname, &$list)
	{
		$full_varname = 'L_' . $varname;
		if (!empty($list[$full_varname]))
		{
			return $list[$full_varname];
		}
		$varname= strtolower($varname);
		foreach ($this->langs as $lang)
		{
			if (isset($lang[$varname]))
			{
				return $this->register_var($full_varname, $lang[$varname], $list);
			}
		}
		$empty_string = '';
		return $this->register_var($full_varname, $empty_string, $list);
	}

	protected function auto_load_frequent_vars()
	{
		global $User, $Session;
		$member_connected = $User->check_level(MEMBER_LEVEL);
		$this->assign_vars(array(
			'SID' => SID,
			'THEME' => get_utheme(),
			'LANG' => get_ulang(),
			'C_USER_CONNECTED' => $member_connected,
			'C_USER_NOTCONNECTED' => !$member_connected,
			'PATH_TO_ROOT' => TPL_PATH_TO_ROOT,
			'PHP_PATH_TO_ROOT' => PATH_TO_ROOT,
			'TOKEN' => !empty($Session) ? $Session->get_token() : ''
			));
	}

	private function find_lang_var($varname)
	{
		foreach ($this->langs as $lang)
		{
			if (isset($lang[$varname]))
			{
				return $lang[$varname];
			}
		}
		return '';
	}

	private function register_var($name, $value, &$list)
	{
		$list[$name] = $value;
		return $value;
	}
}

/**
 * @deprecated
 */
define('TEMPLATE_STRING_MODE', Template::TEMPLATE_PARSER_STRING);
?>