import('mvc/dao/mysql_dao');
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
            # IF PK_HAS_GETTER #
                $id = $object->{PK_GETTER}();
            # ELSE #
                $id = $object->{PK_GETTER}('{PK_PROPERTY}');
            # ENDIF #
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
            
            # IF PK_HAS_GETTER #
                $id = $object->{PK_GETTER}();
            # ELSE #
                $id = $object->{PK_GETTER}('{PK_PROPERTY}');
            # ENDIF #
            if ($id !== null)
            {   // UPDATE
                $query = 'UPDATE {TABLE_NAME} SET ';
                $fields_and_values = array();
                foreach ($this->model->fields() as $field)
                {
                    $getter = $field->getter();
                    if ($field->has_getter())
                    {
                        $fields_and_values[]= $field->name() . '=' . $this->escape($object->$getter());
                    }
                    else
                    {
                        $fields_and_values[]= $field->name() . '=' . $this->escape($object->$getter($field->property()));
                    }
                }
                $query .=  implode(', ', $fields_and_values) . ' WHERE {PK_NAME}=' . $this->escape($id);
                $this->connection->query_inject($query, __LINE__, __FILE__);
            }
            else
            {   // CREATE
                $fields_names = array('{PK_NAME}');
                $fields_values = array('NULL');
                foreach ($this->model->fields() as $field)
                {
                    $fields_names[] = $field->name();
                    $getter = $field->getter();
                    if ($field->has_getter())
                    {
                        $fields_values[] = $this->escape($object->$getter());
                    }
                    else
                    {
                        $fields_values[] = $this->escape($object->$getter($field->property()));
                    }
                }
                $query = 'INSERT INTO {TABLE_NAME} (' . implode(',', $fields_names) . ') VALUES (' . implode(',', $fields_values) . ')';
                $this->connection->query_inject($query, __LINE__, __FILE__);
                # IF PK_HAS_SETTER #
                    $object->{PK_SETTER}($this->connection->insert_id());
                # ELSE #
                    $object->{PK_SETTER}('{PK_PROPERTY}', $this->connection->insert_id());
                # ENDIF #
                
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
        $query = 'SELECT {PK_NAME} AS {PK_PROPERTY}# START fields #, {fields.NAME} AS {fields.PROPERTY}# END fields #
        # START extra_fields #, {extra_fields.NAME} AS {extra_fields.PROPERTY}# END extra_fields # FROM {TABLES_NAMES}
        WHERE {PK_NAME}=' . $id # IF JOIN_CLAUSE #. ' AND {JOIN_CLAUSE}'# ENDIF #;
        $sql_results = $this->connection->query_while($query, __LINE__, __FILE__);
        while ($row = $this->connection->fetch_assoc($sql_results))
        {
            if ($row !== false)
            {
                $this->connection->query_close($sql_results);
                return $this->model->build($row);
            }
        }
        $this->connection->query_close($sql_results);
        return null;
    }

    public function find_all($offset = 0, $max_results = 100, $order_by = null, $way = ICriteria::ASC)
    {
        $query = 'SELECT {PK_NAME} AS {PK_PROPERTY}# START fields #, {fields.NAME} AS {fields.PROPERTY}# END fields #
        # START extra_fields #, {extra_fields.NAME} AS {extra_fields.PROPERTY}# END extra_fields # FROM {TABLES_NAMES}'
        # IF JOIN_CLAUSE #. ' WHERE {JOIN_CLAUSE}'# ENDIF #;
        
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
        $this->connection->query_close($sql_results);
        return $results;
    }
}
