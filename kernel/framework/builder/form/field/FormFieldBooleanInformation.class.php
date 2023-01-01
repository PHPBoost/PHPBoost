<?php
/**
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2018 05 05
 * @since       PHPBoost 3.0 - 2010 08 08
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class FormFieldBooleanInformation extends FormFieldFree
{
	/**
	 * @param bool $value
	 */
	public function __construct($id, $label, $value, array $properties)
	{
		parent::__construct($id, $label, $value, $properties);
	}

	public function display()
	{
		$template = $this->get_template_to_use();

		$this->assign_common_template_variables($template);

		$template->assign_block_vars('fieldelements', array(
			'ELEMENT' => $this->get_html_value()
		));

		return $template;
	}

	protected function get_html_value()
	{
		return '<i class="fa fa-2x ' . ($this->get_value() ? 'fa-check success' : 'fa-times error') . '"></i>';
	}
}
?>
