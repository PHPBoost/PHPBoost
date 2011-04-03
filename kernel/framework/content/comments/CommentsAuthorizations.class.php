<?php
/*##################################################
 *                              CommentsAuthorizations.class.php
 *                            -------------------
 *   begin                : April 1, 2010
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
class CommentsAuthorizations
{
	private $array_authorization = array();
	private $read_bit = 0;
	private $post_bit = 0;
	private $moderation_bit = 0;
	
	const READ_AUTHORIZATIONS = 1;
	const POST_AUTHORIZATIONS = 2;
	const MODERATION_AUTHORIZATIONS = 4;
	
	/*
	 * Setters
	*/
	public function set_array_authorization(Array $array_authorization)
	{
		$this->array_authorization = $array_authorization;
	}
	
	public function set_read_bit($read_bit)
	{
		$this->read_bit = $read_bit;
	}
	
	public function set_post_bit($post_bit)
	{
		$this->post_bit = $post_bit;
	}
	
	public function set_moderation_bit($moderation_bit)
	{
		$this->moderation_bit = $moderation_bit;
	}
	
	public function is_authorized_read()
	{
		return $this->check_authorizations($this->read_bit, self::READ_AUTHORIZATIONS);
	}
	
	public function is_authorized_post()
	{
		return $this->check_authorizations($this->post_bit, self::POST_AUTHORIZATIONS);
	}
	
	public function is_authorized_moderation()
	{
		return $this->check_authorizations($this->moderation_bit, self::MODERATION_AUTHORIZATIONS);
	}
	
	private function check_authorizations($bit, $global_bit)
	{
		if (!empty($this->array_authorization) && $bit !== 0)
		{
			//return AppContext::get_user()->check_auth($this->array_authorization, $bit);
			return true;
		}
		else
		{
			//return AppContext::get_user()->check_auth(CommentsConfig::load()->get_authorizations(), $global_bit);
			return true;
		}
	}
}
?>