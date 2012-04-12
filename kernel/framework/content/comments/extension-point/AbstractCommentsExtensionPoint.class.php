<?php
/*##################################################
 *                           AbstractCommentsExtensionPoint.class.php
 *                            -------------------
 *   begin                : September 23, 2011
 *   copyright            : (C) 2011 Kevin MASSY
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

abstract class AbstractCommentsExtensionPoint implements CommentsExtensionPoint
{
	/**
	 * @param string $module_id
	 * @param int $id_in_module
	 * @return class CommentsAuthorizations
	 */
	public function get_authorizations($module_id, $id_in_module)
	{
		return new CommentsAuthorizations();
	}
	
	/**
	 * @param string $module_id
	 * @param int $id_in_module
	 * @return boolean display
	 */
	public function is_display($module_id, $id_in_module)
	{
		return false;
	}
	
	/**
	 * @param string $module_id
	 * @param int $id_in_module
	 * @return int number comments display default
	 */
	public function get_number_comments_display($module_id, $id_in_module)
	{
		return CommentsConfig::load()->get_number_comments_display();
	}
}
?>