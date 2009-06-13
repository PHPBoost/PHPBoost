<?php
mvcimport('mvc/dao/builder/sql_dao_builder');
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
    	$tpl = parent::generate_content('mysql_dao_builder.class.tpl');
    	return $tpl->parse(TEMPLATE_STRING_MODE);
    }
}
?>