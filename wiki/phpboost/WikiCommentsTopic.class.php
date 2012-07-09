<?php
/*##################################################
 *                           WikiCommentsTopic.class.php
 *                            -------------------
 *   begin                : April 09, 2012
 *   copyright            : (C) 2012 Kevin MASSY
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

class WikiCommentsTopic extends CommentsTopic
{
	public function __construct()
	{
		parent::__construct('wiki');
	}
	
	public function get_authorizations()
	{
		global $_WIKI_CONFIG;
		
		$cache = new Cache();
		$cache->load($this->get_module_id());
		
		require_once(PATH_TO_ROOT .'/'. $this->get_module_id() . '/wiki_auth.php');
		
		$article = $this->get_articles();
		
		$cat_authorizations = $article['auth'];
		if (!is_array($cat_authorizations))
		{
			$cat_authorizations = $_WIKI_CONFIG['auth'];
		}
		$authorizations = new CommentsAuthorizations();
		$authorizations->set_authorized_access_module(AppContext::get_current_user()->check_auth($cat_authorizations, AUTH_NEWS_READ));
		return $authorizations;
	}
	
	public function is_display()
	{
		return true;
	}

	private function get_articles()
	{
		$columns = array('*');
		$condition = 'WHERE id = :id_in_module';
		$parameters = array('id_in_module' => $this->get_id_in_module());
		return PersistenceContext::get_querier()->select_single_row(PREFIX . 'wiki_articles', $columns, $condition, $parameters);
	}
}
?>