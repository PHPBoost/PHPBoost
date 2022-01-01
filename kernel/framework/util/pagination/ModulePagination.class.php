<?php
/**
 * @package     Util
 * @subpackage  Pagination
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 4.0 - 2013 06 19
*/

class ModulePagination
{
	private $pagination;
	private $current_page;
	private $number_elements;
	private $number_items_per_page;

	public function __construct($current_page, $number_elements, $number_items_per_page, $type = Pagination::FULL_PAGINATION)
	{
		$this->current_page = $current_page;
		$this->number_elements = $number_elements;
		$this->number_items_per_page = (int)$number_items_per_page;
		$this->pagination = new Pagination($this->get_number_pages(), $this->current_page, $type);
	}

	public function set_url(Url $url)
	{
		$this->pagination->set_url_sprintf_pattern($url->rel());
	}

	public function display()
	{
		return $this->pagination->export();
	}

	public function get_number_items_per_page()
	{
		return $this->number_items_per_page;
	}

	public function current_page_is_empty()
	{
		return $this->current_page > $this->pagination->get_number_pages();
	}

	public function get_display_from()
	{
		$current_page = $this->current_page > 0 ? $this->current_page : 1;
		return ($current_page - 1) * $this->get_number_items_per_page();
	}

	public function has_several_pages()
	{
		return $this->get_number_pages() > 1;
	}

	public function get_number_pages()
	{
		return ceil($this->number_elements / $this->get_number_items_per_page());
	}
}
?>
