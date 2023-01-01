<?php
/**
 * This class manage an multitabs link.
 *
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 03 13
 * @since       PHPBoost 5.2 - 2019 10 11
*/

class FormFieldMultitabsLink extends AbstractFormField
{
	/**
	 * @var FormFieldMultitabsLinkElement
	 */
	private $action;

	/**
	 * @param string $id, the form field id
	 * @param string $title, the action title
	 * @param string $target, the id of the target (don't use with #, it's sent to a data attribute)
	 * @param string $css_class, the action font awesome css class
	 * @param Url $img the action icon url
	 */
	public function __construct($id, $title, $trigger, $target, $css_class = '', $img = '', $class = '')
	{
		$this->action = new FormFieldMultitabsLinkElement($title, $trigger, $target, $css_class, $img, $class);
		parent::__construct($id, '', '');
	}

	/**
	 * @return string The html code for the field.
	 */
	public function display()
	{
		$field = new FormFieldMultitabsLinkList($this->id, array($this->action));
		return $field->display();
	}

	protected function get_default_template()
	{
		return new FileTemplate('framework/builder/form/menu/FormFieldMultitabsLinkList.tpl');
	}
}
?>
