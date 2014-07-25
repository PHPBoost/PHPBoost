<?php
/*##################################################
 *                          UrlMapping.class.php
 *                            -------------------
 *   begin                : October 06, 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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

class UrlMapping
{	
    private $to;
    private $from;
    private $options;
	
	/**
	 * @param UrlMapping[] $mappings
	 */
	public function __construct($from, $to, $options = 'L,QSA')
	{
        $this->to = $to;
        $this->from = $from;
        $this->options = $options;
	}
    
    /**
     * {@inheritdoc}
     */
    public function from()
    {
        return $this->from;
    }
    
    /**
     * {@inheritdoc}
     */
    public function to()
    {
        return $this->to;
    }
    
	/**
     * {@inheritdoc}
     */
    public function options()
    {
        return $this->options;
    }
}
?>