<?php
/**
 * This class is a fork of FormFieldActionLinkList and manage action links for a wizard menu.
 *
 * @package     Builder
 * @subpackage  Form\menu
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2025 03 07
 * @since       PHPBoost 6.0 - 2025 03 07
*/

class TabsNavList extends AbstractFormField
{
	/**
	 * @var TabsNavElementElement[]
	 */
	private $actions;

	/**
	 * @param string $id
	 * @param TabsNavElementElement[] $actions
	 */
	public function __construct($id, array $actions)
	{
		$this->actions = $actions;
		parent::__construct($id, '', '');
	}

	/**
	 * @return string The html code for the free field.
	 */
	public function display()
	{
		$template = $this->get_template_to_use();

		foreach ($this->actions as $action) {
			$template->assign_block_vars('action', array(
				'C_IS_ACTIVE_MODULE' => ($action->get_active_module() == '') || (ModulesManager::is_module_installed($action->get_active_module()) & ModulesManager::is_module_activated($action->get_active_module())),
				'C_PICTURE'          => $action->has_css_class() || $action->has_img(),
				'C_IMG'              => $action->has_img(),
				'TITLE'     => $action->get_title(),
				'CLASS'     => $action->get_class(),
				'CSS_CLASS' => $action->get_css_class(),
				'TARGET'    => $action->get_target(),
				'U_IMG'     => $action->has_img() ? $action->get_img()->rel() : '',
			));
		}

		return $template;
	}

	protected function get_default_template()
	{
		return new FileTemplate('framework/builder/form/menu/TabsNavList.tpl');
	}
}
?>
