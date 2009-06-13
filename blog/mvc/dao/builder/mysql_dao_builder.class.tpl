// TODO rename extension (remove .php)
mvcimport('mvc/dao/mysql_dao');
class {CLASSNAME}MySQLDAO extends MySQLDAO
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
                foreach ($this->fields_names_list as $field)
                {
                    $getter = ModelField::GETTER_PREFIX . $field;
                    $fields_and_values[]= $field . '=' . $this->escape($object->$getter());
                }
                $query .=  implode(', ', $fields_and_values) . ' WHERE {PK_NAME}=' . $this->escape($object->{PK_GETTER}());
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
            }
            $this->connection->query_inject($query, __LINE__, __FILE__);

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
        $params = array(
            '{TABLE_NAME}', '{PK_NAME}',
            # START fields #'{fields.NAME}',# END fields #
            'WHERE {PK_NAME}=' . $id, __LINE__, __FILE__);

        $result = call_user_func_array(array($this->connection, 'query_array'), $params);
        $classname = $this->model->name();
        $object = new {CLASSNAME}();
        foreach ($this->fields_names_list as $field)
        {
            $setter = $field;
            $object->$setter($result[$field]);
        }
        return $object;
    }
    
    private $fields_names_list = array(# START fields #'{fields.NAME}',# END fields #);
}
