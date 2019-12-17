<?php
/**
 * This class is a default implementation of the {@link TemplateData} interface.
 * @package     IO
 * @subpackage  Template\data
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 09 14
 * @since       PHPBoost 3.0 - 2010 02 19
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class DefaultTemplateData implements TemplateData
{
	private $strict = false;
	private $vars = array();

	/**
	 * {@inheritdoc}
	 */
	public function enable_strict_mode()
	{
		$this->strict = true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function disable_strict_mode()
	{
		$this->strict = false;
	}

	/**
	 * {@inheritdoc}
	 */
	public function auto_load_frequent_vars()
	{
		$session = AppContext::get_session();
		$user = AppContext::get_current_user();
		$this->put_all(array(
			'THEME'             => $user->get_theme(),
			'LANG'              => $user->get_locale(),
			'IS_USER_CONNECTED' => $user->check_level(User::MEMBER_LEVEL),
			'IS_ADMIN'          => $user->check_level(User::ADMIN_LEVEL),
			'IS_MODERATOR'      => $user->check_level(User::MODERATOR_LEVEL),
			'PATH_TO_ROOT'      => TPL_PATH_TO_ROOT,
			'PHP_PATH_TO_ROOT'  => PATH_TO_ROOT,
			'TOKEN'             => !empty($session) ? $session->get_token() : '',
			'REWRITED_SCRIPT'   => REWRITED_SCRIPT
		));
	}

	/**
	 * {@inheritdoc}
	 */
	public function put($key, $value)
	{
		$this->vars[$key] = $value;
	}

	/**
	 * {@inheritdoc}
	 */
	public function put_all(array $vars)
	{
		foreach ($vars as $key => $value)
		{
			$this->vars[$key] = $value;
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function assign_block_vars($block_name, array $array_vars, array $subtemplates = array())
	{
		$current_block = null;
		if (TextHelper::strpos($block_name, '.') !== false) // nested block
		{
			$blocks = explode('.', $block_name);
			$blockcount = count($blocks) - 1;

			$str = &$this->vars;
			for ($i = 0; $i < $blockcount; $i++)
			{
				$str = &$str[$blocks[$i]];
				$str = &$str[count($str) - 1];
			}
			$current_block = &$str[$blocks[$blockcount]][];
		}
		else
		{
			$current_block = &$this->vars[$block_name][];
		}
		$current_block = array_merge($array_vars, $subtemplates);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_block($blockname)
	{
		return $this->get_block_from_list($blockname, $this->vars);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_block_from_list($blockname, $parent_block)
	{
		if (isset($parent_block[$blockname]) && is_array($parent_block[$blockname]))
		{
			return $parent_block[$blockname];
		}
		elseif ($this->strict)
		{
			throw new TemplateRenderingException('Undefined block \'' . $blockname . '\'');
		}
		return array();
	}

	/**
	 * {@inheritdoc}
	 */
	public function is_true($value)
	{
		return !empty($value);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get($varname)
	{
		return $this->get_from_list($varname, $this->vars);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_from_list($varname, &$list)
	{
		if (isset($list[$varname]))
		{
			return $list[$varname];
		}
		elseif ($this->strict)
		{
			throw new TemplateRenderingException('Undefined variable \'' . $varname . '\'');
		}
		return null;
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
	}
}
?>
