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

defined('TEMPLATE_STRING_MODE') or define('TEMPLATE_STRING_MODE', true);
defined('TEMPLATE_WRITE_MODE') or define('TEMPLATE_WRITE_MODE', false);

class Template
{
	private $template_filepath = '';
	private $module_data_path = array();
	private $langs = array();
	private $vars = array();
	private $blocks = array();
	private $subtemplates = array();
	
	/**
	 * @desc Builds a Template object.
	 * @param string $tpl Path of your TPL file. See the class description to know you you have to write this path.
	 */
	public function __construct($tpl = null)
	{
		if ($tpl != null)
		{
			$this->template_filepath = $this->check_file($tpl);
			$this->auto_load_frequent_vars();
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
	
	public function add_lang($lang)
	{
		$this->langs[] = $lang;
	}
	
	public function add_subtemplate($identifier, $template)
	{
		$this->subtemplates[$identifier] =& $template;
	}
	
	public function get_subtemplate($identifier)
	{
		return $this->subtemplates[$identifier];
	}
	
	private function check_file($filepath)
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
	
	private function auto_load_frequent_vars()
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

	public function get_block($blockname, $parent_block = null)
	{	
		if ($parent_block == null)
		{
			$parent_block =& $this->blocks;
		}
		if (isset($parent_block[$blockname]) && is_array($parent_block[$blockname]))
		{
			return $parent_block[$blockname];
		}
		return array();
	}
	
	public function is_true($varname, $list = null)
	{
		if ($list == null)
		{
			$list =& $this->vars;
		}
		return isset($list[$varname]) && $list[$varname];
	}
	
	public function get_var($varname)
	{
		return $this->get_var_from_list($varname, $this->vars);
	}
	
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
			$var = $this->find_var(strtolower(substr($varname, 2)));
			if (!empty($var))
			{
				return $this->register_var($varname, $var, $list);
			}
		}
		else if (strpos($varname, 'EL_') === 0)
		{
			$var = $this->find_var(strtolower(substr($varname, 3)));
			if (!empty($var))
			{
				return $this->register_var($varname, htmlspecialchars($var), $list);
			}
		}
		else if (strpos($varname, 'JL_') === 0)
		{
			$var = $this->find_var(strtolower(substr($varname, 3)));
			if (!empty($var))
			{
				return $this->register_var($varname, to_js_string($var), $list);
			}
		}
		return '';
	}

	protected function find_var(&$varname)
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
	
	private function register_var(&$name, &$value, &$list)
	{
		$list[$name] = $value;
		return $value;
	}
}
?>