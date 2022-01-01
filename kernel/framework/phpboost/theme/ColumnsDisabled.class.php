<?php
/**
 * @package     PHPBoost
 * @subpackage  Theme
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 10 11
 * @since       PHPBoost 3.0 - 2011 04 10
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class ColumnsDisabled
{
	private $disable_top_header = false;
	private $disable_header = false;
	private $disable_sub_header = false;
	private $disable_top_central = false;
	private $disable_bottom_central = false;
	private $disable_top_footer = false;
	private $disable_footer = false;
	private $disable_left_columns = false;
	private $disable_right_columns = false;

	public function top_header_is_disabled()
	{
		return $this->disable_top_header;
	}

	public function header_is_disabled()
	{
		return $this->disable_header;
	}

	public function sub_header_is_disabled()
	{
		return $this->disable_sub_header;
	}

	public function top_central_is_disabled()
	{
		return $this->disable_top_central;
	}

	public function bottom_central_is_disabled()
	{
		return $this->disable_bottom_central;
	}

	public function top_footer_is_disabled()
	{
		return $this->disable_top_footer;
	}

	public function footer_is_disabled()
	{
		return $this->disable_footer;
	}

	public function left_columns_is_disabled()
	{
		return $this->disable_left_columns;
	}

	public function right_columns_is_disabled()
	{
		return $this->disable_right_columns;
	}

	public function set_disable_top_header($disable)
	{
		$this->disable_top_header = $disable;
	}

	public function set_disable_header($disable)
	{
		$this->disable_header = $disable;
	}

	public function set_disable_sub_header($disable)
	{
		$this->disable_sub_header = $disable;
	}

	public function set_disable_top_central($disable)
	{
		$this->disable_top_central = $disable;
	}

	public function set_disable_bottom_central($disable)
	{
		$this->disable_bottom_central = $disable;
	}

	public function set_disable_top_footer($disable)
	{
		$this->disable_top_footer = $disable;
	}

	public function set_disable_footer($disable)
	{
		$this->disable_footer = $disable;
	}

	public function set_disable_left_columns($disable)
	{
		$this->disable_left_columns = $disable;
	}

	public function set_disable_right_columns($disable)
	{
		$this->disable_right_columns = $disable;
	}

	public function set_columns_disabled(Array $disable_columns)
	{
		foreach($disable_columns as $columns)
		{
			$attribute = trim(TextHelper::strtolower($columns));
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

	public function menus_column_is_disabled($column)
	{
		switch ($column)
		{
			case Menu::BLOCK_POSITION__TOP_HEADER:
				return $this->disable_top_header;
				break;
			case Menu::BLOCK_POSITION__HEADER:
				return $this->disable_header;
				break;
			case Menu::BLOCK_POSITION__SUB_HEADER:
				return $this->disable_sub_header;
				break;
			case Menu::BLOCK_POSITION__TOP_CENTRAL:
				return $this->disable_top_central;
				break;
			case Menu::BLOCK_POSITION__BOTTOM_CENTRAL:
				return $this->disable_bottom_central;
				break;
			case Menu::BLOCK_POSITION__TOP_FOOTER:
				return $this->disable_top_footer;
				break;
			case Menu::BLOCK_POSITION__FOOTER:
				return $this->disable_footer;
				break;
			case Menu::BLOCK_POSITION__LEFT:
				return $this->disable_left_columns;
				break;
			case Menu::BLOCK_POSITION__RIGHT:
				return $this->disable_right_columns;
				break;
		}
		return false;
	}
}
?>
