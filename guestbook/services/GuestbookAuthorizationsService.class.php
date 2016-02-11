<?php
/*##################################################
 *                               GuestbookAuthorizationsService.class.php
 *                            -------------------
 *   begin                : June 27, 2013
 *   copyright            : (C) 2013 j1.seth
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

 /**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */
class GuestbookAuthorizationsService
{
	const READ_AUTHORIZATIONS = 1;
	const WRITE_AUTHORIZATIONS = 2;
	const MODERATION_AUTHORIZATIONS = 4;
	
	public static function check_authorizations()
	{
		$instance = new self();
		return $instance;
	}
	
	public function read()
	{
		return $this->get_authorizations(self::READ_AUTHORIZATIONS);
	}
	
	public function write()
	{
		return $this->get_authorizations(self::WRITE_AUTHORIZATIONS);
	}
	
	public function moderation()
	{
		return $this->get_authorizations(self::MODERATION_AUTHORIZATIONS);
	}
	
	private function get_authorizations($bit)
	{
		return AppContext::get_current_user()->check_auth(GuestbookConfig::load()->get_authorizations(), $bit);
	}
}
?>
