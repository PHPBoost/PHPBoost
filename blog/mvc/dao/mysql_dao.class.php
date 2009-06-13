<?php
mvcimport('mvc/dao/sql_dao');	
class MySQLDAO  extends SQLDAO
{
    public function __construct($model)
    {
        parent::__construct($model);
    }

    public function find_by_criteria($criteria, $start = 0, $max_results = 100)
    {
        $criteria->set_offset($start);
        $criteria->set_max_results($max_results);
        return $criteria->results_list();
    }

    public function create_criteria()
    {
        return new MySQLCriteria($this->model);
    }
    
    public function escape($value)
    {
        if ($value === null)
        {
            return null;
        }
        if (!MAGIC_QUOTES)
        {
            return '\'' . addslashes($value) . '\'';
        }
        return '\'' . $value . '\'';
    }
}
?>