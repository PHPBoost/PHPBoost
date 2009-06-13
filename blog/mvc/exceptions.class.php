<?php
/*##################################################
 *                           model.class.php
 *                            -------------------
 *   begin                : June 13 2009
 *   copyright            : (C) 2009 Loïc Rouchon
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

abstract class ModelException {}

class InvalidFieldTypeModelException extends ModelException
{
	public function __construct($field, $type, $length)
	{
		parent::__construct('Invalid field type for field ' . $field . ' of type ' . $type . ' (' . $length . ')');
	}
}

class NoTableModelException extends ModelException
{
	public function __construct()
	{
		parent::__construct('No Table given');
	}
}

class NoPrimaryKeyModelException extends ModelException
{
	public function __construct($model_name)
	{
		parent::__construct('No Primary Key found for model ' . $model_name);
	}
}

class DAOValidationException
{
	public function __construct($message)
	{
		parent::__construct($message);
	}
}
?>