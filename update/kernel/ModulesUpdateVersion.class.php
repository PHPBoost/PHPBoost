<?php
class ModulesUpdateVersion extends KernelUpdateVersion
{
	private $querier;
	
	public function __construct()
	{
		parent::__construct('modules');
		$this->querier = PersistenceContext::get_querier();
	}
	
	public function execute()
	{
		$results = $this->querier->select_rows(PREFIX .'modules', array('*'));
		
		foreach ($results as $row)
		{
			ModulesConfig::load()->add_module(new Module($row['name'], $row['activ'], unserialize($row['auth'])));
		}
		
		$this->drop_modules_table();
	}
	
	private function drop_modules_table()
	{
		PersistenceContext::get_dbms_utils()->drop(array(PREFIX . 'modules'));
	}
}