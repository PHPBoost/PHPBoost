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
	private $authorized_access_module = true;
	
	private $authorized_read = null;
	private $authorized_post = null;
	private $authorized_moderation = null;
	private $authorized_note = null;
	
	const READ_AUTHORIZATIONS = 1;
	const POST_AUTHORIZATIONS = 2;
	const MODERATION_AUTHORIZATIONS = 4;
	const NOTE_AUTHORIZATIONS = 8;

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
		return $this->check_authorizations(self::MODERATION_AUTHORIZATIONS);
	}
	
	public function is_authorized_note()
	{
		return $this->check_authorizations(self::NOTE_AUTHORIZATIONS);
	}
	
	/**
	 * @param boolean $authorized
	 */
	public function set_authorized_access_module($authorized)
	{
		$this->authorized_access_module = $authorized;
	}
	
	/**
	 * @param boolean $authorized
	 */
	public function set_authorized_read($authorized)
	{
		$this->authorized_read = $authorized;
	}
	
	/**
	 * @param boolean $authorized
	 */
	public function set_authorized_post($authorized)
	{
		$this->authorized_post = $authorized;
	}
	
	/**
	 * @param boolean $authorized
	 */
	public function set_authorized_moderation($authorized)
	{
		$this->authorized_moderation = $authorized;
	}
	
	/**
	 * @param boolean $authorized
	 */
	public function set_authorized_note($authorized)
	{
		$this->authorized_note = $authorized;
	}
	
	private function check_authorizations($global_bit)
	{
		$manual_authorizations = $this->manual_authorizations($global_bit);
		if ($manual_authorizations !== null)
		{
			return $manual_authorizations;
		}
		else
		{
			return AppContext::get_current_user()->check_auth(CommentsConfig::load()->get_authorizations(), $global_bit);
		}
	}
	
	private function manual_authorizations($type)
	{
		switch ($type) 
		{
			case self::READ_AUTHORIZATIONS:
				return $this->authorized_read;
			break;
			case self::POST_AUTHORIZATIONS:
				return $this->authorized_post;
			break;
			case self::MODERATION_AUTHORIZATIONS:
				return $this->authorized_post;
			break;
			case self::NOTE_AUTHORIZATIONS:
				return $this->authorized_note;
			break;
		}
	}
}
?>