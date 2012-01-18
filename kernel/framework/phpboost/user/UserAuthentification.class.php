<?php
/**
 * @author K�vin MASSY <soldier.weasel@gmail.com>
 * @package {@package}
 */
class UserAuthentification
{
	protected $login;
	protected $password_hashed;
	
	public function __construct($login, $password = '', $password_hashed = false)
	{
		$this->login = $login;
		
		if (!empty($password))
		{
			$this->password_hashed = $password_hashed ? $password : KeyGenerator::string_hash($password);
		}
	}
	
	public function get_login()
	{
		return $this->login;
	}

	public function get_password_hashed()
	{
		return $this->password_hashed;
	}
}
?>