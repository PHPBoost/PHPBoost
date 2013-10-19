<?php
/*##################################################
 *                             AdminError404.class.php
 *                            -------------------
 *   begin                : December 13, 2009
 *   copyright            : (C) 2009 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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
 * @author Loic Rouchon <loic.rouchon@phpboost.com>
 * @desc
 */
class AdminError404 extends BusinessObject
{    
    private $id;
    private $requested_url;
    private $from_url;
    private $times = 1;

    public function __construct($requested_url = '', $from_url = '')
    {
        $this->requested_url = $requested_url;
        $this->from_url = $from_url;
    }
    
    public function get_id()
    {
        return $this->id;
    }
    
    public function get_requested_url()
    {
        return $this->requested_url;
    }
    
    public function get_from_url()
    {
        return $this->from_url;
    }

	public function get_times()
	{
		return $this->times;
	}
    
    public function set_id($value)
    {
        $this->id = $value;
    }
    
    public function set_requested_url($value)
    {
        $this->requested_url = $value;
    }
    
    public function set_from_url($value)
    {
        $this->from_url = $value;
    }

	public function set_times($value)
	{
		$this->times = $value;
	}
	
	public function increment()
	{
		$this->times++;
	}
}
?>