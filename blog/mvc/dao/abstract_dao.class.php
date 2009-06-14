<?php
/*##################################################
 *                           abstract_dao.class.php
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

mvcimport('mvc/dao/dao');
mvcimport('mvc/dao/builder/dao_builder_factory');

abstract class AbstractDAO extends DAO
{
    public function __construct($model)
    {
        parent::__construct($model);
        $this->sql_dao = DAOBuilderFactory::get_sql_dao($model);
    }

    protected function before_delete($object) {}
    public function delete($object)
    {
        try
        {
            $this->before_delete($object);
            $this->sql_dao->delete($object);
        }
        catch (DAOValidationException $ex)
        {
            throw $ex;
        }
    }

    protected function before_save($object){}
    public function save($object)
    {
        try
        {
            $this->before_save($object);
            $this->sql_dao->save($object);
        }
        catch (DAOValidationException $ex)
        {
            throw $ex;
        }
    }

    public function find_by_id($id)
    {
        return $this->sql_dao->find_by_id($id);
    }

    public function find_by_criteria($criteria)
    {
        return $this->sql_dao->find_by_criteria($criteria);
    }

    public function create_criteria()
    {
        return $this->sql_dao->create_criteria();
    }

    public function find_all($offset = 0, $limit = 100, $order_by = null, $way = ICriteria::ASC)
    {
    	return $this->sql_dao->find_all($offset, $limit);
    }
    
    protected $sql_dao;
}
?>