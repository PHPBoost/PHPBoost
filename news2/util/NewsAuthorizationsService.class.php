<?php
/*##################################################
 *		                NewsAuthorizationsService.class.php
 *                            -------------------
 *   begin                : February 13, 2013
 *   copyright            : (C) 2013 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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

class NewsAuthorizationsService
{
	private $id_category;
	
	const AUTHORIZATIONS_READ = 1;
	const AUTHORIZATIONS_CONTRIBUTION = 2;
	const AUTHORIZATIONS_WRITE = 4;
	const AUTHORIZATIONS_MODERATION = 8;
	
	public static function check_authorizations($id_category = Category::ROOT_CATEGORY)
	{
		$instance = new self();
		$instance->id_category = $identifier;
		return $instance;
	}
		
	public function read()
	{
		$this->get_authorizations(self::AUTHORIZATIONS_READ);
	}
	
	public function contribution()
	{
		$this->get_authorizations(self::AUTHORIZATIONS_CONTRIBUTION);
	}
	
	public function write()
	{
		$this->get_authorizations(self::AUTHORIZATIONS_WRITE);
	}
	
	public function moderation()
	{
		$this->get_authorizations(self::AUTHORIZATIONS_MODERATION);
	}
	
	private function get_authorizations($bit)
	{
		return NewsService::get_categories_manager()->get_categories_cache()->get_category($this->id_category)->check_auth($bit);
	}
}
?>