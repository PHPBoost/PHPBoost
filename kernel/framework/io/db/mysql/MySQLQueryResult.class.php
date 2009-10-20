<?php
/*##################################################
 *                           query_result.class.php
 *                            -------------------
 *   begin                : October 1, 2009
 *   copyright            : (C) 2009 Loic Rouchon
 *   email                : horn@phpboost.com
 *
 *
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

import('io/db/QueryResult');
import('io/db/mysql/MySQLQuerierException');

/**
 * @author loic rouchon <loic.rouchon@phpboost.com>
 * @package sql
 * @subpackage mysql
 * @desc
 */
class MysqlQueryResult implements QueryResult
{
    /**
     * @var Resource
     */
    private $resource = null;
    
    /**
     * @var string[string]
     */
    private $next;
    
    /**
     * @var bool
     */
    private $has_next = true;
    
    /**
     * @var bool
     */
    private $move_intern_resource = true;
    
    /**
     * @var bool
     */
    private $is_disposed = false;
    
    public function __construct($resource)
    {
        if (!is_resource($resource) || $resource === false)
        {
            throw new MySQLQuerierException('query returns an invalid sql resource');
        }
        $this->resource = $resource;
    }
    
    public function __destruct()
    {
        $this->dispose();
    }
    
    public function has_next()
    {
        if ($this->has_next && $this->move_intern_resource)
        {
            $next = mysql_fetch_assoc($this->resource);
            $this->has_next = ($next !== false);
            $this->move_intern_resource = false;
            $this->next = $next;
        }
        return $this->has_next;
    }
    
    public function next()
    {
        if (!$this->has_next)
        {
            $this->dispose();
        }
        $this->move_intern_resource = true;
        return $this->next;
    }
    
    public function dispose()
    {
        if (!$this->is_disposed && is_resource($this->resource))
        {
			if (!@mysql_free_result($this->resource))
			{
			    throw new MySQLQuerierException('can\'t close sql resource');
			}
			$this->is_disposed = true;
        }
    }
}

?>