<?php
/**
 * @package     Builder
 * @subpackage  Form\fieldset
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2018 10 26
 * @since       PHPBoost 3.0 - 2009 05 01
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class FormFieldsetHTML extends AbstractFormFieldset
{
	protected $title = '';

	/**
	 * constructor
	 * @param string $name The name of the fieldset
	 */
	public function __construct($id, $name = '', $options = array())
	{
		parent::__construct($id, $options);
		$this->title = $name;
	}

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

	/**
	 * @param string $title The fieldset title
	 */
	public function set_title($title)
	{
		$this->title = $title;
	}

	/**
	 * @return string The fieldset title
	 */
	public function get_title()
	{
		return $this->title;
	}

	protected function get_default_template()
	{
		return new FileTemplate('framework/builder/form/FormFieldset.tpl');
	}
}

?>
