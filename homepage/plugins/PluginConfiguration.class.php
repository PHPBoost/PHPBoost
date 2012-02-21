<?php
/*##################################################
 *                           PluginConfiguration.class.php
 *                            -------------------
 *   begin                : February 21, 2012
 *   copyright            : (C) 2012 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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

abstract class PluginConfiguration
{
	private $id;
	private $form_configuration;
	
	public function __construct($id, PluginFormConfiguration $form_configuration)
	{
		$this->id = $id;
		$this->form_configuration = $form_configuration;
	}
	
	public function get_id()
	{
		return $this->id;
	}
	
	public function get_form_configuration()
	{
		return $this->form_configuration;
	}
}
?>