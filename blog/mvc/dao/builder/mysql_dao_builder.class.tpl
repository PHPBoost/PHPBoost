mvcimport('mvc/dao/mysql_dao');
class {CLASSNAME}MySQLDAO extends MySQLDAO
{
    public function __construct($model)
    {
        parent::__construct($model);
    }

	public function delete($object)
    {
        $id = null;
        if (is_numeric($object))
        {
            $id = $object;
        }
        else
        {
            $id = $object->{PK_GETTER}();
        }
        if ($id !== null)
        {
            $this->connection->query_inject('DELETE FROM {TABLE_NAME} WHERE {PK_NAME}=' . $this->escape($id), __LINE__, __FILE__);
        }
    }

    public function save($object)
    {
        try
        {
            $id = $object->{PK_GETTER}();
            if ($id !== null)
            {   // UPDATE
                $query = 'UPDATE {TABLE_NAME} SET ';
                $fields_and_values = array();
                foreach ($this->fields_names_list as $field)
                {
                    $getter = ModelField::GETTER_PREFIX . $field;
                    $fields_and_values[]= $field . '=' . $this->escape($object->$getter());
                }
                $query .=  implode(', ', $fields_and_values) . ' WHERE {PK_NAME}=' . $this->escape($id);
                $this->connection->query_inject($query, __LINE__, __FILE__);
            }
            else
            {   // CREATE
                $fields_names = array('{PK_NAME}');
                $fields_values = array('NULL');
                foreach ($this->fields_names_list as $field)
                {
                    $getter = ModelField::GETTER_PREFIX . $field;
                    $fields_names[] = $field;
                    $fields_values[] = $this->escape($object->$getter());
                }
                $query = 'INSERT INTO {TABLE_NAME} (' . implode(',', $fields_names) . ') VALUES (' . implode(',', $fields_values) . ')';
                $this->connection->query_inject($query, __LINE__, __FILE__);
                $object->{PK_SETTER}($this->connection->insert_id());
            }
        }
        catch (Exception $ex)
        {
            // TODO Process Exception Here
            throw $ex;
        }
    }

    public function find_by_id($id)
    {
        $params = array(
            '{TABLE_NAME}', '{PK_NAME}',
            # START fields #'{fields.NAME}',# END fields #
            'WHERE {PK_NAME}=' . $id, __LINE__, __FILE__);
        $result = call_user_func_array(array($this->connection, 'query_array'), $params);
        if ($result !== false)
        {
            return $this->model->build($result);
        }
        return null;
    }

    public function find_all($offset = 0, $max_results = 100, $order_by = null, $way = ICriteria::ASC)
    {
        $query = 'SELECT {PK_NAME}# START fields #, {fields.NAME}# END fields # FROM {TABLE_NAME}';
        if (!empty($order_by))
        {
            $query .= ' ORDER BY ' . $order_by;
            if ($way == ICriteria::ASC)
            {
                $query .= ' ASC ';
            }
            else
            {
                $query .= ' DESC ';
            }
        }
        $query .= ' LIMIT ' . $offset . ', ' . $max_results;

        $results = array();
        $sql_results = $this->connection->query_while($query, __LINE__, __FILE__);
        while ($row = $this->connection->fetch_assoc($sql_results))
        {
            $results[] = $this->model->build($row);
        }
        return $results;
    }
    
    private $fields_names_list = array(# START fields #'{fields.NAME}',# END fields #);
}
