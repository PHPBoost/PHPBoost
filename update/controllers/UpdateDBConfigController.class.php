<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 04 07
 * @since       PHPBoost 3.0 - 2012 03 12
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

class UpdateDBConfigController extends UpdateController
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

	public function execute(HTTPRequestCustom $request)
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
		$this->form = new HTMLForm('databaseForm', '', false);

		$fieldset_server = new FormFieldsetHTML('serverConfig', $this->lang['dbms.parameters']);
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
		array('required' => $this->lang['db.required.schema']),
		array(new FormFieldConstraintRegex('`^[a-z0-9_-]+$`iu')));
		$schema->add_event('change', '$FFS(\'overwriteFieldset\').disable()');
		$fieldset_schema->add_field($schema);
		$tables_prefix = new FormFieldTextEditor('tablesPrefix', $this->lang['schema.tablePrefix'], 'phpboost_',
		array('description' => $this->lang['schema.tablePrefix.explanation']),
		array(new FormFieldConstraintRegex('`^[a-z0-9_]+$`iu')));
		$fieldset_schema->add_field($tables_prefix);

		$action_fieldset = new FormFieldsetSubmit('actions');
		$back = new FormButtonLinkCssImg($this->lang['step.previous'], UpdateUrlBuilder::server_configuration(), 'fa fa-arrow-left');
		$action_fieldset->add_element($back);
		$check = new FormButtonSubmitCssImg($this->lang['db.config.check'], 'fa fa-sync', 'database');
		$action_fieldset->add_element($check);
		$this->submit_button = new FormButtonSubmitCssImg($this->lang['step.next'], 'fa fa-arrow-right', 'database');
		$action_fieldset->add_element($this->submit_button);
		$this->form->add_fieldset($action_fieldset);
	}

	private function handle_form($host, $port, $login, $password, $schema, $tables_prefix)
	{
		$service = new UpdateServices();
		$status = $service->check_db_connection($host, $port, $login, $password, $schema, $tables_prefix);
		switch ($status)
		{
			case UpdateServices::CONNECTION_SUCCESSFUL:
				$this->create_connection($service, $host, $port, $login, $password, $schema, $tables_prefix);
				break;
			case UpdateServices::CONNECTION_ERROR:
				$this->error = $this->lang['db.connection.error'];
				break;
			case UpdateServices::UNEXISTING_DATABASE:
				$this->error = $this->lang['db.unexisting_database'];
				break;
			case UpdateServices::UNKNOWN_ERROR:
			default:
				$this->error = $this->lang['db.unknown.error'];
				break;
		}
	}

	private function create_connection(UpdateServices $service, $host, $port, $login, $password, $schema, $tables_prefix)
	{
		if ($service->is_already_installed($tables_prefix))
		{
			PersistenceContext::close_db_connection();
			$service->create_connection(DBFactory::MYSQL, $host, $port, $schema, $login, $password, $tables_prefix);
			AppContext::get_response()->redirect(UpdateUrlBuilder::update());
		}
		else
		{
			$this->error = $this->lang['phpboost.notInstalled.explanation'];
		}
	}

	/**
	 * @return UpdateDisplayResponse
	 */
	private function create_response()
	{
		$this->view = new FileTemplate('update/database.tpl');
		$this->view->put('DATABASE_FORM', $this->form->display());
		if (!empty($this->error))
		{
			$this->view->put('ERROR', $this->error);
		}
		$step_title = $this->lang['step.dbConfig.title'];
		$response = new UpdateDisplayResponse(3, $step_title, $this->view);
		return $response;
	}
}
?>
