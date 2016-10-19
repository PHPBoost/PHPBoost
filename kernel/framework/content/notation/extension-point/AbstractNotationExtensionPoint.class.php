<?php
/*##################################################
 *                       AbstractNotationExtensionPoint.class.php
 *                            -------------------
 *   begin                : September 28, 2016
 *   copyright            : (C) 2016 Arnaud Genet
 *   email                : elenwii@phpboost.com
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

abstract class AbstractNotationExtensionPoint implements NotationExtensionPoint
{
	private $ContentManagementConfig;
	private $id;

	public function __construct($module_id = '')
	{
		$this->id = $module_id;
		$this->ContentManagementConfig = ContentManagementConfig::load();
	}

	public function is_notation_enabled()
	{
		return $this->ContentManagementConfig->is_notation_enabled() && !in_array($this->id, $this->ContentManagementConfig->get_notation_unauthorized_modules());
	}

	public function get_notation_scale()
	{
		return $this->ContentManagementConfig->get_notation_scale();
	}
	
	public function update_notation_scale($module_id, $old_notation_scale, $new_notation_scale)
	{
		NotationService::update_notation_scale($module_id, $old_notation_scale, $new_notation_scale);
	}
}
?>
