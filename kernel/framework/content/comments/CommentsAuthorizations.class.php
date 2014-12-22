<?php
/*##################################################
 *                              CommentsAuthorizations.class.php
 *                            -------------------
 *   begin                : April 1, 2010
 *   copyright            : (C) 2010 Kevin MASSY
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

 /**
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 * @desc This class could be used to specified comments authorizations (access, read, post, moderation)
 * @package {@package}
 */
class CommentsAuthorizations
{
	private $authorized_access_module = true;
	
	const READ_AUTHORIZATIONS = 1;
	const POST_AUTHORIZATIONS = 2;
	const MODERATE_AUTHORIZATIONS = 4;

	public function is_authorized_access_module()
	{
		return $this->authorized_access_module;
	}
	
	public function is_authorized_read()
	{
		return $this->check_authorizations(self::READ_AUTHORIZATIONS);
	}
	
	public function is_authorized_post()
	{
		return $this->check_authorizations(self::POST_AUTHORIZATIONS);
	}
	
	public function is_authorized_moderation()
	{
		return $this->check_authorizations(self::MODERATE_AUTHORIZATIONS);
	}
	
	/**
	 * @param boolean $authorized
	 */
	public function set_authorized_access_module($authorized)
	{
		$this->authorized_access_module = $authorized;
	}
	
	private function check_authorizations($global_bit)
	{
		return AppContext::get_current_user()->check_auth(CommentsConfig::load()->get_authorizations(), $global_bit);
	}
}
?>