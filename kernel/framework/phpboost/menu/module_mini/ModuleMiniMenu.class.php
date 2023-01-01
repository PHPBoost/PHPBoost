<?php
/**
 * @package     PHPBoost
 * @subpackage  Menu\module_mini
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 12
 * @since       PHPBoost 2.0 - 2008 11 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ModuleMiniMenu extends Menu
{
	const MODULE_MINI_MENU__CLASS = 'ModuleMiniMenu';

	/**
	 * Build a ModuleMiniMenu element.
	 */
	public function __construct()
	{
		parent::__construct($this->get_formated_title());
	}

	public function get_menu_id()
	{
		return '';
	}

	public function get_menu_title()
	{
		return '';
	}

	public function get_formated_title()
	{
		return !empty($this->get_menu_title()) ? $this->get_menu_title() : $this->get_format_title();
	}

	public function is_mini_from_module()
	{
		$class_name = get_class($this);
		return !empty($class_name);
	}

	public function get_menu_content()
	{
		return '';
	}

	public function is_displayed()
	{
		return true;
	}

	public function get_format_title()
	{
		$class_name = get_class($this);
		$module_name = TextHelper::strstr($class_name, self::MODULE_MINI_MENU__CLASS, true);
		$module_name = TextHelper::strlen(preg_replace('/[a-z]*/', '', $module_name)) > 1 ? $module_name : TextHelper::strtolower($module_name); //Pour les modules qui ont plus de 2 majuscules on les garde, sinon on les enlève

		$module = ModulesManager::get_module($module_name);

		if (empty($module))
		{
			foreach (ModulesManager::get_activated_modules_map() as $activated_module)
			{
				if (TextHelper::strstr(TextHelper::strtolower($module_name), TextHelper::strtolower($activated_module->get_id())))
					$module = $activated_module;
			}
		}

		$localized_module_name = !empty($module) ? $module->get_configuration()->get_name() : '';

		return !empty($localized_module_name) ? (!preg_match('/^' . Langloader::get_message('menu.menu', 'menu-lang') . ' /u', $localized_module_name) ? Langloader::get_message('menu.menu', 'menu-lang') . ' ' : '') . $localized_module_name : $class_name;
	}

	public function display()
	{
		$filters = $this->get_filters();
		$is_displayed = empty($filters) || $filters[0]->get_pattern() == '/';

		foreach ($filters as $key => $filter)
		{
			if ($filter->get_pattern() != '/' && $filter->match())
			{
				$is_displayed = true;
				break;
			}
		}

		if ($is_displayed && $this->is_displayed())
		{
			$template = $this->get_template_to_use();
			MenuService::assign_positions_conditions($template, $this->get_block());
			$this->assign_common_template_variables($template);

			$template->put_all(array(
				'ID'       => $this->get_menu_id(),
				'TITLE'    => $this->get_menu_title(),
				'CONTENTS' => $this->get_menu_content()
			));

			return $template->render();
		}
		return '';
	}

	public function get_default_block()
	{
		return self::BLOCK_POSITION__NOT_ENABLED;
	}

	public function default_is_enabled() { return false; }

	protected function get_default_template()
	{
		return new FileTemplate('framework/menus/modules_mini.tpl');
	}
}
?>
