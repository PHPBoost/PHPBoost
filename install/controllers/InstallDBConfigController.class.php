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

	public function execute(HTTPRequest $request)
	{
		parent::load_lang($request);
		$this->build_form();
		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$installation_services = new InstallationServices(LangLoader::get_locale());
			try
			{
				$create_tables_if_needed = true;
				$installation_services->create_phpboost_tables('mysql',
				$this->form->get_value('host'), $this->form->get_value('port'),
				$this->form->get_value('schema'), $this->form->get_value('login'),
				$this->form->get_value('password'), $this->form->get_value('tablePrefix'),
				$create_tables_if_needed);
				AppContext::get_response()->redirect(InstallUrlBuilder::website());
			}
			catch (DBConnectionException $ex)
			{
				// TODO forward this to form
				Debug::dump($ex);
				die('DBConnectionException');
			}
		}
		return $this->create_response();
	}

	private function build_form()
	{
		$this->form = new HTMLForm('databaseForm');

		$fieldset_server = new FormFieldsetHTML('serverConfig', $this->lang['dbms.paramters']);
		$this->form->add_fieldset($fieldset_server);

		$host = new FormFieldTextEditor('host', $this->lang['dbms.host'], 'localhost',
		array('description' => $this->lang['dbms.host.explanation'], 'required' => true));
		$fieldset_server->add_field($host);
		$host = new FormFieldTextEditor('port', $this->lang['dbms.port'], '3306',
		array('description' => $this->lang['dbms.port.explanation'], 'required' => true));
		$host->add_constraint(new FormFieldConstraintIntegerRange(1, 65536));
		$fieldset_server->add_field($host);
		$login = new FormFieldTextEditor('login', $this->lang['dbms.login'], 'root',
		array('description' => $this->lang['dbms.login.explanation'], 'required' => true));
		$fieldset_server->add_field($login);
		$password = new FormFieldTextEditor('password', $this->lang['dbms.password'], '',
		array('description' => $this->lang['dbms.password.explanation']));
		$fieldset_server->add_field($password);

		$fieldset_schema = new FormFieldsetHTML('schemaConfig', $this->lang['schema.properties']);
		$this->form->add_fieldset($fieldset_schema);

		$schema = new FormFieldTextEditor('schema', $this->lang['schema'], '',
		array('description' => $this->lang['schema.explanation'], 'required' => true));
		$fieldset_schema->add_field($schema);
		$table_prefix = new FormFieldTextEditor('tablePrefix', $this->lang['schema.tablePrefix'], 'phpboost_',
		array('description' => $this->lang['schema.tablePrefix.explanation']));
		$fieldset_schema->add_field($table_prefix);

		$this->submit_button = new FormButtonSubmitImg('templates/images/right.png', $this->lang['step.next'], 'submit');
		$this->form->add_button($this->submit_button);
	}

	/**
	 * @return InstallDisplayResponse
	 */
	private function create_response()
	{
		$this->view = new FileTemplate('install/database.tpl');
		$this->view->put('DATABASE_FORM', $this->form->display());
		$step_title = $this->lang['step.dbConfig.title'];
		$response = new InstallDisplayResponse(3, $step_title, $this->view);
		return $response;
	}
}
?>