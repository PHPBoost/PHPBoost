<?php
/**
 * @package     Builder
 * @subpackage  Form\button
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 21
 * @since       PHPBoost 3.0 - 2010 02 16
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

abstract class AbstractFormButton implements FormButton
{
	private string $form_id = '';
	private string $name = '';
	private string $label = '';
	private string $type = '';
	private string $onclick_action = '';
	private mixed $css_class = null;
	private mixed $data_confirmation = null;

	public function __construct(string $type, string $label, string $name, string $onclick_action = '', string $css_class = '', string $data_confirmation = '', string $form_id = '')
	{
		$this->type = $type;
		$this->label = $label;
		$this->name = $name;
		$this->onclick_action = $onclick_action;
		$this->css_class = $css_class;
		$this->data_confirmation = $data_confirmation;
		$this->form_id = $form_id;
	}

	public function display(): Template
	{
		$template = $this->get_template();
		$template->put_all([
			'LABEL' => $this->label,
			'HTML_NAME' => $this->get_html_name(),
			'CSS_CLASS' => $this->css_class,
			'C_DATA_CONFIRMATION' => $this->data_confirmation != '',
			'DATA_CONFIRMATION' => $this->data_confirmation,
			'TYPE' => $this->type,
			'ONCLICK_ACTION' => $this->onclick_action
		]);
		return $template;
	}

	public function get_name(): string
	{
		return $this->name;
	}

	public function get_html_name(): mixed
	{
		return ($this->form_id ? $this->form_id . '_' : '') . $this->name;
	}

	public function set_name(string $name): void
	{
		$this->name = $name;
	}

	public function get_label(): string
	{
		return $this->label;
	}

	public function set_label(string $label): void
	{
		$this->label = $label;
	}

	protected function get_template(): FileTemplate
	{
		return new FileTemplate('framework/builder/form/button/DefaultFormButton.tpl');
	}

	public function set_css_class(string $css_class): void
	{
		$this->css_class = $css_class;
	}

	public function get_css_class(): mixed
	{
		return $this->css_class;
	}

	public function set_data_confirmation(mixed $data_confirmation): void
	{
		$this->data_confirmation = $data_confirmation;
	}

	public function get_data_confirmation(): mixed
	{
		return $this->data_confirmation;
	}

	public function set_form_id(string $form_id): void
	{
		$this->form_id = $form_id;
	}
}
?>
