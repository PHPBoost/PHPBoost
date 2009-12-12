<?php
/*##################################################
 *                           ApcRAMCache.class.php
 *                            -------------------
 *   begin                : December 09, 2009
 *   copyright            : (C) 2009 Benoit Sautel, Loic Rouchon
 *   email                : ben.popeye@phpboost.com, horn@phpboost.com
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

/**
 * @package io
 * @subpackage cache/ram
 * @desc
 * @author Benoit Sautel <ben.popeye@phpboost.com>, Loic Rouchon <horn@phpboost.com>
 *
 */
class ApcRAMCache implements RAMCache
{
	private static $website_id = false;
	
	private $ram_cache_id;
	
	public function __construct($ram_cache_id)
	{
		$this->ram_cache_id = self::get_website_id() . '-' . $ram_cache_id;
	}
	
    public function get($id)
    {
    	$id = $this->get_full_object_id($id);
    	$found = false;
        $object = apc_fetch($id, $found);
        if (!$found)
        {
        	// TODO specialize exception
        	throw new Exception();
        }
        return $object;
    }

    public function contains($id) 
    {
    	$id = $this->get_full_object_id($id);
        $found = false;
        apc_fetch($id, $found);
        return $found;
    }

    public function store($id, $object)
    {
    	$id = $this->get_full_object_id($id);
        return (bool) apc_store($id, $object);
    }
    
    public function delete($id) 
    {
    	$id = $this->get_full_object_id($id);
        return apc_delete($id);
    }
    
    private function get_full_object_id($tiny_id)
    {
    	return $this->ram_cache_id . '-' . $tiny_id;
    }
    
    private static function get_website_id()
    {
    	if (self::$website_id === false)
    	{
    		$website_id_cache_file = PATH_TO_ROOT . '/cache/website_id.cfg';
    		if (file_exists($website_id_cache_file))
    		{
    			self::$website_id = file_get_contents($website_id_cache_file);
    		}
    		if (self::$website_id === false)
    		{
    			self::$website_id = substr(md5(realpath(PATH_TO_ROOT)), 0, 10);
    			$file = new File($website_id_cache_file);
    			$file->write(self::$website_id);
    			$file->close();
    		}
    	}
    	return self::$website_id;
    }
}
?>