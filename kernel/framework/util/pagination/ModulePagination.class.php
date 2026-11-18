<?php
/**
 * @package     Util
 * @subpackage  Pagination
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 02
 * @since       PHPBoost 4.0 - 2013 06 19
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ModulePagination
{
	private Pagination $pagination;
	private int $current_page;
	private int $number_elements;
	private int $number_items_per_page;

	public function __construct(int $current_page, int $number_elements, int $number_items_per_page, string $type = Pagination::FULL_PAGINATION)
	{
		$this->current_page = $current_page;
		$this->number_elements = $number_elements;
		$this->number_items_per_page = (int)$number_items_per_page;
		$this->pagination = new Pagination($this->get_number_pages(), $this->current_page, $type);
	}

	public function set_url(Url $url): void
	{
		$this->pagination->set_url_sprintf_pattern($url->rel());
	}

	public function display(): FileTemplate
	{
		return $this->pagination->export();
	}

	public function get_number_items_per_page(): int
	{
		return $this->number_items_per_page;
	}

	public function current_page_is_empty(): bool
	{
		return $this->current_page > $this->pagination->get_number_pages();
	}

	public function get_display_from(): int|float
	{
		$current_page = $this->current_page > 0 ? $this->current_page : 1;
		return ($current_page - 1) * $this->get_number_items_per_page();
	}

	public function has_several_pages(): bool
	{
		return $this->get_number_pages() > 1;
	}

	public function get_number_pages(): int|float
	{
		return ceil($this->number_elements / $this->get_number_items_per_page());
	}
}
?>
