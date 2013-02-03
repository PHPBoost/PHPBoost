<?php
class SearchCategoryChildrensOptions
{
	private $authorisations_bits = array();
	private $check_all_bits = false;
	private $add_category_in_list = true;
	private $enable_recursive_exploration = true;
	
	public function add_authorisations_bits($authorisations_bits)
	{
		$this->authorisations_bits[] = $authorisations_bits;
	}
	
	public function get_authorisations_bits()
	{
		return $this->authorisations_bits;
	}
	
	public function check_authorizations(Category $category)
	{
		$nbr_bits = count($this->authorisations_bits);
		if ($nbr_bits == 0)
		{
			return true;
		}
		else
		{
			$authorized_bits = array();
			foreach ($this->authorisations_bits as $bit)
			{
				if ($category->check_auth($bit))
					$authorized_bits[] = $bit;
			}
			
			$nbr_authorized_bits = count($authorized_bits);
			if ($this->check_all_bits)
			{
				return $nbr_authorized_bits == $nbr_bits;
			}
			else
			{
				return $nbr_authorized_bits >= 1; 
			}
		}
	}
	
	public function set_check_all_bits($check_all_bits)
	{
		$this->check_all_bits = $check_all_bits;
	}
	
	public function get_check_all_bits()
	{
		return $this->check_all_bits;
	}
	
	public function set_add_category_in_list($add_category_in_list)
	{
		$this->add_category_in_list = $add_category_in_list;
	}
	
	public function add_category_in_list()
	{
		return $this->add_category_in_list;
	}
	
	public function set_enable_recursive_exploration($enable_recursive_exploration)
	{
		$this->enable_recursive_exploration = $enable_recursive_exploration;
	}
	
	public function is_enabled_recursive_exploration()
	{
		return $this->enable_recursive_exploration;
	}
}
?>