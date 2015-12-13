<?php
/*##################################################
 *                        ForumCategoriesCache.class.php
 *                            -------------------
 *   begin                : May 15, 2015
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

/**
 * @author Julien BRISWALTER <julienseth78@phpboost.com>
 * @desc CategoriesCache of the forum module
 */
class ForumCategoriesCache extends CategoriesCache
{
	public function get_table_name()
	{
		return ForumSetup::$forum_cats_table;
	}
	
	public function get_category_class()
	{
		return 'ForumCategory';
	}
	
	public function get_module_identifier()
	{
		return 'forum';
	}
	
	protected function get_category_elements_number($id_category)
	{
		$topics_number = ForumService::count_topics('WHERE idcat = :id_category', array('id_category' => $id_category));
		$messages_number = ForumService::count_messages('WHERE idtopic = :id_category', array('id_category' => $id_category));
		
		return array(
			'topics_number' => $topics_number,
			'messages_number' => $messages_number
		);
	}
	
	public function get_root_category()
	{
		$root = new ForumCategory();
		$root->set_authorizations(ForumConfig::load()->get_authorizations());
		
		return $root;
	}
}
?>
