<?php
/**
 * This class is a default implementation of the {@link TemplateData} interface.
 * @package     IO
 * @subpackage  Template\data
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 03 14
 * @since       PHPBoost 3.0 - 2010 02 19
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
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
			'C_CSS_CACHE_ENABLED' => CSSCacheConfig::load()->is_enabled(),
			'IS_USER_CONNECTED'   => $user->check_level(User::MEMBER_LEVEL),
			'IS_ADMIN'            => $user->check_level(User::ADMINISTRATOR_LEVEL),
			'IS_MODERATOR'        => $user->check_level(User::MODERATOR_LEVEL),

			'THEME'            => $user->get_theme(),
			'PARENT_THEME'     => ThemesManager::get_theme($user->get_theme()) ? ThemesManager::get_theme($user->get_theme())->get_configuration()->get_parent_theme() : '',
			'LANG'             => $user->get_locale(),
			'IS_MOBILE_DEVICE' => AppContext::get_request()->is_mobile_device(),
			'TOKEN'            => !empty($session) ? $session->get_token() : '',
			'REWRITED_SCRIPT'  => REWRITED_SCRIPT,

			'PATH_TO_ROOT'     => TPL_PATH_TO_ROOT,
			'PHP_PATH_TO_ROOT' => PATH_TO_ROOT,
			'U_SITE'	       => GeneralConfig::load()->get_site_url(),
		));

		foreach (ContentFormattingProvidersService::get_editors() as $id => $provider)
		{
			$this->put('C_' . TextHelper::strtoupper($id) . '_EDITOR', (int)($user->get_editor() == $id));
		}

		$theme = ThemesManager::get_theme(AppContext::get_current_user()->get_theme());

		if ($theme)
		{
			$menus = MenusCache::load()->get_menus();
			$columns_disabled = $theme->get_columns_disabled();
			foreach ($menus as $cached_menu)
			{
				$menu = $cached_menu->get_menu();
				if ($menu->check_auth() && !$columns_disabled->menus_column_is_disabled($menu->get_block()))
				{
					$display = false;
					$filters = $menu->get_filters();
					$nbr_filters = count($filters);
					foreach ($filters as $filter)
					{
						if (($nbr_filters > 1 && $filter->get_pattern() != '/') || ($filter->match() && !$display))
							$display = true;
					}

					if ($display)
					{
						$this->put_all(array(
							'C_HAS_TOP_HEADER_MENUS'  => !$columns_disabled->top_header_is_disabled(),
							'C_HAS_HEADER_MENUS'      => !$columns_disabled->header_is_disabled(),
							'C_HAS_SUB_HEADER_MENUS'  => !$columns_disabled->sub_header_is_disabled(),
							'C_HAS_SOME_HEADER_MENUS' => !$columns_disabled->top_header_is_disabled() || !$columns_disabled->header_is_disabled() || !$columns_disabled->sub_header_is_disabled(),
							'C_HAS_ALL_HEADER_MENUS'  => !$columns_disabled->top_header_is_disabled() && !$columns_disabled->header_is_disabled() && !$columns_disabled->sub_header_is_disabled(),

							'C_HAS_LEFT_MENUS'     		=> !$columns_disabled->left_columns_is_disabled(),
							'C_HAS_RIGHT_MENUS'    		=> !$columns_disabled->right_columns_is_disabled(),
							'C_HAS_SOME_VERTICAL_MENUS' => !$columns_disabled->left_columns_is_disabled() || !$columns_disabled->right_columns_is_disabled(),
							'C_HAS_ALL_VERTICAL_MENUS'  => !$columns_disabled->left_columns_is_disabled() && !$columns_disabled->right_columns_is_disabled(),

							'C_HAS_TOP_CENTRAL_MENUS'    => !$columns_disabled->top_central_is_disabled(),
							'C_HAS_BOTTOM_CENTRAL_MENUS' => !$columns_disabled->bottom_central_is_disabled(),
							'C_HAS_SOME_CENTRAL_MENUS'   => !$columns_disabled->top_central_is_disabled() || !$columns_disabled->bottom_central_is_disabled(),
							'C_HAS_ALL_CENTRAL_MENUS'    => !$columns_disabled->top_central_is_disabled() && !$columns_disabled->bottom_central_is_disabled(),

							'C_HAS_TOP_FOOTER_MENUS' => !$columns_disabled->top_footer_is_disabled(),
							'C_HAS_FOOTER_MENUS'     => !$columns_disabled->footer_is_disabled(),
						));
					}
				}
			}
		}
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
