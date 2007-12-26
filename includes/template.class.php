<?php
/*##################################################
 *                              template.php
 *                            -------------------
 *   Update            : August, 12 2005
 *   copyright        : (C) 2005 Viarre Régis
 *   email               : crowkait@phpboost.com
 *
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
###################################################
 *
 * Template class. By Nathan Codding of the phpBB group.
 * The interface was originally inspired by PHPLib templates,
 * and the template file formats are quite similar.
 *
##################################################*/
 
class Templates {
	var $data = array();
	var $files = array();
	var $compiled_code = array();
	var $uncompiled_code = array();
	var $module_data_path = array(); //Tableau contenant les chemins de base des modules chargés.
	
	function Templates() //Constructeur.
	{
		return;
	}	
	
	function set_filenames($filename_array)
	{
		if( is_array($filename_array) )
		{
			foreach( $filename_array as $handle => $filename )
				$this->files[$handle] = $this->check_file($filename);
			return true;
		}
		else
			return false;
	}
	
	//Vérification de l'existence du tpl perso, sinon tentative de chargement du tpl de base.
	function check_file($filename)
	{
		global $CONFIG;
				
		$get_module = explode('/', $filename);
		if( !isset($get_module[4]) ) //Template du thème.
			return $filename;
		
		//Tpl par défaut.
		$module_path = '../' . $get_module[3] . '/templates';
		
		//Test sur le tpl d'un module.
		if( is_dir('../templates/' . $CONFIG['theme'] . '/' . $get_module[3]) ) //Tpl perso du module présent sur le serveur!?
		{	
			$this->module_data_path[$get_module[3]] = '../templates/' . $CONFIG['theme'] . '/' . $get_module[3];
			if( file_exists($filename) )
				return $filename;			
		}
		else //Chemin des données par défaut.
			$this->module_data_path[$get_module[3]] = $module_path;
		
		//Chargement de fichier par défaut du module.	
		return $module_path . '/' . $get_module[4];
	}
	
	//Récupération du chemin des données du module.
	function module_data_path($module)
	{
		if( isset($this->module_data_path[$module]) )
			return $this->module_data_path[$module];
		return '';
	}
	
	function loadfile($handle)
	{
		if( isset($this->uncompiled_code[$handle]) && !empty($this->uncompiled_code[$handle]) )
			return true;

		if( !isset($this->files[$handle]) )
			die('Template->loadfile(): Aucun fichier specifié pour handle ' . $handle);

		$filename = $this->files[$handle];
		$str = @file_get_contents_emulate($filename);
		if( $str !== false && empty($str) )
			die('Template->loadfile(): Le chargement du fichier ' . $filename . ' pour handle ' . $handle . ' a échoué');

		$this->uncompiled_code[$handle] = $str;

		return true;
	}
	
	function pparse($handle)
	{
		if( !$this->loadfile($handle) )
			die('Chargement du Template ' . $handle . ' impossible');

		if( !isset($this->compiled_code[$handle]) || empty($this->compiled_code[$handle]) )
			$this->compiled_code[$handle] = $this->compile($this->uncompiled_code[$handle]);

		eval($this->compiled_code[$handle]);
		return true;
	}

	function assign_var_from_handle($varname, $handle)
	{
		if( !$this->loadfile($handle) )
			die('Template->assign_var_from_handle(): Chargement du Template pour handle ' . $handle . ' impossible');

		$_str = "";
		$code = $this->compile($this->uncompiled_code[$handle], true, '_str');

		eval($code);
		$this->assign_var($varname, $_str);

		return true;
	}

	function assign_block_vars($blockname, $vararray)
	{
		if( strstr($blockname, '.') )
		{
			$blocks = explode('.', $blockname);
			$blockcount = count($blocks) - 1;
			$str = '$this->data';
			for ($i = 0; $i < $blockcount; $i++)
			{
				$str .= '[\'' . $blocks[$i] . '.\']';
				eval('$lastiteration = count(' . $str . ') - 1;');
				$str .= '[' . $lastiteration . ']';
			}

			$str .= '[\'' . $blocks[$blockcount] . '.\'][] = $vararray;';

			eval($str);
		}
		else
			$this->data[$blockname . '.'][] = $vararray;

		return true;
	}

	function assign_vars($vararray) //On explore le array associatif pour recuperer les variables.
	{
		if( is_array($vararray) )
		{
			foreach( $vararray as $key => $val ) 
				$this->data['.'][0][$key] = $val;
			return true;
		}
		else
			return false;
	}

	function assign_var($varname, $varval)
	{
		$this->data['.'][0][$varname] = $varval;

		return true;
	}

	function unassign_block_vars($blockname)
	{
		if( isset($this->data[$blockname . '.']) )
			unset($this->data[$blockname . '.']);

		return true;
	}
	
