<?php
/*##################################################
 *                               ForumAuthorizationsService.class.php
 *                            -------------------
 *   begin                : February 25, 2015
 *   copyright            : (C) 2015 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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
	
	public static function check_authorizations()
	{
		$instance = new self();
		return $instance;
	}
	
	public function flood()
	{
		return $this->get_authorizations(self::FLOOD_AUTHORIZATIONS);
	}
	
	public function hide_edition_mark()
	{
		return $this->get_authorizations(self::HIDE_EDITION_MARK_AUTHORIZATIONS);
	}
	
	public function unlimited_topics_tracking()
	{
		return $this->get_authorizations(self::UNLIMITED_TOPICS_TRACKING_AUTHORIZATIONS);
	}
	
	private function get_authorizations($bit)
	{
		return AppContext::get_current_user()->check_auth(ForumConfig::load()->get_authorizations(), $bit);
	}
}
?>
