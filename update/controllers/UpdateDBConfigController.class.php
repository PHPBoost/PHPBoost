<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 3.0 - 2012 03 12
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      mipel <mipel@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 */

class UpdateDBConfigController extends UpdateController
{
    private Template $view;
    private HTMLForm $form;
    /** @var FormButtonSubmit*/
    private $submit_button;
    private FormFieldsetHTML $overwrite_fieldset;
    private FormFieldCheckbox $overwrite_field;
    private $error = null;

    public function execute(HTTPRequestCustom $request)
    {
        parent::load_lang($request);
        $this->build_form();
        if ($this->submit_button->has_been_submited() && $this->form->validate()) {
            $host          = $this->form->get_value('host');
            $port          = $this->form->get_value('port');
            $login         = $this->form->get_value('login');
            $password      = $this->form->get_value('password');
            $schema        = $this->form->get_value('schema');
            $tables_prefix = $this->form->get_value('tablesPrefix');
            $this->handle_form($host, $port, $login, $password, $schema, $tables_prefix);
        }
        return $this->create_response();
    }

    private function build_form()
    {
        $this->form = new HTMLForm('databaseForm', '', false);

        $fieldset = new FormFieldsetHTML('serverConfig', $this->lang['update.dbms.parameters']);
        $this->form->add_fieldset($fieldset);

        $fieldset->add_field(new FormFieldTextEditor('host', $this->lang['update.dbms.host'], 'localhost',
            [
                'class' => 'half-field',
                'description' => $this->lang['update.dbms.host.clue'], 'required' => $this->lang['update.db.required.host']
            ]
        ));

        $fieldset->add_field($port = new FormFieldTextEditor('port', $this->lang['update.dbms.port'], '3306',
            [
                'class' => 'half-field',
                'description' => $this->lang['update.dbms.port.clue'], 'required' => $this->lang['update.db.required.port']
            ]
        ));
        $port->add_constraint(new FormFieldConstraintIntegerRange(1, 65536));

        $fieldset->add_field(new FormFieldTextEditor('login', $this->lang['update.dbms.login'], 'root',
            [
                'class' => 'half-field',
                'description' => $this->lang['update.dbms.login.clue'], 'required' => $this->lang['update.db.required.login']
            ]
        ));

        $fieldset->add_field(new FormFieldPasswordEditor('password', $this->lang['update.dbms.password'], '',
            [
                'class' => 'half-field',
                'description' => $this->lang['update.dbms.password.clue']
            ]
        ));

        $fieldset->add_field($schema = new FormFieldTextEditor('schema', $this->lang['update.schema'], '',
            [
                'class' => 'half-field',
                'required' => $this->lang['update.db.required.schema']
            ],
            [new FormFieldConstraintRegex('`^[a-z0-9_-]+$`iu')]
        ));
        $schema->add_event('change', '$FFS(\'overwriteFieldset\').disable()');

        $fieldset->add_field(new FormFieldTextEditor('tablesPrefix', $this->lang['update.schema.table.prefix'], 'phpboost_',
            [
                'class' => 'half-field',
                'description' => $this->lang['update.schema.table.prefix.clue']
            ],
            [new FormFieldConstraintRegex('`^[a-z0-9_]+$`iu')]
        ));

        $action_fieldset = new FormFieldsetSubmit('actions');

        $action_fieldset->add_element(new FormButtonLinkCssImg($this->lang['update.step.previous'], UpdateUrlBuilder::server_configuration(), 'fa fa-arrow-left'));
        $action_fieldset->add_element(new FormButtonSubmitCssImg($this->lang['update.db.config.check'], 'fa fa-sync', 'database'));
        $action_fieldset->add_element($this->submit_button = new FormButtonSubmitCssImg($this->lang['update.step.next'], 'fa fa-arrow-right', 'database'));

        $this->form->add_fieldset($action_fieldset);
    }

    private function handle_form(string $host, string $port, string $login, string $password, string $schema, string $tables_prefix)
    {
        $service = new UpdateServices();
        $status  = $service->check_db_connection($host, $port, $login, $password, $schema, $tables_prefix);
        switch ($status) {
            case UpdateServices::CONNECTION_SUCCESSFUL:
                $this->create_connection($service, $host, $port, $login, $password, $schema, $tables_prefix);
                break;
            case UpdateServices::CONNECTION_ERROR:
                $this->error = $this->lang['update.db.connection.error'];
                break;
            case UpdateServices::UNEXISTING_DATABASE:
                $this->error = $this->lang['update.db.unexisting.database'];
                break;
            case UpdateServices::UNKNOWN_ERROR:
            default:
                $this->error = $this->lang['update.db.unknown.error'];
                break;
        }
    }

    private function create_connection(UpdateServices $service, string $host, string $port, string $login, string $password, string $schema, string $tables_prefix)
    {
        if ($service->is_already_installed($tables_prefix)) {
            PersistenceContext::close_db_connection();
            $service->create_connection(DBFactory::MYSQL, $host, $port, $schema, $login, $password, $tables_prefix);
            AppContext::get_response()->redirect(UpdateUrlBuilder::update());
        } else {
            $this->error = $this->lang['phpboost.not.installed.clue'];
        }
    }

    private function create_response(): UpdateDisplayResponse
    {
        $this->view = new FileTemplate('update/database.tpl');
        $this->view->put('DATABASE_FORM', $this->form->display());
        if (!empty($this->error)) {
            $this->view->put('ERROR', $this->error);
        }
        $step_title = $this->lang['update.step.database.config'];
        $response   = new UpdateDisplayResponse(3, $step_title, $this->view);
        return $response;
    }
}
