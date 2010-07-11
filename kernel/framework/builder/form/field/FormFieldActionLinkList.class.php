<?php
/*##################################################
 *                        FormFieldActionLinkList.class.php
 *                            -------------------
 *   begin                : April 13, 2010
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
 * @desc This class manage action links.
 * @package {@package}
 * @subpackage form/field
 */
class FormFieldActionLinkList extends AbstractFormField
{
	/**
	 * @var FormFieldActionLinkElement[]
	 */
	private $actions;

	/**
	 * @param string $id
	 * @param FormFieldActionLinkElement[] $actions
	 */
	public function __construct($id, array $actions)
	{
		$this->actions = $actions;
		parent::__construct($id, '', '');
	}

	/**
	 * @return string The html code for the free field.
	 */
	public function display()
	{
		$template = $this->get_template_to_use();

		foreach ($this->actions as $action) {
			$template->assign_block_vars('action', array(
	            'TITLE' => $action->get_title(),
	            'U_LINK' => $action->get_url()->absolute(),
	            'U_IMG' => $action->get_img()->absolute(),
	            'IS_IMG_RELATIVE' => $action->get_img()->is_relative()
			));
		}

		return $template;
	}

	protected function get_default_template()
	{
		return new FileTemplate('framework/builder/form/FormFieldActionLinkList.tpl');
	}
}

?>