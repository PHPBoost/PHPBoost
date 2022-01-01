<?php
/**
 * @package     Builder
 * @subpackage  Form\button
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2018 11 30
 * @since       PHPBoost 3.0 - 2010 02 16
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

abstract class AbstractFormButton implements FormButton
{
	private $form_id = '';
	private $name = '';
	private $label = '';
	private $type = '';
	private $onclick_action = '';
	private $css_class;
	private $data_confirmation;

	public function __construct($type, $label, $name, $onclick_action = '', $css_class = '', $data_confirmation = '', $form_id = '')
	{
		$this->type = $type;
		$this->label = $label;
		$this->name = $name;
		$this->onclick_action = $onclick_action;
		$this->css_class = $css_class;
		$this->data_confirmation = $data_confirmation;
		$this->form_id = $form_id;
	}

/**
 * {@inheritdoc}
 */
	public function display()
	{
		$template = $this->get_template();
		$template->put_all(array(
			'LABEL' => $this->label,
			'HTML_NAME' => $this->get_html_name(),
			'CSS_CLASS' => $this->css_class,
			'C_DATA_CONFIRMATION' => $this->data_confirmation != '',
			'DATA_CONFIRMATION' => $this->data_confirmation,
			'TYPE' => $this->type,
			'ONCLICK_ACTION' => $this->onclick_action
		));
		return $template;
	}

	public function get_name()
	{
		return $this->name;
	}

	public function get_html_name()
	{
		return ($this->form_id ? $this->form_id . '_' : '') . $this->name;
	}

	public function set_name($name)
	{
		$this->name = $name;
	}

	public function get_label()
	{
		return $this->label;
	}

	public function set_label($label)
	{
		$this->label = $label;
	}

	protected function get_template()
	{
		return new FileTemplate('framework/builder/form/button/DefaultFormButton.tpl');
	}

	public function set_css_class($css_class)
	{
		$this->css_class = $css_class;
	}

	public function get_css_class()
	{
		return $this->css_class;
	}

	public function set_data_confirmation($data_confirmation)
	{
		$this->data_confirmation = $data_confirmation;
	}

	public function get_data_confirmation()
	{
		return $this->data_confirmation;
	}

	public function set_form_id($form_id)
	{
		$this->form_id = $form_id;
	}
}
?>
