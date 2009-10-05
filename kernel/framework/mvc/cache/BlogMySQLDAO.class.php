<?php
import('mvc/dao/mysql_dao');
class BlogMySQLDAO extends MySQLDAO
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

$id = $object->get_id();

}
if ($id !== null)
{
$this->connection->query_inject('DELETE FROM phpboost_Blog WHERE phpboost_Blog.id=' . $this->escape($id), __LINE__, __FILE__);
}
}

public function save($object)
{
try
{


$id = $object->get_id();

if ($id !== null)
{// UPDATE
$query = 'UPDATE phpboost_Blog SET ';
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
$query .=implode(', ', $fields_and_values) . ' WHERE phpboost_Blog.id=' . $this->escape($id);
$this->connection->query_inject($query, __LINE__, __FILE__);
}
else
{// CREATE
$fields_names = array('phpboost_Blog.id');
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
$query = 'INSERT INTO phpboost_Blog (' . implode(',', $fields_names) . ') VALUES (' . implode(',', $fields_values) . ')';
$this->connection->query_inject($query, __LINE__, __FILE__);

$object->set_id($this->connection->insert_id());


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
$query = 'SELECT phpboost_Blog.id AS id, phpboost_Blog.title AS title, phpboost_Blog.description AS description, phpboost_Blog.user_id AS user_id
, phpboost_member.login AS member_login FROM phpboost_Blog,phpboost_member
WHERE phpboost_Blog.id=' . $id . ' AND phpboost_member.user_id=phpboost_Blog.user_id';
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
$query = 'SELECT phpboost_Blog.id AS id, phpboost_Blog.title AS title, phpboost_Blog.description AS description, phpboost_Blog.user_id AS user_id
, phpboost_member.login AS member_login FROM phpboost_Blog,phpboost_member'
. ' WHERE phpboost_member.user_id=phpboost_Blog.user_id';

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

?>