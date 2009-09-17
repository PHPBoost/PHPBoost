<?php
/*##################################################
 *                           template_parser_echo.class.php
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

import('io/template/abstract_template_parser');

class TemplateParserEcho extends AbstractTemplateParser
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
	
	protected function execute()
	{
		include($this->cache_filepath);
	}
	
	protected function optimize()
	{
		$this->content = preg_replace('` \?><\?php `', '', $this->content);
		$this->content = preg_replace('` \?>[\s]+<\?php `', "echo ' ';", $this->content);
		$this->content = preg_replace("`echo ' ';echo `", "echo ' ' . ", $this->content);
		$this->content = preg_replace("`''\);echo `", "'') . ", $this->content);
	}
	
	private function parse_xml()
	{
		$this->content = preg_replace_callback('`\<\?(?!php)(\s.*)\?\>`i', array($this, 'callback_accept_xml'), $this->content);
	}
	
	private function parse_vars()
	{
		$this->content = preg_replace('`{([\w]+)}`i', '<?php echo $this->template->get_var(\'$1\'); ?>', $this->content);
		$this->content = preg_replace_callback('`{([\w\.]+)}`i', array($this, 'callback_parse_blocks_vars'), $this->content);
	}
	
	private function parse_imbricated_blocks()
	{
		$this->content = preg_replace_callback('`# START ([\w\.]+) #`', array($this, 'callback_parse_blocks'), $this->content);
		$this->content = preg_replace('`# END [\w\.]+ #`', '<?php } ?>', $this->content);
	}
	
	private function parse_conditional_blocks()
	{
		$this->content = preg_replace_callback('`# IF (NOT )?([\w\.]+) #`', array($this, 'callback_parse_if_blocks'), $this->content);
		$this->content = preg_replace_callback('`# ELSEIF (NOT )?([\w\.]+) #`', array($this, 'callback_parse_elseif_blocks'), $this->content);
		$this->content = preg_replace('`# ELSE #`', '<?php } else { ?>', $this->content);
		$this->content = preg_replace('`# ENDIF #`', '<?php } ?>', $this->content);
	}
	
	private function parse_includes()
	{
		$this->content = preg_replace('`# INCLUDE ([\w]+) #`', '<?php $_subtemplate = $this->template->get_subtemplate(\'$1\');' . "\n" .
			'if ($_subtemplate !== null) {$_subtemplate->parse(TEMPLATE_STRING_MODE);} ?>', $this->content);
	}
	
	private function callback_accept_xml($mask)
	{
		return '<?php echo \'<?' . str_replace(array('\\', '\''), array('\\\\', '\\\''), trim($mask[1])) . '?>\'; ?>';
	}
	
	private function callback_parse_blocks_vars($blocks)
	{
		$array_block = explode('.', $blocks[1]);
		$varname = array_pop($array_block);
		$last_block = array_pop($array_block);
		
		return '<?php echo $this->template->get_var_from_list(\'' . $varname . '\', $_tmp_' . $last_block . '_value); ?>';
	}
	
	private function callback_parse_blocks($blocks)
	{
		$second_param = '';
		$blockname =& $blocks[1];
		if (strpos($blockname, '.') !== false) //Contient un bloc imbriqué.
		{
			$array_block = explode('.', $blockname);
			$blockname = array_pop($array_block);
			$previous_block = array_pop($array_block);
			
			$second_param =', $_tmp_' . $previous_block . '_value';
		}
		return '<?php foreach ($this->template->get_block(\'' . $blockname. '\'' . $second_param .') as $_tmp_' . $blockname . '_value) {?>';
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
		$not = ($blocks[1] == 'NOT ' ? '!' : '');
		if (strpos($blocks[2], '.') !== false) //Contient un bloc imbriqué.
		{
			$array_block = explode('.', $blocks[2]);
			$varname = array_pop($array_block);
			$last_block = array_pop($array_block);
			
			$second_param = ', $_tmp_' . $last_block . '_value';
		}
		return '<?php ' . $block_type . ' ($this->template->is_true(\'' . $varname . '\'' . $second_param . ')) { ?>';
	}
}

?>