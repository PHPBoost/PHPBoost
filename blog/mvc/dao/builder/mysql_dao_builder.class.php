<?php
class MySQLDAOBuilder extends SQLDAOBuilder
{
    public function __construct($model, $cache_path = '/blog/mvc/cache/')
    {
        parent::__construct($model, $cache_path);
    }

    protected function cache_classname()
    {
        return $this->model->name() . '_MySQL';	
    }
    
    protected function generate_content()
    {
    	import('io/template');
    	// TODO change path here
    	$tpl = new Template('/blog/mvc/dao/builder/mysql_dao_builder.class.tpl.php');
    	$tpl->assign_vars(array(
           'TABLE_NAME' => PREFIX . $this->model->name(),
           'PK_NAME' => $this->model->name(),
           'PK_GETTER' => $this->model->primary_key()->getter(),
           'PK_SETTER' => $this->model->primary_key()->setter(),
    	));
    	$tpl->assign_block_vars('fields', array(
           'NAME' => $this->model->name(),
           'GETTER' => $this->model->primary_key()->getter(),
           'SETTER' => $this->model->primary_key()->setter(),
           'TYPE' => $this->model->primary_key()->setter(),
           'LENGTH' => $this->model->primary_key()->setter(),
        ));
    	
    	return $tpl->parse(TEMPLATE_STRING_MODE);
    }
}
?>