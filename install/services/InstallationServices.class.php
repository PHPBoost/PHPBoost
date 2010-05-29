<?php
/*##################################################
 *                          InstallationServices.class.php
 *                            -------------------
 *   begin                : February 3, 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

class InstallationServices
{
	private static $token_file_content = '1';

	/**
	 * @var File
	 */
	private $token;

	public function __construct()
	{
		$this->token = new File(PATH_TO_ROOT . '/.install_token');
	}

	public function validate_server_configuration()
	{
		return PHPBoostFoldersPermissions::validate_server_configuration();
	}

	public function validate_database_connection()
	{

		$this->generate_installation_token();
	}

	public function configure_website()
	{
		$this->get_installation_token();
	}

	public function create_admin_account()
	{
		$this->get_installation_token();

		$this->delete_installation_token();
	}

	private function get_installation_token()
	{
		$is_token_valid = false;
		try
		{
			$is_token_valid = $this->token->exists() && $this->token->read() == self::$token_file_content;
		}
		catch (IOException $ioe)
		{
			$is_token_valid = false;
		}

		if (!$is_token_valid)
		{
			throw new TokenNotFoundException($this->token->get_path_from_root());
		}
	}

	private function generate_installation_token()
	{
		$this->token->write(self::$token_file_content);
	}

	private function delete_installation_token()
	{
		$this->token->delete();
	}
}

?>
