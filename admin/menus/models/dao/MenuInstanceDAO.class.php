<?php
/*##################################################
 *                             MenuInstanceDAO.class.php
 *                            -------------------
 *   begin                : Ocotober 25, 2009
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
class MenuInstanceDAO extends SQLDAO
{
	private static $instance;

	public static function instance()
	{
		if (self::$instance === null)
		{
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function __construct()
	{
		$classname = 'MenuInstance';
		$tablename = PREFIX . 'menu_instance';
		$primary_key = new MappingModelField('id');

		$fields = array(
		new MappingModelField('menu_id'),
		new MappingModelField('menu_configuration_id'),
		new MappingModelField('block'),
		new MappingModelField('position'),
		);

		$model = new MappingModel($classname, $tablename, $primary_key, $fields);

		parent::__construct($model, PersistenceContext::get_querier());
	}
}
?>