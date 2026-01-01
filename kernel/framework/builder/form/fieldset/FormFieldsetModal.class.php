<?php
/**
 * @package     Builder
 * @subpackage  Form\menu
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2025 03 07
 * @since       PHPBoost 5.2 - 2025 03 06
*/

class FormFieldsetModal extends FormFieldsetHTML
{
	protected $button_class = 'button bgc logo-color';

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
			'C_BUTTON_CLASS' => !empty($this->button_class),
			'BUTTON_CLASS' => $this->button_class,
			'L_TITLE' => $this->title
		));

		$this->assign_template_fields($template);

		return $template;
	}

	protected function compute_options(array &$options)
	{
		foreach($options as $attribute => $value)
		{
			$attribute = TextHelper::strtolower($attribute);
			switch ($attribute)
			{
				case 'button_class':
					$this->button_class = $value;
					unset($options['button_class']);
					break;
			}
		}
		parent::compute_options($options);
	}

	/**
	 * @param string $title The fieldset title
	 */
	public function set_button_class($button_class)
	{
		$this->button_class = $button_class;
	}

	/**
	 * @return string The fieldset button_class
	 */
	public function get_button_class()
	{
		return $this->button_class;
	}

	protected function get_default_template()
	{
		return new FileTemplate('framework/builder/form/FormFieldsetModal.tpl');
	}
}

?>
