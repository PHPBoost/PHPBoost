<?php
/*##################################################
 *                          UserCommentsListPagination.class.php
 *                            -------------------
 *   begin                : October 09, 2012
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

/**
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 */
class UserCommentsListPagination
{
	private $pagination;
	private $id_module;
	private $user_id;
	private $current_page;
	private $number_comments_per_page = 15;
	
	public function __construct($current_page, $id_module = null, $user_id = null)
	{
		$this->current_page = $current_page;
		$this->id_module = $id_module;
		$this->user_id = $user_id;
		$this->pagination = new Pagination($this->get_number_pages(), $this->current_page);
		$this->pagination->set_url_sprintf_pattern(UserUrlBuilder::comments($this->id_module, $this->user_id, '%d')->absolute());
	}
	
	public function display()
	{
		return $this->pagination->export();
	}
	
	public function get_number_comments_per_page()
	{
		return $this->number_comments_per_page;
	}
	
	public function get_display_from()
	{
		$current_page = $this->current_page > 0 ? $this->current_page : 1;
		return ($current_page - 1) * $this->number_comments_per_page;
	}
	
	private function get_number_pages()
	{
		return ceil($this->get_number_comments() / $this->number_comments_per_page);
	}
	
    private function get_number_comments()
    {
    	$row = PersistenceContext::get_querier()->select('
			SELECT comments.user_id, comments.id_topic, COUNT(*) AS nbr_comments,
			topic.module_id
			FROM ' . DB_TABLE_COMMENTS . ' comments
			LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' topic ON comments.id_topic = topic.id_topic 
			'. $this->build_condition_request())->fetch();
 		return $row['nbr_comments'];
    }
    
    private function build_condition_request()
    {
    	$where = 'WHERE ';
    	if ($this->id_module !== null && $this->user_id !== null)
		{
			$where .= 'topic.module_id = \'' . $this->id_module . '\' AND comments.user_id = \'' . $this->user_id . '\'';
		}
		elseif ($this->id_module !== null)
		{
			$where .= 'topic.module_id = \'' . $this->id_module . '\'';
		}
		elseif ($this->user_id !== null)
		{
			$where .= ' comments.user_id = \'' . $this->user_id . '\'';
		}
		else
		{
			$where .= '1';
		}
		return $where;
    }
}
?>