<?php
/*##################################################
 *                           application.class.php
 *                            -------------------
 *   begin                : August 17 2008
 *   copyright            : (C) 2008 Loïc Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
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

define('APPLICATION_TYPE__KERNEL', 'kernel');
define('APPLICATION_TYPE__MODULE', 'module');
define('APPLICATION_TYPE__TEMPLATE', 'template');

class Application
{
    function Application($id, $language, $type = APPLICATION_TYPE__MODULE , $version = 0, $repository = '')
    {
        $this->id = $id;
        $this->language = $language;
        $this->type = $type;
        $this->version = $version;
        $this->repository = $repository;
    }
    
    function get_id() { return $this->id; }
    function get_language() { return $this->language; }
    function get_type() { return $this->type; }
    function get_version() { return $this->version; }
    function get_repository() { return $this->repository; }
    
    var $id = '';
    var $language = '';
    var $type = '';
    var $version = '';
    var $repository = '';
};

?>