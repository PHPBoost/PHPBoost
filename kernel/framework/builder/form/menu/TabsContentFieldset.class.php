<?php
/**
 * @package     Builder
 * @subpackage  Form\menu
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2025 03 07
 * @since       PHPBoost 6.0 - 2025 03 07
*/

class TabsContentFieldset extends FormFieldsetHTML
{

	/**
	 * Return the form
	 * @param Template $template Optionnal template
	 * @return string
	 */
	public function display()
	{
		$template = $this->get_template_to_use();

		$template->put_all(array(
			'C_TITLE' => !empty($this->title),
			'L_TITLE' => $this->title
		));

		$this->assign_template_fields($template);

		return $template;
	}

	protected function get_default_template()
	{
		return new FileTemplate('framework/builder/form/menu/TabsContentFieldset.tpl');
	}
}

?>
