<?php
/*##################################################
 *                          ModuleMiniMenu.class.php
 *                            -------------------
 *   begin                : November 15, 2008
 *   copyright            : (C) 2008 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
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
 * @desc
 * @package {@package}
 */
class ModuleMiniMenu extends Menu
{
	const MODULE_MINI_MENU__CLASS = 'ModuleMiniMenu';

	/**
	 * @desc Build a ModuleMiniMenu element.
	 */
	public function __construct()
	{
		parent::__construct($this->get_formated_title());
	}
	
	public function get_menu_id()
	{
		return '';
	}
	
	public function get_menu_title()
	{
		return '';
	}
	
	public function get_menu_content()
	{
		return '';
	}
	
	public function is_displayed()
	{
		return true;
	}
	
	public function get_formated_title()
	{
		return get_class($this);
	}
	
	public function display()
	{
		if ($this->is_displayed())
		{
			$template = $this->get_template_to_use();
			MenuService::assign_positions_conditions($template, $this->get_block());
			$this->assign_common_template_variables($template);
			
			$template->put_all(array(
				'ID' => $this->get_menu_id(),
				'TITLE' => $this->get_menu_title(),
				'CONTENTS' => $this->get_menu_content()
			));
			
			return $template->render();
		}
		return '';
	}
	
	public function get_default_block()
	{
		return self::BLOCK_POSITION__NOT_ENABLED;
	}
	
	public function default_is_enabled() { return false; }

	protected function get_default_template()
	{
		return new FileTemplate('framework/menus/modules_mini.tpl');
	}
}
?>