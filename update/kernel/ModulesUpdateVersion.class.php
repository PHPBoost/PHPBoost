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
		
		$modules_config = ModulesConfig::load();
		foreach ($results as $row)
		{
			$modules_config->add_module(new Module($row['name'], $row['activ'], unserialize($row['auth'])));
		}
		ModulesConfig::save();
		
		$this->drop_modules_table();
	}
	
	private function drop_modules_table()
	{
		PersistenceContext::get_dbms_utils()->drop(array(PREFIX . 'modules'));
	}
}