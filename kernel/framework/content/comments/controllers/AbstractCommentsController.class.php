<?php
/*##################################################
 *                       AbstractCommentsController.class.php
 *                            -------------------
 *   begin                : August 30, 2011
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

class AbstractCommentsController extends AbstractController
{
	public $module_id;
	public $id_in_module;
	
	public function execute(HTTPRequest $request)
	{
		$this->module_id = $request->get_poststring('module_id', '');
		$this->id_in_module = $request->get_poststring('id_in_module', '');
	}
	
	public function is_authorized_read()
	{
		return $this->get_authorizations()->is_authorized_read();
	}
	
	public function is_authorized_note()
	{
		return $this->get_authorizations()->is_authorized_note();
	}
	
	public function is_display()
	{
		return CommentsService::is_display($this->get_module_id(), $this->get_id_in_module());
	}
	
	public function JSON_response(Array $object)
	{
		return new JSONResponse($object);
	}
	
	public function get_module_id()
	{
		return $this->module_id;
	}
	
	public function get_id_in_module()
	{
		return $this->id_in_module;
	}
	
	private function get_authorizations()
	{
		return CommentsService::get_authorizations($this->get_module_id(), $this->get_id_in_module());
	}
}
?>