<?php
/*##################################################
 *                           view.class.php
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

//import('io/template');

interface ViewParser
{
	public function parse($template_object, $filepath);
}

abstract class AbstractViewParser implements ViewParser
{
	public function parse($template_object, $filepath)
	{
		$this->tpl = $template_object;
		$this->filepath = $filepath;
		$this->compute_cache_filepath();
		if (!$this->is_in_cache())
		{
			$this->generate_cache();
		}
		$this->execute();
	}
	
	protected abstract function compute_cache_filepath();
	
	private function is_in_cache()
	{	
		if (file_exists($this->cache_filepath))
		{
			return @filemtime($this->filepath) <= @filemtime($this->cache_filepath) && @filesize($this->cache_filepath) !== 0;
		}
		return false;
	}
	
	private function generate_cache()
	{
		$this->load();
		$this->do_parse();
		$this->clean();
		$this->optimize();
		$this->save();
	}
	
	protected function execute()
	{
		include($this->cache_filepath);
	}
	
	private function load()
	{
		$this->template = @file_get_contents_emulate($this->filepath);
		if ($this->template === false)
		{
			die('Template::load(): The ' . $this->filepath . ' template loading failed.');
		}
		if (empty($this->template))
		{
			die('Template::load(): The ' . $this->filepath . ' template is empty.');
		}
		
		return true;
	}
	
	protected abstract function do_parse();
	
	protected function clean()
	{
		$this->template = preg_replace(
			array('`# START [\w\.]+ #(.*)# END [\w\.]+ #`s', '`# START [\w\.]+ #`', '`# END [\w\.]+ #`', '`{[\w\.]+}`'),
			array('', '', '', ''),
			$this->template
		);
	}
	
	protected function optimize()
	{
	}
	
	private function save()
	{
		import('io/filesystem/file');
		$file = new File($this->cache_filepath);
		$file->open(WRITE);
		$file->lock();
		$file->write($this->template);
		$file->unlock();
		$file->close();
		$file->change_chmod(0666);
	}
	
	protected $filepath;
	protected $cache_filepath;
	protected $template;
	protected $tpl;
}

class ViewParserEcho extends AbstractViewParser
{
	protected function compute_cache_filepath()
	{
		$this->cache_filepath = PATH_TO_ROOT . '/cache/tpl/' . trim(str_replace(
			array('/', '.', '..', 'tpl', 'templates'),
			array('_', '', '', '', 'tpl'),
			$this->filepath
		), '_') . '.php';
	}
	
	protected function do_parse()
	{
		$this->parse_xml();
		$this->parse_vars();
		$this->parse_imbricated_blocks();
		$this->parse_conditional_blocks();
		$this->parse_includes();
	}
	
	protected function optimize()
	{
		$this->template = preg_replace('` \?><\?php `', '', $this->template);
		$this->template = preg_replace('` \?>[\s]+<\?php `', "echo ' ';", $this->template);
		$this->template = preg_replace("`echo ' ';echo `", "echo ' ' . ", $this->template);
		$this->template = preg_replace("`''\);echo `", "'') . ", $this->template);
	}
	
	private function parse_xml()
	{
		$this->template = preg_replace_callback('`\<\?(?!php)(\s.*)\?\>`i', array($this, 'callback_accept_xml'), $this->template);
	}
	
	private function parse_vars()
	{
		$this->template = preg_replace('`{([\w]+)}`i', '<?php echo $this->tpl->get_var(\'$1\'); ?>', $this->template);
		$this->template = preg_replace_callback('`{([\w\.]+)}`i', array($this, 'callback_parse_blocks_vars'), $this->template);
	}
	
	private function parse_imbricated_blocks()
	{
		$this->template = preg_replace_callback('`# START ([\w\.]+) #`', array($this, 'callback_parse_blocks'), $this->template);
		$this->template = preg_replace('`# END [\w\.]+ #`', '<?php } ?>', $this->template);
	}
	
	private function parse_conditional_blocks()
	{
		$this->template = preg_replace_callback('`# IF (NOT )?([\w\.]+) #`', array($this, 'callback_parse_conditionnal_blocks'), $this->template);
		$this->template = preg_replace_callback('`# ELSEIF (NOT )?([\w\.]+) #`', array($this, 'callback_parse_conditionnal_blocks_bis'), $this->template);
		$this->template = preg_replace('`# ELSE #`', '<?php } else { ?>', $this->template);
		$this->template = preg_replace('`# ENDIF #`', '<?php } ?>', $this->template);
	}
	
	private function parse_includes()
	{
		$this->template = preg_replace('`# INCLUDE ([\w]+) #`', '<?php $this->tpl->_include(\'$1\'); ?>', $this->template);
	}
	
	private function callback_accept_xml($mask)
	{
		return '<?php echo \'<?' . str_replace(array('\\', '\''), array('\\\\', '\\\''), trim($mask[1])) . '?>\'; ?>';
	}
	
	private function callback_parse_blocks_vars($blocks)
	{
		if (isset($blocks[1]))
		{
			$array_block = explode('.', $blocks[1]);
			$varname = array_pop($array_block);
			$last_block = array_pop($array_block);
			
			return '<?php echo $this->tpl->get_var_from_list(\'' . $varname . '\', $_tmp_' . $last_block . '_value); ?>';
		}
		return '';
	}
	
	private function callback_parse_blocks($blocks)
	{
		if (isset($blocks[1]))
		{
			if (strpos($blocks[1], '.') !== false) //Contient un bloc imbriqué.
			{
				$array_block = explode('.', $blocks[1]);
				$current_block = array_pop($array_block);
				$previous_block = array_pop($array_block);

				return '<?php foreach ($_tmp_' . $previous_block . '_value[\'' . $current_block . '\'] as $_tmp_' . $current_block . '_value) {?>';
			}
			else
			{
				return '<?php foreach ($this->tpl->get_simple_block(\'' . $blocks[1] . '\') as $_tmp_' . $blocks[1] . '_value) {?>';
			}
		}
		return '';
	}
	
	private function callback_parse_conditionnal_blocks($blocks)
	{
		if (isset($blocks[2]))
		{
			$not = ($blocks[1] == 'NOT ' ? '!' : '');
			if (strpos($blocks[2], '.') !== false) //Contient un bloc imbriqué.
			{
				$array_block = explode('.', $blocks[2]);
				$varname = array_pop($array_block);
				$last_block = array_pop($array_block);
				
				return '<?php if (isset($_tmpb_' . $last_block . '[\'' . $varname . '\']) && ' . $not . '$_tmpb_' . $last_block . '[\'' . $varname . '\']) { ?>';
			}
			else
			{
				return '<?php if (isset($this->_var[\'' . $blocks[2] . '\']) && ' . $not . '$this->_var[\'' . $blocks[2] . '\']) { ?>';
			}
		}
		return '';
	}
	
	private function callback_parse_conditionnal_blocks_bis($blocks)
	{
		if (isset($blocks[2]))
		{
			$not = ($blocks[1] == 'NOT ' ? '!' : '');
			if (strpos($blocks[2], '.') !== false) //Contient un bloc imbriqué.
			{
				$array_block = explode('.', $blocks[2]);
				$varname = array_pop($array_block);
				$last_block = array_pop($array_block);
				
				return '<?php } elseif (isset($_tmpb_' . $last_block . '[\'' . $varname . '\']) && ' . $not . '$_tmpb_' . $last_block . '[\'' . $varname . '\']) { ?>';
			}
			else
			{
				return '<?php } elseif (isset($this->_var[\'' . $blocks[2] . '\']) && ' . $not . '$this->_var[\'' . $blocks[2] . '\']) { ?>';
			}
		}
		return '';
	}
}

class ViewParserString extends AbstractViewParser
{
	protected function compute_cache_filepath()
	{
		$this->cache_filepath = PATH_TO_ROOT . '/cache/tpl/' . trim(str_replace(
			array('/', '.', '..', 'tpl', 'templates'),
			array('_', '', '', '', 'tpl'),
			$this->filepath
		), '_') . '_str.php';
	}
	
	protected function do_parse()
	{
		
	}
	
	protected function optimize()
	{
		$this->template = str_replace('$tplString .= \'\';', '', $this->template);
		$this->template = preg_replace(array('`[\n]{2,}`', '`[\r]{2,}`', '`[\t]{2,}`', '`[ ]{2,}`'), array('', '', '', ''), $this->template);
		
	}
}

?>