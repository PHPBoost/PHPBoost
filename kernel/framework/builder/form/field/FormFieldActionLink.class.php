<?php
/**
 * This class manage an action link.
 *
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 22
 * @since       PHPBoost 3.0 - 2010 04 14
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FormFieldActionLink extends AbstractFormField
{
	private FormFieldActionLinkElement $action;

	/**
	 * @param string $id the form field id
	 * @param string $title the action title
	 * @param mixed $url the action url
	 * @param string $css_class the action font awesome css class
	 * @param mixed $img the action icon url
	 */
	public function __construct(string $id, string $title, mixed $url, string $css_class = '', mixed $img = '', string $active_module = '', string $fa_icon = '')
	{
		$this->action = new FormFieldActionLinkElement($title, $url, $css_class, $img, $active_module, $fa_icon);
		parent::__construct($id, '', '');
	}

	/**
	 * @return mixed The html code for the field.
	 */
	public function display(): mixed
	{
		$field = new FormFieldActionLinkList($this->id, [$this->action]);
		return $field->display();
	}

	protected function get_default_template(): FileTemplate
	{
		return new FileTemplate('framework/builder/form/FormFieldActionLinkList.tpl');
	}
}
?>
