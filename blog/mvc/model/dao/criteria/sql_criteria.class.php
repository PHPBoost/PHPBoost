<?php
mvcimport('mvc/model/dao/criteria/icriteria');
abstract class SQLCriteria implements ICriteria
{
    public function __construct($model, $connection)
    {
        $this->model = $model;
        $this->connection = $connection;
    }

    public function add($restriction)
    {
        $this->restrictions[] = $restriction;
    }

    public function set_fetch_mode($fetch_attribute, $mode) {}
    public function set_projection($projection) {}
    public function set_max_results($max_results)
    {
        if (is_numeric($max_results))
        {
            $max_results =  numeric($max_results);
            if (is_integer($max_results) && $max_results > 0)
            {
                $this->max_results = $max_results;
                return;
            }
        }
        throw new InvalidArgumentException('ICriteria->set_max_results($max_results): $max_results must be a strictly positive integer');
    }
    public function set_offset($offset)
    {
        if (is_numeric($offset))
        {
            $offset =  numeric($offset);
            if (is_integer($offset) && $offset >= 0)
            {
                $this->offset = $offset;
                return;
            }
        }
        throw new InvalidArgumentException('ICriteria->set_offset($offset): $offset must be a positive integer');
    }

    protected function fields($fields_options = null)
    {
        return '*';
    }

    protected function build_object($row)
    {
        $classname = $this->model->name();
        $object = new $classname();
        foreach ($row as $field_name => $value)
        {
            $setter = $this->model->field($field_name)->setter();
            $object->$setter($value);
        }
        return $object;
    }

    protected $model;
    protected $restrictions = array();
    protected $offset = 0;
    protected $max_results = 100;
}
?>