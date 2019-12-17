<?php
/**
 * This class manage an action link.
 *
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2015 12 14
 * @since       PHPBoost 3.0 - 2010 04 14
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class FormFieldActionLink extends AbstractFormField
{
	/**
	 * @var FormFieldActionLinkElement
	 */
	private $action;

	/**
	 * @param string $id the form field id
	 * @param string $title the action title
	 * @param Url $url the action url
	 * @param string $css_class the action font awesome css class
	 * @param Url $img the action icon url
	 */
	public function __construct($id, $title, $url, $css_class = '', $img = '')
	{
		$this->action = new FormFieldActionLinkElement($title, $url, $css_class, $img);
		parent::__construct($id, '', '');
	}

	/**
	 * @return string The html code for the field.
	 */
	public function display()
	{
		$field = new FormFieldActionLinkList($this->id, array($this->action));
		return $field->display();
	}

	protected function get_default_template()
	{
		return new FileTemplate('framework/builder/form/FormFieldActionLinkList.tpl');
	}
}
?>
