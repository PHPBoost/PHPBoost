<?php
/**
 * @package     PHPBoost
 * @subpackage  Theme
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 23
 * @since       PHPBoost 3.0 - 2011 04 10
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ColumnsDisabled
{
	private bool $disable_top_header = false;
	private bool $disable_header = false;
	private bool $disable_sub_header = false;
	private bool $disable_top_central = false;
	private bool $disable_bottom_central = false;
	private bool $disable_top_footer = false;
	private bool $disable_footer = false;
	private bool $disable_left_columns = false;
	private bool $disable_right_columns = false;

	public function top_header_is_disabled(): bool
	{
		return $this->disable_top_header;
	}

	public function header_is_disabled(): bool
	{
		return $this->disable_header;
	}

	public function sub_header_is_disabled(): bool
	{
		return $this->disable_sub_header;
	}

	public function top_central_is_disabled(): bool
	{
		return $this->disable_top_central;
	}

	public function bottom_central_is_disabled(): bool
	{
		return $this->disable_bottom_central;
	}

	public function top_footer_is_disabled(): bool
	{
		return $this->disable_top_footer;
	}

	public function footer_is_disabled(): bool
	{
		return $this->disable_footer;
	}

	public function left_columns_is_disabled(): bool
	{
		return $this->disable_left_columns;
	}

	public function right_columns_is_disabled(): bool
	{
		return $this->disable_right_columns;
	}

	public function set_disable_top_header(bool $disable): void
	{
		$this->disable_top_header = $disable;
	}

	public function set_disable_header(bool $disable): void
	{
		$this->disable_header = $disable;
	}

	public function set_disable_sub_header(bool $disable): void
	{
		$this->disable_sub_header = $disable;
	}

	public function set_disable_top_central(bool $disable): void
	{
		$this->disable_top_central = $disable;
	}

	public function set_disable_bottom_central(bool $disable): void
	{
		$this->disable_bottom_central = $disable;
	}

	public function set_disable_top_footer(bool $disable): void
	{
		$this->disable_top_footer = $disable;
	}

	public function set_disable_footer(bool $disable): void
	{
		$this->disable_footer = $disable;
	}

	public function set_disable_left_columns(bool $disable): void
	{
		$this->disable_left_columns = $disable;
	}

	public function set_disable_right_columns(bool $disable): void
	{
		$this->disable_right_columns = $disable;
	}

	public function set_columns_disabled(array $disable_columns): void
	{
		foreach($disable_columns as $columns)
		{
			switch ($columns)
			{
				case 'top_header':
					$this->disable_top_header = true;
					unset($disable_columns['top_header']);
					break;
				case 'header':
					$this->disable_header = true;
					unset($disable_columns['header']);
					break;
				case 'sub_header':
					$this->disable_sub_header = true;
					unset($disable_columns['sub_header']);
					break;
				case 'top_central':
					$this->disable_top_central = true;
					unset($disable_columns['top_central']);
					break;
				case 'bottom_central':
					$this->disable_bottom_central = true;
					unset($disable_columns['bottom_central']);
					break;
				case 'top_footer':
					$this->disable_top_footer = true;
					unset($disable_columns['top_footer']);
					break;
				case 'footer':
					$this->disable_footer = true;
					unset($disable_columns['footer']);
					break;
				case 'left':
					$this->disable_left_columns = true;
					unset($disable_columns['left']);
					break;
				case 'right':
					$this->disable_right_columns = true;
					unset($disable_columns['right']);
					break;
			}
		}
	}

	public function menus_column_is_disabled(int $column): bool
	{
		switch ($column)
		{
			case Menu::BLOCK_POSITION__TOP_HEADER:
				return $this->disable_top_header;
			case Menu::BLOCK_POSITION__HEADER:
				return $this->disable_header;
			case Menu::BLOCK_POSITION__SUB_HEADER:
				return $this->disable_sub_header;
			case Menu::BLOCK_POSITION__TOP_CENTRAL:
				return $this->disable_top_central;
			case Menu::BLOCK_POSITION__BOTTOM_CENTRAL:
				return $this->disable_bottom_central;
			case Menu::BLOCK_POSITION__TOP_FOOTER:
				return $this->disable_top_footer;
			case Menu::BLOCK_POSITION__FOOTER:
				return $this->disable_footer;
			case Menu::BLOCK_POSITION__LEFT:
				return $this->disable_left_columns;
			case Menu::BLOCK_POSITION__RIGHT:
				return $this->disable_right_columns;
		}
		return false;
	}
}
?>
