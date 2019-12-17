<?php
/**
 * This class manage select field options.
 * @package     Builder
 * @subpackage  Form\field\enum
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2017 03 10
 * @since       PHPBoost 3.0 - 2009 04 28
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class FormFieldSelectChoiceGroupOption extends AbstractFormFieldEnumOption
{
	private $options = array();

	/**
	 * @param string $label string The label
	 * @param FormFieldSelectChoiceOption[] $options The group's options
	 */
	public function __construct($label, array $options)
	{
		parent::__construct($label, '');
		$this->set_options($options);
	}

	public function set_field(FormField $field)
	{
		parent::set_field($field);
		foreach ($this->options as $option)
		{
			$option->set_field($field);
		}
	}

	public function display()
	{

		$tpl = new FileTemplate('framework/builder/form/fieldelements/FormFieldSelectChoiceGroupOption.tpl');
		$tpl->put_all(array(
			'LABEL' => $this->get_label()
		));

		foreach ($this->options as $option)
		{
			$tpl->assign_block_vars('options', array(), array(
				'OPTION' => $option->display()
			));
		}

		return $tpl;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_option($raw_value)
	{
		foreach ($this->options as $option)
		{
			if ($option->get_option($raw_value))
			{
				return $option;
			}
		}
		return null;
	}

	public function set_options(Array $options)
	{
		$this->options = $options;
	}

	public function get_options()
	{
		return $this->options;
	}
}
?>
