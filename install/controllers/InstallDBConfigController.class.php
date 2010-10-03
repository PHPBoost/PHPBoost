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

	public function execute(HTTPRequest $request)
	{
        parent::load_lang($request);
		$view = new FileTemplate('install/database.tpl');
		$this->build_form($view);
		$this->add_navigation($view);
		return $this->create_response($view);
	}

	/**
	 * @param Template $view
	 * @return InstallDisplayResponse
	 */
	private function create_response(Template $view)
	{
        $step_title = $this->lang['step.dbConfig.title'];
		$response = new InstallDisplayResponse(4, $step_title, $view);
		return $response;
	}

	private function build_form(Template $view)
    {
    	$form = new HTMLForm('databaseForm', InstallUrlBuilder::server_configuration()->absolute());
    	
    	$fieldset_server = new FormFieldsetHTML('serverConfig', $this->lang['dbms.paramters']);
    	$form->add_fieldset($fieldset_server);
    	
        $host = new FormFieldTextEditor('host', $this->lang['dbms.host'], 'localhost', array('description' => $this->lang['dbms.host.explanation']));
        $fieldset_server->add_field($host);
        $login = new FormFieldTextEditor('login', $this->lang['dbms.login'], 'root', array('description' => $this->lang['dbms.login.explanation']));
        $fieldset_server->add_field($login);
        $password = new FormFieldTextEditor('password', $this->lang['dbms.password'], '', array('description' => $this->lang['dbms.password.explanation']));
        $fieldset_server->add_field($password);
        
        $fieldset_schema = new FormFieldsetHTML('schemaConfig', $this->lang['schema.properties']);
        $form->add_fieldset($fieldset_schema);
        
        $schema = new FormFieldTextEditor('schema', $this->lang['schema'], '', array('description' => $this->lang['schema.explanation']));
        $fieldset_schema->add_field($schema);
        $table_prefix = new FormFieldTextEditor('tablePrefix', $this->lang['schema.tablePrefix'], 'phpboost_', array('description' => $this->lang['schema.tablePrefix.explanation']));
        $fieldset_schema->add_field($table_prefix);
    	
    	$navigation_bar = $this->add_navigation();
    	$form->add_button($navigation_bar);
		$view->add_subtemplate('DATABASE_FORM', $form->display());
    }

	private function add_navigation()
    {
    	$nav = new InstallNavigationBar();
    	$nav->set_previous_step_url(InstallUrlBuilder::website()->absolute());
        return $nav;
    }
}
?>