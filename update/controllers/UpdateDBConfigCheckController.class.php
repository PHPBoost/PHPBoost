<?php
/*##################################################
 *                         UpdateDBConfigCheckController.class.php
 *                            -------------------
 *   begin                : March 12, 2012
 *   copyright            : (C) 2012 Kevin MASSY
 *   email                : soldier.weasel@gmail.com
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

class UpdateDBConfigCheckController extends UpdateController
{
	private $status;
	private $already_installed = false;

	public function execute(HTTPRequest $request)
	{
		parent::load_lang($request);
		$host = $request->get_value('host');
		$port = $request->get_value('port');
		$login = $request->get_value('login');
		$password = $request->get_value('password');
		$schema = $request->get_value('schema');
		$tables_prefix = $request->get_value('tablesPrefix');
		$this->check_configuration($host, $port, $login, $password, $schema, $tables_prefix);
		return $this->build_response($host, $port, $login, $password, $schema, $tables_prefix);
	}

	private function check_configuration($host, $port, $login, $password, $schema, $tables_prefix)
	{
		$service = new UpdateServices();
		$this->status = $service->check_db_connection($host, $port, $login, $password, $schema, $tables_prefix);
		if ($this->status == UpdateServices::CONNECTION_SUCCESSFUL)
		{
			$this->already_installed = $service->is_already_installed($tables_prefix);
		}
	}

	private function build_response($host, $port, $login, $password, $schema, $tables_prefix)
	{
		$object = array(
			'message' => $this->get_message(),
			'connectionStatus' => $this->status,
			'alreadyInstalled' => $this->already_installed,
			'connectionData' => array(
				'host' => $host,
				'port' => $port,
				'login' => $login,
				'schema' => $schema,
				'tablesPrefix' => $tables_prefix
		)
		);
		return new JSONResponse($object);
	}

	private function get_message()
	{
		switch ($this->status)
		{
			case UpdateServices::CONNECTION_SUCCESSFUL:
				if (!$this->already_installed)
				{
					return $this->lang['phpboost.notInstalled.alert'];
				}
				return $this->lang['db.connection.success'];
			case UpdateServices::CONNECTION_ERROR:
				return $this->lang['db.connection.error'];
			case UpdateServices::UNEXISTING_DATABASE:
				return $this->lang['db.unexisting_database'];
			case UpdateServices::UNKNOWN_ERROR:
			default:
				return $this->lang['db.unknown.error'];

		}
	}
}
?>