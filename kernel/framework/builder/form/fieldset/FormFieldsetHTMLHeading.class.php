<?php
/**
 * @package     Builder
 * @subpackage  Form\fieldset
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 10 26
 * @since       PHPBoost 5.1 - 2018 10 26
*/

class FormFieldsetHTMLHeading extends FormFieldsetHTML
{
	/**
	 * constructor
	 * @param string $name The name of the fieldset
	 */
	public function __construct($id, $name = '', $options = array())
	{
		parent::__construct($id, $name, $options);
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
			'C_HEADING' => true,
			'C_TITLE'   => !empty($this->title),
			'L_TITLE'   => $this->title
		));

		$this->assign_template_fields($template);

		return $template;
	}
}

?>
