<?php
/*##################################################
 *		                   ArticlesAuthorizationsService.class.php
 *                            -------------------
 *   begin                : October 22, 2011
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

/**
 * @author Kévin MASSY <soldier.weasel@gmail.com>
 */
class ArticlesAuthorizationsService
{
	private $id_category;
	
	const AUTHORIZATIONS_READ = 1;
	const AUTHORIZATIONS_CONTRIBUTION = 2;
	const AUTHORIZATIONS_WRITE = 4;
	const AUTHORIZATIONS_MODERATION = 8;
	
	public static function id_category($identifier)
	{
		$instance = new self();
		$instance->id_category = $identifier;
		return $instance;
	}
	
	public static function default_autorizations()
	{
		return new self();
	}
		
	public function read_articles()
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
		$user = AppContext::get_user();
		if ($this->id_category !== null)
		{
			//TODO
		}
		return $user->check_auth(ArticlesConfig::load(), $bit);
	}
}
?>