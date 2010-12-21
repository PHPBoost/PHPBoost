<?php
/*##################################################
 *                         InstallDBConfigController.class.php
 *                            -------------------
 *   begin                : October 03 2010
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

class InstallDBConfigController extends InstallController
{
	/**
	 * @var Template
	 */
	private $view;

	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var HTMLForm
	 */
	private $submit_button;
	/**
	 * @var FormFieldsetHTML
	 */
	private $overwrite_fieldset;
	/**
	 * @var FormFieldCheckbox
	 */
	private $overwrite_field;
	private $error = null;

	public function execute(HTTPRequest $request)
	{
		parent::load_lang($request);
		$this->build_form();
		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$host = $this->form->get_value('host');
			$port = $this->form->get_value('port');
			$login = $this->form->get_value('login');
			$password = $this->form->get_value('password');
			$schema = $this->form->get_value('schema');
			$tables_prefix = $this->form->get_value('tablesPrefix');
			$this->handle_form($host, $port, $login, $password, $schema, $tables_prefix);
		}
		return $this->create_response();
	}

	private function build_form()
	{
		$this->form = new HTMLForm('databaseForm');

		$fieldset_server = new FormFieldsetHTML('serverConfig', $this->lang['dbms.paramters']);
		$this->form->add_fieldset($fieldset_server);

		$host = new FormFieldTextEditor('host', $this->lang['dbms.host'], 'localhost',
		array('description' => $this->lang['dbms.host.explanation'], 'required' => $this->lang['db.required.host']));
		$fieldset_server->add_field($host);
		$port = new FormFieldTextEditor('port', $this->lang['dbms.port'], '3306',
		array('description' => $this->lang['dbms.port.explanation'], 'required' => $this->lang['db.required.port']));
		$port->add_constraint(new FormFieldConstraintIntegerRange(1, 65536));
		$fieldset_server->add_field($port);
		$login = new FormFieldTextEditor('login', $this->lang['dbms.login'], 'root',
		array('description' => $this->lang['dbms.login.explanation'], 'required' => $this->lang['db.required.login']));
		$fieldset_server->add_field($login);
		$password = new FormFieldPasswordEditor('password', $this->lang['dbms.password'], '',
		array('description' => $this->lang['dbms.password.explanation']));
		$fieldset_server->add_field($password);

		$fieldset_schema = new FormFieldsetHTML('schemaConfig', $this->lang['schema.properties']);
		$this->form->add_fieldset($fieldset_schema);

		$schema = new FormFieldTextEditor('schema', $this->lang['schema'], '',
		array('description' => $this->lang['schema.explanation'], 'required' => $this->lang['db.required.schema']));
		$schema->add_event('change', '$FFS(\'overwriteFieldset\').disable()');
		$fieldset_schema->add_field($schema);
		$tables_prefix = new FormFieldTextEditor('tablesPrefix', $this->lang['schema.tablePrefix'], 'phpboost_',
		array('description' => $this->lang['schema.tablePrefix.explanation']));
		$fieldset_schema->add_field($tables_prefix);

		$this->overwrite_fieldset = new FormFieldsetHTML('overwriteFieldset', $this->lang['phpboost.alreadyInstalled']);
		$this->form->add_fieldset($this->overwrite_fieldset);

		$overwrite_message = new FormFieldHTML('', $this->lang['phpboost.alreadyInstalled.explanation']);
		$this->overwrite_fieldset->add_field($overwrite_message);
		$this->overwrite_field = new FormFieldCheckbox('overwrite', $this->lang['phpboost.alreadyInstalled.overwrite'], false,
			array('required' => $this->lang['phpboost.alreadyInstalled.overwrite.confirm']));
		$this->overwrite_fieldset->add_field($this->overwrite_field);
		$this->overwrite_fieldset->disable();

		$action_fieldset = new FormFieldsetButtons('actions');
		$back = new FormButtonLink($this->lang['step.previous'], InstallUrlBuilder::server_configuration(), 'templates/images/left.png');
		$action_fieldset->add_button($back);
		$check_request = new AjaxRequest(InstallUrlBuilder::check_database(), 'function(response){
		alert(response.responseJSON.message);
		if (response.responseJSON.alreadyInstalled) {
			$FFS(\'overwriteFieldset\').enable();
		} else {
			$FFS(\'overwriteFieldset\').disable();
		}}');
		$check = new FormButtonAjax($this->lang['db.config.check'], $check_request, 'templates/images/refresh.png',
		array($host, $port, $login, $password, $schema, $tables_prefix), '$HF(\'databaseForm\').validate()');
		$action_fieldset->add_button($check);
		$this->submit_button = new FormButtonSubmitImg($this->lang['step.next'], 'templates/images/right.png', 'database');
		$action_fieldset->add_button($this->submit_button);
		$this->form->add_fieldset($action_fieldset);
	}

	private function handle_form($host, $port, $login, $password, $schema, $tables_prefix)
	{
		$service = new InstallationServices();
		$status = $service->check_db_connection($host, $port, $login, $password, $schema, $tables_prefix);
		switch ($status)
		{
			case InstallationServices::CONNECTION_SUCCESSFUL:
				$this->create_tables($service, $host, $port, $login, $password, $schema, $tables_prefix);
				break;
			case InstallationServices::CONNECTION_ERROR:
				$this->error = $this->lang['db.connection.error'];
				break;
			case InstallationServices::UNABLE_TO_CREATE_DATABASE:
				$this->error = $this->lang['db.creation.error'];
				break;
			case InstallationServices::UNKNOWN_ERROR:
			default:
				$this->error = $this->lang['db.unknown.error'];
				break;
		}
	}

	private function create_tables(InstallationServices $service, $host, $port, $login, $password, $schema, $tables_prefix)
	{
		if (!$service->is_already_installed() || (!$this->overwrite_field->is_disabled() && $this->overwrite_field->is_checked()))
		{
			PersistenceContext::close_db_connection();
			$service->create_phpboost_tables(DBFactory::MYSQL, $host, $port, $schema, $login, $password, $tables_prefix);
			AppContext::get_response()->redirect(InstallUrlBuilder::website());
		}
		else
		{
			$this->overwrite_fieldset->enable();
			$this->error = $this->lang['phpboost.alreadyInstalled.explanation'];
		}
	}

	/**
	 * @return InstallDisplayResponse
	 */
	private function create_response()
	{
		$this->view = new FileTemplate('install/database.tpl');
		$this->view->put('DATABASE_FORM', $this->form->display());
		if (!empty($this->error))
		{
			$this->view->put('ERROR', $this->error);
		}
		$step_title = $this->lang['step.dbConfig.title'];
		$response = new InstallDisplayResponse(3, $step_title, $this->view);
		return $response;
	}
}
?>