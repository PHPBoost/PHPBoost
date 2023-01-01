<?php
/**
 * This class is a fork of FormFieldFree and manage subtitle for form with menu.
 * It provides you additionnal field options :
 * <ul>
 * 	<li>template : A template object to personnalize the field</li>
 * 	<li>content : The field html content if you don't use a personnal template</li>
 * </ul>
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2019 07 30
 * @since       PHPBoost 5.2 - 2019 07 30
*/

class FormFieldSubTitle extends AbstractFormField
{
	public function __construct($id, $label, $value, array $properties = array())
	{
		parent::__construct($id, $label, $value, $properties);
		$this->set_css_form_field_class(empty($label) ? 'form-field-free-large' : 'form-field-free');
	}

	public function display()
	{
		$template = $this->get_template_to_use();

		$this->assign_common_template_variables($template);

		$template->assign_block_vars('fieldelements', array(
			'ELEMENT' => $this->get_value()
		));

		$template->put('C_HIDE_FOR_ATTRIBUTE', true);

		return $template;
	}

	protected function get_default_template()
	{
		return new FileTemplate('framework/builder/form/menu/FormFieldSubTitle.tpl');
	}
}

?>
