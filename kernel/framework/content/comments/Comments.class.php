<?php
/*##################################################
 *                              Comments.class.php
 *                            -------------------
 *   begin                : March 31, 2010
 *   copyright            : (C) 2010 Kévin MASSY
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
class Comments
{
	private $module_name;
	private $module_id;
	private $read_authorizations;
	private $post_authorizations;
	private $moderation_authorizations;
	
	public function set_module_name($module)
	{
		$this->module_name = $module;
	}
	
	public function get_module_name()
	{
		return $this->module_name;
	}
	
	public function set_module_id($module_id)
	{
		$this->module_id = $module_id;
	}
	
	public function get_module_id()
	{
		return $this->module_id;
	}
	
	public function set_read_authorizations(Array $read_authorizations)
	{
		$this->read_authorizations = $read_authorizations;
	}
	
	public function get_read_authorizations()
	{
		return !empty($this->read_authorizations) ? $this->read_authorizations : array();
	}
	
	public function set_post_authorizations(Array $post_authorizations)
	{
		$this->post_authorizations = $post_authorizations;
	}
	
	public function get_post_authorizations()
	{
		return !empty($this->post_authorizations) ? $this->post_authorizations : array();
	}
	
	public function set_moderation_authorizations(Array $moderation_authorizations)
	{
		$this->moderation_authorizations = $moderation_authorizations;
	}
	
	public function get_moderation_authorizations()
	{
		return !empty($this->moderation_authorizations) ? $this->moderation_authorizations : array();
	}
}
?>