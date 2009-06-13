<?php
mvcimport('mvc/model/dao/dao');
abstract class AbstractDAO extends DAO
{
    public function __construct($model)
    {
        parent::__construct($model);
        $this->sql_dao = DAOFactory::get_sql_dao($model);
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


    protected $sql_dao;
}
?>