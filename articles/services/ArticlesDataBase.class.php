<?php
/*##################################################
 *                             ArticlesDataBase.class.php
 *                            -------------------
 *   begin                : April 27, 2011
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

class ArticlesDataBase
{
	public static $db_querier;
	
	public static function __static()
	{
		self::$db_querier = PersistenceContext::get_querier();
	}
	
	public static function add(Article $article)
	{
		self::$db_querier->insert(ArticlesSetup::$articles_table, array(
			'id_category' => $article->get_id_categorie(),
			'picture_path' => $article->get_picture(),
			'title' => $article->get_title(),
			'rewrited_title' => $article->get_rewrited_title(),
			'description' => $article->get_description(),
			'contents' => $article->get_contents(),
			'number_view' => $article->get_number_view(),
			'author_user_id' => $article->get_writer_user_id(),
			'author_name_visitor' => $article->get_writer_name_visitor(),
			'published' => $article->get_visibility(),
			'publishing_start_date' => $article->get_start_visibility(),
			'publishing_end_date' => $article->get_end_visibility(),
			'authorizations' => serialize($article->get_authorizations()),
			'timestamp_created' => $article->get_timestamp_created(),
			'timestamp_last_modified' => $article->get_timestamp_last_modified()
		));
	}
	
	public static function update(Article $article)
	{
		$columns = array(
			'id_category' => $article->get_id_categorie(),
			'picture_path' => $article->get_picture(),
			'title' => $article->get_title(),
			'rewrited_title' => $article->get_rewrited_title(),
			'description' => $article->get_description(),
			'contents' => $article->get_contents(),
			'number_view' => $article->get_number_view(),
			'author_user_id' => $article->get_writer_user_id(),
			'author_name_visitor' => $article->get_writer_name_visitor(),
			'published' => $article->get_visibility(),
			'publishing_start_date' => $article->get_start_visibility(),
			'publishing_end_date' => $article->get_end_visibility(),
			'authorizations' => serialize($article->get_authorizations()),
			'timestamp_created' => $article->get_timestamp_created(),
			'timestamp_last_modified' => $article->get_timestamp_last_modified()
		);
		self::$db_querier->update(ArticlesSetup::$articles_table, $columns, 'WHERE id = :id', array('id' => $article->get_id()));
	}
	
	public static function delete($id_article)
	{
		self::$db_querier->delete(ArticlesSetup::$articles_table, 'WHERE id = id', array('id' => $id_article));
	}
}

?>