	function compile($code, $do_not_echo = false, $retvar = '')
	{
		$code = str_replace('\\', '\\\\', $code);
		$code = str_replace('\'', '\\\'', $code);

		$varrefs = array();
		preg_match_all('#\{(([a-z0-9\-_]+?\.)+?)([a-z0-9\-_]+?)\}#is', $code, $varrefs);
		$varcount = count($varrefs[1]);
		for($i = 0; $i < $varcount; $i++)
		{
			$namespace = $varrefs[1][$i];
			$varname = $varrefs[3][$i];
			$new = $this->generate_block_varref($namespace, $varname);

			$code = str_replace($varrefs[0][$i], $new, $code);
		}

		$code = preg_replace('#\{([a-z0-9\-_]*?)\}#is', '\' . ( ( isset($this->data[\'.\'][0][\'\1\']) ) ? $this->data[\'.\'][0][\'\1\'] : \'\' ) . \'', $code);

		$code_lines = explode("\n", $code);

		$block_nesting_level = 0;
		$block_names = array();
		$block_names[0] = ".";

		$line_count = count($code_lines);
		for($i = 0; $i < $line_count; $i++)
		{
			$code_lines[$i] = rtrim($code_lines[$i]);
			if (preg_match('# START (.*?) #', $code_lines[$i], $m)) //On recherche dans le templates pour executer une boucle.
			{
				$n[0] = $m[0];
				$n[1] = $m[1];

				if ( preg_match('# END (.*?) #', $code_lines[$i], $n) )
				{
					$block_nesting_level++;
					$block_names[$block_nesting_level] = $m[1];
					if ($block_nesting_level < 2)
					{
						$code_lines[$i] = '$_' . $n[1] . '_count = ( isset($this->data[\'' . $n[1] . '.\']) ) ?  count($this->data[\'' . $n[1] . '.\']) : 0;';
						$code_lines[$i] .= "\n" . 'for ($_' . $n[1] . '_i = 0; $_' . $n[1] . '_i < $_' . $n[1] . '_count; $_' . $n[1] . '_i++)';
						$code_lines[$i] .= "\n" . '{';
					}
					else
					{
						$namespace = implode('.', $block_names);
						$namespace = substr($namespace, 2);

						$varref = $this->generate_block_data_ref($namespace, false);

						$code_lines[$i] = '$_' . $n[1] . '_count = ( isset(' . $varref . ') ) ? count(' . $varref . ') : 0;';
						$code_lines[$i] .= "\n" . 'for ($_' . $n[1] . '_i = 0; $_' . $n[1] . '_i < $_' . $n[1] . '_count; $_' . $n[1] . '_i++)';
						$code_lines[$i] .= "\n" . '{';
					}


					unset($block_names[$block_nesting_level]);
					$block_nesting_level--;
					$code_lines[$i] .= '} // END ' . $n[1];
					$m[0] = $n[0];
					$m[1] = $n[1];
				}
				else
				{
					$block_nesting_level++;
					$block_names[$block_nesting_level] = $m[1];
					if ($block_nesting_level < 2)
					{
						$code_lines[$i] = '$_' . $m[1] . '_count = ( isset($this->data[\'' . $m[1] . '.\']) ) ? count($this->data[\'' . $m[1] . '.\']) : 0;';
						$code_lines[$i] .= "\n" . 'for ($_' . $m[1] . '_i = 0; $_' . $m[1] . '_i < $_' . $m[1] . '_count; $_' . $m[1] . '_i++)';
						$code_lines[$i] .= "\n" . '{';
					}
					else
					{
						$namespace = implode('.', $block_names);
						$namespace = substr($namespace, 2);

						$varref = $this->generate_block_data_ref($namespace, false);

						$code_lines[$i] = '$_' . $m[1] . '_count = ( isset(' . $varref . ') ) ? count(' . $varref . ') : 0;';
						$code_lines[$i] .= "\n" . 'for ($_' . $m[1] . '_i = 0; $_' . $m[1] . '_i < $_' . $m[1] . '_count; $_' . $m[1] . '_i++)';
						$code_lines[$i] .= "\n" . '{';
					}
				}
			}
			elseif( preg_match('# END (.*?) #', $code_lines[$i], $m) )
			{
				unset($block_names[$block_nesting_level]);
				$block_nesting_level--;
				$code_lines[$i] = '} // END ' . $m[1];
			}
			else
			{
				if (!$do_not_echo)
					$code_lines[$i] = 'echo \'' . $code_lines[$i] . '\' . "\\n";';
				else
					$code_lines[$i] = '$' . $retvar . '.= \'' . $code_lines[$i] . '\' . "\\n";'; 
			}
		}

		$code = implode("\n", $code_lines);
		return $code;

	}

	function generate_block_varref($namespace, $varname)
	{
		$namespace = substr($namespace, 0, strlen($namespace) - 1);
		$varref = $this->generate_block_data_ref($namespace, true);
		$varref .= '[\'' . $varname . '\']';
		$varref = '\' . ( ( isset(' . $varref . ') ) ? ' . $varref . ' : \'\' ) . \'';

		return $varref;
	}

	function generate_block_data_ref($blockname, $include_last_iterator)
	{
		$blocks = explode(".", $blockname);
		$blockcount = count($blocks) - 1;
		$varref = '$this->data';

		for($i = 0; $i < $blockcount; $i++)
			$varref .= '[\'' . $blocks[$i] . '.\'][$_' . $blocks[$i] . '_i]';

		$varref .= '[\'' . $blocks[$blockcount] . '.\']';

		if($include_last_iterator)
			$varref .= '[$_' . $blocks[$blockcount] . '_i]';

			return $varref;
	}
}

?>