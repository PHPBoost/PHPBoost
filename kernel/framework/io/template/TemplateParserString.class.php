<?php
/*##################################################
 *                           template_parser_string.class.php
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



class TemplateParserString extends AbstractTemplateParser
{
	const TPL_VAR_STRING = '$this->resource';
	
	protected function compute_cache_filepath()
	{
		$this->cache_filepath = PATH_TO_ROOT . '/cache/tpl/' . trim(str_replace(
			array('/', '.', '..', 'tpl', 'templates'),
			array('_', '', '', '', 'tpl'),
			$this->template->get_identifier()
		), '_') . '_str.php';
	}
	
	protected function do_parse()
	{
		$this->prepare_parse();
		$this->parse_vars();
		$this->parse_imbricated_blocks();
		$this->parse_conditional_blocks();
		$this->parse_includes();
	}
	
	protected function execute()
	{
		include($this->cache_filepath);
	}
	
	protected function optimize()
	{
		$this->content = str_replace(self::TPL_VAR_STRING . ' .= \'\';', '', $this->content);
		$this->content = preg_replace(array('`[\n]{2,}`', '`[\r]{2,}`', '`[\t]{2,}`', '`[ ]{2,}`'), array('', '', '', ''), $this->content);
	}
	
	private function prepare_parse()
	{
		$this->content = '<?php ' . self::TPL_VAR_STRING . ' = \'' . str_replace(array('\\', '\''), array('\\\\', '\\\''), $this->content) . '\'; ?>';
	}
	
	private function parse_vars()
	{
		$this->content = preg_replace_callback('`{([\w]+)}`i', array($this, 'callback_parse_vars'), $this->content);
		$this->content = preg_replace_callback('`{([\w\.]+)}`i', array($this, 'callback_parse_blocks_vars'), $this->content);
	}
	
	private function parse_imbricated_blocks()
	{
		$this->content = preg_replace_callback('`# START ([\w\.]+) #`', array($this, 'callback_parse_blocks'), $this->content);
		$this->content = preg_replace('`# END [\w\.]+ #`', '\';'."\n".'}'."\n".'' . self::TPL_VAR_STRING . ' .= \'', $this->content);
	}
	
	private function parse_conditional_blocks()
	{
		$this->content = preg_replace_callback('`# IF (NOT )?([\w\.]+) #`', array($this, 'callback_parse_if_blocks'), $this->content);
		$this->content = preg_replace_callback('`# ELSEIF (NOT )?([\w\.]+) #`', array($this, 'callback_parse_elseif_blocks'), $this->content);
		$this->content = preg_replace('`# ELSE #`', '\';}else{' . self::TPL_VAR_STRING . '.=\'', $this->content);
		$this->content = preg_replace('`# ENDIF #`', '\';}' . self::TPL_VAR_STRING . '.=\'', $this->content);
	}
	
	private function parse_includes()
	{
		$this->content = preg_replace('`# INCLUDE ([\w]+) #`', '\';' .
			'$_subtemplate = $this->template->get_subtemplate(\'$1\');' . "\n" .
			'if ($_subtemplate !== null){' . self::TPL_VAR_STRING .
			'.=$_subtemplate->parse(Template::TEMPLATE_PARSER_STRING);}' . self::TPL_VAR_STRING .
			'.=\'', $this->content);
		$this->content = preg_replace_callback('`# INCLUDE ([\w.]+)\.([\w]+) #`',
		array($this, 'callback_parse_blocks_includes'), $this->content);
	}
	
	private function callback_parse_vars($varname)
	{
		$method_var = $this->get_getvar_method_name($varname[1]);
		return '\' . $this->template->' . $method_var['method'] . '(\'' . $method_var['varname'] . '\') . \'';
	}
	
	private function callback_parse_blocks_vars($blocks)
	{
		$array_block = explode('.', $blocks[1]);
		$varname = array_pop($array_block);
		$last_block = array_pop($array_block);
		
		$method_var = $this->get_getvar_method_name($varname);
		return '\' . $this->template->' . $method_var['method'] . '_from_list(\'' . $method_var['varname'] . '\', $_tmp_' . $last_block . '_value[\'vars\']) . \'';
	}
	
	private function callback_parse_blocks($blocks)
	{
		$second_param = '';
		$blockname =& $blocks[1];
		$method = 'get_block';
		if (strpos($blockname, '.') !== false) //Contient un bloc imbriqué.
		{
			$array_block = explode('.', $blockname);
			$blockname = array_pop($array_block);
			$previous_block = array_pop($array_block);
				
			$second_param =', $_tmp_' . $previous_block . '_value';
			$method .= '_from_list';
		}
		return '\'; foreach ($this->template->' . $method .'(\'' . $blockname. '\'' . $second_param .') as $_tmp_' . $blockname . '_value) {' . self::TPL_VAR_STRING . ' .= \'';
	}
	
	private function callback_parse_if_blocks($blocks)
	{
		return $this->parse_conditional_block($blocks, 'if');
	}
	
	private function callback_parse_elseif_blocks($blocks)
	{
		return $this->parse_conditional_block($blocks, 'elseif');
	}
	
	private function parse_conditional_block($blocks, $block_type)
	{
		$varname = $blocks[2];
		$second_param = '';
		$method = 'is_true';
		$not = ($blocks[1] == 'NOT ' ? '!' : '');
		if (strpos($blocks[2], '.') !== false) //Contient un bloc imbriqué.
		{
			$array_block = explode('.', $blocks[2]);
			$varname = array_pop($array_block);
			$last_block = array_pop($array_block);
			
			$second_param = ', $_tmp_' . $last_block . '_value[\'vars\']';
			$method .= '_from_list';
		}
		return '\'; ' . $block_type . ' (' . $not . '$this->template->' . $method .'(\'' . $varname . '\'' . $second_param . ')) {' . self::TPL_VAR_STRING . ' .= \'';
	}

	private function callback_parse_blocks_includes($blocks)
	{
		$varname = $blocks[2];

		$array_block = explode('.', $blocks[1]);
		$second_param = '$_tmp_' .  array_pop($array_block) . '_value[\'subtemplates\']';
		return '\';$_subtemplate = $this->template->get_subtemplate_from_list(\'' . $varname .
			'\', ' . $second_param . ');' . "\n" .
			'if ($_subtemplate !== null){' . self::TPL_VAR_STRING .
			'.=$_subtemplate->parse(Template::TEMPLATE_PARSER_STRING);}' . self::TPL_VAR_STRING .
			'.=\'';
	}
}

?>