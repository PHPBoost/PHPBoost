// TODO rename extension (remove .php)
class {CLASSNAME} extends MySQLDAO
{
    public function __construct($model)
    {
        parent::__construct($model);
    }

	public function delete($object)
    {
        if ($object->{PK_GETTER}() !== null)
        {
            $this->connection->query_inject('DELETE FROM {TABLE_NAME} WHERE {PK_NAME}=' . $this->escape($object->{PK_GETTER}()), __LINE__, __FILE__);
        }
    }

    public function save($object)
    {
        try
        {
            if ($object->{PK_GETTER}() !== null)
            {   // UPDATE
                $query = 'UPDATE {TABLE_NAME} SET ';
                $fields_and_values = array();
                foreach ($this->model->fields() as $field)
                {
                    $getter = $field->getter();
                    $fields_and_values[]= $field->name() . '=' . $this->escape($object->$getter());
                }
                $query .=  implode(', ', $fields_and_values) . ' WHERE ' . $this->model->primary_key()->name() . '=' . $this->escape($object->{PK_GETTER}());
            }
            else
            {   // CREATE
                $fields_names = array($this->model->primary_key()->name());
                $fields_values = array('NULL');
                foreach ($this->model->fields() as $field)
                {
                    $getter = $field->getter();
                    $fields_names[] = $field->name();
                    $fields_values[] = $this->escape($object->$getter());
                }
                $query = 'INSERT INTO ' . $this->model->name() . ' (' . implode(',', $fields_names) . ') VALUES (' . implode(',', $fields_values) . ')';
            }
            $this->connection->query_inject($query, __LINE__, __FILE__);

            {PK_SETTER} = $this->model->primary_key()->setter();
            $object->{PK_SETTER}($this->connection->insert_id());
        }
        catch (Exception $ex)
        {
            // TODO Process Exception Here
            throw $ex;
        }
    }

    public function find_by_id($id)
    {
        $params = array($this->model->name(), $this->model->primary_key()->name());
        foreach ($this->model->fields() as $field)
        {
            $params[] = $field->name();
        }
        $params[] = 'WHERE ' . $this->model->primary_key()->name() . '=' . $id;
        $params[] = __LINE__;
        $params[] = __FILE__;

        $result = call_user_func_array(array($this->connection, 'query_array'), $params);
        $classname = $this->model->name();
        $object = new $classname();
        foreach ($result as $field_name => $value)
        {
            $setter = $this->model->field($field_name)->setter();
            $object->$setter($value);
        }
        return $object;
    }
    
    private $fields_name_list = array(# start fields #'{fields.NAME}',# end fields #);
}
