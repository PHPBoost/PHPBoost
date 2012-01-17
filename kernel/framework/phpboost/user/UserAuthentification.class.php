<?php
/**
 * @author Kévin MASSY <soldier.weasel@gmail.com>
 * @package {@package}
 */
class UserAuthentification
{
	protected $login;
	protected $password;
	
	public function __construct($login, $password)
	{
		$this->login = $login;
		$this->password = $password;
	}
	
	public function get_login()
	{
		return $this->login;
	}

	public function get_password_hashed()
	{
		return KeyGenerator::string_hash($this->password);
	}
}
?>