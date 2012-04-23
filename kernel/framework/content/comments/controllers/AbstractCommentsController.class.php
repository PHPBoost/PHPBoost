<?php
/*##################################################
 *                       AbstractCommentsController.class.php
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

class AbstractCommentsController extends AbstractController
{
	protected $module_id;
	protected $id_in_module;
	protected $provider;
	
	public function execute(HTTPRequest $request)
	{
		$this->module_id = $request->get_poststring('module_id', '');
		$this->id_in_module = $request->get_poststring('id_in_module', '');
		$this->provider = CommentsProvidersService::get_provider($this->module_id);
	}
	
	public function is_authorized_read()
	{
		return $this->get_authorizations()->is_authorized_read();
	}
	
	public function is_display()
	{
		return $this->provider->is_display($this->get_module_id(), $this->get_id_in_module());
	}

	public function get_module_id()
	{
		return $this->module_id;
	}
	
	public function get_id_in_module()
	{
		return $this->id_in_module;
	}
	
	public function get_authorizations()
	{
		return $this->provider->get_authorizations($this->get_module_id(), $this->get_id_in_module());
	}
}
?>