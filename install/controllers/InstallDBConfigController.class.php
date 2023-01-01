<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 02 22
 * @since       PHPBoost 3.0 - 2010 10 04
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

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
	 * @var FormButtonSubmit
	 */
	private $check_button;
	/**
	 * @var FormButtonSubmit
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
	private $success = null;
	private $error = null;

	public function execute(HTTPRequestCustom $request)
	{
		parent::load_lang($request);
		$this->build_form();
		if (($this->submit_button->has_been_submited() || $this->check_button->has_been_submited()) && $this->form->validate())
		{
			$host = $this->form->get_value('host');
			$port = $this->form->get_value('port');
			$login = $this->form->get_value('login');
			$password = TextHelper::html_entity_decode($this->form->get_value('password'));
			$schema = $this->form->get_value('schema');
			$tables_prefix = $this->form->get_value('tablesPrefix');

			if ($this->submit_button->has_been_submited())
				$this->handle_form($host, $port, $login, $password, $schema, $tables_prefix);
			else
				$this->handle_test($host, $port, $login, $password, $schema, $tables_prefix);
		}
		return $this->create_response();
	}

	private function build_form()
	{
		$this->form = new HTMLForm('databaseForm', '', false);

		$fieldset_server = new FormFieldsetHTML('serverConfig', $this->lang['install.dbms.parameters']);
		$this->form->add_fieldset($fieldset_server);

		$fieldset_server->add_field(new FormFieldTextEditor('host', $this->lang['install.dbms.host'], 'localhost',
			array('description' => $this->lang['install.dbms.host.clue'], 'required' => $this->lang['install.db.required.host'])
		));

		$fieldset_server->add_field(new FormFieldTextEditor('port', $this->lang['install.dbms.port'], '3306',
			array('description' => $this->lang['install.dbms.port.clue'], 'required' => $this->lang['install.db.required.port']),
			array(new FormFieldConstraintIntegerRange(1, 65536))
		));

		$fieldset_server->add_field(new FormFieldTextEditor('login', $this->lang['install.dbms.login'], 'root',
			array('description' => $this->lang['install.dbms.login.clue'], 'required' => $this->lang['install.db.required.login'])
		));

		$fieldset_server->add_field(new FormFieldPasswordEditor('password', $this->lang['install.dbms.password'], '',
			array('description' => $this->lang['install.dbms.password.clue'])
		));

		$fieldset_schema = new FormFieldsetHTML('schemaConfig', $this->lang['install.schema.properties']);
		$this->form->add_fieldset($fieldset_schema);

		$schema = new FormFieldTextEditor('schema', $this->lang['install.schema'], '',
		array('description' => $this->lang['install.schema.clue'], 'required' => $this->lang['install.db.required.schema']),
		array(new FormFieldConstraintRegex('`^[a-z0-9_-]+$`iu')));
		$schema->add_event('change', '$FFS(\'overwriteFieldset\').disable()');
		$fieldset_schema->add_field($schema);

		$fieldset_schema->add_field(new FormFieldTextEditor('tablesPrefix', $this->lang['install.schema.table.prefix'], 'phpboost_',
			array('description' => $this->lang['install.schema.table.prefix.clue'], 'required' => true),
			array(new FormFieldConstraintRegex('`^[a-z0-9_]+$`iu'))
		));

		$this->overwrite_fieldset = new FormFieldsetHTML('overwriteFieldset', $this->lang['install.phpboost.already.installed']);
		$this->form->add_fieldset($this->overwrite_fieldset);

		$overwrite_message = new FormFieldHTML('', $this->lang['install.phpboost.already.installed.description'], array('class' => 'half-field'));
		$this->overwrite_fieldset->add_field($overwrite_message);
		$this->overwrite_field = new FormFieldCheckbox('overwrite', $this->lang['install.phpboost.already.installed.overwrite'], false,
			array('class' => 'half-field top-field custom-checkbox', 'required' => $this->lang['install.phpboost.already.installed.overwrite.confirm']));
		$this->overwrite_fieldset->add_field($this->overwrite_field);
		$this->overwrite_fieldset->disable();

		$action_fieldset = new FormFieldsetSubmit('actions', array('css_class' => 'fieldset-submit next-step'));
		$action_fieldset->add_element(new FormButtonLinkCssImg($this->lang['common.previous'], InstallUrlBuilder::server_configuration(), 'fa fa-arrow-left'));
		$this->check_button = new FormButtonSubmitCssImg($this->lang['install.db.config.check'], 'fa fa-sync', 'check_database');
		$action_fieldset->add_element($this->check_button);
		$this->submit_button = new FormButtonSubmitCssImg($this->lang['common.next'], 'fa fa-arrow-right', 'database');
		$action_fieldset->add_element($this->submit_button);
		$this->form->add_fieldset($action_fieldset);
	}

	private function handle_test($host, $port, $login, $password, $schema, $tables_prefix)
	{
		$service = new InstallationServices();
		$status = $service->check_db_connection($host, $port, $login, $password, $schema, $tables_prefix);
		switch ($status)
		{
			case InstallationServices::CONNECTION_SUCCESSFUL:
				$this->success = $this->lang['install.db.connection.success'];
				break;
			case InstallationServices::CONNECTION_ERROR:
				$this->error = $this->lang['install.db.connection.error'];
				break;
			case InstallationServices::UNABLE_TO_CREATE_DATABASE:
				$this->error = $this->lang['install.db.creation.error'];
				break;
			case InstallationServices::UNKNOWN_ERROR:
			default:
				$this->error = $this->lang['install.db.unknown.error.detail'];
				break;
		}
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
				$this->error = $this->lang['install.db.connection.error'];
				break;
			case InstallationServices::UNABLE_TO_CREATE_DATABASE:
				$this->error = $this->lang['install.db.creation.error'];
				break;
			case InstallationServices::UNKNOWN_ERROR:
			default:
				$this->error = $this->lang['install.db.unknown.error'];
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
			$this->error = $this->lang['install.phpboost.already.installed.description'];
		}
	}

	/**
	 * @return InstallDisplayResponse
	 */
	private function create_response()
	{
		$this->view = new FileTemplate('install/database.tpl');
		$this->view->put('DATABASE_FORM', $this->form->display());
		if (!empty($this->success))
		{
			$this->view->put('SUCCESS', $this->success);
		}
		if (!empty($this->error))
		{
			$this->view->put('ERROR', $this->error);
		}
		$step_title = $this->lang['install.db.config.title'];
		$response = new InstallDisplayResponse(3, $step_title, $this->lang, $this->view);
		return $response;
	}
}
?>
