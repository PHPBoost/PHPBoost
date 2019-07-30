<?php
/**
* This class is a fork of FormFieldsetHTML and manage fieldset for form with menu.
*
* @package     Builder
* @subpackage  Form\field
* @copyright   &copy; 2005-2019 PHPBoost
* @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
* @author      Sebastien LARTIGUE <babsolune@phpboost.com>
* @version     PHPBoost 5.3 - last update: 2019 07 30
* @since       PHPBoost 5.2 - 2019 07 30
*/

class TabsMenuFieldset extends AbstractFormFieldset
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
		return new FileTemplate('framework/builder/form/menu/TabsMenuFieldset.tpl');
	}
}

?>
