<?php
/*##################################################
 *                               ForumAuthorizationsService.class.php
 *                            -------------------
 *   begin                : February 25, 2015
 *   copyright            : (C) 2015 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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

class ForumAuthorizationsService
{
	const FLOOD_AUTHORIZATIONS = 16;
	const HIDE_EDITION_MARK_AUTHORIZATIONS = 32;
	const UNLIMITED_TOPICS_TRACKING_AUTHORIZATIONS = 64;
	const READ_TOPICS_CONTENT_AUTHORIZATIONS = 128;
	const CATEGORIES_MANAGEMENT_AUTHORIZATIONS = 256;
	
	public static function check_authorizations($id_category = Category::ROOT_CATEGORY)
	{
		$instance = new self();
		$instance->id_category = $id_category;
		return $instance;
	}
	
	public function read()
	{
		return $this->is_authorized(Category::READ_AUTHORIZATIONS, Authorizations::AUTH_PARENT_PRIORITY);
	}
	
	public function write()
	{
		return $this->is_authorized(Category::WRITE_AUTHORIZATIONS);
	}
	
	public function moderation()
	{
		return $this->is_authorized(Category::MODERATION_AUTHORIZATIONS);
	}
	
	public function flood()
	{
		return $this->is_authorized(self::FLOOD_AUTHORIZATIONS);
	}
	
	public function hide_edition_mark()
	{
		return $this->is_authorized(self::HIDE_EDITION_MARK_AUTHORIZATIONS);
	}
	
	public function unlimited_topics_tracking()
	{
		return $this->is_authorized(self::UNLIMITED_TOPICS_TRACKING_AUTHORIZATIONS);
	}
	
	public function read_topics_content()
	{
		return $this->is_authorized(self::READ_TOPICS_CONTENT_AUTHORIZATIONS);
	}
	
	public function manage_categories()
	{
		return $this->is_authorized(self::CATEGORIES_MANAGEMENT_AUTHORIZATIONS);
	}
	
	private function is_authorized($bit, $mode = Authorizations::AUTH_CHILD_PRIORITY)
	{
		if (!in_array($bit, array(Category::READ_AUTHORIZATIONS, Category::WRITE_AUTHORIZATIONS, Category::MODERATION_AUTHORIZATIONS)))
			$auth = ForumConfig::load()->get_authorizations();
		else
			$auth = ForumService::get_categories_manager()->get_heritated_authorizations($this->id_category, $bit, $mode);
		
		return AppContext::get_current_user()->check_auth($auth, $bit);
	}
}
?>
