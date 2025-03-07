<?php
/**
 * @package     Builder
 * @subpackage  Form\fieldset
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2025 03 07
 * @since       PHPBoost 6.0 - 2025 03 07
*/

class FormFieldsetCapsTop extends AbstractFormFieldset
{
	/**
	 * constructor
	 * @param string $name The name of the fieldset
	 */
	public function __construct($id, $name = '', $options = [])
	{
		parent::__construct($id, $options);
	}

	/**
	 * Return the form
	 * @param Template $template Optionnal template
	 * @return string
	 */
	public function display()
	{
		$template = $this->get_template_to_use();

		$this->assign_template_fields($template);

		return $template;
	}

	protected function get_default_template()
	{
		return new FileTemplate('framework/builder/form/menu/FormFieldsetCapsTop.tpl');
	}
}

?>
