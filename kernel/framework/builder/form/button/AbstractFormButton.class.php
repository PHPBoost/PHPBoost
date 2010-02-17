<?php
/*##################################################
 *                        AbstractFormButton.class.php
 *                            -------------------
 *   begin                : February 16, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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
 * @package builder
 * @subpackage form/button
 * @desc
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 */
class AbstractFormButton implements FormButton
{
	private $name = '';
	private $label = '';
	private $type = '';
	private $onclick_action = '';
	
	public function __construct($type, $label, $name, $onclick_action = '')
	{
		$this->type = $type;
		$this->label = $label;
		$this->name = $name;
		$this->onclick_action = $onclick_action;
	}

	/**
	 * {@inheritdoc}
	 */
	public function display()
	{
		global $LANG;

		$template = new StringTemplate('<input type="{TYPE}" name="{BUTTON_NAME}" value="{L_SUBMIT}" class="submit" onclick="{ONCLICK_ACTION}" />');

		$template->assign_vars(array(
			'L_SUBMIT' => $this->label,
			'BUTTON_NAME' => $this->name,
			'TYPE' => $this->type,
			'ONCLICK_ACTION' => htmlspecialchars($this->onclick_action)
		));

		return $template;
	}
	
	public function get_name()
	{
		return $this->name;
	}
	
	public function set_name($name)
	{
		$this->name = $name;
	}
	
	public function get_label()
	{
		return $this->label;
	}
	
	public function set_label($label)
	{
		$this->label = $label;
	}
}
?>