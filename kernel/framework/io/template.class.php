<?php
/*##################################################
 *                            template.class.php
 *                            -------------------
 *   begin                : Februar 12, 2006
 *   copyright            : (C) 2006 Régis Viarre, Loïc Rouchon
 *   email                : mickaelhemri@gmail.com, horn@phpboost.com
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
 * The PHPboost template engine is actually based on sections of code from phpBB3 templates
 ###################################################*/

define('TEMPLATE_STRING_MODE', true);
define('TEMPLATE_WRITE_MODE', false);
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
	/**
	 * @desc Builds a Template object.
	 * @param string $tpl Path of your TPL file. See the class description to know you you have to write this path.
	 * @param bool $auto_load_vars AUTO_LOAD_FREQUENT_VARS if you want to assign the frequent vars (user lang, user theme, CSRF token,
	 * current path to root, user logged in or not...). DO_NOT_AUTO_LOAD_FREQUENT_VARS if you don't want.
	 */
	function Template($tpl = '', $auto_load_vars = AUTO_LOAD_FREQUENT_VARS)
	{
		if (!empty($tpl))
		{
			global $User, $Session;
			$this->tpl = $this->_check_file($tpl);
			$this->files[$this->tpl] = $this->tpl;
			if ($auto_load_vars)
			{
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
		}
	}

	/**
	 * @desc Loads several files in the same Template instance.
	 * @deprecated
	 * @param string[] $array_tpl A map file_identifier => file_path where file_identifier is the name you give to your file and file_path its path.
	 * See the class description to learn how to write the path.
	 */
	function set_filenames($array_tpl)
	{
		foreach ($array_tpl as $parse_name => $filename)
		{
			$this->files[$parse_name] = $this->_check_file($filename);
		}

		global $Session;
		$this->assign_vars(array(
            'TOKEN' => !empty($Session) ? $Session->get_token() : ''
            ));
	}

	/**
	 * @desc Retrieves the path of the module. This path will be used to write the relative paths in your templates.
	 * @param string $module Name of the module for which you want to know the data path.
	 * @return string The relative path.
	 */
	function get_module_data_path($module)
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
	function assign_vars($array_vars)
	{
		foreach ($array_vars as $key => $val)
		{
			$this->_var[$key] = $val;
		}
	}

	/**
	 * @desc Assigns a template block. A block represents a loop and has a name which be used in your template file to indicate which loop you want to browse.
	 * To know what syntax to use to browse a loop, see the class description, there are examples.
	 * @param string $block_name Block name.
	 * @param string[] $array_vars A map var_name => var_value. Generally, var_name is written in caps characters.
	 */
	function assign_block_vars($block_name, $array_vars)
	{
		if (strpos($block_name, '.') !== false) //Bloc imbriqué.
		{
			$blocks = explode('.', $block_name);
			$blockcount = count($blocks) - 1;

			$str = &$this->_block;
			for ($i = 0; $i < $blockcount; $i++)
			{
				$str = &$str[$blocks[$i]];
				$str = &$str[count($str) - 1];
			}
			$str[$blocks[$blockcount]][] = $array_vars;
		}
		else
		{
			$this->_block[$block_name][] = $array_vars;
		}
	}

	/**
	 * @desc Deletes a block. It won't be browsable any more in your template.
	 * @param string $block_name The name of your block.
	 */
	function unassign_block_vars($block_name)
	{
		if (isset($this->_block[$block_name]))
		{
			unset($this->_block[$block_name]);
		}
	}

	/**
	 * @desc Parses the file. It will use the variables you assigned.
	 * @param bool $return_mode In its default behaviour (TEMPLATE_WRITE_MODE), this class write what it parses in the PHP standard output.
	 * If you want to retrieve the parsed content and not to write it, use the TEMPLATE_STRING_MODE variable.
	 * @return string The parsed content if you used the TEMPLATE_STRING_MODE, otherwise it doesn't return anything.
	 */
	function parse($return_mode = TEMPLATE_WRITE_MODE)
	{
		if ( $return_mode )
		{
			return $this->pparse($this->tpl, $return_mode);
		}
		else
		{
			$this->pparse($this->tpl, $return_mode);
		}
	}

	/**
	 * @deprecated
	 * @desc Parses the file whose name is $parse_name and which has been declared with the set_filenames_method. It uses the variables you assigned (when you assign a
	 * variable it will be usable in every file handled by this object).
	 * @param string $parse_name The identifier of the file you want to parse.
	 * @param bool $return_mode In its default behaviour (TEMPLATE_WRITE_MODE), this class write what it parses in the PHP standard output.
	 * @return string The parsed content if you used the TEMPLATe_STRING_MODE, otherwise it doesn't return anything.
	 */
	function pparse($parse_name, $return_mode = false)
	{
		if (!isset($this->files[$parse_name]))
		{
			return '';
		}

		$this->return_mode = $return_mode;

		$file_cache_path = PATH_TO_ROOT . '/cache/tpl/' . trim(str_replace(array('/', '.', '..', 'tpl', 'templates'), array('_', '', '', '', 'tpl'), $this->files[$parse_name]), '_');
		if ($return_mode)
		{
			$file_cache_path .= '_str';
		}
		$file_cache_path .= '.php';

		//Vérification du statut du fichier de cache associé au template.
		if (!$this->_check_cache_file($this->files[$parse_name], $file_cache_path))
		{
			//Chargement du template.
			if (!$this->_load($parse_name))
			{
				return '';
			}

			//Parse
			$this->_parse($parse_name, $return_mode);
			$this->_clean(); //On nettoie avant d'envoyer le flux.
			$this->_save($file_cache_path); //Enregistrement du fichier de cache.
		}

		include($file_cache_path);

		if ($this->return_mode)
		{
			return $tplString;
		}
	}

	/**
	 * @desc Clones this object.
	 * @return Template A clone of this object.
	 */
	function copy()
	{
		$copy = new Template();

		$copy->tpl = $this->tpl;
		$copy->template = $this->template;
		$copy->files = $this->files;
		$copy->module_data_path = $this->module_data_path;
		$copy->return_mode = $this->return_mode;

		$copy->_var = $this->_var; //Tableau contenant les variables de remplacement des variables simples.
		$copy->_block = $this->_block; //Tableau contenant les variables de remplacement des variables simples.

		return $copy;
	}


	## Protected Methods ##
	/**
	* @access protected
	* @desc Computes the path of the file to load dinamycally according to the user theme and the kind of file (kernel, module, menu or framework file).
	* @param string $filename The file path. See the class description to know what path to use.
	* @return string The path to load.
	*/
	function _check_file($filename)
	{
		global $CONFIG;

		/*
		 Samples :
		 $filename = /forum/forum_topic.tpl
		 $filename = forum/forum_topic.tpl
		 $module = forum
		 $file_name = forum_topic.tpl
		 $file = forum_topic.tpl
		 $folder =


		 $filename = /news/framework/content/syndication/last_news.tpl
		 $filename = news/framework/content/syndication/menu.tpl
		 $module = news
		 $file_name = menu.tpl
		 $file = framework/content/syndication/menu.tpl
		 $folder = framework/content/syndication
		 */

		$i = strpos($filename, '/');
		$module = substr($filename, 0, $i);
		$file = trim(substr($filename, $i), '/');
		$folder = trim(substr($file, 0, strpos($file, '/')), '/');
		$file_name = trim(substr($filename, strrpos($filename, '/')));

		$default_templates_folder = PATH_TO_ROOT . '/templates/default/';
		$theme_templates_folder = PATH_TO_ROOT . '/templates/' . get_utheme() . '/';
		if (strpos($filename, '/') === 0)
		{
			// Load the file from its absolute location
			// (Not overlaodable)
			if (file_exists(PATH_TO_ROOT . $filename))
			{
				return PATH_TO_ROOT . $filename;
			}
		}
		elseif (empty($module) || in_array($module, array('admin') ))
		{   // Kernel - Templates priority order
			//      /templates/$theme/.../$file.tpl
			//      /templates/default/.../$file.tpl
			if (file_exists($file_path = $theme_templates_folder . $filename))
			{
				return $file_path;
			}
			return $default_templates_folder . $filename;
		}
		elseif ($module == 'framework')
		{   // Framework - Templates priority order
			//      /templates/$theme/framework/.../$file.tpl
			//      /templates/default/framework/.../$file.tpl
			if (file_exists($file_path = $theme_templates_folder . $filename))
			{
				return $file_path;
			}

			return $default_templates_folder . $filename;
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
			if (file_exists($file_path = $theme_templates_folder . '/menus/' . $menu . '/' . $file_name))
			{
				return $file_path;
			}

			return PATH_TO_ROOT . '/menus/' . $menu . '/templates/' . $file_name;
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
				if (file_exists($file_path = $theme_templates_folder . $filename))
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

	/**
	 * @access protected
	 * @desc Allows you to know if the cache file is still valid or if it has to be removed because the source file has been updated.
	 * @param string $tpl_path File path to control.
	 * @param string $file_cache_path Cache file path corresponding.
	 * @return bool True if the cache file is still correct, false if the cache file doesn't exist or is not up to date.
	 */
	function _check_cache_file($tpl_path, $file_cache_path)
	{
		//fichier expiré
		if (file_exists($file_cache_path))
		{
			if (@filemtime($tpl_path) > @filemtime($file_cache_path) || @filesize($file_cache_path) === 0)
			{
				return false;
			}
			else
			{
				return true;
			}
		}
		return false;
	}

	/**
	 * @access protected
	 * @desc Loads a template file.
	 * @param string $parse_name Path of the file to load (relative to the current file).
	 * @return bool true if the file could be loaded, false otherwise. If it encounters a fatal problem, an error will be displayed and the script execution will be stopped.
	 */
	function _load($parse_name)
	{
		if (!isset($this->files[$parse_name]))
		return false;

		$this->template = @file_get_contents_emulate($this->files[$parse_name]); //Charge le contenu du fichier tpl.
		if ($this->template === false)
		{
			die('Template::_load(): The ' . $this->files[$parse_name] . ' file loading to parse ' . $parse_name . ' failed.');
		}
		if (empty($this->template))
		{
			die('Template::_load(): The file ' . $this->files[$parse_name] . ' to parse ' . $parse_name . ' is empty.');
		}

		return true;
	}

	/**
	 * @access protected
	 * @deprecated
	 * @desc Include a file in another file. You can access to the same variables in the two variables,
	 * it's just as if you had copied the content of the included file in the includer.
	 * @param string $parse_name The identifier of the file to load. The two files must have been declared in the same template object.
	 * @return string If the file is parsed in the string mode (returns and writes) or nothing if it writes the result in the PHP standard output.
	 */
	function _include($parse_name)
	{
		if (isset($this->files[$parse_name]))
		{
			if ($this->return_mode)
			{
				return $this->pparse($parse_name, $this->return_mode);
			}
			else
			{
				$this->pparse($parse_name, $this->return_mode);
			}
		}
	}

	/**
	 * @desc Parses a PHPBoost template file and converts it to the PHPBoost template cache file format.
	 * @param string $parse_name Path of the file to parse.
	 */
	function _parse($parse_name)
	{
		if ($this->return_mode)
		{
			$this->template = '<?php $tplString = \'' . str_replace(array('\\', '\''), array('\\\\', '\\\''), $this->template) . '\'; ?>';
			//Remplacement des variables simples.
			$this->template = preg_replace('`{([\w]+)}`i', '\'; if (isset($this->_var[\'$1\'])) $tplString .= $this->_var[\'$1\']; $tplString .=\'', $this->template);
			$this->template = preg_replace_callback('`{([\w\.]+)}`i', array($this, '_parse_blocks_vars'), $this->template);

			//Parse des blocs imbriqués ou non.
			$this->template = preg_replace_callback('`# START ([\w\.]+) #`', array($this, '_parse_blocks'), $this->template);
			$this->template = preg_replace('`# END [\w\.]+ #`', '\';'."\n".'}'."\n".'$tplString .= \'', $this->template);

			//Remplacement des blocs conditionnels.
			$this->template = preg_replace_callback('`# IF (NOT )?([\w\.]+) #`', array($this, '_parse_conditionnal_blocks'), $this->template);
			$this->template = preg_replace_callback('`# ELSEIF (NOT )?([\w\.]+) #`', array($this, '_parse_conditionnal_blocks_bis'), $this->template);
			$this->template = preg_replace('`# ELSE #`', '\';'."\n".'} else {'."\n".'$tplString .= \'', $this->template);
			$this->template = preg_replace('`# ENDIF #`', '\';'."\n".'}'."\n".'$tplString .= \'', $this->template);

			//Remplacement des includes.
			$this->template = preg_replace('`# INCLUDE ([\w]+) #`', '\';'."\n".'$tplString .= $this->_include(\'$1\');'."\n".'$tplString .= \'', $this->template);
            
			$this->template = preg_replace_callback('`(?<!^)<\?php(.*)\?>(?!$)`isU', array($this, '_accept_php_block'), $this->template);
		}
		else
		{
			// Protection des tags XML
			$this->template = preg_replace_callback('`\<\?(?!php)(\s.*)\?\>`i', array($this, '_protect_from_inject'), $this->template);

			//Remplacement des variables simples.
			$this->template = preg_replace('`{([\w]+)}`i', '<?php if (isset($this->_var[\'$1\'])) echo $this->_var[\'$1\']; ?>', $this->template);
			$this->template = preg_replace_callback('`{([\w\.]+)}`i', array($this, '_parse_blocks_vars'), $this->template);

			//Parse des blocs imbriqués ou non.
			$this->template = preg_replace_callback('`# START ([\w\.]+) #`', array($this, '_parse_blocks'), $this->template);
			$this->template = preg_replace('`# END [\w\.]+ #`', '<?php } ?>', $this->template);

			//Remplacement des blocs conditionnels.
			$this->template = preg_replace_callback('`# IF (NOT )?([\w\.]+) #`', array($this, '_parse_conditionnal_blocks'), $this->template);
			$this->template = preg_replace_callback('`# ELSEIF (NOT )?([\w\.]+) #`', array($this, '_parse_conditionnal_blocks_bis'), $this->template);
			$this->template = preg_replace('`# ELSE #`', '<?php } else { ?>', $this->template);
			$this->template = preg_replace('`# ENDIF #`', '<?php } ?>', $this->template);

			//Remplacement des includes.
			$this->template = preg_replace('`# INCLUDE ([\w]+) #`', '<?php $this->_include(\'$1\'); ?>', $this->template);
		}
	}

	function _accept_php_block($mask)
	{
		return '\';' . str_replace(array('\\\\', '\\\''), array('\\', '\''), $mask[1]) . '$tplString.=\'';
	}
	
	/**
	 * @desc  Protects a string from PHP syntax errors.
	 * @param string[] $mask Particular matched syntax element containing in the key 1 the string to protect.
	 * @return The protected string.
	 */
	function _protect_from_inject($mask)
	{
		return '<?php echo \'<?' . str_replace(array('\\', '\''), array('\\\\', '\\\''), trim($mask[1])) . '?>\'; ?>';
	}

	/**
	 * @desc Parses a template loop var.
	 * @param string[] $blocks Particular matched syntax element.
	 * @return string The PHPBoost cache file corresponding syntax.
	 */
	function _parse_blocks_vars($blocks)
	{
		if (isset($blocks[1]))
		{
			$array_block = explode('.', $blocks[1]);
			$varname = array_pop($array_block);
			$last_block = array_pop($array_block);

			if ($this->return_mode)
			{
				return '\'; if (isset($_tmpb_' . $last_block . '[\'' . $varname . '\'])) $tplString .= $_tmpb_' . $last_block . '[\'' . $varname . '\']; $tplString .= \'';
			}
			else
			{
				return '<?php if (isset($_tmpb_' . $last_block . '[\'' . $varname . '\'])) echo $_tmpb_' . $last_block . '[\'' . $varname . '\']; ?>';
			}
		}
		return '';
	}

	/**
	 * @desc Parses a template loop (block).
	 * @param string[] $blocks Particular matched syntax element.
	 * @return string The PHPBoost cache file corresponding syntax.
	 */
	function _parse_blocks($blocks)
	{
		if (isset($blocks[1]))
		{
			if (strpos($blocks[1], '.') !== false) //Contient un bloc imbriqué.
			{
				$array_block = explode('.', $blocks[1]);
				$current_block = array_pop($array_block);
				$previous_block = array_pop($array_block);

				if ($this->return_mode)
				{
					return '\';'."\n".'if (!isset($_tmpb_' . $previous_block . '[\'' . $current_block . '\']) || !is_array($_tmpb_' . $previous_block . '[\'' . $current_block . '\'])) $_tmpb_' . $previous_block . '[\'' . $current_block . '\'] = array();' . "\n" . 'foreach ($_tmpb_' . $previous_block . '[\'' . $current_block . '\'] as $' . $current_block . '_key => $' . $current_block . '_value) {' . "\n" . '$_tmpb_' . $current_block . ' = &$_tmpb_' . $previous_block . '[\'' . $current_block . '\'][$' . $current_block . '_key];'."\n".'$tplString .= \'';
				}
				else
				{
					return '<?php if (!isset($_tmpb_' . $previous_block . '[\'' . $current_block . '\']) || !is_array($_tmpb_' . $previous_block . '[\'' . $current_block . '\'])) $_tmpb_' . $previous_block . '[\'' . $current_block . '\'] = array();' . "\n" . 'foreach ($_tmpb_' . $previous_block . '[\'' . $current_block . '\'] as $' . $current_block . '_key => $' . $current_block . '_value) {' . "\n" . '$_tmpb_' . $current_block . ' = &$_tmpb_' . $previous_block . '[\'' . $current_block . '\'][$' . $current_block . '_key]; ?>';
				}
			}
			else
			{
				if ($this->return_mode)
				{
					return '\';'."\n".'if (!isset($this->_block[\'' . $blocks[1] . '\']) || !is_array($this->_block[\'' . $blocks[1] . '\'])) $this->_block[\'' . $blocks[1] . '\'] = array();' . "\n" . 'foreach ($this->_block[\'' . $blocks[1] . '\'] as $' . $blocks[1] . '_key => $' . $blocks[1] . '_value) {' . "\n" . '$_tmpb_' . $blocks[1] . ' = &$this->_block[\'' . $blocks[1] . '\'][$' . $blocks[1] . '_key];'."\n".'$tplString .= \'';
				}
				else
				{
					return '<?php if (!isset($this->_block[\'' . $blocks[1] . '\']) || !is_array($this->_block[\'' . $blocks[1] . '\'])) $this->_block[\'' . $blocks[1] . '\'] = array();' . "\n" . 'foreach ($this->_block[\'' . $blocks[1] . '\'] as $' . $blocks[1] . '_key => $' . $blocks[1] . '_value) {' . "\n" . '$_tmpb_' . $blocks[1] . ' = &$this->_block[\'' . $blocks[1] . '\'][$' . $blocks[1] . '_key]; ?>';
				}
			}
		}
		return '';
	}

	/**
	 * @desc Parses the condition tests.
	 * @param string[] $blocks Particular matched syntax element.
	 * @return string The PHPBoost cache file corresponding syntax.
	 */
	function _parse_conditionnal_blocks($blocks)
	{
		if (isset($blocks[2]))
		{
			$not = ($blocks[1] == 'NOT ' ? '!' : '');
			if (strpos($blocks[2], '.') !== false) //Contient un bloc imbriqué.
			{
				$array_block = explode('.', $blocks[2]);
				$varname = array_pop($array_block);
				$last_block = array_pop($array_block);

				if ($this->return_mode)
				{
					return '\';'."\n".'if (isset($_tmpb_' . $last_block . '[\'' . $varname . '\']) && ' . $not . '$_tmpb_' . $last_block . '[\'' . $varname . '\']) {'."\n".'$tplString .= \'';
				}
				else
				{
					return '<?php if (isset($_tmpb_' . $last_block . '[\'' . $varname . '\']) && ' . $not . '$_tmpb_' . $last_block . '[\'' . $varname . '\']) { ?>';
				}
			}
			else
			{
				if ($this->return_mode)
				{
					return '\';'."\n".'if (isset($this->_var[\'' . $blocks[2] . '\']) && ' . $not . '$this->_var[\'' . $blocks[2] . '\']) {'."\n".'$tplString .= \'';
				}
				else
				{
					return '<?php if (isset($this->_var[\'' . $blocks[2] . '\']) && ' . $not . '$this->_var[\'' . $blocks[2] . '\']) { ?>';
				}
			}
		}
		return '';
	}

	/**
	 * @desc Parses the condition tests.
	 * @param string[] $blocks Particular matched syntax element.
	 * @return string The PHPBoost cache file corresponding syntax.
	 */
	function _parse_conditionnal_blocks_bis($blocks)
	{
		if (isset($blocks[2]))
		{
			$not = ($blocks[1] == 'NOT ' ? '!' : '');
			if (strpos($blocks[2], '.') !== false) //Contient un bloc imbriqué.
			{
				$array_block = explode('.', $blocks[2]);
				$varname = array_pop($array_block);
				$last_block = array_pop($array_block);

				if ($this->return_mode)
				return '\';'."\n".'} elseif (isset($_tmpb_' . $last_block . '[\'' . $varname . '\']) && ' . $not . '$_tmpb_' . $last_block . '[\'' . $varname . '\']) {'."\n".'$tplString .= \'';
				else
				return '<?php } elseif (isset($_tmpb_' . $last_block . '[\'' . $varname . '\']) && ' . $not . '$_tmpb_' . $last_block . '[\'' . $varname . '\']) { ?>';
			}
			else
			{
				if ($this->return_mode)
				return '\';'."\n".'} elseif (isset($this->_var[\'' . $blocks[2] . '\']) && ' . $not . '$this->_var[\'' . $blocks[2] . '\']) {'."\n".'$tplString .= \'';
				else
				return '<?php } elseif (isset($this->_var[\'' . $blocks[2] . '\']) && ' . $not . '$this->_var[\'' . $blocks[2] . '\']) { ?>';
			}
		}
		return '';
	}

	/**
	 * @desc Cleans all the elements in a template file which mustn't be present after the parsing (for instance the comments).
	 */
	function _clean()
	{
		$this->template = preg_replace(
		array('`# START [\w\.]+ #(.*)# END [\w\.]+ #`s', '`# START [\w\.]+ #`', '`# END [\w\.]+ #`', '`{[\w\.]+}`'),
		array('', '', '', ''),
		$this->template);

		//Evite à l'interpréteur PHP du travail inutile.
		if ($this->return_mode)
		{
			$this->template = str_replace('$tplString .= \'\';', '', $this->template);
			$this->template = preg_replace(array('`[\n]{2,}`', '`[\r]{2,}`', '`[\t]{2,}`', '`[ ]{2,}`'), array('', '', '', ''), $this->template);
		}
		else
		{
			$this->template = preg_replace('` \?><\?php `', '', $this->template);
			$this->template = preg_replace('` \?>[\s]+<\?php `', "echo ' ';", $this->template);
			$this->template = preg_replace("`echo ' ';echo `", "echo ' ' . ", $this->template);
			$this->template = preg_replace("`''\);echo `", "'') . ", $this->template);
		}
	}

	/**
	 * @desc Writes the file cache.
	 * @param string $file_cache_path Path where the file must be written.
	 */
	function _save($file_cache_path)
	{
		import('io/filesystem/file');
		$file = new File($file_cache_path);
		$file->open(WRITE);
		$file->lock();
		$file->write($this->template);
		$file->unlock();
		$file->close();
		$file->change_chmod(0666);
	}


	## Private Attribute ##
	/**
	* @var string Template file name
	*/
	var $tpl = '';

	/**
	 * @var string Content of the cache file corresponding to a loaded file just before it to be cached.
	 */
	var $template = '';

	/**
	 * @var string[] Map file_identifier => file_path. The paths have been checked before to be entered in this map.
	 */
	var $files = array();

	/**
	 * @var string[] Map module_name => module_data_path
	 */
	var $module_data_path = array();

	/**
	 * @var bool Parsing type of the current parsing operation. True if it's in string mode, false in write mode.
	 */
	var $return_mode;

	/**
	 * @var string[] Map containing the simple variables assignations.
	 */
	var $_var = array();

	/**
	 * @var string[] Map containing the block assignations.
	 */
	var $_block = array(); //Tableau contenant les variables de remplacement des variables simples.
}

?>
