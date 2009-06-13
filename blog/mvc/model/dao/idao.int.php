<?php

interface IDAO
{
    public function delete($object);
    public function save($object);

    public function find_by_id($id);
    public function find_by_criteria($criteria);

    public function create_criteria();
}

?>