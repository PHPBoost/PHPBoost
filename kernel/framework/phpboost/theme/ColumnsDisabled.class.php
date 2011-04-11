<?php
/*##################################################
/**
 *                         ColumnsDisabled.class.php
 *                            -------------------
 *   begin                : April 10, 2011
 *   copyright            : (C) 2011 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
 *
 *
 *###################################################
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
 *###################################################
 */

 /**
 * @author Kévin MASSY <soldier.weasel@gmail.com>
 * @package {@package}
 */
class ColumnsDisabled
{   
	private $disable_header = false;
	private $disable_sub_header = false;
	private $disable_top_central = false;
	private $disable_bottom_central = false;
	private $disable_top_footer = false;
	private $disable_footer = false;
	private $disable_left_columns = false;
	private $disable_right_columns = false;
	
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
	
	public function set_columns_disabled(Array $disable_columns)
	{
		foreach($disable_columns as $columns => $value)
		{
			$attribute = trim(strtolower($columns));
			switch ($columns)
			{
				case 'header':
					$this->disable_header = !empty($value) && is_bool($value) ? $value : true;
					unset($disable_columns['header']);
					break;
				case 'sub_header':
					$this->disable_sub_header = !empty($value) && is_bool($value) ? $value : true;
					unset($disable_columns['sub_header']);
					break;
				case 'top_central':
					$this->disable_top_central = !empty($value) && is_bool($value) ? $value : true;
					unset($disable_columns['top_central']);
					break;
				case 'bottom_central':
					$this->disable_bottom_central = !empty($value) && is_bool($value) ? $value : true;
					unset($disable_columns['bottom_central']);
					break;
				case 'top_footer':
					$this->disable_top_footer = !empty($value) && is_bool($value) ? $value : true;
					unset($disable_columns['top_footer']);
					break;
				case 'footer':
					$this->disable_footer = !empty($value) && is_bool($value) ? $value : true;
					unset($disable_columns['footer']);
					break;
				case 'left':
					$this->disable_left_columns = !empty($value) && is_bool($value) ? $value : true;
					unset($disable_columns['left']);
					break;
				case 'right':
					$this->disable_right_columns = !empty($value) && is_bool($value) ? $value : true;
					unset($disable_columns['right']);
					break;	
				
			}
		}
	}
}
?>
