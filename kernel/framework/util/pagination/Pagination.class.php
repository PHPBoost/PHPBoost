<?php
/**
 * @package     Util
 * @subpackage  Pagination
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2017 05 31
 * @since       PHPBoost 3.0 - 2009 12 22
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class Pagination
{
	const LINKS_NB = 3;
	const FIRST_LINK = 'first-page';
	const PREV_LINK = 'prev-page';
	const NEXT_LINK = 'next-page';
	const LAST_LINK = 'last-page';

	const LIGHT_PAGINATION = 'light';
	const FULL_PAGINATION = 'full';

	private $tpl;
	private $nb_pages;
	private $current_page;
	private $url_pattern;
	private $url_builder_callback;
	private $before_links_nb = self::LINKS_NB;
	private $after_links_nb = self::LINKS_NB;

	public function __construct($nb_pages, $current_page, $type = self::FULL_PAGINATION)
	{
		$this->nb_pages = $nb_pages;
		$this->current_page = $current_page;
		$this->init_tpl($type);
	}

	public function set_url_sprintf_pattern($url_pattern)
	{
		$this->url_pattern = $url_pattern;
	}

	public function set_url_builder_callback($callback)
	{
		$this->url_builder_callback = $callback;
	}

	public function set_before_links_nb($value)
	{
		$this->before_links_nb = $value;
	}

	public function set_after_links_nb($value)
	{
		$this->after_links_nb = $value;
	}

	public function export()
	{
		$this->generate_first_page_pagination();
		$this->generate_near_pages_pagination();
		$this->generate_last_page_pagination();
		return $this->tpl;
	}

	public function get_number_pages()
	{
		return $this->nb_pages;
	}

	private function init_tpl($type)
	{
		$this->tpl = new FileTemplate('framework/util/pagination.tpl');
		$this->tpl->put_all(array(
			'C_LIGHT_PAGINATION' => $type == self::LIGHT_PAGINATION,
			'C_FULL_PAGINATION'  => $type == self::FULL_PAGINATION,
			'L_FIRST_PAGE'    => LangLoader::get_message('pagination.first', 'common'),
			'L_CURRENT_PAGE'  => LangLoader::get_message('pagination.current', 'common'),
			'L_LAST_PAGE'     => LangLoader::get_message('pagination.last', 'common')
		));
	}

	private function generate_first_page_pagination()
	{
		if ($this->current_page > 1)
		{
			$this->add_pagination_page(self::PREV_LINK, 1);
		}
	}

	private function generate_near_pages_pagination()
	{
		$start = $this->current_page - $this->before_links_nb;
		$end = $this->current_page + $this->after_links_nb;
		for ($i = $start; $i <= $end; $i++)
		{
			if ($i >= 1 && $i <= $this->nb_pages)
			{
				$is_current_page = $i == $this->current_page;
				$this->add_pagination_page($i, $i, $is_current_page);
			}
		}
	}

	private function generate_last_page_pagination()
	{
		if ($this->current_page < $this->nb_pages)
		{
			$this->add_pagination_page(self::NEXT_LINK, $this->nb_pages);
		}
	}

	private function add_pagination_page($name, $page_number, $is_current_page = false)
	{
		$this->tpl->assign_block_vars('page', array(
			'U_PAGE'          => $this->get_url($page_number),
			'PAGE_NAME'       => $name == self::PREV_LINK || $name == self::NEXT_LINK ? '' : $name,
			'C_CURRENT_PAGE'  => $is_current_page,
			'L_PAGE' 		  => $is_current_page ? LangLoader::get_message('pagination.current', 'common') : LangLoader::get_message('pagination.page', 'common') . " " . $page_number,
			'C_PREVIOUS_PAGE' => $name == self::PREV_LINK,
			'C_NEXT_PAGE'     => $name == self::NEXT_LINK
		));
	}

	private function get_url($page_number)
	{
		if (!empty($this->url_pattern))
		{
			return sprintf($this->url_pattern, $page_number);
		}
		else if (!empty($this->url_builder_callback))
		{
			return call_user_func($this->url_builder_callback, $page_number);
		}
		throw new Exception('No url builder mode defined for pagination links');
	}
}
?>
