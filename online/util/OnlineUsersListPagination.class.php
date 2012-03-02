<?php
/*##################################################
 *                          OnlineUsersListPagination.class.php
 *                            -------------------
 *   begin                : January 29, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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

class OnlineUsersListPagination
{
	private $pagination;
	private $current_page;
	private $number_users_per_page = 25;
	
	public function __construct($current_page)
	{
		$this->current_page = $current_page;
		$this->pagination = new Pagination($this->get_number_pages(), $this->current_page);
	}
	
	public function set_url($field, $sort)
	{
		$this->pagination->set_url_sprintf_pattern(OnlineUrlBuilder::users($field, $sort, '%d')->absolute());
	}
	public function display()
	{
		return $this->pagination->export();
	}
	
	public function get_number_users_per_page()
	{
		return $this->number_users_per_page;
	}
	
	public function get_display_form()
	{
		$current_page = $this->current_page > 0 ? $this->current_page : 1;
		return ($current_page - 1) * $this->number_users_per_page;
	}
	
	private function get_number_pages()
	{
		return ceil($this->get_number_users() / $this->number_users_per_page);
	}
	
    private function get_number_users()
    {
    	return PersistenceContext::get_querier()->count(DB_TABLE_SESSIONS);
    }
}
?>