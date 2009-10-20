<?php
/*##################################################
 *                           mapping_model_exception.class.php
 *                            -------------------
 *   begin                : October 2, 2009
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


class MappingModelException extends Exception
{
    public function __construct($classname, $message)
    {
        parent::__construct('mapping model "' . $classname . '" ' . $message);
    }
}

class ObjectNotFoundException extends MappingModelException
{
    public function __construct($classname, $object_id)
    {
        parent::__construct($classname, 'object #' . $object_id . ' was not found');
    }
}

?>