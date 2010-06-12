<?php
/*##################################################
 *                       DefaultTemplateData.class.php
 *                            -------------------
 *   begin                : February 19, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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
 * @subpackage template/data
 * @desc This class is a default implementation of the {@link TemplateData} interface.
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 */
class DefaultTemplateData implements TemplateData
{
	protected $langs = array();
	protected $vars = array();
	protected $blocks = array();
	protected $subtemplates = array();

	/**
	 * {@inheritdoc}
	 */
	public function assign_vars(array $array_vars)
	{
		foreach ($array_vars as $key => $val)
		{
			$this->vars[$key] = $val;
		}
	}

	/**
	 * {@inheritdoc}
	 */
	function assign_block_vars($block_name, array $array_vars, array $subtemplates = array())
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
	 * {@inheritdoc}
	 */
	function add_lang(array $lang)
	{
		$this->langs[] = $lang;
	}

	/**
	 * {@inheritdoc}
	 */
	function add_subtemplate($identifier, Template $template)
	{
		$this->subtemplates[$identifier] =& $template;
	}

	/**
	 * {@inheritdoc}
	 */
	function get_subtemplate($identifier)
	{
		if (isset($this->subtemplates[$identifier]))
		{
			return $this->subtemplates[$identifier];
		}
		return null;
	}

	/**
	 * {@inheritdoc}
	 */
	function get_subtemplate_from_list($identifier, $list)
	{
		if (isset($list[$identifier]))
		{
			return $list[$identifier];
		}
	}


	/**
	 * {@inheritdoc}
	 */
	function get_block($blockname)
	{
		return $this->get_block_from_list($blockname, $this->blocks);
	}

	/**
	 * {@inheritdoc}
	 */
	function get_block_from_list($blockname, $parent_block)
	{
		if (isset($parent_block[$blockname]) && is_array($parent_block[$blockname]))
		{
			return $parent_block[$blockname];
		}
		return array();
	}

	/**
	 * {@inheritdoc}
	 */
	function is_true($varname)
	{
		return $this->is_true_from_list($varname, $this->vars);
	}

	/**
	 * {@inheritdoc}
	 */
	function is_true_from_list($varname, $list)
	{
		return isset($list[$varname]) && $list[$varname];
	}

	/**
	 * {@inheritdoc}
	 */
	function get_var($varname)
	{
		return $this->get_var_from_list($varname, $this->vars);
	}

	/**
	 * {@inheritdoc}
	 */
	function get_var_from_list($varname, &$list)
	{
		if (isset($list[$varname]))
		{
			return $list[$varname];
		}
		$empty_value = '';
		return $this->register_var($varname, $empty_value, $list);
	}

	/**
	 * {@inheritdoc}
	 */
	function get_js_var($varname)
	{
		return $this->get_js_var_from_list($varname, $this->vars);
	}

	/**
	 * {@inheritdoc}
	 */
	function get_js_var_from_list($varname, &$list)
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
		return $this->register_var($full_varname, TextHelper::to_js_string($list[$varname]), $list);
	}

	/**
	 * {@inheritdoc}
	 */
	function get_js_lang_var($varname)
	{
		return $this->get_js_lang_var_from_list($varname, $this->vars);
	}

	/**
	 * {@inheritdoc}
	 */
	function get_js_lang_var_from_list($varname, &$list)
	{
		$full_varname = 'JL_' . $varname;
		if (!empty($list[$full_varname]))
		{
			return $list[$full_varname];
		}

		$lang_var = $this->get_lang_var_from_list($varname, $list);
		return $this->register_var($full_varname, TextHelper::to_js_string($lang_var), $list);
	}

	/**
	 * {@inheritdoc}
	 */
	function get_htmlescaped_lang_var($varname)
	{
		return $this->get_htmlescaped_lang_var_from_list($varname, $this->vars);
	}

	/**
	 * {@inheritdoc}
	 */
	function get_htmlescaped_lang_var_from_list($varname, &$list)
	{
		$full_varname = 'EL_' . $varname;
		if (!empty($list[$full_varname]))
		{
			return $list[$full_varname];
		}

		$lang_var = $this->get_lang_var_from_list($varname, $list);
		return $this->register_var($full_varname, htmlspecialchars($lang_var), $list);
	}

	/**
	 * {@inheritdoc}
	 */
	function get_htmlescaped_var($varname)
	{
		return $this->get_htmlescaped_var_from_list($varname, $this->vars);
	}

	/**
	 * {@inheritdoc}
	 */
	function get_htmlescaped_var_from_list($varname, &$list)
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

	/**
	 * {@inheritdoc}
	 */
	function get_lang_var($varname)
	{
		return $this->get_lang_var_from_list($varname, $this->vars);
	}

	/**
	 * {@inheritdoc}
	 */
	function get_lang_var_from_list($varname, &$list)
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

	/**
	 * {@inheritdoc}
	 */
	public function auto_load_frequent_vars()
	{
		$session = AppContext::get_session();
		$member_connected = AppContext::get_user()->check_level(MEMBER_LEVEL);
		$this->assign_vars(array(
			'SID' => SID,
			'THEME' => get_utheme(),
			'LANG' => get_ulang(),
			'C_USER_CONNECTED' => $member_connected,
			'C_USER_NOTCONNECTED' => !$member_connected,
			'PATH_TO_ROOT' => TPL_PATH_TO_ROOT,
			'PHP_PATH_TO_ROOT' => PATH_TO_ROOT,
			'TOKEN' => !empty($session) ? $session->get_token() : ''
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

	/**
	 * {@inheritdoc}
	 */
	public function bind_vars(TemplateData $data)
	{
		$data->vars =& $this->vars;
		$data->blocks =& $this->blocks;
		$data->langs =& $this->langs;
		$data->subtemplates =& $this->subtemplates;
	}
}
?>