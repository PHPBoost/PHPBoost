<?php
/*##################################################
 *                              CommentsTopic.class.php
 *                            -------------------
 *   begin                : March 31, 2011
 *   copyright            : (C) 2011 Kévin MASSY
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

 /**
 * @author Kévin MASSY <soldier.weasel@gmail.com>
 * @package {@package}
 */
class CommentsTopic
{
	private $module_id;
	private $id_in_module;

	public function set_module_id($identifier)
	{
		$this->module_id = $identifier;
	}
	
	public function get_module_id()
	{
		return $this->module_id;
	}
	
	public function set_id_in_module($id_in_module)
	{
		$this->id_in_module = $id_in_module;
	}
	
	public function get_id_in_module()
	{
		return $this->id_in_module;
	}
}
?>