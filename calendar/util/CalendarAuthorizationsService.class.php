<?php
/*##################################################
 *                              CalendarAuthorizationsService.class.php
 *                            -------------------
 *   begin                : February 25, 2013
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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

class CalendarAuthorizationsService
{
	public $id_category;
	
	public static function check_authorizations($id_category = Category::ROOT_CATEGORY)
	{
		$instance = new self();
		$instance->id_category = $id_category;
		return $instance;
	}
	
	public function read()
	{
		return $this->get_authorizations(Category::READ_AUTHORIZATIONS);
	}
	
	public function contribution()
	{
		return $this->get_authorizations(Category::CONTRIBUTION_AUTHORIZATIONS);
	}
	
	public function write()
	{
		return $this->get_authorizations(Category::WRITE_AUTHORIZATIONS);
	}
	
	public function moderation()
	{
		return $this->get_authorizations(Category::MODERATION_AUTHORIZATIONS);
	}
	
	private function get_authorizations($bit)
	{
		return CalendarService::get_categories_manager()->get_categories_cache()->get_category($this->id_category)->check_auth($bit);
	}
}
?>