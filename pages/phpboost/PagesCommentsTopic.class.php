<?php
/*##################################################
 *                           PagesCommentsTopic.class.php
 *                            -------------------
 *   begin                : April 25, 2012
 *   copyright            : (C) 2012 Kevin MASSY
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

class PagesCommentsTopic extends CommentsTopic
{
	public function __construct()
	{
		parent::__construct('pages');
	}
	
	public function get_authorizations()
	{
		require_once(PATH_TO_ROOT .'/'. $this->get_module_id() . '/pages_defines.php');
		
		$page_authorizations = unserialize($this->get_page_authorizations());
		
		$authorizations = new CommentsAuthorizations();
		if (!empty($page_authorizations))
		{
			$authorizations->set_authorized_access_module(AppContext::get_current_user()->check_auth($page_authorizations, READ_PAGE));
		
		}
		else
		{
			$authorizations->set_authorized_access_module(AppContext::get_current_user()->check_auth(PagesConfig::load()->get_authorizations(), READ_PAGE));
		
		}
		return $authorizations;
	}
	
	public function is_display()
	{
		return true;
	}
	
	private function get_page_authorizations()
	{
		$columns = 'auth';
		$condition = 'WHERE id = :id_in_module';
		$parameters = array('id_in_module' => $this->get_id_in_module());
		return PersistenceContext::get_querier()->get_column_value(PREFIX . 'pages', $columns, $condition, $parameters);
	}
}
?>