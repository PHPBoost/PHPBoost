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
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
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

define('TEMPLATE_WRITE_MODE', 0x01);
define('TEMPLATE_STRING_MODE', 0x02);

define('AUTO_LOAD_FREQUENT_VARS', true);
define('DO_NOT_AUTO_LOAD_FREQUENT_VARS', false);

/**
 * @package io
 * @author Loïc Rouchon <horn@phpboost.com> Régis Viarre <crowkait@phpboost.com>
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
	protected $template_filepath = '';
	protected $module_data_path = array();
	protected $langs = array();
	protected $vars = array();
	protected $blocks = array();
	protected $subtemplates = array();
	
	/**
	 * @desc Builds a Template object.
	 * @param string $tpl Path of your TPL file. See the class description to know you you have to write this path.
	 */
	public function __construct($tpl = null, $auto_load_vars = AUTO_LOAD_FREQUENT_VARS)
	{
		if ($tpl != null)
		{
			$this->template_filepath = $this->check_file($tpl);
			if ($auto_load_vars)
			{
				$this->auto_load_frequent_vars();
			}
		}
	}
	
	/**
	 * @desc Retrieves the path of the module. This path will be used to write the relative paths in your templates.
	 * @param string $module Name of the module for which you want to know the data path.
	 * @return string The relative path.
	 */
	public function get_module_data_path($module)
	{
		if (isset($this->module_data_path[$module]))
		{
			return $this->module_data_path[$module];
		}
		return '';
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
	public function assign_block_vars($block_name, $array_vars)
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
			$str[$blocks[$blockcount]][] = $array_vars;
		}
		else
		{
			$this->blocks[$block_name][] = $array_vars;
		}
	}
	
	/**
	 * @desc Clones this object.
	 * @return Template A clone of this object.
	 */
	public function copy()
	{
		$copy = new Template();
		
		$copy->template_filepath = $this->template_filepath;
		$copy->module_data_path = $this->module_data_path;
		$copy->langs = $this->langs;
		$copy->vars = $this->vars;
		$copy->blocks = $this->blocks;
		$copy->subtemplates = $this->subtemplates;
		
		return $copy;
	}
	
	/**
	  * @desc Returns the filepath used for the template
	  * @return string the filepath used for the template
	  */
	public function get_template_filepath()
	{
		return $this->template_filepath;
	}
	
	/**
	 * @desc Parses the file. It will use the variables you assigned.
	 * @param bool $return_mode In its default behaviour (TEMPLATE_WRITE_MODE), this class write what it parses in the PHP standard output.
	 * If you want to retrieve the parsed content and not to write it, use the TEMPLATE_STRING_MODE variable.
	 * @return string The parsed content if you used the TEMPLATE_STRING_MODE, otherwise it doesn't return anything.
	 */
	public function parse($return_mode = TEMPLATE_WRITE_MODE)
	{
		if ($return_mode == TEMPLATE_WRITE_MODE)
		{
			import('io/template/template_parser_echo');
			$parser = new TemplateParserEcho();
			$parser->parse($this, $this->template_filepath);
		}
		else
		{
			import('io/template/template_parser_string');
			$parser = new TemplateParserString();
			return $parser->parse($this, $this->template_filepath);
		}
	}
	
	
	/**
	  * @desc add a lang map to the template map list in which template variables beginning by L_ will be searched for of not already registered
	  * @param string[string] $lang the language map
	  */
	public function add_lang(&$lang)
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
	  * @return Template the subtemplate identified by the $identifier tag
	  */
	public function get_subtemplate($identifier)
	{
		return $this->subtemplates[$identifier];
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
	  * @param mixed[] &$parent_block the parent block in which $blockname will be searched for
	  * @return mixed[] the requested block
	  */
	public function get_block_from_list($blockname, &$parent_block)
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
	  * @param mixed[] &$list the array in which we varname will be searched for
	  * @return bool true if the variable $varname exists and is not considered as false
	  */
	public function is_true_from_list($varname, &$list)
	{
		return isset($list[$varname]) && $list[$varname];
	}
	
	/**
	  * @desc Returns the $varname variable content
	  * @param string $varname the name of the variable to retrieve
	  * @return string the $varname variable content
	  * @see get_var_from_list($varname, &$list)
	  */
	public function get_var($varname)
	{
		return $this->get_var_from_list($varname, $this->vars);
	}
	
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
	  * @param mixed[] &$list the list in which the variable will be searched for
	  * @return string the $varname variable content 
	  */
	public function get_var_from_list($varname, &$list)
	{
		if (!empty($list[$varname]))
		{
			return $list[$varname];
		}
		else if (strpos($varname, 'E_') === 0)
		{
			$varname = substr($varname, 2);
			if (!empty($list[$varname]))
			{
				return $this->register_var($varname, htmlspecialchars($list[$varname]), $list);
			}
		}
		else if (strpos($varname, 'J_') === 0)
		{
			$varname = substr($varname, 2);
			if (!empty($list[$varname]))
			{
				return $this->register_var($varname, to_js_string($list[$varname]), $list);
			}
		}
		else if (strpos($varname, 'L_') === 0)
		{
			$var = $this->find_lang_var(strtolower(substr($varname, 2)));
			if (!empty($var))
			{
				return $this->register_var($varname, $var, $list);
			}
		}
		else if (strpos($varname, 'EL_') === 0)
		{
			$var = $this->find_lang_var(strtolower(substr($varname, 3)));
			if (!empty($var))
			{
				return $this->register_var($varname, htmlspecialchars($var), $list);
			}
		}
		else if (strpos($varname, 'JL_') === 0)
		{
			$var = $this->find_lang_var(strtolower(substr($varname, 3)));
			if (!empty($var))
			{
				return $this->register_var($varname, to_js_string($var), $list);
			}
		}
		return '';
	}
	
	protected function check_file(&$filepath)
	{
		/*
		 Samples :
		 $filepath = /forum/forum_topic.tpl
		 $filepath = forum/forum_topic.tpl
		 $module = forum
		 $filename = forum_topic.tpl
		 $file = forum_topic.tpl
		 $folder =


		 $filepath = /news/framework/content/syndication/last_news.tpl
		 $filepath = news/framework/content/syndication/menu.tpl
		 $module = news
		 $filename = menu.tpl
		 $file = framework/content/syndication/menu.tpl
		 $folder = framework/content/syndication
		 */
		
		if (strpos($filepath, '/') === 0)
		{
			// Load the file from its absolute location
			// (Not overlaodable)
			if (file_exists(PATH_TO_ROOT . $filepath))
			{
				return PATH_TO_ROOT . $filepath;
			}
		}
		
		$i = strpos($filepath, '/');
		$module = substr($filepath, 0, $i);
		$file = trim(substr($filepath, $i), '/');
		$folder = trim(substr($file, 0, strpos($file, '/')), '/');
		$filename = trim(substr($filepath, strrpos($filepath, '/')));

		$default_templates_folder = PATH_TO_ROOT . '/templates/default/';
		$theme_templates_folder = PATH_TO_ROOT . '/templates/' . get_utheme() . '/';
		if (empty($module) || in_array($module, array('admin') ))
		{   // Kernel - Templates priority order
			//      /templates/$theme/.../$file.tpl
			//      /templates/default/.../$file.tpl
			if (file_exists($file_path = $theme_templates_folder . $filepath))
			{
				return $file_path;
			}
			return $default_templates_folder . $filepath;
		}
		elseif ($module == 'framework')
		{   // Framework - Templates priority order
			//      /templates/$theme/framework/.../$file.tpl
			//      /templates/default/framework/.../$file.tpl
			if (file_exists($file_path = $theme_templates_folder . $filepath))
			{
				return $file_path;
			}

			return $default_templates_folder . $filepath;
		}
		elseif ($module == 'menus')
		{   // Framework - Templates priority order
			//      /templates/$theme/menus/$menu/filename.tpl
			//      /menus/$menu/default/framework/.../$file.tpl
			$menu = substr($folder, 0, strpos($folder, '/'));
			if (empty($menu))
			{
				$menu = $folder;
			}
			if (file_exists($file_path = $theme_templates_folder . '/menus/' . $menu . '/' . $filename))
			{
				return $file_path;
			}

			return PATH_TO_ROOT . '/menus/' . $menu . '/templates/' . $filename;
		}
		else
		{   // Module - Templates
			$theme_module_templates_folder = $theme_templates_folder . 'modules/' . $module . '/';
			$module_templates_folder = PATH_TO_ROOT . '/' . $module . '/templates/';

			if ($folder == 'framework')
			{   // Framework - Templates priority order
				//      /templates/$theme/modules/$module/framework/.../$file.tpl
				//      /templates/$theme/framework/.../$file.tpl
				//      /$module/templates/framework/.../$file.tpl
				//      /templates/default/framework/.../$file.tpl
				if (file_exists($file_path = $theme_module_templates_folder . $file))
				{
					return $file_path;
				}
				if (file_exists($file_path = $theme_templates_folder . $filepath))
				{
					return $file_path;
				}
				if (file_exists($file_path = ($module_templates_folder . 'framework/' . $file)))
				{
					return $file_path;
				}
				return $default_templates_folder . $file;
			}

			//module data path
			if (!isset($this->module_data_path[$module]))
			{
				if (is_dir($theme_module_templates_folder . '/images'))
				{
					$this->module_data_path[$module] = TPL_PATH_TO_ROOT . '/templates/' . get_utheme() . '/' . 'modules/' . $module;
				}
				else
				{
					$this->module_data_path[$module] = TPL_PATH_TO_ROOT . '/' . trim($module . '/templates/', '/');
				}
			}

			if (file_exists($file_path = $theme_module_templates_folder . $file))
			{
				return $file_path;
			}
			else
			{
				return $module_templates_folder . $file;
			}
		}
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

	protected function find_lang_var(&$varname)
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
	
	protected function register_var(&$name, &$value, &$list)
	{
		$list[$name] = $value;
		return $value;
	}
}
?>