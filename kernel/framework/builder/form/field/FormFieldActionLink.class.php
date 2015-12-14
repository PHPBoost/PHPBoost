<?php
/*##################################################
 *                        FormFieldActionLink.class.php
 *                            -------------------
 *   begin                : April 14, 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Loic Rouchon <loic.rouchon@phpboost.com>
 * @desc This class manage an action link.
 * @package {@package}
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