<?php
/**
 * This class manage action links.
 *
 * @package     Builder
 * @subpackage  Form\field
 * @category    Framework
 * @copyright   &copy; 2005-2019 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.2 - last update: 2015 12 14
 * @since       PHPBoost 3.0 - 2010 04 13
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class FormFieldActionLinkList extends AbstractFormField
{
	/**
	 * @var FormFieldActionLinkElement[]
	 */
	private $actions;

	/**
	 * @param string $id
	 * @param FormFieldActionLinkElement[] $actions
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
				'C_PICTURE' => $action->has_css_class() || $action->has_img(),
				'C_IMG' => $action->has_img(),
				'TITLE' => $action->get_title(),
				'CSS_CLASS' => $action->get_css_class(),
				'U_LINK' => $action->get_url()->rel(),
				'U_IMG' => $action->has_img() ? $action->get_img()->rel() : '',
			));
		}

		return $template;
	}

	protected function get_default_template()
	{
		return new FileTemplate('framework/builder/form/FormFieldActionLinkList.tpl');
	}
}
?>
